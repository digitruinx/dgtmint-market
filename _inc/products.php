<?php
// Product catalogue — single source of truth.
// When you have a DB-driven catalogue, replace get_products() with a DB query.

function get_products(): array {
    return [
        // ── AI Agents ─────────────────────────────────────────────────────
        [
            'id'        => 'ai-content-writer',
            'cat'       => 'ai',
            'name'      => 'Content Writer Agent',
            'tagline'   => 'Blogs, captions, ad copy — any niche, Hindi or English',
            'icon'      => '✍️',
            'bg'        => 'linear-gradient(135deg,#0a0010,#1a0a2e)',
            'badge'     => 'AI Agent',
            'badge_cls' => 'badge--ai',
            'is_new'    => false,
            'is_hot'    => false,
            'free_features' => ['3 content types','English output','500 words / run'],
            'pro_features'  => ['12 content types','Hindi + English','Unlimited length','SEO + meta output'],
            'price_free'    => 0,
            'price_pro'     => 999,
            'type'      => 'access',   // 'download' | 'access' | 'service'
            'demo_url'  => '',         // full-screen preview URL
        ],
        [
            'id'        => 'ai-research-agent',
            'cat'       => 'ai',
            'name'      => 'Research & Summariser Agent',
            'tagline'   => 'URLs, PDFs, topics → structured summaries + competitor analysis',
            'icon'      => '🔍',
            'bg'        => 'linear-gradient(135deg,#001018,#0a0f1e)',
            'badge'     => 'AI Agent',
            'badge_cls' => 'badge--ai',
            'is_new'    => false,
            'is_hot'    => false,
            'free_features' => ['URL input','Basic summary'],
            'pro_features'  => ['URL + PDF + topic','Deep analysis','Competitor mode','Export to Notion'],
            'price_free'    => 0,
            'price_pro'     => 1499,
            'type'      => 'access',
            'demo_url'  => '',
        ],
        [
            'id'        => 'ai-moa',
            'cat'       => 'ai',
            'name'      => 'Master Orchestrator Agent',
            'tagline'   => 'Flagship: plans, delegates & executes across all 106 Digitruinx agents',
            'icon'      => '🤖',
            'bg'        => 'linear-gradient(135deg,#0a001a,#1a0a3a)',
            'badge'     => 'AI Flagship',
            'badge_cls' => 'badge--ai',
            'is_new'    => false,
            'is_hot'    => true,
            'free_features' => ['Demo mode (5 runs/day)'],
            'pro_features'  => ['Full orchestration','All 106 agents','Priority Claude Opus','API access'],
            'price_free'    => 0,
            'price_pro'     => 4999,
            'type'      => 'access',
            'demo_url'  => '',
        ],
        [
            'id'        => 'ai-ecom-listing',
            'cat'       => 'ai',
            'name'      => 'E-commerce Product Listing Agent',
            'tagline'   => 'SEO titles, bullets & keywords for Amazon, Flipkart, your store',
            'icon'      => '🛒',
            'bg'        => 'linear-gradient(135deg,#0a0010,#1a003a)',
            'badge'     => 'AI Agent',
            'badge_cls' => 'badge--ai',
            'is_new'    => true,
            'is_hot'    => false,
            'free_features' => ['5 products / day','Title + description'],
            'pro_features'  => ['Unlimited products','Bulk CSV export','Amazon / Flipkart presets','Keyword research'],
            'price_free'    => 0,
            'price_pro'     => 1999,
            'type'      => 'access',
            'demo_url'  => '',
        ],
        [
            'id'        => 'ai-yt-bundle',
            'cat'       => 'workflow',
            'name'      => 'YouTube Automation Agent Bundle',
            'tagline'   => '10 agents: script → thumbnail → SEO → Shorts → schedule',
            'icon'      => '📹',
            'bg'        => 'linear-gradient(135deg,#001a0a,#0a0f1e)',
            'badge'     => 'AI Workflow',
            'badge_cls' => 'badge--ai',
            'is_new'    => true,
            'is_hot'    => true,
            'free_features' => ['3 agents free','English only'],
            'pro_features'  => ['All 10 agents','Hindi + English','n8n workflow included','Priority support'],
            'price_free'    => 0,
            'price_pro'     => 2499,
            'type'      => 'access',
            'demo_url'  => '',
        ],

        // ── Motion Graphics ───────────────────────────────────────────────
        [
            'id'        => 'motion-yt-intros',
            'cat'       => 'motion',
            'name'      => 'YouTube Intro Pack — 20 Templates',
            'tagline'   => 'High-energy intros for CapCut & DaVinci — tech, gaming, edu, news',
            'icon'      => '🎞️',
            'bg'        => 'linear-gradient(135deg,#1a0500,#0a0f1e)',
            'badge'     => 'Motion Pack',
            'badge_cls' => 'badge--motion',
            'is_new'    => false,
            'is_hot'    => true,
            'free_features' => ['3 intro templates','CapCut editable'],
            'pro_features'  => ['All 20 templates','No watermark','CapCut + DaVinci','Commercial licence'],
            'price_free'    => 0,
            'price_pro'     => 799,
            'type'      => 'download',
            'demo_url'  => '',
        ],
        [
            'id'        => 'motion-reels-transitions',
            'cat'       => 'motion',
            'name'      => 'Reels Transition Pack — 50 Clips',
            'tagline'   => 'Drag-and-drop transitions for Instagram Reels & YouTube Shorts',
            'icon'      => '📱',
            'bg'        => 'linear-gradient(135deg,#0a0000,#1a0a00)',
            'badge'     => 'Motion Pack',
            'badge_cls' => 'badge--motion',
            'is_new'    => false,
            'is_hot'    => false,
            'free_features' => ['10 transitions','CapCut ready'],
            'pro_features'  => ['All 50 transitions','Commercial licence','CapCut + Premiere','Tutorial included'],
            'price_free'    => 0,
            'price_pro'     => 599,
            'type'      => 'download',
            'demo_url'  => '',
        ],
        [
            'id'        => 'motion-lower-thirds',
            'cat'       => 'motion',
            'name'      => 'Lower Thirds & Title Pack — 30 Clips',
            'tagline'   => 'News-style lower thirds, name plates & section titles',
            'icon'      => '📊',
            'bg'        => 'linear-gradient(135deg,#001a18,#0a0f1e)',
            'badge'     => 'Motion Pack',
            'badge_cls' => 'badge--motion',
            'is_new'    => true,
            'is_hot'    => false,
            'free_features' => ['5 lower thirds','MP4 export'],
            'pro_features'  => ['Full 30-pack','Alpha (transparent)','After Effects source','Commercial licence'],
            'price_free'    => 0,
            'price_pro'     => 699,
            'type'      => 'download',
            'demo_url'  => '',
        ],

        // ── MCQ Bundles ───────────────────────────────────────────────────
        [
            'id'        => 'mcq-upsc',
            'cat'       => 'mcq',
            'name'      => 'UPSC Prelims — 5000 MCQ Bundle',
            'tagline'   => 'GS Paper 1 & 2 (CSAT) — PYQs + AI-generated practice sets',
            'icon'      => '🏛️',
            'bg'        => 'linear-gradient(135deg,#00001a,#001a18)',
            'badge'     => 'MCQ Bundle',
            'badge_cls' => 'badge--mcq',
            'is_new'    => false,
            'is_hot'    => true,
            'free_features' => ['200 free MCQs','PDF download'],
            'pro_features'  => ['All 5000 MCQs','Detailed solutions','Subject-wise splits','Monthly new sets'],
            'price_free'    => 0,
            'price_pro'     => 499,
            'type'      => 'download',
            'demo_url'  => '',
        ],
        [
            'id'        => 'mcq-rrb',
            'cat'       => 'mcq',
            'name'      => 'RRB NTPC — Complete MCQ Pack',
            'tagline'   => '3000 MCQs — Maths, GI, GA, English — PYQs 2015–2024',
            'icon'      => '🚂',
            'bg'        => 'linear-gradient(135deg,#0a0010,#1a0a1a)',
            'badge'     => 'MCQ Bundle',
            'badge_cls' => 'badge--mcq',
            'is_new'    => false,
            'is_hot'    => false,
            'free_features' => ['150 free MCQs','PDF format'],
            'pro_features'  => ['All 3000 MCQs','Solutions + tricks','Topic-wise sets','Mock tests'],
            'price_free'    => 0,
            'price_pro'     => 399,
            'type'      => 'download',
            'demo_url'  => '',
        ],
        [
            'id'        => 'mcq-ibps',
            'cat'       => 'mcq',
            'name'      => 'IBPS PO / Clerk — Banking MCQ Pack',
            'tagline'   => '2500 MCQs — QA, Reasoning, English, Banking Awareness',
            'icon'      => '🏦',
            'bg'        => 'linear-gradient(135deg,#001a10,#0a0f1e)',
            'badge'     => 'MCQ Bundle',
            'badge_cls' => 'badge--mcq',
            'is_new'    => true,
            'is_hot'    => false,
            'free_features' => ['100 free MCQs','PDF download'],
            'pro_features'  => ['All 2500 MCQs','Solutions + explanations','5 full mock tests','Current affairs add-on'],
            'price_free'    => 0,
            'price_pro'     => 449,
            'type'      => 'download',
            'demo_url'  => '',
        ],

        // ── LMS Courses ───────────────────────────────────────────────────
        [
            'id'        => 'lms-ai-creators',
            'cat'       => 'lms',
            'name'      => 'AI for Indian Creators — Full Course',
            'tagline'   => '12 modules — YouTube, automation, earning online — Hindi + English',
            'icon'      => '🤖',
            'bg'        => 'linear-gradient(135deg,#001018,#001a18)',
            'badge'     => 'LMS Course',
            'badge_cls' => 'badge--lms',
            'is_new'    => false,
            'is_hot'    => true,
            'free_features' => ['Module 1 free','Community access'],
            'pro_features'  => ['All 12 modules','Lifetime LMS access','Certificate of completion','Q&A + mentorship'],
            'price_free'    => 0,
            'price_pro'     => 2999,
            'type'      => 'access',
            'demo_url'  => '',
        ],
        [
            'id'        => 'lms-stock-market',
            'cat'       => 'lms',
            'name'      => 'Stock Market for Beginners',
            'tagline'   => '8 modules — mutual funds, SIPs, portfolio — Hindi with English subtitles',
            'icon'      => '💹',
            'bg'        => 'linear-gradient(135deg,#001a2e,#0a0f1e)',
            'badge'     => 'LMS Course',
            'badge_cls' => 'badge--lms',
            'is_new'    => false,
            'is_hot'    => false,
            'free_features' => ['Module 1 free','Community access'],
            'pro_features'  => ['All 8 modules','Lifetime access','Certificate','Live Q&A sessions'],
            'price_free'    => 0,
            'price_pro'     => 1999,
            'type'      => 'access',
            'demo_url'  => '',
        ],
        [
            'id'        => 'lms-build-ai-agent',
            'cat'       => 'lms',
            'name'      => 'Build Your Own AI Agent — Hands-On',
            'tagline'   => '10 modules — Claude API, Claude Code, n8n — no prior AI experience needed',
            'icon'      => '🧠',
            'bg'        => 'linear-gradient(135deg,#0a0010,#1a0a1e)',
            'badge'     => 'LMS Course',
            'badge_cls' => 'badge--lms',
            'is_new'    => true,
            'is_hot'    => false,
            'free_features' => ['2 modules free','Community access'],
            'pro_features'  => ['All 10 modules','Source code included','Mentor support','Certificate'],
            'price_free'    => 0,
            'price_pro'     => 3499,
            'type'      => 'access',
            'demo_url'  => '',
        ],

        // ── WordPress Themes & Plugins ────────────────────────────────────
        [
            'id'        => 'wp-bizpro',
            'cat'       => 'wp',
            'name'      => 'BizPro — Business WordPress Theme',
            'tagline'   => 'Full-featured theme for Indian SMEs — WooCommerce ready in Pro',
            'icon'      => '🏢',
            'bg'        => 'linear-gradient(135deg,#0d1b2e,#1a3a4a)',
            'badge'     => 'WP Theme',
            'badge_cls' => 'badge--wp',
            'is_new'    => false,
            'is_hot'    => true,
            'free_features' => ['5-page template','Mobile responsive'],
            'pro_features'  => ['20+ page templates','WooCommerce ready','Advanced customizer','Priority support'],
            'price_free'    => 0,
            'price_pro'     => 1999,
            'type'      => 'download',
            'demo_url'  => '',
        ],
        [
            'id'        => 'wp-leadform',
            'cat'       => 'wp',
            'name'      => 'LeadForm Pro — Indian Lead Capture Plugin',
            'tagline'   => 'WhatsApp alerts, Razorpay, CRM export — works on any WP theme',
            'icon'      => '🔌',
            'bg'        => 'linear-gradient(135deg,#0a0010,#1a0a1a)',
            'badge'     => 'WP Plugin',
            'badge_cls' => 'badge--wp',
            'is_new'    => false,
            'is_hot'    => false,
            'free_features' => ['Unlimited forms','Email notifications'],
            'pro_features'  => ['WhatsApp alerts','CRM + CSV export','Conditional logic','Analytics dashboard'],
            'price_free'    => 0,
            'price_pro'     => 999,
            'type'      => 'download',
            'demo_url'  => '',
        ],
        [
            'id'        => 'wp-edureach',
            'cat'       => 'wp',
            'name'      => 'EduReach — School & College WP Theme',
            'tagline'   => 'Admissions, courses, faculty, events — works with any page builder',
            'icon'      => '🎓',
            'bg'        => 'linear-gradient(135deg,#001a10,#0a0f1e)',
            'badge'     => 'WP Theme',
            'badge_cls' => 'badge--wp',
            'is_new'    => true,
            'is_hot'    => false,
            'free_features' => ['4-page layout','Courses listing'],
            'pro_features'  => ['Online admission form','Faculty directory','Events calendar','Priority support'],
            'price_free'    => 0,
            'price_pro'     => 1999,
            'type'      => 'download',
            'demo_url'  => '',
        ],

        // ── Custom PHP Sites ──────────────────────────────────────────────
        [
            'id'        => 'custom-restaurant',
            'cat'       => 'custom',
            'name'      => 'RestoBuild — Restaurant Website',
            'tagline'   => 'Menu, table booking, WhatsApp ordering — no WordPress needed',
            'icon'      => '🍽️',
            'bg'        => 'linear-gradient(135deg,#1a0500,#0a0f1e)',
            'badge'     => 'Custom PHP',
            'badge_cls' => 'badge--custom',
            'is_new'    => false,
            'is_hot'    => true,
            'free_features' => [],
            'pro_features'  => ['4-page starter','Admin panel','WhatsApp integration','Razorpay ordering'],
            'price_free'    => 2999,
            'price_pro'     => 9999,
            'type'      => 'service',
            'demo_url'  => '',
        ],
        [
            'id'        => 'custom-realestate',
            'cat'       => 'custom',
            'name'      => 'PropListr — Real Estate Platform',
            'tagline'   => 'Property listings, search, agent dashboard, map view',
            'icon'      => '🏠',
            'bg'        => 'linear-gradient(135deg,#001a2e,#0a0f1e)',
            'badge'     => 'Custom PHP',
            'badge_cls' => 'badge--custom',
            'is_new'    => false,
            'is_hot'    => false,
            'free_features' => [],
            'pro_features'  => ['Listing + search','Agent login panel','Google Maps','Lead tracking'],
            'price_free'    => 3999,
            'price_pro'     => 14999,
            'type'      => 'service',
            'demo_url'  => '',
        ],
    ];
}

