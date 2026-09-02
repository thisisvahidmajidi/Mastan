<?php
/**
 * Front-end render and markup for the shortcode.
 *
 * @package CoachRoom_OD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Coachroom_OD_Render
 */
class Coachroom_OD_Render {

	/**
	 * Instance.
	 *
	 * @var Coachroom_OD_Render|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return Coachroom_OD_Render
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Render the platform.
	 *
	 * @return string
	 */
	public function render() {
		wp_enqueue_style( 'cr-od-platform' );
		wp_enqueue_script( 'cr-od-platform' );

		$data      = Coachroom_OD_Helpers::dashboard_data();
		$config     = Coachroom_OD_Helpers::config();
		$waves      = Coachroom_OD_Helpers::waves();
		$dims       = Coachroom_OD_Helpers::dimensions();
		$validity   = Coachroom_OD_Helpers::validity_sources();
		$questions  = Coachroom_OD_Helpers::questions();
		$qopts      = Coachroom_OD_Helpers::question_options();
		$weisbord_boxes     = Coachroom_OD_Helpers::weisbord_boxes();
		$weisbord_questions = Coachroom_OD_Helpers::weisbord_questions();
		$strategy   = isset( $data['strategy'] ) ? $data['strategy'] : array();
		$weisbord   = isset( $data['weisbord'] ) ? $data['weisbord'] : array();
		$model_matrix = isset( $data['model_matrix'] ) ? $data['model_matrix'] : array();
		$reliability  = isset( $data['reliability'] ) ? $data['reliability'] : array();

		$strategy_titles = array();
		foreach ( (array) $strategy['selected'] as $st ) {
			$strategy_titles[] = isset( $st['title'] ) ? $st['title'] : '';
		}
		$strategy_titles = array_slice( $strategy_titles, 0, 3 );
		$coaching_rec = ! empty( $strategy['coaching_recommended'] );

		// Weak dimensions (lowest scores) from the measured data, used for evidence-based reporting.
		$weak_labels = array();
		if ( ! empty( $data['analysis']['weaknesses'] ) ) {
			foreach ( $data['analysis']['weaknesses'] as $weak ) {
				$weak_labels[] = isset( $weak['label'] ) ? $weak['label'] : '';
			}
			$weak_labels = array_slice( $weak_labels, 0, 4 );
		}

		// Distribute selected strategies into the 30/60/90 day roadmap by their gate.
		$road_phase1 = array();
		$road_phase2 = array();
		$road_phase3 = array();
		if ( ! empty( $strategy['selected'] ) ) {
			foreach ( $strategy['selected'] as $st ) {
				$gate = isset( $st['gate'] ) ? $st['gate'] : '';
				if ( 'safety' === $gate || 'structure' === $gate ) {
					$road_phase1[] = $st;
				} elseif ( 'performance' === $gate || 'network' === $gate ) {
					$road_phase2[] = $st;
				} elseif ( 'coaching' === $gate || 'sustainability' === $gate ) {
					$road_phase3[] = $st;
				} else {
					$road_phase2[] = $st;
				}
			}
		}

		wp_localize_script(
			'cr-od-platform',
			'crODData',
			array(
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'cr_od_nonce' ),
				'config'     => $config,
				'waves'      => $waves,
				'dimensions'       => $dims,
				'questions'        => $questions,
				'weisbordBoxes'    => $weisbord_boxes,
				'weisbordQuestions'=> $weisbord_questions,
				'data'             => $data,
			)
		);

		$img   = 'assets/img/';
		$brand = 'CoachRoom';

		ob_start();
		?>
		<div class="cr-od-root" id="cr-od-root" dir="rtl" lang="fa">
			<div class="cr-od-shell">

				<!-- Header -->
				<header class="cr-od-hero">
					<div class="cr-od-hero-inner">
						<div class="cr-od-hero-text">
							<div class="cr-od-badge"><?php echo esc_html( $config['industry'] ); ?></div>
							<h1 class="cr-od-title">پلتفرم توسعه سازمانی <span><?php echo esc_html( $brand ); ?></span></h1>
							<p class="cr-od-subtitle">از موج دوم بوروکراتیک به سازمان هم‌آفرین و یادگیرنده؛ ارزیابی داده‌محور ساختار، بازخورد، پرسش‌گری و انتخاب تطبیقی راهبردها (مربی‌گری سرپرستان در صورت آمادگی).</p>
							<div class="cr-od-hero-meta">
								<span><strong data-fa-num><?php echo esc_html( $data['summary']['responses'] ); ?></strong> ارزیابی ثبت‌شده</span>
								<span><strong id="cr-cycle-title"><?php echo esc_html( $data['summary']['cycle_title'] ); ?></strong></span>
								<span>به‌روزرسانی: <span id="cr-last-updated" data-fa-date><?php echo esc_html( $data['summary']['last_updated'] ? date_i18n( 'Y/m/d', strtotime( $data['summary']['last_updated'] ) ) : '—' ); ?></span></span>
								<span id="cr-last-save">آخرین ثبت:
									<strong id="cr-last-role"><?php echo esc_html( $data['summary']['last_role'] ? $data['summary']['last_role'] : '—' ); ?></strong>
									در <strong id="cr-last-dept"><?php echo esc_html( $data['summary']['last_department'] ? $data['summary']['last_department'] : '—' ); ?></strong>
								</span>
							</div>
						</div>
						<div class="cr-od-hero-visual">
							<img src="<?php echo esc_url( CR_OD_PLUGIN_URL . $img . 'hero-energy.jpg' ); ?>" alt="تیم حرفه‌ای صنعت انرژی و نفت" loading="lazy" />
						</div>
					</div>
				</header>

				<!-- Tabs -->
				<nav class="cr-od-tabs" role="tablist" aria-label="بخش‌های پلتفرم">
					<button type="button" class="cr-od-tab is-active" data-tab="dashboard" role="tab" aria-selected="true">داشبورد شاخص‌ها</button>
					<button type="button" class="cr-od-tab" data-tab="assessment" role="tab" aria-selected="false">ارزیابی سازمانی</button>
					<button type="button" class="cr-od-tab" data-tab="roadmap" role="tab" aria-selected="false">نقشه راه مربی‌گری</button>
					<button type="button" class="cr-od-tab" data-tab="departments" role="tab" aria-selected="false">واحدها و روند</button>
					<button type="button" class="cr-od-tab" data-tab="blog" role="tab" aria-selected="false">بلاگ و مبانی علمی</button>
					<button type="button" class="cr-od-tab" data-tab="reports" role="tab" aria-selected="false">گزارش مدیران</button>
				</nav>

				<main class="cr-od-content">

					<!-- DASHBOARD -->
					<section class="cr-od-panel is-active" id="cr-dashboard" role="tabpanel">
						<div class="cr-od-kpi-grid">
							<div class="cr-od-kpi cr-od-kpi-main">
								<span class="cr-od-kpi-label">امتیاز کلی توسعه سازمانی</span>
								<span class="cr-od-kpi-value" id="cr-overall" data-fa-num><?php echo esc_html( $data['summary']['overall'] ); ?></span>
								<span class="cr-od-kpi-range">از ۴.۰۰</span>
							</div>
							<div class="cr-od-kpi">
								<span class="cr-od-kpi-label">موج فعلی سازمان</span>
								<span class="cr-od-kpi-value cr-od-wave-value" id="cr-wave-label" style="color:<?php echo esc_attr( $data['summary']['wave_color'] ); ?>;"><?php echo esc_html( $data['summary']['wave_label'] ); ?></span>
								<span class="cr-od-kpi-range" id="cr-wave-desc"><?php echo esc_html( $data['summary']['wave_desc'] ); ?></span>
							</div>
							<div class="cr-od-kpi">
								<span class="cr-od-kpi-label">موج هدف برنامه</span>
								<span class="cr-od-kpi-value" id="cr-target-label" style="color:<?php echo esc_attr( $waves[ $data['summary']['target_wave'] ]['color'] ); ?>;"><?php echo esc_html( $waves[ $data['summary']['target_wave'] ]['title'] ); ?></span>
								<span class="cr-od-kpi-range">فاصله تا هدف: <span id="cr-gap" data-fa-num><?php echo esc_html( $data['summary']['target_gap'] ); ?></span> نمره</span>
							</div>
						</div>

						<article class="cr-od-card cr-od-card-wide">
							<h3 class="cr-od-card-title">سازگاری سازمان با مدل تعالی EFQM <span class="cr-od-card-sub">توانمندسازها ۵ / نتایج ۴</span></h3>
							<div class="cr-od-efqm-summary">
								<div class="cr-od-efqm-score">
									<span class="cr-od-efqm-score-num" id="cr-efqm-score" data-fa-num><?php echo esc_html( $data['efqm']['score'] ); ?></span>
									<span class="cr-od-efqm-score-range">از ۱۰۰۰ امتیاز EFQM</span>
								</div>
								<div class="cr-od-efqm-level">
									<span class="cr-od-kpi-label">سطح تعالی</span>
									<strong id="cr-efqm-level"><?php echo esc_html( $data['efqm']['level'] ); ?></strong>
								</div>
								<div class="cr-od-efqm-split">
									<span>توانمندسازها: <b id="cr-efqm-enablers" data-fa-num><?php echo esc_html( $data['efqm']['enablers'] ); ?></b> از ۴</span>
									<span>نتایج: <b id="cr-efqm-results" data-fa-num><?php echo esc_html( $data['efqm']['results'] ); ?></b> از ۴</span>
								</div>
							</div>
							<div class="cr-od-efqm-table" id="cr-efqm-table">
								<?php foreach ( $data['efqm']['criteria'] as $crit ) : ?>
									<div class="cr-od-efqm-row">
										<div class="cr-od-efqm-meta">
											<strong><?php echo esc_html( $crit['label'] ); ?></strong>
											<span class="cr-od-efqm-tag"><?php echo 'enabler' === $crit['group'] ? 'توانمندساز' : 'نتیجه'; ?></span>
										</div>
										<div class="cr-od-efqm-bar"><span style="width:<?php echo esc_attr( $crit['score'] * 25 ); ?>%"></span></div>
										<div class="cr-od-efqm-num"><span data-fa-num><?php echo esc_html( $crit['points'] ); ?></span> / ۱۰۰۰</div>
									</div>
								<?php endforeach; ?>
							</div>
						</article>

