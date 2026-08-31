<?php
/**
 * Helpers: dimensions, waves, scoring and roadmap logic.
 *
 * @package CoachRoom_OD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Coachroom_OD_Helpers
 */
class Coachroom_OD_Helpers {

	/**
	 * Organizational development dimensions.
	 *
	 * Score direction: 1 = low organizational maturity / more bureaucratic,
	 * 4 = high maturity / adaptive, learning, coaching organization.
	 *
	 * @return array
	 */
	public static function dimensions() {
		return array(
			'formalization'        => array(
				'slug'       => 'formalization',
				'label'      => 'رسمیت و چابکی ساختاری',
				'short'      => 'رسمیت',
				'icon'       => '§',
				'weight'     => 1.0,
				'indicator'  => 'سطح انعطاف قوانین، رویه‌ها و اسناد حاکمیتی',
				'levels'     => array(
					1 => 'قوانین سخت، جزئی و دستوری؛ تغییر رویه با مانع زیاد',
					2 => 'قوانین مکتوب ولی اجرای کاغذی و پیچیده؛ استثنا در مدیریت',
					3 => 'قوانین ساده و روشن؛ استقلال عمل در چارچوب اصول',
					4 => 'چارچوب‌های توانمندساز؛ تصمیم‌گیری سریع با حداقل قوانین',
				),
				'recommend'  => array(
					'title'   => 'ساده‌سازی رسمیت به قانون‌های توانمندساز',
					'action'  => 'بازنگری رویه‌های کلیدی به شکل "حداقل قوانین قابل اعتماد" و جایگزینی چک‌لیست‌های دستوری با اصول راهنما',
					'kpi'     => 'کاهش زمان تصویب تغییرات تا ۳۰٪',
					'owner'   => 'معاونت توسعه سازمانی',
					'tool'    => 'کارگاه ساده‌سازی رویه‌ها + ماتریس حداقل و حداکثر رسمیت',
				),
			),
			'centralization'       => array(
				'slug'       => 'centralization',
				'label'      => 'تمرکز یا واگذاری تصمیم‌گیری',
				'short'      => 'تمرکز',
				'icon'       => '⬢',
				'weight'     => 1.2,
				'indicator'  => 'محل تصمیم‌گیری‌های عملیاتی و میزان استقلال واحدها',
				'levels'     => array(
					1 => 'تقریباً همه تصمیم‌ها در بالاترین سطح؛ سلسله‌مراتب سخت',
					2 => 'تصمیم‌های مهم در مرکز؛ واحدها فقط اجراکننده',
					3 => 'واگذاری تصمیم‌های عملیاتی به سرپرستان با پاسخ‌گویی',
					4 => 'تصمیم‌گیری شبکه‌ای، خودمختاری تیم‌ها و هم‌آفرینی',
				),
				'recommend'  => array(
					'title'   => 'واگذاری تصمیم‌گیری به سرپرستان-مربیان',
					'action'  => 'تعیین و شفاف‌سازی "اختیار تصمیم" برای سرپرستان عملیاتی (مثلاً ۸۵٪ تصمیم‌های روتین) و پیگیری با شاخص عملکرد',
					'kpi'     => 'کاهش ۴۰٪ ارجاع به مدیران عالی',
					'owner'   => 'مدیران واحدها',
					'tool'    => 'ماتریس RACI + منشور اختیار تصمیم سرپرست',
				),
			),
			'complexity'           => array(
				'slug'       => 'complexity',
				'label'      => 'پیچیدگی و لایه‌های ساختاری',
				'short'      => 'پیچیدگی',
				'icon'       => '≣',
				'weight'     => 1.0,
				'indicator'  => 'تعداد لایه‌ها، سیلوها و هماهنگی بین‌واحدی',
				'levels'     => array(
					1 => 'لایه‌های زیاد، دپارتمان‌های جدا و هماهنگی سخت',
					2 => 'ساختار وظیفه‌ای با ارجاع‌های متعدد و کار کند بین‌واحدی',
					3 => 'واحدهای شبکه‌ای و تیم‌های چندتخصصی با هماهنگی روشن',
					4 => 'ساختار شناور، خودسازمان‌ده و اتصال بین‌واحدی ارگانیک',
				),
				'recommend'  => array(
					'title'   => 'کاهش پیچیدگی و سیلوهای ساختاری',
					'action'  => 'شروع با ۲-۳ تیم چندتخصصی (cross-functional) در عملیات و پروژه؛ تعیین رابط‌های شبکه به جای ارجاع سلسله‌مراتبی',
					'kpi'     => 'کاهش زمان هماهنگی بین‌واحدی تا ۲۵٪',
					'owner'   => 'مدیر برنامه‌ریزی و توسعه',
					'tool'    => 'نقشه شبکه ارتباطی + تیم‌های حل‌مسئله مشترک',
				),
			),
			'active_listening'     => array(
				'slug'       => 'active_listening',
				'label'      => 'گوش دادن فعال اعضای سازمان',
				'short'      => 'گوش فعال',
				'icon'       => '♪',
				'weight'     => 1.4,
				'indicator'  => 'میزان شنیدن واقعی، بدون قضاوت و با قصد فهمیدن',
				'levels'     => array(
					1 => 'حرف یکدیگر قطع می‌شود؛ شنیدن فقط برای پاسخ‌دادن',
					2 => 'گوش دادن ظاهری؛ سوءتفاهم و نشنیدن پیام‌های مهم',
					3 => 'گوش دادن فعال و تأمل‌برانگیز؛ پرسش برای فهم عمیق',
					4 => 'گوش دادن همدلانه در همه سطوح؛ گفت‌وگوهای یادگیرنده',
				),
				'recommend'  => array(
					'title'   => 'تمرین مستمر گوش دادن فعال',
					'action'  => 'برگزاری جلسات "حلقه گوش دادن" هفتگی ۲۰ دقیقه‌ای در تیم‌ها؛ تمرین خلاصه‌سازی و پرسش بدون قضاوت',
					'kpi'     => 'افزایش نمره ادراک "مورد شنیده شدن" به بالای ۳.۵',
					'owner'   => 'سرپرستان عملیاتی',
					'tool'    => 'دفترچه گوش دادن فعال + تکنیک سه‌مرحله‌ای (سکوت، خلاصه، پرسش)',
				),
			),
			'questioning'          => array(
				'slug'       => 'questioning',
				'label'      => 'پرسش‌گری و ذهنیت واگرا',
				'short'      => 'پرسش‌گری',
				'icon'       => '?',
				'weight'     => 1.3,
				'indicator'  => 'میزان پرسش برای کشف جایگزین‌ها و حل مسئله',
				'levels'     => array(
					1 => 'ذهنیت همگرای جواب‌محور؛ ترس از پرسیدن',
					2 => 'پرسش‌های کم‌عمق و تشریفاتی؛ پذیرش جواب آماده',
					3 => 'پرسش‌های باز، چالشی و ساختارمند برای حل‌مسئله',
					4 => 'فرهنگ پرسش‌گری واگرا و ایده‌سازی در همه سطوح',
				),
				'recommend'  => array(
					'title'   => 'ساخت فرهنگ پرسش‌گری و ذهنیت واگرا',
					'action'  => 'راه‌اندازی "میز ۵ چرا" و "هیئت مخالف" در جلسات تصمیم؛ پرسش‌های جایگزین به‌جای دستور',
					'kpi'     => 'افزایش تعداد راهکارهای پیشنهادی هر تیم',
					'owner'   => 'سرپرستان + واحد بهبود فرآیند',
					'tool'    => 'پرسش‌های GROW + تکنیک ۵ چرا + Appreciative Inquiry',
				),
			),
			'feedback'             => array(
				'slug'       => 'feedback',
				'label'      => 'سازوکار بازخورد مؤثر',
				'short'      => 'بازخورد',
				'icon'       => '↔',
				'weight'     => 1.5,
				'indicator'  => 'وجود چرخه منظم بازخورد، سازنده و دوسویه',
				'levels'     => array(
					1 => 'بازخورد فقط در بحران و به‌صورت تنبیهی؛ بدون ساختار',
					2 => 'بازخورد نادر، کلامی و ذهنی؛ بدون پیگیری',
					3 => 'بازخورد منظم، ساختارمند و مبتنی بر شواهد (SBI)',
					4 => 'حلقه بازخورد پیوسته، امن و چندسطحی در سازمان',
				),
				'recommend'  => array(
					'title'   => 'استقرار سیستم بازخورد ساختارمند',
					'action'  => 'آموزش و اجرای فرمول SBI (موقعیت، رفتار، اثر) و جلسه بازخورد ۱:۱ هفتگی',
					'kpi'     => 'انجام ۹۰٪ جلسات بازخورد برنامه‌ریزی‌شده',
					'owner'   => 'سرپرستان + منابع انسانی',
					'tool'    => 'قالب SBI + کانال بازخورد ناشناس',
				),
			),
			'performance_eval'     => array(
				'slug'       => 'performance_eval',
				'label'      => 'ارزیابی عملکرد عادلانه و داده‌محور',
				'short'      => 'ارزیابی عملکرد',
				'icon'       => '◈',
				'weight'     => 1.3,
				'indicator'  => 'شفافیت، داده‌محوری و عدالت در ارزیابی عملکرد',
				'levels'     => array(
					1 => 'نمره‌دهی سلیقه‌ای، بدون داده و بدون بازخورد',
					2 => 'ارزیابی سالانه تشریفاتی؛ نادیده‌گرفتن شایستگی',
					3 => 'ارزیابی دوره‌ای با OKR/KPI و کالیبراسیون',
					4 => 'ارزیابی پیوسته، خودکار، منصفانه و توسعه‌محور',
				),
				'recommend'  => array(
					'title'   => 'به‌روزرسانی سیستم ارزیابی به سنجه‌های شفاف',
					'action'  => 'طراحی OKR و KPI واحدها، داشبورد داده‌محور و کمیته کالیبراسیون',
					'kpi'     => 'کاهش ۵۰٪ شکایات از عدم عدالت ارزیابی',
					'owner'   => 'مدیر منابع انسانی',
					'tool'    => '۴-۳-۱ ارزیابی + ماتریس شایستگی سطح‌محور',
				),
			),
			'psychological_safety' => array(
				'slug'       => 'psychological_safety',
				'label'      => 'امنیت روانی و فضای امن کار',
				'short'      => 'امنیت روانی',
				'icon'       => '♥',
				'weight'     => 1.4,
				'indicator'  => 'احساس امنیت برای اعلام خطا، پرسش و مخالفت',
				'levels'     => array(
					1 => 'ترس از خطا، تمسخر و تنبیه؛ پنهان‌کاری مشکلات',
					2 => 'سکوت در جلسات؛ پذیرش ظاهری به‌جای گفت‌وگو',
					3 => 'اعلام خطا و پرسش بدون ترس؛ مدیریت بر پایه اعتماد',
					4 => 'امنیت روانی بالا؛ تجربه‌گری، خطای یادگیرنده و انصاف',
				),
				'recommend'  => array(
					'title'   => 'ساخت محیط کار امن و رشد‌یافته',
					'action'  => 'اعلام صریح مدیریت: "خطای گزارش‌شده تنبیه ندارد"؛ جلسات بدون سرزنش و تقدیر از اعلام خطا',
					'kpi'     => 'افزایش نمره امنیت روانی تا ۳.۵',
					'owner'   => 'مدیران ارشد + HR',
					'tool'    => 'پیام‌های امنیت روانی + جلسه پس از رویداد',
				),
			),
			'learning_culture'     => array(
				'slug'       => 'learning_culture',
				'label'      => 'فرهنگ یادگیری و بهبود مستمر',
				'short'      => 'یادگیری',
				'icon'       => '✎',
				'weight'     => 1.2,
				'indicator'  => 'یادگیری از تجربه، خطا و آموزش مستمر سازمان',
				'levels'     => array(
					1 => 'آموزش فقط اجباری؛ بدون تسهیم دانش',
					2 => 'آموزش پراکنده؛ دانش در سیلوها',
					3 => 'یادگیری تجربه‌محور و تسهیم دانش در پروژه‌ها',
					4 => 'سازمان یادگیرنده با بازآموزی پیوسته و جامعه علمی',
				),
				'recommend'  => array(
					'title'   => 'طراحی چرخه یادگیری عملیاتی',
					'action'  => 'جلسات بازنگری پس از پروژه (After Action Review) و بانک درس‌آموخته‌ها',
					'kpi'     => 'استفاده حداقل ۲ درس‌آموخته در هر پروژه',
					'owner'   => 'واحد آموزش و توسعه',
					'tool'    => 'AAR + بانک دانش + مربی‌گری همتا',
				),
			),
			'coaching_culture'     => array(
				'slug'       => 'coaching_culture',
				'label'      => 'نقش سرپرستان به مربیان عملکردی',
				'short'      => 'فرهنگ مربیگری',
				'icon'       => '✦',
				'weight'     => 1.4,
				'indicator'  => 'میزان مربی‌گری سرپرستان به جای دستوردهی',
				'levels'     => array(
					1 => 'سرپرست = ناظر دستورده؛ بدون مهارت مربیگری',
					2 => 'سرپرست مشاور جزئی؛ مربیگری تصادفی و کم‌اثر',
					3 => 'سرپرست مربی عملکردی با پرسش، بازخورد و رشد',
					4 => 'سازمان مربی‌محور با مربی‌گری همتا و ارشد',
				),
				'recommend'  => array(
					'title'   => 'ارتقای نقش سرپرستان به مربیان عملکردی',
					'action'  => 'برنامه ۹۰ روزه تربیت سرپرست به مربی؛ آموزش GROW، SBI، گوش فعال و پرسش‌گری + جلسه ۱:۱ هفتگی',
					'kpi'     => 'افزایش مهارت مربیگری سرپرستان به ۳.۵',
					'owner'   => 'مدیر توسعه سازمانی',
					'tool'    => 'کتابخانه سؤالات GROW + روتین هفتگی سرپرست-مربی',
				),
			),
		);
	}

