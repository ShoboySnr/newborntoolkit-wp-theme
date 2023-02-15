<?php

namespace NEST360WPTheme\endpoints;

class Utilities {

    //get default content: post title, description and featured image of any post types
    public function get_default_posts($post_type = 'posts', $limit = 5) {
        $posts = get_posts([
            'post_type' => $post_type,
            'numberofpost' => $limit

        ]);

        $return_post = [];

        if(count($posts) > 0) {
            foreach($posts as $post) {
                $return_post[] = [
                    'id'       => $post->ID,
                    'slug'       => $post->post_name,
                    'title'    => $post->post_title,
                    'image'    => get_the_post_thumbnail_url($post->ID),
                    'except'    => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
                ];
            }
        }

        return $return_post;
    }

    //get default posts with post categories
    public function get_all_newsletters($post_type = 'newsletter', $category = 'user_type_category')
    {
        $return_post = [];


        $args = [
            'post_type'     => $post_type,
            'numberposts'  => -1,
        ];

        $posts = get_posts($args);

        if(!empty($posts)) {
            foreach($posts as $post)
            {
                $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;
                $return_post[] =  [
                    'id'                    => $post->ID,
                    'slug'                  => $post->post_name,
                    'title'                 => $post->post_title,
                    'summary'               => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
                    'date'                  => get_field('newsletter_date', $post->ID),
                    'link'                => get_field('link', $post->ID),
                ];
            }
        }

        return $return_post;
    }

    //get default posts with post categories
    public function get_default_case_studies_with_categories($limit = 5, $paged = 1, $post_type = 'case_studies', $category = 'case_study_category', $country = '', $ignore_sticky = true)
    {
        $return_post = [];

        $get_sticky_posts = get_option( 'sticky_posts' );
        $sticky_args = [
            'post_type'             => $post_type,
            'numberposts'           => 1,
            'post__in'              => $get_sticky_posts,
            'ignore_sticky_posts'   => $ignore_sticky,
        ];

        $args = [
            'post_type'             => $post_type,
            'numberposts'           => $limit,
            'paged'                 => $paged,
            'post__not_in'          => get_option( 'sticky_posts' ),
            'ignore_sticky_posts'   => false,
            'tax_query'             => [],
        ];

        $total_args = [
            'numberposts'           => -1,
            'post_type'             => $post_type,
            'post__not_in'          => get_option( 'sticky_posts' ),
            'ignore_sticky_posts'   => false,
            'tax_query'             => [],
        ];

        if(!empty($country)) {
            $args['tax_query'] = [
                [
                    'taxonomy'      => $category,
                    'field'         => 'slug',
                    'terms'         => $country
                ]
            ];

            $total_args['tax_query'] = [
                [
                    'taxonomy'      => $category,
                    'field'         => 'slug',
                    'terms'         => $country
                ]
            ];
        }

        $posts = get_posts($args);

        $sticky_posts = get_posts($sticky_args);

        $total_posts = get_posts($total_args);

        if(!empty($sticky_posts) && isset($sticky_posts[0]) && isset($get_sticky_posts[0])) {
            $sticky_posts = $sticky_posts[0];

            $post_thumbnail = ( has_post_thumbnail( $sticky_posts->ID ) ) ? get_the_post_thumbnail_url( $sticky_posts->ID ) : null;
            $return_post['sticky_posts'] =  [
                'id'                    => $sticky_posts->ID,
                'slug'                  => $sticky_posts->post_name,
                'title'                 => $sticky_posts->post_title,
                'image'                 => $post_thumbnail,
                'summary'               => empty($sticky_posts->post_excerpt) ? wp_trim_words($sticky_posts->post_content, 55, '...') : $sticky_posts->post_excerpt,
                'category'              => $this->return_category($sticky_posts->ID, $category, $country),
                'date'                  => date('d/m/Y', strtotime($sticky_posts->post_date)),
                'rating'                => get_field('rating', $sticky_posts->ID),
            ];
        } else  $return_post['sticky_posts'] = null;

        if(!empty($posts)) {
            foreach($posts as $post)
            {
                $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;
                $return_post['posts'][] =  [
                    'id'                    => $post->ID,
                    'slug'                  => $post->post_name,
                    'title'                 => $post->post_title,
                    'image'                 => $post_thumbnail,
                    'summary'               => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
                    'category'              => $this->return_category($post->ID, $category),
                    'date'                  => date('d/m/Y', strtotime($post->post_date)),
                    'rating'                => get_field('rating', $post->ID),
                ];
            }
        } else {
            $return_post['posts'] = [];
        }

        $return_post = array_merge($return_post, $this->return_post_count($total_posts, $posts, $limit));

        return $return_post;
    }

