<?php
    
    $server = "localhost";
    $database = "tech_store";
    $username = "root";
    $password ="";

    try{
    $pdo = new PDO("mysql:host=$server;dbname=$database;",$username, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3>Successfully Connected!</h3>";
    }catch(PDOException $e){
        die("Unable to connect to the database".$e->getMessage());
    }
?>