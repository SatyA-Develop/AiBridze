<?php
/** Template Name: Thank You */
get_header();
$social_links = aibridze_social_links();
?>
<section class="thank-you-hero" aria-labelledby="thank-you-title">
	<div class="container thank-you-hero__inner">
		<img class="thank-you-hero__check"
			src="<?php echo esc_url(get_theme_file_uri('/assets/images/thank-you/success-check.svg')); ?>" width="150"
			height="129" alt="">
		<h1 id="thank-you-title">THANK YOU<span>We’ve Got Your Message.</span></h1>
		<p class="thank-you-hero__description">Your request has been received. Our team will review your requirements and
			connect with you to understand your business goals, challenges, and the right technology approach.</p>
		<p class="thank-you-hero__follow">Follow Us On</p>
		<div class="about-hero__socials" style="align-self: center; display: flex;padding-bottom: 0;"
			aria-label="<?php esc_attr_e('Social links', 'aibridze'); ?>">
			<a class="about-hero__social about-hero__social--facebook"
				href="<?php echo esc_url($social_links['facebook'] ?: '#'); ?>" aria-label="Facebook"><img
					src="<?php echo esc_url(get_theme_file_uri('/assets/images/footer/facebook.svg')); ?>" width="8" height="16"
					alt=""></a>
			<a class="about-hero__social about-hero__social--instagram"
				href="<?php echo esc_url($social_links['instagram'] ?: '#'); ?>" aria-label="Instagram"><img
					src="<?php echo esc_url(get_theme_file_uri('/assets/images/footer/instagram.svg')); ?>" width="12" height="12"
					alt=""></a>
			<a class="about-hero__social about-hero__social--linkedin"
				href="<?php echo esc_url($social_links['linkedin'] ?: '#'); ?>" aria-label="LinkedIn"><img
					src="<?php echo esc_url(get_theme_file_uri('/assets/images/footer/linkedin.svg')); ?>" width="12" height="12"
					alt=""></a>
			<a class="about-hero__social about-hero__social--x" href="<?php echo esc_url($social_links['x'] ?: '#'); ?>"
				aria-label="X"><img src="<?php echo esc_url(get_theme_file_uri('/assets/images/footer/x.svg')); ?>" width="14"
					height="14" alt=""></a>
		</div>
	</div>
</section>
<?php
get_template_part('template-parts/sections/about-purpose');
get_template_part('template-parts/sections/about-why');
get_footer();
