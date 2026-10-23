# Todolist Sekolah Robotika: Roadmap Full System

> Dokumen kerja bersama. Dibaca dan di-update setiap selesai satu tahap.
> Kirim file yang dibutuhkan tidak dalam bentuk zip ataupun ditempel di chat.
> Status: **DRAFT v3: env lokal & halaman login (tampilan) selesai; menunggu jawaban pertanyaan bagian 11 sebelum Tahap C**

---

## 0. Cara Kerja Kita

1. Kita kerjakan **per tahap**, bukan sekaligus.
2. Sebelum tahap dimulai: pertanyaan `K` yang terkait harus sudah dijawab.
3. Setiap selesai satu tahap (atau sub-tahap):
   - centang item di todolist ini,
   - **kirim todolist yang sudah di-update**,
   - kirim **hanya file yang berubah/baru** (tanpa zip, tanpa menempel isi file di chat).
4. Setiap tahap ditutup dengan catatan deploy: perintah yang harus dijalankan (migrate, build, cache clear) dan apa yang perlu dicek di hosting.
5. Keputusan yang berubah dicatat di bagian **9. Keputusan** supaya tidak diulang.
6. Kata sandi/kunci rahasia (SMTP, Midtrans, Google OAuth) **tidak pernah** dikirim lewat chat atau ditulis di dokumen ini. Hanya disimpan di `.env` server.
7. **Perintah lokal memakai `docker-compose`, bukan `sail`.** Bentuknya `docker-compose exec -u sail laravel.test <perintah>` (contoh: `... php artisan migrate`, `... npm run build`, `... composer install`). Menyalakan/mematikan: `docker-compose up -d` / `docker-compose down`. Semua catatan deploy di dokumen ini ditulis dengan bentuk itu.

Legenda: `[ ]` belum, `[~]` sedang dikerjakan, `[x]` selesai. Ukuran: **S** kecil, **M** sedang, **L** besar, **XL** sangat besar.

---

## 1. Kondisi Saat Ini

**Stack:** Laravel 9, PHP 8.3, MySQL 8, Blade + Tailwind 3 + Vite, Docker (lokal), shared hosting (cPanel) di `sekolahrobotika.com`. Hasil build `public/build` di-commit, deploy tanpa build ulang di server.

**Sudah selesai**
- [x] Landing page: navbar, hero, statistik, tentang, jenjang, LMS (mockup), galeri, CTA, footer
- [x] Galeri marquee 2 baris + thumbnail otomatis (`GalleryService`)
- [x] Logo ringan otomatis (`LogoService`), skeleton + fade-in
- [x] Foto pemanis hero (3 slot, folder `public/images/hero`)
- [x] Ornamen (roda gigi, bintang, gelombang, chip melayang)
- [x] Footer lengkap (Instagram, WhatsApp, email)
- [x] README.md dan .gitignore
- [x] Perbaikan performa (HTML dari 42 MB menjadi ringan, lazy-load galeri)
- [x] Env lokal: Docker via `docker-compose` (image Laravel Sail, PHP 8.3, MySQL 8) + phpMyAdmin (port 8080) + Mailpit (inbox `localhost:8025`); typo `.env` `B_CONNECTION` sudah dibetulkan jadi `DB_CONNECTION`
- [x] Halaman `/login` (tampilan saja, tanpa database/migrasi): route `login`, `LoginController` placeholder, view `auth/login.blade.php`; semua tombol "Masuk" di landing tidak 404 lagi

**Belum ada / masih statis**
- Semua konten (statistik, jenjang, metode, LMS) masih array di `HomeController`
- `/login` baru **tampilan**: tombol Masuk hanya validasi lalu menampilkan pesan "Fitur masuk belum aktif" (autentikasi asli di Tahap C)
- Mockup dashboard LMS hanya HTML statis
- Belum ada database yang dipakai (belum ada migrasi dijalankan), autentikasi, admin panel, form kontak
- Statistik (1.300 peserta, 86 trainer, 48 kota) masih angka placeholder di kode

---

## 2. Tujuan Sistem

Mengubah website dari **brosur online** menjadi **platform pembelajaran** yang:

1. Menerima pendaftaran akun peserta dan pendaftaran kelas, termasuk pembayaran.
2. Mengelola peserta, trainer, kelas, jadwal, kehadiran, dan kurikulum.
3. Menyajikan materi berjenjang (level berurutan), mencatat progres, menilai, dan menerbitkan rapor serta sertifikat yang bisa diverifikasi publik.
4. Dikelola sehari-hari oleh admin lewat **panel admin buatan sendiri** (desain sesuai brand, tanpa plugin admin).

---

## 3. Analisis Kebutuhan

