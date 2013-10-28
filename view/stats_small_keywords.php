<?php

  include('../security/check_permission.php');
  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  $ID = mysql_real_escape_string( $_GET['ID'] );
  $date = mysql_real_escape_string( $_GET['date'] );
  $userID = $_SESSION['userID'];
  
  if (! $ID) DIE('Invalid or no parameters!');
  
  $query = "SELECT url FROM sites WHERE ID = " . $ID . " AND user_ID = " . $userID;
  $site = $db->query( $query );
  if ( ! $site = mysql_fetch_array($site)){
    DIE('Dude, this is not your site!');
  }
  
  //transforming date to: 2010-05-08
  $dates = explode('-', $date);
  $tempDate = explode('/', $dates[0]);
  $startDate = trim($tempDate[2]) . '-' . trim($tempDate[0]) . '-' . trim($tempDate[1]);
  
  if (count($dates) == 1)$endDate = $startDate;
  else {
  	$tempDate = explode('/', $dates[1]);
    $endDate = trim($tempDate[2]) . '-' . trim($tempDate[0]) . '-' . trim($tempDate[1]);
  }
                
  $query = 'SELECT keywords, sum(pviews) as views FROM search'.$ID.' WHERE date >= "' . $startDate . '" 
            AND date <= "' . $endDate . '" GROUP BY keywords ORDER by views DESC LIMIT 5';
  
  $res = $db->query( $query );
  $counter = 0;
  	?>
		<fieldset>
		  <legend><b>Top Keywords:</b></legend>
		  <?php
		    while ($key = mysql_fetch_array($res)){
		    	?>
		    	
		    	 <?php echo utf8_encode($key['0']); ?> (<?php echo $key['1']; ?> hits)<br />
		    	 
		    	<?php
		    	$counter++;
		    }
		    if (! $counter){
		    	echo 'No visitors came from search engines...<br />';
		    } else { 
		  ?>
		  <br /><a href="Javascript:void(0)" onclick="load_full_keywords(<?php echo $ID; ?>, 0)">View full report</a><br />
		  <?php
		    } 
		  ?>
		</fieldset>