<?php
/**
 * Scroll-led transformation and social-proof section.
 *
 * @package AIBridze
 */

$transformation_copy = "We don't just promise transformation – we deliver measurable results across key sectors. Explore how we turn complex challenges into strategic advantages for our enterprise clients.";
$copy_words          = preg_split( '/\s+/', $transformation_copy );
$testimonials        = get_posts(
	array(
		'post_type'      => 'testimonial',
		'posts_per_page' => 4,
		'post_status'    => 'publish',
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	)
);
$businesses          = get_posts(
	array(
		'post_type'      => 'trusted_business',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);
$businesses = array_filter(
	$businesses,
	static function ( $business ) {
		return (bool) get_the_post_thumbnail_url( $business, 'medium' );
	}
);
?>
<section class="transformation" data-transformation aria-labelledby="transformation-heading">
	<div class="transformation__main container">
		<h2 class="transformation__copy" id="transformation-heading" aria-label="<?php echo esc_attr( $transformation_copy ); ?>">
			<?php foreach ( $copy_words as $word_index => $word ) : ?><span data-transform-word aria-hidden="true"><?php echo esc_html( $word ); ?></span><?php echo $word_index < count( $copy_words ) - 1 ? ' ' : ''; ?><?php endforeach; ?>
		</h2>

		<div class="transformation__testimonials" data-section-testimonials>
			<img class="transformation__quote" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/transformation-quote.png' ) ); ?>" width="35" height="35" alt="">
			<?php foreach ( $testimonials as $index => $testimonial ) :
				$role         = (string) get_post_meta( $testimonial->ID, '_aibridze_testimonial_role', true );
				$company      = (string) get_post_meta( $testimonial->ID, '_aibridze_testimonial_company', true );
				$logo_id      = (int) get_post_meta( $testimonial->ID, '_aibridze_testimonial_logo_id', true );
				$avatar       = has_post_thumbnail( $testimonial ) ? get_the_post_thumbnail_url( $testimonial, 'thumbnail' ) : get_theme_file_uri( '/assets/images/consultation/testimonial-avatar.png' );
				$company_logo = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : get_theme_file_uri( '/assets/images/consultation/company-logo.png' );
				?>
				<article class="transformation-testimonial<?php echo 0 === $index ? ' is-active' : ''; ?>" data-section-testimonial aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
					<p><?php echo esc_html( wp_strip_all_tags( get_the_content( null, false, $testimonial ) ) ); ?></p>
					<footer>
						<img class="transformation-testimonial__avatar" src="<?php echo esc_url( $avatar ); ?>" width="48" height="48" alt="<?php echo esc_attr( get_the_title( $testimonial ) ); ?>">
						<span><strong><?php echo esc_html( get_the_title( $testimonial ) ); ?></strong><small><?php echo esc_html( trim( $role . ( $role && $company ? ', ' : '' ) . $company ) ); ?></small></span>
						<img class="transformation-testimonial__company" src="<?php echo esc_url( $company_logo ); ?>" alt="<?php echo esc_attr( $company ); ?>">
					</footer>
				</article>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( $businesses ) : ?>
	<div class="trusted-businesses">
		<div class="trusted-businesses__inner">
			<div class="trusted-businesses__title"><?php esc_html_e( 'Trusted by Businesses Across Industries', 'aibridze' ); ?></div>
			<div class="trusted-businesses__viewport">
				<div class="trusted-businesses__track" data-logo-track>
					<?php for ( $copy = 0; $copy < 2; $copy++ ) : ?>
						<div class="trusted-businesses__set"<?php echo 1 === $copy ? ' aria-hidden="true"' : ''; ?>>
							<?php foreach ( $businesses as $business ) : ?>
								<?php echo get_the_post_thumbnail( $business, 'medium', array( 'alt' => get_the_title( $business ), 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
							<?php endforeach; ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</section>
