<?php

use WordPlate\Acf\Fields\Number;
use WordPlate\Acf\Fields\Oembed;
use WordPlate\Acf\Fields\Text;
use WordPlate\Acf\Location;
use WordPlate\Acf\Fields\File;

class PaAcfPostFields {

    public function __construct() {
        add_action('init', [$this, 'createACFFields']);
    }

    function createACFFields() {
        // ACF fields pra formato de áudio e video
        register_extended_field_group([
            'title' => __('Áudio ou Video info','iasd'),
            'style' => 'default',
            'fields' => [
                Oembed::make(__('Áudio ou video','iasd'), 'embed_url'),
                Number::make(__('Lenght','iasd'), 'embed_length')
                    ->instructions(__('It will be obtained when saving the post.','iasd'))
                    ->readOnly(),
            ],
            'location' => [
                Location::if('post_type', 'post'),
            ]
        ]);

        register_extended_field_group([
            'title'      => __('Author info', 'iasd'),
            'style'      => 'default',
            'position'   => 'side',
            'fields'     => [
                Text::make(__('Author', 'iasd'), 'custom_author'),
            ],
            'location'   => [
                Location::if('post_type', 'post'),
            ]
        ]);
    }

}

$PaAcfPostFields = new PaAcfPostFields();
