<?php
/** Build enquiry notifications from already validated form values. */
function aibridze_enquiry_email( array $fields, array $article = array() ): array {
	$is_blog = ! empty( $article );
	$lines   = array(
		$is_blog ? 'Blog article consultation request' : 'Customer enquiry',
		'',
		'Name: ' . $fields['name'],
		'Email: ' . $fields['email'],
	);
	if ( '' !== $fields['phone'] ) {
		$lines[] = 'Phone: ' . trim( $fields['country_code'] . ' ' . $fields['phone'] );
	}
	if ( $is_blog ) {
		$lines[] = '';
		$lines[] = 'Article: ' . $article['title'];
		$lines[] = 'Article URL: ' . $article['url'];
		$lines[] = '';
		$lines[] = 'Request: Please contact this reader for a free consultation and quote.';
	} else {
		foreach ( array( 'designation' => 'Designation', 'budget' => 'Budget' ) as $key => $label ) {
			if ( '' !== $fields[ $key ] ) {
				$lines[] = $label . ': ' . $fields[ $key ];
			}
		}
		$lines[] = '';
		$lines[] = 'Project details:';
		$lines[] = $fields['message'];
	}
	return array(
		'subject' => sprintf( $is_blog ? '[AIBridze Blog] Free quote request from %s' : '[AIBridze] Consultation request from %s', $fields['name'] ),
		'body'    => implode( "\n", $lines ),
	);
}
