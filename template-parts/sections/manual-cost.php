<?php
/**
 * Sticky, scroll-sequenced business challenges section.
 *
 * @package AIBridze
 */

$challenges = array(
	'Manual Processes',
	'Unclear Roadmaps',
	'Slow Delivery Cycles',
	'Resource Inefficiencies',
	'Limited Scalability',
);
?>
<section class="manual-cost" data-manual-cost aria-labelledby="manual-cost-title">
	<div class="manual-cost__sticky">
		<div class="manual-cost__rings" aria-hidden="true">
			<div class="manual-cost__ring manual-cost__ring--outer"></div>
			<div class="manual-cost__ring manual-cost__ring--middle"></div>
			<div class="manual-cost__ring manual-cost__ring--inner"></div>
			<div class="manual-cost__ring manual-cost__ring--core"></div>
			<div class="manual-cost__arc manual-cost__arc--outer"></div>
			<div class="manual-cost__arc manual-cost__arc--middle"></div>
			<div class="manual-cost__arc manual-cost__arc--inner"></div>
		</div>
		<h2 class="manual-cost__title" id="manual-cost-title">The Cost of<br>Staying Traditional</h2>
		<div class="manual-cost__challenges">
			<?php foreach ( $challenges as $index => $challenge ) : ?>
				<div class="manual-cost__challenge manual-cost__challenge--<?php echo esc_attr( $index + 1 ); ?>" data-manual-challenge style="--challenge-index: <?php echo esc_attr( $index ); ?>">
					<span aria-hidden="true"></span><?php echo esc_html( $challenge ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
