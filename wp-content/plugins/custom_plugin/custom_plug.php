<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Plugin
 * Plugin URI:        https://www.wordpress.com
 * Description: Plugin per le notizie
 * Version: 1.0
 * Author: Autore
 * License: GPL Attribution-ShareAlike
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
include 'richiesta.php';
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
        <script>
            function elimina_post(){
                alert(jQuery('#post_selected').val());


            }
        </script>
    <h1>SSCEGLI IL CUSTOM POST TYPE DA RIMUOVERE</h1>
    <table>
        <tr class="row">
            <td class="col col-6">SELEZIONA POST DA ELIMINARE</td>
            <td class="col col-6"><select id="post_selected">
                    <?php
                    $protected_list= [      "custom_css", "customize_changeset","acf-field-group","acf-post-type","acf-taxonomy", "wp_template_part", "oembed_cache","acf-field", "wp_block", "post", "page", "wp_global_styles","revision", "user_request", "wp_navigation", "wp_template", "attachment","nav_menu_item"];
                    foreach (get_post_types() as $pt):
                        if(!in_array($pt,$protected_list)):?>
                        <option value="<?php echo $pt; ?>"><?php  echo $pt;?></option>
                    <?php endif; endforeach;?>
                </select></td>
        </tr>
        <tr class="row">
            <td class="col col-12">
                <button onclick="elimina_post()">ELIMINA</button>
            </td>
        </tr>
    </table>
    <?php
}

//add_action('wp_enqueue_scripts', 'add_script');
//function add_script(){
//    wp_register_script('custom_script', '/js/custom_plugin.js', array('jquery'));
//    wp_localize_script('custom_script', 'oggettoAjax', array('proprietaUrl' => admin_url('admin-ajax.php')));
//    wp_enqueue_script('custom_script');
//
//}

function my_add_custom_post()
{
    ?>
    <script>
        function invia_dati() {
            //alert("Ciao " + jQuery("#post_name").val());
            let $post_name = jQuery("#post_name").val();
            let $post_slug = jQuery("#post_slug").val();
            let $post_singular_name = jQuery("#post_singular_name").val();
            let $post_content = jQuery("#post_content")['0']['checked'];
            let $post_excerpt = jQuery("#post_excerpt")['0']['checked'];
            let $post_thumb = jQuery("#post_thumb")['0']['checked'];
            let $post_comments = jQuery("#post_comments")['0']['checked'];
            let $post_custom_fields = jQuery("#post_custom_fields")['0']['checked'];
            if ($post_name != "" && $post_slug != "" && $post_singular_name != "") {
                var dato = {};
                dato['post_name'] = $post_name;
                dato['post_slug'] = $post_slug;
                dato['post_singular_name'] = $post_singular_name;
                dato['post_content'] = $post_content;
                dato['post_excerpt'] = $post_excerpt;
                dato['post_thumb'] = $post_thumb;
                dato['post_comments'] = $post_comments;
                dato['post_custom_fields'] = $post_custom_fields;

                jQuery.ajax({
                    url:"http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/add-custom-post-type/",
                    method: "POST",
                    dataType: "json",
                    data: dato,
                    success: function (response) {
                        console.log(response);
                        if (response.status === 200) {
                            alert("Successo!");
                        } else {
                            alert("Errore interno!");
                        }
                    }
                });
            } else {
                alert("Inserire tutti i campi di testo!");

            }


        }
    </script>

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
                <button onclick="invia_dati()">AGGIUNGI</button>
            </td>
        </tr>

    </table>

    <?php

}

function dimmi_tutto()
{
    echo "ECCO QUI";
}