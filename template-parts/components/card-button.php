<?php
/** Shared link for service and portfolio cards. */
$args = wp_parse_args( $args ?? array(), array( 'url' => '', 'label' => '' ) );
?>
<a class="card-button" href="<?php echo esc_url( $args['url'] ); ?>">
	<span class="card-button__label"><?php echo esc_html( $args['label'] ); ?></span>
	<svg class="card-button__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true" focusable="false"><path d="M0.625327 5.625H11.042M6.04199 10.625L11.042 5.625L6.04199 0.625" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
</a>
