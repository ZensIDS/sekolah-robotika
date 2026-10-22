@php($logo = app(\App\Services\LogoService::class)->logo())
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sekolah Robotika - Belajar Teknologi Masa Depan')</title>
    <meta name="description" content="Program pembelajaran robotika, coding, dan AI berjenjang dari SD hingga Perguruan Tinggi.">
    <link rel="icon" href="{{ $logo['sm']['url'] }}">
    {{-- Mulai unduh logo hero lebih awal (versi ringan) --}}
    <link rel="preload" as="image" href="{{ $logo['md']['url'] }}" @if ($logo['srcset']) imagesrcset="{{ $logo['srcset'] }}" imagesizes="(min-width: 768px) 512px, 90vw" @endif fetchpriority="high">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <noscript><style>.img-fade{opacity:1}.reveal{opacity:1;transform:none}</style></noscript>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-slate-600 antialiased">
    @yield('content')
</body>
</html>