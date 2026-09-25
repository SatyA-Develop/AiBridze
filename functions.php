<?php
/**
 * Theme setup and asset loading.
 *
 * @package AIBridze
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once get_theme_file_path('/inc/service-page-fields.php');
require_once get_theme_file_path('/inc/service-card-gif.php');
require_once get_theme_file_path('/inc/service-category-page-fields.php');
require_once get_theme_file_path('/inc/content-authors.php');
require_once get_theme_file_path('/inc/vector-icons.php');
require_once get_theme_file_path('/inc/navigation-state.php');
require_once get_theme_file_path('/inc/svg-uploads.php');
require_once get_theme_file_path('/inc/enquiry-email.php');
require_once get_theme_file_path('/inc/form-submissions.php');

function aibridze_setup(): void
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', array('style', 'script', 'gallery', 'caption'));
	register_nav_menus(
		array(
			'primary' => __('Primary navigation', 'aibridze'),
		)
	);
}
add_action('after_setup_theme', 'aibridze_setup');

/**
 * Register a tightly scoped role for people who only manage their own blogs.
 */
function aibridze_register_blog_author_role(): void
{
	$allowed_caps = array(
		'read' => true,
		'edit_posts' => true,
		'edit_published_posts' => true,
		'publish_posts' => true,
		'delete_posts' => true,
		'delete_published_posts' => true,
		'upload_files' => true,
		'level_2' => true,
		'level_1' => true,
		'level_0' => true,
	);
	$role = get_role('blog_author');
	if (!$role) {
		add_role('blog_author', __('Blog Author', 'aibridze'), $allowed_caps);
		$role = get_role('blog_author');
	}
	if (!$role) {
		return;
	}
	foreach (array_keys($role->capabilities) as $capability) {
		if (!isset($allowed_caps[$capability])) {
			$role->remove_cap($capability);
		}
	}
	foreach ($allowed_caps as $capability => $grant) {
		$role->add_cap($capability, $grant);
	}
}
add_action('init', 'aibridze_register_blog_author_role', 5);

function aibridze_is_blog_author(?WP_User $user = null): bool
{
	$user = $user ?: wp_get_current_user();
	return in_array('blog_author', (array) $user->roles, true);
}

/** Keep blog authors inside Posts and Media in the dashboard. */
function aibridze_limit_blog_author_menu(): void
{
	if (!aibridze_is_blog_author()) {
		return;
	}
	foreach (array('edit.php?post_type=page', 'edit-comments.php', 'edit.php?post_type=service', 'edit.php?post_type=industry', 'edit.php?post_type=portfolio', 'edit.php?post_type=testimonial', 'edit.php?post_type=video_testimonial', 'edit.php?post_type=trusted_business', 'edit.php?post_type=leader', 'edit.php?post_type=team', 'edit.php?post_type=opportunity', 'edit.php?post_type=job_application', 'themes.php', 'plugins.php', 'users.php', 'tools.php', 'options-general.php') as $menu_slug) {
		remove_menu_page($menu_slug);
	}
	remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
	remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
}
add_action('admin_menu', 'aibridze_limit_blog_author_menu', 999);

function aibridze_guard_blog_author_screens(WP_Screen $screen): void
{
	if (aibridze_is_blog_author() && $screen->post_type && !in_array($screen->post_type, array('post', 'attachment'), true)) {
		wp_die(esc_html__('Blog authors can only manage blog posts and media.', 'aibridze'), 403);
	}
}
add_action('current_screen', 'aibridze_guard_blog_author_screens');

/** Deny direct editing or deletion of non-blog content for blog authors. */
function aibridze_limit_blog_author_meta_caps(array $caps, string $cap, int $user_id, array $args): array
{
	$user = get_userdata($user_id);
	if (!$user || !aibridze_is_blog_author($user) || !in_array($cap, array('edit_post', 'delete_post', 'read_post'), true) || empty($args[0])) {
		return $caps;
	}
	$post = get_post((int) $args[0]);
	if ($post && !in_array($post->post_type, array('post', 'attachment'), true)) {
		return array('do_not_allow');
	}
	return $caps;
}
add_filter('map_meta_cap', 'aibridze_limit_blog_author_meta_caps', 10, 4);

/** Prevent direct form or API requests from creating non-blog content. */
function aibridze_limit_blog_author_inserts(bool $maybe_empty, array $postarr): bool
{
	if (aibridze_is_blog_author() && !in_array($postarr['post_type'] ?? 'post', array('post', 'attachment'), true)) {
		return true;
	}
	return $maybe_empty;
}
add_filter('wp_insert_post_empty_content', 'aibridze_limit_blog_author_inserts', 10, 2);

function aibridze_limit_blog_author_rest($result, WP_REST_Server $server, WP_REST_Request $request)
{
	if (!aibridze_is_blog_author() || 'GET' === $request->get_method()) {
		return $result;
	}
	if (preg_match('#^/wp/v2/(service|industry|portfolio|testimonial|video_testimonial|trusted_business|leader|team|opportunity|job_application|pages)(?:/|$)#', $request->get_route())) {
		return new WP_Error('aibridze_blog_author_forbidden', __('Blog authors can only manage blog posts and media.', 'aibridze'), array('status' => 403));
	}
	return $result;
}
add_filter('rest_pre_dispatch', 'aibridze_limit_blog_author_rest', 10, 3);

function aibridze_assets(): void
{
	$style_path = get_theme_file_path('/assets/css/main.css');
	$script_path = get_theme_file_path('/assets/js/header.js');
	$footer_script_path = get_theme_file_path('/assets/js/footer.js');
	$modal_script_path = get_theme_file_path('/assets/js/consultation-modal.js');
	$transformation_script_path = get_theme_file_path('/assets/js/transformation.js');
	$manual_cost_script_path = get_theme_file_path('/assets/js/manual-cost.js');
	$services_stack_script_path = get_theme_file_path('/assets/js/services-stack.js');
	$process_script_path = get_theme_file_path('/assets/js/process.js');
	$portfolio_stack_script_path = get_theme_file_path('/assets/js/portfolio-stack.js');
	$success_cta_script_path = get_theme_file_path('/assets/js/success-cta.js');
	$lazy_media_script_path = get_theme_file_path('/assets/js/lazy-media.js');
	$customer_stories_script_path = get_theme_file_path('/assets/js/customer-stories.js');
	$faq_script_path = get_theme_file_path('/assets/js/faq.js');
	$industries_ring_script_path = get_theme_file_path('/assets/js/industries-ring.js');
	$about_why_script_path = get_theme_file_path('/assets/js/about-why.js');
	$blog_archive_script_path = get_theme_file_path('/assets/js/blog-archive.js');
	$careers_script_path = get_theme_file_path('/assets/js/careers.js');
	$contact_videos_script_path = get_theme_file_path('/assets/js/contact-video-testimonials.js');
	$service_detail_script_path = get_theme_file_path('/assets/js/service-detail.js');
	wp_enqueue_style('aibridze-main', get_theme_file_uri('/assets/css/main.css'), array(), (string) filemtime($style_path));
	wp_enqueue_script('aibridze-carousel-autoplay', get_theme_file_uri('/assets/js/carousel-autoplay.js'), array(), (string) filemtime(get_theme_file_path('/assets/js/carousel-autoplay.js')), true);
	wp_enqueue_script('aibridze-lottie', get_theme_file_uri('/assets/js/vendor/lottie-light.min.js'), array(), '5.12.2', true);
	wp_enqueue_script('aibridze-header', get_theme_file_uri('/assets/js/header.js'), array('aibridze-lottie'), (string) filemtime($script_path), true);
	wp_enqueue_script('aibridze-footer', get_theme_file_uri('/assets/js/footer.js'), array(), (string) filemtime($footer_script_path), true);
	// Copy and browser shortcut restrictions are temporarily disabled for everyone.
	wp_enqueue_script('aibridze-form-validation', get_theme_file_uri('/assets/js/form-validation.js'), array(), (string) filemtime(get_theme_file_path('/assets/js/form-validation.js')), true);
	wp_enqueue_script('aibridze-consultation-modal', get_theme_file_uri('/assets/js/consultation-modal.js'), array(), (string) filemtime($modal_script_path), true);
	wp_enqueue_script('aibridze-transformation', get_theme_file_uri('/assets/js/transformation.js'), array(), (string) filemtime($transformation_script_path), true);
	wp_enqueue_script('aibridze-manual-cost', get_theme_file_uri('/assets/js/manual-cost.js'), array(), (string) filemtime($manual_cost_script_path), true);
	wp_enqueue_script('aibridze-services-stack', get_theme_file_uri('/assets/js/services-stack.js'), array(), (string) filemtime($services_stack_script_path), true);
	wp_enqueue_script('aibridze-process', get_theme_file_uri('/assets/js/process.js'), array(), (string) filemtime($process_script_path), true);
	wp_enqueue_script('aibridze-portfolio-stack', get_theme_file_uri('/assets/js/portfolio-stack.js'), array(), (string) filemtime($portfolio_stack_script_path), true);
	wp_enqueue_script('aibridze-success-cta', get_theme_file_uri('/assets/js/success-cta.js'), array(), (string) filemtime($success_cta_script_path), true);
	wp_enqueue_script('aibridze-lazy-media', get_theme_file_uri('/assets/js/lazy-media.js'), array(), (string) filemtime($lazy_media_script_path), true);
	wp_enqueue_script('aibridze-customer-stories', get_theme_file_uri('/assets/js/customer-stories.js'), array('aibridze-lazy-media'), (string) filemtime($customer_stories_script_path), true);
	wp_enqueue_script('aibridze-faq', get_theme_file_uri('/assets/js/faq.js'), array(), (string) filemtime($faq_script_path), true);
	wp_enqueue_script('aibridze-gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js', array(), '3.13.0', true);
	wp_enqueue_script('aibridze-gsap-draggable', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/Draggable.min.js', array('aibridze-gsap'), '3.13.0', true);
	wp_enqueue_script('aibridze-industries-ring', get_theme_file_uri('/assets/js/industries-ring.js'), array('aibridze-gsap-draggable'), (string) filemtime($industries_ring_script_path), true);
	wp_enqueue_script('aibridze-about-why', get_theme_file_uri('/assets/js/about-why.js'), array(), (string) filemtime($about_why_script_path), true);
	wp_enqueue_script('aibridze-blog-archive', get_theme_file_uri('/assets/js/blog-archive.js'), array(), (string) filemtime($blog_archive_script_path), true);
	if (is_singular('post')) {
		wp_enqueue_script('aibridze-blog-quote', get_theme_file_uri('/assets/js/blog-quote.js'), array(), (string) filemtime(get_theme_file_path('/assets/js/blog-quote.js')), true);
	}
	wp_enqueue_script('aibridze-careers', get_theme_file_uri('/assets/js/careers.js'), array('aibridze-form-validation'), (string) filemtime($careers_script_path), true);
	wp_enqueue_style('aibridze-plyr', get_theme_file_uri('/assets/vendor/plyr/plyr.css'), array(), '3.7.8');
	wp_enqueue_script('aibridze-plyr', get_theme_file_uri('/assets/vendor/plyr/plyr.js'), array(), '3.7.8', true);
	wp_enqueue_script('aibridze-contact-videos', get_theme_file_uri('/assets/js/contact-video-testimonials.js'), array('aibridze-plyr'), (string) filemtime($contact_videos_script_path), true);
	if (is_singular('service')) {
		wp_enqueue_script('aibridze-service-detail', get_theme_file_uri('/assets/js/service-detail.js'), array(), (string) filemtime($service_detail_script_path), true);
	}
	wp_localize_script(
		'aibridze-blog-archive',
		'aibridzeBlogArchive',
		array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('aibridze_filter_blogs'),
		)
	);
}
add_action('wp_enqueue_scripts', 'aibridze_assets');

/** AJAX category filtering and archive pagination. */
function aibridze_ajax_filter_blogs(): void
{
	check_ajax_referer('aibridze_filter_blogs', 'nonce');
	$category = isset($_POST['category']) ? sanitize_title(wp_unslash($_POST['category'])) : '';
	$paged = max(1, absint($_POST['paged'] ?? 1));
	$args = array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 9, 'paged' => $paged, 'ignore_sticky_posts' => true);
	if ($category) {
		$args['category_name'] = $category;
	}
	$query = new WP_Query($args);
	$fallbacks = array('about/why-purpose.png', 'service-categories/technology-consulting.png', 'about/why-scale.png', 'service-categories/web-app-development.png', 'about/why-transparent.png', 'service-categories/mobile-app-development.png', 'process/step-2.jpg', 'about/why-listen.png', 'about/why-partners.png');
	$items = array();
	// JSON text must contain characters, not HTML entities: the client escapes
	// these values when rendering cards. WordPress title filters encode dashes.
	$plain_text = static function (string $value): string {
		return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	};
	foreach ($query->posts as $index => $post_item) {
		$terms = get_the_category($post_item->ID);
		$image = has_post_thumbnail($post_item) ? get_the_post_thumbnail_url($post_item, 'large') : get_theme_file_uri('/assets/images/' . $fallbacks[$index % count($fallbacks)]);
		$items[] = array(
			'title' => $plain_text(get_the_title($post_item)),
			'url' => get_permalink($post_item),
			'image' => $image,
			'author' => $plain_text(sprintf(__('By %s', 'aibridze'), get_the_author_meta('display_name', $post_item->post_author) ?: __('AIBridze Team', 'aibridze'))),
			'date' => $plain_text(get_the_date('l, j M Y', $post_item)),
			'datetime' => get_the_date(DATE_W3C, $post_item),
			'excerpt' => $plain_text(wp_trim_words(get_the_excerpt($post_item), 20, '…')),
			'category' => $plain_text($terms ? $terms[0]->name : __('Technology', 'aibridze')),
		);
	}
	$term = $category ? get_term_by('slug', $category, 'category') : null;
	wp_send_json_success(
		array(
			'items' => $items,
			'currentPage' => min($paged, max(1, (int) $query->max_num_pages)),
			'totalPages' => (int) $query->max_num_pages,
			'heading' => $plain_text($term ? $term->name : __('All Blog Posts', 'aibridze')),
		)
	);
}
add_action('wp_ajax_nopriv_aibridze_filter_blogs', 'aibridze_ajax_filter_blogs');
add_action('wp_ajax_aibridze_filter_blogs', 'aibridze_ajax_filter_blogs');

