<?php

/* Actions
-------------------------------------------------- */

add_action( 'init', 'add_skb_roles' );
add_action( 'wp_enqueue_scripts', 'skb_scripts' );
add_action( 'wp_enqueue_scripts', 'skb_styles' );
add_action( 'after_setup_theme', 'skb_login_check' );
add_action( 'after_setup_theme', 'skb_register_check' );
add_action( 'after_setup_theme', 'remove_admin_bar' );
add_action( 'manage_investment_posts_custom_column', 'skb_manage_investment_posts_custom_column', 11, 2 );
add_action( 'manage_investment_update_posts_custom_column', 'skb_manage_investment_update_posts_custom_column', 11, 2 );
add_action( 'manage_team_member_posts_custom_column', 'skb_manage_team_member_posts_custom_column', 11, 2 );
add_action( 'manage_partner_posts_custom_column', 'skb_manage_partner_posts_custom_column', 11, 2 );
add_action( 'manage_faq_posts_custom_column', 'skb_manage_faq_posts_custom_column', 11, 2 );
add_action( 'manage_glossary_term_posts_custom_column', 'skb_manage_glossary_term_posts_custom_column', 11, 2 );
add_action( 'pre_get_posts', 'skb_custom_post_type_queries', 1 );
add_action( 'wp_ajax_add_to_watch_list', 'add_to_watch_list' );
add_action( 'wp_ajax_remove_from_watch_list', 'remove_from_watch_list' );
add_action( 'wp_ajax_mark_offering_read', 'mark_offering_read' );
add_action( 'wp_ajax_update_user_account', 'update_user_account' );
add_action( 'wp_ajax_update_user_address', 'update_user_address' );
add_action( 'wp_ajax_update_user_password', 'update_user_password' );
add_action( 'init', 'download_offering_pdf' );
add_action( 'save_post', 'skb_save_first_letter' );
add_action( 'init','skb_run_once' );
add_action( 'set_user_role', 'user_role_update', 10, 3);
add_action( 'skb_waiting_investor', 'waiting_investor', 10, 2 );

remove_action( 'wpua_before_avatar', 'wpua_do_before_avatar');
remove_action( 'wpua_after_avatar', 'wpua_do_after_avatar');


/* Callback Functions
-------------------------------------------------- */


function add_skb_roles() {
    
    global $wp_roles;

    if ( class_exists('WP_Roles') ) {

        if ( ! isset( $wp_roles ) ) {

            $wp_roles = new WP_Roles();
        
        }
    }

    if ( is_object( $wp_roles ) ) {

        if ( ! get_role( 'approved_investor' ) ) {
            // add_role( 'approved_investor', 'Approved Investor', array( 'view_investments' ) );
            add_role( 'approved_investor', 'Approved Investor', array( 'read' ) );
        }

        if ( ! get_role( 'cooling_investor' ) ) {
            add_role( 'waiting_investor', 'Waiting Investor', array( 'read' ) );
        }

        if ( ! get_role( 'registered_investor' ) ) {
            add_role( 'registered_investor', 'Registered Investor', array( 'read' ) );
        } 

        if ( ! get_role( 'pending_investor' ) ) {
            add_role( 'pending_investor', 'Pending Investor', array( 'read' ) );
        } 

		$editor_role = get_role( 'editor' );
		// $editor_role->add_cap( 'view_investments' );
		
		$admin_role = get_role( 'administrator' );
		// $admin_role->add_cap( 'view_investments' );

    }   

}