    //get default posts with post categories
    public function get_default_posts_with_categories($limit = 5, $paged = 1, $post_type = 'post', $category = 'category', $filter_by = '', $ignore_sticky = true, $show_sticky_posts = true)
    {
        $return_post = [];

        $get_sticky_posts = get_option( 'sticky_posts' );
        $sticky_args = [
            'post_type'     => $post_type,
            'numberposts'  => 1,
            'post__in'        => $get_sticky_posts,
            'ignore_sticky_posts' => $ignore_sticky,
        ];

        $args = [
            'post_type'             => $post_type,
            'numberposts'           => $limit,
            'paged'                 => $paged,
            'ignore_sticky_posts'   => false,
        ];

        $total_args = [
            'numberposts' => -1,
            'post_type'     => $post_type,
        ];

        if(!empty($filter_by)) {
            $args['tax_query'] = [
                [
                    'taxonomy'      => 'default_custom_category',
                    'field'         => 'slug',
                    'terms'         => $filter_by
                ],
            ];

            $total_args['tax_query'] = [
                [
                    'taxonomy'      => 'default_custom_category',
                    'field'         => 'slug',
                    'terms'         => $filter_by
                ],
            ];
        }

        $posts = get_posts($args);

        $sticky_posts = get_posts($sticky_args);

        $total_posts = get_posts($total_args);

        if(!empty($sticky_posts) && isset($sticky_posts[0]) && isset($get_sticky_posts[0])) {
            $sticky_posts = $sticky_posts[0];

            $post_thumbnail = ( has_post_thumbnail( $sticky_posts->ID ) ) ? get_the_post_thumbnail_url( $sticky_posts->ID ) : null;
            $return_post['sticky_posts'] =  [
                'id'                    => $sticky_posts->ID,
                'slug'                  => $sticky_posts->post_name,
                'title'                 => $sticky_posts->post_title,
                'image'                 => $post_thumbnail,
                'summary'               => empty($sticky_posts->post_excerpt) ? wp_trim_words($sticky_posts->post_content, 55, '...') : $sticky_posts->post_excerpt,
                'category'            => $this->return_category($sticky_posts->ID, $category),
                'date'                  => date('d/m/Y', strtotime($sticky_posts->post_date)),
                'rating'                => get_field('rating', $sticky_posts->ID),
            ];
        } else  $return_post['sticky_posts'] = null;

        if(!empty($posts)) {
            foreach($posts as $post)
            {
                $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;
                $return_post['posts'][] =  [
                    'id'                    => $post->ID,
                    'slug'                  => $post->post_name,
                    'title'                 => $post->post_title,
                    'image'                 => $post_thumbnail,
                    'summary'               => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
                    'category'            => $this->return_category($post->ID, $category),
                    'date'                  => date('d/m/Y', strtotime($post->post_date)),
                    'rating'                => get_field('rating', $post->ID),
                ];
            }

            $return_post = array_merge($return_post, $this->return_post_count($total_posts, $posts, $limit));
        }
        
        if(!$show_sticky_posts) unset($return_post['sticky_posts']);

        return $return_post;
    }

