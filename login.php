<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    if (isAdmin()) redirect('admin/dashboard.php');
    redirect('peserta/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare('SELECT id, nama, email, password, role FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    redirect('admin/dashboard.php');
                } else {
                    redirect('peserta/dashboard.php');
                }
            } else {
                $error = 'Password salah.';
            }
        } else {
            $error = 'Email tidak terdaftar.';
        }
    }
}

$pageTitle = 'Login - ' . APP_NAME;
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-mortarboard-fill text-primary display-4"></i>
                        <h4 class="fw-bold mt-2"><?= APP_NAME ?></h4>
                        <p class="text-muted small">Masuk untuk memulai try-out</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger auto-dismiss"><i class="bi bi-exclamation-triangle"></i> <?= e($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" required autofocus placeholder="nama@email.com" value="<?= e($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" required placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold"><i class="bi bi-box-arrow-in-right"></i> Masuk</button>
                    </form>

                    <hr class="my-4">
                    <div class="text-center">
                        <small class="text-muted">Belum punya akun? <a href="register.php" class="fw-bold text-decoration-none">Daftar di sini</a></small><br>
                        <small class="text-muted"><a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke beranda</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
