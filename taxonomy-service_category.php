<?php
get_header();
$term = get_queried_object();
$tid = (int) $term->term_id;
$v = static fn(string $key, $fallback = '') => aibridze_category_value($tid, $key, $fallback);
$rows = static fn(string $key) => aibridze_category_rows($tid, $key);
$ai_studio = get_page_by_path('ai-studio', OBJECT, 'service');
$services = get_posts(array('post_type' => 'service', 'posts_per_page' => -1, 'post_status' => 'publish', 'post__not_in' => $ai_studio ? array($ai_studio->ID) : array(), 'orderby' => array('menu_order' => 'ASC', 'title' => 'ASC'), 'tax_query' => array(array('taxonomy' => 'service_category', 'field' => 'term_id', 'terms' => $tid))));
$heading = static function (string $prefix) use ($v): void {
	if ($v($prefix . '_eyebrow'))
		echo '<p class="category-kicker section-callout">' . esc_html($v($prefix . '_eyebrow')) . '</p>';
	if ($v($prefix . '_title'))
		echo '<h2 class="category-heading">' . esc_html($v($prefix . '_title')) . '</h2>';
	if ($v($prefix . '_description'))
		echo '<p class="category-description">' . esc_html($v($prefix . '_description')) . '</p>';
};
$hero_image = $v('hero_image');
?>
<main class="category-page category-page--<?php echo esc_attr($term->slug); ?>">
	<section class="category-hero"
		style="--category-hero:url('<?php echo esc_url($hero_image); ?>');--category-hero-mobile:url('<?php echo esc_url($v('hero_mobile_image', $hero_image)); ?>')">
		<div class="container category-hero__inner">
			<?php if ($v('hero_eyebrow')): ?>
				<p class="category-kicker section-callout"><?php echo esc_html($v('hero_eyebrow')); ?></p><?php endif; ?>
			<h1><?php echo esc_html($v('hero_title', $term->name)); ?></h1>
			<?php if ($v('hero_description', $term->description)): ?>
				<p><?php echo esc_html($v('hero_description', $term->description)); ?></p><?php endif; ?>
			<?php if ($v('hero_cta_label'))
				get_template_part('template-parts/components/button', null, array('label' => $v('hero_cta_label'), 'url' => $v('hero_cta_url', '#contact'), 'class' => 'service-button', 'aria_label' => $v('hero_cta_label'))); ?>
		</div>
	</section>

	<?php if ($v('ecosystem_title') || $rows('ecosystem_items')): ?>
		<section class="category-section category-section--dark category-overview">
			<div class="container">
				<div class="category-overview__top">
					<div><?php $heading('ecosystem'); ?></div><?php if ($v('ecosystem_image')): ?><img
							src="<?php echo esc_url($v('ecosystem_image')); ?>" alt="" loading="lazy"><?php endif; ?>
				</div>
				<div class="category-tile-grid"><?php foreach ($rows('ecosystem_items') as $item): ?>
						<article><?php if (!empty($item[2])): ?><img src="<?php echo esc_url($item[2]); ?>" alt=""
									loading="lazy"><?php endif; ?>
							<h3><?php echo esc_html($item[0] ?? ''); ?></h3>
							<p><?php echo esc_html($item[1] ?? ''); ?></p>
						</article><?php endforeach; ?>
				</div>
			</div>
		</section><?php endif; ?>

	<section class="category-section category-services" data-category-carousel>
		<div class="container">
			<div class="category-services__header">
				<div><?php $heading('services'); ?></div>
				<div class="category-carousel__controls" aria-label="Service carousel controls"><button type="button"
						data-carousel-prev aria-label="Previous services"><span aria-hidden="true">‹</span></button><button
						type="button" data-carousel-next aria-label="Next services"><span aria-hidden="true">›</span></button></div>
			</div>
			<div class="category-card-track" data-carousel-track><?php foreach ($services as $service):
				$img = (string) get_post_meta($service->ID, aibridze_service_meta_key('hero_image'), true);
				if (!$img)
					$img = get_the_post_thumbnail_url($service, 'large');
				$description = (string) get_post_meta($service->ID, aibridze_service_meta_key('category_card_description'), true);
				if (!$description)
					$description = get_the_excerpt($service) ?: wp_strip_all_tags($service->post_content);
				?><a class="category-service-card"
						href="<?php echo esc_url(get_permalink($service)); ?>"><?php if ($img): ?><img
								src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title($service)); ?>"
								loading="lazy"><?php endif; ?>
						<div>
							<h3><?php echo esc_html(get_the_title($service)); ?></h3>
							<p><?php echo esc_html($description); ?></p><span>Explore
								<?php echo esc_html(get_the_title($service)); ?> <img
									src="<?php echo esc_url(get_theme_file_uri('/assets/images/service-categories/ai-development/arrow.png')); ?>"
									width="11" height="15" alt=""></span>
						</div>
					</a><?php endforeach; ?></div>
		</div>
	</section>

	<?php if ($v('value_title') || $rows('value_items')): ?>
		<section class="category-section category-section--dark category-value-scroll" data-value-scroll>
			<div class="category-value-scroll__sticky">
				<div class="container"><?php $heading('value'); ?><?php if ($v('value_image')): ?>
						<figure class="category-value-scroll__image"><img src="<?php echo esc_url($v('value_image')); ?>" alt=""
								loading="lazy"></figure><?php endif; ?>
				</div>
				<div class="category-value-viewport">
					<div class="category-value-grid" data-value-track><?php foreach ($rows('value_items') as $item): ?>
							<article>
								<div>
									<h3><?php echo esc_html($item[0] ?? ''); ?></h3>
									<p><?php echo esc_html($item[1] ?? ''); ?></p>
									<?php foreach (array_filter(array_map('trim', explode(',', $item[2] ?? ''))) as $tag): ?><span><?php echo esc_html($tag); ?></span><?php endforeach; ?>
								</div>
							</article><?php endforeach; ?>
					</div>
				</div>
			</div>
		</section><?php endif; ?>

	<?php if ($v('solutions_title') || $rows('solutions_items')): ?>
		<section class="category-section category-split category-solutions">
			<div class="container category-split__inner">
				<div><?php $heading('solutions'); ?></div>
				<div class="category-accordion" data-single-accordion><?php foreach ($rows('solutions_items') as $item): ?>
						<details>
							<summary><?php echo esc_html($item[0] ?? ''); ?></summary>
							<p><?php echo esc_html($item[1] ?? ''); ?></p>
						</details><?php endforeach; ?>
				</div>
			</div>
		</section><?php endif; ?>

	<?php if ($v('technology_title') || $rows('technology_items')):
		$technology_rows = array('Row 1' => array(), 'Row 2' => array());
		foreach ($rows('technology_items') as $item) {
			$group = ($item[1] ?? '') === 'Row 2' ? 'Row 2' : 'Row 1';
			$technology_rows[$group][] = $item;
		} ?>
		<section class="category-section category-section--dark category-technology">
			<div class="container"><?php $heading('technology'); ?></div>
			<div class="category-technology__rows">
				<?php foreach ($technology_rows as $group => $items):
					if (!$items)
						continue; ?>
					<div class="category-technology__viewport">
						<div
							class="category-logo-grid category-logo-grid--marquee <?php echo 'Row 2' === $group ? 'is-reverse' : ''; ?>">
							<?php for ($copy = 0; $copy < 2; $copy++):
								foreach ($items as $item): ?>
									<div aria-hidden="<?php echo $copy ? 'true' : 'false'; ?>"><?php if (!empty($item[2])): ?><img
												src="<?php echo esc_url($item[2]); ?>" alt="" loading="eager"
												decoding="async"><?php endif; ?><span><?php echo esc_html($item[0] ?? ''); ?></span></div>
								<?php endforeach; endfor; ?>
						</div>
					</div><?php endforeach; ?>
			</div>
		</section><?php endif; ?>

	<?php if ($v('process_title') || $rows('process_items')): ?>
		<section class="category-section">
			<div class="container"><?php $heading('process'); ?>
				<ol class="category-process"><?php foreach ($rows('process_items') as $item): ?>
						<li>
							<h3><?php echo esc_html($item[0] ?? ''); ?></h3>
							<p><?php echo esc_html($item[1] ?? ''); ?></p>
						</li><?php endforeach; ?>
				</ol>
			</div>
		</section><?php endif; ?>

	<?php if ($v('industries_title') || $rows('industries_items')): ?>
		<section class="category-section category-section--dark category-split category-industries">
			<div class="container category-split__inner">
				<div><?php $heading('industries'); ?></div>
				<div class="category-accordion" data-industry-accordion>
					<?php foreach ($rows('industries_items') as $i => $item): ?>
						<details <?php echo 0 === $i ? 'open' : ''; ?>>
							<summary><?php echo esc_html(($i + 1) . '. ' . ($item[0] ?? '')); ?></summary>
							<ul><?php foreach (array_filter(array_map('trim', explode(',', $item[1] ?? ''))) as $use): ?>
									<li><?php if ($v('industries_icon')): ?><img src="<?php echo esc_url($v('industries_icon')); ?>"
												alt="" loading="eager"><?php endif; ?><span><?php echo esc_html($use); ?></span></li>
								<?php endforeach; ?>
							</ul>
						</details><?php endforeach; ?>
				</div>
			</div>
		</section><?php endif; ?>

	<?php if ($v('cta_title')): ?>
		<section class="category-section category-section--dark">
			<div class="container">
				<div class="category-cta" style="--cta-image:url('<?php echo esc_url($v('cta_image')); ?>')">
					<div class="category-cta__copy">
						<h2><?php echo esc_html($v('cta_title')); ?></h2>
						<p><?php echo esc_html($v('cta_description')); ?></p><?php if ($v('cta_label')): ?><a
								class="category-button"
								href="<?php echo esc_url($v('cta_url', '#contact')); ?>"><?php echo esc_html($v('cta_label')); ?>
								→</a><?php endif; ?>
					</div><?php if ('ai-development' === $term->slug && $v('cta_image')): ?><img class="category-cta__art"
							src="<?php echo esc_url($v('cta_image')); ?>" alt="" loading="eager" decoding="async"><?php endif; ?>
				</div>
			</div>
		</section><?php endif; ?>

	<?php if ($v('expertise_title') || $rows('expertise_items')): ?>
		<section class="category-section category-expertise">
			<div class="container">
				<div class="category-expertise__heading"><?php $heading('expertise'); ?></div>
				<div class="category-tile-grid category-tile-grid--five">
					<?php foreach ($rows('expertise_items') as $item): ?>
						<article><?php if (!empty($item[2])): ?><span class="category-expertise__icon"><img
										src="<?php echo esc_url($item[2]); ?>" alt="" loading="lazy"></span><?php endif; ?>
							<h3><?php echo esc_html($item[0] ?? ''); ?></h3>
							<p><?php echo esc_html($item[1] ?? ''); ?></p>
						</article><?php endforeach; ?>
				</div>
			</div>
		</section><?php endif; ?>

	<?php if ($v('compliance_title') || $rows('compliance_items')):
		$compliance_items = $rows('compliance_items'); ?>
		<section class="category-section category-section--dark category-compliance" data-compliance-section>
			<div class="container">
				<div class="category-compliance__heading"><?php $heading('compliance'); ?></div>
				<div class="category-compliance-grid"><?php foreach ($compliance_items as $item): ?>
						<article><?php if (!empty($item[2])): ?>
								<div class="category-compliance__logo"><img src="<?php echo esc_url($item[2]); ?>" alt="" loading="lazy">
								</div><?php endif; ?>
							<h3><?php echo esc_html($item[0] ?? ''); ?></h3><?php if (!empty($item[1])): ?>
								<p><?php echo esc_html($item[1]); ?></p><?php endif; ?>
						</article><?php endforeach; ?>
				</div><?php if (count($compliance_items) > 4): ?><button class="category-compliance__toggle" type="button"
						data-compliance-toggle aria-expanded="false"><span>View More</span><i
							aria-hidden="true"></i></button><?php endif; ?>
			</div>
		</section><?php endif; ?>

	<?php if ($v('faq_title') || $rows('faq_items'))
		get_template_part('template-parts/sections/faq-contact', null, array(
			'section_id' => 'contact',
			'eyebrow' => $v('faq_eyebrow', 'FAQs'),
			'title' => $v('faq_title'),
			'faqs' => $rows('faq_items'),
			'contact_title' => $v('contact_title', 'Tell Us About Your Project'),
			'contact_description' => $v('contact_description'),
			'submit_label' => $v('contact_label', 'Submit'),
			'full_name_label' => $v('contact_full_name_label', 'Full Name'),
			'full_name_placeholder' => $v('contact_full_name_placeholder', 'Enter your name'),
			'email_label' => $v('contact_email_label', 'Email'),
			'email_placeholder' => $v('contact_email_placeholder', 'Enter work email'),
			'designation_label' => $v('contact_designation_label', 'Designation'),
			'designation_placeholder' => $v('contact_designation_placeholder', 'Enter designation'),
			'budget_label' => $v('contact_budget_label', 'Your Preferred Budget Range'),
			'budget_options' => preg_split('/\r\n|\r|\n/', $v('contact_budget_options', '$5k–$15k' . "\n" . '$15k–$50k' . "\n" . '$50k–$100k' . "\n" . '$100k+')),
			'message_label' => $v('contact_message_label', 'How can we help you?'),
			'message_placeholder' => $v('contact_message_placeholder', 'Write Here...'),
			'security_text' => $v('contact_security_text', 'Share with Confidence. Fully NDA-Protected.'),
		)); ?>
</main>
<?php get_footer(); ?>