						<article class="cr-od-card cr-od-card-wide" id="cr-weisbord-diagnosis">
							<h3 class="cr-od-card-title">تشخیص سازمان با مدل شش‌جعبه‌ای وایزبورد <span class="cr-od-card-sub">Weisbord, 1976</span></h3>
							<div class="cr-od-weisbord-summary">
								<div><span class="cr-od-kpi-label">امتیاز کل شش جعبه</span><strong id="cr-weisbord-overall" data-fa-num><?php echo esc_html( isset( $weisbord['overall'] ) ? $weisbord['overall'] : '—' ); ?></strong><small>از ۴</small></div>
								<div><span class="cr-od-kpi-label">سطح تشخیص</span><strong id="cr-weisbord-level"><?php echo esc_html( isset( $weisbord['level'] ) ? $weisbord['level'] : '—' ); ?></strong><small>مدل تشخیصی وایزبورد</small></div>
								<div><span class="cr-od-kpi-label">جعبه‌های بحرانی</span><strong id="cr-weisbord-low-count" data-fa-num><?php echo esc_html( isset( $weisbord['low'] ) ? count( $weisbord['low'] ) : 0 ); ?></strong><small>زیر ۲٫۷۵</small></div>
							</div>
							<p class="cr-od-analysis-text" id="cr-weisbord-diagnosis-text"><?php echo esc_html( isset( $weisbord['diagnosis'] ) ? $weisbord['diagnosis'] : 'پس از تکمیل ۱۸ سؤال وایزبورد، نتیجه تشخیصی نمایش داده می‌شود.' ); ?></p>
							<div class="cr-od-weisbord-grid">
								<?php if ( ! empty( $weisbord['boxes'] ) ) : ?>
									<?php foreach ( $weisbord['boxes'] as $box ) : ?>
										<div class="cr-od-weisbord-box" style="--box-color:<?php echo esc_attr( $box['color'] ); ?>">
											<div class="cr-od-weisbord-box-head">
												<span class="cr-od-q-icon"><?php echo esc_html( $box['icon'] ); ?></span>
												<div><strong><?php echo esc_html( $box['label'] ); ?></strong><small><?php echo esc_html( $box['key_question'] ); ?></small></div>
												<b class="cr-od-weisbord-score" data-fa-num><?php echo esc_html( $box['score'] ); ?></b>
											</div>
											<p class="cr-od-weisbord-likely"><?php echo esc_html( $box['likely'] ); ?></p>
											<span class="cr-od-weisbord-status" style="color:<?php echo esc_attr( $box['color'] ); ?>"><?php echo esc_html( $box['status'] ); ?></span>
											<small class="cr-od-weisbord-efqm">اتصال به EFQM: <?php echo esc_html( $box['efqm'] ); ?></small>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</article>

						<article class="cr-od-card cr-od-card-wide" id="cr-model-matrix">
							<h3 class="cr-od-card-title">ماتریس چندمدلی تشخیص و راهبرد <span class="cr-od-card-sub">موج / EFQM / وایزبورد</span></h3>
							<div class="cr-od-model-matrix">
								<?php if ( ! empty( $model_matrix['matrix'] ) ) : ?>
									<?php foreach ( $model_matrix['matrix'] as $row ) : ?>
										<div class="cr-od-model-row">
											<div class="cr-od-model-head"><strong style="color:<?php echo esc_attr( $row['color'] ); ?>"><?php echo esc_html( $row['title'] ); ?></strong></div>
											<div class="cr-od-model-body">
												<p><?php echo esc_html( $row['diagnosis'] ); ?></p>
												<?php if ( ! empty( $row['strategies'] ) ) : ?>
													<div class="cr-od-model-strategies">
														<?php foreach ( $row['strategies'] as $s_title ) : ?>
															<span><?php echo esc_html( $s_title ); ?></span>
														<?php endforeach; ?>
													</div>
												<?php else : ?>
													<small>راهبرد پس از تکمیل ارزیابی انتخاب می‌شود.</small>
												<?php endif; ?>
												<small class="cr-od-model-note"><?php echo esc_html( $row['note'] ); ?></small>
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</article>

						<article class="cr-od-card cr-od-card-wide" id="cr-reliability">
							<h3 class="cr-od-card-title">روایی و پایایی ارزیابی <span class="cr-od-card-sub">ضریب آلفای کرونباخ</span></h3>
							<div class="cr-od-reliability-grid">
								<?php foreach ( array( 'overall' => 'کل ارزیابی', 'maturity' => 'ابعاد بلوغ', 'weisbord' => 'شش جعبه وایزبورد' ) as $scope => $title ) : ?>
									<?php $s = isset( $reliability['scales'][ $scope ] ) ? $reliability['scales'][ $scope ] : array(); ?>
									<div class="cr-od-reliability-card">
										<strong><?php echo esc_html( $title ); ?></strong>
										<span class="cr-od-reliability-alpha"><b data-fa-num><?php echo esc_html( null !== ( $s['alpha'] ?? null ) ? $s['alpha'] : '—' ); ?></b> α</span>
										<small><?php echo esc_html( ( $s['n'] ?? 0 ) . ' پاسخ‌دهنده کامل — ' . ( $s['note'] ?? 'در انتظار داده کافی' ) ); ?></small>
									</div>
								<?php endforeach; ?>
							</div>
							<p class="cr-od-analysis-text">برای روایی محتوا، هر بُعد به یک مدل و منبع معتبر نگاشت شده است (مینتزبرگ، برنز و استالکر، راجرز، سنژ، ادموندسون، کرنل، دوئر، وایزبورد و وایتمور). پایایی با آلفای کرونباخ در صورت وجود ۳ یا بیشتر پاسخ‌دهنده کامل محاسبه و در گزارش مدیران شفاف نمایش داده می‌شود.</p>
							<div class="cr-od-validity-sources">
								<?php foreach ( $validity as $slug => $src ) : ?>
									<span><?php echo esc_html( $src['model'] ); ?> — <?php echo esc_html( $src['source'] ); ?></span>
								<?php endforeach; ?>
							</div>
						</article>

						<div class="cr-od-grid cr-od-grid-2">
							<article class="cr-od-card">
								<h3 class="cr-od-card-title">رادار بلوغ سازمانی <span class="cr-od-card-sub">مقایسه وضعیت فعلی با هدف</span></h3>
								<div class="cr-od-chart-wrap">
									<canvas id="crRadarChart" aria-label="نمودار رادار بلوغ سازمانی"></canvas>
								</div>
							</article>
							<article class="cr-od-card">
								<h3 class="cr-od-card-title">نقاط قوت و اولویت‌های بهبود <span class="cr-od-card-sub">مرتب‌شده بر اساس کمترین نمره</span></h3>
								<div class="cr-od-ranked">
									<?php foreach ( $data['dimensions'] as $dim ) : ?>
										<div class="cr-od-ranked-row" data-slug="<?php echo esc_attr( $dim['slug'] ); ?>">
											<div class="cr-od-ranked-top">
												<span class="cr-od-ranked-name"><?php echo esc_html( $dim['label'] ); ?></span>
												<span class="cr-od-ranked-score" data-fa-num><?php echo esc_html( $dim['score'] ); ?></span>
											</div>
											<div class="cr-od-bar"><span style="width:<?php echo esc_attr( $dim['score'] * 25 ); ?>%"></span></div>
										</div>
									<?php endforeach; ?>
								</div>
							</article>
						</div>

						<div class="cr-od-grid cr-od-grid-2">
							<article class="cr-od-card">
								<h3 class="cr-od-card-title">درصد تحقق موج‌های سازمانی</h3>
								<div class="cr-od-chart-wrap"><canvas id="crWaveChart" aria-label="نمودار درصد تحقق موج‌ها"></canvas></div>
							</article>
							<article class="cr-od-card">
								<h3 class="cr-od-card-title">مهارت‌های کلیدی سرپرستان <span class="cr-od-card-sub">گوش دادن فعال، پرسش‌گری، بازخورد</span></h3>
								<div class="cr-od-chart-wrap"><canvas id="crSkillsChart" aria-label="نمودار مهارت‌های مربیگری"></canvas></div>
							</article>
						</div>
					</section>