### 3.1 Aktor
| Aktor | Kebutuhan utama | Fase |
|---|---|---|
| Pengunjung | Lihat program, galeri, kontak, verifikasi sertifikat/rapor | 1 |
| Peserta (siswa) | Daftar akun, daftar & bayar kelas, belajar, tugas/kuis, lihat progres, unduh rapor & sertifikat | 1 |
| Trainer | Kelola materi, absensi, nilai tugas, beri umpan balik | 1 |
| Admin | Aktivasi akun, kelola peserta/kelas/kurikulum/konten, verifikasi pembayaran, laporan | 1 |
| Super Admin | Semua di atas + pengaturan sistem | 1 |
| Admin sekolah mitra | Kelola & daftarkan siswa dari sekolahnya | **2 (dikerjakan paling akhir)** |
| Orang tua | Lihat progres anak | **2 (dikerjakan paling akhir, tetap disiapkan di struktur)** |

> Admin per kota/cabang: belum perlu sekarang, kemungkinan nanti. Struktur data disiapkan tidak menutup kemungkinan itu.

### 3.2 Alur inti
```
Pengunjung → daftar akun
   ├─ via Google (OAuth), atau
   ├─ via email + verifikasi OTP (kirim dari no-reply@sekolahrobotika.com), atau
   └─ jalur tanpa email (lihat K18): username + nomor WA → menunggu aktivasi admin
→ (jika kesulitan) hubungi admin → admin aktivasi akun manual
→ peserta masuk → pilih kelas (cohort) sesuai jadwal buka/tutup pendaftaran (ada countdown)
→ pembayaran: (a) invoice di sistem + verifikasi manual admin, atau (b) Midtrans
→ belajar: bacaan / materi interaktif / video, level berurutan
→ tugas + kuis + pertemuan video conference (tautan Zoom eksternal)
→ memenuhi kehadiran minimum + nilai minimum (diatur per kursus)
→ rapor + sertifikat terbit (QR tanda tangan, verifikasi di halaman publik)
```

### 3.3 Struktur kurikulum
```
Jenjang (SD, SMP, SMA/SMK, PT)
  └─ Level (berurutan, terkunci sampai level sebelumnya selesai)
       └─ Kursus
            └─ Modul / Bab
                 └─ Materi (bacaan, interaktif, video, PDF, tautan) + Tugas + Kuis + Pertemuan video conference
```

### 3.4 Kebutuhan non-fungsional
- **Ringan di HP dan jaringan 4G** (sudah jadi prioritas, jangan mundur)
- **Skala puluhan ribu peserta**: lihat bagian 5 (Aturan Data Besar), wajib dipatuhi setiap tahap
- **Aman**: data pelajar, upload file, akses per peran, transaksi pembayaran
- **Mudah dirawat** di shared hosting
- **Backup** harian database dan file unggahan
- **UI/UX** berbahasa Indonesia, menarik, fun, modern; switch bahasa Inggris disiapkan, dikerjakan di akhir

---

## 4. Keputusan Teknis

| Area | Keputusan | Catatan |
|---|---|---|
| Framework | **Tetap Laravel 9** | Keputusan pemilik. Risiko dicatat di bagian 8; ada titik cek sebelum rilis publik (tahap Z) |
| Front-end | Blade + Tailwind + Vite; Alpine/JS ringan untuk bagian interaktif | Disepakati |
| Admin panel | **Dibuat manual** (Blade + Tailwind), tanpa Filament/plugin admin | CRUD sederhana lewat modal/pop-up AJAX; dibangun sebagai "UI kit" dulu (tahap D) |
| Autentikasi | **Dibuat manual** (tanpa Breeze): login, reset password, OTP email, Google OAuth | Google OAuth lewat Socialite (paket kecil, konfigurasi hanya Client ID/Secret/Redirect) |
| Hak akses | **Kolom `role` + middleware** (`role:admin,superadmin`) + **Policy** untuk kepemilikan data | Tanpa spatie; cukup untuk 6 peran. Policy memastikan trainer hanya melihat kelasnya, admin sekolah hanya sekolahnya, orang tua hanya anaknya |
| Email | SMTP `mail.sekolahrobotika.com`, port 465 (SSL/TLS), pengirim `no-reply@sekolahrobotika.com` | Perlu SPF/DKIM/DMARC dan cek batas kirim per jam (K19) |
| WhatsApp | Hanya tautan `wa.me` | OTP via WhatsApp tidak masuk rencana (tidak gratis secara resmi) |
| Video materi | Abstraksi `VideoProvider`; awalnya YouTube **unlisted** embed + pengaman ringan (lihat G4) | Tidak ada cara gratis yang 100% mencegah unduh/bagi tautan; opsi berbayar bertoken tersedia (K21) |
| File upload | Disk **privat** untuk tugas peserta; publik hanya aset konten | Validasi tipe & ukuran, nama file diacak |
| Sertifikat & rapor | PDF dari template desain pemilik + kode unik + QR + halaman verifikasi publik | Data publik dibatasi (K25) |
| Pembayaran | Invoice manual (verifikasi admin) lalu Midtrans | Invoice manual dikerjakan dulu, Midtrans menyusul |
| Antrean/jadwal | Queue `database` + cron `schedule:run` tiap menit | Aman saat banyak pengguna |
| Deploy | Build lokal → commit `public/build` → tarik di hosting | Berjalan |
| Test | Feature test untuk alur kritis (akses peran, pendaftaran, pembayaran, progres, sertifikat) | Wajib di tiap tahap yang menyentuh alur itu |
| Bahasa | Teks baru ditulis dengan `__('teks Indonesia')` sejak sekarang; terjemahan Inggris di akhir | Menghindari menulis ulang semua view nanti |

