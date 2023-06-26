<?php

namespace Blocks\PAContact;

use Blocks\Block;
use ExtendedLocal\PhoneNumber;
use Extended\ACF\Fields\Email;
use Extended\ACF\Fields\Text;

/**
 * Class PAContact
 * @package Blocks\PAContact
 */
class PAContact extends Block {

    public function __construct() {
		// Set block settings
        parent::__construct([
            'title' 	  => __('IASD - News - Contact', 'iasd'),
            'description' => __('Contact block', 'iasd'),
            'category' 	  => 'pa-adventista',
			'keywords' 	  => ['contact', 'text'],
			'icon' 		  => 'format-chat',
        ]);
    }
	
	/**
	 * setFields Register ACF fields with WordPlate/Acf lib
	 *
	 * @return array Fields array
	 */
	protected function setFields(): array {
		return [
			Text::make(__('Title', 'iasd'), 'title')
				->defaultValue(__('IASD - Noticias - Contact', 'iasd')),

			Text::make(__('Description', 'iasd'), 'description')
				->defaultValue(__('IASD - Noticias - Contact', 'iasd')),

			Email::make(__('Email', 'iasd'), 'email'),

			PhoneNumber::make(__('Phone', 'iasd'), 'phone')
		];
	}
	    
    /**
     * with Inject fields values into template
     *
     * @return array
     */
    public function with(): array {
        return [
            'title'  	  => get_field('title'),
			'description' => get_field('description'),
			'email' 	  => get_field('email'),
			'phone'    	  => get_field('phone'),
        ];
    }

}