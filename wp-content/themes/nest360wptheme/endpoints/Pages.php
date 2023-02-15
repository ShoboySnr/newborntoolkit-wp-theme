<?php

namespace NEST360WPTheme\endpoints;


class Pages {
    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;

    public function get_about_problem() {
        $about_data = $this->get_problem_template('problem');
        return wp_send_json_success(array_filter($about_data), $this->success_http_response_code);
    }
    
    public function get_problem_template($slug) {
        $page = get_page_by_path($slug);
        
        if(empty($page)) return [];
        
        $post_id = $page->ID;
    
        $return_data = [];
        $return_data['title'] = $page->post_title;
        $return_data['banner'] = Utilities::get_instance()->get_pages_banner($post_id);
    
        //get the first section
        $first_section = get_field('first_section', $post_id);
        $return_data['first_section'] = [
            'title_1'         => $first_section['title_1'] ?? '',
            'subtitle_1'         => $first_section['subtitle_1'] ?? '',
            'title_2'         => $first_section['title_2'] ?? '',
            'subtitle_2'         => $first_section['subtitle_2'] ?? '',
        ];
    
        //get the second section
        $second_section = get_field('second_section', $post_id);
        $return_data['second_section'] = [
            'image'         => $second_section['image'] ?? null,
            'content'         => $second_section['content'] ?? '',
        ];
    
        //get the third section
        $third_section = get_field('third_section', $post_id);
        $return_data['third_section'] = [
            'header'         => $third_section['header'] ?? '',
            'title_1'         => $third_section['title_1'] ?? '',
            'description_1'         => $third_section['description_1'] ?? '',
            'title_2'         => $third_section['title_2'] ?? '',
            'description_2'         => $third_section['description_2'] ?? '',
        ];
    
        //get the forth section
        $fourth_section = get_field('fourth_section', $post_id);
        $return_data['fourth_section'] = [
            'title'                 => $fourth_section['title'] ?? '',
            'description'           => $fourth_section['description'] ?? '',
            'map'                   => $fourth_section['map'] ?? null,
        ];
    
        //get the fifth section
        $fifth_section = get_field('fifth_section', $post_id);
        $return_data['fifth_section'] = [
            'title'         => $fifth_section['title'] ?? '',
            'subtitle'         => $fifth_section['subtitle'] ?? '',
        ];
    
        //get the sixth section
        $sixth_section = get_field('sixth_section', $post_id);
        $return_data['sixth_section'] = [
            'description'         => $sixth_section['description'] ?? '',
            'link'         => $sixth_section['link'] ?? '',
        ];
        
        return $return_data;
    }

    public function get_about_solution() {
        $solution_data = $this->get_solution_template('solution');
        return wp_send_json_success(array_filter($solution_data), $this->success_http_response_code);
    }
    
    public function get_solution_template($slug) {
        $page = get_page_by_path($slug);
        $post_id = $page->ID;
    
        $return_data = [];
        $return_data['title'] = $page->post_title;
        $return_data['banner'] = Utilities::get_instance()->get_pages_banner($post_id);
    
        //get the first section
        $first_section = get_field('first_section', $post_id);
        $return_data['first_section'] = [
            'title'         => $first_section['title'] ?? '',
            'subtitle'         => $first_section['subtitle'] ?? '',
            'image'         => $first_section['image'] ?? '',
        ];
    
        //get the second section
        $second_section = get_field('second_section', $post_id);
    
        $level = [];
        for($i = 1; $i < 5; $i++) {
            $level[]  = [
                'title' => $second_section['level_title_'.$i] ?? '',
                'description' => $second_section['description_'.$i] ?? '',
            ];
        }
        $second_section['tabs'] =
        $return_data['second_section'] = [
            'header'         => $second_section['header'] ?? '',
            'description'         => $second_section['description'] ?? '',
            'tabs'         => $level,
        ];
    
        //get the third section
        $third_section = get_field('third_section', $post_id);
        $return_data['third_section'] = [
            'header'         => $third_section['header'] ?? '',
            'content'         => $third_section['content'] ?? '',
        ];
        
        return $return_data;
    }
    
