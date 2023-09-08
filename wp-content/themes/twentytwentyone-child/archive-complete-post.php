<?php 

/*$args = array(
    'post_type' => array('complete-post'),
    'posts_per_page' => 5,
    'paged' => returnPaged(get_site_url().'/complete-post'));

query_posts($args);*/
?>


<?php get_header();?>
<?php  if(have_posts()) : while(have_posts()): the_post();?>

    <?php get_template_part("complete_post")?>



<?php endwhile; ?>
<?php endif; ?>
<?php
    the_posts_pagination();
get_footer();?>
