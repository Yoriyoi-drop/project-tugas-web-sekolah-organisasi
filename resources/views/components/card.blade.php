@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if($title || isset($header))
        <div class="card-header">
            @if(isset($header))
                {{ $header }}
            @else
                @if($title)
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                @endif
                @if($subtitle)
                    <p class="card-subtitle text-muted mb-0">{{ $subtitle }}</p>
                @endif
            @endif
        </div>
    @endif
    
    <div class="card-body{{ $padding ? '' : ' p-0' }}">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>