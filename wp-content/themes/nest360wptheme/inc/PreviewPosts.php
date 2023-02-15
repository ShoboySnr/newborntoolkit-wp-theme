<?php

namespace NEST360WPTheme\inc;

class PreviewPosts {

    public function __construct()
    {
//        add_action( 'preview_post_link', [$this, 'handle_preview_posts'], 10, 2);
    }


    //handle this later to preview
    public function handle_preview_posts($preview_link, $post) {
        $root = get_theme_mod('frontend_url');
        return $root == "" ? $this->get_default_url() : $root.$post->post_name;
    }

    public function get_default_url() {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $CurPageURL;
    }

    /**
     * Singleton poop.
     *
     * @return PreviewPosts|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}