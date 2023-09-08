

<!DOCTYPE html>
<html>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_uri()); ?>" type="text/css" />
    <?php wp_head(); ?>
</head>

<body>
<h1><?php bloginfo('name'); ?></h1>
<h2><?php bloginfo('description'); ?></h2>
<?php
global $wp_query;
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
//echo $paged;
$wp_query = new WP_Query( 'posts_per_page=10&paged=' . $paged );
echo get_query_var('paged');

//print_r($wp_query);
$args = array('post_type'=>array('complete-post','pippo','post'));


query_posts($args);
error_log("Archive");
//print_r($wp_query);
?>
<?php
echo "Ciao DA Taxonomy";
if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div style='border:solid 1px black; padding : 20px; margin:20px;'>
        <h3><a href="<?php the_permalink()?>"><?php the_title(); ?></a></h3>

        <?php the_content(); ?>
        <?php wp_link_pages(); ?>
        <?php edit_post_link(); ?>
    </div>
<?php endwhile;
    echo the_posts_pagination();?>

    <!--        --><?php
//        if (get_next_posts_link()) {
//            next_posts_link();
//        }
//        ?>
    <!--        --><?php
//        if (get_previous_posts_link()) {
//            previous_posts_link();
//        }
//        ?>

<?php else : ?>

    <p>No posts found. :(</p>

<?php endif; ?>
<?php wp_footer();
get_footer();
error_log("Ciao da index")
?>
</body>

</html>