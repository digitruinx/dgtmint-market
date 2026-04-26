<?php
require_once __DIR__ . '/_inc/db.php';
require_once __DIR__ . '/_inc/config.php';
require_once __DIR__ . '/_inc/auth.php';
require_once __DIR__ . '/_inc/products.php';
mkt_session_start();

$page_title = 'DGT Market — 100+ Digital Products';
$page_desc  = "India's digital product marketplace by Digitruinx. AI agents, WordPress themes, motion graphics, MCQ bundles, LMS courses. Start free.";

$all_products = get_products();
$cats = [
    'all'      => ['label'=>'All Products',         'icon'=>'🛍️'],
    'ai'       => ['label'=>'AI Agents',             'icon'=>'🤖'],
    'workflow' => ['label'=>'AI Workflows',          'icon'=>'⚙️'],
    'wp'       => ['label'=>'WP Themes & Plugins',  'icon'=>'🧩'],
    'custom'   => ['label'=>'Custom PHP Sites',      'icon'=>'💻'],
    'motion'   => ['label'=>'Motion Graphics',       'icon'=>'🎬'],
    'mcq'      => ['label'=>'MCQ Bundles',           'icon'=>'📝'],
    'lms'      => ['label'=>'LMS / Courses',         'icon'=>'🎓'],
    'training' => ['label'=>'AI Training',           'icon'=>'🧠'],
];

// Group products by category
$by_cat = [];
foreach ($all_products as $p) $by_cat[$p['cat']][] = $p;

require_once __DIR__ . '/_inc/header.php';
?>

<!-- Category Filter Bar -->
<div class="cat-bar">
  <div class="container">
    <div class="cat-bar__inner">
      <?php foreach ($cats as $slug => $c): ?>
        <button class="cat-pill <?= $slug==='all'?'active':'' ?>" data-cat="<?= $slug ?>">
          <?= $c['icon'] ?> <?= $c['label'] ?>
        </button>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Hero -->
<section style="padding:72px 0 56px;background:radial-gradient(ellipse 90% 60% at 50% 0%,rgba(29,209,161,0.07) 0%,transparent 65%),radial-gradient(ellipse 40% 40% at 85% 60%,rgba(168,85,247,0.05) 0%,transparent 60%),linear-gradient(180deg,var(--navy-deeper) 0%,var(--dark-bg) 100%);text-align:center;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(29,209,161,0.06) 1px,transparent 1px);background-size:44px 44px;pointer-events:none;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(29,209,161,0.08);border:1px solid rgba(29,209,161,0.2);padding:5px 16px;border-radius:999px;font-size:0.78rem;font-weight:700;color:var(--mint);margin-bottom:22px;letter-spacing:0.04em;">
      ✦ &nbsp;India's Digital Product Marketplace · By Digitruinx
    </div>
    <h1 style="font-size:clamp(2rem,5vw,3.8rem);font-weight:800;line-height:1.12;margin-bottom:18px;">
      Everything Digital.<br /><span style="color:var(--mint);">One Marketplace.</span>
    </h1>
    <p style="font-size:1rem;color:var(--text-muted);max-width:580px;margin:0 auto 32px;line-height:1.7;">
      AI agents, WordPress themes, motion graphics, MCQ bundles, LMS courses — built by Digitruinx for creators, students, and businesses across India.
    </p>
    <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;margin-bottom:48px;">
      <a href="#products" class="btn btn--primary">Browse Products</a>
      <a href="#pricing"  class="btn btn--outline">Pricing & Plans</a>
    </div>
    <div style="display:flex;justify-content:center;flex-wrap:wrap;border:1px solid var(--border);border-radius:var(--r-lg);background:rgba(13,27,46,0.7);max-width:720px;margin:0 auto;overflow:hidden;">
      <?php foreach ([['100+','Digital Products'],['8','Categories'],['Free','Starting Point'],['₹499','Pro from'],['24hr','Delivery']] as [$n,$l]): ?>
        <div style="flex:1;min-width:120px;padding:18px 14px;text-align:center;border-right:1px solid var(--border);">
          <div style="font-size:1.7rem;font-weight:800;color:var(--mint);"><?= $n ?></div>
          <div style="font-size:0.72rem;color:var(--text-dim);margin-top:3px;"><?= $l ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Products Grid -->
