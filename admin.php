<?php
session_start();
include "dbconnect.php";
include "session_security.php";

// Check if user is logged in and is admin
check_session();
check_admin();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if(isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    
    $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', role='$role' WHERE id='$user_id'";
    mysqli_query($conn, $sql);
}

if(isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    mysqli_query($conn, "DELETE FROM bookings WHERE user_id='$user_id'");
    mysqli_query($conn, "DELETE FROM users WHERE id='$user_id'");
}

if(isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];
    
    $sql = "UPDATE bookings SET status='$new_status' WHERE id='$booking_id'";
    mysqli_query($conn, $sql);
}

if(isset($_POST['delete_booking'])) {
    $booking_id = $_POST['booking_id'];
    mysqli_query($conn, "DELETE FROM bookings WHERE id='$booking_id'");
}

// Get data
$users = mysqli_query($conn, "SELECT * FROM users");
$bookings = mysqli_query($conn, "SELECT * FROM bookings");
$courts = mysqli_query($conn, "SELECT * FROM courts");

$total_users = mysqli_num_rows($users);
$total_bookings = mysqli_num_rows($bookings);
$total_courts = mysqli_num_rows($courts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Padel Court Booking</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .admin-container {
        padding: 40px;
        max-width: 1400px; /* Increased from 1200px */
        margin: 0 auto;
    }

    .admin-table {
        width: 100%;
        background: white;
        border-radius: 10px;
        border-spacing: 0;
        margin: 20px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .admin-table th, 
    .admin-table td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .admin-table th {
        background-color: #004aad;
        color: white;
        font-weight: bold;
    }

    .admin-table td {
        vertical-align: middle;
    }

    .admin-table input[type="text"],
    .admin-table input[type="email"],
    .admin-table select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-start;
    }

    .action-buttons button {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        min-width: 80px;
    }

    .confirm {
        background-color: #004aad;
        color: white;
    }

    .cancel {
        background-color: #dc3545;
        color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .admin-container {
            padding: 20px;
        }
    }

    @media (max-width: 768px) {
        .admin-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .admin-container {
            padding: 10px;
        }
    }
</style>

</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
             <a href="index.php">Home</a> 
             <a href="admin.php">Admin Dashboard</a> 
             <a href="logout.php">Logout</a>
             

        </nav>
    </header>

    <main class="admin-container">
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p><?php echo $total_bookings; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Courts</h3>
                <p><?php echo $total_courts; ?></p>
            </div>
        </div>

        <section>
            <h2 class="section-title">User Management</h2>
            <div class="table-container">
            <table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($user = mysqli_fetch_assoc($users)) { ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td>
                    <form method="POST" class="inline-form">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <input type="text" name="name" value="<?php echo $user['name']; ?>">
                </td>
                <td>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>">
                </td>
                <td>
                    <input type="text" name="phone" value="<?php echo $user['phone']; ?>">
                </td>
                <td>
                    <select name="role">
                        <option value="user" <?php if($user['role']=='user') echo 'selected'; ?>>User</option>
                        <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </td>
                <td class="action-buttons">
                    <button type="submit" name="update_user" class="confirm">Update</button>
                    </form>
                    <form method="POST" class="inline-form" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_user" class="cancel">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
            </div>
        </section>

        <section>
            <h2 class="section-title">Booking Management</h2>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Court ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($booking = mysqli_fetch_assoc($bookings)) { ?>
                            <tr>
                                <td><?php echo $booking['id']; ?></td>
                                <td><?php echo $booking['user_id']; ?></td>
                                <td><?php echo $booking['court_id']; ?></td>
                                <td><?php echo $booking['booking_date']; ?></td>
                                <td><?php echo $booking['booking_time']; ?></td>
                                <td>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <select name="status">
                                            <option value="pending" <?php if($booking['status']=='pending') echo 'selected'; ?>>Pending</option>
                                            <option value="confirmed" <?php if($booking['status']=='confirmed') echo 'selected'; ?>>Confirmed</option>
                                            <option value="cancelled" <?php if($booking['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="confirm">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" name="delete_booking" class="cancel">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <p>© This website was developed by Ahmed AL SABARI, HAMED AL KINDI</p>
        <p>IT students - University of Technology and Applied Sciences - Nizwa</p>
    </footer>
</body>
</html>