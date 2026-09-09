<?php
/**
 * Shared interior-page presentation.
 *
 * @package AIBridze
 */
?>
<section class="content-page">
	<div class="container content-page__inner">
		<p class="listing-hero__eyebrow section-callout"><?php echo esc_html( get_post_meta( get_the_ID(), '_aibridze_eyebrow', true ) ?: get_bloginfo( 'name' ) ); ?></p>
		<h1><?php the_title(); ?></h1>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</div>
</section>
