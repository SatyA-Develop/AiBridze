<?php
/** Computer Vision business-value mosaic. */
$rows = $args['rows'] ?? array();
if ( ! $rows ) return;
?>
<section class="vision-benefits">
	<div class="container">
		<header class="vision-benefits__header">
			<?php if ( $eyebrow = aibridze_service_value( 'benefits_eyebrow' ) ) : ?><p class="service-kicker section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title = aibridze_service_value( 'benefits_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description = aibridze_service_value( 'benefits_description' ) ) : ?><div class="vision-benefits__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<div class="vision-benefits__grid">
			<?php foreach ( $rows as $index => $row ) : ?>
			<article class="vision-benefits__card vision-benefits__card--<?php echo esc_attr( (string) ( $index + 1 ) ); ?>"<?php if ( ! empty( $row['image'] ) ) : ?> style="--vision-card-image:url('<?php echo esc_url( $row['image'] ); ?>')"<?php endif; ?>>
				<h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3>
				<?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
