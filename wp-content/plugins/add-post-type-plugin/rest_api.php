<?php
add_action('rest_api_init', 'register_api');
function register_api()

{
    include "template/PostType.php";
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


function register_custom($postType): array
{
    $res = array('status' => 500, 'message' => __('Error.', 'add-post-type-plugin'));
    if (!check_post_type_existing($postType->post_slug)) {
        if (insert_post_type($postType)) {
            if (function_exists('logger_success')) logger_success($postType, "Custom Post-Type '$postType->post_slug' created!");
            $res['status'] = 200;
            $res['message'] = __('Post-Type created successfully!', 'add-post-type-plugin');
        } else {
            if (function_exists('logger_error')) logger_error($postType, "Custom Post-Type '$postType->post_slug' not created!");
            $res['status'] = 500;
            $res['message'] = __('Error, Post-Type not created.', 'add-post-type-plugin');
        }
    } else {
        if (function_exists('logger_error')) logger_error($postType, "Custom Post-Type '$postType->post_slug' already exists.");
        $res['status'] = 500;
        $res['message'] = __('Error, Post-Type already exists.', 'add-post-type-plugin');
    }
    return $res;
}

function insert_post_type($postType): bool
{
    $args = [
        'post_name' => $postType->post_name,
        'post_slug' => $postType->post_slug,
        'post_singular_name' => $postType->post_singular_name,
        'post_content' => in_array('editor', $postType->supports),
        'post_excerpt' => in_array('excerpt', $postType->supports),
        'post_thumb' => in_array('thumbnail', $postType->supports),
        'post_comments' => in_array('comments', $postType->supports),
        'post_custom_fields' => in_array('custom-fields', $postType->supports),
        'post_taxonomies' => (!empty($postType->post_taxonomies)) ? implode(',', $postType->post_taxonomies) : "",
        'post_enabled' => true
    ];

    global $wpdb;
    return ($wpdb->insert(ADD_POST_TYPE_PLUGIN_TABLE_NAME, $args) != false) ? true : false;
}


function add_custom_post($data)
{

    $obj = $data->get_params();
    $post_slug = $obj['post_slug'];
    if (function_exists('logger_info')) logger_info( "Trying to register Post-Type: '$post_slug'");
    $post_name = $obj['post_name'];
    $post_singular_name = $obj['post_singular_name'];
    $post_taxonomies = $obj['post_taxonomies'];

    $supports = array('title', 'author');

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
    $postType = new PostType($post_name, $post_slug, $post_singular_name, $supports, $post_taxonomies);

    $result = register_custom($postType);

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
    if (function_exists('logger_info')) logger_info( "Trying to Delete a Post-Type with Slug: '$post_slug'");
    $association = $data->get_params()['association'] == "true" ? true : false;
    global $wpdb;
    if ($wpdb->delete(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_slug' => $post_slug)) != false) {
        if ($association) {
            if (function_exists('logger_info')) logger_info( "Trying to delete all posts of type '$post_slug'");
            $res = $wpdb->delete('wp_posts', array('post_type' => $post_slug));
            switch ($res) {
                case 0:
                    if (function_exists('logger_success')) logger_success("Post-Type successful deleted but no posts to delete.");
                    return new WP_REST_Response(array(
                        'status' => 200,
                        'message' => __('Post-Type successful deleted but no posts to delete.', 'add-post-type-plugin')
                    ));
                case false:
                    if (function_exists('logger_error')) logger_error($wpdb->last_query, "Post-Type successful deleted but error deleting posts, Query SQL");
                    return new WP_REST_Response(array(
                        'status' => 200,
                        'message' => __('Post-Type successful deleted! Error deleting posts.', 'add-post-type-plugin')
                    ));
                default:
                    if (function_exists('logger_success')) logger_success("Post-Type successful deleted! .Posts deleted: $res.");
                    return new WP_REST_Response(array(
                        'status' => 200,
                        'message' => sprintf(__('Success! Posts deleted: %x.', 'add-post-type-plugin'), $res)
                    ));
            }
        } else {
            if (function_exists('logger_success')) logger_success("Post-Type successful deleted!");
            return new WP_REST_Response(array(
                'status' => 200,
                'message' => __('Elimination successful!', 'add-post-type-plugin')
            ));
        }

    } else {
        if (function_exists('logger_error')) logger_error($wpdb->last_query, "Post-Type successful deleted but error deleting posts, Query SQL");
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => __('Error, elimination unsuccessful!', 'add-post-type-plugin')
        ));
    }
}

function disable_custom_post($data)
{
    $post_slug = $data->get_params()['post_type'];
    if (function_exists('logger_info')) logger_info( "Trying to Disable a Post-Type : '$post_slug'");
    global $wpdb;
$res = $wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_enabled' => false), array('post_slug' => $post_slug));
    if ( $res!== false) {
        $message ="Disabling '%s' successful!";
        if($res === 0){
            $message = "Type '%s' already disabled!";
            if (function_exists('logger_warning')) logger_warning("Type '$post_slug' already disabled!");
        }else{
            if (function_exists('logger_success')) logger_success("Disabling '$post_slug' successful!");
        }
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => sprintf(__($message, 'add-post-type-plugin'), $post_slug)
        ));
    } else {
        if (function_exists('logger_error')) logger_error($wpdb->last_query, "Error, disabling '$post_slug' fail. Query SQL");
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => __('Error, disabling fail.', 'add-post-type-plugin')
        ));
    }

}

