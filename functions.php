<?php

if (file_exists($composer = __DIR__ . '/vendor/autoload.php'))
    require_once $composer;

define('PARENT_THEME_URI', get_template_directory_uri() . '/');
define('THEME_URI', get_stylesheet_directory_uri() . '/');
define('THEME_DIR', get_stylesheet_directory() . '/');
define('THEME_CSS', THEME_URI . 'assets/css/');
define('THEME_JS', THEME_URI . 'assets/js/');
define('THEME_IMGS', THEME_URI . 'assets/images/');
define('ACF_TO_REST_API_REQUEST_VERSION', 2);


$ChildBlocks = new \Blocks\ChildBlocks;

require_once(dirname(__FILE__) . '/classes/controllers/PA_ACF_HomeFields.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_ACF_PostFields.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_ACF_UserFields.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_Enqueue_Files.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_RewriteRules.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_Util.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_wp_rest_columnists_controller.class.php');
require_once(dirname(__FILE__) . '/classes/PA_Helpers.php');

add_action('after_setup_theme', array('ACF_To_REST_API', 'init'));


// CORE INSTALL
require_once(dirname(__FILE__) . '/core/PA_Theme_Noticias_Install.php');

add_action('after_setup_theme', function () {
    load_theme_textdomain('iasd', THEME_DIR . 'language/');
}, 9);


/**
 * Remove unused taxonomies
 */
add_action('wp_loaded', function () {
    unregister_taxonomy_for_object_type('xtt-pa-colecoes', 'post');
    unregister_taxonomy_for_object_type('xtt-pa-kits', 'post');
    unregister_taxonomy_for_object_type('post_tag', 'post');
    unregister_taxonomy_for_object_type('category', 'post');
    unregister_taxonomy_for_object_type('xtt-pa-materiais', 'post');
});

add_filter('blade/view/paths', function ($paths) {
    $paths = (array)$paths;

    $paths[] = get_stylesheet_directory();

    return $paths;
});

add_filter('template_include', function ($template) {
    $path = explode('/', $template);
    $template_chosen = basename(end($path), '.php');
    $template_chosen = str_replace('.blade', '', $template_chosen);
    $grandchild_template = dirname(__FILE__) . '/' . $template_chosen . '.blade.php';

    if (file_exists($grandchild_template)) :
        blade($template_chosen);
        return '';
    endif;

    return $template;
});

/**
 * Filter save post to get video length
 */
add_action('acf/save_post', function ($post_id) {

    $terms = get_the_terms(get_the_ID(), 'xtt-pa-format');

    if (empty($terms))
        return;

    if (get_post_type($post_id) != 'post' || $terms[0]->slug != 'video')
        return;

    $url = parse_url(get_field('embed_url', $post_id, false));
    $host = '';
    $id = '';

    if (empty($url))
        return;

    if (str_contains($url['host'], 'youtube') || str_contains($url['host'], 'youtu.be')) :
        $host = 'youtube';

        if (array_key_exists('query', $url)) :
            parse_str($url['query'], $params);
            $id = $params['v'];
        else :
            $id = str_replace('/', '', $url['path']);
        endif;
    elseif (str_contains($url['host'], 'vimeo')) :
        $host = 'vimeo';
        $id = str_replace('/', '', $url['path']);
    endif;

    if (!empty($host) && !empty($id))
        getVideoLength($post_id, $host, $id);
});

// Make JavaScript Asynchronous in Wordpress
add_filter('script_loader_tag', function ($tag, $handle) {
    $include = array('pa-child-script');

    if (is_admin() || !in_array($handle, $include))
        return $tag;

    $tag = str_replace(' src', ' defer src', $tag);

    return $tag;
}, 10, 2);


