<?php
/**
 * Scroll-driven success story call to action.
 *
 * @package AIBridze
 */
$success_video_path = get_theme_file_path( '/assets/video/can_you_color_similar_to_F_.mp4' );
$success_video_url  = add_query_arg(
	'ver',
	(string) filemtime( $success_video_path ),
	get_theme_file_uri( '/assets/video/can_you_color_similar_to_F_.mp4' )
);
?>
<section class="success-cta" data-success-cta aria-labelledby="success-cta-title">
	<div class="success-cta__card">
		<div class="success-cta__media" aria-hidden="true">
			<img class="success-cta__mobile-image" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/success-growth-mobile.png' ) ); ?>" width="1110" height="663" loading="lazy" decoding="async" alt="">
			<video class="success-cta__video" data-success-cta-video muted playsinline preload="none">
				<source data-src="<?php echo esc_url( $success_video_url ); ?>" type="video/mp4">
			</video>
		</div>

		<div class="success-cta__content">
			<h2 class="success-cta__title" id="success-cta-title">Ready to Build the Next Success Story?</h2>
			<p class="success-cta__description">Whether you're a startup or an established business, we build AI-powered software and digital solutions that drive innovation, improve efficiency, and accelerate growth.</p>

			<div class="success-cta__stats" aria-label="AIBridze delivery statistics">
				<div class="success-cta__stat">
					<strong>30+</strong>
					<span>Projects<br>Delivered</span>
				</div>
				<div class="success-cta__stat">
					<strong>24/7</strong>
					<span>Development<br>Support</span>
				</div>
			</div>

			<a class="success-cta__button" href="#consultation" data-consultation-open>
				<span>Let's Build Together</span>
				<span class="success-cta__arrow" aria-hidden="true"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/success-cta-arrow.svg' ) ); ?>" width="12" height="12" alt=""></span>
			</a>
		</div>
	</div>
</section>
