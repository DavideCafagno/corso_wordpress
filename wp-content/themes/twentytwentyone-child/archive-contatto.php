
<?php wp_head();
?>
<h1><?php bloginfo('title'); ?></h1>
<h5><?php bloginfo('name'); ?> </h5>
<h1><?php the_title(); ?> </h1>
<?php  if(have_posts()) : while(have_posts()): the_post();?>

    <?php get_template_part("contatto")?>

<?php endwhile; ?>
<?php endif; ?>
<?php
the_posts_pagination();
get_footer();?>
