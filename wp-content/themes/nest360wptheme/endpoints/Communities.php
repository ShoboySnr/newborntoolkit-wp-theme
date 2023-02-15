<?php

namespace NEST360WPTheme\endpoints;

class Communities extends News {
    public $post_types = 'communities';
    
    public function get_news($per_page = 5, $page = 1) {
        $response = Utilities::get_instance()->get_default_posts_with_categories($per_page, $page, $this->post_types, '', '', true, false);
        
        return  wp_send_json_success($response, $this->success_http_response_code);
    }
    
    public function get_new($slug, $post_type = 'communities', $category = 'category') {
        $news = Utilities::get_instance()->get_default_single_post($slug, $post_type, $category);
        
        if(empty($news)) {
            wp_send_json_error("$post_type does not exits", $this->error_http_response_code);
        }
        
        return wp_send_json_success($news, $this->success_http_response_code);
    }
    
    /**
     * Singleton poop.
     *
     * @return Communities|null
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}