<?php
session_start();
require_once 'config.php';

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;

if (!$user_id) {
    die("ERROR: User is not logged in or session ID is missing.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = isset($_POST['class_id']) ? (int)$_POST['class_id'] : 0;

    if ($class_id > 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO bookings (user_id, class_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $class_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: my_bookings.php");
    exit();
}
?>