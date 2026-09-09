<?php
/**
 * Dynamic Industries mega menu.
 *
 * @package AIBridze
 */

$industries = get_posts(
	array(
		'post_type'      => 'industry',
		'posts_per_page' => 12,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
	)
);
?>
<div class="mega-menu mega-menu--industries" data-mega-menu>
	<div class="mega-menu__industries-body">
		<div class="industries-grid">
			<?php foreach ( $industries as $industry ) : ?>
				<?php
				$icon_url = aibridze_industry_icon_url( $industry );
				?>
				<a class="mega-card" href="<?php echo esc_url( get_permalink( $industry ) ); ?>"<?php echo aibridze_navigation_attributes( get_permalink( $industry ) ); ?>><img class="mega-card__icon" src="<?php echo esc_url( $icon_url ); ?>" width="24" height="24" alt=""><?php echo esc_html( get_the_title( $industry ) ); ?></a>
			<?php endforeach; ?>
		</div>
		<div class="mega-menu__industries-visual">
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/industries/industries-menu-visual.png' ) ); ?>" width="258" height="234" alt="">
			<p><?php esc_html_e( 'Building solutions across industries and business domains.', 'aibridze' ); ?></p>
		</div>
	</div>
	<div class="mega-menu__footer">
		<span><?php esc_html_e( "Don't see your industry? Let's explore what's possible with AI.", 'aibridze' ); ?></span>
		<a href="<?php echo esc_url( aibridze_page_url( 'contact-us', '/contact-us/' ) ); ?>"><?php esc_html_e( "Let's discuss your project", 'aibridze' ); ?></a>
	</div>
</div>
