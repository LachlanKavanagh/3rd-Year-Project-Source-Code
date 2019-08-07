<?php
	session_start();

	require_once('../php/config.ini.php');

	$mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project")
  or die('Connect Error ('.$mysqli -> connect_errno.') '. $mysqli -> connect_error);

  	//retrieve all courses the student is enrolled on, check if any of them are currently running 

  	$query_check_running_classes = "SELECT * FROM courses";
  	$query_result = $mysqli->query($query_check_running_classes);

  	$no_of_courses = $query_result->num_rows;
  	$course_running;
  	$course_title;  	
  	$index = 0;

  	if($query_result->num_rows > 0){
   		while($row = $query_result->fetch_assoc()){
   			$course_title[$index] = $row["course_title"];
   			$course_running[$index] = $row["is_running"];
   			$index++;
   		} // while
   	} // if
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Student Homepage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript">
		function update(){
			var course_titles = <?php echo json_encode($course_title) ?>;
			var courses_running = <?php echo json_encode($course_running) ?>;
			var no_of_courses = <?php echo json_encode($no_of_courses) ?>;

			var course_button;
			var button_text;
			var list;
			// create the buttons to take the student to the different courses
			// disable them if they are not currently running
			list = document.getElementById("list");
			for(var i = 0; i < no_of_courses; i++){
				//create the button
				course_button = document.createElement("A");
				course_button.classList.add("list-group-item");
				// course_button.classList.add("list-group-item-action");
				course_button.setAttribute("id", "course" + (i+1));
				// course_button.setAttribute("href", "ClassStudentView.php");

				//add the course title to the button as text

				button_text = document.createTextNode(course_titles[i]);
				course_button.appendChild(button_text);

				//disable the button if the course is not currently running
				if(courses_running[i] == 0){
					course_button.classList.add("disabled");
					console.log(1);
				}
				else{
					console.log(2);
					course_button.classList.add("list-group-item-action");
					course_button.setAttribute("href", "ClassStudentView.php");
				}

				//append the button to the list element
				list.appendChild(course_button);
			} // for each course

		} // func update
	</script>
</head>
<body onload="update()">
	<div class="jumbotron text-center">
		<h1 id = "header" class="display-4"> Class list</h1>
		<p class="lead"> All classes you are enrolled in will appear here. A button being disabled means that class is not currently being run </p>
	</div>
	<div class="container">
		<div class="col">
			<h2> Classes </h2>
			<div id = "list" class = "list-group">

			</div>
			<!--
			<div class="list-group">
	    			<a id="module1" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '1', 1)"> MODULE 1 </a>
	    			<a id="module2" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '2', 2)"> MODULE 2 </a>
	    			<a id="module3" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '3', 3)"> MODULE 3 </a>
	    		</div>
	    	-->
		</div>
	</div>
</body>