	/**
	 * Wave / maturity model.
	 *
	 * @return array
	 */
	public static function waves() {
		return array(
			1 => array(
				'title' => 'موج یکم — سازمان سنتی',
				'short' => 'سنتی',
				'color' => '#dc2626',
				'bg'    => 'rgba(220,38,38,.16)',
				'desc'  => 'ساختار دستوری، متمرکز و مقاوم به تغییر؛ اعتماد پایین و پرسش‌گری محدود.',
			),
			2 => array(
				'title' => 'موج دوم — سازمان بوروکراتیک',
				'short' => 'بوروکراتیک',
				'color' => '#d97706',
				'bg'    => 'rgba(217,119,6,.16)',
				'desc'  => 'منطبق با وضعیت موجود سازمان: قوانین زیاد، شنیدن فعال کم، بازخورد ناکافی و ارزیابی ذهنی.',
			),
			3 => array(
				'title' => 'موج سوم — سازمان هم‌آفرین و شبکه‌ای',
				'short' => 'هم‌آفرین',
				'color' => '#0d9488',
				'bg'    => 'rgba(13,148,136,.16)',
				'desc'  => 'سرپرستان به مربی عملکردی تبدیل شده‌اند؛ پرسش‌گری، بازخورد و تیم‌های چندتخصصی فعال است.',
			),
			4 => array(
				'title' => 'موج چهارم — سازمان یادگیرنده',
				'short' => 'یادگیرنده',
				'color' => '#2563eb',
				'bg'    => 'rgba(37,99,235,.16)',
				'desc'  => 'ساختار خودتنظیم، امنیت روانی بالا و یادگیری پیوسته؛ سازمان خودش را توسعه می‌دهد.',
			),
		);
	}

