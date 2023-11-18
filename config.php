<?php
    // config credential : host, dbname, user, password
    $host = "localhost";
    $dbname   = "auth_php";
    $user = "root";
    $pass = "";

    $connect = new PDO("mysql:host=$host;dbname=$dbname;", $user, $pass);

    // echo "db connected";
?>