add_action('rest_api_init', function () {
    register_rest_field(
        array('post', 'press'),
        'terms',
        array(
            'get_callback'    => 'terms_callback',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field(
        'user',
        'avatar',
        array(
            'get_callback'    => 'avatar_callback',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field(
        'user',
        'column',
        array(
            'get_callback'    => 'column_callback',
            'update_callback' => null,
            'schema'          => null,
        )
    );
});

function terms_callback($post)
{
    return [
        'editorial' => !empty($editorial = getPostEditorial($post['id'])) ? $editorial->name : '',
        'format'    => !empty($format    = getPostFormat($post['id']))    ? $format->name    : '',
    ];
}

function avatar_callback($user)
{
    $img_id = get_field('user_avatar', 'user_' . $user['id']);

    $img_scr = array(
        'full'             => !empty($full    = wp_get_attachment_image_src($img_id, ''))             ? $full[0]    : '',
        'medium'           => !empty($medium  = wp_get_attachment_image_src($img_id, 'medium_large')) ? $medium[0]  : '',
        'small'            => !empty($small   = wp_get_attachment_image_src($img_id, 'thumbnail'))    ? $small[0]   : '',
        'pa-block-preview' => !empty($preview = wp_get_attachment_image_src($img_id, 'thumbnail')) ? $preview[0] : '',
        'pa-block-render'  => !empty($render  = wp_get_attachment_image_src($img_id, 'medium')) ? $render[0]  : '',
    );

    return $img_scr;
}

function column_callback($user)
{
    return [
        'name'    => !empty($name    = get_field('column_name',    'user_' . $user['id'])) ? $name    : '',
        'excerpt' => !empty($excerpt = get_field('column_excerpt', 'user_' . $user['id'])) ? $excerpt : '',
    ];
}


add_filter('option_show_avatars', '__return_false');

function mytheme_add_editor_styles()
{
    add_theme_support('editor-styles');
    add_editor_style('style-editor.css');
}
add_action('admin_init', 'mytheme_add_editor_styles');


add_filter("rest_prepare_post", 'prefix_title_entity_decode', 10, 1);
add_filter("rest_prepare_press", 'prefix_title_entity_decode', 10, 1);
function prefix_title_entity_decode($response)
{
    $data = $response->get_data();
    $data['title']['rendered'] = html_entity_decode($data['title']['rendered']);
    $response->set_data($data);
    return $response;
}

function clear_cf_cache()
{

    //RESET CF CACHE
    $url = "https://" . API_PA . "/clear-cache?zone=adventistas.dev";
    $json = file_get_contents($url);
    $obj = json_decode($json);
    unset($json, $obj);
}
add_action('acf/save_post', 'clear_cf_cache');


// Remover privilégio dos editores para adicionar termos
function restringir_adicionar_termos_editores($caps, $cap, $user_id)
{
    if ('manage_categories' === $cap) {
        $user = get_userdata($user_id);
        if (in_array('editor', (array) $user->roles)) {
            $caps = array('do_not_allow');
        }
    }

    return $caps;
}
add_filter('map_meta_cap', 'restringir_adicionar_termos_editores', 10, 3);

/**
 * Ajusta o limite do tamanho da imagem para 8000 pixels no WordPress.
 *
 * O WordPress introduziu um novo recurso a partir da versão 5.3 que impede 
 * o upload de imagens com mais de 2560 pixels (altura ou largura). 
 * O objetivo é evitar o uso de imagens muito grandes que possam impactar 
 * a performance do site. No entanto, em alguns casos, pode ser necessário 
 * ajustar este limite. A função `adjust_big_image_size_threshold` tem este propósito.
 *
 * @param int   $threshold  O limite padrão de tamanho da imagem em pixels (altura ou largura).
 *                          Padrão é 2560.
 * @param array $imagesize  Um array contendo a largura e altura da imagem em pixels.
 * @param mixed $file       O caminho para o arquivo da imagem sendo processada.
 *
 * @return int  Novo limite para o tamanho da imagem.
 */
function adjust_big_image_size_threshold($threshold, $imagesize, $file)
{
    // Ajusta o limite para 8000 pixels
    return 8000;
}

add_filter('big_image_size_threshold', 'adjust_big_image_size_threshold', 10, 3);
