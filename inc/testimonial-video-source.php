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
