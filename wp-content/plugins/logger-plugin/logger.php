<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       Logger
 * Plugin URI:        https://www.wordpress.com
 * Description: Plugin per LOG di eventi
 * Version: 1.0
 * Author: Davide Cafagno
 * License: GPL Attribution-ShareAlike
 * Text Domain: logger-plugin
 * Domain path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
include "template/logger_function.php";
add_action('plugins_loaded', 'logger_plugin_myplugin_load_translation');
function logger_plugin_myplugin_load_translation(){
    load_plugin_textdomain('logger-plugin', false, '/logger-plugin/languages/');
}
add_action('admin_enqueue_scripts', 'load_logger_script');
function load_logger_script(){
    wp_register_script('logger_script',plugins_url()."/logger-plugin/js/logger-script.js");
    wp_set_script_translations( 'logger_script', 'logger-plugin', plugins_url().'/logger-plugin/languages/');
    load_script_textdomain('logger_script','logger-plugin',plugins_url().'/logger-plugin/languages/');
    wp_enqueue_script('logger_script');
}
add_action('rest_api_init', 'load_logger_rest_api');
function load_logger_rest_api(){
    register_rest_route('logger/v1',"files/",array(
        'methods' => 'GET',
        'callback' => 'logger_list_files',
        'permission_callback' => '__return_true'
    ));
    register_rest_route('logger/v1',"content/",array(
        'methods' => 'GET',
        'callback' => 'logger_file_content',
        'permission_callback' => '__return_true'
    ));
}
add_action('admin_menu', 'logger_plugin_menu_page');
function logger_plugin_menu_page(){
    add_menu_page('LOGGER','LOGGER','manage_options', 'logger_plugin', 'my_logs_view', 'dashicons-media-document');
    add_submenu_page('logger_plugin','Info','Info','manage_options','logger-info','my_logger_info');
}

function my_logger_info(){
    include "template/logger_info.php";
}
function my_logs_view(){
    include "template/list.php";
}