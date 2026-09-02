<?php
/**
 * Pill button component.
 *
 * @package AIBridze
 * @var array $args Component options.
 */

$label      = $args['label'] ?? __( 'Learn more', 'aibridze' );
$url        = $args['url'] ?? '#';
$variant    = sanitize_html_class( $args['variant'] ?? 'primary' );
$classes    = array( 'button', 'button--' . $variant );
$extra      = preg_split( '/\s+/', trim( (string) ( $args['class'] ?? '' ) ) );
$aria_label = trim( (string) ( $args['aria_label'] ?? '' ) );
$icon       = trim( (string) ( $args['icon'] ?? get_theme_file_uri( '/assets/images/button-arrow.png' ) ) );
foreach ( $extra as $class_name ) {
	if ( $class_name ) $classes[] = sanitize_html_class( $class_name );
}
?>
<a class="<?php echo esc_attr( implode( ' ', array_unique( $classes ) ) ); ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $aria_label ? ' aria-label="' . esc_attr( $aria_label ) . '"' : ''; ?>>
	<span><?php echo esc_html( $label ); ?></span>
	<span class="button__icon" aria-hidden="true"><img src="<?php echo esc_url( $icon ); ?>" width="12" height="12" alt=""></span>
</a>
