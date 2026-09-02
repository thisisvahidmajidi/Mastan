<?php
/**
 * Uninstall: remove tables and options created by CoachRoom OD.
 *
 * @package CoachRoom_OD
 */

// If uninstall is not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$prefix = $wpdb->prefix;
$wpdb->query( "DROP TABLE IF EXISTS {$prefix}cr_od_cycles" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
$wpdb->query( "DROP TABLE IF EXISTS {$prefix}cr_od_responses" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

delete_option( 'cr_od_org_name' );
delete_option( 'cr_od_industry' );
delete_option( 'cr_od_target_wave' );
delete_option( 'cr_od_seeded_v1' );
