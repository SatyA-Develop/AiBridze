<?php
/**
 * Dynamic video and written testimonial showcase.
 *
 * @package AIBridze
 */
$video_posts = get_posts( array( 'post_type' => 'video_testimonial', 'posts_per_page' => 4, 'post_status' => 'publish', 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ) ) );
$testimonials = get_posts( array( 'post_type' => 'testimonial', 'posts_per_page' => 4, 'post_status' => 'publish', 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ) ) );
$fallback_videos = array(
	get_theme_file_uri( '/assets/video/hero-ai-technology.mp4' ),
	get_theme_file_uri( '/assets/video/can_you_color_similar_to_F_.mp4' ),
);
$video_urls = array();
foreach ( $video_posts as $video_post ) {
	$url = (string) get_post_meta( $video_post->ID, '_aibridze_video_url', true );
	if ( $url ) $video_urls[] = array( 'url' => $url, 'poster' => has_post_thumbnail( $video_post ) ? get_the_post_thumbnail_url( $video_post, 'large' ) : '' );
}
if ( ! $video_urls ) foreach ( array( 0, 1, 0, 1 ) as $fallback_index ) $video_urls[] = array( 'url' => $fallback_videos[$fallback_index], 'poster' => '' );
?>
<section class="customer-stories" data-customer-stories aria-labelledby="customer-stories-title">
	<header class="customer-stories__header">
		<p class="section-callout"><?php esc_html_e( 'Customer Stories', 'aibridze' ); ?></p>
		<h2 id="customer-stories-title"><?php esc_html_e( 'Trusted by Businesses, Valued by Clients', 'aibridze' ); ?></h2>
		<div><?php esc_html_e( "Real feedback from businesses we've helped with AI, custom software, and digital transformation.", 'aibridze' ); ?></div>
	</header>

	<div class="customer-stories__showcase">
		<div class="video-stack" data-video-stack>
			<?php foreach ( $video_urls as $index => $video_item ) :
				$video_url = $video_item['url'];
				$youtube_id = aibridze_testimonial_youtube_id( $video_url );
				$poster = $video_item['poster'] ?: ( $youtube_id ? 'https://i.ytimg.com/vi/' . $youtube_id . '/hqdefault.jpg' : '' );
				?>
				<article class="video-stack__card<?php echo 0 === $index ? ' is-active' : ''; ?>" data-video-card data-index="<?php echo esc_attr( $index ); ?>" data-video-source="<?php echo esc_url( $video_url ); ?>" data-youtube-id="<?php echo esc_attr( $youtube_id ); ?>">
					<?php if ( $poster ) : ?>
						<img src="<?php echo esc_url( $poster ); ?>" alt="" draggable="false" loading="lazy">
					<?php else : ?>
						<video preload="metadata" playsinline muted src="<?php echo esc_url( $video_url ); ?>#t=0.1"></video>
					<?php endif; ?>
					<button class="video-stack__play" type="button" data-video-play aria-label="<?php esc_attr_e( 'Play testimonial video', 'aibridze' ); ?>">
						<span class="video-stack__play-icon" aria-hidden="true"></span>
						<span class="video-stack__pause-icon" aria-hidden="true"></span>
					</button>
				</article>
			<?php endforeach; ?>
			<div class="video-stack__controls" aria-label="<?php esc_attr_e( 'Video testimonial navigation', 'aibridze' ); ?>">
				<button type="button" data-video-prev aria-label="<?php esc_attr_e( 'Previous video', 'aibridze' ); ?>">←</button>
				<span><?php esc_html_e( 'Swipe', 'aibridze' ); ?></span>
				<button type="button" data-video-next aria-label="<?php esc_attr_e( 'Next video', 'aibridze' ); ?>">→</button>
			</div>
		</div>

		<div class="story-quotes" data-story-quotes>
			<div class="story-quotes__steps" role="group" aria-label="<?php esc_attr_e( 'Testimonial navigation', 'aibridze' ); ?>">
				<?php foreach ( $testimonials as $index => $testimonial ) : ?><button type="button" class="<?php echo 0 === $index ? 'is-active' : ''; ?>" data-story-step aria-label="<?php echo esc_attr( sprintf( __( 'Show testimonial %d', 'aibridze' ), $index + 1 ) ); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"></button><?php endforeach; ?>
			</div>
			<?php foreach ( $testimonials as $index => $testimonial ) :
				$role = (string) get_post_meta( $testimonial->ID, '_aibridze_testimonial_role', true );
				$company = (string) get_post_meta( $testimonial->ID, '_aibridze_testimonial_company', true );
				$logo_id = (int) get_post_meta( $testimonial->ID, '_aibridze_testimonial_logo_id', true );
				$avatar = has_post_thumbnail( $testimonial ) ? get_the_post_thumbnail_url( $testimonial, 'thumbnail' ) : get_theme_file_uri( '/assets/images/consultation/testimonial-avatar.png' );
				$logo = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : get_theme_file_uri( '/assets/images/consultation/company-logo.png' );
				?>
				<article class="story-quote<?php echo 0 === $index ? ' is-active' : ''; ?>" data-story-quote aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
					<div class="story-quote__mark" aria-hidden="true">“</div>
					<p><?php echo esc_html( wp_strip_all_tags( get_the_content( null, false, $testimonial ) ) ); ?></p>
					<footer>
						<img src="<?php echo esc_url( $avatar ); ?>" width="58" height="58" loading="lazy" decoding="async" alt="<?php echo esc_attr( get_the_title( $testimonial ) ); ?>">
						<span><strong><?php echo esc_html( get_the_title( $testimonial ) ); ?></strong><small><?php echo esc_html( trim( $role . ( $role && $company ? ', ' : '' ) . $company ) ); ?></small></span>
						<img class="story-quote__company" src="<?php echo esc_url( $logo ); ?>" loading="lazy" decoding="async" alt="<?php echo esc_attr( $company ); ?>">
					</footer>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
