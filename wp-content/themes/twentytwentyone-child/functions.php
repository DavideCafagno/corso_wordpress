<?php error_log("Start function PHP");
add_theme_support('post-thumbnails');
register_post_type('pippo',
    array(
        'labels' => array(
            'name' => __('pippi', 'textdomain'),
            'singular_name' => __('pippo', 'textdomain'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'excerpt'), // metabox
        // "rewrite"  => [ "slug" => "pippo", "with_front" => true ],
        // "capability_type"       => [ "pippo", "pippi" ],

    )
);
function registraPost()
{
    register_post_type('complete-post',
        array(
            'labels' => array(
                'name' => __('Complete posts', 'textdomain'),
                'singular_name' => __('complete-post-singolo', 'textdomain'),
            ),
            'public' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'author', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats', 'page-attributes'),
            'taxonomies' => array('post_tag', 'categoria_custom'),
            'show_ui' => true,
            // 'capability_type' => array('c_post', 'c_posts'),
            'map_meta_cap' => true,
            'capabilities' => array(
                'edit_post' => 'edit_c_post',
                'edit_posts' => 'edit_c_posts',
                'edit_others_posts' => 'edit_other_c_posts',
                'publish_posts' => 'publish_c_posts',
                'read_post' => 'read_c_post',
                'read_private_posts' => 'read_private_c_posts',
                'delete_post' => 'delete_c_post'
            ),
//            // as pointed out by iEmanuele, adding map_meta_cap will map the meta correctly
//
        )

    );

    register_post_type("notizia",
        array(
            'labels' => array(
                'name' => __("Notizie", 'textdomain'),
                'singular_name' => __("Notizia", 'textdomain'),
            ),
            'public' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'author', 'excerpt', 'comments', 'revisions'),
            'taxonomies' => array('post_tag', 'category', 'categoria_custom'),
            'show_ui' => true,
        )

    );
}

add_action('init', 'registraPost');
//add_post_type_support( 'complete-post', 'comments' );

//register_taxonomy_for_object_type('post_tag', 'complete-post');

function registraTaxonomy()
{
    $labels = [
        'name' => _x('Categoria2', 'taxonomy general name'),
        'singular_name' => _x('Categoria2', 'taxonomy singular name'),
        'search_items' => __('Search Categoria2'),
        'all_items' => __('All Categoria2'),
        'parent_item' => __('Parent Categoria2'),
        'parent_item_colon' => __('Parent Categoria2:'),
        'edit_item' => __('Edit Categoria2'),
        'update_item' => __('Update Categoria2'),
        'add_new_item' => __('Add New Categoria2'),
        'new_item_name' => __('New Categoria2 Name'),
        'menu_name' => __('Categoria2'),
    ];
    register_taxonomy('categoria_custom',
        array(
            'complete-post',
            'post',
            'pippo'

        ), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'categoria_custom', 'with_front' => false),
        ));
}

add_action('init', 'registraTaxonomy');

/*function preSalvataggio($postID)
{
    //$postObj = get_post($postID);
    //error_log(get_the_title($postID));
}

add_action('save_post', 'preSalvataggio');*/

function add_styleandscript(): void
{
    wp_enqueue_style('custom_style', get_stylesheet_directory_uri() . '/css/custom_style.css');

    wp_register_script('custom_script', get_stylesheet_directory_uri() . '/js/custom_script.js', array('jquery'));
    wp_localize_script('custom_script', 'oggettoAjax', array('proprietaUrl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('custom_script');


    wp_register_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', array(), '0.1', true);
    wp_enqueue_script('bootstrap_js');
    wp_register_style('bootstrap_style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap_style');


//    wp_register_style('glidejs', "https://unpkg.com/browse/@glidejs/glide@3.3.0/dist/css/glide.core.min.css");
//    wp_enqueue_style("glidejs");wp_register_style('glidejs', "https://unpkg.com/browse/@glidejs/glide@3.3.0/dist/css/glide.core.min.css");
//    wp_enqueue_style("glidejs");

    // wp_register_script("liker_script", plugin_dir_url(__FILE__) . 'liker_script.js', array('jquery'));

    // localize the script to your domain name, so that you can reference the url to admin-ajax.php file easily


    // enqueue jQuery library and the script you registered above
    //wp_enqueue_script('jquery');
    //wp_enqueue_script('liker_script');
}

add_action('wp_enqueue_scripts', 'add_styleandscript');

function create_title_printer($atts)
{
    //shortcode_atts(array('id' => ''), $atts);
    $res = "";
    $atts = (array)$atts;
    $myIds = $atts['id'];
    $arrayIds = explode(",", $myIds);
    foreach ($arrayIds as $element) {
        $element = trim($element);
        $post = get_post($element);
        if ($post != null) {
            $res .= $post->ID . ": " . $post->post_title . "<br>";
        }

    }

    return $res;
}

add_shortcode('title-printer', 'create_title_printer');


add_action('wp_ajax_retrive_permalink', 'retrive_permalink');
add_action('wp_ajax_nopriv_retrive_permalink', 'retrive_permalink_login');
function retrive_permalink()
{

    // nonce check for an extra layer of security, the function will exit if it fails
    if (!wp_verify_nonce($_REQUEST['nonce_ajax'], "retrive_permalink")) {
        exit("Non loggato!");
    }//numero post di tipo  custom post;
    $postType = trim($_REQUEST['postType_ajax']);

    $response = [];
    if (post_type_exists($postType)) {
        $response['type'] = 'success';
        $response['messaggio'] = $postType . ' n: ' . wp_count_posts($postType)->publish;
    } else {
        $response['type'] = 'error';
        $response['messaggio'] = 'Errore nella compilazione dei dati';
    }

    /*$permalink = get_post_permalink($_REQUEST['post_id_ajax']);



    if (!$permalink) {
        $response['type'] = 'error';
        $response['messaggio'] = 'Link non Trovato';
    } else {
        $response['type'] = 'success';
        $response['messaggio'] = $permalink;
    }*/
// Check if action was fired via Ajax call. If yes, JS code will be triggered, else the user is redirected to the post page
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($response);
        echo $result;
    } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        // header("Location: " . 'www.google.com');
    }


    //echo json_encode($response);
    die();
}

