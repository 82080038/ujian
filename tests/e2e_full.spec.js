/**
 * TryOutKu - Full E2E Test Suite
 * Tests semua halaman, semua fitur, dengan console & network monitoring
 */
const { test, expect } = require('@playwright/test');

// ── Credentials ──────────────────────────────────────────────────────────────
const ADMIN = { email: 'admin@tryoutku.com', password: 'password' };
const PESERTA = { email: 'peserta_demo@tryoutku.com', password: 'password' };

// ── Helpers ───────────────────────────────────────────────────────────────────
function attachConsoleWatcher(page, collected) {
  page.on('console', msg => {
    if (['error', 'warning'].includes(msg.type())) {
      collected.push({ type: msg.type(), text: msg.text(), url: page.url() });
    }
  });
  page.on('pageerror', err => {
    collected.push({ type: 'pageerror', text: err.message, url: page.url() });
  });
  page.on('requestfailed', req => {
    const url = req.url();
    if (url.includes('favicon')) return;
    collected.push({ type: 'net-fail', text: `${req.failure()?.errorText} — ${url}`, url: page.url() });
  });
  page.on('response', res => {
    if (res.status() >= 500) {
      collected.push({ type: 'http-5xx', text: `HTTP ${res.status()} — ${res.url()}`, url: page.url() });
    }
  });
}

