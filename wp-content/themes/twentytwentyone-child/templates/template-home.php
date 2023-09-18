<?php
/*
Template Name: home_template
*/
get_header(); ?>

    <img style="width: 100%;" class=" mx-auto d-block" src="<?php echo the_post_thumbnail_url(); ?>">
<?php
the_content();

?>
    <hr>
<p>Ultime news:  <a href="<?php echo the_permalink().'notizia'?>">Visualizza le ultime news</a></p>
<?php
$args = ['post_type' => 'notizia',
    'posts_per_page'=>4,
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1
];
global $wp_query;
$wp_query = new WP_Query($args);
?>
    <div class="container" style="margin-top:100px;">
            <?php
            while (have_posts()) : the_post(); ?>

                <div class="card" style="margin:20px; width: fit-content; display: inline-grid; width: 250px;height: 400px;">
                    <img class="card-img-top" src="<?php the_post_thumbnail_url(); ?>" style="width: 250px;height: 200px;">
                    <div class="card-body">
                        <h5 class="card-title"><?php the_title() ?></h5>
                        <p class="card-text"><?php the_excerpt(); ?></p>
                        <a href="<?php echo the_permalink() ?>" class="btn btn-primary">Continua a leggere</a>
                    </div>
                </div>
            <?php endwhile; ?>
    </div>

<?php

get_footer();