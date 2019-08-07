<?php
	require_once('../php/config.ini.php');

	session_start();

	$mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project")
  or die('Connect Error ('.$mysqli -> connect_errno.') '. $mysqli -> connect_error);

  	// fetch data for the questions

  	$query_questions = "SELECT * FROM questions WHERE module_id=1";
  	$results_questions = $mysqli->query($query_questions);

  	$questions_text;
  	$index = 0;

  	if($results_questions->num_rows > 0){
  		while($next_question = $results_questions->fetch_assoc()){
  			$questions_text[$index] = $next_question["question_text"];
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
	<title>Module Test</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript">
		function update(){
			var i;
			var answer_div;
			var answer_label;
			var answer_input;
			var answer_text;
			var question_element;
			var question_text;
			var wrapper;
			var submit_button;
			var t;

			var questions = <?php echo json_encode($questions_text) ?>;
			var no_of_questions = <?php echo json_encode($no_of_questions) ?>;
			var answers = <?php echo json_encode($answers_text) ?>;
			var no_of_answers = <?php echo json_encode($no_of_answers) ?>;
			// create the div that will wrap the question & answers
			for(j = 1; j <= no_of_questions; j++){
				wrapper = document.createElement("DIV");
				wrapper.classList.add("form-group");
				wrapper.setAttribute("id", "wrapper" + j);

				// create the question
				question_element = document.createElement("DIV");
				question_text = document.createTextNode(questions[j-1]);
				question_element.appendChild(question_text);
				question_element.classList.add("form-control-plaintext");
				question_element.classList.add("text-center");
				question_element.setAttribute("type", "text");
				question_element.readonly = true;
				question_element.setAttribute("id", "Q" + j);
				wrapper.appendChild(question_element);

				//create its answers
			
				for(i = 1; i <= no_of_answers[j-1]; i++){
					answer_div = document.createElement("DIV");
					answer_div.classList.add("form-check");
					answer_div.setAttribute("id", "answer_div" + i);

					answer_label = document.createElement("LABEL");
					answer_label.classList.add("form-check-label");
					answer_label.setAttribute("for", "Q" + j + "A" + i);
					answer_text = document.createTextNode(answers[j-1][i-1]);
					answer_label.appendChild(answer_text);

					answer_input = document.createElement("INPUT");
					answer_input.classList.add("form-check-input");
					answer_input.setAttribute("type", "radio");
					answer_input.setAttribute("name", "Q" + j);
					answer_input.setAttribute("id", "Q" + j + "A" + i);
					answer_input.setAttribute("value", i);

					answer_div.appendChild(answer_input);
					answer_div.appendChild(answer_label);

					wrapper.appendChild(answer_div);
				} // for

				// add the wrapped q&a's to the form
				document.getElementById("form").appendChild(wrapper);
			} // for

			// finally, add the button to submit the test
			var submit_button = document.createElement("BUTTON");
			submit_button.classList.add("btn");
			submit_button.classList.add("btn-primary");
			submit_button.setAttribute("type", "submit");

			var t = document.createTextNode("Submit Test");
			submit_button.appendChild(t);
			document.getElementById("form").appendChild(submit_button);		
		} // func update
	</script>
</head>
<body onload="update();">
	<div class="jumbotron text-center">
    	<h1 id="heading" class="display-4">Module Test</h1>
    </div>
    <div class="container">
    	<form method="post" action="TestResults.php" id="form">

    	</form>
    </div>
</body>
</html>