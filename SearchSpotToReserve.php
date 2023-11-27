<html>
<head>
<title>Search for a Spot to Reserve</title>
<h1>Search for Open Zones</h1>
<body>
    <form name="form" action="" method="post">
        <input type="text" name="user_input" size="35" placeholder="Enter distance or date in YYYYDDMM format">
        <input type="submit">
    </form>
<?php
    // Connection
    $sql = "";
    $conn = new mysqli('localhost', 'phpuser', 'phpwd', 'PARKING');
    // Get date and check valid input
    $input = $_POST['user_input'];
    if (empty($input)) {
        echo "Not a valid input";
    } else {
        $sql = sprintf("select ZONE_NUM, DESIGNATED_SPOTS, SPOTS_TAKEN, RATE from ZONE where (ZONE_DATE=%s or DISTANCE=%s) and SPOTS_TAKEN>0", $input, $input);
        $result = mysqli_query($conn, $sql);
        echo "<table style=\"width:30%\">";
        echo "<tr>
                <th style=\"border:1px solid\">Zone Number</th>
                <th style=\"border:1px solid\">Spots Available</th>
                <th style=\"border:1px solid\">Rates</th>
            </tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td style=\"border:1px solid\">". $row['ZONE_NUM']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['SPOTS_TAKEN']. "</td>";
            echo "<td style=\"border:1px solid\">". $row['RATE']. "</td>";
            if (date("Y-m-d", strtotime($input)) != date("Y-m-d")) {
            echo "<td style=\"border:none\"><a href=\"ReserveSpot.php\"><button>Reserve</button></a></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
?>
</body>
</html>
