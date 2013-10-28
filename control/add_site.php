<?php
  //checking if user is logged in
  
  include('../security/check_permission.php');


  require_once '../data/database.class.php';
  
  $database = new DataBase();
  $database->connect();
  
  //getting url
  $url = mysql_real_escape_string( $_GET['url'] );
  
  //adding url to the database
  $query = 'INSERT INTO sites (url, user_ID) VALUES("'. $url .'", '. $_SESSION['userID'] .')';
  $database->query( $query );
  
  //retrieveing the ID of the inserted site
  $query = 'SELECT LAST_INSERT_ID();';
  $siteID = $database->query($query);
  $siteID = mysql_fetch_array($siteID);
  $siteID = $siteID[0];
  
  //creating stats table for the new site
  $query = 'CREATE TABLE stats'.$siteID.' (
  url VARCHAR(255) NOT NULL ,
  referrer VARCHAR(255) NULL ,
  date DATE NOT NULL ,
  u INT NULL DEFAULT 0 ,
  v INT NULL DEFAULT 0 ,
  pviews INT NULL DEFAULT 0  )';
  
  //had to remove cuz of a stupid mysql limitation, fk!!!
  //, PRIMARY KEY (`url`, `referrer`, `date`)
  $database->query($query);
  
  //creating search table for the new site
  $query = 'CREATE TABLE search'.$siteID.' (
  url VARCHAR(255) NOT NULL ,
  se VARCHAR(20) NULL ,
  keywords VARCHAR(255) NULL ,
  date DATE NOT NULL ,
  u INT NULL DEFAULT 0 ,
  v INT NULL DEFAULT 0 ,
  pviews INT NULL DEFAULT 0  )';
  $database->query($query);
  
  $database->close();
  ?>
  <p>
  Your site has been successfully added to the system. Please insert the code below in the source of each of
  your pages on your site that you want iAnalytix to track the visitors on.
  </p>
  <?php
  //displaying code to be inserted in the site
  include('../etc/code.php');
?>