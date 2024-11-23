<?php

namespace Blocks\PAListColumnists;

use Blocks\Block;
use Fields\MoreContent;
use Extended\ACF\Fields\Number;
use Extended\ACF\Fields\Text;

/**
 * Class PAListColumnists
 * @package Blocks\PAListColumnists
 */
class PAListColumnists extends Block {

    public function __construct() {
		// Set block settings
        parent::__construct([
            'title' 	  => __('IASD - News - Columnists List', 'iasd'),
            'description' => __('List columnists', 'iasd'),
            'category' 	  => 'pa-adventista',
			'keywords' 	  => ['list', 'posts'],
			'icon' 		  => 'admin-users',
        ]);
    }
	
	/**
	 * setFields Register ACF fields with WordPlate/Acf lib
	 *
	 * @return array Fields array
	 */
	protected function setFields(): array {
		return array_merge(
			[
				Text::make('Título', 'title')
					->defaultValue(__('IASD - List columnists', 'iasd')),

				Number::make(__('Quantity', 'iasd'), 'count')
					->min(1)
					->defaultValue(4)
			],
			MoreContent::make()
		);
	}
	    
    /**
     * with Inject fields values into template
     *
     * @return array
     */
    public function with(): array {
		add_action('pre_user_query', function($query) {
			if($query->get('id') == 'pa-list-columnists' && $query->query_vars['orderby'] == 'rand')
				$query->query_orderby = str_replace('user_login', 'RAND()', $query->query_orderby);
		});

		$args = [
			'id'				  => 'pa-list-columnists',
			'role' 				  => 'colunista',
			'number' 			  => get_field('count'),
			'has_published_posts' => ['post'],
			'orderby'        	  => 'rand',
			'fields' 			  => 'ID',
		];

		if(is_a(get_queried_object(), 'WP_User'))
			$args['exclude'] = [get_queried_object()->ID];

		$query = new \WP_User_Query($args);

        return [
            'title'  	   => get_field('title'),
			'items' 	   => $query->results,
			'enable_link'  => get_field('enable_link'),
			'link'    	   => get_field('link'),
        ];
    }

}