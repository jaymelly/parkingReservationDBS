<?php
include 'reservation.php';
$confirm_num;
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>Cancel Reservation</title>

    
</head>
<body>
    <?php
        //$conn = new mysqli("127.0.0.1", "root", "mySQLmySQL", "ParkingReservations");
        $conn = new mysqli('127.0.0.1', 'phpuser', 'phpwd', 'cse3241_project');
        $cancel_status = false;
            if (!empty($_POST['confirmation_num'])) {
                $confirm_num = $_POST['confirmation_num'];
		echo $_POST['confirmation_num'];
            }
    ?>
    
    <div class="header-container">
        <header class="header">Cancel Reservation</header>
    </div>

    <?php
        if(isset($confirm_num)){
            echo "<p class = \"sentence\"> You have chosen to cancel your reservation: $confirm_num </p>";
            $tempRes = new Reservation(null, null, null, null, $conn);
            $tempRes->setResConfirm($confirm_num);

            echo "<p class = \"sentence\"> Reservation Confirmation #:  $confirm_num </p>";
            echo "<p class = \"sentence\"> Reservation Zone: $tempRes->zone_num </p>";
            echo "<p class = \"sentence\"> Reservation Date:  $tempRes->date_reserved </p>";
            echo "<p class = \"sentence\"> Reservation Fee:  $tempRes->fee </p>";
            echo "<p class = \"sentence\"> Are you sure you wish to cancel? </p>";

            $tempRes->cancelReservation();
            
        } else {
            echo "<p class = \"sentence\">  No reservation selected to cancel. </p>";
        }
        
    ?>
    <div class="button-container"> 
        <form method="post" action="userInterface.php">
            <button class = "button" type="submit" name="cancelButton" onclick="showPopup()">Confirm Cancellation</button>
            <button class = "button" type="" name="goBackButton" onclick="goBack()">Go Back</button>
            <button class = "button" type="" name="nextButton" onclick="goToNewReservations()">New Reservations</button>
        </form>
        
    </div>
    <?php $currentDateTime = date("Y-m-d H:i:s");?>
    <p>Current Date and Time: <?php echo $currentDateTime; ?></p>

    
    <?php
        $conn -> close();
    ?>
    <script>
        function showPopup() {
            alert("Your reservation has been cancelled! \nPress go back to see your other reservations, or make a new reservation to reserve a new spot."); 
            window.location.href = "userInterface.php";
        }
        function goBack() {
            window.location.href = "userInterface.php"; 
        }
        function goToNewReservations() {
            window.location.href = "userInterface.php"; 
        }
    </script>


</body> 
</html>
