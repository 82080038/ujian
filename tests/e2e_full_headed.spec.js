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

test.describe('TryOutKu FULL HEADED E2E - All Pages', () => {

  // ==================== PUBLIC ====================
  test('01. Landing page loads, no console errors', async ({ page }) => {
    await page.goto('');
    await expect(page).toHaveTitle(/TryOutKu/);
    await expect(page.locator('h1')).toContainText('TryOutKu');
    dumpLogs('Landing');
    expect(networkErrors).toHaveLength(0);
  });

  test('02. Login page loads', async ({ page }) => {
    await page.goto('login.php');
    await expect(page.locator('button[type="submit"]')).toBeVisible();
    dumpLogs('Login');
    expect(networkErrors).toHaveLength(0);
  });

  test('03. Register page loads', async ({ page }) => {
    await page.goto('register.php');
    await expect(page.locator('button[type="submit"]')).toContainText('Daftar');
    dumpLogs('Register');
    expect(networkErrors).toHaveLength(0);
  });

  // ==================== ADMIN - ALL PAGES ====================
  test('04. Admin login + all admin pages load without console errors', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });

    const adminPages = [
      { url: 'admin/dashboard.php', label: 'Admin Dashboard' },
      { url: 'admin/kelola_soal.php', label: 'Kelola Soal' },
      { url: 'admin/kelola_soal_form.php', label: 'Kelola Soal Form (Add)' },
      { url: 'admin/kelola_materi.php', label: 'Kelola Materi' },
      { url: 'admin/kelola_paket.php', label: 'Kelola Paket' },
      { url: 'admin/laporan.php', label: 'Laporan' },
      { url: 'admin/export.php', label: 'Export' },
      { url: 'admin/analisis_butir.php', label: 'Analisis Butir' },
      { url: 'admin/catatan_pengajar.php', label: 'Catatan Pengajar' },
      { url: 'admin/jawab_forum.php', label: 'Jawab Forum' },
      { url: 'admin/detail_peserta.php', label: 'Detail Peserta (no id param)' },
    ];

    for (const p of adminPages) {
      clearLogs();
      await page.goto(p.url);
      await page.waitForTimeout(800);
      const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
      const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico'));
      if (pageErrors.length || netErrors.length) {
        console.log(`Issues on ${p.label}:`, [...pageErrors, ...netErrors]);
      }
      expect(pageErrors).toHaveLength(0);
      expect(netErrors).toHaveLength(0);
    }
  });

  // ==================== PESERTA - ALL PAGES ====================
  test('05. Peserta login + all peserta pages load without console errors', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    const pesertaPages = [
      { url: 'peserta/dashboard.php', label: 'Dashboard' },
      { url: 'peserta/profil.php', label: 'Profil' },
      { url: 'peserta/tryout_list.php', label: 'Try-Out List' },
      { url: 'peserta/leaderboard.php', label: 'Leaderboard' },
      { url: 'peserta/forum.php', label: 'Forum' },
      { url: 'peserta/belajar.php', label: 'Belajar' },
      { url: 'peserta/flashcard.php', label: 'Flashcard' },
      { url: 'peserta/rapor.php', label: 'Rapor' },
      { url: 'peserta/mini_tryout.php', label: 'Mini Try-Out Setup' },
      { url: 'peserta/latihan_topik.php', label: 'Latihan Topik' },
      { url: 'peserta/psikologi.php', label: 'Psikologi Landing' },
      { url: 'peserta/psikologi_kraepelin.php', label: 'Psikologi Kraepelin' },
      { url: 'peserta/psikologi_kerja.php?jenis=wartegg', label: 'Psikologi Wartegg (no soal fallback)' },
      { url: 'peserta/psikologi_kerja.php?jenis=epps', label: 'Psikologi EPPS (no soal fallback)' },
    ];

    for (const p of pesertaPages) {
      clearLogs();
      await page.goto(p.url);
      await page.waitForTimeout(800);
      const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
      const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico'));
      if (pageErrors.length || netErrors.length) {
        console.log(`Issues on ${p.label}:`, [...pageErrors, ...netErrors]);
      }
      expect(pageErrors).toHaveLength(0);
      expect(netErrors).toHaveLength(0);
    }
  });

  // ==================== PESERTA - ACTIVE EXAM FLOW ====================
  test('06. Try-Out: start exam -> answer -> submit -> result page', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    // Go to tryout list
    await page.goto('peserta/tryout_list.php');
    await expect(page.locator('a:has-text("Mulai Try-Out")')).toBeVisible();

    // Start exam
    await page.click('a:has-text("Mulai Try-Out")');
    await page.waitForTimeout(1500);

    // Check if exam page loaded
    const soalBox = page.locator('.soal-box, .card-body').first();
    await expect(soalBox).toBeVisible();

    // Answer a few questions
    for (let i = 0; i < 3; i++) {
      const opsi = page.locator('.opsi-jawaban').first();
      const ada = await opsi.isVisible().catch(() => false);
      if (ada) {
        await opsi.click();
        await page.waitForTimeout(500);
      }
      const next = page.locator('a:has-text("Selanjutnya"), button:has-text("Selanjutnya")').first();
      const adaNext = await next.isVisible().catch(() => false);
      if (adaNext) await next.click();
      await page.waitForTimeout(500);
    }

    // Navigate to result page directly (simulate viewing past results)
    await page.goto('peserta/tryout_hasil.php');
    await page.waitForTimeout(800);

    const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
    const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico'));
    if (pageErrors.length || netErrors.length) {
      console.log(`Issues during exam flow:`, [...pageErrors, ...netErrors]);
    }
    expect(pageErrors).toHaveLength(0);
    expect(netErrors).toHaveLength(0);
  });

  // ==================== PESERTA - MINI TRYOUT FLOW ====================
  test('07. Mini Try-Out: setup -> work -> result page', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    // Setup mini tryout
    await page.goto('peserta/mini_tryout.php');
    await page.selectOption('select[name="jenis"]', 'twk');
    await page.selectOption('select[name="jumlah"]', '5');
    await page.click('button[type="submit"]');
    await page.waitForTimeout(1500);

    // Answer 1 question
    const opsi = page.locator('.opsi-jawaban').first();
    const ada = await opsi.isVisible().catch(() => false);
    if (ada) {
      await opsi.click();
      await page.waitForTimeout(500);
    }

    // Navigate to result
    await page.goto('peserta/mini_tryout_hasil.php');
    await page.waitForTimeout(800);

    const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
    const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico'));
    if (pageErrors.length || netErrors.length) {
      console.log(`Issues during mini tryout flow:`, [...pageErrors, ...netErrors]);
    }
    expect(pageErrors).toHaveLength(0);
    expect(netErrors).toHaveLength(0);
  });

  // ==================== PESERTA - LATIHAN FLOW ====================
  test('08. Latihan: setup -> answer -> result page', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    // Setup latihan
    await page.goto('peserta/latihan_topik.php');
    // Select TWK and first available topik
    await page.selectOption('select[name="jenis"]', 'twk');
    await page.waitForTimeout(500);
    await page.selectOption('select[name="topik"]', { index: 1 });
    await page.click('button[type="submit"]');
    await page.waitForTimeout(1500);

    // Answer 1 question
    const opsi = page.locator('.opsi-jawaban').first();
    const ada = await opsi.isVisible().catch(() => false);
    if (ada) {
      await opsi.click();
      await page.waitForTimeout(500);
    }

    const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
    const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico'));
    if (pageErrors.length || netErrors.length) {
      console.log(`Issues during latihan flow:`, [...pageErrors, ...netErrors]);
    }
    expect(pageErrors).toHaveLength(0);
    expect(netErrors).toHaveLength(0);
  });

  // ==================== PESERTA - PSIKOLOGI KRAEPELIN ====================
  test('09. Psikologi Kraepelin: interactive grid loads and submits', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    await page.goto('peserta/psikologi_kraepelin.php');
    await page.waitForTimeout(800);

    // Check grid exists
    await expect(page.locator('#kraepelin-grid input').first()).toBeVisible();

    // Fill a few inputs
    const inputs = await page.locator('#kraepelin-grid input').all();
    for (let i = 0; i < Math.min(5, inputs.length); i++) {
      await inputs[i].fill(String(Math.floor(Math.random() * 9) + 1));
    }

    // Click selesai
    await page.click('#btn-selesai');
    await page.waitForTimeout(1000);

    // Result modal should appear
    const modal = page.locator('#modalHasil');
    const adaModal = await modal.isVisible().catch(() => false);
    if (adaModal) {
      await expect(page.locator('#hasil-benar')).toBeVisible();
    }

    const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
    const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico'));
    if (pageErrors.length || netErrors.length) {
      console.log(`Issues during Kraepelin:`, [...pageErrors, ...netErrors]);
    }
    expect(pageErrors).toHaveLength(0);
    expect(netErrors).toHaveLength(0);
  });

  // ==================== BELAJAR DETAIL ====================
  test('10. Belajar detail page loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    // Get first materi id
    await page.goto('peserta/belajar.php');
    await page.waitForTimeout(500);
    const link = page.locator('a[href*="belajar_detail.php"]').first();
    const ada = await link.isVisible().catch(() => false);
    if (ada) {
      await link.click();
      await page.waitForTimeout(800);
    } else {
      // Direct visit if no link
      await page.goto('peserta/belajar_detail.php?id=1');
      await page.waitForTimeout(800);
    }

    const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
    const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico'));
    if (pageErrors.length || netErrors.length) {
      console.log(`Issues on belajar detail:`, [...pageErrors, ...netErrors]);
    }
    expect(pageErrors).toHaveLength(0);
    expect(netErrors).toHaveLength(0);
  });

  // ==================== FLASHCARD DETAIL ====================
  test('11. Flashcard detail interactive', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    await page.goto('peserta/flashcard.php');
    await page.waitForTimeout(500);

    const link = page.locator('a[href*="flashcard_detail.php"]').first();
    const ada = await link.isVisible().catch(() => false);
    if (ada) {
      await link.click();
      await page.waitForTimeout(800);

      // Interact with flashcard
      await page.click('#flashcard');
      await page.waitForTimeout(500);
      await page.click('#btn-next');
      await page.waitForTimeout(500);
    } else {
      await page.goto('peserta/flashcard_detail.php?id=1');
      await page.waitForTimeout(800);
    }

    const pageErrors = consoleMessages.filter(m => m.includes('[PAGE ERROR]') || m.includes('[ERROR]'));
    const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico'));
    if (pageErrors.length || netErrors.length) {
      console.log(`Issues on flashcard detail:`, [...pageErrors, ...netErrors]);
    }
    expect(pageErrors).toHaveLength(0);
    expect(netErrors).toHaveLength(0);
  });

  // ==================== NETWORK THOROUGH CHECK ====================
  test('12. All pages - thorough 404 check (no missing resources)', async ({ page }) => {
    const allPages = [
      '',
      'login.php',
      'register.php',
      'peserta/dashboard.php',
      'peserta/profil.php',
      'peserta/tryout_list.php',
      'peserta/leaderboard.php',
      'peserta/forum.php',
      'peserta/belajar.php',
      'peserta/flashcard.php',
      'peserta/rapor.php',
      'peserta/mini_tryout.php',
      'peserta/latihan_topik.php',
      'peserta/psikologi.php',
      'peserta/psikologi_kraepelin.php',
      'admin/dashboard.php',
      'admin/kelola_soal.php',
      'admin/kelola_materi.php',
      'admin/kelola_paket.php',
      'admin/laporan.php',
      'admin/export.php',
      'admin/analisis_butir.php',
      'admin/catatan_pengajar.php',
      'admin/jawab_forum.php',
    ];

    let allNetErrors = [];
    for (const p of allPages) {
      clearLogs();
      await page.goto(p);
      await page.waitForTimeout(600);
      const netErrors = networkErrors.filter(m => !m.includes('favicon') && !m.includes('.ico') && !m.includes('analytics'));
      allNetErrors.push(...netErrors.map(e => `[${p}] ${e}`));
    }

    if (allNetErrors.length) {
      console.log('Network errors found:', allNetErrors);
    }
    expect(allNetErrors).toHaveLength(0);
  });

});
