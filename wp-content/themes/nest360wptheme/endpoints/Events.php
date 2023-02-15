<?php

namespace NEST360WPTheme\endpoints;

class Events {

    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;

    public function get_lists_of_events($post_type, $post_type_category, $per_page = 5, $page = 1, $member_only, $category, $month) {

        $posts = Utilities::get_instance()->get_default_events_with_categories($per_page, $page, $post_type, $post_type_category, $member_only, $category, $month);

        return wp_send_json_success($posts, $this->success_http_response_code);
    }

    public function get_single_event($slug, $member_only = 0) {
        $posts = Utilities::get_instance()->get_default_single_event($slug, $member_only);

        return wp_send_json_success($posts, $this->success_http_response_code);
    }

    public function get_events_categories() {
        $events_categories = Utilities::get_instance()->get_all_categories('events_category');

        return wp_send_json_success($events_categories, $this->success_http_response_code);
    }

    public function get_events_months() {
        $event_months = Utilities::get_instance()->get_events_dates('my_events');

        return wp_send_json_success($event_months, $this->success_http_response_code);
    }

    public function get_events_date($members_only = 0) {
        $args = [
            'post_type' => 'my_events',
            'posts_per_page' => '-1',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query' => [
                'relation'  => 'AND',
                [
                    'key'		=> 'events_date',
                    'value'		=> 'date("2011-01-01")',
                    'compare'	=> '>'
                ],
                [
                    'key'		=> 'members_only',
                    'value'		=> '1',
                    'compare'	=> '!='
                ],
            ],
        ];

        if($members_only != 0) {
            $args['meta_query'] = [
                'relation'		=> 'AND',
                [
                    'key'		=> 'members_only',
                    'value'		=> '1',
                ],
                [
                    'key'		=> 'events_date',
                    'value'		=> 'date("2011-01-01")',
                    'compare'	=> '>'
                ],
            ];
        }

        $events = get_posts($args);

        // Iterate over events
        foreach($events as $key => $event) {
            $memberonly = get_field('members_only', $event->ID);
        }
    }


    /**
     * Singleton poop.
     *
     * @return Events|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}