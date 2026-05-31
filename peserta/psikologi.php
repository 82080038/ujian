<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$title = 'Tes Psikologi';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-5">
    <h2 class="mb-4"><i class="bi bi-emoji-smile text-success"></i> Tes Psikologi</h2>
    <p class="text-muted">Latihan tes psikologi untuk persiapan seleksi CPNS, Kedinasan, dan PPPK. Pilih jenis tes di bawah ini.</p>

    <div class="row g-4">
        <!-- Kraepelin -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="display-1 text-primary mb-3"><i class="bi bi-calculator"></i></div>
                    <h5 class="card-title">Tes Kraepelin (Pauli)</h5>
                    <p class="card-text text-muted small">Tes ketahanan kerja angka. Jumlahkan dua angka berdampingan secara berurutan. Mengukur ketelitian dan konsentrasi.</p>
                    <a href="psikologi_kraepelin.php" class="btn btn-primary w-100"><i class="bi bi-play-circle"></i> Mulai Tes</a>
                </div>
            </div>
        </div>

        <!-- Wartegg -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="display-1 text-info mb-3"><i class="bi bi-grid-3x3"></i></div>
                    <h5 class="card-title">Tes Wartegg</h5>
                    <p class="card-text text-muted small">Tes pola gambar dan logika spasial. Pilih kelanjutan gambar yang paling tepat. Mengukur daya imaginasi dan logika visual.</p>
                    <a href="psikologi_kerja.php?jenis=wartegg" class="btn btn-info w-100 text-white"><i class="bi bi-play-circle"></i> Mulai Tes</a>
                </div>
            </div>
        </div>

        <!-- EPPS -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="display-1 text-warning mb-3"><i class="bi bi-person-check"></i></div>
                    <h5 class="card-title">Tes EPPS</h5>
                    <p class="card-text text-muted small">Edwards Personal Preference Schedule. Pilih pernyataan yang paling menggambarkan diri Anda. Mengukur motivasi dan kepribadian.</p>
                    <a href="psikologi_kerja.php?jenis=epps" class="btn btn-warning w-100 text-dark"><i class="bi bi-play-circle"></i> Mulai Tes</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h5><i class="bi bi-info-circle"></i> Penjelasan Singkat</h5>
        <div class="accordion" id="accordionPsikologi">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKraepelin">
                        Apa itu Tes Kraepelin?
                    </button>
                </h2>
                <div id="collapseKraepelin" class="accordion-collapse collapse show" data-bs-parent="#accordionPsikologi">
                    <div class="accordion-body">
                        Tes Kraepelin atau Pauli adalah tes psikologi klasik yang mengukur <strong>ketahanan kerja</strong>, <strong>ketelitian</strong>, dan <strong>konsentrasi</strong> dalam waktu tertentu. Peserta menjumlahkan dua angka berdampingan secara terus-menerus. Banyak digunakan oleh kedinasan (STAN, STIS, IPDN) dan instansi yang memerlukan ketelitian tinggi.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWartegg">
                        Apa itu Tes Wartegg?
                    </button>
                </h2>
                <div id="collapseWartegg" class="accordion-collapse collapse" data-bs-parent="#accordionPsikologi">
                    <div class="accordion-body">
                        Tes Wartegg adalah tes proyeksi dan logika visual. Peserta diminta melanjutkan gambar dasar (garis, lingkaran, titik) menjadi gambar utuh. Mengukur <strong>daya imaginasi</strong>, <strong>kreativitas</strong>, dan <strong>logika spasial</strong>. Sering digunakan untuk seleksi kedinasan dan wawancara kerja.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEPPS">
                        Apa itu Tes EPPS?
                    </button>
                </h2>
                <div id="collapseEPPS" class="accordion-collapse collapse" data-bs-parent="#accordionPsikologi">
                    <div class="accordion-body">
                        Edwards Personal Preference Schedule (EPPS) mengukur <strong>kebutuhan psikologis</strong> seseorang: prestasi, afiliasi, otonomi, dominasi, dan lainnya. Terdiri dari pasangan pernyataan di mana peserta memilih yang paling dan yang least menggambarkan dirinya. Berguna untuk wawancara dan penempatan jabatan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
