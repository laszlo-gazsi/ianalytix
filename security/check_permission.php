<?php 
  require_once '../validation/user_validation.php';
  
  if (! is_logged_in() ) DIE('Please log in to view this page!');
  if (! is_active() ) DIE('Your account has not been activated yet. You are not allowed 
    to access this page.');
?>