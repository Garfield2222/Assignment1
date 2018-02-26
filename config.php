<?php

$servername = "localhost";
$username = "root";
$password = "root";

try{
    //PDO Database Connection object
    $conn = new PDO("mysql:host=$servername; dbname=web", $username, $password); 

    //Setting an attribute to connection catch anny error messages
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected Successfully";
}
catch(PDOException $e){

    //Display any error message if database connect fails
    echo "Connection Failed..." . $e->getMessage();
}

//Close database connection
$conn = null;

?>
