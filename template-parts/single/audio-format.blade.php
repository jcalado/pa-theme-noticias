@php
    $audio_url = get_field('embed_url');
@endphp

{{-- Se o ACF audio_url não estiver vazio executa --}}
@if (!empty($audio_url))
    <div class="audio-container pt-4">
        {!! $audio_url !!}
    </div>
@endif

<div class="row d-flex dark-content-bg pb-3 mb-4">
    <div class="col-md-6 col-12">
        @include('components.metas.author', get_the_ID())
    </div>
    <div class="col-md-6 col-12 post-meta-date">
        @include('components.metas.meta')
    </div>
</div>
<div class="col-12">
    @include('components.metas.share')
</div>
