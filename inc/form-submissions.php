<?php
/** Private, durable enquiry records and delivery tracking for both form types. */
function aibridze_register_submission_type(): void {
 register_post_type( 'form_submission', array(
  'labels' => array( 'name' => 'Enquiries', 'singular_name' => 'Enquiry' ),
  'public' => false, 'show_ui' => true, 'show_in_rest' => false,
  'menu_icon' => 'dashicons-email-alt', 'supports' => array( 'title' ),
 ) );
}
add_action( 'init', 'aibridze_register_submission_type' );
add_filter( 'register_post_type_args', function( $args, $type ) {
 if ( ! in_array( $type, array( 'form_submission', 'job_application' ), true ) ) return $args;
 $args['capabilities'] = array_fill_keys( array( 'edit_post', 'read_post', 'delete_post', 'edit_posts', 'edit_others_posts', 'publish_posts', 'read_private_posts', 'delete_posts', 'delete_private_posts', 'delete_published_posts', 'delete_others_posts', 'edit_private_posts', 'edit_published_posts' ), 'manage_options' );
 $args['capabilities']['create_posts'] = 'do_not_allow';
 $args['map_meta_cap'] = false;
 return $args;
}, 10, 2 );

/** Save all fields in the post itself, before any outbound mail is attempted. */
function aibridze_save_enquiry( array $fields ) {
 return wp_insert_post( wp_slash( array(
  'post_type' => 'form_submission', 'post_status' => 'private',
  'post_title' => $fields['name'] . ' — ' . $fields['email'],
  'post_content' => wp_json_encode( $fields, JSON_UNESCAPED_UNICODE ),
  'meta_input' => array( '_aibridze_form_type' => $fields['form_type'], '_aibridze_mail_status' => 'pending', '_aibridze_reply_status' => 'pending' ),
 ) ), true );
}

/** Mail acceptance does not imply inbox delivery. Failures never remove the saved record. */
function aibridze_send_record_mail( int $id, string $channel, $to, string $subject, string $body, array $headers, array $attachments = array() ): bool {
 $key = 'reply' === $channel ? '_aibridze_reply_status' : '_aibridze_mail_status';
 update_post_meta( $id, $key, 'pending' );
 $failure = '';
 $capture_failure = static function( $error ) use ( &$failure ) {
  global $phpmailer;
  $failure = $error->get_error_message();
  if ( $phpmailer && $phpmailer->isSMTP() ) {
   $smtp_error = $phpmailer->getSMTPInstance()->getError();
   $failure .= ' ' . trim( (string) ( $smtp_error['smtp_code'] ?? '' ) . ' ' . (string) ( $smtp_error['detail'] ?? '' ) );
  }
 };
 add_action( 'wp_mail_failed', $capture_failure );
 try { $sent = wp_mail( $to, $subject, $body, $headers, $attachments ); }
 catch ( Throwable $error ) { $sent = false; $failure = $error->getMessage(); }
 finally { remove_action( 'wp_mail_failed', $capture_failure ); }
 if ( $sent ) delete_post_meta( $id, $key . '_error' );
 else update_post_meta( $id, $key . '_error', sanitize_text_field( $failure ?: 'Mail transport returned false.' ) );
 update_post_meta( $id, $key, $sent ? 'accepted' : 'failed' );
 return (bool) $sent;
}

