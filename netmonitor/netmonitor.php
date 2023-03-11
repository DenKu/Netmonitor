<?php
	$path = "script/results/";
	$dir = new DirectoryIterator($path);
	$files = array();
	foreach ($dir as $fileinfo) {
		$localfilename = $fileinfo->getFilename();
		//Skip . and .. paths
		if($localfilename != "" and $localfilename != "." and $localfilename != ".." ){
        		array_push($files, $path . $fileinfo->getFilename());
		}
	}
	sort($files);
	foreach ($files as $fileinfo) {
		// Open the CSV file
		$filename = $fileinfo;
		$file = fopen($filename, 'r');

		// Initialize arrays to hold the data
		$timestamps = array();
		$ping_times = array();
		$globhostname = "";
		$globhostip = "";
		$globindicator = false;
		$globcolor = "rgb(200,0,0)";

		// Read the remaining lines and extract the data
		while (($line = fgetcsv($file,null,';')) !== false) {
			$timestamp = strtotime($line[0]);
			$globhostip = $line[1];
			$globhostname = $line[2];

			//Ping time might be 'down'
			$ping_time = $line[3];
			if ($ping_time == "down"){
				$ping_time = "-0.1";
				$globindicator = false;
				$globcolor = "rgb(200,0,0)";
			} else {
				$globindicator = true;
				$globcolor = "rgb(50,170,40)";
			}
			$ping_time = floatval($ping_time);

			array_push($timestamps, $timestamp);
			array_push($ping_times, $ping_time);
		}
		$jstimestamps = json_encode($timestamps);
		$jspingtimes =  json_encode($ping_times);

		// Close the file
		fclose($file);

		//Create the chart
		if ($globindicator) {
			echo "<div class='hostsummary'><div class='hostname'>$globhostname ($globhostip)</div><div class='indicator' style='background-color: #32AA28'></div></div>";
		} else {
			echo "<div class='hostsummary'><div class='hostname'>$globhostname ($globhostip)</div><div class='indicator' style='background-color: #C80000'></div></div>";
		}
		echo "<div style='height: 300px; width: 100%'>";
		echo "<canvas id='$globhostname'></canvas>";
		echo "</div>";
		echo "<script>";
			echo "var timestamps = $jstimestamps;";
			echo "var ping_times = $jspingtimes;";

			// Create the chart data object
			echo "var chartData = {labels: timestamps.map(timestamp => new Date(timestamp * 1000).toLocaleString()),datasets: [{label: '$globhostname',data: ping_times,fill: false, borderColor: '$globcolor', lineTension: 0.1}]};";

			// Create the chart options object

			echo "var chartOptions = {plugins: {legend: {display: false}}, maintainAspectRatio: false,scales: {x: {scaleLabel: {display: true,text: 'Time'}},y: {scaleLabel: {display: true,text: 'Test'},ticks: {beginAtZero: true}}}};";

			// Create the chart
			echo "var ctx = document.getElementById('$globhostname').getContext('2d');";
			echo "var chart = new Chart(ctx, {type: 'line',data: chartData,options: chartOptions});";
		echo "</script>";
	}
?>
