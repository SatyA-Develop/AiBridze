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
		$lines = array( 'Hi Sales Team,', '', 'A new sales enquiry has been received from the website. Please review and take the next steps.', '', 'Here are the details:', '', '──────────────────────────────────', '👤 Full Name: ' . $fields['name'], '📩 Email: ' . $fields['email'] );
		foreach ( array( 'subject' => '📝 Subject', 'company' => '🏢 Company Name', 'designation' => 'Designation', 'budget' => '💰 Budget Range' ) as $key => $label ) {
			if ( ! empty( $fields[ $key ] ) ) $lines[] = $label . ': ' . $fields[ $key ];
		}
		if ( ! empty( $fields['phone'] ) ) $lines[] = 'Phone: ' . trim( $fields['country_code'] . ' ' . $fields['phone'] );
		$lines = array_merge( $lines, array( '', '💬 Message:', $fields['message'], '──────────────────────────────────', '', '📅 Submitted On: ' . ( $fields['submitted_on'] ?? '' ), '🌐 IP Address: ' . ( $fields['remote_ip'] ?? '' ), '', 'Please follow up with the user at the earliest.', '', 'Warm regards,', 'Team AiBridze', 'https://aibridze.com' ) );
	}

	return array(
		'subject' => sprintf( $is_blog ? '[AIBridze Blog] Free quote request from %s' : '[AIBridze] Consultation request from %s', $fields['name'] ),
		'body'    => implode( "\n", $lines ),
	);
}

/** Acknowledgement for customer enquiries, including blog requests. */
function aibridze_enquiry_reply( string $name ): string {
 return "Hi {$name},\n\nThank you for contacting us and submitting your enquiry on our website. We’ve received your request and our team is reviewing the details.\n\nOne of our specialists will reach out to you shortly to understand your requirements better and discuss how we can support you with our AI solutions, mobile app development, or web development services.\n\nIf you’d like to share any additional information before the call, feel free to reply to this email.\n\nLooking forward to connecting with you.\n\nWarm regards,\nTeam AiBridze\nhttps://aibridze.com";
}

function aibridze_career_emails( array $f ): array {
 $summary = "👤 Name: {$f['name']}\n💼 Position Applied: {$f['position']}\n📞 Mobile: {$f['phone']}\nCurrent CTC: {$f['current_ctc']}\nExpected CTC: {$f['expected_ctc']}\n📧 Email: {$f['email']}\n📄 Resume: {$f['resume_name']}\n🕓 Experience: {$f['years_experience']}";
 return array(
  'admin' => "Hello Admin,\n\nYou’ve received a new job application from the Job Portal.\n\nHere are the details:\n\n---\n\n{$summary}\n\n---\n\nPlease review the attached resume and follow up accordingly.",
  'reply' => "Hello {$f['name']},\n\nThank you for applying for the {$f['position']} position at our company.\n\nWe’ve received your application successfully and our HR team will review it soon.\nIf your profile matches our requirements, we’ll contact you on your registered email or phone number.\n\nHere’s a quick summary of your submitted details:\n\n{$summary}\n\nThank you again for your interest.\n\nBest regards,\nTeam HR\nAiBridze Technologies",
 );
}
