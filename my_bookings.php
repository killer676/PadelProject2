<?php
session_start();
include "dbconnect.php";
include "session_security.php";

// Check if user is logged in
check_session();


// Get user's bookings
$user_id = $_SESSION['user_id'];
$sql = "SELECT bookings.*, courts.court_name 
        FROM bookings 
        JOIN courts ON bookings.court_id = courts.id 
        WHERE bookings.user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

// Handle booking cancellation
if(isset($_POST['cancel'])) {
    $booking_id = $_POST['booking_id'];
    $cancel_sql = "DELETE FROM bookings WHERE id = '$booking_id' AND user_id = '$user_id'";
    mysqli_query($conn, $cancel_sql);
    header("Location: my_bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .cancel-btn {
            background-color: #ff4444;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .no-bookings {
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Padel Court Booking</h1>
        <nav>
            <a href="userpage.php">Home</a>
            <a href="booking.php">Book a Court</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
       

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Court Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['court_name']; ?></td>
                        <td><?php echo $row['booking_date']; ?></td>
                        <td><?php echo date('h:i A', strtotime($row['booking_time'])); ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="cancel" value="Cancel" class="cancel-btn">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <div class="no-bookings">
                <p>You have no bookings.</p>
                <a href="booking.php">Book a Court</a>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>© This website was developed by Ahmed AL SABARI, HAMED AL KINDI</p>
        <p>IT students - University of Technology and Applied Sciences - Nizwa</p>
    </footer>
</body>
</html>