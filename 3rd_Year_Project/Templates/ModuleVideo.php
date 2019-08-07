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
   $video = $row["video"];
   $module_title = $row["module_name"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Classroom Video</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript">
		function update(){
			var video = <?php echo json_encode($video) ?>;
			var module_title = <?php echo json_encode($module_title) ?>;
			document.getElementById("video").setAttribute("src", video);
			document.getElementById("header").innerHTML = module_title;
		}
	</script>
</head>
<body onload="update()">
	<div class="jumbotron text-center">
		<h1 id = "header" class="display-4"> MODULE TITLE</h1>
	</div>
	<div class="container">
		<div class = "row justify-content-center" style = "margin-bottom: 15px">
    		<video class="embed-responsive-item">
   				<source id="video" src="../Videos/placeholder.mp4" type="video/mp4">
   			</video>
    	</div>
    	<div class = "row justify-content-center">
    		<a href = "ModuleTests.php" class="btn btn-primary"> Start Test </a>
    	</div>
    </div>
</body>
</html>