/** Add varied local demo articles once so the archive can be reviewed immediately. */
function aibridze_seed_demo_blog_posts(): void
{
	if (get_option('aibridze_demo_blog_posts_v1')) {
		return;
	}

	$articles = array(
		array('Innovative Approaches to Software Development with AI', 'AI Development', 'Explore practical ways development teams can use AI to plan, build, test, and improve modern software products.'),
		array('Leading the Charge in API Development for Future Applications', 'API Development', 'Learn how secure, scalable APIs create reliable foundations for connected applications and intelligent digital experiences.'),
		array('Creating a Robust API Integration Framework with AI', 'AI Integration', 'A structured approach to designing resilient integrations with intelligent monitoring, validation, and automated recovery.'),
		array('How Generative AI Is Reshaping Enterprise Workflows', 'Generative AI', 'Discover where generative AI delivers measurable value across operations, knowledge management, support, and decision-making.'),
		array('Building Production-Ready AI Agents for Business', 'AI Agents', 'Understand the architecture, guardrails, observability, and human oversight required for dependable business AI agents.'),
		array('A Practical Guide to Modern Cloud Application Architecture', 'Cloud Computing', 'Review proven architectural patterns for building cloud applications that remain secure, resilient, and easy to scale.'),
		array('Mobile App Performance Strategies That Improve Retention', 'Mobile Development', 'See how faster startup times, responsive interfaces, and efficient data handling can improve mobile engagement.'),
		array('Designing Digital Products Around Real User Needs', 'UI/UX Design', 'Learn how research, prototyping, and usability testing turn complex requirements into intuitive product experiences.'),
		array('Machine Learning Models: From Experiment to Production', 'Machine Learning', 'Follow the essential steps for validating, deploying, monitoring, and continuously improving machine learning models.'),
		array('Cybersecurity Essentials for Growing Digital Businesses', 'Cybersecurity', 'Build a practical security foundation with identity controls, secure development practices, monitoring, and response planning.'),
		array('Why Custom Software Creates a Competitive Advantage', 'Custom Software', 'Understand when tailored software can streamline unique operations and unlock capabilities that packaged tools cannot provide.'),
		array('Automating Repetitive Operations Without Losing Control', 'Automation', 'Identify strong automation opportunities while maintaining transparency, exception handling, and meaningful human oversight.'),
		array('Data Engineering Foundations for Reliable AI Solutions', 'Data Engineering', 'Explore the pipelines, governance, quality checks, and observability practices that reliable AI systems depend on.'),
		array('Scaling eCommerce Platforms for High-Growth Brands', 'eCommerce', 'Prepare commerce platforms for traffic growth with scalable architecture, efficient search, secure payments, and robust analytics.'),
		array('The Business Case for Responsible Digital Transformation', 'Digital Transformation', 'Connect technology investment to measurable outcomes through clear priorities, incremental delivery, and organizational adoption.'),
	);

	foreach ($articles as $offset => $article) {
		$slug = sanitize_title($article[0]);
		if (get_page_by_path($slug, OBJECT, 'post')) {
			continue;
		}
		$existing_category = term_exists($article[1], 'category');
		$category_result = $existing_category ?: wp_insert_term($article[1], 'category');
		$category_id = is_wp_error($category_result) ? 0 : (is_array($category_result) ? (int) $category_result['term_id'] : (int) $category_result);
		wp_insert_post(
			array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'post_title' => $article[0],
				'post_name' => $slug,
				'post_excerpt' => $article[2],
				'post_content' => '<p>' . esc_html($article[2]) . '</p><p>' . esc_html__('AiBridze helps organizations turn emerging technology into secure, scalable solutions designed around real business goals.', 'aibridze') . '</p>',
				'post_category' => $category_id ? array($category_id) : array(),
				'post_date' => gmdate('Y-m-d H:i:s', time() - ($offset * DAY_IN_SECONDS)),
			)
		);
	}

	update_option('aibridze_demo_blog_posts_v1', 1, false);
}

/** Keep the seeded articles ahead of WordPress's default placeholder post. */
function aibridze_set_demo_blog_dates(): void
{
	if (get_option('aibridze_demo_blog_dates_v1')) {
		return;
	}
	$titles = array(
		'Innovative Approaches to Software Development with AI',
		'Leading the Charge in API Development for Future Applications',
		'Creating a Robust API Integration Framework with AI',
		'How Generative AI Is Reshaping Enterprise Workflows',
		'Building Production-Ready AI Agents for Business',
		'A Practical Guide to Modern Cloud Application Architecture',
		'Mobile App Performance Strategies That Improve Retention',
		'Designing Digital Products Around Real User Needs',
		'Machine Learning Models: From Experiment to Production',
		'Cybersecurity Essentials for Growing Digital Businesses',
		'Why Custom Software Creates a Competitive Advantage',
		'Automating Repetitive Operations Without Losing Control',
		'Data Engineering Foundations for Reliable AI Solutions',
		'Scaling eCommerce Platforms for High-Growth Brands',
		'The Business Case for Responsible Digital Transformation',
	);
	$base_time = current_time('timestamp');
	foreach ($titles as $offset => $title) {
		$post = get_page_by_path(sanitize_title($title), OBJECT, 'post');
		if ($post) {
			$date = wp_date('Y-m-d H:i:s', $base_time - ($offset * DAY_IN_SECONDS));
			wp_update_post(array('ID' => $post->ID, 'post_date' => $date, 'post_date_gmt' => get_gmt_from_date($date)));
		}
	}
	update_option('aibridze_demo_blog_dates_v1', 1, false);
}

/** Move WordPress's starter post behind the requested demo archive content. */
function aibridze_demote_starter_blog_post(): void
{
	if (get_option('aibridze_starter_post_demoted_v1')) {
		return;
	}
	$starter = get_page_by_path('hello-world', OBJECT, 'post');
	if ($starter) {
		wp_update_post(array('ID' => $starter->ID, 'post_date' => '2020-01-01 00:00:00', 'post_date_gmt' => '2020-01-01 00:00:00'));
	}
	update_option('aibridze_starter_post_demoted_v1', 1, false);
}

/** Populate the new takeaways field for the local sample articles once. */
function aibridze_seed_demo_takeaways(): void
{
	if (get_option('aibridze_demo_takeaways_v1')) {
		return;
	}
	$posts = get_posts(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1));
	foreach ($posts as $post) {
		if (get_post_meta($post->ID, '_aibridze_key_takeaways', true)) {
			continue;
		}
		$summary = get_the_excerpt($post) ?: wp_trim_words(wp_strip_all_tags($post->post_content), 35);
		update_post_meta($post->ID, '_aibridze_key_takeaways', '<p>' . esc_html($summary) . ' ' . esc_html__('Focus on measurable outcomes, responsible implementation, and a scalable foundation that supports long-term business value.', 'aibridze') . '</p>');
	}
	update_option('aibridze_demo_takeaways_v1', 1, false);
}

/** Add enough varied archive entries for the ten-page pagination design. */
function aibridze_seed_extended_blog_archive(): void
{
	if (get_option('aibridze_extended_blog_archive_v1')) {
		return;
	}
	$topics = array('Artificial Intelligence', 'API Engineering', 'Cloud Architecture', 'Mobile Applications', 'Data Platforms', 'Cybersecurity', 'Digital Commerce', 'Product Design', 'Machine Learning', 'Business Automation', 'DevOps', 'Web Applications', 'Customer Experience', 'Responsible AI', 'Software Modernization');
	$angles = array('Practical Strategies', 'Implementation Guide', 'Architecture Patterns', 'Common Challenges', 'Growth Opportunities');
	$base = strtotime('2025-12-31 12:00:00');
	for ($index = 0; $index < 74; $index++) {
		$topic = $topics[$index % count($topics)];
		$angle = $angles[(int) floor($index / count($topics)) % count($angles)];
		$title = sprintf('%s for %s: Insight %02d', $angle, $topic, $index + 1);
		$slug = sanitize_title($title);
		if (get_page_by_path($slug, OBJECT, 'post')) {
			continue;
		}
		$summary = sprintf('Explore %s for %s, with practical recommendations that help teams improve delivery, reduce risk, and create measurable digital value.', strtolower($angle), $topic);
		$term_result = term_exists($topic, 'category') ?: wp_insert_term($topic, 'category');
		$category_id = is_wp_error($term_result) ? 0 : (is_array($term_result) ? (int) $term_result['term_id'] : (int) $term_result);
		$date = wp_date('Y-m-d H:i:s', $base - ($index * DAY_IN_SECONDS));
		$post_id = wp_insert_post(
			array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'post_title' => $title,
				'post_name' => $slug,
				'post_excerpt' => $summary,
				'post_content' => '<h2>' . esc_html($angle) . '</h2><p>' . esc_html($summary) . '</p><h2>' . esc_html__('What Teams Should Consider', 'aibridze') . '</h2><ul><li>' . esc_html__('Connect technology decisions to clear business outcomes.', 'aibridze') . '</li><li>' . esc_html__('Design security, scalability, and measurement into delivery.', 'aibridze') . '</li><li>' . esc_html__('Improve continuously using real user and operational feedback.', 'aibridze') . '</li></ul>',
				'post_category' => $category_id ? array($category_id) : array(),
				'post_date' => $date,
				'post_date_gmt' => get_gmt_from_date($date),
			)
		);
		if ($post_id && !is_wp_error($post_id)) {
			update_post_meta($post_id, '_aibridze_key_takeaways', '<p>' . esc_html($summary) . '</p>');
		}
	}
	update_option('aibridze_extended_blog_archive_v1', 1, false);
}

/**
 * Use the bundled brand mark until a Site Icon is selected in WordPress.
 */
function aibridze_favicon(): void
{
	if (has_site_icon()) {
		return;
	}

	printf('<link rel="icon" href="%s" sizes="40x39">', esc_url(get_theme_file_uri('/assets/images/favicon.png')));
}
add_action('wp_head', 'aibridze_favicon');

/**
 * Register content managed through the WordPress dashboard.
 */
function aibridze_register_content_types(): void
{
	register_post_type(
		'service',
		array(
			'labels' => array(
				'name' => __('Services', 'aibridze'),
				'singular_name' => __('Service', 'aibridze'),
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'services'),
			'menu_icon' => 'dashicons-admin-tools',
			'show_in_rest' => true,
			'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
		)
	);

	register_taxonomy(
		'service_category',
		'service',
		array(
			'labels' => array(
				'name' => __('Service Categories', 'aibridze'),
				'singular_name' => __('Service Category', 'aibridze'),
			),
			'public' => true,
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'service-category'),
		)
	);

	foreach (array('industry' => 'Industries', 'portfolio' => 'Portfolio') as $type => $label) {
		register_post_type(
			$type,
			array(
				'labels' => array(
					'name' => __($label, 'aibridze'),
					'singular_name' => __(ucfirst($type), 'aibridze'),
				),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'industry' === $type ? 'industries' : 'portfolio'),
				'menu_icon' => 'industry' === $type ? 'dashicons-building' : 'dashicons-portfolio',
				'show_in_rest' => true,
				'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
			)
		);
	}

	register_post_type(
		'testimonial',
		array(
			'labels' => array(
				'name' => __('Testimonials', 'aibridze'),
				'singular_name' => __('Testimonial', 'aibridze'),
				'add_new_item' => __('Add New Testimonial', 'aibridze'),
			),
			'public' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-format-quote',
			'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
		)
	);

	register_post_type(
		'video_testimonial',
		array(
			'labels' => array(
				'name' => __('Video Testimonials', 'aibridze'),
				'singular_name' => __('Video Testimonial', 'aibridze'),
				'add_new_item' => __('Add Video Testimonial', 'aibridze'),
			),
			'public' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-video-alt3',
			'supports' => array('title', 'thumbnail', 'custom-fields', 'page-attributes'),
		)
	);

	register_post_type(
		'trusted_business',
		array(
			'labels' => array(
				'name' => __('Trusted by Businesses', 'aibridze'),
				'singular_name' => __('Business Logo', 'aibridze'),
				'add_new_item' => __('Add Business Logo', 'aibridze'),
			),
			'public' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-format-image',
			'supports' => array('title', 'thumbnail', 'page-attributes'),
		)
	);

	register_post_type(
		'leader',
		array(
			'labels' => array(
				'name' => __('Leadership', 'aibridze'),
				'singular_name' => __('Leader', 'aibridze'),
				'add_new_item' => __('Add Leadership Profile', 'aibridze'),
				'edit_item' => __('Edit Leadership Profile', 'aibridze'),
			),
			'public' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-groups',
			'supports' => array('title', 'thumbnail', 'page-attributes'),
		)
	);

	register_post_type(
		'team',
		array(
			'labels' => array(
				'name' => __('Team', 'aibridze'),
				'singular_name' => __('Team Member', 'aibridze'),
				'add_new_item' => __('Add Team Member', 'aibridze'),
				'edit_item' => __('Edit Team Member', 'aibridze'),
			),
			'public' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-businessperson',
			'supports' => array('title', 'thumbnail', 'page-attributes'),
		)
	);

	register_post_type(
		'opportunity',
		array(
			'labels' => array(
				'name' => __('Opportunities', 'aibridze'),
				'singular_name' => __('Opportunity', 'aibridze'),
				'add_new_item' => __('Add New Opportunity', 'aibridze'),
				'edit_item' => __('Edit Opportunity', 'aibridze'),
			),
			'public' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-businessperson',
			'supports' => array('title', 'editor', 'page-attributes'),
		)
	);

	register_post_type(
		'job_application',
		array(
			'labels' => array(
				'name' => __('Job Applications', 'aibridze'),
				'singular_name' => __('Job Application', 'aibridze'),
			),
			'public' => false,
			'show_ui' => true,
			'show_in_rest' => false,
			'menu_icon' => 'dashicons-portfolio',
			'supports' => array('title'),
			'capabilities' => array('create_posts' => 'do_not_allow'),
			'map_meta_cap' => true,
		)
	);
}
add_action('init', 'aibridze_register_content_types');

