<?php
// ONE-TIME SETUP — self-deletes after running
// Access once at market.dgtmint.com/setup.php then it removes itself
define('SETUP_KEY', 'DGTmarket2026!setup');
if (($_GET['key'] ?? '') !== SETUP_KEY) {
    http_response_code(403); exit('Forbidden');
}

$host    = 'localhost';
$dbname  = 'digitwac_dgtmint';
$user    = 'digitwac_dgtuser';
$pass    = 'DGTdb#2026!Secure';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    die('<pre style="color:red">DB connection failed: '.$e->getMessage().'</pre>');
}

$sqls = [
"CREATE TABLE IF NOT EXISTS mkt_users (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120)     NOT NULL,
    email         VARCHAR(200)     NOT NULL UNIQUE,
    mobile        VARCHAR(15)      DEFAULT NULL,
    password_hash VARCHAR(255)     NOT NULL,
    avatar        VARCHAR(255)     DEFAULT NULL,
    status        ENUM('active','banned','unverified') NOT NULL DEFAULT 'active',
    last_login    DATETIME         DEFAULT NULL,
    created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

"CREATE TABLE IF NOT EXISTS mkt_orders (
    id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id           BIGINT UNSIGNED NOT NULL,
    product_id        VARCHAR(80)     NOT NULL,
    product_name      VARCHAR(255)    NOT NULL,
    tier              ENUM('free','pro') NOT NULL DEFAULT 'pro',
    amount_rupees     INT UNSIGNED    NOT NULL DEFAULT 0,
    currency          CHAR(3)         NOT NULL DEFAULT 'INR',
    rzp_order_id      VARCHAR(80)     DEFAULT NULL,
    rzp_payment_id    VARCHAR(80)     DEFAULT NULL,
    rzp_signature     VARCHAR(255)    DEFAULT NULL,
    status            ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
    download_token    VARCHAR(64)     DEFAULT NULL,
    download_expires  DATETIME        DEFAULT NULL,
    ip_address        VARCHAR(45)     DEFAULT NULL,
    created_at        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES mkt_users(id) ON DELETE CASCADE,
    INDEX idx_user    (user_id),
    INDEX idx_product (product_id),
    INDEX idx_rzp     (rzp_order_id),
    INDEX idx_status  (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

"CREATE TABLE IF NOT EXISTS mkt_access (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED NOT NULL,
    product_id  VARCHAR(80)     NOT NULL,
    order_id    BIGINT UNSIGNED NOT NULL,
    granted_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at  DATETIME        DEFAULT NULL,
    UNIQUE KEY uq_user_product (user_id, product_id),
    FOREIGN KEY (user_id)  REFERENCES mkt_users(id)  ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES mkt_orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

echo '<style>body{font-family:monospace;background:#0a0f1e;color:#e2e8f0;padding:32px;}
.ok{color:#1dd1a1;}.err{color:#ef4444;}.h{color:#b8973a;font-size:1.3rem;}</style>';
echo '<div class="h">DGT Market — Setup</div><br/>';

$all_ok = true;
foreach ($sqls as $sql) {
    preg_match('/CREATE TABLE IF NOT EXISTS (\w+)/', $sql, $m);
    $tbl = $m[1] ?? '?';
    try {
        $pdo->exec($sql);
        echo "<span class='ok'>✓ Table `$tbl` ready</span><br/>";
    } catch (Exception $e) {
        echo "<span class='err'>✗ `$tbl`: ".$e->getMessage()."</span><br/>";
        $all_ok = false;
    }
}

if ($all_ok) {
    echo "<br/><span class='ok'>✓ All tables created. Deleting setup.php...</span><br/>";
    @unlink(__FILE__);
    echo "<span class='ok'>✓ setup.php deleted.</span><br/>";
    echo "<br/><a href='https://market.dgtmint.com/' style='color:#1dd1a1;'>→ Open DGT Market</a>";
} else {
    echo "<br/><span class='err'>Some tables failed. Check errors above.</span>";
}
