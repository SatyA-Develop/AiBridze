<?php
/** Dynamic recent posts, categories, archive grid, and pagination. */

$fallbacks    = array( 'about/why-purpose.png', 'service-categories/technology-consulting.png', 'about/why-scale.png', 'service-categories/web-app-development.png', 'about/why-transparent.png', 'service-categories/mobile-app-development.png', 'process/step-2.jpg', 'about/why-listen.png', 'about/why-partners.png' );
$category     = isset( $_GET['blog_category'] ) ? sanitize_title( wp_unslash( $_GET['blog_category'] ) ) : '';
$paged        = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$archive_args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 9, 'paged' => $paged, 'ignore_sticky_posts' => true );
if ( $category ) {
	$archive_args['category_name'] = $category;
}
$archive_query = new WP_Query( $archive_args );
$categories    = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ) );
$posts_page_id = (int) get_option( 'page_for_posts' );
$archive_url   = is_home() && $posts_page_id ? get_permalink( $posts_page_id ) : get_permalink();
$archive_url   = $archive_url ?: home_url( '/blogs/' );
$selected_term = $category ? get_term_by( 'slug', $category, 'category' ) : null;
?>
<?php get_template_part( 'template-parts/content/recent-posts' ); ?>

<section class="blog-categories" aria-labelledby="blog-categories-title" data-blog-categories>
	<h2 id="blog-categories-title"><?php esc_html_e( 'Explore by Categories', 'aibridze' ); ?></h2>
	<div class="blog-categories__navigation">
		<button type="button" data-category-previous aria-label="<?php esc_attr_e( 'Previous categories', 'aibridze' ); ?>">‹</button>
		<div class="blog-categories__scroller" data-category-scroller>
			<a class="<?php echo $category ? '' : 'is-active'; ?>" data-blog-category="" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'All Blogs', 'aibridze' ); ?></a>
			<?php foreach ( $categories as $term ) : ?><a class="<?php echo $category === $term->slug ? 'is-active' : ''; ?>" data-blog-category="<?php echo esc_attr( $term->slug ); ?>" href="<?php echo esc_url( add_query_arg( 'blog_category', $term->slug, $archive_url ) ); ?>"><?php echo esc_html( $term->name ); ?></a><?php endforeach; ?>
		</div>
		<button type="button" data-category-next aria-label="<?php esc_attr_e( 'Next categories', 'aibridze' ); ?>">›</button>
	</div>
</section>

<section class="all-blog-posts" aria-labelledby="all-blog-posts-title" data-blog-results>
	<div class="all-blog-posts__inner">
		<h2 id="all-blog-posts-title" data-blog-results-title><?php echo esc_html( $selected_term ? $selected_term->name : __( 'All Blog Posts', 'aibridze' ) ); ?></h2>
		<div class="all-blog-posts__grid" data-blog-grid>
			<?php foreach ( $archive_query->posts as $index => $post_item ) :
				$post_categories = get_the_category( $post_item->ID );
				$post_category   = $post_categories ? $post_categories[0]->name : __( 'Technology', 'aibridze' );
				$image           = has_post_thumbnail( $post_item ) ? get_the_post_thumbnail_url( $post_item, 'large' ) : get_theme_file_uri( '/assets/images/' . $fallbacks[ $index % count( $fallbacks ) ] );
				?>
				<article class="all-blog-posts__card">
					<a class="all-blog-posts__image" href="<?php echo esc_url( get_permalink( $post_item ) ); ?>"><img src="<?php echo esc_url( $image ); ?>" width="405" height="240" alt="<?php echo esc_attr( get_the_title( $post_item ) ); ?>"></a>
					<div class="recent-posts__meta"><span><?php echo esc_html( sprintf( __( 'By %s', 'aibridze' ), get_the_author_meta( 'display_name', $post_item->post_author ) ?: __( 'AIBridze Team', 'aibridze' ) ) ); ?></span><i aria-hidden="true"></i><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_item ) ); ?>"><?php echo esc_html( get_the_date( 'l, j M Y', $post_item ) ); ?></time></div>
					<h3><a href="<?php echo esc_url( get_permalink( $post_item ) ); ?>"><?php echo esc_html( get_the_title( $post_item ) ); ?></a></h3>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_item ), 20, '…' ) ); ?></p>
					<span class="recent-posts__badge"><?php echo esc_html( $post_category ); ?></span>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
		$pagination = paginate_links(
			array(
				'total'     => $archive_query->max_num_pages,
				'current'   => $paged,
				'type'      => 'array',
				'prev_next' => false,
				'end_size'  => 3,
				'mid_size'  => 2,
				'add_args'  => $category ? array( 'blog_category' => $category ) : array(),
			)
		);
		?><div data-blog-pagination><?php if ( $pagination ) :
			$previous_url = 1 < $paged ? get_pagenum_link( $paged - 1 ) : '';
			$next_url     = $paged < $archive_query->max_num_pages ? get_pagenum_link( $paged + 1 ) : '';
			if ( $category ) {
				$previous_url = $previous_url ? add_query_arg( 'blog_category', $category, $previous_url ) : '';
				$next_url     = $next_url ? add_query_arg( 'blog_category', $category, $next_url ) : '';
			}
			?>
			<nav class="blog-pagination blog-pagination--desktop" aria-label="<?php esc_attr_e( 'Blog pagination', 'aibridze' ); ?>">
				<?php if ( $previous_url ) : ?><a class="page-numbers prev" href="<?php echo esc_url( $previous_url ); ?>">← <?php esc_html_e( 'Previous', 'aibridze' ); ?></a><?php else : ?><span class="page-numbers prev is-disabled">← <?php esc_html_e( 'Previous', 'aibridze' ); ?></span><?php endif; ?>
				<div class="blog-pagination__numbers"><?php foreach ( $pagination as $page_link ) : echo wp_kses_post( $page_link ); endforeach; ?></div>
				<?php if ( $next_url ) : ?><a class="page-numbers next" href="<?php echo esc_url( $next_url ); ?>"><?php esc_html_e( 'Next', 'aibridze' ); ?> →</a><?php else : ?><span class="page-numbers next is-disabled"><?php esc_html_e( 'Next', 'aibridze' ); ?> →</span><?php endif; ?>
			</nav>
			<nav class="blog-pagination blog-pagination--mobile" aria-label="<?php esc_attr_e( 'Mobile blog pagination', 'aibridze' ); ?>">
				<?php if ( $previous_url ) : ?><a class="page-numbers prev" href="<?php echo esc_url( $previous_url ); ?>">← <?php esc_html_e( 'Previous', 'aibridze' ); ?></a><?php else : ?><span class="page-numbers prev is-disabled">← <?php esc_html_e( 'Previous', 'aibridze' ); ?></span><?php endif; ?>
				<div class="blog-pagination__numbers"><span class="page-numbers current"><?php echo esc_html( $paged ); ?></span><?php if ( $paged < $archive_query->max_num_pages ) : ?><a class="page-numbers" href="<?php echo esc_url( $next_url ); ?>"><?php echo esc_html( $paged + 1 ); ?></a><?php endif; ?><?php if ( $paged + 1 < $archive_query->max_num_pages ) : ?><span class="page-numbers dots">…</span><?php endif; ?></div>
				<?php if ( $next_url ) : ?><a class="page-numbers next" href="<?php echo esc_url( $next_url ); ?>"><?php esc_html_e( 'Next', 'aibridze' ); ?> →</a><?php else : ?><span class="page-numbers next is-disabled"><?php esc_html_e( 'Next', 'aibridze' ); ?> →</span><?php endif; ?>
			</nav>
		<?php endif; ?></div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
