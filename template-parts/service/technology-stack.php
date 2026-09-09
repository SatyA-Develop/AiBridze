<?php
/** Reusable, editor-managed service technology stack. */
$rows = is_array( $args['rows'] ?? null ) ? $args['rows'] : array();
if ( ! $rows ) return;
$groups = array();
foreach ( $rows as $row ) {
	$group = trim( (string) ( $row['group'] ?? '' ) );
	if ( ! $group ) continue;
	$groups[ $group ][] = $row;
}
if ( ! $groups ) return;
$layout = (string) get_post_meta( get_the_ID(), '_aibridze_service_layout', true );
$tabbed = in_array( $layout, array( 'generative', 'vision' ), true );
?>
<section class="service-tech-stack<?php echo $tabbed ? ' service-tech-stack--tabs' : ''; ?>"<?php echo $tabbed ? ' data-service-section' : ''; ?>>
	<div class="service-tech-stack__inner">
		<?php if ( $eyebrow = aibridze_service_value( 'technology_eyebrow' ) ) : ?><p class="service-tech-stack__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
		<?php if ( $title = aibridze_service_value( 'technology_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
		<?php if ( $description = aibridze_service_value( 'technology_description' ) ) : ?><p class="service-tech-stack__description"><?php echo esc_html( $description ); ?></p><?php endif; ?>
		<?php if ( 'vision' === $layout ) : ?>
			<div class="service-tech-stack__vision-shell">
				<div class="service-tech-stack__tabs" role="tablist" aria-label="Technology categories">
					<?php $group_index = 0; foreach ( $groups as $group => $items ) : $tab_id = 'technology-' . sanitize_title( $group ); ?>
						<button type="button" class="service-tech-stack__tab<?php echo 0 === $group_index ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo 0 === $group_index ? 'true' : 'false'; ?>" data-service-tab="<?php echo esc_attr( $tab_id ); ?>"><span><?php echo esc_html( $group ); ?></span><span aria-hidden="true">→</span></button>
					<?php $group_index++; endforeach; ?>
				</div>
				<div class="service-tech-stack__panels">
					<?php $group_index = 0; foreach ( $groups as $group => $items ) : $tab_id = 'technology-' . sanitize_title( $group ); ?>
						<div class="service-tech-stack__panel<?php echo 0 === $group_index ? '' : ' is-hidden'; ?>" data-service-panel="<?php echo esc_attr( $tab_id ); ?>">
							<div class="service-tech-stack__logo-grid"><?php foreach ( $items as $item ) : ?><article class="service-tech-stack__logo-card"><?php if ( ! empty( $item['image'] ) ) : ?><img src="<?php echo esc_url( $item['image'] ); ?>" alt="" loading="lazy"><?php endif; ?><span><?php echo esc_html( $item['title'] ?? '' ); ?></span></article><?php endforeach; ?></div>
						</div>
					<?php $group_index++; endforeach; ?>
				</div>
			</div>
			<div class="service-tech-stack__vision-mobile">
				<?php $group_index = 0; foreach ( $groups as $group => $items ) : ?><details<?php echo 0 === $group_index ? ' open' : ''; ?>><summary><span><?php echo esc_html( $group ); ?></span><span aria-hidden="true"></span></summary><div class="service-tech-stack__logo-grid"><?php foreach ( $items as $item ) : ?><article class="service-tech-stack__logo-card"><?php if ( ! empty( $item['image'] ) ) : ?><img src="<?php echo esc_url( $item['image'] ); ?>" alt="" loading="lazy"><?php endif; ?><span><?php echo esc_html( $item['title'] ?? '' ); ?></span></article><?php endforeach; ?></div></details><?php $group_index++; endforeach; ?>
			</div>
		<?php elseif ( $tabbed ) : ?>
			<div class="service-tech-stack__tabs" role="tablist" aria-label="Technology categories">
				<?php $group_index = 0; foreach ( $groups as $group => $items ) : $tab_id = 'technology-' . sanitize_title( $group ); ?>
					<button type="button" class="service-tech-stack__tab<?php echo 0 === $group_index ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo 0 === $group_index ? 'true' : 'false'; ?>" data-service-tab="<?php echo esc_attr( $tab_id ); ?>"><?php echo esc_html( $group ); ?></button>
				<?php $group_index++; endforeach; ?>
			</div>
			<div class="service-tech-stack__panels">
				<?php $group_index = 0; foreach ( $groups as $group => $items ) : $tab_id = 'technology-' . sanitize_title( $group ); ?>
					<div class="service-tech-stack__panel<?php echo 0 === $group_index ? '' : ' is-hidden'; ?>" data-service-panel="<?php echo esc_attr( $tab_id ); ?>">
						<div class="service-tech-stack__logo-grid">
							<?php foreach ( $items as $item ) : ?>
								<article class="service-tech-stack__logo-card">
									<div class="service-tech-stack__logo-tile"><?php if ( ! empty( $item['image'] ) ) : ?><img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ?? '' ); ?>" loading="lazy"><?php endif; ?></div>
									<span><?php echo esc_html( $item['title'] ?? '' ); ?></span>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
				<?php $group_index++; endforeach; ?>
			</div>
		<?php else : ?>
		<div class="service-tech-stack__groups">
			<?php foreach ( $groups as $group => $items ) : ?>
				<div class="service-tech-stack__group">
					<h3><?php echo esc_html( $group ); ?></h3>
					<div class="service-tech-stack__items" tabindex="0" aria-label="<?php echo esc_attr( $group ); ?>">
						<?php foreach ( $items as $item ) : ?><div class="service-tech-stack__item">
							<?php if ( ! empty( $item['image'] ) ) : ?><img src="<?php echo esc_url( $item['image'] ); ?>" alt="" loading="lazy"><?php endif; ?>
							<span><?php echo esc_html( $item['title'] ?? '' ); ?></span>
						</div><?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
