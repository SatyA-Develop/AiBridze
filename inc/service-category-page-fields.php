<?php
/** Editor-managed service-category landing pages. */

if ( ! defined( 'ABSPATH' ) ) exit;

function aibridze_category_page_groups(): array {
	return array(
		'hero' => array( 'label' => '1. Hero', 'fields' => array(
			'hero_eyebrow' => array( 'Eyebrow', 'text' ), 'hero_title' => array( 'Heading', 'text' ),
			'hero_description' => array( 'Description', 'textarea' ), 'hero_image' => array( 'Desktop background image', 'url' ),
			'hero_mobile_image' => array( 'Mobile background image', 'url' ), 'hero_cta_label' => array( 'Button label', 'text' ),
			'hero_cta_url' => array( 'Button URL', 'url' ),
		) ),
		'ecosystem' => array( 'label' => '2. Category overview / ecosystem', 'fields' => array(
			'ecosystem_eyebrow' => array( 'Eyebrow', 'text' ), 'ecosystem_title' => array( 'Heading', 'text' ),
			'ecosystem_description' => array( 'Description', 'textarea' ), 'ecosystem_image' => array( 'Feature image', 'url' ),
			'ecosystem_items' => array( 'Benefit tiles', 'rows', 'Title | Description | Icon URL' ),
		) ),
		'services' => array( 'label' => '3. Services carousel (cards are automatic)', 'fields' => array(
			'services_eyebrow' => array( 'Eyebrow', 'text' ), 'services_title' => array( 'Heading', 'text' ),
			'services_description' => array( 'Description', 'textarea' ),
		) ),
		'value' => array( 'label' => '4. Business value', 'fields' => array(
			'value_eyebrow' => array( 'Eyebrow', 'text' ), 'value_title' => array( 'Heading', 'text' ),
			'value_description' => array( 'Description', 'textarea' ),
			'value_image' => array( 'Section image', 'url' ),
			'value_items' => array( 'Value cards', 'rows', 'Title | Description | Tags (comma separated)' ),
		) ),
		'solutions' => array( 'label' => '5. Solutions accordion', 'fields' => array(
			'solutions_eyebrow' => array( 'Eyebrow', 'text' ), 'solutions_title' => array( 'Heading', 'text' ),
			'solutions_description' => array( 'Description', 'textarea' ),
			'solutions_items' => array( 'Accordion items', 'rows', 'Title | Description' ),
		) ),
		'technology' => array( 'label' => '6. Technology ecosystem', 'fields' => array(
			'technology_eyebrow' => array( 'Eyebrow', 'text' ), 'technology_title' => array( 'Heading', 'text' ),
			'technology_description' => array( 'Description', 'textarea' ),
			'technology_items' => array( 'Technology logos', 'rows', 'Name | Short group label | Logo URL' ),
		) ),
		'process' => array( 'label' => '7. Development approach', 'fields' => array(
			'process_eyebrow' => array( 'Eyebrow', 'text' ), 'process_title' => array( 'Heading', 'text' ),
			'process_description' => array( 'Description', 'textarea' ),
			'process_items' => array( 'Numbered steps', 'rows', 'Title | Description' ),
		) ),
		'industries' => array( 'label' => '8. Industries accordion', 'fields' => array(
			'industries_eyebrow' => array( 'Eyebrow', 'text' ), 'industries_title' => array( 'Heading', 'text' ),
			'industries_description' => array( 'Description', 'textarea' ),
			'industries_icon' => array( 'List tick icon', 'url' ),
			'industries_items' => array( 'Industry groups', 'rows', 'Industry | Use cases (comma separated)' ),
		) ),
		'cta' => array( 'label' => '9. Conversion banner', 'fields' => array(
			'cta_title' => array( 'Heading', 'text' ), 'cta_description' => array( 'Description', 'textarea' ),
			'cta_image' => array( 'Background / person image', 'url' ), 'cta_label' => array( 'Button label', 'text' ),
			'cta_url' => array( 'Button URL', 'url' ),
		) ),
		'expertise' => array( 'label' => '10. Expertise and compliance', 'fields' => array(
			'expertise_eyebrow' => array( 'Expertise eyebrow', 'text' ), 'expertise_title' => array( 'Expertise heading', 'text' ),
			'expertise_description' => array( 'Expertise description', 'textarea' ),
			'expertise_items' => array( 'Expertise cards', 'rows', 'Title | Description | Icon URL' ),
			'compliance_eyebrow' => array( 'Compliance eyebrow', 'text' ), 'compliance_title' => array( 'Compliance heading', 'text' ),
			'compliance_description' => array( 'Compliance description', 'textarea' ),
			'compliance_items' => array( 'Compliance badges', 'rows', 'Name | Description | Logo URL' ),
		) ),
		'faq' => array( 'label' => '11. FAQs and contact', 'fields' => array(
			'faq_eyebrow' => array( 'Eyebrow', 'text' ), 'faq_title' => array( 'Heading', 'text' ),
			'faq_items' => array( 'FAQs', 'rows', 'Question | Answer' ),
			'contact_title' => array( 'Contact card heading', 'text' ), 'contact_description' => array( 'Contact card description', 'textarea' ),
			'contact_label' => array( 'Submit button label', 'text' ), 'contact_url' => array( 'Contact button URL (legacy)', 'url' ),
			'contact_full_name_label' => array( 'Full name label', 'text' ), 'contact_full_name_placeholder' => array( 'Full name placeholder', 'text' ),
			'contact_email_label' => array( 'Email label', 'text' ), 'contact_email_placeholder' => array( 'Email placeholder', 'text' ),
			'contact_designation_label' => array( 'Designation label', 'text' ), 'contact_designation_placeholder' => array( 'Designation placeholder', 'text' ),
			'contact_budget_label' => array( 'Budget field label', 'text' ), 'contact_budget_options' => array( 'Budget options', 'rows', 'One option per line' ),
			'contact_message_label' => array( 'Message label', 'text' ), 'contact_message_placeholder' => array( 'Message placeholder', 'text' ),
			'contact_security_text' => array( 'Security reassurance', 'text' ),
		) ),
	);
}

