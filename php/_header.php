<?php
// Shared header. Set $PAGE_TITLE and $ACTIVE before including.
if (!isset($PAGE_TITLE)) $PAGE_TITLE = 'العُلا رؤية 2030';
if (!isset($ACTIVE))     $ACTIVE = '';
function navActive($p, $active) { return $p === $active ? ' class="active"' : ''; }
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($PAGE_TITLE) ?></title>
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
    <li><a href="index.html"<?= navActive('index',$ACTIVE) ?>><span class="lang-ar">الرئيسية</span><span class="lang-en">Home</span></a></li>
    <li><a href="about.html"<?= navActive('about',$ACTIVE) ?>><span class="lang-ar">من نحن</span><span class="lang-en">About</span></a></li>
    <li>
      <a href="tourism.html"<?= navActive('tourism',$ACTIVE) ?>><span class="lang-ar">اكتشف العُلا ▾</span><span class="lang-en">Discover ▾</span></a>
      <ul class="dropdown">
        <li><a href="tourism.html#hegra"><span class="lang-ar">الحِجر (مدائن صالح)</span><span class="lang-en">Hegra</span></a></li>
        <li><a href="tourism.html#farid"><span class="lang-ar">قصر الفريد</span><span class="lang-en">Qasr Al-Farid</span></a></li>
        <li><a href="tourism.html#oldtown"><span class="lang-ar">البلدة القديمة</span><span class="lang-en">Old Town</span></a></li>
        <li><a href="tourism.html#nature"><span class="lang-ar">الطبيعة والحياة البرية</span><span class="lang-en">Nature & Wildlife</span></a></li>
        <li><a href="tourism.html#adventure"><span class="lang-ar">المغامرات</span><span class="lang-en">Adventure</span></a></li>
      </ul>
    </li>
    <li><a href="gallery.html"<?= navActive('gallery',$ACTIVE) ?>><span class="lang-ar">معرض الصور</span><span class="lang-en">Gallery</span></a></li>
    <li><a href="booking.php"<?= navActive('booking',$ACTIVE) ?>><span class="lang-ar">احجز جولة</span><span class="lang-en">Book a Tour</span></a></li>
    <li><a href="feedback.php"<?= navActive('feedback',$ACTIVE) ?>><span class="lang-ar">آراء الزوار</span><span class="lang-en">Feedback</span></a></li>
    <li><a href="chatbot.html"<?= navActive('chatbot',$ACTIVE) ?>><span class="lang-ar">المساعد الذكي</span><span class="lang-en">AI Assistant</span></a></li>
    <li><a href="register.php"<?= navActive('register',$ACTIVE) ?>><span class="lang-ar">حساب جديد</span><span class="lang-en">Register</span></a></li>
    <li><a href="login.php"<?= navActive('login',$ACTIVE) ?>><span class="lang-ar">دخول</span><span class="lang-en">Sign In</span></a></li>
  </ul>
</nav>
