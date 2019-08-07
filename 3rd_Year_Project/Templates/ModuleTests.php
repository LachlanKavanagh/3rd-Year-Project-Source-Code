<?php
	session_start();

	require_once('../php/config.ini.php');

	$mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project");

	$query_questions = "SELECT * FROM questions WHERE module_id=1";
  	$results_questions = $mysqli->query($query_questions);

  	$questions_text;
  	$correct_answers;
  	$questions_description;
  	$index = 0;

  	if($results_questions->num_rows > 0){
  		while($next_question = $results_questions->fetch_assoc()){
  			$questions_text[$index] = $next_question["question_text"];
  			$correct_answers[$index] = $next_question["correct_answer"];
  			$questions_description[$index] = $next_question["correct_answer_description"];
  			$index++;
  		} // while
  	} // if
  	$no_of_questions = count($questions_text);

  	// fetch the data for the answers

  	$answers_text;
  	$query_answers;
  	$i;
  	$j = 0;
  	for($i = 0; $i < $no_of_questions; $i++){
  		$query_answers = "SELECT * FROM answers WHERE question_id =".($i+1);
  		$results_answers = $mysqli->query($query_answers);
  		$j = 0;
  		if($results_answers->num_rows > 0){
  			while($next_answer = $results_answers->fetch_assoc()){
  				$answers_text[$i][$j] = $next_answer["answer_text"] ?? '';
  				$j++;
  			} // while
  		$no_of_answers[$i] = $j;
  		} // if
  	} // for each question

  	$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Module Questions</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript">
		google.charts.load('current', {packages: ['corechart', 'bar']});
		// google.charts.setOnLoadCallback(drawBasic);

		var questions_text = <?php echo json_encode($questions_text) ?>;
		var correct_answers = <?php echo json_encode($correct_answers) ?>;
		var correct_answers_description = <?php echo json_encode($questions_description) ?>;
		var no_of_questions = <?php echo json_encode($no_of_questions) ?>;
		var answers_text = <?php echo json_encode($answers_text) ?>;
		var no_of_answers = <?php echo json_encode($no_of_answers) ?>;

		var current_question_index = 0;
		var container;
		function updateQuestion(){
			var answer_text;
			var answer_para;
			var row;
			var button_show_answer;
			var button_text;
			container = document.getElementById("container");
			// clear out the current contents of the container
			while (container.hasChildNodes()) {
    			container.removeChild(container.lastChild);
			} // remove all childen
			// put the Question number and its text in the jumbotron
			document.getElementById("header").innerHTML = "Question " + (current_question_index + 1);
			document.getElementById("jumbop").innerHTML = questions_text[current_question_index];

			// display the answers to the question
			for(var i = 0; i < no_of_answers[current_question_index]; i++){
				// create the row for this answer
				row = document.createElement("DIV");
				row.classList.add("row");
				row.classList.add("text-center");
				row.setAttribute("id", "row");

				// create the p element
				answer_para = document.createElement("P");
				answer_para.classList.add("text-center");
				answer_para.setAttribute("id", "answer_text_" + (current_question_index + 1));
				answer_para.innerHTML = answers_text[current_question_index][i];

				// wrap the p element in the row, then append the row element to the container
				row.appendChild(answer_para);
				container.appendChild(row);
			} // for each answer

			// finally, add the button that will show the answer to the question
			button_show_answer = document.createElement("BUTTON");
			button_show_answer.classList.add("btn");
			button_show_answer.classList.add("btn-primary");
			button_show_answer.setAttribute("id", "btn_show_answer");
			button_show_answer.setAttribute("onclick", "updateAnswer()");

			button_text = document.createTextNode("Show Answer");
			button_show_answer.appendChild(button_text);

			container.appendChild(button_show_answer);

		} // func update
		function updateAnswer(){
			var chart_div_1;
			var chart_div_2;
			var button_proceed;
			var button_proceed_text;
			var row_charts;
			var row_button;
			// display the correct answer in the jumbotron
			document.getElementById("header").innerHTML = "Correct Answer: " + correct_answers[current_question_index];
			document.getElementById("jumbop").innerHTML = correct_answers_description[current_question_index];
			current_question_index++;
			container = document.getElementById("container");
			// clear out the current contents of the container
			while (container.hasChildNodes()) {
    			container.removeChild(container.lastChild);
			} // remove all childen

			// create the elements to display the two charts
			chart_div_1 = document.createElement("DIV");
			chart_div_1.classList.add("col-md-6");
			chart_div_1.setAttribute("id", "chart_div_1");
			chart_div_2 = document.createElement("DIV");
			chart_div_2.classList.add("col-md-6");
			chart_div_2.setAttribute("id", "chart_div_2");

			// create the row that the charts will be placed in, append it to the container
			row_charts = document.createElement("DIV");
			row_charts.classList.add("row");
			row_charts.setAttribute("id", "row_charts");
			row_charts.appendChild(chart_div_1);
			row_charts.appendChild(chart_div_2);
			container.appendChild(row_charts);

			// call drawBasic to draw the two graphs
			drawAnswerDistribution();
			drawScoreBoard();

			// create the button to load the next question
			button_proceed = document.createElement("A");
			button_proceed.classList.add("btn");
			button_proceed.classList.add("btn-primary");
			button_proceed.setAttribute("id", "button_proceed");
			if(current_question_index >= no_of_questions){
				button_proceed.setAttribute("href", "TeacherClassSummary.php");
			}
			else{
				button_proceed.setAttribute("onclick", "updateQuestion()");
			}
			button_proceed_text = document.createTextNode("Proceed");
			button_proceed.appendChild(button_proceed_text);
			container.appendChild(button_proceed);


		} // func updateAnswer
		function drawAnswerDistribution() {
			// create two tables, one for the leadboard, and one for the answers to the current question
			// first the leaderboard 
      		// then the Scores for the current question
      		var data_question = new google.visualization.DataTable();
      		data_question.addColumn('string', 'Answer');
      		data_question.addColumn('number','Score');

      		data_question.addRows([
      			["Answer 1", 0],
      			["Answer 2", 3],
      			["Answer 3", 1],
      			["Answer 4", 10],
      		]);

      		var options_question = {
        		title: 'Answer Distribution',
        		hAxis: {
          			title: 'Answers',
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

    		var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_2'));
    		chart.draw(data_question, options_question);
    	} // func drawAnswerDistribution
    	function drawScoreBoard() {
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

      		var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_1'));

      		chart.draw(data_scoreboard, options_scoreboard);
    	} // func drawScoreBoard
	</script>
</head>
<body onload="updateQuestion()">
	<div class="jumbotron text-center">
    	<h1 id="header" class="display-4">QUESTION NUMBER</h1>
    	<p id="jumbop" class="lead"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras malesuada vulputate arcu sed suscipit. Pellentesque.</p>
    </div>
	<div id="container" class="container">

	</div>
</body>
</html>