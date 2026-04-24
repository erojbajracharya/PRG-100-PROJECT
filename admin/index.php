<?php
require_once '../includes/db.php';
require_once 'header.php';

// Get statistics
$stats = [
    'total_rooms' => $conn->query("SELECT COUNT(*) as count FROM rooms")->fetch_assoc()['count'],
    'available_rooms' => $conn->query("SELECT COUNT(*) as count FROM rooms WHERE status = 'Available'")->fetch_assoc()['count'],
    'total_bookings' => $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'],
    'pending_bookings' => $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'Pending'")->fetch_assoc()['count'],
    'total_revenue' => $conn->query("SELECT SUM(amount) as total FROM payments WHERE status = 'Completed'")->fetch_assoc()['total'] ?? 0
];

// Recent Bookings
$recent_bookings = $conn->query("SELECT b.*, u.name as user_name, r.name as room_name 
                                 FROM bookings b 
                                 JOIN users u ON b.user_id = u.id 
                                 JOIN rooms r ON b.room_id = r.id 
                                 ORDER BY b.id DESC LIMIT 5");
?>

<h3 class="fw-bold mb-4">Dashboard Overview</h3>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card p-3 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Rooms</h6>
                    <h3 class="fw-bold mb-0"><?php echo $stats['total_rooms']; ?></h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                    <i class="fa-solid fa-door-open fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Available Rooms</h6>
                    <h3 class="fw-bold mb-0"><?php echo $stats['available_rooms']; ?></h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                    <i class="fa-solid fa-check fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Pending Bookings</h6>
                    <h3 class="fw-bold mb-0"><?php echo $stats['pending_bookings']; ?></h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                    <i class="fa-solid fa-clock fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-4 border-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Revenue</h6>
                    <h3 class="fw-bold mb-0">Rs. <?php echo number_format($stats['total_revenue'], 2); ?></h3>
                </div>
                <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                    <i class="fa-solid fa-dollar-sign fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Recent Bookings</h5>
        <a href="bookings.php" class="btn btn-sm btn-outline-primary">View all</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="py-3">Guest name</th>
                        <th class="py-3">Room</th>
                        <th class="py-3">Check in - out</th>
                        <th class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($recent_bookings && $recent_bookings->num_rows > 0): ?>
                        <?php while($row = $recent_bookings->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-3">#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['room_name']); ?></td>
                                <td><small><?php echo $row['check_in_date']; ?> To <?php echo $row['check_out_date']; ?></small></td>
                                <td>
                                    <?php 
                                    $badge = 'bg-secondary';
                                    if ($row['status'] == 'Confirmed' || $row['status'] == 'Checked-in') $badge = 'bg-success';
                                    elseif ($row['status'] == 'Pending') $badge = 'bg-warning text-dark';
                                    elseif ($row['status'] == 'Cancelled') $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $badge; ?> rounded-pill"><?php echo $row['status']; ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">No recent bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
