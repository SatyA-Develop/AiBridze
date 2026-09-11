<?php
/** Single blog post template. */
get_header();
while ( have_posts() ) :
	the_post();
	$categories   = get_the_category();
	$category     = $categories ? $categories[0] : null;
	$word_count   = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$reading_time = max( 4, (int) ceil( $word_count / 200 ) );
	$author_name  = get_the_author() ?: 'Prafull Swarnkar';
	$blogs_page   = get_page_by_path( 'blogs' );
	$blogs_url    = $blogs_page ? get_permalink( $blogs_page ) : home_url( '/blogs/' );
	$takeaways    = (string) get_post_meta( get_the_ID(), '_aibridze_key_takeaways', true );
	$featured_url = get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?: get_theme_file_uri( '/assets/images/about/why-purpose.png' );
	$latest_posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 5, 'post__not_in' => array( get_the_ID() ), 'ignore_sticky_posts' => true ) );
	$latest_fallbacks = array( 'service-categories/technology-consulting.png', 'about/why-scale.png', 'about/why-listen.png', 'service-categories/web-app-development.png', 'about/why-partners.png' );
	?>
	<article class="single-blog">
		<header class="single-blog__hero">
			<div class="single-blog__hero-inner">
				<nav class="single-blog__breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aibridze' ); ?>">
					<a href="<?php echo esc_url( $blogs_url ); ?>"><?php esc_html_e( 'Blogs', 'aibridze' ); ?></a><span aria-hidden="true">/</span>
					<?php if ( $category ) : ?><a href="<?php echo esc_url( add_query_arg( 'blog_category', $category->slug, $blogs_url ) ); ?>"><?php echo esc_html( $category->name ); ?></a><?php endif; ?>
				</nav>
				<h1><?php the_title(); ?></h1>
				<div class="single-blog__meta">
					<strong><?php echo esc_html( sprintf( __( 'By %s', 'aibridze' ), $author_name ) ); ?></strong><i aria-hidden="true"></i>
					<span><?php echo esc_html( sprintf( _n( '%d Min Read', '%d Mins Read', $reading_time, 'aibridze' ), $reading_time ) ); ?></span><i aria-hidden="true"></i>
					<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
				</div>
			</div>
		</header>

		<div class="single-blog__layout">
			<main class="single-blog__main">
				<figure class="single-blog__featured"><img src="<?php echo esc_url( $featured_url ); ?>" width="862" height="360" alt="<?php the_title_attribute(); ?>"></figure>
				<?php if ( $takeaways ) : ?><section class="single-blog__takeaways" aria-labelledby="single-blog-takeaways-title"><h2 id="single-blog-takeaways-title"><?php esc_html_e( 'Key Takeaways', 'aibridze' ); ?></h2><div><?php echo wp_kses_post( apply_filters( 'the_content', $takeaways ) ); ?></div></section><?php endif; ?>
				<div class="single-blog__content entry-content"><?php the_content(); ?></div>
			</main>

			<aside class="single-blog__sidebar">
				<section class="single-blog__latest" aria-labelledby="latest-articles-title">
					<h2 id="latest-articles-title"><?php esc_html_e( 'Latest Articles', 'aibridze' ); ?></h2>
					<div class="single-blog__latest-list">
						<?php foreach ( $latest_posts as $index => $latest_post ) :
							$latest_categories = get_the_category( $latest_post->ID );
							$latest_category   = $latest_categories ? $latest_categories[0]->name : __( 'Technology', 'aibridze' );
							$latest_image      = has_post_thumbnail( $latest_post ) ? get_the_post_thumbnail_url( $latest_post, 'thumbnail' ) : get_theme_file_uri( '/assets/images/' . $latest_fallbacks[ $index % count( $latest_fallbacks ) ] );
							?>
							<article><a class="single-blog__latest-image" href="<?php echo esc_url( get_permalink( $latest_post ) ); ?>"><img src="<?php echo esc_url( $latest_image ); ?>" width="110" height="80" alt=""></a><div><div><span><?php echo esc_html( $latest_category ); ?></span><time><?php echo esc_html( get_the_date( 'M j, Y', $latest_post ) ); ?></time></div><h3><a href="<?php echo esc_url( get_permalink( $latest_post ) ); ?>"><?php echo esc_html( get_the_title( $latest_post ) ); ?></a></h3></div></article>
						<?php endforeach; ?>
					</div>
				</section>

				<section class="single-blog__quote-form" aria-labelledby="technology-experts-title">
					<div class="single-blog__quote-form-inner">
						<h2 id="technology-experts-title"><?php esc_html_e( 'Connect with Our Technology Experts', 'aibridze' ); ?></h2>
						<p><?php esc_html_e( 'Get expert guidance for your digital journey.', 'aibridze' ); ?></p>
						<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
							<input type="hidden" name="action" value="aibridze_consultation"><?php wp_nonce_field( 'aibridze_consultation', 'aibridze_consultation_nonce' ); ?><label class="consultation-form__honeypot" aria-hidden="true">Company website<input name="company_website" tabindex="-1" autocomplete="off"></label>
							<input name="full_name" type="text" placeholder="<?php esc_attr_e( 'Enter Name', 'aibridze' ); ?>" autocomplete="name" required>
							<input name="email" type="email" pattern="[^\s@]+@[^\s@]+\.[^\s@]+" placeholder="<?php esc_attr_e( 'Email Id', 'aibridze' ); ?>" autocomplete="email" required>
							<div class="single-blog__phone">
								<div class="single-blog__country">
									<img data-country-flag src="https://flagcdn.com/us.svg" width="24" height="24" alt="" referrerpolicy="no-referrer">
								<label class="screen-reader-text" for="single-blog-country-code"><?php esc_html_e( 'Country calling code', 'aibridze' ); ?></label>
								<select id="single-blog-country-code" name="country_code" aria-label="<?php esc_attr_e( 'Country calling code', 'aibridze' ); ?>">
									<?php foreach ( aibridze_country_calling_codes() as $country_iso => $calling_code ) : ?>
										<option data-country="<?php echo esc_attr( strtolower( $country_iso ) ); ?>" value="<?php echo esc_attr( $calling_code ); ?>"<?php selected( 'US', $country_iso ); ?>><?php echo esc_html( $country_iso . ' ' . $calling_code ); ?></option>
									<?php endforeach; ?>
								</select>
								<svg width="12" height="8" viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1 5 5 5-5" fill="none" stroke="currentColor" stroke-width="2"/></svg>
								</div>
								<input name="phone" type="tel" placeholder="<?php esc_attr_e( 'Phone Number', 'aibridze' ); ?>" autocomplete="tel">
							</div>
							<input type="hidden" name="message" value="<?php esc_attr_e( 'Request for a free consultation from a blog article.', 'aibridze' ); ?>">
							<button type="submit"><span class="single-blog__quote-button-label"><?php esc_html_e( 'Get Free Quote', 'aibridze' ); ?></span><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/faq/quote-arrow.svg' ) ); ?>" width="20" height="20" alt=""></button>
						</form>
					</div>
					<div class="single-blog__nda"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/about/security-shield.png' ) ); ?>" width="16" height="16" alt=""><strong><?php esc_html_e( 'Fully NDA-Protected.', 'aibridze' ); ?></strong></div>
				</section>
			</aside>
		</div>
	</article>
	<?php
endwhile;
get_footer();
