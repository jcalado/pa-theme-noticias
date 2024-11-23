@php
    $sidebar = isset($sidebar) && !empty($sidebar) ? $sidebar : 'front-page';
    $sede = get_field('context');
@endphp

<div class="pa-content py-3 mt-1 mt-md-4">
	<div class="container">
        @include('template-parts.featured-posts', [
            'post_type' => isset($post_type) && !empty($post_type) ? $post_type : 'post',
        ])

        <div class="row">
            <div class="pa-news-content col-12{{ is_active_sidebar($sidebar) ? ' col-md-8' : '' }}">
                @php 
                    global $exclude; 
                    $args = '_fields=featured_media_url.pa-block-render,title,excerpt,link,terms&exclude=' . implode(',', $exclude);
                    if (!empty($sede) && !is_wp_error($sede)){
                        $args .= "&xtt-pa-sedes-tax=". $sede->slug;
                    }
                @endphp

                <load-more 
                    template="card-post" 
                    url="{{ isset($api_url) && !empty($api_url) ? $api_url : get_rest_url(null, 'wp/v2/posts') }}"
                    args="{{ $args }}"
                    nonce="{{ wp_create_nonce('wp_rest') }}"
                >
                    <template id="card-post">
                        <card-post *foreach="@{{this.items}}" .post="@{{item}}"></card-post>
                    </template>
                </load-more>
                <div class="d-flex justify-content-center mt-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">{{__('Loading...', 'iasd')}}</span>
                    </div>
                  </div>
            </div>

            @if(is_active_sidebar($sidebar))
                <aside class="col-md-4 d-none d-xl-block">
                    @php(dynamic_sidebar($sidebar))
                </aside>
            @endif
        </div>
    </div>
</div>