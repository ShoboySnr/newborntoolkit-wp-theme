<?php

namespace NEST360WPTheme\endpoints;


class CaseStudy {

    private $error_http_response_code = 401;

    private $success_http_response_code = 200;

    public function get_lists_of_posts($post_type, $category, $per_page = 5, $page = 1, $filter_post_type = '') {

        $posts = Utilities::get_instance()->get_default_posts_with_categories($per_page, $page, $post_type, $category);

        return wp_send_json_success($posts, $this->success_http_response_code);
    }


    /**
     * Singleton poop.
     *
     * @return Utilities|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}