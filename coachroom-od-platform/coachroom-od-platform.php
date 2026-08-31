<?php
/**
 * Plugin Name:       CoachRoom - Organizational Development Platform
 * Plugin URI:        https://coachroom.ir
 * Description:       سامانه توسعه سازمانی CoachRoom؛ ارزیابی وضعیت سازمان بر پایه "موج‌های سازمانی"، سنجش رسمیت، پیچیدگی و تمرکز تصمیم‌گیری، و تدوین نقشه راه ارتقای سرپرستان به مربیان عملکردی. مخصوص سازمان‌های حوزه انرژی، نفت و گاز.
 * Version:           1.4.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            CoachRoom
 * Author URI:        https://coachroom.ir
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       coachroom-od
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CR_OD_VERSION', '1.4.0' );
define( 'CR_OD_PLUGIN_FILE', __FILE__ );
define( 'CR_OD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CR_OD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CR_OD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Core includes.
require_once CR_OD_PLUGIN_DIR . 'includes/class-cr-od-helpers.php';
require_once CR_OD_PLUGIN_DIR . 'includes/class-cr-od-db.php';
require_once CR_OD_PLUGIN_DIR . 'includes/class-cr-od-ajax.php';
require_once CR_OD_PLUGIN_DIR . 'includes/class-cr-od-render.php';

/**
 * Main bootstrap class.
 */
final class Coachroom_OD_Platform {

	/**
	 * Singleton instance.
	 *
	 * @var Coachroom_OD_Platform|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton.
	 *
	 * @return Coachroom_OD_Platform
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
		register_activation_hook( CR_OD_PLUGIN_FILE, array( 'Coachroom_OD_DB', 'activate' ) );
		register_deactivation_hook( CR_OD_PLUGIN_FILE, array( 'Coachroom_OD_DB', 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'register_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

		Coachroom_OD_Ajax::instance();
	}

	/**
	 * Load textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'coachroom-od', false, dirname( CR_OD_PLUGIN_BASENAME ) . '/languages' );
	}

	/**
	 * Register shortcode.
	 */
	public function register_shortcode() {
		add_shortcode( 'coachroom_od_platform', array( Coachroom_OD_Render::instance(), 'render' ) );
	}

	/**
	 * Register front assets.
	 */
	public function register_assets() {
		wp_register_style(
			'cr-od-platform',
			CR_OD_PLUGIN_URL . 'assets/css/coachroom-od-platform.css',
			array(),
			CR_OD_VERSION
		);

		wp_register_script(
			'cr-od-platform',
			CR_OD_PLUGIN_URL . 'assets/js/coachroom-od-platform.js',
			array(),
			CR_OD_VERSION,
			true
		);
	}

	/**
	 * Admin menu.
	 */
	public function register_admin_menu() {
		add_menu_page(
			'CoachRoom توسعه سازمانی',
			'CoachRoom OD',
			'manage_options',
			'coachroom-od',
			array( $this, 'admin_page' ),
			'dashicons-chart-area',
			26
		);

		add_submenu_page(
			'coachroom-od',
			'تنظیمات پلتفرم',
			'تنظیمات',
			'manage_options',
			'coachroom-od-settings',
			array( $this, 'admin_settings_page' )
		);
	}

