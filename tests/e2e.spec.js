const { test, expect } = require('@playwright/test');

// Console log collector
let consoleMessages = [];
let networkErrors = [];
let networkRequests = [];

test.beforeEach(async ({ page }) => {
  consoleMessages = [];
  networkErrors = [];
  networkRequests = [];

  page.on('console', msg => {
    const text = `[${msg.type().toUpperCase()}] ${msg.text()}`;
    consoleMessages.push(text);
    console.log(text);
  });

  page.on('pageerror', error => {
    const text = `[PAGE ERROR] ${error.message}`;
    networkErrors.push(text);
    console.error(text);
  });

  page.on('request', request => {
    const text = `[REQUEST] ${request.method()} ${request.url()}`;
    networkRequests.push(text);
  });

  page.on('response', response => {
    const text = `[RESPONSE] ${response.status()} ${response.url()}`;
    networkRequests.push(text);
  });

  page.on('requestfailed', request => {
    const text = `[REQUEST FAILED] ${request.method()} ${request.url()} - ${request.failure()?.errorText || 'unknown'}`;
    networkErrors.push(text);
    console.error(text);
  });
});

test.describe('TryOutKu E2E Testing', () => {

  test('1. Landing Page loads correctly', async ({ page }) => {
    await page.goto('');
    await expect(page).toHaveTitle(/TryOutKu/);
    await expect(page.locator('h1')).toContainText('TryOutKu');

    // Check console/network for errors
    console.log('=== Console Messages (Landing) ===');
    consoleMessages.forEach(m => console.log(m));
    console.log('=== Network Errors (Landing) ===');
    networkErrors.forEach(e => console.error(e));

    expect(networkErrors).toHaveLength(0);
  });

  test('2. Login Admin works', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });

    await expect(page.locator('text=Dashboard Admin')).toBeVisible();
    console.log('=== Console Messages (Login Admin) ===');
    consoleMessages.forEach(m => console.log(m));
    expect(networkErrors).toHaveLength(0);
  });

  test('3. Admin can view Kelola Soal', async ({ page }) => {
    // Login first
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });

    await page.goto('admin/kelola_soal.php');
    await expect(page.locator('text=Kelola Soal')).toBeVisible();

    console.log('=== Console Messages (Kelola Soal) ===');
    consoleMessages.forEach(m => console.log(m));
    expect(networkErrors).toHaveLength(0);
  });

  test('4. Peserta can register and login', async ({ page }) => {
    await page.goto('register.php');
    const uniqueEmail = `testpeserta_${Date.now()}@test.com`;
    await page.fill('input[name="nama"]', 'Peserta Test');
    await page.fill('input[name="email"]', uniqueEmail);
    await page.fill('input[name="no_hp"]', '081234567890');
    await page.fill('input[name="password"]', 'password123');
    await page.fill('input[name="password2"]', 'password123');
    await page.click('button[type="submit"]');

    // Should show success
    await expect(page.locator('.alert-success')).toContainText('Pendaftaran berhasil');

    console.log('=== Console Messages (Register) ===');
    consoleMessages.forEach(m => console.log(m));
    expect(networkErrors).toHaveLength(0);
  });

  test('5. Peserta dashboard and try-out flow', async ({ page }) => {
    // Register a test user first (reuse or create new)
    await page.goto('register.php');
    const uniqueEmail = `peserta_e2e_${Date.now()}@test.com`;
    await page.fill('input[name="nama"]', 'E2E Peserta');
    await page.fill('input[name="email"]', uniqueEmail);
    await page.fill('input[name="no_hp"]', '081234567890');
    await page.fill('input[name="password"]', 'password123');
    await page.fill('input[name="password2"]', 'password123');
    await page.click('button[type="submit"]');

    // Login with new user
    await page.goto('login.php');
    await page.fill('input[name="email"]', uniqueEmail);
    await page.fill('input[name="password"]', 'password123');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    await expect(page.locator('h4')).toContainText('Halo');
    console.log('=== Console Messages (Peserta Dashboard) ===');
    consoleMessages.forEach(m => console.log(m));

    // Go to try-out list
    await page.goto('peserta/tryout_list.php');
    await expect(page.locator('h4')).toContainText('Pilih Try-Out');

    console.log('=== Network Requests (Try-Out List) ===');
    networkRequests.forEach(r => console.log(r));
    expect(networkErrors).toHaveLength(0);
  });

  test('6. Admin Laporan page loads', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });

    await page.goto('admin/laporan.php');
    await expect(page.locator('h3')).toContainText('Laporan');

    console.log('=== Console Messages (Laporan) ===');
    consoleMessages.forEach(m => console.log(m));
    expect(networkErrors).toHaveLength(0);
  });

  test('7. Belajar page loads for peserta', async ({ page }) => {
    await page.goto('register.php');
    const uniqueEmail = `belajar_${Date.now()}@test.com`;
    await page.fill('input[name="nama"]', 'Belajar Test');
    await page.fill('input[name="email"]', uniqueEmail);
    await page.fill('input[name="no_hp"]', '081234567890');
    await page.fill('input[name="password"]', 'password123');
    await page.fill('input[name="password2"]', 'password123');
    await page.click('button[type="submit"]');

    await page.goto('login.php');
    await page.fill('input[name="email"]', uniqueEmail);
    await page.fill('input[name="password"]', 'password123');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    await page.goto('peserta/belajar.php');
    await expect(page.locator('text=Materi Belajar')).toBeVisible();

    console.log('=== Console Messages (Belajar) ===');
    consoleMessages.forEach(m => console.log(m));
    expect(networkErrors).toHaveLength(0);
  });

  test('8. Full try-out submission flow', async ({ page }) => {
    // Step 1: Admin login and check paket
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'admin@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });

    // Check if any paket exists with soal
    await page.goto('admin/kelola_paket.php');
    await page.waitForTimeout(500);

    // Step 2: Logout admin, then login as pre-seeded peserta
    await page.goto('logout.php');
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });

    // Step 4: Go to try-out list
    await page.goto('peserta/tryout_list.php');
    await page.waitForTimeout(1000);

    // Check if any paket available
    const mulaiBtn = page.locator('a:has-text("Mulai Try-Out"), a:has-text("Kerjakan Lagi")').first();
    if (await mulaiBtn.isVisible().catch(() => false)) {
      await mulaiBtn.click();
      await page.waitForTimeout(2000);

      // Answer a few questions
      const opsi = page.locator('.opsi-jawaban').first();
      if (await opsi.isVisible().catch(() => false)) {
        await opsi.click();
        await page.waitForTimeout(500);
      }

      console.log('Try-out page loaded, answered one question.');
    } else {
      console.log('No try-out available (no paket with soal yet).');
    }

    console.log('=== All Console Messages (Full Flow) ===');
    consoleMessages.forEach(m => console.log(m));
    console.log('=== All Network Errors (Full Flow) ===');
    networkErrors.forEach(e => console.error(e));

    expect(networkErrors).toHaveLength(0);
  });
});