/** Opportunity fields shown in the dashboard. The main editor stores rich job-description HTML. */
function aibridze_add_opportunity_meta_box(): void
{
	add_meta_box('aibridze-opportunity-details', __('Opportunity Details', 'aibridze'), 'aibridze_render_opportunity_meta_box', 'opportunity', 'normal', 'high');
}
add_action('add_meta_boxes', 'aibridze_add_opportunity_meta_box');

function aibridze_render_opportunity_meta_box(WP_Post $post): void
{
	$experience = (string) get_post_meta($post->ID, '_aibridze_opportunity_experience', true);
	$location = (string) get_post_meta($post->ID, '_aibridze_opportunity_location', true);
	$department = (string) get_post_meta($post->ID, '_aibridze_opportunity_department', true);
	$type = (string) get_post_meta($post->ID, '_aibridze_opportunity_type', true);
	wp_nonce_field('aibridze_save_opportunity', 'aibridze_opportunity_nonce');
	?>
	<p><label for="aibridze-opportunity-experience"><strong><?php esc_html_e('Experience', 'aibridze'); ?></strong></label>
	</p>
	<p><input class="widefat" id="aibridze-opportunity-experience" name="aibridze_opportunity_experience" type="text"
			value="<?php echo esc_attr($experience); ?>" placeholder="3–4 Years"></p>
	<p><label for="aibridze-opportunity-location"><strong><?php esc_html_e('Location', 'aibridze'); ?></strong></label>
	</p>
	<p><input class="widefat" id="aibridze-opportunity-location" name="aibridze_opportunity_location" type="text"
			value="<?php echo esc_attr($location); ?>" placeholder="Remote (IN)"></p>
	<p><label for="aibridze-opportunity-department"><strong><?php esc_html_e('Department', 'aibridze'); ?></strong></label>
	</p>
	<p><input class="widefat" id="aibridze-opportunity-department" name="aibridze_opportunity_department" type="text"
			value="<?php echo esc_attr($department); ?>" placeholder="Engineering"></p>
	<p><label for="aibridze-opportunity-type"><strong><?php esc_html_e('Employment Type', 'aibridze'); ?></strong></label>
	</p>
	<p><select class="widefat" id="aibridze-opportunity-type" name="aibridze_opportunity_type">
			<option value="Full-time" <?php selected($type, 'Full-time'); ?>>Full-time</option>
			<option value="Part-time" <?php selected($type, 'Part-time'); ?>>Part-time</option>
			<option value="Contract" <?php selected($type, 'Contract'); ?>>Contract</option>
			<option value="Internship" <?php selected($type, 'Internship'); ?>>Internship</option>
		</select></p>
	<p class="description">
		<?php esc_html_e('Use the title for the role name and the main WordPress editor for the complete rich job description, including headings, paragraphs, ordered lists and unordered lists.', 'aibridze'); ?>
	</p>
	<?php
}

