<?php

  include('../security/check_permission.php');
  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  $ID = mysql_real_escape_string( $_GET['ID'] );
  $userID = $_SESSION['userID'];
  
  if (! $ID) DIE('Invalid or no parameters!');
  
  $query = "SELECT ID FROM sites WHERE ID = " . $ID . " AND user_ID = " . $userID;
  $site = $db->query( $query );
  if (mysql_fetch_array($site)){
  	$query = "DROP TABLE search" . $ID . "; "; //drop search table
  	$db->query( $query );
  	
  	$query = "DROP TABLE stats" . $ID . "; "; //drop stats table
  	$db->query( $query );
  	
  	$query = "DELETE FROM sites WHERE ID = " . $ID; //remove record from sites
  	$db->query( $query );
  }
  $db->close();

?>

<p class="center">
  Your site and all of its data has been successfully removed from our system.
</p>