    //get default posts with post categories
    public function get_default_events_with_categories($limit = 5, $paged = 1, $post_type = 'my_events', $category = 'events_category', $member_only = 0, $query_category = '', $month = '')
    {
        $return_post = [];
        $args = [
            'post_type'     => $post_type,
            'numberposts'  => $limit,
            'paged'         => $paged,
            'meta_query'    => [],
            'tax_query' => []
        ];

        $total_args = [
            'numberposts' => -1,
            'post_type'     => $post_type,
            'meta_query'    => [],
            'tax_query' => []
        ];

        if($member_only != 0) {
            $args['meta_query'] = [
                [
                    'key'		=> 'members_only',
                    'value'		=> '1',
                    'compare'   => '='
                ],
            ];
            $total_args['meta_query'] = [
                [
                    'key'		=> 'members_only',
                    'value'		=> '1',
                    'compare'   => '='
                ],
            ];
        }

        if(!empty($query_category)) {
            $categories_types_args = [
                [
                    'taxonomy' => $category,
                    'field' => 'slug',
                    'terms' => $query_category,
                ],
            ];

            $args['tax_query'] = array_merge($categories_types_args, $args['tax_query']);
            $total_args['tax_query'] = array_merge($categories_types_args, $total_args['tax_query']);
        }

        if(!empty($month)) {
            $get_first_last_dates = $this->get_first_and_last_day_month($month);

            $date_range = [
                date('Y-m-d', $get_first_last_dates['first_date']),
                date('Y-m-d', $get_first_last_dates['last_date']),
            ];

            $events_date_args  = [
                [
                    'key'     => 'event_date',
                    'value'   => $date_range,
                    'compare' => 'BETWEEN',
                    'type'    => 'date',
                ],
            ];
            $args['meta_query'] = array_merge($events_date_args, $args['meta_query']);
            $total_args['meta_query'] = array_merge($events_date_args, $total_args['meta_query']);
        }

        $posts = get_posts($args);
        $total_posts = get_posts($total_args);

        if(!empty($posts)) {
            foreach($posts as $post)
            {
                $members_only  = get_field('members_only', $post->ID);
                $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;
                $return_post['events'][] =  [
                    'id'                        => $post->ID,
                    'slug'                      => $post->post_name,
                    'title'                     => $post->post_title,
                    'image'                     => $post_thumbnail,
                    'summary'                   => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
                    'category'                  => $this->return_category($post->ID, $category),
                    'event_start_date'          => get_field('event_date', $post->ID),
                    'event_end_date'            => get_field('event_end_date', $post->ID),
                    'event_time'                => get_field('event_time', $post->ID),
                    'event_location'            => get_field('event_location', $post->ID),
                    'organisation'              => get_field('organisation', $post->ID),
                    'members_only'              => $members_only,
                    'event_register_link'       => get_field('event_register_link', $post->ID),
                ];
            }

            $return_post = array_merge(array_filter($return_post), $this->return_post_count($total_posts, $posts, $limit));
        }

        return $return_post;
    }
    
    
    public function get_post_types_slugs($post_type, $index_value) {
        
        $posts = $this->return_post_types_slugs($post_type, $index_value);
        
        return wp_send_json_success($posts, 200);
    }
    
    //get default posts with post categories
    public function return_post_types_slugs($post_type, $index_value)
    {
        $return_post = [];
        $args = [
            'post_type'     => $post_type,
            'numberposts'  => -1,
        ];
        
        $posts = get_posts($args);
        
        if(!empty($posts)) {
            foreach($posts as $post)
            {
                $return_post['data'][]['params'] =  [
                    $index_value                     => $post->post_name,
                ];
            }
        }
        
        return $return_post;
    }

    public function get_first_and_last_day_month($time) {
        return [
            'first_date'    => strtotime(date('Y-m-01', $time)),
            'last_date'    => strtotime(date('Y-m-t', $time)),
        ];
    }

    //get default posts with post categories
    public function get_default_posts_by_categories($limit = 5, $paged = 1, $category_name = '', $post_type = 'post', $category = 'category')
    {
        $return_post = [];
        $args = [
            'post_type'     => $post_type,
            'numberposts'  => $limit,
            'paged'         => $paged,
        ];

        if(!empty($category_name)) {
            $args['tax_query'][] = [
                'taxonomy'      => $category,
                'field'         => 'slug',
                'terms'         =>  $category_name
            ];
        }

        $posts = get_posts($args);

        $total_args = [
            'numberposts' => -1,
            'post_type'     => $post_type,
        ];

        if(!empty($category_name)) {
            $total_args['tax_query'][] = [
                'taxonomy'      => $category,
                'field'         => 'slug',
                'terms'         =>  $category_name
            ];
        }
        $total_posts = get_posts($total_args);

        if(!empty($posts)) {
            foreach($posts as $post)
            {
                $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;
                $return_post['posts'][] =  [
                    'id'            => $post->ID,
                    'slug'          => $post->post_name,
                    'title'         => $post->post_title,
                    'image'         => $post_thumbnail,
                    'summary'       => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
                    'categories'    => $this->return_category($post->ID, $category),
                    'date'          => date('d/m/Y', strtotime($post->post_date)),
                    'rating'        => get_field('rating', $post->ID)
                ];
            }

            $return_post = array_merge(array_filter($return_post), $this->return_post_count($total_posts, $posts, $limit));
        }

        return $return_post;
    }

