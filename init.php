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

// SQL to create tables
$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "admin" => "CREATE TABLE IF NOT EXISTS admin (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )",
    "rooms" => "CREATE TABLE IF NOT EXISTS rooms (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        type VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255),
        status ENUM('Available', 'Booked', 'Maintenance') DEFAULT 'Available',
        description TEXT,
        amenities TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "bookings" => "CREATE TABLE IF NOT EXISTS bookings (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        room_id INT(11) NOT NULL,
        check_in_date DATE NOT NULL,
        check_out_date DATE NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        status ENUM('Pending', 'Confirmed', 'Cancelled', 'Checked-in', 'Checked-out') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
    )",
    "payments" => "CREATE TABLE IF NOT EXISTS payments (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        booking_id INT(11) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        status ENUM('Pending', 'Completed', 'Failed') DEFAULT 'Pending',
        transaction_id VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
    )",
    "support_requests" => "CREATE TABLE IF NOT EXISTS support_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        status ENUM('Pending', 'Resolved') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )"
];

foreach ($tables as $name => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "<span style='color: green;'>✓ Table '$name' ready.</span><br>";
    } else {
        echo "<span style='color: red;'>✗ Error creating table '$name': " . $conn->error . "</span><br>";
    }
}

// Check if amenities column exists (for backward compatibility if script is rerun)
$check_column = $conn->query("SHOW COLUMNS FROM rooms LIKE 'amenities'");
if ($check_column->num_rows == 0) {
    $conn->query("ALTER TABLE rooms ADD COLUMN amenities TEXT DEFAULT NULL AFTER description");
}

// Insert default admin if not exists
$admin_pass = password_hash('@Admin123#EAD', PASSWORD_DEFAULT);
$admin_sql = "INSERT IGNORE INTO admin (id, username, password) VALUES (1, 'admin', '$admin_pass')";
if ($conn->query($admin_sql) === TRUE) {
    if ($conn->affected_rows > 0) {
        echo "<span style='color: blue;'>i Default Admin User Created (admin / @Admin123#EAD)</span><br>";
    } else {
        echo "<span style='color: blue;'>i Admin user already exists.</span><br>";
    }
}

$conn->close();
echo "<br><strong>Initialization complete.</strong><br><br>";
echo "<a href='index.php' style='padding: 10px 20px; background: #8C4A2F; color: white; text-decoration: none; border-radius: 5px;'>Go to Home Page</a>";
?>
