<?php
/*
Title: Investor Transactions
Capability: manage_options
Order: 10
Collapse: false
*/


piklist('field', array(
    'type' => 'group',
    'field' => 'investor_transactions',
    'add_more' => true,
    'label' => 'Investments',
    'description' => 'Add investment transactions for the investor to view on their dashboard.  You can add as many as you want, and they can be drag-and-dropped into the order that you would like them to appear.',
    'fields'  => array(
        array(
          'type' => 'datepicker'
          ,'field' => 'transaction_date'
          ,'label' => 'Date'
          ,'columns' => 4
          ,'description' => 'Choose a date'
          ,'options' => array(
            'dateFormat' => 'M d, yy'
          )
          ,'attributes' => array(
            'size' => 12
          )
          ,'value' => date('M d, Y', time() )
        ),
        array(
            'type' => 'select',
            'field' => 'transaction_investment',
            'label' => 'Investment',
            'columns' => 4,
            'choices' => piklist( 
                get_posts( 
                    array(
                        'post_type' => 'investment',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'investment_category' => array( 'active', 'coming' ),
                    ),
                    'objects'
                ),
                array( 'ID', 'post_title' )
            )
        ),
        array(
            'type' => 'text',
            'field' => 'transaction_investment_entity',
            'label' => 'Investment Entity',
            'columns' => 4
        ),
        array(
            'type' => 'file'
            ,'field' => 'transaction_document'
            ,'label' => 'Transaction Document'
            ,'columns' => 4
            ,'options' => array(
              'basic' => true
            )
        )
    )
));

piklist('shared/code-locater', array(
    'location' => __FILE__,
    'type' => 'Setting'
));
?>