    public function get_default_single_post($slug, $post_type = 'post', $category = 'category')
    {
        global $post;
        $args = [
            'name'          => $slug,
            'numberposts'   => 1,
            'post_type'     => $post_type
        ];
        $post = get_posts($args);

        if(empty($post)) {
            $args = [
                'numberposts'   => 1,
                'post_type'     => $post_type,
                'include'       => [$slug],
                'post_status'   => ['publish', 'draft']
            ];

            $post = get_posts($args);
        }

        if (empty($post)) {
            return [];
        }
        $post = $post[0];

        $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;


        $return_post = [
            'id'            => $post->ID,
            'slug'          => $post->post_name,
            'title'         => $post->post_title,
            'image'         => $post_thumbnail,
            'except'        => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
            'content'       => $this->filter_post_content($post->post_content, $post),
            'category'    => $this->return_category($post->ID, $category),
            'date'          => date('d/m/Y', strtotime($post->post_date)),
        ];

        return $return_post;
    }

    public function get_default_single_tab_toolkits($slug, $post_type = 'post', $category = 'category')
    {
        $args = [
            'name'          => $slug,
            'numberposts'   => 1,
            'post_type'     => $post_type
        ];
        $post = get_posts($args);

        if(empty($post)) {
            $args = [
                'numberposts'   => 1,
                'post_type'     => $post_type,
                'include'       => [$slug],
                'post_status'   => ['publish', 'draft']
            ];

            $post = get_posts($args);
        }

        if (empty($post)) {
            return [];
        }
        $post = $post[0];

        $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;

        $return_post = [
            'id'            => $post->ID,
            'slug'          => $post->post_name,
            'title'         => $post->post_title,
            'image'         => $post_thumbnail,
            'except'        => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
            'content'       => $this->filter_post_content($post->post_content, $post),
            'category'    => $this->return_category($post->ID, $category),
            'date'          => date('d/m/Y', strtotime($post->post_date)),
        ];

        return $return_post;
    }

    public function get_default_single_event($slug, $member_only = 0, $post_type = 'my_events', $category = 'events_category')
    {
        $args = [
            'name'          => $slug,
            'numberposts'   => 1,
            'post_type'     => $post_type,
            'post_status'   => ['publish', 'draft']
        ];
        $post = get_posts($args);

        if(empty($post)) {
            $args = [
                'numberposts'   => 1,
                'post_type'     => $post_type,
                'include'       => [$slug],
                'post_status'   => ['publish', 'draft']
            ];
            $post = get_posts($args);
        }

        if (empty($post)) {
            return [];
        }
        $post = $post[0];

        $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;
        $members_only  = get_field('members_only', $post->ID);
        $return_post = [
            'id'                        => $post->ID,
            'slug'                      => $post->post_name,
            'title'                     => $post->post_title,
            'image'                     => $post_thumbnail,
            'summary'                    => empty($post->post_excerpt) ? wp_trim_words($post->post_content, 55, '...') : $post->post_excerpt,
            'content'                   => $this->filter_post_content($post->post_content, $post),
            'category'                  => $this->return_category($post->ID, $category),
            'event_start_date'          => get_field('event_date', $post->ID),
            'event_end_date'            => get_field('event_end_date', $post->ID),
            'event_time'                => get_field('event_time', $post->ID),
            'event_location'            => get_field('event_location', $post->ID),
            'organisation'              => get_field('organisation', $post->ID),
            'members_only'              => $members_only,
            'event_register_link'       => get_field('event_register_link', $post->ID)
        ];

        return $return_post;
    }

