<?php
/*
Title: First Waiting Email
Setting: skb_theme_settings
Tab: Emails
Order: 30
*/

piklist( 'field', array(
		'type' => 'text',
		'field' => 'waiting_one_subject',
		'label' => __( 'Subject' ),
		'description' => __( 'Subject of first waiting email.', 'piklist' ),
		'attributes' => array(
			'class' => 'regular-text'
		)
	)
);

piklist( 'field', array(
		'type' => 'textarea',
		'field' => 'waiting_one_message',
		'label' => __( 'Body' ),
		'description' => __( 'Message body to send in first waiting email. Message will be wrapped in template and email confirmation link will be added below message.', 'piklist' ),
		'value' => '',
		'template' => 'field',
		'attributes' => array (
			'rows' => 10,
			'columns' => 50,
			'class' => 'large-text',
		)
	)
);