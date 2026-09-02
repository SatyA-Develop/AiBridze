<?php
/**
 * Consultation modal, form, and dynamic testimonial.
 *
 * @package AIBridze
 */

$testimonials = get_posts(
	array(
		'post_type'      => 'testimonial',
		'posts_per_page' => 4,
		'post_status'    => 'publish',
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	)
);
$status      = isset( $_GET['consultation'] ) ? sanitize_key( wp_unslash( $_GET['consultation'] ) ) : '';
?>
<div class="consultation-modal" data-consultation-modal data-form-status="<?php echo esc_attr( $status ); ?>" aria-hidden="true">
	<div class="consultation-modal__backdrop" data-consultation-close></div>
	<section class="consultation-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="consultation-title" tabindex="-1">
		<div class="consultation-modal__visual">
			<h2><?php esc_html_e( "Let's Build Something Amazing Together", 'aibridze' ); ?></h2>
			<p class="consultation-modal__visual-copy"><?php esc_html_e( 'Book a free consultation and discover the right AI solution for your business.', 'aibridze' ); ?></p>
			<img class="consultation-modal__handwritten" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/consultation/handwritten.png' ) ); ?>" width="255" height="29" alt="Here's what they had to say">

			<div class="consultation-testimonials" data-testimonial-slider>
				<?php foreach ( $testimonials as $testimonial_index => $testimonial ) :
					$person_name  = get_the_title( $testimonial );
					$quote        = wp_strip_all_tags( get_the_content( null, false, $testimonial ) );
					$role         = (string) get_post_meta( $testimonial->ID, '_aibridze_testimonial_role', true );
					$company      = (string) get_post_meta( $testimonial->ID, '_aibridze_testimonial_company', true );
					$logo_id      = (int) get_post_meta( $testimonial->ID, '_aibridze_testimonial_logo_id', true );
					$avatar       = has_post_thumbnail( $testimonial ) ? get_the_post_thumbnail_url( $testimonial, 'thumbnail' ) : get_theme_file_uri( '/assets/images/consultation/testimonial-avatar.png' );
					$company_logo = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : get_theme_file_uri( '/assets/images/consultation/company-logo.png' );
					?>
					<article class="consultation-testimonial<?php echo 0 === $testimonial_index ? ' is-active' : ''; ?>" data-testimonial-slide aria-hidden="<?php echo 0 === $testimonial_index ? 'false' : 'true'; ?>">
						<img class="consultation-testimonial__quote" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/consultation/quote.png' ) ); ?>" width="51" height="30" alt="">
						<p><?php echo esc_html( $quote ); ?></p>
						<footer class="consultation-testimonial__person">
							<img src="<?php echo esc_url( $avatar ); ?>" width="42" height="42" alt="<?php echo esc_attr( $person_name ); ?>">
							<span><strong><?php echo esc_html( $person_name ); ?></strong><small><?php echo esc_html( trim( $role . ( $role && $company ? ', ' : '' ) . $company ) ); ?></small></span>
							<img class="consultation-testimonial__logo" src="<?php echo esc_url( $company_logo ); ?>" alt="<?php echo esc_attr( $company ); ?>">
						</footer>
						<img class="consultation-testimonial__watermark" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/consultation/testimonial-watermark.png' ) ); ?>" width="102" height="134" alt="">
					</article>
				<?php endforeach; ?>
				<div class="consultation-testimonial__steps" aria-label="<?php esc_attr_e( 'Choose testimonial', 'aibridze' ); ?>">
					<?php foreach ( $testimonials as $testimonial_index => $testimonial ) : ?><button type="button" data-testimonial-dot="<?php echo esc_attr( $testimonial_index ); ?>" class="<?php echo 0 === $testimonial_index ? 'is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show testimonial %d', 'aibridze' ), $testimonial_index + 1 ) ); ?>"></button><?php endforeach; ?>
				</div>
			</div>
			<img class="consultation-modal__reviews" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/consultation/review-logos.png' ) ); ?>" width="434" height="104" alt="Reviewed on Mobile App Daily, Clutch, Trustpilot and GoodFirms">
		</div>

		<div class="consultation-modal__form-panel">
			<button class="consultation-modal__close" type="button" data-consultation-close aria-label="<?php esc_attr_e( 'Close consultation form', 'aibridze' ); ?>"><span></span><span></span></button>
			<div class="consultation-modal__form-scroll">
				<h2 id="consultation-title"><?php esc_html_e( 'Tell Us About Your Project', 'aibridze' ); ?></h2>
				<p class="consultation-modal__intro"><?php esc_html_e( 'Share your requirements, and our experts will get back to you with the right solution and next steps.', 'aibridze' ); ?></p>
				<?php if ( 'success' === $status ) : ?>
					<p class="consultation-modal__notice is-success" role="status"><?php esc_html_e( 'Thank you. Your consultation request has been sent.', 'aibridze' ); ?></p>
				<?php elseif ( $status ) : ?>
					<p class="consultation-modal__notice is-error" role="alert"><?php esc_html_e( 'Please check the form and try again. SMTP credentials may still need configuring.', 'aibridze' ); ?></p>
				<?php endif; ?>
				<form class="consultation-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
					<input type="hidden" name="action" value="aibridze_consultation">
					<?php wp_nonce_field( 'aibridze_consultation', 'aibridze_consultation_nonce' ); ?>
					<label class="consultation-form__honeypot" aria-hidden="true">Company website<input name="company_website" tabindex="-1" autocomplete="off"></label>
					<label><span><?php esc_html_e( 'Full Name', 'aibridze' ); ?></span><input name="full_name" type="text" placeholder="Enter Your Name" autocomplete="name" required></label>
					<label><span><?php esc_html_e( 'Email Address', 'aibridze' ); ?></span><input name="email" type="email" placeholder="Enter Work Email" autocomplete="email" required></label>
					<label><span><?php esc_html_e( 'Designation', 'aibridze' ); ?></span><input name="designation" type="text" placeholder="Enter Designation" autocomplete="organization-title"></label>
					<label><span class="screen-reader-text"><?php esc_html_e( 'Your Preferred Budget Range', 'aibridze' ); ?></span><select name="budget"><option value=""><?php esc_html_e( 'Your Preferred Budget Range', 'aibridze' ); ?></option><option>$5k–$15k</option><option>$15k–$50k</option><option>$50k–$100k</option><option>$100k+</option></select></label>
					<label><span><?php esc_html_e( 'How can we help you?', 'aibridze' ); ?></span><textarea name="message" placeholder="Write Here..." rows="2" required></textarea></label>
					<button class="consultation-form__submit" type="submit"><?php esc_html_e( 'Submit', 'aibridze' ); ?></button>
				</form>
			</div>
			<div class="consultation-modal__security"><span aria-hidden="true">🛡️</span> <?php esc_html_e( 'Share with Confidence. Fully NDA-Protected.', 'aibridze' ); ?></div>
		</div>
	</section>
</div>
