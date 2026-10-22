<?php

namespace App\Services;

class GalleryService
{
    private const THUMB_WIDTH = 640; // cukup tajam untuk kartu 320px di layar retina
    private const BLUR_WIDTH = 24;   // placeholder super kecil (di-inline di HTML)

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
        $thumb = "{$dir}/{$n}.{$ext}";
        $blur = "{$dir}/{$n}-blur.jpg";
        $mtime = filemtime($src);

        try {
            if (!is_file($thumb) || filemtime($thumb) < $mtime) {
                $this->resize($src, $thumb, self::THUMB_WIDTH, $ext, 75);
            }
            if (!is_file($blur) || filemtime($blur) < $mtime) {
                $this->resize($src, $blur, self::BLUR_WIDTH, 'jpg', 60);
            }
        } catch (\Throwable $e) {
            report($e);

            // Gagal membuat thumbnail: pakai foto asli agar tetap tampil
            return ['src' => "images/gallery/{$n}.jpg?v={$mtime}", 'blur' => null];
        }

        return [
            'src' => "images/gallery/thumbs/{$n}.{$ext}?v={$mtime}",
            'blur' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents($blur)),
        ];
    }

    private function resize(string $src, string $dest, int $width, string $ext, int $quality): void
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

        if (imagesx($img) > $width) {
            $img = imagescale($img, $width, -1, IMG_BICUBIC) ?: $img;
        }

        imageinterlace($img, true);
        $ext === 'webp' ? imagewebp($img, $dest, $quality) : imagejpeg($img, $dest, $quality);
        imagedestroy($img);
    }
}