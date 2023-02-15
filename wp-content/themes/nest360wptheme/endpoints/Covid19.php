<?php

namespace NEST360WPTheme\endpoints;

class Covid19 extends News {
    public $post_types = 'covid-19';
    
    public function get_news($per_page = 5, $page = 1) {
        $response = Utilities::get_instance()->get_default_posts_with_categories($per_page, $page, $this->post_types, '', '', true, false);
        
        return  wp_send_json_success($response, $this->success_http_response_code);
    }
    
    public function get_new($slug) {
        $news = Utilities::get_instance()->get_default_single_post($slug, $this->post_types);
        
        if(empty($news)) {
            wp_send_json_error("$this->post_types does not exits", $this->error_http_response_code);
        }
        
        return wp_send_json_success($news, $this->success_http_response_code);
    }
    
    /**
     * Singleton poop.
     *
     * @return Covid19|null
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}