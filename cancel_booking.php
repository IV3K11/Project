<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $booking_id = (int)$_POST['booking_id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM bookings WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: my_bookings.php");
    exit();
}
?>