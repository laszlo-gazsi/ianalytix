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
  if ( ! mysql_fetch_array($site)){
    DIE('Dude, this is not your site!');
  }
  $db->close();
?>

<div id="date_picker">
<fieldset class="center">
      <legend><b>Date range:</b></legend>
      <input type="text" id="date" name="date"></input><button id="stats_button" name="stats_button" class="button" onclick="load_stat_elements(<?php echo $ID; ?>)">Display data</button>
</fieldset>
</div>

<div id="overview">
</div>

<div id="unique_graph">
</div>

<div id="pageview_graph">
</div>

<div id="top_content">
</div>

<div id="keywords">
</div>

<div id="referrers">
</div>