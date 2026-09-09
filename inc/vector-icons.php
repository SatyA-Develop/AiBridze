<?php
/** Prefer vector counterparts for bundled icons without changing uploaded photos. */
add_filter( 'theme_file_uri', function ( string $url, string $file ): string {
	$file = ltrim( $file, '/' );
	if ( str_starts_with( $file, 'assets/images/' ) && str_ends_with( $file, '.png' ) ) {
		$vector = substr( $file, 0, -4 ) . '.svg';
		if ( file_exists( get_theme_file_path( $vector ) ) ) {
			return substr( $url, 0, -4 ) . '.svg';
		}
	}
	return $url;
}, 10, 2 );
