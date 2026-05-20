<?php
session_start();
// ============================================
// Booking page — handles its own POST.
// On error: stays on page, shows message, keeps input.
// On success: shows a styled confirmation.
// ============================================

$errors  = [];
$success = false;
$booking = null;

// Old input (so the form keeps what the user typed on error)
$old = [
  'place'            => '',
  'date'             => '',
  'time'             => '',
  'guests'           => '1',
  'special_requests' => '',
];

$signed_in = isset($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include __DIR__ . '/php/db_connect.php';

    foreach ($old as $k => $v) {
        if (isset($_POST[$k])) $old[$k] = trim($_POST[$k]);
    }

    $place            = $old['place'];
    $date             = $old['date'];
    $time             = $old['time'];
    $guests           = (int)$old['guests'];
    $special_requests = $old['special_requests'];

    if (!$signed_in) {
        $errors[] = ['ar' => 'يجب تسجيل الدخول أولاً لحجز جولة.',
                     'en' => 'You must be signed in to book a tour.'];
    }
    if ($place === '') {
        $errors[] = ['ar' => 'الرجاء اختيار الوجهة.', 'en' => 'Please choose a destination.'];
    }
    if ($date === '') {
        $errors[] = ['ar' => 'الرجاء اختيار تاريخ الجولة.', 'en' => 'Please choose a tour date.'];
    } else {
        // ----- DATE LOGIC: the tour date cannot be in the past -----
        $today    = new DateTime('today');
        $tourDate = DateTime::createFromFormat('Y-m-d', $date);
        if (!$tourDate) {
            $errors[] = ['ar' => 'صيغة التاريخ غير صحيحة.', 'en' => 'Invalid date format.'];
        } elseif ($tourDate < $today) {
            $errors[] = ['ar' => 'لا يمكن حجز تاريخ في الماضي. الرجاء اختيار تاريخ اليوم أو تاريخ قادم.',
                         'en' => 'You cannot book a date in the past. Please pick today or a future date.'];
        }
    }
    if ($time === '') {
        $errors[] = ['ar' => 'الرجاء اختيار وقت الجولة.', 'en' => 'Please choose a tour time.'];
    }
    if ($guests < 1 || $guests > 10) {
        $errors[] = ['ar' => 'عدد الضيوف يجب أن يكون بين 1 و 10.', 'en' => 'Guests must be between 1 and 10.'];
    }

    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare(
            "INSERT INTO bookings (user_id, place, date, time, guests, special_requests)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssis", $user_id, $place, $date, $time, $guests, $special_requests);
        if ($stmt->execute()) {
            $success = true;
            $booking = ['place' => $place, 'date' => $date, 'time' => $time, 'guests' => $guests];
        } else {
            $errors[] = ['ar' => 'تعذّر حفظ الحجز. حاول مرة أخرى.',
                         'en' => 'Could not save the booking. Please try again.'];
        }
        $stmt->close();
    }
    $conn->close();
}

// Minimum date for the date picker = today
$min_date = date('Y-m-d');

// Helper to safely echo old values back into the form
function v($s) { return htmlspecialchars($s, ENT_QUOTES); }
function sel($a, $b) { return $a === $b ? 'selected' : ''; }
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>احجز جولة | Book a Tour</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body dir="rtl" lang="ar">
<div class="topbar">
  <div class="left">
    <a href="mailto:info@alula-vision.sa">✉ info@alula-vision.sa</a>
    <a href="tel:+966112589999">☏ +966 11 258 9999</a>
  </div>
  <button class="lang-toggle" id="langBtn" onclick="toggleLang()">English</button>
</div>

<header class="site-header">
  <div class="brand">
    <div class="logos">
      <img src="images/logo/imsiu-logo.png" alt="جامعة الإمام محمد بن سعود الإسلامية - IMSIU">
      <img src="images/logo/ccis-logo.png" alt="كلية علوم الحاسب والمعلومات - CCIS">
    </div>
    <span class="brand-text">
      <span class="lang-ar" style="font-family:var(--serif-ar)">العُلا</span>
      <span class="lang-en">AlUla</span>
      <small><span class="lang-ar">رؤية ٢٠٣٠</span><span class="lang-en">VISION 2030</span></small>
    </span>
  </div>
  <div class="brand alula-logo">
    <img src="images/logo/rcu-logo.png" alt="الهيئة الملكية لمحافظة العُلا - Royal Commission for AlUla">
    <img src="images/logo/alula-logo.png" alt="العُلا - AlUla">
  </div>
</header>

