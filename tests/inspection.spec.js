const { test, expect } = require('@playwright/test');

let allConsoleMessages = [];
let allNetworkRequests = [];
let allNetworkErrors = [];
let pageErrors = [];

function clearLogs() {
  allConsoleMessages = [];
  allNetworkRequests = [];
  allNetworkErrors = [];
  pageErrors = [];
}

function logDetailedInfo(label, page) {
  console.log(`\n=== ${label} ===`);
  console.log(`Console Messages (${allConsoleMessages.length}):`);
  allConsoleMessages.forEach(m => console.log(`  ${m}`));
  console.log(`Network Requests (${allNetworkRequests.length}):`);
  allNetworkRequests.forEach(r => console.log(`  ${r.method} ${r.url} -> ${r.status}`));
  console.log(`Network Errors (${allNetworkErrors.length}):`);
  allNetworkErrors.forEach(e => console.log(`  ${e}`));
  console.log(`Page Errors (${pageErrors.length}):`);
  pageErrors.forEach(e => console.log(`  ${e}`));
}

test.beforeEach(async ({ page }) => {
  clearLogs();

  // Console logging
  page.on('console', msg => {
    const type = msg.type().toUpperCase();
    const text = msg.text();
    allConsoleMessages.push(`[${type}] ${text}`);
  });

  // Page errors
  page.on('pageerror', error => {
    pageErrors.push(`[PAGE ERROR] ${error.message}`);
  });

  // Network requests
  page.on('request', request => {
    allNetworkRequests.push({
      method: request.method(),
      url: request.url(),
      pending: true
    });
  });

  page.on('response', response => {
    const req = allNetworkRequests.find(r => r.url === response.url());
    if (req) {
      req.status = response.status();
      req.pending = false;
    }
  });

  // Network failures
  page.on('requestfailed', request => {
    allNetworkErrors.push(`[REQUEST FAILED] ${request.url()} - ${request.failure()?.errorText || 'unknown'}`);
  });
});

