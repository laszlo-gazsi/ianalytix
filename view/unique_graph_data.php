<?php

  require_once '../lib/ofc/open-flash-chart.php';
  include('../security/check_permission.php');
  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  $ID = mysql_real_escape_string( $_GET['ID'] );
  $date = mysql_real_escape_string( $_GET['date'] );
  
  if (! $ID) DIE('Invalid or no parameters!');
  
  $query = "SELECT url FROM sites WHERE ID = " . $ID . " AND user_ID = " . $userID;
  $site = $db->query( $query );
  if ( ! $site = mysql_fetch_array($site)){
    DIE('Dude, this is not your site!');
  }
  
  function roundMax( $max ){
  	$_max = (string) $max;
  	$n = strlen( $_max );
  	$firstChar = $_max[0];

  	$res = (int) $firstChar + 1;
  	for ($i = 1; $i < $n; $i++ )
  	 $res = $res * 10;
  	 
  	return $res;
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
                
  $query = 'SELECT sum(u) as uni, date 
            FROM (
            SELECT u, date FROM stats'.$ID.' WHERE date >= "' . $startDate . '" 
            AND date <= "' . $endDate . '"
            UNION ALL
            SELECT u, date FROM search'.$ID.' WHERE date >= "' . $startDate . '" 
            AND date <= "' . $endDate . '"
            ) derivedTable 
            GROUP BY date;';
  
  $res = $db->query( $query );
  $max = 5;
  
  while ( $row = mysql_fetch_array( $res )){
  	$data[] = (int) $row['uni'];
  	$labels[] = $row['date'];
  	
  	if ($row['uni'] > $max) $max = $row['uni'];
  }
  $db->close();
  
  $max = roundMax( $max );

  $chart = new open_flash_chart();
	$chart->set_title( new title( ' ' ) );
	
  $dot = new solid_dot();
  $dot->size(3)->halo_size(1)->colour('#3D5C56');
  $dot->tooltip('#x_label#<br>#val#');
  
	$area = new area();
	// set the circle line width:
	$area->set_width( 1 );
	$area->set_default_dot_style( $dot );
	$area->set_colour( '#565656' );
	$area->set_fill_colour( '#000000' );
	$area->set_fill_alpha( 0.3 );
	$area->set_values( $data );
	
	// add the area object to the chart:
	$chart->add_element( $area );
	$chart->set_bg_colour( '#FFFFFF' );
	
	$y_axis = new y_axis();
	$y_axis->set_range( 0, $max, $max / 2 );
	$y_axis->labels = null;
	$y_axis->set_offset( false );
	$y_axis->set_grid_colour( '#EEEEEE' );
	
	$x_axis = new x_axis();
	//$x_axis->set_steps( 2 );
	$x_axis->set_grid_colour( '#EEEEEE' );
	
	$x_labels = new x_axis_labels();
	$x_labels->set_steps( count($labels) / 4 );
	$x_labels->set_labels( $labels );
	
	// Add the X Axis Labels to the X Axis
	$x_axis->set_labels( $x_labels );
	
	$chart->set_number_format(0, 1, 1, 1);
	$chart->add_y_axis( $y_axis );
	$chart->set_x_axis($x_axis);
	
	echo $chart->toPrettyString();

?>