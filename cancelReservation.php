<?php
include 'reservation.php';
session_start();
$phone;
$newRes;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>Make Reservation</title>
</head>
<body>
    <?php
        //$conn = new mysqli("127.0.0.1", "root", "mySQLmySQL", "ParkingReservations");
        $conn = new mysqli('127.0.0.1', 'phpuser', 'phpwd', 'cse3241_project');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['date']) && !empty($_POST['rate']) && !empty($_POST['zone_num'])) {
                $_SESSION['date'] = $_POST['date'];
                $_SESSION['zone'] = $_POST['zone_num'];
                $_SESSION['fee']= $_POST['rate'];  
                

            }
            if(!empty($_POST['phone'])){
                $phone = $_POST['phone'];
                $newRes = new Reservation($_SESSION['zone'], $phone, $_SESSION['date'], $_SESSION['fee'], $conn);
                $newRes -> generateUniqueCnf();
            }
            $zone = $_SESSION['zone'];
            $fee = $_SESSION['fee'];
            $date = $_SESSION['date'];
        }
    ?>

    <div class="header-container">
        <header class="header">Confirm Reservation</header>
    </div>

    <?php
        if(isset($zone) && isset($fee)){
            echo "<p class = \"sentence\"> Your selected Zone: $zone </p>";
            echo "<p class = \"sentence\"> Your Selected Zone Rate: $fee </p>";
        }
       
    ?>

    <form method="post">
        <label for="input_usr_reservation">Please Enter a Phone Number for this reservation: </label>
        <input type="text" name="phone" id="input_usr_reservation" required>

        

        <!--
        <label for="input_usr_reservation">Please Enter a name: </label> 
        <input type="text" name="input_usr_phone" id="input_usr_date" required>
        -->
        <?php
        if(!isset($phone)){
            echo "<p style=\"color: red;\" > Please Enter Phone Number! </p>";
        } else if (!isset($_SESSION['date'])){
            echo "<p style=\"color: red;\" > Date not imported! </p>";
        } else if (!isset($_SESSION['fee'])){
            echo "<p style=\"color: red;\" > Fee did not import </p>";
        } else if(!isset($_SESSION['zone'])){
            echo "<p style=\"color: red;\" > Zone did not import </p>";
        }
        else {
            $status = false;
            if(isset($newRes)){

                $status = $newRes->insertReservation();
                if($status) echo "<p> Reservation Made successfully! </p>";
                else echo "<p style=\"color: red;\" > Reservation failed to create! </p>";
            }
        }
       
        $conn -> close();
        ?>

        <div>
            <button class = "button" type="submit" name="submitButton">Confirm Reservation</button>
            <button class = "button" type="" name="goBackButton" onclick="goBack()">Go Back</button>
            <button class = "button" type="" name="nextButton" onclick="goToNewReservations()">New Reservation</button>
        </div>
    </form>
    
    
    <script>
        function showPopup() {
            alert("Your reservation has been Confirmed! \nPress go back to see your other reservations, or make another reservation to reserve a new spot."); 
        }
        function goBack() {
            window.location.href = "UserInterface.php"; 
        }
        function goToNewReservations() {
            window.location.href = "UserInterface.php"; 
        }
    </script>

</body> 
</html>
