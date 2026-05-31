<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    redirect('peserta/dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $no_hp = trim($_POST['no_hp'] ?? '');
    $target_ujian = $_POST['target_ujian'] ?? 'cpns';

    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $password2) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        // Cek jumlah peserta
        $cek = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'peserta'")->fetch_assoc();
        if ($cek['total'] >= MAX_PESERTA) {
            $error = 'Kuota peserta sudah penuh. Hubungi admin.';
        } else {
            // Cek email unik
            $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'Email sudah terdaftar. Silakan login.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt2 = $conn->prepare("INSERT INTO users (nama, email, password, no_hp, role, target_ujian) VALUES (?, ?, ?, ?, 'peserta', ?)");
                $stmt2->bind_param('sssss', $nama, $email, $hash, $no_hp, $target_ujian);
                if ($stmt2->execute()) {
                    $success = 'Pendaftaran berhasil! Silakan login.';
                } else {
                    $error = 'Terjadi kesalahan. Coba lagi.';
                }
            }
        }
    }
}

$pageTitle = 'Daftar - ' . APP_NAME;
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus text-success display-4"></i>
                        <h4 class="fw-bold mt-2">Daftar Akun</h4>
                        <p class="text-muted small">Bergabung untuk mengikuti try-out</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger auto-dismiss"><i class="bi bi-exclamation-triangle"></i> <?= e($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success auto-dismiss"><i class="bi bi-check-circle"></i> <?= e($success) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required value="<?= e($_POST['nama'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" required value="<?= e($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="tel" name="no_hp" class="form-control" value="<?= e($_POST['no_hp'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Target Ujian</label>
                            <select name="target_ujian" class="form-select">
                                <option value="cpns" <?= ($_POST['target_ujian'] ?? '') === 'cpns' ? 'selected' : '' ?>>CPNS</option>
                                <option value="stan" <?= ($_POST['target_ujian'] ?? '') === 'stan' ? 'selected' : '' ?>>PKN STAN</option>
                                <option value="stis" <?= ($_POST['target_ujian'] ?? '') === 'stis' ? 'selected' : '' ?>>Polstat STIS</option>
                                <option value="ipdn" <?= ($_POST['target_ujian'] ?? '') === 'ipdn' ? 'selected' : '' ?>>IPDN</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" name="password2" class="form-control" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-person-check"></i> Daftar</button>
                    </form>

                    <hr class="my-4">
                    <div class="text-center">
                        <small class="text-muted">Sudah punya akun? <a href="login.php" class="fw-bold text-decoration-none">Masuk</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
