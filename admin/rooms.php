<?php
require_once '../includes/db.php';
require_once 'header.php';

$result = $conn->query("SELECT * FROM rooms ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Manage Rooms</h3>
    <a href="add_room.php" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add New Room</a>
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
                        <th class="px-4 py-3">Image</th>
                        <th class="py-3">Room Name</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Price</th>
                        <th class="py-3">Status</th>
                        <th class="px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <?php $img = !empty($row['image']) ? "../uploads/rooms/" . htmlspecialchars($row['image']) : "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&q=80&w=100"; ?>
                                    <img src="<?php echo $img; ?>" alt="Room" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td>$<?php echo number_format($row['price'], 2); ?></td>
                                <td>
                                    <?php
                                    $sbadge = 'bg-success';
                                    if ($row['status'] == 'Booked') $sbadge = 'bg-danger';
                                    elseif ($row['status'] == 'Maintenance') $sbadge = 'bg-warning text-dark';
                                    ?>
                                    <span class="badge <?php echo $sbadge; ?> rounded-pill"><?php echo $row['status']; ?></span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <a href="edit_room.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                    <a href="delete_room.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are You Sure You Want To Delete This Room?');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4">No Rooms Added Yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
