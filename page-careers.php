<?php
/** Template Name: Careers */
get_header();

$career_reasons = array(
	array(
		'title'       => __( 'Learn & Explore With Industry Experts', 'aibridze' ),
		'description' => __( 'Work alongside experienced professionals across AI, software engineering, product development, and emerging technologies. Learn through real projects, collaboration, and hands-on problem-solving.', 'aibridze' ),
		'image'       => get_theme_file_uri( '/assets/images/careers/industry-experts.webp' ),
	),
	array(
		'title'       => __( 'Turn Ideas Into Innovation', 'aibridze' ),
		'description' => __( 'We encourage curiosity, experimentation, and new ideas. Whether you’re improving an existing solution or exploring a new approach, your creativity has room to make an impact.', 'aibridze' ),
		'image'       => get_theme_file_uri( '/assets/images/careers/innovation.webp' ),
	),
	array(
		'title'       => __( 'Build For Global Businesses', 'aibridze' ),
		'description' => __( 'Work on technology solutions designed around real business challenges and diverse customer needs. Gain exposure to projects that broaden your perspective and help you build experience for a global technology landscape.', 'aibridze' ),
		'image'       => get_theme_file_uri( '/assets/images/careers/global-business.webp' ),
	),
);
?>

<main class="careers-page">
	<section class="careers-hero" aria-labelledby="careers-hero-title">
		<div class="careers-hero__inner">
			<h1 id="careers-hero-title"><?php esc_html_e( 'Build What’s Next With AiBridze', 'aibridze' ); ?></h1>
			<p><?php esc_html_e( 'At AiBridze, we bring together AI, software engineering, creativity and problem-solving to build technology that solves real business challenges. Join a team where your ideas matter, your skills keep evolving, and your work contributes to products and solutions used in the real world.', 'aibridze' ); ?></p>
			<?php get_template_part( 'template-parts/components/button', null, array( 'class' => 'button--hero', 'label' => __( 'View Open Opportunities', 'aibridze' ), 'url' => '#open-opportunities' ) ); ?>
		</div>
	</section>

	<section class="careers-reasons" aria-labelledby="careers-reasons-title" data-careers-accordion>
		<div class="careers-reasons__inner">
			<div class="careers-reasons__copy">
				<p class="careers-reasons__eyebrow section-callout"><?php esc_html_e( 'Why Build Your Future With Us?', 'aibridze' ); ?></p>
				<h2 id="careers-reasons-title"><?php esc_html_e( 'Grow With People Who Build What’s Next', 'aibridze' ); ?></h2>
				<p class="careers-reasons__intro"><?php esc_html_e( 'We believe great technology starts with great people. At AiBridze, you’ll work alongside curious minds, experienced professionals, and creative problem-solvers while gaining opportunities to learn, experiment, collaborate, and take ownership of meaningful work.', 'aibridze' ); ?></p>

				<div class="careers-accordion">
					<?php foreach ( $career_reasons as $index => $reason ) : ?>
						<article class="careers-accordion__item<?php echo 0 === $index ? ' is-active' : ''; ?>" data-careers-item>
							<h3><button type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>" aria-controls="career-reason-<?php echo esc_attr( (string) $index ); ?>" data-careers-trigger data-image="<?php echo esc_url( $reason['image'] ); ?>"><span><?php echo esc_html( $reason['title'] ); ?></span><i aria-hidden="true"></i></button></h3>
							<div id="career-reason-<?php echo esc_attr( (string) $index ); ?>" class="careers-accordion__panel"<?php echo 0 === $index ? '' : ' hidden'; ?>><p><?php echo esc_html( $reason['description'] ); ?></p></div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<figure class="careers-reasons__media">
				<img src="<?php echo esc_url( $career_reasons[0]['image'] ); ?>" width="400" height="371" alt="<?php esc_attr_e( 'AiBridze team collaborating', 'aibridze' ); ?>" data-careers-image>
			</figure>
		</div>
	</section>
	<?php
	$opportunities = get_posts(
		array(
			'post_type'      => 'opportunity',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		)
	);
	?>
	<section id="open-opportunities" class="career-openings" aria-labelledby="career-openings-title" data-career-openings>
		<div class="career-openings__inner">
			<header class="career-openings__header">
				<p><?php esc_html_e( 'Find a role where you can make an impact', 'aibridze' ); ?></p>
				<h2 id="career-openings-title"><?php esc_html_e( 'Current Opportunities', 'aibridze' ); ?></h2>
				<div><?php esc_html_e( 'We promise you an inclusive work environment where you’ll love taking on challenges, being challenged, and growing along the way.', 'aibridze' ); ?></div>
			</header>

			<div class="career-openings__search">
				<label class="screen-reader-text" for="career-role-search"><?php esc_html_e( 'Search job opportunities', 'aibridze' ); ?></label>
				<input id="career-role-search" type="search" placeholder="<?php esc_attr_e( 'Find Your Role', 'aibridze' ); ?>" autocomplete="off" data-career-search>
				<span aria-hidden="true"></span>
			</div>

			<div class="career-openings__list" data-career-list>
				<?php foreach ( $opportunities as $opportunity ) :
					$experience = (string) get_post_meta( $opportunity->ID, '_aibridze_opportunity_experience', true );
					$location   = (string) get_post_meta( $opportunity->ID, '_aibridze_opportunity_location', true );
					?>
					<article class="career-opening" data-career-card data-search="<?php echo esc_attr( strtolower( $opportunity->post_title . ' ' . $experience . ' ' . $location ) ); ?>">
						<button type="button" class="career-opening__button" data-career-open="<?php echo esc_attr( (string) $opportunity->ID ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View vacancy details for %s', 'aibridze' ), $opportunity->post_title ) ); ?>">
							<span class="career-opening__content"><strong><?php echo esc_html( $opportunity->post_title ); ?></strong><span><b><?php esc_html_e( 'Experience:', 'aibridze' ); ?></b> <?php echo esc_html( $experience ); ?></span><span><b><?php esc_html_e( 'Location:', 'aibridze' ); ?></b> <?php echo esc_html( $location ); ?></span></span>
							<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/job-arrow.svg' ) ); ?>" width="28" height="28" alt="">
						</button>
						<template data-career-detail="<?php echo esc_attr( (string) $opportunity->ID ); ?>">
							<div data-title><?php echo esc_html( $opportunity->post_title ); ?></div>
							<div data-experience><?php echo esc_html( $experience ); ?></div>
							<div data-location><?php echo esc_html( $location ); ?></div>
							<div data-description><?php echo wp_kses_post( apply_filters( 'the_content', $opportunity->post_content ) ); ?></div>
						</template>
					</article>
				<?php endforeach; ?>
			</div>

			<p class="career-openings__empty" data-career-empty hidden><?php esc_html_e( 'No matching opportunities were found. Try another role or skill.', 'aibridze' ); ?></p>
			<nav class="career-openings__pagination" aria-label="<?php esc_attr_e( 'Opportunity pagination', 'aibridze' ); ?>">
				<button type="button" data-career-prev><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/arrow-left.svg' ) ); ?>" width="20" height="20" alt=""> <?php esc_html_e( 'Previous', 'aibridze' ); ?></button>
				<span data-career-count></span>
				<button type="button" data-career-next><?php esc_html_e( 'Next', 'aibridze' ); ?> <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/arrow-right.svg' ) ); ?>" width="20" height="20" alt=""></button>
			</nav>
		</div>
	</section>

	<section class="career-culture" aria-labelledby="career-culture-title">
		<div class="career-culture__inner">
			<header class="career-culture__header">
				<p><?php esc_html_e( 'Work Culture', 'aibridze' ); ?></p>
				<h2 id="career-culture-title"><?php esc_html_e( 'Setting New Benchmarks for Work Culture', 'aibridze' ); ?></h2>
				<div><?php esc_html_e( 'We believe a great workplace gives people the freedom to learn, contribute, collaborate, and grow. At AiBridze, we focus on creating an environment where people can do meaningful work while continuing to develop personally and professionally.', 'aibridze' ); ?></div>
			</header>
			<div class="career-culture__grid">
				<article class="career-culture__card"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/culture-learning.svg' ) ); ?>" width="50" height="50" alt=""><div><h3><?php esc_html_e( 'Continuous Learning', 'aibridze' ); ?></h3><p><?php esc_html_e( 'Technology never stands still, and neither should we. We encourage continuous learning through new technologies, hands-on projects, knowledge sharing, and opportunities to expand your skills.', 'aibridze' ); ?></p></div></article>
				<article class="career-culture__card"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/culture-growth.svg' ) ); ?>" width="50" height="50" alt=""><div><h3><?php esc_html_e( 'Ownership & Growth', 'aibridze' ); ?></h3><p><?php esc_html_e( 'Take ownership of your work, make decisions, and see your ideas turn into real outcomes. We create opportunities for people to take on new responsibilities and grow with the company.', 'aibridze' ); ?></p></div></article>
				<article class="career-culture__card"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/culture-balance.svg' ) ); ?>" width="50" height="50" alt=""><div><h3><?php esc_html_e( 'Flexibility & Balance', 'aibridze' ); ?></h3><p><?php esc_html_e( 'We value productive work without losing sight of life outside work. Our approach supports flexibility, trust, and a healthy balance between professional and personal commitments.', 'aibridze' ); ?></p></div></article>
				<article class="career-culture__card"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/culture-recognition.svg' ) ); ?>" width="50" height="50" alt=""><div><h3><?php esc_html_e( 'Recognition & Appreciation', 'aibridze' ); ?></h3><p><?php esc_html_e( 'Good work deserves to be recognized. We value the contributions of our people and celebrate the effort, ideas, and impact they bring to the team.', 'aibridze' ); ?></p></div></article>
			</div>
		</div>
	</section>

	<section class="career-resume-cta" aria-labelledby="career-resume-title">
		<div class="career-resume-cta__panel">
			<div class="career-resume-cta__copy">
				<p><?php esc_html_e( 'Didn’t Find the Right Role?', 'aibridze' ); ?></p>
				<h2 id="career-resume-title"><?php esc_html_e( 'We’d Still Love to Hear From You.', 'aibridze' ); ?></h2>
				<div><?php esc_html_e( 'Don’t see an opportunity that matches your skills? Drop your resume with us, and our team will reach out if a relevant opportunity opens up.', 'aibridze' ); ?></div>
				<button type="button" data-career-general-apply><?php esc_html_e( 'Submit Your Resume', 'aibridze' ); ?></button>
			</div>
			<img class="career-resume-cta__person" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/resume-person-hires.png' ) ); ?>" width="370" height="320" alt="<?php esc_attr_e( 'AiBridze team member holding a tablet', 'aibridze' ); ?>">
		</div>
	</section>

	<div class="career-modal" data-career-modal hidden>
		<div class="career-modal__backdrop" data-career-close></div>
		<section class="career-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="career-modal-heading">
			<header class="career-modal__topbar"><h2 id="career-modal-heading" data-modal-heading><?php esc_html_e( 'Vacancy Details', 'aibridze' ); ?></h2><button type="button" data-career-close aria-label="<?php esc_attr_e( 'Close', 'aibridze' ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/careers/modal-close.png' ) ); ?>" width="16" height="16" alt=""></button></header>

			<div class="career-modal__body">
				<div class="career-modal__vacancy" data-vacancy-view>
					<div class="career-modal__vacancy-header"><div><h3 data-vacancy-title></h3><p><strong><?php esc_html_e( 'Experience:', 'aibridze' ); ?></strong> <span data-vacancy-experience></span></p><p><strong><?php esc_html_e( 'Location:', 'aibridze' ); ?></strong> <span data-vacancy-location></span></p></div><button type="button" data-career-apply><?php esc_html_e( 'Apply Now', 'aibridze' ); ?></button></div>
					<div class="career-modal__description"><h4><?php esc_html_e( 'Job Description:', 'aibridze' ); ?></h4><div data-vacancy-description></div></div>
				</div>

				<form class="career-application" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" enctype="multipart/form-data" data-application-form hidden>
					<input type="hidden" name="action" value="aibridze_submit_job_application">
					<?php wp_nonce_field( 'aibridze_job_application', 'job_application_nonce' ); ?>
					<input type="hidden" name="opportunity_id" data-application-opportunity>
					<label class="consultation-form__honeypot" aria-hidden="true">Company website<input name="company_website" tabindex="-1" autocomplete="off"></label>
					<div class="career-application__grid">
						<label><span><?php esc_html_e( 'Full Name', 'aibridze' ); ?><b>*</b></span><input name="full_name" type="text" placeholder="<?php esc_attr_e( 'Your Name', 'aibridze' ); ?>" autocomplete="name" required></label>
						<label><span><?php esc_html_e( 'Email', 'aibridze' ); ?><b>*</b></span><input name="email" type="email" pattern="[^\s@]+@[^\s@]+\.[^\s@]+" placeholder="name@example.com" autocomplete="email" required></label>
						<label class="career-application__wide"><span><?php esc_html_e( 'Applying For Position', 'aibridze' ); ?><b>*</b></span><select data-application-position required><option value="0"><?php esc_html_e( 'General Application', 'aibridze' ); ?></option><?php foreach ( $opportunities as $opportunity ) : ?><option value="<?php echo esc_attr( (string) $opportunity->ID ); ?>"><?php echo esc_html( $opportunity->post_title ); ?></option><?php endforeach; ?></select></label>
						<label><span><?php esc_html_e( 'Phone Number', 'aibridze' ); ?><b>*</b></span><input name="phone" type="tel" placeholder="e.g. 9876543210" autocomplete="tel" required></label>
						<label><span><?php esc_html_e( 'Years of Experience', 'aibridze' ); ?><b>*</b></span><input name="years_experience" type="number" min="0" step="0.5" placeholder="e.g. 5" required></label>
						<label><span><?php esc_html_e( 'Current CTC', 'aibridze' ); ?></span><select name="current_ctc"><option value=""><?php esc_html_e( 'Select', 'aibridze' ); ?></option><option>0–5 LPA</option><option>5–10 LPA</option><option>10–20 LPA</option><option>20+ LPA</option></select></label>
						<label><span><?php esc_html_e( 'Expected CTC', 'aibridze' ); ?></span><select name="expected_ctc"><option value=""><?php esc_html_e( 'Select', 'aibridze' ); ?></option><option>0–5 LPA</option><option>5–10 LPA</option><option>10–20 LPA</option><option>20+ LPA</option></select></label>
						<label class="career-application__wide"><span><?php esc_html_e( 'LinkedIn Profile Link', 'aibridze' ); ?></span><input name="linkedin" type="url" placeholder="<?php esc_attr_e( 'Paste Here…', 'aibridze' ); ?>"></label>
						<label class="career-application__wide career-application__resume"><span><?php esc_html_e( 'Upload Resume', 'aibridze' ); ?><b>*</b></span><input name="resume" type="file" accept=".pdf,.doc,.docx" required><small><?php esc_html_e( 'Supports .pdf, .docx | Max file size 2 MB', 'aibridze' ); ?></small></label>
					</div>
					<label class="career-application__consent"><input name="consent" type="checkbox" value="1" required><span><?php esc_html_e( 'By submitting, you consent to receive communication from our team via email or phone.', 'aibridze' ); ?></span></label>
					<p class="career-application__help"><?php esc_html_e( 'If you are unable to submit your details, then please share your recently updated resume at', 'aibridze' ); ?> <a href="mailto:career@aibridze.com">career@aibridze.com</a></p>
					<p class="career-application__status" data-application-status aria-live="polite"></p>
					<button class="career-application__submit" type="submit"><?php esc_html_e( 'Submit Application', 'aibridze' ); ?></button>
				</form>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
