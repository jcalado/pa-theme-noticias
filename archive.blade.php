@extends('layouts.app')

@section('content')
	@php
		global $wp_query, $queryFeatured;
		
		
	@endphp

	<div class="pa-content py-5">
		<div class="container">
			<div class="row justify-content-center">
				<section class="col-12 col-md-8">

					@php
						if(!empty($queryFeatured->found_posts)):
							if(get_query_var('paged') < 1 && $queryFeatured->found_posts > 0):
							get_template_part('template-parts/global/feature', 'feature', [
								'post' => $queryFeatured->posts[0],
								'tag'  => $format = get_post_format($queryFeatured->posts[0]) ? : __('News', 'iasd'),
							]); 
							endif;
						endif;
					@endphp
					
					{{-- @includeWhen(get_query_var('paged') < 1 && $queryFeatured->found_posts > 0, 'template-parts.global.feature', [
						'post'   => $queryFeatured->posts[0],
						'format' => !empty($format = getPostFormat($queryFeatured->posts[0]->ID)) ? $format->name : '',
					]) --}}

					@php

						if($wp_query->found_posts >= 1):
					@endphp

					<div class="pa-blog-itens my-5">
						@php
							foreach($wp_query->posts as $post):
								$categories = get_the_category($post->ID);
								get_template_part('template-parts/global/card-post', 'card-post', [
									'post'     => $post,
									'category' => !empty($categories) ? $categories[0]->name : '',
									'format'   => get_post_format($post) ? : __('News', 'iasd'),
								]); 
							endforeach; 
						@endphp
					</div>
					@php
			 			endif; 
					@endphp

					{{-- @if($wp_query->found_posts >= 1)
						<div class="pa-blog-itens my-5">
							@foreach($wp_query->posts as $post)
								@include('template-parts.global.card-post', [
									'post'     => $post,
									'category' => !empty($editorial = getPostEditorial($post->ID)) ? $editorial->name : '',
									'format'   => !empty($format = getPostFormat($post->ID)) ? $format->name : '',
								])
							@endforeach
						</div>
					@endif --}}
					
					<div class="pa-pg-numbers row">
						@php( PaThemeHelpers::pageNumbers())
					</div>
				</section>

				@if(is_active_sidebar('archive'))
					<aside class="col-md-4 d-none d-xl-block">
						@php(dynamic_sidebar('archive'))
					</aside>
				@endif
			</div>
		</div>
	</div>
@endsection