function retrive_permalink_login()
{
    return "Effettua prima il login";
    die();
}

add_filter('template_include', 'at_force_template', 15, 2);
function at_force_template($template)
{
    $isArchive = 'page.php' === basename($template);
    $isSingle = 'single.php' === basename($template);
    error_log($template);
    return $template;
}

/*function remove_page_from_query_string($wp_query)
{
    // $wp_query['paged'] = null;
    return $wp_query;
}*/

//add_filter('request', 'remove_page_from_query_string');


function returnPaged($url)
{
    $basePagingUrlLenght = strlen($url . '/page/');
    $pageUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $pageUrlLenght = strlen($pageUrl);
    $pagePath = '';
    if ($basePagingUrlLenght < $pageUrlLenght) {
        $pagePath = substr($pageUrl, $basePagingUrlLenght);
        $pagePath = substr($pagePath, 0, strlen($pagePath) - 1);
    }
    $paged = 1;
    if ($pagePath != "") {
        $paged = $pagePath;
    }

    echo "  BasePagingURL : " . $url . '/page/' . " (lun: " . $basePagingUrlLenght . ") || PageURL : " . $pageUrl . " (lun: " . $pageUrlLenght . ")<br>";
    echo "N PAG: " . $paged;
    return $paged;

}

function target_main_category_query_with_conditional_tags($query)
{
    global $wp_query;
    global $_GET;
    $variabile = $wp_query;
    error_log(json_encode($_GET));
    //controllare se siamo in home; esistenza post-type,verificare tipo post-ytpe e controllare contenuto
    //se troviamo 'post' aggiungiamo settings(type and post per page);
    if (is_home()) {
        if (!array_key_exists('post_type', $query->query_vars)) {
            // if(!is_array($query->query_vars['post_type'])){
            //    if("post" == $query->query_vars['post_type']){
            $query->set('posts_per_page', 4);
            $query->set('post_type', array('complete-post', 'post', 'pippo'));
            //     }else{
            //         $query->set('posts_per_page', 1);
            //    }
            //}else{
            //     $query->set('posts_per_page', 2);
            //}
        } else {
            $query->set('posts_per_page', 3);
        }

    } else {
        //$query->set('posts_per_page', 5);
    }


}

add_action('pre_get_posts', 'target_main_category_query_with_conditional_tags');
/*function getPostProva($post, $query)
{
    $abc = $query;
    //error_log($query->query_vars['post_type']);
    return $post;
}

add_action('posts_results', 'getPostProva', 10, 2);*/

