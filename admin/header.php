<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: login.php");
    exit();
}
// Get base URL dynamically from the constant defined in db.php
$baseUrl = BASE_URL;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Hotel EAD</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8C4A2F;
            /* Terracotta Red */
            --secondary-color: #2D1B10;
            /* Dark Timber */
            --bg-light: #F5F5F0;
            /* Muted Cream */
            --bg-card: #FFFFFF;
            --text-dark: #2D1B10;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--secondary-color);
            color: white;
            min-height: 100vh;
            transition: all 0.3s;
            border-right: none;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 15px 20px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(140, 74, 47, 0.4);
            border-left: 4px solid var(--primary-color);
        }

        .sidebar .nav-link i {
            width: 25px;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.3s;
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            color: #fff;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .admin-header {
            background: var(--bg-card);
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(45, 27, 16, .05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid rgba(45, 27, 16, 0.1);
        }

        .card {
            background-color: var(--bg-card);
            border-radius: 10px;
            border: 1px solid rgba(45, 27, 16, 0.1) !important;
            box-shadow: 0 4px 6px rgba(45, 27, 16, .05);
            color: var(--text-dark);
        }

        .card-header {
            background-color: var(--bg-card) !important;
            border-bottom: 1px solid rgba(45, 27, 16, 0.1) !important;
            color: var(--text-dark) !important;
        }

        .table {
            color: var(--text-dark);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(140, 74, 47, 0.05);
            color: var(--text-dark);
        }

        .table thead th {
            background-color: rgba(45, 27, 16, 0.03) !important;
            color: var(--secondary-color) !important;
            border-bottom: 1px solid rgba(45, 27, 16, 0.1);
            border-top: none;
        }

        .table tbody td {
            border-bottom: 1px solid rgba(45, 27, 16, 0.05);
            color: var(--text-dark);
        }

        .bg-light {
            background-color: var(--bg-light) !important;
        }

        .btn-light {
            background-color: #fff;
            color: var(--text-dark);
            border-color: rgba(45, 27, 16, 0.2);
        }

        .btn-light:hover {
            background-color: var(--bg-light);
            color: var(--secondary-color);
            border-color: rgba(45, 27, 16, 0.3);
        }

        .dropdown-menu {
            background-color: #fff;
            border: 1px solid rgba(45, 27, 16, 0.1);
        }

        .dropdown-item {
            color: var(--text-dark);
        }

        .dropdown-item:hover {
            background-color: rgba(140, 74, 47, 0.1);
            color: var(--secondary-color);
        }

        .text-muted {
            color: #665042 !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        .form-control,
        .form-select {
            background-color: #fff !important;
            color: var(--text-dark) !important;
            border: 1px solid rgba(45, 27, 16, 0.2) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.25rem rgba(140, 74, 47, 0.25) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .text-danger {
            color: #e74c3c !important;
        }
    </style>
</head>

<body>

    <?php if (isset($_SESSION['admin_id'])): ?>
        <div class="sidebar d-none d-md-block">
            <div class="p-3 text-center border-bottom border-secondary mb-3">
                <h4 class="mb-0 text-white"><i class="fa-solid fa-user-shield me-2 text-danger"></i>Admin Panel</h4>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                        href="index.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['rooms.php', 'add_room.php', 'edit_room.php']) ? 'active' : ''; ?>"
                        href="rooms.php"><i class="fa-solid fa-door-open"></i> Manage Rooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>"
                        href="bookings.php"><i class="fa-solid fa-calendar-check"></i> Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'active' : ''; ?>"
                        href="payments.php"><i class="fa-solid fa-file-invoice-dollar"></i> Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'support.php' ? 'active' : ''; ?>"
                        href="support.php"><i class="fa-solid fa-headset"></i> Support Requests</a>
                </li>
                <li class="nav-item mt-5">
                    <a class="nav-link text-danger" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>
                        Logout Admin</a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="admin-header">
                <h5 class="mb-0 fw-bold">Hotel EAD Management</h5>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="adminMenu" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-solid fa-user-circle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminMenu">
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout Admin</a></li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="main-content" style="padding: 0;">
            <?php endif; ?>