	/**
	 * Wave from numeric score.
	 *
	 * @param float $score Average score.
	 * @return int
	 */
	public static function wave_from_score( $score ) {
		$score = (float) $score;
		if ( $score <= 1.75 ) {
			return 1;
		}
		if ( $score <= 2.75 ) {
			return 2;
		}
		if ( $score <= 3.45 ) {
			return 3;
		}
		return 4;
	}

	/**
	 * Target threshold score for a given wave.
	 *
	 * @param int $wave Wave number.
	 * @return float
	 */
	public static function target_threshold( $wave ) {
		$map = array( 1 => 1.5, 2 => 2.5, 3 => 3.35, 4 => 3.75 );
		return isset( $map[ $wave ] ) ? $map[ $wave ] : 3.35;
	}

	/**
	 * EFQM criteria mapping.
	 *
	 * Uses the classic EFQM Excellence criteria: 5 enablers + 4 results.
	 *
	 * @return array
	 */
	public static function efqm_criteria() {
		return array(
			'leadership' => array(
				'key'   => 'leadership',
				'label' => 'رهبری و حکمرانی',
				'group' => 'enabler',
				'weight' => 1.2,
				'dims'  => array( 'centralization', 'complexity', 'coaching_culture' ),
				'action' => 'استقرار هیئت رهبری توسعه‌محور: منشور اختیار تصمیم، جلسات منظم رهبران با سرپرستان و بازنگری سه‌ماهه اقدامات مربی‌گری.',
			),
			'strategy' => array(
				'key'   => 'strategy',
				'label' => 'راهبرد و برنامه‌ریزی',
				'group' => 'enabler',
				'weight' => 1.0,
				'dims'  => array( 'formalization', 'centralization', 'performance_eval' ),
				'action' => 'ترجمه اهداف به OKR واحدها، پیوند داده‌های ارزیابی سازمانی با نقشه استراتژیک و داشبورد ماهانه مدیران.',
			),
			'people' => array(
				'key'   => 'people',
				'label' => 'منابع انسانی و فرهنگ',
				'group' => 'enabler',
				'weight' => 1.4,
				'dims'  => array( 'active_listening', 'feedback', 'coaching_culture', 'psychological_safety', 'learning_culture' ),
				'action' => 'برنامه ۹۰ روزه «سرپرست به مربی عملکردی»، جلسات بازخورد SBI و ۱:۱ هفتگی، امنیت روانی و یادگیری از خطا.',
			),
			'resources' => array(
				'key'   => 'resources',
				'label' => 'شراکت‌ها و منابع',
				'group' => 'enabler',
				'weight' => 1.0,
				'dims'  => array( 'complexity', 'formalization' ),
				'action' => 'ساده‌سازی منابع و فرایندها، استقرار بانک دانش و شراکت‌های شبکه‌ای برای کاهش سیلوها.',
			),
			'processes' => array(
				'key'   => 'processes',
				'label' => 'فرایندها و محصولات',
				'group' => 'enabler',
				'weight' => 1.1,
				'dims'  => array( 'formalization', 'complexity', 'performance_eval' ),
				'action' => 'طراحی «حداقل قوانین قابل اعتماد»، جریان ارزش ساده‌شده و سنجه‌های فرایندی داده‌محور.',
			),
			'customer_results' => array(
				'key'   => 'customer_results',
				'label' => 'نتایج مشتریان',
				'group' => 'result',
				'weight' => 1.0,
				'dims'  => array( 'feedback', 'complexity' ),
				'action' => 'پایش رضایت ذی‌نفعان داخلی و مشتریان، حلقه بازخورد مشتری و اقدام اصلاحی مستند.',
			),
			'people_results' => array(
				'key'   => 'people_results',
				'label' => 'نتایج کارکنان',
				'group' => 'result',
				'weight' => 1.3,
				'dims'  => array( 'psychological_safety', 'active_listening', 'learning_culture' ),
				'action' => 'سنجش منظم رضایت، به‌زیستی، امنیت روانی و نرخ رشد شایستگی کارکنان.',
			),
			'society_results' => array(
				'key'   => 'society_results',
				'label' => 'نتایج جامعه و پایداری',
				'group' => 'result',
				'weight' => 1.1,
				'dims'  => array( 'learning_culture', 'psychological_safety' ),
				'action' => 'تعریف شاخص‌های ESG، سلامت و ایمنی، تاب‌آوری و کاهش ریسک‌های محیطی در حوزه انرژی.',
			),
			'key_results' => array(
				'key'   => 'key_results',
				'label' => 'نتایج کلیدی عملکرد',
				'group' => 'result',
				'weight' => 1.2,
				'dims'  => array( 'performance_eval', 'feedback', 'formalization' ),
				'action' => 'داشبورد KPI، ارزیابی کالیبره، استقرار چرخه PDCA و بهبود مستمر نتایج مالی و عملیاتی.',
			),
		);
	}