add_action('init', 'addAdminCapability');
function addAdminCapability()
{
    $customCap = [
        'edit_c_post',
        'edit_c_posts',
        'edit_other_c_posts',
        'publish_c_posts',
        'read_c_post',
        'read_private_c_posts',
        'delete_c_post'
    ];
    $role = get_role('administrator');
    $role->add_cap('show_complete_post');

//foreach (array_keys($role -> capabilities) as $c){
//    $role->remove_cap($c);
//

//foreach ($customCap as $c){
//    $role->remove_cap($c);
//}

    $role->add_cap('edit_c_post');
    $role->add_cap('edit_c_posts');
    $role->add_cap('edit_other_c_posts');
    $role->add_cap('publish_c_posts');
    $role->add_cap('read_c_post');
    $role->add_cap('read_private_c_posts');
    $role->add_cap('delete_c_post');


// ADMIN CAPABILITIES
//    $role->add_cap('activate_plugins');
//    $role->add_cap('delete_others_pages');
//    $role->add_cap('delete_others_posts');
//    $role->add_cap('delete_pages');
//    $role->add_cap('delete_posts');
//    $role->add_cap('delete_private_pages');
//    $role->add_cap('delete_private_posts');
//    $role->add_cap('delete_published_pages');
//    $role->add_cap('delete_published_posts');
//    $role->add_cap('edit_dashboard');
//    $role->add_cap('edit_others_pages');
//    $role->add_cap('edit_others_posts');
//    $role->add_cap('edit_pages');
//    $role->add_cap('edit_posts');
//    $role->add_cap('edit_private_pages');
//    $role->add_cap('edit_private_posts');
//    $role->add_cap('edit_published_pages');
//    $role->add_cap('edit_published_posts');
//    $role->add_cap('edit_theme_options');
//    $role->add_cap('export');
//    $role->add_cap('import');
//    $role->add_cap('list_users');
//    $role->add_cap('manage_categories');
//    $role->add_cap('manage_links');
//    $role->add_cap('manage_options');
//    $role->add_cap('moderate_comments');
//    $role->add_cap('promote_users');
//    $role->add_cap('publish_pages');
//    $role->add_cap('publish_posts');
//    $role->add_cap('read_private_pages');
//    $role->add_cap('read_private_posts');
//    $role->add_cap('read');
//    $role->add_cap('create Patterns');
//    $role->add_cap('edit Patterns');
//    $role->add_cap('read Patterns');
//    $role->add_cap('delete Patterns');
//    $role->add_cap('remove_users');
//    $role->add_cap('switch_themes');
//    $role->add_cap('upload_files');
//    $role->add_cap('customize');
//    $role->add_cap('delete_site');
//    $role->add_cap('update_core');
//    $role->add_cap('update_plugins');
//    $role->add_cap('update_themes');
//    $role->add_cap('install_plugins');
//    $role->add_cap('install_themes');
//    $role->add_cap('delete_themes');
//    $role->add_cap('delete_plugins');
//    $role->add_cap('edit_plugins');
//    $role->add_cap('edit_themes');
//    $role->add_cap('edit_files');
//    $role->add_cap('edit_users');
//    $role->add_cap('add_users');
//    $role->add_cap('create_users');
//    $role->add_cap('delete_users');
//    $role->add_cap('unfiltered_html');


}

add_action('rest_api_init', 'addCustomRestApi');

function addCustomRestApi()
{
    register_rest_route('contacts/v1', '/list/', array(
        'methods' => 'GET',
        'callback' => function () {
            return get_posts(array('post_type' => 'contatto'));
        },
    ));
    register_rest_route('contacts/v1', '/add/', array(
        'methods' => 'POST',
        'callback' => 'addContact',
    ));
    register_rest_route("posts/v1", "/search/", array(
        'methods' => 'POST',
        'callback' => 'searchPost',
    ));
}
function searchPost($data)
{
    $id = $data->get_params()['id'];
    $type = $data->get_params()['type'];
    $username = $data->get_params()['username'];
    $password = $data->get_params()['password'];

    if (wp_authenticate_username_password(null, $username, $password) instanceof WP_User) {
        if (!empty($id) && $id > 0) {
            $post_found = get_post($id);
            if ($post_found != null) {
                if (!empty($type)) {
                    if ($post_found->post_type == $type) {
                        return new WP_REST_Response($post_found, 200, array(
                            'message' => "Post con id $id di tipo '$type' trovato!",
                        ));
                    } else {
                        $msg = "Post con id '$id' esiste ma non é di tipo : '$type'";
                        $msg2 = str_replace('é', mb_convert_encoding('é', "ISO-8859-1"), $msg);
                        return new WP_REST_Response($msg, 404, array(
                            'message' => $msg2,
                        ));
                    }

                } else {
                    return new WP_REST_Response($post_found, 302, array(
                        'message' => "Post con id '$id' trovato",
                    ));
                }
            } else {
                $msg = "Post con id '$id' non trovato, verificarne formato e presenza";
                return new WP_REST_Response($msg, 404, array(
                    'message' => $msg,
                ));
            }

        } else {
            if (!empty($id) || $id == 0) $msg = "Errore, specificare un ID maggiore di 0";
            else $msg = "Errore, specificare almeno un ID";

            return new WP_REST_Response($msg, 404, array(
                'message' => $msg,
            ));
        }
    } else {
        if (get_user_by('login', $username) != false) {
            $msg = Ucwords($username) . ", hai sbagliato la password!";
            return new WP_REST_Response($msg, 401, array(
                'message' => $msg,
            ));
        } else {
            $msg = "Username '$username' non presente!";
            return new WP_REST_Response($msg, 401, array(
                'message' => $msg,
            ));
        }

    }

}

function addContact($data)
{
    $postToAdd = $data->get_params();
    $postToAdd['post_status'] = 'publish';
    $id = wp_insert_post($postToAdd);
    foreach (array_keys($postToAdd['post_meta']) as $metakey) {
        add_post_meta($id, $metakey, $postToAdd['post_meta'][$metakey]);
    }
    $res = [];
    if ($id > 0) {
        return new WP_REST_Response(
            array(
                'status' => 200,
                'response' => "Avvenuto con successo",

            )
        );
    } else {
        return new WP_Error(
            array(
                'status' => 500,
                'response' => "Errore nell 'inserimento",
            ));
    }
}

