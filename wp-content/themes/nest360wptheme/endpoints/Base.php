<?php

namespace NEST360WPTheme\endpoints;

class Base {

    private $error_http_response_code = 401;

    private $success_http_response_code = 200;

    public function __construct() {
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    public function register_rest_routes() {
        //get the primary meny
        register_rest_route( NEST360_BASE_ROUTE, '/header_menu', array(
            'methods' => 'GET',
            'callback' => function(){
                return PrimaryMenu::get_instance()->get_primary_menu();
            },
            'permission_callback' => '__return_true',
        ));

        //get the homepage fields
        register_rest_route( NEST360_BASE_ROUTE, '/home', array(
            'methods' => 'GET',
            'callback' => function(){
                return HomePageFields::get_instance()->get_homepage_fields();
            },
            'permission_callback' => '__return_true',
        ));

        //get the problem page
        register_rest_route( NEST360_BASE_ROUTE, '/about/problem', array(
            'methods' => 'GET',
            'callback' => function(){
                return Pages::get_instance()->get_about_problem();
            },
            'permission_callback' => '__return_true',
        ));

        //get the problem page
        register_rest_route( NEST360_BASE_ROUTE, '/about/solution', array(
            'methods' => 'GET',
            'callback' => function(){
                return Pages::get_instance()->get_about_solution();
            },
            'permission_callback' => '__return_true',
        ));

        //get the list of team categories
        register_rest_route( NEST360_BASE_ROUTE, '/team/categories', array(
            'methods' => 'GET',
            'callback' => function(){
                return Category::get_instance()->get_categories('team_category');
            },
            'permission_callback' => '__return_true',
        ));

        //get all the teams
        register_rest_route( NEST360_BASE_ROUTE, '/teams', array(
            'methods' => 'GET',
            'callback' => function(){
                return Team::get_instance()->get_all_team();
            },
            'permission_callback' => '__return_true',
        ));

        //get the team by categories
        register_rest_route( NEST360_BASE_ROUTE, '/teams/category/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $category = $requests->get_param('slug');
                $include_logos = $requests->get_param('include_logos') ? $requests->get_param('include_logos') : '';
                return Team::get_instance()->get_team_by_category($category, $include_logos);
            },
            'permission_callback' => '__return_true',
        ));

        //get the news page banner
        register_rest_route( NEST360_BASE_ROUTE, '/news-banner', array(
            'methods' => 'GET',
            'callback' => function(){
                return News::get_instance()->get_news_banner();
            },
            'permission_callback' => '__return_true',
        ));

        //get the lists of news
        register_rest_route( NEST360_BASE_ROUTE, '/news', array(
            'methods' => \WP_REST_SERVER::READABLE,
            'callback' => function($requests){
                $per_page = $requests->get_param('per_page') ? $requests->get_param('per_page') : '12';
                $page = $requests->get_param('page') ? $requests->get_param('page') : '1';
                return News::get_instance()->get_news($per_page, $page);
            },
            'permission_callback' => '__return_true',
        ));

