<?php

namespace NEST360WPTheme\inc;

class DataRead {

    public function get_custom_field_posts($custom_field_slug, $post_type) {
        global $wpdb;

        $str = "SELECT $wpdb->posts.*
            FROM $wpdb->posts, $wpdb->postmeta
            WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
            AND $wpdb->posts.post_status = 'publish'
            AND $wpdb->posts.post_type = $post_type
            ORDER BY $wpdb->postmeta.meta_value ASC
        ";

        $result = $wpdb->get_results($str);

        return $result;
    }

    public function get_posts() {
        global $wpdb;

        $str = "SELECT $wpdb->posts.*
            FROM $wpdb->posts, $wpdb->postmeta
            WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
            AND $wpdb->posts.post_status = 'publish'
            ORDER BY $wpdb->postmeta.meta_value ASC
        ";

        $result = $wpdb->get_results($str);

        return $result;
    }

    /**
     * Singleton poop.
     *
     * @return DataRead|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

}