function aibridze_category_meta_key( string $name ): string { return '_aibridze_category_' . $name; }
function aibridze_category_value( int $term_id, string $name, $default = '' ) {
	$value = get_term_meta( $term_id, aibridze_category_meta_key( $name ), true );
	return '' === $value ? $default : $value;
}
function aibridze_category_rows( int $term_id, string $name ): array {
	$raw = (string) aibridze_category_value( $term_id, $name );
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		if ( '' === trim( $line ) ) continue;
		$rows[] = array_map( 'trim', explode( '|', $line ) );
	}
	return $rows;
}

function aibridze_register_category_page_meta(): void {
	foreach ( aibridze_category_page_groups() as $group ) foreach ( $group['fields'] as $name => $field ) {
		register_term_meta( 'service_category', aibridze_category_meta_key( $name ), array(
			'type' => 'string', 'single' => true, 'show_in_rest' => true,
			'sanitize_callback' => 'sanitize_textarea_field',
			'auth_callback' => static fn(): bool => current_user_can( 'manage_categories' ),
		) );
	}
}
add_action( 'init', 'aibridze_register_category_page_meta', 13 );

function aibridze_category_page_builder( WP_Term $term ): void {
	wp_nonce_field( 'aibridze_save_category_page', 'aibridze_category_page_nonce' );
	echo '<tr class="form-field"><th scope="row"><label>Category Page Builder</label></th><td><div class="service-builder category-builder">';
	echo '<p class="description">Content is unique to this category. Service cards are fetched automatically. Empty sections are hidden. For repeatable items, add one item per line using the displayed pipe-separated format.</p>';
	foreach ( aibridze_category_page_groups() as $group ) {
		echo '<details class="service-builder__panel"><summary>' . esc_html( $group['label'] ) . '</summary><div class="service-builder__panel-body">';
		foreach ( $group['fields'] as $name => $field ) {
			$value = (string) aibridze_category_value( $term->term_id, $name );
			echo '<label class="service-builder__field"><strong>' . esc_html( $field[0] ) . '</strong>';
			if ( 'textarea' === $field[1] || 'rows' === $field[1] ) {
				if ( ! empty( $field[2] ) ) echo '<small class="description">One per line: ' . esc_html( $field[2] ) . '</small>';
				echo '<textarea rows="' . ( 'rows' === $field[1] ? '6' : '4' ) . '" name="aibridze_category_page[' . esc_attr( $name ) . ']">' . esc_textarea( $value ) . '</textarea>';
			} else {
				echo '<input type="' . esc_attr( $field[1] ) . '" name="aibridze_category_page[' . esc_attr( $name ) . ']" value="' . esc_attr( $value ) . '">';
			}
			echo '</label>';
		}
		echo '</div></details>';
	}
	echo '</div></td></tr>';
}
add_action( 'service_category_edit_form_fields', 'aibridze_category_page_builder', 20 );

