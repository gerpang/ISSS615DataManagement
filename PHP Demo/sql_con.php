<?php
// Change these variables to connect to your own database
  $server = 'localhost';
  $user = 'root';
  $pwd = '';
  $db = 'phase2_schema';
  $conn = mysqli_connect($server, $user, $pwd, $db);
  if (!$conn)
  {  exit( 'Could not connect:'  
             .mysqli_connect_error($conn) ); 	
  }
?>