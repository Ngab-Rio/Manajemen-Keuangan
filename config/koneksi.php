<?php

// require "route.php";

$host = "localhost";
$db_name = "keuangan";
$user = "root";
$pass = "root";

$conn = mysqli_connect($host, $user, $pass, $db_name);

date_default_timezone_set('Asia/Jakarta');

function logActivity($conn, $user_id, $action, $description) {
    $query = "INSERT INTO logs (user_id, action, description) 
              VALUES ('$user_id', '$action', '$description')";
    $conn->query($query);
}



?>