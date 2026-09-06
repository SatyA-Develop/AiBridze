<?php
/**
 * Sticky stacked service-category cards.
 *
 * @package AIBridze
 */

$categories = get_terms(
	array(
		'taxonomy'   => 'service_category',
		'hide_empty' => false,
		'orderby'    => 'term_id',
	)
);
$category_fallbacks = array(
	'ai-development'            => '/assets/images/service-categories/technology-consulting.png',
	'ai-technology-consulting'  => '/assets/images/service-categories/technology-consulting.png',
	'staff-augmentation'        => '/assets/images/service-categories/staff-augmentation.png',
	'mobile-app-development'    => '/assets/images/service-categories/mobile-app-development.png',
	'web-app-development'       => '/assets/images/service-categories/web-app-development.png',
	'ui-ux-design'              => '/assets/images/service-categories/web-app-development.png',
);
$category_descriptions = array(
	'ai-development'           => "Redefining what's possible with AI.",
	'ai-technology-consulting' => 'Turn complex technology decisions into a clear growth roadmap.',
	'staff-augmentation'       => 'Scale your delivery team with experienced technology specialists.',
	'mobile-app-development'   => 'Build high-performance mobile products for every platform.',
	'web-app-development'      => 'Create secure, scalable applications with modern technologies.',
	'ui-ux-design'             => 'Design intuitive digital experiences people enjoy using.',
);
$partners = get_posts(
	array(
		'post_type'      => 'trusted_business',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'orderby'        => array( 'menu_order' => 'ASC' ),
	)
);
$partner_fallbacks = array( '/assets/images/consultation/company-logo.png', '/assets/images/footer/goodfirms.png', '/assets/images/footer/clutch.png' );
?>
<section class="services-stack" id="home-services" data-services-stack aria-labelledby="services-stack-title">
	<div class="services-stack__container">
		<div class="services-stack__intro">
			<h2 id="services-stack-title">Build Smarter. Scale Faster.</h2>
			<p>From AI-powered applications and custom software to cloud infrastructure and DevOps, we help businesses innovate, scale, and stay ahead in a rapidly evolving digital landscape.</p>
			<div class="services-stack__partners">
				<h3>Our Trusted Technology Partners</h3>
				<div class="services-stack__partner-viewport">
					<div class="services-stack__partner-list">
						<?php for ( $partner_copy = 0; $partner_copy < 2; $partner_copy++ ) : ?>
							<div class="services-stack__partner-set"<?php echo 1 === $partner_copy ? ' aria-hidden="true"' : ''; ?>>
								<?php foreach ( $partners as $index => $partner ) :
									$partner_logo = has_post_thumbnail( $partner ) ? get_the_post_thumbnail_url( $partner, 'medium' ) : get_theme_file_uri( $partner_fallbacks[ $index % count( $partner_fallbacks ) ] );
									?>
									<div class="services-stack__partner"><img src="<?php echo esc_url( $partner_logo ); ?>" alt="<?php echo esc_attr( get_the_title( $partner ) ); ?>"></div>
								<?php endforeach; ?>
							</div>
						<?php endfor; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="services-stack__cards">
			<?php foreach ( $categories as $index => $category ) :
				$image_id   = (int) get_term_meta( $category->term_id, '_aibridze_featured_image_id', true );
				$image_url  = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : get_theme_file_uri( $category_fallbacks[ $category->slug ] ?? '/assets/images/service-categories/technology-consulting.png' );
				$description = $category->description ?: ( $category_descriptions[ $category->slug ] ?? 'Technology solutions designed to move your business forward.' );
				?>
				<article class="service-stack-card" data-service-stack-card style="--card-index: <?php echo esc_attr( $index ); ?>">
					<div class="service-stack-card__content">
						<span class="service-stack-card__eyebrow">We Offer</span>
						<h3><?php echo esc_html( $category->name ); ?></h3>
						<p><?php echo esc_html( $description ); ?></p>
						<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">View Services <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/service-categories/button-arrow.png' ) ); ?>" width="12" height="12" alt=""></a>
					</div>
					<img class="service-stack-card__image" src="<?php echo esc_url( $image_url ); ?>" width="374" height="380" alt="">
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
