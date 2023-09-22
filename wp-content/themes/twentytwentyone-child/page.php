<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */
session_start();
global $wp_query;
global $wp_textdomain_registry;
$searchresult = array_key_exists('pagename',$wp_query->query) && $wp_query->query['pagename'] == 'search-result';
if ($searchresult) {
    global $_POST;
    global $_SESSION;
    if ($_POST != []) {

        $title = $_POST['title_search_param'];
        $content = $_POST['content_search_param'];
        $tag = $_POST['tag_search_param'];
        $_SESSION['title_search_param'] = $title;
        $_SESSION['content_search_param'] = $content;
        $_SESSION['tag_search_param'] = $tag;
    } else {
        $title = $_SESSION['title_search_param'];
        $content = $_SESSION['content_search_param'];
        $tag = $_SESSION['tag_search_param'];
    }
    global $_SESSION;

    $_SESSION['title_search_param'] = $title;
    $types = array('complete-post', 'post', 'pippo', 'notizia', 'contatto');
    $args = ['post_type' => $types,
        'posts_per_page' => 5,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1];
    if ($title != "" || $content != "") {
        add_filter('posts_where', 'searchInTitleAndContent');
        function searchInTitleAndContent($where)
        {
            global $title;
            global $content;
            $where .= " AND wp_posts.post_title like '%" . $title . "%' AND wp_posts.post_content like '%" . $content . "%'";
            return $where;
        }
    }
    if ($tag != "") {
        $args['tax_query'] = array(
            'relation'=>'OR',
            array(
                'taxonomy' => 'post_tag',   // taxonomy name
                'field' => 'slug',        // term_id, slug or name
                'terms' => $tag,                  // term id, term slug or term name
            ),
            array(
                'taxonomy' => 'category',   // taxonomy name
                'field' => 'slug',        // term_id, slug or name
                'terms' => $tag,                  // term id, term slug or term name
            ),
            array(
                'taxonomy' => 'categoria_custom',   // taxonomy name
                'field' => 'slug',        // term_id, slug or name
                'terms' => $tag,                  // term id, term slug or term name
            ),
        );
    }
    $wp_query = new WP_Query($args);
}
get_header();

/* Start the Loop */

while (have_posts()) :
    the_post();
    if ($searchresult) {
        get_template_part('template-parts/content/content-single');
    } else {
        get_template_part('template-parts/content/content-page');
    }


    // If comments are open or there is at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
endwhile; // End of the loop.
if ($searchresult) {
    the_posts_pagination();
}
get_footer();
remove_filter('posts_where', 'searchInTitle');

