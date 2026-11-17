<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM classes";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fitness Classes - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1>Fitness Class Booking</h1>
            <nav>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="home.php">Home</a>
                <a href="my_bookings.php">My Bookings</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="admin.php">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Available Fitness Classes</h2>
        <div class="classes-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="class-card">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Instructor:</strong> <?php echo htmlspecialchars($row['instructor']); ?></p>
                        <p><strong>Schedule:</strong> <?php echo htmlspecialchars($row['schedule']); ?></p>
                        <p><strong>Capacity:</strong> <?php echo htmlspecialchars($row['capacity']); ?></p>
                        <form action="book.php" method="POST">
    <input type="hidden" name="class_id" value="<?php echo $row['id']; ?>">
    <button type="submit" class="btn">Book Class</button>
</form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No fitness classes available at the moment.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>