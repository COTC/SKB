<?php
/*
Title: Second Waiting Email
Setting: skb_theme_settings
Tab: Emails
Order: 50
*/

piklist( 'field', array(
		'type' => 'text',
		'field' => 'waiting_second_subject',
		'label' => __( 'Subject' ),
		'description' => __( 'Subject of second waiting email.', 'piklist' ),
		'attributes' => array(
			'class' => 'regular-text'
		)
	)
);

piklist( 'field', array(
		'type' => 'textarea',
		'field' => 'waiting_second_message',
		'label' => __( 'Body' ),
		'description' => __( 'Message body to send in second waiting email. Message will be wrapped in template and email confirmation link will be added below message.', 'piklist' ),
		'value' => '',
		'template' => 'field',
		'attributes' => array (
			'rows' => 10,
			'columns' => 50,
			'class' => 'large-text',
		)
	)
);