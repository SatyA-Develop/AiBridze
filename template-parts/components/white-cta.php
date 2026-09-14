<?php
/** Shared white CTA: labels and actions vary; appearance and motion stay common. */
$label = $args['label'] ?? __( 'Learn more', 'aibridze' );
$is_submit = ! empty( $args['submit'] );
$classes = array( 'white-cta' );
foreach ( preg_split( '/\s+/', trim( $args['class'] ?? '' ) ) as $class_name ) {
    if ( $class_name ) $classes[] = sanitize_html_class( $class_name );
}
?>
<?php if ( $is_submit ) : ?>
<button type="submit" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
<?php else : ?>
<a class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" href="<?php echo esc_url( $args['url'] ?? '#' ); ?>"<?php if ( ! empty( $args['consultation'] ) ) : ?> data-consultation-open<?php endif; ?>>
<?php endif; ?>
    <span class="white-cta__label"><?php echo esc_html( $label ); ?></span>
    <img class="white-cta__icon" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/faq/submit-arrow.svg' ) ); ?>" width="20" height="20" alt="" aria-hidden="true">
<?php if ( $is_submit ) : ?></button><?php else : ?></a><?php endif; ?>
