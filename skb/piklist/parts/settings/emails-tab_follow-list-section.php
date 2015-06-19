<?php
/*
Title: Investment Update - Follow List
Setting: skb_theme_settings
Tab: Emails
Order: 100
*/



piklist( 'field', array(
		'type' => 'text',
		'field' => 'investment_follow_list_subject',
		'label' => __( 'Subject' ),
		'description' => __( 'Subject of investment update follow list email.', 'piklist' ),
		'attributes' => array(
			'class' => 'regular-text'
		)
	)
);

piklist( 'field', array(
		'type' => 'textarea',
		'field' => 'investment_follow_list_message',
		'label' => __( 'Body' ),
		'description' => __( 'Message body to send in investment update follow list email. Message will be wrapped in template and email confirmation link will be added below message.', 'piklist' ),
		'value' => '',
		'template' => 'field',
		'attributes' => array (
			'rows' => 10,
			'columns' => 50,
			'class' => 'large-text',
		)
	)
);