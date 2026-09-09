<?php
$rows = aibridze_service_value( 'faqs', array() );
if ( ! $rows ) return;
?>
<section class="service-faq" aria-labelledby="service-faq-title">
	<div class="container">
		<header class="service-faq__header">
			<p class="service-kicker section-callout"><?php echo esc_html( aibridze_service_value( 'faqs_eyebrow', 'FAQs' ) ); ?></p>
			<h2 id="service-faq-title"><?php echo esc_html( aibridze_service_value( 'faqs_title', __( 'Have questions?Check out the FAQs', 'aibridze' ) ) ); ?></h2>
		</header>
		<div class="service-faq__list">
			<?php foreach ( $rows as $index => $row ) : ?>
				<details class="service-faq__item"<?php echo 0 === $index ? ' open' : ''; ?>>
					<summary><span><?php echo esc_html( $row['title'] ); ?></span><span class="service-faq__icon" aria-hidden="true"></span></summary>
					<div class="service-faq__answer"><?php echo wp_kses_post( wpautop( $row['description'] ) ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
