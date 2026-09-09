<?php
$title = aibridze_service_value( 'hero_title', get_the_title() );
$description = aibridze_service_value( 'hero_description', get_the_excerpt() );
$image = aibridze_service_value( 'hero_image', get_the_post_thumbnail_url( get_the_ID(), 'full' ) );
$mobile = aibridze_service_value( 'hero_mobile_image', $image );
$label = aibridze_service_value( 'hero_cta_label', __( 'Talk to Our Experts', 'aibridze' ) );
$url = aibridze_service_value( 'hero_cta_url', aibridze_page_url( 'contact-us', '/contact-us/' ) );
?>
<section class="service-hero" style="--service-hero-image:url('<?php echo esc_url( $image ); ?>');--service-hero-mobile:url('<?php echo esc_url( $mobile ); ?>')"><div class="service-hero__shade"></div><div class="container service-hero__inner">
	<?php if ( aibridze_service_value( 'hero_eyebrow' ) ) : ?><p class="service-kicker section-callout"><?php echo esc_html( aibridze_service_value( 'hero_eyebrow' ) ); ?></p><?php endif; ?><h1><?php echo esc_html( $title ); ?></h1>
	<?php if ( $description ) : ?><div class="service-hero__copy"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
	<?php if ( $label ) get_template_part( 'template-parts/components/button', null, array( 'label' => $label, 'url' => $url, 'class' => 'service-button', 'aria_label' => $label ) ); ?>
</div></section>
