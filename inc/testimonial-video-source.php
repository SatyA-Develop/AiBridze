<?php
/** Resolve supported external video URLs without fetching arbitrary embeds. */
function aibridze_testimonial_youtube_id( string $url ): string {
	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) ) return '';
	$host = strtolower( $parts['host'] ?? '' );
	$path = trim( $parts['path'] ?? '', '/' );
	$id = '';
	if ( in_array( $host, array( 'youtu.be', 'www.youtu.be' ), true ) ) {
		$id = explode( '/', $path )[0];
	} elseif ( in_array( $host, array( 'youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com' ), true ) ) {
		parse_str( $parts['query'] ?? '', $query );
		if ( 'watch' === $path ) $id = is_string( $query['v'] ?? null ) ? $query['v'] : '';
		elseif ( preg_match( '#^(?:embed|shorts|live)/([^/]+)#', $path, $matches ) ) $id = $matches[1];
	}
	return preg_match( '/^[A-Za-z0-9_-]{11}$/', $id ) ? $id : '';
}

/** Shared poster source for every video testimonial section. */
function aibridze_testimonial_thumbnail( int $post_id ): string {
	$image_id = absint( get_post_meta( $post_id, '_aibridze_video_thumbnail_id', true ) );
	$poster = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : false;
	if ( ! $poster ) $poster = get_the_post_thumbnail_url( $post_id, 'large' );
	if ( $poster ) return $poster;
	$youtube_id = aibridze_testimonial_youtube_id( (string) get_post_meta( $post_id, '_aibridze_video_url', true ) );
	return $youtube_id ? 'https://i.ytimg.com/vi/' . $youtube_id . '/hqdefault.jpg' : '';
}
