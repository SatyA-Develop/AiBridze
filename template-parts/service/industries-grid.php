<?php
/**
 * Reusable, editor-managed industries grid.
 *
 * @var array $args Template arguments.
 */
$rows        = isset( $args['rows'] ) && is_array( $args['rows'] ) ? $args['rows'] : array();
$eyebrow     = aibridze_service_value( 'industries_eyebrow' );
$title       = aibridze_service_value( 'industries_title' );
$description = aibridze_service_value( 'industries_description' );
$layout      = get_post_meta( get_the_ID(), '_aibridze_service_layout', true );
$is_chatbot  = 'chatbot' === $layout;
$has_more    = in_array( $layout, array( 'chatbot', 'rag' ), true );

if ( ! $rows ) return;
?>
<section class="service-industries" data-service-section>
	<div class="container">
		<header class="service-industries__header">
			<?php if ( $eyebrow ) : ?><p class="service-industries__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description ) : ?><div class="service-industries__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<div class="service-industries__track" aria-label="<?php esc_attr_e( 'Industries we serve', 'aibridze' ); ?>">
			<?php foreach ( $rows as $row ) : ?>
				<article class="service-industries__card"<?php if ( ! empty( $row['image'] ) ) : ?> style="--industry-card-image:url('<?php echo esc_url( $row['image'] ); ?>')"<?php endif; ?>>
					<div class="service-industries__content">
						<h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3>
						<?php if ( ! empty( $row['description'] ) ) : ?><div><?php echo wp_kses_post( wpautop( $row['description'] ) ); ?></div><?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php if ( $has_more && count( $rows ) > 6 ) : ?>
			<button class="service-industries__more" type="button" data-industries-more aria-expanded="false"><span aria-hidden="true">⌄</span><span data-more-label><?php esc_html_e( 'View More', 'aibridze' ); ?></span></button>
		<?php endif; ?>
	</div>
</section>
