<h1>Archivio Pippo</h1>
<?php 
    global $wp_query;
   // print_r($wp_query);
    $args = array('post_type'=>array('pippo'));
    
    
    query_posts($args);
   // print_r($wp_query);
?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <h3><?php the_title(); ?></h3> 
            <h4><?php the_modified_date(); ?></h4>
            <?php the_content(); ?>
            <?php wp_link_pages(); ?>
            <?php edit_post_link(); ?>

        <?php endwhile; ?>

        <?php
        if (get_next_posts_link()) {
            next_posts_link();
        }
        ?>
        <?php
        if (get_previous_posts_link()) {
            previous_posts_link();
        }
        ?>

    <?php else : ?>

        <p>No posts found. :(</p>

    <?php endif; ?>