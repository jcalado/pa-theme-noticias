<?php

use Log1x\AcfPhoneNumber\PhoneNumberField;

/**
 *
 * Bootloader Install
 *
 */

class PAThemeNoticiasInstall
{

	public function __construct()
	{
		add_action('after_setup_theme', array($this, 'installRoutines'), 11);
		add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
        add_action('enqueue_block_editor_assets', array($this, 'gutenbergCustomAssets') );
		add_action('after_setup_theme', array($this, 'removePostFormats'), 100);
		add_filter('manage_edit-press_columns', array($this, 'removeFakeColumn'));
		add_action('init', array($this, 'addCustomRoles'));
		add_action('widgets_init', array($this, 'setWidgets'), 11);
		add_action('init', array($this, 'changePostObjectLabel'));
		add_action('admin_menu', array($this, 'changePostMenuLabel'));
		add_filter('manage_post_posts_columns', array($this, 'removeColumns'), 10001);
		add_action('init', array($this, 'addTaxOnPress') );
	}

	function installRoutines()
	{
		/**
		 *
		 * SALA DE IMPRENSA
		 *
		 */
		$labels = array(
			'name'                  => __('Press', 'iasd'),
			'singular_name'         => __('Press', 'iasd'),
			'menu_name'             => __('Press', 'iasd'),
			'name_admin_bar'        => __('Add item', 'iasd'),
			'add_new'               => __('Add New', 'iasd'),
			'add_new_item'          => __('Add New Item', 'iasd'),
			'new_item'              => __('New item', 'iasd'),
			'edit_item'             => __('Edit item', 'iasd'),
			'view_item'             => __('View item', 'iasd'),
			'all_items'             => __('All items', 'iasd'),
			'search_items'          => __('Search item', 'iasd'),
			'not_found'             => __('No press found.', 'iasd'),
			'not_found_in_trash'    => __('No press found in Trash.', 'iasd'),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => ['slug' => sanitize_title(__('press-room-slug', 'iasd'))],
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
			// 'taxonomies'         => array( 'category', 'post_tag' ),
			'show_in_rest'       => true,
		);

		register_post_type('press', $args);

		/**
		 *
		 * FORMATO DE POST
		 *
		 */

		$labels = array(
			'name'              => __('Post format', 'iasd'),
			'singular_name'     => __('Post format', 'iasd'),
			'search_items'      => __('Search item', 'iasd'),
			'all_items'         => __('All items', 'iasd'),
			'edit_item'         => __('Edit item', 'iasd'),
			'update_item'       => __('Update item', 'iasd'),
			'add_new_item'      => __('Add new item', 'iasd'),
			'new_item_name'     => __('New item', 'iasd'),
			'menu_name'         => __('Post format', 'iasd'),
		);
		$args   = array(
			'hierarchical'       => true, // make it hierarchical (like categories)
			'labels'             => $labels,
			//'show_ui'            => checkRole('administrator'),
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'show_in_rest'       => true, // add support for Gutenberg editor
			'rewrite'            => ['slug' => sanitize_title(__('xtt-pa-format-slug', 'iasd'))],
			'default_term'		=> array(
				'name' => 'Notícia',
				'slug'	=> 'noticia'
			)
		);

		register_taxonomy('xtt-pa-format', ['post'], $args);

		/**
		 *
		 * FORMATO DE POST
		 *
		 */

		$labels = array(
			'name'              => __('Press type', 'iasd'),
			'singular_name'     => __('Press type', 'iasd'),
			'search_items'      => __('Search items', 'iasd'),
			'all_items'         => __('All items', 'iasd'),
			'edit_item'         => __('Edit items', 'iasd'),
			'update_item'       => __('Update item', 'iasd'),
			'add_new_item'      => __('Add new item', 'iasd'),
			'new_item_name'     => __('New item', 'iasd'),
			'menu_name'         => __('Press type', 'iasd'),
		);
		$args = array(
			'hierarchical'       => true, // make it hierarchical (like categories)
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'show_in_rest'       => true, // add support for Gutenberg editor
			'rewrite'            => ['slug' => sanitize_title(__('xtt-pa-press-type-slug', 'iasd'))],
			'capabilities' 		  => array(
				'edit_terms' 	  => false,
				'delete_terms'    => false,
			),
		);

		register_taxonomy('xtt-pa-press-type', ['press'], $args);

		/**
		 *
		 * REGIÃO
		 *
		 */

		$labels = array(
			'name'          => __('Region', 'iasd'),
			'singular_name' => __('Region', 'iasd'),
			'search_items'  => __('Search item', 'iasd'),
			'all_items'     => __('All items', 'iasd'),
			'edit_item'     => __('Edit item', 'iasd'),
			'update_item'   => __('Update item', 'iasd'),
			'add_new_item'  => __('Add new item', 'iasd'),
			'new_item_name' => __('New item', 'iasd'),
			'menu_name'     => __('Regions', 'iasd'),
		);

		$args   = array(
			'hierarchical'       => true, // make it hierarchical (like categories)
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'show_in_rest'       => true, // add support for Gutenberg editor
			'rewrite'            => ['slug' => sanitize_title(__('xtt-pa-regiao-slug', 'iasd'))],
			// 'capabilities' 		 => array(
			// 	'edit_terms' 	 => false,
			// 	'delete_terms'   => false,
			// ),
		);

		// register_taxonomy('xtt-pa-regiao', ['post'], $args);

		foreach (['acf/include_field_types', 'acf/register_fields'] as $hook) {
			add_filter($hook, function () {
				return new PhoneNumberField(
					get_stylesheet_directory_uri() . '/vendor/log1x/acf-phone-number/public',
					get_stylesheet_directory() . '/vendor/log1x/acf-phone-number/public'
				);
			});
		}
	}

