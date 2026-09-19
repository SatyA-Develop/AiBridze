<?php
/** Template Name: About Us */
get_header();
$social_links = aibridze_social_links();
?>
<section class="about-hero" aria-labelledby="about-hero-title">
	<div class="about-hero__media" aria-hidden="true"></div>
	<div class="container about-hero__inner">
		<div class="about-hero__content">
			<h1 id="about-hero-title">AI Is Complex. Your Journey With It Shouldn’t Be.</h1>
			<p>AiBridze is an AI development company specializing in AI agent development, Generative AI solutions, intelligent automation, and custom software development for startups, growing businesses, and enterprises across the United States, India, Middle East and global markets. We help organizations transform complex ideas into secure, scalable digital products that automate operations, improve decision-making, and accelerate business growth.</p>
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'class' => 'button--hero',
					'label' => __( 'Book You Free Strategy Session', 'aibridze' ),
					'url'   => '#consultation',
				)
			);
			?>
			<div class="about-hero__security"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/faq/security-shield.svg' ) ); ?>" width="20" height="20" alt=""><span><?php esc_html_e( '100% Confidential Consultation with Expert Guidance', 'aibridze' ); ?></span></div>
		</div>
		<div class="about-hero__socials" aria-label="<?php esc_attr_e( 'Social links', 'aibridze' ); ?>">
			<a class="about-hero__social about-hero__social--facebook" href="<?php echo esc_url( $social_links['facebook'] ?: '#' ); ?>" aria-label="Facebook"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/facebook.svg' ) ); ?>" width="8" height="16" alt=""></a>
			<a class="about-hero__social about-hero__social--instagram" href="<?php echo esc_url( $social_links['instagram'] ?: '#' ); ?>" aria-label="Instagram"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/instagram.svg' ) ); ?>" width="12" height="12" alt=""></a>
			<a class="about-hero__social about-hero__social--linkedin" href="<?php echo esc_url( $social_links['linkedin'] ?: '#' ); ?>" aria-label="LinkedIn"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/linkedin.svg' ) ); ?>" width="12" height="12" alt=""></a>
			<a class="about-hero__social about-hero__social--x" href="<?php echo esc_url( $social_links['x'] ?: '#' ); ?>" aria-label="X"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/x.svg' ) ); ?>" width="14" height="14" alt=""></a>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/sections/about-purpose' ); ?>

<?php get_template_part( 'template-parts/sections/about-why' ); ?>

<section class="about-technologies" aria-labelledby="about-technologies-title">
	<div class="about-technologies__panel">
		<h2 id="about-technologies-title"><?php esc_html_e( 'Building the Future with AI & Modern Technologies', 'aibridze' ); ?></h2>
		<p><?php esc_html_e( 'We leverage industry-leading AI models, cloud platforms, and modern development frameworks to build secure, scalable, and production-ready digital solutions.', 'aibridze' ); ?></p>
		<div class="about-technologies__list" aria-label="<?php esc_attr_e( 'Technologies we use', 'aibridze' ); ?>">
			<?php
			$technologies = array(
				array( 'OpenAI', 'tech-openai.svg' ),
				array( 'Claude', 'tech-claude.svg' ),
				array( 'Gemini', 'tech-gemini.svg' ),
				array( 'Python', 'tech-python.svg' ),
				array( 'React', 'tech-react.svg' ),
				array( 'Next.js', 'tech-nextjs.svg' ),
				array( 'Flutter', 'tech-flutter.svg' ),
				array( 'AWS', 'tech-aws.svg' ),
				array( 'Azure', 'tech-azure.svg' ),
			);
			foreach ( $technologies as $technology ) :
				?>
				<div class="about-technologies__pill"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/' . $technology[1] ) ); ?>" width="24" height="24" alt=""><span><?php echo esc_html( $technology[0] ); ?></span></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
