<html>

<?php
/*
Template Name: oldpost1
 */

//	ultimi/primi 2 post per ordine di modifica/creazione scegliendoli tra 'post' 'complete-post'
/*$args = array(
    'post_type'=> array('post', 'complete-post'),
    'orderby'=>'modified',
    'order'=> 'DESC',
    'posts_per_page'=> 2,

    );*/

// 2 post per ordine di modifica/creazione filtrati da Taxonomy Categoria2
//$args = array(
//    'post_type' => array('post', 'complete-post', 'pippo'),
//    'posts_per_page' => 2,
//    'orderby' => 'modified',
//    'order' => 'ASC',
//    'tax_query' => array(
//        array(
//            'taxonomy' => 'post_tag',   // taxonomy name
//            'field' => 'slug',        // term_id, slug or name
//            'terms' => 'ciao_',                  // term id, term slug or term name
//        )
//    )
//);

//2 post per ordine di modifica/creazione filtrati da 'post' nel titolo

//add_filter('posts_where', 'likeInTitle');
//function likeInTitle($where)
//{
//    global $title;
//    $where .= "AND wp_posts.post_title like '%" . $title . "%'";
//    return $where;
//}
//$title = 'post';
//$args = array(
//    'post_type' => array('post', 'complete-post', 'pippo'),
//    'posts_per_page' => 20,
//);
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$capArgs = (current_user_can('show_complete_post')) ?
    ['post', 'complete-post', 'pippo'] : ['post', 'pippo'];
$args = [
    'post_type' => $capArgs,
    'posts_per_page' => 2,
    'paged' => $paged,
    //'meta_key' => 'Campo-Custom-1',
    //'meta_compare' => 'IN',
    //'meta_value' => ['asdasd', 'afas', 'a'],
];

$my_query = new WP_Query($args);

//$temp_query = $wp_query;
//$wp_query = null;
$wp_query = $my_query;;

wp_head();
?>
<h5><?php bloginfo('name'); ?> </h5>
<h1><?php the_title(); ?> </h1>
<?php
while ($my_query->have_posts()) : $my_query->the_post(); ?>

    <div style='border:solid 1px black; padding : 20px; margin:20px;'>

        <h3>
            <?php if (current_user_can('edit_posts')) { ?>
                <a href="<?php the_permalink() ?>">
                    <?php the_title(); ?>
                </a>
            <?php } else { ?>
                <?php the_title(); ?>
            <?php } ?>
        </h3>

        <?php the_content(); ?>
        <?php the_modified_date('D, d M Y H:i:s'); ?><br>
        <?php wp_link_pages(); ?>
        <?php edit_post_link(); ?>
    </div>
<?php endwhile;
//wp_reset_postdata();
the_posts_pagination([
    'prev_text' => __('« Precedente'),
    'next_text' => __('Successivo »')
]);

/*get_the_posts_pagination(array(
    'base'               => '%_%',
    'format'             => '?paged=%#%',
    'total'              => 10,
    'current'            => 0,
    'show_all'           => false,
    'end_size'           => 1,
    'mid_size'           => 2,
    'prev_next'          => true,
    'prev_text'          => __('« Previous'),
    'next_text'          => __('Next »')
    'type'               => 'plain',
    'add_args'           => false,
    'add_fragment'       => '',
    'before_page_number' => '',
    'after_page_number'  => ''
));*/


get_footer();
remove_filter('posts_where', 'likeInTitle');

?>
</html>