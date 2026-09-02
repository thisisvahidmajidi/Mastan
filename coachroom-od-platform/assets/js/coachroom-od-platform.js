/* CoachRoom Organizational Development Platform front-end */
(function () {
  'use strict';

  if (!window.crODData) { return; }

  var state = {
    config: window.crODData.config || {},
    waves: window.crODData.waves || {},
    dimensions: window.crODData.dimensions || {},
    questions: window.crODData.questions || [],
    weisbordQuestions: window.crODData.weisbordQuestions || [],
    weisbordBoxes: window.crODData.weisbordBoxes || {},
    data: window.crODData.data || { summary: {}, dimensions: [], departments: [], trend: [], recommendations: [] }
  };

  var FA_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
  var TAB_PANELS = ['dashboard', 'assessment', 'roadmap', 'departments', 'blog', 'reports'];

  function getData() { return state.data || {}; }
  function getSummary() { return getData().summary || {}; }
  function getDims() { return getData().dimensions || []; }
  function getQuestions() { return state.questions || []; }
  function getWeisbordQuestions() { return state.weisbordQuestions || []; }
  function getStrategy() { return getData().strategy || {}; }
  function getWeisbord() { return getData().weisbord || {}; }
  function getReliability() { return getData().reliability || {}; }
  function getModelMatrix() { return getData().model_matrix || {}; }
  function getDepts() { return getData().departments || []; }
  function getRoles() { return getData().roles || []; }
  function getTrendData() { return getData().trend || []; }
  function getRecs() { return getData().recommendations || []; }
  function getEfqm() { return getData().efqm || {}; }
  function getAnalysis() { return getData().analysis || {}; }
  function getOkr() { return getData().okr || {}; }
  function getRecordOrDefault(record) { return record || {}; }

  function esc(v) {
    return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[c];
    });
  }

  function faNum(v) {
    if (v === null || v === undefined || v === '') { return ''; }
    return String(v).replace(/\d/g, function (d) { return FA_DIGITS[+d]; }).replace(/\./g, '٫');
  }

  function num(v) {
    var n = parseFloat(v);
    return isNaN(n) ? 0 : n;
  }

  function clamp(v, lo, hi) { return Math.max(lo, Math.min(hi, v)); }

  function fmtNum(v, d) {
    var x = num(v);
    var s = x.toFixed(d === undefined ? 2 : d);
    return s.replace(/\.?0+$/, '');
  }

  function percent(v) { return fmtNum(v, 0) + '٪'; }

  function q(sel, root) { return (root || document).querySelector(sel); }
  function qa(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  function convertDigitsInside(root) {
    qa('[data-fa-num]', root || document).forEach(function (el) {
      el.dataset.raw = el.textContent.trim();
      el.textContent = faNum(el.dataset.raw);
    });
    qa('[data-fa-date]', root || document).forEach(function (el) {
      var raw = el.textContent.trim();
      el.textContent = faNum(raw);
    });
  }

  var GLOSSARY = [
    ['ESG', 'ESG مخفف Environmental (محیط‌زیست)، Social (اجتماعی) و Governance (حکمرانی) است و مجموعه شاخص‌هایی برای سنجش پایداری و مسئولیت‌پذیری سازمان در برابر محیط، جامعه و مدیریت شفاف است.'],
    ['EFQM', 'مدل تعالی EFQM یک چارچوب خودارزیابی و بهبود سازمانی است که با ۵ توانمندساز و ۴ نتیجه، عملکرد سازمان را به‌صورت شواهد‌محور و در مقیاس ۰ تا ۱۰۰۰ امتیازدهی می‌کند.'],
    ['RADAR', 'منطق RADAR در EFQM شامل پنج گام است: Results (نتایج)، Approach (رویکرد)، Deployment (استقرار)، Assessment (ارزیابی) و Refinement (بهبود و یادگیری).'],
    ['SBI', 'مدل بازخورد SBI یعنی موقعیت (Situation)، رفتار (Behavior) و اثر (Impact). به سرپرستان کمک می‌کند بازخورد را بدون قضاوت شخصی و بر اساس شواهد بدهند.'],
    ['GROW', 'مدل مربی‌گری GROW شامل هدف (Goal)، وضعیت فعلی (Reality)، گزینه‌ها (Options) و اراده/قدم بعدی (Will) است؛ یکی از پرکاربردترین مدل‌های مربیگری عملکردی است.'],
    ['OKR', 'OKR مخفف Objectives and Key Results است؛ یعنی اهداف کیفی روشن و نتایج کلیدی کمی و قابل اندازه‌گیری که برای هم‌راستاسازی واحدها استفاده می‌شود.'],
    ['KPI', 'KPI یا شاخص کلیدی عملکرد، معیار کمی برای پایش موفقیت در دستیابی به اهداف سازمانی است و پایه ارزیابی داده‌محور است.'],
    ['AAR', 'AAR یا After Action Review، جلسه بازبینی پس از اجرای پروژه یا حادثه است که در آن درس‌آموخته‌ها به‌صورت شفاف جمع‌آوری و مستند می‌شوند.'],
    ['PDCA', 'چرخه PDCA (Plan-Do-Check-Act) یک روش شناخته‌شده بهبود مستمر است: برنامه‌ریزی، اجرا، بررسی و اصلاح.'],
    ['SDGs', 'SDGs یا اهداف توسعه پایدار سازمان ملل، ۱۷ هدف جهانی برای رفع فقر، نابرابری و حفاظت از سیاره تا سال ۲۰۳۰ است.'],
    ['HSE', 'HSE مخفف Health, Safety, Environment؛ یعنی سلامت، ایمنی و محیط‌زیست؛ در صنایع انرژی و نفت و گاز یکی از مهم‌ترین حوزه‌های عملکردی است.'],
    ['1:1', 'جلسه ۱:۱ یک گفت‌وگوی منظم و خصوصی میان سرپرست/مربی و کارمند است (معمولاً هفتگی) که برای بازخورد، رشد و حل مسئله استفاده می‌شود.'],
    ['امنیت روانی', 'امنیت روانی یعنی کارکنان بدون ترس از تنبیه یا تمسخر بتوانند خطا، پرسش و مخالفت خود را مطرح کنند؛ پایه یادگیری و نوآوری تیم است.'],
    ['هم‌آفرین', 'سازمان هم‌آفرین یا شبکه‌ای، سازمانی است که تصمیم و نوآوری در تیم‌ها و شبکه‌ها توزیع می‌شود و به‌جای سلسله‌مراتب صرف، روی شفافیت، اعتماد و همکاری تمرکز دارد.'],
    ['بوروکراتیک', 'سازمان بوروکراتیک دارای قوانین زیاد، سلسله‌مراتب سخت و تمرکز تصمیم در سطوح بالا است؛ در تغییرات سریع معمولاً کند ارزیابی می‌شود.'],
    ['رسمیت', 'رسمیت به میزان قوانین، رویه‌ها و مستندات رسمی در سازمان اشاره دارد؛ رسمیت بالا یعنی چابکی کمتر و رسمیت توانمندساز یعنی قوانین ساده و قابل اعتماد.'],
    ['تمرکز', 'تمرکز به محل تصمیم‌گیری اشاره دارد؛ تمرکز بالا یعنی تصمیم‌ها در سطوح عالی و تمرکز پایین یعنی واگذاری تصمیم به واحدها و سرپرستان.'],
    ['پیچیدگی', 'پیچیدگی ساختاری به تعداد لایه‌ها، واحدها و نیاز به هماهنگی بین‌بخشی اشاره دارد؛ پیچیدگی زیاد معمولاً با سیلو و فرایند کند همراه است.'],
    ['تاب‌آوری', 'تاب‌آوری سازمانی یعنی توانایی پیش‌بینی، پاسخ‌دهی و بازگشت سریع از بحران و تغییرات غیرمنتظره بدون از دست دادن عملکرد پایدار.'],
    ['پایداری', 'پایداری یعنی تأمین نیاز امروز بدون به خطر انداختن آینده؛ در سازمان یعنی تعادل میان سود، مردم، جامعه و محیط‌زیست.'],
    ['کالیبراسیون', 'کالیبراسیون ارزیابی یعنی نشست مدیران برای هم‌سطح‌سازی نمرات و کاهش سوگیری شخصی به‌منظور ارزیابی عادلانه و داده‌محور.'],
    ['وایزبورد', 'مدل شش‌جعبه‌ای وایزبورد (Weisbord, 1976) سازمان را از شش زاویه اهداف، ساختار، روابط، پاداش، رهبری و مکانیسم‌های کمکی تشخیص می‌دهد و برای سازمان‌های سلسله‌مراتبی مناسب است.'],
    ['آلفای کرونباخ', 'آلفای کرونباخ ضریب پایایی است که هم‌سو بودن سؤال‌های یک مقیاس را نشان می‌دهد؛ معمولاً ۰.۷ به بالا پایایی قابل قبول محسوب می‌شود.']
  ];

  function isSkippableNode(node) {
    if (!node.parentElement) { return true; }
    var p = node.parentElement;
    var tag = (p.tagName || '').toLowerCase();
    if (tag === 'script' || tag === 'style' || tag === 'code' || tag === 'a') { return true; }
    return p.closest('.cr-tip');
  }

  function enhanceGlossary(rootEl) {
    if (!rootEl || !rootEl.childNodes) { return; }
    var walker = document.createTreeWalker(rootEl, NodeFilter.SHOW_TEXT, null);
    var nodes = [];
    while (walker.nextNode()) { nodes.push(walker.currentNode); }
    nodes.forEach(function (node) {
      if (isSkippableNode(node)) { return; }
      var text = node.nodeValue;
      if (!text || text.trim() === '') { return; }
      var counter = 0;
      var html = text.replace(/[A-Za-z0-9:]+|[\u0600-\u06FF][\u0600-\u06FF ]{1,}/g, function (m) {
        for (var i = 0; i < GLOSSARY.length; i++) {
          var term = GLOSSARY[i][0];
          var tip = GLOSSARY[i][1];
          if (m.indexOf(term) > -1 && m.length <= term.length + 2) {
            counter++;
            return '<span class="cr-tip" data-tip="' + tip.replace(/"/g, '&quot;') + '" tabindex="0">' + esc(m) + '</span>';
          }
        }
        return m;
      });
      if (!counter) { return; }
      var wrap = document.createElement('span');
      wrap.innerHTML = html;
      while (wrap.firstChild) { node.parentNode.insertBefore(wrap.firstChild, node); }
      node.parentNode.removeChild(node);
    });
  }

  function setupCanvas(id, fallbackW, fallbackH) {
    var canvas = document.getElementById(id);
    if (!canvas) { return null; }
    var rect = canvas.getBoundingClientRect();
    var width = Math.max(280, rect.width || fallbackW || 520);
    var height = Math.max(240, rect.height || fallbackH || 300);
    var dpr = window.devicePixelRatio || 1;
    canvas.width = Math.round(width * dpr);
    canvas.height = Math.round(height * dpr);
    canvas.style.width = width + 'px';
    canvas.style.height = height + 'px';
    var ctx = canvas.getContext('2d');
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.clearRect(0, 0, width, height);
    return { ctx: ctx, w: width, h: height };
  }

  function colorFor(value) {
    if (value < 2) { return '#dc2626'; }
    if (value < 2.75) { return '#d97706'; }
    if (value < 3.45) { return '#0d9488'; }
    return '#2563eb';
  }

  function dimMap() {
    var map = {};
    getDims().forEach(function (d) { map[d.slug] = d; });
    return map;
  }

  function dimScore(slug) {
    var map = dimMap();
    return map[slug] ? num(map[slug].score) : 1;
  }

  function waveThreshold(wave) {
    return { 1: 1.5, 2: 2.5, 3: 3.35, 4: 3.75 }[wave] || 3.35;
  }

  /* ---------------- Radar ---------------- */
  function drawRadar() {
    var setup = setupCanvas('crRadarChart', 520, 300);
    if (!setup) { return; }
    var ctx = setup.ctx, w = setup.w, h = setup.h;
    ctx.direction = 'rtl';
    var dims = getDims();
    var values = dims.map(function (d) { return num(d.score); });
    var labels = dims.map(function (d) { return d.short; });
    var targetVal = waveThreshold(state.config.target_wave || 3);
    var n = values.length || 4;
    var cx = w / 2, cy = h / 2 + 8;
    var radius = Math.min(w, h) * 0.30;
    var angle = function (i) { return (Math.PI * 2 * i / n) - Math.PI / 2; };
    var point = function (i, val) {
      var r = radius * (val / 4);
      return [cx + Math.cos(angle(i)) * r, cy + Math.sin(angle(i)) * r];
    };
    var ring = function (val) {
      var pts = [];
      for (var i = 0; i < n; i++) { pts.push(point(i, val)); }
      return pts;
    };
    var drawPoly = function (pts, stroke, fill, width, dash) {
      ctx.beginPath();
      pts.forEach(function (p, i) { i ? ctx.lineTo(p[0], p[1]) : ctx.moveTo(p[0], p[1]); });
      ctx.closePath();
      if (fill) { ctx.fillStyle = fill; ctx.fill(); }
      if (stroke) { ctx.strokeStyle = stroke; ctx.lineWidth = width || 2; ctx.setLineDash(dash || []); ctx.stroke(); ctx.setLineDash([]); }
    };

    for (var v = 1; v <= 4; v++) {
      ctx.strokeStyle = 'rgba(20,33,46,.14)';
      ctx.lineWidth = 1;
      drawPoly(ring(v), 'rgba(20,33,46,.14)', null);
    }
    for (var i = 0; i < n; i++) {
      var p = point(i, 4);
      ctx.beginPath(); ctx.moveTo(cx, cy); ctx.lineTo(p[0], p[1]);
      ctx.strokeStyle = 'rgba(20,33,46,.10)'; ctx.lineWidth = 1; ctx.stroke();
      var lp = point(i, 4.8);
      ctx.font = '700 11px ' + getComputedStyle(document.body).fontFamily || 'Vazirmatn,Arial';
      ctx.fillStyle = '#33475b';
      ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
      ctx.fillText(labels[i] || '', lp[0], lp[1]);
    }

    drawPoly(values.map(function (v2, i) { return point(i, v2); }), '#0d9488', 'rgba(13,148,136,.16)', 3);
    drawPoly(values.map(function (v2, i) { return point(i, Math.max(v2, targetVal)); }), '#f59e0b', 'rgba(245,158,11,.10)', 2, [4, 4]);

    // Value labels
    ctx.font = '800 11px ' + getComputedStyle(document.body).fontFamily || 'Vazirmatn,Arial';
    ctx.fillStyle = '#0c4a6e';
    values.forEach(function (v2, i) {
      var p = point(i, v2 + .55);
      ctx.fillText(faNum(fmtNum(v2, 1)), p[0], p[1]);
    });

    // Legend
    var ly = h - 18;
    var lx = 10;
    ctx.textAlign = 'right'; ctx.textBaseline = 'middle';
    ctx.fillStyle = '#0d9488'; ctx.fillRect(lx, ly - 4, 14, 8);
    ctx.fillStyle = '#475569'; ctx.font = '700 11px Arial';
    ctx.fillText('وضعیت فعلی', lx + 20, ly);
    ctx.fillStyle = '#f59e0b'; ctx.fillRect(lx + 130, ly - 4, 14, 8);
    ctx.fillStyle = '#475569';
    ctx.fillText('موج هدف (' + faNum(state.config.target_wave || 3) + ')', lx + 150, ly);
  }

  /* ---------------- Vertical bars ---------------- */
  function drawBars(id, labels, values, opts) {
    var setup = setupCanvas(id, 520, 300);
    if (!setup) { return; }
    var ctx = setup.ctx, w = setup.w, h = setup.h;
    var o = opts || {};
    var max = o.max || 4;
    var colors = o.colors || [];
    var small = o.small || false;
    var pad = { l: 30, r: 16, t: 22, b: 42 };
    var chartW = w - pad.l - pad.r;
    var chartH = h - pad.t - pad.b;
    var n = values.length || 1;

    // grid
    ctx.font = '700 10px Arial';
    ctx.textAlign = 'right';
    for (var t = 0; t <= max; t++) {
      var y = pad.t + chartH - (chartH * (t / max));
      ctx.strokeStyle = 'rgba(20,33,46,.10)';
      ctx.beginPath(); ctx.moveTo(pad.l, y); ctx.lineTo(w - pad.r, y); ctx.stroke();
      ctx.fillStyle = '#8a9aab'; ctx.fillText(faNum(t), 6, y + 3);
    }

    var gap = Math.min(16, chartW / (n * 4));
    var bw = Math.max(12, (chartW / n) * (small ? .55 : .48) - gap);
    values.forEach(function (v, i) {
      var x = pad.l + (chartW / n) * i + ((chartW / n) - bw) / 2;
      var hh = chartH * (clamp(num(v), 0, max) / max);
      var c = (colors[i] || colorFor(v));
      ctx.fillStyle = c;
      ctx.beginPath();
      ctx.moveTo(x, pad.t + chartH);
      ctx.lineTo(x, pad.t + chartH - hh);
      ctx.lineTo(x + bw, pad.t + chartH - hh);
      ctx.lineTo(x + bw, pad.t + chartH);
      ctx.closePath(); ctx.fill();
      ctx.fillStyle = c;
      ctx.font = '800 11px Arial';
      ctx.textAlign = 'center';
      ctx.fillText(faNum(fmtNum(v, 1)), x + bw / 2, pad.t + chartH - hh - 7);
      ctx.fillStyle = '#475569';
      ctx.font = '700 10px Arial';
      ctx.save();
      ctx.textAlign = 'center';
      ctx.fillText(labels[i] || '', x + bw / 2, pad.t + chartH + 14);
      ctx.restore();
    });
  }

  /* ---------------- Horizontal bars ---------------- */
  function drawHbars(id, labels, values, colors, opts) {
    var setup = setupCanvas(id, 520, 300);
    if (!setup) { return; }
    var ctx = setup.ctx, w = setup.w, h = setup.h;
    var o = opts || {};
    var max = o.max || 4;
    var pad = { l: 150, r: 26, t: 20, b: 20 };
    var chartW = w - pad.l - pad.r;
    var chartH = h - pad.t - pad.b;
    var n = values.length || 1;
    var rowH = chartH / n;
    var bw = Math.max(22, rowH * .48);

    for (var t = 0; t <= max; t++) {
      var x = pad.l + chartW * (t / max);
      ctx.strokeStyle = 'rgba(20,33,46,.10)';
      ctx.beginPath(); ctx.moveTo(x, pad.t); ctx.lineTo(x, pad.t + chartH); ctx.stroke();
      ctx.fillStyle = '#8a9aab'; ctx.font = '700 10px Arial'; ctx.textAlign = 'center';
      ctx.fillText(faNum(t), x, pad.t + chartH + 12);
    }

    values.forEach(function (v, i) {
      var y = pad.t + i * rowH + (rowH - bw) / 2;
      var barW = chartW * (clamp(num(v), 0, max) / max);
      ctx.fillStyle = colors[i] || colorFor(v);
      ctx.beginPath();
      ctx.moveTo(pad.l, y + bw / 2);
      ctx.lineTo(pad.l + barW, y);
      ctx.lineTo(pad.l + barW, y + bw);
      ctx.lineTo(pad.l, y + bw);
      ctx.closePath(); ctx.fill();
      ctx.fillStyle = '#33475b'; ctx.font = '700 11px Arial'; ctx.textAlign = 'left';
      ctx.fillText(labels[i] || '', pad.l - 12, y + bw / 2 + 3);
      ctx.fillStyle = '#0c4a6e'; ctx.font = '800 11px Arial'; ctx.textAlign = 'left';
      ctx.fillText(faNum(fmtNum(v, 1)), pad.l + barW + 6, y + bw / 2 + 3);
    });
  }

  /* ---------------- Line chart ---------------- */
  function drawTrend(id) {
    var setup = setupCanvas(id, 520, 300);
    if (!setup) { return; }
    var ctx = setup.ctx, w = setup.w, h = setup.h;
    var trend = getTrendData();
    var labels = trend.map(function (t) { return t.label || 'دوره'; });
    var values = trend.map(function (t) { return num(t.overall); });
    if (!values.length) { values = [1]; labels = ['بدون داده']; }
    var max = 4, pad = { l: 34, r: 20, t: 22, b: 40 };
    var chartW = w - pad.l - pad.r;
    var chartH = h - pad.t - pad.b;
    var n = values.length;

    for (var t = 0; t <= max; t++) {
      var y = pad.t + chartH - (chartH * (t / max));
      ctx.strokeStyle = 'rgba(20,33,46,.10)';
      ctx.beginPath(); ctx.moveTo(pad.l, y); ctx.lineTo(w - pad.r, y); ctx.stroke();
      ctx.fillStyle = '#8a9aab'; ctx.font = '700 10px Arial'; ctx.textAlign = 'right';
      ctx.fillText(faNum(t), 6, y + 3);
    }

    if (n === 1) {
      values = [getSummary().overall || 1];
      n = 1;
    }
    var px = function (i) { return pad.l + (chartW / Math.max(1, n - 1)) * i; };
    var py = function (v) { return pad.t + chartH - chartH * (num(v) / max); };

    ctx.beginPath();
    values.forEach(function (v, i) { var x = px(i), y = py(v); i ? ctx.lineTo(x, y) : ctx.moveTo(x, y); });
    ctx.strokeStyle = '#0c4a6e'; ctx.lineWidth = 3; ctx.stroke();

    values.forEach(function (v, i) {
      var x = px(i), y = py(v);
      ctx.beginPath(); ctx.arc(x, y, 5, 0, Math.PI * 2);
      ctx.fillStyle = '#0d9488'; ctx.fill();
      ctx.strokeStyle = '#fff'; ctx.lineWidth = 2; ctx.stroke();
      ctx.fillStyle = '#0c4a6e'; ctx.font = '800 11px Arial'; ctx.textAlign = 'center';
      ctx.fillText(faNum(fmtNum(v, 1)), x, y - 10);
      ctx.fillStyle = '#475569'; ctx.font = '700 10px Arial';
      ctx.fillText(labels[i] || '', x, pad.t + chartH + 16);
    });
  }

  function drawWaveChart() {
    var overall = num(getSummary().overall);
    var waves = [];
    for (var wave = 1; wave <= 4; wave++) {
      var lo = { 1: 0, 2: 1.75, 3: 2.75, 4: 3.35 }[wave];
      var hi = { 1: 1.75, 2: 2.75, 3: 3.35, 4: 3.75 }[wave];
      var fit = clamp((overall - lo) / (hi - lo) * 100, 0, 100);
      waves.push(Math.round(fit));
    }
    var labels = [1, 2, 3, 4].map(function (x) { return 'موج ' + faNum(x); });
    var colors = ['#dc2626', '#d97706', '#0d9488', '#2563eb'];
    drawBars('crWaveChart', labels, waves, { max: 100, colors: colors, small: true });
  }

  function drawSkillsChart() {
    var slugs = ['active_listening', 'questioning', 'feedback', 'coaching_culture', 'psychological_safety', 'learning_culture'];
    var labels = [];
    var values = [];
    slugs.forEach(function (s) {
      var d = dimMap()[s];
      labels.push(d ? d.short : s);
      values.push(dimScore(s));
    });
    drawBars('crSkillsChart', labels, values, { max: 4, colors: ['#0d9488', '#0c4a6e', '#d97706', '#dc2626', '#2563eb', '#7c3aed'] });
  }

  function drawDepartmentChart() {
    var depts = getDepts();
    var labels = depts.map(function (d) { return d.name; });
    var values = depts.map(function (d) { return num(d.overall); });
    var colors = depts.map(function (d) { return colorFor(num(d.overall)); });
    drawHbars('crDeptChart', labels, values, colors, { max: 4 });
  }

  function drawRoleChart() {
    var roles = getRoles();
    var labels = roles.map(function (d) { return d.name; });
    var values = roles.map(function (d) { return num(d.overall); });
    var colors = roles.map(function (d) { return colorFor(num(d.overall)); });
    drawHbars('crRoleChart', labels, values, colors, { max: 4 });
  }

  function drawAll() {
    drawRadar();
    drawWaveChart();
    drawSkillsChart();
    drawDepartmentChart();
    drawRoleChart();
    drawTrend();
  }

  /* ---------------- Live DOM refresh ---------------- */
  function refreshKpis() {
    var s = getSummary();
    var overall = num(s.overall);
    var wave = num(s.wave);
    var waveMeta = state.waves[wave] || {};
    var targetWave = num(s.target_wave) || 3;
    var targetMeta = state.waves[targetWave] || {};
    var gap = num(s.target_gap);

    setText('cr-overall', faNum(fmtNum(overall)));
    var wl = q('#cr-wave-label');
    if (wl) { wl.textContent = waveMeta.title || ''; wl.style.color = waveMeta.color || '#0d9488'; }
    setText('cr-wave-desc', waveMeta.desc || '');
    var tl = q('#cr-target-label');
    if (tl) { tl.textContent = targetMeta.title || ''; tl.style.color = targetMeta.color || '#0d9488'; }
    setText('cr-gap', faNum(fmtNum(gap)));
    setText('cr-cycle-title', s.cycle_title || 'بدون دوره');
  }

  function setText(id, text) {
    var el = document.getElementById(id);
    if (el) { el.textContent = text; }
  }

  function refreshRanked() {
    var dims = getDims();
    dims.forEach(function (d) {
      var row = q('.cr-od-ranked-row[data-slug="' + d.slug + '"]');
      if (!row) { return; }
      var scoreEl = q('.cr-od-ranked-score', row);
      if (scoreEl) { scoreEl.textContent = faNum(fmtNum(d.score)); scoreEl.style.color = colorFor(num(d.score)); }
      var bar = q('.cr-od-bar span', row);
      if (bar) { bar.style.width = (num(d.score) * 25) + '%'; }
    });
  }

  function refreshDeptTable() {
    var tbody = document.getElementById('cr-dept-tbody');
    if (!tbody) { return; }
    var depts = getDepts();
    var rows = depts.map(function (d) {
      var cells = '';
      var scores = d.scores || {};
      getDims().forEach(function (dm) {
        cells += '<td data-fa-num>' + esc(fmtNum(num(scores[dm.slug] || 1))) + '</td>';
      });
      var wave = state.waves[d.wave] || {};
      return '<tr><td>' + esc(d.name) + '</td>' + cells +
        '<td><span class="cr-od-wave-chip" style="color:' + esc(wave.color || '#0c4a6e') + '">' + esc(wave.short || '—') + '</span></td></tr>';
    });
    if (!rows.length) { rows = ['<tr><td colspan="' + ((getDims()).length + 2) + '">هنوز داده‌ای ثبت نشده است.</td></tr>']; }
    tbody.innerHTML = rows.join('');
    convertDigitsInside(tbody);
  }

  function refreshLastSave() {
    var s = getSummary();
    setText('cr-last-role', s.last_role || '—');
    setText('cr-last-dept', s.last_department || '—');
    setText('cr-report-last-role', s.last_role || '—');
    setText('cr-report-last-dept', s.last_department || '—');
    setText('cr-report-roles-count', faNum(num(s.roles_count)));
    setText('cr-report-depts-count', faNum(num(s.departments_count)));
    setText('cr-report-responses', faNum(num(s.responses)));
  }

  function refreshRoleTable() {
    var tbody = document.getElementById('cr-role-tbody');
    if (!tbody) { return; }
    var roles = getRoles();
    var rows = roles.map(function (d) {
      var wave = state.waves[d.wave] || {};
      return '<tr><td>' + esc(d.name) + '</td>' +
        '<td data-fa-num>' + esc(fmtNum(num(d.overall))) + '</td>' +
        '<td data-fa-num>' + esc(num(d.count)) + '</td>' +
        '<td><span class="cr-od-wave-chip" style="color:' + esc(wave.color || '#0c4a6e') + '">' + esc(wave.short || '—') + '</span></td></tr>';
    });
    if (!rows.length) { rows = ['<tr><td colspan="4">تاکنون ارزیابی نقش ثبت نشده است.</td></tr>']; }
    tbody.innerHTML = rows.join('');
    convertDigitsInside(tbody);
  }

  function buildRoleDimRows() {
    var roles = getRoles();
    if (!roles.length) { return ['<tr><td colspan="' + (getDims().length + 2) + '">تاکنون ارزیابی نقش ثبت نشده است.</td></tr>']; }
    return roles.map(function (d) {
      var scores = d.scores || {};
      var cells = '';
      getDims().forEach(function (dm) {
        cells += '<td data-fa-num>' + esc(fmtNum(num(scores[dm.slug] || 1))) + '</td>';
      });
      var wave = state.waves[d.wave] || {};
      return '<tr><td>' + esc(d.name) + '</td>' + cells +
        '<td><span class="cr-od-wave-chip" style="color:' + esc(wave.color || '#0c4a6e') + '">' + esc(wave.short || '—') + '</span></td></tr>';
    });
  }

  function refreshRoleDimTables() {
    var rows = buildRoleDimRows();
    var tbody1 = document.getElementById('cr-role-dim-tbody');
    if (tbody1) { tbody1.innerHTML = rows.join(''); convertDigitsInside(tbody1); }
    var tbody2 = document.getElementById('cr-report-role-dim-tbody');
    if (tbody2) { tbody2.innerHTML = rows.join(''); convertDigitsInside(tbody2); }
  }

  function refreshRoadmap() {
    var list = document.getElementById('cr-roadmap-actions-list');
    if (!list) { return; }
    var recs = getRecs();
    if (!recs.length) {
      list.innerHTML = '<div class="cr-od-empty">شاخص‌ها در محدوده هدف هستند؛ برای اولویت‌بندی دقیق‌تر، ابتدا ارزیابی را تکمیل کنید.</div>';
      return;
    }
    var html = recs.map(function (r) {
      var dim = state.dimensions[r.slug] || {};
      var icon = dim.icon || '✦';
      return '<div class="cr-od-action">' +
        '<div class="cr-od-action-head"><span class="cr-od-action-icon">' + esc(icon) + '</span>' +
        '<div><h4>' + esc(r.title) + '</h4><span class="cr-od-action-priority">' + esc(r.level) + '</span></div>' +
        '<span class="cr-od-action-score"><span data-fa-num>' + esc(fmtNum(num(r.score))) + '</span>/۴</span></div>' +
        '<p>' + esc(r.action) + '</p>' +
        '<div class="cr-od-action-meta"><span>مسئول: ' + esc(r.owner) + '</span>' +
        '<span>شاخص: ' + esc(r.kpi) + '</span><span>ابزار: ' + esc(r.tool) + '</span></div></div>';
    }).join('');
    list.innerHTML = html;
    convertDigitsInside(list);
  }

  function refreshReport() {
    var doc = document.getElementById('cr-report-document');
    if (!doc) { return; }
    var s = getSummary();
    var kpis = qa('.cr-od-kpi', doc);
    if (kpis[0]) {
      var v = q('.cr-od-kpi-value', kpis[0]); if (v) { v.textContent = faNum(fmtNum(num(s.overall))); }
    }
    if (kpis[1]) {
      var waveMeta = state.waves[num(s.wave)] || {};
      var v2 = q('.cr-od-kpi-value', kpis[1]); if (v2) { v2.textContent = waveMeta.title || ''; v2.style.color = waveMeta.color; }
    }
    if (kpis[2]) {
      var tm = state.waves[num(s.target_wave) || 3] || {};
      var v3 = q('.cr-od-kpi-value', kpis[2]); if (v3) { v3.textContent = tm.title || ''; v3.style.color = tm.color; }
    }
  }

  function refreshReportActions() {
    var list = document.getElementById('cr-report-actions-list');
    if (!list) { return; }
    var recs = getRecs();
    if (!recs.length) {
      list.innerHTML = '<div class="cr-od-empty">شاخص‌ها در محدوده هدف هستند؛ در دوره بعدی به‌روزرسانی گزارش انجام شود.</div>';
      return;
    }
    var html = recs.map(function (r) {
      return '<div class="cr-od-report-action">' +
        '<div class="cr-od-report-action-head"><strong>' + esc(r.title) + '</strong>' +
        '<span class="cr-od-report-pill">' + esc(r.level) + '</span>' +
        '<span class="cr-od-report-score"><span data-fa-num>' + esc(fmtNum(num(r.score))) + '</span>/۴</span></div>' +
        '<p>' + esc(r.action) + '</p>' +
        '<div class="cr-od-action-meta"><span>مسئول: ' + esc(r.owner) + '</span>' +
        '<span>شاخص: ' + esc(r.kpi) + '</span><span>ابزار: ' + esc(r.tool) + '</span></div></div>';
    }).join('');
    list.innerHTML = html;
    convertDigitsInside(list);
  }

  function refreshEfqm() {
    var efqm = getEfqm() || {};
    setText('cr-efqm-score', faNum(fmtNum(num(efqm.score))));
    setText('cr-efqm-level', efqm.level || '—');
    setText('cr-efqm-enablers', faNum(fmtNum(num(efqm.enablers))));
    setText('cr-efqm-results', faNum(fmtNum(num(efqm.results))));
    setText('cr-report-efqm-score', faNum(fmtNum(num(efqm.score))));
    setText('cr-report-efqm-enablers', faNum(fmtNum(num(efqm.enablers))));
    setText('cr-report-efqm-results', faNum(fmtNum(num(efqm.results))));
    setText('cr-report-efqm-level', efqm.level || '—');
  }

  function refreshAnalysis() {
    var a = getAnalysis() || {};
    setText('cr-analysis-summary', a.summary || '');
  }

  function refreshOkr() {
    var okr = getOkr() || {};
    var items = okr.items || [];
    setText('cr-report-okr-unit', (okr.focus_unit && okr.focus_unit.name) || '—');
    setText('cr-report-okr-unit-score', okr.focus_unit && okr.focus_unit.overall != null ? faNum(fmtNum(okr.focus_unit.overall)) : '—');
    setText('cr-report-okr-role', (okr.focus_role && okr.focus_role.name) || '—');
    setText('cr-report-okr-role-score', okr.focus_role && okr.focus_role.overall != null ? faNum(fmtNum(okr.focus_role.overall)) : '—');
    setText('cr-report-okr-cycle', okr.cycle || '۹۰ روزه');

    var grid = document.getElementById('cr-okr-grid');
    var tbody = document.getElementById('cr-report-okr-tbody');
    if (!items.length) {
      var empty = '<div class="cr-od-empty">شاخص‌ها در محدوده هدف هستند؛ OKR تثبیت و بهبود مستمر تعریف شود.</div>';
      if (grid) { grid.innerHTML = empty; }
      if (tbody) { tbody.innerHTML = '<tr><td colspan="4">OKR تثبیت و بهبود مستمر در محدوده هدف تعریف شود.</td></tr>'; }
      return;
    }
    var cards = items.map(function (item) {
      var krs = (item.krs || []).map(function (kr) {
        return '<div><span class="cr-od-kr-badge">KR</span> ' + esc(kr) + '</div>';
      }).join('');
      return '<div class="cr-od-okr-card"><div class="cr-od-okr-head">' +
        '<span class="cr-od-action-priority">' + esc(item.priority || '') + '</span>' +
        '<span class="cr-od-action-score"><span data-fa-num>' + esc(fmtNum(num(item.score))) + '</span>/۴</span></div>' +
        '<h4>' + esc(item.objective || '') + '</h4><div class="cr-od-okr-krs">' + krs + '</div>' +
        '<span class="cr-od-okr-owner">مسئول اجرا: ' + esc(item.owner || '') + '</span></div>';
    }).join('');
    if (grid) { grid.innerHTML = cards; convertDigitsInside(grid); }

    if (tbody) {
      var rows = items.map(function (item) {
        return '<tr><td class="cr-od-table-long">' + esc(item.objective || '') + '</td>' +
          '<td class="cr-od-table-long">' + esc((item.krs || []).join(' | ')) + '</td>' +
          '<td>' + esc(item.priority || '') + '</td>' +
          '<td data-fa-num>' + esc(fmtNum(num(item.score))) + '</td></tr>';
      }).join('');
      tbody.innerHTML = rows;
      convertDigitsInside(tbody);
    }
  }

  function refreshStrategy() {
    var strategy = getStrategy() || {};
    var selected = strategy.selected || [];
    var buckets = { 30: [], 60: [], 90: [] };
    selected.forEach(function (st) {
      var gate = st.gate || '';
      if (gate === 'safety' || gate === 'structure') { buckets['30'].push(st); }
      else if (gate === 'performance' || gate === 'network') { buckets['60'].push(st); }
      else { buckets['90'].push(st); }
    });

    var fallback = {
      '30': '<li>نقشه راه مرحله ۳۰ بر اساس داده‌های پایه از همین فرم محاسبه می‌شود.</li>',
      '60': '<li>راهبردهای شواهدمحور در این بازه بر اساس نتایج ارزیابی انتخاب می‌شوند.</li>',
      '90': '<li>بازارزیابی شاخص‌ها، کمیته کالیبراسیون و بانک درس‌آموخته‌ها.</li>'
    };

    ['30', '60', '90'].forEach(function (phase) {
      var el = document.getElementById('cr-roadmap-phase-' + phase);
      if (!el) { return; }
      var list = buckets[phase] || [];
      if (!list.length) {
        el.innerHTML = fallback[phase];
        return;
      }
      el.innerHTML = list.map(function (st) {
        var title = st.title || '';
        var actions = (st.actions || []).slice(0, 2).join('، ');
        return '<li>' + esc(title) + ': ' + esc(actions) + '</li>';
      }).join('');
    });

    var note = document.getElementById('cr-strategy-note');
    if (note) {
      if (strategy.coaching_recommended) {
        note.innerHTML = '<strong>سازمان در شرایط آمادگی برای مربی‌گری است؛ راهبرد «ارتقای نقش سرپرستان به مربیان عملکردی» فعال شده است.</strong>';
      } else {
        note.innerHTML = '<strong>راهبرد مربی‌گری فعال نشده است. ' + esc(strategy.coaching_reason || 'بر اساس بلوغ فعلی سازمان، ابتدا راهبردهای پیش‌نیاز اجرا شوند.') + '</strong>';
      }
    }
    convertDigitsInside(note || document.getElementById('cr-od-root'));
  }

  function refreshWeisbord() {
    var w = getWeisbord() || {};
    setText('cr-weisbord-overall', faNum(fmtNum(num(w.overall))));
    setText('cr-weisbord-level', w.level || '—');
    setText('cr-weisbord-low-count', faNum((w.low || []).length));
    setText('cr-weisbord-diagnosis-text', w.diagnosis || 'پس از تکمیل ۱۸ سؤال وایزبورد، نتیجه تشخیصی نمایش داده می‌شود.');
    setText('cr-report-weisbord-overall', faNum(fmtNum(num(w.overall))));
    setText('cr-report-weisbord-level', w.level || '—');
    setText('cr-report-weisbord-low', faNum((w.low || []).length));
    var grid = q('.cr-od-weisbord-grid');
    if (!grid || !w.boxes) { return; }
    grid.innerHTML = w.boxes.map(function (box) {
      return '<div class="cr-od-weisbord-box" style="--box-color:' + esc(box.color || '#0d9488') + '">' +
        '<div class="cr-od-weisbord-box-head"><span class="cr-od-q-icon">' + esc(box.icon || '') + '</span>' +
        '<div><strong>' + esc(box.label || '') + '</strong><small>' + esc(box.key_question || '') + '</small></div>' +
        '<b class="cr-od-weisbord-score" data-fa-num>' + esc(fmtNum(num(box.score))) + '</b></div>' +
        '<p class="cr-od-weisbord-likely">' + esc(box.likely || '') + '</p>' +
        '<span class="cr-od-weisbord-status" style="color:' + esc(box.color || '#0d9488') + '">' + esc(box.status || '') + '</span>' +
        '<small class="cr-od-weisbord-efqm">اتصال به EFQM: ' + esc(box.efqm || '') + '</small></div>';
    }).join('');
    convertDigitsInside(grid);
  }

  function refreshModelMatrix() {
    var mm = getModelMatrix() || {};
    var el = q('#cr-model-matrix .cr-od-model-matrix');
    if (!el) { return; }
    var rows = mm.matrix || [];
    if (!rows.length) {
      el.innerHTML = '<div class="cr-od-empty">پس از تکمیل ارزیابی، ماتریس چندمدلی نمایش داده می‌شود.</div>';
      return;
    }
    el.innerHTML = rows.map(function (row) {
      var strategies = (row.strategies || []).length
        ? row.strategies.map(function (t) { return '<span>' + esc(t) + '</span>'; }).join('')
        : '<small>راهبرد پس از تکمیل ارزیابی انتخاب می‌شود.</small>';
      return '<div class="cr-od-model-row"><div class="cr-od-model-head"><strong style="color:' + esc(row.color || '#0d9488') + '">' + esc(row.title || '') + '</strong></div>' +
        '<div class="cr-od-model-body"><p>' + esc(row.diagnosis || '') + '</p>' +
        '<div class="cr-od-model-strategies">' + strategies + '</div>' +
        '<small class="cr-od-model-note">' + esc(row.note || '') + '</small></div></div>';
    }).join('');
    convertDigitsInside(el);
  }

  function refreshReliability() {
    var r = getReliability() || {};
    var scopes = { overall: 'کل ارزیابی', maturity: 'ابعاد بلوغ', weisbord: 'شش جعبه وایزبورد' };
    var el = q('#cr-reliability .cr-od-reliability-grid');
    if (!el) { return; }
    el.innerHTML = Object.keys(scopes).map(function (scope) {
      var s = (r.scales && r.scales[scope]) || {};
      var alpha = (s.alpha === null || s.alpha === undefined) ? '—' : fmtNum(s.alpha, 2);
      return '<div class="cr-od-reliability-card"><strong>' + esc(scopes[scope]) + '</strong>' +
        '<span class="cr-od-reliability-alpha"><span data-fa-num>' + esc(alpha) + '</span> α</span>' +
        '<small>' + esc((s.n || 0) + ' پاسخ‌دهنده کامل — ' + (s.note || 'در انتظار داده کافی')) + '</small></div>';
    }).join('');
    convertDigitsInside(el);
  }

  function updateAll() {
    refreshKpis();
    refreshLastSave();
    refreshRanked();
    refreshDeptTable();
    refreshRoleTable();
    refreshRoleDimTables();
    refreshRoadmap();
    refreshReport();
    refreshReportActions();
    refreshEfqm();
    refreshAnalysis();
    refreshOkr();
    refreshStrategy();
    refreshWeisbord();
    refreshModelMatrix();
    refreshReliability();
    drawAll();
    convertDigitsInside();
    enhanceGlossary(document.getElementById('cr-od-root'));
    selfTest();
  }

  function selfTest() {
    var issues = [];
    var requiredIds = [
      'cr-dashboard', 'cr-assessment', 'cr-roadmap', 'cr-departments', 'cr-blog', 'cr-reports',
      'crRadarChart', 'crWaveChart', 'crSkillsChart', 'crDeptChart', 'crRoleChart', 'crTrendChart',
      'cr-od-assessment-form', 'cr-dept-tbody', 'cr-role-tbody', 'cr-role-dim-tbody',
      'cr-report-role-dim-tbody', 'cr-roadmap-actions-list', 'cr-efqm-table', 'cr-report-efqm',
      'cr-okr-grid', 'cr-okr-roadmap', 'cr-report-okr', 'cr-report-okr-tbody',
      'cr-strategy-note', 'cr-roadmap-phase-30', 'cr-roadmap-phase-60', 'cr-roadmap-phase-90',
      'cr-weisbord-diagnosis', 'cr-model-matrix', 'cr-reliability', 'cr-report-weisbord'
    ];
    requiredIds.forEach(function (id) {
      if (!document.getElementById(id)) { issues.push('missing:' + id); }
    });

    var s = getSummary();
    if (!s || typeof s !== 'object') { issues.push('summary'); }
    if (!Array.isArray(getDims()) || getDims().length < 10) { issues.push('dimensions'); }
    if (!Array.isArray(getQuestions()) || getQuestions().length < 30) { issues.push('questions'); }
    if (!Array.isArray(getWeisbordQuestions()) || getWeisbordQuestions().length < 18) { issues.push('weisbord-questions'); }
    if (qa('.cr-od-sub-question').length < 48) { issues.push('question-fields'); }
    if (!getWeisbord() || typeof getWeisbord() !== 'object') { issues.push('weisbord'); }
    if (!getReliability() || typeof getReliability() !== 'object') { issues.push('reliability'); }
    if (!getModelMatrix() || typeof getModelMatrix() !== 'object') { issues.push('model-matrix'); }
    if (!Array.isArray(getRoles())) { issues.push('roles'); }
    if (!Array.isArray(getDepts())) { issues.push('departments'); }
    if (!Array.isArray(getRecs())) { issues.push('recommendations'); }

    var efqm = getEfqm() || {};
    if (!efqm.criteria || efqm.criteria.length !== 9) { issues.push('efqm-criteria'); }

    var okr = getOkr() || {};
    if (!okr || typeof okr !== 'object') { issues.push('okr'); }
    if (!Array.isArray(okr.items || [])) { issues.push('okr-items'); }

    getRoles().forEach(function (r) {
      var sc = r.scores || {};
      getDims().forEach(function (d) {
        if (sc[d.slug] === undefined || sc[d.slug] === null) { issues.push('role-score:' + r.name + ':' + d.slug); }
      });
    });

    var status = document.getElementById('cr-od-system-status');
    var ok = issues.length === 0;
    if (status) {
      status.textContent = ok ? 'همه بخش‌ها سالم و هماهنگ ✓' : 'نیاز به بررسی (' + issues.length + ')';
      status.style.color = ok ? '#22ffc0' : '#ffb3b3';
    }
    if (window.console) { window.console[ok ? 'log' : 'warn']('CoachRoom OD self-test', ok ? 'OK' : issues); }
    return ok;
  }

  /* ---------------- Tabs ---------------- */
  function activateTab(name) {
    qa('.cr-od-tab').forEach(function (b) {
      var active = b.getAttribute('data-tab') === name;
      b.classList.toggle('is-active', active);
      b.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    qa('.cr-od-panel').forEach(function (p) {
      var active = p.id === 'cr-' + name;
      p.classList.toggle('is-active', active);
      p.hidden = !active;
    });
    if (name === 'departments') { drawDepartmentChart(); drawRoleChart(); drawTrend(); }
    else if (name === 'dashboard') { drawRadar(); drawWaveChart(); drawSkillsChart(); }
    else if (name === 'blog') { convertDigitsInside(); }
  }

  function bindTabs() {
    qa('.cr-od-tab').forEach(function (b) {
      b.addEventListener('click', function () { activateTab(b.getAttribute('data-tab')); });
    });
  }

  /* ---------------- Assessment form ---------------- */
  function bindSelection() {
    qa('.cr-od-sub-question').forEach(function (fieldset) {
      qa('input[type=radio]', fieldset).forEach(function (input) {
        input.addEventListener('change', function () {
          qa('.cr-od-option', fieldset).forEach(function (opt) {
            var radio = q('input', opt);
            if (radio) { opt.classList.toggle('is-selected', radio.checked); }
          });
        });
      });
    });
  }

  function postSaveBatch(payload, department, role) {
    var form = document.getElementById('cr-od-assessment-form');
    var nonceInput = form ? q('input[name="nonce"]', form) : null;
    var nonce = nonceInput ? nonceInput.value : (window.crODData && window.crODData.nonce);
    var body = new URLSearchParams();
    body.append('action', 'cr_od_save_response');
    body.append('nonce', nonce || '');
    body.append('questions', JSON.stringify(payload));
    body.append('dimensions', JSON.stringify(payload.map(function (p) { return { slug: p.dimension, score: p.score }; })));
    body.append('department', department || 'نامشخص');
    body.append('assessor_role', role || 'کارمند');
    body.append('organization', state.config.org || '');

    return fetch(window.crODData.ajaxUrl || '/wp-admin/admin-ajax.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString()
    }).then(function (res) {
      return res.text().then(function (text) {
        try { return JSON.parse(text); }
        catch (e) { throw new Error('پاسخ سرور قابل خواندن نیست (' + res.status + ')'); }
      });
    });
  }

  function bindForm() {
    var form = document.getElementById('cr-od-assessment-form');
    if (!form) { return; }
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var status = q('.cr-od-form-status', form);
      var missing = [];
      var payload = [];
      var questions = getQuestions();
      var weisbordQuestions = getWeisbordQuestions();
      var dimensionFromSlug = {};
      Object.keys(state.dimensions || {}).forEach(function (slug) {
        dimensionFromSlug[slug] = slug;
      });

      function collect(q) {
        var slug = q.dimension || '';
        var key = q.key || '';
        var name = key || slug;
        var checked = form.querySelector('input[name="' + name + '"]:checked');
        if (!checked) { missing.push(q.label || key); }
        else {
          payload.push({
            dimension: slug,
            question_key: key,
            question_label: q.label || '',
            score: Number(checked.value)
          });
        }
      }
      questions.forEach(collect);
      weisbordQuestions.forEach(collect);

      // Fallback: if for any reason question metadata is unavailable, still collect dimension-level radios.
      if (!questions.length && !weisbordQuestions.length) {
        Object.keys(state.dimensions || {}).forEach(function (slug) {
          var checked = q('input[name="' + slug + '"]:checked', form);
          if (!checked) { missing.push(state.dimensions[slug].label); }
          else { payload.push({ dimension: slug, question_key: 'dimension_' + slug, question_label: 'ارزیابی کلی ' + state.dimensions[slug].label, score: Number(checked.value) }); }
        });
      }

      if (missing.length) {
        var missingText = missing.slice(0, 2).join('، ') + (missing.length > 2 ? (' و ' + (missing.length - 2) + ' مورد دیگر') : '');
        if (status) { status.textContent = 'لطفاً ابتدا به همه سؤال‌ها پاسخ دهید: ' + esc(missingText); status.className = 'cr-od-form-status err'; }
        return;
      }
      var btn = q('button[type=submit]', form);
      if (btn) { btn.disabled = true; btn.textContent = 'در حال ثبت...'; }
      if (status) { status.textContent = 'در حال پردازش...'; status.className = 'cr-od-form-status'; }

      var department = q('input[name="department"]', form) ? q('input[name="department"]', form).value.trim() : 'نامشخص';
      var role = q('select[name="assessor_role"]', form) ? q('select[name="assessor_role"]', form).value : 'کارمند';

      postSaveBatch(payload, department, role)
        .then(function (res) {
          if (!res || res.success === false) {
            var msg = (res && res.data && res.data.message) ? res.data.message : 'خطا در ثبت داده‌ها.';
            throw new Error(msg);
          }
          // WordPress wraps the payload in res.data; our backend returns
          // { message, data: dashboard }. The unwrapping below is safe for both shapes.
          var serverPayload = res.data && res.data.data ? res.data.data : (res.data && (res.data.summary || res.data.dimensions) ? res.data : null);
          if (serverPayload) { state.data = serverPayload; }
          var successMsg = (res.data && res.data.message) ? res.data.message : 'ارزیابی با موفقیت ثبت شد و داشبورد به‌روزرسانی شد.';

          try {
            updateAll();
          } catch (e) {
            // Never block the save because of a rendering edge case.
            if (window.console) { window.console.warn('CoachRoom update render:', e); }
          }
          if (btn) { btn.disabled = false; btn.textContent = 'ثبت ارزیابی و بروزرسانی داشبورد'; }
          if (status) { status.textContent = successMsg; status.className = 'cr-od-form-status ok'; }
          activateTab('dashboard');
        })
        .catch(function (err) {
          if (btn) { btn.disabled = false; btn.textContent = 'ثبت ارزیابی و بروزرسانی داشبورد'; }
          if (status) { status.textContent = 'خطا: ' + (err && err.message ? err.message : 'لطفاً دوباره تلاش کنید.'); status.className = 'cr-od-form-status err'; }
        });
    });
  }

  /* ---------------- Reports ---------------- */
  function bindReports() {
    var printBtn = document.getElementById('cr-print-report');
    if (printBtn) { printBtn.addEventListener('click', function () { window.print(); }); }

    var csvBtn = document.getElementById('cr-export-csv');
    if (csvBtn) {
      csvBtn.addEventListener('click', function () {
        var rows = [
          ['شاخص', 'امتیاز (از ۴)', 'حالت'],
          ['امتیاز کلی', fmtNum(num(getSummary().overall)), getSummary().wave_label || '']
        ];
        getDims().forEach(function (d) {
          rows.push([d.label, fmtNum(num(d.score)), d.score < 2.5 ? 'اولویت بهبود' : (d.score < 3.35 ? 'پایش' : 'نقطه قوت')]);
        });
        rows.push([]);
        rows.push(['واحد سازمانی', 'امتیاز کل', 'موج']);
        getDepts().forEach(function (d) {
          var w = state.waves[d.wave] || {};
          rows.push([d.name, fmtNum(num(d.overall)), w.short || '']);
        });
        rows.push([]);
        rows.push(['نقش سازمانی', 'امتیاز کل', 'موج']);
        getRoles().forEach(function (d) {
          var w = state.waves[d.wave] || {};
          rows.push([d.name, fmtNum(num(d.overall)), w.short || '']);
        });
        var csv = '\uFEFF' + rows.map(function (r) {
          return r.map(function (c) { return '"' + String(c == null ? '' : c).replace(/"/g, '""') + '"'; }).join(',');
        }).join('\n');
        var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        var a = document.createElement('a');
        var url = URL.createObjectURL(blob);
        a.href = url;
        a.download = 'CoachRoom-OD-Report.csv';
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
        setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
      });
    }
  }

  /* ---------------- Init ---------------- */
  function init() {
    bindTabs();
    bindSelection();
    bindForm();
    bindReports();
    refreshKpis();
    refreshLastSave();
    refreshRanked();
    refreshDeptTable();
    refreshRoleTable();
    refreshRoleDimTables();
    refreshRoadmap();
    refreshReport();
    refreshReportActions();
    refreshEfqm();
    refreshAnalysis();
    refreshOkr();
    refreshStrategy();
    refreshWeisbord();
    refreshModelMatrix();
    refreshReliability();
    drawAll();
    enhanceGlossary(document.getElementById('cr-od-root'));
    convertDigitsInside();
    selfTest();

    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        var active = q('.cr-od-tab.is-active');
        var name = active ? active.getAttribute('data-tab') : 'dashboard';
        if (name === 'dashboard') { drawRadar(); drawWaveChart(); drawSkillsChart(); }
        if (name === 'departments') { drawDepartmentChart(); drawRoleChart(); drawTrend(); }
      }, 200);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
