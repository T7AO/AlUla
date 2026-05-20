<?php
session_start();
// ============================================
// Login page — handles its own POST.
// Errors keep the email on the page; success redirects home.
// ============================================

$errors = [];
$old = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include __DIR__ . '/php/db_connect.php';

    $old['email'] = trim($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';

    if ($old['email'] === '' || $password === '') {
        $errors[] = ['ar'=>'الرجاء إدخال البريد الإلكتروني وكلمة المرور.', 'en'=>'Please enter both email and password.'];
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $old['email']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $errors[] = ['ar'=>'هذا البريد غير مسجّل. <a href="register.php">أنشئ حسابًا</a> أولًا.',
                         'en'=>'This email is not registered. <a href="register.php">Create an account</a> first.'];
        } else {
            $stmt->bind_result($id, $name, $hash);
            $stmt->fetch();
            if (password_verify($password, $hash)) {
                $_SESSION['user_id']   = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['email']     = $old['email'];
                $stmt->close();
                $conn->close();
                header("Location: index.html");
                exit;
            } else {
                $errors[] = ['ar'=>'كلمة المرور غير صحيحة.', 'en'=>'The password is incorrect.'];
            }
        }
        $stmt->close();
    }
    $conn->close();
}

function v($s){ return htmlspecialchars($s, ENT_QUOTES); }

$PAGE_TITLE = 'تسجيل الدخول | Sign In';
$ACTIVE = 'login';
include __DIR__ . '/php/_header.php';
?>

<section class="hero" style="height:38vh;min-height:300px">
  <div class="hero-bg" style="background-image:url('images/places/al-farid.jpg')"></div>
  <div class="hero-content">
    <p class="eyebrow"><span class="lang-ar">مرحبًا بعودتك</span><span class="lang-en">Welcome Back</span></p>
    <h1><span class="lang-ar">تسجيل الدخول</span><span class="lang-en">Sign In</span></h1>
  </div>
</section>

<section class="section">
  <div class="section-narrow">

    <?php if (!empty($errors)): ?>
    <div class="alert alert-error" style="max-width:540px;margin:0 auto 24px">
      <strong><span class="lang-ar">تعذّر تسجيل الدخول:</span><span class="lang-en">Could not sign in:</span></strong>
      <ul style="margin:8px 20px 0">
        <?php foreach ($errors as $e): ?>
          <li><span class="lang-ar"><?= $e['ar'] ?></span><span class="lang-en"><?= $e['en'] ?></span></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <div class="form-wrap">
      <form action="login.php" method="POST">
        <div class="form-group">
          <label><span class="lang-ar">البريد الإلكتروني</span><span class="lang-en">Email</span> <span class="req">*</span></label>
          <input type="email" name="email" required value="<?= v($old['email']) ?>">
        </div>
        <div class="form-group">
          <label><span class="lang-ar">كلمة المرور</span><span class="lang-en">Password</span> <span class="req">*</span></label>
          <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-gold"><span class="lang-ar">دخول</span><span class="lang-en">Sign In</span></button>
        <p style="text-align:center;margin-top:16px;font-size:.9rem">
          <span class="lang-ar">جديد هنا؟ <a href="register.php" style="color:var(--terra)">أنشئ حسابًا</a></span>
          <span class="lang-en">New here? <a href="register.php" style="color:var(--terra)">Create an account</a></span>
        </p>
      </form>
    </div>

  </div>
</section>

<?php include __DIR__ . '/php/_footer.php'; ?>
</body>
</html>
