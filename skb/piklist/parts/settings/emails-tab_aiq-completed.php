<?php
/*
Title: AIQ Completed
Setting: skb_theme_settings
Tab: Emails
Order: 25
*/

piklist( 'field', array(
		'type' => 'text',
		'field' => 'aiq_completed_subject',
		'label' => __( 'Subject' ),
		'description' => __( 'Subject of AIQ completed email.', 'piklist' ),
		'attributes' => array(
			'class' => 'regular-text'
		)
	)
);

piklist( 'field', array(
		'type' => 'textarea',
		'field' => 'aiq_completed_message',
		'label' => __( 'Body' ),
		'description' => __( 'Message body to send in AIQ completed email. Message will be wrapped in template and email confirmation link will be added below message.', 'piklist' ),
		'value' => '',
		'template' => 'field',
		'attributes' => array (
			'rows' => 10,
			'columns' => 50,
			'class' => 'large-text',
		)
	)
);