    public function get_all_pages() {
        $return_data = [];
        
        $args = [
            'post_type'         => 'page',
            'numberposts'       => -1
        ];
        
        $get_pages = get_posts($args);
        
        if(!empty($get_pages)) {
            foreach($get_pages as $get_page) {
                $page_template = get_page_template_slug($get_page->ID);
                if(in_array($page_template, ['page-problem.php', 'page-solution.php'] )) {
                    $return_data[] = [
                        'id'        => $get_page->ID,
                        'title'     => $get_page->post_title,
                        'slug'      => $get_page->post_name,
                    ];
                }
            }
        }
        
        return $return_data;
    }

    public function get_default_page($slug) {
        if(empty($slug)) {
            return wp_send_json_error('Slug parameter is empty', $this->error_http_response_code);
        }

        $page = get_page_by_path($slug);
        
        if(empty($page)) return wp_send_json_error('Cannot find this page', $this->error_http_response_code);
        
        $return_data = [];
        
        if(!empty($page)) {
            $page_template = get_page_template_slug($page->ID);
            switch($page_template) {
                case 'page-problem.php':
                    $return_data = $this->get_problem_template($page->post_name);
                    $return_data['template'] = 'problem-template';
                    break;
                case 'page-solution.php':
                    $return_data = $this->get_solution_template($page->post_name);
                    $return_data['template'] = 'solution-template';
                    break;
                default:
                    $return_data = [
                        'title'         => $page->post_title,
                        'description'   => Utilities::get_instance()->filter_post_content($page->post_content, $page->ID),
                        'image'         => has_post_thumbnail($page->ID) ? get_the_post_thumbnail_url($page->ID) : null,
                        'template'      => ''
                    ];
            }
        }
        
        return wp_send_json_success($return_data, $this->success_http_response_code);
    }

    public function get_implementation_toolkit_menu()
    {
        $args = [
            'names' => 'reading',
        ];

        $post_types = get_post_types($args);

    }
    
    public function return_specific_tabs($post_type, $menu, $type = 'reading', $page = 1)
    {
        $args = [
            'post_type' => $post_type,
            'name' => $menu,
            'numberposts' => 1
        ];
        
        $post = get_posts($args);
        
        if (!empty($post)) {
            $post = $post[0];
            
            $default_category = 'reading_category';
            switch ($type) {
                case 'in_practice':
                    $default_category = 'in_practice_category';
                    break;
                case 'tools':
                    $default_category = 'tools_category';
                    break;
                default:
                    $default_category = 'reading_category';
            }
            
            return wp_send_json_success($this->return_specific_reading_with_tabs($type, $post->post_title, $post->post_type, $default_category, $page), $this->success_http_response_code);
        }
    }
    
    public function get_lists_of_toolkit_pages() {
        $return_data = [];
        $toolkits = Utilities::get_instance()->implementation_toolkits_list();

        $args = [
            'post_type'     => $toolkits,
            'numberposts'   => -1
        ];
        
        $lists = get_posts($args);
        
        if(!empty($lists)) {
            foreach ($lists as $list) {
                $return_data[] = [
                    'params' => [
                        'toolkit'       => $list->post_type,
                        'toolkitInner'  => $list->post_name,
                        'name'          => $list->post_title
                    ]
                ];
            }
        }
        
        return $return_data;
    }
    
