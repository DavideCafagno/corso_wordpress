<?php
function register_api()
{
    register_rest_route("plug/v1", "/add-custom-post-type/", array(
        'methods' => 'POST',
        'callback' => 'add_custom_post',
        'permission_callback' => '__return_true'
    ));
    register_rest_route("plug/v1", "/remove-custom-post-type/", array(
        'methods' => 'POST',
        'callback' => 'remove_custom_post',
        'permission_callback' => '__return_true'
    ));
    register_rest_route("plug/v1", "/disable-custom-post-type/", array(
        'methods' => 'POST',
        'callback' => 'disable_custom_post',
        'permission_callback' => '__return_true'
    ));
    register_rest_route("plug/v1", "/enable-custom-post-type/", array(
        'methods' => 'POST',
        'callback' => 'enable_custom_post',
        'permission_callback' => '__return_true'
    ));
    register_rest_route("plug/v1", "/get-custom-post-type/", array(
        'methods' => 'GET',
        'callback' => 'get_custom_post',
        'permission_callback' => '__return_true'
    ));
    register_rest_route("plug/v1", "/update-custom-post-type/", array(
        'methods' => 'POST',
        'callback' => 'update_custom_post',
        'permission_callback' => '__return_true'
    ));
}

add_action('rest_api_init', 'register_api');
function register_custom($post_slug, $post_name, $post_singular_name, $supports, $post_taxonomies): array
{
    $res = array('status' => 500, 'message' => 'Errrore!');
    if (!check_post_type_existing($post_slug)) {
        if (insert_post_type($post_slug, $post_name, $post_singular_name, $supports, $post_taxonomies)) {
            $res['status'] = 200;
            $res['message'] = "Post Type creato con successo!";
        } else {
            $res['status'] = 500;
            $res['message'] = "Errore, Post Type non creato";
        }
    } else {
        $res['status'] = 500;
        $res['message'] = "Errore, Post Type già esistente";
    }
    return $res;
}

function insert_post_type($post_slug, $post_name, $post_singular_name, $supports, $post_taxonomies): bool
{
    $args = [
        'post_name' => $post_name,
        'post_slug' => $post_slug,
        'post_singular_name' => $post_singular_name,
        'post_content' => in_array('editor', $supports),
        'post_excerpt' => in_array('excerpt', $supports),
        'post_thumb' => in_array('thumbnail', $supports),
        'post_comments' => in_array('comments', $supports),
        'post_custom_fields' => in_array('custom-fields', $supports),
        'post_taxonomies' => (!empty($post_taxonomies)) ? implode(',', $post_taxonomies) : "",
        'post_enabled' => true
    ];

    global $wpdb;
    return ($wpdb->insert(ADD_POST_TYPE_PLUGIN_TABLE_NAME, $args) != false) ? true : false;
}


function add_custom_post($data)
{
    $supports = array('title', 'author');
    $obj = $data->get_params();
    $post_name = $obj['post_name'];
    $post_slug = $obj['post_slug'];
    $post_singular_name = $obj['post_singular_name'];
    $post_taxonomies = $obj['post_taxonomies'];

    $post_content = $obj['post_content'];
    if ($post_content == "true") {
        $supports [] = 'editor';
    }
    $post_excerpt = $obj['post_excerpt'];
    if ($post_excerpt == "true") {
        $supports [] = 'excerpt';
    }
    $post_thumb = $obj['post_thumb'];
    if ($post_thumb == "true") {
        $supports [] = 'thumbnail';
    }
    $post_comments = $obj['post_comments'];
    if ($post_comments == "true") {
        $supports [] = 'comments';
    }
    $post_custom_fields = $obj['post_custom_fields'];
    if ($post_custom_fields == "true") {
        $supports [] = 'custom-fields';
    }

    $result = register_custom($post_slug, $post_name, $post_singular_name, $supports, $post_taxonomies);

    if ($result['status'] == 200) {
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => $result['message']
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => $result['message']
        ));
    }
}

function remove_custom_post($data)
{
    $post_slug = $data->get_params()['post_type'];
    global $wpdb;
    if ($wpdb->delete(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_slug' => $post_slug)) != false) {
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => "Eliminazione andata a buon fine!"
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => "Eliminazione non andata a buon fine."
        ));
    }
}

function disable_custom_post($data)
{
    $post_slug = $data->get_params()['post_type'];
    global $wpdb;
    if ($wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_enabled' => false), array('post_slug' => $post_slug)) != false) {
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => "Disabilitazione andata a buon fine!"
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => "Errore, Disabilitazione non andata a buon fine."
        ));
    }

}

function enable_custom_post($data)
{
    $post_slug = $data->get_params()['post_type'];
    global $wpdb;
    if ($wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_enabled' => true), array('post_slug' => $post_slug)) != false) {
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => "Attivazione andata a buon fine!"
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => "Attivazione non andata a buon fine."
        ));
    }
}

function get_custom_post($data)
{
    $slug = $data->get_params()['post_slug'];
    global $wpdb;

    $post = $wpdb->get_results("SELECT * FROM " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_slug = '" . $slug . "'");
    if ($post != null || !empty($post)) {
        return new WP_REST_Response(array(
            'status' => 200,
            'post' => $post
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 404,

        ));
    }
}


function update_custom_post($data)
{
    $params = $data->get_params();
    $association = $params['association'] == "true" ? true : false;
    $old_slug = $params['old_slug'];
    global $wpdb;
    $args = [
        'post_name' => $params['post_name'],
        'post_slug' => $params['post_slug'],
        'post_singular_name' => $params['post_singular_name'],
        'post_content' => ($params['post_content'] == 'true') ? true : false,
        'post_excerpt' => ($params['post_excerpt'] == 'true') ? true : false,
        'post_thumb' => ($params['post_thumb'] == 'true') ? true : false,
        'post_comments' => ($params['post_comments'] == 'true') ? true : false,
        'post_custom_fields' => ($params['post_custom_fields'] == 'true') ? true : false,
        'post_taxonomies' => array_key_exists('post_taxonomies', $params) ? implode(',',$params['post_taxonomies']) : "",
    ];
    $post_type_updating = $wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, $args, array('post_slug' => $old_slug));
    switch ($post_type_updating) {
        case 0:
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => 'Nessuna modifica da effettuare.'
            ));
        case false:
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => 'Errore, modifica non avvenuta.'
            ));
        default:
            if ($association) {
                $post_updating = $wpdb->update('wp_posts', array('post_type' => $params['post_slug']), array('post_type' => $old_slug));
                switch ($post_updating) {
                    case 0:
                        return new WP_REST_Response(array(
                            'status' => 200,
                            'message' => 'Modifica avvenuta con successo! Nessun post da modificare!'
                        ));
                    case false:
                        return new WP_REST_Response(array(
                            'status' => 200,
                            'message' => 'Modifica avvenuta con successo! Errore nella modifica dei posts'
                        ));
                    default:
                        return new WP_REST_Response(array(
                            'status' => 200,
                            'message' => 'Modifica avvenuta con successo! Post modificati : ' . $post_updating
                        ));
                }

            } else {
                return new WP_REST_Response(array(
                    'status' => 200,
                    'message' => 'Modifica post_type avvenuta con successo! '
                ));
            }


    }
}

function check_post_type_existing($slug): bool
{
    global $wpdb;
    $res = $wpdb->get_results("SELECT * FROM " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_slug = '" . $slug . "'");
    return ($res == null || empty($res)) ? false : true;
}