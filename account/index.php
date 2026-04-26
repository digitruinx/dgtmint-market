<?php
require_once dirname(__DIR__) . '/_inc/db.php';
require_once dirname(__DIR__) . '/_inc/config.php';
require_once dirname(__DIR__) . '/_inc/auth.php';
require_once dirname(__DIR__) . '/_inc/products.php';
mkt_session_start();
mkt_require_login('/account/');

$user    = mkt_current_user();
$flash   = $_SESSION['mkt_flash']       ?? '';
$ferror  = $_SESSION['mkt_flash_error'] ?? '';
unset($_SESSION['mkt_flash'], $_SESSION['mkt_flash_error']);

$orders = db_query(
    'SELECT o.*, o.created_at as purchased_at
     FROM mkt_orders o
     WHERE o.user_id=? ORDER BY o.created_at DESC',
    [(int)$user['id']]
)->fetchAll();

$page_title = 'My Account';
require_once dirname(__DIR__) . '/_inc/header.php';
?>

<div class="account-wrap">
  <div class="container">

    <?php if ($flash):  ?><div class="alert alert--success" style="margin-bottom:20px;"><?= htmlspecialchars($flash) ?></div><?php endif; ?>
    <?php if ($ferror): ?><div class="alert alert--error"   style="margin-bottom:20px;"><?= htmlspecialchars($ferror) ?></div><?php endif; ?>

    <div class="account-grid">

      <!-- Sidebar -->
      <div class="account-sidebar">
        <div class="account-avatar"><?= htmlspecialchars(strtoupper(substr($user['name'],0,1))) ?></div>
        <div class="account-name"><?= htmlspecialchars($user['name']) ?></div>
        <div class="account-email"><?= htmlspecialchars($user['email']) ?></div>
        <?php if ($user['mobile']): ?>
          <div style="text-align:center;font-size:0.78rem;color:var(--text-dim);margin-bottom:16px;"><?= htmlspecialchars($user['mobile']) ?></div>
        <?php endif; ?>
        <ul class="sidebar-nav">
          <li><a href="/account/" class="active">📦 My Purchases</a></li>
          <li><a href="/account/?tab=downloads">⬇️ Downloads</a></li>
          <li><a href="/account/?tab=access">🔑 Active Access</a></li>
          <li><a href="/account/?tab=profile">👤 Profile</a></li>
          <li><a href="<?= SITE_URL ?>/">🛍️ Browse Market</a></li>
          <li><a href="<?= SITE_URL ?>/logout.php" style="color:var(--danger);">↩ Sign Out</a></li>
        </ul>
      </div>

      <!-- Main -->
      <div class="account-main">

        <!-- Stats row -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
          <?php
            $total_paid     = count(array_filter($orders, fn($o) => $o['status']==='paid'));
            $total_spent    = array_sum(array_map(fn($o) => $o['status']==='paid' ? $o['amount_rupees'] : 0, $orders));
            $total_downloads= count(array_filter($orders, fn($o) => $o['status']==='paid' && $o['download_token']));
          ?>
          <div class="account-section" style="padding:20px;text-align:center;">
            <div style="font-size:2rem;font-weight:800;color:var(--mint);"><?= $total_paid ?></div>
            <div style="font-size:0.8rem;color:var(--text-muted);">Purchases</div>
          </div>
          <div class="account-section" style="padding:20px;text-align:center;">
            <div style="font-size:2rem;font-weight:800;color:var(--gold);">₹<?= number_format($total_spent) ?></div>
            <div style="font-size:0.8rem;color:var(--text-muted);">Total Spent</div>
          </div>
          <div class="account-section" style="padding:20px;text-align:center;">
            <div style="font-size:2rem;font-weight:800;color:var(--cyan);"><?= $total_downloads ?></div>
            <div style="font-size:0.8rem;color:var(--text-muted);">Downloads</div>
          </div>
        </div>

        <!-- Order history -->
        <div class="account-section">
          <h2>Purchase History</h2>
          <?php if (empty($orders)): ?>
            <div class="empty-state">
              <div class="es-icon">🛍️</div>
              <p>You haven't bought anything yet.</p>
              <a href="<?= SITE_URL ?>/" class="btn btn--primary btn--sm">Browse Products</a>
            </div>
          <?php else: ?>
            <?php foreach ($orders as $order):
              $prod = get_product($order['product_id']);
              $icon = $prod ? $prod['icon'] : '📦';
            ?>
            <div class="purchase-card">
              <div class="purchase-icon"><?= $icon ?></div>
              <div class="purchase-info">
                <div class="purchase-name"><?= htmlspecialchars($order['product_name']) ?></div>
                <div class="purchase-meta">
                  <span class="badge <?= $order['tier']==='pro' ? 'badge--pro' : 'badge--free' ?>"><?= strtoupper($order['tier']) ?></span>
                  &nbsp;·&nbsp;
                  ₹<?= number_format($order['amount_rupees']) ?>
                  &nbsp;·&nbsp;
                  <?= date('d M Y', strtotime($order['purchased_at'])) ?>
                  <?php if ($order['rzp_payment_id']): ?>
                    &nbsp;·&nbsp; <span style="color:var(--text-dim);font-size:0.75rem;"><?= htmlspecialchars($order['rzp_payment_id']) ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <span class="purchase-status status--<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
              <?php if ($order['status']==='paid'): ?>
                <div style="margin-left:8px;display:flex;gap:6px;">
                  <?php if ($order['download_token'] && strtotime($order['download_expires']) > time()): ?>
                    <a href="<?= SITE_URL ?>/download.php?token=<?= urlencode($order['download_token']) ?>"
                       class="btn btn--primary btn--sm">⬇ Download</a>
                  <?php elseif ($prod && $prod['type']==='access'): ?>
                    <a href="#" class="btn btn--outline btn--sm">🔑 Access</a>
                  <?php endif; ?>
                  <a href="<?= SITE_URL ?>/invoice.php?oid=<?= $order['id'] ?>" class="btn btn--ghost btn--sm">🧾 Invoice</a>
                </div>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Active access -->
        <div class="account-section">
          <h2>Active Access</h2>
          <?php
            $access_rows = db_query(
                'SELECT a.*, o.product_name FROM mkt_access a JOIN mkt_orders o ON o.id=a.order_id WHERE a.user_id=? ORDER BY a.granted_at DESC',
                [(int)$user['id']]
            )->fetchAll();
          ?>
          <?php if (empty($access_rows)): ?>
            <div class="empty-state">
              <div class="es-icon">🔑</div>
              <p>No active access yet. Buy an AI agent or LMS course to see it here.</p>
            </div>
          <?php else: ?>
            <?php foreach ($access_rows as $row):
              $prod = get_product($row['product_id']);
              $icon = $prod ? $prod['icon'] : '🔑';
            ?>
            <div class="purchase-card">
              <div class="purchase-icon"><?= $icon ?></div>
              <div class="purchase-info">
                <div class="purchase-name"><?= htmlspecialchars($row['product_name']) ?></div>
                <div class="purchase-meta">
                  Granted <?= date('d M Y', strtotime($row['granted_at'])) ?>
                  <?php if ($row['expires_at']): ?>
                    · Expires <?= date('d M Y', strtotime($row['expires_at'])) ?>
                  <?php else: ?>
                    · <span style="color:var(--mint);">Lifetime access</span>
                  <?php endif; ?>
                </div>
              </div>
              <a href="#" class="btn btn--outline btn--sm">Open →</a>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</div>

<?php require_once dirname(__DIR__) . '/_inc/footer.php'; ?>
