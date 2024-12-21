<?php
include 'dbconnect.php';

if(isset($_POST['register'])){
    $Username = $_POST['username'];
    $Email = $_POST['email'];
    $pnumber = $_POST['Pnumber'];
    $Gender = $_POST['gender'];
    $Password = $_POST['password'];
    $city = $_POST['city'];

    
    $message = '';
    $message_type = '';

    // Validation checks
    if(empty($Username) || empty($Email) || empty($pnumber) || empty($Gender) || empty($Password || empty($city))){
        $message = 'Please fill in all fields';
        $message_type = 'error';
    } 
    else if (!preg_match("/^\S+@\S+\.\S+$/", $Email)) {
        $message = "Please enter a valid email address.";
        $message_type = 'error';
    } 
    else if (strlen($Password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $message_type = 'error';
    }
    else if(!is_numeric($pnumber)){
        $message = "Phone number must be numeric.";
        $message_type = 'error';
    }
    else {
        $sql = "SELECT * FROM users WHERE name='$Username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $message = "Username already taken. Please choose another.";
            $message_type = 'error';
        } else {
           
            $sql = "INSERT INTO users (name, phone,city, email, gender, Password) VALUES ('$Username', '$pnumber','$city', '$Email', '$Gender', '$Password')";
            if ($conn->query($sql) === TRUE) {
                $message = "Registration successful!";
                $message_type = 'success';
                
            } else {
                $message = "Something went wrong. Please try again later.";
                $message_type = 'error';
            }
        }
    }
}

$conn->close();
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
            <a href="login.php">Login</a>
        </nav>
    </header>
    <main>
    <?php if(!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email">
            
            <label for="Pnumber">Phone Number:</label>
            <input type="text" id="Pnumber" name="Pnumber" placeholder="+968 12345678">
            
            <label for="City">City:</label>
            <input type="text" id="City" name="city" placeholder="Nizwa">
            
<label class="gender-label" for="gender">Gender:</label>
<div class="radio-group">
    <div class="radio-option">
        <input type="radio" id="Male" name="gender" value="MALE">
        <label for="Male">Male</label>
    </div>
    <div class="radio-option">
        <input type="radio" id="Female" name="gender" value="FEMALE">
        <label for="Female">Female</label>
    </div>
</div>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password">

            <button type="submit" name="register">Register</button>
            <div class="register-link">
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
        </form>

        
    </main>
    <footer>
        <a href="index.php">Padel Court Booking</a> | &copy; This website was developed by Ahmed AL SABARI, HAMED AL KINDI, IT students - University of Technology and Applied Sciences - Nizwa
    </footer>
</body>
</html>