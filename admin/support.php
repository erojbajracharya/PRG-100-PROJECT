require_once '../includes/db.php';

// Handle Resolve
if (isset($_GET['resolve']) && is_numeric($_GET['resolve'])) {
    $id = $conn->real_escape_string($_GET['resolve']);
    $conn->query("UPDATE support_requests SET status = 'Resolved' WHERE id = '$id'");
    header("Location: support.php?success=Request marked as resolved");
    exit();
}

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $conn->query("DELETE FROM support_requests WHERE id = '$id'");
    header("Location: support.php?success=Request deleted");
    exit();
}

$sql = "SELECT * FROM support_requests ORDER BY created_at DESC";
$result = $conn->query($sql);

require_once 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Support Requests</h3>
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
                        <th class="px-4 py-3">Date</th>
                        <th class="py-3">Name & Email</th>
                        <th class="py-3">Subject</th>
                        <th class="py-3">Message</th>
                        <th class="py-3">Image</th>
                        <th class="py-3">Status</th>
                        <th class="px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-3 small text-muted"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td>
                                    <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($row['message']); ?>">
                                        <?php echo htmlspecialchars($row['message']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if(!empty($row['image'])): ?>
                                        <a href="../uploads/support/<?php echo $row['image']; ?>" target="_blank">
                                            <img src="../uploads/support/<?php echo $row['image']; ?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">None</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge rounded-pill <?php echo $row['status'] == 'Resolved' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <?php if($row['status'] == 'Pending'): ?>
                                        <a href="support.php?resolve=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success" title="Mark as Resolved"><i class="fa-solid fa-check"></i></a>
                                    <?php endif; ?>
                                    <a href="support.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are You Sure You Want To Delete This Request?');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-4">No Support Requests Found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
