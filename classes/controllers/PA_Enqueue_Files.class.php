<?php

class PA_Enqueue_Files
{
	public function __construct()
	{
		add_action('wp_enqueue_scripts', [$this, 'RegisterChildAssets']);
		add_action('admin_enqueue_scripts', [$this, 'enqueueAssets'], 15);
	}

	public function RegisterChildAssets()
	{
		wp_enqueue_style('pa-child-style', get_stylesheet_uri());
		wp_enqueue_script('pa-child-script', get_stylesheet_directory_uri() . '/assets/js/script.js', array(), false, false);
		wp_localize_script(
			'pa-child-script',
			'pa',
			array(
				'url'   	=> get_rest_url(null, 'wp/v2/'),
				'nonce' 	=> wp_create_nonce('wp_rest'),
				'site_path' => parse_url(home_url(), PHP_URL_PATH) ?: '/',
			)
		);
	}

	function enqueueAssets()
	{
		wp_localize_script(
			'adventistas-admin',
			'iasd',
			array(
				'requiredTaxonomies' => [
					[
						'post_type'    => 'post',
						'taxonomies'   => [
							'xtt-pa-editorias',
							'xtt-pa-owner',
							'xtt-pa-sedes'
						],
					],
					[
						'post_type'    => 'press',
						'taxonomies'   => [
							'xtt-pa-editorias',
							'xtt-pa-owner'
						],
					]
				],
				'unregisterBlocks' => [
					'acf/p-a-magazines',
					'acf/p-a-list-news',
					'acf/p-a-carousel-downloads',
					'acf/p-a-list-downloads',
					'acf/p-a-carousel-ministry',
					'acf/p-a-list-buttons',
					'acf/p-a-list-items',
					'acf/p-a-list-icons',
					'acf/p-a-carousel-feature',
					// 'acf/p-a-list-videos'
				],
			)
		);
	}
}
$PA_Enqueue_Files = new PA_Enqueue_Files();
