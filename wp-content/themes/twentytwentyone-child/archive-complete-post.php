<?php 
global $wp_query;
$args = array('post_type'=>array('complete-post'));
query_posts($args);
?>


<?php  if(have_posts()) : while(have_posts()): the_post();?>

    <?php /*include 'single-complete-post.php' */
        get_template_part('single-complete-post');
    ?>

<?php endwhile; ?>
<?php endif; ?>

