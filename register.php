<?php
session_start();
// ============================================
// Register page — handles its own POST.
// Errors keep input on the page; success shows a styled confirmation.
// ============================================

$errors  = [];
$success = false;

$old = ['name'=>'','user_id'=>'','dob'=>'','nationality'=>'','mobile'=>'','email'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include __DIR__ . '/php/db_connect.php';

    foreach (['name','user_id','dob','nationality','mobile','email'] as $k) {
        if (isset($_POST[$k])) $old[$k] = trim($_POST[$k]);
    }
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (strlen($old['name']) < 3) {
        $errors[] = ['ar'=>'الاسم مطلوب (3 أحرف على الأقل).', 'en'=>'Name is required (min 3 characters).'];
    }
    if (!preg_match('/^[0-9]{10}$/', $old['user_id'])) {
        $errors[] = ['ar'=>'رقم الهوية يجب أن يكون 10 أرقام بالضبط.', 'en'=>'National ID must be exactly 10 digits.'];
    }
    if ($old['dob'] === '') {
        $errors[] = ['ar'=>'تاريخ الميلاد مطلوب.', 'en'=>'Date of birth is required.'];
    } else {
        $dob = DateTime::createFromFormat('Y-m-d', $old['dob']);
        $today = new DateTime('today');
        if (!$dob) {
            $errors[] = ['ar'=>'صيغة تاريخ الميلاد غير صحيحة.', 'en'=>'Invalid date of birth.'];
        } elseif ($dob >= $today) {
            $errors[] = ['ar'=>'تاريخ الميلاد يجب أن يكون في الماضي.', 'en'=>'Date of birth must be in the past.'];
        } else {
            $age = $today->diff($dob)->y;
            if ($age < 12) {
                $errors[] = ['ar'=>'يجب أن يكون عمرك 12 سنة على الأقل للتسجيل.', 'en'=>'You must be at least 12 years old to register.'];
            } elseif ($age > 120) {
                $errors[] = ['ar'=>'تاريخ الميلاد غير منطقي.', 'en'=>'Date of birth is not realistic.'];
            }
        }
    }
    if ($old['nationality'] === '') {
        $errors[] = ['ar'=>'الجنسية مطلوبة.', 'en'=>'Nationality is required.'];
    }
    if (!preg_match('/^05[0-9]{8}$/', $old['mobile'])) {
        $errors[] = ['ar'=>'رقم الجوال يجب أن يبدأ بـ 05 ويتكون من 10 أرقام.', 'en'=>'Mobile must start with 05 and be 10 digits.'];
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = ['ar'=>'البريد الإلكتروني غير صحيح.', 'en'=>'Email is not valid.'];
    }
    if (strlen($password) < 6) {
        $errors[] = ['ar'=>'كلمة المرور يجب أن تكون 6 أحرف على الأقل.', 'en'=>'Password must be at least 6 characters.'];
    }
    if ($password !== $confirm) {
        $errors[] = ['ar'=>'كلمتا المرور غير متطابقتين.', 'en'=>'Passwords do not match.'];
    }

    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $old['email']);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors[] = ['ar'=>'هذا البريد مسجّل مسبقًا. <a href="login.php">سجّل الدخول</a> بدلًا من ذلك.',
                         'en'=>'This email is already registered. <a href="login.php">Sign in</a> instead.'];
        }
        $check->close();
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare(
            "INSERT INTO users (name, user_id, dob, nationality, mobile, email, password)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssssss", $old['name'], $old['user_id'], $old['dob'], $old['nationality'], $old['mobile'], $old['email'], $hash);
        if ($stmt->execute()) {
            $success = true;
            $reg_name = $old['name'];
        } else {
            $errors[] = ['ar'=>'تعذّر إنشاء الحساب. حاول مرة أخرى.', 'en'=>'Could not create the account. Please try again.'];
        }
        $stmt->close();
    }
    $conn->close();
}

function v($s){ return htmlspecialchars($s, ENT_QUOTES); }
function sel($a,$b){ return $a === $b ? 'selected' : ''; }
$max_dob = date('Y-m-d', strtotime('-12 years'));

$PAGE_TITLE = 'حساب جديد | Register';
$ACTIVE = 'register';
include __DIR__ . '/php/_header.php';
?>

<section class="hero" style="height:38vh;min-height:300px">
  <div class="hero-bg" style="background-image:url('images/gallery/resort-night.jpg')"></div>
  <div class="hero-content">
    <p class="eyebrow"><span class="lang-ar">انضم إلينا</span><span class="lang-en">Join Us</span></p>
    <h1><span class="lang-ar">حساب جديد</span><span class="lang-en">Create Account</span></h1>
  </div>
</section>

<section class="section">
  <div class="section-narrow">

