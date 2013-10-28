<?php

  require_once '../data/database.class.php';
  require_once '../validation/user_validation.php';
  
  if (! is_logged_in() || is_active() ) DIE('ACCESS DENIED!');
  
  session_start();
  $ID = $_SESSION['userID'];
  session_write_close();
  
  $db = new DataBase();
  $db->connect();
  
  $user_code = mysql_real_escape_string( $_GET['code'] );
  $query = "SELECT code FROM users WHERE ID = " . $ID;
  $code = $db->query( $query );
  $code = mysql_fetch_array( $code );
  
  if ( $code['0'] == $user_code ){
  	//updating user record
  	$query = "UPDATE users SET active = 1 WHERE ID = " . $ID;
  	$db->query( $query );
  }
  else {
  	include '../view/main_left_activation_error.html';
  }
  
  $db->close();

?>