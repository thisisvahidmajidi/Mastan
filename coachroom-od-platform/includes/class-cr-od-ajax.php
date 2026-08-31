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
	}

	/**
	 * Save all submitted dimensions from the front-end assessment form in a single request.
	 *
	 * The front-end sends a JSON string in the `dimensions` field, but for compatibility
	 * we also accept the older single `dimension`/`score` format.
	 */
	public function save_response() {
		check_ajax_referer( 'cr_od_nonce', 'nonce' );

		$allowed = array_keys( Coachroom_OD_Helpers::dimensions() );
		$dept    = isset( $_POST['department'] ) ? sanitize_text_field( wp_unslash( $_POST['department'] ) ) : 'نامشخص';
		$role    = isset( $_POST['assessor_role'] ) ? sanitize_text_field( wp_unslash( $_POST['assessor_role'] ) ) : 'کارمند';
		$notes   = isset( $_POST['notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['notes'] ) ) : '';
		$organization = isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : get_option( 'cr_od_org_name', 'شرکت توسعه انرژی و نفت' );

		if ( empty( $dept ) ) {
			$dept = 'نامشخص';
		}

		$dimensions = array();

		// New batch format: dimensions = [{ "slug": "...", "score": 3 }, ...].
		if ( isset( $_POST['dimensions'] ) ) {
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

			foreach ( $dimensions as $dimension => $score ) {
				// Replace only the previous answer of the SAME role + department + dimension.
				// Different roles/units are preserved so every role's result stays in the analysis.
				$wpdb = $GLOBALS['wpdb'];
				$wpdb->delete(
					$responses,
					array(
						'cycle_id'     => $cycle_id,
						'dimension'    => $dimension,
						'department'   => $dept,
						'assessor_role'=> $role,
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
						'score'         => $score,
						'weight'        => isset( $weights[ $dimension ] ) ? $weights[ $dimension ] : 1,
						'notes'         => $notes,
					)
				);
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