    public function return_category($post_id, $category_type, $term_slug = '') {
        $return_cat = [];
        
        if(!empty($term_slug)) {
            $get_term = get_term_by('slug', $term_slug, $category_type);
            
            if(is_wp_error($get_term)) {
                return [];
            }
            
            return [
                'id' => $get_term->term_id,
                'title' => $get_term->name,
                'slug' => $get_term->slug,
            ];
        }
        
        $categories = get_the_terms($post_id, $category_type);

        if($categories && ! empty($categories)) {
            foreach($categories as $category) {
                $return_cat[] = [
                    'id' => $category->term_id,
                    'title' => $category->name,
                    'slug' => $category->slug,
                ];
            }
        } else return [];

        return $return_cat[0];
    }

    public function return_children_categories($term_id, $taxonomy)
    {
        $return_data = [];
        $terms = get_term_children($term_id, $taxonomy);

        if (!empty($terms)) {
            foreach ($terms as $key => $value) {
                $term = get_term($value);
                $return_data[] = [
                    'id' => $term->term_id,
                    'slug' => $term->slug,
                    'title' => $term->name,
                ];
            }
        }

        return $return_data;

    }

    public function return_the_category($category_slug, $taxonomy = 'category')
    {
        $return_data = [];
        $get_term = get_term_by('slug', $category_slug, $taxonomy, 'object');

        if(!empty($get_term)) {
            $return_data = [
                'id' => $get_term->term_id,
                'slug' => $get_term->slug,
                'name' => $get_term->name,
            ];
        }

        return $return_data;
    }

    public function return_search_categories($post_id, $category_type) {
        $return_cat = [];
        $categories = get_the_terms($post_id, $category_type);

        if(!empty($categories)) {
            foreach($categories as $category) {
                $return_cat[] = [
                    'id' => $category->term_id,
                    'title' => $category->name,
                    'slug' => $category->slug,
                ];
            }
        } else return [];

        return $return_cat;
    }

    public function return_post_count($posts, $item_posts, $per_page) {
        if(!empty($posts) && !empty($item_posts)) {
            $total_items = count($posts);

            return [
                'totalItems'        => $total_items,
                'pageCount'         => ceil($total_items / $per_page),
                'itemCount'         => count($item_posts)
            ];
        } else return [
            'totalItems'        => 0,
            'pageCount'         => 0,
            'itemCount'         => 0
        ];
    }


    public function return_tags($post_id)
    {
        $returnTags = [];
        $tags = get_the_tags($post_id);

        if ($tags != '') {
            foreach($tags as $tag) {
                $returnTags[] = [
                    'tag_id' => $tag->term_id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ];
            }
        }

        return $returnTags;
    }

    //get pages banner
    public function get_pages_banner($page_id)
    {
        return [
            'banner_title'         => get_field('banner_page_title', $page_id),
            'banner_description'   => get_field('banner_page_description', $page_id),
            'banner_button'         => get_field('banner_action_button', $page_id),
            'banner_image'          => has_post_thumbnail($page_id) ? get_the_post_thumbnail_url($page_id) : null
        ];
    }

    //get all categories
    public function get_all_categories($taxonomy = 'category', $hide_empty = true) {
        $return_cat = [];
        $args = [
            'taxonomy'          => $taxonomy,
            'include_parent'    => 0,
            'hide_empty'        => $hide_empty
        ];

        $categories = get_terms($args);

        if(is_wp_error($categories)) {
            return false;
        }


        if(!empty($categories)) {
            foreach($categories as $category) {
                $return_cat[] = [
                    'id' => $category->term_id,
                    'title' => $category->name,
                    'slug' => $category->slug,
                ];
            }
        } else return [];

        return $return_cat;
    }

    //get all categories
    public function get_parent_categories($taxonomy = 'category') {
        $return_cat = [];
        $args = [
            'taxonomy'      => $taxonomy,
            'parent'        => 0
        ];

        $categories = get_terms($args);

        if(is_wp_error($categories)) {
            return false;
        }


        if(!empty($categories)) {
            foreach($categories as $category) {
                $return_cat[] = [
                    'id' => $category->term_id,
                    'title' => $category->name,
                    'slug' => $category->slug,
                ];
            }
        } else return [];

        return $return_cat;
    }

