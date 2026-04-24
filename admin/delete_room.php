<?php
require_once '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Get image to delete
    $res = $conn->query("SELECT image FROM rooms WHERE id = '$id'");
    if ($res->num_rows > 0) {
        $img = $res->fetch_assoc()['image'];
        if (!empty($img)) {
            $file_to_delete = dirname(__DIR__) . "/uploads/rooms/" . $img;
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }
        }
    }
    
    $conn->query("DELETE FROM rooms WHERE id = '$id'");
    header("Location: rooms.php?success=Room deleted successfully");
} else {
    header("Location: rooms.php");
}
exit();
?>
