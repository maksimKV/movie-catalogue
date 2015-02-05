<?php
session_start();

	if (!isset($_SESSION['user_id'])){
		header("Location: login.php");
	}

	//session_destroy();

	include 'user.php';
	include 'movies.php';

	$current_user = new User($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_password']);

	include 'functionality.php';

    // $catalogue is an array with all the movie objects

    // Don't forget to update the previous code to register a user
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/style.css" media="screen" rel="stylesheet">
	<title>Movie Store</title>
</head>

<body>
	<div class="wrapper">
		<ul class="main-nav">
			<li><a class="nav-movies" href="#">Movies</a></li>
			<li><a class="nav-loans" href="#">Loans</a></li>
			<li><a class="nav-account" href="#">Account</a></li>
		</ul>

		<div class="content">
			<div id="movies" class="hidden">
				<h2> Movie Store </h2>
				<?php if(isset($error_movies)){ echo "<p class='error'>" . $error_movies . "</p>";} ?>

				<form name="catalogue_form" action="" method="post">
				<?php foreach($catalogue as $movie){
					$movie->getMovie();
				}?>

				<div class="rent">
					<input type="submit" value="Loan" />
				</div>

				</form>
			</div>

			<div id="loans" class="hidden">
				<h2>Your Loans</h2>

				<?php if($current_user->number_movies > 0){
					echo '<form name="loan_form" action="" method="post">';
						
					$current_user->loans($borrowed_movies);
					
					echo '<div class="rent">
							<input type="submit" value="Return" />
						  </div></form>';
				}  else {
					echo '<p class="loans_info">You have not borrowed any movies</p>';
				} ?>
			</div>

			<div id="account" class="hidden">
				<h2>Account</h2>
				<form name="logout_form" action="" method="post">
					<fieldset>
						<div class="description">
							<p>Welcome to your account section.
								<?php
									if(!empty($current_user->fines)){
										echo "You currently have £" . $current_user->fines . " in fines!";
									} else {
										echo "You currently do not have a single fine. Well done!";
									}
								?>
							</p>
						</div>

						<input type='hidden' name='sign_out' value='sign_out' />
					</fieldset>

					<div class="rent">
						<input type="submit" value="Sign Out" />
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".nav-movies").click(function() {
				$("#loans").addClass('hidden');
				$("#movies").removeClass('hidden');
				$('#account').addClass('hidden');
			});

			$(".nav-loans").click(function() {
				$("#loans").removeClass('hidden');
				$("#movies").addClass('hidden');
				$('#account').addClass('hidden');
			});

			$(".nav-account").click(function() {
				$("#loans").addClass('hidden');
				$("#movies").addClass('hidden');
				$('#account').removeClass('hidden');
			});

			if(<?php echo $_SESSION['page']; ?> == 1){
				$("#loans").addClass('hidden');
				$("#movies").removeClass('hidden');
				$('#account').addClass('hidden');
			} else if (<?php echo $_SESSION['page']; ?> == 2){
				$("#loans").removeClass('hidden');
				$("#movies").addClass('hidden');
				$('#account').addClass('hidden');
			}
		});
	</script>
</body>
</html>