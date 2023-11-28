<h2>Add New Zone Entry</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Designated Spots: <input type="text"  name="designated_spots" required><br>
	Rate: <input type="number" name="rate" required><br>
    Zone Date: <input type="date" name="zone_date"><br>
    <input type="submit" value="Add Entry">
</form>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    // Check if 'zone_num' key is set

// Check if 'designated_spots' key is set
if (!empty($_POST['designated_spots']) && !empty($_POST['rate'])&& !empty($_POST['zone_date'])) {
$designatedSpots = isset($_POST["designated_spots"]) ?$_POST["designated_spots"] : null;

// Check if 'rate' key is set
$rate = isset($_POST["rate"]) ?$_POST["rate"] : null;




// Check if 'Zone date' key is set
$zoneDate = isset($_POST["zone_date"]) ? $_POST["zone_date"] : null;


		

			// Insert the new entry
			$sql = "INSERT INTO ZONE (DESIGNATED_SPOTS, RATE, ZONE_DATE, DISTANCE)
			VALUES('$designatedSpots', '$rate', '$zoneDate', '0')";
	

				if ($conn->query($sql) === TRUE) {
					echo "<p>New entry added successfully!</p>";
				} else {
					echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
				}

				//Redirect to the same page to avoid form resubmission
				header("Location: " . $_SERVER["PHP_SELF"]);
				exit();
		
		

}
}

?>