function aibridze_save_opportunity(int $post_id): void
{
	if (!isset($_POST['aibridze_opportunity_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['aibridze_opportunity_nonce'])), 'aibridze_save_opportunity') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
		return;
	}
	foreach (array('experience', 'location', 'department', 'type') as $field) {
		$key = 'aibridze_opportunity_' . $field;
		update_post_meta($post_id, '_aibridze_opportunity_' . $field, sanitize_text_field(wp_unslash($_POST[$key] ?? '')));
	}
}
add_action('save_post_opportunity', 'aibridze_save_opportunity');

/** Seed a substantial editable opportunity catalogue for the initial careers launch. */
function aibridze_seed_opportunities(): void
{
	if (get_option('aibridze_opportunity_seed_v1')) {
		return;
	}
	$roles = array(
		array('Associate UI/UX Designer', '3–4 Years', 'Remote (IN)', 'Design'),
		array('Senior Business Development Executive', '5–8 Years', 'Remote (IN)', 'Sales'),
		array('WordPress Developer', '1–3 Years', 'Remote (IN)', 'Engineering'),
		array('Data Analyst', '2–4 Years', 'Hybrid (CA)', 'Data'),
		array('Mobile App Developer', '1–3 Years', 'Remote (UK)', 'Engineering'),
		array('Senior React Developer', '4–7 Years', 'Noida (IN)', 'Engineering'),
		array('Node.js Backend Engineer', '3–6 Years', 'Remote (IN)', 'Engineering'),
		array('Machine Learning Engineer', '3–5 Years', 'Hybrid (IN)', 'AI & ML'),
		array('Generative AI Engineer', '2–5 Years', 'Remote (US)', 'AI & ML'),
		array('DevOps Engineer', '3–6 Years', 'Noida (IN)', 'Cloud'),
		array('Cloud Solutions Architect', '6–10 Years', 'Remote (AE)', 'Cloud'),
		array('Quality Assurance Engineer', '2–4 Years', 'Remote (IN)', 'Quality'),
		array('Flutter Developer', '2–5 Years', 'Remote (IN)', 'Engineering'),
		array('Product Manager', '5–8 Years', 'Hybrid (IN)', 'Product'),
		array('Technical Project Manager', '6–9 Years', 'Remote (UK)', 'Delivery'),
		array('Cybersecurity Engineer', '3–6 Years', 'Remote (US)', 'Security'),
		array('Automation Test Engineer', '2–5 Years', 'Noida (IN)', 'Quality'),
		array('SEO Specialist', '2–4 Years', 'Remote (IN)', 'Marketing'),
		array('Content Marketing Specialist', '2–5 Years', 'Remote (IN)', 'Marketing'),
		array('Solutions Consultant', '4–7 Years', 'Hybrid (AE)', 'Consulting'),
		array('Python Developer', '2–5 Years', 'Remote (IN)', 'Engineering'),
		array('AI Research Intern', '0–1 Year', 'Noida (IN)', 'AI & ML'),
		array('Frontend Engineering Intern', '0–1 Year', 'Remote (IN)', 'Engineering'),
		array('Database Administrator', '4–7 Years', 'Hybrid (CA)', 'Data'),
		array('Customer Success Manager', '4–6 Years', 'Remote (US)', 'Customer Success'),
	);
	foreach ($roles as $index => $role) {
		if (get_page_by_title($role[0], OBJECT, 'opportunity')) {
			continue;
		}
		$content = '<h2>Job Description</h2><p>Join AiBridze and help deliver thoughtful technology solutions for ambitious global businesses.</p><h3>What You Will Do</h3><ul><li>Collaborate with multidisciplinary teams to plan and deliver high-quality outcomes.</li><li>Translate business needs into practical, scalable solutions.</li><li>Communicate progress clearly and contribute to continuous improvement.</li><li>Take ownership of quality, documentation, and measurable results.</li></ul><h3>What We Are Looking For</h3><ul><li>Relevant hands-on experience and strong problem-solving skills.</li><li>A collaborative mindset with clear written and verbal communication.</li><li>Curiosity, accountability, and a desire to keep learning.</li></ul>';
		$post_id = wp_insert_post(array('post_type' => 'opportunity', 'post_status' => 'publish', 'post_title' => $role[0], 'post_content' => $content, 'menu_order' => $index));
		if (!is_wp_error($post_id)) {
			update_post_meta($post_id, '_aibridze_opportunity_experience', $role[1]);
			update_post_meta($post_id, '_aibridze_opportunity_location', $role[2]);
			update_post_meta($post_id, '_aibridze_opportunity_department', $role[3]);
			update_post_meta($post_id, '_aibridze_opportunity_type', 'Full-time');
		}
	}
	update_option('aibridze_opportunity_seed_v1', 1);
}
add_action('init', 'aibridze_seed_opportunities', 35);

/** Receive career applications through AJAX and retain them in the dashboard. */
function aibridze_submit_job_application(): void
{
	check_ajax_referer('aibridze_job_application', 'job_application_nonce');
	if (!empty($_POST['company_website'])) {
		wp_send_json_error(array('message' => __('Unable to submit this application.', 'aibridze')), 400);
	}

	$name = sanitize_text_field(wp_unslash($_POST['full_name'] ?? ''));
	$email_raw = trim(wp_unslash($_POST['email'] ?? ''));
	$email = sanitize_email($email_raw);
	$phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
	$experience = sanitize_text_field(wp_unslash($_POST['years_experience'] ?? ''));
	$current_ctc = sanitize_text_field(wp_unslash($_POST['current_ctc'] ?? ''));
	$expected_ctc = sanitize_text_field(wp_unslash($_POST['expected_ctc'] ?? ''));
	$linkedin = esc_url_raw(wp_unslash($_POST['linkedin'] ?? ''));
	$opportunity_id = absint($_POST['opportunity_id'] ?? 0);
	$opportunity = get_post($opportunity_id);
	$valid_opportunity = 0 === $opportunity_id || ($opportunity && 'opportunity' === $opportunity->post_type && 'publish' === $opportunity->post_status);
	$role_title = 0 === $opportunity_id ? __('General Application', 'aibridze') : get_the_title($opportunity_id);

	if ('' === $name || !is_email($email_raw) || $email !== $email_raw || '' === $phone || '' === $experience || !$valid_opportunity || empty($_POST['consent'])) {
		wp_send_json_error(array('message' => __('Please complete every required field and accept the consent statement.', 'aibridze')), 422);
	}
	if (empty($_FILES['resume']['tmp_name']) || !empty($_FILES['resume']['error']) || (int) $_FILES['resume']['size'] > 2 * MB_IN_BYTES) {
		wp_send_json_error(array('message' => __('Please upload a PDF, DOC or DOCX resume no larger than 2 MB.', 'aibridze')), 422);
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	$uploaded = wp_handle_upload(
		$_FILES['resume'],
		array(
			'test_form' => false,
			'mimes' => array('pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
		)
	);
	if (isset($uploaded['error'])) {
		wp_send_json_error(array('message' => sanitize_text_field($uploaded['error'])), 422);
	}

	$attachment_id = wp_insert_attachment(array('post_mime_type' => $uploaded['type'], 'post_title' => sanitize_file_name(pathinfo($uploaded['file'], PATHINFO_FILENAME)), 'post_status' => 'inherit'), $uploaded['file'], 0, true);
	if (is_wp_error($attachment_id) || !$attachment_id) {
		wp_send_json_error(array('message' => 'Your resume could not be saved. Please try again.'), 500);
	}
	if (!is_wp_error($attachment_id)) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $uploaded['file']));
	}

	$fields = array('name' => $name, 'email' => $email, 'phone' => $phone, 'years_experience' => $experience, 'current_ctc' => $current_ctc, 'expected_ctc' => $expected_ctc, 'linkedin' => $linkedin, 'opportunity_id' => $opportunity_id, 'resume_id' => (int) $attachment_id);
	$fields['position'] = $role_title;
	$fields['resume_name'] = wp_basename($uploaded['file']);
	$fields['resume_url'] = $uploaded['url'];
	$fields['consent'] = true;
	$fields['submitted_on'] = current_time('mysql');
	$application_id = wp_insert_post(
		array(
			'post_type' => 'job_application',
			'post_status' => 'private',
			'post_title' => sprintf('%s — %s', $name, $role_title),
			'post_content' => wp_slash(wp_json_encode($fields, JSON_UNESCAPED_UNICODE)),
			'meta_input' => array('_aibridze_mail_status' => 'pending', '_aibridze_reply_status' => 'pending'),
		),
		true
	);
	if (is_wp_error($application_id) || !$application_id) {
		wp_send_json_error(array('message' => __('Your application could not be saved. Please try again.', 'aibridze')), 500);
	}
	foreach ($fields as $key => $value) {
		update_post_meta($application_id, '_aibridze_application_' . $key, $value);
	}

	$subject = sprintf('[AiBridze Careers] %s applied for %s', $name, $role_title);
	$templates = aibridze_career_emails($fields);
	$body = $templates['admin'];
	$recipient = (string) apply_filters('aibridze_career_recipient', 'career@aibridze.com, dashsatybrata1999@gmail.com');
	$mail_sent = aibridze_send_record_mail($application_id, 'notification', $recipient, $subject, $body, array('Content-Type: text/plain; charset=UTF-8', 'From: AiBridze Careers <career@aibridze.com>', sprintf('Reply-To: %s <%s>', $name, $email)), array($uploaded['file']));
	update_post_meta($application_id, '_aibridze_application_notification_status', $mail_sent ? 'accepted' : 'failed');
	aibridze_send_record_mail($application_id, 'reply', $email, 'Your application to AiBridze has been received', $templates['reply'], array('Content-Type: text/plain; charset=UTF-8', 'From: AiBridze Careers <career@aibridze.com>', 'Reply-To: career@aibridze.com'));
	wp_send_json_success(array(
		'message' => $mail_sent
			? __('Resume submitted successfully! Thank you for your interest in joining our team. We’ll review your profile and get back to you if your experience matches an opportunity.', 'aibridze')
			: __('Your application has been saved for our team to review. Please do not submit it again.', 'aibridze')
	));
}
add_action('wp_ajax_nopriv_aibridze_submit_job_application', 'aibridze_submit_job_application');
add_action('wp_ajax_aibridze_submit_job_application', 'aibridze_submit_job_application');

function aibridze_add_application_meta_box(): void
{
	add_meta_box('aibridze-application-details', __('Application Details', 'aibridze'), 'aibridze_render_application_meta_box', 'job_application', 'normal', 'high');
}
add_action('add_meta_boxes', 'aibridze_add_application_meta_box');

function aibridze_render_application_meta_box(WP_Post $post): void
{
	$labels = array('name' => 'Full Name', 'email' => 'Email', 'phone' => 'Phone', 'years_experience' => 'Years of Experience', 'current_ctc' => 'Current CTC', 'expected_ctc' => 'Expected CTC', 'linkedin' => 'LinkedIn');
	echo '<table class="widefat striped"><tbody>';
	foreach ($labels as $key => $label) {
		$value = (string) get_post_meta($post->ID, '_aibridze_application_' . $key, true);
		printf('<tr><th style="width:180px">%s</th><td>%s</td></tr>', esc_html($label), 'linkedin' === $key && $value ? '<a href="' . esc_url($value) . '" target="_blank" rel="noopener">' . esc_html($value) . '</a>' : esc_html($value));
	}
	$opportunity_id = absint(get_post_meta($post->ID, '_aibridze_application_opportunity_id', true));
	$resume_id = absint(get_post_meta($post->ID, '_aibridze_application_resume_id', true));
	$position_title = $opportunity_id ? get_the_title($opportunity_id) : __('General Application', 'aibridze');
	printf('<tr><th>%s</th><td>%s</td></tr>', esc_html__('Position', 'aibridze'), esc_html($position_title));
	printf('<tr><th>%s</th><td>%s</td></tr>', esc_html__('Resume', 'aibridze'), $resume_id ? '<a href="' . esc_url(wp_get_attachment_url($resume_id)) . '" target="_blank" rel="noopener">' . esc_html__('View resume', 'aibridze') . '</a>' : '—');
	echo '</tbody></table>';
}

/** Leadership profile fields shown in the dashboard. */
function aibridze_add_leader_meta_box(): void
{
	add_meta_box('aibridze-leader-details', __('Leadership Details', 'aibridze'), 'aibridze_render_leader_meta_box', 'leader', 'normal', 'high');
}
add_action('add_meta_boxes', 'aibridze_add_leader_meta_box');

function aibridze_render_leader_meta_box(WP_Post $post): void
{
	$position = (string) get_post_meta($post->ID, '_aibridze_leader_position', true);
	$linkedin = (string) get_post_meta($post->ID, '_aibridze_leader_linkedin', true);
	wp_nonce_field('aibridze_save_leader', 'aibridze_leader_nonce');
	?>
	<p><label for="aibridze-leader-position"><strong><?php esc_html_e('Position', 'aibridze'); ?></strong></label></p>
	<p><input class="widefat" id="aibridze-leader-position" name="aibridze_leader_position" type="text"
			value="<?php echo esc_attr($position); ?>" placeholder="<?php esc_attr_e('Co-Founder & Director', 'aibridze'); ?>">
	</p>
	<p><label for="aibridze-leader-linkedin"><strong><?php esc_html_e('LinkedIn URL', 'aibridze'); ?></strong></label></p>
	<p><input class="widefat" id="aibridze-leader-linkedin" name="aibridze_leader_linkedin" type="url"
			value="<?php echo esc_attr($linkedin); ?>" placeholder="https://www.linkedin.com/in/..."></p>
	<p class="description">
		<?php esc_html_e('Use the title field for the leader name, Featured Image for the portrait, and Order to control the display order.', 'aibridze'); ?>
	</p>
	<?php
}

function aibridze_save_leader(int $post_id): void
{
	if (!isset($_POST['aibridze_leader_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['aibridze_leader_nonce'])), 'aibridze_save_leader')) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	$position = isset($_POST['aibridze_leader_position']) ? sanitize_text_field(wp_unslash($_POST['aibridze_leader_position'])) : '';
	$linkedin = isset($_POST['aibridze_leader_linkedin']) ? esc_url_raw(wp_unslash($_POST['aibridze_leader_linkedin'])) : '';
	update_post_meta($post_id, '_aibridze_leader_position', $position);
	update_post_meta($post_id, '_aibridze_leader_linkedin', $linkedin);
}
add_action('save_post_leader', 'aibridze_save_leader');

/** Core team role field shown in the dashboard. */
function aibridze_add_team_meta_box(): void
{
	add_meta_box('aibridze-team-details', __('Team Member Details', 'aibridze'), 'aibridze_render_team_meta_box', 'team', 'normal', 'high');
}
add_action('add_meta_boxes', 'aibridze_add_team_meta_box');

function aibridze_render_team_meta_box(WP_Post $post): void
{
	$position = (string) get_post_meta($post->ID, '_aibridze_team_position', true);
	wp_nonce_field('aibridze_save_team', 'aibridze_team_nonce');
	?>
	<p><label for="aibridze-team-position"><strong><?php esc_html_e('Position', 'aibridze'); ?></strong></label></p>
	<p><input class="widefat" id="aibridze-team-position" name="aibridze_team_position" type="text"
			value="<?php echo esc_attr($position); ?>" placeholder="<?php esc_attr_e('Software Engineer', 'aibridze'); ?>">
	</p>
	<p class="description">
		<?php esc_html_e('Use the title field for the member name, Featured Image for the portrait, and Order to control the display order.', 'aibridze'); ?>
	</p>
	<?php
}

function aibridze_save_team(int $post_id): void
{
	if (!isset($_POST['aibridze_team_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['aibridze_team_nonce'])), 'aibridze_save_team')) {
		return;
	}
	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
		return;
	}

	$position = isset($_POST['aibridze_team_position']) ? sanitize_text_field(wp_unslash($_POST['aibridze_team_position'])) : '';
	update_post_meta($post_id, '_aibridze_team_position', $position);
}
add_action('save_post_team', 'aibridze_save_team');

/** Rich Key Takeaways field for blog posts. */
function aibridze_register_blog_takeaways_meta(): void
{
	register_post_meta(
		'post',
		'_aibridze_key_takeaways',
		array(
			'type' => 'string',
			'single' => true,
			'show_in_rest' => true,
			'sanitize_callback' => 'wp_kses_post',
			'auth_callback' => static fn(): bool => current_user_can('edit_posts'),
		)
	);
}
add_action('init', 'aibridze_register_blog_takeaways_meta');

function aibridze_add_blog_takeaways_meta_box(): void
{
	add_meta_box('aibridze-blog-takeaways', __('Key Takeaways', 'aibridze'), 'aibridze_render_blog_takeaways_meta_box', 'post', 'normal', 'high');
}
add_action('add_meta_boxes', 'aibridze_add_blog_takeaways_meta_box');

function aibridze_render_blog_takeaways_meta_box(WP_Post $post): void
{
	$value = (string) get_post_meta($post->ID, '_aibridze_key_takeaways', true);
	wp_nonce_field('aibridze_save_blog_takeaways', 'aibridze_blog_takeaways_nonce');
	wp_editor($value, 'aibridze_key_takeaways_editor', array('textarea_name' => 'aibridze_key_takeaways', 'textarea_rows' => 6, 'media_buttons' => false));
	?>
	<p class="description">
		<?php esc_html_e('Supports Visual and Code editing, headings, bold text, links, and lists. This field is also exposed through the WordPress REST API for Elementor integrations.', 'aibridze'); ?>
	</p><?php
}

function aibridze_save_blog_takeaways(int $post_id): void
{
	if (!isset($_POST['aibridze_blog_takeaways_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['aibridze_blog_takeaways_nonce'])), 'aibridze_save_blog_takeaways')) {
		return;
	}
	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
		return;
	}
	$value = isset($_POST['aibridze_key_takeaways']) ? wp_kses_post(wp_unslash($_POST['aibridze_key_takeaways'])) : '';
	update_post_meta($post_id, '_aibridze_key_takeaways', $value);
}
add_action('save_post_post', 'aibridze_save_blog_takeaways');

function aibridze_key_takeaways_shortcode(): string
{
	return (string) get_post_meta(get_the_ID(), '_aibridze_key_takeaways', true);
}
add_shortcode('aibridze_key_takeaways', 'aibridze_key_takeaways_shortcode');

/** Enable the standard Post type in Elementor when the plugin is active. */
function aibridze_enable_elementor_blog_posts(): void
{
	if (!did_action('elementor/loaded')) {
		return;
	}
	$supported = (array) get_option('elementor_cpt_support', array('page'));
	if (!in_array('post', $supported, true)) {
		$supported[] = 'post';
		update_option('elementor_cpt_support', array_values(array_unique($supported)));
	}
}
add_action('init', 'aibridze_enable_elementor_blog_posts', 40);

/**
 * Register the Service icon field for REST and editor integrations.
 */
function aibridze_register_service_meta(): void
{
	foreach (array('service', 'industry') as $post_type) {
		register_post_meta(
			$post_type,
			'_aibridze_icon_id',
			array(
				'type' => 'integer',
				'single' => true,
				'default' => 0,
				'show_in_rest' => true,
				'sanitize_callback' => 'absint',
				'auth_callback' => static function (): bool {
					return current_user_can('edit_posts');
				},
			)
		);
	}
}
add_action('init', 'aibridze_register_service_meta');

function aibridze_register_service_category_meta(): void
{
	register_term_meta(
		'service_category',
		'_aibridze_featured_image_id',
		array(
			'type' => 'integer',
			'single' => true,
			'show_in_rest' => true,
			'sanitize_callback' => 'absint',
			'auth_callback' => static fn(): bool => current_user_can('manage_categories'),
		)
	);
}
add_action('init', 'aibridze_register_service_category_meta');

function aibridze_service_category_image_field(?WP_Term $term = null): void
{
	$image_id = $term ? (int) get_term_meta($term->term_id, '_aibridze_featured_image_id', true) : 0;
	$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : get_theme_file_uri('/assets/images/service-categories/technology-consulting.png');
	?>
	<div class="form-field aibridze-icon-field" data-service-icon-field>
		<label><?php esc_html_e('Featured card image', 'aibridze'); ?></label>
		<img class="aibridze-icon-field__preview" src="<?php echo esc_url($image_url); ?>" width="187" height="190" alt=""
			style="display:block;object-fit:cover;margin:8px 0;">
		<input type="hidden" name="aibridze_category_image_id" value="<?php echo esc_attr($image_id); ?>"
			data-service-icon-input>
		<button class="button" type="button"
			data-service-icon-select><?php esc_html_e('Choose image', 'aibridze'); ?></button>
		<button class="button-link-delete" type="button" data-service-icon-remove<?php echo $image_id ? '' : ' hidden'; ?>><?php esc_html_e('Remove', 'aibridze'); ?></button>
		<p class="description"><?php esc_html_e('Recommended size: 374×380px.', 'aibridze'); ?></p>
	</div>
	<?php
}
add_action('service_category_add_form_fields', static function (): void {
	aibridze_service_category_image_field();
});
add_action('service_category_edit_form_fields', static function (WP_Term $term): void { ?>
	<tr class="form-field">
		<th scope="row"></th>
		<td><?php aibridze_service_category_image_field($term); ?></td>
	</tr><?php });

function aibridze_save_service_category_image(int $term_id): void
{
	if (!current_user_can('manage_categories')) {
		return;
	}
	$image_id = absint($_POST['aibridze_category_image_id'] ?? 0);
	$image_id ? update_term_meta($term_id, '_aibridze_featured_image_id', $image_id) : delete_term_meta($term_id, '_aibridze_featured_image_id');
}
add_action('created_service_category', 'aibridze_save_service_category_image');
add_action('edited_service_category', 'aibridze_save_service_category_image');

/**
 * Add the icon selector to Service edit screens.
 */
function aibridze_add_service_icon_meta_box(): void
{
	foreach (array('service' => __('Service Icon', 'aibridze'), 'industry' => __('Industry Icon', 'aibridze')) as $post_type => $title) {
		add_meta_box('aibridze-content-icon', $title, 'aibridze_render_service_icon_meta_box', $post_type, 'side', 'default');
	}
}
add_action('add_meta_boxes', 'aibridze_add_service_icon_meta_box');

/**
 * Render the Service icon media field.
 */
function aibridze_render_service_icon_meta_box(WP_Post $post): void
{
	$icon_id = (int) get_post_meta($post->ID, '_aibridze_icon_id', true);
	$icon_url = $icon_id ? wp_get_attachment_image_url($icon_id, 'thumbnail') : '';
	wp_nonce_field('aibridze_save_service_icon', 'aibridze_service_icon_nonce');
	?>
	<div class="aibridze-icon-field" data-service-icon-field>
		<img class="aibridze-icon-field__preview"
			src="<?php echo esc_url($icon_url ?: get_theme_file_uri('/assets/images/service-icon-default.png')); ?>" width="48"
			height="48" alt="">
		<input type="hidden" name="aibridze_service_icon_id" value="<?php echo esc_attr($icon_id); ?>"
			data-service-icon-input>
		<p>
			<button class="button" type="button"
				data-service-icon-select><?php esc_html_e('Choose icon', 'aibridze'); ?></button>
			<button class="button-link-delete" type="button" data-service-icon-remove<?php echo $icon_id ? '' : ' hidden'; ?>><?php esc_html_e('Remove', 'aibridze'); ?></button>
		</p>
		<p class="description">
			<?php esc_html_e('Recommended: square PNG or SVG. Displayed at 24×24px in the mega menu.', 'aibridze'); ?>
		</p>
	</div>
	<?php
}

/**
 * Save the Service icon selection.
 */
function aibridze_save_service_icon(int $post_id): void
{
	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id)) {
		return;
	}

	$nonce = isset($_POST['aibridze_service_icon_nonce']) ? sanitize_text_field(wp_unslash($_POST['aibridze_service_icon_nonce'])) : '';
	if (!wp_verify_nonce($nonce, 'aibridze_save_service_icon') || !current_user_can('edit_post', $post_id)) {
		return;
	}

	$icon_id = isset($_POST['aibridze_service_icon_id']) ? absint($_POST['aibridze_service_icon_id']) : 0;
	if ($icon_id) {
		update_post_meta($post_id, '_aibridze_icon_id', $icon_id);
	} else {
		delete_post_meta($post_id, '_aibridze_icon_id');
	}
}
add_action('save_post_service', 'aibridze_save_service_icon');
add_action('save_post_industry', 'aibridze_save_service_icon');

/**
 * Register and render the fields used by testimonial cards.
 */
function aibridze_register_testimonial_meta(): void
{
	foreach (array('_aibridze_testimonial_role', '_aibridze_testimonial_company') as $meta_key) {
		register_post_meta(
			'testimonial',
			$meta_key,
			array(
				'type' => 'string',
				'single' => true,
				'show_in_rest' => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback' => static fn(): bool => current_user_can('edit_posts'),
			)
		);
	}

	register_post_meta(
		'testimonial',
		'_aibridze_testimonial_logo_id',
		array(
			'type' => 'integer',
			'single' => true,
			'show_in_rest' => true,
			'sanitize_callback' => 'absint',
			'auth_callback' => static fn(): bool => current_user_can('edit_posts'),
		)
	);
}
add_action('init', 'aibridze_register_testimonial_meta');

function aibridze_add_testimonial_meta_box(): void
{
	add_meta_box('aibridze-testimonial-details', __('Testimonial Details', 'aibridze'), 'aibridze_render_testimonial_meta_box', 'testimonial', 'normal', 'high');
}
add_action('add_meta_boxes', 'aibridze_add_testimonial_meta_box');

function aibridze_render_testimonial_meta_box(WP_Post $post): void
{
	$role = (string) get_post_meta($post->ID, '_aibridze_testimonial_role', true);
	$company = (string) get_post_meta($post->ID, '_aibridze_testimonial_company', true);
	$logo_id = (int) get_post_meta($post->ID, '_aibridze_testimonial_logo_id', true);
	$logo = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';
	wp_nonce_field('aibridze_save_testimonial', 'aibridze_testimonial_nonce');
	?>
	<p><label
			for="aibridze-testimonial-role"><strong><?php esc_html_e('Job title', 'aibridze'); ?></strong></label><br><input
			class="widefat" id="aibridze-testimonial-role" name="aibridze_testimonial_role"
			value="<?php echo esc_attr($role); ?>"></p>
	<p><label
			for="aibridze-testimonial-company"><strong><?php esc_html_e('Company', 'aibridze'); ?></strong></label><br><input
			class="widefat" id="aibridze-testimonial-company" name="aibridze_testimonial_company"
			value="<?php echo esc_attr($company); ?>"></p>
	<div class="aibridze-icon-field" data-service-icon-field>
		<p><strong><?php esc_html_e('Company logo', 'aibridze'); ?></strong></p>
		<img class="aibridze-icon-field__preview"
			src="<?php echo esc_url($logo ?: get_theme_file_uri('/assets/images/consultation/company-logo.png')); ?>"
			width="120" height="54" alt="">
		<input type="hidden" name="aibridze_testimonial_logo_id" value="<?php echo esc_attr($logo_id); ?>"
			data-service-icon-input>
		<p><button class="button" type="button"
				data-service-icon-select><?php esc_html_e('Choose logo', 'aibridze'); ?></button> <button
				class="button-link-delete" type="button" data-service-icon-remove<?php echo $logo_id ? '' : ' hidden'; ?>><?php esc_html_e('Remove', 'aibridze'); ?></button></p>
	</div>
	<?php
}

function aibridze_save_testimonial(int $post_id): void
{
	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id)) {
		return;
	}
	$nonce = isset($_POST['aibridze_testimonial_nonce']) ? sanitize_text_field(wp_unslash($_POST['aibridze_testimonial_nonce'])) : '';
	if (!wp_verify_nonce($nonce, 'aibridze_save_testimonial') || !current_user_can('edit_post', $post_id)) {
		return;
	}
	update_post_meta($post_id, '_aibridze_testimonial_role', sanitize_text_field(wp_unslash($_POST['aibridze_testimonial_role'] ?? '')));
	update_post_meta($post_id, '_aibridze_testimonial_company', sanitize_text_field(wp_unslash($_POST['aibridze_testimonial_company'] ?? '')));
	update_post_meta($post_id, '_aibridze_testimonial_logo_id', absint($_POST['aibridze_testimonial_logo_id'] ?? 0));
}
add_action('save_post_testimonial', 'aibridze_save_testimonial');

/**
 * Store the single video selected for a Video Testimonial entry.
 */
require_once get_theme_file_path('/inc/testimonial-video-source.php');

function aibridze_register_video_testimonial_meta(): void
{
	register_post_meta('video_testimonial', '_aibridze_video_thumbnail_id', array('type' => 'integer', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'absint', 'auth_callback' => static fn(): bool => current_user_can('edit_posts')));
	register_post_meta('video_testimonial', '_aibridze_video_url', array('type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'esc_url_raw', 'auth_callback' => static fn(): bool => current_user_can('edit_posts')));
}
add_action('init', 'aibridze_register_video_testimonial_meta');

function aibridze_add_video_testimonial_meta_box(): void
{
	add_meta_box('aibridze-video-testimonial', __('Testimonial Video', 'aibridze'), 'aibridze_render_video_testimonial_meta_box', 'video_testimonial', 'normal', 'high');
}
add_action('add_meta_boxes', 'aibridze_add_video_testimonial_meta_box');

function aibridze_render_video_testimonial_meta_box(WP_Post $post): void
{
	$video_url = (string) get_post_meta($post->ID, '_aibridze_video_url', true);
	wp_nonce_field('aibridze_save_video_testimonial', 'aibridze_video_testimonial_nonce');
	?>
	<div data-video-testimonial-field>
		<p><strong><?php esc_html_e('Video thumbnail', 'aibridze'); ?></strong></p>
		<?php $thumbnail_id = absint(get_post_meta($post->ID, '_aibridze_video_thumbnail_id', true)); ?>
		<input type="hidden" name="aibridze_video_thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>"
			data-video-thumbnail-input>
		<img data-video-thumbnail-preview
			src="<?php echo esc_url($thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : ''); ?>" alt=""
			style="max-width:300px;height:auto" <?php echo $thumbnail_id ? '' : 'hidden'; ?>>
		<p><button type="button" class="button"
				data-video-thumbnail-select><?php esc_html_e('Choose thumbnail', 'aibridze'); ?></button> <button type="button"
				class="button-link-delete" data-video-thumbnail-remove <?php echo $thumbnail_id ? '' : 'hidden'; ?>><?php esc_html_e('Remove thumbnail', 'aibridze'); ?></button></p>
		<p class="description">
			<?php esc_html_e('This image fills the testimonial card. The video opens when visitors click Play.', 'aibridze'); ?>
		</p>
		<video controls width="360" style="display:none;max-width:100%;margin-bottom:12px"
			data-video-testimonial-preview></video>
		<p data-video-testimonial-external hidden>YouTube video selected. It will open in the website’s external video player.
		</p>
		<label for="testimonial-video-url">YouTube URL or uploaded video URL</label>
		<input type="url" id="testimonial-video-url" class="widefat" name="aibridze_video_testimonial_url"
			value="<?php echo esc_attr($video_url); ?>" placeholder="https://" data-video-testimonial-input>
		<p><button class="button button-primary" type="button"
				data-video-testimonial-select><?php esc_html_e('Choose video', 'aibridze'); ?></button> <button
				class="button-link-delete" type="button" data-video-testimonial-remove<?php echo $video_url ? '' : ' hidden'; ?>><?php esc_html_e('Remove video', 'aibridze'); ?></button></p>
		<p class="description">
			<?php esc_html_e('Paste a YouTube watch, share, Shorts, or embed URL. Set a title and Video thumbnail above; otherwise the Featured Image or YouTube thumbnail is used. Publish to display in the testimonial sections. Use Order to arrange the cards.', 'aibridze'); ?>
		</p>
	</div>
	<?php
}

function aibridze_save_video_testimonial(int $post_id): void
{
	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id))
		return;
	$nonce = sanitize_text_field(wp_unslash($_POST['aibridze_video_testimonial_nonce'] ?? ''));
	if (!wp_verify_nonce($nonce, 'aibridze_save_video_testimonial') || !current_user_can('edit_post', $post_id))
		return;
	$thumbnail_id = absint($_POST['aibridze_video_thumbnail_id'] ?? 0);
	if (!$thumbnail_id || wp_attachment_is_image($thumbnail_id)) {
		update_post_meta($post_id, '_aibridze_video_thumbnail_id', $thumbnail_id);
	}
	update_post_meta($post_id, '_aibridze_video_url', esc_url_raw(wp_unslash($_POST['aibridze_video_testimonial_url'] ?? '')));
}
add_action('save_post_video_testimonial', 'aibridze_save_video_testimonial');

/**
 * Register the editable details used by homepage portfolio cards.
 */
function aibridze_register_portfolio_meta(): void
{
	$fields = array(
		'_aibridze_portfolio_logo_id' => 'integer',
		'_aibridze_portfolio_stat_one' => 'string',
		'_aibridze_portfolio_stat_one_label' => 'string',
		'_aibridze_portfolio_stat_two' => 'string',
		'_aibridze_portfolio_stat_two_label' => 'string',
		'_aibridze_portfolio_url' => 'string',
	);
	foreach ($fields as $key => $type) {
		register_post_meta('portfolio', $key, array('type' => $type, 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'integer' === $type ? 'absint' : 'sanitize_text_field', 'auth_callback' => static fn(): bool => current_user_can('edit_posts')));
	}
}
add_action('init', 'aibridze_register_portfolio_meta');

function aibridze_add_portfolio_meta_box(): void
{
	add_meta_box('aibridze-portfolio-details', __('Portfolio Card Details', 'aibridze'), 'aibridze_render_portfolio_meta_box', 'portfolio', 'normal', 'high');
}
add_action('add_meta_boxes', 'aibridze_add_portfolio_meta_box');

function aibridze_render_portfolio_meta_box(WP_Post $post): void
{
	$logo_id = (int) get_post_meta($post->ID, '_aibridze_portfolio_logo_id', true);
	$logo = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : get_theme_file_uri('/assets/images/portfolio-logo.png');
	wp_nonce_field('aibridze_save_portfolio', 'aibridze_portfolio_nonce');
	?>
	<div class="aibridze-icon-field" data-service-icon-field>
		<p><strong><?php esc_html_e('Project logo', 'aibridze'); ?></strong></p>
		<img class="aibridze-icon-field__preview" src="<?php echo esc_url($logo); ?>" width="140" height="60" alt=""
			style="object-fit:contain;background:#171719;padding:8px;">
		<input type="hidden" name="aibridze_portfolio_logo_id" value="<?php echo esc_attr($logo_id); ?>"
			data-service-icon-input>
		<p><button class="button" type="button"
				data-service-icon-select><?php esc_html_e('Choose logo', 'aibridze'); ?></button> <button
				class="button-link-delete" type="button" data-service-icon-remove<?php echo $logo_id ? '' : ' hidden'; ?>><?php esc_html_e('Remove', 'aibridze'); ?></button></p>
	</div>
	<p><label><strong><?php esc_html_e('Statistic one', 'aibridze'); ?></strong></label><br><input class="widefat"
			name="aibridze_portfolio_stat_one"
			value="<?php echo esc_attr(get_post_meta($post->ID, '_aibridze_portfolio_stat_one', true)); ?>" placeholder="$52M">
	</p>
	<p><label><strong><?php esc_html_e('Statistic one label', 'aibridze'); ?></strong></label><br><input class="widefat"
			name="aibridze_portfolio_stat_one_label"
			value="<?php echo esc_attr(get_post_meta($post->ID, '_aibridze_portfolio_stat_one_label', true)); ?>"
			placeholder="Raised in Funding"></p>
	<p><label><strong><?php esc_html_e('Statistic two', 'aibridze'); ?></strong></label><br><input class="widefat"
			name="aibridze_portfolio_stat_two"
			value="<?php echo esc_attr(get_post_meta($post->ID, '_aibridze_portfolio_stat_two', true)); ?>" placeholder="500k+">
	</p>
	<p><label><strong><?php esc_html_e('Statistic two label', 'aibridze'); ?></strong></label><br><input class="widefat"
			name="aibridze_portfolio_stat_two_label"
			value="<?php echo esc_attr(get_post_meta($post->ID, '_aibridze_portfolio_stat_two_label', true)); ?>"
			placeholder="New Users Acquired"></p>
	<p><label><strong><?php esc_html_e('Project URL', 'aibridze'); ?></strong></label><br><input class="widefat" type="url"
			name="aibridze_portfolio_url"
			value="<?php echo esc_attr(get_post_meta($post->ID, '_aibridze_portfolio_url', true)); ?>" placeholder="https://">
	</p>
	<?php
}

function aibridze_save_portfolio(int $post_id): void
{
	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id))
		return;
	$nonce = sanitize_text_field(wp_unslash($_POST['aibridze_portfolio_nonce'] ?? ''));
	if (!wp_verify_nonce($nonce, 'aibridze_save_portfolio') || !current_user_can('edit_post', $post_id))
		return;
	update_post_meta($post_id, '_aibridze_portfolio_logo_id', absint($_POST['aibridze_portfolio_logo_id'] ?? 0));
	foreach (array('stat_one', 'stat_one_label', 'stat_two', 'stat_two_label') as $field)
		update_post_meta($post_id, '_aibridze_portfolio_' . $field, sanitize_text_field(wp_unslash($_POST['aibridze_portfolio_' . $field] ?? '')));
	update_post_meta($post_id, '_aibridze_portfolio_url', esc_url_raw(wp_unslash($_POST['aibridze_portfolio_url'] ?? '')));
}
add_action('save_post_portfolio', 'aibridze_save_portfolio');

