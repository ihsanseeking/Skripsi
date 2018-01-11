<html>
	<head>
		<title>Translator SQL</title>
	</head>
	<body>
		<p>Connected to server localhost successfully </p>	
		
		<form action="program-4.php" method="post">
			<p>Pilih Database :
			<select name="database">
				<option> = Pilih Database = </option>
				<option value="dunia">dunia</option>
				<option value="hobimahasiswa" selected="selected">hobimahasiswa</option>
				<option value="information_schema">information_schema</option>
				<option value="mysql">mysql</option>
				<option value="performance_schema">performance_schema</option>
				<option value="phpmyadmin">phpmyadmin</option>
				<option value="sakila">sakila</option>
				<option value="test">test</option>
			</select>
			<input name="kalimat" value="tampilkan semua data mahasiswa" type="hidden">
			<input value="kirim" type="submit"></p>
		</form>
	
		<p>Connected to database <b>hobimahasiswa</b> successfully </p> 
		Database <b>hobimahasiswa</b> 
		<br> Table [hobi] =&gt; Atribut :  (kodehobi)  (namahobi) 
		<br> Table [mahasiswa] =&gt; Atribut :  (nim)  (nama)  (tempat_lahir)  (tanggal_lahir)  (kota)  (tanggal_masuk) 
		<br> Table [mhshobi] =&gt; Atribut :  (nim)  (kodehobi) 
		<br> 
		<br>Contoh : tampilkan <i>nama_atribut1</i> , ... , <i>nama_atributn</i> dari <i>nama_tabel</i> 
		<br>Contoh : tampilkan semua data <i>nama_tabel</i> 
		<br>Contoh : tampilkan <i>nama_atribut1</i> , ... , <i>nama_atributn</i> dari <i>nama_tabel</i> yang memiliki <i>nama_atribut</i> = <i>nilai</i> 
		<br>Contoh : tampilkan semua data <i>nama_tabel</i> yang memiliki <i>nama_atribut</i> = <i>nilai</i> dan <i>nama_atribut</i> = <i>nilai</i> 
		<br>Contoh : tampilkan semua data <i>nama_tabel</i> dan <i>nama_tabel2</i> 
		<br>Contoh : tampilkan <i>nama_atribut1</i> <i>nama_tabel1</i> , ... , <i>nama_atributn</i> <i>nama_tabeln</i> dari <i>nama_tabel1</i> dan <i>nama_tabeln</i> 
		<br><p></p>   
		
		<form action="program-4.php" method="post">
			<p><textarea name="kalimat" rows="4" cols="50" placeholder="Kalimat Perintah">tampilkan semua data mahasiswa</textarea></p>
			<input name="database" value="hobimahasiswa" type="hidden">
			<p><input value="kirim" type="submit"></p>
		</form>
		<p>Kalimat Perintah : </p>
		<p> tampilkan semua data mahasiswa</p>
	   
		<p>Tahap 1. Preprocessing</p>
		Token-Token : 
		<br>Token 1 : tampilkan
		<br>Token 2 : semua
		<br>Token 3 : data
		<br>Token 4 : mahasiswa
		<br>	   <p>Tahap 2. identifikasi perintah dalam kalimat</p>
	   Token Perintah : tampilkan =&gt; SELECT
	   <br>Token Sumber : data =&gt; FROM
	   <br>	   <p>Tahap 3. identifikasi kata kunci</p>
	   kata kunci tabel : mahasiswa
	   <br>kata kunci atribut : *
	   <br>kata kunci atribut : *
	   <br>kata kunci atribut : *
	   <br>kata kunci atribut : *
	   <br>kata kunci atribut : *
	   <br>kata kunci atribut : *
	   <br>	   <p>Hasil Translator</p>
	   SELECT * , * , * , * , * , * FROM  mahasiswa     
	</body>
</html>