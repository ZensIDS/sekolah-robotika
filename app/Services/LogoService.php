<?php

namespace App\Services;

class LogoService
{
    private const SRC = 'images/logo.png';
    private const DIR = 'images/logo-thumbs';
    private const SIZES = ['sm' => 400, 'md' => 640, 'lg' => 1024]; // sm: navbar/footer/favicon, md+lg: hero
    private const BLUR_WIDTH = 24;

    private static ?array $memo = null;

    /**
     * Versi ringan dari public/images/logo.png.
     * Thumbnail (webp/png transparan) dibuat otomatis sekali saja di public/images/logo-thumbs,
     * lalu dipakai terus sampai logo aslinya diganti.
     *
     * Return: ['sm','md','lg' => ['url','w','h'], 'srcset' => string, 'blur' => data-uri|null]
     */
    public function logo(): array
    {
        return self::$memo ??= $this->build();
    }

    private function build(): array
    {
        $src = public_path(self::SRC);
        $info = is_file($src) ? @getimagesize($src) : false;

        if (!$info) {
            $v = ['url' => asset(self::SRC), 'w' => 512, 'h' => 512];

            return ['sm' => $v, 'md' => $v, 'lg' => $v, 'srcset' => '', 'blur' => null];
        }

        [$ow, $oh] = $info;
        $mtime = filemtime($src);
        $ext = function_exists('imagewebp') ? 'webp' : 'png';
        $dir = public_path(self::DIR);

        // Daftar file yang dibutuhkan
        $jobs = [];
        foreach (self::SIZES as $key => $target) {
            $jobs[$key] = $this->job($dir, "logo-{$key}.{$ext}", min($target, $ow), $ow, $oh, $ext);
        }
        $jobs['blur'] = $this->job($dir, 'logo-blur.png', min(self::BLUR_WIDTH, $ow), $ow, $oh, 'png');

        $this->generate($src, $mtime, $dir, $jobs);

        // Susun hasil; jika thumbnail gagal dibuat, pakai logo asli agar tetap tampil
        $res = [];
        foreach (array_keys(self::SIZES) as $key) {
            $j = $jobs[$key];
            $res[$key] = is_file($j['file'])
                ? ['url' => asset(self::DIR . '/' . basename($j['file'])) . '?v=' . filemtime($j['file']), 'w' => $j['w'], 'h' => $j['h']]
                : ['url' => asset(self::SRC) . '?v=' . $mtime, 'w' => $ow, 'h' => $oh];
        }

        $thumbOk = is_file($jobs['md']['file']) && is_file($jobs['lg']['file']);
        $res['srcset'] = ($thumbOk && $res['lg']['w'] > $res['md']['w'])
            ? "{$res['md']['url']} {$res['md']['w']}w, {$res['lg']['url']} {$res['lg']['w']}w"
            : '';

        $blur = $jobs['blur']['file'];
        $res['blur'] = is_file($blur) ? 'data:image/png;base64,' . base64_encode(file_get_contents($blur)) : null;

        return $res;
    }

    private function job(string $dir, string $name, int $w, int $ow, int $oh, string $ext): array
    {
        return [
            'file' => "{$dir}/{$name}",
            'w' => $w,
            'h' => max(1, (int) round($oh * $w / $ow)),
            'ext' => $ext,
        ];
    }

    private function generate(string $src, int $mtime, string $dir, array $jobs): void
    {
        $stale = array_filter($jobs, fn ($j) => !is_file($j['file']) || filemtime($j['file']) < $mtime);
        if (!$stale) {
            return;
        }

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        if (!is_writable($dir)) {
            return; // folder tidak bisa ditulis: pakai file lama jika ada, atau logo asli
        }

        try {
            set_time_limit(0);
            ini_set('memory_limit', '512M');

            // Logo asli cukup dibaca sekali untuk semua ukuran
            $img = imagecreatefromstring((string) file_get_contents($src));
            if (!$img) {
                throw new \RuntimeException("Gagal membaca logo: {$src}");
            }

            foreach ($stale as $j) {
                $this->save($img, $j);
            }
            imagedestroy($img);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function save($img, array $j): void
    {
        $dst = imagecreatetruecolor($j['w'], $j['h']);

        // Pertahankan transparansi PNG
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));

        imagecopyresampled($dst, $img, 0, 0, 0, 0, $j['w'], $j['h'], imagesx($img), imagesy($img));

        $j['ext'] === 'webp'
            ? imagewebp($dst, $j['file'], 85)
            : imagepng($dst, $j['file'], 9);

        imagedestroy($dst);
    }
}