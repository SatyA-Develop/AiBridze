<?php
/** Resolve navigation state from the WordPress route on every page request. */
function aibridze_navigation_attributes( string $url, string $section = '' ): string {
	if ( is_404() ) return '';
	global $wp;
	$current = is_singular() ? get_permalink( get_queried_object_id() ) : home_url( '/' . $wp->request );
	$path = static function ( $value ) {
		return untrailingslashit( rawurldecode( (string) wp_parse_url( $value, PHP_URL_PATH ) ) );
	};
	$exact = $path( $url ) === $path( $current );
	$active = $exact;
	$object = get_queried_object();
	$is_studio = $object instanceof WP_Post && 'ai-studio' === $object->post_name;
	if ( 'services' === $section && ! $is_studio ) {
		$active = $active || is_singular( 'service' ) || is_post_type_archive( 'service' ) || is_tax( 'service_category' );
	} elseif ( 'industries' === $section ) {
		$active = $active || is_singular( 'industry' ) || is_post_type_archive( 'industry' );
	} elseif ( 'portfolio' === $section ) {
		$active = $active || is_singular( 'portfolio' ) || is_post_type_archive( 'portfolio' );
	} elseif ( 'ai-studio' === $section ) {
		$active = $active || $is_studio;
	}
	if ( $section && is_page() && ! $is_studio ) {
		foreach ( get_post_ancestors( get_queried_object_id() ) as $ancestor ) {
			if ( $section === get_post_field( 'post_name', $ancestor ) ) $active = true;
		}
	}
	return $active ? ' data-nav-active="true"' . ( $exact ? ' aria-current="page"' : '' ) : '';
}
