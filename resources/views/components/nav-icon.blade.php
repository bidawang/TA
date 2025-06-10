@props(['link', 'icon', 'label'])

<a href="{{ $link }}" class="text-center text-decoration-none text-dark flex-fill d-flex flex-column align-items-center justify-content-center" style="font-size: 13px;">
  <i class="bi {{ $icon }} fs-4 mb-1"></i>
  <span>{{ $label }}</span>
</a>