					<!-- ASSESSMENT -->
					<section class="cr-od-panel" id="cr-assessment" role="tabpanel" hidden>
						<div class="cr-od-assessment-intro">
							<div class="cr-od-intro-text">
								<h2>ارزیابی وضعیت موجود سازمان</h2>
						<p>این فرم شامل <strong data-fa-num><?php echo esc_html( count( $questions ) + count( $weisbord_questions ) ); ?></strong> سؤال دقیق است: <strong data-fa-num><?php echo esc_html( count( $questions ) ); ?></strong> سؤال در ۱۰ بُعد ساختاری و فرهنگی برای سنجش موج سازمانی، به‌همراه <strong data-fa-num><?php echo esc_html( count( $weisbord_questions ) ); ?></strong> سؤال تشخیصی بر اساس مدل شش‌جعبه‌ای وایزبورد برای دیدن ساختار رسمی و غیررسمی هم‌زمان. پاسخ‌ها مبنای تشخیص بلوغ، انتخاب راهبرد و سنجش روایی/پایایی می‌شوند؛ هیچ راهبردی از قبل به سازمان تحمیل نمی‌شود.</p>
							<div class="cr-od-scale-info">
									<span><b>۱</b> وضعیت ضعیف / بوروکراتیک</span>
									<span><b>۲</b> در حال بهبود</span>
									<span><b>۳</b> مناسب / هم‌آفرین</span>
									<span><b>۴</b> پیشرو / یادگیرنده</span>
								</div>
							</div>
							<img src="<?php echo esc_url( CR_OD_PLUGIN_URL . $img . 'team-coaching.jpg' ); ?>" alt="مربی‌گری تیمی در صنعت انرژی" loading="lazy" />
						</div>