<section class="section" id="products">
  <div class="container">
    <?php foreach ($cats as $slug => $c):
      if ($slug === 'all') continue;
      $products_in_cat = $by_cat[$slug] ?? [];
      if (empty($products_in_cat)) continue;
    ?>
    <div class="cat-section-block" data-cat="<?= $slug ?>" style="margin-bottom:56px;">
      <div class="cat-row">
        <h3><?= $c['icon'] ?> <?= $c['label'] ?></h3>
        <a href="?cat=<?= $slug ?>">View all →</a>
      </div>
      <div class="grid">
        <?php foreach ($products_in_cat as $p):
          $is_hot = $p['is_hot'];
          $is_new = $p['is_new'];
          $is_service = $p['type'] === 'service';
        ?>
        <div class="card <?= $is_hot?'card--gold':'' ?> <?= $p['cat']==='ai'||$p['cat']==='workflow'?'card--ai':'' ?>"
             onclick="openProduct('<?= $p['id'] ?>')"
             data-id="<?= $p['id'] ?>">
          <div class="card__preview" style="background:<?= $p['bg'] ?>;">
            <?= get_mockup_html($p) ?>
            <div class="card__badges">
              <span class="badge <?= $p['badge_cls'] ?>"><?= htmlspecialchars($p['badge']) ?></span>
              <?php if ($is_hot): ?><span class="badge badge--hot">Hot</span><?php endif; ?>
              <?php if ($is_new): ?><span class="badge badge--new">New</span><?php endif; ?>
            </div>
            <?php if ($is_hot): ?><div class="card__ribbon card__ribbon--gold">POPULAR</div><?php endif; ?>
          </div>
          <div class="card__body">
            <div class="card__name"><?= htmlspecialchars($p['name']) ?></div>
            <div class="card__tagline"><?= htmlspecialchars($p['tagline']) ?></div>
            <?php if (!$is_service && !empty($p['free_features'])): ?>
            <div class="tier">
              <div class="tier__col tier__col--free">
                <div class="tier__head">Free</div>
                <?php foreach (array_slice($p['free_features'],0,3) as $f): ?>
                  <div class="tier__item"><span class="ic ic--check">✓</span> <?= htmlspecialchars($f) ?></div>
                <?php endforeach; ?>
              </div>
              <div class="tier__col tier__col--pro">
                <div class="tier__head">Pro</div>
                <?php foreach (array_slice($p['pro_features'],0,3) as $f): ?>
                  <div class="tier__item"><span class="ic ic--lock">★</span> <?= htmlspecialchars($f) ?></div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
            <div class="price-row">
              <?php if ($is_service): ?>
                <div class="price-block">
                  <span class="price-block__label">Starter</span>
                  <span class="price-block__val price-block__val--free"><?= fmt_price($p['price_free']) ?></span>
                </div>
                <div class="price-divider"></div>
                <div class="price-block">
                  <span class="price-block__label">Full Build</span>
                  <span class="price-block__val price-block__val--pro"><?= fmt_price($p['price_pro']) ?></span>
                </div>
              <?php else: ?>
                <div class="price-block">
                  <span class="price-block__label">Free</span>
                  <span class="price-block__val price-block__val--free">₹0</span>
                </div>
                <div class="price-divider"></div>
                <div class="price-block">
                  <span class="price-block__label">Pro (one-time)</span>
                  <span class="price-block__val price-block__val--pro"><?= fmt_price($p['price_pro']) ?></span>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="card__actions" onclick="event.stopPropagation()">
            <?php if ($is_service): ?>
              <a href="<?= SITE_URL ?>/checkout.php?pid=<?= $p['id'] ?>&tier=pro"
                 class="btn btn--gold">Request Build</a>
            <?php else: ?>
              <a href="<?= SITE_URL ?>/checkout.php?pid=<?= $p['id'] ?>&tier=free"
                 class="btn btn--outline">Free</a>
              <a href="<?= SITE_URL ?>/checkout.php?pid=<?= $p['id'] ?>&tier=pro"
                 class="btn btn--gold">Buy Pro <?= fmt_price($p['price_pro']) ?></a>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Pricing section -->
    <div id="pricing" style="padding-top:20px;">
      <div class="s-header s-header--center" style="margin-bottom:36px;">
        <h2>Simple Pricing</h2>
        <p>One-time payments. Lifetime licences. No subscriptions. No hidden fees.</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;max-width:900px;margin:0 auto;">
        <?php foreach ([
          ['plan--free','Free','₹0','No credit card · No expiry',false,[
            '✓ Starter on every product','✓ 100–200 free MCQs / bundle','✓ Module 1 free on all LMS courses','✓ 3 AI agent runs / day','✗ Advanced features','✗ Commercial licence'],
            'Browse Free Products', SITE_URL.'/#products', 'btn--outline'],
          ['plan--pro plan--popular','Pro — Per Product','₹499+','One-time · Lifetime licence',true,[
            '✓ Everything in Free','✓ All Pro features unlocked','✓ Commercial licence (1 domain)','✓ Full MCQ bundles + solutions','✓ Lifetime LMS access','✓ 6 months priority support'],
            'Browse Pro Products', SITE_URL.'/#products', 'btn--gold'],
          ['plan--bundle','Mega Bundle','₹9,999','One-time · Everything unlocked',false,[
            '✓ All AI agents — Pro access','✓ All MCQ bundles — all exams','✓ All motion graphic packs','✓ All LMS courses — lifetime','✓ All WP themes + plugins','✓ 1 year priority support'],
            'Get Mega Bundle', '#', 'btn--primary'],
        ] as [$cls,$name,$price,$period,$popular,$feats,$cta,$url,$btn_cls]):
        ?>
        <div style="background:var(--surface);border:1px solid <?= $popular?'var(--mint)':'var(--border)' ?>;border-radius:var(--r-lg);padding:28px 24px;position:relative;display:flex;flex-direction:column;">
          <?php if ($popular): ?>
            <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--mint);color:#000;font-size:0.65rem;font-weight:800;letter-spacing:0.07em;text-transform:uppercase;padding:3px 14px;border-radius:999px;">MOST POPULAR</div>
          <?php endif; ?>
          <div style="font-size:0.76rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-dim);margin-bottom:6px;"><?= $name ?></div>
          <div style="font-size:2rem;font-weight:800;color:<?= strpos($cls,'free')!==false?'var(--mint)':(strpos($cls,'bundle')!==false?'var(--cyan)':'var(--gold)') ?>;margin-bottom:3px;"><?= $price ?></div>
          <div style="font-size:0.74rem;color:var(--text-dim);margin-bottom:20px;"><?= $period ?></div>
          <ul style="list-style:none;flex:1;margin-bottom:20px;">
            <?php foreach ($feats as $f): ?>
              <li style="display:flex;align-items:flex-start;gap:7px;font-size:0.82rem;color:<?= str_starts_with($f,'✗')?'var(--text-faint)':'var(--text-muted)' ?>;padding:6px 0;border-bottom:1px solid var(--border);"><?= $f ?></li>
            <?php endforeach; ?>
          </ul>
          <a href="<?= $url ?>" class="btn <?= $btn_cls ?> btn--full"><?= $cta ?></a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<!-- Product Overlay (full-screen preview + info) -->
