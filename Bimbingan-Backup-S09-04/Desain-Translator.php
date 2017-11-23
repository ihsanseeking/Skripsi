 <!DOCTYPE html>
<html lang="en">
<head>
	<title>Translator SQL</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap.min.css">
	<script src="../jquery.min.js"></script>
	<script src="../bootstrap.min.js"></script>
</head>
<body>
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "";

		// Create connection
		$conn = mysqli_connect($servername, $username, $password);

		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		echo "<p>Connected to server localhost successfully </p>";
	?>
	<?php
		if (!empty($_POST["kalimat"])) {
			$string = $_POST["kalimat"]; 
		} else {
			$string = "";
		}
	?>
<div class="container">
  <h2>Translator</h2>
  <p>Perancangan Translator Bahasa Alami ke dalam format SQL yang berfokus pada DML :</p>                  
  <ul class="breadcrumb">
    <li><a href="?page=database">Info Database</a></li>
    <li><a href="?page=translator">Translator</a></li>
    <li><a href="?page=penelusuran">Penelusuran</a></li>    
  </ul>
	
	<?php 
	if ($_GET["page"]=="database") {
	?>
	<form action="Translator.php" method="post">
		<div class="form-group">
			<label for="sel1">Pilih Database :</label>
			<?php
				$sql = "SHOW DATABASES";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) {
					// output data of each row
					?>
					
					<select class="form-control" name="database" id="database">
					<option>= Pilih Database =</option>
					<?php
					while($row = mysqli_fetch_assoc($result)) {
						if (!empty($_POST["database"])) {
							if ($_POST["database"] == $row["Database"]){
								echo "<option value=". $row["Database"]." selected=selected>". $row["Database"]."</option>";
							} else {
								echo "<option value=". $row["Database"].">". $row["Database"]."</option>";
							}
						} else {
							echo "<option value=". $row["Database"].">". $row["Database"]."</option>";
						}
					}
					?>
					</select></div>
					<?php
				} else {
					echo "0 results";
				}
			?>
		<input name='kalimat' value="<?php echo $string; ?>" type='hidden'>
		<div class="form-group">
			<input value="kirim" type="submit" class="btn btn-primary btn-md">
		</div>
	</form>
	
  <div class="row">
    <div class="col-sm-4">
      <h3>Column 1</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
    <div class="col-sm-4">
      <h3>Column 2</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
    <div class="col-sm-4">
      <h3>Column 3</h3>        
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
  </div>
  
  		<?php
			$i=0; $j=0;
			if (!empty($_POST["database"])) {
				$dbname = $_POST["database"]; 
				// Create connection to database
				$connd = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
				if (!$connd) {
					die("Connection failed: " . mysqli_connect_error());
				}
				echo "<p>Connected to database <b>$dbname</b> successfully </p>";	
				
				$sql = "SHOW FULL TABLES";
				$result = mysqli_query($connd, $sql);

				if (mysqli_num_rows($result) > 0) {
					// output data of each row
					echo "Database <b>$dbname</b><br>";
					$i1=0;$i2=0;
					while($row = mysqli_fetch_assoc($result)) {
						if ($row["Table_type"]=="VIEW"){
							//Echo "View ";
							$vtable = $row["Tables_in_$dbname"];
							$vtables[$i1] = $vtable;
							$i1++;
						} else {
							//Echo "Base ";
							$btable = $row["Tables_in_$dbname"];
							$btables[$i2] = $btable;
							$i2++;
						}
						//echo "Table [". $row["Tables_in_$dbname"]. "] => ";
						$table = $row["Tables_in_$dbname"];
						$tables[$i] = $table;
						$i++;
						$sql2 = "DESC $table";
						$result2 = mysqli_query($connd, $sql2);
						if (mysqli_num_rows($result2) > 0) {
							// output data of each row
							//echo "Atribut : ";
							$j=0;
							while($row2 = mysqli_fetch_assoc($result2)) {
								//echo " (".$row2["Field"]. ")";
								//$atribut = $row2["Field"];
								$atributs[$table]['field'][$j] = $row2["Field"];
								$atributs[$table]['type'][$j] = $row2["Type"];
								$atributs[$table]['key'][$j] = $row2["Key"];
								$j++;
							}
							//echo "<br>";
						} else {
							echo "0 results";
						}
					}
				} else {
					echo "0 results";
				}
			} else {
				echo "Database Belum dipilih";
			}
			//*
			//mysqli_close($conn);
			echo "<br><b>Tahap 0. Analisis Database</b><br>";
			echo "<br><u>Tahap 0.1.  Analisis Tabel dan Atribut</u><br>";
			echo "<br>";
			for($i=0; $i<$i2; $i++){
				echo "Tabel Base [".$btables[$i]."] => Atribut : <br>";
				$c_attr = count($atributs[$btables[$i]]['field']);
				for($j=0; $j<$c_attr; $j++){
					echo 
						" ( ".
						$atributs[$btables[$i]]['key'][$j].
						" [".
						$atributs[$btables[$i]]['field'][$j].
						"] = ".
						$atributs[$btables[$i]]['type'][$j].
						")"
					;
					echo "<br>";
				}
				echo "<br>";
			}
			echo "<br>";
			for($i=0; $i<$i1; $i++){
				echo "Tabel View [".$vtables[$i]."] => Atribut : <br>";
				$c_attr = count($atributs[$vtables[$i]]['field']);
				for($j=0; $j<$c_attr; $j++){
					echo 
						" ( ".
						$atributs[$vtables[$i]]['key'][$j].
						" [".
						$atributs[$vtables[$i]]['field'][$j].
						"] = ".
						$atributs[$vtables[$i]]['type'][$j].
						")"
					;
					echo "<br>";
				}
				echo "<br>";
			}//*/
			
			//Cari Relasi tabel
			
			for($i=0; $i<$i2; $i++){
				$c_attr = count($atributs[$btables[$i]]['field']);
				for($j=0; $j<$c_attr; $j++){
					if ($atributs[$btables[$i]]['key'][$j] == "PRI") {
						//echo $btables[$i].".".$atributs[$btables[$i]]['field'][$j].":<br> ";
						$r=0;
						for($ii=0; $ii<$i2; $ii++){
							if ($btables[$i] != $btables[$ii]){
								$c_attr2 = count($atributs[$btables[$ii]]['field']);
								for($jj=0; $jj<$c_attr2; $jj++){
									
									if ($atributs[$btables[$i]]['field'][$j] == $atributs[$btables[$ii]]['field'][$jj]) {
										//echo "[".$btables[$i]."] :";
										//echo " [".$btables[$ii]."] => ";
										//echo "(".$btables[$i].".".$atributs[$btables[$i]]['field'][$j];
										//echo " : ";
										//echo $btables[$ii].".".$atributs[$btables[$ii]]['field'][$jj]. ")";
										//echo " Relasi = [".$atributs[$btables[$i]]['key'][$j]." : ".$atributs[$btables[$ii]]['key'][$jj]."] <br>";
										$relasi[$btables[$i]]['tab'][$r] = $btables[$ii];
										$relasi[$btables[$i]]['attr'][$r] = $atributs[$btables[$ii]]['field'][$jj];
										$relasi[$btables[$i]]['car'][$r] = $atributs[$btables[$i]]['key'][$j]."-".$atributs[$btables[$ii]]['key'][$jj];
										//$c_relasi = count($relasi[$btables[$i]]['tab']);
										//echo"---- $c_relasi ------";
										$r++;
									}
									
								}
								
							}
							
						}
						//echo "<br>";
					}
				}
			}
			
			echo "<u>Tahap 0.2.  Analisis Tabel dan Atribut</u><br><br>";
			for($i=0; $i<$i2; $i++){
				echo "[".$btables[$i]."] => Relasi : <br>";
				
				$c_relasi = count($relasi[$btables[$i]]['tab']);

				for($j=0; $j<$c_relasi; $j++){
					echo 
						" [ ".
						$relasi[$btables[$i]]['tab'][$j].
						".".
						$relasi[$btables[$i]]['attr'][$j].
						"] = ".
						$relasi[$btables[$i]]['car'][$j]
					;
					echo "<br>";
				}
				
				echo "<br>";
			}
			
		?>
  
  
  
  
  
  
  
  
  
  
  
	<?php	
	} elseif ($_GET["page"]=="translator") {
		echo " translator";
	?>
	
	<?php
		
	} elseif ($_GET["page"]=="penelusuran") {
		echo " penelusuran";
	?>
	
	<?php
	}
	?>
	
</div>

</body>
</html> 