<?php
/**
 * Site footer.
 *
 * @package AIBridze
 */
?>
</main>
<?php
$footer_service_categories = array();
$footer_service_slugs      = array( 'ai-development', 'ai-technology-consulting', 'staff-augmentation', 'mobile-app-development', 'web-app-development', 'ui-ux-design' );
foreach ( $footer_service_slugs as $service_slug ) {
	$service_category = get_term_by( 'slug', $service_slug, 'service_category' );
	if ( $service_category ) {
		$footer_service_categories[] = $service_category;
	}
}
$footer_industries = get_posts(
	array(
		'post_type'      => 'industry',
		'posts_per_page' => 6,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
	)
);
$social_links = aibridze_social_links();
?>
<footer class="site-footer">
	<div class="site-footer__shell">
		<div class="site-footer__container">
			<div class="site-footer__content">
			<div class="site-footer__brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'AIBridze home', 'aibridze' ); ?>">
					<img class="site-footer__logo" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/footer-logo.png' ) ); ?>" width="200" height="41" alt="AIBridze">
				</a>
				<address class="site-footer__address">
					<span><strong class="site-footer__address-label"><?php esc_html_e( 'Add:', 'aibridze' ); ?></strong> <?php esc_html_e( 'KR Signature Tower, PLOT, Street Number 3, Sector 135, Noida, Uttar Pradesh 201304', 'aibridze' ); ?></span>
					<a href="tel:+917065169433"><svg class="site-footer__contact-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.69 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.33 1.84.56 2.8.69A2 2 0 0 1 22 16.92Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg><span>+91 - 70651 69433</span></a>
					<a href="mailto:info@aibridze.com"><svg class="site-footer__contact-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.5"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg><span>info@aibridze.com</span></a>
				</address>
				<div class="site-footer__socials" aria-label="<?php esc_attr_e( 'Social links', 'aibridze' ); ?>">
					<a href="<?php echo esc_url( $social_links['facebook'] ?: '#' ); ?>" aria-label="Facebook"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/facebook.svg' ) ); ?>" width="8" height="16" alt=""></a>
					<a href="<?php echo esc_url( $social_links['instagram'] ?: '#' ); ?>" aria-label="Instagram"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/instagram.svg' ) ); ?>" width="12" height="12" alt=""></a>
					<a href="<?php echo esc_url( $social_links['linkedin'] ?: '#' ); ?>" aria-label="LinkedIn"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/linkedin.svg' ) ); ?>" width="12" height="12" alt=""></a>
					<a href="<?php echo esc_url( $social_links['x'] ?: '#' ); ?>" aria-label="X"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/x.svg' ) ); ?>" width="14" height="14" alt=""></a>
				</div>
			</div>

			<nav class="site-footer__column" aria-label="<?php esc_attr_e( 'Footer services', 'aibridze' ); ?>">
				<h2><button class="site-footer__toggle" type="button" aria-expanded="false" aria-controls="footer-services"><?php esc_html_e( 'Services', 'aibridze' ); ?><span aria-hidden="true"></span></button></h2>
				<ul id="footer-services">
					<?php foreach ( $footer_service_categories as $category ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<nav class="site-footer__column" aria-label="<?php esc_attr_e( 'Footer industries', 'aibridze' ); ?>">
				<h2><button class="site-footer__toggle" type="button" aria-expanded="false" aria-controls="footer-industries"><?php esc_html_e( 'Industries', 'aibridze' ); ?><span aria-hidden="true"></span></button></h2>
				<ul id="footer-industries">
					<?php foreach ( $footer_industries as $industry ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $industry ) ); ?>"><?php echo esc_html( get_the_title( $industry ) ); ?></a></li>
					<?php endforeach; ?>
					<li><a class="site-footer__more" href="<?php echo esc_url( aibridze_page_url( 'industries', '/industries/' ) ); ?>"><?php esc_html_e( 'See All Industries', 'aibridze' ); ?> <span aria-hidden="true">↗</span></a></li>
				</ul>
			</nav>

			<nav class="site-footer__column" aria-label="<?php esc_attr_e( 'Footer company links', 'aibridze' ); ?>">
				<h2><button class="site-footer__toggle" type="button" aria-expanded="false" aria-controls="footer-company"><?php esc_html_e( 'Our Company', 'aibridze' ); ?><span aria-hidden="true"></span></button></h2>
				<ul id="footer-company">
					<li><a href="<?php echo esc_url( aibridze_page_url( 'contact-us', '/contact-us/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'aibridze' ); ?></a></li>
					<li><a href="<?php echo esc_url( aibridze_page_url( 'about-us', '/about-us/' ) ); ?>"><?php esc_html_e( 'About Us', 'aibridze' ); ?></a></li>
					<li><a href="<?php echo esc_url( aibridze_page_url( 'careers', '/careers/' ) ); ?>"><?php esc_html_e( 'Careers', 'aibridze' ); ?></a></li>
					<li><a href="<?php echo esc_url( aibridze_page_url( 'blogs', '/blogs/' ) ); ?>"><?php esc_html_e( 'Blogs', 'aibridze' ); ?></a></li>
				</ul>
			</nav>
			</div>

			<div class="site-footer__bottom">
				<div class="site-footer__legal">
					<a href="https://www.dmca.com/Protection/Status.aspx?ID=b2ba9278-15da-4a7b-adb0-5e86b820dbe9" title="DMCA.com Protection Status" class="dmca-badge"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/dmca-custom.png' ) ); ?>" width="300" height="95" alt="DMCA.com Protection Status"></a>
					<script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js" defer></script>
					<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> AIBridze | <a href="<?php echo esc_url( aibridze_page_url( 'terms-conditions', '/terms-conditions/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'aibridze' ); ?></a> | <?php esc_html_e( 'All Rights Reserved', 'aibridze' ); ?></span>
				</div>
				<div class="site-footer__recognition" role="img" aria-label="Mobile App Daily, Clutch, Trustpilot and GoodFirms">
					<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/mobile-app-daily-hires.png' ) ); ?>" width="153" height="28" alt="Mobile App Daily">
					<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/clutch-hires.png' ) ); ?>" width="96" height="28" alt="Clutch">
					<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/trustpilot-hires.png' ) ); ?>" width="127" height="28" alt="Trustpilot">
					<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/goodfirms-hires.png' ) ); ?>" width="148" height="28" alt="GoodFirms">
				</div>
			</div>
		</div>
	</div>
</footer>
<?php get_template_part( 'template-parts/components/consultation-modal' ); ?>
<?php wp_footer(); ?>
</body>
</html>
