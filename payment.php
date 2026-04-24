<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: my_bookings.php");
    exit();
}

$booking_id = $conn->real_escape_string($_GET['id']);
$user_id = $_SESSION['user_id'];

// Verify booking belongs to user
$sql = "SELECT b.*, r.name as room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = '$booking_id' AND b.user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: my_bookings.php");
    exit();
}

$booking = $result->fetch_assoc();

if ($booking['status'] != 'Pending') {
    $msg = "This booking has already been processed.";
    header("Location: my_bookings.php?msg=" . urlencode($msg));
    exit();
}

// Process payment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $transaction_id = "TXN" . strtoupper(uniqid()); // Mock transaction ID
    $amount = $booking['total_price'];
    
    $pay_sql = "INSERT INTO payments (booking_id, amount, payment_method, status, transaction_id) 
                VALUES ('$booking_id', '$amount', '$payment_method', 'Completed', '$transaction_id')";
                
    if ($conn->query($pay_sql) === TRUE) {
        // Update booking status
        $conn->query("UPDATE bookings SET status = 'Confirmed' WHERE id = '$booking_id'");
        
        header("Location: my_bookings.php?success=Payment successful");
        exit();
    } else {
        $error = "Payment failed: " . $conn->error;
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-credit-card fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold" style="color: var(--primary-color);">Make Payment</h2>
                        <p class="text-muted">Complete Your Booking Securely</p>
                    </div>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger rounded-pill"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info rounded-3 mb-4">
                        <h5 class="fw-bold mb-2">Booking Summary</h5>
                        <p class="mb-1"><strong>Room:</strong> <?php echo htmlspecialchars($booking['room_name']); ?></p>
                        <p class="mb-1"><strong>Dates:</strong> <?php echo $booking['check_in_date']; ?> To <?php echo $booking['check_out_date']; ?></p>
                        <h4 class="mt-3 text-center fw-bold">Total To Pay: <span class="text-danger">$<?php echo number_format($booking['total_price'], 2); ?></span></h4>
                    </div>
                    
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Debit Card">Debit Card</option>
                                <option value="PayPal">PayPal</option>
                            </select>
                        </div>
                        
                        <!-- Mock Card Details -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Card Number</label>
                            <input type="text" class="form-control" placeholder="**** **** **** ****" required>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label fw-bold">Expiry Date</label>
                                <input type="text" class="form-control" placeholder="MM/YY" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">CVV</label>
                                <input type="text" class="form-control" placeholder="***" required>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill"><i class="fa-solid fa-lock me-2"></i>Pay Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
