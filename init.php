<?php
// We don't require db.php here because db.php redirects to init.php if database doesn't exist.
// But we can define the connection parameters here or try to include a simplified version.

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_ead_db";

// Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h3>Hotel EAD System Initialization</h3>";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "<span style='color: green;'>✓ Database '$dbname' created or already exists.</span><br>";
} else {
    die("<span style='color: red;'>✗ Error creating database: " . $conn->error . "</span><br>");
}

$conn->select_db($dbname);

// Import SQL file
$sql_file = 'hotel_ead_db.sql';
if (file_exists($sql_file)) {
    $sql_content = file_get_contents($sql_file);
    
    // Split SQL into individual queries
    // This is a simple split, for complex SQL files you might need a more robust parser
    $queries = explode(";", $sql_content);
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($conn->query($query)) {
                $success_count++;
            } else {
                // Ignore some common errors like "Table already exists" if not using DROP
                if ($conn->errno != 1050) {
                    echo "<span style='color: red;'>✗ Error executing query: " . $conn->error . "</span><br>";
                    $error_count++;
                }
            }
        }
    }
    echo "<span style='color: blue;'>i Imported $success_count queries from $sql_file.</span><br>";
    if ($error_count > 0) {
        echo "<span style='color: orange;'>! Encountred $error_count errors during import.</span><br>";
    }
} else {
    die("<span style='color: red;'>✗ Error: $sql_file not found.</span><br>");
}

$conn->close();
echo "<br><strong>Initialization complete.</strong><br><br>";

echo "<a href='index.php' style='padding: 10px 20px; background: #8C4A2F; color: white; text-decoration: none; border-radius: 5px;'>Go to Home Page</a>";
?>
