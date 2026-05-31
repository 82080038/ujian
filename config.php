<?php
session_start();

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
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Passing Grade Default
define('PG_TWK', 65);
define('PG_TIU', 80);
define('PG_TKP', 166);
define('PG_KUMULATIF', 311);

// Maks peserta
define('MAX_PESERTA', 10);
