<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="UserInterface.css">
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
<header class="header" style="font-size: 40px; margin-left: 10px">User Page</header>
<section class="sidebar">
                <div class="grid-item"><a href="index.php">Home</a></div>
                <div class="grid-item"><a href="UserInterface.php">User Page</a></div>
                <div class="grid-item"><a href="login.php">Admin Portal</a></div>
</section>
<main class="main">
<!---------------------------------------------------- Search for spot to reserve -------------------------------------------------------------------->
<div class="column">
<h1>Search for Open Zones</h1>
<form name="form" action="" method="post">
    <input type="text" name="user_input_search" size="55" placeholder="Enter admired distance from zone or date in YYYYDDMM format" required>
    <input type="submit">
</form>
<?php
// Connection
$sql = "";
$input = "";
$conn = new mysqli('127.0.0.1', 'phpuser', 'phpwd', 'cse3241_project');

// Check if input is set yet
if (isset($_POST['user_input_search'])) {
    $input = $_POST['user_input_search'];
}

// Do sql if there is an input
if (!empty($input)) {
    // Gets SQL query results
    $sql = sprintf("select z.ZONE_NUM, DESIGNATED_SPOTS, SPOTS_TAKEN, RATE, d.DISTANCE, z.Zone_date, d.EVENT from ZONES z, DISTANCE d where z.ZONE_NUM = d.ZONE_NUM and (ZONE_DATE=%d or d.DISTANCE=%d) and (DESIGNATED_SPOTS-SPOTS_TAKEN)>0", $input, $input);
    $result = mysqli_query($conn, $sql);
    // Checks if there are any results
    if (mysqli_num_rows($result)==0) {
        echo "No Results Found. Please Check Formatting and Try Again.";
    // Continue with code
    } else {
?>
    <!-- Print table header -->
    <table style="width:100%">
        <tr>
            <th style="border:1px solid">Zone Number</th>
            <th style="border:1px solid">Spots Available</th>
            <th style="border:1px solid">Rates</th>
            <th style="border:1px solid">Date</th>
            <th style="border:1px solid">Event</th>
        </tr>
    <?php
        // Loop and print results of query into table
        while ($row = mysqli_fetch_array($result)) {
            
            // Get data to send to reservation
            $zone = $row['ZONE_NUM'];
            $rate = $row['RATE'];
            $date = $row['Zone_date'];
	    $event = $row['EVENT'];

            // Prints table information
            echo "<tr>";
            echo "<td style=\"border:1px solid\">". $row['ZONE_NUM']. "</td>";
            echo "<td style=\"border:1px solid\">". ($row['DESIGNATED_SPOTS']-$row['SPOTS_TAKEN']). "</td>";
            echo "<td style=\"border:1px solid\">". $row['RATE']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['Zone_date']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['EVENT']. "</td>";
            //  Checks if user is attempting to reserve date on the same day. If so, it does not display reserve button
            if (date("Y-m-d", strtotime($input)) < date("Y-m-d")) {
                // Change file name to Jack's Make reservation file name and delete this comment
                echo "<form method=post action=\"confirmReservation.php\">";
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
    <input type="text" name="user_input_reservation" size="38" placeholder="Enter Phone Number or Confirmation Number">
    <input type="submit">
</form>
<?php
// Connection
$sql = "";
$input = "";
$conn = new mysqli('127.0.0.1', 'phpuser', 'phpwd', 'cse3241_project');

// Check if input is set yet
if (isset($_POST['user_input_reservation'])) {
    $input = $_POST['user_input_reservation'];
}

if (!empty($input)) {
    // Gets SQL query results
    intval($input);
    $sql = sprintf("select * from RESERVATIONS where PHONE=%s or Confirmation_id=%s", $input, $input);
    $result = mysqli_query($conn, $sql);
    // Checks if any results. If not, try again
    if (mysqli_num_rows($result)==0) {
        echo "No Results Found. Please Check Formatting and Try Again.";
    // Continue with code
    } else {
?>
    <!-- Print table header -->
    <table style="width:75%">
    <tr>
	<th style="border:1px solid">Name</th>
        <th style="border:1px solid">Confirmation_id</th>
        <th style="border:1px solid">Zone_number</th>
        <th style="border:1px solid">Phone</th>
        <th style="border:1px solid">Cancelled</th>
        <th style="border:1px solid">Date_reserved</th>
        <th style="border:1px solid">Fee</th>
    </tr>
<?php
        // Prints results of query into table
        while ($row = mysqli_fetch_array($result)) {

            // Get data to send to cancellation
            $confirmation_num = $row[0];

            // Prints table information
            echo "<tr>";
	    echo "<td style=\"border:1px solid\">". $row['NAME']. "</td>";
            echo "<td style=\"border:1px solid\">". $row[0]. "</td>";
            echo "<td style=\"border:1px solid\">". $row[1]. "</td>";
            echo "<td style=\"border:1px solid\">". $row[2]. "</td>";
            echo "<td style=\"border:1px solid\">". $row[3]. "</td>";
            echo "<td style=\"border:1px solid\">". $row[4]. "</td>";
            // Checks if button is already canceled, if it is then option is disabled
		if ($row[3] == true) {
                echo "<td style=\"border:1px solid\">Cancelled</td>";
                echo "<td style=\"border:none\"><button disabled>Cancel</button></td>";
            } else {
                if (date("Y-m-d", strtotime($row[4])) >= date('Y-m-d', strtotime(' - 2 days'))) {
                    echo "<td style=\"border:1px solid\"></td>";
                    echo "<td style=\"border:none\"><button disabled>Cancel</button></td>";
                } else {
                    // Send phone or confirmation, mainly confirmation
                    echo "<td style=\"border:1px solid\">Active</td>";
                    echo "<form method=post action='cancelReservation.php'>";
                    echo "<td style=\"border:none\"><button name='confirmation_num' value=$confirmation_num>Cancel</button></td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>
</div>
</main>

</body>
</html>