        //get a single news
        register_rest_route( NEST360_BASE_ROUTE, '/news/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $slug = $requests->get_param('slug');
                return News::get_instance()->get_new($slug);
            },
            'permission_callback' => '__return_true',
        ));
    
        //get the lists of covid-19
        register_rest_route( NEST360_BASE_ROUTE, '/covid-19', array(
            'methods' => \WP_REST_SERVER::READABLE,
            'callback' => function($requests){
                $per_page = $requests->get_param('per_page') ? $requests->get_param('per_page') : '12';
                $page = $requests->get_param('page') ? $requests->get_param('page') : '1';
                return Covid19::get_instance()->get_news($per_page, $page);
            },
            'permission_callback' => '__return_true',
        ));
    
        //get a single covid 19 news
        register_rest_route( NEST360_BASE_ROUTE, '/covid-19/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $slug = $requests->get_param('slug');
                return Covid19::get_instance()->get_new($slug);
            },
            'permission_callback' => '__return_true',
        ));
    
        //get the lists of communities
        register_rest_route( NEST360_BASE_ROUTE, '/communities', array(
            'methods' => \WP_REST_SERVER::READABLE,
            'callback' => function($requests){
                $per_page = $requests->get_param('per_page') ? $requests->get_param('per_page') : '12';
                $page = $requests->get_param('page') ? $requests->get_param('page') : '1';
                return Communities::get_instance()->get_news($per_page, $page);
            },
            'permission_callback' => '__return_true',
        ));
    
        //get the lists of events
        register_rest_route( NEST360_BASE_ROUTE, '/slugs/communities', array(
            'methods' => 'GET',
            'callback' => function($requests){
                return Utilities::get_instance()->get_post_types_slugs('communities', 'community');
            },
            'permission_callback' => '__return_true',
        ));
    
        //get a single covid 19 news
        register_rest_route( NEST360_BASE_ROUTE, '/communities/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $slug = $requests->get_param('slug');
                return Communities::get_instance()->get_new($slug);
            },
            'permission_callback' => '__return_true',
        ));

        //get the lists of news
        register_rest_route( NEST360_BASE_ROUTE, '/case-studies', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $per_page = $requests->get_param('per_page') ? $requests->get_param('per_page') : '12';
                $page = $requests->get_param('page') ? $requests->get_param('page') : '1';
                $country = $requests->get_param('country') ? $requests->get_param('country') : '';
                return News::get_instance()->get_lists_of_case_studies('case_studies', 'case_study_category', $per_page, $page, $country);
            },
            'permission_callback' => '__return_true',
        ));

        //get the lists of news
        register_rest_route( NEST360_BASE_ROUTE, '/case-study/categories', array(
            'methods' => 'GET',
            'callback' => function(){
                return News::get_instance()->get_lists_of_case_studies_category('case_study_category');
            },
            'permission_callback' => '__return_true',
        ));
    
        //get the lists of events
        register_rest_route( NEST360_BASE_ROUTE, '/slugs/case-studies', array(
            'methods' => 'GET',
            'callback' => function($requests){
                return Utilities::get_instance()->get_post_types_slugs('case_studies', 'caseStudy');
            },
            'permission_callback' => '__return_true',
        ));

        //get a single news
        register_rest_route( NEST360_BASE_ROUTE, '/case-studies/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $slug = $requests->get_param('slug');
                return News::get_instance()->get_new($slug, 'case_studies', 'case_study_category');
            },
            'permission_callback' => '__return_true',
        ));

        //get the lists of tools
        register_rest_route( NEST360_BASE_ROUTE, '/tools', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $per_page = $requests->get_param('per_page') ? $requests->get_param('per_page') : '12';
                $filter_by = $requests->get_param('filter_by') ? $requests->get_param('filter_by') : '';
                $page = $requests->get_param('page') ? $requests->get_param('page') : '1';
                return News::get_instance()->get_lists_of_posts('tools', 'default_custom_category', $per_page, $page, $filter_by);
            },
            'permission_callback' => '__return_true',
        ));


        //get a single news
        register_rest_route( NEST360_BASE_ROUTE, '/tools/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $slug = $requests->get_param('slug');
                return News::get_instance()->get_new($slug, 'tools', 'default_custom_category');
            },
            'permission_callback' => '__return_true',
        ));

        register_rest_route( NEST360_BASE_ROUTE, '/in_practice', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $per_page = $requests->get_param('per_page') ? $requests->get_param('per_page') : '12';
                $page = $requests->get_param('page') ? $requests->get_param('page') : '1';
                $filter_by = $requests->get_param('filter_by') ? $requests->get_param('filter_by') : '';
                return News::get_instance()->get_lists_of_posts('in_practice', 'default_custom_category', $per_page, $page, $filter_by);
            },
            'permission_callback' => '__return_true',
        ));

        //get a single news
        register_rest_route( NEST360_BASE_ROUTE, '/in_practice/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $slug = $requests->get_param('slug');
                return News::get_instance()->get_new($slug, 'in_practice', 'default_custom_category');
            },
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( NEST360_BASE_ROUTE, '/reading/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $slug = $requests->get_param('slug');
                return News::get_instance()->get_new($slug, 'reading', 'reading_category');
            },
            'permission_callback' => '__return_true',
        ));

        //get all news letter
        register_rest_route( NEST360_BASE_ROUTE, '/newsletters', array(
            'methods' => 'GET',
            'callback' => function($requests){
                return News::get_instance()->get_lists_of_newsletter();
            },
            'permission_callback' => '__return_true',
        ));
    
        //get the details of default page of title and content
        register_rest_route( NEST360_BASE_ROUTE, '/pages', array(
            'methods' => 'GET',
            'callback' => function( $requests){
                return Pages::get_instance()->get_all_pages();
            },
            'permission_callback' => '__return_true',
        ));

        //get the details of default page of title and content
        register_rest_route( NEST360_BASE_ROUTE, '/pages/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function( $requests){
                $page_slug = $requests->get_param('slug');
                return Pages::get_instance()->get_default_page($page_slug);
            },
            'permission_callback' => '__return_true',
        ));
    
        //get the sidebar menu of toolkits pages
        register_rest_route( NEST360_BASE_ROUTE, '/implementation_toolkits/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function( $requests){
                $page_slug = $requests->get_param('slug');
    
                $page_numbers_array = [
                    'reading_page_number' => $requests->get_param('reading_page_number') ?? 1,
                    'tools_page_number' => $requests->get_param('tools_page_number') ?? 1,
                    'in_practice_page_number' => $requests->get_param('in_practice_page_number') ?? 1,
                ];
                return Pages::get_instance()->get_list_of_toolkits($page_slug, $page_numbers_array);
            },
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( NEST360_BASE_ROUTE, '/implementation_toolkit/pages', array(
            'methods' => 'GET',
            'callback' => function(){
                return Pages::get_instance()->get_lists_of_toolkit_pages();
            },
            'permission_callback' => '__return_true',
        ));

        //get the sidebar menu of toolkits pages
        register_rest_route( NEST360_BASE_ROUTE, '/implementation_toolkits/menu/', array(
            'methods' => 'GET',
            'callback' => function(){
                return Pages::get_instance()->get_implementation_toolkit_menu();
            },
            'permission_callback' => '__return_true',
        ));

        //get the sidebar menu of toolkits pages
        register_rest_route( NEST360_BASE_ROUTE, '/implementation_toolkits/menu/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function( $requests){
                $page_slug = $requests->get_param('slug');
                return Pages::get_instance()->get_list_of_posts($page_slug);
            },
            'permission_callback' => '__return_true',
        ));
