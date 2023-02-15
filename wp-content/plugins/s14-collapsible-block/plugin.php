<?php
/**
 * Plugin Name: S14 Collapsible Block
 * Plugin URI: http://studio14online.co.uk
 * Description: Custom Made Collapsible Block Extending to Rest API
 * Version: 1.0
 * Author: S14 WordPress Devs
 * Author URI: http://studio14online.co.uk
 *
 * @category Gutenberg
 * @author S14 WordPress Devs
 * @version 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function collapsible_block_editor_assets(){
	$url = untrailingslashit( plugin_dir_url( __FILE__ ) );
	
    // Scripts.
    wp_enqueue_script(
        's14-collapsible-block-js', // Handle.
        plugins_url('build/index.js',  __FILE__),
        array( 'wp-editor','wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor')
    );

    // Styles.
    wp_enqueue_style(
        's14-collapsible-block-editor-css', // Handle.
        plugins_url('/build/editor.css', __FILE__),
        array( 'wp-edit-blocks' )
    );

} // End function collapsible_block_editor_assets().

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'collapsible_block_editor_assets' );

function collapsible_block_assets(){
	$url = untrailingslashit( plugin_dir_url( __FILE__ ) );
	
	wp_enqueue_style(
        's14-collapsible-block-frontend-css', // Handle.
        plugins_url('/build/style.css', __FILE__)
    );
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'collapsible_block_assets' );