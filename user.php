<?php
	class User
	{
		public $id;
		public $name;
		public $password;

		public $borrowed_movies = array();
		public $number_movies;

		public $fines;

		public function __construct($id, $name, $password){
			$this->id = $id;
			$this->name = $name;
			$this->password = $password;

			// Finding the movies which the user already has borrowed
			if ($storage = fopen('data/loan.csv', 'r')) {
    			while ($data = fgetcsv($storage, 1000, ";")) {
	    			if($data[0] == $id){
	    				$this->borrowed_movies[] = $data[1];
	    			}
        		}
    		}

    		fclose($storage);

    		$this->number_movies = count($this->borrowed_movies);
		}

		public function loans($borrowed_movies){
			foreach($borrowed_movies as $movie){
				echo '<fieldset class="movie">
				<p class="movie_title">' . $movie->title . '(<i>' . $movie->year . '</i>)</p>

				<div class="description">
					<p class="title">Description</p>
					<p class="info">' . $movie->description . '</p>
				</div>

				<div class="director">
					<p class="title">Director</p>
					<p class="info">' . $movie->director . '</p>
				</div>

				<div class="borrow_movie">';

				// Finding when movie has been taken
				if(($storage = fopen('data/loan.csv', 'r')) !== FALSE) {
	    			while(($data = fgetcsv($storage, 1000, ";")) !== FALSE) {
		    			if($data[1] == $movie->id){
		    				$date_taken = $data[2];
		    			}
	        		}
	    		}

	    		// Checking if any fines are in order
				$time_difference = time() - $date_taken;
				$day_difference = floor($time_difference/(60*60*24));
				
				$day = 0;
				while($day <= $day_difference) {
					if ($day > 7 && $day <= 14){
						$this->fines = $this->fines + 0.25;
					} else if($day > 14) {
						$this->fines = $this->fines + 0.50;
					}

					$day++;
				}

				$overdue = $day_difference - 7;

	    		echo "<p class='borrowed_date'>Borrowed <br />
					<i>(". date('j F Y', $date_taken) .")</i></p>
					<input type='checkbox' name='return_id[]' value='" . $movie->id . "'>Return Movie</div>";

				if($overdue > 0){
					echo "<p class='overdue'>Overdue (" . $overdue . " Days)</p>";
				}

				echo "</fieldset>";
			}
		}
	}
?>