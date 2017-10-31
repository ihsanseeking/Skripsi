<html>
	<head>
		<title>Translator SQL</title>
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
		<form action="program-5.php" method="post">
			<p>Pilih Database :
			<?php
				$sql = "show databases";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) {
					// output data of each row
					echo "<select name='database'>";
					echo "<option>= Pilih Database =</option>";
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
					echo"</select>";
				} else {
					echo "0 results";
				}
			?>
			<input name='kalimat' value="<?php echo $string; ?>" type='hidden'>
			<input value="kirim" type="submit"></p>
		</form>
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
				
				$sql = "show tables";
				$result = mysqli_query($connd, $sql);

				if (mysqli_num_rows($result) > 0) {
					// output data of each row
					echo "Database <b>$dbname</b><br>";
					while($row = mysqli_fetch_assoc($result)) {
						echo "Table [" . $row["Tables_in_$dbname"]. "] => ";
						$table = $row["Tables_in_$dbname"];
						$tables[$i] = $table;
						$i++;
						$sql2 = "desc $table";
						$result2 = mysqli_query($connd, $sql2);
						if (mysqli_num_rows($result2) > 0) {
							// output data of each row
							echo "Atribut : ";
							$j=0;
							while($row2 = mysqli_fetch_assoc($result2)) {
								echo " (".$row2["Field"]. ")";
								//$atribut = $row2["Field"];
								$atributs[$table][$j] = $row2["Field"];
								$j++;
							}
							echo "<br>";
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
			//mysqli_close($conn);
		?>
		<?php
			//load database
			if (!empty($_POST["database"])) {
				$dbname = $_POST["database"];
				print_r($tables);
				
				print_r($atributs);
			} else {
				$dbname = "";
			}
		?>
		<p>
			Contoh : tampilkan <i>nama_atribut1</i> , ... , <i>nama_atributn</i> dari <i>nama_tabel</i> 
			<br>Contoh : tampilkan semua data <i>nama_tabel</i> 
			<br>Contoh : tampilkan <i>nama_atribut1</i> , ... , <i>nama_atributn</i> dari <i>nama_tabel</i> yang memiliki <i>nama_atribut</i> = <i>nilai</i> 
			<br>Contoh : tampilkan semua data <i>nama_tabel</i> yang memiliki <i>nama_atribut</i> = <i>nilai</i> dan <i>nama_atribut</i> = <i>nilai</i> 
			<br>Contoh : tampilkan semua data <i>nama_tabel</i> dan <i>nama_tabel2</i> 
			<br>Contoh : tampilkan <i>nama_atribut1</i> <i>nama_tabel1</i> , ... , <i>nama_atributn</i> <i>nama_tabeln</i> dari <i>nama_tabel1</i> dan <i>nama_tabeln</i> 
		</p>   
		<form action="program-5.php" method="post">
			<p><textarea name="kalimat" rows="4" cols="50" placeholder="Kalimat Perintah"><?php echo $string; ?></textarea></p>
			<input name="database" value="<?php echo $dbname; ?>" type="hidden">
			<p><input value="kirim" type="submit"></p>
		</form>
		<?php
			if (!empty($_POST["kalimat"])) 
			{
				$string = $_POST["kalimat"]; 
				?>
				<p>Kalimat Perintah : </p>
				<p> <?php echo$string; ?></p>
			   
				<p>Tahap 1. Preprocessing</p>
				<?php
					// parameter = $string
					// Proses pemotongan String berdasarkan spasi
					$token = strtok($string, " ");
					echo "Token-Token : <br>";
					$i = 0;
					$tokens = array();
					while ($token !== false)
					{
						$i++;
						//echo "Token $i : $token<br>";
						// masukan token kedalam array
						$tokens[$i] = $token;
						$token = strtok(" ");
						echo "Token $i : $tokens[$i]<br>";
					} 
				?>
				<p>Tahap 2. identifikasi perintah dalam kalimat</p>
				<?php
					$count = count($tokens);
					$kata_perintah = "tidak teridentifikasi";
					$kata_sumber = "tidak teridentifikasi";
					$i = 0;
					while ($i !== $count)
					{
						$i++;
						//echo "Token $i : $tokens[$i]<br>";
						if($tokens[$i] == "tampilkan")
						{
							$kata_perintah = "SELECT";
							echo "Token Perintah : $tokens[$i] => $kata_perintah<br>";
							$j = 0;
							while ($j !== $count)
							{
								$j++;
								if($tokens[$j] == "dari")
								{
									$kata_sumber = "FROM";
									$posisi_sumber=$j;
									echo "Token Sumber : $tokens[$j] => $kata_sumber<br>";
								}
								else if($tokens[$j] == "data")
								{
									$kata_sumber = "FROM";
									$posisi_sumber=$j;
									echo "Token Sumber : $tokens[$j] => $kata_sumber<br>";
								}
								else if($tokens[$j] == "yang")
								{
									$kata_kondisi = "WHERE";
									$posisi_kondisi=$j;
									echo "Token Kondisi : $tokens[$j] => $kata_kondisi<br>";
								}
							}
						}
					}
				?> 
				<p>Tahap 3. identifikasi kata kunci</p>
				<?php
					$kunci_table = array();
					$kunci_atribut = array();
					$i = 0; $ku = 0; $kt = 0; $kk = 0;
					$count_table = count($tables);
					//Nyari Tabel dulu
					while ($i !== $count)
					{
						$i++;
						for ($j = 0; $j < $count_table; $j++)
						{
							//echo "$tokens[$i] == $table[$j] | $atribut[$j]<br>";
							//cari tabel
							if ($tokens[$i] == $tables[$j])
							{
								$kunci_table[$ku] = $tables[$j];
								echo "kata kunci tabel : $kunci_table[$ku]<br>";
								$ku++;
								
								//nyari atribut dari tabel
								$i2=0;
								$count_atribut = count($atributs[$tables[$j]]);
								while ($i2 !== $posisi_sumber)
								{
									$i2++;
									for ($j2 = 0; $j2 < $count_atribut; $j2++)
									{
										//echo "kata kunci atribut : $tokens[$i2]<br>";
										if ($tokens[$i2] == $atributs[$tables[$j]][$j2])
										{
											$kunci_atribut[$kt] = $atributs[$tables[$j]][$j2];
											echo "kata kunci atribut : $kunci_atribut[$kt]<br>";
											$kt++;
										}
									}
								}
								//Cari Atribut Kondisi
								$i2 = $posisi_kondisi;
								while ($i2 !== $count)
								{
									for ($j2 = 0; $j2 < $count_atribut; $j2++)
									{
										//echo "kata kunci atribut : $tokens[$i2]<br>";
										if ($tokens[$i2] == $atributs[$tables[$j]][$j2])
										{
											$kunci_kondisi[$kk] = $atributs[$tables[$j]][$j2];
											$kunci_kondisi[$kk+1] = $tokens[$i2+1];
											$kunci_kondisi[$kk+2] = $tokens[$i2+2];
											//$k=$i+1;
											//if ($tokens[$k] == "="){
											//	$kunci_kondisi[$kk] = $atribut[$j] + " = " + $tokens[$k+1];
											//}
											echo "kata kunci kondisi : $kunci_kondisi[0] $kunci_kondisi[1] '$kunci_kondisi[2]' <br>";
											//$kk++;
										}
									}
									$i2++;
								}
							}
						}
					}
				?>
				<p>Hasil Translator</p>
				<?php
					$count_kunci_atribut = count($kunci_atribut);
					//echo "$kata_perintah ";
					$s=0;
					$partsql[$s] = "$kata_perintah ";
					$s++;
					for ($i = 0;$i < $count_kunci_atribut; $i++)
					{
						if ($kunci_atribut[$i] == "semua")
						{
							//echo "* ";
							$partsql[$s] = "* ";
							$s++;
						} else {
							//echo "$kunci_atribut[$i] ";
							$partsql[$s] = "$kunci_atribut[$i] ";
							$s++;
							if ($i < ($count_kunci_atribut-1))
							{
								//echo ", ";
								$partsql[$s] = ", ";
								$s++;
							}
						}
					}
					//echo "$kata_sumber ";
					$partsql[$s] = "$kata_sumber ";
					$s++;
					//echo " $kunci_table[0] ";
					$partsql[$s] = " $kunci_table[0] ";
					$s++;
					//echo " $kata_kondisi $kunci_kondisi[0] $kunci_kondisi[1] '$kunci_kondisi[2]'";
					$partsql[$s] = " $kata_kondisi $kunci_kondisi[0] $kunci_kondisi[1] '$kunci_kondisi[2]'";
					$s++;
				?> 
				<?php
					//Eksekusi
					$codesql = join("",$partsql);
					$result = mysqli_query($connd, $codesql);
					
					if (mysqli_num_rows($result) > 0) {
						// output data of each row
						//echo "Database <b>$dbname</b><br>";
						$count_attrb=count($kunci_atribut);
						for ($kt=0;$kt<$count_attrb;$kt++)
						{
							echo"| $kunci_atribut[$kt] |";
						}
						while($row = mysqli_fetch_assoc($result)) {
							echo "<br>";
							for ($kt=0;$kt<$count_attrb;$kt++)
							{
								echo"| ".$row[$kunci_atribut[$kt]]." |";
							}
						}
					} else {
						echo "0 results";
					}
					
				?>

				<?php
			}
		?>
	</body>
</html>