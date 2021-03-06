<?php

  include('../security/check_permission.php');
  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  $ID = mysql_real_escape_string( $_GET['ID'] );
  $date = mysql_real_escape_string( $_GET['date'] );
  $start = mysql_real_escape_string( $_GET['start'] );
  $userID = $_SESSION['userID'];
  
  if (! $start) {
    $start = "0";
  }
  
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
            AND date <= "' . $endDate . '" GROUP BY keywords ORDER by views DESC LIMIT '. $start .',15;';
  
  $queryAll = 'SELECT count(*) FROM 
            (SELECT keywords, sum(pviews) as views FROM search'.$ID.' WHERE date >= "' . $startDate . '" 
            AND date <= "' . $endDate . '" GROUP BY keywords ORDER by views DESC) as Temp;';
  
  $res = $db->query( $query );
  $all = $db->query( $queryAll );
  $all = mysql_fetch_array( $all );
  $all = $all['0'];
  
  
  $counter = 0;
  	?>
		<fieldset>
		  <legend><b>Keywords:</b></legend>
		  <?php
		    while ($key = mysql_fetch_array($res)){
		    	?>
          <div class="item">
           <?php echo strtolower(utf8_encode($key['0'])); ?> <div class="floatRight">(<?php echo $key['1']; ?> hits)</div><br />
          </div>
		    	<?php
		    	$counter++;
		    }
		    
		    ?>
		    <div class="item control">
		    <div id="prev">
		    <?php
		    
		    //displaying prev link if needed
			  if ( $start > 0 ){
			    ?>
			       <a href="Javascript: void(0);" onclick="load_full_keywords(<?php echo $ID ?>, <?php echo $start - 15; ?>)">Prev</a>
			    <?php
			  }
			  else echo '-';
			  
			  //displaying counter
			  ?>
			    </div>
			    <div id="counter">
			      Displaying: <?php echo ($all != 0) ? $start + 1 : 0; ?> - <?php if ($start + 15 < $all) echo $start + 15; else echo $all; ?> / <?php echo $all; ?>
			    </div>
			    <div id="next">
			  <?php
			  
			  //displaying next link if needed
			  if ( $start + 15 < $all ){
			    ?>
			       <a href="Javascript: void(0);" onclick="load_full_keywords(<?php echo $ID ?>, <?php echo $start + 15; ?>)">Next</a>
			    <?php
			  }
			  else echo '-';
		    	 
		  ?>
		  </div>
		  <div class="clr"></div>
		  </div>
		  <div class="item control">
		    <a href="Javascript:void(0)" onclick="load_stats(<?php echo $ID; ?>)">Back to overview</a><br />
		  </div>
		</fieldset>