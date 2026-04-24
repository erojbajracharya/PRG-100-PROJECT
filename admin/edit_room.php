<?php
require_once '../includes/db.php';
require_once 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$id = $conn->real_escape_string($_GET['id']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $type = $conn->real_escape_string($_POST['type']);
    $price = $conn->real_escape_string($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $amenities = isset($_POST['amenities']) ? implode(',', $_POST['amenities']) : '';
    $amenities = $conn->real_escape_string($amenities);
    
    $update_img = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $allowed)) {
            $new_name = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/rooms/' . $new_name)) {
                $update_img = ", image='$new_name'";
            }
        }
    }
    
    $sql = "UPDATE rooms SET name='$name', type='$type', price='$price', description='$description', status='$status', amenities='$amenities' $update_img WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: rooms.php?success=Room updated successfully");
        exit();
    } else {
        $error = "Error updating room: " . $conn->error;
    }
}

$room = $conn->query("SELECT * FROM rooms WHERE id = '$id'")->fetch_assoc();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Edit Room</h3>
    <a href="rooms.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Room Name</label>
                    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($room['name']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Room Type</label>
                    <select name="type" class="form-select" required>
                        <?php 
                        $types = ['Single', 'Double', 'Suite', 'Family', 'Deluxe'];
                        foreach($types as $t) {
                            $sel = ($room['type'] == $t) ? 'selected' : '';
                            echo "<option value='$t' $sel>$t</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Price per Night (NPR)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required value="<?php echo $room['price']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Available" <?php if($room['status']=='Available') echo 'selected';?>>Available</option>
                        <option value="Booked" <?php if($room['status']=='Booked') echo 'selected';?>>Booked</option>
                        <option value="Maintenance" <?php if($room['status']=='Maintenance') echo 'selected';?>>Maintenance</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Room Amenities</label>
                    <div class="row g-2">
                        <?php 
                        $all_amenities = ['Free WiFi', 'Air Conditioning', 'Smart TV', 'Room Service', 'Mini Bar', 'Breakfast Included', 'Swimming Pool Access', 'Hot Water'];
                        $current_amenities = explode(',', $room['amenities'] ?? '');
                        foreach($all_amenities as $am): ?>
                        <div class="col-md-3 col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="<?php echo $am; ?>" id="am_<?php echo str_replace(' ', '', $am); ?>" <?php echo in_array($am, $current_amenities) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="am_<?php echo str_replace(' ', '', $am); ?>">
                                    <?php echo $am; ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Room Image (Leave empty to keep current)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if(!empty($room['image'])): ?>
                        <div class="mt-2">
                            <img src="../uploads/rooms/<?php echo $room['image']; ?>" height="80" class="rounded">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($room['description']); ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-2"></i>Update Room</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>
