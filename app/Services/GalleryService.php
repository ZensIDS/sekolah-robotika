<?php

namespace App\Services;

class GalleryService
{
    private const THUMB_WIDTH = 640;       // cukup tajam untuk kartu 320px di layar retina
    private const BLUR_WIDTH = 24;         // placeholder super kecil (di-inline di HTML)
    private const BLUR_MAX_BYTES = 4096;   // batas aman: placeholder lebih besar dari ini TIDAK di-inline
    private const VERSION = 'v2';          // ganti nilai ini jika ingin memaksa thumbnail dibuat ulang

    /**
     * Ambil foto public/images/gallery/{n}.jpg.
     * Thumbnail dibuat otomatis sekali saja (di public/images/gallery/thumbs),
     * lalu dipakai terus sampai file aslinya diganti.
     * Return null jika foto tidak ada.
     */
    public function foto(int $n): ?array
    {
        $src = public_path("images/gallery/{$n}.jpg");
        if (!is_file($src)) {
            return null;
        }

        $dir = public_path('images/gallery/thumbs');
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $ext = function_exists('imagewebp') ? 'webp' : 'jpg';
        $v = self::VERSION;
        $thumb = "{$dir}/{$n}-{$v}.{$ext}";
        $blur = "{$dir}/{$n}-{$v}-blur.jpg";
        $mtime = filemtime($src);

        $jobs = [];
        if (!is_file($thumb) || filemtime($thumb) < $mtime) {
            $jobs[] = [$thumb, self::THUMB_WIDTH, $ext, 75];
        }
        if (!is_file($blur) || filemtime($blur) < $mtime) {
            $jobs[] = [$blur, self::BLUR_WIDTH, 'jpg', 50];
        }

        if ($jobs && is_dir($dir) && is_writable($dir)) {
            try {
                $this->generate($src, $jobs); // foto dibaca sekali untuk semua ukuran
            } catch (\Throwable $e) {
                report($e);
            }
        }

        // Thumbnail gagal dibuat: pakai foto asli agar tetap tampil
        $url = is_file($thumb)
            ? "images/gallery/thumbs/{$n}-{$v}.{$ext}?v={$mtime}"
            : "images/gallery/{$n}.jpg?v={$mtime}";

        // Placeholder hanya di-inline jika benar-benar kecil, agar HTML tidak membengkak
        $blurUri = (is_file($blur) && filesize($blur) <= self::BLUR_MAX_BYTES)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($blur))
            : null;

        return ['src' => $url, 'blur' => $blurUri];
    }

    /** @param array<int, array{0:string,1:int,2:string,3:int}> $jobs [tujuan, lebar, ext, kualitas] */
    private function generate(string $src, array $jobs): void
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $img = imagecreatefromjpeg($src);
        if (!$img) {
            throw new \RuntimeException("Gagal membaca gambar: {$src}");
        }

        // Perbaiki orientasi foto dari HP (EXIF)
        $exif = function_exists('exif_read_data') ? @exif_read_data($src) : false;
        $angle = [3 => 180, 6 => -90, 8 => 90][$exif['Orientation'] ?? 0] ?? 0;
        if ($angle) {
            $img = imagerotate($img, $angle, 0) ?: $img;
        }

        foreach ($jobs as [$dest, $width, $ext, $quality]) {
            $this->save($img, $dest, $width, $ext, $quality);
        }

        imagedestroy($img);
    }

    private function save($img, string $dest, int $width, string $ext, int $quality): void
    {
        $w = imagesx($img);
        $h = imagesy($img);

        if ($w > $width) {
            // imagecopyresampled lebih andal daripada imagescale untuk pengecilan ekstrem
            $newH = max(1, (int) round($h * $width / $w));
            $dst = imagecreatetruecolor($width, $newH);
            imagecopyresampled($dst, $img, 0, 0, 0, 0, $width, $newH, $w, $h);
        } else {
            $dst = $img;
        }

        imageinterlace($dst, true);
        $ext === 'webp' ? imagewebp($dst, $dest, $quality) : imagejpeg($dst, $dest, $quality);

        if ($dst !== $img) {
            imagedestroy($dst);
        }
    }
}