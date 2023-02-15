<?php

namespace NEST360WPTheme\endpoints;

use NEST360WPTheme\inc\DataRead;

class Tools {

    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;

    /**
     * @param int $per_page
     * @param int $page
     *
     */
    public function get_tools($per_page = 5, $page = 1) {
        $post_id = get_option( 'page_for_posts' );

        $response = [];
        $response['banner'] = Utilities::get_instance()->get_pages_banner($post_id);

        $response = array_merge($response, Utilities::get_instance()->get_default_posts_with_categories($per_page, $page, 'tools', 'tools_category'));

        return  wp_send_json_success($response, $this->success_http_response_code);
    }

    public function get_tool($slug) {
        $tools = Utilities::get_instance()->get_default_single_post($slug, 'tools');

        if(empty($tools)) {
            wp_send_json_error('Tools does not exits', $this->error_http_response_code);
        }

        return wp_send_json_success($tools, $this->success_http_response_code);
    }


    /**
     * Singleton poop.
     *
     * @return Tools|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}