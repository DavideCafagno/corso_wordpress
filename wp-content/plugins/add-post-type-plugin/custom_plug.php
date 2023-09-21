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
    global $wpdb;
    define('ADD_POST_TYPE_PLUGIN_TABLE_NAME', $wpdb->prefix . 'add_post_type_plugin');


    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . "(
                    id integer PRIMARY KEY AUTO_INCREMENT,
                    post_name varchar(100) NOT NULL,
                    post_slug varchar(100) NOT NULL UNIQUE,
                    post_singular_name varchar(100) NOT NULL,
                    post_content boolean DEFAULT false NOT NULL,
                    post_excerpt boolean DEFAULT false NOT NULL,
                    post_thumb boolean DEFAULT false NOT NULL,
                    post_comments boolean DEFAULT false NOT NULL,
                    post_custom_fields boolean DEFAULT false NOT NULL,
                    post_enabled boolean DEFAULT true NOT NULL
                ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_action('init', function () {
        foreach (custom_post_list() as $r) {
            $post_name = $r->post_name;
            $post_slug = check_fun_name($r->post_slug);
            $post_singular_name = $r->post_singular_name;
            $support = ['title','author'];
            if($r->post_content == '1') $support[]= "editor" ;
            if($r->post_excerpt == '1') $support[]= "excerpt";
            if($r->post_thumb == '1') $support[]= "thumbnail";
            if($r->post_comments == '1') $support[]= "comments";
            if($r->post_custom_fields == '1') $support[]= "custom-field";
            register_post_type($post_slug,
                array(
                    "labels" => array(
                        "name" => __($post_name, "textdomain"),
                        "singular_name" => __($post_singular_name, "textdomain"),
                    ),
                    "public" => true,
                    "has_archive" => true,
                    "hierarchical" => false,
                    "supports" => $support,
                    "taxonomies" => array("post_tag", "category"),
                    "show_ui" => true,
                )
            );
        }
    });
}

function check_fun_name($post_slug)
{
    $post_slug = str_replace(" ", '-', $post_slug);
    $post_slug = strtolower($post_slug);
    return preg_replace("{[à-ù0-9\"'!£$%&/,;.()=?^ÈÙ*|+Ú]}", "", $post_slug);
}

function custom_post_list()
{
    global $wpdb;
    $records = $wpdb->get_results("SELECT * from " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_enabled = true");
    return $records;
}

function disabled_custom_post_list()
{
    global $wpdb;
    $records = $wpdb->get_results("SELECT * from " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_enabled = false");
    return $records;
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
    add_submenu_page('add_custom_post_plugin', "my plugin", "Remove Post - Type", 'manage_options', 'remove-post_type', 'my_remove_custom_post');
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