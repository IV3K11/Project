<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $instructor = trim($_POST['instructor']);
    $schedule = trim($_POST['schedule']);
    $capacity = (int)$_POST['capacity'];

    $stmt = mysqli_prepare($conn, "INSERT INTO classes (title, description, instructor, schedule, capacity) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $title, $description, $instructor, $schedule, $capacity);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Add Fitness Class</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1>Fitness Class Booking</h1>
            <nav>
                <a href="home.php">Home</a>
                <a href="my_bookings.php">My Bookings</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Add New Fitness Class</h2>
        <form action="admin.php" method="POST">
            <div class="form-group">
                <label>Class Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Instructor</label>
                <input type="text" name="instructor" required>
            </div>
            <div class="form-group">
                <label>Schedule (e.g., Mon & Wed 18:00)</label>
                <input type="text" name="schedule" required>
            </div>
            <div class="form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" required>
            </div>
            <button type="submit" class="btn">Add Class</button>
        </form>
    </main>
</body>
</html>