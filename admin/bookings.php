<?php
require_once '../includes/db.php';
require_once 'header.php';

// Handle status updates
if (isset($_GET['action']) && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $action = $_GET['action'];
    
    $valid_actions = ['confirm' => 'Confirmed', 'cancel' => 'Cancelled', 'checkin' => 'Checked-in', 'checkout' => 'Checked-out'];
    
    if (array_key_exists($action, $valid_actions)) {
        $new_status = $valid_actions[$action];
        $conn->query("UPDATE bookings SET status = '$new_status' WHERE id = '$id'");
        
        // If checked out or cancelled, free the room
        if ($new_status == 'Checked-out' || $new_status == 'Cancelled') {
            $b_res = $conn->query("SELECT room_id FROM bookings WHERE id = '$id'");
            if ($b_res->num_rows > 0) {
                $r_id = $b_res->fetch_assoc()['room_id'];
                $conn->query("UPDATE rooms SET status = 'Available' WHERE id = '$r_id'");
            }
        }
        
        header("Location: bookings.php?success=Booking status updated to $new_status");
        exit();
    }
}

$sql = "SELECT b.*, u.name as user_name, u.email, u.phone, r.name as room_name 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN rooms r ON b.room_id = r.id 
        ORDER BY b.id DESC";
$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Manage Bookings</h3>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="py-3">Guest Details</th>
                        <th class="py-3">Room</th>
                        <th class="py-3">Dates</th>
                        <th class="py-3">Status</th>
                        <th class="px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-3 fw-bold">#<?php echo $row['id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($row['user_name']); ?><br>
                                    <small class="text-muted"><i class="fa-solid fa-envelope me-1"></i><?php echo htmlspecialchars($row['email']); ?></small><br>
                                    <small class="text-muted"><i class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($row['phone']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['room_name']); ?></td>
                                <td>
                                    <small class="d-block text-nowrap"><strong class="text-muted">In:</strong> <?php echo $row['check_in_date']; ?></small>
                                    <small class="d-block text-nowrap"><strong class="text-muted">Out:</strong> <?php echo $row['check_out_date']; ?></small>
                                </td>
                                <td>
                                    <?php 
                                    $badge = 'bg-secondary';
                                    if ($row['status'] == 'Confirmed') $badge = 'bg-primary';
                                    elseif ($row['status'] == 'Checked-in') $badge = 'bg-success';
                                    elseif ($row['status'] == 'Pending') $badge = 'bg-warning text-dark';
                                    elseif ($row['status'] == 'Cancelled') $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $badge; ?> rounded-pill"><?php echo $row['status']; ?></span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <?php if($row['status'] == 'Pending'): ?>
                                                <li><a class="dropdown-item text-success" href="?action=confirm&id=<?php echo $row['id']; ?>">Confirm Booking</a></li>
                                                <li><a class="dropdown-item text-danger" href="?action=cancel&id=<?php echo $row['id']; ?>">Cancel Booking</a></li>
                                            <?php endif; ?>
                                            
                                            <?php if($row['status'] == 'Confirmed'): ?>
                                                <li><a class="dropdown-item text-success" href="?action=checkin&id=<?php echo $row['id']; ?>">Mark Checked-In</a></li>
                                                <li><a class="dropdown-item text-danger" href="?action=cancel&id=<?php echo $row['id']; ?>">Cancel Booking</a></li>
                                            <?php endif; ?>
                                            
                                            <?php if($row['status'] == 'Checked-in'): ?>
                                                <li><a class="dropdown-item text-secondary" href="?action=checkout&id=<?php echo $row['id']; ?>">Mark Checked-Out</a></li>
                                            <?php endif; ?>
                                            
                                            <?php if($row['status'] == 'Checked-out' || $row['status'] == 'Cancelled'): ?>
                                                <li><span class="dropdown-item text-muted disabled">No Actions Available</span></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4">No Bookings Found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
