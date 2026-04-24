<?php
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
                                <td>
                                    <div style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo htmlspecialchars($row['subject']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo htmlspecialchars($row['message']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if(!empty($row['image'])): ?>
                                        <i class="fa-solid fa-image text-primary"></i>
                                    <?php else: ?>
                                        <span class="text-muted small">None</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge rounded-pill <?php echo $row['status'] == 'Resolved' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end" style="white-space: nowrap; width: 180px;">
                                    <button class="btn btn-sm btn-outline-primary me-1" type="button" data-bs-toggle="collapse" data-bs-target="#details_<?php echo $row['id']; ?>" aria-expanded="false">
                                        <i class="fa-solid fa-eye me-1"></i>View details
                                    </button>
                                    <?php if($row['status'] == 'Pending'): ?>
                                        <a href="support.php?resolve=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success" title="Mark as Resolved"><i class="fa-solid fa-check"></i></a>
                                    <?php endif; ?>
                                    <a href="support.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this request?');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr class="collapse" id="details_<?php echo $row['id']; ?>">
                                <td colspan="7" class="bg-light px-4 py-4">
                                    <div class="row">
                                        <div class="<?php echo !empty($row['image']) ? 'col-md-8' : 'col-12'; ?>">
                                            <div class="mb-3 pb-3 border-bottom">
                                                <h6 class="fw-bold text-secondary mb-1">Subject:</h6>
                                                <h5 class="fw-bold text-dark mb-0" style="word-break: break-word;"><?php echo htmlspecialchars($row['subject']); ?></h5>
                                            </div>
                                            <h6 class="fw-bold mb-2 text-secondary">Full Message:</h6>
                                            <p class="text-dark mb-0" style="white-space: pre-wrap; line-height: 1.6; word-break: break-word;"><?php echo htmlspecialchars($row['message']); ?></p>
                                        </div>
                                        <?php if(!empty($row['image'])): ?>
                                            <div class="col-md-4 text-center mt-3 mt-md-0">
                                                <h6 class="fw-bold mb-3 text-secondary">Uploaded Image:</h6>
                                                <a href="../uploads/support/<?php echo $row['image']; ?>" target="_blank">
                                                    <img src="../uploads/support/<?php echo $row['image']; ?>" class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: contain; border: 1px solid #ddd;">
                                                </a>
                                                <div class="mt-2 small text-muted">Click image to view full size</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-4">No support requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
