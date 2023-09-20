<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       Add Post-Type
 * Plugin URI:        https://www.wordpress.com
 * Description: Plugin per creazione di Post-type
 * Version: 1.0
 * Author: Davide Cafagno
 * License: GPL Attribution-ShareAlike
 *
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function load_custom_post_type()
{
    foreach (custom_post_list() as $post) {
        include 'post_types/' . $post . '.php';
    }
}

function custom_post_list()
{
    $file = @opendir('../wp-content/plugins/add-post-type-plugin/post_types/');
    $res = [];
    if ($file != false) {
        while ($fname = @readdir($file)) {
            if ($fname != '.' && $fname != '..')
                $res[] = substr($fname, 0, (strlen($fname) - 4));
        }
    }
    return $res;
}
function disabled_custom_post_list(){
    $file = @opendir('../wp-content/plugins/add-post-type-plugin/disabled/');
    $res = [];
    if ($file != false) {
        while ($fname = @readdir($file)) {
            if ($fname != '.' && $fname != '..')
                $res[] = substr($fname, 0, (strlen($fname) - 4));
        }
    }
    return $res;
}

include 'rest_api.php';
load_custom_post_type();
add_action('admin_enqueue_scripts', 'add_script');
function add_script()
{
    wp_register_script('add-post-type-plugin.js', plugins_url('add-post-type-plugin/js/custom_plugin.js'));//, array('jquery'));
//    wp_localize_script('custom_script', 'oggettoAjax', array('proprietaUrl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('add-post-type-plugin.js');

}

add_action('admin_menu', 'register_my_custom_menu_page');

function register_my_custom_menu_page()
{
    add_menu_page('my plugin', 'Add Post-Type', 'manage_options', 'add_custom_post_plugin', 'my_add_custom_post', 'dashicons-plus-alt2', 66);
}

add_action('admin_menu', 'register_my_custom_sub_menu_page');

function register_my_custom_sub_menu_page()
{
    add_submenu_page('add_custom_post_plugin', "my plugin", "Add Custom Post", 'manage_options', 'add-post_type', 'my_add_custom_post');
    add_submenu_page('add_custom_post_plugin', "my plugin", "Remove Post-Type", 'manage_options', 'remove-post_type', 'my_remove_custom_post');
    add_submenu_page('add_custom_post_plugin', "my plugin", "Disabled Posts", 'manage_options', 'enable-post_type', 'my_enable_custom_post');
}
function my_add_custom_post()
{
    include 'template/adding.php';
}
function my_remove_custom_post()
{
    include 'template/removing.php';
}
function my_enable_custom_post()
{
    include 'template/enabling.php';
}
