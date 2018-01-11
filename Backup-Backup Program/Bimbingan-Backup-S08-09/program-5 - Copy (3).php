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
				//print_r($tables);
				
				//print_r($atributs);
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
			if (!empty($_POST["kalimat"])) {
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
						else if($tokens[$j] == "memiliki")
						{
							$kata_kondisi = "WHERE";
							$posisi_kondisi=$j;
							echo "Token Kondisi : $tokens[$j] => $kata_kondisi<br>";
						}
						else if($tokens[$j] == "dimana")
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
			$count_atribut = count($atributs);
			//nyari atribut
			while ($i !== $posisi_sumber)
			{
				$i++;
				for ($j = 0; $j < $count_atribut; $j++)
				{
					if ($tokens[$i] == $atribut[$j])
					{
						$kunci_atribut[$kt] = $atribut[$j];
						echo "kata kunci atribut : $kunci_atribut[$kt]<br>";
						$kt++;
					}
				}
			}
			$i = $posisi_sumber;
			while ($i !== $count)
			{
				$i++;
				for ($j = 0; $j < $count_table; $j++)
				{
					//echo "$tokens[$i] == $table[$j] | $atribut[$j]<br>";
					//cari tabel
					if ($tokens[$i] == $table[$j])
					{
						$kunci_table[$ku] = $table[$j];
						echo "kata kunci tabel : $kunci_table[$ku]<br>";
						$ku++;
					}
				}
				//cari atribut kondisi
				for ($j = 0; $j < $count_atribut; $j++)
				{
					if ($tokens[$i] == $atribut[$j])
					{
						$kunci_kondisi[$kk] = $atribut[$j];
						$kunci_kondisi[$kk+1] = $tokens[$i+1];
						$kunci_kondisi[$kk+2] = $tokens[$i+2];
						//$k=$i+1;
						//if ($tokens[$k] == "="){
						//	$kunci_kondisi[$kk] = $atribut[$j] + " = " + $tokens[$k+1];
						//}
						echo "kata kunci kondisi : $kunci_kondisi[0] $kunci_kondisi[1] $kunci_kondisi[2] <br>";
						//$kk++;
					}
				}
			}
		?>
		kata kunci tabel : mahasiswa
		<br>kata kunci atribut : *
		<br>kata kunci atribut : *
		<br>kata kunci atribut : *
		<br>kata kunci atribut : *
		<br>kata kunci atribut : *
		<br>kata kunci atribut : *
		
		<p>Hasil Translator</p>
		<?php
			$count_kunci_atribut = count($kunci_atribut);

			echo "$kata_perintah ";
			for ($i = 0;$i < $count_kunci_atribut; $i++)
			{
				if ($kunci_atribut[$i] == "semua")
					echo "* ";
				else
					echo "$kunci_atribut[$i] ";
					if ($i < ($count_kunci_atribut-1))
					{
						echo ", ";
					}
			}
			echo "$kata_sumber ";
			echo " $kunci_table[0] ";
			echo " $kata_kondisi $kunci_kondisi[0] $kunci_kondisi[1] $kunci_kondisi[2]";
			
		?>
		SELECT * , * , * , * , * , * FROM  mahasiswa  
		<?php
			}
		?>
	</body>
</html>