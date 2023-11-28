<html>
<head>
<title>User Page</title>
<!-- Styling of columns -->
<style>
* {
  box-sizing: border-box;
}

.column {
  float: left;
  width: 50%;
  padding: 10px;
  height: 300px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
</head>
<body>

<!---------------------------------------------------- Search for spot to reserve -------------------------------------------------------------------->
<div class="column">
<h1>Search for Open Zones</h1>
<form name="form" action="" method="post">
    <input type="text" name="user_input_search" onsubmit=true size="55" value="<?= (isset($_POST['user_input_search']) ? $_POST['user_input_search'] : '');?>" placeholder="Enter admired distance from zone or date in YYYYDDMM format" required>
    <input type="submit">
</form>
<?php
// Connection
$sql = "";
$input = "";
$conn = new mysqli('localhost', 'phpuser', 'phpwd', 'PARKING');

// Check if input is set yet
if (isset($_POST['user_input_search'])) {
    $input = $_POST['user_input_search'];
}

// Do sql if there is an input
if (!empty($input)) {
    // Gets SQL query results
    $sql = sprintf("select z.ZONE_NUM, DESIGNATED_SPOTS, SPOTS_TAKEN, RATE, d.DISTANCE from ZONE z, DISTANCE d where z.ZONE_NUM = d.ZONE_NUM and (ZONE_DATE=%d or d.DISTANCE=%d) and (DESIGNATED_SPOTS-SPOTS_TAKEN)>0", $input, $input);
    $result = mysqli_query($conn, $sql);
    // Checks if there are any results
    if (mysqli_num_rows($result)==0) {
        echo "No Results Found. Please Check Formatting and Try Again.";
    // Continue with code
    } else {
?>
    <!-- Print table header -->
    <table style="width:75%">
        <tr>
            <th style="border:1px solid">Zone Number</th>
            <th style="border:1px solid">Spots Available</th>
            <th style="border:1px solid">Rates</th>
        </tr>
    <?php
        // Loop and print results of query into table
        while ($row = mysqli_fetch_array($result)) {
            
            // Get data to send to reservation
            $zone = $row['ZONE_NUM'];
            $rate = $row['RATE'];
            $date = $input;

            // Prints table information
            echo "<tr>";
            echo "<td style=\"border:1px solid\">". $row['ZONE_NUM']. "</td>";
            echo "<td style=\"border:1px solid\">". ($row['DESIGNATED_SPOTS']-$row['SPOTS_TAKEN']). "</td>";
            echo "<td style=\"border:1px solid\">". $row['RATE']. "</td>";
            //  Checks if user is attempting to reserve date on the same day. If so, it does not display reserve button
            if (date("Y-m-d", strtotime($input)) < date("Y-m-d")) {
                // Change file name to Jack's Make reservation file name and delete this comment
                echo "<form method=post action=\"ReserveASpot.php\">";
                echo "<input type='hidden' name='date' value=$date>";
                echo "<input type='hidden' name='rate' value=$rate>";
                echo "<td style=\"border:none\"><button name='zone_num' value=$zone>Reserve</button></td>";
                echo "</form>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>
</div>

<!---------------------------------------------------- Search for your reservations -------------------------------------------------------------------->
<div class="column">
<h1>Search for Your Reservation</h1>
<form name="form" action="" method="post">
    <input type="text" name="user_input_reservation" onsubmit="return false" size="38" value="<?= (isset($_POST['user_input_reservation']) ? $_POST['user_input_reservation'] : '');?>" placeholder="Enter Phone Number or Confirmation Number">
    <input type="submit">
</form>
<?php
// Connection
$sql = "";
$input = "";
$conn = new mysqli('localhost', 'phpuser', 'phpwd', 'PARKING');

// Check if input is set yet
if (isset($_POST['user_input_reservation'])) {
    $input = $_POST['user_input_reservation'];
}

if (!empty($input)) {
    // Gets SQL query results
    $sql = sprintf("select * from RESERVATION where PHONE=%d or CONFIRMATION_NUM=%d", $input, $input);
    $result = mysqli_query($conn, $sql);
    // Checks if any results. If not, try again
    if (mysqli_num_rows($result)==0) {
        echo "No Results Found. Please Check Formatting and Try Again.";
    // Continue with code
    } else {
?>
    <!-- Print table header -->
    <table style="width:90%">
    <tr>
        <th style="border:1px solid">Phone Number</th>
        <th style="border:1px solid">Date Reserved</th>
        <th style="border:1px solid">Fee</th>
        <th style="border:1px solid">Zone Number</th>
        <th style="border:1px solid">Confirmation Number</th>
        <th style="border:1px solid">Cancellation Status</th>
    </tr>
<?php
        // Prints results of query into table
        while ($row = mysqli_fetch_array($result)) {

            // Get data to send to cancellation
            $confirmation_num = $row['CONFIRMATION_NUM'];

            // Prints table information
            echo "<tr>";
            echo "<td style=\"border:1px solid\">". $row['PHONE']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['DATE_RESERVED']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['FEE']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['ZONE_NUM']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['CONFIRMATION_NUM']. "</td>";
            // Checks if button is already canceled, if it is then option is disabled and prints cancelled in column
            if ($row['CANCELLED'] == true || date("Y-m-d", strtotime($row['DATE_RESERVED'])) >= date('Y-m-d', strtotime(' - 2 days'))) {
                echo "<td style=\"border:1px solid\">Cancelled</td>";
                echo "<td style=\"border:none\"><button disabled>Cancel</button></td>";
            } else {
                // Send phone or confirmation, mainly confirmation
                // Change file name to Jacks cancel reservation file name
                echo "<td style=\"border:1px solid\"></td>";
                echo "<form method=post action='ReserveASpot.php'>";
                echo "<td style=\"border:none\"><button name='confirmation_num' value=$confirmation_num>Cancel</button></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>
</div>
</body>
</html>
