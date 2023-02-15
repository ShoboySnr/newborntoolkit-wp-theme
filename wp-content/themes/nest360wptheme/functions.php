<?php

use NEST360WPTheme\custom_posts;
/**
 * Nest360 Theme WP functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Nest360_WP_Theme
 */

require __DIR__ . '/vendor/autoload.php';
define('NEST360_BASE_ROUTE', 'nest360/v1');
define('NEST360_GUTENBERG_STYLE_CSS', __DIR__.'/gutenberg/style.css');
define('NEST360_GUTENBERG_THEME_CSS', __DIR__.'/gutenberg/theme.css');
define('NEST360_GUTENBERG_CUSTOM_BLOCK_CSS', plugins_url('/build/style.css', 's14-collapsible-block/plugin.php'));
//define('NEST360_GUTENBERG_CUSTOM_BLOCK_JS', get_template_directory_uri().'/gutenberg/block.js');

\NEST360WPTheme\endpoints\Base::get_instance();
\NEST360WPTheme\wp_trigger\Trigger::get_instance();
//\NEST360WPTheme\wp_trigger\HtmlToPdf::get_instance();
\NEST360WPTheme\inc\Init::init();


if ( ! defined( '_S_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'nest360_theme_wp_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */

    function nest360_theme_wp_setup() {

        /*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
        add_theme_support( 'title-tag' );

        /*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
        add_theme_support( 'post-thumbnails' );

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(
            array(
                'primary_menu' => esc_html__( 'Primary Menu', 'nest360-wp-theme' ),
                'footer_menu' => esc_html__( 'Footer Menu', 'nest360-wp-theme' ),
            )
        );

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );

        //initialize rest api
        $instance = new \NEST360WPTheme\endpoints\Base();
        $instance->get_instance();
    }

endif;
add_action( 'after_setup_theme', 'nest360_theme_wp_setup' );

//change post to news
add_action( 'admin_menu', function() {
    global $menu;
    global $submenu;

    $menu[5][0] = 'News'; // Change Posts to Houses
    $submenu['edit.php'][5][0] = 'News';
    $submenu['edit.php'][10][0] = 'Add News';
});

add_action( 'init', function () {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;

    $labels->name = 'News';
    $labels->singular_name = 'News';
    $labels->add_new = 'Add News';
    $labels->add_new_item = 'Add News';
    $labels->edit_item = 'Edit News';
    $labels->new_item = 'News';
    $labels->view_item = 'View News';
    $labels->search_items = 'Search News';
    $labels->not_found = 'No News found';
    $labels->not_found_in_trash = 'No News found in Trash';
    $labels->all_items = 'All News';
    $labels->menu_name = 'News';
    $labels->name_admin_bar = 'News';

});

//hook into before creating a new post
add_action('save_post', function($post_id, $post) {
    $post_type = $post->post_type;
    $include_post_types = \NEST360WPTheme\endpoints\Utilities::get_instance()->implementation_toolkits_list();
    if('publish' == $post->post_status && in_array($post_type, $include_post_types)) {
        switch($post_type) {
            case 'toolkits':
                custom_posts\CustomPosts::get_instance()->auto_create_categories_content($post, ['reading_category'], $post_type);
                break;
            default:
                custom_posts\CustomPosts::get_instance()->auto_create_categories_content($post, ['reading_category', 'tools_category', 'in_practice_category'], $post_type);
        }
    }
},  10, 2);

//hook into when deleting a new post
add_action('trashed_post', function ($post_id) {
    $post = get_post($post_id);
    $post_type = $post->post_type;
    switch($post_type) {
        case 'toolkits':
            custom_posts\CustomPosts::get_instance()->auto_delete_categories_content($post, ['reading_category'], $post_type);
            break;
        default:
            custom_posts\CustomPosts::get_instance()->auto_delete_categories_content($post, ['reading_category', 'tools_category', 'in_practice_category'], $post_type);
    }

}, 10, 1);

//fix cors issue
add_action('init', function () {
    header('Access-Control-Allow-Origin: *');

    $implmentation_toolkits = \NEST360WPTheme\endpoints\Utilities::get_instance()->get_implementation_toolkits();
    $custom_post_fields = \NEST360WPTheme\endpoints\Utilities::get_instance()->get_custom_post_fields();

    $custom_fields = array_merge($implmentation_toolkits, $custom_post_fields);
    foreach($custom_fields as $custom_field) {
        register_taxonomy_for_object_type('post_tag', $custom_field['value']);
    }
});

//set default image on certain custom post types
add_filter( 'dfi_thumbnail_id', function($dfi_id, $post_id) {
    $post = get_post($post_id);
    if ( 'team' === $post->post_type ) {
        return 1080; // no image
    }
    return $dfi_id; // the original featured image id
}, 10, 2 );

//add url to the customizer field
add_action('customize_register', function ($wp_customize) {
    /**
     * Frontend URL
     *
     */
    $wp_customize->add_setting('frontend_url', array(
        'default' => 'http://nest360-ui.s14staging.co.uk/',
        'type' => 'theme_mod',
    ));

    $wp_customize->add_control('frontend_url', array(
        'label' => __('Frontend URL', 'http://nest360-ui.s14staging.co.uk/'),
        'section' => 'title_tagline',
        'settings' => 'frontend_url',
        'type' => 'url'
    ));
});

//redirect the user to the frontend
function redirect_user_to_frontend() {
    global $posts;

    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    $actual_link = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $url_array = parse_url($actual_link);
    $url_array['host'] = get_theme_mod('frontend_url');

    if (strpos($url_array['path'], 'about/', )) {
        $url_array['path'] = str_replace('about/', "", $url_array['path']);
    }

    //check for each post types
    $post_types = $posts[0]->post_type;
    $append_post_type_part = '';

    if($post_types == 'post') {
        $append_post_type_part = '/news';
    }

    if($posts[0]->post_status == 'draft') {
        $url_array['path'] = "/".$posts[0]->ID;
    }

    $url_array['path'] = $append_post_type_part.$url_array['path'];

    $new_url = $url_array['host'].$url_array['path'];

    return wp_redirect($new_url);
}

add_filter( 'the_content', function($the_content) {
  return $the_content.'<!-- Begin Theme Styles --> <style>'.file_get_contents(NEST360_GUTENBERG_STYLE_CSS).' '.file_get_contents(NEST360_GUTENBERG_THEME_CSS).'</style><!-- End Theme Styles --> ';
});

add_filter('get_terms_orderby', function($orderby, $args){
    return 't.menu_order';
}, 10, 2);

add_filter( 'post_link', function ($permalink, $post, $leavename) {
    if ( is_object( $post ) ) {
        if($post->post_type == 'post') {
            return str_replace ( $post->post_name , 'news/'.$post->post_name , $permalink );
        }
    }
    return $permalink;
}, 10, 3 );

add_filter('rest_url', 'wptips_home_url_as_api_url');
function wptips_home_url_as_api_url($url) {
    $url = str_replace(home_url(),site_url() , $url);
    return $url;
}