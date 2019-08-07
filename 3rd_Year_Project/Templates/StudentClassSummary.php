<?php
	session_start();

	// fetch the session variables for the test results, tally up the scores
	// retrieve the number of questions
	require_once('../php/config.ini.php');

	$mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project")
    or die('Connect Error ('.$mysqli -> connect_errno.') '. $mysqli -> connect_error);

    $query_correct = "SELECT * FROM questions WHERE module_id=1";
  	$result_correct = $mysqli->query($query_correct);

  	$i = 1;
    $no_of_questions = 0;
  	if($result_correct->num_rows > 0){
  		while($current_row = $result_correct->fetch_assoc()){
  			$no_of_questions++;
  			$index = "Q".$i;
  			if ($current_row["correct_answer"] == $_SESSION[$index]) {
  				$no_of_correct_answers++;
  				$i++;
  			} // if 
  		} // while
  	} // if

  	// store the result in the student's marks

  	$no_of_correct_answers = intval($no_of_correct_answers);
  	$id = intval($_SESSION["id"]);
  	$query_save_test = "UPDATE test_results
  						SET score = '$no_of_correct_answers'
  						WHERE student_id='$id' AND module_id = 1";

  	if($mysqli->query($query_save_test) != TRUE) {
  		echo "Error updateing record: ".$mysqli->error;
  	}

  	// fetch the pass mark for the given module
  	$query_pass = "SELECT pass_mark FROM modules WHERE id=1;"
  	$result_pass = $mysqli->query($query_pass);
  	$result_pass = $result_pass->fetch_assoc();
  	$pass_mark = $result_pass["pass_mark"];

  	$mysqli->close();
?>
<!DOCTYPE html>
<html lang="eng">
<head>
	<title>Test Results</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript">
		function update(){
			var score = <?php echo json_encode($no_of_correct_answers) ?>;
			var max_score = <?php echo json_encode($no_of_questions) ?>;
			var pass_mark = <?php echo json_encode($pass_mark) ?>;
			var pass_fail = document.getElementById("pass/fail")

			if(score >= pass_mark){
				pass_fail.innerHTML = "Pass";
			}
			else{
				pass_fail.innerHTML = "fail";
			}

			document.getElementById("display_score").innerHTML = score + "/" + max_score;


		} // func update
	</script>
</head>
<body onload="update()">
	<div class="jumbotron text-center">
		<h1 class="display-4"> Test Results </h1>
	</div>
	<div class="container text-center">
		<div class="row text-center">
			<h2> Your Score:</h2>
		</div>
		<div class="row text-center">
			<p id="display_score"> SCORE / MAX </p>
			<p id="pass/fail">PASS/FAIL</p>
		</div>
		<div class="row text-center">
			<a href="IndividualModulePage.php" class="btn btn-danger">Restart Module</a>
			<a href="StudentHomepage.php" class="btn btn-primary">Back to Homepage</a>
		</div>
	</div>
</body>
</html>