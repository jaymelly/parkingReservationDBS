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
	
	<section class="sidebar"></section>

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
	<table>
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
		
			<h2>Create new zone</h2>          
			<div>
				<ul>
					<li>Name: 
						<input class="search_input" type="text" placeholder="Enter the name of the new zone you want to add" placeholder-style="color: #888888;">
					</li>
					<li>Max Spots: 
						<input class="search_input" type="text" placeholder="Enter the number of designated spots" placeholder-style="color: #888888;">
					</li>
					<li>Rate: 
						<input class="search_input" type="text" placeholder="Enter the normal rate" placeholder-style="color: #888888;">
					</li>
				</ul>
			</div>
			<button class="search_button">Confirm</button>	
		</div>

		<div class="card">
			<h2>Other Actions (hover on the spot and rate in the cells)</h2>
			<div class="action-block">	
				<h4>Adjusting Spots</h4>
				<form method="post">	
				<div id="spotChange" class="spotChange-block" style="display: none"></div>
				<div id="setSpot" style="display:none; margin-top: 15px">
					<form method="post">	
						Enter the zone number:
						<input type="text" id="zoneValue" name="zoneValue">
						<br>To set a new value:
						<input type="text" id="spotValue" name="spotValue">
						<button type="submit" name="submitSpot" class="update_button">Update</button>
					</form>
				</div>
				<?php
					if (isset($_POST['submitSpot'])) {
    						if (isset($_POST['zoneValue']) && isset($_POST['spotValue'])) {
    							$spotValue = $_POST['spotValue'];
    							$zoneNum = $_POST['zoneValue'];
							$sql = "UPDATE zone SET Designated_spots = '$spotValue' WHERE Zone_num = '$zoneNum'";
							if ($conn -> query($sql) === TRUE) {
								echo "Spot record updated successfully";
							}	
						} else {
							echo "Missing 'zoneNum' in the POST data";
						}
					}	
				?>
			</div>
			<div class="action-block">	
				<h4>Adjusting Rates</h4>
				<div id="rateChange" class="rateChange-block" style="display: none"></div>
				<div id="setRate" style="display:none; margin-top: 15px">
					<form method="post">	
						Enter the zone number:
						<input type="text" id="zoneValue" name="zoneValue">
						<br>To set a new value:
						<input type="text" id="rateValue" name="rateValue">
						<button type="submit" name="submitRate" class="update_button">Update</button>
					</form>
				</div>
				<?php
					if (isset($_POST['submitRate'])) {
    						if (isset($_POST['zoneValue']) && isset($_POST['rateValue'])) {
    							$rateValue = $_POST['rateValue'];
    							$zoneNum = $_POST['zoneValue'];
							$sql = "UPDATE zone SET Rate = '$rateValue' WHERE Zone_num = '$zoneNum'";
							if ($conn -> query($sql) === TRUE) {
								echo "Rate record updated successfully";
							}	
						} else {
							echo "Missing 'zoneNum' in the POST data";
						}
					}	
					
				?>
			</div>
		</div>		
		
		<div class="card">	
			<button onclick="showDate()" style="font-size: 20px"71                                 $result = $conn -> query($sql);>Run Report</button>
			<div id="dateDiv" style="display: none">
				Pick a specific date
				<input type="date" id="report-date">
			</div>
		</div>

	</main>

	<script>
        	function showDate() {
            		var dateDiv = document.getElementById("dateDiv");
            		dateDiv.style.display = "block";
			dateDiv.style.backgroundColor = "#fff";
			dateDiv.style.width = "500px";
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
