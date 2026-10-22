@extends('layouts.app')

@php($logo = app(\App\Services\LogoService::class)->logo())

@section('content')

{{-- NAVBAR --}}
<header class="fixed inset-x-0 top-0 z-50 bg-brand-deep/80 backdrop-blur-md">
    <div class="wrap flex items-center justify-between py-3">
        <a href="#beranda" class="flex items-center gap-2">
            <img src="{{ $logo['sm']['url'] }}" width="{{ $logo['sm']['w'] }}" height="{{ $logo['sm']['h'] }}" alt="Sekolah Robotika" class="h-10 w-auto">
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

    {{-- Ornamen hero --}}
    <x-gear class="spin-slow h-56 w-56 text-white/10" style="right:-3rem;top:6rem" />
    <x-gear class="spin-rev h-24 w-24 text-brand-sun/25" style="left:4%;bottom:7rem" />
    <span class="orn twinkle text-2xl text-brand-sun" style="left:6%;top:9rem">✦</span>
    <span class="orn twinkle text-lg text-brand-sky" style="left:47%;top:7rem;animation-delay:.8s">✦</span>
    <span class="orn twinkle text-xl text-white/70" style="right:12%;top:8rem;animation-delay:1.6s">✦</span>
    <span class="orn twinkle text-2xl text-brand-sun" style="right:6%;bottom:9rem;animation-delay:.4s">+</span>
    <span class="orn bg-dots hidden h-24 w-24 md:block" style="right:3%;bottom:6rem"></span>

    <div class="wrap relative grid items-center gap-10 pb-28 pt-10 md:grid-cols-2">
        <div>
            <span class="inline-block rounded-full bg-white/10 px-4 py-1 text-sm font-semibold text-brand-sun">🤖 Robotika • Coding • AI</span>
            <h1 class="mt-5 text-4xl font-extrabold leading-tight md:text-6xl">
                Belajar <span class="relative inline-block text-brand-sun">Teknologi<svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" preserveAspectRatio="none" fill="none" aria-hidden="true"><path d="M2 8c20-8 40 8 60 0s40 8 60 0 40 8 76 0" stroke="#1ba8f0" stroke-width="4" stroke-linecap="round"/></svg></span> Masa Depan, Mulai Hari Ini
            </h1>
            <p class="mt-5 max-w-xl text-lg text-white/80">
                Program Sekolah Robotika Berjenjang Dari SD Hingga Perguruan Tinggi. Praktik Langsung, Berbasis Proyek, Dan Siap Membentuk Generasi Inovator Indonesia.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ url('/login') }}" class="btn-sun">Masuk ke Kelas →</a>
                <a href="#tentang" class="btn-ghost">Kenali Program</a>
            </div>
        </div>
        <div class="relative flex justify-center">
            {{-- Logo hero: placeholder blur + fade-in, memakai versi ringan --}}
            <div class="logo-wrap relative w-full max-w-lg animate-float drop-shadow-[0_20px_40px_rgba(27,168,240,.45)]">
                @if ($logo['blur'])
                    <img src="{{ $logo['blur'] }}" alt="" aria-hidden="true" class="logo-ph absolute inset-0 h-full w-full animate-pulse object-contain blur-xl">
                @endif
                <img src="{{ $logo['md']['url'] }}"
                     @if ($logo['srcset']) srcset="{{ $logo['srcset'] }}" sizes="(min-width: 768px) 512px, 90vw" @endif
                     width="{{ $logo['md']['w'] }}" height="{{ $logo['md']['h'] }}"
                     alt="Logo Sekolah Robotika" fetchpriority="high" decoding="async"
                     class="img-fade relative block h-auto w-full"
                     onload="this.classList.add('loaded');this.parentElement.classList.add('loaded')"
                     onerror="this.classList.add('loaded');this.parentElement.classList.add('loaded')">
            </div>
            <span class="orn bob left-0 top-6 hidden rounded-2xl bg-white px-3 py-2 text-sm font-bold text-brand-navy shadow-xl sm:block" style="animation-delay:.3s">💡 Coding</span>
            <span class="orn bob right-0 top-1/3 hidden rounded-2xl bg-brand-sun px-3 py-2 text-sm font-bold text-brand-deep shadow-xl sm:block" style="animation-delay:1.2s">🧠 AI</span>
            <span class="orn bob bottom-6 left-8 hidden rounded-2xl bg-brand-sky px-3 py-2 text-sm font-bold text-white shadow-xl sm:block" style="animation-delay:2s">🤖 Robotika</span>
        </div>
    </div>

    <svg class="absolute bottom-0 w-full text-white" viewBox="0 0 1440 80" preserveAspectRatio="none" fill="currentColor"><path d="M0 80V30c240 50 480 50 720 20s480-40 720 0v30z"/></svg>
