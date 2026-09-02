<?php
/** Contact page FAQ accordion. */
$contact_faqs = array(
	array( 'Do you develop AI solutions responsibly?', 'Yes. We consider responsible AI practices throughout development, with a focus on data privacy, security, transparency, reliability, and appropriate human oversight.' ),
	array( 'Is data privacy considered during AI development?', 'Yes. We consider data privacy and security from the start of the development process, including how data is collected, processed, stored, accessed, and used by AI systems.' ),
	array( 'Do your AI solutions replace human decision-making?', 'Not necessarily. We design AI to support and augment human decision-making where appropriate, helping teams automate repetitive work, access information faster, and make more informed decisions.' ),
	array( 'How can AiBridze help me identify the right AI solution for my business?', 'We assess your business goals, challenges, workflows, data, and existing technology to identify the right AI opportunities. We then recommend the most suitable approach, architecture, and technologies for your needs.' ),
	array( 'How do we get started?', 'Start by sharing your business challenge, goals, or project requirements with us. We’ll understand your needs, explore suitable solutions, and discuss the best way to move forward.' ),
	array( 'Do you sign Non-Disclosure Agreements (NDAs)?', 'Yes. We can work under NDAs to protect confidential business, technical, and project information shared during our discussions and development process.' ),
	array( 'Can you modernize our existing software?', 'Yes. We can assess your existing software and help modernize, integrate, or enhance it with modern technologies and AI capabilities while aligning improvements with your business requirements.' ),
);
?>
<section class="contact-faq" aria-labelledby="contact-faq-title">
	<header class="contact-faq__header">
		<p><?php esc_html_e( 'FAQs', 'aibridze' ); ?></p>
		<h2 id="contact-faq-title"><?php esc_html_e( 'Have questions? Check out the FAQs', 'aibridze' ); ?></h2>
	</header>
	<div class="contact-faq__accordion" data-faq-accordion>
		<?php foreach ( $contact_faqs as $index => $faq ) : ?>
			<article class="faq-item<?php echo 0 === $index ? ' is-open' : ''; ?>">
				<h3><button type="button" data-faq-toggle aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>" aria-controls="contact-faq-answer-<?php echo esc_attr( $index ); ?>"><span><?php echo esc_html( $faq[0] ); ?></span><i aria-hidden="true"></i></button></h3>
				<div class="faq-item__answer" id="contact-faq-answer-<?php echo esc_attr( $index ); ?>"<?php echo 0 === $index ? '' : ' hidden'; ?>><p><?php echo esc_html( $faq[1] ); ?></p></div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