---

## 5. Aturan Data Besar (target puluhan ribu peserta)

Berlaku untuk **setiap** halaman daftar/laporan:

1. **Tidak pernah** memuat semua baris. Selalu paginasi di server (25/50 per halaman); untuk tabel sangat besar pakai `simplePaginate` atau `cursorPaginate`.
2. Pencarian & filter hanya pada kolom ber-**index**; hindari `LIKE '%kata%'` pada tabel besar (pakai awalan `kata%` atau kolom khusus).
3. Hindari N+1: `with()` untuk relasi, `select()` kolom yang dipakai saja.
4. Angka ringkasan dashboard (jumlah peserta, dll.) **di-cache** dan dihitung ulang berkala, bukan `COUNT(*)` tiap tampil.
5. Progres belajar: simpan **persen siap pakai** di `enrollments`, di-update saat ada event (materi selesai), bukan dihitung ulang dari jutaan baris.
6. `lesson_progress` memakai unique index `(user_id, lesson_id)` dan index pendukung.
7. Impor/ekspor (Excel/CSV), penerbitan sertifikat/rapor massal, dan email massal **lewat antrean**, dibaca/ditulis per potongan (chunk).
8. PDF sertifikat/rapor dibuat saat dibutuhkan lalu di-cache; penerbitan massal dipecah per batch.
9. Session, cache, dan queue memakai driver `database` (hindari puluhan ribu file kecil yang menghabiskan batas inode di cPanel).
10. Satu permintaan harus singkat: batas koneksi database per akun di shared hosting terbatas; tidak ada query berat di jalur login & halaman publik.
11. Setiap tahap yang membuat tabel/daftar baru wajib diuji dengan **data contoh besar** (seeder).

---

## 6. Rancangan Data Awal (draft, disempurnakan di tahap E)

Nama tabel/kolom bahasa Inggris (konvensi Laravel); tampilan UI Indonesia.

| Kelompok | Tabel |
|---|---|
| Pengguna | `users` (kolom `role`, `status`: pending/aktif/ditangguhkan), `student_profiles`, `trainer_profiles`, `otp_codes`, `social_accounts`, `consents` (versi kebijakan + waktu setuju) |
| Wilayah/institusi | `cities` (opsional), `schools` |
| Relasi fase 2 | `school_admins`, `parent_student` |
| Kurikulum | `levels` (jenjang/level), `courses`, `modules`, `lessons` (tipe: teks/pdf/video/interaktif/tautan) |
| Kelas | `cohorts` (kuota, mode, buka/tutup pendaftaran, tautan Zoom), `cohort_sessions` (pertemuan, mode online/offline), `enrollments` (status, persen progres) |
| Belajar | `lesson_progress`, `assignments`, `submissions`, `quizzes`, `questions`, `options`, `quiz_attempts` |
| Kehadiran | `attendances` |
| Hasil | `report_cards`, `certificates`, `certificate_signers` (penandatangan) |
| Pembayaran | `invoices`, `payments`, `payment_proofs`, `vouchers` (jika dipakai) |
| Operasional | `announcements`, `activity_logs` |
| Website/CMS | `leads`, `settings` (statistik, kontak), `gallery_photos`, `testimonials`, `faqs` |

Prinsip: data pribadi seminimal mungkin; siswa anak tidak wajib punya email pribadi (K18).

---

## 7. Roadmap per Tahap

Jalur utama: **A → C → D → E → F → G → H → I → J → Z (rilis publik)**.
Tahap **B** bisa paralel kapan saja. Fase 2 setelah rilis: **S** (admin sekolah) → **O** (orang tua) → **T** (bahasa Inggris).

