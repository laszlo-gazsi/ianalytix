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
                
  $query = 'SELECT sum(u), sum(v), sum(pviews)
            FROM (
            SELECT u, v, pviews FROM stats'.$ID.' WHERE date >= "' . $startDate . '" 
            AND date <= "' . $endDate . '"
            UNION ALL
            SELECT u, v, pviews FROM search'.$ID.' WHERE date >= "' . $startDate . '" 
            AND date <= "' . $endDate . '"
            ) derivedTable;';
  
  $res = $db->query( $query );
  if ($overview = mysql_fetch_array($res)){
  	?>
  
		<fieldset>
		  <legend><b>Overview:</b></legend>
		  <b>URL: </b> <a href="<?php echo $site['url']; ?>" target="_blank"><?php echo $site['url']; ?></a><br />
		  <b>Visits: </b> <?php echo ($overview['1']) ? $overview['1'] : '0'; ?><br />
		  <b>Unique visitors: </b> <?php echo ($overview['0']) ? $overview['0'] : '0'; ?><br />
		  <b>Pageviews: </b> <?php echo ($overview['2']) ? $overview['2'] : '0'; ?><br />
		  <b>Pageviews / Visit: </b> <?php echo ($overview['1'] && $overview['2']) ? number_format((int) $overview['2'] / (int) $overview['1'], 2) : '-'; ?><br />
		</fieldset>

    <?php
  }
  else {
  	?>
  
    <fieldset>
      <legend><b>Overview:</b></legend>
      There is no displayable data!
    </fieldset>

    <?php
  }
 ?>