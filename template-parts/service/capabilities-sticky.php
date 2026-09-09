<?php
/** Sticky desktop / two-row mobile capabilities carousel. */
$rows = is_array( $args['rows'] ?? null ) ? $args['rows'] : array();
if ( ! $rows ) return;
$is_vision = 'vision' === get_post_meta( get_the_ID(), '_aibridze_service_layout', true );
$pages = $is_vision ? (int) ceil( count( $rows ) / 2 ) : (int) ceil( count( $rows ) / 2 );
?>
<section class="service-capabilities<?php echo $is_vision ? ' service-capabilities--vision' : ''; ?>" data-service-capabilities>
	<div class="container service-capabilities__layout">
		<header class="service-capabilities__header">
			<?php if ( $eyebrow = aibridze_service_value( 'capabilities_eyebrow' ) ) : ?><p class="service-capabilities__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title = aibridze_service_value( 'capabilities_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description = aibridze_service_value( 'capabilities_description' ) ) : ?><div class="service-capabilities__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<div class="service-capabilities__viewport">
			<div class="service-capabilities__track">
				<?php if ( $is_vision ) : ?>
					<?php $column_count = (int) ceil( count( $rows ) / 2 ); for ( $column = 0; $column < $column_count; $column++ ) : ?>
						<div class="service-capabilities__column">
							<?php foreach ( array( $column, $column + $column_count ) as $index ) : if ( ! isset( $rows[ $index ] ) ) continue; $row = $rows[ $index ]; ?>
								<article class="service-capabilities__card"><h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3><div><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div></article>
							<?php endforeach; ?>
						</div>
					<?php endfor; ?>
				<?php else : ?>
					<?php foreach ( $rows as $row ) : ?><article class="service-capabilities__card"><h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3><div><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div></article><?php endforeach; ?>
				<?php endif; ?>
			</div>
			<?php if ( $pages > 1 ) : ?><div class="service-capabilities__pagination<?php echo $is_vision ? ' service-capabilities__pagination--arrows' : ''; ?>" role="group" aria-label="<?php esc_attr_e( 'Capability pages', 'aibridze' ); ?>"><?php if ( $is_vision ) : ?><button type="button" data-capability-direction="prev" aria-label="<?php esc_attr_e( 'Previous capabilities', 'aibridze' ); ?>">&#8249;</button><button type="button" data-capability-direction="next" aria-label="<?php esc_attr_e( 'Next capabilities', 'aibridze' ); ?>">&#8250;</button><?php else : ?><?php for ( $page = 0; $page < $pages; $page++ ) : ?><button type="button" class="<?php echo 0 === $page ? 'is-active' : ''; ?>" data-capability-page="<?php echo esc_attr( $page ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show capability page %d', 'aibridze' ), $page + 1 ) ); ?>"></button><?php endfor; ?><?php endif; ?></div><?php endif; ?>
		</div>
	</div>
</section>
