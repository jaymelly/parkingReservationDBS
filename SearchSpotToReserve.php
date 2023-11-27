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
    // Change from spots taken to checking avaiable spots (des-taken)>0
    $sql = sprintf("select ZONE_NUM, DESIGNATED_SPOTS, SPOTS_TAKEN, RATE from ZONE where (ZONE_DATE=%s or DISTANCE=%s) and SPOTS_TAKEN>0", $input, $input);
    $result = mysqli_query($conn, $sql);
?>
    <table style="width:30%">
    <tr>
        <th style="border:1px solid">Zone Number</th>
        <th style="border:1px solid">Spots Available</th>
        <th style="border:1px solid">Rates</th>
    </tr>
<?php
        while ($row = mysqli_fetch_array($result)) {

            $zone = $row['ZONE_NUM'];

            echo "<tr>";
            echo "<td style=\"border:1px solid\">". $row['ZONE_NUM']. "</td>";
            // Need to print (des-taken)
            echo "<td style=\"border:1px solid\">". $row['SPOTS_TAKEN']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['RATE']. "</td>";
            if (date("Y-m-d", strtotime($input)) < date("Y-m-d")) {
                // Change file name to Jack's Make reservation file name
                echo "<form method=post action=\"ReserveASpot.php\">";
                echo "<td style=\"border:none\"><button name='zone_num' value=$zone>Reserve</button></a></td>";
                echo "</form>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
?>
</body>
</html>
