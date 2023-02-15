<?php

namespace NEST360WPTheme\endpoints;

class PageLayout {

    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;

    /**
     * @param $page_id
     *
     * return the page id, page name, page slug and page color
     */
    public function get_sub_pages($page_id) {
        if(strlen($page_id) < 1) {
            $response = [
                'message'   => 'Page ID not set'
            ];

            wp_send_json_error($response, $this->error_http_response_code);
        }

        return wp_send_json_success($this->get_page_children($page_id), $this->success_http_response_code);
    }

    public function get_page_children($page_id) {
        $return_data = [];

        $child_args = array(
            'post_parent' => $page_id, // The parent id.
            'post_type'   => 'page',
            'post_status' => 'publish',
            'orderby'        => 'DESC'
        );

        $children = get_children($child_args);

        if($children != '') {
            foreach($children as $child) {
                $return_data[] = [
                    'id'    => $child->ID,
                    'title' => $child->post_title,
                    'slug' => $child->post_name,
                    'bg_color'  => get_field('page_color', $child->ID) ? get_field('page_color', $child->ID)  : '',
                    'subpages'  => self::get_page_children($child->ID)
                ];
            }
        }

        return $return_data;

    }


    /**
     * Singleton poop.
     *
     * @return PageLayout|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}