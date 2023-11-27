<?php

include 'reservation.php';
session_start();

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Make Reservation</title>
</head>
<body>
    <?php
        $conn = new mysqli("127.0.0.1", "root", "mySQLmySQL", "ParkingReservations");
    ?>
    
    <header class="header" style="font-size: 40px; margin-left: 10px">Confirm Reservation</header>

    <?php
        echo "<p> You have selected Zone: " + $reservation['zone'] + "</p>";
        echo "<p> Your Selected zone rate: " + $reservation['zone'] + "</p>";
    ?>
    <p> Enter a valid confirmation number or a phone number associated with the reservation below.</p>

    <form method="post">
        <label for="input_usr_reservation">Please enter a Phone Number for this reservation:</label>
        <input type="text" name="input_usr_phone" id="input_usr_reservation" required>

        <label for="input_usr_reservation">Please Enter a name:</label>
        <input type="text" name="input_usr_phone" id="input_usr_date" required>

        <input type="submit" value="Submit">
    </form>
      <?php
        $date;
        $phone;
        $fee;
        $zone;
        $newRes;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $phone = $_POST['input_usr_phone'];
            $date = $_SESSION['date'];
            $zone = $_SESSION['zone'];
            $fee = $_SESSION['rate'];
            $newRes = new Reservation($zone, $phone, $date, $fee);
        }
        if(!isset($phone)){
            echo "<p style=\"color: red;\" > Please Enter Phone Number! </p>";
        } else if (!isset($date)){
            echo "<p style=\"color: red;\" > Date not imported! </p>";
        } else if (!isset($fee)){
            echo "<p style=\"color: red;\" > Fee did not import </p>";
        } else if(!isset($zone)){
            echo "<p style=\"color: red;\" > Fee did not import </p>";
        }
        else {
            $newRes->insertReservation();
            echo "<p> Reservation Made successfully! </p>";
        }
       
    $conn -> close();
      ?>

</body> 
</html>
