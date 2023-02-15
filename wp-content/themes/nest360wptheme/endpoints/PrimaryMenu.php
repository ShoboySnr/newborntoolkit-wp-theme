<?php

namespace NEST360WPTheme\endpoints;

class PrimaryMenu {

    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;

    public function get_primary_menu() {
        $menu_location = get_nav_menu_locations();
        if(isset($menu_location['primary_menu'])) {
            $menu = $this->get_wp_menu($menu_location['primary_menu']);
        } else return wp_send_json_success([], $this->success_http_response_code);

        return wp_send_json_success($this->filter_menu($menu), $this->success_http_response_code);
    }

    public function get_wp_menu($menu_location) {
        $menu = wp_get_nav_menu_object($menu_location);
        $x = wp_get_nav_menu_items($menu->term_id);
        $menu = array();
        $submenu = array();
        foreach($x as $y){
            $y->submenu = array();
            if($y->menu_item_parent === '0')
                array_push($menu, $y);
            else
                array_push($submenu, $y);
        }
        for($i=0; $i < count($submenu); $i++) {
            $index = $this->get_index($menu,$submenu[$i]->menu_item_parent);
            if($index > -1) {
                array_push($menu[$index]->submenu,$submenu[$i]);
            }
        }

        return $menu;
    }

    public function check_basename($basename_url) {
        return $basename_url === '' ? '#' : '/'.basename($basename_url);
    }

    public function filter_menu($menu) {
        $posts_data = [];
        foreach($menu as $key => $value) {
            $posts_data[]  = [
                'id' => $value->ID,
                'title' => $value->title,
                'slug' =>  $this->check_basename($value->url),
                'submenu' => self::filter_menu($value->submenu),
            ];
        }

        return array_filter($posts_data);
    }

    public function get_index($menu,$parent_id) {
        $index = -1;
        for($i = 0; $i < count($menu); $i++) {
            if((string)$menu[$i]->ID === $parent_id) {
                $index = $i;
                break;
            }
        }
        return $index;
    }

    /**
     * Singleton poop.
     *
     * @return PrimaryMenu|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}