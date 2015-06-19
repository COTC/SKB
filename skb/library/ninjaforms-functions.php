<?php
// Ninja Forms 

//  1 - Contact
//  2 - Individual
//  3 - Invest in this Deal 
//  4 - Trust 
//  5 - *Deleted
//  6 - Joint Tenants 
//  7 - Tenants in Common 
//  8 - Community Property 
//  9 - Individual Retirement Account 
// 10 - *Deleted
// 11 - Limited Partnership
// 12 - Limited Liability Corporation 
// 13 - *Deleted
// 14 - Corporation
// 15 - Existing Investor 

// 42 - Investor Verification


add_action( 'wp_head', 'skb_move_ninja_forms_messages' );
add_action( 'init', 'skb_ninja_forms_check' );
add_action( 'init', 'skb_ninja_forms_submitted' );
add_action( 'init', 'skb_ninja_forms_completed' );

// Move responses down to after the form so user can easily see them when submitting
function skb_move_ninja_forms_messages() {

    remove_action( 'ninja_forms_display_before_form', 'ninja_forms_display_response_message', 10 );
    add_action( 'ninja_forms_display_after_form', 'ninja_forms_display_response_message', 10 );

}

// Check user/form access
function skb_ninja_forms_check() {

	add_action( 'ninja_forms_display_pre_init', 'skb_form_check' );

}

// Form submitted, handle data
function skb_ninja_forms_submitted() {

	add_action( 'ninja_forms_pre_process', 'skb_form_submitted' );

}

function skb_ninja_forms_completed() {

	add_action( 'ninja_forms_post_process', 'skb_form_completed' );

}


function skb_form_check() {

	global $ninja_forms_loading;

	$current_form_id = $ninja_forms_loading->get_form_ID();
	$current_form_id = '2';

	// Check the current form to see if we need to limit the user from filling out this form again

	switch ( $current_form_id ) {

		case '2': // Individual
		case '6': // Joint Tenants
		case '7': // Tenants in Common
		case '8': // Community Property

			// Multiple submissions are not allowed	
			break;

		case '4':  // Trust
		case '9':  // IRA 
		case '11': // Limited Partnership 
		case '12': // LLC 
		case '14': // Corporation 
		default:

			// Multiple submissions are allowed or not an AIQ form
			return;

	}

	$user_id = get_current_user_id();

	$args = array(
		'user_id'   => $user_id,
	);
	
	$subs = Ninja_Forms()->subs()->get( $args );

	foreach ( $subs as $sub ) {

		$form_id = $sub->form_id;

		switch ( $form_id ) {

			case '2': // Individual
			case '6': // Joint Tenants
			case '7': // Tenants in Common
			case '8': // Community Property
				// Multiple submissions are not allowed	
				$ninja_forms_loading->add_error( 'skb_duplicate', 'Multiple submissions of this form are not allowed.' );
				break;

			default:
				// not an AIQ form;
				break;

		}

	}

}


function skb_form_submitted() {

	global $ninja_forms_processing;

	$form_id = $ninja_forms_processing->get_form_ID();

	if ( $form_id != '43' ) {
	
		return;
	
	}

	// We've got an Investor Verification Being Uploaded
	$user_id = get_current_user_id();
	update_user_meta( $user_id, 'user_verification', date( 'Y-m-d H:i:s', time() ) );

}


function skb_form_completed() {

	global $ninja_forms_processing;

	$form_id = $ninja_forms_processing->get_form_ID();
	$sub_id = $ninja_forms_processing->get_form_setting( 'sub_id' );

	$skb_forms = array( 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 );

	if ( ! in_array( $form_id, $skb_forms , true ) || ! is_user_logged_in() ) {

		return;

	}

	$theme_settings = get_option('skb_theme_settings');

	$from_name = $theme_settings['skb_from_name'];
	$from_email = $theme_settings['skb_from_email'];

	$user_id = get_current_user_id();
	$user_info = get_userdata( $user_id );
	$user_meta = array_map( function( $a ) { return $a[0]; }, get_user_meta( $user_id ) );

	$to_name = $user_meta['first_name'] . ' ' . $user_meta['last_name'];
	$to_email = $user_info->user_email;

	$headers = 'From: ' . $from_name . ' <' . $from_email . '>' . PHP_EOL;	
	$to = $to_name . ' <' . $to_email . '>';

	switch ($form_id) {
		case 3:
			// Invest in this Deal

			$subject = $theme_settings['invest_subject'];
			$email_title = $subject;
			$email_body = 'Dear ' . $to_name . ',<br><br>';
			$email_body .= do_shortcode( wpautop( $theme_settings['invest_message'] ) );
			$email_footer = '';

			// Send to DocuSign to be signed
			send_to_docusign( $to_name, $to_email, $form_id, $sub_id );

			$redirect_page = $theme_settings['invest_landing'];
			$redirect = get_permalink( $redirect_page );

			break;

		case 15:
			// Existing Investor

			$subject = $theme_settings['existing_investor_subject'];
			$email_title = $subject;
			$email_body = 'Dear ' . $to_name . ',<br><br>';
			$email_body .= do_shortcode( wpautop( $theme_settings['existing_investor_message'] ) );
			$email_footer = '';

			$redirect_page = $theme_settings['existing_landing'];
			$redirect = get_permalink( $redirect_page );

			break;

		default: 
			// The rest are all AIQs 

			$subject = $theme_settings['aiq_completed_subject'];
			$email_title = $subject;
			$email_body = 'Dear ' . $to_name . ',<br><br>';
			$email_body .= do_shortcode( wpautop( $theme_settings['aiq_completed_message'] ) );
			$email_footer = '';

			// Send to DocuSign to be signed

			$result = send_to_docusign( $to_name, $to_email, $form_id, $sub_id );

			if ( $result ) {

				wp_redirect( home_url() . '/docusign?url=' . urlencode( $result ) );
				exit;

			}

			if ( check_user_role( 'registered_investor', $user_id ) ) {

				switch_user_role( 'waiting_investor', $user_id );
	
			} elseif ( check_user_role( 'waiting_investor', $user_id  ) ) {
				
				$redirect = home_url( '/investments' );
				wp_redirect( $redirect );
				exit;

			} elseif ( check_user_role( 'approved_investor', $user_id ) ) {

				$redirect = home_url( '/dashboard' );
				wp_redirect( $redirect );
				exit;

			} else {

				$redirect = home_url( '/' );
				wp_redirect( $redirect );
				exit;

			}

			// Setup single cron events for each of cooling phases which include emails and lastly a role change to approved
			wp_schedule_single_event( time() + ( 2 * 24 * 60 * 60 ), 'skb_waiting_investor', array( $user_id, '1') );
			wp_schedule_single_event( time() + ( 5 * 24 * 60 * 60 ), 'skb_waiting_investor', array( $user_id, '2') );

			$redirect_page = $theme_settings['waiting_landing'];
			$redirect = get_permalink( $redirect_page );

			break;
	}

	// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
	add_filter( 'wp_mail_content_type', 'set_html_content_type' );

	ob_start();
	include( locate_template( 'library/templates/email-basic.php', false, true ) );
	$message = ob_get_clean();

	wp_mail( $to, $subject, $message, $headers );

	remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
	
	wp_redirect( $redirect );

	exit;

}