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

add_filter('template_include', 'at_force_template', 15, 2);
function at_force_template($template)
{
    $isArchive = 'page.php' === basename($template);
    $isSingle = 'single.php' === basename($template);
    error_log($template);
    return $template;
}

function remove_page_from_query_string($wp_query)
{
    // $wp_query['paged'] = null;
    return $wp_query;
}

//add_filter('request', 'remove_page_from_query_string');


//function prefix_change_cpt_archive_per_page( $query ) {
//
//    //* for cpt or any post type main archive
//    if ( $query->is_main_query() && ! is_admin() && is_post_type_archive( 'product' ) ) {
//        $query->set( 'posts_per_page', '2' );
//    }
//
//}
//add_action( 'pre_get_posts', 'prefix_change_cpt_archive_per_page' );
//
///**
// *
// * Posts per page for category (test-category) under CPT archive
// *
// */
//function prefix_change_category_cpt_posts_per_page( $query ) {
//
//    if ( $query->is_main_query() && ! is_admin() && is_category( 'test-category' ) ) {
//        $query->set( 'post_type', array( 'product' ) );
//        $query->set( 'posts_per_page', '2' );
//    }
//
//}
//add_action( 'pre_get_posts', 'prefix_change_category_cpt_posts_per_page' );
//
//
///**
// *
// * custom numbered pagination
// * @http://callmenick.com/post/custom-wordpress-loop-with-pagination
// *
// */
//function custom_pagination( $numpages = '', $pagerange = '', $paged='' ) {
//
//    if (empty($pagerange)) {
//        $pagerange = 2;
//    }
//
//    /**
//     * This first part of our function is a fallback
//     * for custom pagination inside a regular loop that
//     * uses the global $paged and global $wp_query variables.
//     *
//     * It's good because we can now override default pagination
//     * in our theme, and use this function in default queries
//     * and custom queries.
//     */
//    global $paged;
//    if (empty($paged)) {
//        $paged = 1;
//    }
//    if ($numpages == '') {
//        global $wp_query;
//        $numpages = $wp_query->max_num_pages;
//        if(!$numpages) {
//            $numpages = 1;
//        }
//    }
//
//    /**
//     * We construct the pagination arguments to enter into our paginate_links
//     * function.
//     */
//    $pagination_args = array(
//        'base'            => get_pagenum_link(1) . '%_%',
//        'format'          => 'page/%#%',
//        'total'           => $numpages,
//        'current'         => $paged,
//        'show_all'        => False,
//        'end_size'        => 1,
//        'mid_size'        => $pagerange,
//        'prev_next'       => True,
//        'prev_text'       => __('&laquo;'),
//        'next_text'       => __('&raquo;'),
//        'type'            => 'plain',
//        'add_args'        => false,
//        'add_fragment'    => ''
//    );
//
//    $paginate_links = paginate_links($pagination_args);
//
//    if ($paginate_links) {
//        echo "<nav class='custom-pagination'>";
//        echo "<span class='page-numbers page-num'>Page " . $paged . " of " . $numpages . "</span> ";
//        echo $paginate_links;
//        echo "</nav>";
//    }
//
//}
//
//function my_pagination_rewrite() {
//    add_rewrite_rule('([a-z]+)/page/?([0-9]{1,})/?$', 'index.php?category_name=$matches[1]&paged=$matches[2]', 'top');
//}
//add_action('init', 'my_pagination_rewrite');

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
    $variabile =  $wp_query;
    error_log(json_encode($_GET));
    //controllare se siamo in home; esistenza post-type,verificare tipo post-ytpe e controllare contenuto
    //se troviamo 'post' aggiungiamo settings(type and post per page);
if(is_home()){
    if(!array_key_exists('post_type', $query->query_vars)){
       // if(!is_array($query->query_vars['post_type'])){
        //    if("post" == $query->query_vars['post_type']){
                $query->set('posts_per_page', 4);
                $query->set('post_type', array('complete-post','post','pippo'));
       //     }else{
       //         $query->set('posts_per_page', 1);
       //    }
        //}else{
       //     $query->set('posts_per_page', 2);
        //}
    }else{
        $query->set('posts_per_page', 3);
    }

}else{
    $query->set('posts_per_page', 5);
}


    /*if (true) {
        if (
            is_array($query->query_vars) &&
            array_key_exists('post_type', $query->query_vars) &&
            is_array($query->query_vars["post_type"])

        ) {

            if (in_array('complete-post', $query->query_vars["post_type"])) {
                if (is_category()) {
                    // It's the main query for a category archive.

                    // Let's change the query for category archives.
                    $query->set('posts_per_page', 3);
                }else{
                    $query->set('posts_per_page', 2);
                }
            }
        }
        // Not a query for an admin page.
        // It's the main query for a front end page of your site.


    }*/
}

add_action('pre_get_posts', 'target_main_category_query_with_conditional_tags');
function getPostProva($post,$query){
    $abc = $query;
    //error_log($query->query_vars['post_type']);
    return $post;
}
add_action('posts_results', 'getPostProva',10,2);