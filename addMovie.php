<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $movie_name = trim($_POST['movie_name']);
    $movie_quality = trim($_POST['movie_quality']);
    $movie_rating = trim($_POST['movie_rating']);
    $movie_desc = trim($_POST['movie_desc']);
    $movie_image = trim($_POST['movie_image']);

    if (empty($movie_name) || empty($movie_quality) || empty($movie_rating)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO movies (movie_name, movie_quality, movie_rating, movie_desc, movie_image) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $movie_name, $movie_quality, $movie_rating, $movie_desc, $movie_image);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Fitness class added successfully!";
        } else {
            $error = "Error adding class to database.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Fitness Class</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1>Fitness Admin Panel</h1>
            <nav>
                <a href="home.php">Home</a>
                <a href="admin.php">Manage Classes</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Add New Fitness Class</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        
        <form action="add_movie.php" method="POST">
            <div class="form-group">
                <label>Class Name</label>
                <input type="text" name="movie_name" required>
            </div>
            <div class="form-group">
                <label>Intensity</label>
                <input type="text" name="movie_quality" required>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="movie_rating" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="movie_desc" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="movie_image">
            </div>
            <button type="submit" class="btn">Add Class</button>
        </form>
    </main>
</body>
</html>
