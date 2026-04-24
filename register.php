<?php
require_once 'includes/db.php';

$error = '';
$success = '';

if (isset($_GET['registered'])) {
    $success = "Registration successful! You can now <a href='login.php'>login</a>.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $conn->real_escape_string(trim($_POST['phone']));

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $check_sql = "SELECT id FROM users WHERE email = '$email'";
        $check_res = $conn->query($check_sql);
        if ($check_res->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$hashed_password', '$phone')";

            if ($conn->query($sql) === TRUE) {
                header("Location: register.php?registered=1");
                exit();
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: var(--primary-color);">Create Account</h2>
                        <p class="text-muted">Join Hotel EAD and start booking</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger rounded-pill text-center"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success rounded-pill text-center"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="Your Full Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address *</label>
                            <input type="email" name="email" class="form-control" required
                                placeholder="name@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+977 9812345678" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password *</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Confirm Password *</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">Register</button>
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-muted">Already have an account? <a href="login.php"
                                    class="text-decoration-none fw-bold" style="color: var(--secondary-color);">Login
                                    here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>