function get_product(string $id): ?array {
    foreach (get_products() as $p) {
        if ($p['id'] === $id) return $p;
    }
    return null;
}

function get_mockup_html(array $p): string {
    $cat  = $p['cat'];
    $icon = $p['icon'];
    $bg   = $p['bg'];

    if (in_array($cat, ['wp','custom'])) {
        // Browser-window mockup
        $accent = $cat === 'wp' ? '#4f8ef7' : '#a855f7';
        return <<<HTML
<div class="mockup mockup--browser" style="background:$bg;">
  <div class="mockup__bar">
    <div class="mockup__dot mockup__dot--r"></div>
    <div class="mockup__dot mockup__dot--y"></div>
    <div class="mockup__dot mockup__dot--g"></div>
    <div class="mockup__url"></div>
  </div>
  <div class="mockup__body">
    <div class="mockup__hero" style="background:linear-gradient(90deg,{$accent}44,{$accent}22);display:flex;align-items:center;padding:0 8px;gap:6px;">
      <span style="font-size:1.1rem;">$icon</span>
      <div style="flex:1;">
        <div class="mockup__line mockup__line--med" style="background:{$accent}33;margin-bottom:4px;"></div>
        <div class="mockup__line mockup__line--short" style="background:{$accent}22;"></div>
      </div>
      <div style="width:40px;height:14px;background:{$accent};border-radius:3px;opacity:0.8;"></div>
    </div>
    <div class="mockup__grid2">
      <div class="mockup__card-sm"></div><div class="mockup__card-sm"></div>
      <div class="mockup__card-sm"></div><div class="mockup__card-sm"></div>
    </div>
    <div class="mockup__line mockup__line--med" style="margin-top:4px;"></div>
    <div class="mockup__line mockup__line--short"></div>
  </div>
</div>
HTML;
    }

    if (in_array($cat, ['ai','workflow','training'])) {
        return <<<HTML
<div class="mockup mockup--browser mockup--terminal" style="width:88%;height:80%;border-radius:8px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.5);">
  <div class="mockup__bar" style="background:#0a0010;">
    <div class="mockup__dot mockup__dot--r"></div>
    <div class="mockup__dot mockup__dot--y"></div>
    <div class="mockup__dot mockup__dot--g"></div>
    <span style="font-size:0.6rem;color:#a855f7;margin-left:6px;">DGT Agent</span>
  </div>
  <div style="padding:10px;flex:1;overflow:hidden;background:#0a0010;">
    <div class="term-line term-line--prompt">▶ agent.run("$icon {$p['name']}")</div>
    <div class="term-line term-line--out">Initialising Claude Opus…</div>
    <div class="term-line term-line--ok">✓ Context loaded</div>
    <div class="term-line term-line--out">Processing request…</div>
    <div class="term-line term-line--gold">★ {$p['pro_features'][0]}</div>
    <div class="term-line term-line--ok">✓ Output ready</div>
  </div>
</div>
HTML;
    }

    if ($cat === 'motion') {
        return <<<HTML
<div class="mockup mockup--video" style="width:88%;height:80%;border-radius:8px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.5);background:{$bg};">
  <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
    <span style="font-size:2.5rem;">$icon</span>
    <div style="display:flex;gap:3px;align-items:center;">
      <div style="width:3px;height:16px;background:#1dd1a1;border-radius:2px;animation:eq 0.8s ease infinite alternate;"></div>
      <div style="width:3px;height:24px;background:#1dd1a1;border-radius:2px;animation:eq 0.6s ease infinite alternate 0.1s;"></div>
      <div style="width:3px;height:12px;background:#1dd1a1;border-radius:2px;animation:eq 0.9s ease infinite alternate 0.2s;"></div>
      <div style="width:3px;height:20px;background:#1dd1a1;border-radius:2px;animation:eq 0.7s ease infinite alternate 0.3s;"></div>
      <div style="width:3px;height:14px;background:#1dd1a1;border-radius:2px;animation:eq 0.85s ease infinite alternate 0.4s;"></div>
    </div>
  </div>
  <div class="mockup__filmstrip">
    <div class="mockup__frame"></div><div class="mockup__frame"></div><div class="mockup__frame"></div>
    <div class="mockup__frame"></div><div class="mockup__frame"></div><div class="mockup__frame"></div>
    <div class="mockup__frame"></div><div class="mockup__frame"></div>
  </div>
</div>
<style>@keyframes eq{from{transform:scaleY(0.5)}to{transform:scaleY(1)}}</style>
HTML;
    }

    if ($cat === 'mcq') {
        $feats = array_slice($p['free_features'] + $p['pro_features'], 0, 3);
        return <<<HTML
<div class="mockup mockup--doc" style="width:88%;height:80%;border-radius:8px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.5);">
  <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
    <span style="font-size:1.3rem;">$icon</span>
    <span style="font-size:0.65rem;font-weight:700;color:#e2e8f0;">{$p['name']}</span>
  </div>
  <div class="doc-q">Q. Which article of the Indian Constitution deals with…</div>
  <div style="display:flex;flex-direction:column;gap:3px;margin-bottom:6px;">
    <div style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);border-radius:3px;padding:3px 7px;font-size:0.57rem;color:#86efac;">✓ A) Article 32 — Right to Constitutional Remedies</div>
    <div style="background:rgba(255,255,255,0.04);border-radius:3px;padding:3px 7px;font-size:0.57rem;color:#64748b;">B) Article 19</div>
    <div style="background:rgba(255,255,255,0.04);border-radius:3px;padding:3px 7px;font-size:0.57rem;color:#64748b;">C) Article 21</div>
  </div>
  <div style="display:flex;gap:4px;flex-wrap:wrap;">
    <span class="doc-tag">{$p['price_pro']} MCQs</span>
    <span class="doc-tag">Solutions</span>
    <span class="doc-tag">PDF</span>
  </div>
