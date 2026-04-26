<?php
if (!defined('DB_CREATED'))  require_once __DIR__ . '/db.php';
if (!defined('MKT_CONFIG'))  require_once __DIR__ . '/config.php';

function mkt_session_start(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '.dgtmint.com',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    ini_set('session.use_strict_mode',        '1');
    ini_set('session.use_only_cookies',       '1');
    ini_set('session.gc_maxlifetime',         '7200');
    ini_set('session.sid_length',             '64');
    ini_set('session.sid_bits_per_character', '6');
    session_name('DGTMKT_SESS');
    session_start();
    if (empty($_SESSION['_init'])) {
        $_SESSION['_init'] = time();
        $_SESSION['_ip']   = $_SERVER['REMOTE_ADDR'] ?? '';
    } elseif ((time() - $_SESSION['_init']) > 1800) {
        session_regenerate_id(true);
        $_SESSION['_init'] = time();
    }
}

function mkt_is_logged_in(): bool {
    return !empty($_SESSION['mkt_uid']);
}

function mkt_current_user(): ?array {
    if (!mkt_is_logged_in()) return null;
    return db_row('SELECT id,name,email,mobile,avatar,created_at FROM mkt_users WHERE id=? AND status="active"',
        [$_SESSION['mkt_uid']]);
}

function mkt_require_login(string $redirect = ''): void {
    if (!mkt_is_logged_in()) {
        $back = $redirect ?: $_SERVER['REQUEST_URI'];
        header('Location: '.SITE_URL.'/login.php?next='.urlencode($back));
        exit;
    }
}

function mkt_login(string $email, string $password): array {
    $user = db_row('SELECT * FROM mkt_users WHERE email=? AND status="active"', [strtolower($email)]);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return ['ok' => false, 'msg' => 'Invalid email or password.'];
    }
    session_regenerate_id(true);
    $_SESSION['mkt_uid']   = $user['id'];
    $_SESSION['mkt_email'] = $user['email'];
    $_SESSION['mkt_name']  = $user['name'];
    db_execute('UPDATE mkt_users SET last_login=NOW() WHERE id=?', [$user['id']]);
    return ['ok' => true];
}

function mkt_logout(): void {
    session_unset();
    session_destroy();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time()-3600, $params['path'], $params['domain'],
              $params['secure'], $params['httponly']);
    header('Location: '.SITE_URL.'/login.php');
    exit;
}

function mkt_register(string $name, string $email, string $password, string $mobile = ''): array {
    $email = strtolower(trim($email));
    if (db_val('SELECT COUNT(*) FROM mkt_users WHERE email=?', [$email])) {
        return ['ok' => false, 'msg' => 'An account with this email already exists.'];
    }
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $id   = db_insert(
        'INSERT INTO mkt_users (name,email,mobile,password_hash,created_at) VALUES (?,?,?,?,NOW())',
        [trim($name), $email, trim($mobile), $hash]
    );
    session_regenerate_id(true);
    $_SESSION['mkt_uid']   = $id;
    $_SESSION['mkt_email'] = $email;
    $_SESSION['mkt_name']  = trim($name);
    return ['ok' => true, 'id' => $id];
}

// CSRF
function mkt_csrf_token(): string {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}
function mkt_verify_csrf(string $token): bool {
    return !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
}

// Rate limiting (session-based, light)
function mkt_rate_limit(string $key, int $max, int $window): bool {
    $k = '_rl_'.$key;
    $now = time();
    if (empty($_SESSION[$k]) || ($now - $_SESSION[$k]['t']) > $window) {
        $_SESSION[$k] = ['t' => $now, 'n' => 1];
        return true;
    }
    if ($_SESSION[$k]['n'] >= $max) return false;
    $_SESSION[$k]['n']++;
    return true;
}

// Check if user owns a product
function mkt_user_owns(int $user_id, int $product_id): bool {
    return (bool) db_val(
        'SELECT COUNT(*) FROM mkt_orders WHERE user_id=? AND product_id=? AND status="paid"',
        [$user_id, $product_id]
    );
}