	/**
	 * EFQM level label.
	 *
	 * @param float $score Overall 1-4 score.
	 * @return string
	 */
	public static function efqm_level( $score ) {
		if ( $score < 2 ) {
			return 'مرحله آغاز / واکنشی';
		}
		if ( $score < 2.5 ) {
			return 'مرحله رشد / مدیریت‌شده';
		}
		if ( $score < 3 ) {
			return 'مرحله نوظهور / تطبیقی';
		}
		if ( $score < 3.5 ) {
			return 'مرحله بلوغ / تعالی';
		}
		return 'مرحله پیشرو / تعالی پایدار';
	}

	/**
	 * Build EFQM dashboard data from a dimension score map.
	 *
	 * @param array $score_map slug => 1-4 score.
	 * @return array
	 */
	public static function efqm_data( $score_map ) {
		$criteria = self::efqm_criteria();
		$out      = array();
		$enb_scores = array();
		$res_scores = array();

		foreach ( $criteria as $key => $criterion ) {
			$scores = array();
			foreach ( $criterion['dims'] as $slug ) {
				$scores[] = isset( $score_map[ $slug ] ) ? (float) $score_map[ $slug ] : 1.0;
			}
			$avg = $scores ? round( array_sum( $scores ) / count( $scores ), 2 ) : 1.0;
			$out[] = array(
				'key'      => $key,
				'label'    => $criterion['label'],
				'group'    => $criterion['group'],
				'score'    => $avg,
				'points'   => round( $avg / 4 * 1000 ),
				'action'   => $criterion['action'],
				'priority' => $avg < 2.4 ? 'اولویت یکم' : ( $avg < 2.9 ? 'اولویت دوم' : 'تثبیت و بهبود' ),
			);
			if ( 'enabler' === $criterion['group'] ) {
				$enb_scores[] = $avg;
			} else {
				$res_scores[] = $avg;
			}
		}

		$overall_score = (float) self::weighted_average( $score_map );
		$enablers      = $enb_scores ? round( array_sum( $enb_scores ) / count( $enb_scores ), 2 ) : 1.0;
		$results       = $res_scores ? round( array_sum( $res_scores ) / count( $res_scores ), 2 ) : 1.0;

		return array(
			'score'       => round( $overall_score / 4 * 1000 ),
			'level'       => self::efqm_level( $overall_score ),
			'enablers'    => $enablers,
			'results'     => $results,
			'criteria'    => $out,
		);
	}

