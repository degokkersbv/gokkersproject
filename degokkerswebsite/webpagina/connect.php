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

if (!mysqli_select_db($dbdb, mysqli_connect($host, $user, $dbpass)))
{
    echo "Connectie met de database mislukt.";
    exit();
}
else {$con = mysqli_select_db($dbdb, mysqli_connect($host, $user, $dbpass));}
?>
?>