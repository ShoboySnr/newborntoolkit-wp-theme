<?php

namespace NEST360WPTheme\inc;

class FormValidity {

    public function __construct()
    {
//        add_action( 'wp_insert_post_data', [$this, 'check_form_validity'], '99', 2);
    }

    public function check_form_validity($post_id, $posts) {
       $post_type = $posts['post_type'];

       switch($post_type) {
           case 'my_events':
                $this->validate_events_fields($post_id, $posts);
               break;
           default:
               $this->validate_news_fields($post_id, $posts);

       }

       exit;
    }

    public function validate_events_fields($post_id, $posts) {

    }


    public function validate_news_fields($post_id, $posts) {
        $error = '';
        $error_count = 0;

        //check for post title
        if(empty($posts['post_title'])) {
            $error .= __('Title is empty', 'nest360-wp-theme');
            $error_count += 1;
        }

        if(!has_category('category', $post_id)) {
            $error .= __('News category is empty, please select a category', 'nest360-wp-theme');
            $error_count += 1;
        }


        var_dump(has_term('', 'category', $posts['ID']));
        //check for featured image
//        var_dump(has_post_thumbnail($post_id));
//        if(empty(get_the_post_thumbnail_url($post_id)) || get_the_post_thumbnail_url($post_id)) {
//
//        }
//        var_dump(get_the_post_thumbnail_url($post_id));
    }


    /**
     * Singleton poop.
     *
     * @return FormValidity|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}