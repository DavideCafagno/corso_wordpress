<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

the_title( '<a href="'. get_the_permalink().'" <h1 class="entry-title">', '</h1></a>' );
