<?php
/** Dashboard-managed service page sections. */

if ( ! defined( 'ABSPATH' ) ) exit;

function aibridze_service_field_groups(): array {
	return array(
		'hero' => array(
			'label' => __( '1. Hero', 'aibridze' ),
			'fields' => array(
				'hero_eyebrow' => array( 'label' => 'Eyebrow', 'type' => 'text' ),
				'hero_title' => array( 'label' => 'Heading', 'type' => 'text' ),
				'hero_description' => array( 'label' => 'Description', 'type' => 'textarea' ),
				'hero_image' => array( 'label' => 'Desktop background image', 'type' => 'media' ),
				'hero_mobile_image' => array( 'label' => 'Mobile background image', 'type' => 'media' ),
				'hero_cta_label' => array( 'label' => 'Button label', 'type' => 'text' ),
				'hero_cta_url' => array( 'label' => 'Button URL', 'type' => 'url' ),
				'category_card_description' => array( 'label' => 'Service category card description', 'type' => 'textarea' ),
			),
		),
		'intro' => array(
			'label' => __( '2. Introduction', 'aibridze' ),
			'fields' => array(
				'intro_eyebrow' => array( 'label' => 'Eyebrow', 'type' => 'text' ),
				'intro_title' => array( 'label' => 'Heading', 'type' => 'text' ),
				'intro_description' => array( 'label' => 'Description (HTML allowed)', 'type' => 'rich' ),
				'intro_image' => array( 'label' => 'Section image', 'type' => 'media' ),
			),
		),
		'benefits' => array( 'label' => '3. Business benefits', 'repeater' => 'benefits', 'title' => true ),
		'technology' => array( 'label' => '4. Technology stack', 'repeater' => 'technology', 'title' => true ),
		'expertise' => array( 'label' => '5. Expertise / tabs', 'repeater' => 'expertise', 'title' => true ),
		'solutions' => array( 'label' => '6. Solutions / cards', 'repeater' => 'solutions', 'title' => true ),
		'capabilities' => array( 'label' => '7. Capabilities', 'repeater' => 'capabilities', 'title' => true ),
		'process' => array( 'label' => '8. Development process', 'repeater' => 'process', 'title' => true ),
		'industries' => array( 'label' => '9. Industries', 'repeater' => 'industries', 'title' => true ),
		'trust_principles' => array(
			'label' => '10. Responsible AI trust card',
			'fields' => array(
				'trust_title' => array( 'label' => 'Heading', 'type' => 'text' ),
				'trust_description' => array( 'label' => 'Description', 'type' => 'textarea' ),
				'trust_image' => array( 'label' => 'Section image', 'type' => 'media' ),
				'trust_principles_label' => array( 'label' => 'Principles label', 'type' => 'text' ),
				'trust_cta_label' => array( 'label' => 'Button label', 'type' => 'text' ),
				'trust_cta_url' => array( 'label' => 'Button URL', 'type' => 'url' ),
			),
			'repeater' => 'trust_principles',
		),
		'cta' => array(
			'label' => '11. Trust / conversion banner',
			'fields' => array(
				'cta_position' => array( 'label' => 'Section position (default, after_technology, or before_expertise)', 'type' => 'text' ),
				'cta_variant' => array( 'label' => 'Design variant (for example: agent-banner)', 'type' => 'text' ),
				'cta_title' => array( 'label' => 'Heading', 'type' => 'text' ),
				'cta_highlight' => array( 'label' => 'Highlighted heading text', 'type' => 'text' ),
				'cta_description' => array( 'label' => 'Description', 'type' => 'textarea' ),
				'cta_image' => array( 'label' => 'Image', 'type' => 'media' ),
				'cta_label' => array( 'label' => 'Button label', 'type' => 'text' ),
				'cta_url' => array( 'label' => 'Button URL', 'type' => 'url' ),
			),
		),
		'action_cta' => array(
			'label' => '12. Final action banner',
			'fields' => array(
				'action_cta_title' => array( 'label' => 'Heading', 'type' => 'text' ),
				'action_cta_description' => array( 'label' => 'Description', 'type' => 'textarea' ),
				'action_cta_image' => array( 'label' => 'Image', 'type' => 'media' ),
				'action_cta_label' => array( 'label' => 'Button label', 'type' => 'text' ),
				'action_cta_url' => array( 'label' => 'Button URL', 'type' => 'url' ),
			),
		),
		'faq' => array( 'label' => '13. FAQs', 'repeater' => 'faqs', 'title' => true ),
	);
}

function aibridze_service_meta_key( string $name ): string { return '_aibridze_service_' . $name; }
function aibridze_service_value( string $name, $default = '' ) {
	$value = get_post_meta( get_the_ID(), aibridze_service_meta_key( $name ), true );
	return '' === $value ? $default : $value;
}

function aibridze_service_register_meta(): void {
	register_post_meta( 'service', '_aibridze_service_layout', array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_key' ) );
	foreach ( aibridze_service_field_groups() as $group ) {
		foreach ( $group['fields'] ?? array() as $name => $field ) {
			register_post_meta( 'service', aibridze_service_meta_key( $name ), array( 'type' => 'string', 'single' => true, 'show_in_rest' => true ) );
		}
		if ( ! empty( $group['repeater'] ) ) {
			register_post_meta( 'service', aibridze_service_meta_key( $group['repeater'] ), array( 'type' => 'array', 'single' => true, 'show_in_rest' => false ) );
		}
	}
}
add_action( 'init', 'aibridze_service_register_meta', 12 );

function aibridze_service_add_meta_box(): void {
	add_meta_box( 'aibridze-service-builder', __( 'Service Page Builder', 'aibridze' ), 'aibridze_service_render_meta_box', 'service', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'aibridze_service_add_meta_box' );

function aibridze_service_render_field( int $post_id, string $name, array $field ): void {
	$key = aibridze_service_meta_key( $name );
	$value = (string) get_post_meta( $post_id, $key, true );
	echo '<label class="service-builder__field"><strong>' . esc_html( $field['label'] ) . '</strong>';
	if ( 'textarea' === $field['type'] || 'rich' === $field['type'] ) {
		echo '<textarea name="aibridze_service[' . esc_attr( $name ) . ']" rows="4">' . esc_textarea( $value ) . '</textarea>';
	} elseif ( 'media' === $field['type'] ) {
		echo '<span class="service-builder__media"><input type="url" name="aibridze_service[' . esc_attr( $name ) . ']" value="' . esc_attr( $value ) . '" placeholder="https://…"><button type="button" class="button" data-service-media>Choose image</button></span>';
	} else {
		echo '<input type="' . esc_attr( $field['type'] ) . '" name="aibridze_service[' . esc_attr( $name ) . ']" value="' . esc_attr( $value ) . '">';
	}
	echo '</label>';
}

function aibridze_service_render_repeater( int $post_id, string $name ): void {
	$rows = get_post_meta( $post_id, aibridze_service_meta_key( $name ), true );
	$rows = is_array( $rows ) ? $rows : array();
	$rows[] = array( '__template' => true );
	echo '<div class="service-repeater" data-repeater="' . esc_attr( $name ) . '"><div class="service-repeater__rows">';
	foreach ( $rows as $index => $row ) {
		$template = ! empty( $row['__template'] );
		$token = $template ? '__INDEX__' : (string) $index;
		echo '<div class="service-repeater__row' . ( $template ? ' is-template' : '' ) . '"' . ( $template ? ' hidden' : '' ) . ' data-row>';
		echo '<div class="service-repeater__bar"><strong>Item</strong><span><button type="button" class="button-link" data-row-up>↑</button> <button type="button" class="button-link" data-row-down>↓</button> <button type="button" class="button-link-delete" data-row-remove>Remove</button></span></div>';
		foreach ( array( 'group' => 'Group / tab label', 'title' => 'Title', 'description' => 'Description (HTML allowed)', 'image' => 'Image URL or icon URL', 'link_label' => 'Link label', 'link_url' => 'Link URL' ) as $field => $label ) {
			$value = $template ? '' : (string) ( $row[ $field ] ?? '' );
			echo '<label><span>' . esc_html( $label ) . '</span>';
			if ( 'description' === $field ) echo '<textarea rows="3" name="aibridze_repeaters[' . esc_attr( $name ) . '][' . esc_attr( $token ) . '][' . esc_attr( $field ) . ']">' . esc_textarea( $value ) . '</textarea>';
			elseif ( 'image' === $field ) echo '<span class="service-builder__media"><input type="url" name="aibridze_repeaters[' . esc_attr( $name ) . '][' . esc_attr( $token ) . '][' . esc_attr( $field ) . ']" value="' . esc_attr( $value ) . '"><button type="button" class="button" data-service-media>Choose</button></span>';
			else echo '<input type="text" name="aibridze_repeaters[' . esc_attr( $name ) . '][' . esc_attr( $token ) . '][' . esc_attr( $field ) . ']" value="' . esc_attr( $value ) . '">';
			echo '</label>';
		}
		echo '</div>';
	}
	echo '</div><button type="button" class="button button-secondary" data-row-add>Add item</button></div>';
}

function aibridze_service_render_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'aibridze_save_service', 'aibridze_service_nonce' );
	$layout = get_post_meta( $post->ID, '_aibridze_service_layout', true ) ?: 'default';
	echo '<div class="service-builder"><p class="description">Empty sections are automatically omitted on the front end. Use the main WordPress editor for additional rich content.</p>';
	echo '<label class="service-builder__layout"><strong>Page style</strong><select name="aibridze_service_layout">';
	foreach ( array( 'default' => 'Default', 'agent' => 'AI Agent', 'generative' => 'Generative AI', 'chatbot' => 'AI Chatbot', 'automation' => 'AI Automation', 'rag' => 'RAG', 'voice' => 'Voice AI', 'vision' => 'Computer Vision', 'ai-development' => 'AI Development', 'ml' => 'Machine Learning' ) as $key => $label ) echo '<option value="' . esc_attr( $key ) . '"' . selected( $layout, $key, false ) . '>' . esc_html( $label ) . '</option>';
	echo '</select></label>';
	foreach ( aibridze_service_field_groups() as $group ) {
		echo '<details class="service-builder__panel"><summary>' . esc_html( $group['label'] ) . '</summary><div class="service-builder__panel-body">';
		if ( ! empty( $group['title'] ) ) {
			$prefix = $group['repeater'];
			aibridze_service_render_field( $post->ID, $prefix . '_eyebrow', array( 'label' => 'Section eyebrow', 'type' => 'text' ) );
			aibridze_service_render_field( $post->ID, $prefix . '_title', array( 'label' => 'Section heading', 'type' => 'text' ) );
			aibridze_service_render_field( $post->ID, $prefix . '_description', array( 'label' => 'Section description', 'type' => 'textarea' ) );
			aibridze_service_render_repeater( $post->ID, $prefix );
		} else {
			foreach ( $group['fields'] as $name => $field ) aibridze_service_render_field( $post->ID, $name, $field );
			if ( ! empty( $group['repeater'] ) ) aibridze_service_render_repeater( $post->ID, $group['repeater'] );
		}
		echo '</div></details>';
	}
	echo '<details class="service-builder__panel"><summary>13. Display options</summary><div class="service-builder__panel-body">';
	foreach ( array( 'show_shared_contact' => 'Show shared FAQ and consultation form instead of service FAQs' ) as $name => $label ) {
		$checked = get_post_meta( $post->ID, aibridze_service_meta_key( $name ), true );
		echo '<label><input type="checkbox" name="aibridze_service[' . esc_attr( $name ) . ']" value="1" ' . checked( $checked, '1', false ) . '> ' . esc_html( $label ) . '</label>';
	}
	echo '</div></details></div>';
}

function aibridze_service_save_meta( int $post_id ): void {
	if ( ! isset( $_POST['aibridze_service_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aibridze_service_nonce'] ) ), 'aibridze_save_service' ) || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) ) return;
	update_post_meta( $post_id, '_aibridze_service_layout', sanitize_key( wp_unslash( $_POST['aibridze_service_layout'] ?? 'default' ) ) );
	$submitted = isset( $_POST['aibridze_service'] ) && is_array( $_POST['aibridze_service'] ) ? wp_unslash( $_POST['aibridze_service'] ) : array();
	$all_fields = array( 'show_shared_contact' );
	foreach ( aibridze_service_field_groups() as $group ) {
		$all_fields = array_merge( $all_fields, array_keys( $group['fields'] ?? array() ) );
		if ( ! empty( $group['repeater'] ) ) $all_fields = array_merge( $all_fields, array( $group['repeater'] . '_eyebrow', $group['repeater'] . '_title', $group['repeater'] . '_description' ) );
	}
	foreach ( array_unique( $all_fields ) as $name ) {
		$value = $submitted[ $name ] ?? '';
		if ( str_contains( $name, 'description' ) ) $value = wp_kses_post( $value );
		elseif ( str_contains( $name, 'url' ) || str_contains( $name, 'image' ) ) $value = esc_url_raw( $value );
		else $value = sanitize_text_field( $value );
		'' === $value ? delete_post_meta( $post_id, aibridze_service_meta_key( $name ) ) : update_post_meta( $post_id, aibridze_service_meta_key( $name ), $value );
	}
	$repeaters = isset( $_POST['aibridze_repeaters'] ) && is_array( $_POST['aibridze_repeaters'] ) ? wp_unslash( $_POST['aibridze_repeaters'] ) : array();
	foreach ( aibridze_service_field_groups() as $group ) {
		if ( empty( $group['repeater'] ) ) continue;
		$name = $group['repeater']; $clean = array();
		foreach ( $repeaters[ $name ] ?? array() as $row ) {
			if ( ! is_array( $row ) || empty( trim( (string) ( $row['title'] ?? '' ) ) ) ) continue;
			$clean[] = array( 'group' => sanitize_text_field( $row['group'] ?? '' ), 'title' => sanitize_text_field( $row['title'] ?? '' ), 'description' => wp_kses_post( $row['description'] ?? '' ), 'image' => esc_url_raw( $row['image'] ?? '' ), 'link_label' => sanitize_text_field( $row['link_label'] ?? '' ), 'link_url' => esc_url_raw( $row['link_url'] ?? '' ) );
		}
		$clean ? update_post_meta( $post_id, aibridze_service_meta_key( $name ), $clean ) : delete_post_meta( $post_id, aibridze_service_meta_key( $name ) );
	}
}
add_action( 'save_post_service', 'aibridze_service_save_meta' );

function aibridze_service_builder_admin_assets( string $hook ): void {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || 'service' !== get_current_screen()->post_type ) return;
	wp_enqueue_media();
	wp_enqueue_style( 'aibridze-service-admin', get_theme_file_uri( '/assets/css/admin-service-page.css' ), array(), (string) filemtime( get_theme_file_path( '/assets/css/admin-service-page.css' ) ) );
	wp_enqueue_script( 'aibridze-service-admin', get_theme_file_uri( '/assets/js/admin-service-page.js' ), array(), (string) filemtime( get_theme_file_path( '/assets/js/admin-service-page.js' ) ), true );
}
add_action( 'admin_enqueue_scripts', 'aibridze_service_builder_admin_assets' );

/** Populate the approved AI Agent hero through the same fields exposed in post edit. */
function aibridze_seed_ai_agent_service_hero(): void {
	if ( get_option( 'aibridze_ai_agent_hero_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$values = array(
		'hero_title'        => 'AI Agent Development Services',
		'hero_description'  => 'Transform your business with custom AI agent development services. From autonomous AI agents and intelligent assistants to multi-agent systems and workflow automation, we build scalable AI solutions that drive measurable business outcomes.',
		'hero_image'        => get_theme_file_uri( '/assets/images/services/ai-agent-hero.png' ),
		'hero_mobile_image' => get_theme_file_uri( '/assets/images/services/ai-agent-hero.png' ),
		'hero_cta_label'    => 'Consult Our AI Experts',
		'hero_cta_url'      => aibridze_page_url( 'contact-us', '/contact-us/' ),
	);
	update_post_meta( $service->ID, '_aibridze_service_layout', 'agent' );
	foreach ( $values as $field => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $field ), $value );
	update_option( 'aibridze_ai_agent_hero_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_hero', 40 );

/** Populate the approved AI Agent introduction through editable Service fields. */
function aibridze_seed_ai_agent_service_intro(): void {
	if ( get_option( 'aibridze_ai_agent_intro_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$description = '<p>Artificial Intelligence is evolving beyond chatbots. Modern businesses need AI agents that can understand goals, reason through complex tasks, and make decisions. They must also interact with software and automate end-to-end workflows.</p>';
	$description .= '<p>At AiBridze, we help organizations build intelligent AI agents that automate operations, improve customer experiences, and increase productivity. As a trusted AI Agent Development Company in India, we design custom AI agents for your business goals. We build enterprise AI assistants, multi-agent systems, and autonomous AI solutions.</p>';
	$description .= '<p>Whether you want to automate customer support. Or build AI-powered employees. You can also create internal productivity tools. You can improve workflows too. Our AI engineers can help. They turn bold ideas into scalable AI solutions.</p>';
	$values = array(
		'intro_eyebrow'     => 'Custom AI Agent Development',
		'intro_title'       => 'Build Intelligent AI Agents That Think, Act, and Deliver Business Results',
		'intro_description' => $description,
		'intro_image'       => get_theme_file_uri( '/assets/images/services/ai-agent-intro.png' ),
	);
	foreach ( $values as $field => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $field ), $value );
	update_option( 'aibridze_ai_agent_intro_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_intro', 41 );

/** Populate the approved AI Agent benefits cards through the repeater fields. */
function aibridze_seed_ai_agent_service_benefits(): void {
	if ( get_option( 'aibridze_ai_agent_benefits_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_eyebrow' ), 'Why AiBridze?' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_title' ), 'Why Choose AiBridze for AI Agent Development?' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_description' ), 'Building successful AI agents requires far more than integrating an LLM API. It demands expertise in AI architecture, software engineering, enterprise systems, security, workflow orchestration, and responsible AI development. At AiBridze, we combine AI strategy, custom software development, machine learning expertise, and cloud engineering to deliver production-ready AI agents that solve real business challenges.' );
	$cards = array(
		array( 'title' => 'Custom AI Agent Development', 'description' => 'Every AI agent is designed around your workflows, data sources, users, and operational requirements.', 'image' => 'benefit-custom.png' ),
		array( 'title' => 'Enterprise-Ready Architecture', 'description' => 'Our AI agents are secure, scalable, maintainable, and designed for production environments.', 'image' => 'benefit-enterprise.png' ),
		array( 'title' => 'End-To-End Development', 'description' => 'From AI consulting and prototyping to deployment and continuous optimization, we support the complete AI lifecycle.', 'image' => 'benefit-end-to-end.png' ),
		array( 'title' => 'Responsible AI Development', 'description' => 'Security, transparency, privacy, governance, and human oversight are embedded into every AI solution we build.', 'image' => 'benefit-responsible.png' ),
		array( 'title' => 'Continuous AI Innovation', 'description' => 'We stay up to date with AI advances. We help clients use the latest models, frameworks, and tools to stay competitive.', 'image' => 'benefit-innovation.png' ),
	);
	$rows = array();
	foreach ( $cards as $card ) $rows[] = array( 'group' => '', 'title' => $card['title'], 'description' => $card['description'], 'image' => get_theme_file_uri( '/assets/images/services/' . $card['image'] ), 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), $rows );
	update_option( 'aibridze_ai_agent_benefits_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_benefits', 42 );

/** Apply the approved transparent benefit icons without hard-coding them in templates. */
function aibridze_update_ai_agent_benefit_icons(): void {
	if ( get_option( 'aibridze_ai_agent_benefit_icons_v2' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$rows = get_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), true );
	if ( ! is_array( $rows ) ) return;
	$icons = array(
		'Enterprise-Ready Architecture' => 'benefit-enterprise-v2.png',
		'End-To-End Development'        => 'benefit-end-to-end-v2.png',
		'Responsible AI Development'    => 'benefit-responsible-v2.png',
		'Continuous AI Innovation'      => 'benefit-innovation-v2.png',
	);
	foreach ( $rows as &$row ) {
		if ( isset( $icons[ $row['title'] ] ) ) $row['image'] = get_theme_file_uri( '/assets/images/services/' . $icons[ $row['title'] ] );
	}
	unset( $row );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), $rows );
	update_option( 'aibridze_ai_agent_benefit_icons_v2', 1, false );
}
add_action( 'init', 'aibridze_update_ai_agent_benefit_icons', 43 );

