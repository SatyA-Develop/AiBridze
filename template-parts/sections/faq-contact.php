<?php
/**
 * FAQ accordion and consultation form.
 *
 * @package AIBridze
 */
$default_faqs = array(
	array( 'Do you develop AI solutions responsibly?', 'Yes. We consider responsible AI practices throughout development, with a focus on data privacy, security, transparency, reliability, and appropriate human oversight.' ),
	array( 'Is data privacy considered during AI development?', 'Yes. We consider data privacy and security from the start of the development process, including how data is collected, processed, stored, accessed, and used by AI systems.' ),
	array( 'Do your AI solutions replace human decision-making?', 'Not necessarily. We design AI to support and augment human decision-making where appropriate, helping teams automate repetitive work, access information faster, and make more informed decisions.' ),
	array( 'How can AiBridze help me identify the right AI solution for my business?', 'We assess your business goals, challenges, workflows, data, and existing technology to identify the right AI opportunities. We then recommend the most suitable approach, architecture, and technologies for your needs.' ),
	array( 'How do we get started?', 'Start by sharing your business challenge, goals, or project requirements with us. We’ll understand your needs, explore suitable solutions, and discuss the best way to move forward.' ),
	array( 'Do you sign Non-Disclosure Agreements (NDAs)?', 'Yes. We can work under NDAs to protect confidential business, technical, and project information shared during our discussions and development process.' ),
	array( 'Can you modernize our existing software?', 'Yes. We can assess your existing software and help modernize, integrate, or enhance it with modern technologies and AI capabilities while aligning improvements with your business requirements.' ),
);
$args = wp_parse_args( $args ?? array(), array(
	'section_id' => '', 'eyebrow' => 'FAQs', 'title' => 'Have questions? Check out the FAQs', 'faqs' => $default_faqs,
	'contact_title' => 'Tell Us About Your Project', 'contact_description' => 'Share your requirements, and our experts will get back to you with the right solution and next steps.', 'submit_label' => 'Submit',
	'full_name_label' => 'Full Name', 'full_name_placeholder' => 'Enter your name', 'email_label' => 'Email', 'email_placeholder' => 'Enter work email',
	'designation_label' => 'Designation', 'designation_placeholder' => 'Enter designation', 'budget_label' => 'Your Preferred Budget Range',
	'budget_options' => array( '$5k–$15k', '$15k–$50k', '$50k–$100k', '$100k+' ), 'message_label' => 'How can we help you?', 'message_placeholder' => 'Write Here...',
	'security_text' => 'Share with Confidence. Fully NDA-Protected.',
) );
$faqs = is_array( $args['faqs'] ) && $args['faqs'] ? $args['faqs'] : $default_faqs;
?>
<section class="faq-contact"<?php echo $args['section_id'] ? ' id="' . esc_attr( $args['section_id'] ) . '"' : ''; ?> aria-labelledby="faq-contact-title">
	<div class="faq-contact__inner">
		<div class="faq-contact__faqs">
			<p class="faq-contact__eyebrow section-callout"><?php echo esc_html( $args['eyebrow'] ); ?></p>
			<h2 id="faq-contact-title"><?php echo esc_html( $args['title'] ); ?></h2>
			<div class="faq-accordion" data-faq-accordion>
				<?php foreach ( $faqs as $index => $faq ) : ?>
					<article class="faq-item<?php echo 0 === $index ? ' is-open' : ''; ?>">
						<h3><button type="button" data-faq-toggle aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>" aria-controls="faq-answer-<?php echo esc_attr( $index ); ?>"><span><?php echo esc_html( $faq[0] ); ?></span><i aria-hidden="true"></i></button></h3>
						<div class="faq-item__answer" id="faq-answer-<?php echo esc_attr( $index ); ?>"<?php echo 0 === $index ? '' : ' hidden'; ?>><p><?php echo esc_html( $faq[1] ); ?></p></div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="faq-contact__form-panel">
			<h2><?php echo esc_html( $args['contact_title'] ); ?></h2>
			<p><?php echo esc_html( $args['contact_description'] ); ?></p>
			<form class="faq-contact__form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="aibridze_consultation">
				<?php wp_nonce_field( 'aibridze_consultation', 'aibridze_consultation_nonce' ); ?>
				<label class="consultation-form__honeypot" aria-hidden="true">Company website<input name="company_website" tabindex="-1" autocomplete="off"></label>
				<label><span><?php echo esc_html( $args['full_name_label'] ); ?></span><input name="full_name" type="text" placeholder="<?php echo esc_attr( $args['full_name_placeholder'] ); ?>" autocomplete="name" required></label>
				<label><span><?php echo esc_html( $args['email_label'] ); ?></span><input name="email" type="email" pattern="[^\s@]+@[^\s@]+\.[^\s@]+" placeholder="<?php echo esc_attr( $args['email_placeholder'] ); ?>" autocomplete="email" required></label>
				<label><span><?php echo esc_html( $args['designation_label'] . ' (' . __( 'Optional', 'aibridze' ) . ')' ); ?></span><input name="designation" type="text" placeholder="<?php echo esc_attr( $args['designation_placeholder'] ); ?>" autocomplete="organization-title"></label>
				<label><span class="screen-reader-text"><?php echo esc_html( $args['budget_label'] . ' (' . __( 'Optional', 'aibridze' ) . ')' ); ?></span><select name="budget"><option value=""><?php echo esc_html( $args['budget_label'] . ' (' . __( 'Optional', 'aibridze' ) . ')' ); ?></option><?php foreach ( array_filter( array_map( 'trim', (array) $args['budget_options'] ) ) as $option ) : ?><option><?php echo esc_html( $option ); ?></option><?php endforeach; ?></select></label>
				<label><span><?php echo esc_html( $args['message_label'] ); ?></span><textarea name="message" placeholder="<?php echo esc_attr( $args['message_placeholder'] ); ?>" rows="2" required></textarea></label>
				<div class="faq-contact__form-actions">
					<?php get_template_part( 'template-parts/components/white-cta', null, array( 'label' => $args['submit_label'], 'submit' => true ) ); ?>
				</div>
			</form>
			<div class="faq-contact__security"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/faq/security-shield.svg' ) ); ?>" width="22" height="22" alt="" aria-hidden="true"> <span><?php echo str_replace( array( 'Fully NDA-Protected', 'Fully NDA Protected' ), array( '<strong>Fully NDA-Protected</strong>', '<strong>Fully NDA Protected</strong>' ), esc_html( $args['security_text'] ) ); ?></span></div>
		</div>
	</div>
</section>
