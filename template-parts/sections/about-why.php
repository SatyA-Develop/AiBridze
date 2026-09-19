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