$leadership_posts = get_posts(
	array(
		'post_type'      => 'leader',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);
if ( $leadership_posts ) :
?>
<section class="about-leadership" aria-labelledby="about-leadership-title">
	<div class="about-leadership__inner">
		<p class="about-leadership__eyebrow section-callout"><?php esc_html_e( 'Our Leadership', 'aibridze' ); ?></p>
		<h2 id="about-leadership-title"><?php esc_html_e( 'The Vision Behind AiBridze', 'aibridze' ); ?></h2>
		<p class="about-leadership__description"><?php esc_html_e( 'Led by experienced technology leaders, AiBridze combines expertise in AI development, Generative AI, custom software development, mobile and web application development, and digital transformation to help businesses solve complex challenges through intelligent, scalable technology solutions.', 'aibridze' ); ?></p>
		<div class="about-leadership__profiles">
			<?php foreach ( $leadership_posts as $leader ) :
				$name     = get_the_title( $leader );
				$position = (string) get_post_meta( $leader->ID, '_aibridze_leader_position', true );
				$linkedin = (string) get_post_meta( $leader->ID, '_aibridze_leader_linkedin', true );
				?>
				<article class="about-leadership__profile">
					<div class="about-leadership__portrait"><?php echo get_the_post_thumbnail( $leader, 'large', array( 'alt' => $name, 'loading' => 'lazy', 'decoding' => 'async' ) ); ?></div>
					<div class="about-leadership__details">
						<div><h3><?php echo esc_html( $name ); ?></h3><?php if ( $position ) : ?><p><?php echo esc_html( $position ); ?></p><?php endif; ?></div>
						<?php if ( $linkedin ) : ?><a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( sprintf( __( '%s on LinkedIn', 'aibridze' ), $name ) ); ?>"><?php else : ?><span aria-hidden="true"><?php endif; ?>
							<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/linkedin-leader.svg' ) ); ?>" width="40" height="40" alt="">
						<?php echo $linkedin ? '</a>' : '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
endif;
$team_posts = get_posts(
	array(
		'post_type'      => 'team',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);
if ( $team_posts ) :
?>
<section class="about-team" aria-labelledby="about-team-title">
	<div class="about-team__inner">
		<p class="about-team__eyebrow section-callout"><?php esc_html_e( 'Our Core Team', 'aibridze' ); ?></p>
		<h2 id="about-team-title"><?php esc_html_e( 'Different minds. Shared vision. One team.', 'aibridze' ); ?></h2>
		<p class="about-team__description"><?php esc_html_e( 'Our AI engineers, machine learning specialists, software developers, UI/UX designers, cloud architects, and product experts collaborate to build AI-powered solutions, Generative AI applications, mobile and web applications, and custom software that help businesses innovate, automate, and scale with confidence.', 'aibridze' ); ?></p>
		<div class="about-team__grid">
			<?php foreach ( $team_posts as $member ) :
				$name     = get_the_title( $member );
				$position = (string) get_post_meta( $member->ID, '_aibridze_team_position', true );
				?>
				<article class="about-team__member">
					<div class="about-team__portrait"><?php echo get_the_post_thumbnail( $member, 'large', array( 'alt' => $name, 'loading' => 'lazy', 'decoding' => 'async' ) ); ?></div>
					<h3><?php echo esc_html( $name ); ?></h3>
					<p><?php echo esc_html( $position ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php endif; ?>
<section class="about-careers" aria-labelledby="about-careers-title">
	<div class="about-careers__panel">
		<div class="about-careers__copy">
			<h2 id="about-careers-title"><?php esc_html_e( 'Build Technology That Shapes The Future', 'aibridze' ); ?></h2>
			<p><?php esc_html_e( 'Join our team to build AI-powered products, Generative AI applications, AI agents, and enterprise software that solve real-world business challenges.', 'aibridze' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'Explore Careers', 'aibridze' ); ?></a>
		</div>
		<picture class="about-careers__media">
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/careers-team-clear.webp' ) ); ?>" width="2528" height="1686" alt="<?php esc_attr_e( 'AiBridze team collaborating in the office', 'aibridze' ); ?>">
		</picture>
	</div>
</section>

<?php get_template_part( 'template-parts/sections/faq-contact' ); ?>
<?php get_footer(); ?>
