<?php
require_once '../includes/db.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);
    
    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid Password.";
        }
    } else {
        $error = "Admin not found.";
    }
}

require_once 'header.php';
?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; background: var(--bg-light);">
    <div class="card shadow-lg border-0" style="width: 400px; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="fa-solid fa-user-shield fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold">Admin Login</h3>
                <p class="text-muted small">Hotel EAD Management System</p>
            </div>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger text-center py-2"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="Enter Username">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter Password">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Login To Dashboard</button>
                </div>
                <div class="text-center mt-3">
                    <a href="../index.php" class="text-muted text-decoration-none small"><i class="fa-solid fa-arrow-left me-1"></i> Back To Website</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
