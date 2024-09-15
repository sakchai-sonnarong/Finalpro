<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "psydb";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname" , $username , $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        // echo "Connect success";
    } catch(PDOException $e){
        echo "Connect fail: " . $e->getMessage();
    }
    // $conn->query("SET sql_mode=");
?>