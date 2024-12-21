<?php
session_start();
include "dbconnect.php";
include "session_security.php";

// Check if user is logged in
check_session();

// Rest of your booking.php code...
   /*
   $query = INSERT INTO courts (court_name, location, description) VALUES
('Court A', 'Indoor', 'Premium indoor court with professional LED lighting, air conditioning, and cushioned flooring. Perfect for year-round play regardless of weather conditions.'),
('Court B', 'Indoor', 'Standard indoor court equipped with proper lighting and ventilation system. Ideal for both beginners and intermediate players.'),
('Court C', 'Outdoor', 'Professional outdoor court with high-quality artificial turf and excellent natural lighting. Features shaded player rest areas.'),
('Court D', 'Outdoor', 'Standard outdoor court with durable synthetic grass surface. Perfect for morning and evening games.'),
('Court E', 'Premium', 'Elite premium court with professional-grade facilities including spectator seating, premium flooring, and tournament-standard lighting.');
    */
    //$e = mysqli_query($conn, $query);
    

 

?>
<?php


  
  if(!isset($_SESSION['user_id'])) {
      header("Location: login.php");
      exit();
  }
  
  // Fetch courts from database
  $sql = "SELECT * FROM courts";
  $result = mysqli_query($conn, $sql);
  
  // Get today's date
  $today = date('Y-m-d');
  
  // Booking process with validations
  if(isset($_POST['book'])) {
      $court_id = $_POST['court'];
      $user_id = $_SESSION['user_id'];
      $booking_date = $_POST['date'];
      $booking_time = $_POST['time_slot'];
      
      $errors = [];
      
      // Date validation
      if($booking_date < $today) {
          $errors[] = "Cannot book for past dates";
      }
      
      // Time validation
      if($booking_time < '09:00' || $booking_time > '20:00') {
          $errors[] = "Booking time must be between 9 AM and 8 PM";
      }
      
      // Check availability
      $check_sql = "SELECT * FROM bookings 
                   WHERE court_id = '$court_id' 
                   AND booking_date = '$booking_date' 
                   AND booking_time = '$booking_time'";
      
      $check_result = mysqli_query($conn, $check_sql);
      
      if(mysqli_num_rows($check_result) > 0) {
          $errors[] = "This court is already booked for the selected time";
      }
      
      // If no errors, proceed with booking
      if(empty($errors)) {
          $insert_sql = "INSERT INTO bookings (court_id, user_id, booking_date, booking_time, status) 
                        VALUES ('$court_id', '$user_id', '$booking_date', '$booking_time', 'confirmed')";
          
          if(mysqli_query($conn, $insert_sql)) {
              header("Location: my_bookings.php");
              exit();
          } else {
              $errors[] = "Error making booking. Please try again.";
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
      <style>
          .booking-form {
              width: 80%;
              margin: 20px auto;
              padding: 20px;
          }
          .error {
              color: red;
              margin: 10px 0;
              padding: 10px;
              background-color: #ffebee;
              border-radius: 4px;
          }
          .form-group {
              margin-bottom: 15px;
          }
          label {
              display: block;
              margin-bottom: 5px;
          }
          select, input {
              width: 100%;
              padding: 8px;
          }
          .submit-booking {
              background-color:rgb(54, 65, 221);
              color: white;
              padding: 10px;
              border: none;
              cursor: pointer;
              width: 100%;
          }
      </style>
  </head>
  <body>
      <header>
          <h1>Padel Court Booking</h1>
          <nav>
              <a href="index.php">Home</a>
              <a href="my_bookings.php">My Bookings</a>
              <a href="logout.php">Logout</a>
          </nav>
      </header>
  
      <main>
          <?php if(!empty($errors)): ?>
              <?php foreach($errors as $error): ?>
                  <div class="error"><?php echo $error; ?></div>
              <?php endforeach; ?>
          <?php endif; ?>
  
          <form method="POST" class="booking-form">
              <h2>Book Your Court</h2>
  
              <div class="form-group">
                  <label for="court">Select Court:</label>
                  <select id="court" name="court" required>
                      <option value="">Choose a court</option>
                      <?php while($court = mysqli_fetch_assoc($result)): ?>
                          <option value="<?php echo $court['id']; ?>">
                              <?php echo $court['court_name'] . " - " . $court['location']; ?>
                          </option>
                      <?php endwhile; ?>
                  </select>
              </div>
  
              <div class="form-group">
                  <label for="date">Select Date:</label>
                  <input type="date" id="date" name="date" 
                         min="<?php echo $today; ?>" 
                         value="<?php echo $today; ?>" 
                         required>
              </div>
  
              <div class="form-group">
                  <label for="time_slot">Select Time:</label>
                  <select name="time_slot" required>
                      <option value="09:00">9:00 AM</option>
                      <option value="10:00">10:00 AM</option>
                      <option value="11:00">11:00 AM</option>
                      <option value="12:00">12:00 PM</option>
                      <option value="13:00">1:00 PM</option>
                      <option value="14:00">2:00 PM</option>
                      <option value="15:00">3:00 PM</option>
                      <option value="16:00">4:00 PM</option>
                      <option value="17:00">5:00 PM</option>
                      <option value="18:00">6:00 PM</option>
                      <option value="19:00">7:00 PM</option>
                      <option value="20:00">8:00 PM</option>
                  </select>
              </div>
  
              <div class="form-group">
                  <label for="players">Number of Players:</label>
                  <select id="players" name="players" required>
                      <option value="2">2 Players</option>
                      <option value="3">3 Players</option>
                      <option value="4">4 Players</option>
                  </select>
              </div>
  
              <input type="submit" name="book" value="Book Court" class="submit-booking">
          </form>
      </main>
  
      <footer>
          <p>© This website was developed by Ahmed AL SABARI, HAMED AL KINDI</p>
          <p>IT students - University of Technology and Applied Sciences - Nizwa</p>
      </footer>
  </body>
  </html>