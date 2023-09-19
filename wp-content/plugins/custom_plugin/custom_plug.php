<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       ADD Custom Post-Type
 * Plugin URI:        https://www.wordpress.com
 * Description: Plugin per le notizie
 * Version: 1.0
 * Author: Davide Cafagno
 * License: GPL Attribution-ShareAlike
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
    $file = @opendir('../wp-content/plugins/custom_plugin/post_types/');
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
    wp_register_script('custom_plugin.js', plugins_url('custom_plugin/js/custom_plugin.js'));//, array('jquery'));
//    wp_localize_script('custom_script', 'oggettoAjax', array('proprietaUrl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('custom_plugin.js');

}

add_action('admin_menu', 'register_my_custom_menu_page');

function register_my_custom_menu_page()
{
    add_menu_page('my plugin', 'Custom plugin', 'manage_options', 'add_custom_post_plugin', 'my_add_custom_post', plugins_url('myplugin/images/icon.png'), 66);
}

add_action('admin_menu', 'register_my_custom_sub_menu_page');

function register_my_custom_sub_menu_page()
{
    add_submenu_page('add_custom_post_plugin', "my plugin", "Add Custom Post", 'manage_options', 'add-post_type', 'my_add_custom_post');
    add_submenu_page('add_custom_post_plugin', "my plugin", "Remove Post-Type", 'manage_options', 'remove-post_type', 'my_remove_custom_post');
}

function my_remove_custom_post()
{
    ?>

    <h1>SSCEGLI IL CUSTOM POST TYPE DA RIMUOVERE</h1>
    <table>
        <tr class="row">
            <td class="col col-6">SELEZIONA POST DA ELIMINARE</td>
            <td class="col col-6"><select id="post_selected">
                    <?php
                    foreach (custom_post_list() as $pt):?>
                        <option value="<?php echo $pt; ?>"><?php echo $pt; ?></option>
                    <?php endforeach; ?>
                </select></td>
        </tr>
        <tr class="row">
            <td class="col col-12">
                <button class="button" onclick="elimina_post()">ELIMINA</button>
            </td>
        </tr>
    </table>
    <?php
}


function my_add_custom_post()
{
    ?>

    <h1>AGGIUNGI UN CUSTOM POST TYPE</h1>
    <table>
        <tr class="row">
            <td class="col col-6">NOME CUSTOM POST</td>
            <td class="col col-6"><input id="post_name" placeholder="Inserire nome campo da registrare"></td>
        </tr>
        <tr class="row">
            <td class="col col-6">SLUG POST</td>
            <td class="col col-6"><input id="post_slug" placeholder="Inserire slug da registrare"></td>
        </tr>
        <tr class="row">
            <td class="col col-6">NOME SINGOLARE</td>
            <td class="col col-6"><input id="post_singular_name" placeholder="Inserire nome campo da registrare"></td>
        </tr>
        <tr class="row">
            <td class="col col-6">CONTENTUTO</td>
            <td class="col col-6"><input id="post_content" type="checkbox"></td>
        </tr>
        <tr class="row">
            <td class="col col-6">RIASSUNTO</td>
            <td class="col col-6"><input id="post_excerpt" type="checkbox"></td>
        </tr>
        <tr class="row">
            <td class="col col-6">IMMAAGINE</td>
            <td class="col col-6"><input type="checkbox" id="post_thumb"></td>
        </tr>
        <tr class="row">
            <td class="col col-6">COMMENTI</td>
            <td class="col col-6"><input type="checkbox" id="post_comments"></td>
        </tr>
        <tr class="row">
            <td class="col col-6">CUSTOM FIELDS</td>
            <td class="col col-6"><input type="checkbox" id="post_custom_fields"></td>
        </tr>
        <tr class="row">
            <td class="col col-12">
                <button class="button" onclick="invia_dati()">AGGIUNGI</button>
            </td>
        </tr>

    </table>

    <?php

}

