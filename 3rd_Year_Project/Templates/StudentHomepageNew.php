<?php
	session_start();

	// open the mysql connection
	require_once('../php/config.ini.php');

	$mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project")
  	or die('Connect Error ('.$mysqli -> connect_errno.') '. $mysqli -> connect_error);

  	// fetch the data for the first box

  	$query_check_next_module = "SELECT * FROM completed_modules WHERE course_id = 1";
  	$query_no_of_modules = "SELECT * FROM courses WHERE id = 1";

  	$result_next_module = $mysqli->query($query_check_next_module);
  	$result_no_of_modules = $mysqli->query($query_no_of_modules);

  	$row = $result_next_module->fetch_assoc();
  	$next_module = $row["modules_completed"] + 1;
  	$row = $result_no_of_modules->fetch_assoc();
  	$no_of_modules = $row["no_of_modules"];

  	$course_completed = $next_module > $no_of_modules;

  	// if the course hasn't been completed, fetch the data on the next module
  	if(!$course_completed){
  		$next_module = intval($next_module);
  		$query_module = "SELECT * FROM modules WHERE module_number = $next_module AND course_id = 1";
  		$result_module = $mysqli->query($query_module);
  		$row = $result_module->fetch_assoc();
  		$next_module_name = $row["module_name"];
  		$next_module_description = $row["Description"];
  		# GET IMAGE

  		// fetch the previous mark, if one exists
  		$id = $mysqli->real_escape_string($_SESSION["id"] ?? 1);
  		$query_mark = "SELECT * FROM test_results WHERE student_id = $id AND module_id = $next_module";
  		$result_mark = $mysqli->query($query_mark);
  		if($row = $result_module->fetch_assoc()){
  			$previous_mark = $row["score"]; 
  		}
  		else{
  			$previous_mark = "undefined";
  		}
  	} // if course not completed

  	// fetch the data for the second box

  	$query_get_all_modules = "SELECT * FROM modules WHERE course_id = 1";
  	$result_get_all_modules = $mysqli->query($query_get_all_modules);

  	$module_numbers;
  	$module_names;
  	$i = 0;
  	if($result_get_all_modules->num_rows > 0){
	   	while($row = $result_get_all_modules->fetch_assoc()){
	   		$module_numbers[$i] = $row['module_number'];
	   		$module_names[$i] = $row['module_name'];
	   		$i++;
	   	}//while
   	}//if

   	// fetch data for the third box
   	$query_get_running_class = "SELECT * FROM courses WHERE id = 1";
   	$result_get_running_class = $mysqli->query($query_get_running_class);
   	$row = $result_get_running_class->fetch_assoc();
   	$is_running = $row["is_running"];

   	// TODO - fetch data for fourth box

   	$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Student Homepage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script href="../js/CookieHandler.js" type="text/javascript"></script>
	<script type="text/javascript">
		function update(){
			// jumbotron
			var student_name = getCookie("name");

			document.getElementById("header").innerHTML = "Welcome " + student_name;

			// first box
			var course_completed = <?php echo json_encode($course_completed)?>;
			var next_module_name = <?php echo json_encode($next_module_name)?>;
			var next_module_description = <?php echo json_encode($next_module_description)?>;
			var previous_mark = <?php echo json_encode($previous_mark)?>;

			if(!course_completed){
				document.getElementById("next_module_name").innerHTML = next_module_name;
				document.getElementById("next_module_description").innerHTML = next_module_description;
				document.getElementById("previous_mark").innerHTML = previous_mark;
			}
			else{

			}

			// second box
			var module_names = <?php echo json_encode($module_names)?>;
			var module_numbers = <?php echo json_encode($module_numbers)?>;
			var no_of_modules = <?php echo json_encode($no_of_modules)?>;

			var a;
			for(var i = 0; i < no_of_modules; i++){
				a = document.createElement("A");
				a.classList.add("list-group-item");
				a.classList.add("list-group-item-action");
				a.setAttribute("id", module_numbers[i]);
				a.setAttribute("href", "IndividualModulePage.php");
				a.innerHTML = module_names[i];
				a.addEventListener("click", setCookie("current_module_number", module_numbers[i], 1));
				(document.getElementById("module_list")).appendChild(a);
			} // for each module

			// third box
			var is_running = <?php echo json_encode($is_running)?>;
			if(is_running){
				// module
				// teacher
				// button to join class

				// var
			}
			else{
				var p_no_class = document.createElement("P");
				p_no_class.innerHTML = "There is no class running right now.";
			}
		} // func update
	</script>
	<script type="text/javascript">
		function getCookie(cname) {
			var i;
			var c;
			var name = cname + "=";
			var decodedCookie = decodeURIComponent(document.cookie);
			var ca = decodedCookie.split(";");
			for(i = 0; i < ca.length; i+=1){
				c = ca[i];
				while (c.charAt(0) == " ") {
						c = c.substring(1);
				}//while
				if(c.indexOf(name) == 0) {
						debug = c.substring(name.length, c.length);
						console.debug(debug)
						return c.substring(name.length, c.length);
				}//if
			}//for
		}// func getCookie#
		function deleteCookie(cname){
			document.cookie = cname + "=' expires = Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
		} // func deleteCookie
		function setCookie(cname, cvalue, exdays) {
			if(cvalue != ""){
				var d = new Date();
				d.setTime(d.getTime() + (exdays*24*60*60*1000));
				var expires = "expires" + d.toUTCString();
				document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
			} // if
			else{
				alert("Please Enter a name");
			}
		}// func setCookie
	</script>
</head>
<body onload="update()">
	<div class="jumbotron text-center">
    	<h1 class="display-4" id="header">Welcome STUDENT</h1>
    </div>
	<div class="container">
		<div class="row">
			<div id="next_module_col" class="col border border-primary">
				<h2 id = "next_module_name">NEXT_MODULE_NAME</h2>
				<img src="../images/default.jpg" class="img-thumbnail">
				<p id="next_module_description">NEXT_MODULE DESCRIPTION</p>
				<div class = "row">
					<div class = "col">
						<p id="previous_mark">PREVIOUS SCORE</p>
					</div>
					<div class = "col">
						<a href="IndividualModulePage.php" class = "btn btn-primary", onclick="setCookie('current_module',this.id,1)">Start Module</a>
					</div>
				</div>
			</div>
			<div class="col border border-primary">
				<h2> Module List </h2>
				<div id="module_list" class="list-group">
					<!--
	    			<a id="module1" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '1', 1)"> MODULE 1 </a>
	    			<a id="module2" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '2', 2)"> MODULE 2 </a>
	    			<a id="module3" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '3', 3)"> MODULE 3 </a>
	    			-->
	    		</div>
			</div>
		</div>
		<div class="row">
			<div id= "running_class" class="col">

			</div>
			<div id = "forum" class="col">

			</div>
		</div>
	</div>
</body>