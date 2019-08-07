<?php
   session_start();
	require_once('../php/config.ini.php');
	$isStudent = 0;
	$isTeacher = 0;
	$nameNotSanitized = $_POST["Username"] ?? '';
	$passwordNotSanitized = $_POST["Password"] ?? '';

	$name = filter_var($nameNotSanitized, FILTER_SANITIZE_STRING);
	$password = filter_var($passwordNotSanitized, FILTER_SANITIZE_STRING);

	$mysqli = new mysqli('127.0.0.1', 'root','', "3rd year Project");

	if($mysqli -> connect_error)
   {
    die('Connect Error ('.$mysqli -> connect_errno.') '.
        $mysqli -> connect_error);
   }//if

   $queryS="SELECT * FROM student_details";
   $queryT="SELECT * FROM teachers";
   $resultS=$mysqli->query($queryS);
   $resultT=$mysqli->query($queryT);

   if($resultS->num_rows > 0){
   	while($rowS = $resultS->fetch_assoc()){
   		if($name==$rowS["name"] && $password==$rowS["password"]){
   			$isStudent=1;
            $_SESSION["id"] = $rowS["id"];
            $_SESSION["username"] = $name;
   		}//if
   	}//while
   }//if

   if($resultT->num_rows > 0){
      while($rowT = $resultT->fetch_assoc()){
         if($name==$rowT["name"] && $password==$rowT["password"]){
            $isTeacher=1;
            $_SESSION["id"] = $rowT["id"];
            $_SESSION["username"] = $name;
         }//if
      }//while
   }//if

   $mysqli ->close();

   if($isStudent == 1){
      header('Location: StudentHomepage.php');	
   }
   elseif($isTeacher == 1){
      header('Location: TeacherHomepage.php');
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <title>Login page</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/bootstrap.css">
   <script type="text/javascript" src="../js/CookieHandler.js"></script>
</head>
<body>
   <div class="jumbotron text-center">
      <h1 class="display-4 ">Welcome to PROJECT TITLE</h1>
      <div class="alert alert-danger" role="alert">
            This login is not recognised
      </div>
    </div>
    <div class = "container">
      <form method="post" action="LoginLogic.php">
         <div class ="form-group">
            <label for="Username">Username</label>
            <input type="text" class="form-control" id="Username" name="Username" placeholder="Username">
         </div>
         <div class ="form-group">
            <label for="Password">Password</label>
            <input type="password" class="form-control" id="Password" name="Password" placeholder="Password">
         </div>
         <button type="submit" class="btn btn-primary" onClick="setCookie('name', document.getElementById('Username').value ,1);"">Log In</button>
      </form>
   </div>
</body>
</html>