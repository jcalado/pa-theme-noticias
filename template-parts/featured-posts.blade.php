@php
    global $exclude;
    $count = get_field('featured_layout');
    $count = !empty($count) ? $count : 1;
    $items = [];
    $data  = get_field('featured_items');
    
    if(isset($data['data'])):
        $items = array_slice($data['data'], 0, $count);

        $items = array_column($items, 'id');
    endif;

    $exclude = $items;
@endphp

@notempty($items)
    <div class="row pa-widget pa-w-featured-post position-relative pb-0 mb-md-5 pb-md-2">
        @foreach($items as $item)
            @if(count($items) == 3)
                @if($loop->index == 0)
                    <div class="col-md-8 pe-md-2">
                @elseif($loop->index == 1)
                    <div class="col-md-4">
                @endif
            @endif

            <div class="col-md-{{ count($items) == 1 || count($items) == 3 ? '12' : '6' }}">
                @php
                    $class = '';

                    if((count($items) == 3 && $loop->index == 1) || count($items) == 1)
                        $class = ' mb-3 pb-1 pb-md-0';
                    elseif(count($items) > 1)
                        $class = ' mb-3 mb-md-0 pb-1 pb-md-0';
                    else
                        $class = ' mb-3 pb-md-3 mb-md-4';
                @endphp

                <div class="pa-blog-itens{{ $class }}">
                    <div class="pa-blog-feature">
                        <a href="{{ get_the_permalink($item) }}" title="{{ get_the_title($item) }}">
                            <div class="ratio {{ count($items) == 1 ? 'ratio-591x244' : 'ratio-16x9' }}">
                                <figure class="figure m-xl-0 w-100">
                                    <img src="{{ check_immg($item, 'full') }}" class="figure-img img-fluid m-0 rounded w-100 h-100 object-cover" alt="{{ get_the_title($item) }}" />

                                    <figcaption class="figure-caption position-absolute w-100 p-3 rounded-bottom">
                                        @notempty(getPostFormat($item))

                                            @if (sanitize_title(getPostFormat($item)->name) == 'video')
                                                <span class="pa-tag-icon d-inline-block pag-tag-icon-video"><i class="fas fa-play"></i></span>
                                            @endif

                                            @if (sanitize_title(getPostFormat($item)->name) == 'audio')
                                                <span class="pa-tag-icon d-inline-block pag-tag-icon-audio"><i class="fas fa-headphones-alt"></i></span>
                                            @endif

                                            <span class="pa-tag rounded-1 text-uppercase mb-2 d-inline-block px-2">{{ getPostFormat($item)->name }}</span>
                                        @endnotempty

                                        <h3 class="h5 pt-2 pa-truncate-2">{!! get_the_title($item) !!}</h3>
                                    </figcaption>
                                </figure>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            @if(count($items) == 3 && $loop->index == 0 || count($items) == 3 && $loop->index == 2)
                </div>
            @endif
        @endforeach
    </div>
@endnotempty
