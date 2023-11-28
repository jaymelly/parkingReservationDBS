<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="admin.css">
	<title>Admin</title>
</head>
<body>
	<?php
		$conn = new mysqli('127.0.0.1', 'phpuser', 'phpwd', 'cse3241_project');
	?>
	<header class="header" style="font-size: 40px; margin-left: 10px">Admin Portal</header>
	
	<section class="sidebar">
		<div class="grid-item"><a href="index.php">Home</a></div>
		<div class="grid-item"><a href="UserInterface.php">User Page</a></div>
                <div class="grid-item"><a href="login.php">Admin Portal</a></div>
	</section>

	<main class="main">
		<div class="card">
		<h1>Zone Information</h1>
		<div disable="true" onclick="showDate()">
			<input disabled="true" class="search_input" type="text" placeholder="Find Spots Taken by date or range of dates" placeholder-style="color: #888888;"></input>
			<div id="dateDiv" style="disploay: none">
				<form method="post">
            		            	<input class="date" type="date" id="start-date" name="start-date">
                        		<input class="date" type="date" id="end-date" name="end-date">
					<button type="submit" name="submit" class="search_button">Search</button>
					<button class="search_button" onclick="resetSpots()">Reset</button>
				</form>
			</div>
		</div>
	<table style="margin-bottom: 40px">
        	<thead>
            		<tr>
                		<th>Zone_num</th>
                		<th>Designated_spots</th>
                		<th>Rate</th>
				<th>Spots Taken</th>
				<th>Event Date</th>
            			<th>Name</th>
			</tr>
        	</thead>
        	<tbody>
			<?php
				$sql = '';
				$startDate = '';
				$endDate = '';
				$check = -1;

				if (isset($_POST['submit']) && isset($_POST['start-date']) && isset($_POST['end-date'])) {
					if (!empty($_POST['start-date']) && !empty($_POST['end-date'])) {
						$startDate = $_POST['start-date'];
						$endDate = $_POST['end-date'];
						$sql = "SELECT * FROM zones WHERE Zone_date between '$startDate' AND '$endDate'";
						if ($endDate <= $startDate) {
							echo '<div class="message">End date must be after start date.</div>';
							$sql = "SELECT * FROM zones WHERE Zone_date = '2023-11-23'";
						}
					
					} else if (!empty($_POST['start-date']) && empty($_POST['end-date'])) {
						$startDate = $_POST['start-date'];	
						$sql = "SELECT * FROM zones WHERE Zone_date = '$startDate'";
					} else if (empty($_POST['start-date']) && !empty($_POST['end-date'])) {
						echo '<div class="message">Start date is empty.</div>';
						$sql = "SELECT * FROM zones WHERE Zone_date = '2023-11-23'";
					} else {
						echo '<div class="message">You did not select any dates.</div>';
						$sql = "SELECT * FROM zones WHERE Zone_date = '2023-11-23'";
					}
				} else {
					$sql = "SELECT * FROM zones WHERE Zone_date = '2023-11-23'";
				}

				$result = $conn -> query($sql);
				if (!$result) {
					die("Error in SQL query: " . $conn->error);
				}
				while ($row = $result -> fetch_row()){
					echo '<tr>';
					echo '<td>' . $row[0] . '</td>';
					echo '<td id="highlight_spot" onclick="showSpotChange(this)">' . $row[1] . '</td>';
					echo '<td id="highlight_rate" onclick="showRateChange(this)">' . $row[2] . '</td>';
					echo '<td>' . $row[3] . '</td>';
					echo '<td>' . $row[4] . '</td>';
					echo '<td>' . $row[5] . '</td>';
					echo '</tr>';
				}
				echo '<p>Selected From Date: ' . $startDate . '</p>';
				echo '<p>Selected To Date: ' . $endDate . '</p>';
			?>
        	</tbody>
    	</table>          
			<div style="display: flex">	
			<h3>Add new zone</h3>
			<form method="post">
    				Name: <input type="text"  name="name" required><br>
    				Designated Spots: <input type="text"  name="designated_spots" required><br>
				Rate: <input type="number" name="rate" required><br>
    				Zone Date: <input type="date" name="zone_date" required><br>
    				<button type="submit" name="addEntry">Add Entry</button>
			</form>
			<?php
				if (isset($_POST["addEntry"])) {
					if (isset($_POST["designated_spots"]) && isset($_POST["rate"]) && isset($_POST["zone_date"]) && isset($_POST["name"])) {
						$designatedSpots = $_POST["designated_spots"];
						$rate = $_POST["rate"];
						$zoneDate = $_POST["zone_date"];
						$name = $_POST["name"];
						$sql = "INSERT INTO ZONES (Designated_spots, Rate, Spots_taken, Zone_date, Name) VALUES('$designatedSpots', '$rate', '0', '$zoneDate', '$name')";
						if ($conn->query($sql) === TRUE) {
							echo "<p>New entry added successfully!</p>";
							echo '<script type="text/javascript">location.reload(true);</script>';
						} else {
							echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
						}
					}
				}
			?>

			<h3>Delete zone</h3>
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
        				$query = "DELETE FROM ZONES WHERE ZONE_NUM = '$zone_num' AND ZONE_DATE = '$zone_date'";

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
			?>
		</div>
		</div>

		<div class="card">
			<h2>Other Actions (hover on the spot and rate in the cells)</h2>
			<div class="action-block">	
				<h4>Adjusting Spots</h4>
				<form method="post">	
				<div id="spotChange" class="spotChange-block" style="display: none"></div>
				<div id="setSpot" style="display:none; margin-top: 10px">
					<form method="post">	
						Enter the zone number:
						<input type="text" id="zoneValue" name="zoneValue" required>
						<br>Enter the date:
						<input type="text" id="dateValue" name="dateValue" required>
						<br>Enter the new number of spots:
						<input type="text" id="spotValue" name="spotValue" required>
						<button type="submit" name="submitSpot" class="update_button">Update</button>
					</form>
				</div>
				<?php
					if (isset($_POST['submitSpot'])) {
    						if (isset($_POST['zoneValue']) && isset($_POST['dateValue']) && isset($_POST['spotValue'])) {
    							$spotValue = $_POST['spotValue'];
    							$zoneNum = $_POST['zoneValue'];
							$dateValue = $_POST['dateValue'];
							$sql = "UPDATE zones SET Designated_spots = '$spotValue' WHERE Zone_num = '$zoneNum' AND Zone_date = '$dateValue'";
							if ($conn -> query($sql) === TRUE) {
								echo "Spots record updated successfully";
								echo '<script type="text/javascript">location.reload(true);</script>';
							}	
						} else {
							echo "Missing date in the POST";
						}
					}	
				?>
			</div>
			<div class="action-block">	
				<h4>Adjusting Rates</h4>
				<div id="rateChange" class="rateChange-block" style="display: none"></div>
				<div id="setRate" style="display:none; margin-top: 10px">
					<form method="post">	
						Enter the zone number:
						<input type="text" id="zoneValue" name="zoneValue" required>
						<br>Enter the date:
						<input type="text" id="dateValue" name="dateValue" required>
						<br>Enter the new rate:
						<input type="text" id="rateValue" name="rateValue" required>
						<button type="submit" name="submitRate" class="update_button">Update</button>
					</form>
				</div>
				<?php
					if (isset($_POST['submitRate'])) {
    						if (isset($_POST['zoneValue']) && isset($_POST['rateValue'])) {
    							$rateValue = $_POST['rateValue'];
    							$zoneNum = $_POST['zoneValue'];
							$dateValue = $_POST['dateValue'];
							$sql = "UPDATE zones SET Rate = '$rateValue' WHERE Zone_num = '$zoneNum' AND Zone_date = '$dateValue'";
							if ($conn -> query($sql) === TRUE) {
								echo "Rate record updated successfully";
								echo '<script type="text/javascript">location.reload(true);</script>';
							}	
						} else {
							echo "Missing date in the POST";
						}
					}	
					
				?>
			</div>
		</div>		
		
		<div class="card">	
			<div class="reportDateDiv">
				Pick a specific date:
				<form method="post">
					<input class="date" type="date" id="report-date" name="report-date">
					<button type="submit" name="submitReportDate" style="font-size: 20px">Run Report</button>
				</form>
			</div>
			<div>
			<?php
				if (isset($_POST['submitReportDate'])) {
					if (isset($_POST['report-date']) && !empty($_POST['report-date'])) {
					$reportDate = $_POST['report-date'];
					echo '<p>The Report on ' . $reportDate . ' is following:</p>';
					$sql = "SELECT Z.Zone_num, Z.Designated_spots, COUNT(R.Confirmation_id), Z.Rate, COUNT(R.Confirmation_id) * Z.Rate FROM zones Z, reservations R WHERE R.Date_reserved = '$reportDate' AND R.Zone_num = Z.Zone_num AND Z.Zone_date = '$reportDate' GROUP BY Z.Zone_num, Z.Designated_spots, Z.Rate";
					$result = $conn -> query($sql);
					if (!$result) {
						die("Error in SQL query: " . $conn->error);
					}

					echo '<table><thead><tr><th>Zone_num</th><th># of Spots</th><th># of Reservations</th><th>Fee/hour</th><th>Total Revenue</th></tr></thead><tbody>';
					while ($row = $result -> fetch_row()){
						echo '<tr>';
						echo '<td>' . $row[0] . '</td>';	
						echo '<td>' . $row[1] . '</td>';
						echo '<td>' . $row[2] . '</td>';
						echo '<td>' . $row[3] . '</td>';
						echo '<td>' . $row[4] . '</td>';
						echo '</tr>';
					}
					echo '</tbody></table>';
					} else {
						echo '<p>You have to select a date.</p>';
					}
				}
			?>
			</div>	
		</div>

	</main>

	<script>
        	function showDate() {
            		var dateDiv = document.getElementById("dateDiv");
            		dateDiv.style.display = "block";
			dateDiv.style.backgroundColor = "#fff";
			dateDiv.style.width = "500px";
        		dateDiv.style.height = "200px"; // Set the height as needed
    			dateDiv.style.color = "#333"; // Set the text color
    			dateDiv.style.border = "1px solid #ccc"; // Add a border
		}
		
		function resetSpots() {
			var startDateInput = document.getElementById('start-date');
			startDateInput.value = '';
			var selectedStartDateElement = document.getElementById('selected-start-date');
  			selectedStartDateElement.textContent = 'Selected Start Date: ';
			var endDateInput = document.getElementById('end-date');
			endDateInput.value = '';
			var selectedEndDateElement = document.getElementById('selected-end-date');
  			selectedEndDateElement.textContent = 'Selected End Date: ';
			
		}

		function showSpotChange(td) {
			var cellValue = td.innerText || td.textContent;
			// Find the previous sibling <td>
        		var zoneNumTd = td.previousElementSibling;

        		// Check if the previous <td> exists before accessing its value
       	 		var zoneNum = zoneNumTd ? zoneNumTd.innerText || zoneNumTd.textContent : null;
			
			var infoBlock = document.getElementById('spotChange');
			var setBlock = document.getElementById('setSpot');
			infoBlock.innerHTML = 'The current designated spots of zone ' + zoneNum + ' are: ' + cellValue + '<br>';
			if (infoBlock.style.display == 'none') {
				infoBlock.style.display = 'block';
			}
			if (setBlock.style.display == 'none') {
				setBlock.style.display = 'block';
			}
		}

		function showRateChange(td) {
			var cellValue = td.innerText || td.textContent;
			var infoBlock = document.getElementById('rateChange');
			var setBlock = document.getElementById('setRate');
			infoBlock.innerHTML = 'The current rate is: ' + cellValue + '$/hour<br>';
			if (infoBlock.style.display == 'none') { 
				infoBlock.style.display = 'block';
			}
			if (setBlock.style.display == 'none') {
				setBlock.style.display = 'block';
			}
		}

    	</script>
</body>

</html>
