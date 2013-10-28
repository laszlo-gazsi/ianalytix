<?php

  require_once '../validation/user_validation.php';
  
  if ( is_logged_in() ){
  	//displaying user info
  	include 'user_info.php';
  }
  else {
  	//displaying login form
  	include 'login_form.html';
  }
  
?>