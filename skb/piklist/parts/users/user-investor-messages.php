<?php
/*
Title: Investor Messages
Capability: manage_options
Order: 10
Collapse: false
*/


piklist('field', array(
  'type' => 'group'
  ,'field' => 'investor_messages'
  ,'add_more' => true
  ,'label' => 'Messages'
  ,'description' => 'Add messages for the investor to view on their dashboard.  You can add as many as you want, and they can be drag-and-dropped into the order that you would like them to appear.'
  ,'fields'  => array(
    array(
        'type' => 'datepicker'
        ,'field' => 'message_date'
        ,'label' => 'Date'
        ,'columns' => 2
        ,'description' => 'Choose a date'
        ,'options' => array(
          'dateFormat' => 'M d, yy'
        )
        ,'attributes' => array(
          'size' => 12
        )
        ,'value' => date('M d, Y', time() )
    )
    ,array(
        'type' => 'select',
        'field' => 'message_investment',
        'label' => 'Investment',
        'columns' => 5,
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
    )
    ,array(
      'type' => 'text'
      ,'field' => 'message_investment_entity'
      ,'label' => 'Investment Entity'
      ,'columns' => 5
    )
    ,array(
      'type' => 'text'
      ,'field' => 'message_subject'
      ,'label' => 'Subject'
      ,'columns' => 12
    )
    ,array(
      'type' => 'textarea'
      ,'field' => 'investor_message'
      ,'label' => 'Message'
      ,'columns' => 12
      ,'description' => ''
      ,'value' => ''
      ,'options' => array(
        'textarea_rows' => 5
      )
    )
  )
));

piklist('shared/code-locater', array(
  'location' => __FILE__
  ,'type' => 'Setting'
));

?>