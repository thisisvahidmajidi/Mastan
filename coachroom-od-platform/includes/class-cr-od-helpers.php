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
	 * Expanded assessment question bank.
	 *
	 * Three targeted questions per dimension improves signal quality. Every question
	 * maps to one of the ten dimensions; answers are aggregated so role/unit profiles
	 * stay comparable while the detail is preserved for analysis.
	 *
	 * @return array
	 */
	public static function questions() {
		return array(
			array( 'key' => 'formalization_q1', 'dimension' => 'formalization', 'label' => 'آیا رویه‌های سازمان کوتاه، روشن و قابل اجرا هستند؟', 'weight' => 1.0 ),
			array( 'key' => 'formalization_q2', 'dimension' => 'formalization', 'label' => 'آیا تغییر یک رویه به‌سرعت و بدون توقف طولانی انجام می‌شود؟', 'weight' => 1.0 ),
			array( 'key' => 'formalization_q3', 'dimension' => 'formalization', 'label' => 'آیا قوانین به کارکنان فضای استقلال کافی در چارچوب اصول می‌دهند؟', 'weight' => 1.0 ),

			array( 'key' => 'centralization_q1', 'dimension' => 'centralization', 'label' => 'تصمیم‌های عملیاتی عمدتاً در کدام سطح گرفته می‌شوند؟', 'weight' => 1.2 ),
			array( 'key' => 'centralization_q2', 'dimension' => 'centralization', 'label' => 'سرپرستان چقدر اختیار تصمیم‌گیری بدون ارجاع به بالا دارند؟', 'weight' => 1.2 ),
			array( 'key' => 'centralization_q3', 'dimension' => 'centralization', 'label' => 'میزان ارجاع کارهای روزمره به مدیران عالی چقدر است؟', 'weight' => 1.2 ),

			array( 'key' => 'complexity_q1', 'dimension' => 'complexity', 'label' => 'برای انجام یک کار ساده چند لایه/واحد باید درگیر شود؟', 'weight' => 1.0 ),
			array( 'key' => 'complexity_q2', 'dimension' => 'complexity', 'label' => 'هماهنگی بین‌واحدی چقدر روان و کم‌هزینه است؟', 'weight' => 1.0 ),
			array( 'key' => 'complexity_q3', 'dimension' => 'complexity', 'label' => 'سیلوهای سازمانی چقدر مانع اشتراک اطلاعات می‌شوند؟', 'weight' => 1.0 ),

			array( 'key' => 'active_listening_q1', 'dimension' => 'active_listening', 'label' => 'در جلسات تیمی، حرف اعضا قطع می‌شود؟', 'weight' => 1.4 ),
			array( 'key' => 'active_listening_q2', 'dimension' => 'active_listening', 'label' => 'پیش از پاسخ دادن، پیام طرف مقابل خلاصه و تأیید می‌شود؟', 'weight' => 1.4 ),
			array( 'key' => 'active_listening_q3', 'dimension' => 'active_listening', 'label' => 'کارکنان احساس می‌کنند مدیرشان واقعاً آن‌ها را می‌شنود؟', 'weight' => 1.4 ),

			array( 'key' => 'questioning_q1', 'dimension' => 'questioning', 'label' => 'در تصمیم‌گیری‌ها به‌جای جواب آماده، سؤال باز مطرح می‌شود؟', 'weight' => 1.3 ),
			array( 'key' => 'questioning_q2', 'dimension' => 'questioning', 'label' => 'ایده‌های جایگزین و راه‌های متفاوت به‌راحتی مطرح می‌شوند؟', 'weight' => 1.3 ),
			array( 'key' => 'questioning_q3', 'dimension' => 'questioning', 'label' => '«چرا» و «چه می‌شد اگر» بخشی از فرهنگ سازمان است؟', 'weight' => 1.3 ),

			array( 'key' => 'feedback_q1', 'dimension' => 'feedback', 'label' => 'بازخورد عملکرد به‌صورت منظم (نه فقط پایان سال) داده می‌شود؟', 'weight' => 1.5 ),
			array( 'key' => 'feedback_q2', 'dimension' => 'feedback', 'label' => 'بازخورد مبتنی بر شواهد است و از قضاوت شخصی پرهیز می‌شود؟', 'weight' => 1.5 ),
			array( 'key' => 'feedback_q3', 'dimension' => 'feedback', 'label' => 'کارکنان می‌توانند به مدیر بازخورد دوسویه و امن بدهند؟', 'weight' => 1.5 ),

			array( 'key' => 'performance_eval_q1', 'dimension' => 'performance_eval', 'label' => 'ارزیابی عملکرد بر اساس داده، شاخص و شواهد است؟', 'weight' => 1.3 ),
			array( 'key' => 'performance_eval_q2', 'dimension' => 'performance_eval', 'label' => 'معیارهای ارزیابی برای همه شفاف و از پیش اعلام‌شده است؟', 'weight' => 1.3 ),
			array( 'key' => 'performance_eval_q3', 'dimension' => 'performance_eval', 'label' => 'نتیجه ارزیابی به توسعه فردی، جبران منصفانه و مربی‌گری وصل می‌شود؟', 'weight' => 1.3 ),

			array( 'key' => 'psychological_safety_q1', 'dimension' => 'psychological_safety', 'label' => 'اعلام خطا بدون ترس از تنبیه یا تمسخر ممکن است؟', 'weight' => 1.4 ),
			array( 'key' => 'psychological_safety_q2', 'dimension' => 'psychological_safety', 'label' => 'مخالفت محترمانه با نظر مدیر در جلسات پذیرفته می‌شود؟', 'weight' => 1.4 ),
			array( 'key' => 'psychological_safety_q3', 'dimension' => 'psychological_safety', 'label' => 'کارکنان به‌جای سکوت، نگرانی‌های خود را مطرح می‌کنند؟', 'weight' => 1.4 ),

			array( 'key' => 'learning_culture_q1', 'dimension' => 'learning_culture', 'label' => 'پس از پروژه‌ها، درس‌آموخته‌ها جمع‌آوری و مستند می‌شود؟', 'weight' => 1.2 ),
			array( 'key' => 'learning_culture_q2', 'dimension' => 'learning_culture', 'label' => 'دانش و تجربه بین واحدها به‌راحتی جریان دارد؟', 'weight' => 1.2 ),
			array( 'key' => 'learning_culture_q3', 'dimension' => 'learning_culture', 'label' => 'یادگیری بخشی از کار روزمره است، نه یک دوره اجباری؟', 'weight' => 1.2 ),

			array( 'key' => 'coaching_culture_q1', 'dimension' => 'coaching_culture', 'label' => 'سرپرستان به‌جای دستور، سؤال مربیگری می‌پرسند؟', 'weight' => 1.4 ),
			array( 'key' => 'coaching_culture_q2', 'dimension' => 'coaching_culture', 'label' => 'جلسه ۱:۱ منظم بین سرپرست و اعضا برگزار می‌شود؟', 'weight' => 1.4 ),
			array( 'key' => 'coaching_culture_q3', 'dimension' => 'coaching_culture', 'label' => 'سرپرستان مهارت گوش فعال، پرسش‌گری و بازخورد مؤثر دارند؟', 'weight' => 1.4 ),
		);
	}

	/**
	 * Question level descriptor (same four-point scale for every question).
	 *
	 * @return array
	 */
	public static function question_options() {
		return array(
			1 => 'وضعیت ضعیف / بوروکراتیک',
			2 => 'در حال بهبود',
			3 => 'مناسب / هم‌آفرین',
			4 => 'پیشرو / یادگیرنده',
		);
	}

	/**
	 * Weisbord Six-Box diagnosis model (Weisbord, 1976).
	 *
	 * The six boxes form a separate diagnostic lens from the ten maturity
	 * dimensions. Each box answers a key diagnostic question and connects back
	 * to the EFQM criteria so management can compare the formal and informal
	 * structure at the same time.
	 *
	 * @return array
	 */
	public static function weisbord_boxes() {
		return array(
			'weisbord_goals' => array(
				'slug'        => 'weisbord_goals',
				'label'       => 'اهداف (Goals)',
				'short'       => 'اهداف',
				'icon'        => '◎',
				'weight'      => 1.2,
				'key_question'=> 'آیا اهداف سازمان شفاف، مشترک و قابل‌فهم‌اند؟',
				'likely'      => 'اهداف مبهم، متناقض و صرفاً بالادستی؛ در عین حال جلسات ماهانه ندارند.',
				'findings'    => 'برای تشخیص، شفافیت هدف در جلسات، هم‌راستایی اهداف واحدها و درک مشترک سنجه‌ها بررسی می‌شود.',
				'efqm'        => 'راهبرد و برنامه‌ریزی + نتایج کلیدی',
				'strategy'    => 'feedback_performance',
			),
			'weisbord_structure' => array(
				'slug'        => 'weisbord_structure',
				'label'       => 'ساختار (Structure)',
				'short'       => 'ساختار',
				'icon'        => '⬢',
				'weight'      => 1.1,
				'key_question'=> 'آیا ساختار با اهداف همخوانی دارد؟',
				'likely'      => 'سلسله‌مراتب صلب، سیلوهای سازمانی و بروکراسی ناکارآمد.',
				'findings'    => 'لایه‌های تصمیم، رسمیت، تمرکز و هماهنگی بین‌واحدی به‌عنوان علائم ساختاری بررسی می‌شوند.',
				'efqm'        => 'رهبری و حکمرانی + فرایندها',
				'strategy'    => 'structure_simplification',
			),
			'weisbord_relationships' => array(
				'slug'        => 'weisbord_relationships',
				'label'       => 'روابط (Relationships)',
				'short'       => 'روابط',
				'icon'        => '↔',
				'weight'      => 1.3,
				'key_question'=> 'کیفیت تعاملات بین واحدها چگونه است؟',
				'likely'      => 'رقابت مخرب، بی‌اعتمادی و ارتباطات یک‌طرفه.',
				'findings'    => 'گوش دادن فعال، بازخورد دوسویه، امنیت روانی و حل‌مسئله مشترک بین‌واحدی بررسی می‌شوند.',
				'efqm'        => 'منابع انسانی و فرهنگ + نتایج کارکنان',
				'strategy'    => 'network_innovation',
			),
			'weisbord_rewards' => array(
				'slug'        => 'weisbord_rewards',
				'label'       => 'پاداش (Rewards)',
				'short'       => 'پاداش',
				'icon'        => '◆',
				'weight'      => 1.2,
				'key_question'=> 'آیا سیستم انگیزشی با عملکرد واقعی مرتبط است؟',
				'likely'      => 'پاداش مبتنی بر سابقه و وفاداری، نه شایستگی و عملکرد.',
				'findings'    => 'پیوند ارزیابی با پاداش، شفافیت معیارها و عادلانه‌بودن نظام پاداش بررسی می‌شوند.',
				'efqm'        => 'نتایج کارکنان + نتایج کلیدی',
				'strategy'    => 'feedback_performance',
			),
			'weisbord_leadership' => array(
				'slug'        => 'weisbord_leadership',
				'label'       => 'رهبری (Leadership)',
				'short'       => 'رهبری',
				'icon'        => '♛',
				'weight'      => 1.4,
				'key_question'=> 'آیا رهبران تعادل بین جعبه‌ها را حفظ می‌کنند؟',
				'likely'      => 'مدیریت بحران‌محور، واکنشی و فاقد چشم‌انداز توسعه‌ای.',
				'findings'    => 'تعادل رهبری بین اهداف، ساختار، روابط، پاداش و فرایندهای کمکی بررسی می‌شود.',
				'efqm'        => 'رهبری و حکمرانی',
				'strategy'    => 'safety_learning',
			),
			'weisbord_helping' => array(
				'slug'        => 'weisbord_helping',
				'label'       => 'مکانیسم‌های کمکی (Helping Mechanisms)',
				'short'       => 'مکانیسم‌های کمکی',
				'icon'        => '❖',
				'weight'      => 1.1,
				'key_question'=> 'آیا فرایندها، فناوری و سیستم‌های اطلاعاتی کارآمدند؟',
				'likely'      => 'سیستم‌های اطلاعاتی جزیره‌ای و داده‌های غیرقابل‌اتکا.',
				'findings'    => 'داده‌محوری، ابزارها، فرآیندهای پشتیبان و کیفیت اطلاعات برای تصمیم‌گیری بررسی می‌شوند.',
				'efqm'        => 'شراکت‌ها و منابع + فرایندها',
				'strategy'    => 'learning_sustainability',
			),
		);
	}

	/**
	 * Weisbord diagnostic questions (3 per box = 18).
	 *
	 * @return array
	 */
	public static function weisbord_questions() {
		return array(
			array( 'key' => 'weisbord_goals_q1', 'dimension' => 'weisbord_goals', 'label' => 'اهداف سازمان برای همه واحدها شفاف و قابل‌فهم است؟', 'weight' => 1.2 ),
			array( 'key' => 'weisbord_goals_q2', 'dimension' => 'weisbord_goals', 'label' => 'اهداف واحدها با اهداف شرکت هم‌راستا هستند؟', 'weight' => 1.2 ),
			array( 'key' => 'weisbord_goals_q3', 'dimension' => 'weisbord_goals', 'label' => 'پیشرفت نسبت به اهداف به‌صورت منظم و با سنجه مشخص بررسی می‌شود؟', 'weight' => 1.2 ),

			array( 'key' => 'weisbord_structure_q1', 'dimension' => 'weisbord_structure', 'label' => 'ساختار سازمان برای هدف‌ها و نوع کار مناسب است؟', 'weight' => 1.1 ),
			array( 'key' => 'weisbord_structure_q2', 'dimension' => 'weisbord_structure', 'label' => 'هماهنگی بین واحدها با کمترین ارجاع اضافی انجام می‌شود؟', 'weight' => 1.1 ),
			array( 'key' => 'weisbord_structure_q3', 'dimension' => 'weisbord_structure', 'label' => 'لایه‌های تصمیم‌گیری به‌اندازه ضروری محدود شده‌اند؟', 'weight' => 1.1 ),

			array( 'key' => 'weisbord_relationships_q1', 'dimension' => 'weisbord_relationships', 'label' => 'واحدها به‌جای رقابت مخرب با هم همکاری می‌کنند؟', 'weight' => 1.3 ),
			array( 'key' => 'weisbord_relationships_q2', 'dimension' => 'weisbord_relationships', 'label' => 'ارتباطات بین واحدها دوسویه، امن و بدون ترس است؟', 'weight' => 1.3 ),
			array( 'key' => 'weisbord_relationships_q3', 'dimension' => 'weisbord_relationships', 'label' => 'اختلاف‌ها به‌صورت باز و با حل مسئله مشترک مدیریت می‌شوند؟', 'weight' => 1.3 ),

			array( 'key' => 'weisbord_rewards_q1', 'dimension' => 'weisbord_rewards', 'label' => 'پاداش و تشویق بر اساس عملکرد واقعی و شایستگی است؟', 'weight' => 1.2 ),
			array( 'key' => 'weisbord_rewards_q2', 'dimension' => 'weisbord_rewards', 'label' => 'معیارهای پاداش از قبل شفاف و برای همه یکسان است؟', 'weight' => 1.2 ),
			array( 'key' => 'weisbord_rewards_q3', 'dimension' => 'weisbord_rewards', 'label' => 'رفتارهای همکاری، یادگیری و توسعه نیز پاداش می‌گیرند؟', 'weight' => 1.2 ),

			array( 'key' => 'weisbord_leadership_q1', 'dimension' => 'weisbord_leadership', 'label' => 'رهبران بین اهداف، ساختار، روابط و پاداش تعادل برقرار می‌کنند؟', 'weight' => 1.4 ),
			array( 'key' => 'weisbord_leadership_q2', 'dimension' => 'weisbord_leadership', 'label' => 'مدیریت به‌جای واکنش به بحران، چشم‌انداز توسعه‌ای دارد؟', 'weight' => 1.4 ),
			array( 'key' => 'weisbord_leadership_q3', 'dimension' => 'weisbord_leadership', 'label' => 'رهبران الگوی رفتار یادگیری، بازخورد و هم‌آفرینی هستند؟', 'weight' => 1.4 ),

			array( 'key' => 'weisbord_helping_q1', 'dimension' => 'weisbord_helping', 'label' => 'فرایندهای پشتیبان و ابزارها کار را آسان می‌کنند؟', 'weight' => 1.1 ),
			array( 'key' => 'weisbord_helping_q2', 'dimension' => 'weisbord_helping', 'label' => 'داده‌ها و گزارش‌ها برای تصمیم‌گیری قابل‌اتکا و یکپارچه‌اند؟', 'weight' => 1.1 ),
			array( 'key' => 'weisbord_helping_q3', 'dimension' => 'weisbord_helping', 'label' => 'سیستم اطلاعاتی از مدیریت پروفایل واحدها و نقش‌ها پشتیبانی می‌کند؟', 'weight' => 1.1 ),
		);
	}

	/**
	 * Aggregate Weisbord box scores into a diagnostic result.
	 *
	 * @param array $score_map box slug => 1-4 score.
	 * @return array
	 */
	public static function weisbord_data( $score_map ) {
		$boxes  = self::weisbord_boxes();
		$result = array();
		$sum_w  = 0.0;
		$sum_sc = 0.0;

		foreach ( $boxes as $slug => $box ) {
			$score = isset( $score_map[ $slug ] ) ? (float) $score_map[ $slug ] : 1.0;
			if ( $score < 2.2 ) {
				$status = 'نیازمند مداخله فوری';
				$color  = '#b91c1c';
			} elseif ( $score < 2.75 ) {
				$status = 'شکننده / در حال شفاف‌سازی';
				$color  = '#d97706';
			} elseif ( $score < 3.35 ) {
				$status = 'در حال بهبود';
				$color  = '#2563eb';
			} else {
				$status = 'مطلوب و پایدار';
				$color  = '#0f766e';
			}
			$result[ $slug ] = array(
				'slug'         => $slug,
				'label'        => $box['label'],
				'short'        => $box['short'],
				'icon'         => $box['icon'],
				'score'        => $score,
				'status'       => $status,
				'color'        => $color,
				'key_question' => $box['key_question'],
				'likely'       => $box['likely'],
				'findings'     => $box['findings'],
				'efqm'         => $box['efqm'],
				'strategy'     => $box['strategy'],
			);
			$sum_w  += (float) $box['weight'];
			$sum_sc += $score * (float) $box['weight'];
		}

		$overall = $sum_w > 0 ? round( $sum_sc / $sum_w, 2 ) : 2.0;

		$low = array();
		foreach ( $result as $item ) {
			if ( $item['score'] < 2.75 ) {
				$low[] = $item;
			}
		}
		usort( $low, function ( $a, $b ) {
			return $a['score'] <=> $b['score'];
		} );

		$diagnosis = 'بر اساس مدل شش‌جعبه‌ای وایزبورد، شش بعد کلیدی سازمان به‌صورت جداگانه تشخیص داده شدند. ';
		if ( $low ) {
			$diagnosis .= 'ضعیف‌ترین جعبه‌ها: ' . implode( '، ', array_map( function ( $item ) {
				return $item['short'] . ' (' . round( $item['score'], 1 ) . ' از ۴)';
			}, array_slice( $low, 0, 3 ) ) ) . '. ';
		} else {
			$diagnosis .= 'هیچ جعبه‌ای در محدوده بحرانی نیست و تمرکز اصلی بر تثبیت و ارتقای مستمر است. ';
		}
		$diagnosis .= 'این مدل در کنار EFQM باعث می‌شود ساختار رسمی و غیررسمی به‌صورت هم‌زمان دیده شوند.';

		return array(
			'overall'   => $overall,
			'boxes'     => $result,
			'low'       => array_slice( $low, 0, 3 ),
			'diagnosis' => $diagnosis,
			'level'     => $overall < 2.2 ? 'ضعیف/بوروکراتیک' : ( $overall < 2.75 ? 'شکننده' : ( $overall < 3.35 ? 'در حال بهبود' : 'مطلوب' ) ),
		);
	}

	/**
	 * Content-validity mapping: each dimension and box is anchored to a credible
	 * source/model so the assessment is transparent for managers.
	 *
	 * @return array
	 */
	public static function validity_sources() {
		return array(
			'formalization'        => array( 'model' => 'ساختار سازمانی مینتزبرگ', 'source' => 'Mintzberg, H. (1979). The Structuring of Organizations.' ),
			'centralization'       => array( 'model' => 'ساختار سازمانی مینتزبرگ', 'source' => 'Mintzberg, H. (1979).' ),
			'complexity'           => array( 'model' => 'ساختار ارگانیک/مکانیکی', 'source' => 'Burns, T. & Stalker, G. M. (1961). The Management of Innovation.' ),
			'active_listening'     => array( 'model' => 'مهارت‌های گفت‌وگو و مربی‌گری', 'source' => 'Rogers, C. R. & Farson, R. E. (1957). Active Listening.' ),
			'questioning'          => array( 'model' => 'پرسش‌گری و سازمان یادگیرنده', 'source' => 'Senge, P. M. (1990). The Fifth Discipline.' ),
			'feedback'             => array( 'model' => 'بازخورد رفتاری', 'source' => 'Center for Creative Leadership; SBI model.' ),
			'performance_eval'     => array( 'model' => 'مدیریت عملکرد و OKR', 'source' => 'Doerr, J. (2018). Measure What Matters.' ),
			'psychological_safety' => array( 'model' => 'امنیت روانی تیم', 'source' => 'Edmondson, A. C. (1999). Psychological Safety and Learning Behavior in Work Teams.' ),
			'learning_culture'     => array( 'model' => 'سازمان یادگیرنده', 'source' => 'Senge, P. M. (1990). The Fifth Discipline.' ),
			'coaching_culture'     => array( 'model' => 'مربی‌گری عملکردی', 'source' => 'Whitmore, J. (2009). Coaching for Performance (GROW).' ),
			'weisbord_goals'       => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976). Organizational Diagnosis.' ),
			'weisbord_structure'   => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'weisbord_relationships' => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'weisbord_rewards'     => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'weisbord_leadership'  => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'weisbord_helping'     => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
		);
	}

	/**
	 * Multi-perspective diagnostic matrix.
	 *
	 * @param array $score_map  Dimension scores.
	 * @param array $weisbord   Weisbord diagnostic.
	 * @param array $efqm       EFQM data.
	 * @param array $strategy   Adaptive strategy data.
	 * @return array
	 */
	public static function model_matrix( $score_map, $weisbord, $efqm, $strategy ) {
		$wave       = self::wave_from_score( self::weighted_average( $score_map ) );
		$waves      = self::waves();
		$strategies = self::strategies();

		$matrix = array(
			array(
				'key'      => 'maturity',
				'title'    => 'موج بلوغ سازمانی',
				'color'    => isset( $waves[ $wave ]['color'] ) ? $waves[ $wave ]['color'] : '#0d9488',
				'diagnosis'=> 'سازمان در "' . $waves[ $wave ]['title'] . '" قرار دارد؛ داده‌های ۳۰ سؤالی ابعاد ساختاری و فرهنگی در کنار ۱۸ سؤال تشخیصی شش‌جعبه وایزبورد وضعیت فعلی را نشان می‌دهند.',
				'strategies' => array(),
				'note'     => 'این مدل مسیر حرکت به موج بعدی را تعیین می‌کند.',
			),
			array(
				'key'      => 'efqm',
				'title'    => 'مدل تعالی EFQM',
				'color'    => '#2563eb',
				'diagnosis'=> 'امتیاز تعالی ' . ( isset( $efqm['score'] ) ? $efqm['score'] : 0 ) . ' از ۱۰۰۰ با سطح "' . ( isset( $efqm['level'] ) ? $efqm['level'] : '—' ) . '" است.',
				'strategies' => array(),
				'note'     => 'نگاه سیستم‌اتیک به توانمندسازها و نتایج؛ مبنای RADAR.',
			),
			array(
				'key'      => 'weisbord',
				'title'    => 'شش‌جعبه وایزبورد',
				'color'    => '#7c3aed',
				'diagnosis'=> ( isset( $weisbord['diagnosis'] ) ? $weisbord['diagnosis'] : 'در انتظار داده کافی.' ),
				'strategies' => array(),
				'note'     => 'تشخیص ساختار رسمی و غیررسمی هم‌زمان؛ مناسب سازمان‌های سلسله‌مراتبی.',
			),
		);

		foreach ( $matrix as $idx => $row ) {
			$titles = array();
			if ( 'maturity' === $row['key'] ) {
				$titles = array_map( function ( $st ) { return $st['title']; }, (array) $strategy['selected'] );
			} elseif ( 'efqm' === $row['key'] ) {
				$titles = array( 'راهبردهای تطبیقی بر اساس معیارهای ضعیف EFQM' );
			} elseif ( 'weisbord' === $row['key'] && isset( $weisbord['low'] ) ) {
				$titles = array();
				foreach ( $weisbord['low'] as $box ) {
					if ( isset( $strategies[ $box['strategy'] ] ) ) {
						$titles[] = $strategies[ $box['strategy'] ]['title'];
					}
				}
			}
			$matrix[ $idx ]['strategies'] = array_slice( array_values( array_unique( array_filter( $titles ) ) ), 0, 3 );
		}

		return array(
			'matrix'  => $matrix,
			'methods' => array(
				'موج بلوغ سازمانی' => $waves[ $wave ]['short'],
				'EFQM'          => ( isset( $efqm['level'] ) ? $efqm['level'] : '—' ),
				'Weisbord'      => ( isset( $weisbord['level'] ) ? $weisbord['level'] : '—' ),
			),
		);
	}

	/**
	 * Cronbach's alpha reliability estimate for the assessment scales.
	 *
	 * Items are the question keys; respondents are the combination of cycle,
	 * department, role and user. Returns null when there are not enough usable
	 * respondents so the platform can show "needs more data" instead of a
	 * misleading number.
	 *
	 * @param array $rows Database response rows.
	 * @return array
	 */
	public static function reliability_data( $rows ) {
		$instances = array();
		foreach ( $rows as $row ) {
			$key = $row->cycle_id . '|' . $row->user_id . '|' . $row->department . '|' . $row->assessor_role;
			if ( ! isset( $instances[ $key ] ) ) {
				$instances[ $key ] = array();
			}
			$instances[ $key ][ $row->question_key ] = max( 1, min( 4, (float) $row->score ) );
		}

		$dimensions   = self::dimensions();
		$weisbord     = self::weisbord_boxes();
		$questions    = self::questions();
		$wquestions   = self::weisbord_questions();

		$scope_map = array(
			'overall' => array(
				'label' => 'کل ارزیابی (۴۸ سؤال)',
				'items' => array_merge(
					array_values( array_unique( wp_list_pluck( $questions, 'key' ) ) ),
					array_values( array_unique( wp_list_pluck( $wquestions, 'key' ) ) )
				),
			),
			'maturity' => array(
				'label' => 'ابعاد بلوغ (۱۰ بُعد)',
				'items' => array_values( array_unique( wp_list_pluck( $questions, 'key' ) ) ),
			),
			'weisbord' => array(
				'label' => 'شش جعبه وایزبورد (۱۸ سؤال)',
				'items' => array_values( array_unique( wp_list_pluck( $wquestions, 'key' ) ) ),
			),
		);

		$result = array( 'scales' => array(), 'sample_n' => 0, 'valid' => false, 'method' => 'Cronbach alpha' );
		foreach ( $scope_map as $scope => $meta ) {
			$usable = array();
			foreach ( $instances as $key => $scores ) {
				$ok = true;
				foreach ( $meta['items'] as $item ) {
					if ( ! isset( $scores[ $item ] ) ) {
						$ok = false;
						break;
					}
				}
				if ( $ok ) {
					$usable[ $key ] = $scores;
				}
			}

			$n = count( $usable );
			$alpha = null;
			$label = 'داده کافی نیست؛ حداقل ۳ پاسخ‌دهنده کامل لازم است.';
			if ( $n >= 3 ) {
				$k = count( $meta['items'] );
				$item_vars = array();
				$totals    = array();
				foreach ( $meta['items'] as $item ) {
					$vals = array();
					foreach ( $usable as $scores ) {
						$vals[] = (float) $scores[ $item ];
					}
					$mean = array_sum( $vals ) / $n;
					$var  = 0.0;
					foreach ( $vals as $v ) {
						$var += ( $v - $mean ) * ( $v - $mean );
					}
					$item_vars[ $item ] = $var / $n;
				}
				foreach ( $usable as $scores ) {
					$total = 0;
					foreach ( $meta['items'] as $item ) {
						$total += (float) $scores[ $item ];
					}
					$totals[] = $total;
				}
				$tmean = array_sum( $totals ) / $n;
				$tvar  = 0.0;
				foreach ( $totals as $t ) {
					$tvar += ( $t - $tmean ) * ( $t - $tmean );
				}
				$tvar = $tvar / $n;
				$sum_item_vars = array_sum( $item_vars );
				if ( $k > 1 && $tvar > 0 ) {
					$alpha = round( ( $k / ( $k - 1 ) ) * ( 1 - ( $sum_item_vars / $tvar ) ), 3 );
				}
				$label = 'پایایی معنا می‌کند: ' . ( $alpha >= 0.7 ? 'قابل قبول/خوب' : ( $alpha !== null && $alpha >= 0.6 ? 'قابل قبول مشروط' : 'نیازمند بررسی' ) ) . ' (' . round( $alpha, 2 ) . ')';
			}

			$result['scales'][ $scope ] = array(
				'label'  => $meta['label'],
				'n'      => $n,
				'alpha'  => $alpha,
				'note'   => $label,
			);
		}
		$result['sample_n'] = max( array_column( $result['scales'], 'n' ) );
		$result['valid']    = $result['sample_n'] >= 3;
		return $result;
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
	 * OKR definitions connected to organizational dimensions.
	 *
	 * Lower-scored dimensions cascade into OKRs so the roadmap is evidence-based.
	 *
	 * @return array
	 */
	public static function okr_catalog() {
		return array(
			'active_listening' => array(
				'slug'     => 'active_listening',
				'objective' => 'تقویت شنیدن فعال در جلسات تیمی و واحدها',
				'krs'      => array(
					'حداقل ۸۰٪ سرپرستان در جلسات از تکنیک خلاصه‌سازی و پرسش بدون قضاوت استفاده کنند.',
					'نمره «مورد شنیده شدن» در نظرسنجی ماهانه به ۳.۵ از ۴ برسد.',
				),
			),
			'questioning' => array(
				'slug'     => 'questioning',
				'objective' => 'توسعه ذهنیت پرسش‌گری و حل‌مسئله در تیم‌ها',
				'krs'      => array(
					'راه‌اندازی «میز ۵ چرا» و «جلسه هیئت مخالف» در حداقل ۲ جلسه تصمیم هر ماه.',
					'افزایش ۳۰٪ تعداد راهکارهای پیشنهادی در تیم‌های چندتخصصی.',
				),
			),
			'feedback' => array(
				'slug'     => 'feedback',
				'objective' => 'ایجاد چرخه بازخورد منظم و سازنده',
				'krs'      => array(
					'اجرای ۹۰٪ جلسات بازخورد ۱:۱ هفتگی بر اساس فرمت SBI.',
					'کاهش ۵۰٪ موارد تأخیر در پاسخ به بازخورد ثبت‌شده.',
				),
			),
			'performance_eval' => array(
				'slug'     => 'performance_eval',
				'objective' => 'استقرار ارزیابی عملکرد عادلانه و داده‌محور',
				'krs'      => array(
					'تعریف ۱۰۰٪ شاخص‌های OKR/KPI واحدها در داشبورد داده‌محور.',
					'برگزاری کمیته کالیبراسیون برای همه نمرات سه‌ماهه.',
				),
			),
			'coaching_culture' => array(
				'slug'     => 'coaching_culture',
				'objective' => 'ارتقای نقش سرپرستان به مربیان عملکردی',
				'krs'      => array(
					'افزایش نمره مهارت مربیگری سرپرستان به ۳.۵ از ۴.',
					'برگزاری ۶ جلسه مربیگری/کارگاه در سه‌ماهه اول.',
				),
			),
			'centralization' => array(
				'slug'     => 'centralization',
				'objective' => 'واگذاری تصمیم‌گیری عملیاتی به واحدها و سرپرستان',
				'krs'      => array(
					'شفاف‌سازی منشور اختیار تصمیم برای ۸۵٪ تصمیمات روتین.',
					'کاهش ۴۰٪ ارجاع به مدیران عالی.',
				),
			),
			'formalization' => array(
				'slug'     => 'formalization',
				'objective' => 'ساده‌سازی قوانین به «حداقل قوانین قابل اعتماد»',
				'krs'      => array(
					'بازنگری ۵ رویه کلیدی و کاهش مستندات دستوری.',
					'کاهش ۳۰٪ زمان تصویب تغییرات.',
				),
			),
			'complexity' => array(
				'slug'     => 'complexity',
				'objective' => 'کاهش سیلوها و پیچیدگی هماهنگی بین‌واحدی',
				'krs'      => array(
					'تشکیل ۳ تیم چندتخصصی عملیاتی.',
					'کاهش ۲۵٪ زمان هماهنگی بین‌واحدی.',
				),
			),
			'psychological_safety' => array(
				'slug'     => 'psychological_safety',
				'objective' => 'ایجاد محیط کار امن و حمایت‌کننده',
				'krs'      => array(
					'نمره امنیت روانی به ۳.۵ از ۴ برسد.',
					'کاهش ۵۰٪ ترس از اعلام خطا در گزارش‌های داخلی.',
				),
			),
			'learning_culture' => array(
				'slug'     => 'learning_culture',
				'objective' => 'ایجاد فرهنگ یادگیری و بهبود مستمر',
				'krs'      => array(
					'اجرای جلسه AAR در ۱۰۰٪ پروژه‌های اصلی.',
					'استفاده از حداقل ۲ درس‌آموخته در هر پروژه.',
				),
			),
		);
	}

	/**
	 * Build OKR data from actual dimension scores.
	 *
	 * @param array $score_map slug => 1-4 score.
	 * @return array
	 */
	public static function okr_data( $score_map ) {
		$catalog  = self::okr_catalog();
		$items    = array();
		$target_wave = absint( get_option( 'cr_od_target_wave', 3 ) );
		$threshold   = self::target_threshold( $target_wave );

		foreach ( $catalog as $slug => $okr ) {
			$score = isset( $score_map[ $slug ] ) ? (float) $score_map[ $slug ] : 1.0;
			$gap   = max( 0, round( $threshold - $score, 2 ) );
			if ( $gap <= 0.45 ) {
				continue;
			}
			$items[] = array(
				'slug'      => $slug,
				'objective' => $okr['objective'],
				'krs'       => $okr['krs'],
				'score'     => $score,
				'gap'       => $gap,
				'priority'  => $score < 2.2 ? 'O1 — اولویت فوری' : ( $score < 2.75 ? 'O2 — اولویت مهم' : 'O3 — تثبیت' ),
				'owner'     => 'سرپرستان/مدیران مرتبط',
			);
		}

		usort( $items, function ( $a, $b ) {
			return $a['score'] <=> $b['score'];
		} );

		return array_slice( $items, 0, 5 );
	}

	/**
	 * Align OKR recommendations with unit and role scores for systemic planning.
	 *
	 * @param array $score_map slug => score.
	 * @param array $departments Department groups.
	 * @param array $roles       Role groups.
	 * @return array
	 */
	public static function okr_systemic( $score_map, $departments, $roles, $strategy = array() ) {
		$okrs = self::okr_data( $score_map );

		// The coaching-oriented OKR is only included when the maturity data supports it.
		// If the organization is not ready yet, coaching is deferred to a later cycle.
		if ( ! empty( $strategy ) && empty( $strategy['coaching_recommended'] ) ) {
			$okrs = array_values( array_filter( $okrs, function ( $item ) {
				return 'coaching_culture' !== $item['slug'];
			} ) );
		}

		$top_dept  = array();
		$top_role  = array();

		if ( $departments ) {
			usort( $departments, function ( $a, $b ) {
				return $a['overall'] <=> $b['overall'];
			} );
			$top_dept = array(
				'name'    => $departments[0]['name'],
				'overall' => $departments[0]['overall'],
			);
		}
		if ( $roles ) {
			usort( $roles, function ( $a, $b ) {
				return $a['overall'] <=> $b['overall'];
			} );
			$top_role = array(
				'name'    => $roles[0]['name'],
				'overall' => $roles[0]['overall'],
			);
		}

		return array(
			'items'    => $okrs,
			'focus_unit' => $top_dept,
			'focus_role' => $top_role,
			'cycle'    => '۹۰ روزه',
		);
	}

	/**
	 * Strategy catalog for contingency-based organizational development.
	 *
	 * The supervisor-to-coach strategy is intentionally NOT forced. It is selected
	 * only when the assessed maturity profile (safety, listening and structure gates)
	 * shows the organization is ready for coaching-led change.
	 *
	 * @return array
	 */
	public static function strategies() {
		return array(
			'safety_learning' => array(
				'code'        => 'safety_learning',
				'title'       => 'ایجاد امنیت روانی و یادگیری از خطا',
				'phase'       => 'پیش‌نیاز',
				'gate'        => 'safety',
				'why'         => 'وقتی ترس از خطا و سرزنش زیاد است، هیچ برنامه مربی‌گری یا بازخوردی نمی‌تواند واقعاً اجرا شود.',
				'actions'     => array(
					'اعلام صریح مدیریت: «خطای گزارش‌شده تنبیه ندارد»',
					'برگزاری جلسات بدون سرزنش و تشویق به مطرح‌کردن نگرانی',
					'استقرار جلسه AAR و بانک درس‌آموخته در پروژه‌ها',
				),
				'kpi'     => 'افزایش نمره امنیت روانی و نرخ گزارش خطا',
				'owner'   => 'مدیران ارشد + HR + واحد بهبود',
			),
			'structure_simplification' => array(
				'code'        => 'structure_simplification',
				'title'       => 'ساده‌سازی ساختار و واگذاری تصمیم',
				'phase'       => 'درمان ساختاری',
				'gate'        => 'structure',
				'why'         => 'وقتی رسمیت، تمرکز یا پیچیدگی ضعیف است، ابتدا باید ساختار سبک‌تر شود تا تغییرات رفتاری ممکن شوند.',
				'actions'     => array(
					'بازنگری رویه‌ها به «حداقل قوانین قابل اعتماد»',
					'منشور اختیار تصمیم برای سرپرستان و ماتریس RACI',
					'شروع تیم‌های چندتخصصی برای شکستن سیلوها',
				),
				'kpi'     => 'کاهش زمان تصویب تغییرات و ارجاع‌ها',
				'owner'   => 'معاونت توسعه سازمانی + مدیران واحدها',
			),
			'feedback_performance' => array(
				'code'        => 'feedback_performance',
				'title'       => 'استقرار بازخورد و ارزیابی داده‌محور',
				'phase'       => 'سنجش و شفافیت',
				'gate'        => 'performance',
				'why'         => 'بدون بازخورد منظم و سنجش شفاف، نمی‌توان بهبود را اندازه گرفت یا به افراد کمک کرد.',
				'actions'     => array(
					'اجرای فرمت بازخورد SBI و جلسات ۱:۱ منظم',
					'تعریف OKR/KPI و داشبورد عملکرد',
					'ایجاد کمیته کالیبراسیون برای عدالت ارزیابی',
				),
				'kpi'     => 'نرخ اجرای جلسات بازخورد و شفافیت سنجه‌ها',
				'owner'   => 'HR + مدیران میانی + واحد بهبود',
			),
			'network_innovation' => array(
				'code'        => 'network_innovation',
				'title'       => 'شبکه‌سازی و تیم‌های وابسته‌به‌هم',
				'phase'       => 'ساختار هم‌آفرین',
				'gate'        => 'network',
				'why'         => 'وقتی پیچیدگی و سیلوها مانع همکاری است اما بازخورد و امنیت نسبی وجود دارد، شبکه‌سازی مؤثرترین اقدام است.',
				'actions'     => array(
					'تشکیل تیم‌های چندتخصصی پروژه‌محور',
					'رابط‌های شبکه‌ای به‌جای ارجاع سلسله‌مراتبی',
					'جلسات مشترک حل‌مسئله واحدهای عملیاتی و پشتیبانی',
				),
				'kpi'     => 'کاهش زمان هماهنگی بین‌واحدی',
				'owner'   => 'مدیران پروژه + برنامه‌ریزی',
			),
			'coaching_supervisors' => array(
				'code'        => 'coaching_supervisors',
				'title'       => 'ارتقای نقش سرپرستان به مربیان عملکردی',
				'phase'       => 'مربیگری (در صورت آمادگی)',
				'gate'        => 'coaching',
				'why'         => 'این راهبرد زمانی انتخاب می‌شود که امنیت روانی، شنیدن فعال و ساختار به اندازه کافی آماده باشند؛ در غیر این صورت صرفاً یک اقدام زودهنگام و پرریسک خواهد بود.',
				'actions'     => array(
					'برنامه ۹۰ روزه تربیت سرپرست به مربی',
					'جلسه ۱:۱ هفتگی با پرسش‌های GROW و بازخورد SBI',
					'سنجش ماهانه مهارت مربیگری و تعدیل برنامه',
				),
				'kpi'     => 'افزایش نمره مهارت مربیگری سرپرستان',
				'owner'   => 'مدیر توسعه سازمانی + سرپرستان',
			),
			'learning_sustainability' => array(
				'code'        => 'learning_sustainability',
				'title'       => 'یادگیری مستمر و توسعه پایدار (ESG)',
				'phase'       => 'تعالی بلندمدت',
				'gate'        => 'sustainability',
				'why'         => 'وقتی شاخص‌های پایه بهبود یابند، سازمان می‌تواند به یادگیری پیوسته، به‌زیستی و شاخص‌های پایداری بپردازد.',
				'actions'     => array(
					'جریان‌سازی دانش و AAR در همه پروژه‌ها',
					'تعیین شاخص‌های ESG، تاب‌آوری و به‌زیستی',
					'استقرار چرخه PDCA و جلسات یادگیری ربع‌سال',
				),
				'kpi'     => 'آمادگی برای موج چهارم/پنجم و شاخص‌های ESG',
				'owner'   => 'هیئت توسعه سازمانی + واحد پایداری',
			),
		);
	}

	/**
	 * Determine the appropriate strategy set based on the assessed maturity profile.
	 *
	 * @param array $score_map slug => score.
	 * @param array $departments Department rows.
	 * @param array $roles       Role rows.
	 * @return array
	 */
	public static function strategy_data( $score_map, $departments, $roles ) {
		$strategies = self::strategies();

		$g = function ( $key ) use ( $score_map ) {
			return isset( $score_map[ $key ] ) ? (float) $score_map[ $key ] : 1.0;
		};
		$avg = function ( $keys ) use ( $g ) {
			$s = 0;
			foreach ( $keys as $k ) {
				$s += $g( $k );
			}
			return round( $s / count( $keys ), 2 );
		};

		$safety_gate   = $g( 'psychological_safety' ) >= 2.5;
		$listening_gate = $avg( array( 'active_listening', 'questioning', 'feedback' ) ) >= 2.2;
		$structure_gate = $avg( array( 'formalization', 'centralization', 'complexity' ) ) >= 2.0;
		$performance_gate = $avg( array( 'feedback', 'performance_eval' ) ) >= 2.2;
		$network_gate   = $avg( array( 'complexity', 'centralization' ) ) >= 2.0 && $safety_gate;
		$coaching_gate  = $g( 'coaching_culture' ) <= 2.8 && $safety_gate && $listening_gate && $structure_gate;
		$sustainability_gate = $safety_gate && $performance_gate && $avg( array( 'learning_culture', 'performance_eval' ) ) >= 2.4;

		$selected = array();
		$phases   = array();

		if ( ! $safety_gate ) {
			$selected[] = $strategies['safety_learning'];
			$phases[]   = 'ph1';
		}
		if ( ! $structure_gate ) {
			$selected[] = $strategies['structure_simplification'];
			$phases[]   = 'ph2';
		} elseif ( $g( 'complexity' ) < 2.8 && $network_gate ) {
			$selected[] = $strategies['network_innovation'];
			$phases[]   = 'ph2b';
		}
		if ( ! $performance_gate ) {
			$selected[] = $strategies['feedback_performance'];
			$phases[]   = 'ph3';
		}
		if ( $coaching_gate ) {
			$selected[] = $strategies['coaching_supervisors'];
			$phases[]   = 'ph4';
		}
		if ( $sustainability_gate && count( $selected ) <= 3 ) {
			$selected[] = $strategies['learning_sustainability'];
			$phases[]   = 'ph5';
		}

		$coaching_recommended = $coaching_gate;
		$coaching_reason      = '';

		if ( ! $coaching_gate ) {
			if ( ! $safety_gate ) {
				$coaching_reason = 'امنیت روانی هنوز زیر آستانه ۲.۵ است؛ ابتدا راهبرد «ایمنی و یادگیری» اجرا شود.';
			} elseif ( ! $listening_gate ) {
				$coaching_reason = 'میانگین گوش دادن فعال، پرسش‌گری و بازخورد زیر آستانه ۲.۲ است؛ ابتدا زیرساخت گفت‌وگو و بازخورد تقویت شود.';
			} elseif ( ! $structure_gate ) {
				$coaching_reason = 'ساختار هنوز بسیار متمرکز/رسمی است؛ پیش از مربیگری، ساختار ساده و تصمیم‌ها واگذار شوند.';
			} else {
				$coaching_reason = 'نمره فرهنگ مربیگری به محدوده هدف نزدیک است؛ تمرکز فعلی بر تثبیت سایر شاخص‌ها و سپس ارزیابی دوره بعد قرار گیرد.';
			}
		} else {
			$coaching_reason = 'آمادگی سازمان برای مربی‌گری سرپرستان تأیید شد؛ این راهبرد در این شرایط مؤثرترین گام است.';
		}

		// Add readiness summary line.
		$maturity_text = $coaching_gate
			? 'سازمان در مرحله‌ای است که توسعه رفتاری (مربی‌گری) می‌تواند محور اصلی برنامه باشد.'
			: 'سازمان هنوز در شرایط پیش‌نیاز است؛ ابتدا باید شاخص‌های «امنیت روانی / ساختار / بازخورد» بهبود یابند تا مربی‌گری مؤثر واقع شود.';

		return array(
			'maturity_profile' => array(
				'safety_gate'      => $safety_gate,
				'listening_gate'   => $listening_gate,
				'structure_gate'   => $structure_gate,
				'performance_gate' => $performance_gate,
				'network_gate'     => $network_gate,
				'coaching_gate'    => $coaching_gate,
				'sustainability_gate' => $sustainability_gate,
				'safety_gate_score'   => $g( 'psychological_safety' ),
				'listening_gate_score'=> $avg( array( 'active_listening', 'questioning', 'feedback' ) ),
				'structure_gate_score'=> $avg( array( 'formalization', 'centralization', 'complexity' ) ),
				'performance_gate_score'=> $avg( array( 'feedback', 'performance_eval' ) ),
			),
			'selected' => array_slice( $selected, 0, 4 ),
			'phases'   => $phases,
			'maturity_text' => $maturity_text,
			'coaching_recommended' => $coaching_recommended,
			'coaching_reason'      => $coaching_reason,
			'by_model' => array(
				'weave'   => array_map( function ( $st ) { return $st['title']; }, array_slice( $selected, 0, 3 ) ),
				'efqm'    => array( 'تقویت معیارهای ضعیف توانمندساز و نتایج' ),
				'weisbord'=> array( 'شروع از ضعیف‌ترین جعبه شش‌گانه وایزبورد' ),
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
					'text'   => 'شاخص اثرگذار بر موج سازمانی است؛ اولویت آن بر اساس بلوغ فعلی در نقشه راه تطبیقی تعیین شده است.',
				);
			}
		}

		usort( $weaknesses, function ( $a, $b ) {
			return $a['score'] <=> $b['score'];
		} );

		$wave_title  = $summary['wave_label'];
		$target_wave = $summary['target_wave'];
		$gap_text    = $summary['target_gap'];

		$strategy = self::strategy_data( $score_map, array(), array() );
		$strategy_titles = array();
		foreach ( (array) $strategy['selected'] as $st ) {
			$strategy_titles[] = isset( $st['title'] ) ? $st['title'] : '';
		}
		$strategy_text = $strategy_titles ? implode( '؛ ', array_slice( $strategy_titles, 0, 3 ) ) : 'ارتقای مستمر شاخص‌ها';
		$coach_line = $strategy['coaching_recommended']
			? 'بر اساس آستانه‌های آمادگی (امنیت روانی، شنیدن فعال و ساختار)، راهبرد «ارتقای نقش سرپرستان به مربیان عملکردی» در این دوره مؤثر است.'
			: ( isset( $strategy['coaching_reason'] ) ? $strategy['coaching_reason'] : '' );

		$summary_text = 'بر اساس ارزیابی ۴۸ سؤالی ثبت‌شده (۳۰ سؤال بلوغ + ۱۸ سؤال تشخیص وایزبورد)، سازمان در ' . $wave_title . ' قرار دارد و ' . $gap_text . ' نمره تا آستانه موج هدف فاصله دارد. '
			. 'این نتیجه در کنار مدل تعالی EFQM و مدل شش‌جعبه‌ای وایزبورد بررسی شده است. '
			. 'راهبردهای متناسب با بلوغ فعلی: ' . $strategy_text . '. ' . $coach_line;

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

		// Weisbord box scores are stored alongside maturity dimensions under
		// question keys like weisbord_goals_q1. We aggregate them separately so
		// the formal structure (Mintzberg type) and informal diagnosis stay distinct.
		$weisbord_boxes = self::weisbord_boxes();
		$wq_to_box      = array();
		foreach ( self::weisbord_questions() as $wq ) {
			$wq_to_box[ $wq['key'] ] = $wq['dimension'];
		}
		$w_scores = array();
		foreach ( $weisbord_boxes as $slug => $box ) {
			$w_scores[ $slug ] = array(
				'sum' => 0.0,
				'n'   => 0,
			);
		}
		$weisbord_score_map = array();

		$by_department = array();
		$by_role       = array();
		$count_rows    = 0;
		$last_date     = '';
		$last_department = '';
		$last_role       = '';
		$last_slug       = '';

		foreach ( $rows as $row ) {
			$is_weisbord = isset( $wq_to_box[ $row->question_key ] );
			$score       = max( 1, min( 4, (float) $row->score ) );
			$dept_key    = $row->department ? $row->department : 'نامشخص';
			$role_key    = $row->assessor_role ? $row->assessor_role : 'کارمند';
			$count_rows ++;

			if ( $is_weisbord ) {
				$box = $wq_to_box[ $row->question_key ];
				if ( isset( $w_scores[ $box ] ) ) {
					$w_scores[ $box ]['sum'] += $score;
					$w_scores[ $box ]['n']++;
				}
				if ( ! empty( $row->created_at ) ) {
					$last_date     = $row->created_at;
					$last_department = $dept_key;
					$last_role       = $role_key;
					$last_slug       = $box;
				}
				continue;
			}

			if ( ! isset( $dimensions[ $row->dimension ] ) ) {
				continue;
			}
			$slug        = $row->dimension;

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

		// Normalize Weisbord box scores.
		foreach ( $weisbord_boxes as $slug => $box ) {
			$avg = 1.0;
			if ( isset( $w_scores[ $slug ]['n'] ) && $w_scores[ $slug ]['n'] > 0 ) {
				$avg = round( $w_scores[ $slug ]['sum'] / $w_scores[ $slug ]['n'], 2 );
			}
			$weisbord_score_map[ $slug ] = $avg;
		}

		$overall = self::weighted_average( $score_map );
		$wave    = self::wave_from_score( $overall );

		$departments = array();
		foreach ( $by_department as $name => $dept ) {
			$group   = self::normalize_group( $dept );
			$dept_avg = $group['weighted'];
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
			$role_avg = $group['weighted'];
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
		$strategy = self::strategy_data( $score_map, $departments, $roles );
		$analysis = self::analysis_data( $score_map, $summary, $efqm );
		$okr      = self::okr_systemic( $score_map, $departments, $roles, $strategy );

		// Hybrid assessment: the 30 maturity questions plus 18 Weisbord diagnostic
		// questions (48 total). The pure maturity part remains the wavelet engine.
		$w_score_map   = $weisbord_score_map;
		$weisbord      = self::weisbord_data( $w_score_map );
		$model_matrix  = self::model_matrix( $score_map, $weisbord, $efqm, $strategy );
		$reliability   = self::reliability_data( $rows );

		// A premature coaching recommendation must not appear while readiness gates are unmet.
		if ( empty( $strategy['coaching_recommended'] ) ) {
			$recommendations = array_values( array_filter( $recommendations, function ( $rec ) {
				return 'coaching_culture' !== $rec['slug'];
			} ) );
		}

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
			'okr'            => $okr,
			'strategy'       => $strategy,
			'weisbord'       => $weisbord,
			'model_matrix'   => $model_matrix,
			'reliability'    => $reliability,
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
