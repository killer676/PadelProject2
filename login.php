<?php
session_start();
include "dbconnect.php";

if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    // Simple validation
    if(empty($username) || empty($password)) {
        $message = "Please fill in all fields";
        $message_type = "error";
    } else {
        // Check user in database
        $query = "SELECT id, name, password, role FROM users WHERE name = '$username'";
        $result = mysqli_query($conn, $query);
        
        if($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if($password === $user['password']) {  
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                
                // Redirect based on role
                if($user['role'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: userpage.php");
                }
                exit();
            } else {
                $message = "Invalid password";
                $message_type = "error";
            }
        } else {
            $message = "User not found";
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padel Court Booking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Padel Court Booking</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </nav>
    </header>
    <main>
        <?php if(!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password">

            <button type="submit" name="login">Login</button>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </form>
    </main>
    <footer>
        <a href=index.php>Padel Court Booking</a> | &copy This website was developed by Ahmed AL SABARI, HAMED AL KINDI, IT students - University of Technology and Applied Sciences - Nizwa
    </footer>
</body>
</html>