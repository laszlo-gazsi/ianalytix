<?php
  require_once '../validation/user_validation.php';
  
  if (is_logged_in() && is_active()){
  	?>
  	  <ul>
        <li><a href="http://ianalytix.info">Home</a></li>
        <li><a href="Javascript: void(0)" onclick="load_users_sites(0)">Sites</a></li>
        <li><a href="Javascript: void(0)" onclick="logout()">Logout</a></li>
      </ul>
    <?php
  }
  else {
  	?>
  	  <ul>
        <li><a href="http://ianalytix.info">Home</a></li>
        <li><a href="Javascript: void(0);" onclick="load_user_registration_form()">Register</a></li>
      </ul>
  	<?php
  }
?>