<?php 

/* Filters
-------------------------------------------------- */

add_filter( 'manage_investment_posts_columns', 'skb_manage_investment_posts_columns', 11, 1 );
add_filter( 'manage_investment_update_posts_columns', 'skb_manage_investment_update_posts_columns', 11, 1 );
add_filter( 'manage_team_member_posts_columns', 'skb_manage_team_member_posts_columns', 11, 1 );
add_filter( 'manage_partner_posts_columns', 'skb_manage_partner_posts_columns', 11, 1 );
add_filter( 'manage_faq_posts_columns', 'skb_manage_faq_posts_columns', 11, 1 );
add_filter( 'manage_glossary_term_posts_columns', 'skb_manage_glossary_term_posts_columns', 11, 1 );
add_filter( 'excerpt_more', 'skb_excerpt_more' );
add_filter( 'post_gallery', 'wp_bootstrap_gallery', 10, 2 );
add_filter( 'ninja_forms_field_wrap_class', 'prefix_add_custom_field_class_wrap', 10, 2 );
add_filter( 'ninja_forms_submission_pdf_name', 'skb_pdf_name', 20, 2 );
add_filter( 'retrieve_password_message', 'skb_retrieve_password_messsage', 10, 2 );

/* Callback Functions
-------------------------------------------------- */

function skb_manage_investment_posts_columns( $columns ) {

    return array(
        'cb' => '<input type="checkbox" />',
        'photo' => __( 'Photo' ),
        'title' => __( 'Title'),
        'location' => __( 'Location'),
        'status' => __( 'Status'),
        'featured' => __( 'Featured'),
        'goal' => __( 'Goal'),
        'funded' => __( 'Funded'),
        'date' => __( 'Date' ),
    );
	
}

function skb_manage_investment_update_posts_columns( $columns ) {

    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title (internal use only)'),
        'investment' => __( 'Investment'),
        'date' => __( 'Date' ),
    );
	
}


function skb_manage_team_member_posts_columns( $columns ) {

    return array(
        'cb' => '<input type="checkbox" />',
        'photo' => __( 'Photo' ),
        'title' => __( 'Name'),
        'description' => __( 'Title' ),
        'date' => __( 'Date' ),
    );
	
}

function skb_manage_partner_posts_columns( $columns ) {

    return array(
        'cb' => '<input type="checkbox" />',
        'logo' => __( 'Logo' ),
        'title' => __( 'Title'),
        'link' => __( 'Link' ),
        'date' => __( 'Date' ),
    );
	
}

function skb_manage_faq_posts_columns( $columns ) {

    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Question'),
        'answer' => __( 'Answer' ),
        'date' => __( 'Date' ),
    );
	
}

function skb_manage_glossary_term_posts_columns( $columns ) {

    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Term'),
        'definition' => __( 'Definition' ),
        'date' => __( 'Date' ),
    );
	
}

/* 
 * Remove […] and modify the standard excerpt read more link
 */
function skb_excerpt_more( $more ) {

	global $post;

	return ' ... <a class="read-more" href="' . get_permalink($post->ID) . '" role="button" title="'. __( 'Read ', 'skb-theme' ) . get_the_title($post->ID).'">'. __( 'Read more &raquo;', 'skb-theme' ) .'</a>';

}

function prefix_add_custom_field_class_wrap( $field_wrap_class, $field_id ) {
	
	$field_wrap_class .= ' form-group';
	return $field_wrap_class;

}

function skb_pdf_name( $name, $sub_id ) { 

	$name = 'skbincrowd-doc_' . $sub_id; 
	return $name; 

}

function skb_retrieve_password_messsage( $message, $key ) {

     // Replace brackets that break when message is converted to HTML
     $message = str_replace('<', '', $message);
     $message = str_replace('>', '', $message);

     // Replace line returns with <br>
     $message = str_replace("\r\n", '<br>', $message);

     return $message;
     
}