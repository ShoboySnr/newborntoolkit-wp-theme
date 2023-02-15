<?php


namespace NEST360WPTheme\endpoints;

class Search {
    private $error_http_response_code = 401;

    private $success_http_response_code = 200;

    public function get_search_attributes() {
        $return_data = [];

        //get the overview, reading, tools and inpractice
        $categories = [
            'overview'          => 'Overview',
            'reading'           => 'Reading',
            'tools'             => 'Tools',
            'in_practice'       => 'In Practice',
        ];

        foreach ($categories as $key => $category) {
            $return_data['category'][] = [
                'name'      => $category,
                'value'      => $key,
            ];
        }

        $user_types = Utilities::get_instance()->get_all_categories('user_type_category');
        foreach($user_types as $user_type) {
            $return_data['user_type'][] = [
                'name'  => $user_type['title'],
                'value'  => $user_type['slug'],
            ];
        }

        $return_data['hsbb'] = Utilities::get_instance()->get_implementation_toolkits();

        return wp_send_json_success($return_data, $this->success_http_response_code);
    }

    public function find_search_results($search_term = '', $category = '', $user_type = '', $hsbb = '', $per_page = 10, $paged = 1) {
        global $wpdb;
        
        $return_data = [];
        $args = [
            's'                 => $search_term,
            'paged'             => $paged,
            'posts_per_page'       => $per_page,
            'tax_query'         => [],
            'post__in'          => []
        ];
    
        $is_post_type = false;
        $default_post_types = [];
        
        if(!empty($category)) {
            if($category !== 'page') {
                
                if(!empty($hsbb)) {
                    $args['tax_query'][] = [
                        'taxonomy' => $category.'_category',
                        'field' => 'slug',
                        'terms' => $hsbb,
                    ];
                } else {
                    $all_implementation_toolkits = Utilities::get_instance()->implementation_toolkits_list();
                    foreach ($all_implementation_toolkits as $all_implementation_toolkit) {
                        $args['tax_query'][] = [
                            'taxonomy' => $category.'_category',
                            'field' => 'slug',
                            'terms' => $all_implementation_toolkit,
                        ];
                    }
                }
                
            }
            
            if($category === 'overview') {
                $is_post_type = true;
                
                if(!empty($hsbb)) {
                    $default_post_types = array_merge([$hsbb], $default_post_types);
                } else {
                    $all_implementation_toolkits = Utilities::get_instance()->implementation_toolkits_list();
                    foreach ($all_implementation_toolkits as $all_implementation_toolkit) {
                        $default_post_types[] = $all_implementation_toolkit;
                    }
                }
            }
        }
        
        if(!empty($hsbb) && empty($category) && empty($user_type)) {
            $tools_types = ['reading', 'tools', 'in_practice'];
            $posts_ids = [];
            foreach ($tools_types as $tools_type) {
                $terms = get_term_by('slug', $hsbb, $tools_type.'_category');
                $term_id = $terms->term_id;
                
                $post_ids_post = $wpdb->get_col( $wpdb->prepare( "
                SELECT DISTINCT ID FROM {$wpdb->posts}
                LEFT JOIN  $wpdb->term_relationships  as t
                ON ID = t.object_id
                WHERE post_type = '%s' OR t.term_taxonomy_id = '%s'
                OR post_title LIKE '%s'
                OR post_content LIKE '%s'
            ", $hsbb, $term_id, $search_term, $search_term ) );
    
                $posts_ids = array_merge($post_ids_post, $posts_ids);
            }
            
            $posts_ids = array_unique($posts_ids);
    
            $args['post__in'] = array_merge($posts_ids, $args['post__in']);
        }
        
        if(!$is_post_type) {
            $default_post_types = get_post_types([
                'public'   => true,
            ], 'names');
        }

        if(!empty($user_type)) {
            $terms = get_term_by('slug', $user_type, 'user_type_category');
            $term_id = $terms->term_id;
            $attach_post_types = '';
            if(!empty($hsbb)) {
                $default_post_types = array_merge([$hsbb], $default_post_types);
                $attach_post_types .= ' AND post_type = \' '.$hsbb.'\' ';
            }
            
            if(!empty($category)) {
                $attach_post_types .= ' AND post_type = \' '.$category.'\' ';
            }
    
            $post_ids_post = $wpdb->get_col( $wpdb->prepare( "
                SELECT DISTINCT ID FROM {$wpdb->posts}
                INNER JOIN  $wpdb->term_relationships  as t
                ON ID = t.object_id
                WHERE t.term_taxonomy_id = '%s' ".$attach_post_types."
                OR post_title LIKE '%s'
                OR post_content LIKE '%s'
            ",  $term_id, $search_term, $search_term ) );
    
            $args['post__in'] = array_merge($post_ids_post, $args['post__in']);
        }

        $args['tax_query']['relation'] = 'OR';
    
        if(empty($category) && empty($hsbb) && empty($user_type)) {
            // Search in all custom fields
            $post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
                SELECT DISTINCT post_id FROM {$wpdb->postmeta}
                WHERE meta_value LIKE '%s'
            ", $search_term));
        
            // Search in post_title and post_content
            $defaults_posts_id = $wpdb->get_col( $wpdb->prepare( "
                SELECT DISTINCT ID FROM {$wpdb->posts}
                WHERE post_title LIKE '%s'
                OR post_content LIKE '%s'
            ", $search_term, $search_term ) );
        
            $args['post__in'] = array_unique(array_merge($post_ids_meta, $defaults_posts_id, $args['post__in']));
        }

        //remove the post types that are needed in the toolkits search
        unset($default_post_types['attachment'],
            $default_post_types['post'],
            $default_post_types['my_events'],
            $default_post_types['case_studies'],
            $default_post_types['newsletter'],
            $default_post_types['team']);
        
        $args['post_type'] = array_filter($default_post_types);
        
    
        $wp_query = new \WP_Query($args);
        
        $total_posts = $wp_query->max_num_pages;

        if($wp_query->have_posts()) {
            foreach($wp_query->posts as $post) {
                $post_type = $post->post_type;
                $hsbb = Utilities::get_instance()->return_taxonmies($post->ID, $post_type) ? Utilities::get_instance()->return_taxonmies($post->ID, $post_type) : [];
                $category_object = get_post_type_object(get_post_type($post->ID))->labels->name;
    
                $category_hsbb = [];
                if(empty($hsbb)) {
                    $category_hsbb[] = [
                        'id'        => $post->ID,
                        'slug'      => get_post_type_object(get_post_type($post->ID))->name,
                        'title'      => get_post_type_object(get_post_type($post->ID))->label,
                    ];
                }
                $link = $category_object == 'Reading' ? get_field('external_link', $post->ID) : str_replace(get_home_url(), get_theme_mod('frontend_url'), get_permalink($post->ID));
                $return_data['search_results'][] = [
                    'title'         => $post->post_title,
                    'slug'          => $post->post_name,
                    'link'          => $link,
                    'excerpt'       => Utilities::get_instance()->limitStr($post->post_content, 70),
                    'category'      => empty($hsbb) ? ucfirst($category) : $category_object,
                    'user_type'     => Utilities::get_instance()->return_search_categories($post->ID, 'user_type_category') ? Utilities::get_instance()->return_search_categories($post->ID, 'user_type_category') : [],
                    'hsbb'          => empty($hsbb) ? $category_hsbb : $hsbb,
                    'keywords'      => Utilities::get_instance()->return_tags($post->ID),
                ];
            }
        }

        return wp_send_json_success(array_merge(array_filter($return_data), $this->count_posts($total_posts, $wp_query->found_posts, $wp_query->post_count)), $this->success_http_response_code);
    }
    
    
    public function count_posts($posts, $item_posts, $per_page) {
        if($posts <= 0) $posts = 1;
        return  [
            'totalItems'        => $item_posts,
            'pageCount'         => $posts,
            'itemCount'         =>  $per_page
        ];
    }


    /**
     * Singleton poop.
     *
     * @return Search|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}