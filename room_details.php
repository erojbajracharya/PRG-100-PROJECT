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
                    
                    <h4 class="room-price mb-4">Rs. <?php echo number_format($room['price'], 2); ?> <span class="text-muted fs-6 fw-normal">/ night</span></h4>
                    
                    <h5 class="fw-bold mb-3">Description</h5>
                    <p class="text-muted lh-lg"><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
                    
                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-3">Room amenities</h5>
                    <div class="row g-3">
                        <?php 
                        if (!empty($room['amenities'])) {
                            $ams = explode(',', $room['amenities']);
                            $icons = [
                                'Free WiFi' => 'fa-wifi',
                                'Air Conditioning' => 'fa-snowflake',
                                'Smart TV' => 'fa-tv',
                                'Room Service' => 'fa-bell-concierge',
                                'Mini Bar' => 'fa-glass-water',
                                'Breakfast Included' => 'fa-utensils',
                                'Swimming Pool Access' => 'fa-person-swimming',
                                'Hot Water' => 'fa-hot-tub-person'
                            ];
                            foreach($ams as $am) {
                                $icon = isset($icons[$am]) ? $icons[$am] : 'fa-check';
                                echo "<div class='col-md-4 col-6'><i class='fa-solid $icon text-secondary me-2'></i> $am</div>";
                            }
                        } else {
                            echo "<div class='col-12 text-muted'>No amenities listed.</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow" style="border-radius: 15px; position: sticky; top: 100px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4 text-center">Book this room</h4>
                    
                    <?php if ($room['status'] != 'Available'): ?>
                        <div class="alert alert-warning text-center rounded-pill">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>This room is currently not available.
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
                                <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill">Proceed to booking</button>
                            <?php else: ?>
                                <div class="alert alert-info text-center rounded-pill" style="font-size: 0.9rem;">
                                    Please <a href="login.php" class="fw-bold">login</a> to book this room.
                                </div>
                                <a href="login.php" class="btn btn-outline-primary w-100 btn-lg rounded-pill">Login to book</a>
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
