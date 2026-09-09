<?php
/**
 * Homepage hero section.
 *
 * @package AIBridze
 */

$title       = get_theme_mod( 'hero_title', 'Transform Your Business with Custom AI Software Development' );
$description = get_theme_mod( 'hero_description', 'AIBridze develops custom AI-powered software solutions ranging from smart automation to scalable digital platforms. We help businesses accelerate growth, reduce costs and stay ahead in an AI-first world.' );
$button_text = get_theme_mod( 'hero_button_text', 'Book Free Consultation' );
$button_url  = get_theme_mod( 'hero_button_url', '#consultation' );
?>
<?php
$hero_video_path = get_theme_file_path( '/assets/video/hero-ai-technology.mp4' );
$hero_video_url  = add_query_arg( 'ver', (string) filemtime( $hero_video_path ), get_theme_file_uri( '/assets/video/hero-ai-technology.mp4' ) );
?>
<section class="hero" aria-labelledby="hero-title">
	<div class="hero__stage" data-hero-stage>
		<video class="hero__video" data-desktop-src="<?php echo esc_url( $hero_video_url ); ?>" muted loop playsinline preload="none" poster="<?php echo esc_url( get_theme_file_uri( '/assets/images/hero-logistics-port.webp' ) ); ?>" aria-hidden="true">
		</video>
		<img class="hero__mobile-image" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/hero-mobile.webp' ) ); ?>" alt="" aria-hidden="true">
		<div class="container hero__container">
			<div class="hero__content">
			<h1 class="hero__title" id="hero-title"<?php echo 'Transform Your Business with Custom AI Software Development' === $title ? ' aria-label="' . esc_attr( $title ) . '"' : ''; ?>>
				<?php if ( 'Transform Your Business with Custom AI Software Development' === $title ) : ?>
					<span class="hero__title-desktop" aria-hidden="true">
						<span>Transform Your Business</span>
						<span>with Custom AI</span>
						<span>Software Development</span>
					</span>
					<span class="hero__title-mobile" aria-hidden="true">
						<span>Transform Your</span>
						<span>Business with</span>
						<span>Custom AI Software</span>
						<span>Development</span>
					</span>
				<?php else : ?>
					<?php echo esc_html( $title ); ?>
				<?php endif; ?>
			</h1>
			<p class="hero__description"><?php echo esc_html( $description ); ?></p>
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $button_text,
					'url'   => $button_url,
				)
			);
			?>
			</div>

			<img class="hero__review" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/hero-review.png' ) ); ?>" width="234" height="52" alt="Reviewed on Clutch, 5.0 rating">
		</div>
	</div>
</section>
