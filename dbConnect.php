<?php
function db_connect() {
    static $connection;
    if(!isset($connection)) {
        $config = parse_ini_file('./private/configram.ini'); 
        $connection = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
    }
	
    if($connection === false) {
        return mysqli_connect_error(); 
    }
    return $connection;
}
// Connect to the database
$connection = db_connect();

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?> 