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
                
  $query = 'SELECT referrer, sum(pviews) as views FROM stats'.$ID.' WHERE 
            referrer != "" AND LOCATE("'.$site['url'].'", referrer) = 0 
            AND date >= "' . $startDate . '"AND date <= "' . $endDate . '" 
            GROUP BY referrer ORDER by views DESC LIMIT 5';
  
  $res = $db->query( $query );
  $counter = 0;
  	?>
		<fieldset>
		  <legend><b>Top Referrers:</b></legend>
		  <?php
		    while ($ref = mysql_fetch_array($res)){
		    	//limit anchor to 65 chars
		    	$anchor = $ref['0'];
		    	if ( strlen($anchor) > 65 ){
		    		$anchor = substr($anchor, 0, 65);
		    		$anchor .= '...';
		    	}
		    	?>
		    	
		    	 <a href="<?php echo ($ref['0']); ?>" target="_blank" title="<?php echo ($ref['0']); ?>"><?php echo ($anchor); ?></a> (<?php echo $ref['1']; ?> hits)<br />
		    	 
		    	<?php
		    	$counter++;
		    }
		    if (! $counter){
		    	echo 'No referrers to list...<br />';
		    } else { 
		  ?>
		  <br /><a href="Javascript:void(0)" onclick="load_full_referrers(<?php echo $ID; ?>, 0)">View full report</a><br />
		  <?php
		    } 
		  ?>
		</fieldset>