<?php

//session
session_start();

//détruit la session
$_SESSION = array();  
session_destroy(); 
session_start();

//redirection
header('location:index.php');

?>