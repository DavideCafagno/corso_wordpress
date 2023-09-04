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

        ), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'categoria_custom'),
        ));
}

add_action('init', 'registraTaxonomy');

function preSalvataggio($postID)
{
    //$postObj = get_post($postID);
    //error_log(get_the_title($postID));
}

add_action('save_post', 'preSalvataggio');

function add_styleandscript(): void
{
    wp_enqueue_style('custom_style', get_stylesheet_directory_uri() . '/css/custom_style.css');

    wp_register_script('custom_script', get_stylesheet_directory_uri() . '/js/custom_script.js', array('jquery'));
    wp_localize_script('custom_script', 'oggettoAjax', array('proprietaUrl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('custom_script');


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
        $res .= $post->ID . ": " . $post->post_title . "<br>";
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