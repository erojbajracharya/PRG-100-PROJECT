<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch 3 available rooms for the featured section
$sql = "SELECT * FROM rooms WHERE status = 'Available' LIMIT 3";
$result = $conn->query($sql);
?>

<!-- Hero Section -->
<section class="hero-section mt-5 pt-5">
    <div class="container">
        <h1 class="hero-title">Welcome to Hotel EAD</h1>
        <a href="rooms.php" class="btn btn-primary btn-lg mt-3">Book your stay now</a>
    </div>
</section>

<!-- Featured Rooms Section -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: var(--primary-color);">Featured Rooms</h2>
        </div>
        
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card room-card h-100">
                            <div class="room-img-container">
                                <?php
                                $img = !empty($row['image']) ? ROOM_IMG_PATH . htmlspecialchars($row['image']) : "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&q=80";
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
                                    <span class="room-price">Rs. <?php echo number_format($row['price'], 2); ?> <small class="text-muted fs-6 fw-normal">/ night</small></span>
                                    <a href="room_details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger rounded-pill">View details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="rooms.php" class="btn btn-primary rounded-pill px-5">View all rooms</a>
        </div>
    </div>
</section>

<!-- Amenities Section -->
<section class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: var(--primary-color);">Hotel Amenities</h2>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <i class="fa-solid fa-wifi fa-3x mb-3" style="color: var(--secondary-color);"></i>
                <h5 class="fw-bold">Free WiFi</h5>
            </div>
            <div class="col-md-3 col-6">
                <i class="fa-solid fa-water-ladder fa-3x mb-3" style="color: var(--secondary-color);"></i>
                <h5 class="fw-bold">Swimming Pool</h5>
            </div>
            <div class="col-md-3 col-6">
                <i class="fa-solid fa-utensils fa-3x mb-3" style="color: var(--secondary-color);"></i>
                <h5 class="fw-bold">Restaurant</h5>
            </div>
            <div class="col-md-3 col-6">
                <i class="fa-solid fa-car fa-3x mb-3" style="color: var(--secondary-color);"></i>
                <h5 class="fw-bold">Parking</h5>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
