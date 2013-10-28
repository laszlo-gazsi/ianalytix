<?php
  include('../security/check_permission.php');
  require_once '../data/database.class.php';
  
  $database = new DataBase();
  $database->connect();
  
  $start = mysql_real_escape_string( $_GET['start'] );
  if (! $start) {
  	$start = "0";
  }
  
  $query = 'SELECT ID, url FROM sites WHERE user_ID = '. $_SESSION['userID'] .' limit '. $start .',5;';
  $sites = $database->query( $query );
  
  $query = 'SELECT count(*) FROM sites WHERE user_ID = ' . $_SESSION['userID'];
  $count = $database->query( $query );
  $count = mysql_fetch_array( $count );
  $count = $count['0'];
  
  //displaying websites of the user
  while( $site = mysql_fetch_array( $sites )){
  	?>
  	 <div class="item">
  	   <?php echo $site['url']; ?>
  	   <div class="floatRight">
  	     <a href="Javascript:void(0)" onclick="load_stats(<?php echo $site['ID']; ?>)">stats</a> 
  	     <a href="Javascript:void(0)" onclick="ask_remove_site(<?php echo $site['ID']; ?>)">remove</a>
  	   </div>
  	 </div>
  	<?php
  }
  
  $database->close();
  
  //or some message if the user has not added any sites yet
  if (! $count){
  ?>
  <div class="item">
    You have not added any sites yet. Please use the link below to add your website(s) to our system. 
  </div>
  <?php
  }
  ?>
   <div class="item control">
   <div id="prev">
  <?php
  //displaying prev link if needed
  if ( $start > 0 ){
  	?>
  	   <a href="Javascript: void(0);" onclick="load_users_sites(<?php echo $start - 5; ?>)">Prev</a>
  	<?php
  }
  else echo "-";
  
  //displaying counter
  ?>
    </div>
    <div id="counter">
      Sites: <?php echo ($count != 0) ? $start + 1 : 0; ?> - <?php if ($start + 5 < $count) echo $start + 5; else echo $count; ?> / <?php echo $count; ?>
    </div>
    <div id="next">
  <?php
  
  //displaying next link if needed
  if ( $start + 5 < $count ){
  	?>
       <a href="Javascript: void(0);" onclick="load_users_sites(<?php echo $start + 5; ?>)">Next</a>
    <?php
  }
  else echo "-";
  ?>
  </div>
  <div class="clr"></div>
  </div>
  <?php
  
?>

<div class="item control">
  <a href="Javascript: void(0)" onclick="load_add_site_form()" >Add new site</a>
</div>