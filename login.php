<?php
require_once __DIR__ . '/_inc/db.php';
require_once __DIR__ . '/_inc/config.php';
require_once __DIR__ . '/_inc/auth.php';
mkt_session_start();

if (mkt_is_logged_in()) {
    header('Location: '.SITE_URL.'/account/');
    exit;
}

$error = '';
$next  = htmlspecialchars(trim($_GET['next'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!mkt_verify_csrf($_POST['_csrf'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } elseif (!mkt_rate_limit('login', 8, 600)) {
        $error = 'Too many attempts. Please wait 10 minutes.';
    } else {
        $result = mkt_login(trim($_POST['email'] ?? ''), $_POST['password'] ?? '');
        if ($result['ok']) {
            $go = ($next && str_starts_with($next, '/')) ? SITE_URL.$next : SITE_URL.'/account/';
            header('Location: '.$go);
            exit;
        }
        $error = $result['msg'];
    }
}

$page_title = 'Sign In';
$csrf = mkt_csrf_token();
require_once __DIR__ . '/_inc/header.php';
?>

<div class="auth-wrap">
  <div class="auth-box">
    <div class="auth-logo">
      <svg width="26" height="26" viewBox="0 0 28 28" fill="none"><rect width="28" height="28" rx="8" fill="#1dd1a1" fill-opacity="0.12"/><path d="M7 14h3l3-7 4 14 3-7h4" stroke="#1dd1a1" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
      DGT<span class="dot">.</span>Market
    </div>
    <div class="auth-title">Welcome back</div>
    <div class="auth-sub">Sign in to access your purchases and downloads</div>

    <?php if ($error): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" novalidate>
      <input type="hidden" name="_csrf" value="<?= $csrf ?>" />
      <?php if ($next): ?><input type="hidden" name="next" value="<?= $next ?>" /><?php endif; ?>
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" required autocomplete="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="you@example.com" />
      </div>
      <div class="form-group">
        <label for="password" style="display:flex;justify-content:space-between;">
          Password
          <a href="#" style="font-size:0.78rem;">Forgot password?</a>
        </label>
        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
      </div>
      <button type="submit" class="btn btn--primary btn--full" style="margin-top:8px;">Sign In</button>
    </form>

    <p style="text-align:center;margin-top:20px;font-size:0.85rem;color:var(--text-muted);">
      Don't have an account? <a href="<?= SITE_URL ?>/register.php<?= $next ? '?next='.urlencode($next) : '' ?>">Create one</a>
    </p>
  </div>
</div>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
