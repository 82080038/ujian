<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$title = 'Tes Kraepelin';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';

// Generate random numbers for Kraepelin (single digits 1-9)
$rows = 20;
$cols = 20;
$numbers = [];
for ($r = 0; $r < $rows; $r++) {
    $row = [];
    for ($c = 0; $c < $cols; $c++) {
        $row[] = rand(1, 9);
    }
    $numbers[] = $row;
}
// Precompute correct sums for adjacent pairs (horizontal)
$solutions = [];
for ($r = 0; $r < $rows; $r++) {
    $rowSums = [];
    for ($c = 0; $c < $cols - 1; $c++) {
        $rowSums[] = $numbers[$r][$c] + $numbers[$r][$c + 1];
    }
    $solutions[] = $rowSums;
}
$jsonNumbers = json_encode($numbers);
$jsonSolutions = json_encode($solutions);
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-calculator text-primary"></i> Tes Kraepelin (Pauli)</h3>
        <div class="badge bg-dark fs-5"><i class="bi bi-clock"></i> <span id="timer">02:00</span></div>
    </div>

    <div class="alert alert-info small">
        <strong>Petunjuk:</strong> Jumlahkan dua angka berdampingan secara horizontal dari kiri ke kanan.
        Hasil penjumlahan ditulis di kolom bawahnya. Contoh: 3 + 5 = <strong>8</strong>, 5 + 7 = <strong>12</strong> (tulis <strong>2</strong> jika hanya 1 digit yang diminta, atau 12 jika 2 digit). <br>
        Dalam versi ini, jika hasil > 9, tulis <strong>angka satuan saja</strong> (contoh: 12 = <strong>2</strong>).
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body p-2" style="overflow-x:auto;">
            <table class="table table-bordered table-sm text-center mb-0" style="font-family: monospace; font-size: 1.1rem;">
                <tbody id="kraepelin-grid">
                    <?php foreach ($numbers as $r => $row): ?>
                    <tr>
                        <?php foreach ($row as $c => $num): ?>
                        <td class="bg-light fw-bold"><?= $num ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php for ($c = 0; $c < count($row) - 1; $c++): ?>
                        <td class="p-0">
                            <input type="text" data-row="<?= $r ?>" data-col="<?= $c ?>"
                                   class="form-control form-control-sm text-center border-0 kraepelin-input"
                                   style="font-family: monospace; font-size: 1.1rem; min-width: 36px;" maxlength="1">
                        </td>
                        <td class="p-0"></td>
                        <?php endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="button" id="btn-selesai" class="btn btn-success fw-bold"><i class="bi bi-check-lg"></i> Selesai & Hitung Skor</button>
        <a href="psikologi.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <!-- Result Modal -->
    <div class="modal fade" id="modalHasil" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle"></i> Hasil Tes Kraepelin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="display-4 fw-bold text-success" id="hasil-benar">0</div>
                    <p class="text-muted">Jawaban Benar</p>
                    <div class="row mt-3">
                        <div class="col-6 border-end">
                            <div class="fs-4 fw-bold text-primary" id="hasil-total">0</div>
                            <small class="text-muted">Total Soal</small>
                        </div>
                        <div class="col-6">
                            <div class="fs-4 fw-bold text-info" id="hasil-persen">0%</div>
                            <small class="text-muted">Akurasi</small>
                        </div>
                    </div>
                    <div id="hasil-status" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <a href="psikologi_kraepelin.php" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Coba Lagi</a>
                    <a href="psikologi.php" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const solutions = <?= $jsonSolutions ?>;
let timeLeft = 120; // 2 menit
const timerEl = document.getElementById('timer');
const timerInterval = setInterval(() => {
    timeLeft--;
    const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
    const s = (timeLeft % 60).toString().padStart(2, '0');
    timerEl.textContent = m + ':' + s;
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        document.getElementById('btn-selesai').click();
    }
}, 1000);

document.getElementById('btn-selesai').addEventListener('click', () => {
    clearInterval(timerInterval);
    let benar = 0, total = 0;
    document.querySelectorAll('.kraepelin-input').forEach(inp => {
        const r = parseInt(inp.dataset.row);
        const c = parseInt(inp.dataset.col);
        const val = parseInt(inp.value);
        const correct = solutions[r][c];
        total++;
        if (!isNaN(val) && val === correct) {
            benar++;
            inp.classList.add('bg-success', 'text-white');
        } else {
            inp.classList.add('bg-danger', 'text-white');
            // Show correct answer
            inp.title = 'Jawaban: ' + correct;
        }
    });
    document.getElementById('hasil-benar').textContent = benar;
    document.getElementById('hasil-total').textContent = total;
    document.getElementById('hasil-persen').textContent = total > 0 ? Math.round((benar/total)*100) + '%' : '0%';

    const statusEl = document.getElementById('hasil-status');
    if (benar >= (total * 0.7)) {
        statusEl.innerHTML = '<span class="badge bg-success fs-6"><i class="bi bi-emoji-smile"></i> Ketahanan kerja & konsentrasi baik</span>';
    } else if (benar >= (total * 0.5)) {
        statusEl.innerHTML = '<span class="badge bg-warning text-dark fs-6"><i class="bi bi-emoji-neutral"></i> Cukup, perlu latihan lebih</span>';
    } else {
        statusEl.innerHTML = '<span class="badge bg-danger fs-6"><i class="bi bi-emoji-frown"></i> Perlu latihan ketelitian & konsentrasi</span>';
    }

    new bootstrap.Modal(document.getElementById('modalHasil')).show();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
