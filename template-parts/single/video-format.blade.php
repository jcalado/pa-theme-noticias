@php
    $video_url = get_field('embed_url', $post_id, true);
@endphp

{{-- Se o ACF audio_url não estiver vazio executa --}}
@if (!empty($video_url))
    <div class="floating-video position-relative" id="embed-video">
        <picture class="d-inline-block mw-100 floating-video__thumbnail w-100 overflow-hidden">
            {!! $video_url !!}
        </picture>
        <div class="card__play position-absolute">
            <div class="icon rounded-circle d-flex align-items-center">
                <em class="uil uil-play mx-auto"></em>
            </div>
        </div>
    </div>
@endif

<div class="row d-flex align dark-content-bg meta-video pt-4 pb-3 mb-4">
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
