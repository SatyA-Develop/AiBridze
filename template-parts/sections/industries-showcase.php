<?php
/**
 * Animated industries showcase populated from the Industry CPT.
 *
 * @package AIBridze
 */
$industries = get_posts(
	array(
		'post_type'      => 'industry',
		'posts_per_page' => 10,
		'post_status'    => 'publish',
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);
$fallback_images = array(
	'/assets/images/service-categories/mobile-app-development.png',
	'/assets/images/industries/industries-menu-visual.png',
	'/assets/images/process/step-4.jpg',
	'/assets/images/service-categories/staff-augmentation.png',
	'/assets/images/process/step-2.jpg',
	'/assets/images/hero-logistics-port.webp',
);
$ring_industries = $industries;
?>
<section class="industries-showcase" aria-labelledby="industries-showcase-title">
	<header class="industries-showcase__header">
		<p><?php esc_html_e( 'Built for Every Industry', 'aibridze' ); ?></p>
		<h2 id="industries-showcase-title"><?php esc_html_e( 'Technology That Fits Every Industry', 'aibridze' ); ?></h2>
	</header>

	<div class="industries-showcase__viewport" data-industry-ring tabindex="0" aria-label="<?php esc_attr_e( 'Drag or use the arrow keys to browse industries', 'aibridze' ); ?>">
		<div class="industries-showcase__scene">
			<div class="industries-showcase__ring">
				<?php foreach ( $ring_industries as $index => $industry ) :
					$image = has_post_thumbnail( $industry ) ? get_the_post_thumbnail_url( $industry, 'large' ) : get_theme_file_uri( $fallback_images[ $index % count( $fallback_images ) ] );
					?>
					<div class="industry-showcase-card" data-href="<?php echo esc_url( get_permalink( $industry ) ); ?>" data-label="<?php echo esc_attr( sprintf( __( 'View %s industry', 'aibridze' ), get_the_title( $industry ) ) ); ?>" style="--industry-image: url('<?php echo esc_url( $image ); ?>');">
						<a class="industry-showcase-card__link" href="<?php echo esc_url( get_permalink( $industry ) ); ?>"><?php echo esc_html( get_the_title( $industry ) ); ?></a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="industries-showcase__links"></div>
		<div class="industries-showcase__vignette" aria-hidden="true"></div>
		<div class="industries-showcase__dragger" aria-hidden="true"></div>
	</div>

	<a class="industries-showcase__button" href="<?php echo esc_url( aibridze_page_url( 'industries', '/industries/' ) ); ?>">
		<span><?php esc_html_e( 'View All Industries', 'aibridze' ); ?></span><span aria-hidden="true">→</span>
	</a>
</section>