### Tahap A: Fondasi & Kesiapan Produksi · ukuran L
Prasyarat: K29 (boleh sambil jalan)
- [ ] A1. Audit hosting: ekstensi PHP (GD, mbstring, zip, intl), kuota disk **dan inode**, batas koneksi MySQL, batas upload/memori/waktu eksekusi, batas kirim email per jam
- [ ] A2. Kunci versi paket yang kompatibel dengan Laravel 9 + PHP 8.3; bersihkan peringatan deprecated
- [ ] A3. Konfigurasi produksi: `.env.example` lengkap, `APP_DEBUG=false`, timezone `Asia/Jakarta`, locale `id`, session/cache/queue → `database`
- [ ] A4. Email: SMTP `no-reply@sekolahrobotika.com` (port 465), tes kirim, SPF/DKIM/DMARC; kata sandi hanya di `.env` server
- [ ] A5. Pindahkan data statis `HomeController` ke struktur siap-DB (`settings`), tampilan tidak berubah
- [ ] A6. Logging harian, halaman error 404/419/429/500 sesuai brand
- [ ] A7. Backup otomatis (DB + `storage/` + `public/images`) dan uji restore sekali
- [ ] A8. Keamanan dasar: HTTPS paksa, header keamanan, `.env`/`storage` tidak terakses publik, rate limiter
- [ ] A9. Cron `schedule:run` + worker antrean (`queue:work --stop-when-empty` tiap menit), cek berjalan di hosting
- [ ] A10. Dokumentasikan alur rilis & rollback di README
- [ ] A11. Mulai gaya penulisan teks `__()` untuk semua view baru

### Tahap B: Landing & Kontak · ukuran M (paralel)
- [ ] B1. Form **Hubungi Kami** (nama, kontak, jenjang, pesan) → `leads` + tautan WhatsApp (untuk yang kesulitan mendaftar)
- [ ] B2. Anti-spam: honeypot + rate limit
- [ ] B3. Halaman detail per jenjang
- [ ] B4. FAQ, testimoni, mitra/sekolah (jika ada data)
- [ ] B5. SEO dasar: judul/deskripsi, Open Graph, `sitemap.xml`, `robots.txt`
- [ ] B6. Halaman Kebijakan Privasi & Syarat/Ketentuan (diberi nomor versi; dipakai untuk centang persetujuan di C2)
- [ ] B7. Statistik dari `settings` (diisi manual lewat CMS di tahap D)

### Tahap C: Autentikasi & Hak Akses · ukuran L
Prasyarat: A selesai; K17, K18, K19, K30
- [~] C1. Login/logout manual, lupa/reset password via email
  - [x] Tampilan halaman login (tanpa DB)
  - [ ] `Auth::attempt` + throttle, logout, lupa/reset password (ganti placeholder `LoginController@store`; tambahkan link "Lupa kata sandi" & "Daftar" di halaman login setelah route-nya ada, supaya tidak 404)
- [ ] C2. Registrasi peserta + **centang persetujuan** (syarat/privasi, pernyataan izin orang tua); versi & waktu tersimpan di `consents`
- [ ] C3. Verifikasi email dengan **OTP** (kode di-hash, kedaluwarsa singkat, batas percobaan & kirim ulang)
- [ ] C4. Daftar/masuk dengan **Google** (Socialite); gabungkan dengan akun email yang sama
- [ ] C5. Jalur tanpa email sesuai K18 (username + nomor WA, status "menunggu aktivasi")
- [ ] C6. Aktivasi manual oleh admin (status pending/aktif/ditangguhkan) + antrean "menunggu aktivasi"
- [ ] C7. Peran + middleware `role:` + Policy kepemilikan data; peran orang tua & admin sekolah disiapkan di struktur, fiturnya fase 2
- [ ] C8. Redirect per peran; tombol "Masuk" di landing ke login nyata (link ke `/login` sudah terpasang, tinggal login aslinya)
- [ ] C9. Pembatasan percobaan login, sesi aman, ganti password, ekspor/hapus data akun (dasar hak pemilik data)
- [ ] C10. Seeder akun super admin awal
- [ ] C11. Feature test: akses per peran, OTP, aktivasi, Google callback

### Tahap D: Admin UI Kit & CMS · ukuran XL
Prasyarat: C selesai
- [ ] D1. Layout admin (sidebar, topbar, responsif, tema sesuai brand)
- [ ] D2. Komponen dasar: tabel server-side (paginasi, cari, filter, urut), modal form AJAX (tambah/ubah/hapus), toast, konfirmasi hapus, validasi inline
- [ ] D3. Pola CRUD generik (base controller/trait + konfigurasi) supaya modul berikutnya cepat dibuat
- [ ] D4. Manajemen pengguna dasar (daftar, filter status, aktivasi, reset password)
- [ ] D5. CMS konten: statistik (isi manual), kontak, jenjang, metode, FAQ, testimoni
- [ ] D6. Galeri & foto hero dari admin (upload → thumbnail otomatis, urutan)
- [ ] D7. Kelola `leads` (baru/dihubungi/selesai, ekspor)
- [ ] D8. Landing membaca konten dari DB dengan cache
- [ ] D9. Log aktivitas admin
- [ ] D10. Uji tampilan di HP