    /**
     * @param $slug
     * @param $page_number_arrays
     * @return mixed
     */
    public function get_list_of_toolkits($slug, $page_number_arrays)
    {
        if(empty($slug)) {
            return wp_send_json_error('Slug parameter is empty', $this->error_http_response_code);
        }
        
        $args = [
            'post_type'         => $slug,
            'numberposts'       => -1
        ];
        
        $menus = get_posts($args);
        
        $return_data = [];
        if(!empty($menus)) {
            foreach ($menus as $key => $menu) {
                $return_data['toolkits'][$key] = [
                    'id'    => $menu->ID,
                    'name'    => $menu->post_title,
                    'slug'    => $menu->post_name,
                ];
                
                $args = [
                    'post_type'     => $slug,
                    'name'          => $menu->post_name,
                    'numberposts'   => 1
                ];
                
                $post = get_posts($args);
                
                if(!empty($post)) {
                    $post = $post[0];
                    
                    $return_data['toolkits'][$key][$menu->post_name]['overview'] = [
                        'id'    => $post->ID,
                        'title'    => $post->post_title,
                        'slug'    => $post->post_name,
                        'content'    => Utilities::get_instance()->filter_post_content($post->post_content, $post),
                    ];
                }
                
                //get the other tabs
                $return_data['toolkits'][$key][$menu->post_name]['reading'] = $this->return_reading_with_tabs('reading', $menu->post_title, '', $page_number_arrays['reading_page_number']);
                $return_data['toolkits'][$key][$menu->post_name]['tools'] = $this->return_posts_with_tabs('tools', $menu->post_title, '', 'tools_category', $page_number_arrays['tools_page_number']);
                $return_data['toolkits'][$key][$menu->post_name]['in_practice'] = $this->return_posts_with_tabs('in_practice', $menu->post_title, '', 'in_practice_category', $page_number_arrays['in_practice_page_number']);
            }
            
            return wp_send_json_success($return_data, $this->success_http_response_code);
        }
        
        return wp_send_json_success([], $this->success_http_response_code);
    }
    
    public function get_single_toolkits($post_type, $menu, $page_number_arrays = [])
    {
        $return_data = [];
        
        $args = [
            'post_type' => $post_type,
            'name' => $menu,
            'numberposts' => 1
        ];
        
        $post = get_posts($args);
        
        if (!empty($post)) {
            $post = $post[0];
            
            $return_data['overview'] = [
                'id' => $post->ID,
                'title' => $post->post_title,
                'slug' => $post->post_name,
                'content' => Utilities::get_instance()->filter_post_content($post->post_content, $post),
            ];
            
            //get the other tabs
            $return_data['reading'] = $this->return_reading_with_tabs('reading', $post->post_title, $post->post_type, $page_number_arrays['reading_page_number']);
            $return_data['tools'] = $this->return_posts_with_tabs('tools', $post->post_title, $post->post_type, 'tools_category', $page_number_arrays['tools_page_number']);
            $return_data['in_practice'] = $this->return_posts_with_tabs('in_practice', $post->post_title, $post->post_type, 'in_practice_category', $page_number_arrays['in_practice_page_number']);
            
            return wp_send_json_success($return_data, $this->success_http_response_code);
        } else return wp_send_json_success([], $this->success_http_response_code);
    }

    public function get_list_of_posts($slug)
    {
        if(empty($slug)) {
            return wp_send_json_error('Slug parameter is empty', $this->error_http_response_code);
        }

        $args = [
            'post_type'         => $slug,
            'numberposts'       => -1
        ];

        $posts = get_posts($args);

        $return_data = [];
        if(!empty($posts)) {
            foreach ($posts as $post) {
                $return_data[] = [
                    'id'    => $post->ID,
                    'name'    => $post->post_title,
                    'slug'    => $post->post_name,
                ];
            }

            return wp_send_json_success($return_data, $this->success_http_response_code);
        }

        return wp_send_json_success([], $this->success_http_response_code);
    }

    public function get_single_post($slug, $post_type) {
        if(empty($slug) || empty($post_type)) {
            return wp_send_json_error('Post type and slug not set', $this->error_http_response_code);
        }

        $args = [
            'post_type'     => $post_type,
            'name'          => $slug,
            'numberposts'   => 1
        ];

        $post = get_posts($args);

        $return_data = [];
        if(!empty($post)) {
            $post = $post[0];

            $return_data = [
                'id'    => $post->ID,
                'title'    => $post->post_title,
                'slug'    => $post->post_name,
                'content'    => Utilities::get_instance()->filter_post_content($post->post_content, $post),
            ];
        }

        return wp_send_json_success($return_data, $this->success_http_response_code);
    }
    
