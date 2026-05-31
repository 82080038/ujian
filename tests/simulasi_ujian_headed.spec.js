const { test, expect } = require('@playwright/test');

let networkErrors = [];
let consoleMessages = [];

function clearLogs() {
  consoleMessages = [];
  networkErrors = [];
}

test.beforeEach(async ({ page }) => {
  clearLogs();
  page.on('console', msg => consoleMessages.push(`[${msg.type().toUpperCase()}] ${msg.text()}`));
  page.on('pageerror', error => networkErrors.push(`[PAGE ERROR] ${error.message}`));
  page.on('requestfailed', request => networkErrors.push(`[REQUEST FAILED] ${request.url()}`));
});

test('Simulasi Ujian Peserta Demo - Headed', async ({ page }) => {
  console.log('\n========== SIMULASI UJIAN HEADED (SLOW MOTION) ==========\n');

  // Step 1: Login peserta demo
  console.log('[1] Login peserta demo...');
  await page.goto('login.php');
  await page.fill('input[name="email"]', 'peserta_demo@tryoutku.com');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
  console.log('    Login berhasil.');

  // Step 2: Pilih Try-Out
  console.log('[2] Menuju Try-Out List...');
  await page.goto('peserta/tryout_list.php');
  await page.waitForTimeout(1000);

  const mulaiBtn = page.locator('a:has-text("Mulai Try-Out"), a:has-text("Kerjakan")').first();
  await expect(mulaiBtn).toBeVisible();
  console.log('    Paket ujian tersedia.');

  // Step 3: Mulai ujian
  console.log('[3] Memulai ujian...');
  await mulaiBtn.click();
  await page.waitForTimeout(2000);

  const soalBox = page.locator('.soal-box, .card').first();
  await expect(soalBox).toBeVisible();
  console.log('    Halaman ujian aktif.');

  // Step 4: Kerjakan soal satu per satu
  console.log('[4] Mengerjakan soal...');
  let nomor = 1;
  const maxSoal = 20; // safety limit

  while (nomor <= maxSoal) {
    const opsi = page.locator('.opsi-jawaban').first();
    const visible = await opsi.isVisible().catch(() => false);
    if (!visible) {
      console.log('    Soal habis atau sudah submit.');
      break;
    }

    const opsiList = await page.locator('.opsi-jawaban').all();
    if (opsiList.length > 0) {
      const pilihIdx = Math.floor(Math.random() * opsiList.length);
      await opsiList[pilihIdx].click();
      console.log(`    Soal #${nomor}: dijawab`);
    }

    const nextBtn = page.locator('button:has-text("Selanjutnya"), a:has-text("Selanjutnya")').first();
    const adaNext = await nextBtn.isVisible().catch(() => false);
    if (adaNext) {
      await nextBtn.click();
    } else {
      break;
    }
    nomor++;
  }

  // Step 5: Submit ujian
  console.log('[5] Submit ujian...');
  const selesaiBtn = page.locator('button:has-text("Selesai"), a:has-text("Selesai"), button[type="button"]').filter({ has: page.locator('i.bi-check-lg') }).first();
  if (await selesaiBtn.isVisible().catch(() => false)) {
    await selesaiBtn.click();
    await page.waitForTimeout(1000);
  }

  // Handle modal confirm
  const modalSubmit = page.locator('#modalSubmit button[type="submit"]').first();
  if (await modalSubmit.isVisible().catch(() => false)) {
    await modalSubmit.click();
    console.log('    Submit diklik.');
    await page.waitForTimeout(3000);
  }

  // Step 6: Verifikasi hasil
  console.log('[6] Verifikasi hasil...');
  await page.waitForTimeout(2000);

  const url = page.url();
  console.log(`    URL akhir: ${url}`);

  const screenshotPath = `test-results/simulasi_peserta_demo_headed.png`;
  await page.screenshot({ path: screenshotPath, fullPage: true });
  console.log(`    Screenshot: ${screenshotPath}`);

  const hasilTitle = page.locator('h3, h4').filter({ hasText: /Hasil|Skor|Rapor|Try-Out/ }).first();
  const hasHasil = await hasilTitle.isVisible().catch(() => false);
  if (hasHasil) {
    console.log(`    Halaman terdeteksi: ${await hasilTitle.textContent()}`);
  }

  if (networkErrors.length > 0) {
    console.log('    [WARNING] Errors:', networkErrors.length);
  }

  console.log('\n========== SELESAI ==========\n');
});