<div class="overlay" id="product-overlay" role="dialog" aria-modal="true">
  <div class="overlay__backdrop" onclick="closeProduct()"></div>
  <div class="overlay__panel">

    <!-- Left: live preview / demo -->
    <div class="overlay__preview" id="overlay-preview">
      <div class="overlay__preview-placeholder" id="overlay-placeholder">
        <span class="big-icon" id="overlay-big-icon">📦</span>
        <p id="overlay-placeholder-text">Loading preview…</p>
      </div>
      <iframe id="overlay-iframe" style="display:none;width:100%;height:100%;border:none;"></iframe>
    </div>

    <!-- Close button -->
    <button class="overlay__close" onclick="closeProduct()" aria-label="Close">✕</button>

    <!-- Right: product info panel -->
    <div class="overlay__info">
      <div class="overlay__info-header">
        <div class="overlay__badge-row" id="overlay-badges"></div>
        <div class="overlay__name" id="overlay-name"></div>
        <div class="overlay__tagline" id="overlay-tagline"></div>
      </div>

      <div class="overlay__info-body">

        <!-- Free features -->
        <div class="feat-section" id="overlay-free-section">
          <h4>✓ Included in Free</h4>
          <ul class="feat-list" id="overlay-free-list"></ul>
        </div>

        <!-- Pro features -->
        <div class="feat-section">
          <h4 style="color:var(--gold);">★ Pro Features</h4>
          <ul class="feat-list" id="overlay-pro-list"></ul>
        </div>

        <!-- Navigation arrows -->
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
          <button onclick="navProduct(-1)" class="btn btn--ghost btn--sm">← Prev</button>
          <span id="overlay-nav-label" style="font-size:0.78rem;color:var(--text-dim);"></span>
          <button onclick="navProduct(+1)" class="btn btn--ghost btn--sm">Next →</button>
        </div>

        <!-- Price + CTA -->
        <div class="overlay__price-block">
          <div class="overlay__price-grid">
            <div class="overlay__price-item">
              <span class="overlay__price-label">Free</span>
              <span class="overlay__price-val overlay__price-val--free" id="overlay-price-free">₹0</span>
            </div>
            <div class="overlay__price-divider"></div>
            <div class="overlay__price-item">
              <span class="overlay__price-label" id="overlay-pro-label">Pro (one-time)</span>
              <span class="overlay__price-val overlay__price-val--pro" id="overlay-price-pro"></span>
            </div>
          </div>
          <div class="overlay__cta-stack" id="overlay-ctas"></div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php