function aibridze_record_status( int $id, string $channel ): string {
 $value = get_post_meta( $id, 'reply' === $channel ? '_aibridze_reply_status' : '_aibridze_mail_status', true );
 if ( ! $value && 'notification' === $channel ) $value = get_post_meta( $id, '_aibridze_application_notification_status', true );
 return $value ?: 'unknown';
}
foreach ( array( 'form_submission', 'job_application' ) as $record_type ) {
 add_filter( 'manage_' . $record_type . '_posts_columns', function( $columns ) {
  $columns['aibridze_type'] = 'Form type';
  $columns['aibridze_notification'] = 'Team email';
  $columns['aibridze_reply'] = 'Sender reply';
  return $columns;
 } );
 add_action( 'manage_' . $record_type . '_posts_custom_column', function( $column, $id ) {
  if ( 'aibridze_type' === $column ) echo esc_html( 'job_application' === get_post_type( $id ) ? 'Career' : ucfirst( get_post_meta( $id, '_aibridze_form_type', true ) ?: 'enquiry' ) );
  if ( 'aibridze_notification' === $column ) echo esc_html( ucfirst( aibridze_record_status( $id, 'notification' ) ) );
  if ( 'aibridze_reply' === $column ) echo esc_html( ucfirst( aibridze_record_status( $id, 'reply' ) ) );
 }, 10, 2 );
}
add_action( 'restrict_manage_posts', function( $type ) {
 if ( ! in_array( $type, array( 'form_submission', 'job_application' ), true ) ) return;
 $filters = array( 'mail_status' => 'Team email', 'reply_status' => 'Sender reply' );
 foreach ( $filters as $name => $label ) {
  echo '<select name="aibridze_' . esc_attr( $name ) . '" aria-label="' . esc_attr( $label ) . '"><option value="">' . esc_html( $label . ': all' ) . '</option>';
  foreach ( array( 'failed' => 'Failed', 'pending' => 'Pending', 'accepted' => 'Accepted by mail server' ) as $value => $text ) {
   echo '<option value="' . esc_attr( $value ) . '" ' . selected( sanitize_key( $_GET['aibridze_' . $name] ?? '' ), $value, false ) . '>' . esc_html( $text ) . '</option>';
  }
  echo '</select>';
 }
 if ( 'form_submission' === $type ) {
  echo '<select name="aibridze_form_type" aria-label="Form type"><option value="">All enquiry types</option>';
  foreach ( array( 'enquiry' => 'Customer enquiry', 'blog' => 'Blog enquiry' ) as $value => $text ) echo '<option value="' . esc_attr( $value ) . '" ' . selected( sanitize_key( $_GET['aibridze_form_type'] ?? '' ), $value, false ) . '>' . esc_html( $text ) . '</option>';
  echo '</select>';
 }
} );
add_action( 'pre_get_posts', function( $query ) {
 if ( ! is_admin() || ! $query->is_main_query() || ! in_array( $query->get( 'post_type' ), array( 'form_submission', 'job_application' ), true ) ) return;
 $meta = (array) $query->get( 'meta_query' );
 foreach ( array( 'mail_status', 'reply_status', 'form_type' ) as $key ) {
  $value = sanitize_key( $_GET['aibridze_' . $key] ?? '' );
  $allowed = 'form_type' === $key ? array( 'enquiry', 'blog' ) : array( 'failed', 'pending', 'accepted' );
  if ( in_array( $value, $allowed, true ) ) $meta[] = array( 'key' => '_aibridze_' . $key, 'value' => $value );
 }
 $query->set( 'meta_query', $meta );
} );
add_action( 'add_meta_boxes', function() {
 foreach ( array( 'form_submission', 'job_application' ) as $type ) {
  add_meta_box( 'aibridze-record', 'Submitted data and email status', function( $post ) {
   echo '<p><strong>Team email:</strong> ' . esc_html( aibridze_record_status( $post->ID, 'notification' ) ) . ' &nbsp; <strong>Sender reply:</strong> ' . esc_html( aibridze_record_status( $post->ID, 'reply' ) ) . '</p><p>Accepted means the mail server accepted the email, not confirmed inbox delivery.</p>';
   foreach ( array( '_aibridze_mail_status_error' => 'Team email error', '_aibridze_reply_status_error' => 'Reply email error' ) as $error_key => $label ) {
    $error = get_post_meta( $post->ID, $error_key, true );
    if ( $error ) echo '<p><strong>' . esc_html( $label ) . ':</strong> ' . esc_html( $error ) . '</p>';
   }
   $data = json_decode( $post->post_content, true );
   if ( ! is_array( $data ) ) return;
   echo '<table class="widefat striped"><tbody>';
   foreach ( $data as $key => $value ) {
    if ( is_array( $value ) ) $value = wp_json_encode( $value, JSON_UNESCAPED_UNICODE );
    echo '<tr><th>' . esc_html( ucwords( str_replace( '_', ' ', $key ) ) ) . '</th><td style="white-space:pre-wrap;overflow-wrap:anywhere">' . esc_html( (string) $value ) . '</td></tr>';
   }
   echo '</tbody></table>';
  }, $type, 'normal', 'high' );
 }
} );