//
    
        register_rest_route( NEST360_BASE_ROUTE, '/implementation_toolkits/(?P<post_type>[a-zA-Z0-9-_]+)/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function( $requests){
                $post_type = $requests->get_param('post_type');
                $page_slug = $requests->get_param('slug');
            
                //reading, tools and in practise pagination
                $page_numbers_array = [
                    'reading_page_number' => $requests->get_param('reading_page_number') ?? 1,
                    'tools_page_number' => $requests->get_param('tools_page_number') ?? 1,
                    'in_practice_page_number' => $requests->get_param('in_practice_page_number') ?? 1,
                ];
            
                return Pages::get_instance()->get_single_toolkits($post_type, $page_slug, $page_numbers_array);
            },
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( NEST360_BASE_ROUTE, '/implementation_toolkits/(?P<post_type>[a-zA-Z0-9-_]+)/(?P<slug>[a-zA-Z0-9-_]+)/tabs/(?P<type>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function( $requests){
                $post_type = $requests->get_param('post_type');
                $page_slug = $requests->get_param('slug');
                $type = $requests->get_param('type');
                $page = $requests->get_param('page') ?? 1;
            
                return Pages::get_instance()->return_specific_tabs($post_type, $page_slug, $type, $page);
            },
            'permission_callback' => '__return_true',
        ));
