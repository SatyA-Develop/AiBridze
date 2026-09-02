<?php
/** Contact-page video testimonial carousel. */
$video_posts = get_posts(
	array(
		'post_type'      => 'video_testimonial',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);

$videos = array();
foreach ( $video_posts as $video_post ) {
	$video_url = (string) get_post_meta( $video_post->ID, '_aibridze_video_url', true );
	if ( ! $video_url ) continue;
	$videos[] = array(
		'url'    => $video_url,
		'poster' => has_post_thumbnail( $video_post ) ? get_the_post_thumbnail_url( $video_post, 'large' ) : '',
		'title'  => get_the_title( $video_post ),
	);
}
?>
<?php if ( $videos ) : ?>
<section class="contact-videos" data-contact-videos aria-labelledby="contact-videos-title">
	<div class="contact-videos__top">
		<h2 id="contact-videos-title"><?php esc_html_e( 'Hear from Our Happy Clients', 'aibridze' ); ?></h2>
		<div class="contact-videos__stats" aria-label="Company achievements">
			<div><strong>15+</strong><span><?php esc_html_e( 'Years of Experience', 'aibridze' ); ?></span></div>
			<div><strong>12+</strong><span><?php esc_html_e( 'Industries Served', 'aibridze' ); ?></span></div>
			<div><strong>50+</strong><span><?php esc_html_e( 'Problems Solved', 'aibridze' ); ?></span></div>
		</div>
	</div>
	<div class="contact-videos__viewport">
		<div class="contact-videos__track" data-contact-video-track>
			<?php foreach ( $videos as $video ) : ?>
				<article class="contact-video-card">
					<video preload="none" playsinline data-lazy-video data-src="<?php echo esc_url( $video['url'] ); ?>"<?php echo $video['poster'] ? ' poster="' . esc_url( $video['poster'] ) . '"' : ''; ?>></video>
					<button type="button" data-contact-video-play aria-label="<?php echo esc_attr( sprintf( __( 'Play %s', 'aibridze' ), $video['title'] ) ); ?>"><i aria-hidden="true"></i></button>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
