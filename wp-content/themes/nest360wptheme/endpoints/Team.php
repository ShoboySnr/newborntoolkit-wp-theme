<?php

namespace NEST360WPTheme\endpoints;

class Team {
    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;

    public function get_all_team() {
        $args = [
            'post_type' => 'team',
            'numberposts' => -1,
        ];

        $teams = get_posts($args);

        $return_data = [];
        if(!empty($teams)) {
            foreach ($teams as $value) {
                $return_data[]   = [
                    'id'            => $value->ID,
                    'title'         => $value->post_title,
                    'slug'          => $value->post_name,
                    'image'         => get_the_post_thumbnail_url($value->ID),
                    'organisation'  => get_field('organisation', $value->ID),
                    'content'       => Utilities::get_instance()->filter_post_content($value->post_content, $value->ID),
                    'category'      => Utilities::get_instance()->return_category($value->ID, 'team_category'),
                ];
            }
        }

        return wp_send_json_success(array_filter($return_data), $this->success_http_response_code);
    }

    public function get_team_by_category($category = [], $include_logos = '') {
        if(empty($category)) {
            return wp_send_json_success('No category name specified', $this->success_http_response_code);
        }

        $args = [
            'post_type'     => 'team',
            'numberposts'   => -1,
            'tax_query'     => [
                [
                    'taxonomy'      => 'team_category',
                    'field'         => 'slug',
                    'terms'         =>  $category
                ]
            ]
        ];

        $teams = get_posts($args);

        $return_data = [];
        if(!empty($teams)) {
            foreach ($teams as $value) {
                $return_data['teams'][]    = [
                    'id'        => $value->ID,
                    'title'        => $value->post_title,
                    'slug'        => $value->post_name,
                    'image'        => get_the_post_thumbnail_url($value->ID),
                    'organisation'        => get_field('organisation', $value->ID),
                    'content'        => Utilities::get_instance()->filter_post_content($value->post_content, $value->ID),
                    'category'        => Utilities::get_instance()->return_the_category($category, 'team_category'),
               ];
            }
        } else $return_data['teams'] = [];

        return wp_send_json_success($return_data, $this->success_http_response_code);
    }

    /**
     * Singleton poop.
     *
     * @return Team|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}