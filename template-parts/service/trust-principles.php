<?php
/** Reusable, dashboard-managed Responsible AI trust card. */
$rows = isset( $args['rows'] ) && is_array( $args['rows'] ) ? $args['rows'] : array();
if ( ! $rows ) return;

$title       = aibridze_service_value( 'trust_title' );
$description = aibridze_service_value( 'trust_description' );
$image       = aibridze_service_value( 'trust_image' );
$label       = aibridze_service_value( 'trust_principles_label' );
$cta_label   = aibridze_service_value( 'trust_cta_label' );
$cta_url     = aibridze_service_value( 'trust_cta_url', '#consultation' );
?>
<section class="service-responsible-ai" data-service-section>
	<div class="container">
		<div class="service-responsible-ai__card">
			<?php if ( $image ) : ?>
				<div class="service-responsible-ai__visual"><img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy"></div>
			<?php endif; ?>
			<div class="service-responsible-ai__body">
				<h2><?php echo esc_html( $title ); ?></h2>
				<?php if ( $description ) : ?><div class="service-responsible-ai__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
				<?php if ( $label ) : ?><h3><?php echo esc_html( $label ); ?></h3><?php endif; ?>
				<div class="service-responsible-ai__principles">
					<?php foreach ( $rows as $row ) : ?>
						<div class="service-responsible-ai__principle">
							<?php if ( ! empty( $row['image'] ) ) : ?><img src="<?php echo esc_url( $row['image'] ); ?>" alt="" loading="lazy"><?php endif; ?>
							<span><?php echo esc_html( $row['title'] ?? '' ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( $cta_label ) : ?>
					<a class="service-responsible-ai__cta" href="<?php echo esc_url( $cta_url ); ?>"<?php if ( '#consultation' === $cta_url ) : ?> data-consultation-open<?php endif; ?>><?php echo esc_html( $cta_label ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
