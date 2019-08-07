<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Student Homepage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css">
	<script href="../js/CookieHandler.js" type="text/javascript"></script>
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
<body onload="personalise();">
    <div class="jumbotron text-center">
    	<h1 class="display-4" id="header">Welcome STUDENT</h1>
    	<p class="lead"> View your modules on the left hand side of the screen. Your messages can be accessed on the right</p>
    </div>
    <div class="container">
    	<div class="row">
	    	<div class="col">
	    		<h2> Modules </h2>
	    		<div class="list-group">
	    			<a id="module1" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '1', 1)"> MODULE 1 </a>
	    			<a id="module2" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '2', 2)"> MODULE 2 </a>
	    			<a id="module3" href="IndividualModulePage.php" class="list-group-item list-group-item-action list-group-item-danger" onclick="setCookie('module' '3', 3)"> MODULE 3 </a>
	    		</div>
	    	</div>
	    	<div class="col">
	    		<h2> Messaging </h2>
	    		<div class="list-group">
	    			<li class="list-group-item">MESSAGE 1</li>
	    			<li class="list-group-item">MESSAGE 2</li>
	    		</div>
	    		<button type="button" type="btn btn-primary">Messages</button>
	    	</div>
	    </div>
	    <div class = "row">
	    	<a href="LoginPage.html" onclick="deleteCookies()" class ="btn btn-danger">Logout</a>
	    	<a href="RunningClasses.php" class="btn btn-primary">Running Classes</a>
	    </div>
    </div>
</body>
</html>