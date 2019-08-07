<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Module Questions</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
		google.charts.load('current', {packages: ['corechart', 'bar']});
		google.charts.setOnLoadCallback(drawBasic);
		function drawBasic(){
			var data_scoreboard = new google.visualization.DataTable();
          data_scoreboard.addColumn('string', 'Students (first name)');
          data_scoreboard.addColumn('number', 'Current Score');

          data_scoreboard.addRows([
            ['Student A', 1],
            ['Student B', 2],
            ['Student C', 3],
            ['Student D', 4],
          ]);

          var options_scoreboard = {
            title: 'Students scores',
            hAxis: {
                title: 'Students (first names)',
                // format: 'h:mm a',
                viewWindow: {
                  min: [7, 30, 0],
                  max: [17, 30, 0]
                }
            },
            vAxis: {
                title: 'Scores'
            },
            height: 400,
        };

          var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

          chart.draw(data_scoreboard, options_scoreboard);
		} // func drawBasic
	</script>
</head>
<body>
	<div class="jumbotron text-center">
    	<h1 id="header" class="display-4">Final Leaderboard</h1>
    </div>
    <div class="container">
    	<div class="row">
    		<div id="chart_div">

    		</div>
    	</div>
    	<div class = "row">
    		<a href="TeacherHomepage.php" class="btn btn-primary">End Class</a>
    	</div> 
	</div>
</body>