function skb_scripts() {

	if ( wp_is_mobile() ) {

        wp_register_script( 'modernizer-js', get_template_directory_uri() . '/library/js/modernizr.custom.js', array( 'jquery' ), '1.0', FALSE );        
        wp_enqueue_script( 'modernizer-js' );
        wp_register_script( 'classie-js', get_template_directory_uri() . '/library/js/classie.js', array( 'jquery' ), '1.0', FALSE );        
        wp_enqueue_script( 'classie-js' );

	}

    if ( ! is_admin() ) {

        wp_register_script( 'bootstrap-js', get_template_directory_uri() . '/library/js/bootstrap.min.js', array( 'jquery' ), '3.3.1', TRUE );        
        wp_enqueue_script( 'bootstrap-js' );

    }

    if ( is_front_page() || is_page( 'how-to-invest' ) ) {

        wp_register_script( 'flexslider-js', get_template_directory_uri() . '/library/js/jquery.flexslider-min.js', array( 'jquery' ), '2.2.2', TRUE );        
        wp_enqueue_script( 'flexslider-js' );

    }

    if ( is_page( 'individual' ) ) {

    	wp_register_script( 'validate-js', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js', array( 'jquery' ), '1.11.1', TRUE );
        wp_enqueue_script( 'validate-js' );

        wp_register_script( 'multi-part-aiq-js', get_template_directory_uri() . '/library/js/multipart-aiq.js', array( 'jquery' ), '1.0', TRUE ); 
        wp_enqueue_script( 'multi-part-aiq-js' );
    }

    if ( is_singular( 'investment' ) ) {

		wp_enqueue_script( 'investments-js', get_template_directory_uri() . '/library/js/investments.js', array( 'jquery' ), '1.0', TRUE );
		
		// $offering_pdf = get_post_meta( get_the_id(), 'investment_offering', true );
		// $offering_pdf_url = wp_get_attachment_url( $offering_pdf );
		$offering_pdf_url = home_url( '/?invoffpdf=' . get_the_id() );
		wp_localize_script( 'investments-js', 'Investment', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ), 
			'id' => get_the_id(),
			'watchlist_nonce' => wp_create_nonce( 'watchlist-nonce' ),
			'readlist_nonce' => wp_create_nonce( 'readlist-nonce' ),
			'offering_pdf' => $offering_pdf_url,
			) 
		);

    }

    if ( is_page( 'my-account' ) ) {

		wp_enqueue_script( 'myaccount-js', get_template_directory_uri() . '/library/js/my-account.js', array( 'jquery' ), '1.0', TRUE );
		
		wp_localize_script( 'myaccount-js', 'MyAccount', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ), 
			'account_nonce' => wp_create_nonce( 'account-nonce' ),
			'address_nonce' => wp_create_nonce( 'address-nonce' ),
			'password_nonce' => wp_create_nonce( 'password-nonce' ),
			) 
		);

    }

    if ( is_page( 'register' ) ) {

		wp_enqueue_script( 'register-js', get_template_directory_uri() . '/library/js/register.js', array( 'jquery' ), '1.0', TRUE  );

    }

}

function skb_styles() {   

    if ( is_front_page() || is_page( 'how-to-invest' ) ) {
		
		wp_register_style( 'flexslider', get_template_directory_uri() . '/library/css/flexslider.css', array(), '2.2.2' );    	
	    wp_enqueue_style( 'flexslider' );

    }

    wp_register_style( 'bootstrap', get_template_directory_uri() . '/library/css/bootstrap.css', array(), '3.3.1' );
    wp_register_style( 'skb', get_template_directory_uri() . '/library/css/skb.css', array('dashicons'), '0.2' );
    wp_enqueue_style( 'bootstrap' );
    wp_enqueue_style( 'skb' );

}

function skb_login_check() {

	if ( isset( $_POST['log_in'] ) ) {

		if ( isset( $_POST['remember'] ) && 'yes' == $_POST['remember'] ) {

			$remember = true;

		} else {

			$remember = false;

		}

	    log_in_user( $_POST['username'], $_POST['password'], $remember );
	
	}

}


