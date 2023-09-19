<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

/* Start the Loop */

if(!($post->post_name=='home')) {
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content/content-page');

        // If comments are open or there is at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    endwhile; // End of the loop.
}else{
    get_template_part('template-parts/content/content-page');
}
get_footer();
