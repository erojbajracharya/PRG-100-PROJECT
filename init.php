<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS hotel_ead_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$conn->select_db("hotel_ead_db");

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

// Check if amenities column exists, if not add it
$check_column = $conn->query("SHOW COLUMNS FROM rooms LIKE 'amenities'");
if ($check_column->num_rows == 0) {
    $conn->query("ALTER TABLE rooms ADD COLUMN amenities TEXT DEFAULT NULL AFTER description");
}

foreach ($tables as $name => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table $name created successfully<br>";
    } else {
        echo "Error creating table $name: " . $conn->error . "<br>";
    }
}

// Insert or update default admin
$admin_pass = password_hash('@Admin123#EAD', PASSWORD_DEFAULT);
$admin_sql = "REPLACE INTO admin (id, username, password) VALUES (1, 'admin', '$admin_pass')";
if ($conn->query($admin_sql) === TRUE) {
    echo "Default Admin User Updated (admin / @Admin123#EAD)<br>";
}

$conn->close();
echo "<br>Initialization complete. Go to <a href='index.php'>Home Page</a>.";
?>
