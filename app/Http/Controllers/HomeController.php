<?php

namespace App\Http\Controllers;

use App\Services\GalleryService;

class HomeController extends Controller
{
    // Data sementara (statis). Nanti diganti query dari database / admin panel.
    public function index(GalleryService $gallery)
    {
        return view('home', [
            // TODO: ganti dengan angka asli
            'stats' => [
                ['label' => 'Peserta', 'value' => 1300, 'icon' => '🎓'],
                ['label' => 'Trainer', 'value' => 86, 'icon' => '🧑‍🏫'],
                ['label' => 'Kota', 'value' => 48, 'icon' => '📍'],
            ],
            'jenjang' => [
                ['nama' => 'SD / MI', 'icon' => '🚗', 'desc' => 'Mengenal konsep dasar robotika dengan cara menyenangkan dan interaktif.', 'materi' => ['Algoritma & logika', 'Elektronika dasar']],
                ['nama' => 'SMP / MTs', 'icon' => '🤖', 'desc' => 'Mulai memakai mikrokontroler, menyusun algoritma, dan mengerjakan proyek.', 'materi' => ['Dasar pemrograman', 'Arduino, sensor & aktuator']],
                ['nama' => 'SMA / SMK', 'icon' => '📡', 'desc' => 'Teknologi lebih kompleks yang dekat dengan kebutuhan dunia industri.', 'materi' => ['Embedded System & IoT', 'Sistem otomasi', 'Final Project']],
                ['nama' => 'Perguruan Tinggi', 'icon' => '🧠', 'desc' => 'Inovasi, riset, dan teknologi terapan untuk solusi nyata.', 'materi' => ['AI & Computer Vision', 'Smart System', 'Prototype & Research Project']],
            ],
            'metode' => [
                ['Learning by Doing', 'Belajar lewat praktik langsung merakit, menguji, dan mengembangkan robot.', '🛠️'],
                ['Project-Based Learning', 'Setiap level menghasilkan produk atau solusi teknologi nyata.', '🚀'],
                ['Challenge-Based Learning', 'Menyelesaikan tantangan untuk melatih kreativitas dan berpikir kritis.', '🏆'],
                ['Collaborative Learning', 'Bekerja dalam tim untuk menyelesaikan proyek robotika bersama.', '🤝'],
            ],
            'lms' => [
                ['Materi Belajar', 'Akses modul, video, dan latihan kapan saja sesuai jenjang dan level kamu.', '📚'],
                ['Proses & Progres', 'Pantau perkembangan belajar, tugas, dan evaluasi di setiap level.', '📈'],
                ['Sertifikat', 'Selesaikan level dan unduh sertifikat kelulusan langsung dari akun kamu.', '📜'],
            ],
            // Row atas = 1.jpg - 15.jpg, row bawah = 16.jpg - 30.jpg
            // di public/images/gallery/. Foto yang tidak ada => null (tampil ikon kamera)
            'galeriAtas' => $this->fotoGaleri($gallery, 1, 15),
            'galeriBawah' => $this->fotoGaleri($gallery, 16, 30),
        ]);
    }

    // Foto n = $dari..$sampai; null jika file tidak ada (tampil ikon kamera)
    private function fotoGaleri(GalleryService $gallery, int $dari, int $sampai): array
    {
        return array_map(fn ($n) => $gallery->foto($n), range($dari, $sampai));
    }
}