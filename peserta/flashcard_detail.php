<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

if (!isset($_GET['id'])) redirect('peserta/flashcard.php');
$id = intval($_GET['id']);

$stmt = $conn->prepare('SELECT * FROM materi WHERE id = ? AND tipe = "flashcard"');
$stmt->bind_param('i', $id); $stmt->execute();
$materi = $stmt->get_result()->fetch_assoc();
if (!$materi) redirect('peserta/flashcard.php');

// Parse flashcard: each card separated by ===
$rawCards = array_map('trim', explode('===', $materi['konten_html'] ?? ''));
$cards = [];
foreach ($rawCards as $c) {
    if (empty($c)) continue;
    $parts = explode('---', $c, 2);
    $cards[] = ['front' => trim($parts[0] ?? ''), 'back' => trim($parts[1] ?? '')];
}

$pageTitle = e($materi['judul']) . ' - Flashcard';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="flashcard.php">Flashcard</a></li>
            <li class="breadcrumb-item active"><?= e($materi['judul']) ?></li>
        </ol>
    </nav>

    <div class="text-center mb-3">
        <span class="badge bg-primary" id="card-counter">1 / <?= count($cards) ?></span>
    </div>

    <div class="flashcard-container" style="perspective:1000px; max-width:400px; margin:0 auto;">
        <div class="flashcard" id="flashcard" style="width:100%; min-height:220px; cursor:pointer; position:relative; transform-style:preserve-3d; transition:transform 0.6s;">
            <div class="flashcard-front card shadow-lg d-flex align-items-center justify-content-center p-4" style="position:absolute; width:100%; height:100%; backface-visibility:hidden; background:linear-gradient(135deg, #0d6efd, #0a58ca); color:#fff; border-radius:16px;">
                <div class="text-center">
                    <i class="bi bi-question-circle fs-1 mb-2"></i>
                    <p class="fs-5 fw-bold" id="card-front"><?= e($cards[0]['front'] ?? '') ?></p>
                    <small class="opacity-75">Ketuk untuk lihat jawaban</small>
                </div>
            </div>
            <div class="flashcard-back card shadow-lg d-flex align-items-center justify-content-center p-4" style="position:absolute; width:100%; height:100%; backface-visibility:hidden; transform:rotateY(180deg); background:linear-gradient(135deg, #198754, #146c43); color:#fff; border-radius:16px;">
                <div class="text-center">
                    <i class="bi bi-check-circle fs-1 mb-2"></i>
                    <p class="fs-5 fw-bold" id="card-back"><?= e($cards[0]['back'] ?? '') ?></p>
                    <small class="opacity-75">Ketuk untuk kembali</small>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4" style="max-width:400px; margin:1rem auto 0;">
        <button type="button" class="btn btn-secondary" id="btn-prev" disabled><i class="bi bi-arrow-left"></i> Sebelumnya</button>
        <button type="button" class="btn btn-primary" id="btn-next">Selanjutnya <i class="bi bi-arrow-right"></i></button>
    </div>
</div>

<script>
const cards = <?= json_encode($cards) ?>;
let current = 0;
let flipped = false;

function showCard(idx) {
    current = idx;
    $('#card-front').text(cards[idx].front);
    $('#card-back').text(cards[idx].back);
    $('#card-counter').text((idx+1) + ' / ' + cards.length);
    $('#btn-prev').prop('disabled', idx === 0);
    $('#btn-next').prop('disabled', idx === cards.length - 1);
    flipped = false;
    $('#flashcard').css('transform', 'rotateY(0deg)');
}

$('#flashcard').on('click', function() {
    flipped = !flipped;
    $(this).css('transform', flipped ? 'rotateY(180deg)' : 'rotateY(0deg)');
});

$('#btn-next').on('click', function() {
    if (current < cards.length - 1) showCard(current + 1);
});
$('#btn-prev').on('click', function() {
    if (current > 0) showCard(current - 1);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
