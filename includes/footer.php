    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('<?= BASE_URL ?>sw.js').catch(()=>{});
    }
    </script>
    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>
