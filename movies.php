<?php
	class Movie {
		public $id;
		public $title;
		public $year;
		public $description;
		public $director;

		public function __construct($id, $title, $year, $description, $director){
			$this->id = $id;
			$this->title = $title;
			$this->year = $year;
			$this->description = $description;
			$this->director = $director;
		}

		public function getMovie(){
			echo '<fieldset class="movie">
				<p class="movie_title">' . $this->title . '(<i>' . $this->year . '</i>)</p>

				<div class="description">
					<p class="title">Description</p>
					<p class="info">' . $this->description . '</p>
				</div>

				<div class="director">
					<p class="title">Director</p>
					<p class="info">' . $this->director . '</p>
				</div>

				<div class="borrow_movie">';

					// Finding if the movie has been taken and when
					$taken = "";
					if($storage = fopen('data/loan.csv', 'r')) {
		    			while($data = fgetcsv($storage, 1000, ";")) {
			    			if($data[1] == $this->id){
			    				$taken = $data[1];
			    				$date_taken = $data[2];
			    			}
		        		}
		    		}
		    		fclose($storage);

		    		if($taken == $this->id){
		    			echo "<p class='borrowed_date'>Borrowed <br />
		    				<i>(". date('j F Y', $date_taken) .")</i>
		    			</p>";

		    			echo "<p class='due'";
		    				if ($date_taken - time() < 0){ 
		    					echo "style='color:#8C1717;'"; 
		    				}
		    			echo ">Due <i>(" . date('j F Y', strtotime('+7 day', $date_taken) ) . ")</i></p>";
		    		} else {
		    			echo '<input type="checkbox" name="movie_id[]" value="' . $this->id . '">Borrow Movie';
		    		}

				
			echo '</div>
			</fieldset>';
		}
	}
?>