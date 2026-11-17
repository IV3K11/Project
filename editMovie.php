<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';
$class = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $class = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$class) {
        header("Location: admin.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $movie_name = trim($_POST['movie_name']);
    $movie_quality = trim($_POST['movie_quality']);
    $movie_rating = trim($_POST['movie_rating']);
    $movie_desc = trim($_POST['movie_desc']);
    $movie_image = trim($_POST['movie_image']);

    if (empty($movie_name) || empty($movie_quality) || empty($movie_rating)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE movies SET movie_name = ?, movie_quality = ?, movie_rating = ?, movie_desc = ?, movie_image = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssssi", $movie_name, $movie_quality, $movie_rating, $movie_desc, $movie_image, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Fitness class updated successfully!";
            $stmt2 = mysqli_prepare($conn, "SELECT * FROM movies WHERE id = ?");
            mysqli_stmt_bind_param($stmt2, "i", $id);
            mysqli_stmt_execute($stmt2);
            $class = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));
            mysqli_stmt_close($stmt2);
        } else {
            $error = "Error updating fitness class.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Fitness Class</title>
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
        <h2>Edit Fitness Class</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        
        <?php if ($class): ?>
        <form action="edit_movie.php?id=<?php echo $class['id']; ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $class['id']; ?>">
            <div class="form-group">
                <label>Class Name</label>
                <input type="text" name="movie_name" value="<?php echo htmlspecialchars($class['movie_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Intensity</label>
                <input type="text" name="movie_quality" value="<?php echo htmlspecialchars($class['movie_quality']); ?>" required>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="movie_rating" value="<?php echo htmlspecialchars($class['movie_rating']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="movie_desc" rows="4"><?php echo htmlspecialchars($class['movie_desc']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="movie_image" value="<?php echo htmlspecialchars($class['movie_image']); ?>">
            </div>
            <button type="submit" class="btn">Update Class</button>
        </form>
        <?php endif; ?>
    </main>
</body>
</html>