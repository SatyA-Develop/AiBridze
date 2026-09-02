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
					'label' => __( 'Book Free Consultation', 'aibridze' ),
					'url'   => '#consultation',
				)
			);
			?>
			<div class="about-hero__security"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/security-shield.png' ) ); ?>" width="20" height="20" alt=""><span><?php esc_html_e( '100% Confidential Consultation with Expert Guidance', 'aibridze' ); ?></span></div>
		</div>
		<div class="about-hero__socials" aria-label="<?php esc_attr_e( 'Social links', 'aibridze' ); ?>">
			<a class="about-hero__social about-hero__social--facebook" href="<?php echo esc_url( $social_links['facebook'] ?: '#' ); ?>" aria-label="Facebook"><span aria-hidden="true">f</span></a>
			<a class="about-hero__social about-hero__social--instagram" href="<?php echo esc_url( $social_links['instagram'] ?: '#' ); ?>" aria-label="Instagram"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/instagram.png' ) ); ?>" width="12" height="12" alt=""></a>
			<a class="about-hero__social about-hero__social--linkedin" href="<?php echo esc_url( $social_links['linkedin'] ?: '#' ); ?>" aria-label="LinkedIn"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/linkedin.png' ) ); ?>" width="12" height="12" alt=""></a>
			<a class="about-hero__social about-hero__social--x" href="<?php echo esc_url( $social_links['x'] ?: '#' ); ?>" aria-label="X"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/footer/x.png' ) ); ?>" width="14" height="14" alt=""></a>
		</div>
	</div>
</section>

<section class="about-purpose" aria-label="<?php esc_attr_e( 'Our mission, vision and impact', 'aibridze' ); ?>">
	<div class="container about-purpose__grid">
		<article class="about-purpose__card about-purpose__card--mission">
			<h2><?php esc_html_e( 'Mission', 'aibridze' ); ?></h2>
			<p><?php esc_html_e( 'To help businesses solve complex challenges through AI development, intelligent automation, and custom software solutions that improve efficiency, accelerate innovation, and create measurable business value.', 'aibridze' ); ?></p>
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/mission-graph.png' ) ); ?>" width="427" height="225" alt="">
		</article>

		<article class="about-purpose__card about-purpose__card--vision">
			<h2><?php esc_html_e( 'Vision', 'aibridze' ); ?></h2>
			<p><?php esc_html_e( 'To become a globally trusted AI development company, empowering businesses through Generative AI, AI agents, intelligent automation, and scalable solutions that shape the future of digital transformation.', 'aibridze' ); ?></p>
		</article>

		<div class="about-purpose__impact">
			<article class="about-purpose__stats">
				<h2><?php esc_html_e( 'We don’t just build technology. We build partnerships that create lasting impact.', 'aibridze' ); ?></h2>
				<div class="about-purpose__stats-grid">
					<div><strong>15+</strong><span><?php esc_html_e( 'Years of Industry Experience', 'aibridze' ); ?></span></div>
					<div><strong>5+</strong><span><?php esc_html_e( 'Countries Served Globally', 'aibridze' ); ?></span></div>
				</div>
			</article>

			<article class="about-purpose__review">
				<p><?php esc_html_e( 'Behind every number is a challenge solved, an idea brought to life, and real business value created.', 'aibridze' ); ?></p>
				<img class="about-purpose__hand" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/partnership-hand.png' ) ); ?>" width="193" height="269" alt="">
				<img class="about-purpose__clutch" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/clutch-review.png' ) ); ?>" width="209" height="46" alt="Reviewed on Clutch, 5.0 rating">
			</article>
		</div>
	</div>
</section>

