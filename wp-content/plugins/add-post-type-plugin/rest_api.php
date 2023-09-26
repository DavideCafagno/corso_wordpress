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
    $res = array('status' => 500, 'message' => __('Error.', 'add-post-type-plugin'));
    if (!check_post_type_existing($post_slug)) {
        if (insert_post_type($post_slug, $post_name, $post_singular_name, $supports, $post_taxonomies)) {
            $res['status'] = 200;
            $res['message'] = __('Post-Type created successfully!', 'add-post-type-plugin');
        } else {
            $res['status'] = 500;
            $res['message'] = __('Error, Post-Type not created.', 'add-post-type-plugin');
        }
    } else {
        $res['status'] = 500;
        $res['message'] = __('Error, Post-Type already exists.', 'add-post-type-plugin');
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
            'message' => __('Elimination successful!', 'add-post-type-plugin')
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => __('Error, elimination unsuccessful!', 'add-post-type-plugin')
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
            'message' => __('Disabling successful!', 'add-post-type-plugin')
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => __('Error, disabling fail.', 'add-post-type-plugin')
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
            'message' => __('Activation successful!', 'add-post-type-plugin')
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => __('Error, activation fail.', 'add-post-type-plugin')
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
            'message' => __('Error, post not found.', 'add-post-type-plugin')
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
        'post_taxonomies' => array_key_exists('post_taxonomies', $params) ? implode(',', $params['post_taxonomies']) : "",
    ];
    $post_type_updating = $wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, $args, array('post_slug' => $old_slug));
    switch ($post_type_updating) {
        case 0:
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => __('Error, no changes to make.', 'add-post-type-plugin')
            ));
        case false:
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => __('Error, changes not made.', 'add-post-type-plugin')
            ));
        default:
            if ($association) {
                $post_updating = $wpdb->update('wp_posts', array('post_type' => $params['post_slug']), array('post_type' => $old_slug));
                switch ($post_updating) {
                    case 0:
                        return new WP_REST_Response(array(
                            'status' => 200,
                            'message' => __('Error, no posts to change.', 'add-post-type-plugin')
                        ));
                    case false:
                        return new WP_REST_Response(array(
                            'status' => 200,
                            'message' => __('Error, changes not made.', 'add-post-type-plugin')
                        ));
                    default:
                        return new WP_REST_Response(array(
                            'status' => 200,
                            'message' => __('Changes made. Post updated: ', 'add-post-type-plugin') . $post_updating
                        ));
                }

            } else {
                return new WP_REST_Response(array(
                    'status' => 200,
                    'message' => __('Post-type changes successful!', 'add-post-type-plugin')
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