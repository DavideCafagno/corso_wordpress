<?php
include "template/PostType.php";
add_action('rest_api_init', 'register_api');
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

add_action('wp_ajax_add_custom_post', 'add_custom_post');
function add_custom_post($data)
{
    if (wp_verify_nonce($_REQUEST['addPostNonce'])) {
        $obj = $_REQUEST['post_type'];
        $post_slug = $obj['post_slug'];
        if (function_exists('logger_info')) logger_info("Trying to register Post-Type: '$post_slug'");
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
            echo json_encode(new WP_REST_Response($result['message'], 200));
        } else {
            echo json_encode(new WP_REST_Response($result['message'], 500));
        }
    } else {
        echo json_encode(new WP_REST_Response("ERROR, YOU NOT ARE LOGGED IN.", 401));

    }
    die();
}

add_action('wp_ajax_remove_custom_post', 'remove_custom_post');
function remove_custom_post()
{
    if (wp_verify_nonce($_REQUEST['removePostNonce'])) {
        $post_slug = $_REQUEST['post_type'];
        if (function_exists('logger_info')) logger_info("Trying to Delete a Post-Type with Slug: '$post_slug'");
        $association = $_REQUEST['association'] == "true" ? true : false;
        global $wpdb;
        if ($wpdb->delete(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_slug' => $post_slug)) != false) {
            if ($association) {
                if (function_exists('logger_info')) logger_info("Trying to delete all posts of type '$post_slug'");
                $res = $wpdb->delete('wp_posts', array('post_type' => $post_slug));
                switch ($res) {
                    case 0:
                        if (function_exists('logger_success')) logger_success("Post-Type successful deleted but no posts to delete.");
                        echo json_encode(new WP_REST_Response(__('Post-Type successful deleted but no posts to delete.', 'add-post-type-plugin'), 200));
                        break;
                    case false:
                        if (function_exists('logger_error')) logger_error($wpdb->last_query, "Post-Type successful deleted but error deleting posts, Query SQL");
                        echo json_encode(new WP_REST_Response(__('Post-Type successful deleted! Error deleting posts.', 'add-post-type-plugin'), 200));
                        break;
                    default:
                        if (function_exists('logger_success')) logger_success("Post-Type successful deleted! .Posts deleted: $res.");
                        echo json_encode(new WP_REST_Response(sprintf(__('Success! Posts deleted: %x.', 'add-post-type-plugin'), $res), 200));

                }
            } else {
                if (function_exists('logger_success')) logger_success("Post-Type successful deleted!");
                echo json_encode(new WP_REST_Response(__('Elimination successful!', 'add-post-type-plugin'), 200));
            }

        } else {
            if (function_exists('logger_error')) logger_error($wpdb->last_query, "Post-Type successful deleted but error deleting posts, Query SQL");
            echo json_encode(new WP_REST_Response(__('Error, elimination unsuccessful.', 'add-post-type-plugin'), 500));
        }
    } else {
        echo json_encode(new WP_REST_Response("ERROR, YOU NOT ARE LOGGED IN.", 401));
    }
    die();
}

add_action('wp_ajax_disable_custom_post', 'disable_custom_post');
function disable_custom_post()
{
    if (wp_verify_nonce($_REQUEST['disablePostNonce'])) {
        $post_slug = $_REQUEST['post_type'];
        if (function_exists('logger_info')) logger_info("Trying to Disable a Post-Type : '$post_slug'");
        global $wpdb;
        $res = $wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_enabled' => false), array('post_slug' => $post_slug));
        if ($res !== false) {
            $message = "Disabling '%s' successful!";
            if ($res === 0) {
                $message = "Type '%s' already disabled!";
                if (function_exists('logger_warning')) logger_warning("Type '$post_slug' already disabled!");
            } else {
                if (function_exists('logger_success')) logger_success("Disabling '$post_slug' successful!");
            }
            echo json_encode(new WP_REST_Response(sprintf(__($message, 'add-post-type-plugin'), $post_slug), 200));
        } else {
            if (function_exists('logger_error')) logger_error($wpdb->last_query, "Error, disabling '$post_slug' fail. Query SQL");
            echo json_encode(new WP_REST_Response(__('Error, disabling fail.', 'add-post-type-plugin'), 500));
        }
    } else {
        echo json_encode(new WP_REST_Response("ERROR, YOU NOT ARE LOGGED IN.", 401));
    }
    die();
}

add_action('wp_ajax_enable_custom_post', 'enable_custom_post');
function enable_custom_post()
{
    if (wp_verify_nonce($_REQUEST['enablePostNonce'])) {
        $post_slug = $_REQUEST['post_type'];
        if (function_exists('logger_info')) logger_info("Trying to enable a Post-Type : '$post_slug'");
        global $wpdb;
        if ($wpdb->update(ADD_POST_TYPE_PLUGIN_TABLE_NAME, array('post_enabled' => true), array('post_slug' => $post_slug)) != false) {
            if (function_exists('logger_success')) logger_success("Enabling Post-Type '$post_slug' successful!");
            echo json_encode(new WP_REST_Response(__('Activation successful!', 'add-post-type-plugin'), 200));
        } else {
            if (function_exists('logger_error')) logger_error($wpdb->last_query, "Error, enabling Post-Type '$post_slug' fail. Query SQL");
            echo json_encode(new WP_REST_Response(__('Error, activation fail.', 'add-post-type-plugin'), 500));
        }
    } else {
        echo json_encode(new WP_REST_Response("ERROR, YOU NOT ARE LOGGED IN.", 401));
    }
    die();
}

add_action('wp_ajax_get_custom_post', 'get_custom_post');
function get_custom_post()
{
    if (wp_verify_nonce($_REQUEST['getPostNonce'])) {
        $slug = $_REQUEST['post_slug'];
        if (function_exists('logger_info')) logger_info("Trying to get a Post-Type with slug: '$slug'");
        global $wpdb;

        $post = $wpdb->get_results("SELECT * FROM " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_slug = '" . $slug . "'");
        if ($post != null || !empty($post)) {
            if (function_exists('logger_success')) logger_success($post, "Get the Post-type 'slug' -> ");
            echo json_encode(new WP_REST_Response($post, 200));
        } else {
            if (function_exists('do_log')) do_log("NOT_FOUND", "The Post-type with slug: '$slug', not found.");
            echo json_encode(new WP_REST_Response(__('Error, post not found.', 'add-post-type-plugin'), 404));
        }
    } else {
        echo json_encode(new WP_REST_Response("ERROR, YOU NOT ARE LOGGED IN.", 401));
    }
    die();
}

add_action('wp_ajax_update_custom_post', 'update_custom_post');
function update_custom_post()
{
    if (wp_verify_nonce($_REQUEST['updatePostNonce'])) {
        global $wpdb;

        $association = $_REQUEST['association'] == "true" ? true : false;
        $post = $_REQUEST['post_type'];
        $old_slug = $_REQUEST['old_slug'];
        $oldP = $wpdb->get_results("SELECT * FROM " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_slug = '" . $old_slug . "'");
        if (function_exists('logger_info')) logger_info($oldP[0], "Trying to modify old Post-Type with slug: '$old_slug', POST-TYPE Before Modification");
        $args = [
            'post_name' => $post['post_name'],
            'post_slug' => $post['post_slug'],
            'post_singular_name' => $post['post_singular_name'],
            'post_content' => ($post['post_content'] == 'true') ? true : false,
            'post_excerpt' => ($post['post_excerpt'] == 'true') ? true : false,
            'post_thumb' => ($post['post_thumb'] == 'true') ? true : false,
            'post_comments' => ($post['post_comments'] == 'true') ? true : false,
            'post_custom_fields' => ($post['post_custom_fields'] == 'true') ? true : false,
            'post_taxonomies' => array_key_exists('post_taxonomies', $post) ? implode(',', $post['post_taxonomies']) : "",
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
                if (function_exists('logger_warning')) logger_warning($wpdb->last_query, "Error, no changes to make. Rows updated: 0. SQL");
                echo json_encode(new WP_REST_Response(__('Error, no changes to make.', 'add-post-type-plugin'), 500));
                break;
            case false:
                if (function_exists('logger_error')) logger_error($wpdb->last_query, "Post-Type '$postType->post_slug' changes, fail.  SQL");
                echo json_encode(new WP_REST_Response(__('Error, changes not made.', 'add-post-type-plugin'), 500));
                break;
            default:
                if ($association) {
                    if (function_exists('logger_info')) logger_info("Trying to modify posts of type '$old_slug' into type: '$postType->post_slug'");
                    $post_updating = $wpdb->update('wp_posts', array('post_type' => $post['post_slug']), array('post_type' => $old_slug));
                    switch ($post_updating) {
                        case 0:
                            if (function_exists('logger_success')) logger_success($postType, "No posts to modify. New Post-Type '$postType->post_slug'");
                            echo json_encode(new WP_REST_Response(__('Post-type updated but no posts to change.', 'add-post-type-plugin'), 200));
                            break;
                        case false:
                            if (function_exists('logger_success')) logger_success(array($postType, $wpdb->last_query), "Post-type '$postType->post_slug' updated but post changes not made. New Post-Type '$postType->post_slug' & Query");
                            echo json_encode(new WP_REST_Response(__('Post-type updated but post changes not made.', 'add-post-type-plugin'), 200));
                            break;
                        default:
                            if (function_exists('logger_success')) logger_success($postType, "Post-type '$postType->post_slug' updated and $post_updating posts are changed from type '$old_slug' into type '$postType->post_slug'.New Post-Type '$postType->post_slug'");
                            echo json_encode(new WP_REST_Response(sprintf(__('Changes made. Posts updated: %x.', 'add-post-type-plugin'), $post_updating), 200));
                    }
                } else {
                    if (function_exists('logger_success')) logger_success($postType, "Post-type '$postType->post_slug' updated! From '$old_slug' into '$postType->post_slug'");
                    echo json_encode(new WP_REST_Response(__('Post-type changes successful!', 'add-post-type-plugin'), 200));
                }
        }
    } else {
        echo json_encode(new WP_REST_Response("ERROR, YOU NOT ARE LOGGED IN.", 401));
    }
    die();
}

function check_post_type_existing($slug): bool
{
    global $wpdb;
    $res = $wpdb->get_results("SELECT * FROM " . ADD_POST_TYPE_PLUGIN_TABLE_NAME . " WHERE post_slug = '" . $slug . "'");
    return ($res == null || empty($res)) ? false : true;
}