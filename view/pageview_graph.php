<?php
  
  include('../security/check_permission.php');
  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  $ID = mysql_real_escape_string( $_GET['ID'] );
  $date = mysql_real_escape_string( $_GET['date'] );
  $userID = $_SESSION['userID'];
  $db->close();
  
  if (! $ID || ! $date) DIE('Invalid or no parameters!');

  $url = 'http://ianalytix.info/view/pageview_graph_data.php?ID=' . $ID . '&date=' . $date;
  $url = urlencode( $url );
?>

<fieldset>
      <legend><b>Pageviews:</b></legend>
        <center>
        <div style="position: relative; z-index: 2;">
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
				        codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
				        width="500" height="150" id="my-graph" align="middle">
				    <param name="allowScriptAccess" value="sameDomain" />
				    <param name="movie" value="/flash/open-flash-chart.swf?data-file=<?php echo $url; ?>" />
				    <param name="quality" value="high" />
				    <embed src="lib/ofc/open-flash-chart.swf?data-file=<?php echo $url; ?>" 
				           wmode="transparent" 
				           quality="high"
				           bgcolor="#FFFFFF" width="500" height="150"
				           name="open-flash-chart" align="middle"
				           allowScriptAccess="sameDomain" type="application/x-shockwave-flash"
				           pluginspage="http://www.macromedia.com/go/getflashplayer">
				    </embed>
				</object>
				</div>
				</center>
</fieldset>