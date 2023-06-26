@php
    $format_slug = !empty($format = getPostFormat(get_the_ID())) ? $format->slug : 'noticia';
@endphp

<header class="{{ $format_slug == 'audio' || $format_slug == 'video' ? 'post-header pt-5 pb-4 post-header-'.$format_slug : 'post-header mb-4' }}">

    <h1 class="fw-bold mb-3 {{ $format_slug ? 'title-'.$format_slug : '' }}">{!! single_post_title() !!}</h1>

    @if($format_slug != 'audio' && $format_slug != 'video')

        <h2 class="mb-3 pb-3">{!! \Illuminate\Support\Str::of(get_the_excerpt())->limit(250) !!}</h3>

            @include('components.metas.author', array( 'id' => get_the_ID()))

            @include('components.metas.meta')

            <hr class="my-45">

        @include('components.metas.share')

    @endif
</header>