<nav class="main-nav">
  <ul>
    <li><a href="index.html"><span class="lang-ar">الرئيسية</span><span class="lang-en">Home</span></a></li>
    <li><a href="about.html"><span class="lang-ar">من نحن</span><span class="lang-en">About</span></a></li>
    <li>
      <a href="tourism.html"><span class="lang-ar">اكتشف العُلا ▾</span><span class="lang-en">Discover ▾</span></a>
      <ul class="dropdown">
        <li><a href="tourism.html#hegra"><span class="lang-ar">الحِجر (مدائن صالح)</span><span class="lang-en">Hegra</span></a></li>
        <li><a href="tourism.html#farid"><span class="lang-ar">قصر الفريد</span><span class="lang-en">Qasr Al-Farid</span></a></li>
        <li><a href="tourism.html#oldtown"><span class="lang-ar">البلدة القديمة</span><span class="lang-en">Old Town</span></a></li>
        <li><a href="tourism.html#nature"><span class="lang-ar">الطبيعة والحياة البرية</span><span class="lang-en">Nature & Wildlife</span></a></li>
        <li><a href="tourism.html#adventure"><span class="lang-ar">المغامرات</span><span class="lang-en">Adventure</span></a></li>
      </ul>
    </li>
    <li><a href="gallery.html"><span class="lang-ar">معرض الصور</span><span class="lang-en">Gallery</span></a></li>
    <li><a href="booking.php" class="active"><span class="lang-ar">احجز جولة</span><span class="lang-en">Book a Tour</span></a></li>
    <li><a href="feedback.php"><span class="lang-ar">آراء الزوار</span><span class="lang-en">Feedback</span></a></li>
    <li><a href="chatbot.html"><span class="lang-ar">المساعد الذكي</span><span class="lang-en">AI Assistant</span></a></li>
    <li><a href="register.php"><span class="lang-ar">حساب جديد</span><span class="lang-en">Register</span></a></li>
    <li><a href="login.php"><span class="lang-ar">دخول</span><span class="lang-en">Sign In</span></a></li>
  </ul>
</nav>

<section class="hero" style="height:38vh;min-height:300px">
  <div class="hero-bg" style="background-image:url('images/places/hegra-tourists.jpg')"></div>
  <div class="hero-content">
    <p class="eyebrow"><span class="lang-ar">خطط لزيارتك</span><span class="lang-en">Plan Your Visit</span></p>
    <h1><span class="lang-ar">احجز جولة</span><span class="lang-en">Book a Tour</span></h1>
  </div>
</section>

<section class="section">
  <div class="section-narrow">

<?php if ($success): ?>
    <div class="form-wrap" style="text-align:center">
      <div style="font-size:3.4rem;margin-bottom:10px">✅</div>
      <h2 style="color:var(--terra);font-family:var(--serif-ar);margin-bottom:8px">
        <span class="lang-ar">تم تأكيد الحجز!</span><span class="lang-en">Booking Confirmed!</span>
      </h2>
      <p style="color:var(--ink-soft);margin-bottom:22px">
        <span class="lang-ar">شكرًا لك، تم تسجيل حجزك بنجاح. تفاصيل الحجز:</span>
        <span class="lang-en">Thank you, your booking was saved successfully. Details:</span>
      </p>
      <div class="alert alert-success" style="text-align:right">
        <p><strong><span class="lang-ar">الوجهة:</span><span class="lang-en">Destination:</span></strong> <?= v($booking['place']) ?></p>
        <p><strong><span class="lang-ar">التاريخ:</span><span class="lang-en">Date:</span></strong> <?= v($booking['date']) ?></p>
        <p><strong><span class="lang-ar">الوقت:</span><span class="lang-en">Time:</span></strong> <?= v($booking['time']) ?></p>
        <p><strong><span class="lang-ar">عدد الضيوف:</span><span class="lang-en">Guests:</span></strong> <?= v($booking['guests']) ?></p>
      </div>
      <div style="margin-top:24px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="index.html" class="btn btn-dark"><span class="lang-ar">الصفحة الرئيسية</span><span class="lang-en">Home</span></a>
        <a href="booking.php" class="btn btn-gold"><span class="lang-ar">حجز جولة أخرى</span><span class="lang-en">Book Another</span></a>
      </div>
    </div>
