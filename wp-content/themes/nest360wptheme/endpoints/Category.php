<?php

namespace NEST360WPTheme\endpoints;

class Category {
    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;

    public function get_categories($categories = []) {
        $return_data = [];

        $args = [
            'taxonomy'      => $categories,
            'hide_empty'    => false,
            'parent' => 0
        ];

        if(empty($categories)) {
            return wp_send_json_success(array_filter($return_data), $this->success_http_response_code);
        }


        $terms = get_terms($args);

        if(!empty($terms)) {
            foreach ($terms as $key => $value) {
                $return_data[] = [
                    'id'    =>$value->term_id,
                    'slug'    =>$value->slug,
                    'name'    =>$value->name,
                    'children'  => Utilities::get_instance()->return_children_categories($value->term_id, $categories),
                ];
            }
        }

        return wp_send_json_success(array_filter($return_data), $this->success_http_response_code);
    }

    /**
     * Singleton poop.
     *
     * @return Category|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}