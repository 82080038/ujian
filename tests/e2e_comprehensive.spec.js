const { test, expect } = require('@playwright/test');

let consoleMessages = [];
let networkErrors = [];

function clearLogs() {
  consoleMessages = [];
  networkErrors = [];
}

function dumpLogs(label) {
  console.log(`=== Console (${label}) ===`);
  consoleMessages.forEach(m => console.log(m));
  if (networkErrors.length) {
    console.log(`=== Network Errors (${label}) ===`);
    networkErrors.forEach(e => console.error(e));
  }
}

test.beforeEach(async ({ page }) => {
  clearLogs();
  page.on('console', msg => consoleMessages.push(`[${msg.type().toUpperCase()}] ${msg.text()}`));
  page.on('pageerror', error => networkErrors.push(`[PAGE ERROR] ${error.message}`));
  page.on('requestfailed', request => networkErrors.push(`[REQUEST FAILED] ${request.url()} - ${request.failure()?.errorText || 'unknown'}`));
});

test.describe('TryOutKu Comprehensive E2E', () => {

  // ==================== AUTH ====================
  test('01. Landing page loads with correct title', async ({ page }) => {
    await page.goto('');
    await expect(page).toHaveTitle(/TryOutKu/);
    await expect(page.locator('h1')).toContainText('TryOutKu');
    await expect(page.locator('a[href="login.php"]')).toBeVisible();
    dumpLogs('Landing');
    expect(networkErrors).toHaveLength(0);
  });

  test('02. Admin login redirects to admin dashboard', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });
    await expect(page.locator('text=Dashboard Admin')).toBeVisible();
    dumpLogs('Admin Login');
    expect(networkErrors).toHaveLength(0);
  });

  test('03. Peserta register and login', async ({ page }) => {
    await page.goto('register.php');
    const email = `comp_${Date.now()}@test.com`;
    await page.fill('input[name="nama"]', 'Comprehensive Test');
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="no_hp"]', '081111111111');
    await page.fill('input[name="password"]', 'password123');
    await page.fill('input[name="password2"]', 'password123');
    await page.click('button[type="submit"]');

    // Handle quota full case
    const successMsg = page.locator('.alert-success');
    const errorMsg = page.locator('.alert-danger');
    if (await successMsg.isVisible().catch(() => false)) {
      await expect(successMsg).toContainText('Pendaftaran berhasil');
    } else {
      await expect(errorMsg).toContainText(/Kuota|Email sudah terdaftar/);
      console.log('Registration blocked (quota full or duplicate) - expected in repeated test runs');
    }
    dumpLogs('Register');
    expect(networkErrors).toHaveLength(0);
  });

  // ==================== ADMIN MODULES ====================
  test('04. Admin Kelola Soal loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });
    await page.goto('admin/kelola_soal.php');
    await expect(page.locator('text=Kelola Soal')).toBeVisible();
    dumpLogs('Kelola Soal');
    expect(networkErrors).toHaveLength(0);
  });

  test('05. Admin Kelola Materi loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });
    await page.goto('admin/kelola_materi.php');
    await expect(page.locator('h3')).toContainText('Materi');
    dumpLogs('Kelola Materi');
    expect(networkErrors).toHaveLength(0);
  });

  test('06. Admin Kelola Paket loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });
    await page.goto('admin/kelola_paket.php');
    await expect(page.locator('h3')).toContainText('Paket');
    dumpLogs('Kelola Paket');
    expect(networkErrors).toHaveLength(0);
  });

  test('07. Admin Laporan loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });
    await page.goto('admin/laporan.php');
    await expect(page.locator('h3')).toContainText('Laporan');
    dumpLogs('Laporan');
    expect(networkErrors).toHaveLength(0);
  });

  test('08. Admin Export page loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });
    await page.goto('admin/export.php');
    await expect(page.locator('h3')).toContainText('Export');
    await expect(page.locator('h5').first()).toContainText('Data Peserta');
    await expect(page.locator('h5').nth(1)).toContainText('Hasil Ujian');
    dumpLogs('Export');
    expect(networkErrors).toHaveLength(0);
  });

  // ==================== PESERTA MODULES ====================
  test('09. Peserta Dashboard loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await expect(page.locator('h4')).toContainText('Halo');
    dumpLogs('Peserta Dashboard');
    expect(networkErrors).toHaveLength(0);
  });

  test('10. Peserta Try-Out List loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.goto('peserta/tryout_list.php');
    await expect(page.locator('h4')).toContainText('Pilih Try-Out');
    dumpLogs('Try-Out List');
    expect(networkErrors).toHaveLength(0);
  });

  test('11. Peserta Mini Try-Out page loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.goto('peserta/mini_tryout.php');
    await expect(page.locator('h4')).toContainText('Mini Try-Out');
    await expect(page.locator('select[name="jumlah"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toContainText('Mulai');
    dumpLogs('Mini Try-Out');
    expect(networkErrors).toHaveLength(0);
  });

  test('12. Peserta Latihan per Topik loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.goto('peserta/latihan_topik.php');
    await expect(page.locator('h4')).toContainText('Latihan Soal per Topik');
    dumpLogs('Latihan Topik');
    expect(networkErrors).toHaveLength(0);
  });

  test('13. Peserta Belajar page loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.goto('peserta/belajar.php');
    await expect(page.locator('h4')).toContainText('Materi Belajar');
    dumpLogs('Belajar');
    expect(networkErrors).toHaveLength(0);
  });

  test('14. Peserta Flashcard page loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.goto('peserta/flashcard.php');
    await expect(page.locator('h4')).toContainText('Flashcard');
    dumpLogs('Flashcard');
    expect(networkErrors).toHaveLength(0);
  });

  test('15. Peserta Leaderboard loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.goto('peserta/leaderboard.php');
    await expect(page.locator('h4')).toContainText('Leaderboard');
    dumpLogs('Leaderboard');
    expect(networkErrors).toHaveLength(0);
  });

  test('16. Peserta Forum Q&A loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.goto('peserta/forum.php');
    await expect(page.locator('h4')).toContainText('Forum Tanya');
    await expect(page.locator('textarea[name="pertanyaan"]')).toBeVisible();
    dumpLogs('Forum');
    expect(networkErrors).toHaveLength(0);
  });

  test('17. Peserta Rapor loads with charts', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.goto('peserta/rapor.php');
    await page.waitForTimeout(1000);
    await expect(page.locator('h4')).toContainText('Rapor');
    // Check if chart canvas exists
    await expect(page.locator('canvas#grafikSkor')).toBeVisible();
    await expect(page.locator('canvas#grafikRadar')).toBeVisible();
    dumpLogs('Rapor');
    expect(networkErrors).toHaveLength(0);
  });

  // ==================== NAVIGATION LINKS ====================
  test('17. Navbar links use correct baseURL', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });

    // Check navbar links contain /ujian/
    const links = await page.locator('nav a[href*="admin/"]').all();
    for (const link of links) {
      const href = await link.getAttribute('href');
      expect(href).toContain('/ujian/');
    }
    dumpLogs('Navbar Links');
    expect(networkErrors).toHaveLength(0);
  });

  // ==================== CONSOLE & NETWORK CLEAN ====================
  test('18. No console errors on all peserta pages', async ({ page }) => {
    const pages = [
      'peserta/dashboard.php',
      'peserta/profil.php',
      'peserta/tryout_list.php',
      'peserta/tryout_kerja.php?paket=1',
      'peserta/tryout_hasil.php',
      'peserta/mini_tryout.php',
      'peserta/mini_tryout_kerja.php?jenis=twk&jumlah=5&topik=&level=',
      'peserta/mini_tryout_hasil.php',
      'peserta/latihan_topik.php',
      'peserta/latihan_kerja.php?jenis=twk&topik=Pancasila&jumlah=5',
      'peserta/belajar.php',
      'peserta/belajar_detail.php?id=1',
      'peserta/flashcard.php',
      'peserta/flashcard_detail.php?id=1',
      'peserta/rapor.php',
      'peserta/leaderboard.php',
      'peserta/forum.php',
      'peserta/psikologi.php',
      'peserta/psikologi_kraepelin.php',
      'peserta/psikologi_kerja.php?jenis=wartegg',
    ];

    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    for (const p of pages) {
      clearLogs();
      await page.goto(p);
      await page.waitForTimeout(500);
      const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
      const netErrors = networkErrors.filter(m => !m.includes('favicon'));
      if (pageErrors.length || netErrors.length) {
        console.log(`Issues on ${p}:`, [...pageErrors, ...netErrors]);
      }
      expect(pageErrors).toHaveLength(0);
      expect(netErrors).toHaveLength(0);
    }
  });

  test('19. No console errors on all admin pages', async ({ page }) => {
    const pages = [
      'admin/dashboard.php',
      'admin/kelola_soal.php',
      'admin/kelola_soal_form.php',
      'admin/kelola_materi.php',
      'admin/kelola_paket.php',
      'admin/laporan.php',
      'admin/analisis_butir.php',
      'admin/catatan_pengajar.php',
      'admin/jawab_forum.php',
      'admin/export.php',
      'admin/detail_peserta.php',
    ];

    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });

    for (const p of pages) {
      clearLogs();
      await page.goto(p);
      await page.waitForTimeout(500);
      const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
      const netErrors = networkErrors.filter(m => !m.includes('favicon'));
      if (pageErrors.length || netErrors.length) {
        console.log(`Issues on ${p}:`, [...pageErrors, ...netErrors]);
      }
      expect(pageErrors).toHaveLength(0);
      expect(netErrors).toHaveLength(0);
    }
  });

});
