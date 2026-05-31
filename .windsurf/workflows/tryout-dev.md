---
description: TryOutKu Development Workflow
---

# TryOutKu - Bimbel Online Try-Out Development Workflow

Aplikasi try-out online untuk CPNS, Kedinasan, dan PPPK. Dibangun dengan PHP Native + Bootstrap 5 + jQuery. Mobile-first PWA.

## Project Structure

```
c:\xampp\htdocs\ujian/
├── config.php              # DB config, constants, DEV_MODE
├── index.php               # Landing page
├── login.php / register.php
├── manifest.json / sw.js   # PWA assets
├── admin/                  # Admin panel (CRUD, reports)
├── peserta/                # Participant pages (exam, learning)
├── api/                    # AJAX endpoints
├── includes/               # header, footer, navbar, functions, db
├── assets/                 # CSS, JS, icons
├── database/               # db_tryout.sql (schema)
├── db_seed.sql             # Seed data (admin, demo soal)
├── setup_db.php            # Auto setup script
└── tests/                  # Playwright E2E tests
```

## Development Mode

`config.php` has `DEV_MODE` flag. When `true`:
- Service Worker is **unregistered** (no cache issues)
- Easy to test CSS/JS changes without hard refresh
- Set to `false` for production to enable PWA caching

## Key Database Tables

| Table | Purpose |
|-------|---------|
| `users` | Admin & peserta accounts |
| `soal` | Questions (TWK, TIU, TKP, psikologi) |
| `opsi_jawaban` | Answer options with bobot & kunci |
| `materi` | Learning materials & flashcards |
| `paket_ujian` | Exam packages |
| `paket_soal` | Many-to-many: soal in package |
| `hasil_ujian` | Exam results (status: proses/lulus/gugur) |
| `detail_jawaban` | Per-question answers during exam |
| `rekomendasi_belajar` | Post-exam learning recommendations |
| `tanya_admin` | Forum Q&A |
| `catatan_pengajar` | Teacher notes per student |
| `kategori_ujian` | Exam categories (CPNS, STAN, etc.) |

## Exam Flow (Try-Out)

1. Peserta clicks package on `tryout_list.php`
2. `tryout_kerja.php` creates `hasil_ujian` (status=proses) + `detail_jawaban` rows
3. One question per page. Answer click → AJAX to `api/simpan_jawaban_temp.php`
4. Timer runs client-side (`startTimer()` in app.js), backed by `localStorage`
5. Auto-navigate to next question after answer saved (400ms delay)
6. Submit → `api/submit_ujian.php` calculates scores → redirect to `tryout_hasil.php`

## Critical Code Patterns

### Body Class for Exam Mode
Exam pages set `$bodyClass = 'bg-light mode-ujian';` before including header. This triggers:
- Anti-cheat (no right-click, tab switch detection, no copy/paste)
- Swipe gesture navigation on mobile

### AJAX Answer Save
```javascript
// app.js: on opsi-jawaban click
$.post(BASE_URL + 'api/simpan_jawaban_temp.php', {
    soal_id, opsi_id, paket_id, is_ragu
}, function(res) {
    updateNavigasiColor(soalId, status);
    // auto-redirect to next question
});
```

### Timer Key
Timer uses `localStorage` key `timer_paket_{paket_id}` so each exam has its own timer.

## Mobile-First Checklist

When modifying any peserta page:
- [ ] Bottom nav present (`<nav class="bottom-nav-mobile">`)
- [ ] Offcanvas navbar working (click hamburger on mobile)
- [ ] Inputs font-size >= 16px (prevents iOS zoom)
- [ ] Touch targets >= 44px height
- [ ] No horizontal scroll on 320px viewport
- [ ] Swipe hint visible only on mobile (`<div class="swipe-hint">`)

## Testing

### E2E Tests (Playwright)
```bash
cd tests
npx playwright test
```

### Manual Testing Checklist
1. **Auth**: Register → Login → Logout for both roles
2. **Exam**: Start try-out → answer questions → submit → view result
3. **Mobile**: Chrome DevTools mobile view (iPhone SE / Galaxy S8)
4. **Console**: No JS errors, all network requests 200
5. **Timer**: Refresh mid-exam, timer should resume (not reset)
6. **Navigasi Soal**: Colors update after each answer (belum/dijawab/ragu)

## Common Issues & Fixes

| Symptom | Cause | Fix |
|---------|-------|-----|
| Timer resets to 00:00 on reload | `localStorage` key collision | Use per-paket key |
| Navigasi soal warna tidak update | Bootstrap `.active` overrides custom class | Use `!important` + higher specificity |
| SQL error "Reference not supported" | Aggregate in ORDER BY with strict mode | Wrap in subquery |
| Soal psikologi redirect terus | `< 1 soal` triggers flash+redirect | Show "Dalam Persiapan" UI instead |
| CSS/JS tidak update | Service Worker cache | Set `DEV_MODE = true` |
| Duplicate `<body>` tag | Exam pages had inline `<body>` | Use `$bodyClass` variable before header include |

## Deployment Notes

1. Set `DEV_MODE = false` in `config.php`
2. Run `setup_db.php` (CLI or browser) for fresh install
3. Default admin: `admin@tryoutku.com` / `password`
4. Default peserta: `peserta_demo@tryoutku.com` / `password`
5. Ensure `uploads/` directory is writable
6. Set `display_errors = 0` in production

## File Editing Conventions

- Use `BASE_URL` constant for all links/assets
- Use `e()` (htmlspecialchars) for all output
- Use prepared statements for all DB queries
- Use `flash()` + `redirect()` pattern for POST-redirect-GET
- Use `$pageTitle` before including `header.php`
- Use `$bodyClass` for custom body classes (exam mode, etc.)
