<?php
require_once __DIR__ . '/_inc/db.php';
require_once __DIR__ . '/_inc/config.php';
require_once __DIR__ . '/_inc/auth.php';
require_once __DIR__ . '/_inc/products.php';
mkt_session_start();
mkt_require_login();

$product_id = trim($_GET['pid'] ?? '');
$tier       = ($_GET['tier'] ?? 'pro') === 'free' ? 'free' : 'pro';
$product    = $product_id ? get_product($product_id) : null;

if (!$product) {
    header('Location: '.SITE_URL.'/');
    exit;
}

$user        = mkt_current_user();
$amount      = $tier === 'pro' ? $product['price_pro'] : $product['price_free'];
$already_own = mkt_user_owns((int)$user['id'], 0); // placeholder; pass product DB id once DB-driven

// If free tier, record and redirect immediately
if ($tier === 'free' && $amount === 0) {
    $_SESSION['mkt_flash'] = 'You now have free access to '.$product['name'].'. Enjoy!';
    header('Location: '.SITE_URL.'/account/');
    exit;
}

// Create Razorpay order via cURL
$rzp_order = null;
$rzp_error = '';
if ($amount > 0) {
    $payload = json_encode([
        'amount'   => $amount * 100, // paise
        'currency' => 'INR',
        'receipt'  => 'mkt_'.time().'_'.substr($product_id,0,10),
        'notes'    => ['product_id' => $product_id, 'user_id' => $user['id'], 'tier' => $tier],
    ]);
    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_USERPWD        => RZP_KEY_ID.':'.RZP_KEY_SECRET,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code === 200) {
        $rzp_order = json_decode($resp, true);
        // Store pending order in DB
        $order_db_id = db_insert(
            'INSERT INTO mkt_orders (user_id,product_id,product_name,tier,amount_rupees,rzp_order_id,status,ip_address,created_at)
             VALUES (?,?,?,?,?,?,"pending",?,NOW())',
            [(int)$user['id'], $product_id, $product['name'], $tier, $amount, $rzp_order['id'], $_SERVER['REMOTE_ADDR']??'']
        );
        $_SESSION['mkt_pending_order'] = $order_db_id;
    } else {
        $rzp_error = 'Payment gateway unavailable. Please try again shortly.';
    }
}

$page_title = 'Checkout — '.$product['name'];
$extra_head = '<script src="https://checkout.razorpay.com/v1/checkout.js"></script>';
require_once __DIR__ . '/_inc/header.php';
?>

