<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$success = '';
$error = '';

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
            if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/support/' . $new_name)) {
                $image = $new_name;
            }
        }
    }
    
    $sql = "INSERT INTO support_requests (user_id, name, email, subject, message, image) 
            VALUES ($user_id, '$name', '$email', '$subject', '$message', '$image')";
            
    if ($conn->query($sql) === TRUE) {
        $success = "Your Support Request Has Been Sent Successfully. We Will Get Back To You Soon.";
    } else {
        $error = "Error Sending Request: " . $conn->error;
    }
}
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-5">
                <h1 class="fw-bold" style="color: var(--primary-color);">Contact Us / Support</h1>
                <p class="text-muted">Have A Question Or Need Help? Send Us A Message!</p>
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
                                <input type="text" name="name" class="form-control" required placeholder="Enter Your Full Name" value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" required placeholder="Enter Your Email">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Subject</label>
                                <input type="text" name="subject" class="form-control" required placeholder="What Is This About?">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Message</label>
                                <textarea name="message" class="form-control" rows="5" required placeholder="Describe Your Issue Or Inquiry..."></textarea>
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
