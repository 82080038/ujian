<?php
require_once __DIR__ . '/db.php';

function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}

function flash($key, $message = null) {
    if ($message !== null) {
        $_SESSION['flash_' . $key] = $message;
    } elseif (isset($_SESSION['flash_' . $key])) {
        $msg = $_SESSION['flash_' . $key];
        unset($_SESSION['flash_' . $key]);
        return $msg;
    }
    return null;
}

function getFlash($key) {
    return flash($key);
}

function hasFlash($key) {
    return isset($_SESSION['flash_' . $key]);
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function formatTanggal($datetime) {
    return date('d M Y, H:i', strtotime($datetime));
}

function formatWaktu($detik) {
    $m = floor($detik / 60);
    $s = $detik % 60;
    return sprintf('%02d:%02d', $m, $s);
}

function generateCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isPeserta() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'peserta';
}

function requireAuth() {
    if (!isLoggedIn()) {
        flash('error', 'Silakan login terlebih dahulu.');
        redirect('login.php');
    }
}

function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        flash('error', 'Akses ditolak.');
        redirect('peserta/dashboard.php');
    }
}

function requirePeserta() {
    requireAuth();
    if (!isPeserta()) {
        flash('error', 'Akses ditolak.');
        redirect('admin/dashboard.php');
    }
}

function getUserById($conn, $id) {
    $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}

function getKategoriUjian($conn) {
    $res = $conn->query('SELECT * FROM kategori_ujian ORDER BY nama');
    return $res->fetch_all(MYSQLI_ASSOC);
}

function hitungSkorTIU_TWK($jawaban_benar) {
    return $jawaban_benar * 5;
}

function hitungSkorTKP($conn, $hasil_ujian_id) {
    $stmt = $conn->prepare('SELECT SUM(dj.nilai_diperoleh) as total FROM detail_jawaban dj WHERE dj.hasil_ujian_id = ?');
    $stmt->bind_param('i', $hasil_ujian_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res['total'] ?? 0;
}

function getStatusLulus($skor_twk, $skor_tiu, $skor_tkp, $kategori) {
    if ($skor_twk < PG_TWK || $skor_tiu < PG_TIU || $skor_tkp < PG_TKP) {
        return 'gugur';
    }
    $kumulatif = $skor_twk + $skor_tiu + $skor_tkp;
    if ($kumulatif >= PG_KUMULATIF) {
        return 'lulus';
    }
    return 'gugur';
}

function getTingkatKesulitanLabel($nilai) {
    if ($nilai >= 0.81) return ['Sangat Mudah', 'success'];
    if ($nilai >= 0.61) return ['Mudah', 'info'];
    if ($nilai >= 0.41) return ['Sedang', 'warning'];
    if ($nilai >= 0.21) return ['Sulit', 'danger'];
    return ['Sangat Sulit', 'dark'];
}

function getJenisTesLabel($jenis) {
    $map = ['twk' => 'TWK', 'tiu' => 'TIU', 'tkp' => 'TKP'];
    return $map[$jenis] ?? strtoupper($jenis);
}

function getJenisTesColor($jenis) {
    $map = ['twk' => 'primary', 'tiu' => 'warning', 'tkp' => 'success'];
    return $map[$jenis] ?? 'secondary';
}

function escapeLike($str) {
    return str_replace(['%', '_'], ['\%', '\_'], $str);
}