						<form class="cr-od-form" id="cr-od-assessment-form">
							<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'cr_od_nonce' ) ); ?>" />
							<div class="cr-od-form-meta">
								<label>واحد سازمانی
									<input type="text" name="department" placeholder="مثال: عملیات، تولید و پالایش" />
								</label>
								<label>نقش ارزیاب
									<select name="assessor_role">
										<option>کارمند</option>
										<option>سرپرست</option>
										<option>مدیر میانی</option>
										<option>مربی سازمانی</option>
									</select>
								</label>
							</div>

							<?php foreach ( $dims as $slug => $dim ) : ?>
								<?php
								$dim_questions = array();
								foreach ( $questions as $q ) {
									if ( $q['dimension'] === $slug ) {
										$dim_questions[] = $q;
									}
								}
								if ( empty( $dim_questions ) ) {
									continue;
								}
								?>
								<fieldset class="cr-od-question" data-dimension="<?php echo esc_attr( $slug ); ?>">
									<legend>
										<span class="cr-od-q-icon"><?php echo esc_html( $dim['icon'] ); ?></span>
										<span class="cr-od-q-label"><?php echo esc_html( $dim['label'] ); ?></span>
										<span class="cr-od-q-indicator"><?php echo esc_html( $dim['indicator'] ); ?></span>
										<span class="cr-od-q-count"><?php echo esc_html( count( $dim_questions ) ); ?> سؤال</span>
									</legend>

									<?php foreach ( $dim_questions as $q ) : ?>
										<div class="cr-od-sub-question" data-question-key="<?php echo esc_attr( $q['key'] ); ?>">
											<div class="cr-od-question-text">
												<span class="cr-od-qq"><?php echo esc_html( $q['label'] ); ?></span>
											</div>
											<div class="cr-od-levels">
												<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
													<label class="cr-od-option">
														<input type="radio" name="<?php echo esc_attr( $q['key'] ); ?>" value="<?php echo esc_attr( $i ); ?>" />
														<span><?php echo esc_html( $i ); ?></span>
														<small><?php echo esc_html( $qopts[ $i ] ); ?></small>
													</label>
												<?php endfor; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</fieldset>
							<?php endforeach; ?>

							<fieldset class="cr-od-question cr-od-weisbord-section">
								<legend>
									<span class="cr-od-q-icon">◫</span>
									<span class="cr-od-q-label">تشخیص شش‌جعبه‌ای وایزبورد (Weisbord's Six-Box)</span>
									<span class="cr-od-q-indicator">برای سازمان‌های سلسله‌مراتبی؛ ساختار رسمی و غیررسمی را هم‌زمان می‌بیند.</span>
									<span class="cr-od-q-count"><?php echo esc_html( count( $weisbord_questions ) ); ?> سؤال</span>
								</legend>

								<?php foreach ( $weisbord_boxes as $box_slug => $box ) : ?>
									<fieldset class="cr-od-sub-box" data-box="<?php echo esc_attr( $box_slug ); ?>">
										<legend><strong><?php echo esc_html( $box['label'] ); ?></strong> <small><?php echo esc_html( $box['key_question'] ); ?></small></legend>
										<?php foreach ( $weisbord_questions as $wq ) : ?>
											<?php if ( $wq['dimension'] !== $box_slug ) { continue; } ?>
											<div class="cr-od-sub-question" data-question-key="<?php echo esc_attr( $wq['key'] ); ?>">
												<div class="cr-od-question-text"><span class="cr-od-qq"><?php echo esc_html( $wq['label'] ); ?></span></div>
												<div class="cr-od-levels">
													<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
														<label class="cr-od-option">
															<input type="radio" name="<?php echo esc_attr( $wq['key'] ); ?>" value="<?php echo esc_attr( $i ); ?>" />
															<span><?php echo esc_html( $i ); ?></span>
															<small><?php echo esc_html( $qopts[ $i ] ); ?></small>
														</label>
													<?php endfor; ?>
												</div>
											</div>
										<?php endforeach; ?>
									</fieldset>
								<?php endforeach; ?>
							</fieldset>

							<div class="cr-od-form-submit">
								<button type="submit" class="cr-od-btn cr-od-btn-primary">ثبت ارزیابی و بروزرسانی داشبورد</button>
								<span class="cr-od-form-status" role="status"></span>
							</div>
						</form>
					</section>

					<!-- ROADMAP -->
					<section class="cr-od-panel" id="cr-roadmap" role="tabpanel" hidden>
						<div class="cr-od-roadmap-intro">
							<div class="cr-od-intro-text">
								<h2>نقشه راه تطبیقی توسعه سازمانی</h2>
								<p>راهبردها اکنون بر اساس بلوغ واقعی سازمان از داده‌های ارزیابی انتخاب می‌شوند. اگر شاخص‌های پیش‌نیاز (امنیت روانی، ساختار، بازخورد) آماده نباشند، ابتدا همان‌ها تقویت می‌شوند و راهبرد «ارتقای سرپرستان به مربیان عملکردی» فقط زمانی اضافه می‌شود که شواهد آن را تأیید کند.</p>
								<div class="cr-od-strategy-note" id="cr-strategy-note">
									<?php
									$coaching_rec = isset( $strategy['coaching_recommended'] ) && $strategy['coaching_recommended'];
									$strategy_text = $coaching_rec
										? 'سازمان در شرایط آماده‌گی برای مربی‌گری است؛ راهبرد «ارتقای نقش سرپرستان به مربیان عملکردی» فعال شده است.'
										: ( isset( $strategy['coaching_reason'] ) ? 'راهبرد مربی‌گری فعال نشده است. ' . esc_html( $strategy['coaching_reason'] ) : '' );
									?>
									<strong><?php echo esc_html( $strategy_text ); ?></strong>
								</div>
							</div>
							<img src="<?php echo esc_url( CR_OD_PLUGIN_URL . $img . 'online-review.jpg' ); ?>" alt="بازبینی عملکرد آنلاین در صنعت انرژی" loading="lazy" />
						</div>

						<div class="cr-od-roadmap-grid">
							<article class="cr-od-phase">
								<div class="cr-od-phase-num">۳۰</div>
								<h3>روز ۱ تا ۳۰ — پایه و امنیت</h3>
								<ul id="cr-roadmap-phase-30">
									<?php if ( ! empty( $road_phase1 ) ) : ?>
										<?php foreach ( $road_phase1 as $st ) : ?>
											<li><?php echo esc_html( $st['title'] ); ?>: <?php echo esc_html( implode( '، ', array_slice( $st['actions'], 0, 2 ) ) ); ?></li>
										<?php endforeach; ?>
									<?php else : ?>
										<li>نقشه راه مرحله ۳۰ بر اساس داده‌های پایه از همین فرم محاسبه می‌شود.</li>
									<?php endif; ?>
								</ul>
							</article>
							<article class="cr-od-phase">
								<div class="cr-od-phase-num">۶۰</div>
								<h3>روز ۳۱ تا ۶۰ — عمل و شواهد</h3>
								<ul id="cr-roadmap-phase-60">
									<?php if ( ! empty( $road_phase2 ) ) : ?>
										<?php foreach ( $road_phase2 as $st ) : ?>
											<li><?php echo esc_html( $st['title'] ); ?>: <?php echo esc_html( implode( '، ', array_slice( $st['actions'], 0, 2 ) ) ); ?></li>
										<?php endforeach; ?>
									<?php else : ?>
										<li>راهبردهای شواهدمحور در این بازه بر اساس نتایج ارزیابی انتخاب می‌شوند.</li>
									<?php endif; ?>
								</ul>
							</article>
							<article class="cr-od-phase">
								<div class="cr-od-phase-num">۹۰</div>
								<h3>روز ۶۱ تا ۹۰ — تثبیت و ارتقا</h3>
								<ul id="cr-roadmap-phase-90">
									<?php if ( ! empty( $road_phase3 ) ) : ?>
										<?php foreach ( $road_phase3 as $st ) : ?>
											<li><?php echo esc_html( $st['title'] ); ?>: <?php echo esc_html( implode( '، ', array_slice( $st['actions'], 0, 2 ) ) ); ?></li>
										<?php endforeach; ?>
									<?php else : ?>
										<li>بازارزیابی شاخص‌ها، کمیته کالیبراسیون و بانک درس‌آموخته‌ها.</li>
									<?php endif; ?>
								</ul>
							</article>
						</div>

						<div class="cr-od-roadmap-actions" id="cr-roadmap-actions">
							<h3>اقدامات اولویت‌دار بر اساس داده‌های فعلی</h3>
							<div id="cr-roadmap-actions-list">
							<?php if ( ! empty( $data['recommendations'] ) ) : ?>
								<?php foreach ( $data['recommendations'] as $rec ) : ?>
									<div class="cr-od-action">
										<div class="cr-od-action-head">
											<span class="cr-od-action-icon"><?php echo esc_html( $dims[ $rec['slug'] ]['icon'] ); ?></span>
											<div>
												<h4><?php echo esc_html( $rec['title'] ); ?></h4>
												<span class="cr-od-action-priority"><?php echo esc_html( $rec['level'] ); ?></span>
											</div>
											<span class="cr-od-action-score"><span data-fa-num><?php echo esc_html( $rec['score'] ); ?></span>/۴</span>
										</div>
										<p><?php echo esc_html( $rec['action'] ); ?></p>
										<div class="cr-od-action-meta">
											<span>مسئول: <?php echo esc_html( $rec['owner'] ); ?></span>
											<span>شاخص: <?php echo esc_html( $rec['kpi'] ); ?></span>
											<span>ابزار: <?php echo esc_html( $rec['tool'] ); ?></span>
										</div>
									</div>
								<?php endforeach; ?>
							<?php else : ?>
								<div class="cr-od-empty">شاخص‌ها در محدوده هدف هستند؛ برای اولویت‌بندی دقیق‌تر، ابتدا ارزیابی را تکمیل کنید.</div>
							<?php endif; ?>
							</div>
						</div>

						<div class="cr-od-roadmap-actions" id="cr-okr-roadmap">
							<h3>نقشه راه مدیریت عملکرد و هدف‌گذاری OKR</h3>
							<p class="cr-od-efqm-intro">OKR از داده‌های ارزیابی استخراج می‌شود: هر نقطه ضعف، به یک هدف (O) و نتایج کلیدی (KR) قابل سنجش تبدیل می‌شود تا مدیران تصمیم‌گیری شواهدمحور داشته باشند.</p>
							<div class="cr-od-okr-summary">
								<div class="cr-od-okr-focus">
									<strong>تمرکز سیستمی</strong>
									<span>واحد اولویت‌دار: <?php echo esc_html( ! empty( $data['okr']['focus_unit']['name'] ) ? $data['okr']['focus_unit']['name'] : '—' ); ?> <b data-fa-num><?php echo esc_html( ! empty( $data['okr']['focus_unit']['overall'] ) ? $data['okr']['focus_unit']['overall'] : '—' ); ?></b></span>
									<span>نقش اولویت‌دار: <?php echo esc_html( ! empty( $data['okr']['focus_role']['name'] ) ? $data['okr']['focus_role']['name'] : '—' ); ?> <b data-fa-num><?php echo esc_html( ! empty( $data['okr']['focus_role']['overall'] ) ? $data['okr']['focus_role']['overall'] : '—' ); ?></b></span>
									<span>دوره: <?php echo esc_html( $data['okr']['cycle'] ); ?></span>
								</div>
							</div>
							<div class="cr-od-okr-grid" id="cr-okr-grid">
								<?php if ( ! empty( $data['okr']['items'] ) ) : ?>
									<?php foreach ( $data['okr']['items'] as $okr ) : ?>
										<div class="cr-od-okr-card">
											<div class="cr-od-okr-head">
												<span class="cr-od-action-priority"><?php echo esc_html( $okr['priority'] ); ?></span>
												<span class="cr-od-action-score"><span data-fa-num><?php echo esc_html( $okr['score'] ); ?></span>/۴</span>
											</div>
											<h4><?php echo esc_html( $okr['objective'] ); ?></h4>
											<div class="cr-od-okr-krs">
												<?php foreach ( $okr['krs'] as $kr ) : ?>
													<div><span class="cr-od-kr-badge">KR</span> <?php echo esc_html( $kr ); ?></div>
												<?php endforeach; ?>
											</div>
											<span class="cr-od-okr-owner">مسئول اجرا: <?php echo esc_html( $okr['owner'] ); ?></span>
										</div>
									<?php endforeach; ?>
								<?php else : ?>
									<div class="cr-od-empty">شاخص‌ها در محدوده هدف هستند؛ OKR تثبیت و بهبود مستمر تعریف شود.</div>
								<?php endif; ?>
							</div>
						</div>

						<div class="cr-od-roadmap-actions" id="cr-efqm-roadmap">
							<h3>نقشه راه هماهنگ با مدل EFQM (فرم RADAR)</h3>
							<p class="cr-od-efqm-intro">ترتیب استاندارد EFQM برای بهبود پایدار: ابتدا نتایج هدف، سپس رویکرد، استقرار، ارزیابی و در نهایت اصلاح و یادگیری. OKR داخل همین چرخه قرار می‌گیرد تا نتایج عملکرد فردی و سازمانی قابل سنجش باشند.</p>
							<div class="cr-od-radar-grid" id="cr-radar-grid">
								<?php foreach ( $data['analysis']['efqm_roadmap'] as $step ) : ?>
									<div class="cr-od-radar-step">
										<div class="cr-od-radar-letter"><?php echo esc_html( $step['letter'] ); ?></div>
										<h4><?php echo esc_html( $step['title'] ); ?></h4>
										<p><?php echo esc_html( $step['action'] ); ?></p>
										<span class="cr-od-radar-owner">مسئول: <?php echo esc_html( $step['owner'] ); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</section>

					<!-- DEPARTMENTS / TREND -->
					<section class="cr-od-panel" id="cr-departments" role="tabpanel" hidden>
						<div class="cr-od-grid cr-od-grid-2">
							<article class="cr-od-card">
								<h3 class="cr-od-card-title">وضعیت واحدهای سازمانی <span class="cr-od-card-sub">میانگین کل</span></h3>
								<div class="cr-od-chart-wrap"><canvas id="crDeptChart" aria-label="نمودار وضعیت واحدها"></canvas></div>
							</article>
							<article class="cr-od-card">
								<h3 class="cr-od-card-title">روند بهبود در دوره‌های ارزیابی <span class="cr-od-card-sub">امتیاز کل</span></h3>
								<div class="cr-od-chart-wrap"><canvas id="crTrendChart" aria-label="نمودار روند بهبود"></canvas></div>
							</article>
						</div>
						<article class="cr-od-card">
							<h3 class="cr-od-card-title">جدول مقایسه واحدها و ابعاد</h3>
							<div class="cr-od-table-wrap">
								<table class="cr-od-table" id="cr-dept-table">
									<thead>
										<tr>
											<th>واحد</th>
											<?php foreach ( $data['dimensions'] as $dim ) : ?>
												<th title="<?php echo esc_attr( $dim['label'] ); ?>"><?php echo esc_html( $dim['short'] ); ?></th>
											<?php endforeach; ?>
											<th>موج</th>
										</tr>
									</thead>
									<tbody id="cr-dept-tbody">
										<?php if ( ! empty( $data['departments'] ) ) : ?>
											<?php foreach ( $data['departments'] as $dept ) : ?>
												<tr>
													<td><?php echo esc_html( $dept['name'] ); ?></td>
													<?php foreach ( $data['dimensions'] as $dim ) : ?>
														<td data-fa-num><?php echo esc_html( $dept['scores'][ $dim['slug'] ] ?? 1.0 ); ?></td>
													<?php endforeach; ?>
													<td><span class="cr-od-wave-chip" style="color:<?php echo esc_attr( $waves[ $dept['wave'] ]['color'] ); ?>"><?php echo esc_html( $waves[ $dept['wave'] ]['short'] ); ?></span></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr><td colspan="<?php echo esc_attr( count( $data['dimensions'] ) + 2 ); ?>">هنوز داده‌ای ثبت نشده است.</td></tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</article>

						<article class="cr-od-card">
							<h3 class="cr-od-card-title">بررسی دیدگاه نقش‌های سازمانی <span class="cr-od-card-sub">همه موقعیت‌ها بدون خطا</span></h3>
							<div class="cr-od-chart-wrap"><canvas id="crRoleChart" aria-label="نمودار میانگین دیدگاه نقش‌های سازمانی"></canvas></div>
							<div class="cr-od-table-wrap" style="margin-top:14px;">
								<table class="cr-od-table" id="cr-role-table">
									<thead>
										<tr><th>نقش سازمانی</th><th>میانگین کل</th><th>تعداد سطر ارزیابی</th><th>موج ادراک‌شده</th></tr>
									</thead>
									<tbody id="cr-role-tbody">
										<?php if ( ! empty( $data['roles'] ) ) : ?>
											<?php foreach ( $data['roles'] as $role ) : ?>
												<tr>
													<td><?php echo esc_html( $role['name'] ); ?></td>
													<td data-fa-num><?php echo esc_html( $role['overall'] ); ?></td>
													<td data-fa-num><?php echo esc_html( $role['count'] ); ?></td>
													<td><span class="cr-od-wave-chip" style="color:<?php echo esc_attr( $waves[ $role['wave'] ]['color'] ); ?>"><?php echo esc_html( $waves[ $role['wave'] ]['short'] ); ?></span></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr><td colspan="4">تاکنون ارزیابی نقش ثبت نشده است.</td></tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
							<div class="cr-od-table-wrap" style="margin-top:18px;">
								<h4 class="cr-od-subheading">مقایسه امتیاز تکتک ابعاد به تفکیک نقش</h4>
								<table class="cr-od-table" id="cr-role-dim-table">
									<thead>
										<tr>
											<th>نقش سازمانی</th>
											<?php foreach ( $data['dimensions'] as $dim ) : ?>
												<th title="<?php echo esc_attr( $dim['label'] ); ?>"><?php echo esc_html( $dim['short'] ); ?></th>
											<?php endforeach; ?>
											<th>موج</th>
										</tr>
									</thead>
									<tbody id="cr-role-dim-tbody">
										<?php if ( ! empty( $data['roles'] ) ) : ?>
											<?php foreach ( $data['roles'] as $role ) : ?>
												<tr>
													<td><?php echo esc_html( $role['name'] ); ?></td>
													<?php foreach ( $data['dimensions'] as $dim ) : ?>
														<td data-fa-num><?php echo esc_html( $role['scores'][ $dim['slug'] ] ?? 1.0 ); ?></td>
													<?php endforeach; ?>
													<td><span class="cr-od-wave-chip" style="color:<?php echo esc_attr( $waves[ $role['wave'] ]['color'] ); ?>"><?php echo esc_html( $waves[ $role['wave'] ]['short'] ); ?></span></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr><td colspan="<?php echo esc_attr( count( $data['dimensions'] ) + 2 ); ?>">تاکنون ارزیابی نقش ثبت نشده است.</td></tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</article>
					</section>

					<!-- BLOG / SCIENTIFIC BASE -->
					<section class="cr-od-panel" id="cr-blog" role="tabpanel" hidden>
						<div class="cr-od-blog-hero">
							<div>
								<span class="cr-od-badge cr-od-badge-blog">مبانی علمی &amp; مرور مفهوم</span>
								<h2>موج‌های تحول سازمانی و نقش توسعه سازمانی</h2>
								<p>خلاصه‌ای کاربردی از نظریه‌های سازمان برای مدیرانی که می‌خواهند بدانند سازمان امروز در کدام موج قرار دارد، به کدام موج می‌رود و چرا «توسعه منابع انسانی» راهبرد اصلی این حرکت است.</p>
							</div>
							<img src="<?php echo esc_url( CR_OD_PLUGIN_URL . $img . 'team-coaching.jpg' ); ?>" alt="مربی‌گری تیمی در صنعت انرژی و نفت" loading="lazy" />
						</div>

						<div class="cr-od-grid cr-od-grid-2">
							<article class="cr-od-card cr-od-blog-card">
								<h3 class="cr-od-card-title">چرا توسعه سازمانی؟</h3>
								<p>سازمان‌ها برای بقا باید با فشارهای محیطی، فناوری و انتظارات نوین نیروی کار سازگار شوند. نظریه‌های کلاسیک (تیلور و وبر) بر کارایی و کنترل، و نظریه‌های نوین (برنز و استالکر، مینتزبرگ، سنژ، ادموندسون و لالو) بر <strong>انعطاف، یادگیری، امنیت روانی و هم‌آفرینی</strong> تأکید می‌کنند. توسعه سازمانی یعنی حرکت عمدی از ساختارهای سخت و متمرکز به سمت ساختارهایی که هم عملکرد فردی و هم یادگیری جمعی را بالا می‌برند.</p>
							</article>
							<article class="cr-od-card cr-od-blog-card">
								<h3 class="cr-od-card-title">نقش سرپرستان و مربی‌گری (مشروط به آمادگی)</h3>
								<p>تغییر ساختار بدون تغییر رفتار ممکن نیست. سرپرستان حلقه اتصال مدیریت و کارکنان‌اند؛ اگر آن‌ها به‌جای دستوردهی، <strong>گوش دادن فعال، پرسش‌گری واگرا و بازخورد ساختارمند</strong> را تمرین کنند، رسمیت کم، تمرکز تصمیم واگذار و سیلوهای ساختاری کاهش می‌یابد. اما این پلتفرم «سرپرست → مربی عملکردی» را پیش‌فرض نمی‌کند؛ این راهبرد فقط وقتی از داده‌های بلوغ استخراج می‌شود که امنیت روانی، شنیدن فعال و ساختار به آستانه آمادگی رسیده باشند.</p>
							</article>
							<article class="cr-od-card cr-od-blog-card">
								<h3 class="cr-od-card-title">مدل شش‌جعبه‌ای وایزبورد (Weisbord, 1976)</h3>
								<p>وایزبورد برای تشخیص سازمان‌های بزرگ سلسله‌مراتبی، شش «جعبه» کلیدی را پیشنهاد می‌کند: <strong>اهداف، ساختار، روابط، پاداش، رهبری و مکانیسم‌های کمکی</strong>. این مدل از آنجا اهمیت دارد که ساختار رسمی و غیررسمی را هم‌زمان می‌بیند و به مدیر کمک می‌کند بداند مشکل صرفاً در «نمودار سازمانی» نیست، بلکه در هدف‌های مبهم، روابط بی‌اعتماد یا پاداش نادرست هم می‌تواند باشد. در این پلتفرم، نتیجه این مدل به‌صورت جداگانه و کنار EFQM نمایش داده می‌شود تا مدیر بتواند سه رویکرد را با هم مقایسه کند.</p>
								<blockquote>«اگر جعبه‌های اهداف، ساختار، روابط، پاداش و مکانیسم‌های کمکی با هم ناسازگار باشند، رهبری باید تعادل بین آن‌ها را برقرار کند.» — ماروین وایزبورد</blockquote>
							</article>
							<article class="cr-od-card cr-od-blog-card">
								<h3 class="cr-od-card-title">تضمین روایی و پایایی</h3>
								<p>روایی محتوا یعنی هر سؤال به یک مدل/منبع علمی مشخص متصل باشد؛ در این پلتفرم ابعاد بلوغ به مینتزبرگ، برنز و استالکر، راجرز، سنژ، ادموندسون، دوئر و وایتمور و شش جعبه به وایزبورد نگاشت شده‌اند. پایایی نیز با <strong>ضریب آلفای کرونباخ</strong> برای سه مقیاس «کل ارزیابی»، «ابعاد بلوغ» و «شش جعبه وایزبورد» محاسبه می‌شود. اگر کمتر از ۳ پاسخ‌دهنده کامل وجود داشته باشد، پلتفرم به‌جای عدد گمراه‌کننده، پیام «داده کافی نیست» نمایش می‌دهد.</p>
							</article>
						</div>

						<div class="cr-od-blog-waves">
							<h3>مرور پنج موج تحول سازمانی</h3>
							<div class="cr-od-wave-table">
								<div class="cr-od-wave-row cr-od-wave-head">
									<div>موج</div><div>ویژگی اصلی</div><div>کانون کنترل</div><div>نشانه‌ها در سازمان</div>
								</div>
								<div class="cr-od-wave-row">
									<div><strong>موج ۱</strong><small>سنتی / دستوری</small></div>
									<div>کنترل &amp; کارایی مبتنی بر دستور</div>
									<div>مدیریت عالی</div>
									<div>تمرکز شدید، عدم استقلال، اطاعت محور</div>
								</div>
								<div class="cr-od-wave-row">
									<div><strong>موج ۲</strong><small>بوروکراتیک</small></div>
									<div>قواعد، سلسله‌مراتب و مستندات</div>
									<div>قواعد + مدیران</div>
									<div>رسمیت زیاد، پیچیدگی زیاد، بازخورد و ارزیابی ذهنی</div>
								</div>
								<div class="cr-od-wave-row">
									<div><strong>موج ۳</strong><small>هم‌آفرین / شبکه‌ای</small></div>
									<div>تیم‌ها، پرسش‌گری و بازخورد فعال</div>
									<div>تیم‌ها + سرپرستان مربی</div>
									<div>واگذاری تصمیم، شکسته‌شدن سیلوها، امنیت روانی</div>
								</div>
								<div class="cr-od-wave-row">
									<div><strong>موج ۴</strong><small>یادگیرنده</small></div>
									<div>یادگیری مستمر، کالیبراسیون و تجربه‌گری</div>
									<div>شبکه توزیع‌شده</div>
									<div>بانک دانش، AAR، خودسازمان‌دهی، نوسازی مشترک</div>
								</div>
								<div class="cr-od-wave-row is-fifth">
									<div><strong>موج ۵</strong><small>انسانی و پایدار</small></div>
									<div>انسان‌محوری، تاب‌آوری، پایداری و داده‌محوری</div>
									<div>ذی‌نفعان + جامعه + سیاره</div>
									<div>سلامت و به‌زیستی، ESG، اقتصاد چرخشی، رهبری توانمندساز</div>
								</div>
							</div>
						</div>

						<div class="cr-od-blog-future">
							<div class="cr-od-intro-text">
								<h3>دورنمای موج پنجم: انسان‌محوری، تاب‌آوری و پایداری</h3>
								<p>موج پنجم از مرز «سازمان یادگیرنده» فراتر می‌رود و سازمان را مسئولیتی هم‌زمان در برابر <strong>انسان، جامعه و سیاره</strong> می‌داند. این موج تحت تأثیر اهداف توسعه پایدار (SDGs)، چارچوب‌های ESG، رویکرد سازمان‌های بازآفرین (Regenerative) و پژوهش‌های به‌زیستی و تاب‌آوری است (Laloux, 2014; Schaufeli &amp; Bakker, 2004; World Economic Forum, 2020).</p>
							</div>
							<div class="cr-od-future-grid">
								<div class="cr-od-future-card">
									<h4>انسان‌محوری</h4>
									<p>کارکنان به‌عنوان «کل انسان» دیده می‌شوند؛ رهبری همدلانه، امنیت روانی، شنیده‌شدن و معناداری کار در مرکز طراحی سازمان قرار دارد.</p>
								</div>
								<div class="cr-od-future-card">
									<h4>تاب‌آوری</h4>
									<p>سازمان توان پیش‌بینی، پاسخ و بازگشت سریع از بحران‌ها را می‌سازد؛ تیم‌های خودسازمان‌ده، منابع انعطاف‌پذیر و فرهنگ یادگیری از خطا، ستون تاب‌آوری هستند.</p>
								</div>
								<div class="cr-od-future-card">
									<h4>پایداری &amp; ESG</h4>
									<p>عملکرد با سه‌گانه «سود، مردم، سیاره» سنجیده می‌شود؛ کاهش ردپای کربن، اقتصاد چرخشی، شفافیت گزارش‌دهی و عدالت اجتماعی بخشی از KPIهاست.</p>
								</div>
								<div class="cr-od-future-card">
									<h4>داده‌محوری &amp; تحول دیجیتال</h4>
									<p>تصمیم‌گیری با شاخص‌های زنده، هوش مصنوعی و داشبوردهای یکپارچه؛ ولی داده ابزار انسان‌محوری است، نه جایگزین قضاوت و اعتماد.</p>
								</div>
								<div class="cr-od-future-card">
									<h4>یادگیری پیوسته</h4>
									<p>مسیرهای رشد فردی و سازمانی بر پایه شایستگی، مربی‌گری و تسهیم دانش؛ آموزش به «یادگیری در جریان کار» تبدیل می‌شود.</p>
								</div>
								<div class="cr-od-future-card">
									<h4>رهبری مربی‌گر</h4>
									<p>مدیران به‌جای ناظر، «تسهیل‌گر رشد» هستند؛ گفت‌وگوهای توسعه‌ای، بازخورد دوسویه و هم‌آفرینی راهبردهای اصلی رهبری می‌شوند.</p>
								</div>
							</div>
						</div>

						<div class="cr-od-blog-okr">
							<div class="cr-od-intro-text">
								<h3>OKR: هدف‌گذاری و مدیریت عملکرد</h3>
								<p><strong>OKR</strong> (Objectives &amp; Key Results) یک روش ساده هدف‌گذاری است: هر هدف (O) کیفی و الهام‌بخش است و با ۳ تا ۵ نتیجه کلیدی (KR) قابل سنجش تعریف می‌شود. مثلاً در صورت آمادگی سازمان، هدف «سرپرستان به مربی تبدیل شوند» با KRهایی چون «۹۰٪ جلسات ۱:۱ بر اساس SBI» اندازه‌گیری می‌شود.</p>
								<p>در پلتفرم CoachRoom، OKR از داده ارزیابی ساخته می‌شود: هر بُعدی که نمره کمتری دارد، به یک <strong>Objective</strong> مشخص و <strong>Key Results</strong> قابل اندازه تبدیل می‌شود. تا زمانی که نمره به آستانه هدف (حدود ۳٫۳۵) نرسد، همان OKR باز می‌ماند و مدیران می‌دانند کدام واحد/نقش اولویت دارد.</p>
							</div>
							<div class="cr-od-okr-learn-grid">
								<div class="cr-od-efqm-learn-card"><h4>Objective</h4><p>هدف کیفی و کوتاه (معمولاً یک جمله) که به واحد یا تیم جهت می‌دهد و باید قابل فهم و انگیزشی باشد.</p><span>«به کجا می‌رویم؟»</span></div>
								<div class="cr-od-efqm-learn-card"><h4>Key Results</h4><p>نتایج کمی و مشخص که موفقیت هدف را نشان می‌دهند و باید عدد، سنجه و بازه زمانی داشته باشند.</p><span>«از کجا بفهمیم موفق شدیم؟»</span></div>
								<div class="cr-od-efqm-learn-card"><h4>Cadence / روتین</h4><p>بازبینی هفتگی (Check-in)، ارزیابی سه‌ماهه (Review) و هم‌ترازسازی سالانه؛ این چرخه OKR را زنده نگه می‌دارد.</p><span>«چه زمانی بازبینی کنیم؟»</span></div>
							</div>
							<div class="cr-od-efqm-learn-note">
								<strong>نحوه اتصال به EFQM و موج‌ها:</strong> OKR معیار EFQM «راهبرد و برنامه‌ریزی» را با «نتایج کارکنان و نتایج کلیدی» پیوند می‌زند. اگر OKRها به شاخص‌های ارزیابی گره بخورند، هر بهبود قابل اندازه است و سازمان به‌جای اراده‌محوری، به‌صورت سیستماتیک به سمت موج سوم/چهارم حرکت می‌کند.
							</div>
						</div>

						<div class="cr-od-blog-efqm">
							<div class="cr-od-intro-text">
								<h3>مدل تعالی EFQM به زبان ساده</h3>
								<p><strong>EFQM</strong> یک مدل خودارزیابی و بهبود سازمانی است که از ۹ معیار استفاده می‌کند: <strong>۵ توانمندساز</strong> (آنچه سازمان انجام می‌دهد) و <strong>۴ نتیجه</strong> (آنچه به‌دست می‌آورد). این مدل به مدیران کمک می‌کند به‌جای قضاوت سلیقه‌ای، با شواهد و شاخص‌ها تصمیم بگیرند.</p>
							</div>
							<div class="cr-od-efqm-learn-grid">
								<div class="cr-od-efqm-learn-card">
									<h4>۵ توانمندساز</h4>
									<p>۱. رهبری و حکمرانی — ۲. راهبرد — ۳. منابع انسانی و فرهنگ — ۴. شراکت‌ها و منابع — ۵. فرایندها و محصولات.</p>
									<span>«چگونه عمل می‌کنیم؟»</span>
								</div>
								<div class="cr-od-efqm-learn-card">
									<h4>۴ نتیجه</h4>
									<p>۱. مشتریان — ۲. کارکنان — ۳. جامعه و پایداری — ۴. نتایج کلیدی عملکرد.</p>
									<span>«چه نتیجه‌ای به‌دست می‌آوریم؟»</span>
								</div>
								<div class="cr-od-efqm-learn-card">
									<h4>منطق RADAR</h4>
									<p>نتایج هدف ← رویکرد ← استقرار ← ارزیابی ← بهبود. این چرخه باعث می‌شود بعد از هر دوره ارزیابی، نقشه راه اصلاح شود.</p>
									<span>«چگونه بهبود را پایدار کنیم؟»</span>
								</div>
							</div>
							<div class="cr-od-efqm-learn-note">
								<strong>کاربرد در این پلتفرم:</strong> امتیازهای ارزیابی ۱ تا ۴ (از ۴۸ سؤال: ۳۰ سؤال بلوغ + ۱۸ سؤال وایزبورد) به ۹ معیار EFQM نگاشت و به امتیاز ۰ تا ۱۰۰۰ تبدیل می‌شود. سپس نقشه راه ۹۰ روزه، اقدامات اولویت‌دار و گزارش مدیران بر اساس همین معیارها تهیه می‌شود. اولویت شروع از داده‌های بلوغ تعیین می‌شود؛ برای مثال اگر امنیت روانی یا ساختار ضعیف باشد، ابتدا همان‌ها تقویت و در صورت وجود آستانه آمادگی، راهبرد مربی‌گری سرپرستان به نقشه اضافه می‌شود.
							</div>
						</div>

						<article class="cr-od-card cr-od-blog-card">
							<h3 class="cr-od-card-title">اهمیت داده‌محوری و نقشه راه منابع انسانی</h3>
							<p>برای این‌که حرکت از موج دوم به موج‌های بالاتر «ادعا» نباشد بلکه «شواهد» باشد، ارزیابی‌های ساختاری و رفتار فردی باید به داده تبدیل و به‌صورت دوره‌ای مقایسه شوند. پلتفرم حاضر همین کار را انجام می‌دهد: ورودی ارزیابی → محاسبه وزن‌دار → تشخیص موج → اولویت‌بندی اقدامات → خروجی گزارش برای مدیران.</p>
							<blockquote>
								<strong>راهبردها به‌صورت تطبیقی از بلوغ سازمان انتخاب می‌شوند:</strong> ۱) در صورت ضعف امنیت روانی، ابتدا ایمنی و یادگیری از خطا. ۲) در صورت پیچیدگی/تمرکز، ساده‌سازی ساختار و منشور اختیار تصمیم. ۳) در صورت ضعف بازخورد/ارزیابی، استقرار SBI و داشبورد داده‌محور. ۴) در صورت آمادگی، مربی‌گری و تربیت سرپرستان به مربی عملکردی. ۵) تیم‌های چندتخصصی و بازنگری پس از پروژه. ۶) شاخص‌های ESG، تاب‌آوری و رضایت/به‌زیستی.
							</blockquote>
							<div class="cr-od-refs">
								<h4>منابع علمی کلیدی</h4>
								<ul>
									<li>Taylor, F.W. (1911). <em>The Principles of Scientific Management</em>.</li>
									<li>Weber, M. (1946). <em>Essays in Sociology</em> — مفهوم بوروکراسی.</li>
									<li>Burns, T. &amp; Stalker, G.M. (1961). <em>The Management of Innovation</em> — ساختار مکانیکی در برابر ارگانیک.</li>
									<li>Mintzberg, H. (1979). <em>The Structuring of Organizations</em> — رسمیت، پیچیدگی، تمرکز.</li>
									<li>Senge, P. (1990). <em>The Fifth Discipline</em> — سازمان یادگیرنده.</li>
									<li>Edmondson, A. (1999). <em>Psychological Safety and Learning Behavior in Work Teams</em>.</li>
									<li>Whitmore, J. (2009). <em>Coaching for Performance</em> — مدل GROW.</li>
									<li>Laloux, F. (2014). <em>Reinventing Organizations</em> — سازمان‌های تکامل‌یافته.</li>
									<li>Schaufeli, W. &amp; Bakker, A. (2004). <em>UWES</em> — سلامت و به‌زیستی شغلی.</li>
									<li>World Economic Forum (2020). <em>Measuring Stakeholder Capitalism</em> — شاخص‌های ESG.</li>
									<li>United Nations (2015). <em>2030 Agenda for Sustainable Development / SDGs</em>.</li>
								</ul>
							</div>
						</article>
					</section>

					<!-- REPORTS -->
					<section class="cr-od-panel" id="cr-reports" role="tabpanel" hidden>
						<div class="cr-od-report-hero">
							<div>
								<h2>گزارش توسعه سازمانی برای مدیران و تصمیم‌گیران</h2>
								<p>این گزارش بر پایه داده‌های ثبت‌شده ارزیابی، شاخص‌های ساختاری و نقشه راه مربی‌گری تهیه می‌شود و قابل چاپ و خروجی اکسل برای هیئت مدیره است.</p>
							</div>
							<img src="<?php echo esc_url( CR_OD_PLUGIN_URL . $img . 'control-room.jpg' ); ?>" alt="اتاق کنترل عملیات صنعت انرژی" loading="lazy" />
						</div>

						<div class="cr-od-report-actions">
							<button type="button" class="cr-od-btn cr-od-btn-primary" id="cr-print-report">چاپ گزارش</button>
							<button type="button" class="cr-od-btn" id="cr-export-csv">خروجی CSV شاخص‌ها</button>
						</div>

						<div class="cr-od-report-document" id="cr-report-document">
						<div class="cr-od-report-title">
							<h2>گزارش توسعه سازمانی <?php echo esc_html( $config['org'] ); ?></h2>
							<p>حوزه فعالیت: <?php echo esc_html( $config['industry'] ); ?> | <?php echo esc_html( $data['summary']['cycle_title'] ); ?> | آخرین به‌روزرسانی: <span data-fa-date><?php echo esc_html( $data['summary']['last_updated'] ? date_i18n( 'Y/m/d', strtotime( $data['summary']['last_updated'] ) ) : '—' ); ?></span></p>
						</div>

						<div class="cr-od-report-proof" id="cr-report-proof">
							<div><span>آخرین ثبت</span><strong id="cr-report-last-role"><?php echo esc_html( $data['summary']['last_role'] ? $data['summary']['last_role'] : '—' ); ?></strong><small>واحد: <span id="cr-report-last-dept"><?php echo esc_html( $data['summary']['last_department'] ? $data['summary']['last_department'] : '—' ); ?></span></small></div>
							<div><span>نقش‌های شرکت‌کننده</span><strong id="cr-report-roles-count" data-fa-num><?php echo esc_html( $data['summary']['roles_count'] ); ?></strong><small>واحدهای ثبت‌شده: <span id="cr-report-depts-count" data-fa-num><?php echo esc_html( $data['summary']['departments_count'] ); ?></span></small></div>
							<div><span>سطرهای تحلیل‌شده</span><strong id="cr-report-responses" data-fa-num><?php echo esc_html( $data['summary']['responses'] ); ?></strong><small>پردازش‌شده در دوره جاری</small></div>
						</div>

						<div class="cr-od-report-summary cr-od-kpi-grid">
								<div class="cr-od-kpi"><span class="cr-od-kpi-label">امتیاز کل</span><span class="cr-od-kpi-value" data-fa-num><?php echo esc_html( $data['summary']['overall'] ); ?></span></div>
								<div class="cr-od-kpi"><span class="cr-od-kpi-label">موج فعلی</span><span class="cr-od-kpi-value" style="color:<?php echo esc_attr( $data['summary']['wave_color'] ); ?>;"><?php echo esc_html( $data['summary']['wave_label'] ); ?></span></div>
								<div class="cr-od-kpi"><span class="cr-od-kpi-label">موج هدف</span><span class="cr-od-kpi-value"><?php echo esc_html( $waves[ $data['summary']['target_wave'] ]['title'] ); ?></span></div>
							</div>

							<h3>تحلیل وضعیت و تصمیم‌گیری مدیران</h3>
							<div class="cr-od-report-body">
								<p id="cr-analysis-summary"><?php echo esc_html( $data['analysis']['summary'] ); ?></p>
							</div>
							<div class="cr-od-report-two">
								<div class="cr-od-report-col">
									<h4>نقاط قوت</h4>
									<?php if ( ! empty( $data['analysis']['strengths'] ) ) : ?>
										<?php foreach ( $data['analysis']['strengths'] as $st ) : ?>
											<div class="cr-od-report-strength"><strong><?php echo esc_html( $st['label'] ); ?></strong><span>(<span data-fa-num><?php echo esc_html( $st['score'] ); ?></span>/۴)</span><p><?php echo esc_html( $st['text'] ); ?></p></div>
										<?php endforeach; ?>
									<?php else : ?>
										<div class="cr-od-empty">شاخص بالای ۳ ثبت نشده است؛ تمرکز بر بهبود اولویت‌ها.</div>
									<?php endif; ?>
								</div>
								<div class="cr-od-report-col">
									<h4>نقاط بهبود / اولویت‌ها</h4>
									<?php if ( ! empty( $data['analysis']['weaknesses'] ) ) : ?>
										<?php foreach ( $data['analysis']['weaknesses'] as $wk ) : ?>
											<div class="cr-od-report-weakness"><strong><?php echo esc_html( $wk['label'] ); ?></strong><span>(<span data-fa-num><?php echo esc_html( $wk['score'] ); ?></span>/۴)</span><p><?php echo esc_html( $wk['text'] ); ?></p></div>
										<?php endforeach; ?>
									<?php else : ?>
										<div class="cr-od-empty">هیچ شاخص ضعیفی شناسایی نشد؛ سازمان در وضعیت مطلوب است.</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="cr-od-report-efqm" id="cr-report-efqm">
								<h4>نتایج تعالی EFQM</h4>
								<div class="cr-od-report-efqm-kpis">
									<div><span>امتیاز کل</span><strong id="cr-report-efqm-score" data-fa-num><?php echo esc_html( $data['efqm']['score'] ); ?></strong><small>از ۱۰۰۰</small></div>
									<div><span>توانمندسازها</span><strong id="cr-report-efqm-enablers" data-fa-num><?php echo esc_html( $data['efqm']['enablers'] ); ?></strong><small>از ۴</small></div>
									<div><span>نتایج</span><strong id="cr-report-efqm-results" data-fa-num><?php echo esc_html( $data['efqm']['results'] ); ?></strong><small>از ۴</small></div>
									<div><span>سطح</span><strong id="cr-report-efqm-level"><?php echo esc_html( $data['efqm']['level'] ); ?></strong><small>مدل تعالی</small></div>
								</div>
								<table class="cr-od-table">
									<thead><tr><th>معیار EFQM</th><th>حوزه</th><th>امتیاز (۱-۴)</th><th>امتیاز تعالی</th><th>اقدام پیشنهادی</th></tr></thead>
									<tbody>
										<?php foreach ( $data['efqm']['criteria'] as $crt ) : ?>
											<tr>
												<td><?php echo esc_html( $crt['label'] ); ?></td>
												<td><?php echo 'enabler' === $crt['group'] ? 'توانمندساز' : 'نتیجه'; ?></td>
												<td data-fa-num><?php echo esc_html( $crt['score'] ); ?></td>
												<td data-fa-num><?php echo esc_html( $crt['points'] ); ?></td>
												<td class="cr-od-table-long"><?php echo esc_html( $crt['action'] ); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

							<div class="cr-od-report-efqm" id="cr-report-okr">
								<h4>نقشه راه OKR برای تصمیم‌گیری مدیران</h4>
								<div class="cr-od-report-proof">
									<div><span>واحد تمرکز سیستمی</span><strong id="cr-report-okr-unit"><?php echo esc_html( $data['okr']['focus_unit']['name'] ?? '—' ); ?></strong><small id="cr-report-okr-unit-score" data-fa-num><?php echo esc_html( $data['okr']['focus_unit']['overall'] ?? '—' ); ?></small></div>
									<div><span>نقش تمرکز سیستمی</span><strong id="cr-report-okr-role"><?php echo esc_html( $data['okr']['focus_role']['name'] ?? '—' ); ?></strong><small id="cr-report-okr-role-score" data-fa-num><?php echo esc_html( $data['okr']['focus_role']['overall'] ?? '—' ); ?></small></div>
									<div><span>چرخه هدف‌گذاری</span><strong id="cr-report-okr-cycle"><?php echo esc_html( $data['okr']['cycle'] ); ?></strong><small>بازبینی ۳ ماهه</small></div>
								</div>
								<table class="cr-od-table">
									<thead><tr><th>هدف (Objective)</th><th>نتایج کلیدی (Key Results)</th><th>اولویت</th><th>امتیاز فعلی</th></tr></thead>
									<tbody id="cr-report-okr-tbody">
										<?php if ( ! empty( $data['okr']['items'] ) ) : ?>
											<?php foreach ( $data['okr']['items'] as $okr ) : ?>
												<tr>
													<td class="cr-od-table-long"><?php echo esc_html( $okr['objective'] ); ?></td>
													<td class="cr-od-table-long"><?php echo esc_html( implode( ' | ', $okr['krs'] ) ); ?></td>
													<td><?php echo esc_html( $okr['priority'] ); ?></td>
													<td data-fa-num><?php echo esc_html( $okr['score'] ); ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr><td colspan="4">OKR تثبیت و بهبود مستمر در محدوده هدف تعریف شود.</td></tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>

							<div class="cr-od-report-efqm" id="cr-report-roles">
								<h4>امتیاز ابعاد به تفکیک نقش‌های سازمانی</h4>
								<div class="cr-od-table-wrap">
									<table class="cr-od-table" id="cr-report-role-dim-table">
										<thead>
											<tr>
												<th>نقش سازمانی</th>
												<?php foreach ( $data['dimensions'] as $dim ) : ?>
													<th title="<?php echo esc_attr( $dim['label'] ); ?>"><?php echo esc_html( $dim['short'] ); ?></th>
												<?php endforeach; ?>
												<th>موج</th>
											</tr>
										</thead>
										<tbody id="cr-report-role-dim-tbody">
											<?php if ( ! empty( $data['roles'] ) ) : ?>
												<?php foreach ( $data['roles'] as $role ) : ?>
													<tr>
														<td><?php echo esc_html( $role['name'] ); ?></td>
														<?php foreach ( $data['dimensions'] as $dim ) : ?>
															<td data-fa-num><?php echo esc_html( $role['scores'][ $dim['slug'] ] ?? 1.0 ); ?></td>
														<?php endforeach; ?>
														<td><span class="cr-od-wave-chip" style="color:<?php echo esc_attr( $waves[ $role['wave'] ]['color'] ); ?>"><?php echo esc_html( $waves[ $role['wave'] ]['short'] ); ?></span></td>
													</tr>
												<?php endforeach; ?>
											<?php else : ?>
												<tr><td colspan="<?php echo esc_attr( count( $data['dimensions'] ) + 2 ); ?>">تاکنون ارزیابی نقش ثبت نشده است.</td></tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>

							<div class="cr-od-report-efqm" id="cr-report-weisbord">
							<h4>تشخیص شش‌جعبه‌ای وایزبورد</h4>
							<div class="cr-od-report-proof">
								<div><span>امتیاز کل تشخیص</span><strong id="cr-report-weisbord-overall" data-fa-num><?php echo esc_html( isset( $weisbord['overall'] ) ? $weisbord['overall'] : '—' ); ?></strong><small>از ۴</small></div>
								<div><span>سطح تشخیص</span><strong id="cr-report-weisbord-level"><?php echo esc_html( isset( $weisbord['level'] ) ? $weisbord['level'] : '—' ); ?></strong><small>مدل وایزبورد</small></div>
								<div><span>جعبه‌های بحرانی</span><strong id="cr-report-weisbord-low" data-fa-num><?php echo esc_html( isset( $weisbord['low'] ) ? count( $weisbord['low'] ) : 0 ); ?></strong><small>از ۶ جعبه</small></div>
							</div>
							<p class="cr-od-analysis-text"><?php echo esc_html( isset( $weisbord['diagnosis'] ) ? $weisbord['diagnosis'] : 'پس از تکمیل سؤال‌های تشخیصی، نتیجه نمایش داده می‌شود.' ); ?></p>
							<?php if ( ! empty( $weisbord['low'] ) ) : ?>
								<div class="cr-od-report-color-list">
									<?php foreach ( $weisbord['low'] as $low_box ) : ?>
										<span style="color:<?php echo esc_attr( $low_box['color'] ); ?>"><?php echo esc_html( $low_box['short'] ); ?> — <?php echo esc_html( $low_box['likely'] ); ?></span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<h3>نتیجه‌گیری مدیریتی</h3>
							<div class="cr-od-report-body">
							<p>سازمان در حال حاضر در <strong><?php echo esc_html( $data['summary']['wave_label'] ); ?></strong> قرار دارد. داده‌های ثبت‌شده نشان می‌دهد پایین‌ترین نمرات مربوط به <strong><?php echo esc_html( implode( '، ', $weak_labels ) ?: 'شاخص‌های اندازه‌گیری‌شده' ); ?></strong> است. همین شاخص‌ها مبناي انتخاب راهبرد قرار می‌گیرند.</p>
							<p>راهبردها در این گزارش به‌صورت <strong>تطبیقی و بر اساس بلوغ سازمان</strong> انتخاب شده‌اند: <?php echo esc_html( implode( '؛ ', $strategy_titles ) ?: 'برای این دوره هنوز ارزیابی کافی ثبت نشده است.' ); ?>. <?php if ( $coaching_rec ) : ?>داده‌ها نشان می‌دهد سازمان برای راهبرد «ارتقای نقش سرپرستان به مربیان عملکردی» آماده است؛ بنابراین این راهبرد در برنامه فعال شده است.<?php else : ?><?php echo esc_html( isset( $strategy['coaching_reason'] ) ? $strategy['coaching_reason'] : 'راهبرد مربی‌گری در صورت تأیید آستانه‌های آمادگی در دوره‌های بعد اضافه می‌شود.' ); ?><?php endif; ?></p>
							</div>

							<h3>دستورالعمل اجرایی ۹۰ روزه (ساخته‌شده از راهبرد منتخب)</h3>
							<div class="cr-od-report-phases">
								<div><strong>روز ۱-۳۰:</strong> <?php if ( $road_phase1 ) : ?><?php foreach ( $road_phase1 as $st ) : ?>«<?php echo esc_html( $st['title'] ); ?>» — <?php echo esc_html( implode( '، ', array_slice( $st['actions'], 0, 2 ) ) ); ?>. <?php endforeach; ?><?php else : ?>تکمیل ارزیابی پایه و توافق حکمرانی توسعه سازمانی.<?php endif; ?></div>
								<div><strong>روز ۳۱-۶۰:</strong> <?php if ( $road_phase2 ) : ?><?php foreach ( $road_phase2 as $st ) : ?>«<?php echo esc_html( $st['title'] ); ?>» — <?php echo esc_html( implode( '، ', array_slice( $st['actions'], 0, 2 ) ) ); ?>. <?php endforeach; ?><?php else : ?>بازخورد SBI، تیم‌های چندتخصصی و داشبورد OKR واحدها.<?php endif; ?></div>
								<div><strong>روز ۶۱-۹۰:</strong> <?php if ( $road_phase3 ) : ?><?php foreach ( $road_phase3 as $st ) : ?>«<?php echo esc_html( $st['title'] ); ?>» — <?php echo esc_html( implode( '، ', array_slice( $st['actions'], 0, 2 ) ) ); ?>. <?php endforeach; ?><?php else : ?>بازارزیابی، کالیبراسیون عملکرد، بانک درس‌آموخته و نقشه ۱۲ ماهه.<?php endif; ?></div>
							</div>

							<h3>اقدامات اولویت‌دار (محاسبه‌شده از داده‌های ارزیابی)</h3>
							<div class="cr-od-report-actions-list" id="cr-report-actions-list">
								<?php if ( ! empty( $data['recommendations'] ) ) : ?>
									<?php foreach ( $data['recommendations'] as $rec ) : ?>
										<div class="cr-od-report-action">
											<div class="cr-od-report-action-head">
												<strong><?php echo esc_html( $rec['title'] ); ?></strong>
												<span class="cr-od-report-pill"><?php echo esc_html( $rec['level'] ); ?></span>
												<span class="cr-od-report-score"><span data-fa-num><?php echo esc_html( $rec['score'] ); ?></span>/۴</span>
											</div>
											<p><?php echo esc_html( $rec['action'] ); ?></p>
											<div class="cr-od-action-meta">
												<span>مسئول: <?php echo esc_html( $rec['owner'] ); ?></span>
												<span>شاخص: <?php echo esc_html( $rec['kpi'] ); ?></span>
												<span>ابزار: <?php echo esc_html( $rec['tool'] ); ?></span>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else : ?>
									<div class="cr-od-empty">شاخص‌ها در محدوده هدف هستند؛ در دوره بعدی به‌روزرسانی گزارش انجام شود.</div>
								<?php endif; ?>
							</div>

							<div class="cr-od-report-footer">تهیه‌شده توسط پلتفرم توسعه سازمانی <strong>CoachRoom</strong> — coachroom.ir</div>
						</div>
					</section>

				</main>

				<footer class="cr-od-footer">
					<div class="cr-od-footer-brand">
						<strong><?php echo esc_html( $brand ); ?></strong>
						<p>پلتفرم توسعه سازمانی مبتنی بر داده؛ کمک به ایجاد محیط کار امن و رشد‌یافته در صنعت انرژی.</p>
					</div>
					<div class="cr-od-footer-meta">
						<span>سازمان: <?php echo esc_html( $config['org'] ); ?></span>
						<span>دوره: <?php echo esc_html( $data['summary']['cycle_title'] ); ?></span>
						<span>وضعیت سامانه: <strong id="cr-od-system-status">در حال بررسی...</strong></span>
					</div>
				</footer>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
