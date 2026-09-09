<?php
/** Sanitized SVG uploads and Media Library previews. */
spl_autoload_register( static function ( $class ) {
	$prefix = 'enshrined\\svgSanitize\\';
	if ( 0 !== strpos( $class, $prefix ) ) return;
	$file = __DIR__ . '/vendor/svg-sanitize/src/' . str_replace( '\\', '/', substr( $class, strlen( $prefix ) ) ) . '.php';
	if ( is_file( $file ) ) require_once $file;
} );

add_filter( 'upload_mimes', static function ( $mimes ) {
	if ( current_user_can( 'upload_files' ) ) $mimes['svg'] = 'image/svg+xml';
	return $mimes;
} );

function aibridze_sanitize_svg_upload( $file ) {
	if ( 'svg' !== strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) ) || ! empty( $file['error'] ) ) return $file;
	if ( ! current_user_can( 'upload_files' ) || ! class_exists( 'DOMDocument' ) ) {
		$file['error'] = __( 'SVG upload is unavailable for this account or server.', 'aibridze' );
		return $file;
	}
	$sanitizer = new \enshrined\svgSanitize\Sanitizer();
	$sanitizer->removeRemoteReferences( true );
	$clean = $sanitizer->sanitize( file_get_contents( $file['tmp_name'] ) );
	$document = new DOMDocument();
	if ( ! $clean || ! @$document->loadXML( $clean, LIBXML_NONET ) || 'svg' !== $document->documentElement->localName ) {
		$file['error'] = __( 'Please upload a valid SVG image.', 'aibridze' );
		return $file;
	}
	if ( false === file_put_contents( $file['tmp_name'], $clean ) ) {
		$file['error'] = __( 'The sanitized SVG could not be saved.', 'aibridze' );
		return $file;
	}
	$file['type'] = 'image/svg+xml';
	$file['size'] = strlen( $clean );
	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'aibridze_sanitize_svg_upload' );
add_filter( 'wp_handle_sideload_prefilter', 'aibridze_sanitize_svg_upload' );

add_filter( 'wp_check_filetype_and_ext', static function ( $data, $file, $filename ) {
	if ( 'svg' === strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) && current_user_can( 'upload_files' ) && class_exists( 'DOMDocument' ) ) {
		$document = new DOMDocument();
		if ( @$document->load( $file, LIBXML_NONET ) && 'svg' === $document->documentElement->localName ) {
			$data['ext'] = 'svg';
			$data['type'] = 'image/svg+xml';
		}
	}
	return $data;
}, 10, 3 );

add_filter( 'wp_generate_attachment_metadata', static function ( $metadata, $id ) {
	if ( 'image/svg+xml' !== get_post_mime_type( $id ) || ! class_exists( 'DOMDocument' ) ) return $metadata;
	$document = new DOMDocument();
	if ( ! @$document->load( get_attached_file( $id ), LIBXML_NONET ) ) return $metadata;
	$svg = $document->documentElement;
	$viewbox = preg_split( '/[\s,]+/', trim( $svg->getAttribute( 'viewBox' ) ) );
	$width = (float) $svg->getAttribute( 'width' );
	$height = (float) $svg->getAttribute( 'height' );
	if ( 4 === count( $viewbox ) ) {
		$width = (float) $viewbox[2];
		$height = (float) $viewbox[3];
	}
	return array( 'width' => max( 1, (int) $width ), 'height' => max( 1, (int) $height ), 'file' => get_post_meta( $id, '_wp_attached_file', true ), 'sizes' => array() );
}, 10, 2 );

add_filter( 'wp_prepare_attachment_for_js', static function ( $response, $attachment, $meta ) {
	if ( 'image/svg+xml' !== $attachment->post_mime_type ) return $response;
	$response['icon'] = $response['url'];
	$response['sizes']['full'] = array( 'url' => $response['url'], 'width' => $meta['width'] ?? 24, 'height' => $meta['height'] ?? 24, 'orientation' => 'landscape' );
	return $response;
}, 10, 3 );