/**
 * Load the media selector only on Service edit screens.
 */
function aibridze_service_admin_assets(string $hook_suffix): void
{
	$screen = get_current_screen();
	$is_content_screen = $screen && in_array($screen->post_type, array('service', 'industry', 'testimonial', 'video_testimonial', 'portfolio'), true) && in_array($hook_suffix, array('post.php', 'post-new.php'), true);
	$is_category_screen = $screen && 'service_category' === $screen->taxonomy && in_array($hook_suffix, array('edit-tags.php', 'term.php'), true);
	if (!$is_content_screen && !$is_category_screen) {
		return;
	}

	wp_enqueue_media();
	$admin_script_path = get_theme_file_path('/assets/js/admin-service-icon.js');
	wp_enqueue_script('aibridze-service-icon-admin', get_theme_file_uri('/assets/js/admin-service-icon.js'), array(), (string) filemtime($admin_script_path), true);
	if ($screen && 'video_testimonial' === $screen->post_type) {
		$video_admin_path = get_theme_file_path('/assets/js/admin-video-testimonial.js');
		wp_enqueue_script('aibridze-video-testimonial-admin', get_theme_file_uri('/assets/js/admin-video-testimonial.js'), array(), (string) filemtime($video_admin_path), true);
	}
}
add_action('admin_enqueue_scripts', 'aibridze_service_admin_assets');