</div>
HTML;
    }

    if ($cat === 'lms') {
        return <<<HTML
<div class="mockup mockup--browser" style="background:#0d1b2e;width:88%;height:80%;border-radius:8px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.5);">
  <div class="mockup__bar">
    <div class="mockup__dot mockup__dot--r"></div>
    <div class="mockup__dot mockup__dot--y"></div>
    <div class="mockup__dot mockup__dot--g"></div>
    <span style="font-size:0.58rem;color:#ec4899;margin-left:6px;">LMS · Course Player</span>
  </div>
  <div style="padding:8px;flex:1;">
    <div style="display:flex;gap:6px;margin-bottom:6px;align-items:center;">
      <span style="font-size:1.4rem;">$icon</span>
      <div>
        <div class="mockup__line mockup__line--med" style="height:8px;background:rgba(236,72,153,0.3);margin-bottom:3px;"></div>
        <div class="mockup__line mockup__line--short" style="height:5px;"></div>
      </div>
    </div>
    <div style="background:rgba(236,72,153,0.08);border:1px solid rgba(236,72,153,0.15);border-radius:4px;padding:5px 7px;margin-bottom:5px;">
      <div style="font-size:0.55rem;color:#f9a8d4;font-weight:600;">▶ Module 1 — Introduction</div>
    </div>
    <div style="display:flex;gap:3px;align-items:center;margin-bottom:4px;">
      <div style="flex:1;height:4px;background:rgba(236,72,153,0.2);border-radius:2px;"><div style="width:35%;height:100%;background:#ec4899;border-radius:2px;"></div></div>
      <span style="font-size:0.52rem;color:#94a3b8;">35%</span>
    </div>
    <div class="mockup__line mockup__line--med" style="margin-bottom:3px;"></div>
    <div class="mockup__line mockup__line--short"></div>
  </div>
</div>
HTML;
    }

    // Fallback
    return "<div class='card__icon'>$icon</div>";
}

function fmt_price(int $paise_or_rupee): string {
    // products store in rupees directly
    return '₹' . number_format($paise_or_rupee);
}

function product_type_label(string $type): string {
    return match($type) {
        'download' => 'Instant Download',
        'access'   => 'Activate Access',
        'service'  => 'Request Build',
        default    => 'Get Product',
    };
}
