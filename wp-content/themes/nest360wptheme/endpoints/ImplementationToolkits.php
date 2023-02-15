<?php

namespace NEST360WPTheme\endpoints;

class ImplementationToolkits {
    private int $error_http_response_code = 401;

    private int $success_http_response_code = 200;


    /**
     * Singleton poop.
     *
     * @return ImplementationToolkits|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}