<?php
/** Default title layout for the Services archive URL. */
get_header();
?>
<section class="content-page">
	<div class="container content-page__inner">
		<p class="listing-hero__eyebrow section-callout"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		<h1><?php esc_html_e( 'Services', 'aibridze' ); ?></h1>
	</div>
</section>
<?php get_footer();
