<div align="center">

# 🤖 Sekolah Robotika

**Website Program Sekolah Robotika Berjenjang: dari SD hingga Perguruan Tinggi**

Robotika • Coding • Artificial Intelligence

![Laravel](https://img.shields.io/badge/Laravel-9-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-build-646CFF?logo=vite&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Sail--style-2496ED?logo=docker&logoColor=white)

</div>

<!-- Tambahkan screenshot di sini, contoh:
<p align="center"><img src="docs/screenshot.png" alt="Landing page Sekolah Robotika" width="800"></p>
-->

---

## 📖 Tentang Project

**Sekolah Robotika** adalah program pendidikan teknologi yang aplikatif dan berkelanjutan, mendukung penguatan pembelajaran koding, Artificial Intelligence, Computational Thinking, dan STEM. Program ini dikembangkan oleh **Decaa.id** melalui DECAA Training Centre bersama **Komunitas Peduli Digital (KPD)**.

Repository ini berisi website Sekolah Robotika. Saat ini yang tersedia baru **landing page (halaman depan)**. Login/LMS dan admin panel masih dalam roadmap.

## ✨ Fitur

- 🎨 **Landing page responsif** dengan Tailwind CSS 3 dan warna brand khusus
- 📊 **Statistik animasi**: angka naik otomatis saat di-scroll
- 🎓 **Jenjang pembelajaran**: SD/MI, SMP/MTs, SMA/SMK, dan Perguruan Tinggi
- 🧩 **4 metode belajar**: Learning by Doing, Project-Based, Challenge-Based, dan Collaborative Learning
- 💻 **Preview sistem belajar (LMS)** berupa mockup dashboard peserta
- 🖼️ **Galeri marquee 2 baris** (atas ke kanan, bawah ke kiri) dengan:
  - thumbnail otomatis (webp, atau jpg jika webp tidak tersedia)
  - placeholder blur inline (base64) dan efek shimmer
  - foto fade-in setelah selesai dimuat
  - ikon kamera sebagai pengganti jika foto belum ada
- 🔄 **Konten berbasis array** di controller, siap diganti query database saat admin panel dibuat

## 🧱 Tech Stack

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 9, PHP 8.3 |
| Database | MySQL 8.0 (belum dipakai halaman depan) |
| Frontend | Blade, Tailwind CSS 3, JavaScript biasa |
| Build tool | Vite |
| Environment | Docker (gaya Laravel Sail), phpMyAdmin di port 8080 |

## 🚀 Memulai

### Prasyarat

- Docker dan Docker Compose
- Git

### Instalasi

```bash
# 1. Clone repository
git clone <url-repository-kamu>
cd <nama-folder>

# 2. Siapkan environment
cp .env.example .env
# Pastikan .env berisi WWWUSER, WWWGROUP, dan konfigurasi DB_* (host: mysql)

# 3. Nyalakan container
docker-compose up -d

# 4. Install dependensi
docker-compose exec laravel.test composer install
docker-compose exec laravel.test npm install

# 5. Generate app key
docker-compose exec laravel.test php artisan key:generate

# 6. Jalankan Vite (biarkan terminal tetap terbuka)
docker-compose exec laravel.test npm run dev

# 7. Jalankan migrasi (jika sudah ada migrasi yang dipakai)
docker-compose exec laravel.test php artisan migrate
```

> Jika Docker kamu mendukung `docker compose` (tanpa tanda hubung), perintahnya sama saja.

### Akses

| Layanan | URL |
|---|---|
| Website | http://localhost (atau port `APP_PORT` di `.env`) |
| phpMyAdmin | http://localhost:8080 |

## 🗂️ Struktur Project

```
app/
  Http/Controllers/HomeController.php   # data halaman depan (array statis)
  Services/GalleryService.php           # baca foto galeri + buat thumbnail otomatis
resources/
  views/
    layouts/app.blade.php               # kerangka HTML (head, font, @vite)
    home.blade.php                      # seluruh halaman depan (per section)
  css/app.css                           # Tailwind + komponen custom + marquee galeri
  js/app.js                             # animasi scroll, counter angka, menu mobile
routes/web.php                          # GET / → HomeController@index
tailwind.config.js                      # warna brand, font, animasi float
public/images/
  logo.png                              # logo Sekolah Robotika
  gallery/1.jpg … 30.jpg                # foto galeri (diisi manual)
  gallery/thumbs/                       # dibuat otomatis, jangan diedit/commit
```

## 🔁 Alur Halaman Depan

```
Browser → GET /
        → routes/web.php
        → HomeController@index
              ├─ data statis: stats, jenjang, metode, lms
              └─ GalleryService → galeriAtas (foto 1–15), galeriBawah (foto 16–30)
        → home.blade.php (extends layouts/app.blade.php)
```

**Section pada halaman depan:** Navbar → Hero → Statistik → Tentang → Jenjang → LMS → Galeri → CTA → Footer.

## ✏️ Mengubah Konten

Semua konten dinamis ada di `HomeController@index` sebagai array PHP. Blade hanya melakukan loop.

| Variabel | Isi |
|---|---|
| `$stats` | `label`, `value`, `icon` (angka masih placeholder, ganti dengan data asli) |
| `$jenjang` | `nama`, `icon`, `desc`, `materi[]` |
| `$metode` | `[judul, deskripsi, icon]` |
| `$lms` | `[judul, deskripsi, icon]` |
| `$galeriAtas`, `$galeriBawah` | 15 item; tiap item `['src', 'blur']` atau `null` |

## 🖼️ Mengelola Galeri

1. Taruh foto asli di `public/images/gallery/` dengan nama `1.jpg` sampai `30.jpg` (ekstensi harus `.jpg` huruf kecil).
2. Foto **1–15** tampil di baris atas, foto **16–30** di baris bawah.
3. Saat halaman dibuka, `GalleryService` membuat otomatis thumbnail lebar 640px dan placeholder blur 24px di `public/images/gallery/thumbs/`.
4. Thumbnail dibuat ulang otomatis jika foto aslinya diganti.
5. Foto yang tidak ada akan tampil sebagai ikon kamera.

> Kunjungan pertama bisa lebih lambat karena thumbnail sedang dibuat. Setelah itu halaman akan cepat.

## 🎨 Styling

- **Warna brand** (`tailwind.config.js`): `brand-navy #002c86`, `brand-sky #1ba8f0`, `brand-sun #fccc0d`, `brand-deep #001a52`
- **Font**: Poppins (Google Fonts)
- **Komponen custom** (`app.css`): `.btn-sun`, `.btn-ghost`, `.btn-navy`, `.wrap`, `.section`, `.pill`, `.h2`, `.card`, `.reveal`, `.bg-grid`, `.marquee*`
- **Capitalize otomatis**: seluruh teks memakai `text-transform: capitalize`. Tambahkan class `normal-case` pada elemen yang ingin dikecualikan.

## ⚠️ Catatan Penting

- Link `/login` belum punya route, jadi akan menghasilkan 404 sampai LMS dibuat.
- Mockup dashboard di section LMS hanya HTML statis, bukan fitur sungguhan.
- Laravel 9 resminya mendukung PHP sampai 8.2. Di PHP 8.3 umumnya berjalan, namun bisa muncul warning deprecated.
- Tailwind dipin ke **versi 3** (`tailwindcss@3`). Jangan upgrade ke v4 tanpa menyesuaikan `tailwind.config.js` dan `postcss.config.js`.
- Tambahkan `public/images/gallery/thumbs` ke `.gitignore`.

## 🛣️ Roadmap

- [x] Landing page
- [ ] Isi data asli (statistik) dan foto galeri
- [ ] Migrasi database dan autentikasi (login peserta)
- [ ] Mini LMS: materi, progres belajar, sertifikat
- [ ] Admin panel untuk mengelola konten (statistik, jenjang, galeri, materi), mengganti array di `HomeController` dengan query database

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur: `git checkout -b fitur/nama-fitur`
3. Commit perubahan: `git commit -m "Tambah nama-fitur"`
4. Push ke branch: `git push origin fitur/nama-fitur`
5. Buka Pull Request

## 📄 Lisensi

Tentukan lisensi project ini (misalnya MIT atau proprietary) dan tambahkan file `LICENSE` jika diperlukan.

---

<div align="center">

Diselenggarakan oleh **Decaa.id** bersama **Komunitas Peduli Digital (KPD)**

© Sekolah Robotika. Belajar Teknologi Masa Depan.

</div>