</section>

{{-- STATISTIK --}}
<section class="relative z-10 -mt-2 pb-6">
    <div class="wrap grid gap-5 md:grid-cols-3">
        @foreach ($stats as $s)
            <div class="card group flex items-center gap-5">
                <div class="grid h-16 w-16 place-items-center rounded-2xl bg-brand-sun/20 text-3xl transition duration-300 group-hover:-rotate-12 group-hover:scale-110">{{ $s['icon'] }}</div>
                <div>
                    <div class="text-4xl font-extrabold text-brand-navy"><span data-count="{{ $s['value'] }}">0</span>+</div>
                    <div class="font-medium text-slate-500">{{ $s['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- TENTANG --}}
<section id="tentang" class="section relative overflow-hidden">
    <div class="orn -left-24 top-10 h-72 w-72 rounded-full bg-brand-sky/10 blur-3xl"></div>
    <span class="orn bg-dots-navy hidden h-28 w-28 md:block" style="right:2%;top:2.5rem"></span>
    <x-gear class="spin-slow h-28 w-28 text-brand-navy/10" style="left:3%;bottom:2rem" />
    <span class="orn twinkle text-2xl text-brand-sun" style="left:48%;top:4rem">✦</span>
    <div class="wrap relative grid items-center gap-12 md:grid-cols-2">
        <div class="reveal">
            <span class="pill">Apa itu Sekolah Robotika?</span>
            <h2 class="h2">Program Pendidikan Teknologi Yang Aplikatif Dan Berkelanjutan</h2>
            <p class="mt-5">Sekolah Robotika Mendukung Penguatan Pembelajaran Koding, Artificial Intelligence, Computational Thinking, Dan STEM. Peserta Tidak Hanya Memahami Teori, Tetapi Mampu Merancang, Merakit, Dan Memprogram Robot Menjadi Karya Nyata.</p>
            <p class="mt-4">Dikembangkan Oleh <b class="text-brand-navy">Decaa.id</b> Melalui DECAA Training Centre Bersama <b class="text-brand-navy">Komunitas Peduli Digital (KPD)</b>.</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($metode as [$judul, $desc, $icon])
                <div class="card group reveal">
                    <div class="text-3xl transition duration-300 group-hover:-rotate-12 group-hover:scale-125">{{ $icon }}</div>
                    <h3 class="mt-3 font-bold text-brand-navy">{{ $judul }}</h3>
                    <p class="mt-1 text-sm">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- JENJANG --}}
<section id="jenjang" class="section relative overflow-hidden bg-slate-50">
    <svg class="orn inset-x-0 top-0 h-8 w-full text-white md:h-12" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="currentColor" aria-hidden="true"><path d="M0 0H1440V28C1200 60 960 60 720 36C480 12 240 4 0 36Z"/></svg>
    <x-gear class="spin-slow h-44 w-44 text-brand-navy/[.06]" style="right:-2rem;top:6rem" />
    <span class="orn bg-dots-navy hidden h-24 w-24 md:block" style="left:2%;bottom:3rem"></span>
    <span class="orn twinkle text-2xl text-brand-sun" style="left:8%;top:6rem">✦</span>
    <span class="orn twinkle text-xl text-brand-sky" style="right:10%;bottom:4rem;animation-delay:1s">✦</span>
    <div class="wrap relative">
        <div class="reveal mx-auto max-w-2xl text-center">
            <span class="pill">Jenjang Pembelajaran</span>
            <h2 class="h2">Dari pengenalan dasar hingga inovasi tingkat lanjut</h2>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($jenjang as $j)
                <div class="card group reveal relative border-t-4 {{ $loop->odd ? 'border-t-brand-sky' : 'border-t-brand-sun' }}">
                    <div class="flex items-start justify-between">
                        <div class="text-4xl transition duration-300 group-hover:-rotate-12 group-hover:scale-125">{{ $j['icon'] }}</div>
                        <span class="text-4xl font-extrabold leading-none text-slate-200">0{{ $loop->iteration }}</span>
                    </div>
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
    <svg class="orn inset-x-0 top-0 h-8 w-full text-slate-50 md:h-12" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="currentColor" aria-hidden="true"><path d="M0 0H1440V28C1200 60 960 60 720 36C480 12 240 4 0 36Z"/></svg>
    <svg class="orn inset-x-0 bottom-0 h-8 w-full text-white md:h-12" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="currentColor" aria-hidden="true"><path d="M0 60H1440V32C1200 4 960 4 720 28C480 52 240 60 0 28Z"/></svg>
    <x-gear class="spin-slow h-64 w-64 text-white/5" style="left:-4rem;bottom:3rem" />
    <x-gear class="spin-rev h-32 w-32 text-brand-sun/15" style="right:6%;top:5rem" />
    <span class="orn twinkle text-2xl text-brand-sun" style="left:45%;top:6rem">✦</span>
    <span class="orn twinkle text-xl text-white/70" style="right:4%;bottom:8rem;animation-delay:1.2s">✦</span>
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
        <div class="relative">
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
        {{-- Ornamen: chip melayang --}}
        <span class="orn bob -right-1 -top-5 rounded-2xl bg-brand-sun px-3 py-2 text-sm font-bold text-brand-deep shadow-xl md:-right-6">🔥 7 Hari Beruntun</span>
        <span class="orn bob -left-1 bottom-12 rounded-2xl bg-brand-sky px-3 py-2 text-sm font-bold text-white shadow-xl md:-left-6" style="animation-delay:1.5s">⭐ +50 XP</span>
        </div>
    </div>
</section>

{{-- GALERI --}}
<section id="galeri" class="section relative overflow-hidden">
    <span class="orn bg-dots-navy hidden h-24 w-24 md:block" style="left:4%;top:3rem"></span>
    <span class="orn bg-dots-navy hidden h-24 w-24 md:block" style="right:4%;top:5rem"></span>
    <span class="orn twinkle text-2xl text-brand-sun" style="left:28%;top:4rem">✦</span>
    <span class="orn twinkle text-xl text-brand-sky" style="right:27%;top:6rem;animation-delay:1.1s">✦</span>
    <span class="orn bob text-3xl" style="left:12%;top:7rem">📸</span>
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
                                         loading="lazy" fetchpriority="low"
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
    <div class="wrap reveal relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-brand-sun to-yellow-300 p-10 text-center md:p-14">
        <x-gear class="spin-slow h-48 w-48 text-brand-deep/10" style="left:-3rem;bottom:-3rem" />
        <x-gear class="spin-rev h-32 w-32 text-white/40" style="right:-1.5rem;top:-1.5rem" />
        <span class="orn bob hidden text-4xl md:block" style="left:9%;top:22%">🚀</span>
        <span class="orn bob hidden text-4xl md:block" style="right:9%;bottom:20%;animation-delay:1s">🤖</span>
        <span class="orn bob h-3 w-3 rounded-full bg-brand-navy/50" style="left:20%;top:14%;animation-delay:.2s"></span>
        <span class="orn bob h-3 w-3 rounded-md bg-white/80" style="right:22%;top:16%;animation-delay:.9s"></span>
        <span class="orn bob h-4 w-4 rounded-full border-2 border-brand-navy/40" style="left:30%;bottom:14%;animation-delay:1.6s"></span>
        <span class="orn twinkle text-2xl text-brand-deep/60" style="right:30%;bottom:12%">✦</span>
        <div class="relative">
            <h2 class="text-3xl font-extrabold text-brand-deep md:text-4xl">Siap jadi inovator teknologi masa depan?</h2>
            <p class="mx-auto mt-3 max-w-xl text-brand-deep/80">Bergabung dengan Sekolah Robotika dan mulai perjalanan belajar robotika, coding, dan AI.</p>
            <a href="{{ url('/login') }}" class="btn-navy mt-7">Mulai Belajar</a>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="relative overflow-hidden bg-brand-deep text-white/70">
    <div class="bg-grid absolute inset-0 opacity-40"></div>
    <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-brand-sky/20 blur-3xl"></div>
    <x-gear class="spin-slow h-40 w-40 text-white/5" style="left:-2rem;bottom:3rem" />
    <span class="orn twinkle text-xl text-brand-sun" style="right:8%;bottom:6rem">✦</span>

    <div class="wrap relative grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Brand --}}
        <div class="sm:col-span-2 lg:col-span-1">
            <img src="{{ $logo['sm']['url'] }}" width="{{ $logo['sm']['w'] }}" height="{{ $logo['sm']['h'] }}" alt="Sekolah Robotika" class="h-14 w-auto" loading="lazy">
            <p class="mt-4 text-sm leading-relaxed">
                Program Robotika, Coding, Dan AI Berjenjang Dari SD Hingga Perguruan Tinggi. Praktik Langsung, Berbasis Proyek.
            </p>
            <div class="mt-5 flex flex-wrap gap-2">
                <a href="https://www.instagram.com/sekolah.robotika" target="_blank" rel="noopener" class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white transition hover:bg-brand-sun hover:text-brand-deep">Instagram</a>
            </div>
        </div>

        {{-- Navigasi --}}
        <div>
            <h4 class="font-bold text-brand-sun">Navigasi</h4>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="#tentang" class="transition hover:text-white">Tentang</a></li>
                <li><a href="#jenjang" class="transition hover:text-white">Jenjang</a></li>
                <li><a href="#lms" class="transition hover:text-white">Sistem Belajar</a></li>
                <li><a href="#galeri" class="transition hover:text-white">Galeri</a></li>
                <li><a href="{{ url('/login') }}" class="transition hover:text-white">Login Peserta</a></li>
            </ul>
        </div>

        {{-- Jenjang (otomatis dari data $jenjang) --}}
        <div>
            <h4 class="font-bold text-brand-sun">Jenjang</h4>
            <ul class="mt-4 space-y-2 text-sm">
                @foreach ($jenjang as $j)
                    <li><a href="#jenjang" class="transition hover:text-white">{{ $j['nama'] }}</a></li>
                @endforeach
            </ul>
        </div>

        {{-- Kontak --}}
        <div>
            <h4 class="font-bold text-brand-sun">Hubungi Kami</h4>
            <ul class="mt-4 space-y-3 text-sm">
                <li class="flex gap-2"><span>💬</span><a href="https://wa.me/62882009500390" target="_blank" rel="noopener" class="transition hover:text-white">+62 882-0095-00390</a></li>
                <li class="flex gap-2"><span>✉️</span><a href="mailto:info@sekolahrobotika.com" class="normal-case transition hover:text-white">info@sekolahrobotika.com</a></li>
            </ul>
            <a href="https://wa.me/62882009500390" target="_blank" rel="noopener" class="btn-sun mt-5 !py-2 text-sm">Tanya Via WhatsApp</a>
        </div>
    </div>

    {{-- Bar bawah --}}
    <div class="relative border-t border-white/10">
        <div class="wrap flex flex-col items-center justify-between gap-3 py-5 text-xs sm:flex-row">
            <p>© {{ date('Y') }} Sekolah Robotika. Belajar Teknologi Masa Depan.</p>
            <p>Diselenggarakan Oleh <b class="text-white"><a href="https://decaa.id" target="_blank">Decaa.id</a></b> Bersama <b class="text-white">Komunitas Peduli Digital (KPD)</b></p>
            <a href="#beranda" class="rounded-full bg-white/10 px-3 py-1 font-semibold text-white transition hover:bg-brand-sun hover:text-brand-deep">↑ Kembali Ke Atas</a>
        </div>
    </div>
</footer>

@endsection