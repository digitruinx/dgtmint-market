<?php
require_once __DIR__ . '/_inc/db.php';
require_once __DIR__ . '/_inc/config.php';
require_once __DIR__ . '/_inc/auth.php';
require_once __DIR__ . '/_inc/products.php';
mkt_session_start();
mkt_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !mkt_verify_csrf($_POST['_csrf'] ?? '')) {
    http_response_code(400);
    exit('Invalid request');
}

$rzp_payment_id = trim($_POST['rzp_payment_id'] ?? '');
$rzp_order_id   = trim($_POST['rzp_order_id']   ?? '');
$rzp_signature  = trim($_POST['rzp_signature']   ?? '');
$product_id     = trim($_POST['product_id']      ?? '');
$tier           = ($_POST['tier'] ?? 'pro') === 'free' ? 'free' : 'pro';
$user           = mkt_current_user();

// Verify Razorpay signature
$expected = hash_hmac('sha256', $rzp_order_id.'|'.$rzp_payment_id, RZP_KEY_SECRET);
if (!hash_equals($expected, $rzp_signature)) {
    // Signature mismatch — mark failed, redirect with error
    db_execute(
        'UPDATE mkt_orders SET status="failed", rzp_payment_id=?, updated_at=NOW() WHERE rzp_order_id=? AND user_id=?',
        [$rzp_payment_id, $rzp_order_id, (int)$user['id']]
    );
    $_SESSION['mkt_flash_error'] = 'Payment verification failed. If money was deducted contact support.';
    header('Location: '.SITE_URL.'/account/');
    exit;
}

// Signature valid — mark paid
$product = get_product($product_id);
db_execute(
    'UPDATE mkt_orders SET status="paid", rzp_payment_id=?, rzp_signature=?, updated_at=NOW()
     WHERE rzp_order_id=? AND user_id=?',
    [$rzp_payment_id, $rzp_signature, $rzp_order_id, (int)$user['id']]
);
$order_id = db_val('SELECT id FROM mkt_orders WHERE rzp_order_id=?', [$rzp_order_id]);

// Grant access record
try {
    db_execute(
        'INSERT IGNORE INTO mkt_access (user_id,product_id,order_id,granted_at)
         VALUES (?,?,?,NOW())',
        [(int)$user['id'], $product_id, (int)$order_id]
    );
} catch (Throwable $e) {
    error_log('[DGT Market] access grant failed: '.$e->getMessage());
}

// Generate download token for downloadable products
if ($product && $product['type'] === 'download') {
    $token   = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+48 hours'));
    db_execute(
        'UPDATE mkt_orders SET download_token=?, download_expires=? WHERE id=?',
        [$token, $expires, (int)$order_id]
    );
}

$_SESSION['mkt_flash'] = 'Payment successful! '
    .($product ? htmlspecialchars($product['name']) : 'Your product')
    .' is now in your account.';
header('Location: '.SITE_URL.'/account/');
exit;
