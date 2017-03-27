<?php
/**
 * Created by PhpStorm.
 * User: Dion
 * Date: 13-3-2017
 * Time: 15:51
 */
$user = "root";
$dbpass = "";
$host = "localhost:";
$dbdb = "degokker";
$table="users";

if (!mysql_select_db($dbdb, mysql_connect($host, $user, $dbpass)))
{
     echo "Connectie met de database mislukt.";
     exit();
}
else {$con = mysql_select_db($dbdb, mysql_connect($host, $user, $dbpass));}
session_start();
?>