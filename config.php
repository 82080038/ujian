<?php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? null) == 443);

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'db_tryout');

// Konfigurasi Aplikasi
define('BASE_URL', 'http://localhost/ujian/');
define('APP_NAME', 'TryOutKu - Bimbel Online');
define('APP_VERSION', '1.0.0');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting (ganti 0 saat production)
define('DEV_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', DEV_MODE ? 1 : 0);

// Passing Grade Default
define('PG_TWK', 65);
define('PG_TIU', 80);
define('PG_TKP', 166);
define('PG_KUMULATIF', 311);
define('PG_PSIKOLOGI', 100);

// Maks peserta
define('MAX_PESERTA', 10);

// Timeout ujian (dalam menit) - auto-submit jika melebihi waktu ini
define('TIMEOUT_UJIAN', 120); // 2 jam

