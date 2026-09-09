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
				'label'      => 'رسمیت ساختاری (قوانین، رویه‌ها و اسناد)',
				'short'      => 'رسمیت',
				'icon'       => '§',
				'weight'     => 1.0,
				'indicator'  => 'میزان قوانین، رویه‌ها و مستندات رسمی؛ مقوله‌ای جدا از «چابکی ساختاری» است (رسمیت بالا معمولاً چابکی را محدود می‌کند)',
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
			'agility'              => array(
				'slug'       => 'agility',
				'label'      => 'چابکی ساختاری (سرعت و انعطاف در تغییر)',
				'short'      => 'چابکی',
				'icon'       => '⚡',
				'weight'     => 1.2,
				'indicator'  => 'سرعت سازگاری رویه‌ها، ساختار و تصمیم‌ها با تغییرات محیطی؛ مقوله‌ای مجزا از رسمیت',
				'levels'     => array(
					1 => 'تغییرات بسیار کند؛ هر کار کوچک به تصویب و عبور از چند لایه نیاز دارد',
					2 => 'سازگاری موردی و واکنشی؛ تغییرها با تأخیر و مقاومت اجرا می‌شوند',
					3 => 'تغییر در چارچوب اصول به‌سرعت ممکن است؛ واحدها از اختیار لازم برخوردارند',
					4 => 'سازمان چابک؛ مسیرهای تغییر کوتاه، یادگیری سریع و تصمیم‌گیری در نزدیک‌ترین نقطه به مسئله',
				),
				'recommend'  => array(
					'title'   => 'افزایش چابکی ساختاری بدون حذف رسمیت لازم',
					'action'  => 'جداسازی «قوانین غیرقابل مذاکره» (ایمنی، مالکیت، اخلاق) از «رویه‌های قابل انعطاف»؛ فعال‌سازی مسیر تغییر ۷۲ ساعته و تیم‌های بهبود سریع',
					'kpi'     => 'کاهش میانگین زمان اجرای تغییر از ۳۰ به ۷ روز',
					'owner'   => 'مدیر تحول و بهبود فرآیندها',
					'tool'    => 'ماتریس رسمیت/چابکی + تیم‌های بهبود سریع + منشور اختیار تغییر',
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
					'tool'    => 'پرسش‌های OSKAR + تکنیک ۵ چرا + Appreciative Inquiry',
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
					'action'  => 'برنامه ۹۰ روزه تربیت سرپرست به مربی؛ آموزش OSKAR، SBI، گوش فعال و پرسش‌گری + جلسه ۱:۱ هفتگی',
					'kpi'     => 'افزایش مهارت مربیگری سرپرستان به ۳.۵',
					'owner'   => 'مدیر توسعه سازمانی',
					'tool'    => 'کتابخانه سؤالات OSKAR + روتین هفتگی سرپرست-مربی',
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
			// رسمیت (قوانین و رویه‌ها)
			array( 'key' => 'formalization_q1', 'dimension' => 'formalization', 'label' => 'قوانین و رویه‌های سازمان چقدر ساده، روشن و قابل اجرا هستند؟', 'weight' => 1.0 ),
			array( 'key' => 'formalization_q2', 'dimension' => 'formalization', 'label' => 'کارکنان چقدر در چارچوب اصول و قوانین لازم، استقلال عمل دارند؟', 'weight' => 1.0 ),

			// تمرکز / واگذاری تصمیم
			array( 'key' => 'centralization_q1', 'dimension' => 'centralization', 'label' => 'تصمیم‌های عملیاتی چقدر در نزدیک‌ترین سطح به کار گرفته می‌شوند؟', 'weight' => 1.2 ),
			array( 'key' => 'centralization_q2', 'dimension' => 'centralization', 'label' => 'سرپرستان چقدر می‌توانند بدون ارجاع به مدیران عالی تصمیم بگیرند؟', 'weight' => 1.2 ),

			// پیچیدگی ساختاری
			array( 'key' => 'complexity_q1', 'dimension' => 'complexity', 'label' => 'انجام کارهای روزمره چقدر ساده و بدون درگیر شدن در لایه‌ها و واحدهای اضافی است؟', 'weight' => 1.0 ),
			array( 'key' => 'complexity_q2', 'dimension' => 'complexity', 'label' => 'هماهنگی بین واحدها چقدر روان، سریع و کم‌هزینه است؟', 'weight' => 1.0 ),

			// چابکی ساختاری
			array( 'key' => 'agility_q1', 'dimension' => 'agility', 'label' => 'سازمان در مواجهه با تغییر شرایط، چقدر سریع و منعطف تصمیم می‌گیرد و حرکت می‌کند؟', 'weight' => 1.2 ),
			array( 'key' => 'agility_q2', 'dimension' => 'agility', 'label' => 'چابکی سازمان چقدر با حفظ قوانین ایمنی، اخلاقی و مالکیتی هم‌زمان ممکن شده است؟', 'weight' => 1.2 ),

			// گوش دادن فعال
			array( 'key' => 'active_listening_q1', 'dimension' => 'active_listening', 'label' => 'در جلسات و گفت‌وگوها، سخنان همکاران چقدر کامل و بدون قطع‌شدن شنیده می‌شود؟', 'weight' => 1.4 ),
			array( 'key' => 'active_listening_q2', 'dimension' => 'active_listening', 'label' => 'کارکنان چقدر احساس می‌کنند مدیرشان واقعاً حرف آن‌ها را می‌شنود؟', 'weight' => 1.4 ),

			// پرسش‌گری
			array( 'key' => 'questioning_q1', 'dimension' => 'questioning', 'label' => 'در تصمیم‌گیری‌ها چقدر به‌جای جواب آماده، سؤال باز و گزینه‌های جایگزین مطرح می‌شود؟', 'weight' => 1.3 ),
			array( 'key' => 'questioning_q2', 'dimension' => 'questioning', 'label' => '«چرا» و «چه می‌شد اگر» چقدر در فرهنگ جلسات و کار روزمره رایج است؟', 'weight' => 1.3 ),

			// بازخورد
			array( 'key' => 'feedback_q1', 'dimension' => 'feedback', 'label' => 'بازخورد عملکرد چقدر منظم و مستمر داده می‌شود (نه فقط پایان سال)؟', 'weight' => 1.5 ),
			array( 'key' => 'feedback_q2', 'dimension' => 'feedback', 'label' => 'کارکنان چقدر می‌توانند بدون ترس به مدیر بازخورد بدهند؟', 'weight' => 1.5 ),

			// ارزیابی عملکرد
			array( 'key' => 'performance_eval_q1', 'dimension' => 'performance_eval', 'label' => 'ارزیابی عملکرد چقدر بر اساس داده، شاخص و شواهد انجام می‌شود؟', 'weight' => 1.3 ),
			array( 'key' => 'performance_eval_q2', 'dimension' => 'performance_eval', 'label' => 'معیارهای ارزیابی چقدر از قبل شفاف و برای همه روشن است؟', 'weight' => 1.3 ),

			// امنیت روانی
			array( 'key' => 'psychological_safety_q1', 'dimension' => 'psychological_safety', 'label' => 'اعلام خطا چقدر بدون ترس از تنبیه یا تمسخر ممکن است؟', 'weight' => 1.4 ),
			array( 'key' => 'psychological_safety_q2', 'dimension' => 'psychological_safety', 'label' => 'مخالفت محترمانه با نظر مدیر چقدر در جلسات پذیرفته می‌شود؟', 'weight' => 1.4 ),

			// فرهنگ یادگیری
			array( 'key' => 'learning_culture_q1', 'dimension' => 'learning_culture', 'label' => 'پس از هر پروژه یا تجربه مهم، درس‌آموخته‌ها چقدر جمع‌آوری و مستند می‌شوند؟', 'weight' => 1.2 ),
			array( 'key' => 'learning_culture_q2', 'dimension' => 'learning_culture', 'label' => 'دانش و تجربه بین واحدها چقدر راحت جریان پیدا می‌کند؟', 'weight' => 1.2 ),

			// فرهنگ مربی‌گری
			array( 'key' => 'coaching_culture_q1', 'dimension' => 'coaching_culture', 'label' => 'سرپرستان چقدر به‌جای دستور دادن، با پرسش مربی‌گری به رشد اعضا کمک می‌کنند؟', 'weight' => 1.4 ),
			array( 'key' => 'coaching_culture_q2', 'dimension' => 'coaching_culture', 'label' => 'جلسه‌های کوتاه و منظم بین سرپرست و اعضا (نشست ۱:۱) چقدر برگزار می‌شود؟', 'weight' => 1.4 ),
		);
	}

	/**
	 * Question level descriptor (same four-point scale for every question).
	 *
	 * @return array
	 */
	public static function question_options() {
		return array(
			1 => 'خیلی کم / ضعیف',
			2 => 'کم / در حال رشد',
			3 => 'زیاد / مناسب',
			4 => 'خیلی زیاد / قوی',
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
			array( 'key' => 'weisbord_goals_q1', 'dimension' => 'weisbord_goals', 'label' => 'اهداف سازمان چقدر برای همه واحدها شفاف و قابل‌درک است؟', 'weight' => 1.2 ),
			array( 'key' => 'weisbord_goals_q2', 'dimension' => 'weisbord_goals', 'label' => 'پیشرفت نسبت به اهداف چقدر منظم و با معیار مشخص بررسی می‌شود؟', 'weight' => 1.2 ),

			array( 'key' => 'weisbord_structure_q1', 'dimension' => 'weisbord_structure', 'label' => 'ساختار سازمان چقدر با اهداف و نوع کار همخوان است؟', 'weight' => 1.1 ),
			array( 'key' => 'weisbord_structure_q2', 'dimension' => 'weisbord_structure', 'label' => 'لایه‌های تصمیم‌گیری چقدر به مقدار لازم و ضروری محدود شده‌اند؟', 'weight' => 1.1 ),

			array( 'key' => 'weisbord_relationships_q1', 'dimension' => 'weisbord_relationships', 'label' => 'واحدها چقدر به‌جای رقابت مخرب، با هم همکاری می‌کنند؟', 'weight' => 1.3 ),
			array( 'key' => 'weisbord_relationships_q2', 'dimension' => 'weisbord_relationships', 'label' => 'ارتباط بین واحدها چقدر دوسویه، امن و بدون ترس است؟', 'weight' => 1.3 ),

			array( 'key' => 'weisbord_rewards_q1', 'dimension' => 'weisbord_rewards', 'label' => 'پاداش و تشویق چقدر بر اساس عملکرد واقعی و شایستگی است؟', 'weight' => 1.2 ),
			array( 'key' => 'weisbord_rewards_q2', 'dimension' => 'weisbord_rewards', 'label' => 'معیارهای پاداش چقدر از پیش شفاف و برای همه یکسان است؟', 'weight' => 1.2 ),

			array( 'key' => 'weisbord_leadership_q1', 'dimension' => 'weisbord_leadership', 'label' => 'رهبران چقدر بین اهداف، ساختار، روابط و پاداش تعادل خوبی برقرار می‌کنند؟', 'weight' => 1.4 ),
			array( 'key' => 'weisbord_leadership_q2', 'dimension' => 'weisbord_leadership', 'label' => 'رهبران چقدر الگوی عملی یادگیری، بازخورد و هم‌آفرینی هستند؟', 'weight' => 1.4 ),

			array( 'key' => 'weisbord_helping_q1', 'dimension' => 'weisbord_helping', 'label' => 'فرایندها، ابزارها و سیستم‌های سازمان چقدر کارها را آسان‌تر می‌کنند؟', 'weight' => 1.1 ),
			array( 'key' => 'weisbord_helping_q2', 'dimension' => 'weisbord_helping', 'label' => 'داده‌ها و گزارش‌ها چقدر برای تصمیم‌گیری قابل‌اتکا و یکپارچه هستند؟', 'weight' => 1.1 ),
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
			'boxes'     => array_values( $result ),
			'low'       => array_slice( $low, 0, 3 ),
			'diagnosis' => $diagnosis,
			'level'     => $overall < 2.2 ? 'ضعیف/بوروکراتیک' : ( $overall < 2.75 ? 'شکننده' : ( $overall < 3.35 ? 'در حال بهبود' : 'مطلوب' ) ),
		);
	}

	/**
	 * Iran Oil & Gas HR strategic programme components (Horizon 1410).
	 *
	 * The vision is: "حوزه منابع انسانی صنعت نفت، برند برتر کارفرمایی در ایران
	 * با شاخصه‌های توسعه‌یافتگی، جذابیت و بهره‌وری بالا در افق ۱۴۱۰".
	 *
	 * @return array
	 */
	public static function hr1410_components() {
		return array(
			'brand_employer' => array(
				'slug'   => 'brand_employer',
				'label'  => 'برند برتر کارفرمایی',
				'short'  => 'برند کارفرمایی',
				'icon'   => '★',
				'weight' => 1.2,
				'kpi'    => 'شاخص برند کارفرمایی و اولویت انتخاب صنعت نفت در بازار کار',
				'mapping'=> array( 'psychological_safety', 'active_listening', 'weisbord_relationships', 'attitude_satisfaction' ),
			),
			'development' => array(
				'slug'   => 'development',
				'label'  => 'توسعه‌یافتگی منابع انسانی',
				'short'  => 'توسعه‌یافتگی',
				'icon'   => '✦',
				'weight' => 1.1,
				'kpi'    => 'شاخص توسعه و یادگیری منابع انسانی؛ سطح شایستگی‌محوری',
				'mapping'=> array( 'learning_culture', 'coaching_culture', 'questioning', 'attitude_commitment' ),
			),
			'attractiveness' => array(
				'slug'   => 'attractiveness',
				'label'  => 'جذابیت و نگهداشت',
				'short'  => 'جذابیت',
				'icon'   => '♥',
				'weight' => 1.2,
				'kpi'    => 'شاخص جذابیت، انگیزه و تمایل به ماندگاری',
				'mapping'=> array( 'psychological_safety', 'feedback', 'performance_eval', 'attitude_satisfaction', 'attitude_other' ),
			),
			'productivity' => array(
				'slug'   => 'productivity',
				'label'  => 'بهره‌وری بالا',
				'short'  => 'بهره‌وری',
				'icon'   => '↗',
				'weight' => 1.2,
				'kpi'    => 'شاخص بهره‌وری منابع انسانی؛ بهبود عملکرد فردی و سازمانی',
				'mapping'=> array( 'feedback', 'performance_eval', 'formalization', 'complexity', 'attitude_commitment' ),
			),
		);
	}

	/**
	 * Employee attitude model from the Iran oil industry HR strategy.
	 *
	 * Satisfaction, commitment and other attitudes jointly drive employee
	 * performance, which in turn drives customer satisfaction and profitability.
	 *
	 * @return array
	 */
	public static function attitude_groups() {
		return array(
			'attitude_satisfaction' => array(
				'slug'   => 'attitude_satisfaction',
				'label'  => 'رضایت شغلی',
				'short'  => 'رضایت',
				'icon'   => '◆',
				'weight' => 1.3,
				'items'  => array( 'پیشرفت شغلی', 'ارتباط با مدیران', 'حقوق و مزایا' ),
			),
			'attitude_commitment' => array(
				'slug'   => 'attitude_commitment',
				'label'  => 'تعهد سازمانی',
				'short'  => 'تعهد',
				'icon'   => '⚙',
				'weight' => 1.4,
				'items'  => array( 'تعلق و تعهد احساسی', 'تعهد رفتاری', 'مشارکت داوطلبانه' ),
			),
			'attitude_other' => array(
				'slug'   => 'attitude_other',
				'label'  => 'سایر نگرش‌ها',
				'short'  => 'سایر نگرش‌ها',
				'icon'   => '❋',
				'weight' => 1.2,
				'items'  => array( 'عدالت سازمانی', 'میل به ترک شغل', 'سلامت روان' ),
			),
		);
	}

	/**
	 * Attitude questions (4 per group = 12).
	 *
	 * @return array
	 */
	public static function attitude_questions() {
		return array(
			array( 'key' => 'attitude_satisfaction_q1', 'dimension' => 'attitude_satisfaction', 'label' => 'کارکنان چقدر فرصت روشن برای پیشرفت و رشد شغلی دارند؟', 'weight' => 1.3 ),
			array( 'key' => 'attitude_satisfaction_q2', 'dimension' => 'attitude_satisfaction', 'label' => 'کارکنان چقدر ارتباط مؤثر و منظم با مدیران خود دارند؟', 'weight' => 1.3 ),
			array( 'key' => 'attitude_satisfaction_q3', 'dimension' => 'attitude_satisfaction', 'label' => 'حقوق، مزایا و جبران خدمت چقدر منصفانه و مبتنی بر عملکرد است؟', 'weight' => 1.3 ),

			array( 'key' => 'attitude_commitment_q1', 'dimension' => 'attitude_commitment', 'label' => 'کارکنان چقدر به سازمان و اهداف آن احساس تعلق و تعهد دارند؟', 'weight' => 1.4 ),
			array( 'key' => 'attitude_commitment_q2', 'dimension' => 'attitude_commitment', 'label' => 'تعهد رفتاری (حضور، همکاری و مسئولیت‌پذیری) چقدر در عمل دیده می‌شود؟', 'weight' => 1.4 ),
			array( 'key' => 'attitude_commitment_q3', 'dimension' => 'attitude_commitment', 'label' => 'کارکنان چقدر داوطلبانه در بهبود و تیم‌های توسعه مشارکت می‌کنند؟', 'weight' => 1.4 ),

			array( 'key' => 'attitude_other_q1', 'dimension' => 'attitude_other', 'label' => 'عدالت سازمانی در توزیع فرصت‌ها و پاداش چقدر رعایت می‌شود؟', 'weight' => 1.2 ),
			array( 'key' => 'attitude_other_q2', 'dimension' => 'attitude_other', 'label' => 'میل به ترک شغل در سازمان چقدر پایین است؟', 'weight' => 1.2 ),
			array( 'key' => 'attitude_other_q3', 'dimension' => 'attitude_other', 'label' => 'سلامت روان و به‌زیستی کارکنان چقدر به‌صورت فعال پشتیبانی می‌شود؟', 'weight' => 1.2 ),
		);
	}

	/**
	 * Build the employee attitude model result.
	 *
	 * @param array $score_map group slug => 1-4 score.
	 * @return array
	 */
	public static function attitude_data( $score_map ) {
		$groups   = self::attitude_groups();
		$result   = array();
		$sum_w    = 0.0;
		$sum_sc   = 0.0;

		foreach ( $groups as $slug => $group ) {
			$score = isset( $score_map[ $slug ] ) ? (float) $score_map[ $slug ] : 1.0;
			if ( $score < 2.2 ) {
				$status = 'بحرانی';
				$color  = '#b91c1c';
			} elseif ( $score < 2.75 ) {
				$status = 'شکننده';
				$color  = '#d97706';
			} elseif ( $score < 3.35 ) {
				$status = 'در حال بهبود';
				$color  = '#2563eb';
			} else {
				$status = 'مطلوب';
				$color  = '#0f766e';
			}
			$likely = array(
				'attitude_satisfaction' => 'افت انگیزه و کیفیت رابطه؛ توقف زنجیره بهبود عملکرد و رضایت مشتری.',
				'attitude_commitment'   => 'کاهش مشارکت، ماندگاری و هویت سازمانی؛ تضعیف برند کارفرمایی.',
				'attitude_other'        => 'افزایش میل به ترک و کاهش سلامت روان/تعادل؛ کاهش بهره‌وری پایدار.',
			);
			$result[ $slug ] = array(
				'slug'    => $slug,
				'label'   => $group['label'],
				'short'   => $group['short'],
				'icon'    => $group['icon'],
				'score'   => $score,
				'status'  => $status,
				'color'   => $color,
				'items'   => $group['items'],
				'likely'  => isset( $likely[ $slug ] ) ? $likely[ $slug ] : '',
			);
			$sum_w  += (float) $group['weight'];
			$sum_sc += $score * (float) $group['weight'];
		}

		$satisfaction = isset( $result['attitude_satisfaction']['score'] ) ? $result['attitude_satisfaction']['score'] : 1.0;
		$commitment   = isset( $result['attitude_commitment']['score'] ) ? $result['attitude_commitment']['score'] : 1.0;
		$other        = isset( $result['attitude_other']['score'] ) ? $result['attitude_other']['score'] : 1.0;

		$employee_performance = round( ( $satisfaction * 0.35 ) + ( $commitment * 0.40 ) + ( $other * 0.25 ), 2 );
		$customer_satisfaction = round( ( $employee_performance * 0.70 ) + ( $satisfaction * 0.30 ), 2 );
		$profitability = round( ( $customer_satisfaction * 0.65 ) + ( $commitment * 0.35 ), 2 );

		$chain = array(
			'employee_performance' => $employee_performance,
			'customer_satisfaction'=> $customer_satisfaction,
			'profitability'        => $profitability,
		);

		$low = array_values( array_filter( $result, function ( $r ) {
			return $r['score'] < 2.75;
		} ) );
		usort( $low, function ( $a, $b ) {
			return $a['score'] <=> $b['score'];
		} );

		$diagnosis = 'بر اساس مدل نگرش شغلی، سه گروه «رضایت، تعهد و سایر نگرش‌ها» به‌صورت جداگانه سنجیده شدند. ';
		if ( $low ) {
			$diagnosis .= 'ضعیف‌ترین محورها: ' . implode( '، ', array_map( function ( $r ) {
				return $r['short'] . ' (' . round( $r['score'], 1 ) . ')';
			}, array_slice( $low, 0, 3 ) ) ) . '. ';
		} else {
			$diagnosis .= 'هیچ محوری در محدوده بحرانی نیست. ';
		}
		$diagnosis .= 'این نگرش‌ها به‌ترتیب بر عملکرد کارکنان، رضایت مشتریان و سودآوری اثر می‌گذارند.';

		return array(
			'overall'     => $sum_w > 0 ? round( $sum_sc / $sum_w, 2 ) : 1.0,
			'groups'      => array_values( $result ),
			'low'         => array_slice( $low, 0, 3 ),
			'chain'       => $chain,
			'diagnosis'   => $diagnosis,
			'level'       => $sum_w > 0 && $sum_sc / $sum_w < 2.2 ? 'بحرانی' : ( $sum_w > 0 && $sum_sc / $sum_w < 2.75 ? 'شکننده' : ( $sum_w > 0 && $sum_sc / $sum_w < 3.35 ? 'در حال بهبود' : 'مطلوب' ) ),
		);
	}

	/**
	 * Build the Horizon-1410 alignment score.
	 *
	 * @param array $score_map Dimension/box/attitude score map.
	 * @param array $attitude  Attitude model result.
	 * @return array
	 */
	public static function hr1410_data( $score_map, $attitude ) {
		$components = self::hr1410_components();
		$label_map  = array();
		foreach ( self::dimensions() as $slug => $dim ) {
			$label_map[ $slug ] = $dim['label'];
		}
		foreach ( self::weisbord_boxes() as $slug => $box ) {
			$label_map[ $slug ] = $box['label'];
		}
		foreach ( self::attitude_groups() as $slug => $group ) {
			$label_map[ $slug ] = $group['label'];
		}
		$result     = array();
		$sum_w      = 0.0;
		$sum_sc     = 0.0;

		foreach ( $components as $slug => $comp ) {
			$scores = array();
			foreach ( $comp['mapping'] as $m ) {
				if ( isset( $score_map[ $m ] ) ) {
					$scores[] = (float) $score_map[ $m ];
				}
			}
			if ( empty( $scores ) && isset( $attitude['overall'] ) ) {
				$scores[] = (float) $attitude['overall'];
			}
			$score = $scores ? round( array_sum( $scores ) / count( $scores ), 2 ) : 1.0;
			$target = 3.35;
			$gap = max( 0, round( $target - $score, 2 ) );
			if ( $score < 2.2 ) {
				$status = 'نیازمند مداخله فوری';
				$color  = '#b91c1c';
			} elseif ( $score < 2.75 ) {
				$status = 'شکننده';
				$color  = '#d97706';
			} elseif ( $score < 3.35 ) {
				$status = 'در مسیر چشم‌انداز';
				$color  = '#2563eb';
			} else {
				$status = 'منطبق با افق ۱۴۱۰';
				$color  = '#0f766e';
			}
			$mapping_labels = array();
			foreach ( $comp['mapping'] as $m ) {
				$mapping_labels[] = isset( $label_map[ $m ] ) ? $label_map[ $m ] : $m;
			}
			$result[ $slug ] = array(
				'slug'           => $slug,
				'label'          => $comp['label'],
				'short'          => $comp['short'],
				'icon'           => $comp['icon'],
				'score'          => $score,
				'gap'            => $gap,
				'status'         => $status,
				'color'          => $color,
				'kpi'            => $comp['kpi'],
				'mapping'        => $comp['mapping'],
				'mapping_labels' => $mapping_labels,
			);
			$sum_w  += (float) $comp['weight'];
			$sum_sc += $score * (float) $comp['weight'];
		}

		$overall = $sum_w > 0 ? round( $sum_sc / $sum_w, 2 ) : 1.0;
		$gap     = max( 0, round( 3.35 - $overall, 2 ) );

		$priority = array_values( $result );
		usort( $priority, function ( $a, $b ) {
			return $a['score'] <=> $b['score'];
		} );

		return array(
			'overall'    => $overall,
			'gap'        => $gap,
			'components' => array_values( $result ),
			'priority'   => array_slice( $priority, 0, 3 ),
			'vision'     => 'حوزه منابع انسانی صنعت نفت، برند برتر کارفرمایی در ایران با شاخصه‌های توسعه‌یافتگی، جذابیت و بهره‌وری بالا در افق ۱۴۱۰.',
			'aligned'    => $overall >= 3.35,
			'level'      => $overall < 2.2 ? 'فاصله زیاد از چشم‌انداز' : ( $overall < 2.75 ? 'فاصله قابل توجه' : ( $overall < 3.35 ? 'در مسیر چشم‌انداز' : 'منطبق با چشم‌انداز' ) ),
		);
	}

	/**
	 * McKinsey 7S diagnostic components.
	 *
	 * Each of the seven elements acts as a compass for the organizational
	 * development journey. Scores are derived from the already collected
	 * maturity, Weisbord and attitude data so the concise 43-item assessment remains
	 * the single evidence base while managers get a 7S executive lens.
	 *
	 * @return array
	 */
	public static function mckinsey7s_components() {
		return array(
			'strategy' => array(
				'key'    => 'strategy',
				'label'  => 'راهبرد و جهت‌گیری',
				'en'     => 'Strategy',
				'icon'   => '🧭',
				'kpi'    => 'شفافیت اهداف، برنامه راهبردی و جریان تصمیم‌گیری',
				'mapping'=> array( 'performance_eval', 'questioning', 'hr1410_overall' ),
				'question' => 'آیا سازمان برای سال‌های آینده مسیر روشن، شاخص و تصمیم‌های منسجم دارد؟',
				'signs'   => 'نشانه ضعف: اهداف پراکنده، ارزیابی ذهنی، فقدان پرسش راهبردی یا عدم هم‌راستایی واحدها',
				'guidance'=> 'تدوین نقشه راه ۱۲ ماهه، OKR سالانه، بازبینی سه‌ماهه راهبرد و اتصال شاخص‌های هر واحد به هدف کلان',
			),
			'structure' => array(
				'key'    => 'structure',
				'label'  => 'ساختار سازمانی',
				'en'     => 'Structure',
				'icon'   => '🏗️',
				'kpi'    => 'تعادل رسمیت لازم، چابکی، تمرکز تصمیم و کاهش پیچیدگی',
				'mapping'=> array( 'formalization', 'agility', 'centralization', 'complexity' ),
				'question' => 'آیا ساختار، اختیار و لایه‌های تصمیم برای اجرای راهبرد مناسب و چابک است؟',
				'signs'   => 'نشانه ضعف: رسمیت بالا بدون چابکی، تمرکز بیش از حد، لایه‌ها و سیلوهای متعدد',
				'guidance'=> 'ماتریس رسمیت/چابکی، منشور اختیار تصمیم، کاهش لایه‌های غیرضروری و تیم‌های بین‌واحدی',
			),
			'systems' => array(
				'key'    => 'systems',
				'label'  => 'سیستم‌ها و فرایندها',
				'en'     => 'Systems',
				'icon'   => '⚙️',
				'kpi'    => 'ارزیابی عملکرد، بازخورد، داشبورد داده و فرایندهای تصمیم',
				'mapping'=> array( 'feedback', 'performance_eval', 'weisbord_helping' ),
				'question' => 'آیا سیستم‌های ارزیابی، بازخورد و اطلاعات از تصمیم‌های داده‌محور پشتیبانی می‌کنند؟',
				'signs'   => 'نشانه ضعف: ارزیابی سالانه و ذهنی، فقدان بازخورد منظم، داده پراکنده و فرایند کاغذی',
				'guidance'=> 'استقرار SBI، داشبورد KPI و OKR، جلسه ۱:۱ منظم و سیستم اطلاعات واحدها',
			),
			'skills' => array(
				'key'    => 'skills',
				'label'  => 'مهارت‌ها و شایستگی‌ها',
				'en'     => 'Skills',
				'icon'   => '🛠️',
				'kpi'    => 'شایستگی فنی/مدیریتی، یادگیری و مهارت‌های مربی‌گری',
				'mapping'=> array( 'learning_culture', 'coaching_culture', 'attitude_satisfaction' ),
				'question' => 'آیا سازمان برای اجرای راهبرد، شایستگی فردی، تیمی و مربی‌گری کافی دارد؟',
				'signs'   => 'نشانه ضعف: آموزش بدون انتقال به کار، نبود مهارت بازخورد و مربی‌گری، تکرار خطا',
				'guidance'=> 'نقشه شایستگی، برنامه یادگیری تجربی، آموزش SBI/OSKAR و جلسات بازارزیابی مهارت',
			),
			'staff' => array(
				'key'    => 'staff',
				'label'  => 'کارکنان و منابع انسانی',
				'en'     => 'Staff',
				'icon'   => '👥',
				'kpi'    => 'رضایت، تعهد، نگهداشت، عدالت و به‌زیستی کارکنان',
				'mapping'=> array( 'attitude_satisfaction', 'attitude_commitment', 'attitude_other' ),
				'question' => 'آیا کارکنان مناسب در نقش‌های مناسب با انگیزه و توان ماندگاری حضور دارند؟',
				'signs'   => 'نشانه ضعف: رضایت پایین، میل به ترک، عدالت سازمانی ضعیف و فرسودگی',
				'guidance'=> 'برنامه جذابیت و نگهداشت، عدالت در پاداش، مسیر رشد شغلی و پایش سلامت روان',
			),
			'style' => array(
				'key'    => 'style',
				'label'  => 'سبک رهبری',
				'en'     => 'Style of Leadership',
				'icon'   => '🧠',
				'kpi'    => 'گوش دادن فعال، پرسش‌گری، بازخورد و الگوی رفتاری مدیران',
				'mapping'=> array( 'active_listening', 'questioning', 'coaching_culture', 'weisbord_leadership' ),
				'question' => 'آیا مدیران و سرپرستان به‌جای دستور، با گوش دادن، پرسش و بازخورد رهبری می‌کنند؟',
				'signs'   => 'نشانه ضعف: دستوردهی، قطع‌کردن صحبت، ترس از مخالفت و نبود جلسه ۱:۱',
				'guidance'=> 'تقویت سبک مربی‌گری، تمرین گوش دادن فعال، پرسش‌گری واگرا و بازخورد دوسویه',
			),
			'shared_values' => array(
				'key'    => 'shared_values',
				'label'  => 'ارزش‌های مشترک و فرهنگ',
				'en'     => 'Shared Values',
				'icon'   => '💎',
				'kpi'    => 'امنیت روانی، عدالت، یادگیری، همکاری و ارزش‌های ایمنی/اخلاقی',
				'mapping'=> array( 'psychological_safety', 'learning_culture', 'attitude_other', 'weisbord_relationships' ),
				'question' => 'آیا ارزش‌های مشترک، امنیت روانی و فرهنگ، همه مؤلفه‌های دیگر را به هم متصل می‌کنند؟',
				'signs'   => 'نشانه ضعف: ترس از خطا، سکوت، رقابت مخرب، سیلو و نبود ارزش مشترک',
				'guidance'=> 'گفت‌وگوی ارزش‌ها، یادگیری از خطا بدون تنبیه، تقویت امنیت روانی و همکاری بین‌واحدی',
			),
		);
	}

	/**
	 * Compute the McKinsey 7S lens from the unified assessment score map.
	 *
	 * @param array $score_map Combined maturity + Weisbord + attitude score map.
	 * @param array $attitude  Attitude model result.
	 * @return array
	 */
	public static function mckinsey7s_data( $score_map, $attitude = array(), $target_wave = 3, $hr1410 = array() ) {
		$components = self::mckinsey7s_components();
		$label_map  = array();
		foreach ( self::dimensions() as $slug => $dim ) {
			$label_map[ $slug ] = $dim['label'];
		}
		foreach ( self::weisbord_boxes() as $slug => $box ) {
			$label_map[ $slug ] = $box['label'];
		}
		foreach ( self::attitude_groups() as $slug => $group ) {
			$label_map[ $slug ] = $group['label'];
		}
		$target = max( 3.35, self::target_threshold( $target_wave ) );
		$result = array();
		$sum    = 0.0;
		foreach ( $components as $slug => $comp ) {
			$scores = array();
			foreach ( $comp['mapping'] as $m ) {
				if ( 'hr1410_overall' === $m ) {
					if ( isset( $hr1410['overall'] ) ) {
						$scores[] = (float) $hr1410['overall'];
					}
					continue;
				}
				if ( isset( $score_map[ $m ] ) ) {
					$scores[] = (float) $score_map[ $m ];
				}
			}
			if ( empty( $scores ) ) {
				$scores[] = isset( $attitude['overall'] ) ? (float) $attitude['overall'] : 1.0;
			}
			$score = $scores ? round( array_sum( $scores ) / count( $scores ), 2 ) : 1.0;
			$score = max( 1, min( 4, $score ) );
			$gap   = max( 0, round( $target - $score, 2 ) );
			if ( $score < 2.2 ) {
				$status = 'نیازمند مداخله فوری';
				$color  = '#b91c1c';
			} elseif ( $score < 2.75 ) {
				$status = 'شکننده';
				$color  = '#d97706';
			} elseif ( $score < $target ) {
				$status = 'در مسیر وضعیت مطلوب';
				$color  = '#2563eb';
			} else {
				$status = 'هم‌راستا با وضعیت مطلوب';
				$color  = '#0f766e';
			}
			$mapping_labels = array();
			foreach ( $comp['mapping'] as $m ) {
				if ( 'hr1410_overall' === $m ) {
					$mapping_labels[] = 'هم‌راستایی با افق ۱۴۱۰';
					continue;
				}
				$mapping_labels[] = isset( $label_map[ $m ] ) ? $label_map[ $m ] : $m;
			}
			$result[ $slug ] = array(
				'key'           => $slug,
				'label'         => $comp['label'],
				'en'            => $comp['en'],
				'icon'          => $comp['icon'],
				'score'         => $score,
				'gap'           => $gap,
				'target'        => $target,
				'status'        => $status,
				'color'         => $color,
				'kpi'           => $comp['kpi'],
				'question'      => $comp['question'],
				'signs'         => $comp['signs'],
				'guidance'      => $comp['guidance'],
				'mapping'       => $comp['mapping'],
				'mapping_labels'=> $mapping_labels,
			);
			$sum += $score;
		}
		$overall = count( $result ) ? round( $sum / count( $result ), 2 ) : 1.0;
		$priority = array_values( $result );
		usort( $priority, function ( $a, $b ) {
			return $a['score'] <=> $b['score'];
		} );
		$diagnosis = count( $result )
			? 'قطب‌نمای 7S مک‌کنزی: میانگین هفت مؤلفه ' . $overall . ' از ۴ است. نزدیک‌ترین نقاط بهبود ' . implode( '، ', array_map( function ( $c ) { return $c['label'] . ' (' . $c['score'] . ')'; }, array_slice( $priority, 0, 2 ) ) ) . ' هستند که به‌عنوان محور اقدامات توسعه سازمانی و مربی‌گری عملکردی پایش می‌شوند.'
			: 'پس از تکمیل ارزیابی، قطب‌نمای 7S محاسبه و نمایش داده می‌شود.';
		return array(
			'overall'    => $overall,
			'gap'        => max( 0, round( $target - $overall, 2 ) ),
			'target'     => $target,
			'components' => array_values( $result ),
			'priority'   => array_slice( $priority, 0, 3 ),
			'diagnosis'  => $diagnosis,
			'aligned'    => $overall >= $target,
		);
	}

	/**
	 * OSKAR coaching model.
	 *
	 * OSKAR is used here as the coaching/performance-improvement model (an
	 * appreciative, strengths-based alternative to problem-solving models that
	 * precede it). It is especially useful when feedback (SBI) shows a gap,
	 * because it moves the employee from a concrete outcome to scaled reality,
	 * know-how and an affirmed action plan that is reviewed on a short cycle.
	 *
	 * @return array
	 */
	public static function oskar() {
		return array(
			'code'  => 'OSKAR',
			'label' => 'مدل مربی‌گری و بهبود عملکرد OSKAR',
			'source'=> 'Wilson, C. (2014). Performance Coaching; OSKAR model.',
			'steps' => array(
				'Outcome'  => array( 'fa' => 'نتیجه', 'meaning' => 'کارمند دقیقاً چه نتیجه‌ای می‌خواهد به‌دست آورد؟ (شاخص قابل مشاهده)' ),
				'Scaling'  => array( 'fa' => 'مقیاس', 'meaning' => 'امروز از ۱ تا ۱۰ کجاست؟ شواهد و داده‌ها چیست؟' ),
				'Know-how' => array( 'fa' => 'دانش و توان', 'meaning' => 'برای رسیدن به نمره بالاتر چه دانش، مهارت یا منبعی لازم است؟' ),
				'Affirm'   => array( 'fa' => 'تأیید و اقدام', 'meaning' => 'چه چیزی خوب انجام شده؟ چه اقدام مشخص و کوچکی انجام می‌شود؟' ),
				'Review'   => array( 'fa' => 'بازبینی', 'meaning' => 'چطور و چه زمانی پیشرفت را اندازه می‌گیریم؟' ),
			),
		);
	}

	/**
	 * Individual/career performance evaluation dimensions.
	 *
	 * These are transparent, measurable indicators used to fill the missing
	 * individual performance mechanism. They are kept separate from the
	 * organizational-maturity assessment so a role-level performance profile is
	 * not mixed with the organizational wave diagnosis.
	 *
	 * @return array
	 */
	public static function performance_dimensions() {
		return array(
			'results_score' => array(
				'key'   => 'results_score',
				'label' => 'تحقق نتایج و اهداف',
				'short' => 'نتایج',
				'icon'  => '🎯',
				'weight'=> 1.4,
				'kpi'   => 'درصد تحقق OKR و شاخص‌های عملیاتی/کیفی دوره',
				'answer'=> 'کارمند تا چه اندازه به اهداف و نتایج تعیین‌شده در دوره دست یافته است؟',
				'evidence' => 'درصد تحقق objectives / نتایج کلیدی، مستند داشبورد OKR یا خروجی واحد.',
				'source'   => 'Doerr, J. (2018). Measure What Matters; مدیریت عملکرد با OKR.',
				'rubric'   => array(
					1 => 'کمتر از ۴۰٪ اهداف: نتیجه نامشخص یا بدون مستند.',
					2 => '۴۰-۶۹٪ اهداف با شواهد محدود؛ برخی نتایج ناقص.',
					3 => '۷۰-۸۹٪ اهداف با مستند قابل بررسی؛ انحراف عادی و قابل توضیح.',
					4 => '۹۰٪+ اهداف با شواهد مستند و اثر مثبت بر کل واحد.',
				),
			),
			'quality_score' => array(
				'key'   => 'quality_score',
				'label' => 'کیفیت، دقت و کنترل خطا',
				'short' => 'کیفیت',
				'icon'  => '♦',
				'weight'=> 1.2,
				'kpi'   => 'نرخ خطا، بازکاری، ممیزی ایمنی/کیفیت و خروجی استاندارد',
				'answer'=> 'کیفیت کار، دقت مستندات، رعایت استاندارد و کاهش خطا چگونه است؟',
				'evidence' => 'تعداد/نرخ خطا، بازکاری، نتایج ممیزی ISO/HSE و کنترل کیفیت دوره.',
				'source'   => 'ISO 9001/45001؛ ممیزی کیفیت و ایمنی صنعت نفت.',
				'rubric'   => array(
					1 => 'خطاهای مکرر، بازکاری بالا یا نقض استاندارد مستند.',
					2 => 'خطاهای موردی؛ مستندسازی و بازبینی ضعیف است.',
					3 => 'خطای اندک با بازبینی منظم؛ ممیزی بدون یافته جدی.',
					4 => 'خروجی بدون بازکاری، مستندسازی کامل و بهبود مستمر فرایند.',
				),
			),
			'teamwork_score' => array(
				'key'   => 'teamwork_score',
				'label' => 'همکاری، شبکه و کار تیمی',
				'short' => 'همکاری',
				'icon'  => '🤝',
				'weight'=> 1.1,
				'kpi'   => 'مشارکت در تیم‌های چندتخصصی، انتقال دانش و مدیریت تعارض',
				'answer'=> 'کارمند چقدر در همکاری بین‌واحدی، اشتراک دانش و حل مسئله تیمی نقش دارد؟',
				'evidence' => 'مشارکت در جلسات مشترک، خروجی تیم‌های چندتخصصی و بازخورد همکاران.',
				'source'   => 'Senge, P. M. (1990). The Fifth Discipline; شبکه‌های یادگیری.',
				'rubric'   => array(
					1 => 'کار سیلویی؛ عدم مشارکت یا ایجاد تعارض در تیم.',
					2 => 'همکاری موردی؛ دانش کم اشتراک‌گذاری می‌شود.',
					3 => 'مشارکت منظم در تیم و انتقال دانش به همکاران.',
					4 => 'نقش تسهیل‌گر شبکه، رفع تعارض و اشتراک دانش سطح سازمان.',
				),
			),
			'learning_score' => array(
				'key'   => 'learning_score',
				'label' => 'یادگیری و رشد حرفه‌ای',
				'short' => 'یادگیری',
				'icon'  => '✎',
				'weight'=> 1.1,
				'kpi'   => 'تکمیل آموزش، جذب بازخورد، بهبود رفتار پس از بازخورد',
				'answer'=> 'کارمند از بازخورد و مربی‌گری چقدر یاد می‌گیرد و در رفتار خود تغییر می‌دهد؟',
				'evidence' => 'آموزش تکمیل‌شده، پروژه یادگیری، و تغییر رفتار پس از بازخورد OSKAR.',
				'source'   => 'Kolb (1984). Experiential Learning; بازخورد رشد‌محور.',
				'rubric'   => array(
					1 => 'عدم تکمیل آموزش یا تکرار همان خطا پس از بازخورد.',
					2 => 'یادگیری پراکنده و بدون انتقال به کار واقعی.',
					3 => 'تکمیل برنامه یادگیری و تغییر قابل مشاهده پس از بازخورد.',
					4 => 'یادگیری فعال، مربی‌گری همتا و بهبود مستمر مستند.',
				),
			),
			'behavior_score' => array(
				'key'   => 'behavior_score',
				'label' => 'رفتار حرفه‌ای، امنیت و اخلاق کار',
				'short' => 'رفتار',
				'icon'  => '♥',
				'weight'=> 1.2,
				'kpi'   => 'احترام، امنیت روانی، مسئولیت‌پذیری، رعایت HSE و اخلاق حرفه‌ای',
				'answer'=> 'رفتار کارمند در محیط کار، سلامت روان، امنیت و مسئولیت‌پذیری چگونه است؟',
				'evidence' => 'ثبت HSE، گزارش امنیت روانی، بازخورد رفتاری و مسئولیت‌پذیری.',
				'source'   => 'Edmondson (1999). Psychological Safety; کد اخلاق حرفه‌ای.',
				'rubric'   => array(
					1 => 'نقض حاد HSE/اخلاق یا رفتار تهدیدکننده امنیت روانی.',
					2 => 'رفتار ناهنجار موردی؛ مسئولیت‌پذیری ناقص.',
					3 => 'رعایت HSE، احترام و مسئولیت‌پذیری در سطح انتظار.',
					4 => 'الگوی رفتار حرفه‌ای، امنیت روانی و ارتقای فرهنگ ایمنی.',
				),
			),
			'customer_score' => array(
				'key'   => 'customer_score',
				'label' => 'اثر بر مشتری/ذی‌نفع',
				'short' => 'مشتری',
				'icon'  => '📈',
				'weight'=> 1.1,
				'kpi'   => 'رضایت مشتری داخلی/خارجی، زمان تحویل، کاهش شکایت',
				'answer'=> 'نتیجه کار کارمند چه اثری بر رضایت مشتری یا ذی‌نفع داشته است؟',
				'evidence' => 'نظرسنجی مشتری، شکایات، زمان تحویل و بازخورد ذی‌نفعان.',
				'source'   => 'EFQM Results (Client/People); مدل تعالی EFQM.',
				'rubric'   => array(
					1 => 'افزایش شکایت یا افت مستند رضایت ذی‌نفع.',
					2 => 'رضایت پایدار متوسط؛ پیگیری ضعیف شکایات.',
					3 => 'رضایت خوب و بهبود مستند زمان/کیفیت تحویل.',
					4 => 'اثر قابل اندازه‌گیری بر رضایت، کاهش شکایت و مشتری/ذی‌نفع.',
				),
			),
		);
	}

	/**
	 * Individual performance status classification.
	 *
	 * @param float $score Overall score 1-4.
	 * @return array
	 */
	public static function performance_status( $score ) {
		$score = max( 1, min( 4, (float) $score ) );
		if ( $score < 2.2 ) {
			return array( 'level' => 'نیازمند مداخله فوری', 'color' => '#b91c1c', 'likely' => 'خطر افت عملکرد، انگیزه و احتمال ترک شغل بالاست.' );
		}
		if ( $score < 2.75 ) {
			return array( 'level' => 'در حال توسعه', 'color' => '#d97706', 'likely' => 'پتانسیل بهبود وجود دارد؛ بازخورد SBI و مربی‌گری OSKAR اولویت دارد.' );
		}
		if ( $score < 3.35 ) {
			return array( 'level' => 'مؤثر', 'color' => '#2563eb', 'likely' => 'عملکرد مطابق انتظار است؛ تثبیت و توسعه مهارت‌های کلیدی.' );
		}
		return array( 'level' => 'پیشرو', 'color' => '#0f766e', 'likely' => 'الگوی عملکردی و مربی همتا برای سایر کارکنان است.' );
	}

	/**
	 * Compute role-level performance summary from stored rows.
	 *
	 * @param array $rows Performance rows.
	 * @return array
	 */
	public static function performance_data( $rows = array() ) {
		if ( empty( $rows ) && class_exists( 'Coachroom_OD_DB' ) ) {
			$rows = Coachroom_OD_DB::get_performances();
		}
		$dims  = self::performance_dimensions();
		$items = array();

		$sum_overall = 0.0;
		$sum_eff = 0.0;
		$count = 0;
		$feedback_given = 0;
		$oskar_used = 0;

		foreach ( (array) $rows as $row ) {
			$overall = isset( $row->overall_score ) ? (float) $row->overall_score : 2.5;
			$status  = self::performance_status( $overall );
			$eff     = isset( $row->coaching_effectiveness ) ? (float) $row->coaching_effectiveness : 1;
			$growth  = isset( $row->growth_score ) ? (float) $row->growth_score : 1;
			$evidence_raw = isset( $row->scores_evidence ) ? $row->scores_evidence : '';
			$evidence_map = json_decode( $evidence_raw, true );
			if ( ! is_array( $evidence_map ) ) {
				$evidence_map = array();
			}

			$scores = array(
				'results_score' => isset( $row->results_score ) ? (float) $row->results_score : 2.5,
				'quality_score' => isset( $row->quality_score ) ? (float) $row->quality_score : 2.5,
				'teamwork_score'=> isset( $row->teamwork_score ) ? (float) $row->teamwork_score : 2.5,
				'learning_score'=> isset( $row->learning_score ) ? (float) $row->learning_score : 2.5,
				'behavior_score'=> isset( $row->behavior_score ) ? (float) $row->behavior_score : 2.5,
				'customer_score'=> isset( $row->customer_score ) ? (float) $row->customer_score : 2.5,
			);
			$evidence = array();
			foreach ( $scores as $key => $score ) {
				$evidence[ $key ] = isset( $evidence_map[ $key ] ) ? (string) $evidence_map[ $key ] : '';
			}

			$weak = array_keys( $scores );
			usort( $weak, function ( $a, $b ) use ( $scores ) {
				return $scores[ $a ] <=> $scores[ $b ];
			} );
			$weak = array_slice( $weak, 0, 2 );
			$weak_labels = array();
			foreach ( $weak as $key ) {
				$weak_labels[] = isset( $dims[ $key ]['label'] ) ? $dims[ $key ]['label'] : $key;
			}
			$first_weak = isset( $dims[ $weak[0] ] ) ? $dims[ $weak[0] ] : array( 'label' => 'عملکرد کل', 'kpi' => 'شاخص عملکرد' );
			$target_scale = min( 10, max( 1, round( $scores[ $weak[0] ] / 4 * 10 ) + 2 ) );

			$items[] = array(
				'id'          => (int) $row->id,
				'cycle_id'    => (int) $row->cycle_id,
				'department'  => isset( $row->department ) ? $row->department : '',
				'employee_name'=> isset( $row->employee_name ) ? $row->employee_name : '',
				'employee_role'=> isset( $row->employee_role ) ? $row->employee_role : '',
				'supervisor_name'=> isset( $row->supervisor_name ) ? $row->supervisor_name : '',
				'evaluator_role'=> isset( $row->evaluator_role ) ? $row->evaluator_role : '',
				'period'      => isset( $row->period ) ? $row->period : '',
				'scores'      => $scores,
				'evidence'    => $evidence,
				'overall'     => $overall,
				'status'      => $status,
				'feedback_given' => ! empty( $row->feedback_given ),
				'sbi_quality' => isset( $row->sbi_quality ) ? (float) $row->sbi_quality : 1,
				'oskar_used'  => ! empty( $row->oskar_used ),
				'coaching_effectiveness' => $eff,
				'growth_score' => $growth,
				'notes'       => isset( $row->notes ) ? $row->notes : '',
				'created_at'  => isset( $row->created_at ) ? $row->created_at : '',
				'weak'        => $weak,
				'weak_labels' => $weak_labels,
				'plan'        => array(
					'okr_objective' => 'ارتقای «' . $first_weak['label'] . '» در نقش ' . ( isset( $row->employee_role ) ? $row->employee_role : 'کارمند' ) . ' و واحد ' . ( isset( $row->department ) ? $row->department : '' ),
					'okr_krs'       => array(
						'ثبت ۲ شاهد عینی در ' . $first_weak['label'] . ' در ۳۰ روز آینده.',
						'رسیدن از نمره ' . round( $scores[ $weak[0] ], 1 ) . ' به ' . min( 4, round( ( $target_scale / 10 ) * 4, 1 ) ) . ' در بازارزیابی دوره بعد.',
					),
					'oskar'         => array(
						'Outcome'  => 'نتیجه مطلوب: بهبود مستند در ' . $first_weak['label'] . '.',
						'Scale'    => 'مقیاس فعلی: ' . round( $scores[ $weak[0] ] * 2.5, 1 ) . ' از ۱۰؛ شواهد ثبت‌شده: ' . ( $evidence[ $weak[0] ] ? $evidence[ $weak[0] ] : 'مستند عینی ثبت شود.' ),
						'Know-how' => 'دانش/مهارت موردنیاز: ' . $first_weak['kpi'] . ' + جلسه ۱:۱ با بازخورد SBI.',
						'Affirm'   => 'نقطه قوت: ' . ( $scores[ $weak[1] ] >= 2.75 ? 'عملکرد بالاتر در ' . $weak_labels[1] : 'پایبندی به برنامه فعلی' ) . '؛ گام کوچک: ثبت یک اقدام مشخص.',
						'Review'   => 'بازبینی: جلسه ۱:۱ در دو هفته آینده + بازارزیابی OKR در ۳۰ روز.',
					),
				),
			);

			$sum_overall += $overall;
			$sum_eff += $eff;
			$count++;
			if ( ! empty( $row->feedback_given ) ) {
				$feedback_given++;
			}
			if ( ! empty( $row->oskar_used ) ) {
				$oskar_used++;
			}
		}

		$average = $count ? round( $sum_overall / $count, 2 ) : 1.0;
		$avg_eff = $count ? round( $sum_eff / $count, 2 ) : 1.0;
		$average_status = self::performance_status( $average );
		$low = array_filter( $items, function ( $item ) {
			return $item['overall'] < 2.75;
		} );
		$low = array_values( $low );
		usort( $low, function ( $a, $b ) {
			return $a['overall'] <=> $b['overall'];
		} );

		$unit_summary = array();
		foreach ( $items as $item ) {
			$dept = $item['department'] ? $item['department'] : 'نامشخص';
			if ( ! isset( $unit_summary[ $dept ] ) ) {
				$unit_summary[ $dept ] = array(
					'name'    => $dept,
					'count'   => 0,
					'sum'     => 0.0,
					'feedback'=> 0,
					'oskar'   => 0,
				);
			}
			$unit_summary[ $dept ]['count']++;
			$unit_summary[ $dept ]['sum'] += $item['overall'];
			if ( $item['feedback_given'] ) {
				$unit_summary[ $dept ]['feedback']++;
			}
			if ( $item['oskar_used'] ) {
				$unit_summary[ $dept ]['oskar']++;
			}
		}
		$units = array();
		foreach ( $unit_summary as $name => $unit ) {
			$avg = $unit['count'] ? round( $unit['sum'] / $unit['count'], 2 ) : 1.0;
			$units[] = array(
				'name'    => $name,
				'count'   => $unit['count'],
				'average' => $avg,
				'status'  => self::performance_status( $avg ),
				'feedback_rate' => $unit['count'] ? round( ( $unit['feedback'] / $unit['count'] ) * 100, 1 ) : 0,
				'oskar_rate' => $unit['count'] ? round( ( $unit['oskar'] / $unit['count'] ) * 100, 1 ) : 0,
			);
		}
		usort( $units, function ( $a, $b ) {
			return $a['average'] <=> $b['average'];
		} );

		$sources = array();
		foreach ( $dims as $key => $dim ) {
			$sources[ $key ] = isset( $dim['source'] ) ? $dim['source'] : '';
		}

		return array(
			'count'        => $count,
			'average'      => $average,
			'status'       => $average_status,
			'feedback_given' => $feedback_given,
			'oskar_used'   => $oskar_used,
			'feedback_rate'=> $count ? round( ( $feedback_given / $count ) * 100, 1 ) : 0,
			'oskar_rate'   => $count ? round( ( $oskar_used / $count ) * 100, 1 ) : 0,
			'avg_effectiveness' => $avg_eff,
			'low'          => $low,
			'items'        => $items,
			'units'        => $units,
			'dimensions'   => $dims,
			'sources'      => $sources,
			'model'        => self::oskar(),
			'diagnosis'    => $count
				? 'بر اساس ' . $count . ' ارزیابی عملکرد فردی/سازمانی، میانگین عملکرد ' . $average . ' از ۴ و اثربخشی مربی‌گری سرپرستان ' . $avg_eff . ' از ۴ است. نرخ بازخورد SBI: ' . round( ( $feedback_given / $count ) * 100, 1 ) . '٪، نرخ استفاده از OSKAR: ' . round( ( $oskar_used / $count ) * 100, 1 ) . '٪. نمره‌ها بر اساس سطح‌های استاندارد شاهد‌محور ثبت شده‌اند و با داده ارزیابی سازمانی کالیبره می‌شوند.'
				: 'هنوز ارزیابی عملکرد فردی/سازمانی ثبت نشده است؛ ابتدا ارزیابی سازمانی را در تب «ارزیابی سازمانی» تکمیل کنید، سپس عملکرد کارکنان را با سطح‌های استاندارد و مستند عینی ثبت کنید تا بازخورد SBI و مربی‌گری OSKAR داده‌محور فعال شود.',
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
			'agility'              => array( 'model' => 'چابکی سازمانی و ساختار تطبیقی', 'source' => 'Worley, C. G. & Lawler, E. E. (2006). Designing Organizations That Are Built to Change.' ),
			'mckinsey7s'           => array( 'model' => 'چارچوب 7S مک‌کنزی', 'source' => 'Peters, T. & Waterman, R. (1982); McKinsey 7S Framework.' ),
			'active_listening'     => array( 'model' => 'مهارت‌های گفت‌وگو و مربی‌گری', 'source' => 'Rogers, C. R. & Farson, R. E. (1957). Active Listening.' ),
			'questioning'          => array( 'model' => 'پرسش‌گری و سازمان یادگیرنده', 'source' => 'Senge, P. M. (1990). The Fifth Discipline.' ),
			'feedback'             => array( 'model' => 'بازخورد رفتاری', 'source' => 'Center for Creative Leadership; SBI model.' ),
			'performance_eval'     => array( 'model' => 'مدیریت عملکرد و OKR', 'source' => 'Doerr, J. (2018). Measure What Matters.' ),
			'psychological_safety' => array( 'model' => 'امنیت روانی تیم', 'source' => 'Edmondson, A. C. (1999). Psychological Safety and Learning Behavior in Work Teams.' ),
			'learning_culture'     => array( 'model' => 'سازمان یادگیرنده', 'source' => 'Senge, P. M. (1990). The Fifth Discipline.' ),
			'coaching_culture'     => array( 'model' => 'مربی‌گری عملکردی', 'source' => 'Wilson, C. (2014). Performance Coaching; OSKAR model.' ),
			'weisbord_goals'       => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976). Organizational Diagnosis.' ),
			'weisbord_structure'   => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'weisbord_relationships' => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'weisbord_rewards'     => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'weisbord_leadership'  => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'weisbord_helping'     => array( 'model' => 'مدل شش‌جعبه‌ای وایزبورد', 'source' => 'Weisbord, M. R. (1976).' ),
			'attitude_satisfaction' => array( 'model' => 'مدل نگرش شغلی منابع انسانی صنعت نفت', 'source' => 'برنامه راهبردی توسعه منابع انسانی صنعت نفت در افق ۱۴۱۰.' ),
			'attitude_commitment'  => array( 'model' => 'تعهد سازمانی', 'source' => 'Meyer, J. P. & Allen, N. J. (1991). A three-component conceptualization of organizational commitment.' ),
			'attitude_other'       => array( 'model' => 'عدالت سازمانی، به‌زیستی و تمایل به ماندگاری', 'source' => 'Greenberg, J. (1987). Organizational Justice.' ),
		);
	}

	/**
	 * Multi-perspective diagnostic matrix.
	 *
	 * @param array $score_map  Dimension scores.
	 * @param array $weisbord   Weisbord diagnostic.
	 * @param array $efqm       EFQM data.
	 * @param array $strategy   Adaptive strategy data.
	 * @param array $attitude   Attitude model data.
	 * @param array $hr1410     Horizon 1410 data.
	 * @return array
	 */
	public static function model_matrix( $score_map, $weisbord, $efqm, $strategy, $attitude = array(), $hr1410 = array(), $mckinsey7s = array() ) {
		$wave       = self::wave_from_score( self::weighted_average( $score_map ) );
		$waves      = self::waves();
		$strategies = self::strategies();

		$matrix = array(
			array(
				'key'      => 'maturity',
				'title'    => 'موج بلوغ سازمانی',
				'color'    => isset( $waves[ $wave ]['color'] ) ? $waves[ $wave ]['color'] : '#0d9488',
				'diagnosis'=> 'سازمان در "' . $waves[ $wave ]['title'] . '" قرار دارد؛ داده‌های ' . count( self::questions() ) . ' سؤالی ابعاد ساختاری و فرهنگی (شامل رسمیت و چابکی مجزا) در کنار ' . count( self::weisbord_questions() ) . ' سؤال تشخیصی شش‌جعبه وایزبورد وضعیت فعلی را نشان می‌دهند.',
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
			array(
				'key'      => 'attitude',
				'title'    => 'مدل نگرش شغلی',
				'color'    => '#0f766e',
				'diagnosis'=> ( isset( $attitude['diagnosis'] ) ? $attitude['diagnosis'] : 'در انتظار داده نگرش شغلی.' ),
				'strategies' => array(),
				'note'     => 'رضایت، تعهد و سایر نگرش‌ها → بهبود عملکرد کارکنان → رضایت مشتریان → سودآوری.',
			),
			array(
				'key'      => 'hr1410',
				'title'    => 'برنامه راهبردی منابع انسانی نفت ۱۴۱۰',
				'color'    => '#d97706',
				'diagnosis'=> isset( $hr1410['overall'] )
					? 'هم‌راستایی با چشم‌انداز ۱۴۱۰: ' . $hr1410['overall'] . ' از ۴ (فاصله ' . $hr1410['gap'] . ' تا هدف). ' . $hr1410['vision']
					: 'در انتظار داده کافی.',
				'strategies' => array(),
				'note'     => 'کانون تمرکز: برند کارفرمایی، توسعه‌یافتگی، جذابیت و بهره‌وری.',
			),
			array(
				'key'      => 'mckinsey7s',
				'title'    => 'قطب‌نمای 7S مک‌کنزی',
				'color'    => '#0e7490',
				'diagnosis'=> isset( $mckinsey7s['overall'] )
					? 'شاخص 7S: ' . $mckinsey7s['overall'] . ' از ۴ (فاصله ' . $mckinsey7s['gap'] . ' تا وضعیت مطلوب). ' . $mckinsey7s['diagnosis']
					: 'پس از تکمیل ارزیابی، قطب‌نمای 7S محاسبه می‌شود.',
				'strategies' => array(),
				'note'     => 'Strategy, Structure, Systems, Skills, Staff, Style of Leadership, Shared Values.',
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
			} elseif ( 'attitude' === $row['key'] && isset( $attitude['low'] ) ) {
				foreach ( $attitude['low'] as $g ) {
					$titles[] = 'تقویت ' . $g['short'];
				}
				$titles[] = 'OKR بهبود عملکرد کارکنان';
			} elseif ( 'hr1410' === $row['key'] && isset( $hr1410['priority'] ) ) {
				foreach ( $hr1410['priority'] as $comp ) {
					$titles[] = 'تقویت ' . $comp['short'];
				}
				$titles[] = 'شاخص‌های رصد ۱۴۱۰';
			} elseif ( 'mckinsey7s' === $row['key'] && isset( $mckinsey7s['priority'] ) ) {
				foreach ( $mckinsey7s['priority'] as $comp ) {
					$titles[] = 'تقویت ' . $comp['label'];
				}
				$titles[] = 'پایش دوره‌ای 7S';
			}
			$matrix[ $idx ]['strategies'] = array_slice( array_values( array_unique( array_filter( $titles ) ) ), 0, 3 );
		}

		return array(
			'matrix'  => $matrix,
			'methods' => array(
				'موج بلوغ سازمانی' => $waves[ $wave ]['short'],
				'EFQM'          => ( isset( $efqm['level'] ) ? $efqm['level'] : '—' ),
				'Weisbord'      => ( isset( $weisbord['level'] ) ? $weisbord['level'] : '—' ),
				'نگرش شغلی'     => ( isset( $attitude['level'] ) ? $attitude['level'] : '—' ),
				'افق ۱۴۱۰'      => ( isset( $hr1410['level'] ) ? $hr1410['level'] : '—' ),
				'7S مک‌کنزی'    => ( isset( $mckinsey7s['overall'] ) ? $mckinsey7s['overall'] . ' از ۴' : '—' ),
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
		$aqquestions  = self::attitude_questions();

		$scope_map = array(
			'overall' => array(
				'label' => 'کل ارزیابی (' . ( count( $questions ) + count( $wquestions ) + count( $aqquestions ) ) . ' سؤال)',
				'items' => array_merge(
					array_values( array_unique( wp_list_pluck( $questions, 'key' ) ) ),
					array_values( array_unique( wp_list_pluck( $wquestions, 'key' ) ) ),
					array_values( array_unique( wp_list_pluck( $aqquestions, 'key' ) ) )
				),
			),
			'maturity' => array(
				'label' => 'ابعاد بلوغ/ساختار (' . count( $dimensions ) . ' بُعد)',
				'items' => array_values( array_unique( wp_list_pluck( $questions, 'key' ) ) ),
			),
			'weisbord' => array(
				'label' => 'شش جعبه وایزبورد (' . count( $wquestions ) . ' سؤال)',
				'items' => array_values( array_unique( wp_list_pluck( $wquestions, 'key' ) ) ),
			),
			'attitude' => array(
				'label' => 'مدل نگرش شغلی (' . count( $aqquestions ) . ' سؤال)',
				'items' => array_values( array_unique( wp_list_pluck( $aqquestions, 'key' ) ) ),
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
	 * Practical execution guide for managers and supervisors.
	 *
	 * Short, actionable explanations of the actions and tools referenced across
	 * the roadmap, OKR dashboard and management report. Kept in one place so the
	 * Blog tab, Reports tab and glossary pop-ups stay consistent.
	 *
	 * @return array
	 */
	public static function execution_guide() {
		return array(
			'title' => 'راهنمای اجرای اقدامات و ابزارهای پلتفرم',
			'intro' => 'این راهنما برای مدیران، سرپرستان و مربیان سازمانی طراحی شده است تا اقدامات پیشنهادی پلتفرم را ساده، عملی و قابل اندازه‌گیری اجرا کنند. هر کارت شامل زمان اجرا، مسئول، گام‌های اجرایی و شاخص موفقیت است.',
			'tools' => array(
				'roadmap' => array(
					'title'   => 'نقشه راه ۳۰/۶۰/۹۰ روزه',
					'icon'    => '🗺',
					'term'    => 'OKR',
					'when'    => 'فاز ۱ تا ۹۰: بلافاصله پس از امتیازدهی نتایج ارزیابی.',
					'owner'   => 'مدیران ارشد + HR + سرپرستان واحدها.',
					'steps'   => array(
						'روز ۱-۳۰: امنیت و ساختار را با «جلسه بدون سرزنش»، «منشور اختیار» و «AAR» پایه‌ریزی کنید.',
						'روز ۳۱-۶۰: بازخورد SBI و جلسات ۱:۱ را منظم کنید و OKR واحدها را در داشبورد ثبت کنید.',
						'روز ۶۱-۹۰: بازارزیابی، کالیبراسیون عملکرد و مستندسازی درس‌آموخته‌ها را انجام دهید.',
					),
					'kpi'     => 'تکمیل ۳ مرحله در ۹۰ روز و افزایش نمره شاخص‌های اولویت‌دار در دوره بعد.',
				),
				'sbi' => array(
					'title'   => 'بازخورد SBI',
					'icon'    => '💬',
					'term'    => 'SBI',
					'when'    => 'فاز ۳۱-۶۰ و بعد از هر جلسه کاری یا رویداد مهم.',
					'owner'   => 'هر سرپرست/مربی در جلسه ۱:۱ با همکار.',
					'steps'   => array(
						'Situation: موقعیت و زمان دقیق اتفاق را بدون قضاوت بیان کنید.',
						'Behavior: رفتار قابل مشاهده را توصیف کنید، نه نیت یا شخصیت فرد.',
						'Impact: اثر آن رفتار بر تیم، مشتری یا نتیجه را شرح دهید.',
						'Next: از همکار بخواهید گام بعدی را خودش تعیین کند و آن را پیگیری کنید.',
					),
					'kpi'     => 'نرخ برگزاری جلسات ۱:۱ و ثبت بازخورد مستند؛ کاهش بازخوردهای مبهم.',
				),
				'oskar' => array(
					'title'   => 'مربی‌گری عملکردی OSKAR',
					'icon'    => '🌱',
					'term'    => 'OSKAR',
					'when'    => 'فاز ۶۰-۹۰ یا هر زمان که آستانه آمادگی مربی‌گری فعال شد.',
					'owner'   => 'مربی سازمانی / سرپرست آموزش‌دیده.',
					'steps'   => array(
						'Outcome: نتیجه مطلوب و قابل مشاهده را به‌صورت مثبت تعریف کنید.',
						'Scale: با نمره ۱ تا ۱۰ مشخص کنید امروز کجاست و داده/شواهد چیست.',
						'Know-how: چه دانش، مهارت و پشتیبانی برای رسیدن به نمره بالاتر لازم است.',
						'Affirm: نقاط قوت، پیشرفت و اقدام کوچک بعدی را تأیید و برنامه‌ریزی کنید.',
						'Review: زمان و شاخص مرور پیشرفت را مشخص و پیگیری کنید.',
					),
					'kpi'     => 'تعداد جلسات مربی‌گری مؤثر، تحقق نمره مقیاس و پیشرفت اهداف توسعه فردی.',
				),
				'okr' => array(
					'title'   => 'OKR و داشبورد شاخص‌ها',
					'icon'    => '🎯',
					'term'    => 'OKR',
					'when'    => 'فاز ۳۱-۶۰ و سپس هر دوره ۹۰ روزه.',
					'owner'   => 'HR + معاونت توسعه سازمانی + مدیران واحدها.',
					'steps'   => array(
						'یک Objective کیفی و الهام‌بخش برای ضعیف‌ترین شاخص انتخاب کنید.',
						'برای هر Objective دو تا سه Key Result کمی تعریف کنید.',
						'شاخص‌ها را در داشبورد ثبت و هر ۳۰ روز بازبینی کنید.',
						'در پایان دوره، پلتفرم را بازارزیابی و امتیازها را مقایسه کنید.',
					),
					'kpi'     => 'تحقق ۷۰٪ یا بیشتر نتایج کلیدی در پایان دوره؛ کاهش فاصله تا موج هدف.',
				),
				'aar' => array(
					'title'   => 'جلسه AAR و بانک درس‌آموخته',
					'icon'    => '📋',
					'term'    => 'AAR',
					'when'    => 'پس از هر پروژه، حادثه، تغییر یا رویداد مهم.',
					'owner'   => 'مدیر پروژه + تیم + نماینده توسعه سازمانی.',
					'steps'   => array(
						'چه چیزی باید اتفاق می‌افتاد؟',
						'چه چیزی واقعاً اتفاق افتاد؟',
						'چرا تفاوت وجود داشت؟',
						'چه چیزی را در دفعه بعد حفظ یا تغییر می‌دهیم؟',
					),
					'kpi'     => 'تعداد جلسات AAR ثبت‌شده و تعداد اقدامات بهبود مستند.',
				),
				'raci' => array(
					'title'   => 'منشور اختیار و RACI',
					'icon'    => '🧭',
					'term'    => 'RACI',
					'when'    => 'فاز ۱-۳۰: هم‌زمان با ساده‌سازی ساختار.',
					'owner'   => 'معاونت توسعه سازمانی + مدیران واحدها.',
					'steps'   => array(
						'برای هر تصمیم مهم، مسئول (R) و پاسخگو (A) را مشخص کنید.',
						'نقش مشورت‌دهنده (C) و اطلاع‌گیرنده (I) را شفاف کنید.',
						'منشور را در جلسه تیم اعلام و برای ۳۰ روز اجرا کنید.',
						'در انتهای ماه، گلوگاه‌های تصمیم را اصلاح کنید.',
					),
					'kpi'     => 'کاهش زمان تصویب تصمیمات و تعداد ارجاع‌های سلسله‌مراتبی.',
				),
				'one2one' => array(
					'title'   => 'جلسه ۱:۱ سرپرست-همکار',
					'icon'    => '🤝',
					'term'    => '1:1',
					'when'    => 'هفتگی یا حداقل هر ۲ هفته؛ فاز ۳۱-۶۰ و پس از آن ادامه دارد.',
					'owner'   => 'سرپرست مستقیم.',
					'steps'   => array(
						'بدون گوشی و در فضای امن برگزار کنید.',
						'۵۰٪ زمان را به شنیدن پیشرفت، موانع و نیازهای همکار اختصاص دهید.',
						'یک بازخورد SBI و یک پرسش OSKAR در هر جلسه به‌کار ببرید.',
						'قدم بعدی و پیگیری را در یادداشت مشترک ثبت کنید.',
					),
					'kpi'     => 'نرخ برگزاری منظم جلسات و میزان رضایت همکار از گفت‌وگو.',
				),
				'calibration' => array(
					'title'   => 'کالیبراسیون ارزیابی عملکرد',
					'icon'    => '⚖',
					'term'    => 'کالیبراسیون',
					'when'    => 'پایان هر دوره ۹۰ روزه و پیش از تصمیم‌های پاداش/ترفیع.',
					'owner'   => 'کمیته متشکل از HR + مدیران میانی.',
					'steps'   => array(
						'نمرات نقش‌ها و واحدها را به‌صورت ناشناس مرور کنید.',
						'سوگیری‌های شخصی و تفاوت سخت‌گیری بین واحدها را بحث کنید.',
						'شاخص‌های رفتاری و نتیجه‌ای را با شواهد هم‌سطح کنید.',
						'پس از توافق، نتایج و مسیر بهبود را با همکاران شفاف به اشتراک بگذارید.',
					),
					'kpi'     => 'کاهش اختلاف غیرمنطقی نمرات و افزایش اعتماد به فرایند ارزیابی.',
				),
			),
			'faq' => array(
				array( 'q' => 'چرا پلتفرم به‌جای یک پاسخ واحد، چند مدل را نشان می‌دهد؟', 'a' => 'چون هر مدل به یک سؤال متفاوت پاسخ می‌دهد: موج بلوغ به «سازمان در چه مرحله‌ای است؟»، EFQM به «تعالی فرایندها و نتایج چگونه است؟»، وایزبورد به «ساختار رسمی و غیررسمی چه وضعیتی دارد؟»، نگرش شغلی به «کارکنان چه احساس و رفتاری دارند؟» و افق ۱۴۱۰ به «چقدر به چشم‌انداز راهبردی منابع انسانی صنعت نفت نزدیک هستیم؟». ترکیب این پنج لنز، تصمیم را شفاف‌تر می‌کند.' ),
				array( 'q' => 'اگر یکی از گروه‌های نگرش پایین باشد، از کجا شروع کنیم؟', 'a' => 'از کمترین نمره شروع کنید؛ مثلاً اگر «سایر نگرش‌ها» (عدالت، میل به ترک، سلامت روان، تعادل) ضعیف باشد، ابتدا عدالت فرایندی و حمایت از سلامت روان را تقویت کنید، سپس به تعهد و رضایت برسید. پلتفرم همین اولویت را در کارت افق ۱۴۱۰ و OKR نشان می‌دهد.' ),
				array( 'q' => 'آیا اجرای همه اقدامات هم‌زمان لازم است؟', 'a' => 'خیر. موتور راهبرد فقط اقداماتی را انتخاب می‌کند که آستانه‌های آمادگی آنها تأیید شده باشد. مثلاً مربی‌گری سرپرستان فقط پس از امنیت روانی، شنیدن فعال و ساختار مناسب فعال می‌شود.' ),
				array( 'q' => 'چگونه بفهمیم بهبود واقعی رخ داده است؟', 'a' => 'شاخص‌های کلیدی (KPI) هر اقدام در داشبورد، گزارش مدیران و خروجی CSV ثبت می‌شود. در پایان دوره ۹۰ روزه، بازارزیابی و مقایسه فاصله تا موج هدف/افق ۱۴۱۰ نشان می‌دهد که بهبود پایدار بوده است.' ),
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
			'agility' => array(
				'slug'     => 'agility',
				'objective' => 'افزایش چابکی ساختاری با حفظ رسمیت لازم ایمنی و اخلاق',
				'krs'      => array(
					'فعال‌سازی مسیر تغییر ۷۲ ساعته برای رویه‌های قابل انعطاف.',
					'کاهش میانگین زمان اجرای تغییر از ۳۰ به ۷ روز.',
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
	 * @param array $strategy    Strategy data.
	 * @param array $attitude    Employee attitude data.
	 * @param array $hr1410      Horizon 1410 data.
	 * @return array
	 */
	public static function okr_systemic( $score_map, $departments, $roles, $strategy = array(), $attitude = array(), $hr1410 = array() ) {
		$okrs = self::okr_data( $score_map );

		// The coaching-oriented OKR is only included when the maturity data supports it.
		// If the organization is not ready yet, coaching is deferred to a later cycle.
		if ( ! empty( $strategy ) && empty( $strategy['coaching_recommended'] ) ) {
			$okrs = array_values( array_filter( $okrs, function ( $item ) {
				return 'coaching_culture' !== $item['slug'];
			} ) );
		}

		// Horizon-1410 monitoring OKR: keeps the strategic HR programme visible
		// every 90 days and ties the roadmap to a measurable gap.
		if ( ! empty( $hr1410 ) && isset( $hr1410['overall'] ) && $hr1410['overall'] < 3.35 ) {
			$krs = array(
				'افزایش امتیاز هم‌راستایی کل به‌حداقل ۳٫۳۵ در ۹۰ روز',
				'پایش سه جزء اولویت‌دار: ' . implode( '، ', array_map( function ( $c ) { return $c['short']; }, $hr1410['priority'] ) ),
			);
			$okrs[] = array(
				'slug'      => 'hr1410_alignment',
				'objective' => 'هم‌راستایی با برنامه راهبردی منابع انسانی صنعت نفت (افق ۱۴۱۰)',
				'krs'       => $krs,
				'score'     => $hr1410['overall'],
				'gap'       => $hr1410['gap'],
				'priority'  => $hr1410['overall'] < 2.75 ? 'O1 — اولویت فوری' : 'O2 — اولویت مهم',
				'owner'     => 'HR + معاونت توسعه سازمانی',
			);
		}

		// Attitude performance OKR: the model explicitly predicts profitability,
		// so the roadmap includes an objective to move the chain.
		if ( ! empty( $attitude ) && isset( $attitude['overall'] ) && $attitude['overall'] < 3.35 ) {
			$okrs[] = array(
				'slug'      => 'attitude_chain',
				'objective' => 'ارتقای نگرش کارکنان و زنجیره عملکرد-مشتری-سودآوری',
				'krs'       => array(
					'افزایش رضایت و تعهد به‌نمای زنجیره ارزش در ۹۰ روز',
					'پایش شاخص عملکرد کارکنان و رضایت مشتریان: ' . ( isset( $attitude['chain']['customer_satisfaction'] ) ? $attitude['chain']['customer_satisfaction'] : '—' ),
				),
				'score'     => $attitude['overall'],
				'gap'       => isset( $attitude['gap'] ) ? $attitude['gap'] : max( 0, round( 3.35 - $attitude['overall'], 2 ) ),
				'priority'  => $attitude['overall'] < 2.75 ? 'O1 — اولویت فوری' : 'O2 — اولویت مهم',
				'owner'     => 'HR + سرپرستان + روابط صنعتی',
			);
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
					'جلسه ۱:۱ هفتگی با پرسش‌های OSKAR و بازخورد SBI',
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

		$maturity_count = count( self::questions() );
		$weisbord_count = count( self::weisbord_questions() );
		$attitude_count = count( self::attitude_questions() );
		$summary_text = 'بر اساس ارزیابی ' . ( $maturity_count + $weisbord_count + $attitude_count ) . ' سؤالی ثبت‌شده (' . $maturity_count . ' سؤال بلوغ/ساختاری شامل رسمیت و چابکی مجزا + ' . $weisbord_count . ' سؤال تشخیص وایزبورد + ' . $attitude_count . ' سؤال نگرش شغلی) و قطب‌نمای هفت‌مؤلفه‌ای 7S، سازمان در ' . $wave_title . ' قرار دارد و ' . $gap_text . ' نمره تا آستانه موج هدف فاصله دارد. '
			. 'این نتیجه در کنار مدل تعالی EFQM، مدل شش‌جعبه‌ای وایزبورد، مدل نگرش شغلی و چارچوب 7S مک‌کنزی بررسی شده است. '
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

		// Employee attitude questions are aggregated in their own groups so the
		// satisfaction/commitment/other → performance chain can be modelled.
		$attitude_groups = self::attitude_groups();
		$aq_to_group     = array();
		foreach ( self::attitude_questions() as $aq ) {
			$aq_to_group[ $aq['key'] ] = $aq['dimension'];
		}
		$a_scores = array();
		foreach ( $attitude_groups as $slug => $group ) {
			$a_scores[ $slug ] = array(
				'sum' => 0.0,
				'n'   => 0,
			);
		}
		$attitude_score_map = array();

		$by_department = array();
		$by_role       = array();
		$count_rows    = 0;
		$last_date     = '';
		$last_department = '';
		$last_role       = '';
		$last_slug       = '';

		foreach ( $rows as $row ) {
			$is_weisbord = isset( $wq_to_box[ $row->question_key ] );
			$is_attitude = isset( $aq_to_group[ $row->question_key ] );
			$score       = max( 1, min( 4, (float) $row->score ) );
			$dept_key    = $row->department ? $row->department : 'نامشخص';
			$role_key    = $row->assessor_role ? $row->assessor_role : 'کارمند';
			$count_rows ++;

			if ( $is_attitude ) {
				$group = $aq_to_group[ $row->question_key ];
				if ( isset( $a_scores[ $group ] ) ) {
					$a_scores[ $group ]['sum'] += $score;
					$a_scores[ $group ]['n']++;
				}
				if ( ! empty( $row->created_at ) ) {
					$last_date     = $row->created_at;
					$last_department = $dept_key;
					$last_role       = $role_key;
					$last_slug       = $group;
				}
				continue;
			}

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

		// Normalize employee attitude group scores.
		foreach ( $attitude_groups as $slug => $group ) {
			$avg = 1.0;
			if ( isset( $a_scores[ $slug ]['n'] ) && $a_scores[ $slug ]['n'] > 0 ) {
				$avg = round( $a_scores[ $slug ]['sum'] / $a_scores[ $slug ]['n'], 2 );
			}
			$attitude_score_map[ $slug ] = $avg;
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

		// Hybrid assessment: 30 maturity + 18 Weisbord + 12 attitude = 60 items.
		// The pure maturity part remains the wavelet engine; the other lenses stay
		// separate so each model can be diagnosed independently.
		$w_score_map   = $weisbord_score_map;
		$a_score_map   = $attitude_score_map;
		$weisbord      = self::weisbord_data( $w_score_map );
		$attitude      = self::attitude_data( $a_score_map );
		$all_score_map = array_merge( $score_map, $w_score_map, $a_score_map );
		$hr1410        = self::hr1410_data( $all_score_map, $attitude );
		$mckinsey7s    = self::mckinsey7s_data( $all_score_map, $attitude, $target_wave, $hr1410 );
		$okr           = self::okr_systemic( $score_map, $departments, $roles, $strategy, $attitude, $hr1410 );
		$model_matrix  = self::model_matrix( $score_map, $weisbord, $efqm, $strategy, $attitude, $hr1410, $mckinsey7s );
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
			'attitude'       => $attitude,
			'hr1410'         => $hr1410,
			'mckinsey7s'     => $mckinsey7s,
			'model_matrix'   => $model_matrix,
			'reliability'    => $reliability,
			'performance'    => self::performance_data(),
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