// Pass product catalogue to JS
$js_products = [];
foreach ($all_products as $p) {
    $js_products[] = [
        'id'       => $p['id'],
        'cat'      => $p['cat'],
        'name'     => $p['name'],
        'tagline'  => $p['tagline'],
        'icon'     => $p['icon'],
        'badge'    => $p['badge'],
        'badgeCls' => $p['badge_cls'],
        'isHot'    => $p['is_hot'],
        'isNew'    => $p['is_new'],
        'freeFeat' => $p['free_features'],
        'proFeat'  => $p['pro_features'],
        'priceFree'=> $p['price_free'],
        'pricePro' => $p['price_pro'],
        'type'     => $p['type'],
        'demoUrl'  => $p['demo_url'],
    ];
}
$checkout_url = SITE_URL . '/checkout.php';
$is_logged_in = mkt_is_logged_in() ? 'true' : 'false';
?>
<script>
const PRODUCTS     = <?= json_encode($js_products, JSON_HEX_QUOT|JSON_HEX_APOS) ?>;
const CHECKOUT_URL = '<?= $checkout_url ?>';
const IS_LOGGED_IN = <?= $is_logged_in ?>;

let currentIdx   = 0;
let filteredList = [...PRODUCTS];

// ── Category filter ────────────────────────────────────────────────
document.querySelectorAll('.cat-pill').forEach(pill => {
  pill.addEventListener('click', () => {
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    pill.classList.add('active');
    const cat = pill.dataset.cat;
    filteredList = cat === 'all' ? [...PRODUCTS] : PRODUCTS.filter(p => p.cat === cat);
    document.querySelectorAll('.cat-section-block').forEach(sec => {
      sec.style.display = (cat === 'all' || sec.dataset.cat === cat) ? '' : 'none';
    });
  });
});

