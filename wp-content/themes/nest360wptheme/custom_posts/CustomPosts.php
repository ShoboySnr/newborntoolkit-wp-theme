<?php

namespace NEST360WPTheme\custom_posts;

class CustomPosts {
    
    public function toolkits($post) {
        //insert into the reading categories
        $title = $post->post_title;
        $slug = $post->post_name;

        $args = [
            'slug'          => $slug,
            'parent'        => 0,
            'description'   => 'Category generated from the toolkits post types'
        ];
        $insert_term = wp_insert_term($title, 'reading_categories', $args);

        if(is_wp_error($insert_term)) {
            return false;
        }

        return  $insert_term;
    }

    public function information_systems($post) {
        $title = $post->post_title;
        $slug = $post->post_name;

        $args = [
            'slug'          => $slug,
            'parent'        => 0,
            'description'   => 'Category generated from the toolkits post types'
        ];
        $insert_term = wp_insert_term($title, 'reading_categories', $args);
        $insert_term = wp_insert_term($title, 'tools_category', $args);
        $insert_term = wp_insert_term($title, 'in_practice_category', $args);

        if(is_wp_error($insert_term)) {
            return false;
        }

        return  $insert_term;
    }

    public function auto_create_categories_content($post, $taxonomy = [], $post_type = '') {
        $title = $post->post_title;
        $slug = $post->post_name;
        $post_type = get_post_type_object($post->post_type)->labels->name;
        $post_type_slug = get_post_type_object($post->post_type)->name;
        $new_slug = sanitize_title($post_type_slug. ' '.$slug);


        $parent_args = [
            'slug'          => $post_type_slug,
            'parent'        => 0,
            'description'   => "Category generated from $post_type"
        ];

        $insert_term = false;

        //loop through the taxonomy
        if (!empty($taxonomy)) {
            foreach($taxonomy as $taxon) {
                $insert_parent_term = wp_insert_term($post_type, $taxon, $parent_args);
                if(is_wp_error($insert_parent_term) && isset($insert_parent_term->error_data['term_exists'])) {
                    $insert_parent_term = $insert_parent_term->error_data['term_exists'];
                } else {
                    $insert_parent_term = $insert_parent_term['term_id'];
                }

                $args = [
                    'name'          => $title,
                    'slug'          => $new_slug,
                    'parent'        => $insert_parent_term,
                    'description'   => "Sub Category generated from $post_type"
                ];

                //check if a term exists by slug
                $check_term = term_exists($new_slug, $taxon, $insert_parent_term);

                if(!empty($check_term)) {
                    $insert_term = wp_update_term($check_term['term_id'], $taxon, $args);
                } else $insert_term = wp_insert_term($title, $taxon, $args);

            }
            if(!$insert_term) {
                return false;
            }
        }

        return  $insert_term;
    }

    public function auto_delete_categories_content($post, $taxonomy = [], $post_type = '') {
        $title = $post->post_title;
        $slug = $post->post_name;
        $post_type = get_post_type_object($post->post_type)->labels->name;
        $post_type_slug = get_post_type_object($post->post_type)->name;


        $parent_args = [
            'slug'          => $post_type_slug,
            'parent'        => 0,
            'description'   => "Category generated from $post_type"
        ];

        $insert_term = false;
        //loop through the taxonomy
        if (!empty($taxonomy)) {
            foreach($taxonomy as $taxon) {
                //get the term
                $get_term = get_term_by('slug', $post_type_slug, $taxon);
                $get_children_terms = get_term_children($get_term->term_id, $taxon);

                foreach($get_children_terms as $get_children_term) {
                    $term = get_term($get_children_term);
                    if($term->name == $title) {
                        wp_delete_term($term->term_id, $term->taxonomy);
                    }
                }

                $get_children_terms = get_term_children($get_term->term_id, $taxon);
                if(empty($get_children_terms)) {
                    wp_delete_term($get_term->term_id, $get_term->taxonomy);
                }
            }
        }

        return  $insert_term;
    }


    /**
     * Singleton poop.
     *
     * @return CustomPosts|null
     */
    public static function get_instance() {
    static $instance = null;

    if (is_null($instance)) {
        $instance = new self();
    }

    return $instance;
}
}