<?php
    $server = 'localhost'; 
    $user = 'root';
    $password = '';
    $database = 'unisphere';

    $connection = mysqli_connect($server, $user, $password, $database);

    if ($connection == false) {
        die('Connection failed!' . mysqli_connect_error());
    }
?>