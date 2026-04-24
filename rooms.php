<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Search and Filter functionality
$where = "WHERE status = 'Available'";
$type_filter = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

if (!empty($type_filter)) {
    $where .= " AND type = '$type_filter'";
}
if (!empty($search)) {
    $where .= " AND name LIKE '%$search%'";
}

$sql = "SELECT * FROM rooms $where ORDER BY id DESC";
$result = $conn->query($sql);

// Get unique room types for filter
$type_sql = "SELECT DISTINCT type FROM rooms WHERE status = 'Available'";
$type_result = $conn->query($type_sql);
?>

<div class="container py-5 mt-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold" style="color: var(--primary-color);">Our Rooms</h1>
    </div>

    <!-- Search and Filter Bar -->
    <div class="card shadow-sm border-0 mb-5" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search By Room Name..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <select name="type" class="form-select">
                        <option value="">All Room Types</option>
                        <?php if($type_result && $type_result->num_rows > 0): ?>
                            <?php while($t = $type_result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($t['type']); ?>" <?php echo ($type_filter == $t['type']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t['type']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary rounded-pill">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Room List -->
    <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card room-card h-100">
                        <div class="room-img-container">
                            <?php
                            $img = !empty($row['image']) ? "uploads/rooms/" . htmlspecialchars($row['image']) : "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&q=80";
                            ?>
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($row['type']); ?></span>
                            </div>
                            <p class="card-text text-muted flex-grow-1"><?php echo substr(htmlspecialchars($row['description']), 0, 100) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="room-price">$<?php echo number_format($row['price'], 2); ?> <small class="text-muted fs-6 fw-normal">/ Night</small></span>
                                <a href="room_details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger rounded-pill">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-bed-pulse fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Rooms Found</h4>
                <a href="rooms.php" class="btn btn-outline-primary mt-3 rounded-pill px-4">Clear Filters</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
