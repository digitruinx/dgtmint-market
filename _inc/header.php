<?php
// Call mkt_session_start() before including this file
$_nav_user = mkt_is_logged_in() ? mkt_current_user() : null;
$_nav_initials = $_nav_user ? strtoupper(substr($_nav_user['name'],0,1)) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($page_title ?? 'DGT Market') ?> — Digital Products by Digitruinx</title>
  <meta name="description" content="<?= htmlspecialchars($page_desc ?? 'India\'s digital product marketplace by Digitruinx.') ?>" />
  <link rel="canonical" href="<?= SITE_URL . htmlspecialchars($_SERVER['REQUEST_URI']) ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= SITE_URL ?>/_assets/css/market.css" />
  <?php if (!empty($extra_head)) echo $extra_head; ?>

  <!-- Google tag (gtag.js) — GA4 -->
  <?php if (defined('GA4_MEASUREMENT_ID') && GA4_MEASUREMENT_ID): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= GA4_MEASUREMENT_ID ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= GA4_MEASUREMENT_ID ?>', { anonymize_ip: true });
  </script>
  <?php endif; ?>
</head>
<body>

<nav class="nav">
  <div class="container">
    <div class="nav__inner">
      <a href="<?= SITE_URL ?>/" class="nav__brand">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
          <rect width="28" height="28" rx="8" fill="#1dd1a1" fill-opacity="0.12"/>
          <path d="M7 14h3l3-7 4 14 3-7h4" stroke="#1dd1a1" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        DGT<span class="dot">.</span>Market
      </a>
      <div class="nav__search">
        <span class="nav__search-icon">⌕</span>
        <input type="search" id="global-search" placeholder="Search themes, agents, MCQs…" autocomplete="off" />
      </div>
      <ul class="nav__links">
        <li><a href="<?= SITE_URL ?>/#products">Products</a></li>
        <li><a href="<?= SITE_URL ?>/#pricing">Pricing</a></li>
        <?php if ($_nav_user): ?>
          <li>
            <a href="<?= SITE_URL ?>/account/" class="nav__user">
              <span class="nav__user-dot"></span>
              <?= htmlspecialchars($_nav_user['name']) ?>
            </a>
          </li>
          <li><a href="<?= SITE_URL ?>/logout.php" class="btn btn--ghost btn--sm">Sign Out</a></li>
        <?php else: ?>
          <li><a href="<?= SITE_URL ?>/login.php">Sign In</a></li>
          <li><a href="<?= SITE_URL ?>/register.php" class="btn btn--primary btn--sm">Get Started</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
