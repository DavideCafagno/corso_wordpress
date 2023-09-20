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
}

add_action('rest_api_init', 'register_api');

function register_custom($post_slug, $post_name, $post_singular_name, $supports): array
{
    $post_slug = str_replace(" ",'-',$post_slug);
    $res = array('ok' => false, 'err_msg' => 'Errrore!');
    if (!file_exists('wp-content/plugins/custom_plugin/post_types/' . $post_slug . '.php')) {
        $file = fopen('wp-content/plugins/custom_plugin/post_types/' . $post_slug . '.php', 'w');
        if (false != $file) {
            fwrite($file, '<?php 
                add_action("init", "register_custom_' . check_fun_name($post_slug) . '");
                    function register_custom_' . check_fun_name($post_slug) . '(){
                        register_post_type("' . $post_slug . '",
                            array(
                                "labels" => array(
                                    "name" => __("' . $post_name . '", "textdomain"),
                                    "singular_name" => __("' . $post_singular_name . '", "textdomain"),
                                ),
                                "public" => true,
                                "has_archive" => true,
                                "hierarchical" => false,
                                "supports" => ' . arraytoString($supports) . ',
                                "taxonomies" => array("post_tag", "category", "categoria_custom"),
                                "show_ui" => true,
                            )
                    
                        );
                    }
                ?>');
            fclose($file);

            $res['ok'] = true;
            $res['err_msg'] = "";
        } else {
            $res['ok'] = false;
            $res['err_msg'] = "Errore, Custom_Post non creato";
        }

    } else {
        $res['ok'] = false;
        $res['err_msg'] = "Errore, File già esistente";
    }
    return $res;
}

function remove_custom_post($data)
{
    $post_type = $data->get_params()['post_type'];
    $path="";
    if(file_exists('wp-content/plugins/custom_plugin/post_types/' . $post_type . '.php')){
        $path = 'wp-content/plugins/custom_plugin/post_types/' . $post_type . '.php';
    }elseif (file_exists('wp-content/plugins/custom_plugin/disabled/' . $post_type . '.php')){
        $path = 'wp-content/plugins/custom_plugin/disabled/' . $post_type . '.php';
    }

    if($path != ""){
        if (unlink($path)) {
            return new WP_REST_Response(array(
                'status' => 200,
                'message' => "Eliminazione andata a buon fine"
            ));
        } else {
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => "Eliminazione non andata a buon fine"
            ));
        }
    }else{
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => "Errore, file non trovato"
        ));
    }

}

function add_custom_post($data)
{
    if(!file_exists('wp-content/plugins/custom_plugin/post_types')){
        mkdir('wp-content/plugins/custom_plugin/post_types');
    }
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

    $result = register_custom($post_slug, $post_name, $post_singular_name, $supports);
    if ($result['ok']) {
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => "Registrazione andata a buon fine"
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => $result['err_msg']
        ));
    }
}

function disable_custom_post($data)
{
    if(!file_exists('wp-content/plugins/custom_plugin/disabled')){
        mkdir('wp-content/plugins/custom_plugin/disabled');
    }
    $post_type = $data->get_params()['post_type'];
    $path = 'wp-content/plugins/custom_plugin/post_types/' . $post_type . '.php';
    $disable_path = 'wp-content/plugins/custom_plugin/disabled/' . $post_type . '.php';
    if (file_exists($path)) {
        if (rename($path, $disable_path)) {
            return new WP_REST_Response(array(
                'status' => 200,
                'message' => "Post disabilitato con successo"
            ));
        } else {
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => "Errore, procedura annullata"
            ));
        }

    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => "Errore, File non trovato!"
        ));
    }

}

function enable_custom_post($data)
{
    $post_type = $data->get_params()['post_type'];
    $path = 'wp-content/plugins/custom_plugin/disabled/' . $post_type . '.php';
    $disable_path = 'wp-content/plugins/custom_plugin/post_types/' . $post_type . '.php';
    if (file_exists($path)) {
        if (rename($path, $disable_path)) {
            return new WP_REST_Response(array(
                'status' => 200,
                'message' => "Post riabilitato con successo"
            ));
        } else {
            return new WP_REST_Response(array(
                'status' => 500,
                'message' => "Errore, procedura annullata"
            ));
        }

    } else {
        return new WP_REST_Response(array(
            'status' => 500,
            'message' => "Errore, File non trovato!"
        ));
    }

}

function arraytoString($array)
{
    $res = "array(";
    foreach ($array as $value) {
        $res .= '"' . $value . '",';
    }
    $res .= ')';
    return $res;
}

function check_fun_name($post_slug)
{
    return preg_replace("{[à-ù' 0-9 \"!£$%&/,;.()=?^ÈÙ*|+Ú\-]}", "_", $post_slug);
}