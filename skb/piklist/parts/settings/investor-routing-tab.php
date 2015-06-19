<?php
/*
Title:  Landing Pages
Setting: skb_theme_settings
Tab: Landing Pages
Tab Order: 60
Order: 10
*/

piklist( 'field', array(
        'type' => 'select',
        'field' => 'registration_landing',
        'label' => 'Registration Landing Page',
        'description' => '<p>Where to send a <strong>New Investor</strong> after submitting a registration form.</p>',
        'choices' => piklist( 
            get_posts( 
                array(
                    'post_type' => 'page',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
                'objects'
            ),
            array( 'ID', 'post_title' )
        ),
    )
);


piklist( 'field', array(
        'type' => 'select',
        'field' => 'unconfirmed_landing',
        'label' => 'Unconfirmed Landing Page',
        'description' => '<p>Where to send a <strong>New Investor</strong> who attempts to log in before confirming email.</p>',
        'choices' => piklist( 
            get_posts( 
                array(
                    'post_type' => 'page',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
                'objects'
            ),
            array( 'ID', 'post_title' )
        ),
    )
);


piklist( 'field', array(
        'type' => 'select',
        'field' => 'approved_landing',
        'label' => 'Approved Landing Page',
        'description' => '<p>Where to send a <strong>Registered Investor</strong> after submitting an AIQ form.</p>',
        'choices' => piklist( 
            get_posts( 
                array(
                    'post_type' => 'page',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
                'objects'
            ),
            array( 'ID', 'post_title' )
        ),
    )
);


piklist( 'field', array(
        'type' => 'select',
        'field' => 'existing_landing',
        'label' => 'Existing Investor Landing Page',
        'description' => '<p>Where to send a <strong>Guest or Registered User</strong> after submitting an Existing Investor form.</p>',        
        'choices' => piklist( 
            get_posts( 
                array(
                    'post_type' => 'page',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
                'objects'
            ),
            array( 'ID', 'post_title' )
        ),
    )
);

piklist( 'field', array(
        'type' => 'select',
        'field' => 'invest_landing',
        'label' => 'Invest Landing Page',
        'description' => '<p>Where to send an <strong>Registered Investor</strong> after submitting an Invest in this Deal form.</p>',        
        'choices' => piklist( 
            get_posts( 
                array(
                    'post_type' => 'page',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
                'objects'
            ),
            array( 'ID', 'post_title' )
        ),
    )
);