function enable_custom_post($data)
{
    $post_slug = $data->get_params()['post_type'];
    if (function_exists('logger_info')) logger_info( "Trying to enable a Post-Type : '$post_slug'");
    global $wpdb;
    if ($wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_enabled' => true), array('post_slug' => $post_slug)) != false) {
        if (function_exists('logger_success')) logger_success("Enabling Post-Type '$post_slug' successful!");
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => __('Activation successful!', 'add-post-type-plugin')
        ));
    } else {
        if (function_exists('logger_error')) logger_error($wpdb->last_query, "Error, enabling Post-Type '$post_slug' fail. Query SQL");
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => __('Error, activation fail.', 'add-post-type-plugin')
        ));
    }
}

function get_custom_post($data)
{

    $slug = $data->get_params()['post_slug'];
    if (function_exists('logger_info')) logger_info( "Trying to get a Post-Type with slug: '$slug'");
    global $wpdb;

    $post = $wpdb->get_results("SELECT * FROM " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_slug = '" . $slug . "'");
    if ($post != null || !empty($post)) {
        if (function_exists('logger_success')) logger_success($post, "Get the Post-type 'slug' -> ");
        return new WP_REST_Response(array(
            'status' => 200,
            'post' => $post
        ));
    } else {
        if (function_exists('do_log')) do_log("NOT_FOUND", "The Post-type with slug: '$slug', not found.");
        return new WP_REST_Response(array(
            'status' => 404,
            'message' => __('Error, post not found.', 'add-post-type-plugin')
        ));
    }
}


function update_custom_post($data)
{
    global $wpdb;
    $params = $data->get_params();
    $association = $params['association'] == "true" ? true : false;
    $old_slug = $params['old_slug'];

    $oldP = $wpdb->get_results("SELECT * FROM " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_slug = '" . $old_slug . "'");
    if (function_exists('logger_info')) logger_info( $oldP[0], "Trying to modify old Post-Type with slug: '$old_slug', POST-TYPE Before Modification");
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
    $postType = new PostType(
        $args['post_name'],
        $args['post_slug'],
        $args['post_singular_name'],
        array(
            'post_content' => $args['post_content'],
            'post_excerpt' => $args['post_excerpt'],
            'post_thumb' => $args['post_thumb'],
            'post_comments' => $args['post_comments'],
            'post_custom_fields' => $args['post_custom_fields']
        ),
        $args['post_taxonomies']
    );
    $post_type_updating = $wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, $args, array('post_slug' => $old_slug));

    switch ($post_type_updating) {
        case 0:
            if (function_exists('logger_error')) logger_error($wpdb->last_query, "Error, no changes to make. Rows updated: 0. SQL");
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => __('Error, no changes to make.', 'add-post-type-plugin')
            ));
        case false:
            if (function_exists('logger_error')) logger_error($wpdb->last_query, "Post-Type '$postType->post_slug' changes, fail.  SQL");
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => __('Error, changes not made.', 'add-post-type-plugin')
            ));
        default:
            if ($association) {
                if (function_exists('logger_info')) logger_info( "Trying to modify posts of type '$old_slug' into type: '$postType->post_slug'");
                $post_updating = $wpdb->update('wp_posts', array('post_type' => $params['post_slug']), array('post_type' => $old_slug));
                switch ($post_updating) {
                    case 0:
                        if (function_exists('logger_success')) logger_success($postType, "No posts to modify. New Post-Type '$postType->post_slug'");
                        return new WP_REST_Response(array(
                            'status' => 200,
                            'message' => __('Post-type updated but no posts to change.', 'add-post-type-plugin')
                        ));
                    case false:
                        if (function_exists('logger_success')) logger_success(array($postType, $wpdb->last_query), "Post-type '$postType->post_slug' updated but post changes not made. New Post-Type '$postType->post_slug' & Query");
                        return new WP_REST_Response(array(
                            'status' => 200,
                            'message' => __('Post-type updated but post changes not made.', 'add-post-type-plugin')
                        ));
                    default:
                        if (function_exists('logger_success')) logger_success($postType, "Post-type '$postType->post_slug' updated and $post_updating posts are changed from type '$old_slug' into type '$postType->post_slug'.New Post-Type '$postType->post_slug'");
                        return new WP_REST_Response(array(
                            'status' => 200,
                            //'message' => __('Changes made. Posts updated: ', 'add-post-type-plugin') . $post_updating
                            'message' => sprintf(__('Changes made. Posts updated: %x.', 'add-post-type-plugin'), $post_updating)
                        ));
                }
            } else {
                if (function_exists('logger_success')) logger_success($postType, "Post-type '$postType->post_slug' updated! From '$old_slug' into '$postType->post_slug'");
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