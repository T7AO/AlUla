<?php
session_start();
// ============================================
// Feedback page — handles its own POST.
// Errors keep input; success shows a styled thank-you, then the list.
// ============================================

$errors  = [];
$success = false;
$old = ['name'=>'','email'=>'','rating'=>'','message'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include __DIR__ . '/php/db_connect.php';

    foreach (['name','email','rating','message'] as $k) {
        if (isset($_POST[$k])) $old[$k] = trim($_POST[$k]);
    }
    $rating = (int)$old['rating'];

    if (strlen($old['name']) < 2) {
        $errors[] = ['ar'=>'الاسم مطلوب.', 'en'=>'Name is required.'];
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = ['ar'=>'البريد الإلكتروني غير صحيح.', 'en'=>'Email is not valid.'];
    }
    if ($rating < 1 || $rating > 5) {
        $errors[] = ['ar'=>'الرجاء اختيار تقييم.', 'en'=>'Please choose a rating.'];
    }
    if (strlen($old['message']) < 10) {
        $errors[] = ['ar'=>'الرأي يجب أن يكون 10 أحرف على الأقل.', 'en'=>'Feedback must be at least 10 characters.'];
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, rating, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $old['name'], $old['email'], $rating, $old['message']);
        if ($stmt->execute()) {
            $success = true;
            $old = ['name'=>'','email'=>'','rating'=>'','message'=>''];
        } else {
            $errors[] = ['ar'=>'تعذّر إرسال الرأي. حاول مرة أخرى.', 'en'=>'Could not submit feedback. Please try again.'];
        }
        $stmt->close();
    }
    $conn->close();
}

function v($s){ return htmlspecialchars($s, ENT_QUOTES); }
function sel($a,$b){ return (string)$a === (string)$b ? 'selected' : ''; }

$PAGE_TITLE = 'آراء الزوار | Feedback';
$ACTIVE = 'feedback';
include __DIR__ . '/php/_header.php';
?>

<section class="hero" style="height:38vh;min-height:300px">
  <div class="hero-bg" style="background-image:url('images/gallery/clay-pool.jpg')"></div>
  <div class="hero-content">
    <p class="eyebrow"><span class="lang-ar">شاركنا رأيك</span><span class="lang-en">Share Your Voice</span></p>
    <h1><span class="lang-ar">آراء الزوار</span><span class="lang-en">Visitor Feedback</span></h1>
  </div>
</section>

<section class="section">
  <div class="section-narrow">

    <?php if ($success): ?>
    <div class="alert alert-success" style="max-width:540px;margin:0 auto 24px;text-align:center">
      <div style="font-size:2.2rem">✅</div>
      <strong><span class="lang-ar">شكرًا لك! تم إرسال رأيك بنجاح.</span><span class="lang-en">Thank you! Your feedback was submitted successfully.</span></strong>
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
      <form action="feedback.php" method="POST">
        <div class="form-group">
          <label><span class="lang-ar">الاسم</span><span class="lang-en">Your Name</span> <span class="req">*</span></label>
          <input type="text" name="name" required value="<?= v($old['name']) ?>">
        </div>
        <div class="form-group">
          <label><span class="lang-ar">البريد الإلكتروني</span><span class="lang-en">Your Email</span> <span class="req">*</span></label>
          <input type="email" name="email" required value="<?= v($old['email']) ?>">
        </div>
        <div class="form-group">
          <label><span class="lang-ar">التقييم</span><span class="lang-en">Rating</span> <span class="req">*</span></label>
          <select name="rating" required>
            <option value="">-- اختر / Choose --</option>
            <option value="5" <?= sel($old['rating'],'5') ?>>★★★★★ ممتاز / Excellent</option>
            <option value="4" <?= sel($old['rating'],'4') ?>>★★★★ جيد جدًا / Very Good</option>
            <option value="3" <?= sel($old['rating'],'3') ?>>★★★ جيد / Good</option>
            <option value="2" <?= sel($old['rating'],'2') ?>>★★ مقبول / Fair</option>
            <option value="1" <?= sel($old['rating'],'1') ?>>★ ضعيف / Poor</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="lang-ar">رأيك</span><span class="lang-en">Your Feedback</span> <span class="req">*</span></label>
          <textarea name="message" rows="5" required minlength="10"><?= v($old['message']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-gold"><span class="lang-ar">إرسال</span><span class="lang-en">Submit</span></button>
      </form>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="section-narrow">
    <div class="section-head reveal">
      <h2><span class="lang-ar">ماذا قال زوارنا</span><span class="lang-en">What Visitors Say</span></h2>
      <div class="divider-line"></div>
    </div>
    <div style="max-width:760px;margin:0 auto">
<?php
include __DIR__ . '/php/db_connect.php';
$result = $conn->query("SELECT name, rating, message, created_at FROM feedback ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $r     = (int)$row['rating'];
        $stars = str_repeat("&#9733;", $r) . str_repeat("&#9734;", 5 - $r);
        $name  = htmlspecialchars($row['name']);
        $msg   = htmlspecialchars($row['message']);
        $date  = date('Y-m-d', strtotime($row['created_at']));
        echo '<div style="background:var(--white);padding:26px;margin-bottom:16px;box-shadow:var(--shadow-1);border-right:4px solid var(--gold)">';
        echo '<div style="color:var(--gold);font-size:1.2rem">' . $stars . '</div>';
        echo '<p style="margin:10px 0">"' . $msg . '"</p>';
        echo '<p style="color:var(--terra);font-weight:600">&mdash; ' . $name . ' <span style="color:var(--ink-soft);font-weight:400;font-size:.85rem">| ' . $date . '</span></p>';
        echo '</div>';
    }
} else {
    echo '<p style="text-align:center;color:var(--ink-soft)">';
    echo '<span class="lang-ar">لا توجد آراء بعد. كن أول من يشاركنا تجربته!</span>';
    echo '<span class="lang-en">No feedback yet. Be the first to share your experience!</span>';
    echo '</p>';
}
$conn->close();
?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/php/_footer.php'; ?>
</body>
</html>
