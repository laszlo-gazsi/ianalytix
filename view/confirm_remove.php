<?php
  include('../security/check_permission.php');
  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  $ID = mysql_real_escape_string( $_GET['ID'] );
  
  if (! $ID) DIE('Invalid or no parameters!');
  $query = "SELECT url FROM sites WHERE ID = " . $ID;
  $url = $db->query( $query );
  $url = mysql_fetch_array( $url );
  $url = $url['url'];
  $db->close();
?>

<p class="center">
  Are you sure you want to remove <b><?php echo $url; ?></b>?
</p>

<p class="center">
  <button class="button" onclick="remove_yes(<?php echo $ID; ?>)">Yes</button>
  <button class="button" onclick="remove_no()">No</button>
</p>