	/**
	 * Build the textual/directional analysis for managers.
	 *
	 * @param array $score_map slug => score.
	 * @param array $summary  dashboard summary.
	 * @param array $efqm     efqm data.
	 * @return array
	 */
	public static function analysis_data( $score_map, $summary, $efqm ) {
		$dims        = self::dimensions();
		$strengths   = array();
		$weaknesses  = array();

		foreach ( $dims as $slug => $dim ) {
			$score = isset( $score_map[ $slug ] ) ? (float) $score_map[ $slug ] : 1.0;
			if ( $score >= 3 ) {
				$strengths[] = array(
					'label'  => $dim['label'],
					'score'  => $score,
					'text'   => 'نمره ' . round( $score, 1 ) . ' از ۴؛ می‌تواند به‌عنوان الگوی داخلی برای سایر واحدها و ابعاد استفاده شود.',
				);
			}
			if ( $score <= 2.75 ) {
				$weaknesses[] = array(
					'label'  => $dim['label'],
					'score'  => $score,
					'text'   => 'شاخص اثرگذار بر موج سازمانی است و با راهبرد مربی‌گری سرپرستان در اولویت قرار می‌گیرد.',
				);
			}
		}

		usort( $weaknesses, function ( $a, $b ) {
			return $a['score'] <=> $b['score'];
		} );

		$wave_title  = $summary['wave_label'];
		$target_wave = $summary['target_wave'];
		$gap_text    = $summary['target_gap'];

		$summary_text = 'بر اساس ارزیابی ثبت‌شده، سازمان در ' . $wave_title . ' قرار دارد و ' . $gap_text . ' نمره تا آستانه موج هدف فاصله دارد. '
			. 'ضعیف‌ترین شاخص‌ها عمدتاً به فرهنگ مربی‌گری، بازخورد، ارزیابی داده‌محور و پرسش‌گری مربوط است؛ بنابراین راهبرد «ارتقای سرپرستان به مربیان عملکردی» به‌همراه اصلاح ساختار رسمیت، تمرکز و پیچیدگی، منطبق با کرایتریای منابع انسانی و فرایندها در EFQM توصیه می‌شود.';

		$efqm_roadmap = array(
			array(
				'letter' => 'R',
				'title'  => 'نتایج (Results)',
				'action' => 'تعریف و پایش KPIهای توسعه سازمانی: امتیاز کل، موج هدف، نمره بازخورد، امنیت روانی، رضایت کارکنان و شاخص‌های ESG.',
				'owner'  => 'مدیرعامل + مدیر توسعه سازمانی',
			),
			array(
				'letter' => 'A',
				'title'  => 'رویکرد (Approach)',
				'action' => 'طراحی رویکرد مبتنی بر مدل EFQM: سنجش شاخص‌ها در ۵ بعد توانمندساز و ۴ بعد نتایج، با تأکید بر منابع انسانی و فرایندها.',
				'owner'  => 'کمیته تعالی سازمانی',
			),
			array(
				'letter' => 'D',
				'title'  => 'استقرار (Deployment)',
				'action' => 'استقرار در همه واحدها: جلسات ۱:۱، بازخورد SBI، تیم‌های چندتخصصی و داشبورد OKR در عملیات، HSE، پشتیبانی و منابع انسانی.',
				'owner'  => 'سرپرستان + مدیران واحدها',
			),
			array(
				'letter' => 'A',
				'title'  => 'ارزیابی (Assessment)',
				'action' => 'ارزیابی دوره‌ای ۹۰ روزه، تحلیل شکاف و مقایسه با دوره قبل؛ در صورت انحراف، بازنگری فوری برنامه.',
				'owner'  => 'مدیران میانی + واحد بهبود',
			),
			array(
				'letter' => 'R',
				'title'  => 'بهبود (Refinement)',
				'action' => 'یادگیری از نتایج، به‌روزرسانی نقشه راه و جابه‌جایی اولویت‌ها به سمت موج سوم و سپس موج چهارم/پنجم.',
				'owner'  => 'هیئت توسعه سازمانی',
			),
		);

		return array(
			'summary'       => $summary_text,
			'wave_title'    => $wave_title,
			'strengths'     => array_slice( $strengths, 0, 4 ),
			'weaknesses'    => array_slice( $weaknesses, 0, 6 ),
			'efqm_roadmap'  => $efqm_roadmap,
			'efqm_level'    => $efqm['level'],
			'efqm_score'    => $efqm['score'],
		);
	}

