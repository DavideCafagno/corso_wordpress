<html>

<?php
/*
Template Name: lista_contatti
*/
global $wp_query;
$paged = get_query_var('paged')? get_query_var('paged'):1;
$args = [
    'post_type'=>'contatto',
    'posts_per_page' => 1,
    'paged'=> $paged];
$my_query = new WP_Query($args);
$wp_query = $my_query;

 wp_head();
?>
<h5><?php bloginfo('name'); ?> </h5>
<h1><?php the_title(); ?> </h1>
<?php  if(have_posts()) : while(have_posts()): the_post();?>

<?php get_template_part("contatto")?>

<?php endwhile; ?>
<?php endif; ?>
<?php
the_posts_pagination();
get_footer();?>

</html>