    public function return_posts_with_tabs($post_type, $taxonomy, $parent_taxonomy = '', $category_name = '', $page = 1, $posts_per_page = 8)
    {
        if (empty($taxonomy) || empty($post_type)) {
            return [];
        }
        
        $taxonomy_names = get_object_taxonomies($post_type);
        
        $total_args = [
            'post_type' => $post_type,
            'numberposts' => -1,
        ];
        
        $args = [
            'post_type' => $post_type,
            'numberposts' => $posts_per_page,
            'page' => $page
        ];
        
        if (!empty($category_name)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => $category_name,
                    'field' => 'name',
                    'terms' => $taxonomy
                ]
            ];
            $total_args['tax_query'] = [
                [
                    'taxonomy' => $category_name,
                    'field' => 'name',
                    'terms' => $taxonomy
                ]
            ];
        }
        
        if (!empty($parent_taxonomy)) {
            $parent_args = [
                [
                    'taxonomy' => $taxonomy_names[1],
                    'field' => 'slug',
                    'terms' => $parent_taxonomy
                ]
            ];
            
            $args['tax_query']['relation'] = 'AND';
            $args['tax_query'] = array_merge($parent_args, $args['tax_query']);
            $total_args['tax_query']['relation'] = 'AND';
            $total_args['tax_query'] = array_merge($parent_args, $total_args['tax_query']);
        }
        
        $posts = get_posts($args);
        
        $total_posts = get_posts($total_args);
        
        $return_data = [];
        
        $return_data['data'] = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                //check if the lists of posts doesn't belong to oh
                $post_thumbnail = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail_url($post->ID) : null;
                $return_data['data'][] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'slug' => $post->post_name,
                    'excerpt' => $post->post_excerpt,
                    'content' => Utilities::get_instance()->filter_post_content($post->post_content, $post),
                    'image' => $post_thumbnail,
                    'category' => Utilities::get_instance()->return_category($post->ID, 'default_custom_category'),
                    'date' => date('d/m/Y', strtotime($post->post_date)),
                ];
            }
        }
        
        $return_data = array_merge($return_data, Utilities::get_instance()->return_post_count($total_posts, $posts, $posts_per_page));
        
        return $return_data;
    }
    
    public function return_specific_reading_with_tabs($post_type, $taxonomy, $parent_taxonomy = '', $default_category = '', $page = 1, $posts_per_page = 8)
    {
        if (empty($taxonomy) || empty($post_type)) {
            return [];
        }
        
        $taxonomy_names = get_object_taxonomies($post_type);
        
        $args = [
            'post_type' => $post_type,
            'numberposts' => $posts_per_page,
            'paged' => $page,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy_names[1],
                    'field' => 'name',
                    'terms' => $taxonomy
                ],
            ],
        ];
        
        if (!empty($parent_taxonomy)) {
            $parent_args = [
                [
                    'taxonomy' => $taxonomy_names[1],
                    'field' => 'slug',
                    'terms' => $parent_taxonomy
                ]
            ];
            
            $args['tax_query']['relation'] = 'AND';
            $args['tax_query'] = array_merge($parent_args, $args['tax_query']);
        }
        
        $posts = get_posts($args);
        
        $return_data = [];
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $post_thumbnail = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail_url($post->ID) : null;
                $return_data[] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'slug' => $post->post_name,
                    'excerpt' => $post->post_excerpt,
                    'content' => Utilities::get_instance()->filter_post_content($post->post_content, $post),
                    'image' => $post_thumbnail,
                    'category' => Utilities::get_instance()->return_category($post->ID, $default_category),
                    'date' => date('d/m/Y', strtotime($post->post_date)),
                ];
            }
        }
        
        return $return_data;
    }
    
    
    public function return_reading_with_tabs($post_type, $taxonomy, $parent_taxonomy = '', $page = 1, $posts_per_page = 8)
    {
        if (empty($taxonomy) || empty($post_type)) {
            return [];
        }
        
        $taxonomy_names = get_object_taxonomies($post_type);
        
        $total_args = [
            'post_type' => $post_type,
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy_names[1],
                    'field' => 'name',
                    'terms' => $taxonomy
                ],
            ],
        ];
        
        $args = [
            'post_type' => $post_type,
            'numberposts' => $posts_per_page,
            'paged' => $page,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy_names[1],
                    'field' => 'name',
                    'terms' => $taxonomy
                ],
            ],
        ];
        
        if (!empty($parent_taxonomy)) {
            $parent_args = [
                [
                    'taxonomy' => $taxonomy_names[1],
                    'field' => 'slug',
                    'terms' => $parent_taxonomy
                ]
            ];
            
            $args['tax_query']['relation'] = 'AND';
            $args['tax_query'] = array_merge($parent_args, $args['tax_query']);
            $total_args['tax_query']['relation'] = 'AND';
            $total_args['tax_query'] = array_merge($parent_args, $args['tax_query']);
        }
        
        $posts = get_posts($args);
        $total_posts = get_posts($total_args);
        
        $return_data = [];
        
        $return_data['data'] = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $post_thumbnail = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail_url($post->ID) : null;
                $return_data['data'][] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'slug' => $post->post_name,
                    'excerpt' => $post->post_excerpt,
                    'content' => Utilities::get_instance()->filter_post_content($post->post_content, $post),
                    'image' => $post_thumbnail,
                    'category' => Utilities::get_instance()->return_category($post->ID, 'default_reading_category'),
                    'date' => date('d/m/Y', strtotime($post->post_date)),
                ];
            }
        }
        
        $return_data = array_merge($return_data, Utilities::get_instance()->return_post_count($total_posts, $posts, $posts_per_page));
        
        return $return_data;
    }

    public function get_posts_with_tabs($post_type, $taxonomy) {
        if(empty($taxonomy) || empty($post_type)) {
            return wp_send_json_error('Necessary parameters not set completely', $this->error_http_response_code);
        }

        $taxonomy_names = get_object_taxonomies($post_type);

        $args = [
          'post_type'   => $post_type,
          'numberposts'   => -1,
          'tax_query'   => [
              [
                  'taxonomy'      => $taxonomy_names[0],
                  'field'         => 'slug',
                  'terms'         =>  $taxonomy
              ],
          ],
        ];

        $posts  = get_posts($args);

        $return_data = [];

        if(!empty($posts)) {
            foreach($posts as $post) {
                $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;
                $return_data[] = [
                    'id'            => $post->ID,
                    'title'         => $post->post_title,
                    'slug'          => $post->post_name,
                    'excerpt'       => $post->post_excerpt,
                    'content'       => Utilities::get_instance()->filter_post_content($post->post_content, $post),
                    'image'         => $post_thumbnail,
                    'category'      => Utilities::get_instance()->return_category($post->ID, 'default_custom_category')
                ];
            }
        }

        return wp_send_json_success($return_data, $this->success_http_response_code);
    }

    public function get_post_single($id) {
        if(empty($id)) {
            return wp_send_json_error('Slug parameter is missing', $this->error_http_response_code);
        }

        $post_type = get_post_type($id);

        $args = [
            'post__in'      => [$id],
            'numberposts'   => 1,
            'post_type'     => $post_type,
        ];


        $post = get_posts($args);

        if(!empty($post)) {
            $post = $post[0];
            $post_thumbnail = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail_url( $post->ID ) : null;
            $return_data = [
                'id'            => $post->ID,
                'title'         => $post->post_title,
                'slug'          => $post->post_name,
                'content'       => Utilities::get_instance()->filter_post_content($post->post_content, $post),
                'image'         => $post_thumbnail,
                'category'      => Utilities::get_instance()->return_category($post->ID, 'default_custom_category'),
                'date'          => date('d/m/Y', strtotime($post->post_date)),
            ];
        }

        return wp_send_json_success($return_data, $this->success_http_response_code);
    }


    /**
     * Singleton poop.
     *
     * @return Pages|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}