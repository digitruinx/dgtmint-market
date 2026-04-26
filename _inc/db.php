<?php
define('DB_HOST',    'localhost');
define('DB_NAME',    'digitwac_dgtmint');
define('DB_USER',    'digitwac_dgtuser');
define('DB_PASS',    'DGTdb#2026!Secure');
define('DB_CHARSET', 'utf8mb4');
define('DB_CREATED', true);

function get_db(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $pdo;
}

function db_query(string $sql, array $params = []): PDOStatement {
    $st = get_db()->prepare($sql);
    $st->execute($params);
    return $st;
}
function db_row(string $sql, array $params = []): ?array {
    $r = db_query($sql, $params)->fetch();
    return $r ?: null;
}
function db_val(string $sql, array $params = []) {
    $r = db_query($sql, $params)->fetchColumn();
    return $r === false ? null : $r;
}
function db_execute(string $sql, array $params = []): int {
    return db_query($sql, $params)->rowCount();
}
function db_insert(string $sql, array $params = []): string {
    db_query($sql, $params);
    return get_db()->lastInsertId();
}
