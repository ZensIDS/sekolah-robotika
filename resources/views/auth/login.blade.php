@extends('layouts.app')

@section('title', __('Masuk - Sekolah Robotika'))

@php
    $logo = app(\App\Services\LogoService::class)->logo();
@endphp

@section('content')
<main class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gradient-to-br from-brand-deep via-brand-navy to-brand-navy px-5 py-10">
    <div class="bg-grid absolute inset-0"></div>
    <div class="absolute -left-24 top-20 h-72 w-72 rounded-full bg-brand-sky/30 blur-3xl"></div>
    <div class="absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-brand-sun/20 blur-3xl"></div>

    {{-- Ornamen --}}
    <x-gear class="spin-slow h-52 w-52 text-white/10" style="right:-3rem;top:3rem" />
    <x-gear class="spin-rev h-24 w-24 text-brand-sun/25" style="left:5%;bottom:4rem" />
    <span class="orn twinkle text-2xl text-brand-sun" style="left:8%;top:6rem">✦</span>
    <span class="orn twinkle text-xl text-white/70" style="right:14%;bottom:7rem;animation-delay:1.2s">✦</span>

    <div class="relative w-full max-w-md">
        <a href="{{ route('home') }}" class="mx-auto mb-6 block w-40">
            <img src="{{ $logo['sm']['url'] }}" width="{{ $logo['sm']['w'] }}" height="{{ $logo['sm']['h'] }}"
                 alt="Sekolah Robotika" class="h-auto w-full drop-shadow-[0_10px_24px_rgba(27,168,240,.45)]">
        </a>

        <div class="rounded-3xl bg-white p-7 shadow-2xl shadow-brand-deep/40 sm:p-9">
            <span class="pill">🤖 {{ __('Login Peserta') }}</span>
            <h1 class="mt-3 text-2xl font-extrabold text-brand-navy">{{ __('Selamat Datang Kembali!') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ __('Masuk untuk melanjutkan belajar robotika, coding, dan AI.') }}</p>

            @if (session('info'))
                <div class="mt-5 rounded-2xl bg-brand-sun/20 px-4 py-3 text-sm font-medium text-brand-deep" role="status">
                    ⚙️ {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-5 rounded-2xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5" novalidate>
                @csrf

                <div>
                    <label for="login" class="mb-1.5 block text-sm font-semibold text-brand-navy">{{ __('Email / Username') }}</label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus
                           autocomplete="username" autocapitalize="none" spellcheck="false"
                           placeholder="{{ __('Nama@Email.Com') }}"
                           class="w-full rounded-2xl border-2 border-slate-200 px-4 py-3 normal-case text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-brand-sky focus:ring-4 focus:ring-brand-sky/20">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-brand-navy">{{ __('Kata Sandi') }}</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                               autocomplete="current-password" placeholder="••••••••"
                               class="w-full rounded-2xl border-2 border-slate-200 py-3 pl-4 pr-14 normal-case text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-brand-sky focus:ring-4 focus:ring-brand-sky/20">
                        <button type="button" id="toggle-password" aria-label="{{ __('Tampilkan kata sandi') }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full px-2 py-1 text-xs font-bold text-brand-navy hover:bg-brand-sky/15">
                            {{ __('Lihat') }}
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-navy w-full">{{ __('Masuk') }} →</button>
            </form>

            <div class="mt-6 border-t border-slate-100 pt-5 text-center text-sm text-slate-500">
                <p>{{ __('Kesulitan masuk atau belum punya akun?') }}</p>
                <a href="https://wa.me/62882009500390" target="_blank" rel="noopener"
                   class="mt-1 inline-block font-semibold text-brand-navy hover:text-brand-sky">💬 {{ __('Hubungi Admin Via WhatsApp') }}</a>
            </div>
        </div>

        <p class="mt-6 text-center text-sm">
            <a href="{{ route('home') }}" class="font-semibold text-white/80 transition hover:text-brand-sun">← {{ __('Kembali Ke Beranda') }}</a>
        </p>
    </div>
</main>

<script>
    // Tampilkan/sembunyikan kata sandi
    (function () {
        var btn = document.getElementById('toggle-password');
        var input = document.getElementById('password');
        if (!btn || !input) return;
        btn.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.textContent = show ? @json(__('Sembunyi')) : @json(__('Lihat'));
        });
    })();
</script>
@endsection