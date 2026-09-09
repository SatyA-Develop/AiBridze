<?php
$title = aibridze_service_value( 'cta_title' );
if ( ! $title ) return;
$image   = aibridze_service_value( 'cta_image' );
$variant = sanitize_html_class( aibridze_service_value( 'cta_variant', 'default' ) );
$highlight = trim( (string) aibridze_service_value( 'cta_highlight' ) );
$heading = esc_html( $title );
if ( $highlight && false !== strpos( $title, $highlight ) ) {
	$parts = explode( $highlight, $title, 2 );
	$heading = esc_html( $parts[0] ) . '<span class="service-trust__highlight">' . esc_html( $highlight ) . '</span>' . esc_html( $parts[1] );
}
?>
<section class="service-trust service-trust--<?php echo esc_attr( $variant ); ?>"><div class="container"><div class="service-trust__card">
	<div class="service-trust__copy"><h2><?php echo wp_kses( $heading, array( 'span' => array( 'class' => true ) ) ); ?></h2><?php echo wp_kses_post( wpautop( aibridze_service_value( 'cta_description' ) ) ); ?>
	<?php if ( aibridze_service_value( 'cta_label' ) ) get_template_part( 'template-parts/components/button', null, array( 'label' => aibridze_service_value( 'cta_label' ), 'url' => aibridze_service_value( 'cta_url', aibridze_page_url( 'contact-us', '/contact-us/' ) ), 'variant' => 'light', 'class' => 'service-trust__button', 'icon' => get_theme_file_uri( '/assets/images/service-cta-arrow.png' ) ) ); ?>
	</div>
	<?php if ( $image ) : ?><div class="service-trust__media"><img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy"></div><?php endif; ?>
</div></div></section>
