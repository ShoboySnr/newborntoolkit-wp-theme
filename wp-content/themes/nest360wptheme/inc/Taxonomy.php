<?php

namespace NEST360WPTheme\inc;

class Taxonomy {

    public function __construct()
    {
        add_filter( 'category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('tools_category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('default_custom_category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('user_type_category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('case_study_category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('events_category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('team_category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('reading_category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('in_practice_category_row_actions', [$this, 'remove_view_actions'], 10, 2);
        add_filter('post_tag_row_actions', [$this, 'remove_view_actions'], 10, 2);
    }

    public function remove_view_actions($actions, $tag) {
        unset($actions['view']);
        return $actions;
    }

    /**
     * Singleton poop.
     *
     * @return Taxonomy|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}