function skb_register_check() {

	if ( isset( $_POST['register'] ) ) {

		global $skb_error;

	    if ( strlen( $_POST['password'] ) < 8 ) {
	        $skb_error = new WP_Error( 'skb_password', 'Password length must be at least 8 characters.' );
	    }

	    if ( ! is_email( $_POST['email'] ) ) {
	        $skb_error = new WP_Error( 'skb_email', 'Please enter a valid email address.' );
	    }

	    if ( email_exists( $_POST['email'] ) ) {
	        $skb_error = new WP_Error( 'skb_email', 'The email entered is already in use.' );
	    }

	    if ( ! is_wp_error( $skb_error ) ) {
		    register_user( $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'] );
		}

	}

}


function remove_admin_bar() {

	if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {

		show_admin_bar( false );

	}

}



function skb_manage_investment_posts_custom_column( $column_name, $post_id ) {
 
	setlocale(LC_MONETARY, 'en_US');

	switch ( $column_name ) {
		case 'photo':
			if ( has_post_thumbnail() ) {
		        echo get_the_post_thumbnail( $post_id, array( 100, 100 ) );
			} else {
				echo '<img src="http://placehold.it/100x100&text=SKB" title="No Featured Image">';
			}
	        break;
		case 'location':
	        echo get_post_meta( $post_id, 'investment_city', true ) . ', ' . get_post_meta( $post_id, 'investment_state', true );
	        break;
		case 'status':
			$terms = wp_get_post_terms( $post_id, 'investment_category', array("fields" => "names") );
	        echo $terms[0];
	        break;
		case 'featured':
			$featured = get_post_meta( $post_id, 'investment_featured', true );
	        echo $featured;
	        break;
		case 'goal':
	        echo money_format( '%(#10.2n', get_post_meta( $post_id, 'investment_goal', true ) );
	        break;
		case 'funded':
	        echo money_format( '%(#10.2n', '000' );
	        break;
    }
}


function skb_manage_investment_update_posts_custom_column( $column_name, $post_id ) {
 
	switch ( $column_name ) {
		case 'investment':
	        echo get_the_title( get_post_meta( $post_id, 'investment_id', true ) );
	        break;
    }
}


function skb_manage_team_member_posts_custom_column( $column_name, $post_id ) {
 
	switch ( $column_name ) {
		case 'photo':
			if ( has_post_thumbnail() ) {
		        echo get_the_post_thumbnail( $post_id, array( 100, 100 ) );
			} else {
				echo '<img src="http://placehold.it/100x100&text=SKB" title="No Featured Image">';
			}
	        break;
		case 'description':
	        echo get_post_meta( $post_id, 'team_member_title', true );
	        break;
    }
}

function skb_manage_partner_posts_custom_column( $column_name, $post_id ) {
 
	switch ( $column_name ) {
		case 'logo':
			if ( has_post_thumbnail() ) {
		        echo get_the_post_thumbnail( $post_id, array( 100, 100 ) );
			} else {
				echo '<img src="http://placehold.it/100x100&text=SKB" title="No Featured Image">';
			}
	        break;
		case 'link':
	        echo get_post_meta( $post_id, 'partner_link', true );
	        break;
    }
}

function skb_manage_faq_posts_custom_column( $column_name, $post_id ) {
 
	switch ( $column_name ) {
		case 'answer':
	        echo get_post_meta( $post_id, 'faq_answer', true );
	        break;
    }
}

function skb_manage_glossary_term_posts_custom_column( $column_name, $post_id ) {
 
	switch ( $column_name ) {
		case 'definition':
	        echo get_post_meta( $post_id, 'glossary_term_definition', true );
	        break;
    }
}

function skb_custom_post_type_queries( $query ) {

    if ( $query->is_main_query() && is_post_type_archive( 'faq' ) || is_post_type_archive( 'investment' ) ) {
        // On the faq custom post type we want all the faqs to display on one page
        $query->set( 'posts_per_page', -1 );
        return;
    }

    if ( $query->is_main_query() && is_post_type_archive( 'glossary_term' ) ) {
        // On the glossary term custom post type we want all the terms to display on one page
        $query->set( 'posts_per_page', -1 );
        $query->set( 'order', 'ASC' );
        $query->set( 'orderby', 'title' );
        return;
    }

}


function add_to_watch_list() {

	$nonce = $_REQUEST['watchlist_nonce'];

	if ( wp_verify_nonce( $nonce, 'watchlist-nonce') ) {

		$investment_id = $_REQUEST['investment_id'];

	    if ( current_user_can( 'view_investments' ) ) {

			$user_id = get_current_user_id();

			add_user_meta( $user_id, 'watch_list', $investment_id );
			add_post_meta( $investment_id, '_watch_list', $user_id, false );

		    $response = 'This investment has been added to your <a class="alert-link" href="' . home_url( '/dashboard' ) . '">watch list</a>.';

		} else {

		    $response = 'Sorry, you do not have permission to add investments to a watch list.';

		}

	    echo $response;

	}

	exit();
}


function remove_from_watch_list() {

	$nonce = $_REQUEST['watchlist_nonce'];

	if ( wp_verify_nonce( $nonce, 'watchlist-nonce') ) {

		$investment_id = $_REQUEST['investment_id'];

	    if ( current_user_can( 'view_investments' ) ) {

			$user_id = get_current_user_id();

			delete_user_meta( $user_id, 'watch_list', $investment_id );
			delete_post_meta( $investment_id, '_watch_list', $user_id );

		    $response = 'This investment has been removed from your <a class="alert-link" href="' . home_url( '/dashboard' ) . '">watch list</a>.';

		} else {

		    $response = 'Sorry, you do not have permission to remove investments from a watch list.';

		}

	    echo $response;

	}

	exit();
}


function mark_offering_read() {

	$nonce = $_REQUEST['readlist_nonce'];

	if ( wp_verify_nonce( $nonce, 'readlist-nonce') ) {

		$investment_id = $_REQUEST['investment_id'];

	    if ( current_user_can( 'view_investments' ) ) {

			$user_id = get_current_user_id();

			add_user_meta( $user_id, 'read_list', $investment_id );
			add_post_meta( $investment_id, '_read_list', $user_id, false );

		}

	}

	exit();
}


function download_offering_pdf() {

	if ( isset( $_REQUEST["invoffpdf"] ) && is_user_logged_in() ) {

		$investment_id = $_REQUEST["invoffpdf"];

		$current_user = wp_get_current_user();
		$current_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
		$investment_title = safe_filename( get_the_title( $investment_id ) );

		require_once( TEMPLATEPATH . '/library/fpdf/fpdf.php' );
		require_once( TEMPLATEPATH . '/library/fpdi/fpdi.php' );

		$pdf = new FPDI();

		$offering_id = get_post_meta( $investment_id, 'investment_offering', true );
		$offering_pdf = get_attached_file( $offering_id );
		$pages = $pdf->setSourceFile( $offering_pdf );

		for ( $page = 1; $page <= $pages; $page++ ) {
			
			$pdf->importPage( $page );
			$pdf->addPage();
			$pdf->useTemplate( $page );
			$pdf->SetFont( 'Helvetica' );
			$pdf->SetTextColor( 255, 0, 0 );
			$pdf->SetXY( 30, 265 );	
			$pdf->Write( 8, 'Confidential - For Intended Recipient ' . $current_name .' - IM - ' . date("YmdHis") );

		}

		$pdf->Output( $investment_title . '-IM-' . date("YmdHis") . '.pdf', 'D' );

		exit();

	} 

}


function safe_filename($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}


function update_user_account() {
	
	$nonce = $_POST['account_nonce'];

	if ( wp_verify_nonce( $nonce, 'account-nonce') ) {

	    if ( isset( $_POST['first_name'] ) && isset( $_POST['last_name'] ) && isset( $_POST['phone'] ) ) {

			$user_id = get_current_user_id();
			$user_meta = array_map( function( $a ) { return $a[0]; }, get_user_meta( $user_id ) );
	
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$phone = $_POST['phone'];

			update_user_meta( $user_id, 'first_name', $first_name, $user_meta['first_name'] );
			update_user_meta( $user_id, 'last_name', $last_name, $user_meta['last_name'] );
			update_user_meta( $user_id, 'phone', $phone, $user_meta['phone'] );

		    $response = 'Your account info has been updated.';

		}

	} else {

	    $response = 'Your don\'t have permission to update your account.';

	}

	echo $response;

	exit();
}


function update_user_address() {

	$nonce = $_POST['address_nonce'];

	if ( wp_verify_nonce( $nonce, 'address-nonce') ) {

		$user_id = get_current_user_id();
		$user_meta = array_map( function( $a ) { return $a[0]; }, get_user_meta( $user_id ) );

		$address_1 = $_POST['address_1'];
		$address_2 = $_POST['address_2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$postal_code = $_POST['postal_code'];

		update_user_meta( $user_id, 'address_1', $address_1, $user_meta['address_1'] );
		update_user_meta( $user_id, 'address_2', $address_2, $user_meta['address_2'] );
		update_user_meta( $user_id, 'city', $city, $user_meta['city'] );
		update_user_meta( $user_id, 'state', $state, $user_meta['state'] );
		update_user_meta( $user_id, 'postal_code', $postal_code, $user_meta['postal_code'] );

	    $response = 'Your address has been updated.';

	} else {

	    $response = 'Your don\'t have permission to update your address.';

	}

    echo $response;

	exit();
}


function update_user_password() {
	
	$nonce = $_POST['password_nonce'];

	if ( wp_verify_nonce( $nonce, 'password-nonce') ) {

			$user_id = get_current_user_id();

			$current_password = $_POST['current_password'];
			$new_password = $_POST['new_password'];

			$user = wp_update_user( array( 'ID' => $user_id, 'user_pass' => $new_password ) );

			if ( is_wp_error( $user ) ) {

				$response = $user->get_error_message();

			} else {

			    $response = 'Your password has been updated.';

			}

	} else {

	    $response = 'Your don\'t have permission to update your password.';

	}

    echo $response;

	exit();
}


/* When a glossary item is saved, save our custom data */
function skb_save_first_letter( $post_id ) {

    // Verify if this is an auto save routine which in this case we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

        return $post_id;

    }

    // check post type 
    $limitPostTypes = array( 'glossary_term' );
    if ( ! in_array( $_POST['post_type'], $limitPostTypes ) ) {

        return $post_id;

    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {

        return $post_id;

    }

    // OK, we're authenticated: we need to find and save the data
    $taxonomy = 'glossary_category';

    //set term as first letter of post title, lower case
    wp_set_post_terms( $post_id, strtolower(substr($_POST['post_title'], 0, 1)), $taxonomy );

    //delete the transient that is storing the alphabet letters
    delete_transient( 'skb_glossary_alphabet');

}


//create array from existing posts
function skb_run_once(){

    if ( false === get_transient( 'skb_run_once' ) ) {

        $taxonomy = 'glossary_category';
        $alphabet = array();

        $posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'glossary_term' ) );

        foreach( $posts as $p ) :
        //set term as first letter of post title, lower case
        wp_set_post_terms( $p->ID, strtoupper(substr($p->post_title, 0, 1)), $taxonomy );
        endforeach;

        set_transient( 'skb_run_once', 'true' );

    }

}

