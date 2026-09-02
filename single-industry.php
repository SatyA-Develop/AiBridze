<?php
/** Default title and content layout for Industry posts. */
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/content/standard-page' );
endwhile;
get_footer();
