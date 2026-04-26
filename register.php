<?php
require_once __DIR__ . '/_inc/db.php';
require_once __DIR__ . '/_inc/config.php';
require_once __DIR__ . '/_inc/auth.php';
mkt_session_start();

if (mkt_is_logged_in()) { header('Location: '.SITE_URL.'/account/'); exit; }

$error  = '';
$next   = htmlspecialchars(trim($_GET['next'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!mkt_verify_csrf($_POST['_csrf'] ?? '')) {
        $error = 'Invalid request.';
    } elseif (!mkt_rate_limit('register', 3, 3600)) {
        $error = 'Too many registrations from this session.';
    } else {
        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $mobile   = trim($_POST['mobile']   ?? '');
        $password = $_POST['password']      ?? '';
        $confirm  = $_POST['confirm']       ?? '';

        if (strlen($name) < 2)                          $error = 'Please enter your full name.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Enter a valid email address.';
        elseif (strlen($password) < 8)                  $error = 'Password must be at least 8 characters.';
        elseif ($password !== $confirm)                  $error = 'Passwords do not match.';
        elseif ($mobile && !preg_match('/^[6-9]\d{9}$/', $mobile)) $error = 'Enter a valid 10-digit Indian mobile number.';
        else {
            $result = mkt_register($name, $email, $password, $mobile);
            if ($result['ok']) {
                $go = ($next && str_starts_with($next, '/')) ? SITE_URL.$next : SITE_URL.'/account/';
                header('Location: '.$go);
                exit;
            }
            $error = $result['msg'];
        }
    }
}

$page_title = 'Create Account';
$csrf = mkt_csrf_token();
require_once __DIR__ . '/_inc/header.php';
?>

<div class="auth-wrap">
  <div class="auth-box">
    <div class="auth-logo">
      <svg width="26" height="26" viewBox="0 0 28 28" fill="none"><rect width="28" height="28" rx="8" fill="#1dd1a1" fill-opacity="0.12"/><path d="M7 14h3l3-7 4 14 3-7h4" stroke="#1dd1a1" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
      DGT<span class="dot">.</span>Market
    </div>
    <div class="auth-title">Create your account</div>
    <div class="auth-sub">Free to join. No credit card needed.</div>

    <?php if ($error): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <input type="hidden" name="_csrf" value="<?= $csrf ?>" />
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" required autocomplete="name"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Aditya Sai" />
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" required autocomplete="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="you@example.com" />
      </div>
      <div class="form-group">
        <label>Mobile <span style="color:var(--text-dim);font-weight:400;">(optional)</span></label>
        <input type="tel" name="mobile" autocomplete="tel" maxlength="10"
               value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>" placeholder="9876543210" />
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters" />
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm" required autocomplete="new-password" placeholder="Repeat password" />
      </div>
      <button type="submit" class="btn btn--primary btn--full" style="margin-top:8px;">Create Account</button>
    </form>

    <p style="text-align:center;margin-top:20px;font-size:0.85rem;color:var(--text-muted);">
      Already have an account? <a href="<?= SITE_URL ?>/login.php">Sign in</a>
    </p>
    <p style="text-align:center;margin-top:12px;font-size:0.75rem;color:var(--text-dim);">
      By creating an account you agree to our terms. GST invoices available on request.
    </p>
  </div>
</div>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
