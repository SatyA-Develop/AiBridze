<?php
/**
 * Front-page template.
 *
 * @package AIBridze
 */

get_header();
get_template_part( 'template-parts/sections/hero' );
get_template_part( 'template-parts/sections/transformation' );
get_template_part( 'template-parts/sections/manual-cost' );
get_template_part( 'template-parts/sections/services-stack' );
get_template_part( 'template-parts/sections/process' );
get_template_part( 'template-parts/sections/portfolio-stack' );
get_template_part( 'template-parts/sections/success-cta' );
get_template_part( 'template-parts/sections/customer-stories' );
get_template_part( 'template-parts/sections/industries-showcase' );
get_template_part( 'template-parts/sections/compliance' );
get_template_part( 'template-parts/sections/faq-contact' );
get_footer();
