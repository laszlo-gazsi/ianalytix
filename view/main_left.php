<?php

  require_once '../validation/user_validation.php';
  
  if (! is_logged_in() ) include 'main_left_default.html';
  else { 
  	if (! is_active() ) include 'main_left_activation_form.html';
  	else include 'sites_list.php';
  }
  
  
?>