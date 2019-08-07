<?php
	class LoginValidation{
		function checkvarchar($varchar){
			if(!preg_match("/^[a-zA-Z ]*$/",$varchar) || $varchar=="")
				$err = -1;
			else
				$err = 1;
			return $err;
		} // checkname
	}
