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
					<button type="submit" name="submit" class="search_button" onclick="searchSpots()">Search</button>
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
				<th>Name</th>
				<th>Spots Taken</th>
            		</tr>
        	</thead>
        	<tbody>
			<?php
				$sql1 = "SELECT * FROM ZONE";
				$result1 = $conn -> query($sql1);
				$startDate = '';
				$endDate = '';
				$check = -1;
				if (isset($_POST['submit']) && isset($_POST['start-date']) && isset($_POST['end-date'])) {
					if (!empty($_POST['start-date']) && !empty($_POST['end-date'])) {
						$startDate = $_POST['start-date'];
						$endDate = $_POST['end-date'];
						if ($endDate <= $startDate) {
							echo '<div class="message">End date must be after start date.</div>';
						}
						$check = 0;
					} else if (!empty($_POST['start-date']) && empty($_POST['end-date'])) {
						$startDate = $_POST['start-date'];	
						$check = 1;
					} else if (empty($_POST['start-date']) && !empty($_POST['end-date'])) {
						echo '<div class="message">Start date is empty.</div>';
					} else {
						echo '<div class="message">You did not select any dates.</div>';
					}
				}
				while ($row1 = $result1 -> fetch_row()){
					echo '<tr>';
					echo '<td>' . $row1[0] . '</td>';
					echo '<td id="highlight_spot" onclick="showSpotChange()">' . $row1[1] . '</td>';
					echo '<td id="highlight_rate" onclick="showRateChange()">' . $row1[2] . '</td>';
					echo '<td>' . $row1[3] . '</td>';
					if ($check == 0) {
						$sql2 = "SELECT COUNT(*) FROM reservation WHERE Date_reserved >= '$startDate' AND Date_reserved <= '$endDate' AND Zone_num = '$row1[0]'";
						$result2 = $conn -> query($sql2);
						if ($result2 -> num_rows > 0) {
							$row2 = $result2 -> fetch_row();
							echo '<td>' . $row2[0] . '</td>';
						}
					} else if ($check == 1) {
						$sql3 = "SELECT COUNT(*) FROM reservation WHERE Date_reserved = '$startDate' AND Zone_num = '$row1[0]'";
						$result3 = $conn -> query($sql3);
						if ($result3 -> num_rows > 0) {
							$row3 = $result3 -> fetch_row();
							echo '<td>' . $row3[0] . '</td>';
						}
					}
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
						<input class="search_input" type="text" placeholder="Enter the name of the new zone you want to add" placeholder-style="color: #888888;"></input>
					</li>
					<li>Max Spots: 
						<input class="search_input" type="text" placeholder="Enter the number of designated spots" placeholder-style="color: #888888;"></input>
					</li>
					<li>Rate: 
						<input class="search_input" type="text" placeholder="Enter the normal rate" placeholder-style="color: #888888;"></input>
					</li>
				</ul>
			</div>
			<button class="search_button">Confirm</button>	
		</div>

		<div class="card">
			<h2>Other Actions</h2>
			<div id="spotChange" class="spotChange-block" style="display: none">
				Enter the following information to change the designated spots
			</div>
			<div id="rateChange" class="rateChange-block" style="display: none">
				Enter the following information to change the rate
			</div>
		</div>		
		
		<div class="card">	
			<button onclick="showDate()" style="font-size: 20px">Run Report</button>
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

		function showSpotChange() {
			var infoBlock = document.getElementById('spotChange');
			if (infoBlock.style.display === 'none') {
				infoBlock.style.display = 'block';
			} else {
				infoBlock.style.display = 'none';	
			}
		}

		function showRateChange() {
			var infoBlock = document.getElementById('rateChange');
			if (infoBlock.style.display == 'none') { 
				infoBlock.style.display = 'block';
			} else {
				infoBlock.style.display = 'none';
			}
		}

    	</script>
</body>

</html>
