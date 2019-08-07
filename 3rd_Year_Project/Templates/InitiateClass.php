<?php
	session_start();
	require_once('../php/config.ini.php');

	$mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project");

	if($mysqli -> connect_error)
   	{
    die('Connect Error ('.$mysqli -> connect_errno.') '.
        $mysqli -> connect_error);
   	}//if

   	$query = "SELECT * FROM modules WHERE id = 1";
   	$result = $mysqli->query($query);

   	$row = $result->fetch_assoc();
   	$module_title = $row["module_name"];

   	$query_initiate_class = "UPDATE courses 
  					   		 SET is_running = 1
  					   		 WHERE id = 1;";
  	if($mysqli->query($query_initiate_class) != TRUE) {
  		echo "Error updateing record: ".$mysqli->error;
  	}


   $mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Begin Class</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript">
		function update(){
			var module_title = <?php echo json_encode($module_title) ?>;
			document.getElementById("header").innerHTML = module_title;
		}
	</script>
</head>
<body onload="update()">
	<div class="jumbotron text-center">
		<h1 id = "header" class="display-4">Module Title</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="NoOfStudents"> Number of Students:</label>
					<p id="no_of_students"> 5 </p>
				</div>
			</div>
			<div class="col-md-6">
				<ul class="list-group">
					<li class="list-group-item">STUDENT 1</li>
					<li class="list-group-item">STUDENT 2</li>
					<li class="list-group-item">STUDENT 3</li>
				</ul>
			</div>
		</div>
		<a class="btn btn-primary" href="ModuleVideo.php" role="button">Start Class</a>
	</div>
</body>
</html>