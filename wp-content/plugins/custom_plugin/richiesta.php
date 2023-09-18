<?php
function register_api()
{
    register_rest_route("plug/v1", "/add-custom-post-type/", array(
        'methods' => 'POST',
        'callback' => 'add_custom_post',
    ));
    register_rest_route("plug/v1", "/remove-custom-post-type/", array(
        'methods' => 'POST',
        'callback' => 'remove_custom_post',
    ));
}

add_action('rest_api_init', 'register_api');


function remove_custom_post($data)
{
    $post_type = $data->get_params()['post_type'];
    if (unregister_post_type($post_type) != false) {
        return new WP_REST_Response(null, 200, array(
            'content_type' => 'application/json',
        ));
    } else {
        return new WP_REST_Response(null, 500, array(
            'content_type' => 'application/json',
        ));
    }
}

function add_custom_post($data)
{
    $supports = array('title', 'author');
    $obj = $data->get_params();
    $post_name = $obj['post_name'];
    $post_slug = $obj['post_slug'];
    $post_singular_name = $obj['post_singular_name'];
    $post_content = $obj['post_content'];
    if ($post_content == true) {
        $supports [] = 'editor';
    }
    $post_excerpt = $obj['post_excerpt'];
    if ($post_excerpt == true) {
        $supports [] = 'excerpt';
    }
    $post_thumb = $obj['post_thumb'];
    if ($post_thumb == true) {
        $supports [] = 'thumbnail';
    }
    $post_comments = $obj['post_comments'];
    if ($post_comments == true) {
        $supports [] = 'comments';
    }
    $post_custom_fields = $obj['post_custom_fields'];
    if ($post_custom_fields == true) {
        $supports [] = 'custom-fields';
    }


    $post_T = register_post_type($post_slug,
            array(
                'labels' => array(
                    'name' => __($post_name, 'textdomain'),
                    'singular_name' => __($post_singular_name, 'textdomain'),
                ),
                'public' => true,
                'has_archive' => true,
                'hierarchical' => false,
                'supports' => $supports,
                'taxonomies' => array('post_tag', 'category', 'categoria_custom'),
                'show_ui' => true,
            )

        );

    if( $post_T instanceof WP_Post_Type) {
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => "Registrazione andata a buon fine"
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => "Registrazione non andata a buon fine"
        ));
    }
}