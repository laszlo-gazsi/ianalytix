<?php

  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  
  $email = mysql_real_escape_string( $_GET['email'] );
  $used = $db->query( "SELECT count(*) FROM users WHERE email = \"" . $email . "\"" ); 
  
  $used = mysql_fetch_array( $used );
  if ((string)0 === (string)$used['0']) echo 'ok';
  
  $db->close();
  
?>