<?php
/** Animated homepage card artwork managed directly from Service Categories. */

function aibridze_service_category_display_name( WP_Term $term ): string {
	$name = (string) get_term_meta( $term->term_id, '_aibridze_display_name', true );
	return '' !== trim( $name ) ? $name : $term->name;
}

function aibridze_render_service_display_name( ?WP_Term $term = null ): void {
	wp_nonce_field( 'aibridze_service_display_name', 'aibridze_service_display_name_nonce' );
	?><input type="text" id="aibridze-display-name" name="aibridze_display_name" value="<?php echo esc_attr( $term ? aibridze_service_category_display_name( $term ) : '' ); ?>"><p class="description">Name displayed on the homepage service card. Leave blank to use the category name.</p><?php
}
add_action( 'service_category_add_form_fields', static function (): void {
	?><div class="form-field"><label for="aibridze-display-name">Displaying name</label><?php aibridze_render_service_display_name(); ?></div><?php
} );
add_action( 'service_category_edit_form_fields', static function ( WP_Term $term ): void {
	?><tr class="form-field"><th scope="row"><label for="aibridze-display-name">Displaying name</label></th><td><?php aibridze_render_service_display_name( $term ); ?></td></tr><?php
} );
function aibridze_save_service_display_name( int $term_id ): void {
	$nonce = sanitize_text_field( wp_unslash( $_POST['aibridze_service_display_name_nonce'] ?? '' ) );
	$taxonomy = get_taxonomy( 'service_category' );
	if ( ! $taxonomy || ! wp_verify_nonce( $nonce, 'aibridze_service_display_name' ) || ! current_user_can( $taxonomy->cap->edit_terms ) || ! isset( $_POST['aibridze_display_name'] ) ) return;
	update_term_meta( $term_id, '_aibridze_display_name', sanitize_text_field( wp_unslash( $_POST['aibridze_display_name'] ) ) );
}
add_action( 'created_service_category', 'aibridze_save_service_display_name' );
add_action( 'edited_service_category', 'aibridze_save_service_display_name' );

function aibridze_render_service_card_gif( ?WP_Term $term = null ): void {
	$gif_id = $term ? (int) get_term_meta( $term->term_id, '_aibridze_card_gif_id', true ) : 0;
	wp_nonce_field( 'aibridze_service_category_gif', 'aibridze_service_category_gif_nonce' );
	?>
	<div data-service-gif-field>
		<input type="hidden" name="aibridze_card_gif_id" value="<?php echo esc_attr( $gif_id ); ?>" data-gif-input>
		<img data-gif-preview src="<?php echo esc_url( $gif_id ? wp_get_attachment_url( $gif_id ) : '' ); ?>" alt="" style="max-width:240px;max-height:180px;object-fit:contain"<?php echo $gif_id ? '' : ' hidden'; ?>>
		<p><button type="button" class="button" data-gif-select><?php esc_html_e( 'Choose / upload GIF', 'aibridze' ); ?></button> <button type="button" class="button-link-delete" data-gif-remove<?php echo $gif_id ? '' : ' hidden'; ?>><?php esc_html_e( 'Remove GIF', 'aibridze' ); ?></button></p>
		<p data-gif-error role="alert" hidden><?php esc_html_e( 'Please select a GIF image.', 'aibridze' ); ?></p>
		<p class="description"><?php esc_html_e( 'Replaces the image on this category’s homepage service card. The original GIF plays automatically; use a looping GIF for continuous playback. Removing it restores the featured card image. Recommended size: 374×380px.', 'aibridze' ); ?></p>
	</div>
	<?php
}

add_action( 'service_category_add_form_fields', static function (): void {
	?><div class="form-field"><label><?php esc_html_e( 'Homepage Card GIF', 'aibridze' ); ?></label><?php aibridze_render_service_card_gif(); ?></div><?php
} );
add_action( 'service_category_edit_form_fields', static function ( WP_Term $term ): void {
	?><tr class="form-field"><th scope="row"><?php esc_html_e( 'Homepage Card GIF', 'aibridze' ); ?></th><td><?php aibridze_render_service_card_gif( $term ); ?></td></tr><?php
} );

function aibridze_save_service_category_gif( int $term_id ): void {
	$nonce = isset( $_POST['aibridze_service_category_gif_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['aibridze_service_category_gif_nonce'] ) ) : '';
	$taxonomy = get_taxonomy( 'service_category' );
	if ( ! $taxonomy || ! wp_verify_nonce( $nonce, 'aibridze_service_category_gif' ) || ! current_user_can( $taxonomy->cap->edit_terms ) ) return;
	// Quick edits and requests without this field must preserve its value.
	if ( ! isset( $_POST['aibridze_card_gif_id'] ) ) return;
	$gif_id = absint( $_POST['aibridze_card_gif_id'] );
	if ( $gif_id && ( 'attachment' !== get_post_type( $gif_id ) || 'image/gif' !== get_post_mime_type( $gif_id ) ) ) return;
	$gif_id ? update_term_meta( $term_id, '_aibridze_card_gif_id', $gif_id ) : delete_term_meta( $term_id, '_aibridze_card_gif_id' );
}
add_action( 'created_service_category', 'aibridze_save_service_category_gif' );
add_action( 'edited_service_category', 'aibridze_save_service_category_gif' );

/** Return the original file, since generated thumbnails can lose animation. */
function aibridze_service_card_gif_url( int $category_id ): string {
	$gif_id = (int) get_term_meta( $category_id, '_aibridze_card_gif_id', true );
	if ( ! $gif_id || 'attachment' !== get_post_type( $gif_id ) || 'image/gif' !== get_post_mime_type( $gif_id ) ) return '';
	return wp_get_attachment_url( $gif_id ) ?: '';
}

/** Use a lightweight companion when the selected GIF has been optimized. */
function aibridze_service_card_animation( int $category_id ): array {
	$id = (int) get_term_meta( $category_id, '_aibridze_card_gif_id', true );
	$file = $id ? get_attached_file( $id ) : '';
	$url = aibridze_service_card_gif_url( $category_id );
	if ( ! $file || ! $url ) return array();
	$stem = preg_replace( '/\.gif$/i', '', $file );
	$url_stem = preg_replace( '/\.gif$/i', '', $url );
	if ( ! is_file( $stem . '.card.mp4' ) || ! is_file( $stem . '.card.jpg' ) ) return array();
	return array( 'video' => $url_stem . '.card.mp4', 'poster' => $url_stem . '.card.jpg' );
}

add_action( 'admin_enqueue_scripts', static function ( string $hook ): void {
	$screen = get_current_screen();
	if ( ! $screen || 'service_category' !== $screen->taxonomy || ! in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) ) return;
	wp_enqueue_media();
	$path = '/assets/js/admin-service-gif.js';
	wp_enqueue_script( 'aibridze-service-gif', get_theme_file_uri( $path ), array( 'media-editor', 'media-views' ), (string) filemtime( get_theme_file_path( $path ) ), true );
} );