/**
 * Return a bundled icon matched to a seeded Service slug.
 */
function aibridze_service_icon_url(WP_Post $service): string
{
	$icon_id = (int) get_post_meta($service->ID, '_aibridze_icon_id', true);
	if ($icon_id) {
		$custom_icon = wp_get_attachment_image_url($icon_id, 'thumbnail');
		if ($custom_icon) {
			return $custom_icon;
		}
	}

	foreach (array('svg', 'png') as $extension) {
		$icon_path = '/assets/images/services/' . $service->post_name . '.' . $extension;
		if (file_exists(get_theme_file_path($icon_path))) {
			return get_theme_file_uri($icon_path);
		}
	}
	return get_theme_file_uri('/assets/images/service-icon-default.png');
}

/**
 * Return the custom or bundled icon for an Industry post.
 */
function aibridze_industry_icon_url(WP_Post $industry): string
{
	$icon_id = (int) get_post_meta($industry->ID, '_aibridze_icon_id', true);
	if ($icon_id) {
		$custom_icon = wp_get_attachment_image_url($icon_id, 'thumbnail');
		if ($custom_icon) {
			return $custom_icon;
		}
	}

	foreach (array('svg', 'png') as $extension) {
		$icon_path = '/assets/images/industries/' . $industry->post_name . '.' . $extension;
		if (file_exists(get_theme_file_path($icon_path))) {
			return get_theme_file_uri($icon_path);
		}
	}
	return get_theme_file_uri('/assets/images/service-icon-default.png');
}

/**
 * Import a bundled theme image into the Media Library once.
 */
