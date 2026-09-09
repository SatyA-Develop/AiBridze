<?php
/**
 * Reusable archive grid.
 *
 * @package AIBridze
 * @var array $args Grid settings.
 */

$post_type   = $args['post_type'] ?? get_post_type();
$title       = $args['title'] ?? post_type_archive_title( '', false );
$description = $args['description'] ?? '';
$taxonomy    = $args['taxonomy'] ?? '';
$term_id     = $args['term_id'] ?? 0;
$query_args  = array(
	'post_type'      => $post_type,
	'posts_per_page' => -1,
	'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
);

if ( $taxonomy && $term_id ) {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => $taxonomy,
			'field'    => 'term_id',
			'terms'    => (int) $term_id,
		),
	);
}

$items       = get_posts(
	$query_args
);
?>
<section class="listing-hero">
	<div class="container">
		<p class="listing-hero__eyebrow section-callout"><?php esc_html_e( 'AIBridze', 'aibridze' ); ?></p>
		<h1><?php echo esc_html( $title ); ?></h1>
		<?php if ( $description ) : ?><p><?php echo esc_html( $description ); ?></p><?php endif; ?>
	</div>
</section>
<section class="listing-section">
	<div class="container card-grid">
		<?php foreach ( $items as $item ) : ?>
			<article class="content-card">
				<a href="<?php echo esc_url( get_permalink( $item ) ); ?>">
					<h2><?php echo esc_html( get_the_title( $item ) ); ?></h2>
					<p><?php echo esc_html( get_the_excerpt( $item ) ?: __( 'Discover how AIBridze can help your business grow.', 'aibridze' ) ); ?></p>
					<span><?php esc_html_e( 'Explore', 'aibridze' ); ?> →</span>
				</a>
			</article>
		<?php endforeach; ?>
	</div>
</section>
