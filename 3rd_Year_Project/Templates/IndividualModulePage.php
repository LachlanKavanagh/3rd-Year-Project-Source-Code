<?php
  require_once('../php/config.ini.php');

  session_start();

  $mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project")
  or die('Connect Error ('.$mysqli -> connect_errno.') '. $mysqli -> connect_error);

  $query = "SELECT * FROM modules WHERE id = 1";
  $result = $mysqli->query($query);

  // echo $_COOKIE["module"];

  $module_info = $result->fetch_assoc();
  $module_title = $module_info["module_name"];
  $module_video_link = $module_info["video"]; 
  $module_description = $module_info["Description"];

  $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Module Video</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/IndividualModulePageStyle.css">
	<script type="text/javascript">
		function update(){
			var module_title = <?php echo json_encode($module_title)?>;
			var module_video_link = <?php echo json_encode($module_video_link)?>;
			var module_description = <?php echo json_encode($module_description)?>;
			document.getElementById("heading").innerHTML = module_title;
			var vid = document.getElementById("video");
			vid.src = module_video_link;
			document.getElementById("description").innerHTML = module_description;
		}
	</script>
</head>
<body onload="update();">
	<div class="jumbotron text-center">
    <h1 id="heading" class="display-4">Module Title</h1>
  </div>
  <div class="container">
   	<div id="first-row" class="row">
   		<div class="col-9">
   			<div align="center" class="embed-responsive embed-responsive-16by9">
   				<video autoplay class="embed-responsive-item">
   					<source id="video" src="../Videos/placeholder.mp4" type="video/mp4">
   				</video>
   			</div>
   		</div>
   		<div class="col-3">
         <p id="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec imperdiet dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In facilisis dolor eget mollis vehicula. Maecenas tempor pretium arcu non varius. Duis lacus leo, commodo in tortor non, imperdiet consectetur nisl. Proin fermentum orci quis diam blandit blandit. In dapibus nisi ut diam venenatis, non lacinia ante fermentum. Suspendisse vel molestie nisl. Sed dapibus quis orci eget eleifend. Curabitur magna sem, mollis nec convallis eget, commodo at metus.</p>
	    	</div>
	    </div>
      <div class= "row justify-content-center">
        <a class="btn btn-danger" href="StudentHomepage.php" role="button">Back</a>
        <a class="btn btn-primary" href="IndividualTestPage - Copy.php" role="button">Test</a>
      </div>
	  </div>
</body>
</html>