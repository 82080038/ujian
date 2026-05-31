    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
    <?php if (!defined('DEV_MODE') || !DEV_MODE): ?>
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('<?= BASE_URL ?>sw.js').catch(()=>{});
    }
    </script>
    <?php else: ?>
    <!-- DEV_MODE: Service Worker dinonaktifkan untuk memudahkan testing -->
    <script>
    // Unregister existing service worker saat development
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(regs => regs.forEach(r => r.unregister()));
    }
    </script>
    <?php endif; ?>
    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>
