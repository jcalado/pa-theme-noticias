<?php

use ExtendedLocal\LocalData;
use Extended\ACF\Location;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\ButtonGroup;
use Extended\ACF\Fields\Taxonomy;

class PaAcfHomeFields
{

    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    function init()
    {
        $this->createACFFields('page-front-page.blade.php', 'post', ['xtt-pa-sedes', 'xtt-pa-editorias', 'xtt-pa-projetos']);
        $this->createACFFields('page-press-room.blade.php', 'press', []);
        $this->createACFFieldsContext();  
    }

    function createACFFields($template, $postType, $taxonomies)
    {
        register_extended_field_group([
            'title' => __('Features','iasd'),
            'key'   => "featured_{$postType}",
            'style' => 'default',
            'fields' => [
                ButtonGroup::make(__('Model','iasd'), 'featured_layout')
                    ->instructions(__('This selection will influence the amount of items displayed in the highlight..', 'iasd'))
                    ->choices([
                        1 => '1 post',
                        2 => '2 posts',
                        3 => '3 posts',
                    ])
                    ->defaultValue(1),
                LocalData::make(__('Featured posts', 'iasd'), "featured_items")
                    ->instructions("")
                    ->postTypes([$postType])
                    ->initialLimit(10)
                    ->filterTaxonomies($taxonomies)
                    ->manualItems(false)
                    ->limitFilter(false)
            ],
            'location' => [
                Location::where('page_template', '==', $template),
            ]
        ]);
    }

    function createACFFieldsContext(){
        register_extended_field_group([
            'title' => __('Headquarter','iasd'),
            'key'   => "context",
            'style' => 'default',
            'fields' => [
                Taxonomy::make(__('Headquarter','iasd'), 'context')
                    ->instructions(__('Select the regional headquarters to filter page contents on-page.', 'iasd'))
                    ->taxonomy('xtt-pa-sedes')
                    ->appearance('select')
                    ->required()
                    ->returnFormat('object')
            ],
            'location' => [
                Location::where('page_template', '==', 'page-front-page.blade.php'),
            ]
        ]);
    }

}

$PaAcfHomeFields = new PaAcfHomeFields();
