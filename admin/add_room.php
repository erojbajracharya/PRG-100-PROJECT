<?php
require_once '../includes/db.php';
require_once 'header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $type = $conn->real_escape_string($_POST['type']);
    $price = $conn->real_escape_string($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $allowed)) {
            $new_name = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/rooms/' . $new_name)) {
                $image = $new_name;
            }
        }
    }
    
    $sql = "INSERT INTO rooms (name, type, price, description, status, image) VALUES ('$name', '$type', '$price', '$description', '$status', '$image')";
    if ($conn->query($sql) === TRUE) {
        header("Location: rooms.php?success=Room added successfully");
        exit();
    } else {
        $error = "Error adding room: " . $conn->error;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Add New Room</h3>
    <a href="rooms.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Rooms</a>
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
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Deluxe Ocean View">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Room Type</label>
                    <select name="type" class="form-select" required>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                        <option value="Suite">Suite</option>
                        <option value="Family">Family</option>
                        <option value="Deluxe">Deluxe</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Price per Night ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required placeholder="e.g. 150.00">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Available">Available</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Room Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Enter room description and amenities..."></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-2"></i>Save Room</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>
