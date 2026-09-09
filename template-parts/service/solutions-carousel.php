<?php
/** Reusable, editor-managed service solutions carousel. */
$rows = is_array( $args['rows'] ?? null ) ? $args['rows'] : array();
if ( ! $rows ) return;
$eyebrow = aibridze_service_value( 'solutions_eyebrow' );
$title = aibridze_service_value( 'solutions_title' );
$description = aibridze_service_value( 'solutions_description' );
$layout = get_post_meta( get_the_ID(), '_aibridze_service_layout', true );
?>
<section class="service-solutions<?php echo 'voice' === $layout ? ' service-solutions--voice' : ''; ?>" data-service-section>
	<div class="container">
		<header class="service-solutions__header">
			<div>
				<?php if ( $eyebrow ) : ?><p class="service-solutions__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
				<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
				<?php if ( $description ) : ?><div class="service-solutions__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
			</div>
			<div class="service-solutions__controls" aria-label="<?php esc_attr_e( 'Solution carousel controls', 'aibridze' ); ?>">
				<button class="is-prev" type="button" data-service-slide="prev" aria-label="<?php esc_attr_e( 'Previous solution', 'aibridze' ); ?>"></button>
				<button class="is-next" type="button" data-service-slide="next" aria-label="<?php esc_attr_e( 'Next solution', 'aibridze' ); ?>"></button>
			</div>
		</header>
		<div class="service-solutions__track service-group__grid">
			<?php foreach ( $rows as $index => $row ) : ?>
			<article class="service-solutions__card service-card<?php echo 'vision' === $layout && 0 === $index ? ' is-active' : ''; ?>" tabindex="0">
				<?php if ( 'voice' === $layout ) : ?><div class="service-solutions__copy"><?php endif; ?>
				<?php if ( ! empty( $row['image'] ) ) : ?><span class="service-solutions__icon"><img src="<?php echo esc_url( $row['image'] ); ?>" alt="" loading="lazy"></span><?php endif; ?>
				<h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3>
				<div><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div>
				<?php if ( 'voice' === $layout ) : ?></div><?php endif; ?>
				<?php if ( 'voice' === $layout && ! empty( $row['image'] ) ) : ?><img class="service-solutions__photo" src="<?php echo esc_url( $row['image'] ); ?>" alt="" loading="lazy"><?php endif; ?>
			</article>
			<?php endforeach; ?>
		</div>
		<div class="service-solutions__controls service-solutions__controls--mobile" aria-label="<?php esc_attr_e( 'Solution carousel controls', 'aibridze' ); ?>">
			<button class="is-prev" type="button" data-service-slide="prev" aria-label="<?php esc_attr_e( 'Previous solution', 'aibridze' ); ?>"></button>
			<button class="is-next" type="button" data-service-slide="next" aria-label="<?php esc_attr_e( 'Next solution', 'aibridze' ); ?>"></button>
		</div>
	</div>
</section>
