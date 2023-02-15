<?php

namespace NEST360WPTheme\wp_trigger;

class Trigger {
    public function __construct()
    {
        add_action('admin_init', [$this, 'deploy']);
        add_action( 'admin_bar_menu', [$this, 'deploy_wp_toolbar_link'], 999);
        add_action( 'customize_register', [$this, 'register_wp_trigger_settings'], 1, 1);
    }

    /**
     * Ensure the WordPress Deploys
     */
    public function deploy() {
        if(isset($_GET['nest360wp-deploy']) && $_GET['nest360wp-deploy'] === __('yes', 'nest360-wp-theme')) {
            $nest360_ga_option_token = get_theme_mod('nest360_ga_option_token');
            $nest360_ga_username = get_theme_mod('nest360_ga_username');
            $nest360_ga_repo = get_theme_mod('nest360_ga_repo');

            if (empty($nest360_ga_option_token) || empty($nest360_ga_username) || empty($nest360_ga_repo)) {
                return add_action( 'admin_notices', function() {
                    $class = 'notice notice-error';
                    $message = __( 'Ensure you fill all the Deploy Required Fields in Customizer Settings', 'nest360-wp-theme' );

                    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                } );
            }

            //if all the settings are filled
            if(!is_wp_error($this->run_hook())) {
                add_action( 'admin_notices', function() {
                    ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php _e( 'Deployment was successful.', 'nest360-wp-theme' ); ?></p>
                    </div>
                    <?php
                } );
            } else {
                add_action( 'admin_notices', function() {
                    $class = 'notice notice-error';
                    $message = __( 'Deployment failed', 'sample-text-domain' );

                    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                } );
            }
        }
    }
    /**
     * add the deploy button to the admin bar
     */
    public function deploy_wp_toolbar_link( $wp_admin_bar ) {
        global $wp;
        $current_url =  add_query_arg( $_SERVER['QUERY_STRING'], '', admin_url() );
        $url = add_query_arg( 'nest360wp-deploy', 'yes', $current_url);
        $args = array(
            'id' => 'nest360wp-deploy',
            'title' => 'Deploy to Frontend',
            'href' => $url,
            'meta' => array(
                'title' => 'Deploy WordPress'
            )
        );
        $wp_admin_bar->add_node( $args );
    }

    private function run_hook()
    {
        $github_token = get_theme_mod('nest360_ga_option_token');
        $github_username = get_theme_mod('nest360_ga_username');
        $github_repo = get_theme_mod('nest360_ga_repo');

        if ($github_token && $github_username && $github_repo) {
            $url = 'https://api.github.com/repos/' . $github_username . '/' . $github_repo . '/dispatches';
            $args = array(
                'method'  => 'POST',
                'body'    => json_encode(array(
                    'event_type' => 'wordpress'
                )),
                'headers' => array(
                    'Accept' => 'application/vnd.github.v3+json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'token ' . $github_token
                ),
            );

            wp_remote_post($url, $args);
        } else {
            return false;
        }
    }

    /**
     * Register all the fields needed to trigger wordpress
     *
     */
    public function register_wp_trigger_settings($wp_customize) {
        /**
         * Add Section for Github settings
         *
         */
        $wp_customize->add_section('nest360_deploy_settings', array(
            'title' => 'Deploy Settings',
            'description' => 'Enter Github Settings Information',
            'priority' => 110,
        ));

        $wp_customize->add_setting('nest360_ga_option_token', array(
            'default' => '',
            'type' => 'theme_mod',
        ));

        $wp_customize->add_control('nest360_ga_option_token', array(
            'label' => __('Github Token', ''),
            'section' => 'nest360_deploy_settings',
            'settings' => 'nest360_ga_option_token',
            'type' => 'text'
        ));

        $wp_customize->add_setting('nest360_ga_username', array(
            'default' => '',
            'type' => 'theme_mod',
        ));

        $wp_customize->add_control('nest360_ga_username', array(
            'label' => __('Github Username', ''),
            'section' => 'nest360_deploy_settings',
            'settings' => 'nest360_ga_username',
            'type' => 'text'
        ));

        $wp_customize->add_setting('nest360_ga_repo', array(
            'default' => '',
            'type' => 'theme_mod',
        ));

        $wp_customize->add_control('nest360_ga_repo', array(
            'label' => __('Github Repo', ''),
            'section' => 'nest360_deploy_settings',
            'settings' => 'nest360_ga_repo',
            'type' => 'text'
        ));
    }

    /**
     * Singleton poop.
     *
     * @return Trigger|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}