<?php
	session_start();

	require_once('../php/config.ini.php');

	$mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project")
  or die('Connect Error ('.$mysqli -> connect_errno.') '. $mysqli -> connect_error);

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
<!--
	This page will have 3 "stages"
	The first will present the question to the student, along with the available answers, and the ability to submit them
	The second will be a confirmation of the submitted answer, asking the student to wait until the teacher has revealed the answer
	The third will Tell the student if they where right or wrong, with a small explination of the correct answer
	This cycle will repeat untill the final question, where a summary screen will then be shown
-->
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Student Homepage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript">
		var questions_text = <?php echo json_encode($questions_text) ?>;
		var correct_answers = <?php echo json_encode($correct_answers) ?>;
		var correct_answers_description = <?php echo json_encode($questions_description) ?>;
		var no_of_questions = <?php echo json_encode($no_of_questions) ?>;
		var answers_text = <?php echo json_encode($answers_text) ?>;
		var no_of_answers = <?php echo json_encode($no_of_answers) ?>;

		var current_question_index = 0;
		var answer;

		function updateQuestions(){
			var body;
			var container;
			var checkbox;
			var label;
			var answer_text;
			var form_group_wrapper;
			var form_check_wrapper;
			var container;
			var form;
			var submit_button;
			var submit_button_text;
			var hidden;

			// first, wipe the old html past the jumbotron
			document.getElementById("body").removeChild(document.getElementById("container"));
			// then create the new container to hold the below HTML
			container = document.createElement("DIV");
			container.classList.add("container");
			container.setAttribute("id", "container");

			// Fetch the question text, display it in the jumbotron
			document.getElementById("header").innerHTML = "Question " + (current_question_index + 1);
			document.getElementById("jumbo_p").innerHTML = questions_text[current_question_index];

			form_group_wrapper = document.createElement("DIV");
			form_group_wrapper.classList.add("form-group");
			form_group_wrapper.setAttribute("id", "form_group_wrapper");

			//next, create the checkboxes for the answers
			for(var i = 1; i <= no_of_answers[current_question_index]; i++){
				form_check_wrapper = document.createElement("DIV");
				form_check_wrapper.classList.add("form-check");
				form_check_wrapper.setAttribute("id", "form_check_wrapper" + i);

				label = document.createElement("LABEL");
				label.classList.add("form-check-label");
				label.setAttribute("for", "Q" + (current_question_index + 1) + "A" + i);
				answer_text = document.createTextNode(answers_text[current_question_index][i-1]);
				label.appendChild(answer_text);

				checkbox = document.createElement("INPUT");
				checkbox.classList.add("form-check-input");
				checkbox.setAttribute("type", "radio");
				checkbox.setAttribute("name", "Q" + (current_question_index + 1));
				checkbox.setAttribute("id", "Q" + (current_question_index + 1) + "A" + i);
				checkbox.setAttribute("value", i);

				form_check_wrapper.appendChild(checkbox);
				form_check_wrapper.appendChild(label);

				form_group_wrapper.appendChild(form_check_wrapper);
			} // for each answer

			// add a hidden field, used to tell AddSessionVariable which question to store the answer for
			hidden = document.createElement("INPUT");
			hidden.setAttribute("type", "hidden");
			hidden.setAttribute("id", "qnumber");
			hidden.setAttribute("name", "qnumber");
			hidden.setAttribute("value", "Q" + (current_question_index + 1));

			//create the form element and append the checkboxes
			form = document.createElement("FORM");
			form.setAttribute("id", "form");
			//form.setAttribute("method", "POST");
			//form.setAttribute("action", "AddSessionVariable.php");

			form.appendChild(form_group_wrapper);
			form.appendChild(hidden);

			//create and add the submit button
			submit_button = document.createElement("BUTTON");
			submit_button.classList.add("btn");
			submit_button.classList.add("btn-primary");
			submit_button.setAttribute("type", "submit");
			submit_button.setAttribute("id", "submit_button")

			submit_button_text = document.createTextNode("Submit Answer");
			submit_button.appendChild(submit_button_text);
			form.appendChild(submit_button);

			container.appendChild(form);

			body = document.getElementById("body");
			body.appendChild(container);

		} // func initial update
		function updateConfirmation(){
			var container;
			var col;
			var col2;
			var col_p;
			var button_proceed;
			var button_proceed_text;
			var session_var_key;
			//update the jumbotron to reflect the new state
			document.getElementById("header").innerHTML = "Answer Submitted";
			// session_var_key = "Q" + (current_question_index + 1) + " Answer";
			// var answer = "C";
			document.getElementById("jumbo_p").innerHTML = "Your answer was: " + answer;

			// first, wipe the old html past the jumbotron
			document.getElementById("body").removeChild(document.getElementById("container"));
			// then create the new container to hold the below HTML
			container = document.createElement("DIV");
			container.classList.add("container");
			container.setAttribute("id", "container");

			// just display a message asking the user to wait until the class is moved on
			col_p = document.createElement("P");
			col_p.innerHTML = "Your answer has been submitted. Please wait until the class moves on";

			col = document.createElement("DIV");
			col.classList.add("col");

			col.appendChild(col_p);
			container.appendChild(col);

			// second col to hold the proceed button
			col2 = document.createElement("DIV");
			col2.classList.add("col");

			// proceed button
			button_proceed = document.createElement("BUTTON");
			button_proceed.classList.add("btn");
			button_proceed.classList.add("btn-primary");
			button_proceed.setAttribute("onclick", "updateResult()");

			button_proceed_text = document.createTextNode("Proceed");
			button_proceed.appendChild(button_proceed_text);

			container.appendChild(button_proceed);

			document.getElementById("body").appendChild(container);

		} // func updateConfirmation
		function updateResult(){
			var container;
			var row1;
			var row2;
			var p_your_answer;
			var p_correct_answer;
			var p_description;
			var button_proceed;
			var button_proceed_text;
			// update the jumbotron to reflect the new state
			document.getElementById("jumbo_p").innerHTML="";
			if(answer == correct_answers[current_question_index]){
				document.getElementById("header").innerHTML = "You were Correct!"
			}
			else{
				document.getElementById("header").innerHTML = "You were Wrong!"
			}

			// first, wipe the old html past the jumbotron
			document.getElementById("body").removeChild(document.getElementById("container"));
			// then create the new container to hold the below HTML
			container = document.createElement("DIV");
			container.classList.add("container");
			container.setAttribute("id", "container");

			// create the first row to show the user their answer and the correct one
			row1 = document.createElement("DIV");
			row1.classList.add("row");
			row1.classList.add("text-center");
			row1.setAttribute("id", "row1");

			p_your_answer = document.createElement("P");
			p_your_answer.innerHTML = "Your Answer: " + answer;

			p_correct_answer = document.createElement("P");
			p_correct_answer.innerHTML = "Correct Answer: " + correct_answers[current_question_index];

			row1.appendChild(p_your_answer);
			row1.appendChild(p_correct_answer);

			// create the row to hold a description of the correct answer
			row2 = document.createElement("DIV");
			row2.classList.add("row");
			row2.classList.add("text-center");
			row2.setAttribute("id", "row2");

			p_description = document.createElement("P");
			p_description.innerHTML = correct_answers_description[current_question_index];

			row2.appendChild(p_description);

			button_proceed = document.createElement("BUTTON");
			button_proceed.classList.add("btn");
			button_proceed.classList.add("btn-primary");
			if(current_question_index != (no_of_questions + 1)){
				button_proceed.setAttribute("onclick", "updateQuestions(); return false;");
			}
			else{
				button_proceed.setAttribute("action", "StudentClassSummary.php");
			}

			button_proceed_text = document.createTextNode("Proceed");
			button_proceed.appendChild(button_proceed_text);

			container.appendChild(row1);
			container.appendChild(row2);
			container.appendChild(button_proceed);

			document.getElementById("body").appendChild(container);

			current_question_index++;
		} // func updateResult
		
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#form").submit(function(event){			
				event.preventDefault();
				var form = $(this);
				var url = "../php/AddSessionVariable.php"
				
				$.ajax({
					type: "POST",
					url: url,
					data: $(this).serialize(),
					success: function(data){
						answer = data;
						updateConfirmation();
					},
					error: function(data){
						console.log("Your score could not be submitted");
						console.log(data);
					},
				});	// ajax request		
			}); // on submit
		}); // document.ready
	</script>
</head>
<body id="body" onload="updateQuestions()">
	<div class="jumbotron text-center">
		<h1 id = "header" class="display-4"> JUMBOTRON NUMBER</h1>
		<p id="jumbo_p" class = "lead">JUMBOTRON TEXT</p>
	</div>
	<div class="container" id="container">

	</div>
</body>