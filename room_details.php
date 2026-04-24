<?php
require_once 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM rooms WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: rooms.php");
    exit();
}

$room = $result->fetch_assoc();
require_once 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <?php
                $img = !empty($room['image']) ? "uploads/rooms/" . htmlspecialchars($room['image']) : "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&q=80";
                ?>
                <img src="<?php echo $img; ?>" class="img-fluid w-100" style="height: 500px; object-fit: cover;" alt="<?php echo htmlspecialchars($room['name']); ?>">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="fw-bold mb-0" style="color: var(--primary-color);"><?php echo htmlspecialchars($room['name']); ?></h2>
                        <span class="badge bg-secondary fs-6"><?php echo htmlspecialchars($room['type']); ?></span>
                    </div>
                    
                    <h4 class="room-price mb-4">$<?php echo number_format($room['price'], 2); ?> <span class="text-muted fs-6 fw-normal">/ Night</span></h4>
                    
                    <h5 class="fw-bold mb-3">Description</h5>
                    <p class="text-muted lh-lg"><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
                    
                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-3">Room Amenities</h5>
                    <div class="row g-3">
                        <div class="col-md-4 col-6"><i class="fa-solid fa-wifi text-secondary me-2"></i> Free High-Speed WiFi</div>
                        <div class="col-md-4 col-6"><i class="fa-solid fa-tv text-secondary me-2"></i> Smart TV</div>
                        <div class="col-md-4 col-6"><i class="fa-solid fa-snowflake text-secondary me-2"></i> Air Conditioning</div>
                        <div class="col-md-4 col-6"><i class="fa-solid fa-mug-hot text-secondary me-2"></i> Coffee Maker</div>
                        <div class="col-md-4 col-6"><i class="fa-solid fa-hot-tub-person text-secondary me-2"></i> Hot Water</div>
                        <div class="col-md-4 col-6"><i class="fa-solid fa-bell-concierge text-secondary me-2"></i> Room Service</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow" style="border-radius: 15px; position: sticky; top: 100px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4 text-center">Book This Room</h4>
                    
                    <?php if ($room['status'] != 'Available'): ?>
                        <div class="alert alert-warning text-center rounded-pill">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>This Room Is Currently Not Available.
                        </div>
                    <?php else: ?>
                        <form method="POST" action="book.php">
                            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Check-in Date</label>
                                <input type="date" name="check_in" class="form-control" required min="<?php echo date('Y-m-d'); ?>" id="check_in">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Check-out Date</label>
                                <input type="date" name="check_out" class="form-control" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" id="check_out">
                            </div>
                            
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill">Proceed to Booking</button>
                            <?php else: ?>
                                <div class="alert alert-info text-center rounded-pill" style="font-size: 0.9rem;">
                                    Please <a href="login.php" class="fw-bold">Login</a> To Book This Room.
                                </div>
                                <a href="login.php" class="btn btn-outline-primary w-100 btn-lg rounded-pill">Login to Book</a>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Ensure checkout is after checkin
    document.getElementById('check_in').addEventListener('change', function() {
        let checkInDate = new Date(this.value);
        checkInDate.setDate(checkInDate.getDate() + 1);
        
        let dd = String(checkInDate.getDate()).padStart(2, '0');
        let mm = String(checkInDate.getMonth() + 1).padStart(2, '0');
        let yyyy = checkInDate.getFullYear();
        
        let minCheckOut = yyyy + '-' + mm + '-' + dd;
        let checkOutInput = document.getElementById('check_out');
        checkOutInput.min = minCheckOut;
        
        if (checkOutInput.value && checkOutInput.value < minCheckOut) {
            checkOutInput.value = minCheckOut;
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
