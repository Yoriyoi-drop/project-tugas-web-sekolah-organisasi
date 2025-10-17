@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false
])

@php
    $classes = 'btn';
    
    // Variant classes
    $classes .= match($variant) {
        'primary' => ' btn-primary',
        'secondary' => ' btn-secondary',
        'success' => ' btn-success',
        'danger' => ' btn-danger',
        'warning' => ' btn-warning',
        'info' => ' btn-info',
        'light' => ' btn-light',
        'dark' => ' btn-dark',
        'outline-primary' => ' btn-outline-primary',
        'outline-secondary' => ' btn-outline-secondary',
        default => ' btn-primary'
    };
    
    // Size classes
    $classes .= match($size) {
        'sm' => ' btn-sm',
        'lg' => ' btn-lg',
        default => ''
    };
    
    if ($disabled) {
        $classes .= ' disabled';
    }
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled) disabled @endif>
        {{ $slot }}
    </button>
@endif