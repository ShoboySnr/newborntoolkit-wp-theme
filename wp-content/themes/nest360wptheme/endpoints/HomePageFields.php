<?php

namespace NEST360WPTheme\endpoints;

use NEST360WPTheme\inc\DataRead;

class HomePageFields {

    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;

    /**
     *
     */
    public function get_homepage_fields() {
        $front_page_id = get_option( 'page_on_front' );

        $post_data = [];

        //get banner fields
        $post_data['banner'] = Utilities::get_instance()->get_pages_banner($front_page_id);

        $placeholders = $this->get_placeholder_images($front_page_id);

        $section_two = $this->get_section_two_fields($front_page_id);

        $section_three = $this->get_section_three_fields($front_page_id);

        $section_four = $this->get_section_four_fields($front_page_id);

        $post_data = array_merge($post_data, $placeholders, $section_two, $section_three, $section_four);


        return wp_send_json_success($post_data, $this->success_http_response_code);

    }

    /**
     * @param $page_id
     * @return array
     *
     */
    public function get_section_two_fields($page_id) {
        $section_two = get_field('section_two', $page_id);
        $response = [];

        if($section_two != '') {
            for($count = 1; $count <= 2; $count++) {
                $response['section_2'][] = array(
                    'header_'.$count => isset($section_two['header_'.$count]) ? $section_two['header_'.$count] : '',
                    'subtitle_'.$count => isset($section_two['subtitle_'.$count]) ? $section_two['subtitle_'.$count] : '',
                    'description_'.$count => isset($section_two['description_'.$count]) ? $section_two['description_'.$count] : '',
                    'button_'.$count  => isset($section_two['button_'.$count]) ? $section_two['button_'.$count] : '',
                );
            }
        }

        return $response;
    }

    /**
     * @param $page_id
     * @return array
     *
     */
    public function get_section_three_fields($page_id) {
        $section_three = get_field('section_three', $page_id);
        $response = [];

        if($section_three != '') {
            $response['section_3']['title'] = $section_three['title'];
            $response['section_3']['image'] = $section_three['image'];

            for($count = 1; $count <= 2; $count++) {
                $response['section_3']['tabs'][] = array(
                    'tab_'.$count.'_title' => $section_three['tab_'.$count.'_title'],
                    'tab_'.$count.'_description' => $section_three['tab_'.$count.'_description']
                );
            }
        }

        return $response;

    }

    /**
     * @param $page_id
     * @return array
     *
     */
    public function get_section_four_fields($page_id) {
        $section_four = get_field('section_four', $page_id);
        $response = [];

        //get the testimonials
        $response['section_four']['testimonials'] = Utilities::get_instance()->get_default_posts('testimonials');


        //get the testimonials
        if ($section_four != '') {
            $response['section_four']['community_title'] = $section_four['community_title'];
            $response['section_four']['community_description'] = $section_four['community_description'];
            $response['section_four']['action_button'] = $section_four['action_button'];
        }

        return $response;
    }

    /**
     * @param $page_id
     * @return array
     *
     */
    public function get_placeholder_images($page_id)
    {
        $placeholder_logos = acf_photo_gallery('placeholder_logos', $page_id);
        $images = [];

        if($placeholder_logos != '') {
            foreach($placeholder_logos as $logo) {
                $images['logos'][] = array(
                    'id'    => $logo['id'],
                    'title' => $logo['title'],
                    'alt'   => get_field('photo_gallery_alt', $logo['id']),
                    'image' => $logo['full_image_url'],
                );
            }
        }

        return $images;
    }

    /**
     * Singleton poop.
     *
     * @return HomePageFields|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}