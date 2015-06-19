<?php
/*
Title: Transactions
Order: 40
Flow: User
Tab Order: 40
*/
   
  piklist('include_user_profile_fields', array(
    'meta_boxes' => array(
      'Investor Transactions',
    )
  ));

  piklist('shared/code-locater', array(
    'location' => __FILE__
    ,'type' => 'Workflow Tab'
  ));

?>