function aibridze_save_category_page( int $term_id ): void {
	if ( ! current_user_can( 'manage_categories' ) || empty( $_POST['aibridze_category_page_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aibridze_category_page_nonce'] ) ), 'aibridze_save_category_page' ) ) return;
	$submitted = isset( $_POST['aibridze_category_page'] ) && is_array( $_POST['aibridze_category_page'] ) ? wp_unslash( $_POST['aibridze_category_page'] ) : array();
	foreach ( aibridze_category_page_groups() as $group ) foreach ( $group['fields'] as $name => $field ) {
		$value = (string) ( $submitted[ $name ] ?? '' );
		$value = 'url' === $field[1] ? esc_url_raw( $value ) : ( in_array( $field[1], array( 'textarea', 'rows' ), true ) ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ) );
		'' === $value ? delete_term_meta( $term_id, aibridze_category_meta_key( $name ) ) : update_term_meta( $term_id, aibridze_category_meta_key( $name ), $value );
	}
}
add_action( 'edited_service_category', 'aibridze_save_category_page', 20 );

function aibridze_category_builder_assets( string $hook ): void {
	$screen = get_current_screen();
	if ( 'term.php' !== $hook || ! $screen || 'service_category' !== $screen->taxonomy ) return;
	wp_enqueue_style( 'aibridze-service-admin', get_theme_file_uri( '/assets/css/admin-service-page.css' ), array(), (string) filemtime( get_theme_file_path( '/assets/css/admin-service-page.css' ) ) );
}
add_action( 'admin_enqueue_scripts', 'aibridze_category_builder_assets' );

/** Load category-page interactions only on service category archives. */
function aibridze_category_page_assets(): void {
	if ( ! is_tax( 'service_category' ) ) return;
	$path = get_theme_file_path( '/assets/js/category-services-carousel.js' );
	wp_enqueue_script( 'aibridze-category-carousel', get_theme_file_uri( '/assets/js/category-services-carousel.js' ), array(), (string) filemtime( $path ), true );
}
add_action( 'wp_enqueue_scripts', 'aibridze_category_page_assets', 20 );