### Tahap E: Data Akademik · ukuran L
Prasyarat: D selesai; K20, K26, K30
- [ ] E1. Master: sekolah mitra (dasar), kota (opsional)
- [ ] E2. Kurikulum: jenjang → level → kursus → modul → materi (urutan drag); tipe materi: bacaan, interaktif, video, PDF, tautan
- [ ] E3. Pengaturan per kursus: **kehadiran minimum (%)**, **nilai minimum**, aturan level berurutan
- [ ] E4. Trainer (profil, penugasan), peserta (impor Excel/CSV lewat antrean, chunk)
- [ ] E5. Kelas/batch (cohort): kursus, trainer, mode (online/offline/hybrid), kuota, periode, **jadwal buka & tutup pendaftaran**, tautan Zoom
- [ ] E6. Seeder data besar (mis. 20.000 peserta) dan uji kecepatan daftar
- [ ] E7. Index database untuk daftar & pencarian

### Tahap F: Pendaftaran Kelas & Pembayaran · ukuran L
Prasyarat: E selesai; K27, K28
Urutan kerja: invoice manual dulu, Midtrans menyusul.
- [ ] F1. Daftar kelas: cek jendela pendaftaran, kuota, prasyarat level
- [ ] F2. **Countdown** buka/tutup pendaftaran (acuan waktu server) di landing & halaman kelas
- [ ] F3. Invoice di sistem (nomor unik, nominal, status); peserta unggah bukti, **admin verifikasi manual**
- [ ] F4. Status bayar → aktivasi enrollment (akses kelas)
- [ ] F5. Rekap & laporan pembayaran admin (ekspor lewat antrean)
- [ ] F6. **Midtrans**: buat transaksi, halaman bayar, **webhook** (verifikasi signature, idempoten, dikecualikan dari CSRF), cek status ulang, sandbox → produksi
- [ ] F7. Voucher/diskon (hanya jika K27 ya)
- [ ] F8. Feature test: webhook palsu ditolak, bayar dua kali tidak ganda, akses terbuka hanya setelah lunas

### Tahap G: LMS Peserta · ukuran XL
Prasyarat: F selesai; K20, K21, K26
- [ ] G1. Dashboard peserta nyata (ganti mockup): kelas aktif, progres, pengumuman, jadwal
- [ ] G2. **Peta level berurutan** (terkunci/terbuka) dengan efek buka kunci, animasi ringan, lencana/poin (tidak boleh memberatkan HP)
- [ ] G3. Halaman materi: bacaan, PDF, tautan; tombol "Tandai selesai"
- [ ] G4. Pemutar video aman (`VideoProvider`): YouTube unlisted + muat saat diklik (facade) + ID video hanya dikirim jika peserta terdaftar + watermark nama peserta; dapat diganti layanan berbayar tanpa menulis ulang
- [ ] G5. Materi interaktif sesuai K20 (iframe ber-sandbox)
- [ ] G6. Progres: `lesson_progress` + persen tersimpan di `enrollments`
- [ ] G7. Tugas proyek: unggah foto/video/berkas (disk privat, batas tipe & ukuran)
- [ ] G8. Kuis otomatis (acak soal, batas percobaan, skor)
- [ ] G9. Tautan video conference (Zoom) tampil pada pertemuan terkait
- [ ] G10. HP-first, uji di jaringan lambat
- [ ] G11. Feature test: kunci level, progres, upload aman, video hanya untuk yang terdaftar

### Tahap H: Jadwal, Absensi & Komunikasi · ukuran M
Prasyarat: G (boleh paralel sebagian); K23
- [ ] H1. Pertemuan (`cohort_sessions`) dengan mode online/offline yang **bisa diubah kapan saja** + email pemberitahuan perubahan
- [ ] H2. Absensi sesuai K23 + rekap persen kehadiran per peserta
- [ ] H3. Pengumuman per kelas
- [ ] H4. Email transaksional lewat antrean: OTP, akun aktif, invoice, pembayaran, perubahan jadwal, sertifikat terbit
- [ ] H5. Kalender jadwal untuk peserta & trainer

### Tahap I: Penilaian, Rapor & Sertifikat · ukuran L
Prasyarat: G, H selesai; K24, K25
> **Saat tahap ini dimulai, minta pemilik mengirim:** file desain rapor, file desain sertifikat, serta data penandatangan A (rapor) dan B, C (sertifikat): nama, jabatan, perusahaan.
- [ ] I1. Penilaian tugas oleh trainer (nilai + komentar), daftar menunggu dinilai
- [ ] I2. Aturan kelulusan per kursus: kehadiran minimum + nilai minimum
- [ ] I3. **Rapor** PDF dari template pemilik; 1 penandatangan (A)
- [ ] I4. **Sertifikat** PDF dari template pemilik; 2 penandatangan (B, C)
- [ ] I5. QR tanda tangan per penandatangan dengan pernyataan: "Dokumen ini telah sah ditandatangani dan disetujui secara elektronik oleh: Nama, Jabatan, Perusahaan ..." (bentuk akhir sesuai K24)
- [ ] I6. Halaman publik `/verifikasi/{kode}`: data minimal, tanpa email/telepon, tanpa nilai rinci, kode acak panjang, rate limit
- [ ] I7. Penerbitan massal lewat antrean per kelas (PDF di-cache)
- [ ] I8. Admin: terbitkan ulang / cabut dokumen (status tampil di halaman verifikasi)
- [ ] I9. Feature test: sertifikat hanya terbit jika memenuhi syarat; kode tidak bisa ditebak

