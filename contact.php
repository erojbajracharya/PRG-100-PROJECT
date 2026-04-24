<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = '';
$error = '';

if (isset($_GET['success'])) {
    $success = "Your support request has been sent successfully. We will get back to you soon.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $allowed)) {
            $new_name = uniqid() . '.' . $ext;
            $upload_path = __DIR__ . '/uploads/support/' . $new_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = $new_name;
            } else {
                $error = "Failed to move uploaded file.";
            }
        } else {
            $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
        $error = "Image upload error code: " . $_FILES['image']['error'];
    }
    
    if (empty($error)) {
        $sql = "INSERT INTO support_requests (user_id, name, email, subject, message, image) 
                VALUES ($user_id, '$name', '$email', '$subject', '$message', '$image')";
                
        if ($conn->query($sql) === TRUE) {
            header("Location: contact.php?success=1");
            exit();
        } else {
            $error = "Error sending request: " . $conn->error;
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-5">
                <h1 class="fw-bold" style="color: var(--primary-color);">Contact us / Support</h1>
                <p class="text-muted">Have a question or need help? Send us a message!</p>
            </div>

            <?php if(!empty($success)): ?>
                <div class="alert alert-success rounded-pill text-center"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if(!empty($error)): ?>
                <div class="alert alert-danger rounded-pill text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Your Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter your full name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" required placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Subject</label>
                                <input type="text" name="subject" class="form-control" required placeholder="What is this about?" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Message</label>
                                <textarea name="message" class="form-control" rows="5" required placeholder="Describe your issue or inquiry..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            <div class="col-12 mb-4">
                                <label class="form-label fw-bold">Upload Image (Optional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5 text-center">
        <div class="col-md-4">
            <div class="p-4">
                <i class="fa-solid fa-location-dot fa-3x mb-3 text-secondary"></i>
                <h5 class="fw-bold">Our Location</h5>
                <p class="text-muted">New Baneshwor, Kathmandu, Nepal</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <i class="fa-solid fa-phone fa-3x mb-3 text-secondary"></i>
                <h5 class="fw-bold">Phone Number</h5>
                <p class="text-muted">+01-5324520</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <i class="fa-solid fa-envelope fa-3x mb-3 text-secondary"></i>
                <h5 class="fw-bold">Email Support</h5>
                <p class="text-muted">hotelead@hotelead.com</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
