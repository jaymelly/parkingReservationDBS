<h2>Remove Zone Entry</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Zone Number: <input type="number" name="zone_num" required><br>
	Zone Date : <input type="date" name="zone_date" required><br>
	<input type="submit" value="Remove Entry">
</form>

<?php

// Handle form submission

    // Get zone_num and zone_date from the form if set
	if (!empty($_POST['zone_num']) && !empty($_POST['zone_date'])) {
    $zone_num = isset($_POST['zone_num']) ? $_POST['zone_num'] : null;
	$zone_date = isset($_POST["zone_date"]) ? $_POST["zone_date"] : null;


    // Check if both values are set before proceeding
    if ($zone_num !== null || $zone_date !== null) {
        $query = "DELETE FROM ZONE WHERE ZONE_NUM = '$zone_num' AND ZONE_DATE = '$zone_date'";

        try {
            $result = $conn->query($query);
            if ($result) {
                echo "Zone removed successfully!";
            } else {
                echo "Error removing zone: " . $conn->error;
            }
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Please provide Zone Number and date.";
    }
	//Redirect to the same page to avoid form resubmission
	header("Location: " . $_SERVER["PHP_SELF"]);
	exit();
}