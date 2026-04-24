<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: rooms.php");
    exit();
}

$room_id = $conn->real_escape_string($_POST['room_id']);
$check_in = $conn->real_escape_string($_POST['check_in']);
$check_out = $conn->real_escape_string($_POST['check_out']);

// Fetch room details
$sql = "SELECT * FROM rooms WHERE id = '$room_id' AND status = 'Available'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Room not available.");
}

$room = $result->fetch_assoc();

// Calculate total price
$date1 = new DateTime($check_in);
$date2 = new DateTime($check_out);
$interval = $date1->diff($date2);
$days = $interval->days;

if ($days < 1) {
    die("Invalid dates. Check-out must be after Check-in.");
}

$total_price = $days * $room['price'];

// Process final booking submission if confirmed
if (isset($_POST['confirm'])) {
    $user_id = $_SESSION['user_id'];
    
    // Create booking
    $book_sql = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price, status) 
                 VALUES ('$user_id', '$room_id', '$check_in', '$check_out', '$total_price', 'Pending')";
    
    if ($conn->query($book_sql) === TRUE) {
        $booking_id = $conn->insert_id;
        
        // Update room status
        $update_room = "UPDATE rooms SET status = 'Booked' WHERE id = '$room_id'";
        $conn->query($update_room);
        
        // Redirect to payment
        header("Location: payment.php?id=" . $booking_id);
        exit();
    } else {
        $error = "Error creating booking: " . $conn->error;
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <h2 class="fw-bold text-center mb-4" style="color: var(--primary-color);">Confirm Your Booking</h2>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger rounded-pill"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <?php $img = !empty($room['image']) ? "uploads/rooms/" . htmlspecialchars($room['image']) : "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&q=80"; ?>
                            <img src="<?php echo $img; ?>" class="img-fluid rounded shadow-sm" alt="Room Image">
                        </div>
                        <div class="col-md-7">
                            <h4 class="fw-bold mb-3"><?php echo htmlspecialchars($room['name']); ?></h4>
                            <ul class="list-unstyled lh-lg text-muted">
                                <li><i class="fa-solid fa-bed me-2 text-secondary"></i> <strong>Type:</strong> <?php echo htmlspecialchars($room['type']); ?></li>
                                <li><i class="fa-regular fa-calendar-check me-2 text-secondary"></i> <strong>Check-in:</strong> <?php echo date('F j, Y', strtotime($check_in)); ?></li>
                                <li><i class="fa-regular fa-calendar-xmark me-2 text-secondary"></i> <strong>Check-out:</strong> <?php echo date('F j, Y', strtotime($check_out)); ?></li>
                                <li><i class="fa-solid fa-moon me-2 text-secondary"></i> <strong>Duration:</strong> <?php echo $days; ?> Night(s)</li>
                                <li><i class="fa-solid fa-tag me-2 text-secondary"></i> <strong>Price Per Night:</strong> $<?php echo number_format($room['price'], 2); ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="bg-light p-4 rounded-3 text-center mb-4">
                        <h4 class="mb-0 fw-bold">Total Amount: <span class="text-danger">$<?php echo number_format($total_price, 2); ?></span></h4>
                    </div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                        <input type="hidden" name="check_in" value="<?php echo $check_in; ?>">
                        <input type="hidden" name="check_out" value="<?php echo $check_out; ?>">
                        <input type="hidden" name="confirm" value="1">
                        
                        <div class="d-flex justify-content-between">
                            <a href="room_details.php?id=<?php echo $room_id; ?>" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Go Back</a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Confirm & Pay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
