<?php
/**
 * Certified expertise and global compliance section.
 *
 * @package AIBridze
 */
$standards = array(
	array( 'GDPR (General Data Protection Regulation):', 'Handling data for European clients with rigorous privacy standards.' ),
	array( 'CCPA (California Consumer Privacy Act):', 'Ensuring compliance for clients operating in California and handling consumer data.' ),
	array( 'HIPAA (Health Insurance Portability and Accountability Act):', 'Adherence to security and privacy rules for health-related information' ),
);
?>
<section class="compliance-section" aria-labelledby="compliance-title">
	<div class="compliance-card">
		<div class="compliance-card__certifications">
			<h2 id="compliance-title"><?php esc_html_e( 'Certified Expertise & Global Compliance', 'aibridze' ); ?></h2>
			<p><?php esc_html_e( "We don't just build AI; we build it responsibly and securely. Our team holds top industry credentials, and our operations comply with major international regulatory frameworks.", 'aibridze' ); ?></p>
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/compliance/certification-badges.png' ) ); ?>" width="200" height="54" alt="PDPA, CCPA, HIPAA and GDPR compliance certifications">
		</div>

		<div class="compliance-card__standards">
			<h2><?php esc_html_e( 'Global Regulatory Compliance', 'aibridze' ); ?></h2>
			<p class="compliance-card__intro"><?php esc_html_e( 'We are committed to operating within strict global data protection and privacy standards, giving you peace of mind when scaling AI internationally.', 'aibridze' ); ?></p>
			<ul>
				<?php foreach ( $standards as $standard ) : ?>
					<li>
						<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/compliance/shield.png' ) ); ?>" width="20" height="24" alt="">
						<div><strong><?php echo esc_html( $standard[0] ); ?></strong><span><?php echo esc_html( $standard[1] ); ?></span></div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
