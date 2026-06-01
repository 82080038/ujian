<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$user_id = $_SESSION['user_id'];
$stmtUser = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmtUser->bind_param('i', $user_id);
$stmtUser->execute();
$user = $stmtUser->get_result()->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = '';
    if (!verifyCSRF($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid. Muat ulang halaman dan coba lagi.';
    } else {
        $action = $_POST['action'] ?? '';
    }

    if ($action === 'update_profil') {
        $nama = trim($_POST['nama'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $target_ujian = $_POST['target_ujian'] ?? 'cpns';

        if (empty($nama)) {
            $error = 'Nama tidak boleh kosong.';
        } else {
            $stmt = $conn->prepare('UPDATE users SET nama = ?, no_hp = ?, target_ujian = ? WHERE id = ?');
            $stmt->bind_param('sssi', $nama, $no_hp, $target_ujian, $user_id);
            if ($stmt->execute()) {
                $success = 'Profil berhasil diperbarui.';
                $_SESSION['nama'] = $nama;
                $stmtRefresh = $conn->prepare('SELECT * FROM users WHERE id = ?');
                $stmtRefresh->bind_param('i', $user_id);
                $stmtRefresh->execute();
                $user = $stmtRefresh->get_result()->fetch_assoc();
            } else {
                $error = 'Gagal memperbarui profil.';
            }
        }
    } elseif ($action === 'update_password') {
        $old_pass = $_POST['old_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $new_pass2 = $_POST['new_password2'] ?? '';

        if (!password_verify($old_pass, $user['password'])) {
            $error = 'Password lama salah.';
        } elseif (strlen($new_pass) < 6) {
            $error = 'Password baru minimal 6 karakter.';
        } elseif ($new_pass !== $new_pass2) {
            $error = 'Konfirmasi password tidak cocok.';
        } else {
            $hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->bind_param('si', $hash, $user_id);
            $stmt->execute();
            $success = 'Password berhasil diubah.';
        }
    }
}

$pageTitle = 'Profil - ' . APP_NAME;
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-person-circle"></i> Profil Saya</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger auto-dismiss"><i class="bi bi-exclamation-triangle"></i> <?= e($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success auto-dismiss"><i class="bi bi-check-circle"></i> <?= e($success) ?></div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white fw-bold"><i class="bi bi-person"></i> Data Profil</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_profil">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRF() ?>">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= e($user['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="tel" name="no_hp" class="form-control" value="<?= e($user['no_hp']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Target Ujian</label>
                            <select name="target_ujian" class="form-select">
                                <?php foreach (['cpns'=>'CPNS','stan'=>'STAN','stis'=>'STIS','ipdn'=>'IPDN'] as $k=>$v): ?>
                                    <option value="<?= $k ?>" <?= $user['target_ujian'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Profil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white fw-bold"><i class="bi bi-key"></i> Ganti Password</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_password">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRF() ?>">
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password2" class="form-control" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-key"></i> Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