/** Populate the AI Agent technology matrix through the editable Technology stack repeater. */
function aibridze_seed_ai_agent_service_technology(): void {
	if ( get_option( 'aibridze_ai_agent_technology_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_eyebrow' ), 'Trusted Technologies' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_title' ), 'Enterprise AI Technologies Powering Intelligent Agents' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_description' ), 'We build AI agents using modern AI frameworks and enterprise-grade technologies.' );
	$groups = array(
		'Foundation Models (LLMs)' => array( 'OpenAI' => 'openai.png', 'Claude' => 'claude.png', 'Gemini' => 'gemini.png', 'Llama' => 'llama.png', 'Grok' => 'grok.png', 'DeepSeek' => 'deepseek.png', 'Kimi K3' => 'kimi.png', 'Mistral' => 'mistral.png' ),
		'AI Frameworks' => array( 'LangGraph' => 'langgraph.png', 'LangChain' => 'langchain.png', 'CrewAI' => 'crewai.png', 'LlamaIndex' => 'llamaindex.png', 'Microsoft Agent Framework' => 'microsoft-agent.png' ),
		'Vector Databases' => array( 'Pinecone' => 'pinecone.png', 'pgvector' => 'pgvector.png', 'Weaviate' => 'weaviate.png', 'Qdrant' => 'qdrant.png', 'ChromaDB' => 'chromadb.png', 'Milvus' => 'milvus.png' ),
		'Cloud Platforms' => array( 'AWS' => 'aws.png', 'Microsoft Azure' => 'azure.png', 'Google Cloud' => 'google-cloud.png' ),
		'Languages' => array( 'Python' => 'python.png', 'TypeScript' => 'typescript.png', 'JavaScript' => 'javascript.png', 'Go' => 'go.png' ),
		'Frameworks & Runtimes' => array( 'Python' => 'python.png', 'React' => 'react.png', 'Next.js' => 'nextjs.png', 'Node.js' => 'nodejs.png', 'Django' => 'django.png', 'FastAPI' => 'fastapi.png' ),
		'Agent Protocols & LLMOps' => array( 'LangSmith' => 'langsmith.png', 'Langfuse' => 'langfuse.png', 'MCP' => 'mcp.png' ),
	);
	$rows = array();
	foreach ( $groups as $group => $items ) foreach ( $items as $title => $icon ) $rows[] = array( 'group' => $group, 'title' => $title, 'description' => '', 'image' => get_theme_file_uri( '/assets/images/services/technology/' . $icon ), 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology' ), $rows );
	update_option( 'aibridze_ai_agent_technology_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_technology', 44 );

/** Populate the approved responsive AI Agent conversion banner. */
function aibridze_seed_ai_agent_service_cta(): void {
	if ( get_option( 'aibridze_ai_agent_cta_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$values = array(
		'cta_variant'     => 'agent-banner',
		'cta_position'    => 'before_expertise',
		'cta_title'       => 'Ready to Build an Intelligent AI Agent?',
		'cta_description' => "Whether you're planning an AI-powered employee, autonomous workflow assistant, enterprise knowledge agent, or customer-facing AI solution, our experts are ready to help.",
		'cta_image'       => get_theme_file_uri( '/assets/images/services/ai-agent-cta.png' ),
		'cta_label'       => 'Talk to AI Experts',
		'cta_url'         => aibridze_page_url( 'contact-us', '/contact-us/' ),
	);
	foreach ( $values as $field => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $field ), $value );
	update_option( 'aibridze_ai_agent_cta_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_cta', 45 );

/** Populate the approved AI Agent expertise tabs through editable repeater fields. */
function aibridze_seed_ai_agent_service_expertise(): void {
	if ( get_option( 'aibridze_ai_agent_expertise_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_eyebrow' ), 'AI Agent Development Expertise' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_title' ), 'End-to-End AI Agent Development Services for Modern Businesses' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_description' ), 'At AiBridze, we design, develop, and deploy intelligent AI agents that automate business processes, enhance customer experiences, and streamline enterprise operations. As a trusted AI Agent Development Company in India, we build AI solutions that integrate seamlessly with your existing applications, data sources, and business workflows.' );
	$items = array(
		array( 'Custom AI Agent Development', '<p>Build intelligent AI agents tailored to your business goals, workflows, and operational requirements.</p><p>Unlike generic AI assistants, our custom AI agents understand your business context, connect with enterprise systems, and perform complex tasks autonomously while remaining aligned with your business objectives.</p><p>Ideal for:</p><ul><li>Enterprise Automation</li><li>Customer Support</li><li>Knowledge Management</li><li>Sales Operations</li><li>HR Automation</li><li>Internal Productivity</li></ul>' ),
		array( 'Enterprise AI Agent Development', '<p>Build enterprise-grade AI agents designed to automate complex business operations and seamlessly integrate with your existing technology ecosystem. Our scalable AI solutions are engineered for performance, security, and enterprise-wide adoption.</p><p>From intelligent process automation to enterprise knowledge management, we develop AI agents that enhance productivity, improve decision-making, and streamline mission-critical workflows across departments.</p><p>Ideal for:</p><ul><li>Enterprise Workflow Automation</li><li>Business Process Optimization</li><li>Knowledge Management Systems</li><li>Internal AI Assistants</li><li>Cross-Department Automation</li></ul>' ),
		array( 'Multi-Agent System Development', '<p>Complex business problems often require multiple AI agents working together.</p><p>We develop collaborative multi-agent systems where specialized AI agents communicate, share information, divide responsibilities, and complete sophisticated workflows across departments.</p><p>Ideal for:</p><ul><li>Supply Chain Automation</li><li>Financial Analysis</li><li>Project Management</li><li>Healthcare Operations</li><li>Customer Service</li><li>Business Intelligence</li></ul>' ),
		array( 'AI Workflow Automation', '<p>Transform repetitive business processes with intelligent AI workflow automation solutions. We build AI agents that understand context, make decisions, and execute multi-step tasks across enterprise applications and business systems.</p><p>Our AI-powered automation solutions help organizations improve operational efficiency, reduce manual effort, and accelerate digital transformation initiatives.</p><p>Ideal for:</p><ul><li>Process Automation</li><li>Customer Service Operations</li><li>HR &amp; Finance Workflows</li><li>Reporting &amp; Analytics</li><li>Operational Efficiency</li></ul>' ),
		array( 'AI Copilot Development', '<p>Empower your workforce with intelligent AI copilots designed to enhance productivity, simplify daily operations, and provide real-time business assistance. Our custom AI copilots seamlessly integrate with your tools, data sources, and enterprise platforms.</p><p>From document analysis and knowledge retrieval to reporting and decision support, AI copilots help teams work faster and make smarter business decisions.</p><p>Ideal for:</p><ul><li>Employee Productivity</li><li>Knowledge Assistants</li><li>Sales &amp; Operations Support</li><li>Document Intelligence</li><li>Decision Support Systems</li></ul>' ),
		array( 'AI Agent Integration Services', '<p>Maximize the value of your existing software investments with seamless AI agent integrations. Our team specializes in integrating custom AI agents with CRMs, ERPs, APIs, databases, cloud platforms, and enterprise applications.</p><p>We design scalable AI solutions that fit naturally into your existing workflows while improving productivity, customer experiences, and operational efficiency without disrupting business operations.</p><p>Ideal for:</p><ul><li>CRM &amp; ERP Integrations</li><li>SaaS Platform Integrations</li><li>API &amp; Database Connectivity</li><li>Legacy System Modernization</li><li>Cloud Integrations</li></ul>' ),
		array( 'AI Agent Consulting', '<p>Successfully implementing AI begins with the right strategy. Our AI consulting experts help businesses identify high-impact use cases, evaluate technical feasibility, and create scalable AI implementation roadmaps tailored to their objectives.</p><p>From AI strategy and architecture planning to technology selection and deployment guidance, we help organizations make confident and informed AI investments.</p><p>Ideal for:</p><ul><li>AI Strategy Consulting</li><li>Proof of Concept Development</li><li>AI Readiness Assessment</li><li>Solution Architecture Planning</li><li>Digital Transformation Initiatives</li></ul>' ),
	);
	$rows = array();
	foreach ( $items as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise' ), $rows );
	update_option( 'aibridze_ai_agent_expertise_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_expertise', 46 );

/** Populate the approved AI Agent solutions carousel through editable fields. */
function aibridze_seed_ai_agent_service_solutions(): void {
	if ( get_option( 'aibridze_ai_agent_solutions_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_eyebrow' ), 'AI Agent Solutions' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_title' ), 'Intelligent AI Agents Designed for Every Business Need' );
	delete_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_description' ) );
	$base = get_theme_file_uri( '/assets/images/services/' );
	$items = array(
		array( 'Autonomous AI Agents', 'Intelligent AI agents that can reason, make decisions, and execute tasks with minimal human intervention. Ideal for business automation, workflow orchestration, and enterprise operations that require end-to-end task execution.', 'solution-atom.png' ),
		array( 'Conversational AI Agents', 'Build context-aware AI agents that understand natural language, answer questions, and deliver personalized experiences across customer support, employee assistance, and enterprise knowledge systems.', 'solution-conversation.png' ),
		array( 'Multi-Agent Systems', 'Deploy multiple AI agents that collaborate to solve complex business problems. Perfect for large-scale workflow automation, intelligent task coordination, and enterprise AI applications.', 'solution-multi-agent.png' ),
		array( 'AI Copilot Agents', 'Empower teams with intelligent AI copilots that assist with research, reporting, document analysis, and productivity tasks. Designed to improve decision-making and operational efficiency across departments.', 'solution-sparkle.png' ),
		array( 'Workflow Automation Agents', 'Automate repetitive and knowledge-intensive business processes using AI-powered workflow agents that integrate seamlessly with your existing software and enterprise systems.', 'solution-conversation.png' ),
		array( 'Enterprise Knowledge Agents', 'Connect AI agents with your business data using RAG and enterprise integrations to deliver accurate, context-aware responses across documents, databases, and internal knowledge bases.', 'solution-multi-agent.png' ),
		array( 'AI Research Agents', 'Accelerate research and analysis with AI agents that gather information, summarize findings, compare sources, and generate actionable insights for faster business decisions.', 'solution-sparkle.png' ),
		array( 'Customer Support Agents', 'Deliver intelligent 24/7 customer experiences with AI agents that automate support workflows, resolve common queries, and seamlessly escalate complex issues when needed.', 'solution-atom.png' ),
	);
	$rows = array();
	foreach ( $items as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), $rows );
	update_option( 'aibridze_ai_agent_solutions_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_solutions', 47 );

/** One-time placement migration for the AI Agent conversion banner. */
function aibridze_seed_ai_agent_cta_position(): void {
	if ( get_option( 'aibridze_ai_agent_cta_position_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	update_post_meta( $service->ID, aibridze_service_meta_key( 'cta_position' ), 'before_expertise' );
	update_option( 'aibridze_ai_agent_cta_position_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_cta_position', 48 );

/** Populate the approved AI Agent capabilities through editable fields. */
function aibridze_seed_ai_agent_service_capabilities(): void {
	if ( get_option( 'aibridze_ai_agent_capabilities_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_eyebrow' ), 'AI Agent Capabilities' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_title' ), 'Intelligent Capabilities That Power Next-Generation AI Agents' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_description' ), 'Our AI agents are built with advanced capabilities that enable them to reason, plan, act, and continuously improve across business workflows.' );
	$items = array(
		array( 'Natural Language Understanding', 'Understand user intent through human-like conversations.' ),
		array( 'Multi-Step Reasoning', 'Break down complex objectives into logical, actionable steps to solve sophisticated problems.' ),
		array( 'Autonomous Decision-Making', 'Analyze context, evaluate options, and perform appropriate actions while following predefined business rules and human oversight.' ),
		array( 'Memory & Context Awareness', 'Maintain conversational context and leverage historical interactions to deliver personalized, relevant experiences.' ),
		array( 'Enterprise Knowledge Retrieval', 'Access structured and unstructured enterprise data using Retrieval-Augmented Generation (RAG) for accurate, context-aware responses.' ),
		array( 'Tool Calling & API Integration', 'Interact with external systems, applications, APIs, databases, CRMs, ERPs, calendars, and cloud services to execute tasks automatically.' ),
		array( 'Workflow Orchestration', 'Coordinate multiple tasks, systems, and AI agents to automate end-to-end business processes efficiently.' ),
		array( 'Human-in-the-Loop Collaboration', 'Escalate complex scenarios to human experts whenever confidence thresholds or business rules require intervention.' ),
		array( 'Continuous Learning & Optimization', 'Monitor performance, gather feedback, and refine AI agent behavior to improve accuracy, efficiency, and user satisfaction over time.' ),
	);
	$rows = array();
	foreach ( $items as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities' ), $rows );
	update_option( 'aibridze_ai_agent_capabilities_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_capabilities', 49 );

/** Populate the approved AI Agent development process through editable fields. */
function aibridze_seed_ai_agent_service_process(): void {
	if ( get_option( 'aibridze_ai_agent_process_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process_eyebrow' ), 'AI Agent Development Process' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process_title' ), 'From AI Strategy to Production-Ready AI Agents' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process_description' ), "Building an AI agent isn't just about connecting an LLM to an application. It requires strategic planning, robust architecture, enterprise integration, rigorous evaluation, and continuous optimization. At AiBridze, we follow a proven AI agent development framework that ensures every solution is secure, scalable, and aligned with your business goals." );
	$items = array(
		array( 'Discovery & AI Strategy', 'We analyze your business goals, workflows, existing systems, and AI opportunities to define a clear roadmap for successful AI agent implementation.' ),
		array( 'Solution Architecture & Planning', 'Our experts design the optimal AI agent architecture, including model selection, memory systems, enterprise integrations, security controls, and workflow orchestration tailored to your requirements.' ),
		array( 'AI Agent Development & Integration', 'We build custom AI agents capable of reasoning, planning, and autonomous task execution while seamlessly integrating them with your applications, APIs, databases, and enterprise systems.' ),
		array( 'Testing, Evaluation & Security', 'We rigorously evaluate AI performance, response quality, security, business logic, and edge cases while implementing AI guardrails and responsible AI practices for reliable outcomes.' ),
		array( 'Deployment & Optimization', 'After deployment, we continuously monitor, optimize, and enhance your AI agents to improve performance, scalability, and business impact as your needs evolve.' ),
	);
	$rows = array();
	foreach ( $items as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process' ), $rows );
	update_option( 'aibridze_ai_agent_process_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_process', 50 );

/** Populate the AI Agent industries section through editable service fields. */
function aibridze_seed_ai_agent_service_industries(): void {
	if ( get_option( 'aibridze_ai_agent_industries_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_eyebrow' ), 'Industries We Serve' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_title' ), 'AI Agent Development Solutions Across Industries' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_description' ), 'Every industry has unique workflows, regulations, and operational challenges. We develop custom AI agents tailored to specific business environments and use cases.' );

	$base = get_theme_file_uri( '/assets/images/services/industries/' );
	$items = array(
		array( 'Healthcare', 'Improve patient engagement, automate administrative tasks, and streamline healthcare workflows with intelligent AI assistants.', 'healthcare.png' ),
		array( 'Banking & Finance', 'Automate document processing, strengthen compliance, and support financial operations with secure AI agents.', 'banking-finance.png' ),
		array( 'Real Estate', 'Build AI-powered property assistants, simplify documentation, automate lead management, and improve customer engagement.', 'real-estate.png' ),
		array( 'Retail & E-Commerce', 'Deliver personalized shopping experiences, automate customer support, optimize inventory, and enhance product discovery.', 'retail-ecommerce.png' ),
		array( 'Education', 'Create AI learning assistants, automate administrative workflows, support educators, and personalize student experiences.', 'education.png' ),
		array( 'Manufacturing', 'Improve production planning, predictive maintenance, quality control, and operational efficiency using intelligent AI agents.', 'manufacturing.png' ),
		array( 'Logistics & Supply Chain', 'Automate shipment tracking, optimize inventory management, improve demand forecasting, and streamline logistics operations.', 'logistics-supply-chain.png' ),
		array( 'SaaS & Technology', 'Enhance product experiences with AI copilots, customer success assistants, workflow automation, and intelligent knowledge systems.', 'saas-technology.png' ),
	);
	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries' ), $rows );
	update_option( 'aibridze_ai_agent_industries_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_industries', 51 );

/** Populate the editable Responsible AI trust card for the AI Agent service. */
function aibridze_seed_ai_agent_trust_principles(): void {
	if ( get_option( 'aibridze_ai_agent_trust_principles_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$base = get_theme_file_uri( '/assets/images/services/responsible-ai/' );
	$values = array(
		'trust_title' => 'Building AI Agents You Can Trust',
		'trust_description' => 'At AiBridze, we design AI agents with a strong focus on privacy, transparency, security, fairness, and human oversight. Every solution is developed with governance, compliance, and long-term reliability in mind.',
		'trust_image' => $base . 'trust-card.png',
		'trust_principles_label' => 'Our Responsible AI principles include:',
		'trust_cta_label' => 'Build With Us',
		'trust_cta_url' => '#consultation',
	);
	foreach ( $values as $field => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $field ), $value );

	$items = array(
		array( 'Privacy-first development', 'lock-key.png' ),
		array( 'Secure data handling', 'hard-drives.png' ),
		array( 'Human-in-the-loop workflows', 'user-switch.png' ),
		array( 'Continuous monitoring', 'monitor-arrow-up.png' ),
		array( 'AI safety guardrails', 'shield-check.png' ),
		array( 'Compliance-ready architecture', 'flag-banner.png' ),
	);
	$rows = array();
	foreach ( $items as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'trust_principles' ), $rows );
	update_option( 'aibridze_ai_agent_trust_principles_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_trust_principles', 52 );

/** Populate the approved AI Agent FAQs through the editable service FAQ repeater. */
function aibridze_seed_ai_agent_service_faqs(): void {
	if ( get_option( 'aibridze_ai_agent_faqs_v1' ) ) return;
	$service = get_page_by_path( 'ai-agent-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs_eyebrow' ), 'FAQs' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs_title' ), 'Have questions?Check out the FAQs' );

	$items = array(
		array( 'Why should I choose AiBridze for AI Agent Development Services?', 'AiBridze combines AI expertise, custom software development, and enterprise-grade engineering to build intelligent AI agents tailored to your business goals. From custom AI agents and multi-agent systems to enterprise AI automation and seamless integrations, we deliver scalable AI solutions designed for real-world business impact.' ),
		array( 'Can you build custom AI agents for my industry and business requirements?', 'Yes. We develop custom AI agents tailored to specific industries, workflows, and operational requirements. Whether you need customer support automation, enterprise knowledge assistants, workflow automation, or AI-powered productivity tools, our solutions are designed around your business objectives.' ),
		array( 'Can AiBridze integrate AI agents with our existing software and enterprise systems?', 'Yes. Our AI engineers specialize in building and integrating custom AI agents that work seamlessly with your existing technology ecosystem. Whether you use CRMs, ERPs, SaaS platforms, APIs, databases, or cloud services, we design AI solutions that securely connect with your business systems to automate workflows, improve productivity, and deliver measurable business value.' ),
		array( 'How do you ensure the security and responsible use of AI agents?', 'We follow responsible AI development practices that prioritize security, privacy, transparency, and human oversight. Our solutions include secure integrations, AI guardrails, role-based access controls, and continuous performance monitoring to ensure reliable and scalable AI systems.' ),
		array( 'What technologies do you use for AI Agent Development?', 'Our AI Agent Development Services leverage leading technologies such as Large Language Models (LLMs), Retrieval-Augmented Generation (RAG), LangGraph, LangChain, CrewAI, vector databases, and cloud platforms including AWS, Microsoft Azure, and Google Cloud to build production-ready AI solutions.' ),
		array( 'Can you develop multi-agent systems for complex business workflows?', 'Yes. We design and develop intelligent multi-agent systems capable of collaborating across tasks, sharing context, and automating complex business processes. Multi-agent architectures are ideal for enterprise automation, research workflows, operational intelligence, and large-scale AI applications.' ),
		array( 'How long does it take to develop a custom AI agent?', 'The development timeline depends on your business requirements, integrations, and the complexity of the solution. Simple AI agent implementations may take a few weeks, while enterprise-grade AI solutions with advanced workflows and integrations can require several months. We provide a tailored project roadmap after understanding your requirements.' ),
		array( 'Do you provide AI consulting and support after deployment?', 'Yes. Our engagement extends beyond development. We provide AI consulting, deployment assistance, performance optimization, and ongoing support to help businesses continuously improve and scale their AI solutions as requirements evolve.' ),
	);
	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs' ), $rows );
	update_option( 'aibridze_ai_agent_faqs_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_agent_service_faqs', 53 );

/** Populate the Generative AI service hero through editable service fields. */
function aibridze_seed_generative_ai_service_hero(): void {
	if ( get_option( 'aibridze_generative_ai_service_hero_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$image = get_theme_file_uri( '/assets/images/services/generative-ai-hero.png' );
	$values = array(
		'hero_title'        => 'Generative AI Development Services',
		'hero_description'  => 'Build intelligent Generative AI solutions that create personalized experiences, accelerate innovation, and enhance business productivity. At AiBridze, we develop custom Generative AI applications, AI-powered software, intelligent chatbots, and enterprise AI solutions tailored to your business goals. From Large Language Model (LLM) development and AI integrations to scalable Generative AI software development, we engineer production-ready solutions designed for real-world business impact.',
		'hero_image'        => $image,
		'hero_mobile_image' => $image,
		'hero_cta_label'    => 'Talk to AI Experts',
		'hero_cta_url'      => aibridze_page_url( 'contact-us', '/contact-us/' ),
	);

	foreach ( $values as $field => $value ) {
		update_post_meta( $service->ID, aibridze_service_meta_key( $field ), $value );
	}
	delete_post_meta( $service->ID, aibridze_service_meta_key( 'hero_eyebrow' ) );
	update_option( 'aibridze_generative_ai_service_hero_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_hero', 54 );

/** Populate the Generative AI introduction through the shared editable service fields. */
function aibridze_seed_generative_ai_service_intro(): void {
	if ( get_option( 'aibridze_generative_ai_service_intro_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$values = array(
		'intro_eyebrow'     => 'Generative AI Innovation',
		'intro_title'       => 'Unlock New Possibilities with Generative AI Development',
		'intro_description' => 'Generative AI is transforming how businesses build digital products, create intelligent experiences, and deliver value at scale. From AI-powered applications and enterprise knowledge systems to intelligent content generation and personalized customer experiences, Generative AI is enabling organizations to innovate faster than ever before. At AiBridze, we develop custom Generative AI solutions that seamlessly integrate with your business ecosystem while remaining secure, scalable, and future-ready.',
		'intro_image'       => get_theme_file_uri( '/assets/images/services/generative-ai-intro.png' ),
	);

	foreach ( $values as $field => $value ) {
		update_post_meta( $service->ID, aibridze_service_meta_key( $field ), $value );
	}
	update_post_meta( $service->ID, '_aibridze_service_layout', 'generative' );
	update_option( 'aibridze_generative_ai_service_intro_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_intro', 55 );

/** Populate the Generative AI business insights through editable Service fields. */
function aibridze_seed_generative_ai_service_benefits(): void {
	if ( get_option( 'aibridze_generative_ai_service_benefits_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_eyebrow' ), 'Generative AI Business Insights' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_title' ), 'Why Businesses Are Investing in Generative AI Development' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_description' ), 'Businesses worldwide are adopting Generative AI to create intelligent products, enhance customer experiences, improve productivity, and accelerate digital innovation. From personalized recommendations and intelligent automation to enterprise knowledge systems and AI-powered applications, Generative AI is unlocking new opportunities for sustainable business growth.' );

	$base = get_theme_file_uri( '/assets/images/services/' );
	$items = array(
		array( 'Accelerate Business Innovation', 'Bring ideas to life faster with intelligent Generative AI applications designed to enhance digital experiences and support continuous innovation.', 'generative-benefit-innovation.png' ),
		array( 'Deliver Personalized Experiences', 'Create highly personalized customer and employee experiences through intelligent recommendations, contextual interactions, and adaptive AI capabilities.', 'generative-benefit-personalized.png' ),
		array( 'Enhance Enterprise Productivity', 'Improve productivity by automating information processing, knowledge retrieval, content generation, and repetitive business workflows.', 'generative-benefit-productivity.png' ),
		array( 'Scale Intelligent Applications', 'Develop production-ready Generative AI solutions that seamlessly scale across users, departments, and enterprise ecosystems.', 'generative-benefit-scale.png' ),
		array( 'Future-Ready AI Solutions', 'Build secure, scalable, and adaptable AI applications designed to evolve alongside your business and technology requirements.', 'generative-benefit-future.png' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), $rows );
	update_option( 'aibridze_generative_ai_service_benefits_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_benefits', 56 );

/** Populate the Generative AI tabbed technology stack through editable Service fields. */
function aibridze_seed_generative_ai_service_technology(): void {
	if ( get_option( 'aibridze_generative_ai_service_technology_v4' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_eyebrow' ), 'Generative AI Technology Stack' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_title' ), 'Enterprise Technologies Powering Generative AI Solutions' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_description' ), 'We leverage industry-leading AI models, modern development frameworks, vector databases, and cloud technologies to build intelligent, scalable, and enterprise-grade Generative AI applications.' );

	$base = get_theme_file_uri( '/assets/images/services/technology/generative/' );
	$groups = array(
		'Deep Learning (DL) Frameworks' => array(
			array( 'TensorFlow', 'deep-tensorflow.png' ),
			array( 'PyTorch', 'deep-pytorch.png' ),
			array( 'Keras', 'deep-keras.png' ),
			array( 'ONNX', 'deep-onnx.png' ),
			array( 'JAX', 'deep-jax.png' ),
			array( 'Deeplearning4j', 'deep-dl4j.png' ),
		),
		'Modules/Toolkits' => array(
			array( 'NVIDIA NeMo', 'module-nvidia-nemo.png' ),
			array( 'PyTorch Lightning', 'module-pytorch-lightning.png' ),
			array( 'Hugging Face Transformers', 'module-hugging-face.png' ),
			array( 'Ollama', 'module-ollama.png' ),
			array( 'vLLM', 'module-vllm.png' ),
			array( 'TensorRT-LLM', 'module-tensorrt.png' ),
		),
		'Generative AI Models' => array(
			array( 'Transformer Models (LLMs)', 'model-transformer.png' ),
			array( 'Diffusion Models', 'model-diffusion.png' ),
			array( 'Mixture-of-Experts (MoE)', 'model-moe.png' ),
			array( 'Multimodal Models', 'model-multimodal.png' ),
			array( 'Vision-Language Models (VLMs)', 'model-vlm.png' ),
			array( 'Text-to-Image / Text-to-Video Models', 'tti-tov-models.png' ),
		),
		'Libraries' => array(
			array( 'NumPy', 'lib-numpy.png' ),
			array( 'Pandas', 'lib-pandas.png' ),
			array( 'Scikit Learn', 'lib-scikit.png' ),
			array( 'openCV', 'lib-opencv.png' ),
			array( 'spaCy', 'lib-spacy.png' ),
			array( 'XGBoost', 'lib-xgboost.png' ),
			array( 'Polars', 'lib-polars.png' ),
			array( 'Matplotlib', 'lib-matplotlib.png' ),
			array( 'Plotly', 'lib-plotly.png' ),
			array( 'LightGBM', 'lib-lightgbm.png' ),
			array( 'Statsmodels', 'lib-statsmodels.png' ),
		),
		'Neural Networks' => array(
			array( 'RNN', 'nn-rnn.png' ),
			array( 'CNN', 'nn-cnn.png' ),
			array( 'GAN', 'nn-gan.png' ),
			array( 'VAE', 'nn-vae.png' ),
			array( 'Transformers', 'nn-transformers.png' ),
			array( 'LSTM Networks', 'nn-lstm.png' ),
			array( 'Autoencoders', 'autoencoders.svg' ),
			array( 'RBM', 'rbm.svg' ),
			array( 'DBN', 'dbn.svg' ),
		),
		'Image Classification Models' => array(
			array( 'Vision Transformer (ViT)', 'class-vit.png' ),
			array( 'YOLO', 'class-yolo.png' ),
			array( 'CLIP', 'class-clip.png' ),
			array( 'Segment Anything', 'class-segment.png' ),
			array( 'ResNet', 'class-resnet.png' ),
			array( 'Xception', 'class-xception.png' ),
			array( 'EfficientNet', 'class-efficientnet.png' ),
			array( 'SqueezeNet', 'class-squeezenet.png' ),
		),
	);

	$rows = array();
	foreach ( $groups as $group => $items ) {
		foreach ( $items as $item ) {
			$rows[] = array(
				'group'       => $group,
				'title'       => $item[0],
				'description' => '',
				'image'       => $base . $item[1],
				'link_label'  => '',
				'link_url'    => '',
			);
		}
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology' ), $rows );
	update_option( 'aibridze_generative_ai_service_technology_v4', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_technology', 57 );

/** Populate the Generative AI market banner through editable Service fields. */
function aibridze_seed_generative_ai_service_cta(): void {
	if ( get_option( 'aibridze_generative_ai_service_cta_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$values = array(
		'cta_position'    => 'after_technology',
		'cta_variant'     => 'genai-market',
		'cta_title'       => 'Generative AI Is Projected To Become A $1.3T Market By 2032. Build Smarter Products, Automate Workflows, And Accelerate Growth With Our Gen AI Development Services.',
		'cta_highlight'   => '$1.3T Market By 2032.',
		'cta_description' => '',
		'cta_image'       => get_theme_file_uri( '/assets/images/services/generative-ai-market-cta.png' ),
		'cta_label'       => "Let's Build Your AI Solution",
		'cta_url'         => '#consultation',
	);
	foreach ( $values as $key => $value ) {
		update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	}
	update_option( 'aibridze_generative_ai_service_cta_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_cta', 58 );

/** Populate the Generative AI engineering expertise through editable Service fields. */
function aibridze_seed_generative_ai_service_expertise(): void {
	if ( get_option( 'aibridze_generative_ai_service_expertise_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_eyebrow' ), 'Generative AI Engineering Expertise' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_title' ), 'Enterprise Generative AI Solutions Tailored to Your Business' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_description' ), 'Our expertise spans custom Generative AI applications, enterprise AI integrations, Large Language Model development, and intelligent software solutions designed to solve complex business challenges. We engineer scalable and production-ready AI solutions tailored to modern business requirements.' );

	$items = array(
		array( 'Custom Generative AI Development', '<p>Develop custom Generative AI solutions tailored to your business objectives and operational requirements. We build intelligent AI applications that create personalized experiences, automate complex workflows, and solve real-world business challenges while delivering scalability, security, and measurable business value.</p><p>Ideal for:</p><ul><li>Enterprise AI Applications</li><li>AI Product Development</li><li>Intelligent Business Solutions</li><li>Personalized Digital Experiences</li><li>Custom AI Solutions</li><li>Digital Transformation Initiatives</li></ul>' ),
		array( 'Generative AI Software Development', '<p>Transform traditional software into intelligent applications powered by Generative AI. From enterprise software and SaaS platforms to web and mobile applications, we develop AI-powered solutions designed to enhance productivity, improve user experiences, and accelerate innovation.</p><p>Ideal for:</p><ul><li>AI-Powered SaaS Platforms</li><li>Enterprise Software Solutions</li><li>Intelligent Web Applications</li><li>AI Mobile Applications</li><li>Custom Software Development</li><li>AI Product Development</li></ul>' ),
		array( 'Generative AI Chatbot Development', '<p>Build intelligent Generative AI chatbots that understand context, deliver accurate responses, and create natural conversational experiences across customer and employee touchpoints.</p><p>Ideal for:</p><ul><li>AI Customer Support</li><li>AI Virtual Assistants</li><li>Enterprise Knowledge Chatbots</li><li>Intelligent Helpdesk Solutions</li><li>Employee Support Systems</li><li>Conversational AI Experiences</li></ul>' ),
		array( 'LLM Development Services', '<p>Harness the capabilities of Large Language Models to develop intelligent applications that understand, generate, and process natural language at scale. We build secure and scalable LLM-powered solutions designed for enterprise knowledge systems, intelligent assistants, and AI-powered digital experiences.</p><p>Ideal for:</p><ul><li>Large Language Model Applications</li><li>Enterprise Knowledge Systems</li><li>Intelligent Search Solutions</li><li>AI Assistants</li><li>Document Intelligence Platforms</li><li>AI-Powered Experiences</li></ul>' ),
		array( 'AI Model Development & Fine-Tuning', '<p>Develop and optimize AI models tailored to your industry, business objectives, and domain-specific requirements. Our model development and fine-tuning services improve performance, contextual understanding, and personalization across enterprise AI applications.</p><p>Ideal for:</p><ul><li>Custom AI Models</li><li>AI Model Optimization</li><li>Personalized AI Experiences</li><li>Domain-Specific AI Applications</li><li>Enterprise AI Solutions</li><li>Intelligent Digital Products</li></ul>' ),
		array( 'Generative AI Integration Services', '<p>Seamlessly integrate Generative AI capabilities across enterprise applications, APIs, databases, cloud platforms, and existing software ecosystems. Our integration services are designed to maximize the value of your technology investments while enhancing operational efficiency and user experiences.</p><p>Ideal for:</p><ul><li>CRM &amp; ERP Integrations</li><li>API Integrations</li><li>Cloud Platforms</li><li>Enterprise Applications</li><li>AI-Enabled Workflows</li><li>Legacy System Modernization</li></ul>' ),
		array( 'Generative AI Strategy & Consulting', '<p>Accelerate your Generative AI journey with strategic consulting services tailored to your business objectives. From identifying high-value use cases and selecting the right technologies to designing scalable implementation roadmaps, we help organizations confidently adopt and scale Generative AI solutions.</p><p>Ideal for:</p><ul><li>AI Strategy Consulting</li><li>AI Readiness Assessments</li><li>Proof of Concept Development</li><li>Technology Selection</li><li>Solution Architecture Planning</li><li>Enterprise AI Adoption</li></ul>' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise' ), $rows );
	update_option( 'aibridze_generative_ai_service_expertise_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_expertise', 59 );

/** Populate the Generative AI business solutions through editable Service fields. */
function aibridze_seed_generative_ai_service_solutions(): void {
	if ( get_option( 'aibridze_generative_ai_service_solutions_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_eyebrow' ), 'Generative AI Business Solutions' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_title' ), 'Transform Business Experiences with Generative AI Solutions' );
	delete_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_description' ) );

	$base = get_theme_file_uri( '/assets/images/services/generative-solutions/' );
	$items = array(
		array( 'Intelligent Customer Experiences', 'Deliver highly personalized and context-aware customer experiences powered by Generative AI. Enhance user engagement through intelligent interactions, adaptive recommendations, and AI-powered capabilities across web, mobile, and enterprise applications.', 'customer.png' ),
		array( 'Intelligent Knowledge Systems', 'Transform enterprise knowledge into intelligent experiences. Enable teams to securely access business information, improve collaboration, and accelerate decision-making with scalable Generative AI solutions designed for modern organizations.', 'knowledge.png' ),
		array( 'Intelligent Content Generation', 'Accelerate content creation across marketing, operations, and business functions with Generative AI solutions designed to deliver consistency, accuracy, and personalization at scale.', 'content.png' ),
		array( 'Enterprise Workflow Optimization', 'Improve operational efficiency by automating information processing, document analysis, and repetitive business workflows with intelligent Generative AI applications built for enterprise environments.', 'workflow.png' ),
		array( 'Personalized AI Experiences', 'Deliver adaptive and personalized digital experiences through contextual interactions, intelligent recommendations, and AI-powered capabilities tailored to individual users and business requirements.', 'personalized.png' ),
		array( 'AI-Powered Product Innovation', 'Build intelligent AI-powered products and applications designed to accelerate innovation, improve user experiences, and create measurable business outcomes across industries.', 'product.png' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), $rows );
	update_option( 'aibridze_generative_ai_service_solutions_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_solutions', 60 );

/** Populate Generative AI capabilities through the editable Service repeater. */
function aibridze_seed_generative_ai_service_capabilities(): void {
	if ( get_option( 'aibridze_generative_ai_service_capabilities_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_eyebrow' ), 'Generative AI Capabilities' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_title' ), 'Enterprise Capabilities Powering Intelligent AI Experiences' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_description' ), 'Our Generative AI solutions are designed to deliver intelligent, scalable, and enterprise-ready capabilities that enhance digital experiences and accelerate business innovation. From contextual understanding and enterprise knowledge retrieval to multimodal intelligence and seamless integrations, we develop AI solutions built for modern business environments.' );

	$items = array(
		array( 'Context-Aware Intelligence', 'Deliver intelligent and personalized experiences through contextual understanding and adaptive AI capabilities.' ),
		array( 'Enterprise Knowledge Intelligence', 'Transform enterprise data into intelligent, searchable, and context-aware knowledge experiences.' ),
		array( 'Personalized AI Experiences', 'Create adaptive and personalized AI experiences tailored to users, preferences, and business needs.' ),
		array( 'Intelligent Information Processing', 'Automate information processing, document analysis, and enterprise workflows with Generative AI.' ),
		array( 'Multimodal AI Capabilities', 'Build AI applications that understand and generate text, images, and digital content.' ),
		array( 'Enterprise AI Integrations', 'Seamlessly integrate Generative AI across enterprise applications, APIs, and cloud platforms.' ),
		array( 'Scalable AI Infrastructure', 'Develop secure and scalable Generative AI solutions built for enterprise growth.' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities' ), $rows );
	update_option( 'aibridze_generative_ai_service_capabilities_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_capabilities', 61 );

/** Populate Generative AI industries through the editable Service repeater. */
function aibridze_seed_generative_ai_service_industries(): void {
	if ( get_option( 'aibridze_generative_ai_service_industries_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_eyebrow' ), 'Generative AI Across Industries' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_title' ), 'Accelerating Innovation Across Modern Industries' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_description' ), 'Organizations across industries are leveraging Generative AI to create intelligent products, personalize digital experiences, and improve operational efficiency. We develop industry-specific Generative AI solutions tailored to unique business challenges and opportunities across modern enterprise environments.' );

	$base = get_theme_file_uri( '/assets/images/services/industries/generative/' );
	$items = array(
		array( 'Healthcare', 'Improve patient experiences, streamline information management, and develop intelligent healthcare applications powered by secure and scalable Generative AI capabilities.', 'healthcare.png' ),
		array( 'Banking & Finance', 'Deliver intelligent financial experiences through AI-powered applications designed to enhance productivity, personalization, and enterprise decision-making.', 'banking.png' ),
		array( 'Real Estate', 'Build intelligent real estate solutions that automate information processing, improve customer engagement, and deliver personalized digital experiences.', 'real-estate.png' ),
		array( 'Retail & E-Commerce', 'Create highly personalized shopping experiences through intelligent recommendations, AI-powered customer interactions, and scalable digital commerce solutions.', 'retail.png' ),
		array( 'Education', 'Transform learning experiences through intelligent educational platforms, AI assistants, and adaptive content generation capabilities designed for modern learners.', 'education.png' ),
		array( 'Manufacturing', 'Enhance efficiency through intelligent document processing, knowledge management systems, and enterprise Generative AI applications tailored to manufacturing environments.', 'manufacturing.png' ),
		array( 'Logistics & Supply Chain', 'Improve visibility, information accessibility, and workflow optimization with intelligent Generative AI solutions designed for modern supply chain ecosystems.', 'logistics.png' ),
		array( 'SaaS & Technology', 'Develop scalable AI-powered products and enterprise applications that enhance user experiences and accelerate innovation across modern technology platforms.', 'saas.png' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries' ), $rows );
	update_option( 'aibridze_generative_ai_service_industries_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_industries', 62 );

/** Populate the Generative AI security and trust card through editable Service fields. */
function aibridze_seed_generative_ai_service_trust(): void {
	if ( get_option( 'aibridze_generative_ai_service_trust_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$base = get_theme_file_uri( '/assets/images/services/responsible-ai/' );
	$values = array(
		'trust_title'            => 'Enterprise-Ready Generative AI Built With Security And Trust',
		'trust_description'      => 'Security, transparency, and responsible AI practices are fundamental to every Generative AI solution we develop, enabling businesses to confidently adopt and scale intelligent AI experiences.',
		'trust_image'            => $base . 'generative-trust.png',
		'trust_principles_label' => '',
		'trust_cta_label'        => 'Build With Us',
		'trust_cta_url'          => '#consultation',
	);
	foreach ( $values as $key => $value ) {
		update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	}

	$items = array(
		array( 'Responsible AI Development', 'shield-check.png' ),
		array( 'Secure Data Handling', 'hard-drives.png' ),
		array( 'Human Oversight', 'user-switch.png' ),
	);
	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'trust_principles' ), $rows );
	update_option( 'aibridze_generative_ai_service_trust_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_trust', 63 );

/** Populate the Generative AI FAQs through the editable Service FAQ repeater. */
function aibridze_seed_generative_ai_service_faqs(): void {
	if ( get_option( 'aibridze_generative_ai_service_faqs_v1' ) ) return;

	$service = get_page_by_path( 'generative-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs_eyebrow' ), 'FAQs' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs_title' ), 'Have questions?Check out the FAQs' );

	$items = array(
		array( 'Can Generative AI solutions be tailored to our business requirements?', 'Absolutely. We develop custom Generative AI solutions tailored to your business objectives, industry requirements, and existing technology ecosystem. From intelligent applications and AI-powered software to enterprise knowledge systems and personalized digital experiences, every solution is designed around your unique business needs.' ),
		array( 'How can Generative AI improve business productivity and operational efficiency?', 'Generative AI can automate information processing, enhance knowledge accessibility, personalize customer experiences, and streamline enterprise workflows. Organizations are increasingly adopting Generative AI to improve productivity, accelerate innovation, and create scalable digital experiences across business functions.' ),
		array( 'Can Generative AI integrate with our existing software and enterprise systems?', 'Yes. Our Generative AI solutions can seamlessly integrate with enterprise applications, APIs, databases, cloud platforms, CRMs, ERPs, and existing business systems. We specialize in developing enterprise-ready AI solutions that enhance your current technology investments while maintaining scalability and security.' ),
		array( 'What types of Generative AI applications can AiBridze develop?', 'Our expertise includes custom Generative AI applications, intelligent chatbots, AI-powered software, enterprise knowledge systems, intelligent document processing solutions, Large Language Model applications, and personalized digital experiences designed for modern businesses.' ),
		array( 'Do you provide Generative AI consulting and implementation services?', 'Yes. We provide end-to-end Generative AI consulting services, including AI strategy, technology selection, solution architecture planning, custom development, enterprise integrations, deployment, and continuous optimization to help businesses successfully adopt and scale AI solutions.' ),
		array( 'How do you ensure security and responsible AI development?', 'Security, privacy, transparency, and human oversight are fundamental to every Generative AI solution we develop. Our responsible AI practices prioritize secure integrations, scalable architectures, and enterprise-ready AI solutions designed for long-term reliability and performance.' ),
		array( 'How long does it take to develop a Generative AI solution?', 'The development timeline depends on the complexity of the solution, required integrations, and business objectives. While smaller implementations may require only a few weeks, enterprise-grade Generative AI applications typically involve multiple development phases to ensure scalability, security, and optimal performance.' ),
		array( 'Why choose AiBridze as your Generative AI Development Company?', 'At AiBridze, we combine Generative AI expertise with modern AI engineering capabilities to develop intelligent, scalable, and enterprise-ready solutions. Our focus on custom development, responsible AI practices, and measurable business outcomes enables organizations to confidently build and scale Generative AI applications tailored to their goals.' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs' ), $rows );
	update_option( 'aibridze_generative_ai_service_faqs_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_generative_ai_service_faqs', 64 );

/** Populate the AI Chatbot hero through editable Service fields. */
function aibridze_seed_ai_chatbot_service_hero(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_hero_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$image = get_theme_file_uri( '/assets/images/services/ai-chatbot-hero.png' );
	$values = array(
		'hero_eyebrow'      => '',
		'hero_title'        => 'AI Chatbot Development Services',
		'hero_description'  => 'Transform customer and employee interactions with intelligent AI chatbot solutions designed for modern businesses. At AiBridze, we develop custom AI chatbots, enterprise virtual assistants, and conversational AI solutions that deliver personalized, context-aware, and human-like interactions across digital channels. From customer support automation and enterprise knowledge assistants to multilingual AI chatbots, we build scalable and production-ready AI solutions tailored to your business goals.',
		'hero_image'        => $image,
		'hero_mobile_image' => $image,
		'hero_cta_label'    => 'Consult Our Chatbot Experts',
		'hero_cta_url'      => '#consultation',
	);
	foreach ( $values as $key => $value ) {
		update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	}
	update_post_meta( $service->ID, '_aibridze_service_layout', 'chatbot' );
	update_option( 'aibridze_ai_chatbot_service_hero_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_hero', 65 );

/** Populate the AI Chatbot introduction through editable Service fields. */
function aibridze_seed_ai_chatbot_service_intro(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_intro_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$values = array(
		'intro_eyebrow'     => 'Conversational AI Solutions',
		'intro_title'       => 'Redefining Intelligent Assistance with AI Chatbots',
		'intro_description' => 'AI chatbots are transforming how businesses engage, assist, and communicate across digital experiences. Powered by modern AI technologies and Large Language Models, intelligent chatbot solutions deliver personalized assistance, automate support experiences, and improve enterprise productivity. From customer service automation and AI virtual assistants to enterprise knowledge chatbots, businesses are leveraging conversational AI to create faster, smarter, and more meaningful interactions.',
		'intro_image'       => get_theme_file_uri( '/assets/images/services/ai-chatbot-intro.png' ),
	);
	foreach ( $values as $key => $value ) {
		update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	}
	update_post_meta( $service->ID, '_aibridze_service_layout', 'chatbot' );
	update_option( 'aibridze_ai_chatbot_service_intro_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_intro', 66 );

/** Populate the AI Chatbot business-benefits cards through the Service repeater. */
function aibridze_seed_ai_chatbot_service_benefits(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_benefits_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_eyebrow' ), 'Conversational AI Business Benefits' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_title' ), 'Why Businesses Are Building Intelligent AI Chatbot Solutions' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits_description' ), 'Modern businesses are leveraging AI chatbot solutions to deliver intelligent assistance, improve customer experiences, and enhance enterprise productivity. Enterprise AI chatbots provide 24/7 support, automate repetitive interactions, and create seamless omnichannel experiences that scale alongside business growth.' );

	$base = get_theme_file_uri( '/assets/images/services/chatbot/' );
	$items = array(
		array( 'Intelligent Customer Assistance', 'Deliver personalized and human-like customer interactions designed to improve engagement and satisfaction across digital channels.', 'benefit-assistance.png' ),
		array( '24/7 Intelligent Support', 'Provide continuous assistance through AI-powered chatbot solutions designed for modern customer expectations and business requirements.', 'benefit-support.svg' ),
		array( 'Enterprise Level Productivity', 'Automate repetitive workflows and improve operational efficiency with intelligent employee and enterprise support solutions.', 'benefit-productivity.png' ),
		array( 'Omnichannel Experiences', 'Deliver seamless conversational experiences across websites, mobile applications, messaging platforms, and enterprise systems.', 'benefit-omnichannel.png' ),
		array( 'Enterprise Knowledge Assistance', 'Enable teams and customers to instantly access business information through intelligent and context-aware AI chatbot experiences.', 'benefit-knowledge.png' ),
	);
	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_benefits_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_benefits', 67 );

/** Populate the grouped AI Chatbot technology stack through the Service repeater. */
function aibridze_seed_ai_chatbot_service_technology(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_technology_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_eyebrow' ), 'Conversational AI Tech Stack' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_title' ), 'Powering Intelligent Conversations with Advanced AI Technologies' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_description' ), 'We leverage industry-leading AI models, conversational AI frameworks, and enterprise technologies to build intelligent chatbot solutions that understand context, deliver personalized interactions, and seamlessly scale across modern business environments.' );

	$base = get_theme_file_uri( '/assets/images/services/chatbot/' );
	$groups = array(
		'AI Models' => array(
			array( 'OpenAI', 'openai.png' ), array( 'Claude', 'claude.png' ), array( 'Gemini', 'gemini.png' ), array( 'Llama', 'llama.png' ),
			array( 'Grok', 'grok.png' ), array( 'DeepSeek', 'deepseek.png' ), array( 'Kimi K3', 'kimi.png' ), array( 'Mistral', 'mistral.png' ),
		),
		'Conversational AI Frameworks' => array(
			array( 'LangGraph', 'langgraph.png' ), array( 'LangChain', 'langchain.png' ), array( 'CrewAI', 'crewai.png' ), array( 'LlamaIndex', 'llamaindex.png' ), array( 'AutoGen', 'autogen.png' ),
		),
		'Vector Databases' => array(
			array( 'Pinecone', 'pinecone.png' ), array( 'pgvector', 'pgvector.png' ), array( 'Weaviate', 'weaviate.png' ), array( 'Qdrant', 'qdrant.png' ), array( 'ChromaDB', 'chromadb.png' ), array( 'Milvus', 'milvus.png' ),
		),
		'Cloud Platforms' => array(
			array( 'AWS', 'aws.png' ), array( 'Microsoft Azure', 'azure.png' ), array( 'Google Cloud', 'google-cloud.png' ),
		),
		'Development Stack' => array(
			array( 'Python', 'python.png' ), array( 'React', 'react.png' ), array( 'Next.js', 'nextjs.png' ), array( 'Node.js', 'nodejs.png' ), array( 'Django', 'django.png' ), array( 'FastAPI', 'fastapi.png' ), array( 'PostgreSQL', 'postgresql.png' ),
		),
	);
	$rows = array();
	foreach ( $groups as $group => $items ) {
		foreach ( $items as $item ) {
			$rows[] = array( 'group' => $group, 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
		}
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_technology_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_technology', 68 );

/** Populate the AI Chatbot conversion banner through editable Service fields. */
function aibridze_seed_ai_chatbot_service_cta(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_cta_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$values = array(
		'cta_position'    => 'after_technology',
		'cta_variant'     => 'chatbot-banner',
		'cta_title'       => 'Build Enterprise AI Chatbots That Scale With Your Business',
		'cta_highlight'   => '',
		'cta_description' => 'Develop intelligent chatbot solutions powered by advanced AI technologies that enhance customer experiences, improve productivity, and deliver seamless conversational experiences at scale.',
		'cta_image'       => get_theme_file_uri( '/assets/images/services/chatbot/chatbot-scale.png' ),
		'cta_label'       => 'Start Your AI Chatbot Project',
		'cta_url'         => '#consultation',
	);
	foreach ( $values as $key => $value ) {
		update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	}
	update_option( 'aibridze_ai_chatbot_service_cta_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_cta', 69 );

/** Populate the AI Chatbot engineering-expertise tabs through the Service repeater. */
function aibridze_seed_ai_chatbot_service_expertise(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_expertise_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_eyebrow' ), 'Conversational AI Engineering Expertise' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_title' ), 'Build Intelligent AI Chatbot Solutions Tailored to Your Business' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise_description' ), 'From enterprise AI chatbots and intelligent virtual assistants to customer support automation and conversational AI experiences, we develop scalable and production-ready AI chatbot solutions designed to enhance customer engagement, improve productivity, and deliver measurable business value.' );

	$items = array(
		array(
			'Custom AI Chatbot',
			'Develop custom AI chatbot solutions tailored to your business objectives, delivering intelligent assistance, personalized interactions, and seamless conversational experiences across digital platforms.',
			array( 'Enterprise AI Chatbots', 'Customer Support Automation', 'Personalized Conversations', 'AI Customer Service Solutions', 'Business Automation', 'Customer Engagement' ),
		),
		array(
			'Enterprise AI Chatbot',
			'Deploy enterprise-ready AI chatbot solutions that automate workflows, improve knowledge accessibility, and enhance customer and employee experiences at scale.',
			array( 'Enterprise Knowledge Systems', 'Employee Support Solutions', 'Workflow Automation', 'Intelligent Helpdesk Solutions', 'Internal Knowledge Assistants', 'Enterprise Applications' ),
		),
		array(
			'Conversational AI',
			'Deliver context-aware conversational AI experiences capable of understanding user intent and providing human-like interactions across business applications.',
			array( 'Conversational AI Solutions', 'Intelligent Interactions', 'Customer Experiences', 'Enterprise Chatbots', 'AI Customer Support', 'Omnichannel Experiences' ),
		),
		array(
			'AI Virtual Assistant',
			'Create intelligent AI virtual assistants designed to provide instant assistance, automate repetitive tasks, and improve productivity across enterprise environments.',
			array( 'AI Virtual Assistants', 'Intelligent Customer Assistance', 'Employee Productivity', 'AI Support Solutions', 'Personalized Experiences', 'Business Automation' ),
		),
		array(
			'Multilingual AI Chatbot',
			'Deliver multilingual AI chatbot experiences that enable businesses to engage global audiences through personalized and context-aware conversations across digital channels.',
			array( 'Global Businesses', 'Multilingual Experiences', 'Customer Support Solutions', 'International Markets', 'Omnichannel Experiences', 'AI Assistants' ),
		),
		array(
			'AI Chatbot Integrations & Consulting',
			'Accelerate your conversational AI journey through seamless enterprise integrations, strategic consulting, and scalable AI adoption strategies.',
			array( 'CRM & ERP Integrations', 'API Integrations', 'AI Strategy Consulting', 'Technology Selection', 'Solution Architecture', 'Enterprise AI Adoption' ),
		),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$list = '<p>' . esc_html( $item[1] ) . '</p><p>Ideal for:</p><ul>';
		foreach ( $item[2] as $ideal ) $list .= '<li>' . esc_html( $ideal ) . '</li>';
		$list .= '</ul>';
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $list, 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_expertise_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_expertise', 70 );

/** Populate the AI Chatbot applications carousel through editable Service fields. */
function aibridze_seed_ai_chatbot_service_solutions(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_solutions_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_eyebrow' ), 'AI Chatbot Applications' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_title' ), 'Types of Intelligent AI Chatbot Solutions We Build' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions_description' ), 'We develop intelligent AI chatbot solutions tailored to diverse business requirements. From enterprise virtual assistants and customer support automation to multilingual experiences and transactional chatbots, our conversational AI solutions are designed to deliver intelligent assistance at scale.' );

	$asset_base = get_theme_file_uri( '/assets/images/services/chatbot/' );
	$items = array(
		array( 'Enterprise AI Chatbots', 'Deliver intelligent and enterprise-ready conversational experiences designed to improve productivity, automate workflows, and enhance business operations.', 'application-chatbot.png' ),
		array( 'AI Virtual Assistants', 'Enable intelligent and personalized assistance across customer and employee experiences through scalable conversational AI solutions.', 'application-virtual.png' ),
		array( 'Multilingual AI Chatbots', 'Deliver seamless multilingual conversations that help businesses engage global audiences across digital channels and platforms.', 'application-language.png' ),
		array( 'Lead Generation Chatbots', 'Capture, qualify, and engage potential customers through intelligent conversational experiences designed to improve conversions and business growth.', 'application-lead.png' ),
		array( 'Voice Enabled AI Chatbots', 'Deliver human-like voice interactions across digital experiences through intelligent AI chatbot solutions powered by conversational AI technologies.', 'application-virtual.png' ),
		array( 'Enterprise Knowledge Assistants', 'Enable instant access to enterprise knowledge through intelligent and context-aware conversational experiences for modern organizations.', 'application-chatbot.png' ),
		array( 'AI Customer Support Chatbots', 'Provide 24/7 intelligent customer assistance through AI-powered chatbot solutions designed to improve response times and customer experiences.', 'application-chatbot.png' ),
		array( 'Transactional AI Chatbots', 'Automate bookings, order management, customer requests, and business workflows through intelligent and scalable AI chatbot solutions.', 'application-lead.png' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array(
			'group'       => '',
			'title'       => $item[0],
			'description' => $item[1],
			'image'       => $asset_base . $item[2],
			'link_label'  => '',
			'link_url'    => '',
		);
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_solutions_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_solutions', 71 );

/** Populate AI Chatbot capabilities through the editable Service repeater. */
function aibridze_seed_ai_chatbot_service_capabilities(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_capabilities_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_eyebrow' ), 'AI Chatbot Capabilities' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_title' ), 'Intelligent Capabilities Powering Modern AI Chatbot Experiences' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities_description' ), 'Our AI chatbot solutions combine conversational intelligence, enterprise integrations, and intelligent automation to deliver personalized, scalable, and human-like experiences across digital channels.' );

	$items = array(
		array( 'Context-Aware Conversations', 'Deliver intelligent conversations that understand user intent, context, and business requirements in real time.' ),
		array( 'Human-Like Interactions', 'Create natural and personalized conversational experiences designed to improve engagement and customer satisfaction.' ),
		array( 'Multilingual AI Experiences', 'Deliver seamless multilingual interactions across global audiences and digital platforms.' ),
		array( 'Enterprise Knowledge Retrieval', 'Enable instant access to enterprise knowledge through intelligent and context-aware AI chatbot experiences.' ),
		array( 'Omnichannel Conversations', 'Provide seamless conversational experiences across websites, mobile applications, messaging platforms, and enterprise systems.' ),
		array( 'Intelligent Workflow Automation', 'Automate repetitive interactions and business workflows through intelligent conversational AI capabilities.' ),
		array( 'Enterprise AI Integrations', 'Seamlessly integrate AI chatbot solutions across enterprise applications, APIs, databases, and cloud platforms.' ),
		array( 'Scalable Conversational AI', 'Build enterprise-ready conversational AI solutions designed to scale alongside your business requirements.' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_capabilities_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_capabilities', 72 );

/** Populate the AI Chatbot development process through editable Service fields. */
function aibridze_seed_ai_chatbot_service_process(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_process_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'process_eyebrow' ), 'AI Chatbot Development Process' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process_title' ), 'Our End-to-End AI Chatbot Development Process' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process_description' ), 'We follow a strategic and scalable AI chatbot development process designed to deliver intelligent, enterprise-ready conversational experiences tailored to your business objectives, users, and technology ecosystem.' );

	$items = array(
		array( 'Requirement Analysis & Discovery', 'We begin by understanding your business objectives, user journeys, support requirements, and automation opportunities to define the most valuable conversational AI experiences for your organization.' ),
		array( 'Conversation Strategy & Experience Design', 'Our experts design intelligent conversation flows, user experiences, and chatbot interactions that deliver personalized and human-like assistance across digital channels.' ),
		array( 'Solution Architecture & Enterprise Integrations', 'We architect scalable AI chatbot solutions and plan seamless integrations with CRMs, ERPs, APIs, databases, cloud platforms, and existing enterprise applications.' ),
		array( 'AI Chatbot Development', 'We develop custom AI chatbot solutions powered by advanced AI models, conversational AI frameworks, and enterprise technologies tailored to your business requirements.' ),
		array( 'Testing, Security & Quality Assurance', 'Every AI chatbot undergoes rigorous testing to validate conversational accuracy, performance, security, and enterprise readiness across real-world scenarios.' ),
		array( 'Deployment, Optimization & Continuous Support', 'We deploy scalable AI chatbot solutions across digital platforms while continuously monitoring performance, optimizing conversations, and supporting evolving business needs.' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_process_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_process', 73 );

/** Populate AI Chatbot industries through the editable Service repeater. */
function aibridze_seed_ai_chatbot_service_industries(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_industries_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_eyebrow' ), 'Conversational AI Across Industries' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_title' ), 'Transforming Customer and Enterprise Experiences Across Industries' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries_description' ), 'Organizations across industries are leveraging AI chatbot solutions to deliver intelligent assistance, improve customer experiences, and enhance operational efficiency. From enterprise knowledge assistants and customer support automation to conversational AI experiences, we develop scalable AI chatbot solutions tailored to modern business needs.' );

	$asset_base = get_theme_file_uri( '/assets/images/services/chatbot/industries/' );
	$items = array(
		array( 'Healthcare', 'heartbeat.png' ),
		array( 'E-Commerce', 'basket.png' ),
		array( 'EdTech', 'books.png' ),
		array( 'Travel', 'airplane.png' ),
		array( 'Real Estate', 'city.png' ),
		array( 'Telecom & Media', 'cell-tower.png' ),
		array( 'Manufacturing', 'factory.png' ),
		array( 'Automotive', 'car.png' ),
		array( 'Agriculture', 'grains.png' ),
		array( 'Energy', 'lightning.png' ),
		array( 'Food & Beverage', 'coffee.png' ),
		array( 'Entertainment', 'youtube.png' ),
		array( 'Legal', 'gavel.png' ),
		array( 'Non-Profits', 'hand-heart.png' ),
		array( 'HR & Enterprise', 'buildings.png' ),
		array( 'Government', 'bank.png' ),
		array( 'Bank & Finance', 'piggy-bank.png' ),
		array( 'Logistics', 'package.png' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $asset_base . $item[1], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_industries_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_industries', 74 );

/** Populate the AI Chatbot security and trust card through editable Service fields. */
function aibridze_seed_ai_chatbot_service_trust(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_trust_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$base = get_theme_file_uri( '/assets/images/services/responsible-ai/' );
	$values = array(
		'trust_title'            => 'Building Intelligent AI Chatbots Designed For Trust, Security, And Human Assistance',
		'trust_description'      => 'At AiBridze, we develop enterprise-ready AI chatbot solutions with a strong focus on privacy, transparency, and responsible AI practices. Every conversational experience is designed to deliver secure, reliable, and human-centric assistance while seamlessly integrating with your business ecosystem.',
		'trust_image'            => $base . 'chatbot-trust.png',
		'trust_principles_label' => '',
		'trust_cta_label'        => 'Book Your Chatbot Consultation',
		'trust_cta_url'          => '#consultation',
	);
	foreach ( $values as $key => $value ) {
		update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	}

	$items = array(
		array( 'Privacy-First Conversations', 'shield-check.png' ),
		array( 'Secure Data Handling', 'hard-drives.png' ),
		array( 'Human-in-the-Loop Assistance', 'user-switch.png' ),
	);
	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'trust_principles' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_trust_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_trust', 75 );

/** Populate the AI Chatbot FAQs through the editable Service FAQ repeater. */
function aibridze_seed_ai_chatbot_service_faqs(): void {
	if ( get_option( 'aibridze_ai_chatbot_service_faqs_v1' ) ) return;

	$service = get_page_by_path( 'ai-chatbot-development', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs_eyebrow' ), 'FAQs' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs_title' ), 'Frequently Asked Questions About AI Chatbot Development Services' );

	$items = array(
		array( 'Can AI chatbots integrate with our existing software and enterprise systems?', 'Absolutely. We develop custom AI chatbot solutions that seamlessly integrate with CRMs, ERPs, APIs, databases, cloud platforms, and existing business applications. Our enterprise-ready conversational AI solutions are designed to enhance your current technology ecosystem while improving productivity and customer experiences.' ),
		array( 'Can AI chatbots be customized for our business requirements?', 'Yes. Every AI chatbot we develop is tailored to your business objectives, workflows, users, and operational requirements. From customer support automation and enterprise virtual assistants to multilingual conversational experiences, our solutions are designed to address real-world business challenges.' ),
		array( 'Can AI chatbots provide multilingual support for global audiences?', 'Yes. Our multilingual AI chatbot solutions are capable of delivering personalized and context-aware conversations across multiple languages and digital channels, enabling businesses to provide seamless customer experiences for global audiences.' ),
		array( 'How do AI chatbots improve customer support and operational efficiency?', 'AI chatbot solutions provide intelligent assistance by automating repetitive interactions, reducing response times, and enabling 24/7 customer support experiences. Businesses can improve productivity while delivering faster and more personalized assistance across digital touchpoints.' ),
		array( 'How do you ensure security and responsible AI development?', 'Security, privacy, transparency, and human oversight are fundamental to every AI chatbot solution we develop. Our responsible conversational AI practices prioritize secure integrations, enterprise-ready architectures, and intelligent human escalation workflows designed for long-term reliability and performance.' ),
		array( 'Can AI chatbots work alongside human support teams?', 'Absolutely. Our conversational AI solutions are designed to complement human teams through intelligent human escalation workflows. AI chatbots can automate routine interactions while seamlessly transferring complex conversations to support teams whenever required.' ),
		array( 'How long does it take to develop a custom AI chatbot solution?', 'Development timelines depend on business requirements, integrations, conversational complexity, and deployment environments. While simpler implementations may take a few weeks, enterprise AI chatbot solutions typically involve multiple development phases to ensure scalability, security, and optimal performance.' ),
		array( 'Why choose AiBridze as your AI Chatbot Development Company?', 'At AiBridze, we combine conversational AI expertise with modern AI engineering capabilities to develop intelligent, scalable, and enterprise-ready AI chatbot solutions. Our focus on custom development, responsible AI practices, and measurable business outcomes enables organizations to confidently build and scale conversational AI experiences tailored to their goals.' ),
	);

	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs' ), $rows );
	update_option( 'aibridze_ai_chatbot_service_faqs_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_chatbot_service_faqs', 76 );

/** Create and populate the editable AI Automation Service page. */
function aibridze_seed_ai_automation_service(): void {
	if ( get_option( 'aibridze_ai_automation_service_v1' ) ) return;

	$service = get_page_by_path( 'ai-automation', OBJECT, 'service' );
	if ( ! $service ) {
		$service_id = wp_insert_post(
			array(
				'post_type'   => 'service',
				'post_status' => 'publish',
				'post_title'  => 'AI Automation Services',
				'post_name'   => 'ai-automation',
			)
		);
		if ( is_wp_error( $service_id ) || ! $service_id ) return;
		$service = get_post( $service_id );
	}

	$base = get_theme_file_uri( '/assets/images/services/automation/' );
	$values = array(
		'hero_eyebrow'      => '',
		'hero_title'        => 'AI Automation Services',
		'hero_description'  => 'Transform manual and repetitive business processes with custom AI automation solutions built around your operations. AiBridze combines artificial intelligence, intelligent workflow automation, and enterprise integrations to automate complex processes, reduce operational effort, and improve business efficiency at scale.',
		'hero_image'        => $base . 'automation-hero.png',
		'hero_mobile_image' => $base . 'automation-hero.png',
		'hero_cta_label'    => 'Schedule a Free Consultation',
		'hero_cta_url'      => '#consultation',
		'intro_eyebrow'     => 'Intelligent Business Automation',
		'intro_title'       => 'Transform Business Operations with AI-Powered Automation',
		'intro_description' => "AI automation goes beyond traditional rule-based automation by bringing intelligence, adaptability, and decision-making into business workflows. We develop AI-powered automation solutions that connect your people, data, applications, and processes to streamline operations and enable smarter, more scalable ways of working.\n\nFrom AI workflow automation and document processing to enterprise process automation and system integrations, we help businesses identify high-impact automation opportunities and turn them into reliable, production-ready solutions.",
		'intro_image'       => $base . 'automation-intro.png',
		'benefits_eyebrow'  => 'AI Automation Business Benefits',
		'benefits_title'    => 'Why Businesses Are Investing in AI Automation',
		'benefits_description' => 'AI-powered automation helps businesses eliminate repetitive work, optimize complex workflows, and improve operational efficiency. By combining AI with existing business systems and processes, organizations can automate more than predefined tasks and build intelligent workflows that adapt to real business needs.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$items = array(
		array( 'Reduce Manual Work', 'Automate repetitive tasks and time-consuming business processes with intelligent AI automation solutions.', 'benefit-manual-work.png' ),
		array( 'Improve Operational Efficiency', 'Streamline workflows and eliminate process bottlenecks to improve productivity across business operations.', 'benefit-efficiency.png' ),
		array( 'Accelerate Business Processes', 'Process information, trigger actions, and complete multi-step workflows faster with AI-powered automation.', 'benefit-acceleration.png' ),
		array( 'Connect Business Systems', 'Integrate AI automation with CRMs, ERPs, APIs, databases, and enterprise applications for connected workflows.', 'benefit-systems.png' ),
		array( 'Improve Process Accuracy', 'Reduce manual errors and improve consistency across data-intensive and repetitive business processes.', 'benefit-accuracy.png' ),
	);
	$rows = array();
	foreach ( $items as $item ) {
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), $rows );
	update_post_meta( $service->ID, '_aibridze_service_layout', 'automation' );
	update_option( 'aibridze_ai_automation_service_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_automation_service', 78 );

/** Populate the AI Automation technology stack through editable grouped rows. */
function aibridze_seed_ai_automation_technology(): void {
	if ( get_option( 'aibridze_ai_automation_technology_v1' ) ) return;
	$service = get_page_by_path( 'ai-automation', OBJECT, 'service' );
	if ( ! $service ) return;

	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_eyebrow' ), 'AI Automation Technology Stack' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_title' ), 'Powering Intelligent Automation with Advanced AI Technologies' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology_description' ), 'We combine modern AI models, automation frameworks, APIs, cloud platforms, and enterprise technologies to build secure and scalable AI automation solutions tailored to complex business workflows.' );

	$base = get_theme_file_uri( '/assets/images/services/automation/tech/' );
	$groups = array(
		'AI & Language Models' => array(
			array( 'OpenAI', 'openai.png' ), array( 'Claude', 'claude.png' ), array( 'Gemini', 'gemini.png' ), array( 'Llama', 'llama.png' ), array( 'Grok', 'grok.png' ), array( 'Kimi K3', 'kimi.png' ), array( 'Mistral', 'mistral.png' ),
		),
		'Workflow & Process Automation' => array(
			array( 'n8n', 'n8n.png' ), array( 'Microsoft Power Automate', 'power-automate.png' ), array( 'UiPath', 'uipath.png' ), array( 'Camunda', 'camunda.png' ), array( 'Zapier', 'zapier.png' ),
		),
		'Intelligent Document Processing' => array(
			array( 'Azure AI Document Intelligence', 'azure-document.png' ), array( 'Amazon Textract', 'textract.png' ), array( 'Google Document AI', 'google-document-ai.png' ), array( 'ABBYY', 'abbyy.png' ),
		),
		'AI & Machine Learning' => array(
			array( 'TensorFlow', 'tensorflow.png' ), array( 'PyTorch', 'pytorch.png' ), array( 'scikit-learn', 'scikit-learn.png' ), array( 'Amazon SageMaker', 'sagemaker.png' ), array( 'Azure Machine Learning', 'azure-ml.png' ),
		),
		'Enterprise Integration & APIs' => array(
			array( 'MuleSoft', 'mulesoft.png' ), array( 'REST APIs', 'rest-api.png' ), array( 'Webhooks', 'webhooks.png' ), array( 'CRM & ERP Integrations', 'crm-erp.png' ),
		),
		'Cloud & Infrastructure' => array(
			array( 'AWS', 'aws.png' ), array( 'Microsoft Azure', 'azure.png' ), array( 'Google Cloud', 'google-cloud.png' ), array( 'Kubernetes', 'kubernetes.png' ), array( 'Docker', 'docker.png' ),
		),
		'Development Technologies' => array(
			array( 'Python', 'python.png' ), array( 'React', 'react.png' ), array( 'Next.js', 'nextjs.png' ), array( 'Node.js', 'nodejs.png' ), array( 'Django', 'django.png' ), array( 'FastAPI', 'fastapi.png' ), array( 'PostgreSQL', 'postgresql.png' ),
		),
	);
	$rows = array();
	foreach ( $groups as $group => $items ) {
		foreach ( $items as $item ) {
			$rows[] = array( 'group' => $group, 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
		}
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology' ), $rows );
	update_option( 'aibridze_ai_automation_technology_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_automation_technology', 79 );

/** Populate the AI Automation conversion banner, expertise tabs, and solution cards. */
function aibridze_seed_ai_automation_expertise(): void {
	if ( get_option( 'aibridze_ai_automation_expertise_v1' ) ) return;
	$service = get_page_by_path( 'ai-automation', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/automation/' );

	$values = array(
		'cta_position'        => 'after_technology',
		'cta_variant'         => 'automation-banner',
		'cta_title'           => 'Turn Complex Business Workflows Into Intelligent Automation',
		'cta_description'     => 'Reduce repetitive work and streamline operations with custom AI automation solutions designed to integrate with your existing systems and scale with your business.',
		'cta_image'           => $base . 'automation-workflows.png',
		'cta_label'           => 'Start Your AI Automation Project',
		'cta_url'             => '#consultation',
		'expertise_eyebrow'   => 'AI Automation Development Expertise',
		'expertise_title'     => 'Custom AI Automation Services Built Around Your Business Processes',
		'expertise_description' => 'From intelligent workflow automation and business process optimization to document processing and enterprise system integration, we develop custom AI automation solutions that reduce manual effort, streamline operations, and improve efficiency across your business.',
		'solutions_eyebrow'   => 'AI Automation Solutions',
		'solutions_title'     => 'Intelligent Automation Solutions for Modern Business Operations',
		'solutions_description' => 'We build AI-powered automation solutions that help businesses streamline repetitive work, accelerate processes, and connect workflows across teams and enterprise systems.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$expertise = array(
		array( 'Custom AI Automation', 'Build AI-powered automation solutions tailored to your workflows, business rules, data, and operational requirements.', array( 'Custom Business Workflows', 'Repetitive Task Automation', 'Operational Automation', 'Process Optimization' ) ),
		array( 'AI Workflow Automation', 'Automate multi-step business workflows by connecting AI, data, applications, and human approvals into intelligent automated processes.', array( 'Workflow Automation', 'Approval Processes', 'Task Orchestration', 'Cross-System Workflows' ) ),
		array( 'Business Process Automation', 'Transform manual and rule-intensive processes with intelligent automation designed to improve speed, accuracy, and operational efficiency.', array( 'Business Process Automation', 'Back-Office Operations', 'Process Optimization', 'Enterprise Workflows' ) ),
		array( 'Document Processing & Automation', 'Automate document-heavy workflows using AI to extract, classify, validate, and process information from invoices, forms, contracts, and other business documents.', array( 'Invoice Processing', 'Data Extraction', 'Document Classification', 'Forms & Contract Processing' ) ),
		array( 'Enterprise AI Automation', 'Connect AI-powered automation with your existing CRM, ERP, databases, APIs, and enterprise applications to create seamless end-to-end workflows.', array( 'CRM Automation', 'ERP Integration', 'API Automation', 'Enterprise System Integration' ) ),
	);
	$expertise_rows = array();
	foreach ( $expertise as $item ) {
		$list = '<p>' . esc_html( $item[1] ) . '</p><p>Ideal for:</p><ul>';
		foreach ( $item[2] as $ideal ) $list .= '<li>' . esc_html( $ideal ) . '</li>';
		$list .= '</ul>';
		$expertise_rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $list, 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise' ), $expertise_rows );

	$solutions = array(
		array( 'Customer Service', 'Automate customer inquiries, ticket routing, response workflows, and support operations to deliver faster and more consistent customer experiences.' ),
		array( 'Sales & Marketing', 'Streamline lead qualification, follow-ups, campaign workflows, and customer engagement with intelligent AI-powered automation.' ),
		array( 'Finance & Accounting', 'Automate invoice processing, data extraction, reconciliation, reporting, and other repetitive finance workflows with greater speed and accuracy.' ),
		array( 'HR & Employee Workflow', 'Simplify employee onboarding, document processing, internal requests, and routine HR workflows through intelligent business automation.' ),
		array( 'Document & Data', 'Extract, classify, validate, and route information from documents and unstructured data to reduce manual processing across business operations.' ),
		array( 'IT & Service Desk', 'Automate ticket classification, request routing, knowledge retrieval, and repetitive IT support workflows to improve service efficiency.' ),
		array( 'Back-Office Process', 'Streamline repetitive administrative and operational processes across departments with connected AI workflows and enterprise automation.' ),
		array( 'Enterprise Workflow', 'Connect applications, data, approvals, and business processes to automate complex, multi-step workflows across enterprise environments.' ),
	);
	$solution_rows = array();
	foreach ( $solutions as $item ) $solution_rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), $solution_rows );
	update_option( 'aibridze_ai_automation_expertise_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_automation_expertise', 80 );

/** Add the automation solution artwork and capability content without replacing editor-managed copy. */
function aibridze_seed_ai_automation_solutions_capabilities(): void {
	if ( get_option( 'aibridze_ai_automation_solutions_capabilities_v1' ) ) return;
	$service = get_page_by_path( 'ai-automation', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/automation/' );

	$icon_map = array(
		'Customer Service'       => 'solution-customer-service.png',
		'Sales & Marketing'      => 'solution-sales-marketing.png',
		'Finance & Accounting'   => 'solution-finance.png',
		'HR & Employee Workflow' => 'solution-hr.png',
		'Document & Data'        => 'solution-finance.png',
		'IT & Service Desk'      => 'solution-customer-service.png',
		'Back-Office Process'    => 'solution-sales-marketing.png',
		'Enterprise Workflow'    => 'solution-hr.png',
	);
	$solutions = get_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), true );
	if ( is_array( $solutions ) ) {
		foreach ( $solutions as &$row ) {
			$title = $row['title'] ?? '';
			if ( isset( $icon_map[ $title ] ) ) $row['image'] = $base . $icon_map[ $title ];
		}
		unset( $row );
		update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), $solutions );
	}

	$values = array(
		'capabilities_eyebrow'     => 'AI Automation Capabilities',
		'capabilities_title'       => 'Intelligent Capabilities Powering AI-Driven Business Automation',
		'capabilities_description' => 'Our AI automation solutions combine artificial intelligence, workflow orchestration, and enterprise integrations to automate complex processes, improve operational efficiency, and enable intelligent decision-making across your organization.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$capabilities = array(
		array( 'Intelligent Workflow Orchestration', 'Automate multi-step business workflows across teams, applications, and enterprise systems with AI-driven process orchestration.' ),
		array( 'Decision Automation', 'Enable AI to evaluate business rules, analyze data, and trigger intelligent actions without constant manual intervention.' ),
		array( 'Document & Data Intelligence', 'Extract, classify, validate, and process structured and unstructured business data using intelligent document processing.' ),
		array( 'Enterprise System Integrations', 'Connect AI automation with CRMs, ERPs, databases, APIs, cloud services, and business applications for seamless end-to-end workflows.' ),
		array( 'Predictive Process Optimization', 'Leverage AI to identify bottlenecks, optimize workflows, and continuously improve business operations using real-time insights.' ),
		array( 'Human-In-The-Loop Automation', 'Combine AI automation with human approvals and review workflows to ensure accuracy, compliance, and operational control.' ),
		array( 'Scalable Business Automation', 'Deploy enterprise-ready AI automation solutions that grow alongside your business, teams, and operational requirements.' ),
		array( 'Secure & Reliable Automation', 'Build automation workflows with enterprise-grade security, monitoring, audit trails, and governance to ensure reliable business operations.' ),
	);
	$rows = array();
	foreach ( $capabilities as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities' ), $rows );
	update_option( 'aibridze_ai_automation_solutions_capabilities_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_automation_solutions_capabilities', 81 );

/** Populate the editable AI Automation process, industry, and control sections. */
function aibridze_seed_ai_automation_process_industries_trust(): void {
	if ( get_option( 'aibridze_ai_automation_process_industries_trust_v1' ) ) return;
	$service = get_page_by_path( 'ai-automation', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/automation/' );
	$values = array(
		'process_eyebrow'       => 'AI Automation Development Process',
		'process_title'         => 'Our End-to-End AI Automation Development Process',
		'process_description'   => 'We follow a structured AI automation development process to identify high-value automation opportunities, redesign workflows, integrate enterprise systems, and deploy scalable automation solutions built around your business operations.',
		'industries_eyebrow'    => 'AI Automation Across Industries',
		'industries_title'      => 'Transforming Business Operations with AI Automation Across Industries',
		'industries_description'=> 'We develop industry-focused AI automation solutions that streamline workflows, reduce manual effort, and improve operational efficiency across complex business processes.',
		'trust_title'           => 'Automate Business Processes Without Compromising Control',
		'trust_description'     => 'Build secure, scalable AI automation solutions with human oversight, controlled workflows, and reliable enterprise integrations for confident business automation.',
		'trust_image'           => $base . 'automation-control.png',
		'trust_principles_label'=> '',
		'trust_cta_label'       => 'Talk to AI Experts',
		'trust_cta_url'         => '#consultation',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$process = array(
		array( '1. Process Discovery & Automation', 'We analyze your existing workflows, repetitive tasks, bottlenecks, systems, and business requirements to identify processes with the highest automation potential.', 'process-discovery.png' ),
		array( '2. Automation Strategy & Workflow', 'We define the automation roadmap and design intelligent workflows, decision points, human approvals, data flows, and exception-handling processes.', 'process-strategy.png' ),
		array( '3. Solution Architecture', 'Our experts design a scalable AI automation architecture and plan integrations with your CRMs, ERPs, APIs, databases, cloud platforms, and existing business systems.', 'process-architecture.png' ),
		array( '4. AI Automation Development', 'We build custom AI automation workflows using AI models, intelligent document processing, workflow automation technologies, APIs, and business rules tailored to your operations.', 'process-development.png' ),
		array( '5. Testing & Process Validation', 'We test automated workflows across real-world scenarios, integrations, exceptions, security requirements, and process conditions to ensure reliable and accurate execution.', 'process-testing.png' ),
		array( '6. Deployment & Enterprise Integration', 'We deploy your AI automation solution, connect it with enterprise systems, and configure secure production workflows for reliable day-to-day operations.', 'process-deployment.png' ),
		array( '7. Monitoring & Continuous Optimization', 'We monitor automation performance, identify bottlenecks, refine workflows, and optimize processes as your data, systems, and business requirements evolve.', 'process-monitoring.png' ),
	);
	$rows = array();
	foreach ( $process as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process' ), $rows );

	$industries = array(
		array( 'Healthcare', 'industry-healthcare.png' ), array( 'E-Commerce', 'industry-ecommerce.png' ), array( 'EdTech', 'industry-edtech.png' ),
		array( 'Travel', 'industry-travel.png' ), array( 'Real Estate', 'industry-real-estate.png' ), array( 'Telecom & Media', 'industry-telecom.png' ),
		array( 'Manufacturing', 'industry-manufacturing.png' ), array( 'Automotive', 'industry-automotive.png' ), array( 'Agriculture', 'industry-agriculture.png' ),
		array( 'Energy', 'industry-energy.png' ), array( 'Food & Beverage', 'industry-food.png' ), array( 'Entertainment', 'industry-entertainment.png' ),
		array( 'Legal', 'industry-legal.png' ), array( 'Non-Profits', 'industry-nonprofits.png' ), array( 'HR & Enterprise', 'industry-hr.png' ),
		array( 'Government', 'industry-hr.png' ), array( 'Bank & Finance', 'industry-finance.png' ), array( 'Logistics', 'industry-logistics.png' ),
	);
	$rows = array();
	foreach ( $industries as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries' ), $rows );

	$principles = array(
		array( 'Continuous Monitoring', 'process-monitoring.png' ),
		array( 'Secure Data Handling', 'process-testing.png' ),
		array( 'Human-in-the-Loop Assistance', 'process-strategy.png' ),
	);
	$rows = array();
	foreach ( $principles as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'trust_principles' ), $rows );
	update_option( 'aibridze_ai_automation_process_industries_trust_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_automation_process_industries_trust', 82 );

/** Populate the editable AI Automation FAQs. */
function aibridze_seed_ai_automation_faqs(): void {
	if ( get_option( 'aibridze_ai_automation_faqs_v1' ) ) return;
	$service = get_page_by_path( 'ai-automation', OBJECT, 'service' );
	if ( ! $service ) return;
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs_eyebrow' ), 'FAQs' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs_title' ), 'Frequently Asked Questions About AI Automation Services' );
	$faqs = array(
		array( 'What types of business processes are best suited for AI automation?', 'AI automation works particularly well for repetitive, data-intensive, document-heavy, and multi-step processes. Our team evaluates your workflows to identify opportunities where automation can reduce manual effort, improve accuracy, and create measurable operational value.' ),
		array( 'How does AiBridze identify the right AI automation opportunities for a business?', 'We analyze existing workflows, bottlenecks, systems, data, and business objectives to identify high-impact automation opportunities. We prioritize processes where AI can deliver practical improvements in efficiency, speed, accuracy, or scalability.' ),
		array( 'Can AI automation work with our existing CRM, ERP, and business applications?', 'Yes. AI automation can integrate with CRMs, ERPs, APIs, databases, cloud platforms, and custom enterprise applications. Our expertise allows us to create connected workflows around your existing technology ecosystem without unnecessary system replacement.' ),
		array( 'Can you enhance our existing workflow automation with AI?', 'Definitely. We can introduce AI capabilities such as intelligent document processing, natural language understanding, data classification, decision support, and exception handling into existing automated workflows where they add meaningful value.' ),
		array( 'Should we start with one AI automation use case or automate multiple processes?', 'For many businesses, starting with a focused, high-impact process is the better approach. It allows us to validate technical feasibility and business value before expanding AI automation across additional workflows, departments, and enterprise systems.' ),
		array( 'How do you measure ROI from AI automation?', 'The impact can be measured through processing time, manual effort saved, error reduction, throughput, operational costs, response times, and employee productivity. We align relevant performance metrics with your automation objectives before implementation.' ),
		array( 'Can humans remain involved in AI-automated business processes?', 'Absolutely. We can design human-in-the-loop workflows with approvals, validation checkpoints, exception handling, and escalation paths, allowing AI to automate routine work while people retain control over critical decisions.' ),
		array( 'Why choose AiBridze as an AI automation development company?', 'AiBridze combines AI engineering, workflow automation, enterprise integration, and custom software development expertise to build automation around real business processes. We focus on secure, scalable AI automation solutions that integrate with your existing systems and deliver measurable operational improvements.' ),
	);
	$rows = array();
	foreach ( $faqs as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs' ), $rows );
	update_option( 'aibridze_ai_automation_faqs_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_automation_faqs', 83 );

/** Create the editable RAG Development Services page and its initial sections. */
function aibridze_seed_rag_development_service(): void {
	if ( get_option( 'aibridze_rag_development_service_v1' ) ) return;
	$service = get_page_by_path( 'rag-development', OBJECT, 'service' );
	if ( ! $service ) {
		$service_id = wp_insert_post( array( 'post_type' => 'service', 'post_status' => 'publish', 'post_title' => 'RAG Development Services', 'post_name' => 'rag-development' ) );
		if ( is_wp_error( $service_id ) || ! $service_id ) return;
		$service = get_post( $service_id );
	}
	$base = get_theme_file_uri( '/assets/images/services/rag/' );
	$values = array(
		'hero_title'          => 'RAG Development Services',
		'hero_description'    => 'Transform your business data into accurate, context-aware AI experiences with custom Retrieval-Augmented Generation solutions. AiBridze develops enterprise RAG systems that connect Large Language Models with your documents, databases, APIs, and knowledge sources to deliver relevant, reliable, and business-specific responses.',
		'hero_image'          => $base . 'rag-hero.png',
		'hero_mobile_image'   => $base . 'rag-hero.png',
		'hero_cta_label'      => 'Schedule RAG Consultation',
		'hero_cta_url'        => '#consultation',
		'intro_eyebrow'       => 'Retrieval-Augmented Generation',
		'intro_title'         => 'What is Retrieval-Augmented Generation (RAG)?',
		'intro_description'   => "Retrieval-Augmented Generation (RAG) is an AI architecture that connects Large Language Models (LLMs) with external knowledge sources such as business documents, databases, APIs, and enterprise knowledge bases.\n\nInstead of relying only on what an AI model already knows, RAG retrieves relevant information from trusted sources and uses that context to generate more accurate, relevant, and knowledge-grounded responses.\n\n<h3>How Retrieval-Augmented Generation Works?</h3><div class=\"rag-how\"><article><strong>1. Retrieval</strong><span>The RAG system searches connected knowledge sources to find the most relevant information for the user's query.</span></article><article><strong>2. Augmentation</strong><span>The retrieved information is added to the user's query, giving the Large Language Model relevant and business-specific context.</span></article><article><strong>3. Generation</strong><span>The LLM uses the retrieved context to generate an accurate, relevant, and context-aware response grounded in your trusted data.</span></article></div>",
		'intro_image'         => $base . 'rag-intro.png',
		'benefits_eyebrow'    => 'Business Value of RAG',
		'benefits_title'      => 'Why Custom RAG Development Matters for Enterprise AI',
		'benefits_description'=> 'Custom RAG solutions connect generative AI with trusted business data, helping organizations improve response accuracy, reduce model retraining, and build secure, scalable AI applications grounded in enterprise knowledge.',
		'technology_eyebrow'  => 'RAG Development Technology Stack',
		'technology_title'    => 'Powering Accurate AI Retrieval with Advanced RAG Technologies',
		'technology_description' => 'We combine Large Language Models, embedding models, vector databases, retrieval frameworks, and cloud technologies to build scalable RAG solutions that securely connect AI with enterprise knowledge.',
		'cta_position'        => 'after_technology',
		'cta_variant'         => 'rag-banner',
		'cta_title'           => 'Build Accurate, Knowledge-Grounded AI Experiences',
		'cta_description'     => 'Connect your enterprise knowledge with advanced RAG systems designed for accurate retrieval, secure integration, and scalable AI applications.',
		'cta_image'           => $base . 'rag-cta.png',
		'cta_label'           => 'Start Your RAG Project',
		'cta_url'             => '#consultation',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$benefits = array(
		array( 'Cost-Efficient AI Development', 'Keep AI applications updated with evolving business knowledge without continuously retraining or fine-tuning large language models.', 'benefit-cost.png' ),
		array( 'Enterprise-Ready Scalability', 'Build scalable RAG architectures capable of retrieving knowledge across growing datasets, complex queries, and enterprise applications.', 'benefit-scale.png' ),
		array( 'Secure Enterprise Knowledge', 'Connect AI with proprietary documents, databases, and knowledge sources while maintaining controlled access to sensitive business information.', 'benefit-secure.png' ),
		array( 'Improved Accuracy & Trust', 'Deliver context-aware AI responses backed by retrieved business knowledge, making enterprise AI applications more reliable and useful.', 'benefit-trust.png' ),
		array( 'Faster Knowledge Access', 'Unify fragmented information across documents, databases, APIs, and knowledge bases to help users find relevant business information faster.', 'benefit-access.png' ),
	);
	$rows = array(); foreach ( $benefits as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), $rows );

	$groups = array(
		'Large Language Models' => array( array('OpenAI','openai.png'),array('Claude','claude.png'),array('Gemini','gemini.png'),array('Llama','llama.png'),array('Grok','grok.png'),array('Kimi K3','kimi.png'),array('Qwen','qwen.png') ),
		'RAG & AI Frameworks' => array( array('LangGraph','langgraph.png'),array('LangChain','langchain.png'),array('LlamaIndex','llamaindex.png'),array('Haystack','haystack.png'),array('Microsoft Agent Framework','microsoft-agent.png') ),
		'Vector Databases' => array( array('Pinecone','pinecone.png'),array('PostgreSQL / pgvector','postgres.png'),array('Weaviate','weaviate.png'),array('Chroma','chroma.png'),array('Milvus','milvus.png'),array('Qdrant','qdrant.png') ),
		'Data & Backend' => array( array('Python','python.png'),array('FastAPI','fastapi.png'),array('Node.js','node.png'),array('Django','django.png'),array('PostgreSQL','postgres-backend.png'),array('MongoDB','mongodb.png') ),
		'Cloud & Infrastructure' => array( array('Google Cloud','google-cloud.png'),array('AWS','aws.png'),array('Microsoft Azure','azure.png'),array('Docker','docker.png'),array('Kubernetes','kubernetes.png'),array('Redis','redis.png') ),
	);
	$rows = array(); foreach ( $groups as $group => $items ) foreach ( $items as $item ) $rows[] = array( 'group' => $group, 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology' ), $rows );
	update_post_meta( $service->ID, '_aibridze_service_layout', 'rag' );
	update_option( 'aibridze_rag_development_service_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_rag_development_service', 84 );

/** Normalize the initial RAG explainer paragraph breaks after first-page creation. */
function aibridze_update_rag_intro_copy(): void {
	if ( get_option( 'aibridze_rag_intro_copy_v2' ) ) return;
	$service = get_page_by_path( 'rag-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$description = "Retrieval-Augmented Generation (RAG) is an AI architecture that connects Large Language Models (LLMs) with external knowledge sources such as business documents, databases, APIs, and enterprise knowledge bases.\n\nInstead of relying only on what an AI model already knows, RAG retrieves relevant information from trusted sources and uses that context to generate more accurate, relevant, and knowledge-grounded responses.\n\n<h3>How Retrieval-Augmented Generation Works?</h3><div class=\"rag-how\"><article><strong>1. Retrieval</strong><span>The RAG system searches connected knowledge sources to find the most relevant information for the user's query.</span></article><article><strong>2. Augmentation</strong><span>The retrieved information is added to the user's query, giving the Large Language Model relevant and business-specific context.</span></article><article><strong>3. Generation</strong><span>The LLM uses the retrieved context to generate an accurate, relevant, and context-aware response grounded in your trusted data.</span></article></div>";
	update_post_meta( $service->ID, aibridze_service_meta_key( 'intro_description' ), $description );
	update_option( 'aibridze_rag_intro_copy_v2', 1, false );
}
add_action( 'init', 'aibridze_update_rag_intro_copy', 85 );

/** Populate the editable RAG engineering expertise and enterprise solution sections. */
function aibridze_seed_rag_expertise_solutions(): void {
	if ( get_option( 'aibridze_rag_expertise_solutions_v1' ) ) return;
	$service = get_page_by_path( 'rag-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/rag/' );
	$values = array(
		'expertise_eyebrow'     => 'Enterprise RAG Engineering Expertise',
		'expertise_title'       => 'Custom RAG Development Services Built Around Your Enterprise Knowledge',
		'expertise_description' => 'From enterprise knowledge retrieval and vector search to multimodal RAG and LLM integration, we develop custom Retrieval-Augmented Generation solutions designed for accuracy, scalability, and real-world business applications.',
		'solutions_eyebrow'     => 'RAG-Powered AI Solutions',
		'solutions_title'       => 'RAG Solutions We Build for Enterprise Knowledge',
		'solutions_description' => 'We develop RAG-powered applications that transform enterprise data into accessible, context-aware intelligence, helping teams find information faster, improve AI accuracy, and make better use of organizational knowledge.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$expertise = array(
		array( 'Custom RAG Development Services', 'Develop custom Retrieval-Augmented Generation solutions that connect Large Language Models with your proprietary business data and knowledge sources.', array( 'Enterprise AI Applications', 'Custom AI Assistants', 'Knowledge Systems', 'Domain-Specific AI', 'Internal AI Tools', 'Generative AI Applications' ) ),
		array( 'Enterprise RAG Solutions', 'Build scalable RAG systems that securely retrieve knowledge across enterprise documents, databases, applications, and distributed information sources.', array( 'Enterprise Search', 'Knowledge Management', 'Internal Q&A', 'Employee Assistants', 'Enterprise AI', 'Large Knowledge Repositories' ) ),
		array( 'RAG Pipeline Development & Optimization', 'Engineer retrieval pipelines with intelligent chunking, embeddings, vector search, hybrid retrieval, reranking, and contextual optimization for more relevant AI responses.', array( 'Semantic Search', 'Hybrid Search', 'Vector Retrieval', 'Retrieval Optimization', 'Query Processing', 'Response Accuracy' ) ),
		array( 'RAG Integration Services', 'Connect RAG solutions with existing databases, APIs, CRMs, ERPs, document repositories, cloud platforms, and enterprise applications.', array( 'Database Integration', 'API Integration', 'Enterprise Applications', 'Knowledge Bases', 'CRM & ERP Data', 'Cloud Systems' ) ),
		array( 'Multimodal RAG Development', 'Build RAG applications capable of retrieving and understanding information across text, documents, images, tables, and other enterprise content.', array( 'Document Intelligence', 'PDF Search', 'Image-Based Knowledge', 'Complex Documents', 'Enterprise Research', 'Multimodal AI' ) ),
		array( 'Advanced RAG Architecture Development', 'Design advanced RAG architectures tailored to retrieval complexity, data environments, performance requirements, and enterprise AI use cases.', array( 'Advanced RAG', 'Agentic RAG', 'Adaptive RAG', 'Corrective RAG', 'Modular RAG', 'Real-Time RAG' ) ),
	);
	$rows = array();
	foreach ( $expertise as $item ) {
		$html = '<p>' . esc_html( $item[1] ) . '</p><p>Ideal for:</p><ul>';
		foreach ( $item[2] as $ideal ) $html .= '<li>' . esc_html( $ideal ) . '</li>';
		$html .= '</ul>';
		$rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $html, 'image' => '', 'link_label' => '', 'link_url' => '' );
	}
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise' ), $rows );

	$solutions = array(
		array( 'Enterprise Knowledge Assistants', 'Build intelligent AI assistants that retrieve relevant information from internal documents, policies, databases, and knowledge repositories to answer business-specific questions.', 'solution-enterprise.png' ),
		array( 'RAG-Powered Enterprise Search', 'Transform traditional enterprise search with semantic and vector-based retrieval that understands user intent and surfaces relevant information across distributed knowledge sources.', 'solution-search.png' ),
		array( 'Document Q&A Systems', 'Enable users to ask natural-language questions across PDFs, reports, manuals, contracts, policies, and other business documents and receive grounded, context-aware answers.', 'solution-research.png' ),
		array( 'Customer Support Knowledge Systems', 'Connect AI-powered support experiences with product documentation, FAQs, knowledge bases, and historical information to provide accurate and consistent customer assistance.', 'solution-support.png' ),
		array( 'Internal Employee Assistants', 'Give employees faster access to company policies, procedures, HR information, technical documentation, and organizational knowledge through conversational AI interfaces.', 'solution-employee.png' ),
		array( 'AI Research & Knowledge Assistants', 'Retrieve, compare, and synthesize relevant information across large knowledge repositories to accelerate research, analysis, and information-intensive workflows.', 'solution-research.png' ),
		array( 'RAG-Powered Recommendation Systems', 'Combine user context with retrieved business data to generate more relevant recommendations across products, content, services, and enterprise applications.', 'solution-domain.png' ),
		array( 'Domain-Specific AI Applications', 'Build specialized RAG solutions grounded in industry or organization-specific knowledge for areas such as healthcare, finance, legal, manufacturing, and other knowledge-intensive domains.', 'solution-domain.png' ),
	);
	$rows = array();
	foreach ( $solutions as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), $rows );
	update_option( 'aibridze_rag_expertise_solutions_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_rag_expertise_solutions', 86 );

/** Populate the editable RAG development process and industry grid. */
function aibridze_seed_rag_process_industries(): void {
	if ( get_option( 'aibridze_rag_process_industries_v1' ) ) return;
	$service = get_page_by_path( 'rag-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/rag/' );
	$values = array(
		'process_eyebrow'        => 'Our RAG Development Process',
		'process_title'          => 'From Enterprise Data to Production-Ready RAG Solutions',
		'process_description'    => 'Our RAG development process focuses on data quality, retrieval accuracy, LLM performance, and enterprise integration to build reliable AI solutions grounded in your business knowledge.',
		'industries_eyebrow'     => 'RAG Across Industries',
		'industries_title'       => 'Unlocking Industry Knowledge with Enterprise RAG Solutions',
		'industries_description' => 'We develop industry-focused RAG solutions that connect Large Language Models with domain-specific data, helping businesses retrieve critical information, improve knowledge accessibility, and deliver accurate, context-aware AI experiences.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$process = array(
		array( 'Use Case & Knowledge Assessment', 'We define business objectives, user queries, knowledge sources, and data requirements to establish the right RAG development strategy.' ),
		array( 'Data Preparation & Knowledge Processing', 'We clean, structure, chunk, and prepare enterprise data for efficient indexing, embedding, and knowledge retrieval.' ),
		array( 'RAG Architecture & Model Selection', 'We select suitable LLMs, embedding models, vector databases, retrieval techniques, and infrastructure based on your performance and scalability requirements.' ),
		array( 'Retrieval Pipeline & LLM Integration', 'We develop and optimize retrieval pipelines using semantic search, hybrid retrieval, embeddings, and reranking, then connect retrieved knowledge with the LLM for grounded responses.' ),
		array( 'RAG Evaluation & Testing', 'We evaluate retrieval relevance, response accuracy, hallucinations, latency, and source grounding across real-world queries before deployment.' ),
		array( 'Deployment & Continuous Optimization', 'We integrate the RAG solution with your enterprise environment, monitor retrieval and response quality, and continuously optimize performance as your knowledge evolves.' ),
	);
	$rows = array();
	foreach ( $process as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process' ), $rows );

	$industries = array(
		array( 'Healthcare', 'industry-healthcare.png' ), array( 'E-Commerce', 'industry-ecommerce.png' ), array( 'EdTech', 'industry-edtech.png' ),
		array( 'Travel', 'industry-travel.png' ), array( 'Real Estate', 'industry-real-estate.png' ), array( 'Telecom & Media', 'industry-telecom.png' ),
		array( 'Manufacturing', 'industry-manufacturing.png' ), array( 'Automotive', 'industry-automotive.png' ), array( 'Agriculture', 'industry-agriculture.png' ),
		array( 'Energy', 'industry-energy.png' ), array( 'Food & Beverage', 'industry-food.png' ), array( 'Entertainment', 'industry-entertainment.png' ),
		array( 'Legal', 'industry-legal.png' ), array( 'Non-Profits', 'industry-nonprofits.png' ), array( 'HR & Enterprise', 'industry-hr.png' ),
		array( 'Government', 'industry-government.png' ), array( 'Bank & Finance', 'industry-finance.png' ), array( 'Logistics', 'industry-logistics.png' ),
	);
	$rows = array();
	foreach ( $industries as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries' ), $rows );
	update_option( 'aibridze_rag_process_industries_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_rag_process_industries', 87 );

/** Populate the editable RAG trust card and FAQs. */
function aibridze_seed_rag_trust_faqs(): void {
	if ( get_option( 'aibridze_rag_trust_faqs_v1' ) ) return;
	$service = get_page_by_path( 'rag-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$rag_base = get_theme_file_uri( '/assets/images/services/rag/' );
	$icon_base = get_theme_file_uri( '/assets/images/services/responsible-ai/' );
	$values = array(
		'trust_title'            => 'Build RAG Solutions Your Business Can Trust',
		'trust_description'      => 'We develop secure, knowledge-grounded RAG solutions designed to protect enterprise data, control information access, and deliver transparent AI responses backed by trusted knowledge sources.',
		'trust_image'            => $rag_base . 'rag-trust.png',
		'trust_principles_label' => '',
		'trust_cta_label'        => 'Talk to Our RAG Experts',
		'trust_cta_url'          => '#consultation',
		'faqs_eyebrow'           => 'FAQs',
		'faqs_title'             => 'Frequently Asked Questions About RAG Development Services',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$principles = array(
		array( 'Secure Knowledge Access', 'shield-check.png' ),
		array( 'Data Privacy & Governance', 'hard-drives.png' ),
		array( 'Role-Based Access Control', 'user-switch.png' ),
	);
	$rows = array();
	foreach ( $principles as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $icon_base . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'trust_principles' ), $rows );

	$faqs = array(
		array( 'How can RAG improve the accuracy of our existing AI applications?', 'RAG grounds Large Language Model responses in relevant information retrieved from your trusted data sources. This helps AI applications provide more context-aware, business-specific responses while reducing reliance on the model’s pre-trained knowledge alone.' ),
		array( 'What types of enterprise data can be connected to a RAG system?', 'RAG solutions can retrieve knowledge from documents, PDFs, databases, APIs, knowledge bases, cloud repositories, product documentation, policies, and other structured or unstructured enterprise data sources.' ),
		array( 'Can AiBridze build a RAG solution around our private enterprise data?', 'Yes. We develop custom RAG solutions around proprietary business knowledge while considering data access, security, retrieval architecture, and enterprise integration requirements. The solution can be tailored to your existing data sources and technology environment.' ),
		array( 'How do you improve retrieval accuracy in a RAG system?', 'Retrieval quality can be improved through techniques such as intelligent chunking, embedding optimization, semantic and hybrid search, metadata filtering, query processing, and reranking. We select and optimize retrieval techniques based on the characteristics of your data and use case.' ),
		array( 'Can RAG work with frequently changing business information?', 'Yes. One of RAG’s key advantages is the ability to retrieve information from external knowledge sources. As connected knowledge is updated and re-indexed appropriately, AI applications can access newer business information without requiring the underlying LLM to be retrained for every knowledge change.' ),
		array( 'How do you evaluate the performance of a RAG solution?', 'We evaluate both <strong>retrieval and generation quality</strong>, including whether the right information is retrieved, whether responses are grounded in that information, response relevance, latency, and performance across representative real-world queries.' ),
		array( 'Do we need to fine-tune an LLM to build a custom RAG solution?', 'Not necessarily. RAG and fine-tuning solve different problems. If the primary requirement is giving an LLM access to proprietary or frequently changing knowledge, RAG may be sufficient. Fine-tuning can be considered separately when model behavior or task-specific performance needs further customization.' ),
		array( 'Why choose AiBridze as your RAG development company?', 'AiBridze combines RAG engineering, LLM integration, vector search, retrieval optimization, enterprise integration, and custom AI development expertise to build solutions around real business knowledge. We focus on developing scalable RAG systems that improve knowledge accessibility, response relevance, and enterprise AI reliability.' ),
	);
	$rows = array();
	foreach ( $faqs as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs' ), $rows );
	update_option( 'aibridze_rag_trust_faqs_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_rag_trust_faqs', 88 );

/** Create the editable Voice AI Development Services page and its opening sections. */
function aibridze_seed_voice_ai_development_service(): void {
	if ( get_option( 'aibridze_voice_ai_development_service_v1' ) ) return;
	$service = get_page_by_path( 'voice-ai-development', OBJECT, 'service' );
	if ( ! $service ) {
		$service_id = wp_insert_post( array( 'post_type' => 'service', 'post_status' => 'publish', 'post_title' => 'Voice AI Development Services', 'post_name' => 'voice-ai-development' ) );
		if ( is_wp_error( $service_id ) || ! $service_id ) return;
		$service = get_post( $service_id );
	}
	$base = get_theme_file_uri( '/assets/images/services/voice-ai/' );
	$values = array(
		'hero_title'           => 'Voice AI Development Services',
		'hero_description'     => 'Transform customer and employee interactions with intelligent Voice AI solutions that understand speech, interpret intent, and respond naturally in real time. At AiBridze, we develop custom Voice AI applications, AI voice assistants, and enterprise voice automation solutions that create seamless, multilingual, and human-like communication experiences.',
		'hero_image'           => $base . 'voice-hero.png',
		'hero_mobile_image'    => $base . 'voice-hero.png',
		'hero_cta_label'       => 'Schedule a Voice AI Strategy Call',
		'hero_cta_url'         => '#consultation',
		'intro_eyebrow'        => 'Voice AI Technology',
		'intro_title'          => 'How Voice AI Understands, Thinks, and Responds',
		'intro_description'    => 'Voice AI combines speech recognition, natural language understanding, and speech synthesis to enable intelligent voice interactions. Instead of relying on predefined commands, Voice AI understands spoken language, interprets user intent, and generates natural, human-like responses in real time.<h3>How Voice AI Works?</h3><div class="voice-how"><article><strong>1. Speech Recognition</strong><span>Convert spoken language into text using advanced Automatic Speech Recognition (ASR).</span></article><article><strong>2. Language Understanding</strong><span>AI understands intent, context, and conversation history before deciding the best response.</span></article><article><strong>3. Voice Generation</strong><span>Generate expressive and human-like speech using advanced neural Text-to-Speech technologies.</span></article></div>',
		'intro_image'          => $base . 'voice-intro.png',
		'benefits_eyebrow'     => 'Business Value of Voice AI',
		'benefits_title'       => 'Why Businesses Are Adopting Voice AI Solutions',
		'benefits_description' => 'Voice AI enables organizations to automate conversations, improve customer experiences, and deliver intelligent voice interactions at scale while reducing operational effort.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$benefits = array(
		array( '24/7 Voice Assistance', 'Provide instant voice support anytime without increasing operational costs.', 'benefit-assistance.png' ),
		array( 'Human-Like Conversations', 'Deliver natural, context-aware interactions that improve customer engagement.', 'benefit-human.png' ),
		array( 'Faster Response Times', 'Reduce waiting times with real-time conversational experiences.', 'benefit-response.png' ),
		array( 'Multilingual Voice Experiences', 'Communicate naturally across multiple languages and regions.', 'benefit-multilingual.png' ),
		array( 'Voice Workflow Automation', 'Automate repetitive voice interactions and operational tasks.', 'benefit-automation.png' ),
	);
	$rows = array();
	foreach ( $benefits as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), $rows );
	update_post_meta( $service->ID, '_aibridze_service_layout', 'voice' );
	update_option( 'aibridze_voice_ai_development_service_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_voice_ai_development_service', 89 );

/** Add the editable Voice AI technology stack and conversion banner. */
function aibridze_seed_voice_ai_technology_cta(): void {
	if ( get_option( 'aibridze_voice_ai_technology_cta_v1' ) ) return;
	$service = get_page_by_path( 'voice-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$voice = get_theme_file_uri( '/assets/images/services/voice-ai/' );
	$rag   = get_theme_file_uri( '/assets/images/services/rag/' );
	$values = array(
		'technology_eyebrow'     => 'Voice AI Technology Stack',
		'technology_title'       => 'Powering Intelligent Voice Experiences with Advanced AI Technologies',
		'technology_description' => 'We combine speech recognition, conversational AI, neural voice synthesis, enterprise integrations, and cloud infrastructure to build scalable Voice AI solutions.',
		'cta_position'           => 'after_technology',
		'cta_variant'            => 'voice-banner',
		'cta_title'              => 'Deliver Human-Like Voice Experiences At Enterprise Scale',
		'cta_description'        => 'Develop intelligent Voice AI solutions capable of understanding conversations, performing business actions, and delivering seamless customer experiences in real time.',
		'cta_image'              => $voice . 'voice-enterprise-agent.png',
		'cta_label'              => 'Talk to Our Voice AI Experts',
		'cta_url'                => '#consultation',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	$groups = array(
		'AI Models' => array( array( 'OpenAI', $rag . 'openai.png' ), array( 'Claude', $rag . 'claude.png' ), array( 'Gemini', $rag . 'gemini.png' ), array( 'Llama', $rag . 'llama.png' ) ),
		'Speech Recognition' => array( array( 'Deepgram', $voice . 'deepgram.png' ), array( 'Whisper', $voice . 'whisper.png' ), array( 'Google Speech-to-Text', $voice . 'google-speech.png' ), array( 'Azure Speech', $rag . 'azure.png' ) ),
		'Text-to-Speech' => array( array( 'ElevenLabs', $voice . 'elevenlabs.png' ), array( 'Azure Neural Voices', $rag . 'azure.png' ), array( 'Google Cloud TTS', $voice . 'google-cloud.png' ), array( 'Amazon Polly', $voice . 'amazon-polly.png' ) ),
		'Voice AI Frameworks' => array( array( 'LiveKit', $voice . 'livekit.png' ), array( 'Pipecat', $voice . 'pipecat.png' ), array( 'LangChain', $rag . 'langchain.png' ), array( 'LangGraph', $rag . 'langgraph.png' ) ),
		'Enterprise Integrations' => array( array( 'CRM', '' ), array( 'ERP', '' ), array( 'SIP', '' ), array( 'Telephony', '' ), array( 'APIs', '' ) ),
		'Cloud & Infrastructure' => array( array( 'AWS', $rag . 'aws.png' ), array( 'Azure', $rag . 'azure.png' ), array( 'Google Cloud', $voice . 'google-cloud.png' ), array( 'Kubernetes', $rag . 'kubernetes.png' ), array( 'Docker', $rag . 'docker.png' ) ),
	);
	$rows = array();
	foreach ( $groups as $group => $items ) foreach ( $items as $item ) $rows[] = array( 'group' => $group, 'title' => $item[0], 'description' => '', 'image' => $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology' ), $rows );
	update_option( 'aibridze_voice_ai_technology_cta_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_voice_ai_technology_cta', 90 );

/** Align the RAG technology matrix with the approved six-column design. */
function aibridze_refresh_rag_technology_matrix(): void {
	if ( get_option( 'aibridze_rag_technology_matrix_v2' ) ) return;
	$service = get_page_by_path( 'rag-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/rag/' );
	$groups = array(
		'Large Language Models' => array( array( 'OpenAI', 'openai.png' ), array( 'Claude', 'claude.png' ), array( 'Gemini', 'gemini.png' ), array( 'Llama', 'llama.png' ), array( 'Grok', 'grok.png' ), array( 'Kimi K3', 'kimi.png' ), array( 'Qwen', 'qwen.png' ) ),
		'RAG & AI Frameworks' => array( array( 'LangGraph', 'langgraph.png' ), array( 'LangChain', 'langchain.png' ), array( 'LlamaIndex', 'llamaindex.png' ), array( 'Haystack', 'haystack.png' ), array( 'Microsoft Agent Framework', 'microsoft-agent.png' ) ),
		'Vector Databases' => array( array( 'Pinecone', 'pinecone.png' ), array( 'Weaviate', 'weaviate.png' ), array( 'Qdrant', 'qdrant.png' ), array( 'pgvector', 'postgres.png' ), array( 'ChromaDB', 'chroma.png' ), array( 'Milvus', 'milvus.png' ) ),
		'Search & Retrieval' => array( array( 'Elasticsearch', 'python.png' ), array( 'OpenSearch', 'node.png' ), array( 'Hybrid Search', 'fastapi.png' ), array( 'Reranking', 'chroma.png' ) ),
		'Data & Knowledge Sources' => array( array( 'PostgreSQL', 'postgres-backend.png' ), array( 'SQL/NoSQL DB', 'mongodb.png' ), array( 'SharePoint', 'microsoft-agent.png' ), array( 'Documents', 'haystack.png' ), array( 'Enterprise Knowledge Bases', 'llamaindex.png' ) ),
		'Cloud & Infrastructure' => array( array( 'AWS', 'aws.png' ), array( 'Azure', 'azure.png' ), array( 'Google Cloud', 'google-cloud.png' ), array( 'Kubernetes', 'kubernetes.png' ), array( 'Docker', 'docker.png' ) ),
	);
	$rows = array();
	foreach ( $groups as $group => $items ) foreach ( $items as $item ) $rows[] = array( 'group' => $group, 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology' ), $rows );
	update_option( 'aibridze_rag_technology_matrix_v2', 1, false );
}
add_action( 'init', 'aibridze_refresh_rag_technology_matrix', 91 );

/** Add editable Voice AI expertise and application sections. */
function aibridze_seed_voice_ai_expertise_solutions(): void {
	if ( get_option( 'aibridze_voice_ai_expertise_solutions_v1' ) ) return;
	$service = get_page_by_path( 'voice-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/voice-ai/' );
	$values = array(
		'expertise_eyebrow' => 'Voice AI Development Expertise',
		'expertise_title' => 'Custom Voice AI Solutions Built for Intelligent, Real-Time Conversations',
		'expertise_description' => 'From AI voice agents and conversational voice assistants to enterprise call automation and multilingual voice experiences, we develop Voice AI solutions that understand natural speech, execute business actions, and create seamless voice interactions.',
		'solutions_eyebrow' => 'Voice AI Applications',
		'solutions_title' => 'Intelligent Voice AI Solutions for Modern Businesses',
		'solutions_description' => 'Voice AI is transforming customer engagement and business operations by enabling intelligent conversations that not only respond but also perform meaningful business actions.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	$expertise = array(
		array( 'AI Voice Agent Development', 'Develop intelligent AI voice agents capable of handling conversations, retrieving information, executing workflows, and assisting customers in real time.' ),
		array( 'AI Voice Assistant Development', 'Build intelligent voice assistants that answer questions, complete tasks, and deliver personalized voice experiences.' ),
		array( 'Conversational Voice AI Solutions', 'Create context-aware voice experiences powered by speech recognition, natural language understanding, and conversational intelligence.' ),
		array( 'Enterprise Voice AI Integration & Automation', 'Integrate Voice AI with CRMs, ERPs, telephony platforms, APIs, and enterprise systems to automate business processes.' ),
		array( 'Multilingual Voice AI Development', 'Deliver natural multilingual voice interactions for global businesses and diverse customer audiences.' ),
		array( 'Voice AI Strategy & Experience Design', 'Design conversation flows, voice personas, and interaction strategies that create intuitive and engaging voice experiences.' ),
	);
	$rows = array();
	foreach ( $expertise as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise' ), $rows );
	$solutions = array(
		array( 'AI Customer Support Voice Agents', 'Deliver 24/7 voice support that answers questions, resolves issues, and escalates conversations when required.', 'voice-intro.png' ),
		array( 'AI Sales & Lead Qualification', 'Qualify prospects, answer product questions, and automate sales conversations.', 'voice-enterprise-agent.png' ),
		array( 'Smart Voice IVR Systems', 'Replace traditional IVR menus with conversational Voice AI experiences.', 'voice-hero.png' ),
		array( 'Appointment & Booking Assistants', 'Automate scheduling, confirmations, reminders, and cancellations through voice.', 'voice-intro.png' ),
		array( 'Enterprise Knowledge Voice Assistants', 'Help employees retrieve policies, SOPs, and enterprise knowledge using natural voice conversations.', 'voice-enterprise-agent.png' ),
		array( 'Voice AI Workflow Automation', 'Execute business actions such as updating CRM records, retrieving customer data, and triggering workflows.', 'voice-hero.png' ),
		array( 'AI Contact Center Automation', 'Improve contact center productivity with intelligent call routing, summaries, and real-time assistance.', 'voice-intro.png' ),
		array( 'AI Voice Concierge', 'Deliver personalized voice experiences across healthcare, hospitality, retail, and enterprise environments.', 'voice-enterprise-agent.png' ),
	);
	$rows = array();
	foreach ( $solutions as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), $rows );
	update_option( 'aibridze_voice_ai_expertise_solutions_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_voice_ai_expertise_solutions', 92 );

/** Add the editable Voice AI development process carousel. */
function aibridze_seed_voice_ai_process(): void {
	if ( get_option( 'aibridze_voice_ai_process_v1' ) ) return;
	$service = get_page_by_path( 'voice-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$values = array(
		'process_eyebrow' => 'Our Voice AI Development Process',
		'process_title' => 'From Voice Strategy to Intelligent Enterprise Conversations',
		'process_description' => 'Our Voice AI development process combines conversational design, AI engineering, speech technologies, and enterprise integrations to build scalable voice solutions that deliver natural interactions and measurable business outcomes.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	$steps = array(
		array( 'Discovery & Voice Strategy', 'We begin by understanding your business objectives, customer journeys, voice interaction requirements, and operational workflows. This helps us identify high-impact use cases and define a Voice AI strategy aligned with your goals.' ),
		array( 'Conversation & Voice Experience Design', 'Our team designs conversation flows, voice personas, dialogue logic, fallback scenarios, and multilingual experiences to create intuitive, human-like interactions. Every conversation is optimized for clarity, engagement, and task completion.' ),
		array( 'Voice AI Development & Integration', 'We develop custom Voice AI solutions using advanced speech recognition, natural language understanding, and voice synthesis technologies. The solution is seamlessly integrated with CRMs, ERPs, telephony systems, APIs, and knowledge bases to enable intelligent automation.' ),
		array( 'Testing & Quality Assurance', 'We rigorously test speech recognition accuracy, conversation flows, latency, voice quality, multilingual support, and system integrations. User interactions are analyzed and optimized to ensure reliable performance across real-world scenarios.' ),
		array( 'Deployment & Monitoring', 'After deployment, we continuously monitor conversation quality, user engagement, and system performance. As business requirements evolve, we refine dialogue flows, optimize AI models, and introduce new capabilities to keep your Voice AI solution accurate, scalable, and effective.' ),
	);
	$rows = array();
	foreach ( $steps as $step ) $rows[] = array( 'group' => '', 'title' => $step[0], 'description' => $step[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process' ), $rows );
	update_option( 'aibridze_voice_ai_process_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_voice_ai_process', 93 );

/** Add editable Voice AI industries and FAQs. */
function aibridze_seed_voice_ai_industries_faqs(): void {
	if ( get_option( 'aibridze_voice_ai_industries_faqs_v1' ) ) return;
	$service = get_page_by_path( 'voice-ai-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$icons = get_theme_file_uri( '/assets/images/services/rag/' );
	$values = array(
		'industries_eyebrow' => 'Voice AI Across Industries',
		'industries_title' => 'Delivering Intelligent Voice Experiences Across Industries',
		'industries_description' => 'We build custom Voice AI solutions that enable businesses to automate conversations, improve customer engagement, and streamline voice-driven operations across a wide range of industries.',
		'faqs_eyebrow' => 'FAQs',
		'faqs_title' => 'Frequently Asked Questions About Voice AI Development Services',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	$industries = array(
		array( 'Healthcare', 'industry-healthcare.png' ), array( 'E-Commerce', 'industry-ecommerce.png' ), array( 'EdTech', 'industry-edtech.png' ), array( 'Travel', 'industry-travel.png' ), array( 'Real Estate', 'industry-real-estate.png' ), array( 'Telecom & Media', 'industry-telecom.png' ),
		array( 'Manufacturing', 'industry-manufacturing.png' ), array( 'Automotive', 'industry-automotive.png' ), array( 'Agriculture', 'industry-agriculture.png' ), array( 'Energy', 'industry-energy.png' ), array( 'Food & Beverage', 'industry-food.png' ), array( 'Entertainment', 'industry-entertainment.png' ),
		array( 'Legal', 'industry-legal.png' ), array( 'Non-Profits', 'industry-nonprofits.png' ), array( 'HR & Enterprise', 'industry-hr.png' ), array( 'Government', 'industry-government.png' ), array( 'Bank & Finance', 'industry-finance.png' ), array( 'Logistics', 'industry-logistics.png' ),
	);
	$rows = array();
	foreach ( $industries as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $icons . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries' ), $rows );
	$faqs = array(
		array( 'What is Voice AI, and how can it benefit my business?', 'Voice AI enables businesses to automate voice conversations using speech recognition, natural language understanding, and AI-powered responses. It helps improve customer support, automate routine calls, reduce operational costs, and deliver faster, more personalized customer experiences.' ),
		array( 'How is Voice AI different from traditional IVR systems?', 'Unlike traditional IVR systems that rely on fixed menus and keypad inputs, Voice AI understands natural speech, interprets user intent, maintains conversational context, and performs business actions such as booking appointments, retrieving information, or updating records.' ),
		array( 'Can Voice AI integrate with our existing business systems?', 'Yes. Voice AI solutions can integrate with CRMs, ERPs, telephony platforms, APIs, knowledge bases, scheduling systems, payment gateways, and other enterprise applications to automate workflows and access real-time business data.' ),
		array( 'What business processes can Voice AI automate?', 'Voice AI can automate customer support, appointment scheduling, lead qualification, order tracking, call routing, FAQs, employee assistance, surveys, and many other repetitive voice-based workflows while seamlessly escalating complex conversations to human agents.' ),
		array( 'Can Voice AI support multiple languages and accents?', 'Yes. Modern Voice AI solutions can understand different languages, regional accents, and speech patterns, enabling businesses to provide consistent multilingual customer experiences across global markets.' ),
		array( 'How do you ensure Voice AI delivers natural and accurate conversations?', 'We combine advanced speech recognition, natural language understanding, conversation design, and AI model optimization to create human-like voice interactions. Every solution is tested and refined to improve accuracy, response quality, and overall user experience.' ),
		array( 'What industries can benefit from Voice AI solutions?', 'Voice AI is widely used across healthcare, banking, insurance, retail, logistics, hospitality, real estate, education, SaaS, and customer service operations where real-time voice communication improves efficiency and customer engagement.' ),
		array( 'Why choose AiBridze for Voice AI development?', 'AiBridze develops custom Voice AI solutions tailored to your business goals. From AI voice agents and conversational voice assistants to enterprise integrations and voice automation, we build scalable solutions that deliver intelligent, real-time voice experiences.' ),
	);
	$rows = array();
	foreach ( $faqs as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs' ), $rows );
	update_option( 'aibridze_voice_ai_industries_faqs_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_voice_ai_industries_faqs', 94 );

/** Populate the Computer Vision technology, CTA, and expertise editor fields. */
function aibridze_seed_computer_vision_technology_expertise(): void {
	if ( get_option( 'aibridze_computer_vision_technology_expertise_v1' ) ) return;
	$service = get_page_by_path( 'computer-vision-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$vision = get_theme_file_uri( '/assets/images/services/computer-vision/' );
	$rag    = get_theme_file_uri( '/assets/images/services/rag/' );
	$values = array(
		'technology_eyebrow'       => 'Computer Vision Technology Stack',
		'technology_title'         => 'Powering Computer Vision With Advanced AI Technologies',
		'technology_description'   => 'We combine computer vision frameworks, deep learning models, image processing libraries, cloud infrastructure, and edge computing technologies to build scalable visual intelligence solutions.',
		'cta_position'             => 'after_technology',
		'cta_variant'              => 'vision-banner',
		'cta_title'                => 'Turn Every Image And Video Into Actionable Intelligence',
		'cta_description'          => 'Build custom computer vision solutions that detect, analyze, and understand visual data to automate processes and improve business decisions.',
		'cta_image'                => $vision . 'vision-cta-person.png',
		'cta_label'                => 'Start Your Computer Vision Project',
		'cta_url'                  => '#consultation',
		'expertise_eyebrow'        => 'Computer Vision Development Expertise',
		'expertise_title'          => 'Custom Computer Vision Solutions for Real-World Business Challenges',
		'expertise_description'    => 'From image recognition and object detection to video analytics and intelligent document processing, we develop computer vision solutions tailored to your data, workflows, and business objectives.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$groups = array(
		'Computer Vision Frameworks' => array( array( 'OpenCV', $vision . 'opencv-logo.png' ), array( 'TensorFlow', $vision . 'tensorflow.png' ), array( 'PyTorch', $vision . 'pytorch.png' ), array( 'YOLO', $vision . 'yolo.png' ), array( 'Detectron2', $vision . 'detectron2.png' ) ),
		'AI & Deep Learning' => array( array( 'CNNs', '' ), array( 'Vision Transformers', '' ), array( 'Deep Learning', '' ), array( 'Machine Learning', '' ), array( 'Image Classification', '' ) ),
		'Image & Video Processing' => array( array( 'OpenCV', $vision . 'opencv-logo.png' ), array( 'FFmpeg', $vision . 'ffmpeg.png' ), array( 'Image Processing APIs', '' ), array( 'Video Analytics', '' ) ),
		'OCR & Document Intelligence' => array( array( 'Tesseract', $vision . 'tesseract.png' ), array( 'PaddleOCR', $vision . 'paddleocr.png' ), array( 'Cloud Vision APIs', '' ), array( 'Document AI', '' ) ),
		'Cloud & Infrastructure' => array( array( 'AWS', $rag . 'aws.png' ), array( 'Microsoft Azure', $rag . 'azure.png' ), array( 'Google Cloud', $rag . 'google-cloud.png' ), array( 'Docker', $rag . 'docker.png' ), array( 'Kubernetes', $rag . 'kubernetes.png' ) ),
		'Edge & Real-Time Computing' => array( array( 'NVIDIA', $vision . 'nvidia.png' ), array( 'CUDA', $vision . 'cuda.png' ), array( 'TensorRT', $vision . 'tensorrt.png' ), array( 'Edge AI', '' ), array( 'IoT Devices', '' ) ),
	);
	$rows = array();
	foreach ( $groups as $group => $items ) foreach ( $items as $item ) $rows[] = array( 'group' => $group, 'title' => $item[0], 'description' => '', 'image' => $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'technology' ), $rows );

	$expertise = array(
		array( 'Image Recognition & Classification', 'Build AI systems that identify, categorize, and analyze objects, products, scenes, and visual patterns.' ),
		array( 'Object Detection & Tracking', 'Detect and track objects in images and video with AI models designed for accurate real-time analysis.' ),
		array( 'Video Analytics', 'Analyze live or recorded video to identify events, behaviors, movements, and anomalies.' ),
		array( 'OCR & Intelligent Document Processing', 'Extract text and structured information from documents, forms, receipts, invoices, and other visual content.' ),
		array( 'Visual Inspection & Quality Control', 'Automate inspection processes by identifying defects, inconsistencies, and quality issues through visual analysis.' ),
		array( 'Custom Computer Vision Model Development', 'Design, train, fine-tune, and deploy custom computer vision models based on your specific datasets and business requirements.' ),
	);
	$rows = array();
	foreach ( $expertise as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'expertise' ), $rows );
	update_option( 'aibridze_computer_vision_technology_expertise_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_computer_vision_technology_expertise', 96 );

/** Populate the editable Computer Vision business-solutions carousel. */
function aibridze_seed_computer_vision_solutions(): void {
	if ( get_option( 'aibridze_computer_vision_solutions_v1' ) ) return;
	$service = get_page_by_path( 'computer-vision-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/computer-vision/' );
	$values = array(
		'solutions_eyebrow'     => 'Computer Vision Solutions We Build',
		'solutions_title'       => 'Intelligent Vision Solutions for Modern Business Operations',
		'solutions_description' => 'Our computer vision development expertise can power applications across inspection, security, document processing, retail, logistics, healthcare, and other visual-data-intensive workflows.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	$items = array(
		array( 'Smart Visual Inspection', 'Automatically identify product defects, quality issues, and production inconsistencies.', 'solution-inspection.png' ),
		array( 'AI-Powered Video Analytics', 'Extract real-time insights from video streams to monitor activities, environments, and operational events.', 'solution-video.png' ),
		array( 'Intelligent Retail Vision', 'Enable product recognition, visual search, shelf monitoring, inventory tracking, and customer behavior analysis.', 'solution-retail.png' ),
		array( 'AI Document & Image Processing', 'Extract and structure information from invoices, forms, receipts, IDs, and other visual documents.', 'solution-document.png' ),
		array( 'Safety & Compliance Monitoring', 'Detect safety risks, restricted activities, PPE compliance, and operational anomalies through visual analysis.', 'solution-safety.png' ),
		array( 'Healthcare Image Analysis', 'Support medical image analysis and visual data processing for healthcare applications where appropriate.', 'solution-inspection.png' ),
		array( 'Smart Surveillance & Monitoring', 'Identify objects, events, and unusual activities across cameras and video feeds.', 'solution-video.png' ),
		array( 'Logistics & Warehouse Vision', 'Improve inventory visibility, package tracking, automated sorting, and warehouse operations through visual intelligence.', 'solution-retail.png' ),
	);
	$rows = array();
	foreach ( $items as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $base . $item[2], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'solutions' ), $rows );
	update_option( 'aibridze_computer_vision_solutions_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_computer_vision_solutions', 97 );

/** Populate the editable Computer Vision development process timeline. */
function aibridze_seed_computer_vision_process(): void {
	if ( get_option( 'aibridze_computer_vision_process_v1' ) ) return;
	$service = get_page_by_path( 'computer-vision-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$values = array(
		'process_eyebrow'     => 'Our Computer Vision Development Process',
		'process_title'       => 'From Visual Data to Production-Ready AI Vision Systems',
		'process_description' => 'Our computer vision development process combines data preparation, model engineering, application development, testing, and deployment to build reliable visual intelligence solutions.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );
	$steps = array(
		array( 'Discovery & Use Case Definition', 'We understand your business objectives, visual data, operational environment, and target outcomes to define the right computer vision approach.' ),
		array( 'Data Collection & Preparation', 'We collect, clean, annotate, augment, and preprocess visual datasets to create high-quality training and validation data.' ),
		array( 'Model Development & Training', 'We select suitable computer vision architectures, train models, and optimize them for accuracy, speed, and your specific business requirements.' ),
		array( 'Application Development & Integration', 'We integrate trained models into your applications, APIs, cameras, cloud platforms, edge devices, or existing enterprise systems.' ),
		array( 'Testing & Model Optimization', 'We evaluate model accuracy, precision, recall, latency, and real-world performance, then fine-tune the solution based on test results.' ),
		array( 'Deployment & Continuous Improvement', 'We deploy the solution to your preferred environment, monitor performance, and continuously improve models as new visual data and requirements emerge.' ),
	);
	$rows = array();
	foreach ( $steps as $step ) $rows[] = array( 'group' => '', 'title' => $step[0], 'description' => $step[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'process' ), $rows );
	update_option( 'aibridze_computer_vision_process_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_computer_vision_process', 98 );

/** Populate the editable Computer Vision capabilities carousel. */
function aibridze_seed_computer_vision_capabilities(): void {
	if ( get_option( 'aibridze_computer_vision_capabilities_v1' ) ) return;
	$service = get_page_by_path( 'computer-vision-development', OBJECT, 'service' );
	if ( ! $service ) return;

	$values = array(
		'capabilities_eyebrow'     => 'Advanced Computer Vision Capabilities',
		'capabilities_title'       => 'Advanced Vision Intelligence Built for Accuracy and Scale',
		'capabilities_description' => 'We combine modern computer vision techniques with optimized AI models to help systems interpret complex visual environments and deliver reliable insights.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$items = array(
		array( 'Object Detection & Recognition', 'Identify and classify objects across images and video with trained AI models.' ),
		array( 'Image Segmentation', 'Separate objects and regions within an image for detailed visual analysis.' ),
		array( 'Facial Analysis', 'Build appropriate facial analysis solutions for identity verification, access control, and other approved use cases.' ),
		array( 'Optical Character Recognition', 'Convert text within images and documents into machine-readable information.' ),
		array( 'Anomaly Detection', 'Identify unusual visual patterns, defects, or events that require attention.' ),
		array( 'Pose & Activity Detection', 'Analyze human movement, posture, and activity for relevant operational applications.' ),
		array( 'Real-Time Video Analytics', 'Process live video streams for immediate detection, monitoring, and decision-making.' ),
		array( 'Edge Computer Vision', 'Deploy optimized vision models closer to cameras and devices for lower-latency processing and real-time applications.' ),
	);
	$rows = array();
	foreach ( $items as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'capabilities' ), $rows );
	update_option( 'aibridze_computer_vision_capabilities_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_computer_vision_capabilities', 99 );

/** Populate the editable Computer Vision industries, final banner, and FAQs. */
function aibridze_seed_computer_vision_completion_sections(): void {
	if ( get_option( 'aibridze_computer_vision_completion_sections_v1' ) ) return;
	$service = get_page_by_path( 'computer-vision-development', OBJECT, 'service' );
	if ( ! $service ) return;
	$base = get_theme_file_uri( '/assets/images/services/computer-vision/' );
	$values = array(
		'industries_eyebrow'       => 'Computer Vision Across Industries',
		'industries_title'         => 'Transforming Industries With Visual Intelligence',
		'industries_description'   => 'We build industry-specific computer vision solutions that turn visual data into actionable insights, helping organizations automate operations, improve accuracy, and make faster decisions.',
		'action_cta_title'          => 'Build Computer Vision Solutions That See More, Do More',
		'action_cta_description'    => 'Turn images, documents, and video into intelligent business actions with custom computer vision software built around your data, workflows, and operational goals.',
		'action_cta_image'          => $base . 'vision-final-cta.png',
		'action_cta_label'          => 'Talk to Our Computer Vision Experts',
		'action_cta_url'            => '#consultation',
		'faqs_eyebrow'              => 'FAQs',
		'faqs_title'                => 'Frequently Asked Questions About Computer Vision Development',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$industries = array(
		array( 'Healthcare', 'industry-healthcare.png' ), array( 'E-Commerce', 'industry-ecommerce.png' ), array( 'EdTech', 'industry-edtech.png' ),
		array( 'Travel', 'industry-travel.png' ), array( 'Real Estate', 'industry-real-estate.png' ), array( 'Telecom & Media', 'industry-telecom.png' ),
		array( 'Manufacturing', 'industry-manufacturing.png' ), array( 'Automotive', 'industry-automotive.png' ), array( 'Agriculture', 'industry-agriculture.png' ),
		array( 'Energy', 'industry-energy.png' ), array( 'Food & Beverage', 'industry-food.png' ), array( 'Entertainment', 'industry-entertainment.png' ),
		array( 'Legal', 'industry-legal.png' ), array( 'Non-Profits', 'industry-nonprofits.png' ), array( 'HR & Enterprise', 'industry-enterprise.png' ),
		array( 'Government', 'industry-government.png' ), array( 'Bank & Finance', 'industry-finance.png' ), array( 'Logistics', 'industry-logistics.png' ),
	);
	$rows = array();
	foreach ( $industries as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => '', 'image' => $base . $item[1], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'industries' ), $rows );

	$faqs = array(
		array( 'What is computer vision and how can it help my business?', 'Computer vision enables AI systems to interpret images and video to identify objects, recognize patterns, extract information, and automate visual tasks. Businesses can use it for inspection, monitoring, document processing, analytics, and other visual workflows.' ),
		array( 'What types of computer vision solutions can AiBridze develop?', 'We develop custom solutions for object detection, image recognition, video analytics, OCR, visual inspection, anomaly detection, image classification, and other business-specific computer vision applications.' ),
		array( 'Can computer vision work with our existing cameras, applications, or enterprise systems?', 'Yes. Computer vision models can be integrated with cameras, mobile and web applications, APIs, cloud platforms, IoT devices, and enterprise systems depending on the project requirements.' ),
		array( 'What data is required to train a custom computer vision model?', 'The requirements depend on the use case, model, and desired accuracy. Visual datasets may need to be collected, cleaned, labeled, annotated, and augmented before they can be used for training and validation.' ),
		array( 'How accurate are computer vision models?', 'Accuracy depends on factors such as dataset quality, use case complexity, model architecture, environmental conditions, and evaluation criteria. We use testing, validation, and model optimization to improve performance for the intended environment.' ),
		array( 'Can computer vision process video in real time?', 'Yes. Computer vision systems can analyze live video streams for applications such as object tracking, safety monitoring, surveillance, quality inspection, and event detection. Architecture and hardware choices determine achievable latency and throughput.' ),
		array( 'Can computer vision models run on edge devices?', 'Yes. Models can be optimized and deployed on edge devices when low latency, local processing, or limited connectivity is important. Edge deployment is particularly useful for real-time visual applications.' ),
		array( 'Why choose AiBridze for computer vision development?', 'AiBridze develops custom computer vision solutions around your business requirements, visual data, and deployment environment. Our expertise spans computer vision development, AI/ML engineering, model optimization, enterprise integration, and production deployment.' ),
	);
	$rows = array();
	foreach ( $faqs as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => '', 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'faqs' ), $rows );
	update_option( 'aibridze_computer_vision_completion_sections_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_computer_vision_completion_sections', 100 );

/** Retire the obsolete Voice AI placeholder; Voice AI Development is canonical. */
function aibridze_retire_legacy_voice_ai_service(): void {
	if ( get_option( 'aibridze_retired_legacy_voice_ai_v1' ) ) return;
	$legacy = get_page_by_path( 'voice-ai', OBJECT, 'service' );
	if ( $legacy && 'trash' !== $legacy->post_status ) wp_trash_post( $legacy->ID );
	update_option( 'aibridze_retired_legacy_voice_ai_v1', 1, false );
}
add_action( 'init', 'aibridze_retire_legacy_voice_ai_service', 101 );

/** Keep the canonical Voice AI service label and requested development URL. */
function aibridze_update_voice_ai_service_identity(): void {
	if ( get_option( 'aibridze_voice_ai_service_identity_v1' ) ) return;
	$service = get_page_by_path( 'voice-ai-development', OBJECT, 'service' );
	if ( $service ) {
		wp_update_post( array(
			'ID'         => $service->ID,
			'post_title' => 'Voice Ai',
			'post_name'  => 'voice-ai-development',
		) );
	}
	update_option( 'aibridze_voice_ai_service_identity_v1', 1, false );
}
add_action( 'init', 'aibridze_update_voice_ai_service_identity', 102 );

/** Place the canonical Voice Ai service in the AI Development category. */
function aibridze_assign_voice_ai_service_category(): void {
	if ( get_option( 'aibridze_voice_ai_service_category_v1' ) ) return;
	$service = get_page_by_path( 'voice-ai-development', OBJECT, 'service' );
	$category = get_term_by( 'name', 'AI Development', 'service_category' );
	if ( $service && $category ) wp_set_object_terms( $service->ID, array( (int) $category->term_id ), 'service_category', false );
	update_option( 'aibridze_voice_ai_service_category_v1', 1, false );
}
add_action( 'init', 'aibridze_assign_voice_ai_service_category', 103 );

/** Preserve the intended AI Development card sequence after adding Voice Ai. */
function aibridze_order_voice_ai_service(): void {
	if ( get_option( 'aibridze_voice_ai_service_order_v1' ) ) return;
	$service = get_page_by_path( 'voice-ai-development', OBJECT, 'service' );
	if ( $service ) {
		wp_update_post( array(
			'ID'         => $service->ID,
			'menu_order' => 5,
		) );
	}
	update_option( 'aibridze_voice_ai_service_order_v1', 1, false );
}
add_action( 'init', 'aibridze_order_voice_ai_service', 104 );

/** Create and populate the Computer Vision Development service opening sections. */
function aibridze_seed_computer_vision_development(): void {
	if ( get_option( 'aibridze_computer_vision_development_v1' ) ) return;

	$service = get_page_by_path( 'computer-vision-development', OBJECT, 'service' );
	if ( ! $service ) $service = get_page_by_path( 'computer-vision', OBJECT, 'service' );
	if ( ! $service ) {
		$service_id = wp_insert_post( array(
			'post_type' => 'service', 'post_status' => 'publish', 'post_title' => 'Computer Vision',
			'post_name' => 'computer-vision-development', 'post_excerpt' => 'Custom computer vision development services.',
		) );
		if ( is_wp_error( $service_id ) || ! $service_id ) return;
		$service = get_post( $service_id );
		$category = get_term_by( 'name', 'AI Development', 'service_category' );
		if ( $category ) wp_set_object_terms( $service_id, (int) $category->term_id, 'service_category' );
	} else {
		wp_update_post( array( 'ID' => $service->ID, 'post_title' => 'Computer Vision', 'post_name' => 'computer-vision-development', 'post_status' => 'publish' ) );
	}

	$base = get_theme_file_uri( '/assets/images/services/computer-vision/' );
	$values = array(
		'hero_title' => 'Computer Vision Development Services',
		'hero_description' => 'Turn images and video into actionable intelligence with custom computer vision solutions. AiBridze develops AI-powered vision systems for object detection, image analysis, OCR, video analytics, visual inspection, and real-time decision-making across industries.',
		'hero_image' => $base . 'computer-vision-hero.png',
		'hero_mobile_image' => $base . 'computer-vision-hero.png',
		'hero_cta_label' => 'Talk to Our Computer Vision Experts',
		'hero_cta_url' => '#consultation',
		'intro_eyebrow' => 'What Is Computer Vision?',
		'intro_title' => 'How Computer Vision Turns Visual Data Into Intelligence',
		'intro_description' => 'Computer vision enables AI systems to interpret images, videos, and visual environments much like humans do. By combining machine learning, deep learning, and image processing, computer vision solutions can identify objects, recognize patterns, analyze scenes, and generate actionable insights from visual data.',
		'intro_image' => $base . 'computer-vision-intro.png',
		'benefits_eyebrow' => 'Why Computer Vision?',
		'benefits_title' => 'Turn Visual Data Into Smarter Business Decisions',
		'benefits_description' => 'Computer vision helps businesses automate visual tasks, improve operational accuracy, and extract insights from images and video at scale.',
	);
	foreach ( $values as $key => $value ) update_post_meta( $service->ID, aibridze_service_meta_key( $key ), $value );

	$benefits = array(
		array( 'Automate Visual Inspection', 'Detect defects, anomalies, and quality issues with AI-powered visual inspection.', '' ),
		array( 'Improve Operational Accuracy', 'Reduce manual errors by using AI to analyze visual information consistently.', '' ),
		array( 'Real-Time Monitoring', 'Analyze live video streams to detect events, objects, activities, and anomalies as they happen.', $base . 'computer-vision-eye.png' ),
		array( 'Extract Hidden Insights', 'Turn large volumes of images and video into structured, actionable business intelligence.', '' ),
		array( 'Accelerate Decision-Making', 'Give teams faster access to visual insights for operational and strategic decisions.', '' ),
	);
	$rows = array();
	foreach ( $benefits as $item ) $rows[] = array( 'group' => '', 'title' => $item[0], 'description' => $item[1], 'image' => $item[2], 'link_label' => '', 'link_url' => '' );
	update_post_meta( $service->ID, aibridze_service_meta_key( 'benefits' ), $rows );
	update_post_meta( $service->ID, '_aibridze_service_layout', 'vision' );
	update_option( 'aibridze_computer_vision_development_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_computer_vision_development', 95 );
