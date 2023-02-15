<?php

namespace NEST360WPTheme\inc;

class PageTemplates {
    
    public function __construct()
    {
        add_action( 'load-post.php', [$this, 'custom_page_templates_boxes_setup']);
        add_action( 'load-post-new.php', [$this, 'custom_page_templates_boxes_setup']);
    }
    
    public function custom_page_templates_boxes_setup()
    {
        add_action( 'add_meta_boxes', [$this, 'custom_page_templates_boxes']);
    }
    
    public function custom_page_templates_boxes()
    {
        add_meta_box(
            'nest360-custom-page-template-selector',
            esc_html__('Select Page Templates', 'nest360-wp-theme'),
            [$this, 'custom_page_templates_selector_callback'],
            'page',
            'side',
            'default'
        );
    }
    
    public function custom_page_templates_selector_callback() {
        ?>
        <?php wp_nonce_field( basename( __FILE__ ), 'custom_page_templates_boxes_class_nonce' ); ?>
        <div class="nest360-custom-page-templates-container">
            <label for="nest360-custom-page-templates-class"><?php _e( "Select a Template", 'nest360-wp-theme' ); ?></label>
            <select id="nest360-custom-page-templates-class" class="" name="nest360-custom-page-templates-class">
                <option value=""><?= __('Select one...', 'nest360-wp-theme') ?></option>
                <option value="problem-template"><?= __('Problem Template', 'nest360-wp-theme') ?></option>
                <option value="solution-template"><?= __('Solution Template', 'nest360-wp-theme') ?></option>
            </select>
        </div>
        <style>
            .nest360-custom-page-templates-container {
                width: 100%;
            }

            .nest360-custom-page-templates-container select {
                margin-top: 15px;
            }
        </style>
    <?php
    }
    
    /**
     * Singleton poop.
     *
     * @return PageTemplates|null
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}