    //get dates
    public function get_events_dates($post_type) {
        $args = [
            'post_type'             => $post_type,
            'posts_per_page'        => -1,
        ];

        $posts = new \WP_Query($args);

        $return_query = [];
        foreach($posts->posts as $key => $value) {
            $return_query[$key]['id']       = $value->ID;
            $return_query[$key]['date'] = self::switch_date(get_field('event_date', $value->ID));
        }

        $return_dates = [];
        $return_dates_check = [];
        foreach($return_query as $key => $value) {
            $event_month = date('F Y', strtotime($value['date']));

            if(!in_array($event_month, $return_dates_check)) {
                $return_dates_check[strtotime($event_month)] = $event_month;
                $return_dates[] = [
                    'name'      => $event_month,
                    'value'     => strtotime($event_month)
                ];
            }
        }

        return $return_dates;
    }

    public static function switch_date($date) {
        if(!empty($date)) {
            $explode_date = explode('/', $date);
            $tmp = $explode_date[0];
            $explode_date[0] = $explode_date[1];
            $explode_date[1] = $tmp;
            unset($tmp);

            return implode('/', $explode_date);
        } else return $date;
    }

    public function get_implementation_toolkits() {
        $fields = $this->implementation_toolkits_list();

        //remove toolkits building blocks => array index of 0
        unset($fields[0]);

        $filtered = [];
        foreach ($fields as $field) {
            $post_type = get_post_type_object($field);
            if(!empty($post_type)) {
                $filtered[] = [
                    'name'  => $post_type->labels->name,
                    'value'  => $post_type->name,
                ];
            }
        }

        return $filtered;
    }

    public function get_custom_post_fields() {
        $fields = ['tools', 'reading', 'in_practice', 'team', 'newsletter', 'my_events', 'case_studies'];

        $filtered = [];
        foreach ($fields as $field) {
            $post_type = get_post_type_object($field);
            if(!empty($post_type)) {
                $filtered[] = [
                    'name'  => $post_type->labels->name,
                    'value'  => $post_type->name,
                ];
            }
        }

        return $filtered;
    }


    public function get_all_post_tags() {
        $args = [];

        $tags = get_tags($args);

        $filtered = [];
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $filtered[] = [
                    'name'  => $tag->name,
                    'value'  => $tag->slug,
                ];
            }
        }

        return $filtered;
    }

    public function limitStr($string, $limit = 100) {
        if(strlen($string) >= $limit) {
            return wp_trim_words($string, $limit, '...');
        }

        return $string;
    }

    public function return_taxonmies($post_id, $post_type) {
        $terms = [];
        $term_check = [];
    
        $taxonomies = get_object_taxonomies($post_type);
        $parent_terms  = [];
        foreach($taxonomies as $taxonomy) {
            if(in_array($taxonomy, $this->get_custom_post_tabs())) {
                $get_terms = get_the_terms($post_id, $taxonomy);
                if(!empty($get_terms)) {
                    foreach($get_terms as $get_term) {
                        if((int) $get_term->parent != 0) {
                            $parent_terms = get_term($get_term->parent);
                            if(!in_array($parent_terms->slug, $term_check)) {
                                $terms[] = [
                                    'id' => $parent_terms->term_id,
                                    'title' => $parent_terms->name,
                                    'slug' => $parent_terms->slug,
                                ];
                                $term_check[] =  $parent_terms->slug;
                            }
                        }
                    }
                }
            }
        }
    
        return $terms;
    }

    public function get_custom_post_tabs() {
        return ['reading_category', 'tools_category', 'in_practice_category'];
    }

    public function implementation_toolkits_list() {
        return ['toolkits', 'information-systems', 'governance',
            'human-resources', 'medical-supplies', 'finance', 'infrastructure', 'family-centred', 'infection'];
    }

    public function filter_post_content($post_content, $post_id) {
        if(empty($post_content)) return $post_content;
        return $post_content.'<!-- Begin Theme Styles --> <style>'.file_get_contents(NEST360_GUTENBERG_STYLE_CSS, true).' '.file_get_contents(NEST360_GUTENBERG_CUSTOM_BLOCK_CSS, true) . file_get_contents(NEST360_GUTENBERG_THEME_CSS, true).'</style><!-- End Theme Styles --> ';
    }



    public function remove_element($array,$value) {
        return array_diff($array, (is_array($value) ? $value : array($value)));
    }


    /**
     * Singleton poop.
     *
     * @return Utilities|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}