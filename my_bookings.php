<?php
session_start();
require_once 'config.php';

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT bookings.id AS booking_id, classes.title, classes.description, classes.instructor, classes.schedule 
        FROM bookings 
        JOIN classes ON bookings.class_id = classes.id 
        WHERE bookings.user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1>Fitness Class Booking</h1>
            <nav>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</span>
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
        <h2>My Booked Fitness Classes</h2>
        <div class="classes-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="class-card">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Instructor:</strong> <?php echo htmlspecialchars($row['instructor']); ?></p>
                        <p><strong>Schedule:</strong> <?php echo htmlspecialchars($row['schedule']); ?></p>
                        <form action="cancel_booking.php" method="POST">
                            <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                            <button type="submit" class="btn" style="background-color: #d9534f;">Cancel Booking</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You haven't booked any fitness classes yet.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>