<?php
// Database configuration - uses environment variables for Vercel deployment
$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'e_commerce_db';
$port = getenv('DB_PORT') ?: '3306';

// Connect to database
$conn = mysqli_connect($servername, $username, $password, $database, $port);
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>