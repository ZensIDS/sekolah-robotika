# Sekolah Robotika: Arsitektur Project

Website Sekolah Robotika. Saat ini baru **landing page (halaman depan)**. Login/LMS dan admin panel belum dibuat.

## 1. Tech Stack

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 9, PHP 8.3 |
| Database | MySQL 8.0 (belum dipakai oleh halaman depan) |
| Environment | Docker (gaya Laravel Sail), phpMyAdmin di port 8080 |
| Frontend | Blade + Tailwind CSS 3 + JavaScript biasa, di-build dengan Vite |

## 2. Menjalankan Project

Perintah di bawah memakai `docker-compose` (dengan tanda hubung). Kalau Docker kamu mendukung `docker compose`, perintahnya sama saja.

```bash
docker-compose up -d                                   # nyalakan container
docker-compose exec laravel.test npm install           # pertama kali saja
docker-compose exec laravel.test npm run dev           # jalankan Vite (biarkan terbuka)
docker-compose exec laravel.test php artisan migrate   # jika sudah ada migrasi
```

- Web: http://localhost (atau port `APP_PORT` di `.env`)
- phpMyAdmin: http://localhost:8080
- `.env` wajib berisi `WWWUSER`, `WWWGROUP`, dan konfigurasi `DB_*` (host `mysql`).

## 3. Struktur File yang Penting

```
app/
  Http/Controllers/HomeController.php   ← data halaman depan (array statis)
  Services/GalleryService.php           ← baca foto galeri + buat thumbnail otomatis
resources/
  views/
    layouts/app.blade.php               ← kerangka HTML (head, font, @vite)
    home.blade.php                      ← seluruh halaman depan (per section)
  css/app.css                           ← Tailwind + komponen custom + marquee galeri
  js/app.js                             ← animasi scroll, counter angka, menu mobile
routes/web.php                          ← 1 route: GET / → HomeController@index
tailwind.config.js                      ← warna brand, font, animasi float
public/images/
  logo.png                              ← logo Sekolah Robotika
  gallery/1.jpg … 30.jpg                ← foto galeri (diisi manual)
  gallery/thumbs/                       ← OTOMATIS dibuat, jangan diedit/commit
```

## 4. Alur Halaman Depan

```
Browser → GET /
        → routes/web.php
        → HomeController@index
              ├─ data statis: stats, jenjang, metode, lms
              └─ GalleryService → galeriAtas (foto 1–15), galeriBawah (foto 16–30)
        → view home.blade.php (extends layouts/app.blade.php)
```

### Section di `home.blade.php` (berurutan)

1. **Navbar**: menu anchor + tombol Masuk (`/login`, belum ada)
2. **Hero**: judul, CTA, logo melayang
3. **Statistik**: peserta, trainer, kota (angka naik saat di-scroll)
4. **Tentang**: penjelasan Sekolah Robotika + 4 metode belajar
5. **Jenjang**: SD, SMP, SMA/SMK, Perguruan Tinggi
6. **LMS**: penjelasan sistem belajar + mockup dashboard (hanya tampilan, bukan fitur sungguhan)
7. **Galeri**: 2 baris marquee (atas ke kanan, bawah ke kiri)
8. **CTA** dan **Footer**

## 5. Data & Cara Mengubah Konten

Semua konten dinamis ada di `HomeController@index` sebagai array PHP. Blade hanya melakukan loop, jadi saat admin panel dibuat, **cukup ganti array itu dengan query database** tanpa menyentuh tampilan.

| Variabel | Isi |
|---|---|
| `$stats` | `label`, `value`, `icon`. **Angka masih placeholder (500/15/8), ganti dengan data asli** |
| `$jenjang` | `nama`, `icon`, `desc`, `materi[]` |
| `$metode` | `[judul, deskripsi, icon]` |
| `$lms` | `[judul, deskripsi, icon]` |
| `$galeriAtas`, `$galeriBawah` | 15 item; tiap item `['src', 'blur']` atau `null` |

## 6. Galeri (`GalleryService`)

- Taruh foto asli di `public/images/gallery/` dengan nama `1.jpg` sampai `30.jpg`. Foto 1–15 masuk baris atas, 16–30 baris bawah. Ekstensi harus `.jpg` huruf kecil.
- Saat halaman dibuka, service membuat otomatis:
  - thumbnail lebar 640px (webp, atau jpg jika webp tidak ada) di `gallery/thumbs/`
  - placeholder blur 24px yang ditanam inline sebagai base64
- Thumbnail dibuat ulang otomatis jika foto aslinya diganti.
- Foto yang tidak ada → `null` → tampil ikon kamera.
- Di browser: placeholder blur + efek shimmer tampil dulu, lalu foto fade-in setelah selesai dimuat (lihat `.marquee-item` di `app.css`).
- Kunjungan pertama bisa lambat karena thumbnail sedang dibuat. Sesudah itu cepat.

## 7. Styling

- **Warna brand** (di `tailwind.config.js`): `brand-navy #002c86`, `brand-sky #1ba8f0`, `brand-sun #fccc0d`, `brand-deep #001a52`.
- **Font**: Poppins (Google Fonts, di-load di layout).
- **Komponen custom** di `app.css`: `.btn-sun`, `.btn-ghost`, `.btn-navy`, `.wrap`, `.section`, `.pill`, `.h2`, `.card`, `.reveal`, `.bg-grid`, `.marquee*`.
- **Seluruh teks otomatis Capitalize Each Word** lewat `body { text-transform: capitalize; }`. Teks yang sudah UPPERCASE tidak berubah. Untuk mengecualikan elemen tertentu, tambahkan class `normal-case`.
- **Animasi** (di `app.js`): `.reveal` muncul saat di-scroll, `[data-count]` menghitung naik, tombol `#menu-btn` membuka menu mobile.

## 8. Catatan Penting

- Link `/login` di beberapa tombol **belum punya route**, jadi akan 404 sampai LMS dibuat.
- Mockup dashboard di section LMS hanya HTML statis.
- Laravel 9 resminya mendukung PHP sampai 8.2. Di PHP 8.3 umumnya jalan, tapi bisa muncul warning deprecated.
- Tailwind dipin ke **versi 3** (`tailwindcss@3`). Jangan upgrade ke v4 tanpa menyesuaikan `tailwind.config.js` dan `postcss.config.js`.
- Tambahkan `public/images/gallery/thumbs` ke `.gitignore`.

## 9. Roadmap

1. ~~Landing page~~ (selesai, tinggal isi data asli dan foto)
2. Migrasi database dan autentikasi (login peserta)
3. Mini LMS: materi, progres belajar, sertifikat
4. Admin panel untuk mengelola konten (statistik, jenjang, galeri, materi) → ganti array di `HomeController` dengan query database