// ── Product overlay ────────────────────────────────────────────────
function openProduct(id) {
  const idx = filteredList.findIndex(p => p.id === id);
  if (idx === -1) {
    // product not in current filter, add to temp list
    const p = PRODUCTS.find(p => p.id === id);
    if (!p) return;
    filteredList = [p];
    renderOverlay(0);
  } else {
    renderOverlay(idx);
  }
  document.getElementById('product-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeProduct() {
  document.getElementById('product-overlay').classList.remove('open');
  document.body.style.overflow = '';
  const iframe = document.getElementById('overlay-iframe');
  iframe.style.display = 'none';
  iframe.src = '';
}

function navProduct(dir) {
  const next = currentIdx + dir;
  if (next < 0 || next >= filteredList.length) return;
  renderOverlay(next);
}

function renderOverlay(idx) {
  currentIdx = idx;
  const p = filteredList[idx];
  if (!p) return;

  // Nav label
  document.getElementById('overlay-nav-label').textContent = (idx+1) + ' / ' + filteredList.length;

  // Badges
  const badgeRow = document.getElementById('overlay-badges');
  badgeRow.innerHTML =
    `<span class="badge ${p.badgeCls}">${p.badge}</span>` +
    (p.isHot ? '<span class="badge badge--hot">Hot</span>' : '') +
    (p.isNew ? '<span class="badge badge--new">New</span>' : '');

  // Text
  document.getElementById('overlay-name').textContent    = p.name;
  document.getElementById('overlay-tagline').textContent = p.tagline;

  // Free features
  const freeList = document.getElementById('overlay-free-list');
  const freeSection = document.getElementById('overlay-free-section');
  freeList.innerHTML = '';
  if (p.freeFeat && p.freeFeat.length) {
    freeSection.style.display = '';
    p.freeFeat.forEach(f => {
      freeList.innerHTML += `<li><span class="fi" style="color:var(--success);">✓</span> ${f}</li>`;
    });
  } else {
    freeSection.style.display = 'none';
  }

  // Pro features
  const proList = document.getElementById('overlay-pro-list');
  proList.innerHTML = '';
  (p.proFeat || []).forEach(f => {
    proList.innerHTML += `<li><span class="fi" style="color:var(--gold);">★</span> ${f}</li>`;
  });

  // Prices
  const isService = p.type === 'service';
  document.getElementById('overlay-price-free').textContent = isService
    ? (p.priceFree ? '₹' + p.priceFree.toLocaleString('en-IN') : 'N/A')
    : '₹0';
  document.getElementById('overlay-pro-label').textContent  = isService ? 'Full Build' : 'Pro (one-time)';
  document.getElementById('overlay-price-pro').textContent  = '₹' + p.pricePro.toLocaleString('en-IN');

  // CTAs
  const ctas = document.getElementById('overlay-ctas');
  if (isService) {
    ctas.innerHTML = `<a href="${CHECKOUT_URL}?pid=${p.id}&tier=pro" class="btn btn--gold btn--full">Request Build — ₹${p.pricePro.toLocaleString('en-IN')}</a>`;
  } else {
    ctas.innerHTML =
      `<a href="${CHECKOUT_URL}?pid=${p.id}&tier=pro" class="btn btn--gold btn--full">Buy Pro — ₹${p.pricePro.toLocaleString('en-IN')}</a>` +
      `<a href="${CHECKOUT_URL}?pid=${p.id}&tier=free" class="btn btn--outline btn--full">Get Free Version</a>`;
  }

  // Demo preview
  const iframe      = document.getElementById('overlay-iframe');
  const placeholder = document.getElementById('overlay-placeholder');
  const bigIcon     = document.getElementById('overlay-big-icon');
  const plText      = document.getElementById('overlay-placeholder-text');
  if (p.demoUrl) {
    iframe.src         = p.demoUrl;
    iframe.style.display = 'block';
    placeholder.style.display = 'none';
  } else {
    iframe.style.display      = 'none';
    placeholder.style.display = 'block';
    bigIcon.textContent = p.icon;
    plText.textContent  = 'Live demo coming soon. Click Buy Pro to get full access.';
  }
}

// Keyboard nav
document.addEventListener('keydown', e => {
  const overlay = document.getElementById('product-overlay');
  if (!overlay.classList.contains('open')) return;
  if (e.key === 'Escape')      closeProduct();
  if (e.key === 'ArrowRight')  navProduct(+1);
  if (e.key === 'ArrowLeft')   navProduct(-1);
});

// Global search
document.getElementById('global-search').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  if (!q) {
    document.querySelectorAll('.card').forEach(c => c.style.display = '');
    return;
  }
  document.querySelectorAll('.card').forEach(card => {
    const id   = card.dataset.id;
    const prod = PRODUCTS.find(p => p.id === id);
    if (!prod) return;
    const match = (prod.name + ' ' + prod.tagline + ' ' + prod.badge).toLowerCase().includes(q);
    card.style.display = match ? '' : 'none';
  });
});
</script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
