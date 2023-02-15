<?php

namespace NEST360WPTheme\inc;

class Init {
    public static function init() {
       FormValidity::get_instance();
       PreviewPosts::get_instance();
       Taxonomy::get_instance();
       Shortcodes::get_instance();
       YoastSEO::get_instance();
    }
}