	/**
	 * Normalize a raw group accumulator (["scores" => [slug=>score...], "sum", "n"])
	 * into a per-group dimension score map and a weighted overall score.
	 *
	 * @param array $group Group accumulator.
	 * @return array
	 */
	public static function normalize_group( $group ) {
		$dimensions = self::dimensions();
		$map        = array();
		foreach ( $dimensions as $slug => $dim ) {
			$sum      = isset( $group['dim_sum'][ $slug ] ) ? (float) $group['dim_sum'][ $slug ] : 0;
			$n        = isset( $group['dim_n'][ $slug ] ) ? (int) $group['dim_n'][ $slug ] : 0;
			$map[ $slug ] = $n > 0 ? round( $sum / $n, 2 ) : 1.0;
		}
		$overall   = self::weighted_average( $map );
		$count     = isset( $group['n'] ) ? (int) $group['n'] : 0;
		$avg_sum   = isset( $group['sum'] ) ? (float) $group['sum'] : 0;
		$avg       = $count > 0 ? round( $avg_sum / $count, 2 ) : 0;

		return array(
			'scores'  => $map,
			'overall' => $avg,
			'weighted' => $overall,
			'count'   => $count,
		);
	}

	/**
	 * Weight normalized dimension scores.
	 *
	 * @return array slug => weight
	 */
	public static function weights() {
		$weights = array();
		foreach ( self::dimensions() as $dim ) {
			$weights[ $dim['slug'] ] = (float) $dim['weight'];
		}
		return $weights;
	}

	/**
	 * Compute weighted average from array of scores.
	 *
	 * @param array $scores slug => number.
	 * @return float
	 */
	public static function weighted_average( $scores ) {
		$weights = self::weights();
		$total   = 0.0;
		$sum_w   = 0.0;
		foreach ( $weights as $slug => $w ) {
			if ( isset( $scores[ $slug ] ) && is_numeric( $scores[ $slug ] ) ) {
				$score = max( 1, min( 4, (float) $scores[ $slug ] ) );
				$total += $score * $w;
				$sum_w += $w;
			}
		}
		if ( $sum_w <= 0 ) {
			return 0.0;
		}
		return round( $total / $sum_w, 2 );
	}

	/**
	 * Get config (organisation meta).
	 *
	 * @return array
	 */
	public static function config() {
		return array(
			'org'         => get_option( 'cr_od_org_name', 'شرکت توسعه انرژی و نفت' ),
			'industry'    => get_option( 'cr_od_industry', 'انرژی، نفت و گاز' ),
			'target_wave' => absint( get_option( 'cr_od_target_wave', 3 ) ),
		);
	}

