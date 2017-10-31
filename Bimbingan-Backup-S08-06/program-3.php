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
	<form action="program-3.php" method="post">
		<p>Pilih Database :
<?php
	$sql = "show databases";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		// output data of each row
		echo "<select name='database'>";
		while($row = mysqli_fetch_assoc($result)) {
			echo "<option value=". $row["Database"].">". $row["Database"]."</option>";
		}
		echo"</select>";
	} else {
		echo "0 results";
	}
?>
		<input type="submit" value="kirim"></p>
	</form>
	
	<?php
    if (!empty($_POST["database"])) {
		$dbname = $_POST["database"]; 
	} else {
		$dbname = "dunia";
	}
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
		while($row = mysqli_fetch_assoc($result)) {
			echo "Table: " . $row["Tables_in_$dbname"]. "<br>";
			$table = $row["Tables_in_$dbname"];
			$sql2 = "desc $table";
			$result2 = mysqli_query($connd, $sql2);
			if (mysqli_num_rows($result2) > 0) {
				// output data of each row
				echo "atribut: ";
				while($row2 = mysqli_fetch_assoc($result2)) {
					echo $row2["Field"]. " | ";
				}
				echo "<br>";
			} else {
				echo "0 results";
			}
		}
	} else {
		echo "0 results";
	}

	//mysqli_close($conn);
 ?> 

   <?php
    $contoh[0] = "tampilkan nim dan nama dari mahasiswa";
	$contoh[1] = "tampilkan semua data mahasiswa";
	$contoh[2] = "tampilkan nim , nama dan alamat dari mahasiswa";
	$contoh[3] = "tampilkan nim , nama dan alamat dari mahasiswa yang memiliki tinggi = 170 cm";
	$contoh[3] = "tampilkan nim , nama dan alamat dari mahasiswa yang memiliki tinggi = 170 cm";
	//Data Dumy Ideal
	$table = array("mahasiswa","dosen","nilai");
	$atribut = array("semua","nim","nama","alamat", "tinggi", "berat");
	$kondisi = array("=",">","<",">=", "<=", "<>");
	echo "<p>";
	echo "nama tabel: ";
	foreach ($table as $value) {
		echo "$value, ";
	}
	echo "<br>nama atribut: ";
	foreach ($atribut as $value) {
		echo "$value, ";
	}
	echo "<br>kondisi: ";
	foreach ($kondisi as $value) {
		echo "$value, ";
	}
	echo"<br>";
	foreach ($contoh as $value) {
		echo "Contoh : $value <br>";
	}
	echo"</p>";
   ?>
   <form action="program-2.php" method="post">
	<p><textarea name="kalimat" rows="4" cols="50" placeholder="Kalimat Perintah"></textarea></p>
	<p><input type="submit" value="kirim"></p>
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
		$count_table = count($table);
		$count_atribut = count($atribut);
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
   <?php
	}
   ?>
 </body>
</html>