@extends('layouts.app')

@php setup_postdata(get_post()); @endphp

@php
    $terms = get_the_terms(get_the_ID(), 'xtt-pa-format' );
    $format_slug = null;
    if(!empty($terms) ) {
        $term = array_shift($terms);
        $format_slug = $term->slug;
    }
@endphp

@section('content')
    <div class="{{ $format_slug == 'audio' || $format_slug == 'video' ? 'pa-content-container' : 'pa-content-container pt-5 mt-3' }}">
        <div class="container">
            <div class="row justify-content-center">
                {{-- Main --}}
                <article class="col-12 col-md-8">
                    {{-- Post header --}}
                    @include('template-parts.single.header', array('format_slug' => $format_slug))

                    {{-- Conteúdo do post --}}
                    <div class="pa-content">
                        @if (!empty($format_slug))

                            {{-- Include do template baseado no formato do post --}}
                            @switch($format_slug)
                                @case('video')
                                    @include('template-parts.single.video-format')
                                    @break

                                @case('audio')
                                    @include('template-parts.single.audio-format')
                                    @break

                                @default
                                    @break
                            @endswitch
                            {{-- Fim do include --}}

                        @endif

                        {!! the_content() !!}
                    </div>

                    <div class="pa-break d-block my-5 py-2"></div>

                    {{-- Post footer --}}
                    @include('template-parts.single.footer')
                </article>
            </div>
        </div>
    </div>
@endsection
