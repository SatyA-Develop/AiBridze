<?php
/**
 * Dynamic Services mega menu.
 *
 * @package AIBridze
 */

$service_groups = array(
	'ai-development' => array(
		'name'     => __( 'AI Development', 'aibridze' ),
		'services' => array( 'AI Agent Development', 'Generative AI Development', 'AI Chatbot Development', 'AI Automation', 'RAG Development', 'Voice AI', 'Computer Vision' ),
	),
	'ai-technology-consulting' => array(
		'name'     => __( 'AI & Technology Consulting', 'aibridze' ),
		'services' => array( 'AI & ML Consulting', 'Business Automation Consulting', 'Software Consulting', 'Digital Transformation Consulting', 'Mobile App Consulting', 'Web Consulting' ),
	),
	'staff-augmentation' => array(
		'name'     => __( 'Staff Augmentation', 'aibridze' ),
		'services' => array( 'AI & ML Engineers', 'Python Developers', 'React Developers', 'Flutter Developers', 'Full Stack Developers', 'Backend Developers', 'QA Engineers', 'Dedicated Teams' ),
	),
	'mobile-app-development' => array(
		'name'     => __( 'Mobile App Development', 'aibridze' ),
		'services' => array( 'iOS App Development', 'Android App Development', 'Cross-Platform Development', 'App Maintenance & Support' ),
	),
	'web-app-development' => array(
		'name'     => __( 'Web App Development', 'aibridze' ),
		'services' => array( 'Custom Web Applications', 'SaaS Application Development', 'Progressive Web Apps', 'Web Maintenance & Support' ),
	),
	'ui-ux-design' => array(
		'name'     => __( 'UI/UX Design', 'aibridze' ),
		'services' => array( 'Product Design', 'UX Research & Strategy', 'Wireframing & Prototyping', 'Design Systems' ),
	),
);

$category_extras = array(
	'ai-development' => array(
		'text' => __( 'Automate, innovate, and grow with AI.', 'aibridze' ),
	),
	'ai-technology-consulting' => array(
		'text'  => __( 'Turn ideas into scalable digital solutions with expert AI and technology consulting.', 'aibridze' ),
		'image' => '/assets/images/services/consulting-technology-icons.png',
	),
	'staff-augmentation' => array(
		'text' => __( 'Scale your team with expert tech talent.', 'aibridze' ),
	),
	'mobile-app-development' => array(
		'text'  => __( 'Build high-performance iOS, Android and cross-platform apps.', 'aibridze' ),
		'image' => '/assets/images/services/mobile-technology-icons.png',
	),
	'web-app-development' => array(
		'text'  => __( 'Build secure, scalable web applications with modern technologies.', 'aibridze' ),
		'image' => '/assets/images/services/web-technology-icons.png',
	),
	'ui-ux-design' => array(
		'text'  => __( 'From research to polished interfaces, we design products people enjoy using.', 'aibridze' ),
		'image' => '/assets/images/services/uiux-technology-icons.png',
	),
);
?>
<div class="mega-menu mega-menu--services" data-mega-menu>
	<div class="mega-menu__body">
		<div class="mega-menu__categories" role="tablist" aria-label="<?php esc_attr_e( 'Service categories', 'aibridze' ); ?>">
			<?php foreach ( $service_groups as $category_slug => $category ) : ?>
				<?php $index = array_search( $category_slug, array_keys( $service_groups ), true ); ?>
				<button class="mega-menu__category<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>" data-mega-tab="service-panel-<?php echo esc_attr( $category_slug ); ?>">
					<?php echo esc_html( $category['name'] ); ?><img class="mega-menu__category-arrow" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/mega-menu-arrow.svg' ) ); ?>" width="16" height="16" alt="">
				</button>
			<?php endforeach; ?>
		</div>

		<div class="mega-menu__panels">
			<?php foreach ( $service_groups as $category_slug => $category ) : ?>
				<?php
				$index = array_search( $category_slug, array_keys( $service_groups ), true );
				$extra = $category_extras[ $category_slug ] ?? array( 'text' => sprintf( __( 'Explore our %s capabilities.', 'aibridze' ), $category['name'] ) );
				?>
				<div class="mega-menu__panel mega-menu__panel--<?php echo esc_attr( $category_slug ); ?><?php echo 0 === $index ? ' is-active' : ''; ?>" id="service-panel-<?php echo esc_attr( $category_slug ); ?>" role="tabpanel">
					<div class="mega-menu__grid">
						<?php foreach ( $category['services'] as $service_title ) : ?>
							<?php
							$service_slug = sanitize_title( $service_title );
							$service_page = get_page_by_path( $service_slug, OBJECT, 'page' );
							$legacy_post  = get_page_by_path( $service_slug, OBJECT, 'service' );
							$icon_source  = $service_page ?: $legacy_post;
							$icon_path    = '/assets/images/services/' . $service_slug . '.png';
							$icon_url     = $icon_source ? aibridze_service_icon_url( $icon_source ) : ( file_exists( get_theme_file_path( $icon_path ) ) ? get_theme_file_uri( $icon_path ) : get_theme_file_uri( '/assets/images/service-icon-default.png' ) );
							$service_url  = $service_page ? get_permalink( $service_page ) : ( $legacy_post ? get_permalink( $legacy_post ) : home_url( '/' . $service_slug . '/' ) );
							?>
							<a class="mega-card" href="<?php echo esc_url( $service_url ); ?>"<?php echo aibridze_navigation_attributes( $service_url ); ?>><img class="mega-card__icon" src="<?php echo esc_url( $icon_url ); ?>" width="24" height="24" alt=""><?php echo esc_html( $service_title ); ?></a>
						<?php endforeach; ?>
					</div>
					<a class="mega-menu__explore" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">
						<span class="mega-menu__explore-copy"><span><?php echo esc_html( $extra['text'] ); ?></span><span class="mega-menu__explore-link"><?php esc_html_e( 'Explore', 'aibridze' ); ?> <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/mega-menu-arrow.svg' ) ); ?>" width="15" height="15" alt=""></span></span>
						<?php if ( ! empty( $extra['image'] ) ) : ?><img class="mega-menu__technology-icons" src="<?php echo esc_url( get_theme_file_uri( $extra['image'] ) ); ?>" width="179" height="115" alt=""><?php endif; ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="mega-menu__footer">
		<span><?php esc_html_e( "Can't find the service you need? Our expertise goes beyond the list.", 'aibridze' ); ?></span>
		<?php get_template_part( 'template-parts/components/mega-menu-cta' ); ?>
	</div>
</div>
