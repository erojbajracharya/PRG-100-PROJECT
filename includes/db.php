<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define Base URL dynamically
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    // Get the project root folder name
    // This file is in /includes/db.php, so we go up two levels
    $project_root = str_replace('\\', '/', dirname(dirname(__FILE__)));
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    
    $base_dir = str_replace($doc_root, '', $project_root);
    // Ensure base_dir starts with / but doesn't end with /
    if ($base_dir == '') $base_dir = '';
    else if ($base_dir[0] !== '/') $base_dir = '/' . $base_dir;
    
    define('BASE_URL', $protocol . "://" . $host . $base_dir);
}

// Common Constants
if (!defined('ROOM_IMG_PATH')) define('ROOM_IMG_PATH', BASE_URL . '/uploads/rooms/');
if (!defined('SUPPORT_IMG_PATH')) define('SUPPORT_IMG_PATH', BASE_URL . '/uploads/support/');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_ead_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if database exists
$db_check = $conn->select_db($dbname);

if (!$db_check) {
    // If DB doesn't exist, try to redirect to init.php
    // We need to know where init.php is relative to the current script
    header("Location: " . BASE_URL . "/init.php");
    exit();
}
?>