### Tahap J: Laporan & Dashboard · ukuran M
- [ ] J1. Dashboard ringkasan admin (data di-cache)
- [ ] J2. Laporan: peserta aktif, progres per kelas, kehadiran, kelulusan, pembayaran
- [ ] J3. Ekspor Excel lewat antrean
- [ ] J4. Statistik landing otomatis dari DB (pilihan, bila nanti diinginkan; sekarang manual lewat CMS)

### Tahap Z: Hardening & Rilis Publik · ukuran M
- [ ] Z1. Tinjau keamanan: hak akses tiap route, validasi upload, rate limit, `APP_DEBUG`, header
- [ ] Z2. **Titik cek versi framework**: nilai ulang risiko Laravel 9 sebelum menerima data pelajar & pembayaran publik (lihat bagian 8)
- [ ] Z3. Uji beban dengan data contoh puluhan ribu; audit kecepatan di HP
- [ ] Z4. Uji restore backup
- [ ] Z5. Panduan singkat untuk admin & trainer
- [ ] Z6. Rilis bertahap: uji internal → pilot satu kelas → buka luas
- [ ] Z7. Evaluasi ambang pindah ke VPS jika pengguna bertambah

### Fase 2 (setelah rilis)
**Tahap S: Admin Sekolah**
- [ ] S1. Peran admin sekolah + Policy per sekolah
- [ ] S2. Daftar siswa sekolahnya, daftarkan siswa (satu per satu & impor), aktivasi
- [ ] S3. Pantau progres/kehadiran siswa sekolahnya, tagihan sekolah (jika ada)

**Tahap O: Orang Tua**
- [ ] O1. Hubungkan akun orang tua ↔ anak (persetujuan/kode)
- [ ] O2. Lihat progres, kehadiran, rapor anak

**Tahap T: Bahasa Inggris**
- [ ] T1. Terjemahan (`lang/en.json`) dan switch bahasa
- [ ] T2. Konten dinamis (CMS) dua bahasa: keputusan lingkup dulu

---

## 8. Risiko & Catatan

| Risiko | Dampak | Mitigasi |
|---|---|---|
| **Laravel 9 sudah tidak menerima update keamanan** (keputusan tetap di 9) | Celah keamanan baru tidak ditambal; paket baru bisa menolak versi lama; PHP 8.3 bukan target resminya | Kunci versi paket, perbarui dependensi yang masih mendukung L9, uji di PHP 8.3, titik cek **Z2** sebelum rilis publik. Upgrade paling murah dilakukan sekarang saat kode masih kecil, makin mahal setelah puluhan tabel |
| Batas email per jam di shared hosting | OTP/notifikasi tertunda saat pendaftaran ramai | Cek batas (K19); siapkan opsi layanan email transaksional; batasi kirim ulang OTP |
| Email masuk spam | Peserta tidak menerima OTP | SPF/DKIM/DMARC, konten sederhana, uji ke Gmail/Yahoo |
| Video bocor/diunduh | Konten dibagikan di luar | Tidak ada pencegahan gratis 100%; pengaman berlapis (G4) dan opsi layanan bertoken (K21) |
| QR berisi teks statis bisa dipalsukan | Dokumen palsu tampak sah | QR mengarah ke URL verifikasi di sistem (K24) |
| Tabel progres membesar (jutaan baris) | Halaman lambat | Aturan Data Besar (bagian 5) |
| Batas koneksi DB / inode / memori di shared hosting | Error saat ramai, impor/PDF gagal | Request singkat, driver `database`, proses berat via antrean, audit di A1 |
| Webhook Midtrans palsu / ganda | Akses gratis atau tagihan ganda | Verifikasi signature, idempoten, uji (F8) |
| Data pelajar/anak | Risiko privasi & kepatuhan | Data minimal, persetujuan saat daftar, akses per peran, kebijakan privasi, penanggung jawab data: pimpinan Sekolah Robotika |
| Cakupan sangat luas | Tidak selesai | Kerjakan per tahap; fase 2 sengaja ditunda |
| Ketergantungan pada satu orang | Sulit dirawat | Dokumentasi di README dan todolist ini |

---

## 9. Keputusan