/** Seed the approved opening sections for the AI Development category. */
function aibridze_seed_ai_development_category_opening(): void {
	if ( get_option( 'aibridze_ai_development_category_opening_v1' ) ) return;
	$term = get_term_by( 'slug', 'ai-development', 'service_category' );
	if ( ! $term ) return;
	$asset = static fn( string $file ): string => get_theme_file_uri( '/assets/images/service-categories/ai-development/' . $file );
	$values = array(
		'hero_eyebrow' => 'Build Intelligent Solutions With AI',
		'hero_title' => 'Artificial Intelligence Development Services',
		'hero_description' => 'Turn artificial intelligence into practical products, intelligent workflows, and better customer experiences with AiBridze. We develop custom AI solutions that combine advanced AI technologies with modern software engineering to solve complex business challenges. From AI agents and generative AI to chatbots, automation, RAG, Voice AI, and computer vision, we build intelligent systems around your business goals.',
		'hero_image' => $asset( 'hero-background.png' ),
		'hero_mobile_image' => $asset( 'hero-background.png' ),
		'hero_cta_label' => 'Talk to Our AI Experts',
		'hero_cta_url' => home_url( '/contact-us/' ),
		'ecosystem_eyebrow' => 'The AI Ecosystem',
		'ecosystem_title' => 'One AI Partner. Multiple Ways to Build Intelligent Solutions.',
		'ecosystem_description' => "Modern artificial intelligence can do much more than generate content or answer questions. AI can understand language, retrieve knowledge, reason through tasks, communicate through voice, interpret visual information, and automate actions.\n\nAt AiBridze, we bring these capabilities together to build AI solutions across products, processes, and customer experiences.",
		'ecosystem_items' => implode( "\n", array(
			'Reason & Execute | Build AI systems that can reason through tasks, use tools, interact with systems, and execute workflows. | ' . $asset( 'ecosystem-1.png' ),
			'Understand | Enable applications to understand language, intent, context, and user interactions. | ' . $asset( 'ecosystem-2.png' ),
			'Interact & Communicate | Create natural, intelligent interactions across text and voice channels. | ' . $asset( 'ecosystem-3.png' ),
			'Generate & Create | Build applications that generate content, summaries, responses, insights, and other intelligent outputs. | ' . $asset( 'ecosystem-4.png' ),
			'Perceive | Enable AI to interpret images, documents, objects, and video. | ' . $asset( 'ecosystem-5.png' ),
			'Retrieve & Ground | Connect AI with trusted business information to deliver relevant and context-aware responses. | ' . $asset( 'ecosystem-6.png' ),
		) ),
		'services_eyebrow' => 'Our Artificial Intelligence Services',
		'services_title' => 'Seven Ways We Build With Artificial Intelligence',
		'services_description' => 'Our AI development services cover the technologies and solutions businesses need to build intelligent products, automate workflows, and create new digital experiences.',
	);
	foreach ( $values as $key => $value ) update_term_meta( $term->term_id, aibridze_category_meta_key( $key ), $value );
	update_option( 'aibridze_ai_development_category_opening_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_development_category_opening', 105 );

/** Add the supplied AI Ecosystem feature image without overwriting editor content. */
function aibridze_seed_ai_development_ecosystem_image(): void {
	if ( get_option( 'aibridze_ai_development_ecosystem_image_v1' ) ) return;
	$term = get_term_by( 'slug', 'ai-development', 'service_category' );
	if ( ! $term ) return;
	update_term_meta( $term->term_id, aibridze_category_meta_key( 'ecosystem_image' ), get_theme_file_uri( '/assets/images/service-categories/ai-development/ecosystem-feature.png' ) );
	update_option( 'aibridze_ai_development_ecosystem_image_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_development_ecosystem_image', 106 );

/** Populate editor-managed descriptions used by AI Development service cards. */
function aibridze_seed_ai_development_card_descriptions(): void {
	if ( get_option( 'aibridze_ai_development_card_descriptions_v1' ) ) return;
	$descriptions = array(
		'ai-agent-development' => 'Develop autonomous AI agents capable of understanding goals, completing multi-step tasks, interacting with applications, and executing business workflows.',
		'generative-ai-development' => 'Build custom Generative AI solutions using large language models for content generation, knowledge assistance, productivity, personalization, and intelligent digital experiences.',
		'ai-chatbot-development' => 'Develop intelligent AI chatbots that understand natural language, maintain context, answer questions, automate support, and connect with business systems.',
		'ai-automation' => 'Combine artificial intelligence, automation, APIs, and enterprise systems to streamline repetitive and complex business workflows.',
		'rag-development' => 'Build Retrieval-Augmented Generation solutions that connect AI models with documents, databases, APIs, and enterprise knowledge sources to deliver grounded, context-aware responses.',
		'voice-ai-development' => 'Develop intelligent AI voice agents and conversational voice solutions for real-time interactions, customer support, assistance, and business workflows.',
		'computer-vision-development' => 'Develop computer vision solutions for image recognition, object detection, OCR, visual inspection, video analytics, and other visual intelligence use cases.',
	);
	foreach ( $descriptions as $slug => $description ) {
		$service = get_page_by_path( $slug, OBJECT, 'service' );
		if ( $service ) update_post_meta( $service->ID, aibridze_service_meta_key( 'category_card_description' ), $description );
	}
	update_option( 'aibridze_ai_development_card_descriptions_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_development_card_descriptions', 107 );

/** Populate the editor-managed AI business-value scroll story. */
function aibridze_seed_ai_development_business_value(): void {
	if ( get_option( 'aibridze_ai_development_business_value_v1' ) ) return;
	$term = get_term_by( 'slug', 'ai-development', 'service_category' );
	if ( ! $term ) return;
	$values = array(
		'value_eyebrow' => 'AI for Business',
		'value_title' => 'Where AI Creates Real Business Value',
		'value_description' => 'AI creates the most value when it is connected to a real business challenge. We use AI to improve processes, products, customer experiences, and employee productivity.',
		'value_image' => get_theme_file_uri( '/assets/images/service-categories/ai-development/business-value.jpg' ),
		'value_items' => implode( "\n", array(
			'Automate Operations | Reduce repetitive manual work and streamline complex workflows. | AI-powered workflows, Intelligent task execution',
			'Improve Customer Experiences | Deliver faster, more personalized support across digital and voice channels. | Conversational AI, AI assistants, Voice AI',
			'Unlock Business Knowledge | Make information across documents, databases, and internal systems easier to access. | RAG, Enterprise Search, Knowledge assistants',
			'Build Intelligent Products | Add AI capabilities to existing products and create new digital-first applications. | Generative AI, Computer Vision, AI agents',
			'Empower Employees | Help teams find information, automate routine tasks, and make faster decisions. | AI copilots, Workflow automation, Knowledge tools',
		) ),
	);
	foreach ( $values as $key => $value ) update_term_meta( $term->term_id, aibridze_category_meta_key( $key ), $value );
	update_option( 'aibridze_ai_development_business_value_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_development_business_value', 108 );

/** Populate the editor-managed AI solutions and two-row technology ecosystem. */
function aibridze_seed_ai_development_solutions_technology(): void {
	if ( get_option( 'aibridze_ai_development_solutions_technology_v2' ) ) return;
	$term = get_term_by( 'slug', 'ai-development', 'service_category' );
	if ( ! $term ) return;
	$icon = static fn( string $file ): string => get_theme_file_uri( '/assets/images/service-categories/ai-development/technology/' . $file );
	$row_1_names = array( 'Docker', 'Kubernetes', 'Google Cloud', 'Azure', 'AWS', 'LangGraph', 'LangChain', 'Pipecat', 'LiveKit', 'Amazon Polly', 'Google Cloud TTS', 'Google Speech-to-Text', 'Whisper', 'Deepgram', 'Llama', 'Gemini', 'Claude', 'OpenAI' );
	$row_2_names = array( 'Pinecone', 'LangGraph', 'Qwen', 'Kimi K3', 'Grok', 'Llama', 'Gemini', 'Claude', 'OpenAI', 'OpenSearch', 'Elasticsearch', 'Milvus', 'ChromaDB', 'pgvector', 'Qdrant', 'Weaviate' );
	$technology = array();
	foreach ( $row_1_names as $index => $name ) $technology[] = $name . ' | Row 1 | ' . $icon( sprintf( 'row1-%02d.png', $index + 1 ) );
	foreach ( $row_2_names as $index => $name ) $technology[] = $name . ' | Row 2 | ' . $icon( sprintf( 'row2-%02d.png', $index + 1 ) );
	$values = array(
		'solutions_eyebrow' => 'AI-Powered Solutions',
		'solutions_title' => 'From Intelligent Assistants to Autonomous AI Systems',
		'solutions_description' => 'Our AI capabilities can be applied to build different types of intelligent solutions depending on your users, workflows, data, and business objectives.',
		'solutions_items' => implode( "\n", array(
			'AI Assistants & Copilots | Intelligent assistants that help customers and employees find information, generate content, answer questions, and complete everyday tasks.',
			'Autonomous AI Agents | AI systems that can reason through tasks, interact with tools and applications, and execute multi-step workflows.',
			'Intelligent Knowledge Systems | AI-powered systems that connect enterprise knowledge with natural-language search and contextual responses.',
			'Conversational & Voice Systems | Natural interfaces that allow customers and employees to interact with applications through text or voice.',
			'Intelligent Automation Systems | AI-powered workflows that understand information, make decisions, trigger actions, and reduce repetitive processes.',
			'Visual Intelligence Systems | AI applications that analyze images, documents, objects, and video to extract information or trigger actions.',
		) ),
		'technology_eyebrow' => 'Technologies We Work With',
		'technology_title' => 'Building With the Modern AI Technology Ecosystem',
		'technology_description' => 'We select technologies based on the requirements, use case, data environment, performance needs, scalability, and deployment model of each AI solution.',
		'technology_items' => implode( "\n", $technology ),
	);
	foreach ( $values as $key => $value ) update_term_meta( $term->term_id, aibridze_category_meta_key( $key ), $value );
	update_option( 'aibridze_ai_development_solutions_technology_v2', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_development_solutions_technology', 109 );

/** Populate the editor-managed AI approach, industry solutions, and CTA. */
function aibridze_seed_ai_development_approach_industries_cta(): void {
	if ( get_option( 'aibridze_ai_development_approach_industries_cta_v1' ) ) return;
	$term = get_term_by( 'slug', 'ai-development', 'service_category' );
	if ( ! $term ) return;
	$asset = static fn( string $file ): string => get_theme_file_uri( '/assets/images/service-categories/ai-development/' . $file );
	$values = array(
		'process_eyebrow' => 'Our Approach to AI Development',
		'process_title' => 'Build AI Around the Problem, Not Just the Technology',
		'process_description' => 'Every AI project starts with a business challenge. We focus on understanding what needs to be solved before determining which AI technologies, models, and architecture are right for the solution.',
		'process_items' => implode( "\n", array(
			'Understand | Identify your business objectives, users, workflows, data, challenges, and desired outcomes.',
			'Define | Determine the right AI use case, solution architecture, technology approach, and success criteria.',
			'Build | Develop the required AI models, applications, agents, integrations, and intelligent workflows.',
			'Validate | Test the solution for accuracy, reliability, performance, security, and real-world usability.',
			'Deploy | Integrate and deploy the AI solution within the required product, workflow, or technology environment.',
			'Evolve | Monitor and refine the solution as your business, users, data, and AI requirements change.',
		) ),
		'industries_eyebrow' => 'AI for Industry-Specific Challenges',
		'industries_title' => 'AI Solutions Built Around the Way Your Industry Works',
		'industries_description' => 'Every industry has different customers, workflows, data, and operational challenges. We combine our AI capabilities to develop solutions aligned with specific business environments.',
		'industries_icon' => $asset( 'industry-tick.png' ),
		'industries_items' => implode( "\n", array(
			'AI For Healthcare | AI-Powered Healthcare Assistants, Healthcare Knowledge Systems, Healthcare Workflow Automation, Conversational Healthcare Solutions',
			'AI For Banking & Financial Services | Intelligent Customer Support, Financial Knowledge Systems, Workflow Automation, Intelligent Digital Experiences',
			'AI For Insurance | AI Customer Assistance, Document & Knowledge Intelligence, Claims & Workflow Automation, Insurance Knowledge Assistants',
			'AI For Retail & E-Commerce | AI Shopping Assistants, Conversational Commerce, Intelligent Customer Support, Visual Intelligence',
			'AI For Real Estate | AI Property Assistants, Customer & Lead Automation, Property Knowledge Systems, Document Intelligence',
			'AI For Manufacturing | Visual Inspection, Intelligent Process Automation, AI Knowledge Assistants, Voice & Conversational Interfaces',
			'AI For Logistics & Supply Chain | Intelligent Workflow Automation, AI Operations Assistants, Document & Data Intelligence, Visual Intelligence',
			'AI For SaaS & Technology | Generative AI Features, AI Copilots & Assistants, AI Agents, Intelligent Search & Knowledge',
		) ),
		'cta_title' => 'Build Custom AI Solutions Around Your Business Goals',
		'cta_description' => 'Whether you need AI agents, Generative AI, AI automation, RAG, chatbots, Voice AI, or computer vision, our AI development team can help turn your business requirements into intelligent, scalable solutions.',
		'cta_image' => $asset( 'cta-robot.png' ),
		'cta_label' => 'Talk to Our AI Experts',
		'cta_url' => home_url( '/contact-us/' ),
	);
	foreach ( $values as $key => $value ) update_term_meta( $term->term_id, aibridze_category_meta_key( $key ), $value );
	update_option( 'aibridze_ai_development_approach_industries_cta_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_development_approach_industries_cta', 110 );

/** Populate the editor-managed AI expertise and compliance sections. */
function aibridze_seed_ai_development_expertise_compliance(): void {
	if ( get_option( 'aibridze_ai_development_expertise_compliance_v1' ) ) return;
	$term = get_term_by( 'slug', 'ai-development', 'service_category' );
	if ( ! $term ) return;
	$expertise = static fn( string $file ): string => get_theme_file_uri( '/assets/images/service-categories/ai-development/expertise/' . $file );
	$compliance = static fn( string $file ): string => get_theme_file_uri( '/assets/images/service-categories/ai-development/compliance/' . $file );
	$values = array(
		'expertise_eyebrow' => 'Why AiBridze for AI Development',
		'expertise_title' => 'AI Expertise Backed by Software Engineering',
		'expertise_description' => 'We combine artificial intelligence, software engineering, automation, and product development expertise to build practical AI solutions around real business requirements.',
		'expertise_items' => implode( "\n", array(
			"Building Real Products | We don't treat AI as an isolated model. We build complete applications, integrations, and systems around it. | " . $expertise( 'building-real-products.png' ),
			"Business-First Thinking | We focus on the problem you're solving, the users you're serving, and the value the solution needs to create. | " . $expertise( 'business-first-thinking.png' ),
			'Custom AI Architecture | We select and combine AI models, frameworks, data sources, integrations, and infrastructure around your requirements. | ' . $expertise( 'custom-ai-architecture.png' ),
			'From Strategy To Production | From strategy and architecture through development, integration, deployment, and optimization. | ' . $expertise( 'strategy-to-production.png' ),
			'Cross-Functional Expertise | Our AI development approach brings together artificial intelligence, software engineering, automation, and product development. | ' . $expertise( 'cross-functional-expertise.png' ),
		) ),
		'compliance_eyebrow' => 'AI Security & Compliance',
		'compliance_title' => 'Building AI With Security, Privacy & Compliance in Mind',
		'compliance_description' => 'AI solutions often interact with sensitive business information, customer data, proprietary knowledge, and connected enterprise systems. We consider security, privacy, access control, and applicable compliance requirements throughout the AI development lifecycle.',
		'compliance_items' => implode( "\n", array(
			'GDPR | General Data Protection Regulation | ' . $compliance( 'gdpr.png' ),
			'The EU AI Act |  | ' . $compliance( 'eu-ai-act.png' ),
			'IEEE Standards for AI Systems and Applications |  | ' . $compliance( 'ieee.png' ),
			'ISO/IEC 27001 | Information Security Management | ' . $compliance( 'iso-27001.png' ),
			'CCPA | California Consumer Privacy Act | ' . $compliance( 'ccpa.png' ),
			'SOC 2 | Service Organization Control 2 | ' . $compliance( 'soc-2.png' ),
			'HIPAA | Health Insurance Portability and Accountability Act | ' . $compliance( 'hipaa.png' ),
			'NIST AI Risk Management Framework |  | ' . $compliance( 'nist.png' ),
			'AI Ethics Guidelines | OECD, EU | ' . $compliance( 'ai-ethics.png' ),
			'PCI DSS | Payment Card Industry Data Security Standard | ' . $compliance( 'pci-dss.png' ),
		) ),
	);
	foreach ( $values as $key => $value ) update_term_meta( $term->term_id, aibridze_category_meta_key( $key ), $value );
	update_option( 'aibridze_ai_development_expertise_compliance_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_development_expertise_compliance', 111 );

/** Populate the editor-managed AI Development FAQ and project form copy. */
function aibridze_seed_ai_development_faq_contact(): void {
	if ( get_option( 'aibridze_ai_development_faq_contact_v1' ) ) return;
	$term = get_term_by( 'slug', 'ai-development', 'service_category' );
	if ( ! $term ) return;
	$values = array(
		'faq_eyebrow' => 'FAQs',
		'faq_title' => 'Have questions? Check out the FAQs',
		'faq_items' => implode( "\n", array(
			'What AI development services does AiBridze offer? | AiBridze offers AI Agent Development, Generative AI Development, AI Chatbot Development, AI Automation, RAG Development, Voice AI, and Computer Vision services.',
			'How do I know which AI solution is right for my business? | We assess your goals, workflows, data, users, and existing technology to recommend the most suitable AI approach, architecture, and technologies.',
			'Can multiple AI technologies be combined into one solution? | Yes. We can combine AI agents, generative AI, RAG, automation, voice, computer vision, and other capabilities within one integrated solution.',
			'Can AiBridze integrate AI into existing software? | Yes. We integrate AI capabilities into existing applications, workflows, APIs, databases, and enterprise systems.',
			'Can you build a custom AI product from scratch? | Yes. We can take an AI product from discovery and architecture through development, integration, deployment, and ongoing improvement.',
			'How do you approach security and compliance in AI development? | We consider data privacy, access control, security, reliability, and applicable compliance requirements throughout the AI development lifecycle.',
			'Can AiBridze improve an existing AI solution? | Yes. We can assess, modernize, optimize, integrate, or extend an existing AI solution based on your current challenges and business goals.',
		) ),
		'contact_title' => 'Tell Us About Your Project',
		'contact_description' => 'Share your requirements, and our experts will get back to you with the right solution and next steps.',
		'contact_label' => 'Submit',
		'contact_full_name_label' => 'Full Name', 'contact_full_name_placeholder' => 'Enter your name',
		'contact_email_label' => 'Email', 'contact_email_placeholder' => 'Enter work email',
		'contact_designation_label' => 'Designation', 'contact_designation_placeholder' => 'Enter designation',
		'contact_budget_label' => 'Your Preferred Budget Range',
		'contact_budget_options' => '$5k–$15k' . "\n" . '$15k–$50k' . "\n" . '$50k–$100k' . "\n" . '$100k+',
		'contact_message_label' => 'How can we help you?', 'contact_message_placeholder' => 'Write Here...',
		'contact_security_text' => 'Share with Confidence. Fully NDA-Protected.',
	);
	foreach ( $values as $key => $value ) update_term_meta( $term->term_id, aibridze_category_meta_key( $key ), $value );
	update_option( 'aibridze_ai_development_faq_contact_v1', 1, false );
}
add_action( 'init', 'aibridze_seed_ai_development_faq_contact', 112 );

/** AI Studio is the entry point for the broader AI Development category page. */
function aibridze_redirect_ai_studio_to_category(): void {
	if ( ! is_singular( 'service' ) ) return;
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post || 'ai-studio' !== $post->post_name ) return;
	$term = get_term_by( 'slug', 'ai-development', 'service_category' );
	if ( $term && ! is_wp_error( $term ) ) wp_safe_redirect( get_term_link( $term ), 301 );
	exit;
}
add_action( 'template_redirect', 'aibridze_redirect_ai_studio_to_category' );
