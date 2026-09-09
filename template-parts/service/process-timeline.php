<?php
/** Responsive, editor-managed service development process. */
$rows = is_array( $args['rows'] ?? null ) ? $args['rows'] : array();
if ( ! $rows ) return;
$layout = get_post_meta( get_the_ID(), '_aibridze_service_layout', true );
$is_rag = 'rag' === $layout;
$is_two_row_mobile = in_array( $layout, array( 'chatbot', 'automation' ), true );
$mobile_pages = $is_two_row_mobile ? (int) ceil( count( $rows ) / 2 ) : count( $rows );

if ( 'vision' === $layout ) :
?>
<section class="service-process service-process--vision" data-vision-process style="--vision-step-count:<?php echo esc_attr( count( $rows ) ); ?>">
	<div class="service-process--vision__sticky">
		<div class="container">
			<header class="service-process__header">
				<?php if ( $eyebrow = aibridze_service_value( 'process_eyebrow' ) ) : ?><p class="service-process__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
				<?php if ( $title = aibridze_service_value( 'process_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
				<?php if ( $description = aibridze_service_value( 'process_description' ) ) : ?><div class="service-process__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
			</header>
			<div class="service-process--vision__timeline" aria-label="<?php echo esc_attr( $title ?: __( 'Computer Vision development process', 'aibridze' ) ); ?>">
				<div class="service-process--vision__rail" aria-hidden="true"><span></span></div>
				<?php foreach ( $rows as $index => $row ) : ?>
				<article class="service-process--vision__step<?php echo 0 === $index ? ' is-active is-reached' : ''; ?>" data-vision-step="<?php echo esc_attr( $index ); ?>">
					<h3><span><?php echo esc_html( $index + 1 ); ?>.</span> <?php echo esc_html( $row['title'] ?? '' ); ?></h3>
					<span class="service-process--vision__node" aria-hidden="true"></span>
					<div><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?php
return;
endif;

if ( 'voice' === $layout ) :
?>
<section class="service-process service-process--voice" data-service-section>
	<div class="container">
		<header class="service-process__header">
			<?php if ( $eyebrow = aibridze_service_value( 'process_eyebrow' ) ) : ?><p class="service-process__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title = aibridze_service_value( 'process_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description = aibridze_service_value( 'process_description' ) ) : ?><div class="service-process__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<div class="service-process--voice__track service-group__grid">
			<?php foreach ( $rows as $index => $row ) : ?>
			<article class="service-process--voice__step service-card<?php echo 0 === $index ? ' is-active' : ''; ?>">
				<span class="service-process--voice__number"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
				<h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3>
				<div><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div>
			</article>
			<?php endforeach; ?>
		</div>
		<div class="service-process--voice__controls" aria-label="<?php esc_attr_e( 'Voice AI process controls', 'aibridze' ); ?>">
			<button class="is-prev" type="button" data-service-slide="prev" aria-label="<?php esc_attr_e( 'Previous step', 'aibridze' ); ?>"></button>
			<button class="is-next" type="button" data-service-slide="next" aria-label="<?php esc_attr_e( 'Next step', 'aibridze' ); ?>"></button>
		</div>
	</div>
</section>
<?php
return;
endif;

if ( $is_rag ) :
?>
<section class="service-process service-process--rag" data-service-process>
	<div class="container">
		<header class="service-process__header">
			<?php if ( $eyebrow = aibridze_service_value( 'process_eyebrow' ) ) : ?><p class="service-process__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title = aibridze_service_value( 'process_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description = aibridze_service_value( 'process_description' ) ) : ?><div class="service-process__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<div class="service-process--rag__steps">
			<?php foreach ( $rows as $index => $row ) : $number = str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ); ?>
			<article class="service-process--rag__step<?php echo 0 === $index ? ' is-active' : ''; ?>">
				<button type="button" data-rag-process-step aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>">
					<span class="service-process--rag__number"><?php echo esc_html( $number ); ?></span>
					<h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3>
				</button>
				<div class="service-process--rag__copy"><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php
return;
endif;
?>
<section class="service-process" data-service-process>
	<div class="container">
		<header class="service-process__header">
			<?php if ( $eyebrow = aibridze_service_value( 'process_eyebrow' ) ) : ?><p class="service-process__eyebrow section-callout"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title = aibridze_service_value( 'process_title' ) ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description = aibridze_service_value( 'process_description' ) ) : ?><div class="service-process__intro"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
		</header>
		<div class="service-process__desktop">
			<img class="service-process__line" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/services/agent-process-timeline.png' ) ); ?>" alt="" loading="lazy">
			<div class="service-process__steps">
				<?php foreach ( $rows as $index => $row ) :
					$position = ( ( $index + 0.5 ) / count( $rows ) ) * 100;
				?><article class="service-process__step service-process__step--<?php echo 0 === $index % 2 ? 'top' : 'bottom'; ?>" style="--step-position:<?php echo esc_attr( $position ); ?>%">
					<img class="service-process__pointer" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/services/agent-process-arrow-' . ( 0 === $index % 2 ? 'up' : 'down' ) . '.png' ) ); ?>" alt="">
					<div class="service-process__copy"><?php if ( ! empty( $row['image'] ) ) : ?><span class="service-process__icon"><img src="<?php echo esc_url( $row['image'] ); ?>" alt="" loading="lazy"></span><?php endif; ?><span class="service-process__number"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3><div><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div></div>
				</article><?php endforeach; ?>
			</div>
		</div>
		<div class="service-process__mobile">
			<div class="service-process__track"><?php foreach ( $rows as $index => $row ) : ?><article class="service-process__card"><?php if ( ! empty( $row['image'] ) ) : ?><span class="service-process__icon"><img src="<?php echo esc_url( $row['image'] ); ?>" alt="" loading="lazy"></span><?php endif; ?><span class="service-process__step-label" data-step="<?php echo esc_attr( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>">Step <?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h3><?php echo esc_html( $row['title'] ?? '' ); ?></h3><div><?php echo wp_kses_post( wpautop( $row['description'] ?? '' ) ); ?></div></article><?php endforeach; ?></div>
			<div class="service-process__pagination" role="group" aria-label="<?php esc_attr_e( 'Development process pages', 'aibridze' ); ?>"><?php for ( $page = 0; $page < $mobile_pages; $page++ ) : ?><button type="button" class="<?php echo 0 === $page ? 'is-active' : ''; ?>" data-process-page="<?php echo esc_attr( $page ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show process page %d', 'aibridze' ), $page + 1 ) ); ?>"></button><?php endfor; ?></div>
		</div>
	</div>
</section>