| # | Keputusan | Status |
|---|---|---|
| D1 | Tetap Laravel 9; titik cek ulang di tahap Z (Z2) | Disepakati (risiko dicatat) |
| D2 | Admin panel dibuat manual (Blade + Tailwind, modal AJAX), tanpa plugin | Disepakati |
| D3 | Autentikasi manual + Google OAuth + OTP email | Disepakati |
| D4 | Hak akses: kolom `role` + middleware + Policy (tanpa spatie) | Disepakati ("terbaik & mudah") |
| D5 | Email dari `no-reply@sekolahrobotika.com` via SMTP hosting | Disepakati |
| D6 | WhatsApp: hanya tautan; otomatisasi hanya jika gratis | Disepakati |
| D7 | Video: abstraksi penyedia, mulai YouTube unlisted + pengaman ringan | Diusulkan (menunggu K21) |
| D8 | Rapor & sertifikat PDF + QR + verifikasi publik, data minimal | Disepakati arah; detail QR menunggu K24, K25 |
| D9 | Pembayaran: invoice manual lalu Midtrans | Disepakati |
| D10 | Queue `database` + cron | Disepakati |
| D11 | Deploy model commit `public/build` | Berjalan |
| D12 | Tabel/kolom bahasa Inggris, UI Indonesia | Asumsi (tidak ada keberatan) |
| D13 | Admin sekolah & orang tua dikerjakan paling akhir (fase 2) | Disepakati |
| D14 | Bahasa Inggris dikerjakan akhir; `__()` dipakai sejak sekarang | Disepakati arah |
| D15 | Aturan Data Besar (bagian 5) wajib di setiap tahap | Diusulkan |
| D16 | Level berurutan (terkunci) dengan tampilan fun | Disepakati |
| D17 | Env lokal memakai image Laravel Sail + phpMyAdmin + Mailpit (Mailpit menampung email OTP/reset saat dev) | Disepakati |
| D18 | Halaman login dibuat dulu sebagai tampilan tanpa DB; session/cache tetap `file` selama belum ada migrasi | Disepakati (sementara, sampai Tahap C/A3) |
| D19 | Perintah lokal selalu lewat `docker-compose exec -u sail laravel.test ...`, bukan `sail` | Disepakati |

---

## 10. Jawaban Pertanyaan v1 (ringkasan)

| # | Pertanyaan | Jawaban |
|---|---|---|
| K1 | Pengguna | Peserta individu; sekolah (paling akhir, admin sekolah bisa daftarkan siswa); orang tua (opsional, paling akhir) |
| K2 | Peran | superadmin, admin, trainer, peserta, orang tua, admin sekolah; admin per kota nanti |
| K3 | Cara belajar | Hybrid; jadwal fleksibel (daring ↔ luring bisa berubah); perlu absensi |
| K4 | Akun | Daftar sendiri; admin sekolah bisa mendaftarkan siswa nanti |
| K5 | Materi | Sudah ada dalam semua bentuk; diunggah superadmin, admin, trainer |
| K6 | Urutan level | Harus berurutan, tampilan fun dengan efek |
| K7 | Penilaian | Kuis otomatis + tugas dinilai trainer; rapor di akhir; lulus = kehadiran minimum N% + nilai minimum (diatur per kursus) |
| K8 | Sertifikat | Desain rapor & sertifikat sudah ada (dikirim saat tahap I); rapor 1 penandatangan (A), sertifikat 2 (B, C); QR berisi pernyataan tanda tangan elektronik |
| K9 | Pembayaran | Invoice manual (verifikasi admin) dan Midtrans |
| K10 | Notifikasi | Email saja |
| K11 | Hosting | Tetap shared hosting; ada SSH/Composer, cron, PHP 8.3 |
| K12 | Persetujuan data anak | Centang persetujuan saat pendaftaran; penanggung jawab: pimpinan Sekolah Robotika |
| K13 | Pengelola & skala | Admin biasa; puluhan ribu peserta; perhatikan cara memanggil data |
| K14 | Bahasa | Disiapkan switch, dikerjakan akhir |
| K15 | Tenggat/acara | Ada jadwal buka-tutup pendaftaran kelas, perlu countdown |
| K16 | Statistik | Manual lewat CMS dulu; nanti mungkin dari DB |

---

## 11. Pertanyaan Baru

⭐ = menentukan arah tahap berikutnya. Tahap **A dan B bisa dimulai sekarang** tanpa menunggu jawaban di bawah.

**Pendaftaran & akun (blokir tahap C)**
- ⭐ **K17.** Setelah daftar via Google atau OTP email, akun **langsung aktif**, atau tetap menunggu **aktivasi admin**? (alur v1 menyebut aktivasi manual: apakah untuk semua, atau hanya jalur bantuan?)
- ⭐ **K18.** Siswa yang belum punya email (mis. SD): boleh daftar dengan **username + nomor WA**, status "menunggu aktivasi", lalu admin menghubungi via WA dan mengaktifkan manual? Atau wajib memakai email (milik orang tua)? Usul saya: dua jalur sekaligus.
- **K19.** Berapa **batas kirim email per jam** di hosting (cek cPanel/tanya penyedia)? Jika terlalu kecil untuk pendaftaran ramai, apakah boleh memakai layanan email transaksional (ada tier gratis, mis. beberapa ratus email/hari)?
- **K30.** Apakah satu akun boleh punya **lebih dari satu peran** (mis. trainer sekaligus admin)? Usul: satu peran per akun dulu, supaya sederhana.