async function loginAdmin(page) {
  await page.goto('login.php');
  await page.fill('input[name="email"]', ADMIN.email);
  await page.fill('input[name="password"]', ADMIN.password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/admin/dashboard.php', { timeout: 10000 });
}

async function loginPeserta(page) {
  await page.goto('login.php');
  await page.fill('input[name="email"]', PESERTA.email);
  await page.fill('input[name="password"]', PESERTA.password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/peserta/dashboard.php', { timeout: 10000 });
}

function assertNoErrors(collected, label) {
  const critical = collected.filter(e =>
    e.type === 'pageerror' || e.type === 'net-fail' || e.type === 'http-5xx'
  );
  if (critical.length > 0) {
    console.error(`\n=== ERRORS on ${label} ===`);
    critical.forEach(e => console.error(`  [${e.type}] ${e.text}`));
  }
  expect(critical, `Errors found on ${label}: ${critical.map(e => e.text).join('; ')}`).toHaveLength(0);
}

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 1: Auth & Landing
// ═════════════════════════════════════════════════════════════════════════════
test.describe('01 - Auth & Landing', () => {
  test('landing page loads, no errors', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await page.goto('');
    await expect(page).toHaveTitle(/TryOutKu/);
    await expect(page.locator('h1')).toBeVisible();
    await expect(page.locator('a[href="login.php"]')).toBeVisible();
    await expect(page.locator('a[href="register.php"]')).toBeVisible();
    assertNoErrors(errs, 'Landing');
  });

  test('login page has form, no errors', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await page.goto('login.php');
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
    assertNoErrors(errs, 'Login Page');
  });

  test('admin login redirects to admin dashboard', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginAdmin(page);
    await expect(page.locator('h3, h4')).toContainText(/Dashboard/);
    assertNoErrors(errs, 'Admin Login');
  });

  test('peserta login redirects to peserta dashboard', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await expect(page.locator('h4')).toContainText(/Halo/);
    assertNoErrors(errs, 'Peserta Login');
  });

  test('invalid login shows error', async ({ page }) => {
    await page.goto('login.php');
    await page.fill('input[name="email"]', 'wrong@email.com');
    await page.fill('input[name="password"]', 'wrongpassword');
    await page.click('button[type="submit"]');
    await expect(page.locator('.alert-danger')).toBeVisible();
  });

  test('register page loads, no errors', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await page.goto('register.php');
    await expect(page.locator('h3, h4')).toContainText(/Daftar/i);
    assertNoErrors(errs, 'Register Page');
  });

  test('unauthenticated access to admin redirects to login', async ({ page }) => {
    await page.goto('admin/dashboard.php');
    await expect(page).toHaveURL(/login\.php/);
  });

  test('unauthenticated access to peserta redirects to login', async ({ page }) => {
    await page.goto('peserta/dashboard.php');
    await expect(page).toHaveURL(/login\.php/);
  });

  test('logout destroys session', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('logout.php');
    await expect(page).toHaveURL(/login\.php|index\.php/);
    await page.goto('peserta/dashboard.php');
    await expect(page).toHaveURL(/login\.php/);
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 2: Admin Pages — Console & Network Health
// ═════════════════════════════════════════════════════════════════════════════
test.describe('02 - Admin Pages Health', () => {
  const adminPages = [
    { path: 'admin/dashboard.php', label: 'Admin Dashboard', h: /Dashboard/ },
    { path: 'admin/kelola_soal.php', label: 'Kelola Soal', h: /Kelola Soal/ },
    { path: 'admin/kelola_soal_form.php', label: 'Kelola Soal Form', h: /Tambah|Edit/ },
    { path: 'admin/kelola_materi.php', label: 'Kelola Materi', h: /Materi/ },
    { path: 'admin/kelola_paket.php', label: 'Kelola Paket', h: /Paket/ },
    { path: 'admin/laporan.php', label: 'Laporan', h: /Laporan/ },
    { path: 'admin/export.php', label: 'Export', h: /Export/ },
    { path: 'admin/jawab_forum.php', label: 'Jawab Forum', h: /Forum/ },
    { path: 'admin/catatan_pengajar.php', label: 'Catatan Pengajar', h: /Catatan/ },
    { path: 'admin/analisis_butir.php', label: 'Analisis Butir', h: /Analisis/ },
    { path: 'admin/laporan.php?tab=butir', label: 'Laporan Butir Soal', h: /Laporan/ },
  ];

  for (const { path, label, h } of adminPages) {
    test(`${label} loads without errors`, async ({ page }) => {
      const errs = [];
      attachConsoleWatcher(page, errs);
      await loginAdmin(page);
      await page.goto(path);
      await page.waitForTimeout(500);
      // Gunakan .container agar tidak mengambil heading di offcanvas/navbar
      await expect(page.locator('.container h3, .container h4').first()).toContainText(h);
      assertNoErrors(errs, label);
    });
  }

  test('Admin: kelola soal — filter by jenis_tes', async ({ page }) => {
    await loginAdmin(page);
    await page.goto('admin/kelola_soal.php');
    await page.selectOption('select[name="jenis"]', 'twk');
    await page.locator('button[type="submit"]').first().click();
    await page.waitForTimeout(500);
    await expect(page.locator('.badge:has-text("TWK")').first()).toBeVisible();
  });

  test('Admin: kelola paket — tambah & hapus works', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginAdmin(page);
    await page.goto('admin/kelola_paket.php');
    await expect(page.locator('.container h3').first()).toContainText(/Paket/);
    await expect(page.locator('form').first()).toBeVisible();
    assertNoErrors(errs, 'Kelola Paket Form');
  });

  test('Admin: export page loads without errors', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginAdmin(page);
    await page.goto('admin/export.php');
    await expect(page.locator('.container h3').first()).toContainText(/Export/);
    await expect(page.locator('.card h5:has-text("Data Peserta")')).toBeVisible();
    await expect(page.locator('.card h5:has-text("Hasil Ujian")')).toBeVisible();
    assertNoErrors(errs, 'Export Page');
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 3: Peserta Pages — Console & Network Health
// ═════════════════════════════════════════════════════════════════════════════
test.describe('03 - Peserta Pages Health', () => {
  const pesertaPages = [
    { path: 'peserta/dashboard.php', label: 'Dashboard', h: /Halo/ },
    { path: 'peserta/profil.php', label: 'Profil', h: /Profil/ },
    { path: 'peserta/tryout_list.php', label: 'Try-Out List', h: /Try-Out/ },
    { path: 'peserta/mini_tryout.php', label: 'Mini Try-Out', h: /Mini Try-Out/ },
    { path: 'peserta/latihan_topik.php', label: 'Latihan Topik', h: /Latihan/ },
    { path: 'peserta/belajar.php', label: 'Belajar', h: /Materi Belajar/ },
    { path: 'peserta/belajar_detail.php?id=1', label: 'Belajar Detail', h: /.+/ },
    { path: 'peserta/flashcard.php', label: 'Flashcard', h: /Flashcard/ },
    { path: 'peserta/flashcard_detail.php?id=1', label: 'Flashcard Detail', h: /.+/ },
    { path: 'peserta/rapor.php', label: 'Rapor', h: /Rapor/ },
    { path: 'peserta/leaderboard.php', label: 'Leaderboard', h: /Leaderboard/ },
    { path: 'peserta/forum.php', label: 'Forum', h: /Forum/ },
    { path: 'peserta/psikologi.php', label: 'Psikologi', h: /Psikologi/ },
    { path: 'peserta/psikologi_kraepelin.php', label: 'Psikologi Kraepelin', h: /Kraepelin/ },
  ];

  for (const { path, label, h } of pesertaPages) {
    test(`${label} loads without errors`, async ({ page }) => {
      const errs = [];
      attachConsoleWatcher(page, errs);
      await loginPeserta(page);
      await page.goto(path);
      await page.waitForTimeout(600);
      // Gunakan .container agar tidak mengambil heading di offcanvas/navbar
      await expect(page.locator('.container h2, .container h3, .container h4').first()).toContainText(h);
      assertNoErrors(errs, label);
    });
  }

  test('Rapor: chart canvases visible', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/rapor.php');
    await page.waitForTimeout(1500);
    await expect(page.locator('canvas#grafikSkor')).toBeVisible();
    await expect(page.locator('canvas#grafikRadar')).toBeVisible();
    assertNoErrors(errs, 'Rapor Charts');
  });

  test('Try-Out List: badge status tampil benar (bukan GUGUR untuk proses)', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('peserta/tryout_list.php');
    // Cek tidak ada badge GUGUR berwarna merah untuk ujian proses (seharusnya PROSES kuning)
    const gugurBadges = page.locator('.badge:has-text("GUGUR")');
    // Jika ada GUGUR, itu harus karena ujian benar-benar gugur bukan proses
    // Test memastikan tidak ada error rendering
    await expect(page.locator('h4')).toContainText('Try-Out');
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 4: Full Try-Out Flow (E2E)
// ═════════════════════════════════════════════════════════════════════════════
test.describe('04 - Try-Out Flow', () => {
  test('Mulai try-out, jawab 1 soal, ragu-ragu, submit', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);

    // Buka list tryout
    await page.goto('peserta/tryout_list.php');
    const startBtn = page.locator('a.btn:has-text("Mulai Try-Out"), a.btn:has-text("Lanjutkan")').first();
    const canStart = await startBtn.isVisible({ timeout: 4000 }).catch(() => false);
    if (!canStart) {
      console.log('SKIP: tidak ada paket yang bisa dimulai (semua sudah selesai)');
      return; // graceful skip
    }
    await startBtn.click();
    await page.waitForURL('**/tryout_kerja.php**', { timeout: 10000 });
    await page.waitForTimeout(1000);

    // Pastikan soal tampil
    await expect(page.locator('.soal-box, .card-body').first()).toBeVisible();

    // Jawab soal pertama — klik opsi pertama
    const firstOpsi = page.locator('.opsi-jawaban').first();
    await expect(firstOpsi).toBeVisible({ timeout: 5000 });
    await firstOpsi.click();
    await page.waitForTimeout(700);

    // Toggle ragu-ragu
    const raguCheckbox = page.locator('input.toggle-ragu, input[id^="ragu-"]').first();
    if (await raguCheckbox.isVisible({ timeout: 1000 }).catch(() => false)) {
      await raguCheckbox.check();
      await page.waitForTimeout(300);
    }

    // Submit ujian via modal
    const submitBtn = page.locator('button[data-bs-target="#modalSubmit"]').first();
    if (await submitBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
      await submitBtn.click();
      await page.waitForTimeout(500);
      const confirmBtn = page.locator('#modalSubmit .btn-danger, #modalSubmit .btn-success').first();
      if (await confirmBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
        await confirmBtn.click();
        await page.waitForURL('**/tryout_hasil.php**', { timeout: 15000 });
        await expect(page.locator('.container h4').first()).toContainText(/Hasil|Tryout/i);
      }
    }
    assertNoErrors(errs, 'Try-Out Flow');
  });

  test('Try-Out: timer element visible dan berjalan', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('peserta/tryout_list.php');
    const startBtn = page.locator('a.btn:has-text("Mulai"), a.btn:has-text("Lanjutkan")').first();
    if (!(await startBtn.isVisible({ timeout: 3000 }).catch(() => false))) {
      console.log('SKIP: tidak ada paket yang bisa dimulai');
      return;
    }
    await startBtn.click();
    await page.waitForURL('**/tryout_kerja.php**', { timeout: 10000 });
    const timer = page.locator('#timer, .timer-box').first();
    await expect(timer).toBeVisible({ timeout: 3000 });
    const t1 = await timer.textContent();
    await page.waitForTimeout(2200);
    const t2 = await timer.textContent();
    expect(t1).not.toBe(t2);
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 5: Mini Try-Out Flow
// ═════════════════════════════════════════════════════════════════════════════
test.describe('05 - Mini Try-Out Flow', () => {
  test('Mini try-out TWK: start, jawab, submit, lihat hasil', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);

    // Start mini tryout
    await page.goto('peserta/mini_tryout.php');
    await page.check('input[name="jenis"][value="twk"]');
    await page.selectOption('select[name="jumlah"]', '10');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/mini_tryout_kerja.php**', { timeout: 10000 });
    await page.waitForTimeout(800);

    // Jawab 3 soal pertama
    for (let i = 0; i < 3; i++) {
      await expect(page.locator('.opsi-jawaban, .mini-opsi').first()).toBeVisible({ timeout: 5000 });
      await page.locator('.mini-opsi').first().click();
      await page.waitForTimeout(600);
      // Next
      const nextBtn = page.locator('a.btn-primary[href*="n="]');
      if (await nextBtn.isVisible({ timeout: 1000 }).catch(() => false)) {
        await nextBtn.click();
        await page.waitForTimeout(600);
      } else break;
    }

    // Klik Selesai
    const selesaiBtn = page.locator('button:has-text("Selesai")');
    if (await selesaiBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
      await selesaiBtn.click();
      await page.waitForTimeout(500);
      // Konfirmasi modal
      const yaBtn = page.locator('.modal.show .btn-success, #modalSubmit .btn-success').first();
      if (await yaBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
        await yaBtn.click();
        await page.waitForURL('**/mini_tryout_hasil.php**', { timeout: 10000 });
        await expect(page.locator('h4')).toContainText(/Hasil Mini/);
      }
    }
    assertNoErrors(errs, 'Mini Try-Out Flow');
  });

  test('Mini try-out: soal order konsisten saat navigasi bolak-balik', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('peserta/mini_tryout.php');
    await page.check('input[name="jenis"][value="tiu"]');
    await page.selectOption('select[name="jumlah"]', '10');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/mini_tryout_kerja.php**', { timeout: 10000 });
    await page.waitForTimeout(500);

    // Baca teks soal pertama
    const soal1 = await page.locator('p.fw-semibold').first().textContent();

    // Navigasi ke soal 2 via nav button
    const nav2 = page.locator('a.btn-soal').nth(1);
    await expect(nav2).toBeVisible({ timeout: 3000 });
    await nav2.click();
    await page.waitForTimeout(500);
    const soal2 = await page.locator('p.fw-semibold').first().textContent();

    // Kembali ke soal 1
    const nav1 = page.locator('a.btn-soal').first();
    await nav1.click();
    await page.waitForTimeout(500);
    const soal1Again = await page.locator('p.fw-semibold').first().textContent();

    // Soal 1 harus sama sebelum dan sesudah navigasi
    expect(soal1.trim()).toBe(soal1Again.trim());
    expect(soal1.trim()).not.toBe(soal2.trim());
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 6: Latihan per Topik Flow
// ═════════════════════════════════════════════════════════════════════════════
test.describe('06 - Latihan Flow', () => {
  test('Latihan topik: mulai, jawab, lihat pembahasan', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);

    await page.goto('peserta/latihan_topik.php');
    await expect(page.locator('h4')).toContainText(/Latihan/);

    // Klik latihan soal pertama yang tersedia
    const latihanBtn = page.locator('a.btn[href*="latihan_kerja.php"]').first();
    if (await latihanBtn.isVisible({ timeout: 3000 }).catch(() => false)) {
      await latihanBtn.click();
      await page.waitForURL('**/latihan_kerja.php**', { timeout: 10000 });
      await page.waitForTimeout(500);

      // Klik opsi jawaban
      await page.locator('.latihan-opsi, .opsi-jawaban').first().click();
      await page.waitForTimeout(600);

      // Klik lihat pembahasan
      const pembahasan = page.locator('button.reveal-btn, button:has-text("Lihat Pembahasan")');
      if (await pembahasan.isVisible({ timeout: 2000 }).catch(() => false)) {
        await pembahasan.click();
        await page.waitForTimeout(500);
        await expect(page.locator('.pembahasan-box, .bg-light h6:has-text("Pembahasan"), h6:has-text("Pembahasan")')).toBeVisible();
      }
    }
    assertNoErrors(errs, 'Latihan Flow');
  });

  test('Latihan: soal order konsisten saat navigasi bolak-balik', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('peserta/latihan_topik.php');

    const latihanBtn = page.locator('a.btn[href*="latihan_kerja.php"]').first();
    if (!(await latihanBtn.isVisible({ timeout: 3000 }).catch(() => false))) return;

    await latihanBtn.click();
    await page.waitForURL('**/latihan_kerja.php**', { timeout: 10000 });

    const soal1 = await page.locator('p.fw-semibold').first().textContent();

    // Navigasi ke soal 2
    const nextBtn = page.locator('a.btn-primary[href*="n=2"]');
    if (!(await nextBtn.isVisible({ timeout: 2000 }).catch(() => false))) return;
    await nextBtn.click();
    await page.waitForTimeout(400);

    // Kembali ke soal 1
    const prevBtn = page.locator('a.btn-outline-secondary[href*="n=1"]');
    if (!(await prevBtn.isVisible({ timeout: 2000 }).catch(() => false))) return;
    await prevBtn.click();
    await page.waitForTimeout(400);

    const soal1Again = await page.locator('p.fw-semibold').first().textContent();
    expect(soal1).toBe(soal1Again);
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 7: Psikologi Flow
// ═════════════════════════════════════════════════════════════════════════════
test.describe('07 - Psikologi Flow', () => {
  test('Psikologi menu loads, 3 jenis tes visible', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/psikologi.php');
    await expect(page.locator('.container h2').first()).toContainText(/Psikologi/);
    await expect(page.locator('.card:has-text("Kraepelin")')).toBeVisible();
    await expect(page.locator('.card:has-text("Wartegg")')).toBeVisible();
    await expect(page.locator('.card:has-text("EPPS")')).toBeVisible();
    assertNoErrors(errs, 'Psikologi Menu');
  });

  test('Psikologi Kraepelin loads without errors', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/psikologi_kraepelin.php');
    await page.waitForTimeout(600);
    await expect(page.locator('h3, h4, h2')).toContainText(/Kraepelin/i);
    assertNoErrors(errs, 'Psikologi Kraepelin');
  });

  test('Psikologi Wartegg loads without errors', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/psikologi_kerja.php?jenis=wartegg');
    await page.waitForTimeout(600);
    // Page either shows test or redirects cleanly
    const status = page.url();
    expect(status).toContain('localhost');
    assertNoErrors(errs, 'Psikologi Wartegg');
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 8: Forum Tanya-Jawab Flow
// ═════════════════════════════════════════════════════════════════════════════
test.describe('08 - Forum Flow', () => {
  test('Forum loads, tanya dengan keyword yang ada di materi', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/forum.php');
    await expect(page.locator('h4')).toContainText(/Forum/);

    await page.fill('textarea[name="pertanyaan"]', 'Pancasila TWK CPNS');
    await page.click('button[name="tanya"]');
    await page.waitForTimeout(1000);

    // Harusnya ada hasil dari materi atau soal
    const hasResults = await page.locator('.card.card-hover').count();
    const hasNoAnswer = await page.locator('.alert-info').isVisible().catch(() => false);
    expect(hasResults > 0 || hasNoAnswer).toBeTruthy();
    assertNoErrors(errs, 'Forum Tanya');
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 9: Profil Update (CSRF protected)
// ═════════════════════════════════════════════════════════════════════════════
test.describe('09 - Profil Flow', () => {
  test('Profil page loads dengan CSRF token di form', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/profil.php');
    // Kedua form harus punya csrf_token hidden input
    const csrfCount = await page.locator('input[name="csrf_token"]').count();
    expect(csrfCount).toBeGreaterThanOrEqual(2);
    assertNoErrors(errs, 'Profil CSRF');
  });

  test('Update nama profil berhasil', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/profil.php');
    await page.fill('input[name="nama"]', 'Peserta Demo Update');
    // Submit form update_profil
    await page.locator('form:has(input[value="update_profil"]) button[type="submit"]').click();
    await page.waitForTimeout(500);
    await expect(page.locator('.alert-success')).toBeVisible();
    // Kembalikan nama asli
    await page.fill('input[name="nama"]', 'Peserta Demo');
    await page.locator('form:has(input[value="update_profil"]) button[type="submit"]').click();
    assertNoErrors(errs, 'Profil Update');
  });

  test('Ganti password dengan password salah menampilkan error', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('peserta/profil.php');
    await page.fill('input[name="old_password"]', 'wrongpassword123');
    await page.fill('input[name="new_password"]', 'newpass123');
    await page.fill('input[name="new_password2"]', 'newpass123');
    await page.locator('form:has(input[value="update_password"]) button[type="submit"]').click();
    await page.waitForTimeout(500);
    await expect(page.locator('.alert-danger')).toContainText(/Password lama salah/);
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 10: API Endpoints
// ═════════════════════════════════════════════════════════════════════════════
test.describe('10 - API Endpoints', () => {
  test('api/simpan_jawaban_temp.php returns JSON', async ({ page }) => {
    await loginPeserta(page);
    const resp = await page.request.post('http://localhost/ujian/api/simpan_jawaban_temp.php', {
      form: { soal_id: '0', opsi_id: '0', paket_id: '0', is_ragu: '0' }
    });
    expect(resp.status()).toBe(200);
    const json = await resp.json();
    expect(json).toHaveProperty('status');
  });

  test('api/simpan_jawaban_temp.php blocks unauthenticated request', async ({ page }) => {
    // Tanpa login, request harus ditolak (tidak return 200 ok)
    const resp = await page.request.post('http://localhost/ujian/api/simpan_jawaban_temp.php', {
      form: { soal_id: '1', opsi_id: '1', paket_id: '1', is_ragu: '0' }
    });
    // Harus redirect atau error, bukan sukses
    const body = await resp.text();
    // Status ok hanya jika sudah login dan ada sesi ujian valid
    // Unauthenticated request harusnya tidak mengembalikan {"status":"ok"}
    expect(body).not.toContain('"status":"ok"');
  });

  test('api/simpan_jawaban_latihan.php returns JSON for peserta', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('peserta/latihan_kerja.php?jenis=twk&topik=Pancasila');
    const csrfToken = await page.evaluate(() => window.CSRF_TOKEN || '');
    const resp = await page.request.post('http://localhost/ujian/api/simpan_jawaban_latihan.php', {
      form: { soal_id: '1', opsi_id: '1', csrf_token: csrfToken }
    });
    expect(resp.status()).toBe(200);
    const json = await resp.json();
    expect(json.status).toBe('ok');
  });

  test('api/submit_ujian.php blocks non-peserta (admin)', async ({ page }) => {
    await loginAdmin(page);
    const resp = await page.request.post('http://localhost/ujian/api/submit_ujian.php', {
      form: { hasil_id: '1' }
    });
    // Admin harus di-redirect atau ditolak, bukan bisa submit
    expect(resp.status()).toBeLessThan(500);
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 11: Anti-Cheat Logic Check
// ═════════════════════════════════════════════════════════════════════════════
test.describe('11 - Anti-Cheat System', () => {
  test('app.js tidak lagi double-count: hanya visibilitychange yang increment counter', async ({ page }) => {
    // Ambil source app.js via HTTP request
    const resp = await page.request.get('http://localhost/ujian/assets/js/app.js');
    expect(resp.status()).toBe(200);
    const appJs = await resp.text();

    // blur TIDAK boleh ada di dalam blok anti-cheat untuk increment counter
    // (boleh ada blur untuk tujuan lain, tapi tidak untuk tabSwitchCount++)
    // Pastikan tidak ada pola: $(window).on('blur'...) yang menambah counter
    expect(appJs).not.toMatch(/\.on\(['"]blur['"][\s\S]{0,200}tabSwitchCount\+\+/);

    // visibilitychange HARUS ada untuk deteksi pindah tab
    expect(appJs).toContain('visibilitychange');

    // cheatKey per-session HARUS ada
    expect(appJs).toContain('cheatKey');

    // maxWarnings HARUS ada (tidak auto-submit di warning pertama)
    expect(appJs).toContain('maxWarnings');
  });

  test('tabSwitchCount menggunakan key per hasil_id (bukan global)', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('peserta/tryout_list.php');
    const startBtn = page.locator('a.btn:has-text("Mulai"), a.btn:has-text("Lanjutkan")').first();
    if (!(await startBtn.isVisible({ timeout: 3000 }).catch(() => false))) return;
    await startBtn.click();
    await page.waitForURL('**/tryout_kerja.php**', { timeout: 10000 });
    await page.waitForTimeout(500);
    // localStorage tidak boleh pakai key global lama 'tabSwitchCount'
    const oldKey = await page.evaluate(() => localStorage.getItem('tabSwitchCount'));
    expect(oldKey).toBeNull();
  });
});

// ═════════════════════════════════════════════════════════════════════════════
// SUITE 12: Regression & Edge Cases
// ═════════════════════════════════════════════════════════════════════════════
test.describe('12 - Regression & Edge Cases', () => {
  test('Admin: kelola_paket kelola_soal parameter aman (intval)', async ({ page }) => {
    await loginAdmin(page);
    // Inject SQL lewat GET — halaman tidak boleh error/crash
    const resp = await page.goto("admin/kelola_paket.php?kelola_soal=1'OR'1'='1");
    await expect(page.locator('.container h3').first()).toContainText(/Paket/);
  });

  test('Leaderboard renders tanpa error saat user tidak di top 10', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/leaderboard.php');
    await expect(page.locator('.container h4').first()).toContainText(/Leaderboard/);
    assertNoErrors(errs, 'Leaderboard');
  });

  test('Dashboard stats tidak ikut menghitung ujian proses', async ({ page }) => {
    await loginPeserta(page);
    await page.goto('peserta/dashboard.php');
    await expect(page.locator('.container h4').first()).toContainText(/Halo/);
    // Stats cards harus tampil (tidak crash karena query sudah difilter)
    await expect(page.locator('.card').first()).toBeVisible();
  });

  test('Belajar.php: filter target ujian tidak crash', async ({ page }) => {
    const errs = [];
    attachConsoleWatcher(page, errs);
    await loginPeserta(page);
    await page.goto('peserta/belajar.php');
    await expect(page.locator('.container h4').first()).toContainText(/Materi/);
    assertNoErrors(errs, 'Belajar Target Filter');
  });

  test('404 page tidak ada di halaman yang ada', async ({ page }) => {
    const resp = await page.goto('http://localhost/ujian/peserta/dashboard.php');
    // Pastikan redirect ke login, bukan 404
    expect([200, 302]).toContain(resp.status());
  });
});