<div class="checkout-wrap">
  <div class="container">
    <div style="margin-bottom:28px;">
      <a href="javascript:history.back()" style="font-size:0.85rem;color:var(--text-muted);">← Back</a>
    </div>
    <div class="checkout-grid">

      <!-- Left: user + billing details -->
      <div>
        <div class="checkout-card">
          <h2>Your Details</h2>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" value="<?= htmlspecialchars($user['name']) ?>" readonly style="opacity:0.7;" />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly style="opacity:0.7;" />
            </div>
            <div class="form-group">
              <label>Mobile</label>
              <input type="tel" id="checkout-mobile" value="<?= htmlspecialchars($user['mobile'] ?? '') ?>"
                     placeholder="9876543210" maxlength="10" />
            </div>
            <div class="form-group">
              <label>GST Number <span style="color:var(--text-dim);font-weight:400;">(optional)</span></label>
              <input type="text" id="checkout-gst" placeholder="22AAAAA0000A1Z5" maxlength="15" />
            </div>
          </div>
          <div class="form-group" style="margin-top:4px;">
            <label>Billing Address <span style="color:var(--text-dim);font-weight:400;">(optional — for GST invoice)</span></label>
            <input type="text" id="checkout-address" placeholder="Flat 101, ABC Apartments, Hyderabad 500001" />
          </div>
        </div>

        <?php if ($rzp_error): ?>
          <div class="alert alert--error" style="margin-top:20px;"><?= htmlspecialchars($rzp_error) ?></div>
        <?php endif; ?>

        <div class="alert alert--info" style="margin-top:20px;font-size:0.82rem;">
          🔒 Your payment is processed by Razorpay. We never store your card details.
          UPI, PhonePe, Google Pay, cards, net banking and EMI accepted.
        </div>
      </div>

      <!-- Right: order summary + pay -->
      <div>
        <div class="checkout-card">
          <h2>Order Summary</h2>
          <div class="product-summary">
            <div class="product-summary__icon"><?= $product['icon'] ?></div>
            <div>
              <div class="product-summary__name"><?= htmlspecialchars($product['name']) ?></div>
              <div class="product-summary__tier">
                <span class="badge <?= $tier==='pro' ? 'badge--pro' : 'badge--free' ?>">
                  <?= strtoupper($tier) ?> version
                </span>
                &nbsp;·&nbsp; <?= htmlspecialchars(product_type_label($product['type'])) ?>
              </div>
              <div class="product-summary__price"><?= fmt_price($amount) ?></div>
            </div>
          </div>

          <div class="order-line"><span>Subtotal</span><span><?= fmt_price($amount) ?></span></div>
          <div class="order-line"><span>GST (18%)</span><span style="color:var(--text-dim);">Incl.</span></div>
          <div class="order-line total"><span>Total</span><span class="val"><?= fmt_price($amount) ?></span></div>

          <div style="margin-top:20px;">
            <?php if ($rzp_order): ?>
              <button id="pay-btn" class="btn btn--gold btn--full btn--lg" onclick="openRazorpay()">
                Pay <?= fmt_price($amount) ?> Securely
              </button>
            <?php else: ?>
              <button class="btn btn--gold btn--full btn--lg" disabled>Payment Unavailable</button>
            <?php endif; ?>
          </div>

          <div class="secure-badge" style="margin-top:16px;justify-content:center;">
            🔐 Secured by Razorpay &nbsp;|&nbsp; 256-bit SSL
          </div>

          <!-- Pro features recap -->
          <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
            <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-dim);margin-bottom:10px;">
              What you get:
            </div>
            <?php foreach ($product['pro_features'] as $f): ?>
              <div style="display:flex;align-items:center;gap:7px;font-size:0.83rem;color:var(--text-muted);margin-bottom:6px;">
                <span style="color:var(--gold);">★</span> <?= htmlspecialchars($f) ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php if ($rzp_order): ?>
<script>
function openRazorpay() {
  var options = {
    key:         '<?= RZP_KEY_ID ?>',
    amount:      <?= $amount * 100 ?>,
    currency:    'INR',
    name:        'DGT Market',
    description: '<?= addslashes($product['name']) ?> — <?= strtoupper($tier) ?>',
    order_id:    '<?= $rzp_order['id'] ?>',
    image:       'https://market.dgtmint.com/_assets/img/logo.png',
    prefill: {
      name:    '<?= addslashes($user['name']) ?>',
      email:   '<?= addslashes($user['email']) ?>',
      contact: document.getElementById('checkout-mobile').value || '<?= addslashes($user['mobile'] ?? '') ?>',
    },
    theme: { color: '#1dd1a1' },
    modal: { ondismiss: function(){ console.log('Payment dismissed'); } },
    handler: function(response) {
      // POST to verify endpoint
      var form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?= SITE_URL ?>/payment-verify.php';
      var fields = {
        rzp_payment_id: response.razorpay_payment_id,
        rzp_order_id:   response.razorpay_order_id,
        rzp_signature:  response.razorpay_signature,
        product_id:     '<?= $product_id ?>',
        tier:           '<?= $tier ?>',
        _csrf:          '<?= mkt_csrf_token() ?>'
      };
      Object.keys(fields).forEach(function(k) {
        var i = document.createElement('input');
        i.type = 'hidden'; i.name = k; i.value = fields[k];
        form.appendChild(i);
      });
      document.body.appendChild(form);
      form.submit();
    }
  };
  var rzp = new Razorpay(options);
  rzp.on('payment.failed', function(resp){
    alert('Payment failed: ' + resp.error.description + '\nError code: ' + resp.error.code);
  });
  rzp.open();
}
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
