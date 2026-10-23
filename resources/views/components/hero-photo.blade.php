{{-- Foto pemanis hero. Bentuk (rounded, border, aspect) diatur lewat class dari pemanggil. --}}
@props(['foto' => null])
<div {{ $attributes->class(['relative overflow-hidden bg-gradient-to-br from-brand-navy to-brand-sky shadow-2xl']) }}
     @if (!empty($foto['blur'])) style="background-image:url('{{ $foto['blur'] }}');background-size:cover;background-position:center" @endif>
    @if ($foto)
        <img src="{{ asset($foto['src']) }}" alt="Dokumentasi Sekolah Robotika" width="640" height="640"
             decoding="async" class="img-fade h-full w-full object-cover"
             onload="this.classList.add('loaded')" onerror="this.classList.add('loaded')">
    @else
        <div class="grid h-full w-full place-items-center text-3xl opacity-70">📷</div>
    @endif
</div>