<?php
/**
 * Created by PhpStorm.
 * User: Dion
 * Date: 13-3-2017
 * Time: 15:51
 */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "degokker";
try {
//Creating connection for mysql
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}
?>
