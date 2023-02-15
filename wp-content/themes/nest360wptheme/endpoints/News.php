<?php

namespace NEST360WPTheme\endpoints;

class News {

    public int $error_http_response_code = 401;

    public int $success_http_response_code = 200;

    /**
     * @param int $per_page
     * @param int $page
     *
     */
    public function get_news_banner() {
        $post_id = get_option( 'page_for_posts' );

        return wp_send_json_success(Utilities::get_instance()->get_pages_banner($post_id), $this->success_http_response_code);
    }
    public function get_news($per_page = 5, $page = 1) {
        $post_id = get_option( 'page_for_posts' );

        $response = Utilities::get_instance()->get_default_posts_with_categories($per_page, $page);

        return  wp_send_json_success($response, $this->success_http_response_code);
    }

    public function get_news_by_category($per_page = 5, $page = 1, $category_name = '') {
        $post_id = get_option( 'page_for_posts' );

        $response = Utilities::get_instance()->get_default_posts_by_categories($per_page, $page, $category_name);

        return  wp_send_json_success($response, $this->success_http_response_code);
    }

    public function get_new($slug, $post_type = 'post', $category = 'category') {
        $news = Utilities::get_instance()->get_default_single_post($slug, $post_type, $category);

        if(empty($news)) {
            wp_send_json_error("$post_type does not exits", $this->error_http_response_code);
        }

        return wp_send_json_success($news, $this->success_http_response_code);
    }

    public function get_lists_of_posts($post_type, $category, $per_page = 5, $page = 1, $filter_by = '') {

        $posts = Utilities::get_instance()->get_default_posts_with_categories($per_page, $page, $post_type, $category, $filter_by, false);

        return wp_send_json_success($posts, $this->success_http_response_code);
    }

    public function get_lists_of_case_studies($post_type, $category, $per_page = 5, $page = 1, $country = '') {

        $posts = Utilities::get_instance()->get_default_case_studies_with_categories($per_page, $page, $post_type, $category, $country, false);

        return wp_send_json_success($posts, $this->success_http_response_code);
    }

    public function get_lists_of_newsletter($post_type = 'newsletter') {
        $newsletters = Utilities::get_instance()->get_all_newsletters($post_type);

        return wp_send_json_success($newsletters, $this->success_http_response_code);
    }

    public function get_lists_of_case_studies_category($taxonomy) {
        $categories = Utilities::get_instance()->get_all_categories($taxonomy, false);
        
        if(empty($categories)) {
            return wp_send_json_success([], $this->success_http_response_code);
        }
        
        return wp_send_json_success($categories, $this->success_http_response_code);
    }

    public function get_lists_of_case_studies_sub_category($taxonomy, $category = '') {
        if(empty($category)) {
            return wp_send_json_success([], $this->success_http_response_code);
        } else {
            //get the term by slug
            $term = Utilities::get_instance()->return_the_category($category, $taxonomy);

            if(!empty($term)) {
                return wp_send_json_success(Utilities::get_instance()->return_children_categories($term['id'], $taxonomy), $this->success_http_response_code);
            } else return wp_send_json_success([], $this->success_http_response_code);
        }
    }


    /**
     * Singleton poop.
     *
     * @return News|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}