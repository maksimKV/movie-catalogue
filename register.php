<?php
session_start();

	if (isset($_SESSION['user_id'])){
		header("Location: index.php");
	}

	if (!empty($_POST)){
		if(!empty($_POST['name']) && !empty($_POST['password'])){

			// Storage data
			$user_id = uniqid();
			$user_name = $_POST['name'];
			$user_password = $_POST['password'];

			// Storing the new user in a CSV file
			$storage = fopen('data/account.csv', 'a');
			fputcsv($storage, array($user_id, $user_name, $user_password), ';');
			fclose($storage);

			/* 
			 * Note: Saves the data in the following format 
			 * id,name,password 
			 */

			$_SESSION['user_name'] = $user_name;
			$_SESSION['user_id'] = $user_id;
			$_SESSION['user_password'] = $user_password;
			$_SESSION['page'] = 1;

			header("Location: index.php");
		
		} else if(!empty($_POST['name'])) {
			$error = "There was an error. Please check that you have entered your password";
		} else if(!empty($_POST['password'])){
			$error = "There was an error. Please check that you have entered your name";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/style.css" media="screen" rel="stylesheet">
	<title>Movie Store | Registration Page</title>
</head>

<body>
	<div class="main">
		<form method="post" action="register.php">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" >

			<label for="password">Password</label>
			<input type="password" name="password" id="password" >
			
			<input type="submit" value="Register" >
		</form>

		<p class="register">If you already have an account please log in <a href="login.php">here</a>.</p>
		<p class="error">
			<?php if(isset($error)){ 
				echo $error; 
			} ?>
		</p>
	</div>
</body>
</html>