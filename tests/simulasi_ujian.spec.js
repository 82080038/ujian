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

test.describe('Simulasi Ujian 2 Peserta', () => {

  const peserta = [
    { nama: 'Peserta Simulasi 1', email: 'simulasi1_1780233573_6a1c356544ee7@test.com', password: 'password123' },
    { nama: 'Peserta Simulasi 2', email: 'simulasi2_1780233573_6a1c356553ea6@test.com', password: 'password123' }
  ];

  for (const p of peserta) {
    test(`Proses Ujian - ${p.nama}`, async ({ page }) => {
      console.log(`\n========== MEMULAI UJIAN: ${p.nama} ==========`);

      // Step 1: Login
      console.log('[1] Login...');
      await page.goto('login.php');
      await page.fill('input[name="email"]', p.email);
      await page.fill('input[name="password"]', p.password);
      await page.click('button[type="submit"]');
      await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
      console.log('    Login berhasil.');

      // Step 2: Pilih Try-Out
      console.log('[2] Menuju Try-Out List...');
      await page.goto('peserta/tryout_list.php');
      await expect(page.locator('h4')).toContainText('Pilih Try-Out');

      const mulaiBtn = page.locator('a:has-text("Mulai Try-Out"), a:has-text("Kerjakan")').first();
      await expect(mulaiBtn).toBeVisible();
      console.log('    Paket ujian tersedia.');

      // Step 3: Mulai Ujian
      console.log('[3] Memulai ujian...');
      await mulaiBtn.click();
      await page.waitForTimeout(1500);

      // Verifikasi halaman ujian dimuat
      const soalBox = page.locator('.soal-box, .card').first();
      await expect(soalBox).toBeVisible();
      console.log('    Halaman ujian aktif.');

      // Step 4: Kerjakan soal satu per satu
      console.log('[4] Mengerjakan soal...');
      let nomor = 1;
      let maxSoal = 15;

      while (nomor <= maxSoal) {
        // Cek apakah masih di halaman soal
        const opsi = page.locator('.opsi-jawaban').first();
        const visible = await opsi.isVisible().catch(() => false);
        if (!visible) {
          console.log('    Soal habis atau sudah submit.');
          break;
        }

        // Pilih opsi pertama (simulasi jawaban)
        const opsiList = await page.locator('.opsi-jawaban').all();
        if (opsiList.length > 0) {
          // Pilih opsi acak
          const pilihIdx = Math.floor(Math.random() * opsiList.length);
          await opsiList[pilihIdx].click();
          await page.waitForTimeout(300);
          console.log(`    Soal #${nomor}: dijawab`);
        }

        // Cek tombol next
        const nextBtn = page.locator('button:has-text("Selanjutnya"), a:has-text("Selanjutnya"), button:has-text("Next")').first();
        const adaNext = await nextBtn.isVisible().catch(() => false);
        if (adaNext) {
          await nextBtn.click();
          await page.waitForTimeout(500);
        } else {
          // Coba tekan tombol navigasi nomor berikutnya
          const navBtn = page.locator(`#nav-btn-${nomor + 1}, .nav-soal-${nomor + 1}`).first();
          const adaNav = await navBtn.isVisible().catch(() => false);
          if (adaNav) {
            await navBtn.click();
            await page.waitForTimeout(500);
          }
        }
        nomor++;
      }

      // Step 5: Submit ujian
      console.log('[5] Submit ujian...');

      // Click submit button to open modal
      const selesaiBtn = page.locator('button:has-text("Selesai"), a:has-text("Selesai"), button[type="button"]').filter({ has: page.locator('i.bi-check-lg') }).first();
      if (await selesaiBtn.isVisible().catch(() => false)) {
        await selesaiBtn.click();
        await page.waitForTimeout(800);
      }

      // Click submit in modal
      const modalSubmit = page.locator('#modalSubmit button[type="submit"]').first();
      if (await modalSubmit.isVisible().catch(() => false)) {
        await modalSubmit.click();
        console.log('    Submit diklik, menunggu redirect...');
        await page.waitForTimeout(3000);
      }

      // Step 6: Verifikasi hasil
      console.log('[6] Verifikasi hasil...');
      await page.waitForTimeout(2000);

      const url = page.url();
      console.log(`    URL akhir: ${url}`);

      const screenshotPath = `test-results/simulasi_${p.nama.replace(/\s+/g, '_')}_hasil.png`;
      await page.screenshot({ path: screenshotPath, fullPage: true });
      console.log(`    Screenshot: ${screenshotPath}`);

      const hasilTitle = page.locator('h3, h4').filter({ hasText: /Hasil|Skor|Rapor/ }).first();
      const hasHasil = await hasilTitle.isVisible().catch(() => false);
      if (hasHasil) {
        const titleText = await hasilTitle.textContent();
        console.log(`    Halaman hasil: ${titleText}`);
      } else {
        console.log('    Catatan: Halaman hasil tidak terdeteksi otomatis, cek screenshot.');
      }

      // Dump error jika ada
      if (networkErrors.length > 0) {
        console.log('    [WARNING] Network errors:', networkErrors);
      }

      console.log(`\n========== SELESAI: ${p.nama} ==========\n`);
    });
  }

});
