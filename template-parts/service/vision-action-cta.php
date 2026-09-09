<?php
/** Editor-managed Computer Vision conversion banner displayed before the FAQ. */
$title = aibridze_service_value( 'action_cta_title' );
if ( ! $title ) return;
$image = aibridze_service_value( 'action_cta_image' );
?>
<section class="vision-action-cta" aria-labelledby="vision-action-cta-title">
	<div class="container">
		<div class="vision-action-cta__card"<?php if ( $image ) : ?> style="--vision-action-image:url('<?php echo esc_url( $image ); ?>')"<?php endif; ?>>
			<div class="vision-action-cta__copy">
				<h2 id="vision-action-cta-title"><?php echo esc_html( $title ); ?></h2>
				<?php echo wp_kses_post( wpautop( aibridze_service_value( 'action_cta_description' ) ) ); ?>
				<?php if ( $label = aibridze_service_value( 'action_cta_label' ) ) : ?><a class="vision-action-cta__button" href="<?php echo esc_url( aibridze_service_value( 'action_cta_url', '#consultation' ) ); ?>" data-consultation-open><?php echo esc_html( $label ); ?></a><?php endif; ?>
			</div>
		</div>
	</div>
</section>
