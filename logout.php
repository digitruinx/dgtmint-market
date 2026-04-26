<?php
require_once __DIR__ . '/_inc/db.php';
require_once __DIR__ . '/_inc/config.php';
require_once __DIR__ . '/_inc/auth.php';
mkt_session_start();
mkt_logout(); // redirects to login
