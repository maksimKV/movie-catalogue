<?php
/* Movie Catalogue Page */
	$movies = array();
	$key = 0;
	if ($storage = fopen('data/movies.csv', 'r')) {
    while ($data = fgetcsv($storage, 1000, ";")){
        $num = count($data);
        $key++;

        for ($i=0; $i < $num; $i++) {
        		$movies[$key][] = $data[$i];
        	}
        }
    }

    fclose($storage);

    $catalogue = array();
    foreach($movies as $movie){
    	$catalogue[] = new Movie($movie[0], $movie[1], $movie[2], $movie[3], $movie[4]);
    }

    $allowed = 3 - $current_user->number_movies;

    if(!empty($_POST['movie_id'])){
    	if( count($_POST['movie_id']) > $allowed){
    		$error_movies = "You cannot rent more than 3 movies at a time. You currently have " . $current_user->number_movies . " taken";
    	} else {
    		$date_borrowed = time();

    		foreach($_POST['movie_id'] as $movie_id){
				$storage = fopen('data/loan.csv', 'a');
				fputcsv($storage, array($current_user->id, $movie_id, $date_borrowed), ';');
				fclose($storage);
    		}

    		$_SESSION['page'] = 1;
    		header('Location: index.php');
    	}
    }

/* End of Movie Catalogue Page */

/* Functionality for the Loan Page */
	$borrowed_movies = array();

	foreach($catalogue as $movie){
		if(in_array($movie->id, $current_user->borrowed_movies)){
			$borrowed_movies[] = $movie;
		}
	}

	// Removing the returned movie from the loan file
	if(!empty($_POST['return_id'])){
		$new_data = array();
		$key = 0;
		if ($storage = fopen('data/loan.csv', 'c+')) {
			while ($data = fgetcsv($storage, 1000, ";")){
				$key++;
				if(!in_array($data[1], $_POST['return_id']) ){
					$new_data[$key][0] = $data[0];
					$new_data[$key][1] = $data[1];
					$new_data[$key][2] = $data[2];
				}
			}

			ftruncate($storage, 0);
			fclose($storage);

			$storage = fopen('data/loan.csv', 'a');
			foreach ($new_data as $data){
				fputcsv($storage, $data, ';');
			}
			fclose($storage);

			$_SESSION['page'] = 2;
			header('Location: index.php');
		}
	}
/* End of the Loan Page */

/* Functionality for the Account Page */
	if(!empty($_POST['sign_out'])){
		session_destroy();
		header('Location: login.php');
	}
/* End of the Account Page */
?>