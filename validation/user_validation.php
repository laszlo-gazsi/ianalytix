<?php

  require_once '../data/database.class.php';

  function is_logged_in() {
  	$ok = 0;
  	session_start();
  	if ( isset($_SESSION['userID']) ) $ok = 1;
  	session_write_close();
  	
  	if ($ok == 1) return true;
  	return false;
  }
  
  function is_active() {
  	session_start();
  	$ID = $_SESSION['userID'];
  	session_write_close();
  	
  	$db = new DataBase();
  	$db->connect();
  	$query = "SELECT active FROM users WHERE ID = " . $ID;
  	$user = $db->query( $query );
  	$user = mysql_fetch_array( $user );
  	$active = $user['0'];
  	$db->close();
  	
  	if ((string)$active === (string)1) return true;
  	return false;
  }

?>