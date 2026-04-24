<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle cancellation
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $cancel_id = $conn->real_escape_string($_GET['cancel']);
    
    // Get booking to make sure it belongs to user and is in a state that can be cancelled
    $check_sql = "SELECT * FROM bookings WHERE id = '$cancel_id' AND user_id = '$user_id'";
    $check_res = $conn->query($check_sql);
    
    if ($check_res->num_rows > 0) {
        $b = $check_res->fetch_assoc();
        if ($b['status'] == 'Pending' || $b['status'] == 'Confirmed') {
            $conn->query("UPDATE bookings SET status = 'Cancelled' WHERE id = '$cancel_id'");
            // Free up the room
            $conn->query("UPDATE rooms SET status = 'Available' WHERE id = '{$b['room_id']}'");
            
            header("Location: my_bookings.php?success=Booking cancelled successfully");
            exit();
        }
    }
}

// Fetch user bookings
$sql = "SELECT b.*, r.name as room_name, r.type as room_type 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        WHERE b.user_id = '$user_id' 
        ORDER BY b.id DESC";
$result = $conn->query($sql);

require_once 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--primary-color);">My Bookings</h2>
    </div>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow border-0" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 rounded-start-3">Booking ID</th>
                            <th class="py-3">Room</th>
                            <th class="py-3">Check-in</th>
                            <th class="py-3">Check-out</th>
                            <th class="py-3">Total Amount</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 px-4 rounded-end-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="py-3 px-4"><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td class="py-3">
                                        <?php echo htmlspecialchars($row['room_name']); ?><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['room_type']); ?></small>
                                    </td>
                                    <td class="py-3"><?php echo $row['check_in_date']; ?></td>
                                    <td class="py-3"><?php echo $row['check_out_date']; ?></td>
                                    <td class="py-3">Rs. <?php echo number_format($row['total_price'], 2); ?></td>
                                    <td class="py-3">
                                        <?php 
                                        $badge_class = 'bg-secondary';
                                        if ($row['status'] == 'Confirmed' || $row['status'] == 'Checked-in') $badge_class = 'bg-success';
                                        elseif ($row['status'] == 'Pending') $badge_class = 'bg-warning text-dark';
                                        elseif ($row['status'] == 'Cancelled') $badge_class = 'bg-danger';
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?> rounded-pill px-3 py-2"><?php echo $row['status']; ?></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php if ($row['status'] == 'Pending'): ?>
                                            <a href="payment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary rounded-pill mb-1">Pay Now</a>
                                        <?php endif; ?>
                                        
                                        <?php if ($row['status'] == 'Pending' || $row['status'] == 'Confirmed'): ?>
                                            <a href="my_bookings.php?cancel=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Are You Sure You Want To Cancel This Booking?');">Cancel</a>
                                        <?php endif; ?>
                                        
                                        <?php if ($row['status'] == 'Cancelled' || $row['status'] == 'Checked-out'): ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fa-3x mb-3"></i>
                                    <h5>No Bookings Found</h5>
                                    <a href="rooms.php" class="btn btn-primary mt-2 rounded-pill px-4">Book A Room</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