	function enqueueAssets()
	{
		global $current_screen;

		if ($current_screen->id == 'user-edit')
			wp_enqueue_media();

		if ($current_screen->id != 'post' && $current_screen->id != 'edit-post')
			return;

		wp_enqueue_script(
			'adventistas-noticias-admin',
			get_stylesheet_directory_uri() . '/assets/scripts/admin.js',
			array('wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'lodash'),
			null,
			false
		);
	}

	function gutenbergCustomAssets() {
        wp_enqueue_script(
            'adventistas-noticias-admin-metabox',
            get_stylesheet_directory_uri() . '/assets/scripts/admin-metabox.js',
            null,
            true
        );

        wp_enqueue_style(
            'adventistas-noticias-admin-metabox',
            get_stylesheet_directory_uri() . '/assets/css/admin-metabox.css'
        );
    }
	    
	function addTaxOnPress()
    	{
        	register_taxonomy_for_object_type('xtt-pa-editorias', 'press');
		register_taxonomy_for_object_type('xtt-pa-owner', 'press');
   	 }

	function removePostFormats()
	{
		remove_theme_support('post-formats');
	}

	function addCustomRoles()
	{
		add_role(
			'colunista',
			__('Columnist', 'iasd'),
			array(
				'level_1' => true,
				'read' => true,
			)
		);
	}

	function setWidgets()
	{
		register_sidebar(array(
			'name'          => __('Archive columnists', 'iasd'),
			'id'            => 'archive-authors',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
		));

		register_sidebar(array(
			'name'          => __('Columnist', 'iasd'),
			'id'            => 'author',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
		));

		register_sidebar(array(
			'name'          => __('Archive press', 'iasd'),
			'id'            => 'front-press',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
		));

		unregister_sidebar('index');
		unregister_sidebar('single');
	}

	function removeFakeColumn($posts_columns)
	{
		unset($posts_columns['fake']);

		return $posts_columns;
	}

	public static function changePostMenuLabel()
	{
		global $menu;
		global $submenu;
		$menu[5][0] = __('News', 'iasd');
		$submenu['edit.php'][5][0] = __('News', 'iasd');
		$submenu['edit.php'][10][0] = __('Add news', 'iasd');
		echo '';
	}

	public static function changePostObjectLabel()
	{
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$wp_post_types['post']->label = $labels->name = __('News', 'iasd');
		$labels->singular_name = __('News', 'iasd');
		$labels->add_new = __('Add news', 'iasd');
		$labels->add_new_item = __('Add news', 'iasd');
		$labels->edit_item = __('Edit', 'iasd');
		$labels->new_item = __('News', 'iasd');
		$labels->view_item = __('View news', 'iasd');
		$labels->search_items = __('Search news', 'iasd');
		$labels->not_found = __('No news found', 'iasd');
		$labels->not_found_in_trash = __('No news in the trash', 'iasd');
	}

	function removeColumns($columns)
	{
		unset($columns['editor']);
		return $columns;
	}
}

$PAThemeNoticiasInstall = new PAThemeNoticiasInstall();
