<?php
require_once '../includes/db.php';
require_once 'header.php';

$sql = "SELECT p.*, b.check_in_date, b.check_out_date, u.name as user_name, r.name as room_name 
        FROM payments p 
        JOIN bookings b ON p.booking_id = b.id 
        JOIN users u ON b.user_id = u.id 
        JOIN rooms r ON b.room_id = r.id 
        ORDER BY p.id DESC";
$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Payment Records</h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Txn ID</th>
                        <th class="py-3">Booking ID</th>
                        <th class="py-3">Guest Name</th>
                        <th class="py-3">Room</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Method</th>
                        <th class="py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-3"><small class="text-muted"><?php echo htmlspecialchars($row['transaction_id']); ?></small></td>
                                <td class="fw-bold">#<?php echo $row['booking_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['room_name']); ?></td>
                                <td class="text-success fw-bold">Rs. <?php echo number_format($row['amount'], 2); ?></td>
                                <td>
                                    <?php
                                        $icon = 'fa-credit-card';
                                        if($row['payment_method'] == 'PayPal') $icon = 'fa-paypal';
                                        echo "<i class='fa-brands $icon me-1 text-secondary'></i>" . htmlspecialchars($row['payment_method']);
                                    ?>
                                </td>
                                <td><small><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></small></td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="fa-solid fa-check me-1"></i><?php echo $row['status']; ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center py-5 text-muted"><i class="fa-solid fa-file-invoice-dollar fa-3x mb-3 d-block"></i>No Payment Records Found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
