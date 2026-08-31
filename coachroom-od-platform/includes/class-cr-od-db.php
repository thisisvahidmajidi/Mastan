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

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql_cycles );
		dbDelta( $sql_responses );

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
		$cols      = array();
		$found     = $wpdb->get_results( "SHOW COLUMNS FROM {$responses}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( $found ) {
			foreach ( $found as $col ) {
				$cols[ $col->Field ] = true;
			}
		}

		if ( isset( $cols['question_key'] ) && isset( $cols['question_label'] ) ) {
			update_option( 'cr_od_db_version', '1.5.0' );
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

		update_option( 'cr_od_db_version', '1.5.0' );
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

		$cycle1 = self::create_cycle( 'دوره پایه — پاییز ۱۴۰۴', 'ارزیابی اولیه ساختار سازمانی' );
		$cycle2 = self::create_cycle( 'دوره میانی — زمستان ۱۴۰۴', 'ارزیابی پس از شروع برنامه مربی‌گری سرپرستان' );
		$cycle3 = self::create_cycle( 'دوره جاری — تابستان ۱۴۰۵', 'وضعیت موجود سازمان و شناسایی گپ‌ها' );

		$departments = array(
			'عملیات، تولید و پالایش' => array(
				'base'   => 0.0,
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
				foreach ( $questions as $q_index => $question ) {
					$slug  = $question['dimension'];
					$score = $base[ $slug ]
						+ $dept_adj['base']
						+ ( isset( $dept_adj[ $slug ] ) ? $dept_adj[ $slug ] : 0 )
						+ $adjust
						+ ( ( $q_index % 3 ) - 1 ) * 0.15;
					$score = max( 1, min( 4, round( $score * 2 ) / 2 ) );
					if ( 0 === $index % 3 ) {
						$notes = 'شاخص‌های مشاهده‌ای از جلسات تیمی و نتایج ارزیابی عملکرد.';
					} else {
						$notes = '';
					}
					self::insert_response(
						array(
							'cycle_id'      => $cycle_id,
							'user_id'       => 0,
							'organization'  => 'شرکت توسعه انرژی و نفت',
							'department'    => $dept_name,
							'assessor_role' => $roles[ $index % count( $roles ) ],
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
			}
		}
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

		$cycles_exist    = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $cycles ) );
		$responses_exist = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $responses ) );

		$rows    = 0;
		$c_count = 0;
		if ( $responses_exist ) {
			$rows = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$responses}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
		if ( $cycles_exist ) {
			$c_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$cycles}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		$question_columns = false;
		if ( $responses_exist ) {
			$columns = $wpdb->get_col( "SHOW COLUMNS FROM {$responses}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$question_columns = in_array( 'question_key', $columns, true ) && in_array( 'question_label', $columns, true );
		}

		$data = array(
			'cycles_table'       => $cycles_exist,
			'responses_table'    => $responses_exist,
			'question_columns'   => $question_columns,
			'rows'               => $rows,
			'cycles'             => $c_count,
		);

		if ( $cycles_exist && $responses_exist && $question_columns ) {
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
		$wpdb->query( "TRUNCATE TABLE " . self::table( 'cycles' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}
}
