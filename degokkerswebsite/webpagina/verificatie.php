<?php

include 'connect.php';

$naam = $_GET['name']; 
$email = $_GET['email']; 
$pass = $_GET['pass']; 
$date = $_GET['date']; 
$month = $_GET['month']; 
$year = $_GET['year']; 


mysql_query("INSERT INTO `".$dbdb."`.`".$table."` (`id`, `name`, `email`, `pass`, `date`, `month`, `year`) VALUES (NULL, '".$naam."', '".$email."', '".$pass."', '".$date."', '".$month."', '".$year."');");

