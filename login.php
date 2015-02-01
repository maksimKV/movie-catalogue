<?php
session_start();

	if (isset($_SESSION['user_id'])){
		header("Location: index.php");
	}

	// Saving the data from the CSV file into a nested multidimentional array
	$users = array();
	$key = 0;
	if ($storage = fopen('data/account.csv', 'r')) {
    while ($data = fgetcsv($storage, 1000, ";")) {
        $num = count($data);
        $key++;

        for ($i=0; $i < $num; $i++) {
        	$users[$key][] = $data[$i];
        }
    }

    fclose($storage);
    }

    if (!empty($_POST)){
		if(!empty($_POST['name']) && !empty($_POST['password'])){

			foreach($users as $user){
				if($user[1] == $_POST['name'] && $user[2] == $_POST['password']){

					$_SESSION['user_name'] = $user[1];
					$_SESSION['user_id'] = $user[0];
					$_SESSION['user_password'] = $user[2];
					$_SESSION['page'] = 1;

					header("Location: index.php");
				}
			}
		
		} else if(!empty($_POST['name'])) {
			$error = "There was an error. Please check that you have entered your password";
		} else if(!empty($_POST['password'])){
			$error = "There was an error. Please check that you have entered your name";
		} else {
			$error = "Please make sure that you have inserted the right name and password";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/style.css" media="screen" rel="stylesheet">
	<title>Movie Store | Login Page</title>
</head>

<body>
	<div class="main">
		<form method="post" action="login.php">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" >

			<label for="password">Password</label>
			<input type="password" name="password" id="password" >

			<input type="submit" value="Login" >
		</form>

		<p class="register">If you do not have an account please register <a href="register.php">here</a>.</p>
		<p class="error">
			<?php if(isset($error)){ 
				echo $error; 
			} ?>
		</p>
	</div>
</body>
</html>