<?php else: ?>

    <?php if (!$signed_in): ?>
    <div class="alert alert-info" style="max-width:540px;margin:0 auto 24px">
      <span class="lang-ar"><strong>ملاحظة:</strong> الحجز متاح للمستخدمين المسجّلين فقط. الرجاء <a href="login.php">تسجيل الدخول</a> أو <a href="register.php">إنشاء حساب</a>.</span>
      <span class="lang-en"><strong>Note:</strong> Booking is for registered users only. Please <a href="login.php">sign in</a> or <a href="register.php">register</a>.</span>
    </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-error" style="max-width:540px;margin:0 auto 24px">
      <strong><span class="lang-ar">الرجاء تصحيح ما يلي:</span><span class="lang-en">Please fix the following:</span></strong>
      <ul style="margin:8px 20px 0">
        <?php foreach ($errors as $e): ?>
          <li><span class="lang-ar"><?= $e['ar'] ?></span><span class="lang-en"><?= $e['en'] ?></span></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <div class="form-wrap">
      <form action="booking.php" method="POST">
        <div class="form-group">
          <label><span class="lang-ar">الوجهة</span><span class="lang-en">Destination</span> <span class="req">*</span></label>
          <select id="place" name="place" required onchange="updateTotal()">
            <option value="">-- اختر الوجهة / Choose --</option>
            <option value="Hegra" data-price="250" <?= sel($old['place'],'Hegra') ?>>Hegra الحِجر — 250 SAR</option>
            <option value="Qasr Al-Farid" data-price="200" <?= sel($old['place'],'Qasr Al-Farid') ?>>Qasr Al-Farid قصر الفريد — 200 SAR</option>
            <option value="Old Town" data-price="120" <?= sel($old['place'],'Old Town') ?>>Old Town البلدة القديمة — 120 SAR</option>
            <option value="Wildlife Safari" data-price="180" <?= sel($old['place'],'Wildlife Safari') ?>>Wildlife Safari رحلة برية — 180 SAR</option>
            <option value="Skydiving" data-price="900" <?= sel($old['place'],'Skydiving') ?>>Skydiving القفز المظلي — 900 SAR</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">تاريخ الجولة</span><span class="lang-en">Tour Date</span> <span class="req">*</span></label>
          <input type="date" id="date" name="date" required min="<?= $min_date ?>" value="<?= v($old['date']) ?>">
          <small style="color:var(--ink-soft);font-size:.8rem">
            <span class="lang-ar">لا يمكن اختيار تاريخ قبل اليوم.</span>
            <span class="lang-en">Past dates cannot be selected.</span>
          </small>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">الوقت</span><span class="lang-en">Time</span> <span class="req">*</span></label>
          <select id="time" name="time" required>
            <option value="">-- اختر الوقت / Choose --</option>
            <option value="08:00" <?= sel($old['time'],'08:00') ?>>08:00 AM</option>
            <option value="10:00" <?= sel($old['time'],'10:00') ?>>10:00 AM</option>
            <option value="14:00" <?= sel($old['time'],'14:00') ?>>02:00 PM</option>
            <option value="17:00" <?= sel($old['time'],'17:00') ?>>05:00 PM (Sunset)</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">عدد الضيوف</span><span class="lang-en">Guests</span> <span class="req">*</span></label>
          <input type="number" id="guests" name="guests" required min="1" max="10" value="<?= v($old['guests'] ?: '1') ?>" onchange="updateTotal()">
        </div>
        <div class="form-group">
          <label><span class="lang-ar">طلبات خاصة</span><span class="lang-en">Special Requests</span></label>
          <textarea name="special_requests" rows="3"><?= v($old['special_requests']) ?></textarea>
        </div>
        <div class="alert alert-info">
          <span class="lang-ar"><strong>الإجمالي:</strong> <span id="total-price">0</span> ريال</span>
          <span class="lang-en"><strong>Total:</strong> <span id="total-price2">0</span> SAR</span>
        </div>
        <button type="submit" class="btn btn-gold"><span class="lang-ar">تأكيد الحجز</span><span class="lang-en">Confirm Booking</span></button>
      </form>
    </div>
<?php endif; ?>

  </div>
</section>

<footer>
  <div class="footer-grid">
    <div>
      <h4><span class="lang-ar">العُلا رؤية ٢٠٣٠</span><span class="lang-en">AlUla Vision 2030</span></h4>
      <p><span class="lang-ar">منصة سياحية تعليمية تبرز جمال العُلا وتدعم أهداف رؤية المملكة ٢٠٣٠ في السياحة والثقافة والتحول الرقمي.</span><span class="lang-en">An educational tourism platform showcasing AlUla and supporting Vision 2030 goals in tourism, culture, and digital transformation.</span></p>
    </div>
    <div>
      <h4><span class="lang-ar">روابط</span><span class="lang-en">Links</span></h4>
      <a href="tourism.html"><span class="lang-ar">اكتشف العُلا</span><span class="lang-en">Discover</span></a>
      <a href="booking.php"><span class="lang-ar">احجز جولة</span><span class="lang-en">Book a Tour</span></a>
      <a href="feedback.php"><span class="lang-ar">آراء الزوار</span><span class="lang-en">Feedback</span></a>
    </div>
    <div>
      <h4><span class="lang-ar">تواصل</span><span class="lang-en">Contact</span></h4>
      <a href="mailto:info@alula-vision.sa">info@alula-vision.sa</a>
      <a href="tel:+966112589999">+966 11 258 9999</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span class="lang-ar">© الفصل الثاني ٢٠٢٦-٢٧ / جامعة الإمام / كلية علوم الحاسب — مشروع مقرر IS337</span>
    <span class="lang-en">© 2nd 2026-27 / IMSIU / CCIS — IS337 Group Project</span>
  </div>
</footer>

<script src="js/main.js"></script>
<script>
function updateTotal(){
  var s=document.getElementById('place');
  if(!s) return;
  var g=parseInt(document.getElementById('guests').value)||0;
  var opt=s.options[s.selectedIndex];
  var p=parseInt(opt?opt.getAttribute('data-price'):0)||0;
  document.getElementById('total-price').textContent=p*g;
  document.getElementById('total-price2').textContent=p*g;
}
updateTotal();
</script>
</body>
</html>
