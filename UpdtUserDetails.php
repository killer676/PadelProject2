<?php
session_start();
include "dbconnect.php";
include "session_security.php";

// Check if user is logged in
check_session();


// Check if user is logged in  
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user details
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    // Update user details
    $update_query = "UPDATE users SET 
                    name = '$name', 
                    email = '$email', 
                    city = '$city',
                    phone = '$phone' 
                    WHERE id = '$user_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Details updated successfully!";
    } else {
        $error_message = "Error updating details: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Details</title>
   
     <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
        <h1>Padel Court Booking</h1>
        <nav>
       
    <a href="userpage.php">Home</a>
    <a href="logout.php">Logout</a>


        </nav>
    </header>
    <div class="container">
        
        
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            </div>

            <button type="submit" class="btn">Update Details</button>
        </form>
    </div>
    <footer>
<a href=index.php>Padel Court Booking</a> | &copy This website was developed by Ahmed AL SABARI, HAMED AL KINDI, IT students - University of Technology and Applied Sciences - Nizwa

    </footer>
</body>
</html>