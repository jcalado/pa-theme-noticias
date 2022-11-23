@php
    $format_slug = !empty($format = getPostFormat($id)) ? $format->slug : 'noticia';
@endphp

@if(is_singular('post'))
    <div class="pa-post-meta mb-2">{{__('By', 'iasd')}}
        <span>{{ getCurrentAuthor($id) }}</span>
        @if($region = getPostRegion($id))
            <em class="pa-pipe">|</em>
            <span><i class="fas fa-map-marker-alt me-2" aria-hidden="true"></i> {{ $region->name }}</span>
        @endif
    </div>
@endif
