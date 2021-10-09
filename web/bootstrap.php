<?php


    error_reporting(E_ALL);
    ini_set('display_errors', true);
    date_default_timezone_set('America/Bogota');
    

    include 'Config.php';
    
    require __DIR__ . '/vendor/autoload.php';
    $Cookie = NULL;
    
    define('ROOT_PATH', dirname(__FILE__));
    
    include 'Clasess/DatabaseConnection.php';

    $Conn = new DatabaseConnection();
    $db = $Conn->connection();
    

?>