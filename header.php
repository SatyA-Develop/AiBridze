<?php
/**
 * Site header.
 *
 * @package AIBridze
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'aibridze' ); ?></a>
<header class="site-header" data-site-header>
	<div class="container site-header__inner">
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'AIBridze home', 'aibridze' ); ?>">
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/aibridze-logo.png' ) ); ?>" width="186" height="42" alt="AIBridze">
		</a>
		<a class="header-contact header-contact--mobile" href="<?php echo esc_url( aibridze_page_url( 'contact-us', '/contact-us/' ) ); ?>"<?php echo aibridze_navigation_attributes( aibridze_page_url( 'contact-us', '/contact-us/' ), 'contact-us' ); ?> aria-label="<?php esc_attr_e( 'Contact Us', 'aibridze' ); ?>">
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/support-ai-icon.png' ) ); ?>" width="20" height="20" alt="">
		</a>

		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-menu-toggle>
			<span class="screen-reader-text"><?php esc_html_e( 'Toggle navigation', 'aibridze' ); ?></span>
			<span></span><span></span><span></span>
		</button>

		<nav class="primary-navigation" id="primary-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'aibridze' ); ?>" data-primary-navigation>
			<ul class="primary-navigation__list">
				<li class="menu-item menu-item--mega">
					<a href="<?php echo esc_url( aibridze_page_url( 'services', '/services/' ) ); ?>"<?php echo aibridze_navigation_attributes( aibridze_page_url( 'services', '/services/' ), 'services' ); ?>>Services <img class="menu-chevron" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/mega-menu-dropdown.svg' ) ); ?>" width="22" height="22" alt=""></a>
					<?php get_template_part( 'template-parts/header/services-mega' ); ?>
				</li>
				<li class="menu-item menu-item--mega">
					<a href="<?php echo esc_url( aibridze_page_url( 'industries', '/industries/' ) ); ?>"<?php echo aibridze_navigation_attributes( aibridze_page_url( 'industries', '/industries/' ), 'industries' ); ?>>Industries <img class="menu-chevron" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/mega-menu-dropdown.svg' ) ); ?>" width="22" height="22" alt=""></a>
					<?php get_template_part( 'template-parts/header/industries-mega' ); ?>
				</li>
				<li class="menu-item"><a href="<?php echo esc_url( aibridze_page_url( 'portfolio', '/portfolio/' ) ); ?>"<?php echo aibridze_navigation_attributes( aibridze_page_url( 'portfolio', '/portfolio/' ), 'portfolio' ); ?>>Portfolio</a></li>
				<li class="menu-item"><a href="<?php echo esc_url( aibridze_page_url( 'about-us', '/about-us/' ) ); ?>"<?php echo aibridze_navigation_attributes( aibridze_page_url( 'about-us', '/about-us/' ), 'about-us' ); ?>>About Us</a></li>
				<li class="menu-item menu-item--studio">
					<?php $ai_studio = get_page_by_path( 'ai-studio', OBJECT, array( 'page', 'service' ) ); ?>
					<a href="<?php echo esc_url( $ai_studio ? get_permalink( $ai_studio ) : home_url( '/ai-studio/' ) ); ?>"<?php echo aibridze_navigation_attributes( $ai_studio ? get_permalink( $ai_studio ) : home_url( '/ai-studio/' ), 'ai-studio' ); ?>><img class="ai-studio-icon" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/ai-studio-star.png' ) ); ?>" width="24" height="24" alt=""> AI Studio</a>
				</li>
			</ul>

			<a class="header-contact" href="<?php echo esc_url( aibridze_page_url( 'contact-us', '/contact-us/' ) ); ?>"<?php echo aibridze_navigation_attributes( aibridze_page_url( 'contact-us', '/contact-us/' ), 'contact-us' ); ?>>
				<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/support-ai-icon.png' ) ); ?>" width="20" height="20" alt="">
				<span>Contact Us</span>
			</a>
		</nav>
	</div>
</header>
<main id="main-content">
