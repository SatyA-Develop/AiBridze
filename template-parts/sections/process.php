<?php
/**
 * Scroll-driven process section.
 *
 * @package AIBridze
 */

$process_steps = apply_filters(
	'aibridze_process_steps',
	array(
		array(
			'title'       => 'Discover & Strategize',
			'description' => 'We analyze your business goals, challenges, and market opportunities to create a tailored digital and AI strategy for long-term growth.',
			'image'       => '/assets/images/process/step-1.jpg',
		),
		array(
			'title'       => 'Design & Architect',
			'description' => 'We design intuitive user experiences and scalable software architectures that power high-performance digital solutions.',
			'image'       => '/assets/images/process/step-2.jpg',
		),
		array(
			'title'       => 'Test & Optimise',
			'description' => 'We rigorously test every feature to ensure secure, reliable, and high-performing software across devices and user scenarios.',
			'image'       => '/assets/images/process/step-3.jpg',
		),
		array(
			'title'       => 'Develop & Integrate',
			'description' => 'We build AI-powered applications, custom software, and scalable APIs, seamlessly integrating them into your digital ecosystem.',
			'image'       => '/assets/images/process/step-4.jpg',
		),
	)
);
?>
<section class="process-section" data-process-section aria-labelledby="process-title">
	<div class="process-section__sticky">
		<div class="process-section__layout">
			<header class="process-section__heading">
				<p>Our Process</p>
				<h2 id="process-title">How We Bring Ideas to Life</h2>
			</header>

			<div class="process-section__viewport">
				<div class="process-section__track" data-process-track>
					<?php foreach ( $process_steps as $index => $step ) : ?>
						<article class="process-card">
							<span class="process-card__step">Step <?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<h3><?php echo esc_html( $step['title'] ); ?></h3>
							<p><?php echo esc_html( $step['description'] ); ?></p>
							<div class="process-card__media">
								<img data-process-image src="<?php echo esc_url( get_theme_file_uri( $step['image'] ) ); ?>" width="280" height="219" alt="">
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
