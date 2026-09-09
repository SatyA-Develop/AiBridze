<?php
/** Reusable Service Expertise tab section. */
$rows = is_array( $args['rows'] ?? null ) ? array_values( $args['rows'] ) : array();
if ( ! $rows ) return;
$check_icon = get_theme_file_uri( '/assets/images/services/expertise-check.png' );
$layout = get_post_meta( get_the_ID(), '_aibridze_service_layout', true );

if ( in_array( $layout, array( 'voice', 'vision' ), true ) ) :
?>
<section class="service-expertise service-expertise--voice service-expertise--<?php echo esc_attr( $layout ); ?>" data-service-section>
	<div class="container service-expertise--voice__layout">
		<header class="service-expertise__header">
			<?php if ( $eyebrow = aibridze_service_value( 'expertise_eyebrow' ) ) : ?><p class="service-expertise__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title = aibridze_service_value( 'expertise_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description = aibridze_service_value( 'expertise_description' ) ) : ?><div class="service-expertise__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<div class="service-expertise--voice__accordion">
			<?php foreach ( $rows as $index => $row ) : ?>
			<details class="service-expertise--voice__item"<?php echo 0 === $index ? ' open' : ''; ?>>
				<summary><span><?php echo esc_html( $row['title'] ?? '' ); ?></span><span aria-hidden="true"></span></summary>
				<div class="service-expertise--voice__answer"><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php
return;
endif;

if ( 'rag' === $layout ) :
?>
<section class="service-expertise service-expertise--rag" data-service-section>
	<div class="container">
		<header class="service-expertise__header">
			<?php if ( $eyebrow = aibridze_service_value( 'expertise_eyebrow' ) ) : ?><p class="service-expertise__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title = aibridze_service_value( 'expertise_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description = aibridze_service_value( 'expertise_description' ) ) : ?><div class="service-expertise__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<div class="service-expertise--rag__viewport">
			<div class="service-expertise--rag__track">
				<?php foreach ( $rows as $row ) : ?>
				<article class="service-expertise--rag__card" style="--expertise-check:url('<?php echo esc_url( $check_icon ); ?>')">
					<h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3>
					<div><?php echo wp_kses_post( $row['description'] ?? '' ); ?></div>
				</article>
				<?php endforeach; ?>
			</div>
			<button class="service-expertise--rag__arrow is-prev" type="button" data-rag-expertise-slide="prev" aria-label="<?php esc_attr_e( 'Previous service', 'aibridze' ); ?>"></button>
			<button class="service-expertise--rag__arrow is-next" type="button" data-rag-expertise-slide="next" aria-label="<?php esc_attr_e( 'Next service', 'aibridze' ); ?>"></button>
		</div>
	</div>
</section>
<?php
return;
endif;
?>
<section class="service-expertise" data-service-section>
	<div class="container">
		<header class="service-expertise__header">
			<?php if ( $eyebrow = aibridze_service_value( 'expertise_eyebrow' ) ) : ?><p class="service-expertise__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title = aibridze_service_value( 'expertise_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description = aibridze_service_value( 'expertise_description' ) ) : ?><div class="service-expertise__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<img class="service-expertise__pattern" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/services/expertise-pattern.png' ) ); ?>" alt="" loading="lazy">
		<div class="service-expertise__layout">
			<div class="service-expertise__tabs" role="tablist" aria-label="<?php echo esc_attr( aibridze_service_value( 'expertise_title', __( 'Service expertise', 'aibridze' ) ) ); ?>">
				<?php foreach ( $rows as $index => $row ) : $id = 'expertise-' . $index; ?>
				<button type="button" role="tab" aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>" class="service-expertise__tab<?php echo 0 === $index ? ' is-active' : ''; ?>" data-service-tab="<?php echo esc_attr( $id ); ?>"><span><?php echo esc_html( $row['title'] ?? '' ); ?></span><span aria-hidden="true">→</span></button>
				<?php endforeach; ?>
			</div>
			<div class="service-expertise__panels">
				<?php foreach ( $rows as $index => $row ) : $id = 'expertise-' . $index; ?>
				<div class="service-expertise__panel<?php echo 0 === $index ? '' : ' is-hidden'; ?>" role="tabpanel" data-service-panel="<?php echo esc_attr( $id ); ?>" style="--expertise-check:url('<?php echo esc_url( $check_icon ); ?>')"><?php echo wp_kses_post( $row['description'] ?? '' ); ?></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
