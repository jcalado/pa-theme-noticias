<?php

/** 
 * videoLength Format video length in 'mm:ss'
 *
 * @param  int $post_id The post ID
 * @return string Formated length string
 */
function videoLength(int $post_id = 0): string
{
    $length = get_field('video_length', !empty($post_id) ? $post_id : get_the_ID());

    if (empty($length))
        return "";

    if ($length / 3600 >= 1)
        return sprintf('%02d:%02d:%02d', ($length / 3600), ($length / 60 % 60), $length % 60);
    else
        return sprintf('%02d:%02d', ($length / 60 % 60), $length % 60);
}

/**
 * getVideoLength Get video length and save data
 *
 * @param  int    $post_id The post ID
 * @param  string $video_host The video host
 * @param  string $video_id The video ID
 * @return void
 */
function getVideoLength(int $post_id, string $video_host, string $video_id): void
{
    $json = file_get_contents("https://api.feliz7play.com/v4/{$video_host}info?video_id={$video_id}");
    $obj = json_decode($json);

    if (!empty($obj))
        update_field('embed_length', $obj->time, $post_id);
}

/**
 * getPostFormat Get the post format
 *
 * @param string $post_id The post ID
 * @return mixed
 */
function getPostFormat($post_id)
{
    if ($term = get_the_terms($post_id, 'xtt-pa-format')) {
        return $term[0];
    }

    if ($term = get_the_terms($post_id, 'xtt-pa-press-type'))
        return $term[0];

    return null;
}

/**
 * getPostEditorial Get the post editorial
 *
 * @param string $post_id The post ID
 * @return mixed
 */
function getPostEditorial($post_id)
{
    if (!is_wp_error($term = get_the_terms($post_id, 'xtt-pa-editorias')))
        return $term[0];

    return null;
}

/**
 * getPostRegion Get the post region
 *
 * @param string $post_id The post ID
 * @return mixed
 */
function getPostRegion($post_id)
{
    if (!is_wp_error($term = get_the_terms($post_id, 'xtt-pa-regiao')))
        return $term[0];

    return null;
}

/**
 * Search the first priority seat of the post
 *
 * @param string $post_id The post ID
 * @return string
 */
function getPrioritySeat($post_id): string
{
    if ($term = get_the_terms($post_id, 'xtt-pa-owner'))
        return $term[0]->name;

    return 'Não há nenhuma sede proprietária vinculada a este post.';
}

/**
 * Search the related posts
 *
 * @param string $post_id The post ID
 * @param int $limit Maximum posts per query. Default = 6
 * @return array
 */
function getRelatedPosts($post_id, $limit = 6): array
{
    if ($term = get_the_terms($post_id, 'xtt-pa-projetos')) :
        $args = array(
            'post_type'      => 'post',
            'post__not_in'   => array($post_id),
            'posts_per_page' => $limit,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'xtt-pa-projetos',
                    'terms'    => $term[0]->name,
                ),
            ),
        );

        return get_posts($args);
    endif;

    return array();
}


function getHeaderTitle($post_id = NULL)
{

    if (is_page())
        return the_title();

    $format = getPostFormat(get_the_ID());
    if (!empty($format)) {
        if (is_archive() && is_author() || $format->slug == 'coluna') //is archive
            return __('Column', 'iasd') . ' | ' . (is_author() ? get_queried_object()->display_name : get_the_author_meta('display_name'));
    }

    if (get_post_type(get_the_ID()) == 'press')
        return __('Press room', 'iasd') . ' | ' . getPostFormat(get_the_ID())->name;

    if (is_singular('post')) { //is single
        $nameEditorial = getPostEditorial($post_id);
        // Caso a editoria retorna vazio, útil para noticias antigas onde não era obrigatório marcar uma editória.
        if (!empty($nameEditorial)) {
            return getPostEditorial($post_id)->name;
        }
    }

    if (is_archive()) //is archive
        return get_taxonomy(get_queried_object()->taxonomy)->label . ' | ' . get_queried_object()->name;

    return the_title();
}

/**
 * Search the first department of the post
 *
 * @param string $post_id The post ID
 * @return mixed
 */
function getDepartment($post_id)
{
    if ($term = get_the_terms($post_id, 'xtt-pa-departamentos'))
        return $term[0];

    return null;
}

add_filter('jetpack_offline_mode', '__return_true');

function unsetJetPackModules($modules)
{
    // unset( $modules['carousel'] );
    unset($modules['comment-likes']);
    unset($modules['comments']);
    unset($modules['contact-form']);
    unset($modules['copy-post']);
    unset($modules['custom-content-types']);
    unset($modules['custom-css']);
    unset($modules['enhanced-distribution']);
    unset($modules['google-analytics']);
    unset($modules['gravatar-hovercards']);
    unset($modules['infinite-scroll']);
    unset($modules['json-api']);
    unset($modules['latex']);
    unset($modules['lazy-images']);
    unset($modules['likes']);
    unset($modules['markdown']);
    unset($modules['masterbar']);
    unset($modules['monitor']);
    unset($modules['notes']);
    unset($modules['photon']);
    unset($modules['photon-cdn']);
    unset($modules['post-by-email']);
    unset($modules['protect']);
    unset($modules['publicize']);
    unset($modules['related-posts']);
    unset($modules['search']);
    unset($modules['seo-tools']);
    unset($modules['sharedaddy']);
    unset($modules['shortlinks']);
    unset($modules['sitemaps']);
    unset($modules['sso']);
    unset($modules['stats']);
    unset($modules['subscriptions']);
    // unset( $modules['tiled-gallery'] );
    unset($modules['verification-tools']);
    unset($modules['videopress']);
    // unset( $modules['widget-visibility'] );
    // unset( $modules['widgets'] );
    unset($modules['woocommerce-analytics']);
    unset($modules['wordads']);
    unset($modules['shortcodes']);
    return $modules;
}
add_filter('jetpack_get_available_modules', 'unsetJetPackModules');

function checkRole($user_role = "")
{
    $user = wp_get_current_user();
    if (array_intersect([strtolower($user_role)], $user->roles))
        return true;

    return false;
}


/**
 * Get current post author and make some normalizations.
 *
 * @param string $post_id The post ID
 * @return string
 */
function getCurrentAuthor($post_id)
{
    if (!empty($custom_author = get_field('custom_author'))) {
        $author = $custom_author;
    } else {
        $author = get_the_author();
    }

    if (str_contains($author, '.')) {
        $author = explode('.', $author);
        return ucfirst($author[0]) . ' ' . ucfirst($author[1]);
    } else {
        return $author;
    }
}