**Konten & video (blokir tahap E, G)**
- ⭐ **K20.** "Materi interaktif" itu bentuknya apa? (mis. H5P, embed Scratch/Tinkercad/Wokwi, simulasi HTML buatan sendiri, kuis/latihan di halaman)
- ⭐ **K21.** Soal keamanan video: YouTube **tidak bisa** dicegah 100% dari dibuka/diunduh walau unlisted (ID video terlihat oleh peserta yang login). Usul: mulai dari YouTube unlisted + pengaman ringan (muat saat diklik, ID hanya untuk peserta terdaftar, watermark nama). Kalau konten sangat berharga, ada layanan berbayar dengan akses bertoken. Mau mulai dari yang gratis, atau sudah ada anggaran? Perkiraan jumlah & durasi video?
- **K26.** Aturan buka level berikutnya: setelah level sebelumnya **100% selesai**, atau juga harus **lulus nilai minimum**? Kuis boleh diulang? Level baru terbuka otomatis atau perlu persetujuan trainer?

**Pertemuan & kehadiran**
- **K22.** Tautan Zoom: usul disimpan **per pertemuan** (karena jadwal bisa berubah), dengan nilai awal dari kursus. Setuju?
- **K23.** Absensi: **diinput trainer** manual, atau peserta **check-in** (klik/kode) lalu dikonfirmasi trainer? Persen kehadiran dihitung dari semua pertemuan?

**Rapor & sertifikat (sebelum tahap I)**
- ⭐ **K24.** QR yang isinya **teks pernyataan statis** bisa dibuat siapa saja, jadi tidak membuktikan keaslian. Usul: QR membuka URL `/verifikasi/{kode}` di sistem yang menampilkan pernyataan itu **dan** status dokumen (sah/dicabut). Setuju? (nama, jabatan, perusahaan penandatangan diisi dari data penandatangan)
- **K25.** Data yang boleh tampil di halaman verifikasi publik: usul **nama (penuh atau disamarkan?)**, kursus, tanggal terbit, status; untuk rapor **tanpa nilai rinci**. Setuju?

**Pembayaran & kelas (sebelum tahap F)**
- **K27.** Harga per kursus, per kelas, atau per level? Ada diskon/voucher dan kebijakan refund? Sudah punya akun Midtrans (merchant disetujui)? Metode apa yang dibuka (VA, QRIS, e-wallet)?
- **K28.** Kelas punya **kuota** peserta? Ada daftar tunggu? Pendaftaran ditutup otomatis saat kuota penuh atau saat tanggal tutup?

**Infrastruktur**
- **K29.** Kuota disk, **batas inode**, dan **batas koneksi MySQL** di paket hosting? (menentukan seberapa ketat aturan bagian 5)
- **K31.** Saat daftar, perlu mengumpulkan **nomor WA orang tua** (opsional) untuk dihubungkan ke akun orang tua di fase 2?

---

## 12. Usulan Urutan Pertama

1. **Tahap A** (fondasi): audit hosting (A1), email SMTP (A4), konfigurasi produksi, backup, cron.
2. **Tahap B** paralel kecil: form Hubungi Kami, halaman Kebijakan Privasi & S&K (dibutuhkan untuk centang persetujuan di tahap C).
3. Jawab **K17, K18, K19, K30** sebelum **Tahap C**.
4. Lalu **C → D → E → F → G → H → I → J → Z**.

---

## 13. Catatan Update

| Tanggal | Perubahan |
|---|---|
| 2026-10-23 | Draft v1 dibuat |
| 2026-10-23 | Draft v2: jawaban K1–K16 dimasukkan; tetap Laravel 9, admin & auth manual, Google OAuth + OTP email, pembayaran dimasukkan ke jalur utama (tahap F), admin sekolah/orang tua dipindah ke fase 2, ditambah Aturan Data Besar, tahap D menjadi Admin UI Kit, pertanyaan baru K17–K31; penomoran tahap berubah (B tetap, pembayaran menjadi F, LMS G, absensi H, penilaian I, laporan J, hardening Z) |
| 2026-10-23 | Draft v3: env lokal (Docker + phpMyAdmin + Mailpit) dan halaman `/login` (tampilan saja) selesai; C1 jadi `[~]`; D17-D18 dicatat. **Catatan deploy:** tiap menambah view/class Tailwind baru jalankan `docker-compose exec -u sail laravel.test npm run build` (atau `... npm run dev` saat mengembangkan), lalu commit `public/build`; tanpa itu tampilan baru berantakan. Tidak ada migrasi yang perlu dijalankan |
| 2026-10-23 | Perintah lokal dipastikan memakai `docker-compose` (bukan `sail`): aturan baru di bagian 0 (poin 7), keputusan D19, dan contoh perintah di catatan deploy diganti |