function user_role_update( $user_id, $new_role, $old_role ) {

    if ( $old_role = 'waiting_investor' && $new_role == 'approved_investor' ) {

		$theme_settings = get_option('skb_theme_settings');

		$from_name = $theme_settings['skb_from_name'];
		$from_email = $theme_settings['skb_from_email'];

		$user_info = get_userdata( $user_id );
		$user_meta = array_map( function( $a ) { return $a[0]; }, get_user_meta( $user_id ) );

		$to_name = $user_meta['first_name'] . ' ' . $user_meta['last_name'];
		$to_email = $user_info->user_email;

		$headers = 'From: ' . $from_name . ' <' . $from_email . '>' . PHP_EOL;	
		$to = $to_name . ' <' . $to_email . '>';

		// Send Welcome Email
		$subject = $theme_settings['welcome_subject'];
		$email_title = $subject;
		$email_body = 'Dear ' . $to_name . ',<br><br>';
		$email_body .= do_shortcode( wpautop( $theme_settings['welcome_message'] ) );
		$email_footer = '';

		// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
		add_filter( 'wp_mail_content_type', 'set_html_content_type' );

		ob_start();
		include( locate_template( 'library/templates/email-basic.php', false, true ) );
		$message = ob_get_clean();

		wp_mail( $to, $subject, $message, $headers );

		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

    }

}

