<?php
  
  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  
  $email = mysql_real_escape_string( $_GET['email'] );
  $password = mysql_real_escape_string( $_GET['password'] );
  
  $query = "SELECT ID FROM users WHERE email = \"" . $email . "\" AND password = \"". $password ."\"";
  $user = $db->query( $query );
  if ( $user = mysql_fetch_array( $user )){
  	session_start();
  	$_SESSION['userID'] = $user['ID'];
  	session_write_close();
  }
  
  $db->close();
?>