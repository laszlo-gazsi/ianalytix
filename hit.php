<?php

  require_once 'data/database.class.php';
  
  function get_search_engine_name($url){
    $parseURL = parse_url($url);
    $domain = $parseURL["host"];
    
    if (strstr($domain, "google")) return 'Google';
    if (strstr($domain, "yahoo")) return 'Yahoo';
    if (strstr($domain, "bing")) return 'Bing';
    
    return false;
  }
  
  function get_keywords($url){
    $parseURL = parse_url($url);
    $domain = $parseURL["host"];
    
    if (strstr($domain, "google"))$parameterName = 'q';
    if (strstr($domain, "yahoo")) $parameterName = 'p';
    if (strstr($domain, "bing")) $parameterName = 'q';
    
    //splitting the url to domain and parameters parts
    $parts = explode('?', $url);
    
    if (count($parts) < 2) return;     //no parameters part
    $parts = explode('&', $parts[1]);  //splitting parameters string to (param = value) pairs
    
    $nr = count($parts);
    for ($i = 0; $i < $nr; $i++){      //finding the part that contains the keywords
      if (strstr($parts[$i], $parameterName . '=')) {
      	$query = $parts[$i];
      	break;
      }
    }
    
    $query = str_replace($parameterName . '=','',$query);  
    $query = str_replace('+',' ',$query);
    return $query;
  }

  $database = new DataBase();
  
  //getting data
  $database->connect();
  $referrer = mysql_real_escape_string($_GET['ref']);
  $siteID = mysql_real_escape_string($_GET['site']);
  $url = $_SERVER["HTTP_REFERER"];
  
  //checking if siteID is set or not
  if (! isset($siteID)) header("Location: button.png");
  
  //checking if there is a site with that ID
  $query = 'SELECT ID FROM sites WHERE ID = ' . $siteID;
  $res = $database->query($query);
  if (! mysql_fetch_array( $res )) header("Location: button.png");
  $database->close();
  
  $identifier = 'iAnalytix' . $siteID; //cookie name
  $u = 0; //unique visitor
  $v = 0; //number of visits
  
  //checking if visitor is unique
  if (! isset($_COOKIE[$identifier])){
  	$u = 1;
  	setcookie($identifier, 'iAnalytix', mktime(24, 0, 0)); //set cookie 'till midnight
  }
  
  //checking if this is a new visit
  session_start();
  if (! isset($_SESSION[$identifier])){
  	$v = 1;
  	$_SESSION[$identifier] = 'iAnalytix'; 
  }
  session_write_close();
  
  //visitor came from a search engine
  if ( $engine = get_search_engine_name($referrer) )        //utf8_decode(urldecode
    if ( $keywords = get_keywords($referrer) ){
    	
    	$keywords = urldecode($keywords);
    	$keywords = iconv("UTF-8", "ISO-8859-2", $keywords);
    	
    	$query = 'SELECT url FROM search' .$siteID. ' WHERE url= "'. $url .'" AND se="'. $engine .'" 
      AND keywords="'. $keywords .'" AND date = CURDATE()';
      $database->connect();
      $res = $database->query($query);
      
      if (! mysql_fetch_array($res))
        $query = 'INSERT INTO search' . $siteID . ' VALUES ("'.$url.'", "'.$engine.'", "'.$keywords.'", CURDATE(), '.$u.', '.$v.', 1)'; 
      else 
        $query = 'UPDATE search' . $siteID . ' SET u=u+'.$u.', v=v+'.$v.', pviews=pviews+1 WHERE URL = "'. $url .'" 
        AND se = "'.$engine.'" AND keywords = "'.$keywords.'" AND date = CURDATE()';
        
      $database->query($query);
      $database->close();
      
      header("Location: button.png");
      DIE();
    }
  
  //visit from another site
  $query = 'SELECT url FROM stats' .$siteID. ' WHERE url= "'. $url .'" AND referrer="'. $referrer .'" 
  AND date = CURDATE()';
  $database->connect();
  $res = $database->query($query);
  
  if (! mysql_fetch_array($res))
    $query = 'INSERT INTO stats' . $siteID . ' VALUES ("'.$url.'", "'.$referrer.'", CURDATE(), '.$u.', '.$v.', 1)'; 
  else 
   	$query = 'UPDATE stats' . $siteID . ' SET u=u+'.$u.', v=v+'.$v.', pviews=pviews+1 WHERE URL = "'. $url .'" 
  	AND referrer = "'.$referrer.'" AND date = CURDATE()';
   	
  $database->query($query);
  $database->close();
  
  header("Location: button.png"); 
?>