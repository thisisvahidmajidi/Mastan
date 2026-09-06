<?php
/**
 * AJAX handlers.
 *
 * @package CoachRoom_OD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Coachroom_OD_Ajax
 */
class Coachroom_OD_Ajax {

	/**
	 * Instance.
	 *
	 * @var Coachroom_OD_Ajax|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return Coachroom_OD_Ajax
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'wp_ajax_cr_od_save_response', array( $this, 'save_response' ) );
		add_action( 'wp_ajax_nopriv_cr_od_save_response', array( $this, 'save_response' ) );
		add_action( 'wp_ajax_cr_od_register_participant', array( $this, 'register_participant' ) );
		add_action( 'wp_ajax_nopriv_cr_od_register_participant', array( $this, 'register_participant' ) );
		add_action( 'wp_ajax_cr_od_save_performance', array( $this, 'save_performance' ) );
		add_action( 'wp_ajax_nopriv_cr_od_save_performance', array( $this, 'save_performance' ) );
	}

	/**
	 * Register the minimal participant profile on the landing page.
	 *
	 * A WordPress subscriber is created (or reused) from an e-mail and a
	 * username, then the participant is automatically signed in so the next
	 * page load reveals the full platform.
	 */
	public function register_participant() {
		check_ajax_referer( 'cr_od_nonce', 'nonce' );

		$username = isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '';
		$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		if ( ! $username || strlen( $username ) < 3 || ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => 'لطفاً نام کاربری (حداقل ۳ حرف) و ایمیل معتبر وارد کنید.' ) );
		}

		$exists_by_login = get_user_by( 'login', $username );
		$exists_by_email = get_user_by( 'email', $email );
		if ( $exists_by_login && $exists_by_email && $exists_by_login->ID === $exists_by_email->ID ) {
			$user_id = (int) $exists_by_login->ID;
		} elseif ( $exists_by_email || $exists_by_login ) {
			wp_send_json_error( array( 'message' => 'این نام کاربری یا ایمیل قبلاً ثبت شده است. از همان اطلاعات استفاده کنید یا وارد coachroom.ir شوید.' ) );
		} else {
			$password = wp_generate_password( 16, true );
			$user_id  = wp_insert_user(
				array(
					'user_login' => $username,
					'user_email' => $email,
					'user_pass'  => $password,
					'role'       => 'subscriber',
				)
			);
			if ( is_wp_error( $user_id ) ) {
				wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
			}
			update_user_meta( $user_id, 'cr_od_participant', 1 );
			update_user_meta( $user_id, 'cr_od_participant_since', current_time( 'mysql' ) );
		}

		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id, true );
		$token = md5( $user_id . '|' . wp_generate_password( 24, false ) . '|' . get_option( 'cr_od_org_name', 'coachroom' ) );
		update_user_meta( $user_id, 'cr_od_access_token', $token );

		if ( ! headers_sent() ) {
			$cookie_domain = defined( 'COOKIE_DOMAIN' ) && COOKIE_DOMAIN ? COOKIE_DOMAIN : '';
			setcookie( 'cr_od_participant', $token, time() + 30 * DAY_IN_SECONDS, '/', $cookie_domain );
		}

		wp_send_json_success(
			array(
				'message' => 'ثبت‌نام انجام شد و به پلتفرم دسترسی دارید.',
				'user'    => array(
					'id'       => $user_id,
					'username' => $username,
					'email'    => $email,
				),
			)
		);
	}

	/**
	 * Save all submitted dimensions from the front-end assessment form in a single request.
	 *
	 * The front-end sends a JSON string in the `dimensions` field, but for compatibility
	 * we also accept the older single `dimension`/`score` format.
	 */
	public function save_response() {
		check_ajax_referer( 'cr_od_nonce', 'nonce' );

		$allowed = array_merge(
			array_keys( Coachroom_OD_Helpers::dimensions() ),
			array_keys( Coachroom_OD_Helpers::weisbord_boxes() ),
			array_keys( Coachroom_OD_Helpers::attitude_groups() )
		);
		$dept    = isset( $_POST['department'] ) ? sanitize_text_field( wp_unslash( $_POST['department'] ) ) : 'نامشخص';
		$role    = isset( $_POST['assessor_role'] ) ? sanitize_text_field( wp_unslash( $_POST['assessor_role'] ) ) : 'کارمند';
		$notes   = isset( $_POST['notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['notes'] ) ) : '';
		$organization = isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : get_option( 'cr_od_org_name', 'شرکت توسعه انرژی و نفت' );

		if ( empty( $dept ) ) {
			$dept = 'نامشخص';
		}

		$questions  = array();
		$dimensions = array();
		$dim_sums   = array();
		$dim_counts = array();

		// New batch format: questions = [{ "dimension":"...", "question_key":"...",
		// "question_label":"...", "score":3 }, ...].
		if ( isset( $_POST['questions'] ) ) {
			$raw          = wp_unslash( $_POST['questions'] );
			$decoded      = json_decode( $raw, true );
			if ( is_array( $decoded ) ) {
				foreach ( $decoded as $item ) {
					if ( ! is_array( $item ) ) {
						continue;
					}
					$slug = isset( $item['slug'] ) && '' !== trim( (string) $item['slug'] )
						? Coachroom_OD_Helpers::sanitize_slug( $item['slug'] )
						: ( isset( $item['dimension'] ) ? Coachroom_OD_Helpers::sanitize_slug( $item['dimension'] ) : '' );
					$qkey = isset( $item['question_key'] ) ? Coachroom_OD_Helpers::sanitize_slug( $item['question_key'] ) : '';
					$qlabel = isset( $item['question_label'] ) ? sanitize_text_field( $item['question_label'] ) : '';
					$score = isset( $item['score'] ) ? max( 1, min( 4, (float) $item['score'] ) ) : 0;
					if ( in_array( $slug, $allowed, true ) && $score >= 1 && ! empty( $qkey ) ) {
						$questions[] = array(
							'dimension'      => $slug,
							'question_key'   => $qkey,
							'question_label' => $qlabel ? $qlabel : $slug,
							'score'          => $score,
						);
						if ( ! isset( $dim_sums[ $slug ] ) ) {
							$dim_sums[ $slug ]   = 0;
							$dim_counts[ $slug ] = 0;
						}
						$dim_sums[ $slug ]   += $score;
						$dim_counts[ $slug ] += 1;
					}
				}
			}
		}

		// Dimension scores are a clean arithmetic mean of their question scores.
		foreach ( $dim_sums as $slug => $sum ) {
			$dimensions[ $slug ] = round( $sum / max( 1, $dim_counts[ $slug ] ), 2 );
		}

		// Backward-compatible dimensions-only format.
		if ( empty( $questions ) && isset( $_POST['dimensions'] ) ) {
			$raw          = wp_unslash( $_POST['dimensions'] );
			$decoded      = json_decode( $raw, true );
			if ( is_array( $decoded ) ) {
				foreach ( $decoded as $item ) {
					if ( ! is_array( $item ) ) {
						continue;
					}
					$slug = isset( $item['slug'] ) ? Coachroom_OD_Helpers::sanitize_slug( $item['slug'] ) : '';
					$score = isset( $item['score'] ) ? max( 1, min( 4, (float) $item['score'] ) ) : 0;
					if ( in_array( $slug, $allowed, true ) && $score >= 1 ) {
						$dimensions[ $slug ] = $score;
					}
				}
			}
		}

		// Legacy single-dimension format.
		if ( empty( $dimensions ) && isset( $_POST['dimension'] ) ) {
			$slug = Coachroom_OD_Helpers::sanitize_slug( wp_unslash( $_POST['dimension'] ) );
			$score = isset( $_POST['score'] ) ? max( 1, min( 4, (float) $_POST['score'] ) ) : 0;
			if ( in_array( $slug, $allowed, true ) && $score >= 1 ) {
				$dimensions[ $slug ] = $score;
			}
		}

		if ( empty( $dimensions ) ) {
			wp_send_json_error(
				array(
					'message' => 'هیچ شاخص معتبری دریافت نشد. لطفاً همه گزینه‌ها را انتخاب و دوباره تلاش کنید.',
				)
			);
		}

		try {
			$cycle_id = $this->ensure_cycle();

			$responses = Coachroom_OD_DB::table( 'responses' );
			$weights   = Coachroom_OD_Helpers::weights();
			$wpdb      = $GLOBALS['wpdb'];

			// Group questions by dimension so we can safely refresh the whole role/unit profile.
			$by_dimension = array();
			foreach ( $questions as $question ) {
				$dim = $question['dimension'];
				if ( ! isset( $by_dimension[ $dim ] ) ) {
					$by_dimension[ $dim ] = array();
				}
				$by_dimension[ $dim ][] = $question;
			}

			if ( $by_dimension ) {
				foreach ( $by_dimension as $dimension => $items ) {
					// Replace only the previous answer of the SAME role + department + dimension.
					// Different roles/units are preserved so every role's result stays in the analysis.
					$wpdb->delete(
						$responses,
						array(
							'cycle_id'      => $cycle_id,
							'dimension'     => $dimension,
							'department'    => $dept,
							'assessor_role' => $role,
						),
						array( '%d', '%s', '%s', '%s' )
					);

					foreach ( $items as $question ) {
						Coachroom_OD_DB::insert_response(
							array(
								'cycle_id'      => $cycle_id,
								'user_id'       => get_current_user_id(),
								'organization'  => $organization,
								'department'    => $dept,
								'assessor_role' => $role,
								'dimension'     => $dimension,
								'question_key'  => $question['question_key'],
								'question_label'=> $question['question_label'],
								'score'         => $question['score'],
								'weight'        => isset( $weights[ $dimension ] ) ? $weights[ $dimension ] : 1,
								'notes'         => $notes,
							)
						);
					}
				}
			} else {
				// Backward-compatible path when only dimension scores are submitted.
				foreach ( $dimensions as $dimension => $score ) {
					$wpdb->delete(
						$responses,
						array(
							'cycle_id'      => $cycle_id,
							'dimension'     => $dimension,
							'department'    => $dept,
							'assessor_role' => $role,
						),
						array( '%d', '%s', '%s', '%s' )
					);

					Coachroom_OD_DB::insert_response(
						array(
							'cycle_id'      => $cycle_id,
							'user_id'       => get_current_user_id(),
							'organization'  => $organization,
							'department'    => $dept,
							'assessor_role' => $role,
							'dimension'     => $dimension,
							'question_key'  => 'dimension_' . $dimension,
							'question_label'=> 'ارزیابی کلی ' . $dimension,
							'score'         => $score,
							'weight'        => isset( $weights[ $dimension ] ) ? $weights[ $dimension ] : 1,
							'notes'         => $notes,
						)
					);
				}
			}

			wp_send_json_success(
				array(
					'message' => 'ارزیابی با موفقیت ثبت شد و داشبورد به‌روزرسانی شد.',
					'data'    => Coachroom_OD_Helpers::dashboard_data( $cycle_id ),
				)
			);
		} catch ( \Throwable $e ) {
			wp_send_json_error(
				array(
					'message' => 'خطا در پردازش ارزیابی: ' . $e->getMessage(),
				)
			);
		}
	}

	/**
	 * Save an individual performance evaluation and return the refreshed dashboard.
	 *
	 * The submitted six indicators are averaged with the dimension weights so the
	 * manager immediately sees a transparent overall score. That score is then
	 * presented together with the recorded SBI feedback and OSKAR coaching quality
	 * so the supervision/coaching cycle becomes evidence-based.
	 */
	public function save_performance() {
		check_ajax_referer( 'cr_od_nonce', 'nonce' );

		$payload = array();
		if ( isset( $_POST['payload'] ) ) {
			$decoded = json_decode( wp_unslash( $_POST['payload'] ), true );
			if ( is_array( $decoded ) ) {
				$payload = $decoded;
			}
		}

		$scores = isset( $payload['scores'] ) && is_array( $payload['scores'] ) ? $payload['scores'] : array();
		$dims   = Coachroom_OD_Helpers::performance_dimensions();

		$clean_scores = array();
		$sum_weight   = 0.0;
		$sum_score    = 0.0;
		foreach ( $dims as $key => $dim ) {
			$score = isset( $scores[ $key ] ) ? max( 1, min( 4, (float) $scores[ $key ] ) ) : 0;
			if ( $score > 0 ) {
				$clean_scores[ $key ] = $score;
				$sum_weight += (float) $dim['weight'];
				$sum_score  += (float) $dim['weight'] * $score;
			}
		}

		$employee_name = isset( $payload['employee_name'] ) ? sanitize_text_field( $payload['employee_name'] ) : '';
		$department    = isset( $payload['department'] ) ? sanitize_text_field( $payload['department'] ) : '';
		$period        = isset( $payload['period'] ) ? sanitize_text_field( $payload['period'] ) : '';

		foreach ( $dims as $key => $dim ) {
			if ( ! isset( $clean_scores[ $key ] ) ) {
				wp_send_json_error( array( 'message' => 'امتیاز «' . $dim['label'] . '» (۱ تا ۴) الزامی است.' ) );
			}
		}
		if ( ! $employee_name || ! $department || ! $period ) {
			wp_send_json_error( array( 'message' => 'نام کارمند، واحد و دوره زمانی الزامی است.' ) );
		}

		$overall = $sum_weight > 0 ? round( $sum_score / $sum_weight, 2 ) : 2.5;
		$cycle_id = $this->ensure_cycle();

		Coachroom_OD_DB::insert_performance(
			array(
				'cycle_id'             => $cycle_id,
				'organization'         => get_option( 'cr_od_org_name', 'شرکت توسعه انرژی و نفت' ),
				'department'           => $department,
				'employee_name'        => $employee_name,
				'employee_role'        => isset( $payload['employee_role'] ) ? sanitize_text_field( $payload['employee_role'] ) : '',
				'supervisor_name'      => isset( $payload['supervisor_name'] ) ? sanitize_text_field( $payload['supervisor_name'] ) : '',
				'evaluator_role'       => isset( $payload['evaluator_role'] ) ? sanitize_text_field( $payload['evaluator_role'] ) : '',
				'period'               => $period,
				'results_score'        => $clean_scores['results_score'],
				'quality_score'        => $clean_scores['quality_score'],
				'teamwork_score'       => $clean_scores['teamwork_score'],
				'learning_score'       => $clean_scores['learning_score'],
				'behavior_score'       => $clean_scores['behavior_score'],
				'customer_score'       => $clean_scores['customer_score'],
				'overall_score'        => $overall,
				'feedback_given'       => ! empty( $payload['feedback_given'] ),
				'sbi_quality'          => isset( $payload['sbi_quality'] ) ? max( 1, min( 4, (float) $payload['sbi_quality'] ) ) : 1,
				'oskar_used'           => ! empty( $payload['oskar_used'] ),
				'coaching_effectiveness' => isset( $payload['coaching_effectiveness'] ) ? max( 1, min( 4, (float) $payload['coaching_effectiveness'] ) ) : 1,
				'growth_score'         => isset( $payload['growth_score'] ) ? max( 1, min( 4, (float) $payload['growth_score'] ) ) : 1,
				'notes'                => isset( $payload['notes'] ) ? sanitize_textarea_field( $payload['notes'] ) : '',
			)
		);

		wp_send_json_success(
			array(
				'message' => 'ارزیابی عملکرد فردی ثبت شد؛ امتیاز کل: ' . $overall . '/۴. بازخورد SBI و مربی‌گری OSKAR به داشبورد مدیران اضافه شد.',
				'data'    => Coachroom_OD_Helpers::dashboard_data( $cycle_id ),
			)
		);
	}

	/**
	 * Return latest active cycle, creating one if needed.
	 *
	 * @return int
	 */
	private function ensure_cycle() {
		global $wpdb;
		$cycles   = Coachroom_OD_DB::table( 'cycles' );
		$latest   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$cycles} WHERE status = %s ORDER BY id DESC LIMIT 1", 'active' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$cycle_id = $latest ? (int) $latest->id : 0;
		if ( ! $cycle_id ) {
			$cycle_id = Coachroom_OD_DB::create_cycle( 'دوره ارزیابی — ' . wp_date( 'Y/m/d' ), 'دوره ثبت‌شده از پلتفرم CoachRoom' );
		}
		return $cycle_id;
	}
}