//
        //get the sidebar menu of toolkits pages
        register_rest_route( NEST360_BASE_ROUTE, '/implementation_toolkits/(?P<taxonomy>[a-zA-Z0-9-_]+)/category/(?P<post_type>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function( $requests){
                $post_type = $requests->get_param('post_type');
                $taxonomy = $requests->get_param('taxonomy');

                return Pages::get_instance()->get_posts_with_tabs($post_type, $taxonomy);
            },
            'permission_callback' => '__return_true',
        ));

        //get the single page of a post
        register_rest_route( NEST360_BASE_ROUTE, '/post/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => function( $requests){
                $id = $requests->get_param('id');

                return Pages::get_instance()->get_post_single($id);
            },
            'permission_callback' => '__return_true',
        ));

        //get the lists of events
        register_rest_route( NEST360_BASE_ROUTE, '/event/categories', array(
            'methods' => 'GET',
            'callback' => function($requests){
                return Events::get_instance()->get_events_categories();
            },
            'permission_callback' => '__return_true',
        ));

        //get the lists of events
        register_rest_route( NEST360_BASE_ROUTE, '/slugs/events', array(
            'methods' => 'GET',
            'callback' => function($requests){
                return Utilities::get_instance()->get_post_types_slugs('my_events', 'event');
            },
            'permission_callback' => '__return_true',
        ));
        
        register_rest_route( NEST360_BASE_ROUTE, '/events/(?P<slug>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $slug = $requests->get_param('slug');
                $member_only = $requests->get_param('member_only') ? $requests->get_param('member_only') : '0';
                return Events::get_instance()->get_single_event($slug, $member_only);
            },
            'permission_callback' => '__return_true',
        ));

        //get the lists of events
        register_rest_route( NEST360_BASE_ROUTE, '/events', array(
            'methods' => 'GET',
            'callback' => function($requests){
                $per_page = $requests->get_param('per_page') ? $requests->get_param('per_page') : '12';
                $page = $requests->get_param('page') ? $requests->get_param('page') : '1';
                $member_only = $requests->get_param('member_only') ? $requests->get_param('member_only') : '0';
                $category = $requests->get_param('category') ? $requests->get_param('category') : '';
                $dates = $requests->get_param('dates') ? $requests->get_param('dates') : '';
                return Events::get_instance()->get_lists_of_events('my_events', 'events_category', $per_page, $page, $member_only, $category, $dates);
            },
            'permission_callback' => '__return_true',
        ));

        //get the lists of events
        register_rest_route( NEST360_BASE_ROUTE, '/event/dates', array(
            'methods' => 'GET',
            'callback' => function(){
                return Events::get_instance()->get_events_months();
            },
            'permission_callback' => '__return_true',
        ));

        //get the search attributes
        register_rest_route( NEST360_BASE_ROUTE, '/search', array(
            'methods' => 'GET',
            'callback' => function($requests){
                return Search::get_instance()->get_search_attributes();
            },
            'permission_callback' => '__return_true',
        ));

        //get the search attributes
        register_rest_route( NEST360_BASE_ROUTE, '/search', array(
            'methods' => 'POST',
            'callback' => function($requests){
                $search_term = ' '.$requests->get_param('search'). ' ';
                $category = $requests->get_param('category');
                $user_type = $requests->get_param('user_type');
                $hsbb = $requests->get_param('hsbb');
                $per_page = $requests->get_param('per_page') ? $requests->get_param('per_page') : '10';
                $page = $requests->get_param('page') ? $requests->get_param('page') : '1';
                return Search::get_instance()->find_search_results($search_term, $category, $user_type, $hsbb, $per_page, $page);
            },
            'permission_callback' => '__return_true',
        ));


    }

    public function get_header() {
        $posts_data = array();
        $menu_locations = get_nav_menu_locations();
        $primary_menu = $menu_locations['primary_menu'];
        $menu = wp_get_nav_menu_object($primary_menu);
        $primary_menu = wp_get_nav_menu_items($menu->term_id);

        foreach ($primary_menu as $key => $value) {
            $posts_data['slug'][$key] = basename($value->url);
            $posts_data['title'][$key] = $value->title;
        }

        return json_encode($posts_data);
    }

    /**
     * Singleton poop.
     *
     * @return Base|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}