	/**
	 * Build dashboard dataset from stored responses.
	 *
	 * @param int|null $cycle_id Specific cycle id. Null = latest cycle.
	 * @return array
	 */
	public static function dashboard_data( $cycle_id = null ) {
		global $wpdb;
		$table_responses = Coachroom_OD_DB::table( 'responses' );
		$table_cycles    = Coachroom_OD_DB::table( 'cycles' );

		$dimensions = self::dimensions();
		$waves      = self::waves();

		// Determine latest cycle.
		$selected_cycle = null;
		if ( empty( $cycle_id ) ) {
			$selected_cycle = $wpdb->get_row( "SELECT * FROM {$table_cycles} ORDER BY id DESC LIMIT 1" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		} else {
			$selected_cycle = $wpdb->get_row(
				$wpdb->prepare( "SELECT * FROM {$table_cycles} WHERE id = %d", $cycle_id ) // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			);
		}

		$cycle_id_for_query = $selected_cycle ? (int) $selected_cycle->id : 0;

		if ( $cycle_id_for_query ) {
			$rows = $wpdb->get_results(
				$wpdb->prepare( "SELECT * FROM {$table_responses} WHERE cycle_id = %d", $cycle_id_for_query ) // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			);
		} else {
			$rows = $wpdb->get_results( "SELECT * FROM {$table_responses} ORDER BY id DESC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		$scores = array();
		foreach ( $dimensions as $slug => $dim ) {
			// Use an array accumulator so scalar-vs-array access is never a PHP error.
			$scores[ $slug ] = array(
				'sum' => 0.0,
				'n'   => 0,
			);
		}

		$by_department = array();
		$by_role       = array();
		$count_rows    = 0;
		$last_date     = '';
		$last_department = '';
		$last_role       = '';
		$last_slug       = '';

		foreach ( $rows as $row ) {
			if ( ! isset( $dimensions[ $row->dimension ] ) ) {
				continue;
			}
			$slug        = $row->dimension;
			$score       = max( 1, min( 4, (float) $row->score ) );
			$dept_key    = $row->department ? $row->department : 'نامشخص';
			$role_key    = $row->assessor_role ? $row->assessor_role : 'کارمند';
			$count_rows ++;

			$scores[ $slug ]['sum'] += $score;
			$scores[ $slug ]['n']++;

			if ( ! isset( $by_department[ $dept_key ] ) ) {
				$by_department[ $dept_key ] = array(
					'name'    => $dept_key,
					'scores'  => array(),
					'dim_sum' => array(),
					'dim_n'   => array(),
					'sum'     => 0,
					'n'       => 0,
				);
			}
			if ( ! isset( $by_department[ $dept_key ]['dim_sum'][ $slug ] ) ) {
				$by_department[ $dept_key ]['dim_sum'][ $slug ] = 0.0;
				$by_department[ $dept_key ]['dim_n'][ $slug ]   = 0;
			}
			$by_department[ $dept_key ]['dim_sum'][ $slug ] += $score;
			$by_department[ $dept_key ]['dim_n'][ $slug ]++;
			$by_department[ $dept_key ]['sum']             += $score;
			$by_department[ $dept_key ]['n']++;

			if ( ! isset( $by_role[ $role_key ] ) ) {
				$by_role[ $role_key ] = array(
					'name'    => $role_key,
					'scores'  => array(),
					'dim_sum' => array(),
					'dim_n'   => array(),
					'sum'     => 0,
					'n'       => 0,
				);
			}
			if ( ! isset( $by_role[ $role_key ]['dim_sum'][ $slug ] ) ) {
				$by_role[ $role_key ]['dim_sum'][ $slug ] = 0.0;
				$by_role[ $role_key ]['dim_n'][ $slug ]   = 0;
			}
			$by_role[ $role_key ]['dim_sum'][ $slug ] += $score;
			$by_role[ $role_key ]['dim_n'][ $slug ]++;
			$by_role[ $role_key ]['sum']             += $score;
			$by_role[ $role_key ]['n']++;

			if ( ! empty( $row->created_at ) ) {
				$last_date     = $row->created_at;
				$last_department = $dept_key;
				$last_role       = $role_key;
				$last_slug       = $slug;
			}
		}

		// Normalize dimensions.
		$dim_data = array();
		$score_map = array();
		foreach ( $dimensions as $slug => $dim ) {
			$avg = 1.0;
			if ( isset( $scores[ $slug ]['n'] ) && $scores[ $slug ]['n'] > 0 ) {
				$avg = round( $scores[ $slug ]['sum'] / $scores[ $slug ]['n'], 2 );
			}
			$score_map[ $slug ] = $avg;
			$dim_data[] = array(
				'slug'  => $slug,
				'label' => $dim['label'],
				'short' => $dim['short'],
				'score' => $avg,
				'icon'  => $dim['icon'],
			);
		}

		$overall = self::weighted_average( $score_map );
		$wave    = self::wave_from_score( $overall );

		$departments = array();
		foreach ( $by_department as $name => $dept ) {
			$group   = self::normalize_group( $dept );
			$dept_avg = $group['overall'];
			$departments[] = array(
				'name'    => $dept['name'],
				'overall' => $dept_avg,
				'weighted' => $group['weighted'],
				'wave'    => self::wave_from_score( $dept_avg ),
				'scores'  => $group['scores'],
				'count'   => $group['count'],
			);
		}
		usort( $departments, function ( $a, $b ) {
			return $a['overall'] <=> $b['overall'];
		} );

		$roles = array();
		foreach ( $by_role as $name => $role ) {
			$group   = self::normalize_group( $role );
			$role_avg = $group['overall'];
			$roles[] = array(
				'name'    => $role['name'],
				'overall' => $role_avg,
				'weighted' => $group['weighted'],
				'wave'    => self::wave_from_score( $role_avg ),
				'scores'  => $group['scores'],
				'count'   => $role['count'],
			);
		}
		usort( $roles, function ( $a, $b ) {
			return $a['overall'] <=> $b['overall'];
		} );

		// Trend across cycles.
		$trend = array();
		$all_cycles = $wpdb->get_results( "SELECT * FROM {$table_cycles} ORDER BY id ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		foreach ( $all_cycles as $cyc ) {
			$cycle_rows = $wpdb->get_results(
				$wpdb->prepare( "SELECT dimension, score FROM {$table_responses} WHERE cycle_id = %d", $cyc->id ) // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			);
			$sum_scores = array();
			foreach ( $dimensions as $slug => $dim ) {
				$sum_scores[ $slug ] = 0;
			}
			$counts = array_fill_keys( array_keys( $dimensions ), 0 );
			foreach ( $cycle_rows as $r ) {
				if ( isset( $sum_scores[ $r->dimension ] ) ) {
					$sum_scores[ $r->dimension ] += (float) $r->score;
					$counts[ $r->dimension ]++;
				}
			}
			$avg_map = array();
			foreach ( $dimensions as $slug => $dim ) {
				$avg_map[ $slug ] = $counts[ $slug ] > 0 ? round( $sum_scores[ $slug ] / $counts[ $slug ], 2 ) : 1.0;
			}
			$trend[] = array(
				'label'   => $cyc->title,
				'overall' => self::weighted_average( $avg_map ),
				'wave'    => self::wave_from_score( self::weighted_average( $avg_map ) ),
			);
		}

		// Recommendations based on gaps to target wave.
		$target_wave = absint( get_option( 'cr_od_target_wave', 3 ) );
		$target_score = array( 1 => 1.5, 2 => 2.5, 3 => 3.35, 4 => 3.75 );
		$threshold    = Coachroom_OD_Helpers::target_threshold( $target_wave );

		$priority = array();
		foreach ( $dimensions as $slug => $dim ) {
			$score = isset( $score_map[ $slug ] ) ? (float) $score_map[ $slug ] : 1.0;
			$priority[] = $slug;
		}
		usort( $priority, function ( $a, $b ) use ( $score_map ) {
			return $score_map[ $a ] <=> $score_map[ $b ];
		} );

		$recommendations = array();
		foreach ( array_slice( $priority, 0, 6 ) as $slug ) {
			$dim  = $dimensions[ $slug ];
			$score = isset( $score_map[ $slug ] ) ? (float) $score_map[ $slug ] : 1.0;
			$gap  = max( 0, round( $threshold - $score, 2 ) );
			if ( $gap <= 0.45 ) {
				continue;
			}
			$recommendations[] = array(
				'slug'    => $slug,
				'label'   => $dim['label'],
				'score'   => $score,
				'gap'     => $gap,
				'level'   => $score < 2.2 ? 'اولویت یکم' : ( $score < 2.75 ? 'اولویت دوم' : 'پایش و تثبیت' ),
				'action'  => $dim['recommend']['action'],
				'title'   => $dim['recommend']['title'],
				'kpi'     => $dim['recommend']['kpi'],
				'owner'   => $dim['recommend']['owner'],
				'tool'    => $dim['recommend']['tool'],
			);
		}

		$summary = array(
			'overall'        => $overall,
			'wave'           => $wave,
			'wave_label'     => $waves[ $wave ]['title'],
			'wave_color'     => $waves[ $wave ]['color'],
			'wave_desc'      => $waves[ $wave ]['desc'],
			'responses'      => $count_rows,
			'target_wave'    => $target_wave,
			'target_gap'     => isset( $target_score[ $target_wave ] ) ? max( 0, round( $target_score[ $target_wave ] - $overall, 2 ) ) : max( 0, round( $threshold - $overall, 2 ) ),
			'cycle_title'    => $selected_cycle ? $selected_cycle->title : 'بدون دوره فعال',
			'last_updated'   => $last_date,
			'last_department'=> $last_department,
			'last_role'      => $last_role,
			'last_dimension' => $last_slug,
			'roles_count'    => count( $roles ),
			'departments_count' => count( $departments ),
		);

		$efqm    = self::efqm_data( $score_map );
		$analysis = self::analysis_data( $score_map, $summary, $efqm );

		return array(
			'config'         => self::config(),
			'dimensions'     => $dim_data,
			'departments'    => $departments,
			'roles'          => $roles,
			'trend'          => $trend,
			'summary'        => $summary,
			'recommendations' => $recommendations,
			'efqm'           => $efqm,
			'analysis'       => $analysis,
		);
	}

	/**
	 * Round to Persian locale (uses Western digits internally; JS converts display).
	 *
	 * @param float $number Number.
	 * @return float
	 */
	public static function num( $number ) {
		return (float) $number;
	}

	/**
	 * Sanitize dimensions slug.
	 *
	 * @param string $slug Slug.
	 * @return string
	 */
	public static function sanitize_slug( $slug ) {
		return preg_replace( '/[^a-z0-9_]/', '', strtolower( (string) $slug ) );
	}
}
