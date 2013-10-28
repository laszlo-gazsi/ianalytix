<?php

  require_once '../data/database.class.php';
  
  $db = new DataBase();
  $db->connect();
  
  $email = mysql_real_escape_string( $_GET['email'] );
  $password = mysql_real_escape_string( $_GET['password'] );
  $code = time();
  
  //inserting new record in the users table
  $query = "INSERT INTO users (email, password, code, date_joined) 
    VALUES(\"". $email ."\", \"". $password ."\", \"". $code ."\", \"". date('Y-m-d H:i:s') ."\")";
  $db->query( $query );
  
  $db->close();
  
  //sending the activation code to the user
  $message = 'Thank you for registering at <a href="http://ianalytics.info">iAnalytix</a>. Before 
    you can use our system, you have to activate your account by entering the following code next
    time you log in:<br /><br />
    <b>Activation code:</b> '. $code .'<br /><br />
    Thank you.';
  
  $headers = 'From: donotreply@ianalytix.info' . "\r\n" .
      'Content-type: text/html; charset=iso-8859-1';
  mail($email, 'iAnalytix - Activation Code', $message, $headers);
  
?>

<div id="box">
  <h2>Successful Registration</h2>
  Thank you for registering! Please check your inbox for the activation code that you will be required to
  enter when signing in for the first time.
</div>