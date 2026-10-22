@extends('layouts.app')

@section('content')

{{-- NAVBAR --}}
<header class="fixed inset-x-0 top-0 z-50 bg-brand-deep/80 backdrop-blur-md">
    <div class="wrap flex items-center justify-between py-3">
        <a href="#beranda" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Sekolah Robotika" class="h-10 w-auto">
        </a>
        <nav class="hidden items-center gap-7 text-sm font-medium text-white/90 md:flex">
            <a href="#tentang" class="hover:text-brand-sun">Tentang</a>
            <a href="#jenjang" class="hover:text-brand-sun">Jenjang</a>
            <a href="#lms" class="hover:text-brand-sun">Sistem Belajar</a>
            <a href="#galeri" class="hover:text-brand-sun">Galeri</a>
            <a href="{{ url('/login') }}" class="btn-sun !py-2 text-sm">Masuk</a>
        </nav>
        <button id="menu-btn" class="text-3xl text-white md:hidden" aria-label="Menu">☰</button>
    </div>
    <div id="menu" class="hidden space-y-3 border-t border-white/10 px-5 pb-5 pt-3 text-white md:hidden">
        <a class="block" href="#tentang">Tentang</a>
        <a class="block" href="#jenjang">Jenjang</a>
        <a class="block" href="#lms">Sistem Belajar</a>
        <a class="block" href="#galeri">Galeri</a>
        <a class="btn-sun w-full" href="{{ url('/login') }}">Masuk</a>
    </div>
</header>

{{-- HERO --}}
<section id="beranda" class="relative overflow-hidden bg-gradient-to-br from-brand-deep via-brand-navy to-brand-navy pt-28 text-white">
    <div class="bg-grid absolute inset-0"></div>
    <div class="absolute -left-24 top-40 h-72 w-72 rounded-full bg-brand-sky/30 blur-3xl"></div>
    <div class="absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-brand-sun/20 blur-3xl"></div>

    <div class="wrap relative grid items-center gap-10 pb-28 pt-10 md:grid-cols-2">
        <div class="reveal">
            <span class="inline-block rounded-full bg-white/10 px-4 py-1 text-sm font-semibold text-brand-sun">🤖 Robotika • Coding • AI</span>
            <h1 class="mt-5 text-4xl font-extrabold leading-tight md:text-6xl">
                Belajar <span class="text-brand-sun">Teknologi</span> Masa Depan, Mulai Hari Ini
            </h1>
            <p class="mt-5 max-w-xl text-lg text-white/80">
                Program Sekolah Robotika Berjenjang Dari SD Hingga Perguruan Tinggi. Praktik Langsung, Berbasis Proyek, Dan Siap Membentuk Generasi Inovator Indonesia.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ url('/login') }}" class="btn-sun">Masuk ke Kelas →</a>
                <a href="#tentang" class="btn-ghost">Kenali Program</a>
            </div>
        </div>
        <div class="reveal flex justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah Robotika" class="w-full max-w-lg animate-float drop-shadow-[0_20px_40px_rgba(27,168,240,.45)]">
        </div>
    </div>

    <svg class="absolute bottom-0 w-full text-white" viewBox="0 0 1440 80" preserveAspectRatio="none" fill="currentColor"><path d="M0 80V30c240 50 480 50 720 20s480-40 720 0v30z"/></svg>
</section>

{{-- STATISTIK --}}
<section class="relative z-10 -mt-2 pb-6">
    <div class="wrap grid gap-5 md:grid-cols-3">
        @foreach ($stats as $s)
            <div class="card reveal flex items-center gap-5">
                <div class="grid h-16 w-16 place-items-center rounded-2xl bg-brand-sun/20 text-3xl">{{ $s['icon'] }}</div>
                <div>
                    <div class="text-4xl font-extrabold text-brand-navy"><span data-count="{{ $s['value'] }}">0</span>+</div>
                    <div class="font-medium text-slate-500">{{ $s['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- TENTANG --}}
<section id="tentang" class="section">
    <div class="wrap grid items-center gap-12 md:grid-cols-2">
        <div class="reveal">
            <span class="pill">Apa itu Sekolah Robotika?</span>
            <h2 class="h2">Program Pendidikan Teknologi Yang Aplikatif Dan Berkelanjutan</h2>
            <p class="mt-5">Sekolah Robotika Mendukung Penguatan Pembelajaran Koding, Artificial Intelligence, Computational Thinking, Dan STEM. Peserta Tidak Hanya Memahami Teori, Tetapi Mampu Merancang, Merakit, Dan Memprogram Robot Menjadi Karya Nyata.</p>
            <p class="mt-4">Dikembangkan Oleh <b class="text-brand-navy">Decaa.id</b> Melalui DECAA Training Centre Bersama <b class="text-brand-navy">Komunitas Peduli Digital (KPD)</b>.</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($metode as [$judul, $desc, $icon])
                <div class="card reveal">
                    <div class="text-3xl">{{ $icon }}</div>
                    <h3 class="mt-3 font-bold text-brand-navy">{{ $judul }}</h3>
                    <p class="mt-1 text-sm">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- JENJANG --}}
<section id="jenjang" class="section bg-slate-50">
    <div class="wrap">
        <div class="reveal mx-auto max-w-2xl text-center">
            <span class="pill">Jenjang Pembelajaran</span>
            <h2 class="h2">Dari pengenalan dasar hingga inovasi tingkat lanjut</h2>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($jenjang as $j)
                <div class="card reveal border-t-4 {{ $loop->odd ? 'border-t-brand-sky' : 'border-t-brand-sun' }}">
                    <div class="text-4xl">{{ $j['icon'] }}</div>
                    <h3 class="mt-3 text-xl font-extrabold text-brand-navy">{{ $j['nama'] }}</h3>
                    <p class="mt-2 text-sm">{{ $j['desc'] }}</p>
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach ($j['materi'] as $m)
                            <li class="flex gap-2"><span class="text-brand-sky">✔</span>{{ $m }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- LMS --}}
