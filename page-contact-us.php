<?php
/** Template Name: Contact Us */
get_header();
?>
<main class="contact-page">
	<section class="contact-hero" aria-labelledby="contact-hero-title">
		<div class="contact-hero__wave" aria-hidden="true"></div>
		<div class="contact-hero__inner">
			<h1 id="contact-hero-title"><?php esc_html_e( 'Have an Idea? Let’s Talk.', 'aibridze' ); ?></h1>
			<p><?php esc_html_e( 'Whether you’re exploring an AI opportunity, planning a new product, automating a business process, or looking to modernize an existing solution, we’d love to understand what you’re building.', 'aibridze' ); ?></p>
			<?php get_template_part( 'template-parts/components/button', null, array( 'label' => __( 'Book Free Consultation', 'aibridze' ), 'url' => '#contact-project-form', 'class' => 'button--hero contact-hero__button' ) ); ?>
		</div>
	</section>
	<section class="contact-project" id="contact-project-form" aria-labelledby="contact-project-title">
		<div class="contact-project__inner">
			<aside class="contact-project__visual">
				<h2><?php esc_html_e( 'Your Next Solution Could Start With One Conversation.', 'aibridze' ); ?></h2>
				<p><?php esc_html_e( 'Tell us what you’re trying to solve. We’ll help you explore the right way forward.', 'aibridze' ); ?></p>
				<div class="contact-project__experts"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/contact/experts-grouped.png' ) ); ?>" width="140" height="80" alt="Our consultation experts"></div>
				<img class="contact-project__handwritten" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/contact/consultation-handwritten.png' ) ); ?>" width="441" height="35" alt="Book your free consultation with our experts">
				<div class="contact-project__reviews">
					<span><?php esc_html_e( 'Reviewed on', 'aibridze' ); ?></span>
					<div class="contact-project__review-logos">
						<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/mobile-app-daily-hires.png' ) ); ?>" width="152" height="20" alt="Mobile App Daily">
						<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/clutch-hires.png' ) ); ?>" width="77" height="22" alt="Clutch">
						<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/trustpilot-hires.png' ) ); ?>" width="115" height="29" alt="Trustpilot">
						<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/goodfirms-hires.png' ) ); ?>" width="152" height="23" alt="GoodFirms">
					</div>
				</div>
			</aside>
			<div class="contact-project__form-panel">
				<h2 id="contact-project-title"><?php esc_html_e( 'Empower your vision with us', 'aibridze' ); ?></h2>
				<p><?php esc_html_e( 'Share your requirements, and our experts will get back to you shortly with the right solution and next steps.', 'aibridze' ); ?></p>
				<form class="contact-project__form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
					<input type="hidden" name="action" value="aibridze_consultation">
					<?php wp_nonce_field( 'aibridze_consultation', 'aibridze_consultation_nonce' ); ?>
					<label class="consultation-form__honeypot" aria-hidden="true">Company website<input name="company_website" tabindex="-1" autocomplete="off"></label>
					<label><span><?php esc_html_e( 'Full Name', 'aibridze' ); ?></span><input name="full_name" type="text" placeholder="Enter Your Name" autocomplete="name" required></label>
					<label><span><?php esc_html_e( 'Email Address', 'aibridze' ); ?></span><input name="email" type="email" pattern="[^\s@]+@[^\s@]+\.[^\s@]+" placeholder="Enter Work Email" autocomplete="email" required></label>
					<label><span><?php esc_html_e( 'Designation (Optional)', 'aibridze' ); ?></span><input name="designation" type="text" placeholder="Enter Designation" autocomplete="organization-title"></label>
					<label><span class="screen-reader-text"><?php esc_html_e( 'Your Preferred Budget Range (Optional)', 'aibridze' ); ?></span><select name="budget"><option value=""><?php esc_html_e( 'Your Preferred Budget Range (Optional)', 'aibridze' ); ?></option><option>$5k–$15k</option><option>$15k–$50k</option><option>$50k–$100k</option><option>$100k+</option></select></label>
					<label><span><?php esc_html_e( 'How can we help you?', 'aibridze' ); ?></span><textarea name="message" placeholder="Write Here..." rows="2" required></textarea></label>
					<button type="submit"><span><?php esc_html_e( 'Submit', 'aibridze' ); ?></span></button>
				</form>
				<div class="contact-project__security"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/consultation/contact-nda-shield.svg' ) ); ?>" width="24" height="24" alt=""><span><?php esc_html_e( 'Share with Confidence.', 'aibridze' ); ?> <strong><?php esc_html_e( 'Fully NDA-Protected.', 'aibridze' ); ?></strong></span></div>
			</div>
		</div>
	</section>
	<?php get_template_part( 'template-parts/sections/contact-video-testimonials' ); ?>
	<section class="contact-idea" aria-labelledby="contact-idea-title">
		<div class="contact-idea__banner">
			<div class="contact-idea__content">
				<h2 id="contact-idea-title"><?php esc_html_e( 'Let’s Turn Your Idea Into Something Real.', 'aibridze' ); ?></h2>
				<p><?php esc_html_e( 'Tell us what you’re trying to achieve, and we’ll help you explore the right AI, software, or automation solution for your business.', 'aibridze' ); ?></p>
				<?php get_template_part( 'template-parts/components/white-cta', null, array( 'label' => __( 'Start a Conversation', 'aibridze' ), 'url' => '#contact-project-form' ) ); ?>
			</div>
			<img class="contact-idea__image" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/contact/idea-laptop.png' ) ); ?>" width="443" height="280" alt="Laptop illuminated with blue and orange light">
		</div>
	</section>
	<?php get_template_part( 'template-parts/sections/contact-faq' ); ?>
</main>
<?php get_footer(); ?>