function waiting_investor( $user_id, $waiting_phase ) {

	$theme_settings = get_option('skb_theme_settings');

	$from_name = $theme_settings['skb_from_name'];
	$from_email = $theme_settings['skb_from_email'];

	$user_info = get_userdata( $user_id );
	$user_meta = array_map( function( $a ) { return $a[0]; }, get_user_meta( $user_id ) );

	$to_name = $user_meta['first_name'] . ' ' . $user_meta['last_name'];
	$to_email = $user_info->user_email;

	$headers = 'From: ' . $from_name . ' <' . $from_email . '>' . PHP_EOL;	
	$to = $to_name . ' <' . $to_email . '>';

	switch ( $waiting_phase ) {

		case '1':
			// Send First Email
			$subject = 'waiting_one_subject';
			$message = 'waiting_one_message';
			break;
		case '2':
			// Send Second Email
			$subject = 'waiting_two_subject';
			$message = 'waiting_two_message';
			break;
		default:
			exit;
			
	}

	$subject = $theme_settings[$subject];
	$email_title = $subject;
	$email_body = 'Dear ' . $user_meta['first_name'] . ',' . PHP_EOL;
	$email_body .= do_shortcode( wpautop( $theme_settings[$message] ) );
	$email_footer = '';

	// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
	add_filter( 'wp_mail_content_type', 'set_html_content_type' );

	ob_start();
	include( locate_template( 'library/templates/email-basic.php', false, true ) );
	$message = ob_get_clean();

	wp_mail( $to, $subject, $message, $headers );

	remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

}