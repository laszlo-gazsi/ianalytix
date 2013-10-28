<?php
  require_once 'data/database.class.php';
  
  $db = new DataBase();
  $query = 'SELECT ID FROM sites';
  
  $db->connect();
  $sites = $db->query( $query );
  while ($site = mysql_fetch_array( $sites )){
  	$query = 'INSERT INTO stats' . $site['0'] . ' (url, referrer, date, u, v, pviews)
  	 VALUES ("", "", CURDATE(), 0, 0, 0)';
  	$db->query( $query );
  }
  $db->close();
?>