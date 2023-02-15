<?php

namespace NEST360WPTheme\inc;

class Shortcodes {

    public function __construct() {
        add_shortcode('nest360-toggle-tabs', [$this, 'nest360_toggle_tabs'], 100, 1);
    }

    public function nest360_toggle_tabs($atts) {
        $atts = shortcode_atts( [
            'title' => '',
            'content' => '',
        ], $atts, 'nest360-toggle-tabs' );

        $title = $atts['title'];
        $content = $atts['content'];

        if(!is_user_logged_in()) {
            $this->render_template($title, $content);
        }
    }


    public function render_template($title, $content) {
        include_once('templates/toogle-tabs.php');
    }

    /**
     * Singleton poop.
     *
     * @return Shortcodes|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}