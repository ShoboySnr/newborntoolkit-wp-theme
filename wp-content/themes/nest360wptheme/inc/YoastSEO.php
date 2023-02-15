<?php

namespace NEST360WPTheme\inc;

class YoastSEO {
    
    /**
     * Constructor
     *
     */
    public function __construct() {
        add_filter( 'wpseo_stylesheet_url', [$this, 'filter_wpseo_stylesheet_url'], 10, 1 );
    
        add_filter( 'wpseo_sitemap_index_links', [$this, 'filter_wpseo_sitemap_index_links'], 10, 1 );
    
        add_filter('rest_url', [$this, 'rest_url_init']);
    }
    
    public function filter_wpseo_stylesheet_url ( $stylesheet ) {
        $home = parse_url(get_option('home'));
        $site = parse_url(get_option('siteurl'));
        
        return str_replace($home, $site, $stylesheet);
    }
    
    public function filter_wpseo_sitemap_index_links ( $links ) {
        $home = parse_url(get_option('home'));
        $site = parse_url(get_option('siteurl'));
        
        foreach($links as $i => $link)
            $links[$i]['loc'] = str_replace($home, $site, $link['loc']);
        return $links;
    }
    
    
    public function rest_url_init ( $url ) {
        $url = str_replace(home_url(), site_url(), $url);
        return $url;
    }
    
    /**
     * Singleton poop.
     *
     * @return YoastSEO
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}