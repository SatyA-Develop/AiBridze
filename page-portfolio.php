<?php
/** Template Name: Portfolio */
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/content/standard-page' );
endwhile;
get_footer();
