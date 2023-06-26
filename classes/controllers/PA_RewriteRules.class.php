<?php

class PaRewriteRules
{
    
    /**** Action e Filter de "Init" ****/

    public function __construct()
    {   

        // Rewrite para posts de Press Room
        add_action('init', [$this, 'RewritePostPressRoom'], 100);

        // Rewrite para terms de Press Room Type
        add_action('init', [$this, 'RewriteTermPressRoomType'], 100);

        // Rewrite para posts de noticias
        add_action('init', [$this, 'RewritePostNews'], 100);

        // Rewrite para posts de Colunistas
        add_action('init', [$this, 'RewritePostColumns'], 100);

        // Rewrite para archive de Author
        add_action('init', [$this, 'RewriteAuthor'], 100);

        // Auterando permalink de Author
        add_action('init', [$this, 'ChangeAuthorPermalink'], 100);

        // Editar o permalink de PressRoom
        add_filter('post_type_link', [$this, 'PrePostLink'], 10, 3);

        // Editar o permalink de Posts
        add_filter('pre_post_link', [$this, 'PrePostLink'], 10, 3);
    }

    /**** Alterando Rewrites ****/
    
    public static function RewritePostPressRoom()
    {
        $permalink = sanitize_title(__('press-room-slug','iasd')) . '/%tipo%/%postname%/';
        $permalink = str_replace('%tipo%', '([^/]+)', $permalink);
        $permalink = str_replace('%postname%', '([^/]+)', $permalink);
        $permalink .= '?$';
        $rewrite_redirect = 'index.php?post_type=press&name=$matches[2]&xtt-pa-press-type=$matches[1]';
        $permalink = add_rewrite_rule($permalink, $rewrite_redirect, 'top');
        flush_rewrite_rules();
    }

    public static function RewriteTermPressRoomType()
    {
        $permalink = sanitize_title(__('press-room-slug','iasd')) . '/%tipo%/';
        $permalink = str_replace('%tipo%', '([^/]+)', $permalink);
        $permalink .= '?$';
        $rewrite_redirect = 'index.php?term=$matches[1]&xtt-pa-press-type=$matches[1]&post_type=press';
        $permalink = add_rewrite_rule($permalink, $rewrite_redirect, 'top');
        flush_rewrite_rules();
    }

    public static function RewritePostNews()
    {
        $permalink = sanitize_title(__('news-slug','iasd')) . '/%editoria%/%postname%/';
        $permalink = str_replace('%editoria%', '([^/]+)', $permalink);
        $permalink = str_replace('%postname%', '([^/]+)', $permalink);
        $permalink .= '?$';
        $rewrite_redirect = 'index.php?post_type=post&name=$matches[2]&xtt-pa-editorias=$matches[1]';
        $permalink = add_rewrite_rule($permalink, $rewrite_redirect, 'top');
        flush_rewrite_rules(); 
    }

    public static function RewritePostColumns()
    {
        $permalink = sanitize_title(__('columns-slug','iasd')) . '/%author%/%postname%/';
        $permalink = str_replace('%author%', '([^/]+)', $permalink);
        $permalink = str_replace('%postname%', '([^/]+)', $permalink);
        $permalink .= '?$';
        $rewrite_redirect = 'index.php?name=$matches[2]&author=$matches[1]';
        $permalink = add_rewrite_rule($permalink, $rewrite_redirect, 'top');
        flush_rewrite_rules();
    }

    public static function RewriteAuthor()
    {
        $permalink = sanitize_title(__('columns-slug','iasd')) . '/%author%/';
        $permalink = str_replace('%author%', '([^/]+)', $permalink);
        $permalink .= '?$';
        $rewrite_redirect = 'index.php?author_name=$matches[1]';
        $permalink = add_rewrite_rule($permalink, $rewrite_redirect, 'top');
        flush_rewrite_rules();
    }

    /**** Alterando Permalinks ****/

    public static function PrePostLink($permalink, $post)
    {

        if (is_object($post) && $post->post_type == 'post') {
            $original = get_option('permalink_structure');
            if ($permalink == $original) {
                $editorias = get_the_terms($post->ID, 'xtt-pa-editorias');
                $post_format = get_the_terms($post->ID, 'xtt-pa-format');
                $author = get_the_author_meta('user_login', $post->post_author);
                
                if(!empty($post_format)){
                    if ($post_format[0]->slug == 'coluna' || $post_format[0]->slug == 'columna') {
                        $permalink = str_replace('/%postname%/', sanitize_title(__('columns-slug','iasd')) . '/' . $author . '/%postname%/', $permalink);
                    }
                } else {
                    if (!empty($editorias)) {
                        $permalink = str_replace('/%postname%/', sanitize_title(__('news-slug','iasd')) . '/' .  $editorias[0]->slug . '/%postname%/', $permalink);
                    }
                }
            }
        }

        if (is_object($post) && $post->post_type == 'press') {

            $p_name=$post->post_name;
            $tipos = get_the_terms($post->ID, 'xtt-pa-press-type');
            $tipo = $tipos[0]->slug;
            $permalink = home_url( sanitize_title(__('press-room-slug','iasd')) . '/' . $tipo . "/" . $p_name . "/");
        }

        return $permalink;
    }

    public static function ChangeAuthorPermalink($vars) 
    {
        global $wp_rewrite;
        $wp_rewrite->author_base = sanitize_title(__('columns-slug','iasd'));
        $wp_rewrite->flush_rules();
    }
}

/* Iniciando a Classe */
$PaRewriteRules = new PaRewriteRules();
