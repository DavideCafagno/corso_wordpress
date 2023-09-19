<?php wp_head();?>
<h1><?php bloginfo('title'); ?></h1>
<?php  if(have_posts()) : while(have_posts()): the_post();?>

    <?php get_template_part("notizia")?>



<?php endwhile; ?>
<?php endif; ?>
<?php
the_posts_pagination();
get_footer();?>