	/**
	 * Admin shortcode instructions page.
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'CoachRoom - Organizational Development Platform', 'coachroom-od' ); ?></h1>
			<div class="card" style="max-width:760px;padding:18px 22px;">
				<h2 style="margin-top:0;">راهنمای فعال‌سازی در سایت</h2>
				<p>برای نمایش پلتفرم توسعه سازمانی در هر صفحه یا نوشته، شورت‌کد زیر را قرار دهید:</p>
				<p><code style="display:inline-block;background:#f1f1f1;padding:8px 14px;border-radius:6px;font-size:15px;">[coachroom_od_platform]</code></p>
				<p>ویژگی‌های پلتفرم:</p>
				<ul style="list-style:disc;padding-inline-start:20px;">
					<li>داشبورد شاخص‌های سازمانی با نمودارهای رادار، میله‌ای و روند</li>
					<li>ارزیابی ابعاد ساختاری (رسمیت، پیچیدگی، تمرکز) و مهارت‌های مربیگری</li>
					<li>تشخیص موج سازمانی (موج یکم تا چهارم)</li>
					<li>نقشه راه ۳۰/۶۰/۹۰ روزه برای ارتقای سرپرستان به مربیان عملکردی</li>
					<li>خروجی CSV و قابلیت چاپ گزارش مدیران</li>
				</ul>
				<hr/>
				<h3>تنظیمات پیشنهادی</h3>
				<p>نام سازمان، صنعت و موج هدف را از منوی <strong>CoachRoom OD &rarr; تنظیمات</strong> تعیین کنید.</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Admin settings page.
	 */
	public function admin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'دسترسی مجاز نیست.', 'coachroom-od' ) );
		}

		if ( isset( $_POST['cr_od_save_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cr_od_save_nonce'] ) ), 'cr_od_save_settings' ) ) {
			update_option( 'cr_od_org_name', isset( $_POST['cr_od_org_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cr_od_org_name'] ) ) : '' );
			update_option( 'cr_od_industry', isset( $_POST['cr_od_industry'] ) ? sanitize_text_field( wp_unslash( $_POST['cr_od_industry'] ) ) : '' );
			update_option( 'cr_od_target_wave', isset( $_POST['cr_od_target_wave'] ) ? absint( $_POST['cr_od_target_wave'] ) : 3 );
			echo '<div class="notice notice-success is-dismissible"><p>تنظیمات ذخیره شد.</p></div>';
		}

		$org_name     = get_option( 'cr_od_org_name', 'شرکت توسعه انرژی و نفت' );
		$industry     = get_option( 'cr_od_industry', 'انرژی، نفت و گاز' );
		$target_wave  = absint( get_option( 'cr_od_target_wave', 3 ) );
		$nonce        = wp_create_nonce( 'cr_od_save_settings' );
		?>
		<div class="wrap">
			<h1>تنظیمات پلتفرم CoachRoom</h1>
			<form method="post" action="" style="max-width:640px;">
				<input type="hidden" name="cr_od_save_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="cr_od_org_name">نام سازمان</label></th>
						<td><input type="text" name="cr_od_org_name" id="cr_od_org_name" value="<?php echo esc_attr( $org_name ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="cr_od_industry">صنعت / حوزه فعالیت</label></th>
						<td><input type="text" name="cr_od_industry" id="cr_od_industry" value="<?php echo esc_attr( $industry ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="cr_od_target_wave">موج هدف</label></th>
						<td>
							<select name="cr_od_target_wave" id="cr_od_target_wave">
								<option value="2" <?php selected( $target_wave, 2 ); ?>>موج دوم — بوروکراتیک</option>
								<option value="3" <?php selected( $target_wave, 3 ); ?>>موج سوم — هم‌آفرین (پیشنهادی)</option>
								<option value="4" <?php selected( $target_wave, 4 ); ?>>موج چهارم — یادگیرنده</option>
							</select>
						</td>
					</tr>
				</table>
				<?php submit_button( 'ذخیره تنظیمات' ); ?>
			</form>
			<hr/>
			<h2>عملیات داده</h2>
			<p>برای شروع نمایش نمونه شاخص‌ها می‌توانید داده‌های نمایشی تولید یا پاک‌سازی کنید.</p>
			<form method="post" action="" style="display:inline-block;">
				<input type="hidden" name="cr_od_action" value="seed" />
				<?php wp_nonce_field( 'cr_od_data_action', 'cr_od_data_nonce' ); ?>
				<?php submit_button( 'تولید داده نمایشی', 'secondary', 'submit', false ); ?>
			</form>
			<form method="post" action="" style="display:inline-block;">
				<input type="hidden" name="cr_od_action" value="clear" />
				<?php wp_nonce_field( 'cr_od_data_action', 'cr_od_data_nonce' ); ?>
				<?php submit_button( 'پاک‌سازی همه نتایج', 'delete', 'submit', false ); ?>
			</form>
			<?php
			if ( isset( $_POST['cr_od_action'] ) && isset( $_POST['cr_od_data_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cr_od_data_nonce'] ) ), 'cr_od_data_action' ) ) {
				if ( 'seed' === sanitize_text_field( wp_unslash( $_POST['cr_od_action'] ) ) ) {
					Coachroom_OD_DB::seed_demo_data( true );
					echo '<div class="notice notice-success is-dismissible"><p>داده نمایشی تولید شد.</p></div>';
				} elseif ( 'clear' === sanitize_text_field( wp_unslash( $_POST['cr_od_action'] ) ) ) {
					Coachroom_OD_DB::clear_all_data();
					echo '<div class="notice notice-warning is-dismissible"><p>همه نتایج پاک شد.</p></div>';
				}
			}
			?>
			<hr/>
			<h2>وضعیت یکپارچگی و عملکرد سامانه</h2>
			<?php
			$health = Coachroom_OD_DB::health();
			$ok     = ! empty( $health['ok'] );
			?>
			<div class="card" style="max-width:760px;padding:18px 22px;">
				<p style="font-size:15px;">
					<?php if ( $ok ) : ?>
						<strong style="color:#0f766e;">✓ سامانه سالم است.</strong>
					<?php else : ?>
						<strong style="color:#b91c1c;">! سامانه نیاز به بررسی دارد.</strong>
					<?php endif; ?>
				</p>
				<table class="widefat striped" style="margin-top:10px;">
					<tbody>
						<tr><th>جدول دوره‌ها</th><td><?php echo $health['cycles_table'] ? 'ایجاد شده ✓' : 'ایجاد نشده ✗'; ?></td></tr>
						<tr><th>جدول پاسخ‌ها</th><td><?php echo $health['responses_table'] ? 'ایجاد شده ✓' : 'ایجاد نشده ✗'; ?></td></tr>
						<tr><th>تعداد دوره‌ها</th><td><?php echo esc_html( $health['cycles'] ); ?></td></tr>
						<tr><th>تعداد سطرهای ارزیابی</th><td><?php echo esc_html( $health['rows'] ); ?></td></tr>
						<?php if ( $ok && isset( $health['dashboard']['summary'] ) ) : ?>
							<tr><th>امتیاز کلی</th><td><?php echo esc_html( $health['dashboard']['summary']['overall'] ); ?> / ۴</td></tr>
							<tr><th>موج فعلی</th><td><?php echo esc_html( $health['dashboard']['summary']['wave_label'] ); ?></td></tr>
							<tr><th>اقدامات اولویت‌دار</th><td><?php echo esc_html( count( $health['dashboard']['recommendations'] ) ); ?> مورد</td></tr>
						<?php endif; ?>
					</tbody>
				</table>
				<p style="color:#6b7280;font-size:13px;margin-top:12px;">این بخش، چرخه «ثبت ارزیابی ← پردازش داده ← داشبورد و نقشه راه» را از سمت سرور بررسی می‌کند.</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Admin assets (only on our pages).
	 *
	 * @param string $hook Current admin page.
	 */
	public function admin_assets( $hook ) {
		if ( false === strpos( $hook, 'coachroom-od' ) ) {
			return;
		}
		wp_enqueue_style( 'cr-od-platform', CR_OD_PLUGIN_URL . 'assets/css/coachroom-od-platform.css', array(), CR_OD_VERSION );
	}
}

Coachroom_OD_Platform::instance();
