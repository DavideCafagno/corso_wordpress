<?php wp_head();?>
    <h1><?php bloginfo('title'); ?></h1>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div style='border:solid 1px black; padding : 20px; margin:20px;' >
            <h3><?php the_title(); ?></h3> 
            <h4><?php the_modified_date(); ?></h4>
            <?php the_content(); ?>
            <?php wp_link_pages(); ?>
            <?php edit_post_link(); ?>
</div>

        <?php endwhile; ?>

        <?php
        the_posts_pagination();
        ?>

    <?php else : ?>

        <p>No posts found. :(</p>

    <?php endif; ?>