function aibridze_import_theme_image(string $theme_path, string $title): int
{
	$existing = get_posts(array('post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'meta_key' => '_aibridze_theme_source', 'meta_value' => $theme_path, 'fields' => 'ids'));
	if ($existing)
		return (int) $existing[0];

	$source = get_theme_file_path($theme_path);
	if (!file_exists($source))
		return 0;
	$upload = wp_upload_bits(wp_basename($source), null, file_get_contents($source));
	if (!empty($upload['error']))
		return 0;

	$file_type = wp_check_filetype($upload['file']);
	$attachment_id = wp_insert_attachment(array('post_mime_type' => $file_type['type'], 'post_title' => $title, 'post_status' => 'inherit'), $upload['file']);
	if (is_wp_error($attachment_id))
		return 0;
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
	update_post_meta($attachment_id, '_aibridze_theme_source', $theme_path);
	return (int) $attachment_id;
}

/**
 * Create the initial pages and menu data once for a fresh installation.
 */
function aibridze_seed_site_content(): void
{
	if ('1.0.9' === get_option('aibridze_seed_version')) {
		return;
	}

	$page_names = array('Services', 'Industries', 'Portfolio', 'About Us', 'Contact Us', 'Careers', 'Blogs', 'Terms & Conditions');
	foreach ($page_names as $page_name) {
		$slug = sanitize_title($page_name);
		if (!get_page_by_path($slug)) {
			wp_insert_post(
				array(
					'post_title' => $page_name,
					'post_name' => $slug,
					'post_status' => 'publish',
					'post_type' => 'page',
				)
			);
		}
	}

	$services = array(
		'AI Development' => array('AI Agent Development', 'Generative AI Development', 'AI Chatbot Development', 'AI Automation', 'RAG Development', 'Voice AI', 'Computer Vision', 'AI Studio'),
		'AI & Technology Consulting' => array('AI & ML Consulting', 'Business Automation Consulting', 'Software Consulting', 'Digital Transformation Consulting', 'Mobile App Consulting', 'Web Consulting'),
		'Staff Augmentation' => array('AI & ML Engineers', 'Python Developers', 'React Developers', 'Flutter Developers', 'Full Stack Developers', 'Backend Developers', 'QA Engineers', 'Dedicated Teams'),
		'Mobile App Development' => array('iOS App Development', 'Android App Development', 'Cross-Platform Development', 'App Maintenance & Support'),
		'Web App Development' => array('Custom Web Applications', 'SaaS Application Development', 'Progressive Web Apps', 'Web Maintenance & Support'),
		'UI/UX Design' => array('Product Design', 'UX Research & Strategy', 'Wireframing & Prototyping', 'Design Systems'),
	);

	foreach ($services as $category_name => $service_names) {
		$term = term_exists($category_name, 'service_category');
		if (!$term) {
			$term = wp_insert_term($category_name, 'service_category');
		}
		$term_id = is_array($term) ? (int) $term['term_id'] : (int) $term;

		foreach ($service_names as $service_order => $service_name) {
			$existing = get_page_by_path(sanitize_title($service_name), OBJECT, 'service');
			if (!$existing) {
				$post_id = wp_insert_post(
					array(
						'post_title' => $service_name,
						'post_name' => sanitize_title($service_name),
						'post_status' => 'publish',
						'post_type' => 'service',
						'post_excerpt' => sprintf(__('Explore our %s capabilities.', 'aibridze'), $service_name),
						'menu_order' => $service_order,
					)
				);
				if (!is_wp_error($post_id)) {
					wp_set_object_terms($post_id, $term_id, 'service_category');
				}
			} else {
				wp_update_post(array('ID' => $existing->ID, 'menu_order' => $service_order));
			}
		}
	}

	$old_research_service = get_page_by_path('ui-research-strategy', OBJECT, 'service');
	if ($old_research_service) {
		wp_update_post(array('ID' => $old_research_service->ID, 'post_title' => 'UX Research & Strategy', 'post_name' => 'ux-research-strategy', 'post_status' => 'draft'));
	}

	$industries = array('E-commerce', 'Healthcare', 'Entertainment', 'Education', 'Restaurant', 'Travel', 'Agriculture', 'Retail', 'Finance', 'Real Estate', 'E-Mobility', 'Logistics');
	$industry_images = array(
		'/assets/images/service-categories/mobile-app-development.png',
		'/assets/images/industries/industries-menu-visual.png',
		'/assets/images/process/step-4.jpg',
		'/assets/images/service-categories/staff-augmentation.png',
		'/assets/images/process/step-2.jpg',
		'/assets/images/hero-logistics-port.webp',
		'/assets/images/service-categories/technology-consulting.png',
		'/assets/images/service-categories/web-app-development.png',
		'/assets/images/portfolio-background.png',
		'/assets/images/consultation/left-background.png',
	);
	foreach ($industries as $industry_order => $industry_name) {
		$industry = get_page_by_path(sanitize_title($industry_name), OBJECT, 'industry');
		$industry_id = $industry ? $industry->ID : wp_insert_post(
			array(
				'post_title' => $industry_name,
				'post_name' => sanitize_title($industry_name),
				'post_status' => 'publish',
				'post_type' => 'industry',
				'menu_order' => $industry_order,
			)
		);
		if (!is_wp_error($industry_id) && !has_post_thumbnail($industry_id)) {
			$image_path = $industry_images[$industry_order % count($industry_images)];
			$image_id = aibridze_import_theme_image($image_path, $industry_name . ' industry');
			if ($image_id)
				set_post_thumbnail($industry_id, $image_id);
		}
	}

	$testimonials = array(
		array('John Doe', 'What we liked about the team is how they did not just understand what we were looking for but also gave us ideas on how we could make the process more efficient.', 'India Head', 'Holcim'),
		array('Kevin', 'The team truly amazed us with their profound insight into our needs and their inventive ideas for optimizing our workflow. They went above and beyond to ensure we felt heard and understood.', 'CEO', 'Service Galaxy'),
		array('Johnny', 'We were absolutely delighted by how the team not only understood our requirements but also came up with imaginative strategies to boost our efficiency. Their enthusiasm was contagious!', 'MD', 'Enuncia.ai'),
		array('Ronald', "What really impressed us was the team's knack for grasping our objectives and providing valuable suggestions for refining our processes. Their proactive approach made a significant difference.", 'CEO', 'AiHomes'),
	);
	foreach ($testimonials as $testimonial_order => $testimonial_data) {
		list($testimonial_name, $testimonial_quote, $testimonial_role, $testimonial_company) = $testimonial_data;
		$testimonial = get_page_by_path(sanitize_title($testimonial_name), OBJECT, 'testimonial');
		$testimonial_id = $testimonial ? $testimonial->ID : wp_insert_post(
			array(
				'post_title' => $testimonial_name,
				'post_name' => sanitize_title($testimonial_name),
				'post_status' => 'publish',
				'post_type' => 'testimonial',
			)
		);
		if (!is_wp_error($testimonial_id)) {
			wp_update_post(array('ID' => $testimonial_id, 'post_content' => $testimonial_quote, 'menu_order' => $testimonial_order));
			update_post_meta($testimonial_id, '_aibridze_testimonial_role', $testimonial_role);
			update_post_meta($testimonial_id, '_aibridze_testimonial_company', $testimonial_company);
		}
	}

	// Video testimonials are managed in the CPT; never seed demo clips.

	for ($portfolio_order = 0; $portfolio_order < 6; $portfolio_order++) {
		$portfolio_slug = 'kobil-super-app-' . ($portfolio_order + 1);
		$portfolio = get_page_by_path($portfolio_slug, OBJECT, 'portfolio');
		$portfolio_id = $portfolio ? $portfolio->ID : wp_insert_post(
			array(
				'post_title' => 'KOBIL Super App',
				'post_name' => $portfolio_slug,
				'post_status' => 'publish',
				'post_type' => 'portfolio',
				'post_excerpt' => 'We proudly serve as a trusted technology partner for Kobil, collaborating closely with them on multiple projects.',
				'menu_order' => $portfolio_order,
			)
		);
		if (!is_wp_error($portfolio_id)) {
			update_post_meta($portfolio_id, '_aibridze_portfolio_stat_one', '$52M');
			update_post_meta($portfolio_id, '_aibridze_portfolio_stat_one_label', 'Raised in Funding');
			update_post_meta($portfolio_id, '_aibridze_portfolio_stat_two', '500k+');
			update_post_meta($portfolio_id, '_aibridze_portfolio_stat_two_label', 'New Users Acquired');
		}
	}

	for ($logo_order = 0; $logo_order < 6; $logo_order++) {
		$logo_slug = 'trusted-business-logo-' . ($logo_order + 1);
		if (!get_page_by_path($logo_slug, OBJECT, 'trusted_business')) {
			wp_insert_post(
				array(
					'post_title' => sprintf('Trusted Business Logo %d', $logo_order + 1),
					'post_name' => $logo_slug,
					'post_status' => 'publish',
					'post_type' => 'trusted_business',
					'menu_order' => $logo_order,
				)
			);
		}
	}

	if (in_array(get_option('blogname'), array('', 'My WordPress Website'), true)) {
		update_option('blogname', 'AIBridze');
		update_option('blogdescription', 'Custom AI software development');
	}

	update_option('aibridze_seed_version', '1.0.9');
	flush_rewrite_rules();
}
add_action('init', 'aibridze_seed_site_content', 20);

/**
 * Get a published page URL with a safe archive fallback.
 */
function aibridze_page_url(string $slug, string $fallback = '/'): string
{
	$page = get_page_by_path($slug);
	return $page ? get_permalink($page) : home_url($fallback);
}

/**
 * Deliver consultation requests without exposing personal data in the URL.
 */
function aibridze_country_calling_codes(): array
{
	return array(
		'AF' => '+93',
		'AL' => '+355',
		'DZ' => '+213',
		'AS' => '+1684',
		'AD' => '+376',
		'AO' => '+244',
		'AI' => '+1264',
		'AQ' => '+672',
		'AG' => '+1268',
		'AR' => '+54',
		'AM' => '+374',
		'AW' => '+297',
		'AU' => '+61',
		'AT' => '+43',
		'AZ' => '+994',
		'AX' => '+35818',
		'BS' => '+1242',
		'BH' => '+973',
		'BD' => '+880',
		'BB' => '+1246',
		'BY' => '+375',
		'BE' => '+32',
		'BZ' => '+501',
		'BJ' => '+229',
		'BM' => '+1441',
		'BT' => '+975',
		'BO' => '+591',
		'BQ' => '+599',
		'BA' => '+387',
		'BW' => '+267',
		'BV' => '+47',
		'BR' => '+55',
		'IO' => '+246',
		'BN' => '+673',
		'BG' => '+359',
		'BF' => '+226',
		'BI' => '+257',
		'CV' => '+238',
		'KH' => '+855',
		'CM' => '+237',
		'CA' => '+1',
		'KY' => '+1345',
		'CF' => '+236',
		'TD' => '+235',
		'CL' => '+56',
		'CN' => '+86',
		'CX' => '+61',
		'CC' => '+61',
		'CO' => '+57',
		'KM' => '+269',
		'CG' => '+242',
		'CD' => '+243',
		'CK' => '+682',
		'CR' => '+506',
		'CI' => '+225',
		'HR' => '+385',
		'CU' => '+53',
		'CW' => '+599',
		'CY' => '+357',
		'CZ' => '+420',
		'DK' => '+45',
		'DJ' => '+253',
		'DM' => '+1767',
		'DO' => '+1809',
		'EC' => '+593',
		'EG' => '+20',
		'SV' => '+503',
		'GQ' => '+240',
		'ER' => '+291',
		'EE' => '+372',
		'SZ' => '+268',
		'ET' => '+251',
		'FK' => '+500',
		'FO' => '+298',
		'FJ' => '+679',
		'FI' => '+358',
		'FR' => '+33',
		'GF' => '+594',
		'PF' => '+689',
		'TF' => '+262',
		'GA' => '+241',
		'GM' => '+220',
		'GE' => '+995',
		'DE' => '+49',
		'GH' => '+233',
		'GI' => '+350',
		'GR' => '+30',
		'GL' => '+299',
		'GD' => '+1473',
		'GP' => '+590',
		'GU' => '+1671',
		'GT' => '+502',
		'GG' => '+44',
		'GN' => '+224',
		'GW' => '+245',
		'GY' => '+592',
		'HT' => '+509',
		'HM' => '+672',
		'VA' => '+39',
		'HN' => '+504',
		'HK' => '+852',
		'HU' => '+36',
		'IS' => '+354',
		'IN' => '+91',
		'ID' => '+62',
		'IR' => '+98',
		'IQ' => '+964',
		'IE' => '+353',
		'IM' => '+44',
		'IL' => '+972',
		'IT' => '+39',
		'JM' => '+1876',
		'JP' => '+81',
		'JE' => '+44',
		'JO' => '+962',
		'KZ' => '+7',
		'KE' => '+254',
		'KI' => '+686',
		'KP' => '+850',
		'KR' => '+82',
		'KW' => '+965',
		'KG' => '+996',
		'LA' => '+856',
		'LV' => '+371',
		'LB' => '+961',
		'LS' => '+266',
		'LR' => '+231',
		'LY' => '+218',
		'LI' => '+423',
		'LT' => '+370',
		'LU' => '+352',
		'MO' => '+853',
		'MG' => '+261',
		'MW' => '+265',
		'MY' => '+60',
		'MV' => '+960',
		'ML' => '+223',
		'MT' => '+356',
		'MH' => '+692',
		'MQ' => '+596',
		'MR' => '+222',
		'MU' => '+230',
		'YT' => '+262',
		'MX' => '+52',
		'FM' => '+691',
		'MD' => '+373',
		'MC' => '+377',
		'MN' => '+976',
		'ME' => '+382',
		'MS' => '+1664',
		'MA' => '+212',
		'MZ' => '+258',
		'MM' => '+95',
		'NA' => '+264',
		'NR' => '+674',
		'NP' => '+977',
		'NL' => '+31',
		'NC' => '+687',
		'NZ' => '+64',
		'NI' => '+505',
		'NE' => '+227',
		'NG' => '+234',
		'NU' => '+683',
		'NF' => '+672',
		'MK' => '+389',
		'MP' => '+1670',
		'NO' => '+47',
		'OM' => '+968',
		'PK' => '+92',
		'PW' => '+680',
		'PS' => '+970',
		'PA' => '+507',
		'PG' => '+675',
		'PY' => '+595',
		'PE' => '+51',
		'PH' => '+63',
		'PN' => '+64',
		'PL' => '+48',
		'PT' => '+351',
		'PR' => '+1787',
		'QA' => '+974',
		'RE' => '+262',
		'RO' => '+40',
		'RU' => '+7',
		'RW' => '+250',
		'BL' => '+590',
		'SH' => '+290',
		'KN' => '+1869',
		'LC' => '+1758',
		'MF' => '+590',
		'PM' => '+508',
		'VC' => '+1784',
		'WS' => '+685',
		'SM' => '+378',
		'ST' => '+239',
		'SA' => '+966',
		'SN' => '+221',
		'RS' => '+381',
		'SC' => '+248',
		'SL' => '+232',
		'SG' => '+65',
		'SX' => '+1721',
		'SK' => '+421',
		'SI' => '+386',
		'SB' => '+677',
		'SO' => '+252',
		'ZA' => '+27',
		'GS' => '+500',
		'SS' => '+211',
		'ES' => '+34',
		'LK' => '+94',
		'SD' => '+249',
		'SR' => '+597',
		'SJ' => '+47',
		'SE' => '+46',
		'CH' => '+41',
		'SY' => '+963',
		'TW' => '+886',
		'TJ' => '+992',
		'TZ' => '+255',
		'TH' => '+66',
		'TL' => '+670',
		'TG' => '+228',
		'TK' => '+690',
		'TO' => '+676',
		'TT' => '+1868',
		'TN' => '+216',
		'TR' => '+90',
		'TM' => '+993',
		'TC' => '+1649',
		'TV' => '+688',
		'UG' => '+256',
		'UA' => '+380',
		'AE' => '+971',
		'GB' => '+44',
		'US' => '+1',
		'UM' => '+1',
		'UY' => '+598',
		'UZ' => '+998',
		'VU' => '+678',
		'VE' => '+58',
		'VN' => '+84',
		'VG' => '+1284',
		'VI' => '+1340',
		'WF' => '+681',
		'EH' => '+212',
		'YE' => '+967',
		'ZM' => '+260',
		'ZW' => '+263',
	);
}

function aibridze_country_flag(string $country_code): string
{
	$country_code = strtoupper($country_code);
	if (2 !== strlen($country_code)) {
		return '';
	}
	return html_entity_decode('&#' . (127397 + ord($country_code[0])) . ';&#' . (127397 + ord($country_code[1])) . ';', ENT_QUOTES, 'UTF-8');
}

function aibridze_handle_consultation(): void
{
	$redirect = wp_get_referer() ?: home_url('/');
	$nonce = isset($_POST['aibridze_consultation_nonce']) ? sanitize_text_field(wp_unslash($_POST['aibridze_consultation_nonce'])) : '';
	$honeypot = isset($_POST['company_website']) ? trim((string) wp_unslash($_POST['company_website'])) : '';

	if (!wp_verify_nonce($nonce, 'aibridze_consultation') || '' !== $honeypot) {
		wp_safe_redirect(add_query_arg('consultation', 'error', $redirect));
		exit;
	}

	$name = sanitize_text_field(wp_unslash($_POST['full_name'] ?? ''));
	$email_raw = trim(wp_unslash($_POST['email'] ?? ''));
	$email = sanitize_email($email_raw);
	$designation = sanitize_text_field(wp_unslash($_POST['designation'] ?? ''));
	$country_code = sanitize_text_field(wp_unslash($_POST['country_code'] ?? ''));
	$phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
	$budget = sanitize_text_field(wp_unslash($_POST['budget'] ?? ''));
	$message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

	if ('' === $name || !is_email($email_raw) || $email !== $email_raw || '' === $message) {
		wp_safe_redirect(add_query_arg('consultation', 'invalid', $redirect));
		exit;
	}

	$recipient = (string) apply_filters('aibridze_consultation_recipient', 'sales@aibridze.com');
	$article = array();
	$source_id = absint($_POST['blog_article_id'] ?? 0);
	$source = $source_id ? get_post($source_id) : null;
	if ($source && 'post' === $source->post_type && 'publish' === $source->post_status) {
		$article = array(
			'title' => wp_strip_all_tags(get_the_title($source)),
			'url' => get_permalink($source),
		);
	}
	require_once get_theme_file_path('/inc/enquiry-email.php');
	$fields = compact('name', 'email', 'phone', 'country_code', 'designation', 'budget', 'message');
	$fields['subject'] = sanitize_text_field(wp_unslash($_POST['subject'] ?? ''));
	$fields['company'] = sanitize_text_field(wp_unslash($_POST['company'] ?? ''));
	$fields['submitted_on'] = current_time('mysql');
	$fields['remote_ip'] = filter_var($_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP) ?: '';
	$fields['source_url'] = esc_url_raw($redirect);
	$fields['form_type'] = $article ? 'blog' : 'enquiry';
	$fields['article'] = $article;
	$submission_id = aibridze_save_enquiry($fields);
	if (is_wp_error($submission_id) || !$submission_id) {
		wp_safe_redirect(add_query_arg('consultation', 'error', $redirect));
		exit;
	}
	$notification = aibridze_enquiry_email($fields, $article);
	$subject = $notification['subject'];
	$body = $notification['body'];
	$headers = array('Content-Type: text/plain; charset=UTF-8', 'From: AiBridze Technologies <sales@aibridze.com>', sprintf('Reply-To: %s <%s>', $name, $email));
	aibridze_send_record_mail($submission_id, 'notification', $recipient, $subject, $body, $headers);
	aibridze_send_record_mail($submission_id, 'reply', $email, 'We’ve received your enquiry — AiBridze', aibridze_enquiry_reply($name), array('Content-Type: text/plain; charset=UTF-8', 'From: AiBridze Technologies <sales@aibridze.com>', 'Reply-To: sales@aibridze.com'));

	wp_safe_redirect(home_url('/thank-you/'));
	exit;
}
add_action('admin_post_nopriv_aibridze_consultation', 'aibridze_handle_consultation');
add_action('admin_post_aibridze_consultation', 'aibridze_handle_consultation');

/**
 * Optional SMTP transport. Define AIBRIDZE_SMTP_* constants in wp-config.php.
 */
function aibridze_configure_smtp($phpmailer): void
{
	if (!defined('AIBRIDZE_SMTP_HOST') || !AIBRIDZE_SMTP_HOST) {
		return;
	}
	$explicit_from = $phpmailer->From;
	$profiles = array(
		'sales@aibridze.com' => array('AIBRIDZE_SALES_SMTP_', 'AiBridze Technologies'),
		'career@aibridze.com' => array('AIBRIDZE_CAREER_SMTP_', 'AiBridze Careers'),
	);
	$profile = $profiles[strtolower($explicit_from)] ?? null;
	// WordPress reuses PHPMailer; never reuse an authenticated session across accounts.
	$phpmailer->smtpClose();
	$phpmailer->Sender = '';
	$site_host = wp_parse_url(network_home_url(), PHP_URL_HOST);
	$default_from = 'wordpress@' . preg_replace('#^www\.#', '', (string) $site_host);
	$phpmailer->isSMTP();
	// Use WordPress's maintained CA bundle where the PHP runtime lacks a trust store.
	$ca_bundle = ABSPATH . WPINC . '/certificates/ca-bundle.crt';
	if (is_readable($ca_bundle)) {
		$phpmailer->SMTPOptions['ssl'] = array('cafile' => $ca_bundle, 'verify_peer' => true, 'verify_peer_name' => true);
	}
	$phpmailer->Host = AIBRIDZE_SMTP_HOST;
	$phpmailer->Port = defined('AIBRIDZE_SMTP_PORT') ? (int) AIBRIDZE_SMTP_PORT : 587;
	$phpmailer->SMTPAuth = defined('AIBRIDZE_SMTP_USERNAME') && '' !== AIBRIDZE_SMTP_USERNAME;
	$phpmailer->Username = defined('AIBRIDZE_SMTP_USERNAME') ? AIBRIDZE_SMTP_USERNAME : '';
	$phpmailer->Password = defined('AIBRIDZE_SMTP_PASSWORD') ? AIBRIDZE_SMTP_PASSWORD : '';
	$phpmailer->SMTPSecure = defined('AIBRIDZE_SMTP_ENCRYPTION') ? AIBRIDZE_SMTP_ENCRYPTION : 'tls';
	if ($profile) {
		$username_key = $profile[0] . 'USERNAME';
		$password_key = $profile[0] . 'PASSWORD';
		if (!defined($username_key) || !defined($password_key) || !constant($username_key) || !constant($password_key)) {
			throw new \PHPMailer\PHPMailer\Exception('SMTP credentials are missing for this form.');
		}
		$phpmailer->Username = constant($username_key);
		$phpmailer->Password = constant($password_key);
		$phpmailer->SMTPAuth = true;
		$phpmailer->setFrom(strtolower($explicit_from), $profile[1], false);
		$phpmailer->Sender = strtolower($explicit_from);
	} elseif (!$explicit_from || 0 === strcasecmp($explicit_from, $default_from)) {
		$from_email = defined('AIBRIDZE_SMTP_FROM_EMAIL') ? AIBRIDZE_SMTP_FROM_EMAIL : get_option('admin_email');
		$phpmailer->setFrom($from_email, 'AIBridze', false);
	}
}
add_action('phpmailer_init', 'aibridze_configure_smtp');

/**
 * Register shared social profile URLs in Appearance > Customize.
 */
function aibridze_customize_social_links(WP_Customize_Manager $customizer): void
{
	$customizer->add_section(
		'aibridze_social_links',
		array(
			'title' => __('Social Links', 'aibridze'),
			'description' => __('These links are used throughout the website, including the About Us hero and footer.', 'aibridze'),
			'priority' => 130,
		)
	);

	$socials = array(
		'facebook' => __('Facebook URL', 'aibridze'),
		'instagram' => __('Instagram URL', 'aibridze'),
		'linkedin' => __('LinkedIn URL', 'aibridze'),
		'x' => __('X URL', 'aibridze'),
	);

	foreach ($socials as $network => $label) {
		$setting = 'aibridze_social_' . $network;
		$customizer->add_setting(
			$setting,
			array(
				'default' => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$customizer->add_control(
			$setting,
			array(
				'label' => $label,
				'section' => 'aibridze_social_links',
				'type' => 'url',
			)
		);
	}
}
add_action('customize_register', 'aibridze_customize_social_links');

/** Technology logos for the homepage services section. */
function aibridze_technology_partners(): array
{
	return array(
		'mongodb' => 'MongoDB',
		'nvidia' => 'NVIDIA',
		'twilio' => 'Twilio',
		'google-cloud' => 'Google Cloud',
		'aws' => 'AWS',
		'microsoft-azure' => 'Microsoft Azure',
	);
}

/** Keep service-section logos independent of customer logos. */
function aibridze_customize_technology_partners(WP_Customize_Manager $customizer): void
{
	$customizer->add_section('aibridze_technology_partners', array(
		'title' => __('Homepage Technology Partners', 'aibridze'),
		'priority' => 131,
	));
	foreach (aibridze_technology_partners() as $slug => $label) {
		$setting = 'aibridze_technology_partner_' . $slug;
		$customizer->add_setting($setting, array(
			'default' => get_theme_file_uri('/assets/images/technology-partners/' . $slug . '.png'),
			'sanitize_callback' => 'esc_url_raw',
		));
		$customizer->add_control(new WP_Customize_Image_Control($customizer, $setting, array(
			'label' => $label,
			'section' => 'aibridze_technology_partners',
		)));
	}
}
add_action('customize_register', 'aibridze_customize_technology_partners');

/**
 * Get the dashboard-managed social profiles used across the theme.
 *
 * @return array<string, string>
 */
function aibridze_social_links(): array
{
	return array(
		'facebook' => (string) get_theme_mod('aibridze_social_facebook', ''),
		'instagram' => (string) get_theme_mod('aibridze_social_instagram', ''),
		'linkedin' => (string) get_theme_mod('aibridze_social_linkedin', ''),
		'x' => (string) get_theme_mod('aibridze_social_x', ''),
	);
}

/** Provision the confirmation page once without reseeding existing site content. */
function aibridze_register_thank_you_page(): void
{
	if (get_option('aibridze_thank_you_page_v1'))
		return;
	$page = get_page_by_path('thank-you');
	$page_id = $page ? $page->ID : wp_insert_post(array(
		'post_title' => 'Thank You',
		'post_name' => 'thank-you',
		'post_status' => 'publish',
		'post_type' => 'page',
	), true);
	if (!is_wp_error($page_id) && $page_id)
		update_option('aibridze_thank_you_page_v1', 1, false);
}
add_action('init', 'aibridze_register_thank_you_page');

/** Built-in SEO fields and metadata for public content. */
function aibridze_seo_post_types(): array
{
	return array('post', 'page', 'service', 'industry', 'portfolio');
}

function aibridze_seo_meta_key(string $field): string
{
	return '_aibridze_seo_' . $field;
}

function aibridze_seo_value(int $post_id, string $field): string
{
	return trim((string) get_post_meta($post_id, aibridze_seo_meta_key($field), true));
}

function aibridze_seo_description(?WP_Post $post = null): string
{
	$post = $post ?: get_post();
	if (!$post) {
		return 'AiBridze builds custom AI-powered software, automation, and digital solutions for ambitious businesses.';
	}
	$description = aibridze_seo_value($post->ID, 'description');
	if (!$description) {
		$description = has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->post_content)), 30, '...');
	}
	return wp_html_excerpt(trim(wp_strip_all_tags($description)), 160, '...');
}

function aibridze_seo_add_meta_box(): void
{
	foreach (aibridze_seo_post_types() as $post_type) {
		add_meta_box('aibridze-seo', __('SEO Settings', 'aibridze'), 'aibridze_seo_render_meta_box', $post_type, 'normal', 'default');
	}
}
add_action('add_meta_boxes', 'aibridze_seo_add_meta_box');

function aibridze_seo_render_meta_box(WP_Post $post): void
{
	wp_nonce_field('aibridze_seo_save', 'aibridze_seo_nonce');
	$fields = array(
		'title' => __('SEO title', 'aibridze'),
		'description' => __('Meta description', 'aibridze'),
		'focus_keyword' => __('Focus keyword', 'aibridze'),
		'og_image' => __('Social image URL', 'aibridze'),
	);
	foreach ($fields as $field => $label) {
		$value = aibridze_seo_value($post->ID, $field);
		echo '<p><label for="aibridze-seo-' . esc_attr($field) . '"><strong>' . esc_html($label) . '</strong></label><br>';
		if ('description' === $field) {
			echo '<textarea class="widefat" rows="3" id="aibridze-seo-' . esc_attr($field) . '" name="aibridze_seo[' . esc_attr($field) . ']" maxlength="160">' . esc_textarea($value) . '</textarea>';
		} else {
			echo '<input class="widefat" type="text" id="aibridze-seo-' . esc_attr($field) . '" name="aibridze_seo[' . esc_attr($field) . ']" value="' . esc_attr($value) . '"></p>';
		}
	}
	$noindex = '1' === aibridze_seo_value($post->ID, 'noindex');
	echo '<p><label><input type="checkbox" name="aibridze_seo[noindex]" value="1" ' . checked($noindex, true, false) . '> ' . esc_html__('Hide this page from search engines', 'aibridze') . '</label></p>';
}

function aibridze_seo_save_meta(int $post_id): void
{
	if (!isset($_POST['aibridze_seo_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['aibridze_seo_nonce'])), 'aibridze_seo_save')) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !current_user_can('edit_post', $post_id) || !in_array(get_post_type($post_id), aibridze_seo_post_types(), true)) {
		return;
	}
	$input = isset($_POST['aibridze_seo']) && is_array($_POST['aibridze_seo']) ? wp_unslash($_POST['aibridze_seo']) : array();
	$values = array(
		'title' => sanitize_text_field($input['title'] ?? ''),
		'description' => sanitize_textarea_field($input['description'] ?? ''),
		'focus_keyword' => sanitize_text_field($input['focus_keyword'] ?? ''),
		'og_image' => esc_url_raw($input['og_image'] ?? ''),
		'noindex' => !empty($input['noindex']) ? '1' : '',
	);
	foreach ($values as $field => $value) {
		$key = aibridze_seo_meta_key($field);
		$value ? update_post_meta($post_id, $key, $value) : delete_post_meta($post_id, $key);
	}
}
add_action('save_post', 'aibridze_seo_save_meta');

function aibridze_seo_title(string $fallback = ''): string
{
	$post = get_post();
	$title = $post ? aibridze_seo_value($post->ID, 'title') : '';
	return $title ?: ($fallback ?: wp_get_document_title());
}

function aibridze_seo_document_title(array $parts): array
{
	$post = get_post();
	$title = $post ? aibridze_seo_value($post->ID, 'title') : '';
	if ($title) {
		$parts['title'] = $title;
	}
	return $parts;
}
add_filter('document_title_parts', 'aibridze_seo_document_title');

function aibridze_seo_head(): void
{
	if (is_admin() || is_feed() || is_404()) {
		return;
	}
	$post = get_post();
	$url = is_front_page() ? home_url('/') : (is_singular() ? get_permalink($post) : home_url('/'));
	$title = aibridze_seo_title();
	$description = aibridze_seo_description($post);
	$image = $post && aibridze_seo_value($post->ID, 'og_image') ? aibridze_seo_value($post->ID, 'og_image') : ($post ? get_the_post_thumbnail_url($post, 'large') : '');
	echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr(is_singular('post') ? 'article' : 'website') . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
	if ($image)
		echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
	if ($image)
		echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
}
add_action('wp_head', 'aibridze_seo_head', 2);

function aibridze_seo_robots(array $robots): array
{
	$post = get_post();
	if ($post && '1' === aibridze_seo_value($post->ID, 'noindex')) {
		$robots['noindex'] = true;
		$robots['nofollow'] = true;
	}
	return $robots;
}
add_filter('wp_robots', 'aibridze_seo_robots');

function aibridze_seo_schema(): void
{
	if (is_admin() || is_feed() || is_404())
		return;
	$post = get_post();
	$home = home_url('/');
	$graph = array(
		array('@type' => 'Organization', '@id' => $home . '#organization', 'name' => 'AiBridze', 'url' => $home, 'logo' => array('@type' => 'ImageObject', 'url' => get_theme_file_uri('/assets/images/aibridze-logo.png'))),
		array('@type' => 'WebSite', '@id' => $home . '#website', 'url' => $home, 'name' => 'AiBridze', 'publisher' => array('@id' => $home . '#organization')),
	);
	if (is_singular() && $post) {
		$type = 'WebPage';
		if ('post' === $post->post_type)
			$type = 'Article';
		if ('service' === $post->post_type)
			$type = 'Service';
		$entity = array('@type' => $type, '@id' => get_permalink($post) . '#webpage', 'url' => get_permalink($post), 'name' => aibridze_seo_title(), 'description' => aibridze_seo_description($post), 'isPartOf' => array('@id' => $home . '#website'), 'publisher' => array('@id' => $home . '#organization'));
		if ('Article' === $type)
			$entity['datePublished'] = get_the_date('c', $post);
		if ('Article' === $type)
			$entity['dateModified'] = get_the_modified_date('c', $post);
		$graph[] = $entity;
		$items = array(array('@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home));
		$items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => get_the_title($post), 'item' => get_permalink($post));
		$graph[] = array('@type' => 'BreadcrumbList', 'itemListElement' => $items);
	}
	echo '<script type="application/ld+json">' . wp_json_encode(array('@context' => 'https://schema.org', '@graph' => $graph), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'aibridze_seo_schema', 3);
