<?php 
global $wp_query;
$args = array('post_type'=>array('complete-post'));
query_posts($args);
?>


<?php get_header();?>
<?php  if(have_posts()) : while(have_posts()): the_post();?>

    <?php get_template_part("complete_post")?>



<?php endwhile; ?>
<?php endif; ?>
<?php get_footer();?>
