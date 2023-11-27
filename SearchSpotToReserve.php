<html>
<head>
<title>Search for a Spot to Reserve</title>
<h1>Search for Open Zones</h1>
<body>
    <form name="form" action="" method="post">
        <input type="text" name="user_input" size="35" placeholder="Enter distance or date in YYYYDDMM format" required>
        <input type="submit">
    </form>
<?php
// Connection
$sql = "";
$input = "";
$conn = new mysqli('localhost', 'phpuser', 'phpwd', 'PARKING');

// Check if input is set yet
if (isset($_POST['user_input'])) {
    $input = $_POST['user_input'];
}

// Do sql if there is an input
if (!empty($input)) {
    // Deal with distance
    $sql = sprintf("select ZONE_NUM, DESIGNATED_SPOTS, SPOTS_TAKEN, RATE from ZONE where (ZONE_DATE=%d or DISTANCE=%d) and (DESIGNATED_SPOTS-SPOTS_TAKEN)>0", $input, $input);
    $result = mysqli_query($conn, $sql);
    // Checks if there are any results
    if (mysqli_num_rows($result)==0) {
        echo "No Results Found. Please Check Formatting and Try Again.";
    // Continue with code
    } else {
?>
    <!-- Print table header -->
    <table style="width:30%">
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
                echo "<td style=\"border:none\"><button name='zone_num' value=$zone>Reserve</button></a></td>";
                echo "</form>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>
</body>
</html>
