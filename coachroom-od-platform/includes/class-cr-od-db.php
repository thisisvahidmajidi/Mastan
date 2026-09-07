<?php
/**
 * Database table creation and demo data.
 *
 * @package CoachRoom_OD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Coachroom_OD_DB
 */
class Coachroom_OD_DB {

	/**
	 * Table name.
	 *
	 * @param string $kind cycles|responses.
	 * @return string
	 */
	public static function table( $kind ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		if ( 'cycles' === $kind ) {
			return $prefix . 'cr_od_cycles';
		}
		if ( 'performances' === $kind ) {
			return $prefix . 'cr_od_performances';
		}
		return $prefix . 'cr_od_responses';
	}

	/**
	 * Activation hook.
	 */
	public static function activate() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$cycles = self::table( 'cycles' );
		$responses = self::table( 'responses' );
		$performances = self::table( 'performances' );

		$sql_cycles = "CREATE TABLE {$cycles} (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title VARCHAR(190) NOT NULL,
			description TEXT NULL,
			status VARCHAR(30) NOT NULL DEFAULT 'active',
			created_by BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL,
			PRIMARY KEY  (id)
		) {$charset_collate};";

		$sql_responses = "CREATE TABLE {$responses} (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			cycle_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
			user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
			organization VARCHAR(190) NOT NULL DEFAULT '',
			department VARCHAR(190) NOT NULL DEFAULT '',
			assessor_role VARCHAR(60) NOT NULL DEFAULT '',
			dimension VARCHAR(60) NOT NULL,
			question_key VARCHAR(120) NOT NULL DEFAULT '',
			question_label VARCHAR(255) NOT NULL DEFAULT '',
			score DECIMAL(5,2) NOT NULL DEFAULT 1.00,
			weight DECIMAL(5,2) NOT NULL DEFAULT 1.00,
			notes TEXT NULL,
			created_at DATETIME NOT NULL,
			PRIMARY KEY  (id),
			KEY cycle_id (cycle_id),
			KEY dimension (dimension),
			KEY question_key (question_key),
			KEY department (department)
		) {$charset_collate};";

		$sql_performances = "CREATE TABLE {$performances} (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			cycle_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
			user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
			organization VARCHAR(190) NOT NULL DEFAULT '',
			department VARCHAR(190) NOT NULL DEFAULT '',
			employee_name VARCHAR(190) NOT NULL DEFAULT '',
			employee_role VARCHAR(60) NOT NULL DEFAULT '',
			supervisor_name VARCHAR(190) NOT NULL DEFAULT '',
			evaluator_role VARCHAR(60) NOT NULL DEFAULT '',
			period VARCHAR(60) NOT NULL DEFAULT '',
			results_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
			quality_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
			teamwork_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
			learning_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
			behavior_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
			customer_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
			overall_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
			feedback_given TINYINT(1) NOT NULL DEFAULT 0,
			sbi_quality DECIMAL(5,2) NOT NULL DEFAULT 1.00,
			oskar_used TINYINT(1) NOT NULL DEFAULT 0,
			coaching_effectiveness DECIMAL(5,2) NOT NULL DEFAULT 1.00,
			growth_score DECIMAL(5,2) NOT NULL DEFAULT 1.00,
			scores_evidence TEXT NULL,
			notes TEXT NULL,
			created_at DATETIME NOT NULL,
			PRIMARY KEY  (id),
			KEY cycle_id (cycle_id),
			KEY department (department),
			KEY employee_name (employee_name)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql_cycles );
		dbDelta( $sql_responses );
		dbDelta( $sql_performances );

		if ( ! get_option( 'cr_od_seeded_v1' ) ) {
			self::seed_demo_data();
			update_option( 'cr_od_seeded_v1', 1 );
		}

		self::maybe_upgrade();
	}

	/**
	 * Upgrade existing tables when the plugin is updated (adds question columns).
	 */
	public static function maybe_upgrade() {
		global $wpdb;
		$responses = self::table( 'responses' );
		$performances = self::table( 'performances' );
		$cols      = array();
		$found     = $wpdb->get_results( "SHOW COLUMNS FROM {$responses}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( $found ) {
			foreach ( $found as $col ) {
				$cols[ $col->Field ] = true;
			}
		}

		// Ensure the individual performance evaluation table exists on update.
		$perf_exists = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $performances ) );
		if ( ! $perf_exists ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$performances} (
				id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				cycle_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
				user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
				organization VARCHAR(190) NOT NULL DEFAULT '',
				department VARCHAR(190) NOT NULL DEFAULT '',
				employee_name VARCHAR(190) NOT NULL DEFAULT '',
				employee_role VARCHAR(60) NOT NULL DEFAULT '',
				supervisor_name VARCHAR(190) NOT NULL DEFAULT '',
				evaluator_role VARCHAR(60) NOT NULL DEFAULT '',
				period VARCHAR(60) NOT NULL DEFAULT '',
				results_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
				quality_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
				teamwork_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
				learning_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
				behavior_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
				customer_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
				overall_score DECIMAL(5,2) NOT NULL DEFAULT 2.50,
				feedback_given TINYINT(1) NOT NULL DEFAULT 0,
				sbi_quality DECIMAL(5,2) NOT NULL DEFAULT 1.00,
				oskar_used TINYINT(1) NOT NULL DEFAULT 0,
				coaching_effectiveness DECIMAL(5,2) NOT NULL DEFAULT 1.00,
				growth_score DECIMAL(5,2) NOT NULL DEFAULT 1.00,
				notes TEXT NULL,
				created_at DATETIME NOT NULL,
				PRIMARY KEY  (id),
				KEY cycle_id (cycle_id),
				KEY department (department),
				KEY employee_name (employee_name)
			) {$charset_collate};";
			dbDelta( $sql );
			self::seed_performances();
		}

		// Ensure the individual performance evidence column exists on update.
		if ( $perf_exists ) {
			$perf_cols = array();
			$perf_found = $wpdb->get_results( "SHOW COLUMNS FROM {$performances}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			if ( $perf_found ) {
				foreach ( $perf_found as $col ) {
					$perf_cols[ $col->Field ] = true;
				}
			}
			if ( ! isset( $perf_cols['scores_evidence'] ) ) {
				$wpdb->query( "ALTER TABLE {$performances} ADD COLUMN scores_evidence TEXT NULL AFTER growth_score" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			}
		}

		if ( version_compare( (string) get_option( 'cr_od_db_version', '0' ), '1.13.0', '<' ) ) {
			self::backfill_agility_responses();
		}

		if ( isset( $cols['question_key'] ) && isset( $cols['question_label'] ) ) {
			update_option( 'cr_od_db_version', '1.13.0' );
			return;
		}

		if ( ! isset( $cols['question_key'] ) ) {
			$wpdb->query( "ALTER TABLE {$responses} ADD COLUMN question_key VARCHAR(120) NOT NULL DEFAULT '' AFTER dimension" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
		if ( ! isset( $cols['question_label'] ) ) {
			$wpdb->query( "ALTER TABLE {$responses} ADD COLUMN question_label VARCHAR(255) NOT NULL DEFAULT '' AFTER question_key" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
		if ( ! isset( $cols['question_key'] ) || ! isset( $cols['question_label'] ) ) {
			$wpdb->query( "ALTER TABLE {$responses} ADD KEY question_key (question_key)" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		update_option( 'cr_od_db_version', '1.13.0' );
	}

	/**
	 * Backfill the new agility questions for existing assessment respondents.
	 *
	 * The 1.11.0 release separates agility from formalization. Existing installs
	 * already have formalization rows, so we create conservative agility rows from
	 * the department's formalization average and the next-wave adjustment instead
	 * of treating the new dimension as a missing/low default.
	 */
	public static function backfill_agility_responses() {
		global $wpdb;
		$responses = self::table( 'responses' );
		$exists    = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $responses ) );
		if ( ! $exists ) {
			return;
		}
		$agility_questions = array();
		foreach ( Coachroom_OD_Helpers::questions() as $q ) {
			if ( 'agility' === $q['dimension'] ) {
				$agility_questions[] = $q;
			}
		}
		if ( empty( $agility_questions ) ) {
			return;
		}
		$groups = $wpdb->get_results( "SELECT DISTINCT cycle_id, user_id, department, assessor_role, organization FROM {$responses} WHERE dimension = 'formalization'" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		foreach ( (array) $groups as $g ) {
			$existing = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$responses} WHERE question_key = %s AND cycle_id = %d AND user_id = %d AND department = %s AND assessor_role = %s", 'agility_q1', $g->cycle_id, $g->user_id, $g->department, $g->assessor_role ) );
			if ( $existing > 0 ) {
				continue;
			}
			$average = $wpdb->get_var( $wpdb->prepare( "SELECT AVG(score) FROM {$responses} WHERE dimension = 'formalization' AND cycle_id = %d AND user_id = %d AND department = %s AND assessor_role = %s", $g->cycle_id, $g->user_id, $g->department, $g->assessor_role ) );
			$base_score = null !== $average ? (float) $average : 2.5;
			foreach ( $agility_questions as $idx => $q ) {
				$score = max( 1, min( 4, round( ( $base_score + ( ( $idx % 3 ) - 1 ) * 0.15 ) * 2 ) / 2 ) );
				self::insert_response(
					array(
						'cycle_id'      => (int) $g->cycle_id,
						'user_id'       => (int) $g->user_id,
						'organization'  => $g->organization,
						'department'    => $g->department,
						'assessor_role' => $g->assessor_role,
						'dimension'     => 'agility',
						'question_key'  => $q['key'],
						'question_label'=> $q['label'],
						'score'         => $score,
						'weight'        => isset( $q['weight'] ) ? $q['weight'] : 1.2,
						'notes'         => 'بازخودکار برای تفکیک رسمیت/چابکی در نسخه 1.11.0.',
					)
				);
			}
		}
	}

	/**
	 * Deactivation hook.
	 */
	public static function deactivate() {
		// Keeps data by design. Use uninstall.php to remove tables.
	}

	/**
	 * Create a cycle.
	 *
	 * @param string $title Cycle title.
	 * @param string $desc  Description.
	 * @return int
	 */
	public static function create_cycle( $title, $desc = '' ) {
		global $wpdb;
		$table = self::table( 'cycles' );
		$title = sanitize_text_field( $title );
		$desc  = sanitize_textarea_field( $desc );
		if ( empty( $title ) ) {
			return 0;
		}
		$wpdb->insert(
			$table,
			array(
				'title'       => $title,
				'description' => $desc,
				'status'      => 'active',
				'created_by'  => get_current_user_id(),
				'created_at'  => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%s', '%d', '%s' )
		);
		return (int) $wpdb->insert_id;
	}

	/**
	 * Insert one response row.
	 *
	 * @param array $data Response data.
	 * @return int
	 */
	public static function insert_response( $data ) {
		global $wpdb;
		$table = self::table( 'responses' );
		$wpdb->insert(
			$table,
			array(
				'cycle_id'      => isset( $data['cycle_id'] ) ? absint( $data['cycle_id'] ) : 0,
				'user_id'       => isset( $data['user_id'] ) ? absint( $data['user_id'] ) : get_current_user_id(),
				'organization'  => isset( $data['organization'] ) ? sanitize_text_field( $data['organization'] ) : '',
				'department'    => isset( $data['department'] ) ? sanitize_text_field( $data['department'] ) : '',
				'assessor_role' => isset( $data['assessor_role'] ) ? sanitize_text_field( $data['assessor_role'] ) : '',
				'dimension'     => Coachroom_OD_Helpers::sanitize_slug( $data['dimension'] ),
				'question_key'  => isset( $data['question_key'] ) ? Coachroom_OD_Helpers::sanitize_slug( $data['question_key'] ) : '',
				'question_label'=> isset( $data['question_label'] ) ? sanitize_text_field( $data['question_label'] ) : '',
				'score'         => isset( $data['score'] ) ? max( 1, min( 4, (float) $data['score'] ) ) : 1,
				'weight'        => isset( $data['weight'] ) ? (float) $data['weight'] : 1,
				'notes'         => isset( $data['notes'] ) ? sanitize_textarea_field( $data['notes'] ) : '',
				'created_at'    => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%s', '%s' )
		);
		return (int) $wpdb->insert_id;
	}

	/**
	 * Insert one individual performance evaluation row.
	 *
	 * @param array $data Performance data.
	 * @return int
	 */
	public static function insert_performance( $data ) {
		global $wpdb;
		$table = self::table( 'performances' );
		$wpdb->insert(
			$table,
			array(
				'cycle_id'             => isset( $data['cycle_id'] ) ? absint( $data['cycle_id'] ) : 0,
				'user_id'              => isset( $data['user_id'] ) ? absint( $data['user_id'] ) : get_current_user_id(),
				'organization'         => isset( $data['organization'] ) ? sanitize_text_field( $data['organization'] ) : '',
				'department'           => isset( $data['department'] ) ? sanitize_text_field( $data['department'] ) : '',
				'employee_name'        => isset( $data['employee_name'] ) ? sanitize_text_field( $data['employee_name'] ) : '',
				'employee_role'        => isset( $data['employee_role'] ) ? sanitize_text_field( $data['employee_role'] ) : '',
				'supervisor_name'      => isset( $data['supervisor_name'] ) ? sanitize_text_field( $data['supervisor_name'] ) : '',
				'evaluator_role'       => isset( $data['evaluator_role'] ) ? sanitize_text_field( $data['evaluator_role'] ) : '',
				'period'               => isset( $data['period'] ) ? sanitize_text_field( $data['period'] ) : '',
				'results_score'        => isset( $data['results_score'] ) ? max( 1, min( 4, (float) $data['results_score'] ) ) : 2.5,
				'quality_score'        => isset( $data['quality_score'] ) ? max( 1, min( 4, (float) $data['quality_score'] ) ) : 2.5,
				'teamwork_score'       => isset( $data['teamwork_score'] ) ? max( 1, min( 4, (float) $data['teamwork_score'] ) ) : 2.5,
				'learning_score'       => isset( $data['learning_score'] ) ? max( 1, min( 4, (float) $data['learning_score'] ) ) : 2.5,
				'behavior_score'       => isset( $data['behavior_score'] ) ? max( 1, min( 4, (float) $data['behavior_score'] ) ) : 2.5,
				'customer_score'       => isset( $data['customer_score'] ) ? max( 1, min( 4, (float) $data['customer_score'] ) ) : 2.5,
				'overall_score'        => isset( $data['overall_score'] ) ? max( 1, min( 4, (float) $data['overall_score'] ) ) : 2.5,
				'feedback_given'       => ! empty( $data['feedback_given'] ) ? 1 : 0,
				'sbi_quality'          => isset( $data['sbi_quality'] ) ? max( 1, min( 4, (float) $data['sbi_quality'] ) ) : 1,
				'oskar_used'           => ! empty( $data['oskar_used'] ) ? 1 : 0,
				'coaching_effectiveness' => isset( $data['coaching_effectiveness'] ) ? max( 1, min( 4, (float) $data['coaching_effectiveness'] ) ) : 1,
				'growth_score'         => isset( $data['growth_score'] ) ? max( 1, min( 4, (float) $data['growth_score'] ) ) : 1,
				'scores_evidence'      => isset( $data['scores_evidence'] ) ? wp_json_encode( $data['scores_evidence'] ) : '',
				'notes'                => isset( $data['notes'] ) ? sanitize_textarea_field( $data['notes'] ) : '',
				'created_at'           => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%f', '%f', '%f', '%f', '%d', '%f', '%d', '%f', '%f', '%s', '%s', '%s' )
		);
		return (int) $wpdb->insert_id;
	}

	/**
	 * Get performance evaluations, newest first.
	 *
	 * @param int $cycle_id Optional cycle filter.
	 * @return array
	 */
	public static function get_performances( $cycle_id = 0 ) {
		global $wpdb;
		$table = self::table( 'performances' );
		$cycle_id = absint( $cycle_id );
		$sql = "SELECT * FROM {$table}";
		if ( $cycle_id > 0 ) {
			$sql .= $wpdb->prepare( ' WHERE cycle_id = %d', $cycle_id );
		}
		$sql .= ' ORDER BY id DESC LIMIT 200';
		$rows = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		return is_array( $rows ) ? $rows : array();
	}

	/**
	 * Seed optional demo individual performance evaluations.
	 *
	 * @param bool $replace Whether to replace existing demo rows.
	 */
	public static function seed_performances( $replace = false ) {
		$table = self::table( 'performances' );
		global $wpdb;
		$exists = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
		if ( ! $exists ) {
			return;
		}
		$count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( $count > 0 && ! $replace ) {
			return;
		}
		if ( $replace ) {
			$wpdb->query( "TRUNCATE TABLE {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		$cycle = Coachroom_OD_DB::create_cycle( 'دوره ارزیابی عملکرد فردی — تابستان ۱۴۰۵', 'ارزیابی عملکرد فردی و حرفه‌ای کارکنان' );
		$samples = array(
			array( 'عملیات، تولید و پالایش', 'مهندس فرآیند', 'رضا محمدی', 'سرپرست', 3.5, 3.0, 3.0, 3.5, 3.5, 3.5, 1, 3.5, 1, 3.0, 3.0 ),
			array( 'HSE، ایمنی و محیط‌زیست', 'کارشناس HSE', 'مریم احمدی', 'سرپرست', 3.0, 3.5, 3.5, 3.0, 3.5, 3.0, 1, 3.0, 1, 3.0, 3.5 ),
			array( 'مهندسی، پروژه و تعمیرات', 'کارشناس تعمیرات', 'علی کریمی', 'مدیر میانی', 2.0, 2.5, 2.5, 2.5, 2.0, 2.5, 1, 2.5, 1, 2.5, 2.0 ),
			array( 'مالی، اداری و پشتیبانی', 'کارشناس اداری', 'سارا نوری', 'سرپرست', 1.5, 2.0, 2.0, 2.0, 2.0, 2.0, 0, 1.5, 0, 1.5, 2.0 ),
			array( 'منابع انسانی و توسعه سازمان', 'مربی سازمانی', 'حمید رضایی', 'مدیر میانی', 3.0, 3.0, 3.5, 3.5, 3.5, 3.0, 1, 3.5, 1, 3.5, 3.5 ),
		);
		foreach ( $samples as $idx => $s ) {
			$overall = ( $s[4] + $s[5] + $s[6] + $s[7] + $s[8] + $s[9] ) / 6;
			self::insert_performance(
				array(
					'cycle_id'      => $cycle,
					'organization'  => 'شرکت توسعه انرژی و نفت',
					'department'    => $s[0],
					'employee_name' => $s[2],
					'employee_role' => $s[1],
					'supervisor_name'=> 'سرپرست ' . $s[0],
					'evaluator_role'=> $s[3],
					'period'        => 'تابستان ۱۴۰۵',
					'results_score' => $s[4],
					'quality_score' => $s[5],
					'teamwork_score'=> $s[6],
					'learning_score'=> $s[7],
					'behavior_score'=> $s[8],
					'customer_score'=> $s[9],
					'overall_score' => round( $overall, 2 ),
					'feedback_given'=> $s[10],
					'sbi_quality'   => $s[11],
					'oskar_used'    => $s[12],
					'coaching_effectiveness' => $s[13],
					'growth_score'  => $s[14],
					'notes'         => 0 === $idx % 2 ? 'ارزیابی دوره‌ای بر اساس شاخص‌های عملکردی و بازخورد ۱:۱.' : '',
				)
			);
		}
	}

	/**
	 * Seed demo data for an energy/oil & gas organization.
	 *
	 * @param bool $replace Whether to clear existing data first.
	 */
	public static function seed_demo_data( $replace = false ) {
		global $wpdb;
		if ( $replace ) {
			self::clear_all_data();
		}

		$dimensions = Coachroom_OD_Helpers::dimensions();
		$weights    = Coachroom_OD_Helpers::weights();
		$questions  = Coachroom_OD_Helpers::questions();
		$wquestions = Coachroom_OD_Helpers::weisbord_questions();
		$wweights   = array();
		foreach ( Coachroom_OD_Helpers::weisbord_boxes() as $slug => $box ) {
			$wweights[ $slug ] = (float) $box['weight'];
		}
		$aqquestions = Coachroom_OD_Helpers::attitude_questions();
		$aweights    = array();
		foreach ( Coachroom_OD_Helpers::attitude_groups() as $slug => $group ) {
			$aweights[ $slug ] = (float) $group['weight'];
		}

		$cycle1 = self::create_cycle( 'دوره پایه — پاییز ۱۴۰۴', 'ارزیابی اولیه ساختار سازمانی' );
		$cycle2 = self::create_cycle( 'دوره میانی — زمستان ۱۴۰۴', 'ارزیابی پس از شروع برنامه مربی‌گری سرپرستان' );
		$cycle3 = self::create_cycle( 'دوره جاری — تابستان ۱۴۰۵', 'وضعیت موجود سازمان و شناسایی گپ‌ها' );

		$departments = array(
			'عملیات، تولید و پالایش' => array(
				'base'   => 0.0,
				'agility'          => -0.25,
				'active_listening' => -0.15,
				'questioning'      => -0.2,
				'feedback'         => -0.25,
				'performance_eval' => -0.2,
				'coaching_culture' => -0.3,
			),
			'مهندسی، پروژه و تعمیرات' => array(
				'base'   => 0.2,
				'active_listening' => 0.1,
				'questioning'      => 0.15,
				'feedback'         => 0.1,
				'psychological_safety' => 0.1,
			),
			'HSE، ایمنی و محیط‌زیست' => array(
				'base'   => 0.25,
				'feedback'         => 0.2,
				'psychological_safety' => 0.2,
				'learning_culture' => 0.15,
			),
			'مالی، اداری و پشتیبانی' => array(
				'base'   => -0.25,
				'formalization'    => -0.1,
				'agility'          => -0.3,
				'centralization'   => -0.2,
				'complexity'       => -0.15,
				'coaching_culture' => -0.3,
			),
			'منابع انسانی و توسعه سازمان' => array(
				'base'   => 0.1,
				'feedback'         => 0.2,
				'performance_eval' => 0.1,
				'coaching_culture' => 0.2,
				'learning_culture' => 0.2,
			),
		);

		// Base (Wave 2) scores per dimension.
		$base = array(
			'formalization'        => 2.50,
			'agility'              => 2.25,
			'centralization'       => 2.05,
			'complexity'           => 2.20,
			'active_listening'     => 2.05,
			'questioning'          => 1.95,
			'feedback'             => 1.85,
			'performance_eval'     => 1.85,
			'psychological_safety' => 2.15,
			'learning_culture'     => 2.25,
			'coaching_culture'     => 1.75,
		);

		// Cycle multipliers: base 0, first previous lower, current a bit higher.
		$cycle_adjust = array(
			$cycle1 => -0.45,
			$cycle2 => -0.18,
			$cycle3 => 0.12,
		);

		$roles = array( 'سرپرست', 'مدیر میانی', 'کارمند', 'مربی سازمانی' );

		$index = 0;
		foreach ( $cycle_adjust as $cycle_id => $adjust ) {
			$cycle_id = (int) $cycle_id;
			if ( $cycle_id <= 0 ) {
				continue;
			}
			foreach ( $departments as $dept_name => $dept_adj ) {
				foreach ( $roles as $role_index => $role_name ) {
					// Every role completes the full assessment for a department so
					// role/unit profiles and reliability are computed on complete data.
					foreach ( $questions as $q_index => $question ) {
						$slug  = $question['dimension'];
						$score = $base[ $slug ]
							+ $dept_adj['base']
							+ ( isset( $dept_adj[ $slug ] ) ? $dept_adj[ $slug ] : 0 )
							+ $adjust
							+ ( ( $q_index % 3 ) - 1 ) * 0.15
							+ ( ( $role_index % 2 ) ? 0.05 : -0.05 );
						$score = max( 1, min( 4, round( $score * 2 ) / 2 ) );
						$notes = 0 === ( $index % 3 ) ? 'شاخص‌های مشاهده‌ای از جلسات تیمی و نتایج ارزیابی عملکرد.' : '';
						self::insert_response(
							array(
								'cycle_id'      => $cycle_id,
								'user_id'       => 0,
								'organization'  => 'شرکت توسعه انرژی و نفت',
								'department'    => $dept_name,
								'assessor_role' => $role_name,
								'dimension'     => $slug,
								'question_key'  => $question['key'],
								'question_label'=> $question['label'],
								'score'         => $score,
								'weight'        => isset( $weights[ $slug ] ) ? $weights[ $slug ] : 1,
								'notes'         => $notes,
							)
						);
						$index++;
					}

					// Weisbord Six-Box diagnostic questions (18 per role/unit/cycle).
					foreach ( $wquestions as $q_index => $question ) {
						$slug  = $question['dimension'];
						$score = 2.3
							+ $dept_adj['base']
							+ $adjust
							+ ( ( $q_index % 3 ) - 1 ) * 0.25
							+ ( ( $role_index % 2 ) ? 0.05 : -0.05 );
						$score = max( 1, min( 4, round( $score * 2 ) / 2 ) );
						self::insert_response(
							array(
								'cycle_id'      => $cycle_id,
								'user_id'       => 0,
								'organization'  => 'شرکت توسعه انرژی و نفت',
								'department'    => $dept_name,
								'assessor_role' => $role_name,
								'dimension'     => $slug,
								'question_key'  => $question['key'],
								'question_label'=> $question['label'],
								'score'         => $score,
								'weight'        => isset( $wweights[ $slug ] ) ? $wweights[ $slug ] : 1,
								'notes'         => '',
							)
						);
						$index++;
					}

					// Employee attitude model questions (12 per role/unit/cycle).
					foreach ( $aqquestions as $q_index => $question ) {
						$slug  = $question['dimension'];
						$score = 2.55
							+ $dept_adj['base']
							+ $adjust
							+ ( ( $q_index % 4 ) - 1.5 ) * 0.1
							+ ( ( $role_index % 2 ) ? 0.05 : -0.05 );
						if ( 'attitude_other' === $slug ) {
							$score -= 0.15;
						}
						$score = max( 1, min( 4, round( $score * 2 ) / 2 ) );
						self::insert_response(
							array(
								'cycle_id'      => $cycle_id,
								'user_id'       => 0,
								'organization'  => 'شرکت توسعه انرژی و نفت',
								'department'    => $dept_name,
								'assessor_role' => $role_name,
								'dimension'     => $slug,
								'question_key'  => $question['key'],
								'question_label'=> $question['label'],
								'score'         => $score,
								'weight'        => isset( $aweights[ $slug ] ) ? $aweights[ $slug ] : 1,
								'notes'         => '',
							)
						);
						$index++;
					}
				}
			}
		}

		self::seed_performances();
	}

	/**
	 * Data integrity / health check.
	 *
	 * @return array
	 */
	public static function health() {
		global $wpdb;
		$cycles    = self::table( 'cycles' );
		$responses = self::table( 'responses' );
		$performances = self::table( 'performances' );

		$cycles_exist    = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $cycles ) );
		$responses_exist = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $responses ) );
		$performances_exist = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $performances ) );

		$rows    = 0;
		$c_count = 0;
		$p_count = 0;
		if ( $responses_exist ) {
			$rows = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$responses}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
		if ( $cycles_exist ) {
			$c_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$cycles}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
		if ( $performances_exist ) {
			$p_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$performances}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		$question_columns = false;
		if ( $responses_exist ) {
			$columns = $wpdb->get_col( "SHOW COLUMNS FROM {$responses}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$question_columns = in_array( 'question_key', $columns, true ) && in_array( 'question_label', $columns, true );
		}

		$data = array(
			'cycles_table'       => $cycles_exist,
			'responses_table'    => $responses_exist,
			'performances_table' => $performances_exist,
			'question_columns'   => $question_columns,
			'rows'               => $rows,
			'cycles'             => $c_count,
			'performance_rows'   => $p_count,
		);

		if ( $cycles_exist && $responses_exist && $question_columns && $performances_exist ) {
			$data['dashboard'] = Coachroom_OD_Helpers::dashboard_data();
			$data['ok']        = true;
		} else {
			$data['ok'] = false;
		}

		return $data;
	}

	/**
	 * Clear all assessment data and cycles.
	 */
	public static function clear_all_data() {
		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE " . self::table( 'responses' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query( "TRUNCATE TABLE " . self::table( 'performances' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query( "TRUNCATE TABLE " . self::table( 'cycles' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}
}