<section id="lms" class="section relative overflow-hidden bg-brand-navy text-white">
    <div class="bg-grid absolute inset-0"></div>
    <div class="wrap relative grid items-center gap-12 md:grid-cols-2">
        <div class="reveal">
            <span class="inline-block rounded-full bg-white/10 px-4 py-1 text-sm font-semibold text-brand-sun">Sistem Sekolah Robotika</span>
            <h2 class="mt-3 text-3xl font-extrabold md:text-4xl">Belajar di mana saja lewat kelas online</h2>
            <p class="mt-4 text-white/80">Login, ikuti materi, pantau progres, dan raih sertifikat. Semua dalam satu platform.</p>
            <div class="mt-8 space-y-4">
                @foreach ($lms as [$judul, $desc, $icon])
                    <div class="flex gap-4 rounded-2xl bg-white/10 p-4">
                        <div class="text-3xl">{{ $icon }}</div>
                        <div><h3 class="font-bold text-brand-sun">{{ $judul }}</h3><p class="text-sm text-white/80">{{ $desc }}</p></div>
                    </div>
                @endforeach
            </div>
            <a href="{{ url('/login') }}" class="btn-sun mt-8">Login Peserta →</a>
        </div>

        {{-- Mock dashboard --}}
        <div class="reveal rounded-3xl bg-white p-6 text-slate-700 shadow-2xl">
            <div class="flex items-center justify-between">
                <div><p class="text-xs text-slate-400">Selamat datang</p><p class="font-bold text-brand-navy">Dashboard Peserta</p></div>
                <span class="rounded-full bg-brand-sun px-3 py-1 text-xs font-bold text-brand-deep">SD Level 1</span>
            </div>
            @foreach ([['Algoritma & Logika', 100], ['Elektronika Dasar', 70], ['Perakitan Robot', 35]] as [$n, $p])
                <div class="mt-5">
                    <div class="flex justify-between text-sm font-medium"><span>{{ $n }}</span><span>{{ $p }}%</span></div>
                    <div class="mt-1 h-3 rounded-full bg-slate-100"><div class="h-3 rounded-full bg-gradient-to-r from-brand-sky to-brand-navy" style="width: {{ $p }}%"></div></div>
                </div>
            @endforeach
            <div class="mt-6 flex items-center gap-3 rounded-2xl border-2 border-dashed border-brand-sun p-4">
                <span class="text-3xl">📜</span>
                <p class="text-sm"><b class="text-brand-navy">Sertifikat</b><br>Terbuka setelah semua materi selesai</p>
            </div>
        </div>
    </div>
</section>

{{-- GALERI --}}
<section id="galeri" class="section overflow-hidden">
    <div class="wrap reveal mx-auto max-w-2xl text-center">
        <span class="pill">Galeri Kegiatan</span>
        <h2 class="h2">Momen seru belajar dan berkarya</h2>
    </div>

    <div class="mt-12 space-y-4">
        @foreach ([['foto' => $galeriAtas, 'arah' => 'marquee-right'], ['foto' => $galeriBawah, 'arah' => 'marquee-left']] as $row)
            <div class="marquee">
                <div class="marquee-track {{ $row['arah'] }}">
                    @foreach ([1, 2] as $salinan)
                        @foreach ($row['foto'] as $f)
                            @if ($f)
                                <div class="marquee-item skeleton" @if ($salinan === 2) aria-hidden="true" @endif
                                     @if ($f['blur']) style="background-image:url('{{ $f['blur'] }}')" @endif>
                                    <img src="{{ asset($f['src']) }}" alt="Dokumentasi Sekolah Robotika"
                                         width="640" height="448" decoding="async"
                                         loading="{{ $loop->index < 8 ? 'eager' : 'lazy' }}"
                                         onload="this.classList.add('loaded');this.parentElement.classList.add('loaded')"
                                         onerror="this.parentElement.classList.add('loaded')">
                                </div>
                            @else
                                <div class="marquee-item skeleton" @if ($salinan === 2) aria-hidden="true" @endif>
                                    <div class="grid h-full w-full place-items-center bg-gradient-to-br from-brand-navy to-brand-sky text-4xl opacity-70">📷</div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="px-5 pb-20">
    <div class="wrap reveal rounded-[2rem] bg-gradient-to-r from-brand-sun to-yellow-300 p-10 text-center md:p-14">
        <h2 class="text-3xl font-extrabold text-brand-deep md:text-4xl">Siap jadi inovator teknologi masa depan?</h2>
        <p class="mx-auto mt-3 max-w-xl text-brand-deep/80">Bergabung dengan Sekolah Robotika dan mulai perjalanan belajar robotika, coding, dan AI.</p>
        <a href="{{ url('/login') }}" class="btn-navy mt-7">Mulai Belajar</a>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-brand-deep py-10 text-center text-sm text-white/70">
    <img src="{{ asset('images/logo.png') }}" alt="Sekolah Robotika" class="mx-auto h-16">
    <p class="mt-3">Diselenggarakan oleh Decaa.id bersama Komunitas Peduli Digital (KPD)</p>
    <p class="mt-1">© {{ date('Y') }} Sekolah Robotika. Belajar Teknologi Masa Depan.</p>
</footer>

@endsection