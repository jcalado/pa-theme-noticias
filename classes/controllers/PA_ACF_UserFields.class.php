<?php

use Extended\ACF\Location;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\Url;

class PaAcfUserFields {

    public function __construct() {
        add_action('init', [$this, 'createACFFields']);
    }

    function createACFFields() {
        register_extended_field_group([
            'title'  => __('Additional Information','iasd'),
            'style'  => 'default',
            'fields' => [
                Image::make('Avatar', 'user_avatar')
                    // ->required()
                    ->returnFormat('id'), 
                Url::make('Facebook URL', 'facebook'),
                Url::make('Twitter URL', 'twitter'),
                Url::make('Instagram URL', 'instagram'),
            ],
            'location' => [
                Location::where('user_form', 'edit')->and('user_role', 'colunista'),
            ]
        ]);

        register_extended_field_group([
            'title'  => __('Column information', 'iasd'),
            'style'  => 'default',
            'fields' => [
                Text::make(__('Column name','iasd'), 'column_name')
                    ->required(),
                Textarea::make(__('Column excerpt', 'iasd'), 'column_excerpt')
                    ->required(),
            ],
            'location' => [
                Location::where('user_form', 'edit')->and('user_role', 'colunista')->and('user_role', '!=', 'administrator'),
            ]
        ]);
    }

}

$PaAcfUserFields = new PaAcfUserFields();
