<?PHP
// php-Playfair cipher class
// by: John Pollard III 
// c. 2003
// updated for php8: 2024
// php7 compatibility fix: 2025


class playfair_cipher {

// =========  ENCODE ===============================================

	public function encode($data,$key, $displayKeytable = false) {
		

		$data = $this->parse($data, false);
		$key_table = $this->parse($key, true);
		$encoded = "";
		if ( $displayKeytable){
			echo "key_table: <table>";
			foreach ($key_table as $row)
			{
				echo "<tr>";
				echo "<td style='font-family: \"Noto Sans Mono\", monospace;'>" . $row . "</td>";
				echo "</tr>";
			}
			echo '</table>';
			echo "<br>";
		}	
		while ( $data ) {

			// shifts off the first element [0] so what was [1] is now [0] 
			$a = $data[0];
			$data = substr($data,1);
					
			// this takes care of any 'x' placements if we need them.
			// otherwise we shift b off too.
			if ($a == substr($data,0,1) || !substr($data,0,1) ) {
				$b = 'x';
			} else {
				$b = $data[0];
				$data = substr($data,1);
			}
			
			//now we locate the coordinates of a and b in our $key_table.
			for ($row = 0; $row <= 4; $row++) {
			
				// see if $a is in this row ; if so get its coordinates
				if ( !  (strpos($key_table[$row],$a) === false)   ) {
					$a_row = $row;
					$a_col = strpos($key_table[$row],$a);
				}
			
				// do the same for b
				if (  ! (strpos($key_table[$row],$b) === false) ) {
					$b_row = $row;
					$b_col = strpos($key_table[$row],$b);
				}
			}
			
			// now that we have the coordinates we encode the letters.	
			// same col shift right --> wrap	
			if ($a_col == $b_col) { 
				$a_col++;
				$b_col++;
				if ($a_col > 4) {
					$a_col = 0;
					$b_col = 0;
				}

				$a = substr($key_table[$a_row],$a_col,1);
				$b = substr($key_table[$b_row],$b_col,1);
			// same row shift down --> wrap
			} elseif ($a_row == $b_row){
				$a_row++;
				$b_row++;
				if ($a_row > 4) {
					$a_row = 0;
					$b_row = 0;
				}
				$a = substr($key_table[$a_row],$a_col,1);
				$b = substr($key_table[$b_row],$b_col,1);

			// otherwise swap corners of square in grid	
			} else {
				$a = substr($key_table[$a_row],$b_col,1);
				$b = substr($key_table[$b_row],$a_col,1);
			}
					
			//finally we append the encoded ones into $encoded string and repeat until we're done
			$encoded = "$encoded$a$b";
						
		}
				
		return $encoded;
	}


// =========  DECODE ===============================================


	public function decode($data,$key, $displayKeytable = false) {

			
		$key_table = $this->parse($key, true);
		$data = $this->parse($data, false);
		$decoded = "";
		if ( $displayKeytable){
			echo "key_table: <table>";
			foreach ($key_table as $row)
			{
				echo "<tr>";
				echo "<td style='font-family: \"Noto Sans Mono\", monospace;'>" . $row . "</td>";
				echo "</tr>";
			}
			echo '</table>';
			echo "<br>";
		}	
		
		while ( $data ) {
	//		echo "hi";

			//pops off the first element [0] so what was [1] is now [0] 
			$a = $data[0];
			$data = substr($data,1);
					
			// this takes care of any 'x' placements if we need them.
			// otherwise we shift b off too.
			if ($a == substr($data,0,1) || !substr($data,0,1) ) {
				$b = 'x';
			} else {
				$b = $data[0];
				$data = substr($data,1);
			}
			
			//now we locate the coordinates of a and b in our $key_table.
			for ($row = 0; $row <= 4; $row++) {
			
				// see if $a is in this row ; if so get its coordinates
				if ( !  (strpos($key_table[$row],$a) === false)   ) {
					$a_row = $row;
					$a_col = strpos($key_table[$row],$a);
				}
			
				// do the same for b
				if (  ! (strpos($key_table[$row],$b) === false) ) {
					$b_row = $row;
					$b_col = strpos($key_table[$row],$b);
				}
			}
			
			// now that we have the coordinates we decode the letters.		
			// same cols shift up --> wrap
			if ($a_col == $b_col) { 
				$a_col--;
				$b_col--;
				if ($a_col < 0) {
					$a_col = 4;
					$b_col = 4;
				}
				$a = substr($key_table[$a_row],$a_col,1);
				$b = substr($key_table[$b_row],$b_col,1);
			// same rows shift left --> wrap
			} elseif ($a_row == $b_row){
				$a_row--;
				$b_row--;
				if ($a_row < 0) {
					$a_row = 4;
					$b_row = 4;
				}
				$a = substr($key_table[$a_row],$a_col,1);
				$b = substr($key_table[$b_row],$b_col,1);

			// normal swap corners
			} else {
				$a = substr($key_table[$a_row],$b_col,1);
				$b = substr($key_table[$b_row],$a_col,1);
			}
					
			//finally we append the encrypted ones into $decoded string and repeat until we're done

			$decoded = "$decoded$a$b";		
			
		}
			
		return $decoded;
	}


	private function parse($data, $isKey = false){

	// ==== [ FORMAT DATA BEFORE ENCODING ] =====
		
		// Make it all lower case;
		$table_vals='';
		
		$data = strtolower($data);
		$data = preg_replace("/[^a-z]/", '', $data);
		$data = preg_replace('/j/','i',$data);
		
		if ($isKey){
			$len = strlen($data);
		
			// account for 0 starting instead of 1
			$len--;
			$alphabet = "abcdefghiklmnopqrstuvwxyz";  //minus q
		
			while (($data)) {
			
				if ( str_contains( $alphabet, $data[0] ) ){
					$table_vals = "$table_vals$data[0]";  //append
					$alphabet   = preg_replace("/$data[0]/",'',$alphabet);
				}
			
				$data = str_replace($data[0],'',$data);	
			}

			// this string should always be 25 char in length
			// A jumbled alphabet minus the letter Q (hince kw replacing QU)
			$table_vals = "$table_vals$alphabet";
			//echo "$table_vals<br>";
			// Now we place this into a 2 dimensional array (table)
			return str_split($table_vals,'5');
		
		} else {		
			return $data;
		}

	}

}



//php 7 compatibility since we're using php8's new str_contains() function.

if (!function_exists('str_contains')) {

    function str_contains($haystack, $needle)
    {
        return (strpos($haystack, $needle) !== false);
    }
}
?>