<?php if ($success): ?>
    <div class="form-wrap" style="text-align:center">
      <div style="font-size:3.4rem;margin-bottom:10px">✅</div>
      <h2 style="color:var(--terra);font-family:var(--serif-ar);margin-bottom:8px">
        <span class="lang-ar">تم إنشاء الحساب بنجاح!</span><span class="lang-en">Account Created!</span>
      </h2>
      <p style="color:var(--ink-soft);margin-bottom:24px">
        <span class="lang-ar">مرحبًا <?= v($reg_name) ?>! يمكنك الآن تسجيل الدخول.</span>
        <span class="lang-en">Welcome <?= v($reg_name) ?>! You can now sign in.</span>
      </p>
      <a href="login.php" class="btn btn-gold"><span class="lang-ar">تسجيل الدخول</span><span class="lang-en">Sign In</span></a>
    </div>
<?php else: ?>

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
      <form action="register.php" method="POST" onsubmit="return validateRegistration()">
        <div class="form-group">
          <label><span class="lang-ar">الاسم الكامل</span><span class="lang-en">Full Name</span> <span class="req">*</span></label>
          <input type="text" id="name" name="name" required minlength="3" value="<?= v($old['name']) ?>">
          <span class="err" id="name-error"><span class="lang-ar">الرجاء إدخال الاسم (3 أحرف على الأقل).</span><span class="lang-en">Enter your full name (min 3 chars).</span></span>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">رقم الهوية / الإقامة</span><span class="lang-en">National ID / Iqama</span> <span class="req">*</span></label>
          <input type="text" id="user_id" name="user_id" required pattern="[0-9]{10}" value="<?= v($old['user_id']) ?>">
          <span class="err" id="id-error"><span class="lang-ar">يجب أن يكون 10 أرقام بالضبط.</span><span class="lang-en">Must be exactly 10 digits.</span></span>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">تاريخ الميلاد</span><span class="lang-en">Date of Birth</span> <span class="req">*</span></label>
          <input type="date" id="dob" name="dob" required max="<?= $max_dob ?>" value="<?= v($old['dob']) ?>">
          <small style="color:var(--ink-soft);font-size:.8rem">
            <span class="lang-ar">يجب أن يكون عمرك 12 سنة على الأقل.</span>
            <span class="lang-en">You must be at least 12 years old.</span>
          </small>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">الجنسية</span><span class="lang-en">Nationality</span> <span class="req">*</span></label>
          <select id="nationality" name="nationality" required>
            <option value="">-- اختر / Choose --</option>
            <option value="Saudi" <?= sel($old['nationality'],'Saudi') ?>>Saudi / سعودي</option>
            <option value="Emirati" <?= sel($old['nationality'],'Emirati') ?>>Emirati / إماراتي</option>
            <option value="Kuwaiti" <?= sel($old['nationality'],'Kuwaiti') ?>>Kuwaiti / كويتي</option>
            <option value="Bahraini" <?= sel($old['nationality'],'Bahraini') ?>>Bahraini / بحريني</option>
            <option value="Qatari" <?= sel($old['nationality'],'Qatari') ?>>Qatari / قطري</option>
            <option value="Omani" <?= sel($old['nationality'],'Omani') ?>>Omani / عُماني</option>
            <option value="Other" <?= sel($old['nationality'],'Other') ?>>Other / أخرى</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">رقم الجوال</span><span class="lang-en">Mobile Number</span> <span class="req">*</span></label>
          <input type="tel" id="mobile" name="mobile" required pattern="05[0-9]{8}" placeholder="05XXXXXXXX" value="<?= v($old['mobile']) ?>">
          <span class="err" id="mobile-error"><span class="lang-ar">يجب أن يبدأ بـ 05 ويتكون من 10 أرقام.</span><span class="lang-en">Must start with 05 and be 10 digits.</span></span>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">البريد الإلكتروني</span><span class="lang-en">Email</span> <span class="req">*</span></label>
          <input type="email" id="email" name="email" required value="<?= v($old['email']) ?>">
          <span class="err" id="email-error"><span class="lang-ar">بريد إلكتروني غير صحيح.</span><span class="lang-en">Invalid email address.</span></span>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">كلمة المرور</span><span class="lang-en">Password</span> <span class="req">*</span></label>
          <input type="password" id="password" name="password" required minlength="6">
          <span class="err" id="password-error"><span class="lang-ar">6 أحرف على الأقل.</span><span class="lang-en">Min 6 characters.</span></span>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">تأكيد كلمة المرور</span><span class="lang-en">Confirm Password</span> <span class="req">*</span></label>
          <input type="password" id="confirm_password" name="confirm_password" required>
          <span class="err" id="confirm-error"><span class="lang-ar">كلمتا المرور غير متطابقتين.</span><span class="lang-en">Passwords do not match.</span></span>
        </div>
        <button type="submit" class="btn btn-gold"><span class="lang-ar">تسجيل</span><span class="lang-en">Register</span></button>
        <p style="text-align:center;margin-top:16px;font-size:.9rem">
          <span class="lang-ar">لديك حساب؟ <a href="login.php" style="color:var(--terra)">سجّل الدخول</a></span>
          <span class="lang-en">Have an account? <a href="login.php" style="color:var(--terra)">Sign in</a></span>
        </p>
      </form>
    </div>
<?php endif; ?>

  </div>
</section>

<?php include __DIR__ . '/php/_footer.php'; ?>
<script src="js/validation.js"></script>
</body>
</html>
