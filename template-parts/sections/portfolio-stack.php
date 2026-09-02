<?php
/**
 * Sticky portfolio showcase.
 *
 * @package AIBridze
 */
$projects = get_posts( array( 'post_type' => 'portfolio', 'posts_per_page' => 6, 'post_status' => 'publish', 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
?>
<?php if ( $projects ) : ?>
<section class="portfolio-stack" data-portfolio-stack aria-labelledby="portfolio-stack-title">
	<div class="portfolio-stack__heading">
		<h2 id="portfolio-stack-title">See Our Work In Action</h2>
	</div>
	<div class="portfolio-stack__cards" data-portfolio-scroll-region>
		<div class="portfolio-stack__viewport" data-portfolio-viewport>
		<?php foreach ( $projects as $index => $project ) :
			$logo_id = (int) get_post_meta( $project->ID, '_aibridze_portfolio_logo_id', true );
			$logo = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : get_theme_file_uri( '/assets/images/portfolio-logo.png' );
			$image = has_post_thumbnail( $project ) ? get_the_post_thumbnail_url( $project, 'full' ) : get_theme_file_uri( '/assets/images/portfolio-background.png' );
			$url = get_post_meta( $project->ID, '_aibridze_portfolio_url', true ) ?: get_permalink( $project );
			?>
			<article class="portfolio-card" data-portfolio-card style="--portfolio-index:<?php echo esc_attr( $index ); ?>">
				<div class="portfolio-card__content">
					<div class="portfolio-card__reveal"><div class="portfolio-card__topline"><span>Our Work</span><a href="<?php echo esc_url( get_post_type_archive_link( 'portfolio' ) ); ?>">All Projects ↗</a></div></div>
					<div class="portfolio-card__reveal"><img class="portfolio-card__logo" src="<?php echo esc_url( $logo ); ?>" width="150" height="55" alt="<?php echo esc_attr( get_the_title( $project ) ); ?> logo"></div>
					<div class="portfolio-card__reveal"><h3><?php echo esc_html( get_the_title( $project ) ); ?></h3></div>
					<div class="portfolio-card__reveal"><p class="portfolio-card__description"><?php echo esc_html( get_the_excerpt( $project ) ); ?></p></div>
					<div class="portfolio-card__reveal"><div class="portfolio-card__stats">
						<div><strong><?php echo esc_html( get_post_meta( $project->ID, '_aibridze_portfolio_stat_one', true ) ); ?></strong><span><?php echo esc_html( get_post_meta( $project->ID, '_aibridze_portfolio_stat_one_label', true ) ); ?></span></div>
						<div><strong><?php echo esc_html( get_post_meta( $project->ID, '_aibridze_portfolio_stat_two', true ) ); ?></strong><span><?php echo esc_html( get_post_meta( $project->ID, '_aibridze_portfolio_stat_two_label', true ) ); ?></span></div>
					</div></div>
					<div class="portfolio-card__reveal portfolio-card__reveal--button">
						<a class="portfolio-card__button" href="<?php echo esc_url( $url ); ?>">View Project <span>→</span></a>
					</div>
				</div>
				<div class="portfolio-card__visual"><img src="<?php echo esc_url( $image ); ?>" width="816" height="638" alt="<?php echo esc_attr( get_the_title( $project ) ); ?>"></div>
			</article>
		<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
