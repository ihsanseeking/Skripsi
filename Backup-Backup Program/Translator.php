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
		<form action="Translator.php" method="post">
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
				//print_r($tables);
				
				//print_r($atributs);
			} else {
				$dbname = "";
			}
		?>
		<!--<p>
			Contoh : tampilkan <i>nama_atribut1</i> , ... , <i>nama_atributn</i> dari <i>nama_tabel</i> 
			<br>Contoh : tampilkan semua data <i>nama_tabel</i> 
			<br>Contoh : tampilkan <i>nama_atribut1</i> , ... , <i>nama_atributn</i> dari <i>nama_tabel</i> yang memiliki <i>nama_atribut</i> = <i>nilai</i> 
			<br>Contoh : tampilkan semua data <i>nama_tabel</i> yang memiliki <i>nama_atribut</i> = <i>nilai</i> dan <i>nama_atribut</i> = <i>nilai</i> 
			<br>Contoh : tampilkan semua data <i>nama_tabel</i> dan <i>nama_tabel2</i> 
			<br>Contoh : tampilkan <i>nama_atribut1</i> <i>nama_tabel1</i> , ... , <i>nama_atributn</i> <i>nama_tabeln</i> dari <i>nama_tabel1</i> dan <i>nama_tabeln</i> 
		</p>-->		
		<form action="Translator.php" method="post">
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
			   
				<p><b>Tahap 1. Preprocessing</b></p>
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
				<p><b>Tahap 2. Proses translator Kalimat</b></p>
				<p><u>Tahap 2.a. Identifikasi Perintah DML</u></p>
				<?php
					$count = count($tokens);
					$kata_perintah = "tidak teridentifikasi";
					$i = 0;
					$s_2a = 0;
					while ($i !== $count)
					{
						$i++;
						//echo "Token $i : $tokens[$i]<br>";
						if($tokens[$i] == "tampilkan") {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "tampilkanlah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "menampilkan")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "siapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "siapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "siapakah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "cari")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "carikan")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "berapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "berapakah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
						} else if ($tokens[$i] == "tambah")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
						} else if ($tokens[$i] == "tambahkan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
						} else if ($tokens[$i] == "masukan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
						} else if ($tokens[$i] == "isikan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
						} else if ($tokens[$i] == "ubah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
						} else if ($tokens[$i] == "ubahlah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
						} else if ($tokens[$i] == "mengubah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
						} else if ($tokens[$i] == "ganti")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
						} else if ($tokens[$i] == "gantilah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
						} else if ($tokens[$i] == "gantikan")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
						} else if ($tokens[$i] == "hapus")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
						} else if ($tokens[$i] == "hapuslah")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
						} else if ($tokens[$i] == "menghapus")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
						} else if ($tokens[$i] == "delete")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
						} else if ($tokens[$i] == "buang")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
						} else if ($tokens[$i] == "kurangi")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
						} 
						echo "DML : $tokens[$i] => $kata_perintah<br>";
					}
				?> 
				<p><u>Tahap 2.b. Identifikasi Nama Tabel</u></p>
				<p><i>Tahap 2.b.i Nama Tabel</i></p>
				<?php
					$nama_table = array();
					$i = 0; $nt = 0; $ct = 0;
					$nama_table[0] = "Tidak ada nama tabel";
					$c_table = count($tables);
					//Nyari Tabel dulu
					while ($i !== $count){
						$i++;
						//looping tabel
						for ($j = 0; $j < $c_table; $j++) {
							//nyari tabel yg sama dengan token
							if ($tokens[$i] == $tables[$j]) {
								//cek jumlah nama_tabel
								if ($nt > 0) {
									//looping nama_tabel
									for ($ct = 0; $ct <= $nt; $ct++) {
										//nyari token yang sama dengan nama_tabel
										if ($tokens[$i] == $nama_table[$ct]) {
											$nt--;
											break;
										} else {
											$nama_table[$nt] = $tables[$j];
											echo "nama tabel[$nt] : $nama_table[$nt]<br>";
										}
									}
									$nt++;								
								} else {
									$nama_table[$nt] = $tables[$j];
									echo "nama tabel[$nt] : $nama_table[$nt]<br>";
									$nt++;
								}
							}
						}
					}
					//echo "<p>jumlah $nt </p>";
				?>
				<p><i>Tahap 2.b.ii Nama Tabel</i></p>
				
				<p><u>Tahap 2.c. Identifikasi Syarat Kondisi</u></p>
				<p><u>Tahap 2.d. Identifikasi Nama atribut</u></p>
				<p><u>Tahap 2.e. Penyusunan Query</u></p>
				<?php
					if ($kata_perintah
					echo $kata_perintah;
					
				?>
				<p>Tahap 3. Pengujian Query</p>
				
				<?php
			}
		?>
	</body>
</html>