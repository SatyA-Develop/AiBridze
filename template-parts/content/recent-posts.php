<?php
/** Shared latest-three-posts section used by the blog archive and Service pages. */
$recent_fallbacks = array(
	'about/why-purpose.png',
	'service-categories/technology-consulting.png',
	'about/why-scale.png',
);
$recent_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
	)
);
$recent_posts = $recent_query->posts;
?>
<section class="recent-posts" aria-labelledby="recent-posts-title">
	<div class="recent-posts__inner">
		<h2 id="recent-posts-title"><?php esc_html_e( 'Recent Blog Posts', 'aibridze' ); ?></h2>
		<?php if ( $recent_posts ) : ?>
			<div class="recent-posts__grid">
				<?php foreach ( $recent_posts as $index => $post_item ) :
					$recent_categories = get_the_category( $post_item->ID );
					$recent_category   = $recent_categories ? $recent_categories[0]->name : __( 'Technology', 'aibridze' );
					$image             = has_post_thumbnail( $post_item ) ? get_the_post_thumbnail_url( $post_item, 'large' ) : get_theme_file_uri( '/assets/images/' . $recent_fallbacks[ $index ] );
					?>
					<article class="recent-posts__card<?php echo 0 === $index ? ' recent-posts__card--featured' : ''; ?>">
						<a class="recent-posts__image" href="<?php echo esc_url( get_permalink( $post_item ) ); ?>"><img src="<?php echo esc_url( $image ); ?>" width="624" height="247" alt="<?php echo esc_attr( get_the_title( $post_item ) ); ?>" loading="lazy"></a>
						<div class="recent-posts__copy">
							<div class="recent-posts__meta"><span><?php echo esc_html( sprintf( __( 'By %s', 'aibridze' ), get_the_author_meta( 'display_name', $post_item->post_author ) ?: __( 'AIBridze Team', 'aibridze' ) ) ); ?></span><i aria-hidden="true"></i><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_item ) ); ?>"><?php echo esc_html( get_the_date( 'l, j M Y', $post_item ) ); ?></time></div>
							<h3><a href="<?php echo esc_url( get_permalink( $post_item ) ); ?>"><?php echo esc_html( get_the_title( $post_item ) ); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_item ), 18, '…' ) ); ?></p>
							<span class="recent-posts__badge"><?php echo esc_html( $recent_category ); ?></span>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'New insights are coming soon.', 'aibridze' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php wp_reset_postdata(); ?>