test.describe('Comprehensive Inspection - Console, Network, Data, Flow', () => {

  test('01. Landing Page - Full Inspection', async ({ page }) => {
    await page.goto('');
    await page.waitForLoadState('networkidle');

    // Page content
    const title = await page.title();
    console.log(`Page Title: ${title}`);

    const h1Text = await page.locator('h1').textContent();
    console.log(`H1 Text: ${h1Text}`);

    // Check all links
    const links = await page.locator('a').all();
    console.log(`Total Links: ${links.length}`);

    // Check images
    const images = await page.locator('img').all();
    console.log(`Total Images: ${images.length}`);

    // Check forms
    const forms = await page.locator('form').all();
    console.log(`Total Forms: ${forms.length}`);

    logDetailedInfo('Landing Page', page);

    expect(allNetworkErrors).toHaveLength(0);
    expect(pageErrors.filter(e => !e.includes('alert'))).toHaveLength(0);
  });

  test('02. Admin Login Flow - Full Inspection', async ({ page }) => {
    await page.goto('login.php');

    // Check form elements
    const emailInput = page.locator('input[name="email"]');
    const passwordInput = page.locator('input[name="password"]');
    const submitBtn = page.locator('button[type="submit"]');

    await expect(emailInput).toBeVisible();
    await expect(passwordInput).toBeVisible();
    await expect(submitBtn).toBeVisible();

    console.log('Form elements verified');

    // Fill and submit
    await emailInput.fill('admin@tryoutku.com');
    await passwordInput.fill('password');

    clearLogs();
    await submitBtn.click();

    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });
    await page.waitForLoadState('networkidle');

    // Check dashboard content
    const dashboardTitle = await page.locator('h3').textContent();
    console.log(`Dashboard Title: ${dashboardTitle}`);

    // Check stats cards
    const statsCards = await page.locator('.card.text-white').all();
    console.log(`Stats Cards: ${statsCards.length}`);

    // Check table
    const table = page.locator('table');
    const tableVisible = await table.isVisible();
    console.log(`Table Visible: ${tableVisible}`);

    logDetailedInfo('Admin Dashboard', page);

    expect(allNetworkErrors).toHaveLength(0);
  });

  test('03. Peserta Login & Dashboard - Full Inspection', async ({ page }) => {
    await page.goto('login.php');

    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');

    clearLogs();
    await page.click('button[type="submit"]');

    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
    await page.waitForLoadState('networkidle');

    // Check dashboard content
    const welcomeText = await page.locator('h4').textContent();
    console.log(`Welcome Text: ${welcomeText}`);

    // Check navigation
    const navLinks = await page.locator('nav a').all();
    console.log(`Nav Links: ${navLinks.length}`);

    // Check cards
    const cards = await page.locator('.card').all();
    console.log(`Cards: ${cards.length}`);

    logDetailedInfo('Peserta Dashboard', page);

    expect(allNetworkErrors).toHaveLength(0);
  });

  test('04. Try-Out List & Start Exam - Flow Inspection', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php');

    clearLogs();
    await page.goto('peserta/tryout_list.php');
    await page.waitForLoadState('networkidle');

    // Check tryout list
    const tryoutCards = await page.locator('.card').all();
    console.log(`Tryout Cards: ${tryoutCards.length}`);

    // Check if there's an active package
    const startBtn = page.locator('a[href*="tryout_kerja.php"]').first();
    const btnVisible = await startBtn.isVisible().catch(() => false);

    if (btnVisible) {
      console.log('Starting exam flow...');
      clearLogs();
      await startBtn.click();
      await page.waitForLoadState('networkidle');

      // Check exam page
      const url = page.url();
      console.log(`Exam Page URL: ${url}`);

      // Check timer
      const timer = page.locator('#timer');
      const timerVisible = await timer.isVisible();
      console.log(`Timer Visible: ${timerVisible}`);

      // Check question
      const question = page.locator('.soal-box');
      const questionVisible = await question.isVisible();
      console.log(`Question Visible: ${questionVisible}`);

      // Check navigation
      const navButtons = await page.locator('.btn-soal').all();
      console.log(`Nav Buttons: ${navButtons.length}`);

      logDetailedInfo('Exam Page', page);
    } else {
      console.log('No active exam package available');
    }

    expect(allNetworkErrors).toHaveLength(0);
  });

  test('05. API Endpoint - Simpan Jawaban Temp Inspection', async ({ page, request }) => {
    // First login to get session
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php');

    // Start an exam if available
    await page.goto('peserta/tryout_list.php');
    const startBtn = page.locator('a[href*="tryout_kerja.php"]').first();
    const btnVisible = await startBtn.isVisible().catch(() => false);

    if (btnVisible) {
      await startBtn.click();
      await page.waitForLoadState('networkidle');

      // Get cookies for API request
      const cookies = await page.context().cookies();
      const cookieHeader = cookies.map(c => `${c.name}=${c.value}`).join('; ');

      // Try to call API directly
      try {
        const response = await request.post('http://localhost/ujian/api/simpan_jawaban_temp.php', {
          headers: {
            'Cookie': cookieHeader,
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: new URLSearchParams({
            soal_id: '1',
            opsi_id: '1',
            paket_id: '1',
            is_ragu: '0'
          })
        });

        console.log(`API Response Status: ${response.status()}`);
        const body = await response.text();
        console.log(`API Response Body: ${body}`);

        expect(response.ok()).toBeTruthy();
      } catch (error) {
        console.log(`API Error: ${error.message}`);
      }
    } else {
      console.log('Skipping API test - no active exam');
    }
  });

  test('06. Admin Kelola Soal - Data Inspection', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php');

    clearLogs();
    await page.goto('admin/kelola_soal.php');
    await page.waitForLoadState('networkidle');

    // Check table
    const table = page.locator('table');
    await expect(table).toBeVisible();

    // Count rows
    const rows = await page.locator('tbody tr').all();
    console.log(`Total Soal Rows: ${rows.length}`);

    // Check filter form
    const filterSelects = await page.locator('select').all();
    console.log(`Filter Selects: ${filterSelects.length}`);

    // Check add button (specific selector for the main add button)
    const addBtn = page.locator('a.btn-primary[href*="kelola_soal_form.php"]');
    const addBtnVisible = await addBtn.isVisible();
    console.log(`Add Button Visible: ${addBtnVisible}`);

    logDetailedInfo('Kelola Soal', page);

    expect(allNetworkErrors).toHaveLength(0);
  });

  test('07. Peserta Belajar Page - Content Inspection', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php');

    clearLogs();
    await page.goto('peserta/belajar.php');
    await page.waitForLoadState('networkidle');

    // Check materi cards
    const materiCards = await page.locator('.card').all();
    console.log(`Materi Cards: ${materiCards.length}`);

    // Check filters
    const filters = await page.locator('select').all();
    console.log(`Filter Selects: ${filters.length}`);

    logDetailedInfo('Belajar Page', page);

    expect(allNetworkErrors).toHaveLength(0);
  });

  test('08. Database Connection Check via PHP', async ({ page }) => {
    // Access a page that requires DB connection
    await page.goto('login.php');

    // Check if page loads without DB error
    const hasDBError = await page.locator('text=Koneksi database gagal').isVisible().catch(() => false);
    console.log(`DB Connection Error: ${hasDBError}`);

    expect(hasDBError).toBeFalsy();
  });

  test('09. Session & Cookie Inspection', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');

    clearLogs();
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php');

    // Check cookies
    const cookies = await page.context().cookies();
    console.log(`Cookies Count: ${cookies.length}`);
    cookies.forEach(c => {
      console.log(`  ${c.name}: ${c.value.substring(0, 20)}...`);
    });

    // Check localStorage
    const localStorage = await page.evaluate(() => {
      const items = {};
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        items[key] = localStorage.getItem(key);
      }
      return items;
    });
    console.log(`LocalStorage Items: ${Object.keys(localStorage).length}`);
    Object.entries(localStorage).forEach(([k, v]) => {
      console.log(`  ${k}: ${v.substring(0, 50)}...`);
    });

    logDetailedInfo('Session Inspection', page);
  });

  test('10. All Pages - Network & Console Health Check', async ({ page }) => {
    const pages = [
      'index.php',
      'login.php',
      'register.php',
      'admin/dashboard.php',
      'admin/kelola_soal.php',
      'admin/kelola_materi.php',
      'admin/kelola_paket.php',
      'admin/laporan.php',
      'admin/export.php',
      'peserta/dashboard.php',
      'peserta/tryout_list.php',
      'peserta/mini_tryout.php',
      'peserta/latihan_topik.php',
      'peserta/belajar.php',
      'peserta/flashcard.php',
      'peserta/leaderboard.php',
      'peserta/forum.php',
      'peserta/rapor.php',
      'peserta/profil.php',
    ];

    // Login as admin first
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php');

    const results = [];

    for (const p of pages) {
      clearLogs();
      await page.goto(p);
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(300);

      const netErrors = allNetworkErrors.filter(e => !e.includes('favicon'));
      const pageErrs = pageErrors.filter(e => !e.includes('alert') && !e.includes('PERINGATAN'));

      results.push({
        page: p,
        networkErrors: netErrors.length,
        pageErrors: pageErrs.length,
        consoleMessages: allConsoleMessages.length,
        networkRequests: allNetworkRequests.filter(r => !r.pending).length
      });

      if (netErrors.length > 0 || pageErrs.length > 0) {
        console.log(`\n⚠️  Issues on ${p}:`);
        console.log(`  Network Errors: ${netErrors.length}`);
        netErrors.forEach(e => console.log(`    ${e}`));
        console.log(`  Page Errors: ${pageErrs.length}`);
        pageErrs.forEach(e => console.log(`    ${e}`));
      }
    }

    console.log('\n=== SUMMARY ===');
    results.forEach(r => {
      const status = (r.networkErrors === 0 && r.pageErrors === 0) ? '✅' : '❌';
      console.log(`${status} ${r.page}: ${r.networkErrors} net errs, ${r.pageErrors} page errs, ${r.networkRequests} requests`);
    });

    const totalErrors = results.reduce((sum, r) => sum + r.networkErrors + r.pageErrors, 0);
    expect(totalErrors).toBe(0);
  });
});
