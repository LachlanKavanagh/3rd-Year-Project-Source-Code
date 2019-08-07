<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Teacher Homepage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript">
		function personalise(){
			// change the header to show the user's name
			var username = getCookie("name");
			document.getElementById("header").innerHTML = "Welcome " + username;
			// update the module colouring to reflect their progress.
		}
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
	</script>
</head>
<body onload="personalise()">
	<div class="jumbotron text-center">
		<h1 id = "header" class="display-4"> Welcome TEACHER</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col">
				<h2> Students </h2>
				<div class="list-group">
					<a href="STUDENT 1" class="list-group-item list-group-item-action"> STUDENT 1 </a>
					<a href="STUDENT 2" class="list-group-item list-group-item-action"> STUDENT 2 </a>
					<a href="STUDENT 3" class="list-group-item list-group-item-action"> STUDENT 3 </a>
					<a href="STUDENT 4" class="list-group-item list-group-item-action"> STUDENT 4 </a>		
				</div>
			</div>
			<div class="col">
				<h2> Modules </h2>
				<div class="list-group">
					<a href="InitiateClass.php" class="list-group-item list-group-item-action"> MODULE 1 </a>
					<a href="MODULE 2" class="list-group-item list-group-item-action"> MODULE 2 </a>
					<a href="MODULE 3" class="list-group-item list-group-item-action"> MODULE 3 </a>
					<a href="MODULE 4" class="list-group-item list-group-item-action"> MODULE 4 </a>	
				</div>
				<a href="messagingTeachers.html" class="btn btn-primary"> Messaging </a>
			</div>
		</div>
		<div class = "row">
	   		<a href="LoginPage.html" onclick="deleteCookies()" class ="btn btn-danger">Logout</a>
	   	</div>
	</div>
</body>
</html>