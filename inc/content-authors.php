<?php
/** Dashboard authorship for custom content. */

function aibridze_custom_author_types(): array {
	return get_post_types( array( '_builtin' => false, 'show_ui' => true ) );
}

add_action( 'init', function (): void {
	foreach ( aibridze_custom_author_types() as $post_type ) {
		add_post_type_support( $post_type, 'author' );
		add_filter( "manage_{$post_type}_posts_columns", function ( array $columns ): array {
			$columns['author'] = __( 'Author', 'aibridze' );
			return $columns;
		}, 100 );
	}
}, 100 );

add_filter( 'wp_insert_post_data', function ( array $data ): array {
	if ( in_array( $data['post_type'] ?? '', aibridze_custom_author_types(), true ) ) {
		$author = get_user_by( 'login', 'satya_dash99' );
		if ( $author ) {
			$data['post_author'] = $author->ID;
		}
	}
	return $data;
} );