<section class="about-why" data-about-why aria-labelledby="about-why-title">
	<div class="about-why__inner">
		<div class="about-why__header">
			<div>
				<p><?php esc_html_e( 'Why AiBridze?', 'aibridze' ); ?></p>
				<h2 id="about-why-title"><?php esc_html_e( 'Why Businesses Choose AiBridze', 'aibridze' ); ?></h2>
			</div>
			<div class="about-why__controls about-why__controls--desktop" aria-label="<?php esc_attr_e( 'Choose cards', 'aibridze' ); ?>">
				<button type="button" data-about-why-previous aria-label="<?php esc_attr_e( 'Previous cards', 'aibridze' ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/carousel-previous.png' ) ); ?>" width="27" height="27" alt=""></button>
				<button type="button" data-about-why-next aria-label="<?php esc_attr_e( 'Next cards', 'aibridze' ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/carousel-next.png' ) ); ?>" width="9" height="15" alt=""></button>
			</div>
		</div>

		<div class="about-why__viewport">
			<div class="about-why__track" data-about-why-track>
				<?php
				$why_cards = array(
					array( 'We Listen Before We Build', 'We understand your business goals before recommending AI solutions, automation, or custom software that delivers measurable results.', 'why-listen.png' ),
					array( 'AI With a Purpose', 'We build AI agents, Generative AI solutions, and intelligent automation that solve real business problems – not AI for the sake of AI.', 'why-purpose.png' ),
					array( 'Tailored for Your Business', 'Every AI solution and custom software application is designed around your workflows, users, and long-term business objectives.', 'why-tailored.png' ),
					array( 'Transparent Collaboration', 'From discovery to deployment, we provide clear communication, agile delivery, and complete visibility throughout your project.', 'why-transparent.png' ),
					array( 'Built to Scale', 'We develop secure, enterprise-ready AI applications and software solutions that evolve with your business and support future growth.', 'why-scale.png' ),
					array( 'Partners Beyond Delivery', 'Our partnership doesn’t end at launch. We continuously optimize, support, and enhance your AI and software solutions as your business grows.', 'why-partners.png' ),
				);
				foreach ( $why_cards as $card ) :
					?>
					<article class="about-why__card">
						<div class="about-why__card-copy"><h3><?php echo esc_html( $card[0] ); ?></h3><p><?php echo esc_html( $card[1] ); ?></p></div>
						<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/' . $card[2] ) ); ?>" width="419" height="189" alt="">
					</article>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="about-why__controls about-why__controls--mobile" aria-label="<?php esc_attr_e( 'Choose cards', 'aibridze' ); ?>">
			<button type="button" data-about-why-previous aria-label="<?php esc_attr_e( 'Previous card', 'aibridze' ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/carousel-previous.png' ) ); ?>" width="27" height="27" alt=""></button>
			<button type="button" data-about-why-next aria-label="<?php esc_attr_e( 'Next card', 'aibridze' ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/carousel-next.png' ) ); ?>" width="9" height="15" alt=""></button>
		</div>

		<div class="about-why__footer">
			<p><?php esc_html_e( 'Your goals are unique. Your technology solution should be too.', 'aibridze' ); ?></p>
			<a href="#consultation" data-consultation-open><?php esc_html_e( 'Build With Us', 'aibridze' ); ?></a>
		</div>
	</div>
</section>

<section class="about-technologies" aria-labelledby="about-technologies-title">
	<div class="about-technologies__panel">
		<h2 id="about-technologies-title"><?php esc_html_e( 'Building the Future with AI & Modern Technologies', 'aibridze' ); ?></h2>
		<p><?php esc_html_e( 'We leverage industry-leading AI models, cloud platforms, and modern development frameworks to build secure, scalable, and production-ready digital solutions.', 'aibridze' ); ?></p>
		<div class="about-technologies__list" aria-label="<?php esc_attr_e( 'Technologies we use', 'aibridze' ); ?>">
			<?php
			$technologies = array(
				array( 'OpenAI', 'tech-openai.png' ),
				array( 'Claude', 'tech-claude.png' ),
				array( 'Gemini', 'tech-gemini.png' ),
				array( 'Python', 'tech-python.png' ),
				array( 'React', 'tech-react.png' ),
				array( 'Next.js', 'tech-nextjs.png' ),
				array( 'Flutter', 'tech-flutter.png' ),
				array( 'AWS', 'tech-aws.png' ),
				array( 'Azure', 'tech-azure.png' ),
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
		'posts_per_page' => 4,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);
$leadership_fallbacks = array(
	array( 'Sitaram Sharma', 'Co-Founder & Director', 'leader-sitaram.png' ),
	array( 'Ashish Chauhan', 'Co-Founder & Director', 'leader-ashish.png' ),
	array( 'Omji Mehrotra', 'Co-Founder & Director', 'leader-omji.png' ),
	array( 'Anand Prakash', 'Co-Founder & Director', 'leader-anand.png' ),
);
?>
<section class="about-leadership" aria-labelledby="about-leadership-title">
	<div class="about-leadership__inner">
		<p class="about-leadership__eyebrow"><?php esc_html_e( 'Our Leadership', 'aibridze' ); ?></p>
		<h2 id="about-leadership-title"><?php esc_html_e( 'The Vision Behind AiBridze', 'aibridze' ); ?></h2>
		<p class="about-leadership__description"><?php esc_html_e( 'Led by experienced technology leaders, AiBridze combines expertise in AI development, Generative AI, custom software development, mobile and web application development, and digital transformation to help businesses solve complex challenges through intelligent, scalable technology solutions.', 'aibridze' ); ?></p>
		<div class="about-leadership__profiles">
			<?php foreach ( $leadership_fallbacks as $index => $fallback ) :
				$leader   = $leadership_posts[ $index ] ?? null;
				$name     = $leader ? get_the_title( $leader ) : $fallback[0];
				$position = $leader ? (string) get_post_meta( $leader->ID, '_aibridze_leader_position', true ) : $fallback[1];
				$linkedin = $leader ? (string) get_post_meta( $leader->ID, '_aibridze_leader_linkedin', true ) : '';
				$image     = $leader && has_post_thumbnail( $leader ) ? get_the_post_thumbnail_url( $leader, 'large' ) : get_theme_file_uri( '/assets/images/about/' . $fallback[2] );
				?>
				<article class="about-leadership__profile">
					<div class="about-leadership__portrait"><img src="<?php echo esc_url( $image ); ?>" width="256" height="271" alt="<?php echo esc_attr( $name ); ?>"></div>
					<div class="about-leadership__details">
						<div><h3><?php echo esc_html( $name ); ?></h3><p><?php echo esc_html( $position ?: __( 'Co-Founder & Director', 'aibridze' ) ); ?></p></div>
						<?php if ( $linkedin ) : ?><a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( sprintf( __( '%s on LinkedIn', 'aibridze' ), $name ) ); ?>"><?php else : ?><span aria-hidden="true"><?php endif; ?>
							<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/linkedin-leader.png' ) ); ?>" width="40" height="40" alt="">
						<?php echo $linkedin ? '</a>' : '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
$team_posts = get_posts(
	array(
		'post_type'      => 'team',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);
$team_fallbacks = array(
	array( 'Praful Swarnkar', 'Lead UX Designer', 'team-praful.png' ),
	array( 'Amar Mishra', 'Sr. Full Stack Engineer', 'team-amar.png' ),
	array( 'Hitesh Verma', 'Sr. Flutter Developer', 'team-hitesh.png' ),
	array( 'Anurag Shrivastava', 'Software Engineer', 'team-anurag.png' ),
	array( 'Prashant Sharma', 'Full Stack Developer', 'team-prashant.png' ),
	array( 'Harsh Pratap Singh', 'DevOps Engineer', 'team-harsh.png' ),
	array( 'Prince Sharma', 'Frontend Engineer', 'team-prince.png' ),
	array( 'Aman Kumar', 'Backend + AI/ML Engineer', 'team-aman.png' ),
	array( 'Lalit Verma', 'Backend + AI/ML Engineer', 'team-lalit.png' ),
	array( 'Bhawya Chandra', 'Business Development Executive', 'team-bhawya.png' ),
);
$team_members = $team_posts ?: $team_fallbacks;
?>
<section class="about-team" aria-labelledby="about-team-title">
	<div class="about-team__inner">
		<p class="about-team__eyebrow"><?php esc_html_e( 'Our Core Team', 'aibridze' ); ?></p>
		<h2 id="about-team-title"><?php esc_html_e( 'Different minds. Shared vision. One team.', 'aibridze' ); ?></h2>
		<p class="about-team__description"><?php esc_html_e( 'Our AI engineers, machine learning specialists, software developers, UI/UX designers, cloud architects, and product experts collaborate to build AI-powered solutions, Generative AI applications, mobile and web applications, and custom software that help businesses innovate, automate, and scale with confidence.', 'aibridze' ); ?></p>
		<div class="about-team__grid">
			<?php foreach ( $team_members as $member ) :
				$is_post  = $member instanceof WP_Post;
				$name     = $is_post ? get_the_title( $member ) : $member[0];
				$position = $is_post ? (string) get_post_meta( $member->ID, '_aibridze_team_position', true ) : $member[1];
				$image     = $is_post ? ( has_post_thumbnail( $member ) ? get_the_post_thumbnail_url( $member, 'large' ) : '' ) : get_theme_file_uri( '/assets/images/about/' . $member[2] );
				?>
				<article class="about-team__member">
					<div class="about-team__portrait"><?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" width="232" height="230" alt="<?php echo esc_attr( $name ); ?>"><?php endif; ?></div>
					<h3><?php echo esc_html( $name ); ?></h3>
					<p><?php echo esc_html( $position ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="about-careers" aria-labelledby="about-careers-title">
	<div class="about-careers__panel">
		<div class="about-careers__copy">
			<h2 id="about-careers-title"><?php esc_html_e( 'Build Technology That Shapes The Future', 'aibridze' ); ?></h2>
			<p><?php esc_html_e( 'Join our team to build AI-powered products, Generative AI applications, AI agents, and enterprise software that solve real-world business challenges.', 'aibridze' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'Explore Careers', 'aibridze' ); ?></a>
		</div>
		<picture class="about-careers__media">
			<source media="(max-width: 700px)" srcset="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/careers-team-mobile.png' ) ); ?>">
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/careers-team-desktop.png' ) ); ?>" width="568" height="238" alt="<?php esc_attr_e( 'AiBridze team collaborating in the office', 'aibridze' ); ?>">
		</picture>
	</div>
</section>

<?php get_template_part( 'template-parts/sections/faq-contact' ); ?>
<?php get_footer(); ?>
