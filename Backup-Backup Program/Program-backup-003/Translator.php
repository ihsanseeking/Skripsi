<?php
	session_start();
	//$_SESSION["db_pendukung"] = "db_translator"; 
	// include composer autoloader
	require_once __DIR__ . '/vendor/autoload.php';
?>
 <!DOCTYPE html>
<html lang="en">
	<head>
		<title>Translator SQL</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="bootstrap.min.css">
		<script src="jquery.min.js"></script>
		<script src="bootstrap.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#h_detail_proses").hide(0);
				$("#s_detail_proses").show(0);
				$("#preprocessing").hide(0);
				$("#processing").hide(0);
					
				$("#h_detail_proses").click(function(){
					$("#h_detail_proses").hide(500);
					$("#s_detail_proses").show(500);
					$("#preprocessing").hide(1000);
					$("#processing").hide(1000);
					
				});
				$("#s_detail_proses").click(function(){
					$("#s_detail_proses").hide(500);
					$("#h_detail_proses").show(1000);
					$("#preprocessing").show(2000);
					$("#processing").show(2000);
				});
			});
		</script>
	</head>
	<body>
	<?php 
		// create stemmer
		// cukup dijalankan sekali saja, biasanya didaftarkan di service container
		/* // Ini untuk Stemming Default
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stemmer  = $stemmerFactory->createStemmer();
		*/
		// ini Untuk Stemming modifikasi data
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();

		$dictionary = $stemmerFactory->createDefaultDictionary();
		$dictionary->addWordsFromTextFile(__DIR__.'/my-dictionary.txt');
		$dictionary->add('termasuk');
		$dictionary->add('dimana');
		$dictionary->add('dengan');
		//$dictionary->remove('desa');
		
		$stemmer = new \Sastrawi\Stemmer\Stemmer($dictionary);
		
		?>
		<?php
			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
		?>
	<?php
		if (!empty($_POST["tab"])) {
			$tab = test_input($_POST["tab"]); 
		} else {
			$tab = "transator";
		}
	?>
		<div class="container">
			<h2>Translator</h2>
			<p>Perancangan Translator Bahasa Alami ke dalam format SQL</p>
			<ul class="nav nav-tabs">
				<li class="<?php if($tab=='setup'){echo"active";}?>"><a data-toggle="tab" href="#setup">Setup</a></li>
				<li class="<?php if($tab=='info'){echo"active";}?>"><a data-toggle="tab" href="#info">Info Database</a></li>
				<li class="<?php if($tab=='transator'){echo"active";}?>"><a data-toggle="tab" href="#transator">Translator</a></li>
			</ul>
			<div class="tab-content">
				<div id="setup" class="tab-pane fade <?php if ($tab == 'setup'){echo "in active";}?>">
					<h3>Setup</h3>
					<p class="text-secondary"></p>
					<hr>
					<?php
						if (!empty($_POST["servername"])) {
							$_SESSION["servername"] = test_input($_POST["servername"]); 
							$_SESSION["username"] = test_input($_POST["username"]);
							$_SESSION["password"] = test_input($_POST["password"]);
							$_SESSION["db_pendukung"] = test_input($_POST["db_pendukung"]);
						}
					?>
					<h4>Connection</h4>
					<p class="text-secondary">form untuk koneksi ke dbms.</p> 
					
					<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="form-group">
							<label class="control-label col-sm-2" for="servername">Servername :</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="servername" placeholder="servername('localhost')" name='servername' value="<?php echo $_SESSION["servername"]?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="username">Username :</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="username" placeholder="username('root')" name='username' value="<?php echo $_SESSION["username"]?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="password">Password :</label>
							<div class="col-sm-10">          
								<input type="password" class="form-control" id="password" placeholder="password('')" name='password' value="<?php echo $_SESSION["password"]?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="db_pendukung">Database :</label>
							<div class="col-sm-10">          
								<input type="text" class="form-control" id="db_pendukung" placeholder="database Pendukung('db_translator')" name='db_pendukung' value="<?php echo $_SESSION["db_pendukung"]?>">
							</div>
						</div>
						<!--
						<div class="form-group">        
							<div class="col-sm-offset-2 col-sm-10">
								<div class="checkbox">
									<label><input type="checkbox"> Remember me</label>
								</div>
							</div>
						</div>
						-->
						<div class="form-group">        
							<div class="col-sm-offset-2 col-sm-10">
								<!--<input name='kalimat' value="<?php //echo $string; ?>" type='hidden'>-->
								<input name='tab' value="setup" type='hidden'>
								<button type="submit" class="btn btn-primary btn-md">Connect</button>
							</div>
						</div>
					</form>
					<?php
						if (!empty($_SESSION["servername"])) {
							//echo $_SESSION["servername"];echo $_SESSION["username"];echo $_SESSION["password"];
							// Create connection
							$connect = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"]);
							if (!$connect) {
								echo "<p class='text-danger'>";	die("Connection failed: " . mysqli_connect_error());echo "</p>";
							} else {
								echo "<p class='text-success'>Connected to server ".$_SESSION["servername"]." successfully </p>";
							}
							// Create connection to database
							$connect_db_p = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"],$_SESSION["db_pendukung"]);
							// Check connection
							if (!$connect_db_p) {
								die("Connection to database pendukung failed: " . mysqli_connect_error());
							}
							//echo "<p>Connected to database <b>$db_pendukung</b> successfully </p>";
						}
					?>
					<hr>
					<h4>Database</h4>
					<p class="text-secondary">Pilih Analisis di Opsi untuk Menganalisis Database yang akan ingin digunakan.</p> 
					
					<?php
						if (!empty($_POST["analisis"])) {
							$db_analisis = $_POST["analisis"];
							//echo "ngeanalisis database $db_analisis"; 
							//Simpan db ke db_pendukung
							$sql_database = "
								INSERT INTO `d_database` (
									`id`,
									`nama`
								) VALUES (
									NULL,
									'$db_analisis'
								)
							";
							$result_database = mysqli_query($connect_db_p, $sql_database);
							if($result_database){
								echo"<p class='text-success'>Analisis Database <b>$db_analisis</b> Berhasil</p>";
							}
							//Temukan ID db_analisis baru
							$sql_database = "
								SELECT * 
								FROM `d_database` 
								WHERE `d_database`.`nama` = '$db_analisis'
							";
							$result_database = mysqli_query($connect_db_p, $sql_database);
							if (mysqli_num_rows($result_database) > 0) {
								// output data of each row
								while($row_database = mysqli_fetch_assoc($result_database)) {
									$db_analisis_id = $row_database["id"];
									//echo "id = $db_analisis_id nama = ".$row_database["nama"];
								}
							}
							//analisis tabel db_analisis baru
							// Create connection to database
							$connect_db_a = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $db_analisis);
							// Check connection
							if (!$connect_db_a) {
								die("Connection failed: " . mysqli_connect_error());
							}
							echo "<p class='text-success'>Connected to database <b>$db_analisis</b> successfully </p>";	
							//lihat semua table di db_analisis
							$sql_database = "SHOW FULL TABLES";
							$result_database = mysqli_query($connect_db_a, $sql_database);
							if (mysqli_num_rows($result_database) > 0) {
								while($row_database = mysqli_fetch_assoc($result_database)) {
									$tbl_analisis = $row_database["Tables_in_$db_analisis"];
									$tbl_analisis_tipe = $row_database["Table_type"];
									//simpan table ke d_table
									$sql_table="
										INSERT INTO `d_table` (
											`id`,
											`id_database`,
											`nama`,
											`tipe`
										) VALUES (
											NULL,
											'$db_analisis_id',
											'$tbl_analisis',
											'$tbl_analisis_tipe'
										)
									";
									//echo "$sql_table";
									$result_table = mysqli_query($connect_db_p, $sql_table);
									if($result_table){
										echo"<p class='text-success'>Analisis Tabel <b>$db_analisis.$tbl_analisis</b> Berhasil</p>";
									}
									//Temukan ID tbl_analisis baru
									$sql_table = "
										SELECT * FROM `d_table` 
										WHERE `d_table`.`id_database` = '$db_analisis_id' 
										AND `d_table`.`nama` = '$tbl_analisis'
									";
									$result_table = mysqli_query($connect_db_p, $sql_table);
									if (mysqli_num_rows($result_table) > 0) {
										// output data of each row
										while($row_table = mysqli_fetch_assoc($result_table)) {
											$tbl_analisis_id = $row_table["id"];
											//echo "id = $tbl_analisis_id nama = ".$row_table["nama"];
										}
									}
									//lihat semua atribut di tbl_analisis baru
									$sql_table = "DESC $tbl_analisis";
									$result_table = mysqli_query($connect_db_a, $sql_table);
									if (mysqli_num_rows($result_table) > 0) {
										$sql_part_attribute = array();
										$sql_i_attribute=0;
										$sql_part_attribute[$sql_i_attribute]="
											INSERT INTO `d_attribute` (
												`id`,
												`id_table`,
												`nama`,
												`tipe`
											) VALUES 
										";
										while($row_table = mysqli_fetch_assoc($result_table)) {
											$attr_analisis = $row_table["Field"];
											$attr_analisis_tipe = '"'.$row_table['Type'].'"';
											//echo "<br> $attr_analisis_tipe <br>";
											$sql_i_attribute++;
											$sql_part_attribute[$sql_i_attribute]="
												(
													NULL,
													'$tbl_analisis_id',
													'$attr_analisis',
													$attr_analisis_tipe
												)
											";
											$sql_i_attribute++;
											$sql_part_attribute[$sql_i_attribute]=",";
										}
										$sql_part_attribute[$sql_i_attribute]=";";
										$sql_attribute = join("",$sql_part_attribute);
										//echo "<br> $sql_attribute </br>";
										$result_attribute = mysqli_query($connect_db_p, $sql_attribute);
										if($result_attribute){
											echo"<p class='text-success'>Analisis Atribut Tabel <b>$tbl_analisis</b> Berhasil</p>";
										}	
									} else {
										echo "0 results";
									}
								}
							} else {
								echo "0 results";
							}
							//lihat semua table di db_analisis
							$sql_database = "SHOW FULL TABLES";
							$result_database = mysqli_query($connect_db_a, $sql_database);
							if (mysqli_num_rows($result_database) > 0) {
								while($row_database = mysqli_fetch_assoc($result_database)) {
									$tbl_analisis = $row_database["Tables_in_$db_analisis"];
									$tbl_analisis_tipe = $row_database["Table_type"];
									if ($tbl_analisis_tipe == "BASE TABLE"){
										//Temukan ID tbl_analisis baru
										$sql_table = "
											SELECT * FROM `d_table` 
											WHERE `d_table`.`id_database` = '$db_analisis_id' 
											AND `d_table`.`nama` = '$tbl_analisis'
										";
										$result_table = mysqli_query($connect_db_p, $sql_table);
										if (mysqli_num_rows($result_table) > 0) {
											// output data of each row
											while($row_table = mysqli_fetch_assoc($result_table)) {
												$tbl_analisis_id = $row_table["id"];
												//echo "id = $tbl_analisis_id nama = ".$row_table["nama"];
											}
										}
										//Temukan Relasi
										$sql_table = "SHOW CREATE TABLE $tbl_analisis";
										$result_table = mysqli_query($connect_db_a, $sql_table);
										if (mysqli_num_rows($result_table) > 0) {
											while($row_table = mysqli_fetch_assoc($result_table)) {
												$rel_analisis = $row_table["Create Table"];
												//echo "<br>$rel_analisis</br>";
												//dapatkan format Relasinya
												$token_relasi = strtok($rel_analisis, " ");
												$tokens_relasi = array();
												$token_i_relasi = 0;
												$token_n_relasi = 0;
												while ($token_relasi !== false) {
													if ($token_relasi == "CONSTRAINT"){
														$token_i_relasi = 0;
														$token_n_relasi++;
														while ($token_i_relasi <= 7) {
															$token_i_relasi++;
															//echo "Token_relasi[$token_n_relasi][$token_i_relasi] : $token_relasi<br>";
															$tokens_relasi[$token_n_relasi][$token_i_relasi] = $token_relasi;										
															$token_relasi = strtok(" ");
														}
													}
													$token_relasi = strtok(" ");
												}
											}
											//Extraksi Informasi dari format yang di dapatkan
											$token_i_relasi = 0;
											while ($token_i_relasi < $token_n_relasi){
												$token_i_relasi++;
												$rel_analisis_nama = strtok($tokens_relasi[$token_i_relasi][2], "`");
												$rel_analisis_foreign = strtok(strtok(strtok($tokens_relasi[$token_i_relasi][5], "("), ")"), "`");
												$rel_analisis_references_table = strtok($tokens_relasi[$token_i_relasi][7], "`");
												$rel_analisis_references_attribute = strtok(strtok(strtok($tokens_relasi[$token_i_relasi][8], "("), ")"), "`");
												//echo "<br>nama = $rel_analisis_nama | foreign = $rel_analisis_foreign | references = $rel_analisis_references_table.$rel_analisis_references_attribute<br>";
												//Temukan id Atribut Foreign
												$sql_attribute = "
													SELECT * 
													FROM `d_attribute`
													WHERE `d_attribute`.`id_table` = '$tbl_analisis_id'
													AND `d_attribute`.`nama` = '$rel_analisis_foreign'
												";
												$result_attribute = mysqli_query($connect_db_p, $sql_attribute);
												if (mysqli_num_rows($result_attribute) > 0) {
													while($row_attribute = mysqli_fetch_assoc($result_attribute)) {
														$rel_analisis_foreign_id = $row_attribute["id"];
														//echo "<br>id_foreign = $rel_analisis_foreign_id = ".$row_attribute["nama"];
													}
												}
												//Temukan id Atribut references
												$sql_attribute = "
													SELECT `d_attribute`.`id`
													FROM `d_attribute`
													LEFT JOIN `d_table` ON `d_attribute`.`id_table` = `d_table`.`id`
													LEFT JOIN `d_database` ON `d_table`.`id_database` = `d_database`.`id`
													WHERE `d_database`.`id` = '$db_analisis_id'
													AND `d_table`.`nama` = '$rel_analisis_references_table'
													AND `d_attribute`.`nama` = '$rel_analisis_references_attribute'
												";
												$result_attribute = mysqli_query($connect_db_p, $sql_attribute);
												if (mysqli_num_rows($result_attribute) > 0) {
													while($row_attribute = mysqli_fetch_assoc($result_attribute)) {
														$rel_analisis_references_id = $row_attribute["id"];
														//echo "<br>id_references = $rel_analisis_references_id";
													}
												}
												//simpan relasi ke d_relasi
												$sql_relasi="
													INSERT INTO `d_relation` (
														`id`,
														`nama`,
														`id_foreign`,
														`id_references`
													) VALUES (
														NULL,
														'$rel_analisis_nama',
														'$rel_analisis_foreign_id',
														'$rel_analisis_references_id'
													)
												";
												//echo "<br>$sql_relasi";
												$result_relasi = mysqli_query($connect_db_p, $sql_relasi);
												if($result_relasi){
													echo"<p class='text-success'>Analisis relasi <b>$rel_analisis_nama</b> Berhasil</p>";
												}
											}
										} else {
											echo "0 results";
										}
									}
								}
							} else {
								echo "0 results";
							}
						}
						if (!empty($_POST["hapus"])) {
							$db_hapus = test_input($_POST["hapus"]);
							$db_id_hapus = test_input($_POST["id_hapus"]);
							//echo "ngehapus database $db_hapus dengan id $db_id_hapus"; 	
							//Simpan db ke db_pendukung
							$sql_hapus = "DELETE FROM `d_database` WHERE `d_database`.`id` = $db_id_hapus";
							$result_hapus = mysqli_query($connect_db_p, $sql_hapus);
							if($result_hapus){
								echo"<p class='text-success'>Hapus Analisis Database <b>$db_hapus</b> berhasil</p>";
							}
						}
					?>
					
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th style="width:5%">#</th>
								<th>Nama Database</th>
								<th>Status Analisis</th>
								<th style="width:10%">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$sql_database = "SELECT * FROM `d_database`";
							$result_database = mysqli_query($connect_db_p, $sql_database);
							if (mysqli_num_rows($result_database) > 0) {
								// output data of each row
								$no=0;
								while($row_database = mysqli_fetch_assoc($result_database)) {
									$no++;
									$d_database["id"][$no]=$row_database["id"];
									$d_database["nama"][$no]=$row_database["nama"];
									//echo "$no - ".$d_database["id"][$no]." - ".$d_database["nama"][$no]."<br>";
								}
							} else {
								$d_database="";
							}
							$sql_database = "SHOW DATABASES";
							$result_database = mysqli_query($connect, $sql_database);
							if (mysqli_num_rows($result_database) > 0) {
								// output data of each row
								$no=0;
								if (!empty($d_database["id"])) {
									$count_db = count($d_database["id"]);
								} else {$count_db = 0;}
								$db_nama="";
								$db_id="";
								//echo $d_database[$count_db];
								while($row_database = mysqli_fetch_assoc($result_database)) {
									$no++;
									?>
									<tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $row_database["Database"]; ?></td>
										<td>
										<?php
											for ($i=1; $i<=$count_db; $i++){
												if($row_database["Database"] == $d_database["nama"][$i]){
													?>
													<p class="text-success">Analisis Sukses</p>
													<?php
													$db_id = $d_database["id"][$i];
													$db_nama = $d_database["nama"][$i];
													//echo "$db_id";
												}
											}
										?>
										</td>
										<td>
										<?php
											if ($db_nama == $row_database["Database"]){
												?>
												<form class="form-horizontal" action<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
													<input type="hidden" name='hapus' value="<?php echo $row_database["Database"]; ?>">
													<input type="hidden" name='id_hapus' value="<?php echo $db_id; ?>">
													<input name='tab' value="setup" type='hidden'>
													<button type="submit" class="btn btn-danger btn-sm">Hapus</button>
												</form>
												<?php
											} else {
												?>
												<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
													<input type="hidden" name='analisis' value="<?php echo $row_database["Database"]; ?>">
													<input name='tab' value="setup" type='hidden'>
													<button type="submit" class="btn btn-primary btn-sm">Analisis</button>
												</form>
												<?php
											}
										?>
											
										</td>
									</tr>
									<?php
								}
							} else {
								echo "0 results";
							}
						?>
						</tbody>
					</table>
				</div>
				<div id="info" class="tab-pane fade <?php if ($tab == 'info'){echo "in active";}?>">
					<h3>Informasi Database</h3>
					<p class="text-secondary"></p>
					<hr>
					<?php
						if (!empty($_POST["db_pilih"])) {
							$_SESSION["db_pilih"] = test_input($_POST["db_pilih"]);
						}
					?>
					<h4>Pilih Database</h4>
					<p class="text-secondary">pilih database untuk menampilkan informasinya.</p> 
					<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="form-group">
							<label class="control-label col-sm-2" for="db_pilih">Database :</label>
							<div class="col-sm-10">
								<select class="form-control" name="db_pilih" id="db_pilih">
									<option>= Pilih Database =</option>
									<?php
										$sql_database = "SELECT * FROM `d_database`";
										$result_database = mysqli_query($connect_db_p, $sql_database);
										if (mysqli_num_rows($result_database) > 0) {
											// output data of each row
											$no=0;
											while($row_database = mysqli_fetch_assoc($result_database)) {
												$no++;
												$d_database["id"][$no]=$row_database["id"];
												$d_database["nama"][$no]=$row_database["nama"];
												//echo "$no - ".$d_database["id"][$no]." - ".$d_database["nama"][$no]."<br>";
												if (!empty($_SESSION["db_pilih"])) {
													if ($_SESSION["db_pilih"] == $d_database["nama"][$no]){
														echo "<option value=". $d_database["nama"][$no]." selected=selected>". $d_database["nama"][$no]."</option>";
													} else {
														echo "<option value=". $d_database["nama"][$no].">". $d_database["nama"][$no]."</option>";
													}
												} else {
													echo "<option value=".$d_database["nama"][$no].">". $d_database["nama"][$no]."</option>";
												}
											
											}
										} else {
											$d_database="";
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">        
							<div class="col-sm-offset-2 col-sm-10">
								<input name='tab' value="info" type='hidden'>
								<button type="submit" class="btn btn-primary btn-md">Pilih</button>
							</div>
						</div>
					</form>
					<?php
						if (!empty($_SESSION["db_pilih"])) {
							$db_pilih = $_SESSION["db_pilih"];
							//Temukan ID db_analisis baru
							$sql_database = "
								SELECT * 
								FROM `d_database` 
								WHERE `d_database`.`nama` = '$db_pilih'
							";
							$result_database = mysqli_query($connect_db_p, $sql_database);
							if($result_database){
								echo"<p class='text-success'>Database <b>$db_pilih</b> Tersedia</p>";
							}
							if (mysqli_num_rows($result_database) > 0) {
								while($row_database = mysqli_fetch_assoc($result_database)) {
									$_SESSION["db_pilih_id"] = $row_database["id"];
									$db_pilih_id = $_SESSION["db_pilih_id"];
									//echo "id = $db_pilih_id => $db_pilih";
								}
							}
							
						}
					?>
					<hr>
					<h4>Informasi Data</h4>
					<p class="text-secondary">Informasi Tabel, Atribut, Alias</p>
					<?php
						// Simpan data alias baru
						if (!empty($_POST["as_nama"])) {
							$as_nama = test_input($_POST["as_nama"]);
							$as_tipe = test_input($_POST["as_tipe"]);
							if($as_tipe == "tbl"){
								$as_tbl_id	= test_input($_POST["as_tbl_id"]);
								$as_tbl_nama = test_input($_POST["as_tbl_nama"]);
								$as_tbl_jump = test_input($_POST["as_tbl_jump"]);
								//echo " tambah alias baru dengan nama <b>$as_nama</b> sebagai alias dari table dengan id <b>$as_tbl_id</b>";
								//Simpan db ke db_pendukung
								$sql_alias = "
									INSERT 
									INTO `d_alias` (
										`id`,
										`id_table`,
										`id_attribute`,
										`nama`
									) VALUES (
										NULL,
										'$as_tbl_id',
										NULL,
										'$as_nama'
									)
								";
								$result_alias = mysqli_query($connect_db_p, $sql_alias);
								if($result_alias){
									echo"<p class='text-success'>Menambahkan Alias <b>$as_nama</b> untuk Table <b>$as_tbl_nama</b> berhasil <a href=#$as_tbl_jump>lihat</a></p>";
									//header ( 'location:Translator.php#$as_tbl_jump' );
								}
							}
							if($as_tipe == "attr"){
								$as_attr_id	= test_input($_POST["as_attr_id"]);
								$as_attr_nama = test_input($_POST["as_attr_nama"]);
								$as_attr_jump = test_input($_POST["as_attr_jump"]);
								//echo " tambah alias baru dengan nama <b>$as_nama</b> sebagai alias dari atribut dengan id <b>$as_attr_id</b>";
								//Simpan db ke db_pendukung
								$sql_alias = "
									INSERT 
									INTO `d_alias` (
										`id`,
										`id_table`,
										`id_attribute`,
										`nama`
									) VALUES (
										NULL,
										NULL,
										'$as_attr_id',
										'$as_nama'
									)
								";
								$result_alias = mysqli_query($connect_db_p, $sql_alias);
								if($result_alias){
									echo"<p class='text-success'>Menambahkan Alias <b>$as_nama</b> untuk Atribute <b>$as_attr_nama</b> berhasil <a href=#$as_attr_jump>lihat</a></p>";
								}
							}
						}
						
						//Hapus Data Alias
						if (!empty($_GET["as_hapus_id"])) {
							$as_hapus_id = $_GET["as_hapus_id"];
							$as_hapus_jump = $_GET["as_hapus_jump"];
							//$as_hapus = $_POST["id_hapus"];
							//echo "ngehapus Alias dengan id <b>$as_hapus_id</b>"; 	
							//Hapus db ke db_pendukung
							$sql_alias = "
								DELETE 
								FROM `d_alias` 
								WHERE `d_alias`.`id` = '$as_hapus_id'
							";
							$result_alias = mysqli_query($connect_db_p, $sql_alias);
							if($result_alias){
								echo"<p class='text-success'>Hapus Alias dengan id <b>$as_hapus_id</b> berhasil <a href=#$as_hapus_jump>lihat</a></p>";
							}
							//header('location:Translator.php');
							//header ( 'location: http://www.alamatwebsite.com' );
						}
						
						//load data table dan atribut
						if (!empty($_SESSION["db_pilih"])) {
							$db_pilih = $_SESSION["db_pilih"];
							$db_pilih_id = $_SESSION["db_pilih_id"];
							$sql_table = "
								SELECT * 
								FROM `d_table` 
								WHERE `d_table`.`id_database` = '$db_pilih_id'
							";
							$result_table = mysqli_query($connect_db_p, $sql_table);
							if (mysqli_num_rows($result_table) > 0) {
								$as_tbl_i_pilih=0;
								$tbl_i_pilih=0;
								while($row_table = mysqli_fetch_assoc($result_table)) {
									$tbl_i_pilih++;
									$tbl_pilih = $row_table["nama"];
									$tbl_pilih_id = $row_table["id"];
									//echo "<br>id = $tbl_pilih_id => ".$row_table["tipe"]." => $tbl_pilih";
									$tbl_pilihan[$tbl_i_pilih]["id"] = $tbl_pilih_id;
									$tbl_pilihan[$tbl_i_pilih]["nama"] = $row_table["nama"]; 
									$tbl_pilihan[$tbl_i_pilih]["tipe"] = $row_table["tipe"];
									//cari alias table
									$sql_alias_tbl = "
										SELECT * 
										FROM `d_alias`
										WHERE `d_alias`.`id_table` = '$tbl_pilih_id'
									";
									$result_alias_tbl = mysqli_query($connect_db_p, $sql_alias_tbl);
									if (mysqli_num_rows($result_alias_tbl) > 0) {
										while($row_alias_tbl = mysqli_fetch_assoc($result_alias_tbl)) {
											$as_tbl_i_pilih++;
											$as_tbl_pilih = $row_alias_tbl["nama"];
											$as_tbl_pilih_id = $row_alias_tbl["id"];
											//echo "<br>--i tbl = $as_tbl_i_pilih => id = $as_tbl_pilih_id => $as_tbl_pilih";
											$as_tbl_pilihan[$as_tbl_i_pilih]["id"] = $as_tbl_pilih_id;
											$as_tbl_pilihan[$as_tbl_i_pilih]["nama"] = $row_alias_tbl["nama"]; 
											$as_tbl_pilihan[$as_tbl_i_pilih]["alias"] = $tbl_pilih;
										}
									}
									
									$sql_attribute = "
										SELECT * 
										FROM `d_attribute` 
										WHERE `d_attribute`.`id_table` = '$tbl_pilih_id'
									";
									$result_attribute = mysqli_query($connect_db_p, $sql_attribute);
									if (mysqli_num_rows($result_attribute) > 0) {
										// output data of each row
										$as_attr_i_pilih=0;
										$attr_i_pilih=0;
										while($row_attribute = mysqli_fetch_assoc($result_attribute)) {
											$attr_i_pilih++;
											$attr_pilih = $row_attribute["nama"];
											$attr_pilih_id = $row_attribute["id"];
											//echo "<br>--id = $attr_pilih_id => ".$row_attribute["nama"]." => ".$row_attribute["tipe"];
											$attr_pilihan[$tbl_pilih][$attr_i_pilih]["id"] = $attr_pilih_id;
											$attr_pilihan[$tbl_pilih][$attr_i_pilih]["nama"] = $row_attribute["nama"]; 
											$attr_pilihan[$tbl_pilih][$attr_i_pilih]["tipe"] = $row_attribute["tipe"];
											
											//cari alias atribut
											$sql_alias_attr = "
												SELECT * 
												FROM `d_alias`
												WHERE `d_alias`.`id_attribute` = '$attr_pilih_id'
											";
											$result_alias_attr = mysqli_query($connect_db_p, $sql_alias_attr);
											if (mysqli_num_rows($result_alias_attr) > 0) {
												while($row_alias_attr = mysqli_fetch_assoc($result_alias_attr)) {
													$as_attr_i_pilih++;
													$as_attr_pilih = $row_alias_attr["nama"];
													$as_attr_pilih_id = $row_alias_attr["id"];
													//echo "<br>----i attr = $as_attr_i_pilih => id = $as_attr_pilih_id => $as_attr_pilih --> $attr_pilih |";
													$as_attr_pilihan[$tbl_pilih][$as_attr_i_pilih]["id"] = $as_attr_pilih_id;
													$as_attr_pilihan[$tbl_pilih][$as_attr_i_pilih]["nama"] = $row_alias_attr["nama"]; 
													$as_attr_pilihan[$tbl_pilih][$as_attr_i_pilih]["alias"] = $attr_pilih;
													//echo $as_attr_pilihan[$tbl_pilih][$as_attr_i_pilih]["nama"];
												}
											}
										}
										//echo "<br>jumlah atribut = ". count($attr_pilihan[$tbl_pilih]);
									}
								
								}
								//echo "<br>jumlah table = ". count($tbl_pilihan);
								
							?>
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th style="width:5%">#Tbl</th>
										<th style="width:5%">#Attr</th>
										<th>Nama Tabel / Attribut</th>
										<th>Tipe</th>
										<th>Alias</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if (!empty($as_tbl_pilihan)) {
										$as_tbl_n_pilihan = count($as_tbl_pilihan);
									}
									$tbl_n_pilihan = count($tbl_pilihan);
									for ($tbl_i = 1; $tbl_i <= $tbl_n_pilihan; $tbl_i++){
										$attr_n_pilihan = count($attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]]);
										?>
										<tr id="<?php echo "tbl_".$tbl_pilihan[$tbl_i]["id"];?>">
											<td rowspan="<?php echo $attr_n_pilihan+1; ?>"><?php echo $tbl_i; ?></td>
											<td colspan="2"><b><?php echo $tbl_pilihan[$tbl_i]["nama"]; ?></b></td>
											<td><?php echo $tbl_pilihan[$tbl_i]["tipe"]; ?></td>
											<td>
												<form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
													<?php
														if (!empty($as_tbl_n_pilihan)) {
															for ($as_tbl_i = 1; $as_tbl_i <= $as_tbl_n_pilihan; $as_tbl_i++){
																if ($as_tbl_pilihan[$as_tbl_i]["alias"] == $tbl_pilihan[$tbl_i]["nama"]){
																	?>
																	<a href="?as_hapus_id=<?php echo $as_tbl_pilihan[$as_tbl_i]["id"];?>&as_hapus_jump=<?php echo "tbl_".$tbl_pilihan[$tbl_i]["id"];?>">
																		<button type="button" class="btn btn-danger btn-sm"><b>-</b></button>
																	</a>
																	<?php echo $as_tbl_pilihan[$as_tbl_i]["nama"]; ?>
																	<?php
																}
															}
														}
													?>
													<div class="form-group">
														<label class="sr-only" for="as_nama">Alias:</label>
														<input type="text" class="form-control input-sm" id="as_nama" placeholder="alias" name='as_nama'>
													</div>
													<input name='as_tipe' value="tbl" type='hidden'>
													<input name='as_tbl_id' value="<?php echo $tbl_pilihan[$tbl_i]["id"];?>" type='hidden'>
													<input name='as_tbl_nama' value="<?php echo $tbl_pilihan[$tbl_i]["nama"];?>" type='hidden'>
													<input name='as_tbl_jump' value="<?php echo "tbl_".$tbl_pilihan[$tbl_i]["id"];?>" type='hidden'>
													<input name='tab' value="info" type='hidden'>
													<button type="submit" class="btn btn-primary btn-sm"><b>+</b></button>
												</form>
											</td>
										</tr>
										<?php
										for ($attr_i = 1; $attr_i <= $attr_n_pilihan; $attr_i++){
											?>
											<tr id="<?php echo "attr_".$attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$attr_i]["id"];?>">
												<td><?php echo $attr_i; ?></td>
												<td><?php echo $attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$attr_i]["nama"]; ?></td>
												<td><?php echo $attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$attr_i]["tipe"]; ?></td>
												<td>
													<form class="form-inline" action="Translator.php" method="post">
													<?php
														if (!empty($as_attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]])) {
															$as_attr_n_pilihan = count($as_attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]]);
															//echo "<br> jumlah  $as_attr_n_pilihan";
															for ($as_attr_i = 1; $as_attr_i <= $as_attr_n_pilihan; $as_attr_i++){
																if ($as_attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$as_attr_i]["alias"] == $attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$attr_i]["nama"]){
																	?>
																	<a href="?as_hapus_id=<?php echo $as_attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$as_attr_i]["id"];?>&as_hapus_jump=<?php echo "attr_".$attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$attr_i]["id"];?>">
																		<button type="button" class="btn btn-danger btn-sm"><b>-</b></button>
																	</a>
																		<?php echo $as_attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$as_attr_i]["nama"]; ?>
																	<?php
																}
															}
														}
													?>
													<div class="form-group">
														<label class="sr-only" for="as_nama">Alias:</label>
														<input type="text" class="form-control input-sm" id="as_nama" placeholder="alias" name='as_nama'>
													</div>
													<input name='as_tipe' value="attr" type='hidden'>
													<input name='as_attr_id' value="<?php echo $attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$attr_i]["id"];?>" type='hidden'>
													<input name='as_attr_nama' value="<?php echo $attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$attr_i]["nama"];?>" type='hidden'>
													<input name='as_attr_jump' value="<?php echo "attr_".$attr_pilihan[$tbl_pilihan[$tbl_i]["nama"]][$attr_i]["id"];?>" type='hidden'>
													<input name='tab' value="info" type='hidden'>
													<button type="submit" class="btn btn-primary btn-sm"><b>+</b></button>
													</form>
												</td>
											</tr>
											<?php
										}
									}
								?>
								</tbody>
							</table>							
							<?php
							}
						}
					?>
					<hr>
					<h4> Informasi Relasi </h4>
					<p class="text-secondary"></p>
					<?php
						if (!empty($_SESSION["db_pilih"])) {
							$db_pilih = $_SESSION["db_pilih"];
							$db_pilih_id = $_SESSION["db_pilih_id"];
							$sql_foreign = "
								SELECT 
									`d_relation`.`id` AS 'rel_id',
									`d_relation`.`nama` AS 'rel_nama',
									`d_table`.`nama` AS 'rel_foreign_tbl',
									`d_attribute`.`nama` AS 'rel_foreign_attr'
								FROM `d_relation`
								LEFT JOIN `d_attribute` ON `d_relation`.`id_foreign` = `d_attribute`.`id`
								LEFT JOIN `d_table` ON `d_attribute`.`id_table` = `d_table`.`id`
								WHERE `d_table`.`id_database` = '$db_pilih_id'
							";
							$result_foreign = mysqli_query($connect_db_p, $sql_foreign);
							if (mysqli_num_rows($result_foreign) > 0) {
								$rel_i_pilih=0;
								//$rel_pilih_tbl="";
								while($row_foreign = mysqli_fetch_assoc($result_foreign)) {
									
									$rel_i_pilih++;
									$rel_pilih = $row_foreign["rel_nama"];
									$rel_pilih_id = $row_foreign["rel_id"];
									$rel_pilihan[$rel_i_pilih]["id"] = $rel_pilih_id;
									$rel_pilihan[$rel_i_pilih]["nama"] = $row_foreign["rel_nama"]; 
									$rel_pilihan[$rel_i_pilih]["foreign_table"] = $row_foreign["rel_foreign_tbl"];
									$rel_pilihan[$rel_i_pilih]["foreign_attribute"] = $row_foreign["rel_foreign_attr"];
									
									$sql_references = "
										SELECT 
											`d_relation`.`id` AS 'rel_id',
											`d_table`.`nama` AS 'rel_references_tbl',
											`d_attribute`.`nama` AS 'rel_references_attr'
										FROM `d_relation`
										LEFT JOIN `d_attribute` ON `d_relation`.`id_references` = `d_attribute`.`id`
										LEFT JOIN `d_table` ON `d_attribute`.`id_table` = `d_table`.`id`
										WHERE `d_relation`.`id` = '$rel_pilih_id'
									";
									$result_references = mysqli_query($connect_db_p, $sql_references);
									if (mysqli_num_rows($result_references) > 0) {
										while($row_references = mysqli_fetch_assoc($result_references)) {
											
											$rel_pilihan[$rel_i_pilih]["references_table"] = $row_references["rel_references_tbl"];
											$rel_pilihan[$rel_i_pilih]["references_attribute"] = $row_references["rel_references_attr"];
										}
									}
									
								}
								
								?>
								<table class="table table-hover table-bordered table-responsive">
									<thead>
										<tr>
											<th style="width:5%">#</th>
											<th>Nama Relasi</th>
											<th>Foreign Key (nama_table.nama_attribute)</th>
											<th>References (nama_table.nama_attribute)</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$rel_n_pilihan = count($rel_pilihan);
										for ($rel_i = 1; $rel_i <= $rel_n_pilihan; $rel_i++){
											?>
											<tr>
												<td><?php echo $rel_i; ?></td>
												<td><?php echo $rel_pilihan[$rel_i]["nama"]; ?></td>
												<td><?php echo $rel_pilihan[$rel_i]["foreign_table"].".<b>".$rel_pilihan[$rel_i]["foreign_attribute"]."</b>"; ?></td>
												<td><?php echo $rel_pilihan[$rel_i]["references_table"].".<b>".$rel_pilihan[$rel_i]["references_attribute"]."</b>"; ?></td>
											</tr>
											<?php
										}	
									
									?>
									</tbody>
								</table>
								<?php
							} else {
								echo "<p class='text-muted'>Tidak ditemukan relasi pada database <b>$db_pilih</b>.</p>";
							}
						}
					?>
				</div>
				<div id="transator" class="tab-pane fade <?php if ($tab == 'transator'){echo "in active";}?>">
					<h3>Translator</h3>
					<?php
						if (!empty($_SESSION["db_pilih"])) {
							$db_pilih = $_SESSION["db_pilih"];
							?>
							<p>Database yang digunakan adalah <b><?php echo $_SESSION["db_pilih"]; ?></b>.</p>
							<?php
						} else {
							?>
							<p>Database yang digunakan belum ditentukan, Pilih database yang akan digunakan di tab Informasi database.</p>
							<?php
						}							
					?>
					<hr>
					<?php
						if (!empty($_POST["kalimat"])) {
							$_SESSION["kalimat"] = test_input($_POST["kalimat"]); 
							$kalimat = $_SESSION["kalimat"];
						} else {
							$kalimat = "";
						}
					?>
					<h4>Kalimat Masukan</h4>
					<p></p>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="form-group">
							<label for="kalimat">Kalimat:</label>
							<textarea class="form-control" rows="3" id="kalimat" name="kalimat"><?php echo $kalimat; ?></textarea>
						</div> 
						<input name='tab' value="transator" type='hidden'>
						<button id="mulai_proses" type="submit" class="btn btn-primary">Terjemahkan</button>
					</form>
					<?php
						if (!empty($_SESSION["kalimat"])) {
							?>
							<hr>
							<button id="s_detail_proses" class="btn btn-default">Tampilkan Detail Proses</button>
							<button id="h_detail_proses" class="btn btn-default">Sembunyikan Detail Proses</button>
							<div id="preprocessing">
								<h4>Tahap Preprocessing</h4>
								<p>Kalimat : <?php echo $kalimat;?></p>
								<?php
								//echo chr(34);
								//echo str_replace('dentist','aneh',$kalimat);
								//echo trim($kalimat,'"');
								//$kalimat = preg_replace('/[^A-Za-z0-9\  ]/', '', $kalimat);
								?>
								<div id="tokenizing">
									<h4>Tokenizing</h4>
									<?php
										// Proses pemotongan String berdasarkan spasi
										$token_kalimat = strtok($kalimat, " ");
										//echo "Token-Token : <br>";
										$i_token = 0;
										$token_kata = array();
										while ($token_kalimat !== false)
										{
											$i_token++;
											// masukan token kedalam array
											$token_kata[$i_token] = $token_kalimat;
											//echo trim($token_kata[$i_token],'quot');;
											$token_kalimat = strtok(" ");
											//echo "Token $i_token : $token_kata[$i_token]<br>";
										}
									?>
									<table class="table table-hover table-bordered table-responsive">
										<thead>
											<tr>
												<th style="width:10%">Nama</th>
												<th>Objek</th>
											</tr>
										</thead>
										<tbody>	
											<tr>
												<td>[Masukan] String Kalimat</td>
												<td><?php echo $kalimat; ?></td>
											</tr>
											<tr>
												<td>[Keluaran] Array Token</td>
												<td>
												<?php 
													$n_token = count($token_kata);
													for ($i_token = 1; $i_token <= $n_token; $i_token++){
														echo "Token $i_token : $token_kata[$i_token]<br>";
													}
												?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div id="stemming">
									<h4>Stemming</h4>
									<?php
										$n_token = count($token_kata);
										for ($i_token = 1; $i_token <= $n_token; $i_token++){
											//echo "<br>".ord("$token_kata[$i_token]");
											if (ord("$token_kata[$i_token]") == "38"){
												//echo "ada kutip";
												//$token_stem[$i_token] = preg_replace('/[^A-Za-z0-9\  ]/', '', $token_kata[$i_token]);
												//$token_stem[$i_token] = trim($token_kata[$i_token],"\"");
												$token_stem[$i_token] = $token_kata[$i_token];
											} else {
												$token_stem[$i_token] = $stemmer->stem($token_kata[$i_token]);
											}
										}
									?>
									<?php
										/*$n_token = count($token_kata);
										for ($i_token = 1; $i_token <= $n_token; $i_token++){
											$token_stem[$i_token] = $stemmer->stem($token_kata[$i_token]);
										}*/
									?>
									<table class="table table-hover table-bordered table-responsive">
										<thead>
											<tr>
												<th style="width:10%">Nama</th>
												<th>Objek</th>
											</tr>
										</thead>
										<tbody>	
											<tr>
												<td>[Masukan] Array Token</td>
												<td>
												<?php 
													for ($i_token = 1; $i_token <= $n_token; $i_token++){
														echo "Token $i_token : $token_kata[$i_token]";
														if($token_kata[$i_token] != $token_stem[$i_token]){
															echo " => $token_stem[$i_token]";
														}
														echo"<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Array Token Stemming</td>
												<td>
												<?php 
													$n_stem = count($token_stem);
													for ($i_stem = 1; $i_stem <= $n_stem; $i_stem++){
														echo "Token $i_stem : $token_stem[$i_stem]<br>";
													}
												?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div id="alias">
									<h4>Alias</h4>
									<?php
										$n_stem = count($token_stem);
										$i_token_as_table=0;
										for ($i_stem = 1; $i_stem <= $n_stem; $i_stem++){
											if (ord("$token_stem[$i_stem]") == "34"){
												$token_alias[$i_stem] = $token_stem[$i_stem];
											} else {
												$token_alias[$i_stem] = $token_stem[$i_stem];
												//Cari Alias Table
												$n_alias = count($as_tbl_pilihan);
												for ($i_alias = 1; $i_alias <= $n_alias; $i_alias++){
													//echo "<br>$token_alias[$i_stem] - ".$as_tbl_pilihan[$i_alias]["nama"];
													if ($token_alias[$i_stem] == $as_tbl_pilihan[$i_alias]["nama"]) {
														$token_alias[$i_stem] = $as_tbl_pilihan[$i_alias]["alias"];
														//echo " -> jadi = $token_alias[$i_stem]";
													}
												}
												$n_as_table = count($tbl_pilihan);
												for ($i_as_table = 1; $i_as_table <= $n_as_table; $i_as_table++){
													//echo "<br>$token_alias[$i_stem] - ".$tbl_pilihan[$i_as_table]["nama"];
													if ($token_alias[$i_stem] == $tbl_pilihan[$i_as_table]["nama"]) {
														$i_token_as_table++;
														$token_as_table[$i_token_as_table] = $tbl_pilihan[$i_as_table]["nama"];
														//echo " -> jadi id $i_as_table = ".$tbl_pilihan[$i_as_table]["nama"];
													}
												}
											}
										}
										for ($i_stem = 1; $i_stem <= $n_stem; $i_stem++){
											if (ord("$token_stem[$i_stem]") == "34"){
												//$token_alias[$i_stem] = trim($token_stem[$i_stem],'",');
												$token_alias[$i_stem] = $token_stem[$i_stem];
											} else {
												$n_token_as_table = count($token_as_table);
												for ($i_token_as_table = 1; $i_token_as_table <= $n_token_as_table; $i_token_as_table++){
													$n_attr = count($as_attr_pilihan[$token_as_table[$i_token_as_table]]);
													for ($i_attr = 1; $i_attr <= $n_attr; $i_attr++){
														//echo "<br>$token_alias[$i_stem] - ".$as_attr_pilihan[$token_as_table[$i_token_as_table]][$i_attr]["nama"];
														if ($token_alias[$i_stem] == $as_attr_pilihan[$token_as_table[$i_token_as_table]][$i_attr]["nama"]) {
															$token_alias[$i_stem] = $as_attr_pilihan[$token_as_table[$i_token_as_table]][$i_attr]["alias"];
															//echo " -> jadi = $token_alias[$i_stem]";
														}
													}
												}
											}
										}
										
									?>
									<table class="table table-hover table-bordered table-responsive">
										<thead>
											<tr>
												<th style="width:10%">Nama</th>
												<th>Objek</th>
											</tr>
										</thead>
										<tbody>	
											<tr>
												<td>[Masukan] Array Token Stemming</td>
												<td>
												<?php 
													$n_stem = count($token_stem);
													for ($i_stem = 1; $i_stem <= $n_stem; $i_stem++){
														echo "Token $i_stem : $token_stem[$i_stem]";
														if($token_stem[$i_stem] != $token_alias[$i_stem]){
															echo " => $token_alias[$i_stem]";
														}
														echo"<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Array Token Alias</td>
												<td>
												<?php 
													$n_alias = count($token_alias);
													for ($i_alias = 1; $i_alias <= $n_alias; $i_alias++){
														echo "Token $i_alias : $token_alias[$i_alias]<br>";
													}
												?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<hr>
							<div id="processing">
								<h4>Tahap processing</h4>
								<p></p>
								<div id="identifikasi_perintah">
									<h4>Identifikasi Perintah</h4>
									<?php
										$kamus_perintah = fopen("kamus_perintah.txt", "r") or die("Unable to open file!");
										// Output one character until end-of-file
										//$kms_perintah = array();
										$i_kms = 0;
										while(!feof($kamus_perintah)) {
											$i_kms++;
											$kms_perintah[$i_kms] = fgetcsv($kamus_perintah,0,'=');
											//echo "[0] => ".$kms_perintah[$i_kms][0]."[1] => ".$kms_perintah[$i_kms][1]." <br>";
										}
										fclose($kamus_perintah);
										$ident_perintah = "tidak teridentifikasi";
										$n_kms_p = count($kms_perintah);
										$i_alias = 0;
										while ($i_alias !== $n_alias)
										{
											$i_alias++;
											//echo "Token $i_alias : $token_alias[$i_alias]<br>";
											for ($i_kms = 1; $i_kms <= $n_kms_p; $i_kms++){
												//echo "Token $i_alias : $token_alias[$i_alias] == ".$kms_perintah[$i_kms][0]."<br>";
												if($token_alias[$i_alias] == $kms_perintah[$i_kms][0]) {
													$ident_perintah = $kms_perintah[$i_kms][1];
													//echo "Ident_perintah : $token_alias[$i_alias] => $ident_perintah<br>";
												}
											}
										}
									?>
									<table class="table table-hover table-bordered table-responsive">
										<thead>
											<tr>
												<th style="width:10%">Nama</th>
												<th>Objek</th>
											</tr>
										</thead>
										<tbody>	
											<tr>
												<td>[Masukan] Array Token Preprocessing</td>
												<td>
												<?php 
													$n_alias = count($token_alias);
													$n_kms_p = count($kms_perintah);
													$i_alias = 0;
													while ($i_alias !== $n_alias)
													{
														$i_alias++;
														echo "Token $i_alias : $token_alias[$i_alias]";
														for ($i_kms = 1; $i_kms <= $n_kms_p; $i_kms++){
															if($token_alias[$i_alias] == $kms_perintah[$i_kms][0]) {
																echo " => $ident_perintah";
															}
														}
														echo "<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Perintah</td>
												<td>
												<?php 
													echo "Ident_perintah : $ident_perintah";
												?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div id="identifikasi_table">
									<h4>Identifikasi Table</h4>
									<?php
										$ident_table = array();
										$i_alias = 0;
										$n_ident_tbl = 0;
										//$nama_table[$nt] = "Tidak ada nama tabel";
										$tbl_n_pilihan = count($tbl_pilihan);
										//echo "<br>tbl_n_pilihan = $tbl_n_pilihan";
										//Nyari Tabel dulu
										while ($i_alias !== $n_alias){
											$i_alias++;
											//echo "<br>token_alias[$i_alias] = $token_alias[$i_alias]";
											//looping tabel
											for ($tbl_i = 1; $tbl_i <= $tbl_n_pilihan; $tbl_i++){
												//nyari tabel yg sama dengan token
												//echo "<br>tbl_pilihan[$tbl_i] => ".$tbl_pilihan[$tbl_i]["nama"];
												if ($token_alias[$i_alias] == $tbl_pilihan[$tbl_i]["nama"]) {
													//cek jumlah nama_tabel
													$n_ident_tbl = count($ident_table);
													if ($n_ident_tbl > 0) {
														//looping nama_tabel
														$sama=false;
														for ($i_ident_tbl = 1; $i_ident_tbl <= $n_ident_tbl; $i_ident_tbl++) {
															//cek bisi ada token yang sama dengan nama_tabel yg sudah di daftarkan
															if($token_alias[$i_alias] == $ident_table[$i_ident_tbl]){
																//echo " nemu nama tabel yg sama [$ident_table[$i_ident_tbl]] <br>";
																$sama=true;	
															} 
														}
														if (!$sama) {
															$n_ident_tbl++;
															$ident_table[$n_ident_tbl] = $tbl_pilihan[$tbl_i]["nama"];
															//echo "ident_table[$n_ident_tbl] : $ident_table[$n_ident_tbl]<br>";
															
														}
													} else {
														$n_ident_tbl++;
														$ident_table[$n_ident_tbl] = $tbl_pilihan[$tbl_i]["nama"];
														//echo "ident_table[$n_ident_tbl] : $ident_table[$n_ident_tbl]<br>";
														
													}
												}
											}
										}
										//echo "<p>jumlah $n_ident_tbl </p>";
									?>
									<!--<h4>Identifikasi Relasi</h4>-->
									<?php
										//Identifikasi Relasi
										$ident_relasi = array();
										$i_ident_rel=0;
										$n_ident_tbl = count($ident_table);
										$i_ident_tbl = 1;
										if ($n_ident_tbl >= 1){
											$i_ident_rel++;
											$ident_relasi[$i_ident_rel] = "FROM `$ident_table[$i_ident_tbl]`";
											//echo "ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
											$rel_n_pilihan = count($rel_pilihan);
											for ($rel_i = 1; $rel_i <= $rel_n_pilihan; $rel_i++){ //Looping Relasi
												//Cek Relasi dengan table sebagai Foreign
												if ($rel_pilihan[$rel_i]["foreign_table"] == $ident_table[$i_ident_tbl]){
													//echo "<br><br>-- Foreign <b>".$rel_pilihan[$rel_i]["foreign_table"]."</b> -> references <b>".$rel_pilihan[$rel_i]["references_table"]."</b>";
													//Cek Relasi ke di sendiri
													if ($rel_pilihan[$rel_i]["foreign_table"] == $rel_pilihan[$rel_i]["references_table"]){
														//echo "<br> Ada Relasi ke diri sendiri = <b>".$rel_pilihan[$rel_i]["nama"]."<b>";
														$i_ident_rel++;
														$ident_relasi[$i_ident_rel] = "INNER JOIN `"
															.$rel_pilihan[$rel_i]["foreign_table"].
															"` ON `"
															.$rel_pilihan[$rel_i]["references_table"].
															"`.`"
															.$rel_pilihan[$rel_i]["references_attribute"].
															"` = `"
															.$rel_pilihan[$rel_i]["foreign_table"].
															"`.`"
															.$rel_pilihan[$rel_i]["foreign_attribute"].
															"`";
														//echo "<br>ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
													} else {
														for ($i_ident_tbl_2 = 1; $i_ident_tbl_2 <= $n_ident_tbl; $i_ident_tbl_2++){ //Looping ident_table dari > 1
															//Pastikan beda table
															if($ident_table[$i_ident_tbl_2] != $ident_table[$i_ident_tbl]){
																//echo "<br>--- Table_2 <b>$ident_table[$i_ident_tbl_2]</b>";
																//Cek Relasi Langsung apakah ada tabel referensi di foregn key
																if ($rel_pilihan[$rel_i]["references_table"] == $ident_table[$i_ident_tbl_2]){
																	//echo "<br> Ada Relasi langsung sebagain references dari foreign = <b>".$rel_pilihan[$rel_i]["nama"]."</b>";
																	$i_ident_rel++;
																	$ident_relasi[$i_ident_rel] = "INNER JOIN `"
																		.$rel_pilihan[$rel_i]["references_table"].
																		"` ON `"
																		.$rel_pilihan[$rel_i]["foreign_table"].
																		"`.`"
																		.$rel_pilihan[$rel_i]["foreign_attribute"].
																		"` = `"
																		.$rel_pilihan[$rel_i]["references_table"].
																		"`.`"
																		.$rel_pilihan[$rel_i]["references_attribute"].
																		"`";
																	//echo "<br>ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
																} else {
																	//Cek Relasi Tidak Langsung
																	for ($rel_i_2 = 1; $rel_i_2 <= $rel_n_pilihan; $rel_i_2++){ //Looping Relasi
																		if ($rel_pilihan[$rel_i_2]["foreign_table"] == $ident_table[$i_ident_tbl_2]){
																			//echo "<br>---- Foreign 2 <b>".$rel_pilihan[$rel_i_2]["foreign_table"]."</b> -> references 2 <b>".$rel_pilihan[$rel_i_2]["references_table"]."</b>";
																			//echo "<br>---- cek tidak langsung rel_1 foregn == rel_1_2 foregn(".$rel_pilihan[$rel_i_2]["references_table"]." == ".$rel_pilihan[$rel_i_2]["references_table"].")";
																			if ($rel_pilihan[$rel_i]["references_table"] == $rel_pilihan[$rel_i_2]["references_table"]){
																				//echo "<br> Ada Relasi tidak langsung sebagain references dari foreign = ".$rel_pilihan[$rel_i]["nama"];
																				$i_ident_rel++;
																				$ident_relasi[$i_ident_rel] = "INNER JOIN `"
																					.$rel_pilihan[$rel_i_2]["references_table"].
																					"` ON `"
																					.$rel_pilihan[$rel_i_2]["foreign_table"].
																					"`.`"
																					.$rel_pilihan[$rel_i_2]["foreign_attribute"].
																					"` = `"
																					.$rel_pilihan[$rel_i_2]["references_table"].
																					"`.`"
																					.$rel_pilihan[$rel_i_2]["references_attribute"].
																					"`";
																				echo "<br>ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
																				$i_ident_rel++;
																				$ident_relasi[$i_ident_rel] = "INNER JOIN `"
																					.$rel_pilihan[$rel_i]["foreign_table"].
																					"` ON `"
																					.$rel_pilihan[$rel_i]["references_table"].
																					"`.`"
																					.$rel_pilihan[$rel_i]["references_attribute"].
																					"` = `"
																					.$rel_pilihan[$rel_i]["foreign_table"].
																					"`.`"
																					.$rel_pilihan[$rel_i]["foreign_attribute"].
																					"`";
																				//echo "<br>ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
																			}
																		}
																	}
																}
															}
														}
													}
												}
												//Cek Relasi Sebagai References
												if ($rel_pilihan[$rel_i]["references_table"] == $ident_table[$i_ident_tbl]){
													//echo "<br><br>-- references <b>".$rel_pilihan[$rel_i]["references_table"]."</b> <- foreign <b>".$rel_pilihan[$rel_i]["foreign_table"]."</b>";
													//echo "<br>sebagai references dari = ".$rel_pilihan[$rel_i]["foreign_table"];
													//Cek Relasi ke di sendiri
													if ($rel_pilihan[$rel_i]["references_table"] == $rel_pilihan[$rel_i]["foreign_table"]){
														//echo "<br> Ada Relasi ke diri sendiri = <b>".$rel_pilihan[$rel_i]["nama"]."</b>";
														$i_ident_rel++;
														$ident_relasi[$i_ident_rel] = "INNER JOIN `"
															.$rel_pilihan[$rel_i]["foreign_table"].
															"` ON `"
															.$rel_pilihan[$rel_i]["references_table"].
															"`.`"
															.$rel_pilihan[$rel_i]["references_attribute"].
															"` = `"
															.$rel_pilihan[$rel_i]["foreign_table"].
															"`.`"
															.$rel_pilihan[$rel_i]["foreign_attribute"].
															"`";
														//echo "<br>ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
													} else {
														for ($i_ident_tbl_2 = 1; $i_ident_tbl_2 <= $n_ident_tbl; $i_ident_tbl_2++){ //Looping ident_table dari > 1
															if($ident_table[$i_ident_tbl_2] != $ident_table[$i_ident_tbl]){
																//echo "<br>--- Table_2 <b>$ident_table[$i_ident_tbl_2]</b>";
																//echo "<br>--- cek langsung rel_1 foregn == table 2(".$rel_pilihan[$rel_i]["foreign_table"]." == $ident_table[$i_ident_tbl_2])";
																if ($rel_pilihan[$rel_i]["foreign_table"] == $ident_table[$i_ident_tbl_2]){
																	//echo "<br> Ada Relasi langsung sebagain foreign ke references = <b>".$rel_pilihan[$rel_i]["nama"]."</b>";
																	$i_ident_rel++;
																	$ident_relasi[$i_ident_rel] = "INNER JOIN `"
																		.$rel_pilihan[$rel_i]["foreign_table"].
																		"` ON `"
																		.$rel_pilihan[$rel_i]["references_table"].
																		"`.`"
																		.$rel_pilihan[$rel_i]["references_attribute"].
																		"` = `"
																		.$rel_pilihan[$rel_i]["foreign_table"].
																		"`.`"
																		.$rel_pilihan[$rel_i]["foreign_attribute"].
																		"`";
																	//echo "<br>ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
																} else {
																	//Cek Relasi Tidak Langsung
																	for ($rel_i_2 = 1; $rel_i_2 <= $rel_n_pilihan; $rel_i_2++){ //Looping Relasi
																		if ($rel_pilihan[$rel_i_2]["references_table"] == $ident_table[$i_ident_tbl_2]){
																			//echo "<br>---- references 2 <b>".$rel_pilihan[$rel_i_2]["references_table"]."</b> <- foreign 2 <b>".$rel_pilihan[$rel_i_2]["foreign_table"]."</b>";
																			//echo "<br>---- cek tidak langsung rel_1 foregn == rel_1_2 foregn(".$rel_pilihan[$rel_i_2]["foreign_table"]." == ".$rel_pilihan[$rel_i_2]["foreign_table"].")";
																			if ($rel_pilihan[$rel_i]["foreign_table"] == $rel_pilihan[$rel_i_2]["foreign_table"]){
																				//echo "<br> Ada Relasi tidak langsung sebagain references dari foreign = ".$rel_pilihan[$rel_i]["nama"];
																				$i_ident_rel++;
																				$ident_relasi[$i_ident_rel] = "INNER JOIN `"
																					.$rel_pilihan[$rel_i]["foreign_table"].
																					"` ON `"
																					.$rel_pilihan[$rel_i]["references_table"].
																					"`.`"
																					.$rel_pilihan[$rel_i]["references_attribute"].
																					"` = `"
																					.$rel_pilihan[$rel_i]["foreign_table"].
																					"`.`"
																					.$rel_pilihan[$rel_i]["foreign_attribute"].
																					"`";
																				//echo "<br>ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
																				$i_ident_rel++;
																				$ident_relasi[$i_ident_rel] = "INNER JOIN `"
																					.$rel_pilihan[$rel_i_2]["references_table"].
																					"` ON `"
																					.$rel_pilihan[$rel_i_2]["foreign_table"].
																					"`.`"
																					.$rel_pilihan[$rel_i_2]["foreign_attribute"].
																					"` = `"
																					.$rel_pilihan[$rel_i_2]["references_table"].
																					"`.`"
																					.$rel_pilihan[$rel_i_2]["references_attribute"].
																					"`";
																				//echo "<br>ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]";
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									?>
									<table class="table table-hover table-bordered table-responsive">
										<thead>
											<tr>
												<th style="width:10%">Nama</th>
												<th>Objek</th>
											</tr>
										</thead>
										<tbody>	
											<tr>
												<td>[Masukan] Array Token Preprocessing</td>
												<td>
												<?php 
													$n_alias = count($token_alias);
													$n_ident_tbl = count($ident_table);
													for ($i_alias = 1; $i_alias <= $n_alias; $i_alias++){
														echo "Token $i_alias : $token_alias[$i_alias]";
														for ($i_ident_tbl = 1; $i_ident_tbl <= $n_ident_tbl; $i_ident_tbl++){
															if($ident_table[$i_ident_tbl] == $token_alias[$i_alias]){
																echo " => $ident_table[$i_ident_tbl]";
															}
														}
														echo "<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Tabel</td>
												<td>
												<?php 
													$n_ident_tbl = count($ident_table);
													for ($i_ident_tbl = 1; $i_ident_tbl <= $n_ident_tbl; $i_ident_tbl++){
														echo "ident_table[$i_ident_tbl] : $ident_table[$i_ident_tbl]<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Relasi</td>
												<td>
												<?php 
													$n_ident_rel = count($ident_relasi);
													for ($i_ident_rel = 1; $i_ident_rel <= $n_ident_rel; $i_ident_rel++){
														echo "ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]<br>";
													}
												?>
												</td>
											</tr>
										</tbody>
									</table>									
								</div>
								<div id="identifikasi_attribute">
									<h4>Identifikasi Attribute</h4>
									<?php
										$ident_attribute = array();
										$i_ident_attr = 0;
										for ($i_tbl = 1; $i_tbl <= $n_ident_tbl; $i_tbl++){
											//echo "<br>- Table : $ident_table[$i_tbl]";
											$n_attr_pilihan = count($attr_pilihan[$ident_table[$i_tbl]]);	
											for ($i_attr = 1; $i_attr <= $n_attr_pilihan; $i_attr++){
												//echo "<br>-- Atribut : ",$attr_pilihan[$ident_table[$i_tbl]][$i_attr]["nama"];
												for($i_token = 1; $i_token <= $n_alias ; $i_token++){
													if ($attr_pilihan[$ident_table[$i_tbl]][$i_attr]["nama"] == $token_alias[$i_token]){
														$i_ident_attr++;
														$ident_attribute[$i_ident_attr]["attribute"] = $attr_pilihan[$ident_table[$i_tbl]][$i_attr]["nama"];
														$ident_attribute[$i_ident_attr]["posisi"] = $i_token;
														//echo "ident_attribute[$i_ident_attr] : ".$ident_attribute[$i_ident_attr]["attribute"]."<br>";
														
													}
												}
											}
										}
									?>
									<table class="table table-hover table-bordered table-responsive">
										<thead>
											<tr>
												<th style="width:10%">Nama</th>
												<th>Objek</th>
											</tr>
										</thead>
										<tbody>	
											<tr>
												<td>[Masukan] Identifikasi Tabel</td>
												<td>
												<?php 
													$n_ident_tbl = count($ident_table);
													for ($i_ident_tbl = 1; $i_ident_tbl <= $n_ident_tbl; $i_ident_tbl++){
														echo "ident_table[$i_ident_tbl] : $ident_table[$i_ident_tbl]<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Masukan] Array Token Preprocessing</td>
												<td>
												<?php 
													$n_alias = count($token_alias);
													$n_ident_attr = count($ident_attribute);
													for ($i_alias = 1; $i_alias <= $n_alias; $i_alias++){
														echo "Token $i_alias : $token_alias[$i_alias]";
														for ($i_ident_attr = 1; $i_ident_attr <= $n_ident_attr; $i_ident_attr++){
															if($ident_attribute[$i_ident_attr]["attribute"] == $token_alias[$i_alias]){
																echo " => ".$ident_attribute[$i_ident_attr]["attribute"];
															}
														}
														echo "<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Atribut</td>
												<td>
												<?php 
													$n_ident_attr = count($ident_attribute);
													for ($i_ident_attr = 1; $i_ident_attr <= $n_ident_attr; $i_ident_attr++){
														echo "ident_attribute[$i_ident_attr] : ".$ident_attribute[$i_ident_attr]["attribute"]."<br>";
													}
												?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div id="identifikasi_fitur">
									<h4>Identifikasi Fitur</h4>
									<?php
										$ident_where = Array();
										$i_ident_where = 0;
										for ($i_where=1; $i_where <= $n_alias; $i_where++){
											//echo "<br> $token_alias[$i_where]";
											if ($token_alias[$i_where] == "yang" ){
												$i_ident_where++;
												$ident_where[$i_ident_where]["posisi"] = $i_where;
												//echo " ident_where[$i_ident_where] : ".$token_alias[$ident_where[$i_ident_where]["posisi"]];
											} else if ($token_alias[$i_where] == "dimana" ) {
												$i_ident_where++;
												$ident_where[$i_ident_where]["posisi"] = $i_where;
												//echo "<br> ident_where[$i_ident_where] : ".$token_alias[$ident_where[$i_ident_where]["posisi"]];
											} else if ($token_alias[$i_where] == "dengan" ) {
												$i_ident_where++;
												$ident_where[$i_ident_where]["posisi"] = $i_where;
												//echo "<br> ident_where[$i_ident_where] : ".$token_alias[$ident_where[$i_ident_where]["posisi"]];
											}
										}
										
										$n_ident_where = count($ident_where); 
										//echo "<br>- $n_ident_where";
										if ($n_ident_where >= 1){
											for ($i_ident_where=1;$i_ident_where <= $n_ident_where;$i_ident_where++) {
												$ps_where = $ident_where[$i_ident_where]["posisi"];
												if ($i_ident_where != $n_ident_where) {
													$pf_where = $ident_where[$i_ident_where+1]["posisi"];
												} else {
													$pf_where = $n_alias+1;
												}
												//echo "<br> $ps_where - $pf_where";
												//cari table
												for ($i_where=$ps_where; $i_where < $pf_where; $i_where++){
													//echo "<br>-$token_alias[$i_where]";
													for ($i_ident_tbl = 1; $i_ident_tbl <= $n_ident_tbl; $i_ident_tbl++){
														//echo "<br> -$token_alias[$i_where] == $ident_table[$i_ident_tbl]";
														if ($token_alias[$i_where] == $ident_table[$i_ident_tbl]){
															$ident_where[$i_ident_where]["table"] = $ident_table[$i_ident_tbl];
															//echo "<br>ident_where_tabel[$i_ident_where] : ".$ident_where[$i_ident_where]["table"];
														}
														$n_ident_attr = count($attr_pilihan[$ident_table[$i_ident_tbl]]);
														for ($i_ident_attr = 1; $i_ident_attr <= $n_ident_attr; $i_ident_attr++){
															//echo "<br>+$token_alias[$i_where] == ".$attr_pilihan[$ident_table[$i_ident_tbl]][$i_ident_attr]["nama"];
															if ($token_alias[$i_where] == $attr_pilihan[$ident_table[$i_ident_tbl]][$i_ident_attr]["nama"]){
																$ident_where[$i_ident_where]["attribute"] = $attr_pilihan[$ident_table[$i_ident_tbl]][$i_ident_attr]["nama"];
																//echo "<br>ident_where_attributr[$i_ident_where] : ".$ident_where[$i_ident_where]["attribute"];
															}
														}
													}
													if ($token_alias[$i_where] == "termasuk"){
														$ident_where[$i_ident_where]["perbandingan"] = "=";
														//echo "<br> ident_where_perbandingan[$i_ident_where] : ".$ident_where[$i_ident_where]["perbandingan"];
														
														
														
													} else if ($token_alias[$i_where] == "kandung"){
														$ident_where[$i_ident_where]["perbandingan"] = "LIKE";
														//echo "<br> ident_where_perbandingan[$i_ident_where] : ".$ident_where[$i_ident_where]["perbandingan"];
													}
													if (ord("$token_alias[$i_where]") == "38"){
														//echo "$token_alias[$i_where]";
														$ident_where[$i_ident_where]["isi"] = trim($token_alias[$i_where],',');
														//echo "<br> ident_Where_isi[$i_ident_where] : ".$ident_where[$i_ident_where]["isi"];
													}
													if ($i_ident_where > 1){
														if ($token_alias[$i_where] == "dan"){
															$ident_where[$i_ident_where]["logika"] = "AND";
															//echo "<br> ident_where_logika[$i_ident_where] : ".$ident_where[$i_ident_where]["logika"];
														} else if ($token_alias[$i_where] == "atau") {
															$ident_where[$i_ident_where]["logika"] = "OR";
															//echo "<br> ident_where_logika[$i_ident_where] : ".$ident_where[$i_ident_where]["logika"];
														}
													}
												}
												if (empty($ident_where[$i_ident_where]["logika"])){
													$ident_where[$i_ident_where]["logika"] = "AND";
													//echo "<br> ident_where_logika[$i_ident_where] : ".$ident_where[$i_ident_where]["logika"];
												}
												
												
											}
										}
									?>
									<table class="table table-hover table-bordered table-responsive">
										<thead>
											<tr>
												<th style="width:10%">Nama</th>
												<th>Objek</th>
											</tr>
										</thead>
										<tbody>	
											<tr>
												<td>[Masukan] Array Token Preprocessing</td>
												<td>
												<?php 
													$n_alias = count($token_alias);
													$n_ident_where = count($ident_where);
													for ($i_alias = 1; $i_alias <= $n_alias; $i_alias++){
														echo "Token $i_alias : $token_alias[$i_alias]";
														for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
															if($token_alias[$ident_where[$i_ident_where]["posisi"]] == $token_alias[$i_alias]){
																echo " => ".$token_alias[$ident_where[$i_ident_where]["posisi"]];
															}
														}
														echo "<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Kondisi</td>
												<td>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo " ident_where[$i_ident_where] : ".$token_alias[$ident_where[$i_ident_where]["posisi"]]."<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Operator Logika</td>
												<td>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_logika[$i_ident_where] : ".$ident_where[$i_ident_where]["logika"]."<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Tabel Kondisi</td>
												<td>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_tabel[$i_ident_where] : ".$ident_where[$i_ident_where]["table"]."<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Atribut Kondisi</td>
												<td>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_atribut[$i_ident_where] : ".$ident_where[$i_ident_where]["attribute"]."<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Operator Banding</td>
												<td>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_banding[$i_ident_where] : ".$ident_where[$i_ident_where]["perbandingan"]."<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Identifikasi Isi Kondisi</td>
												<td>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_isi[$i_ident_where] : ".$ident_where[$i_ident_where]["isi"]."<br>";
													}
												?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div id="query">
									<h4>Penyusunan Query</h4>
									<?php
									
										$query_part = array();
										$i_query=0;
										//Hasil Identifikasi Perintah
										if ($ident_perintah == "SELECT"){
											$i_query++;
											$query_part[$i_query] = $ident_perintah." ";
											//echo "query_part[$i_query] : $query_part[$i_query]<br>"; 
										}
										//Hasil Identifikasi Atribut
										$n_attr = count($ident_attribute);
										if ($n_attr >=1) {
											for ($i_attr = 1; $i_attr <= $n_attr; $i_attr++){
												if ($ident_attribute[$i_attr]["posisi"] < $ident_where[1]["posisi"]){
													$i_query++;
													$query_part[$i_query] = "`".$ident_attribute[$i_attr]["attribute"]."` ";
													//echo "query_part[$i_query] : $query_part[$i_query]<br>";
												}
											}
										}
										//Hasil Identifikasi Table
										$n_rel = count($ident_relasi);
										if ($n_rel >= 1){
											for ($i_rel = 1; $i_rel <= $n_rel; $i_rel++){
												$i_query++;
												$query_part[$i_query] = $ident_relasi[$i_rel]." ";
												//echo "query_part[$i_query] : $query_part[$i_query]<br>";
											}
										}									
										//Hasil Identifikasi WHERE
										$n_whr = count($ident_where);
										if ($n_whr >= 1){
											for ($i_whr = 1; $i_whr <= $n_whr; $i_whr++){
												if ($i_whr == 1){
													$i_query++;
													$query_part[$i_query] = "WHERE ";
													//echo "query_part[$i_query] : $query_part[$i_query]<br>";
												} else if($ident_where[$i_whr]["logika"] == "AND") {
													$i_query++;
													$query_part[$i_query] = " AND ";
													//echo "query_part[$i_query] : $query_part[$i_query]<br>";
												}
												
												if ($ident_where[$i_whr]["perbandingan"] == "="){
													if(empty($ident_where[$i_whr]["attribute"])){
														$ident_where[$i_whr]["attribute"] =  $attr_pilihan[$ident_where[$i_whr]["table"]][2]["nama"];
													}
													if (ord($ident_where[$i_whr]["isi"]) == "38"){
														$ident_where[$i_whr]["isi"] = trim(preg_replace('/[^A-Za-z0-9\  ]/', '',$ident_where[$i_whr]["isi"]),'quot');
													}
													$i_query++;
													$query_part[$i_query] = 
														"`".$ident_where[$i_whr]["table"]."`.`".$ident_where[$i_whr]["attribute"]."` "
														.$ident_where[$i_whr]["perbandingan"]." "
														.chr(39).$ident_where[$i_whr]["isi"].chr(39)
													;
													//echo "query_part[$i_query] : $query_part[$i_query]<br>";
												} else if ($ident_where[$i_whr]["perbandingan"] == "LIKE"){
													if (ord($ident_where[$i_whr]["isi"]) == "38"){
														$ident_where[$i_whr]["isi"] = trim(preg_replace('/[^A-Za-z0-9\  ]/', '',$ident_where[$i_whr]["isi"]),'quot');
													}
													$i_query++;
													$query_part[$i_query] = 
														"`".$ident_where[$i_whr]["table"]."`.`".$ident_where[$i_whr]["attribute"]."` "
														.$ident_where[$i_whr]["perbandingan"]." "
														.chr(39).chr(37).trim($ident_where[$i_whr]["isi"],'"').chr(37).chr(39)
													;
													//echo "query_part[$i_query] : $query_part[$i_query]<br>";
													//echo "<br>".str_replace('"',"a",$ident_where[$i_whr]["isi"]);
												}
											}
										}
										$i_query++;
													$query_part[$i_query] = ";";
										$code_query = join("",$query_part);
										//echo "<br>$code_query";
									?>
									<table class="table table-hover table-bordered table-responsive">
										<thead>
											<tr>
												<th style="width:10%">Nama</th>
												<th>Objek</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>[Masukan] Identifikasi Perintah</td>
												<td>
												<?php 
													echo "Ident_perintah : $ident_perintah";
												?>
												</td>
											</tr>
											<tr>
												<td>[Masukan] Identifikasi Tabel</td>
												<td>
												<?php 
													$n_ident_tbl = count($ident_table);
													for ($i_ident_tbl = 1; $i_ident_tbl <= $n_ident_tbl; $i_ident_tbl++){
														echo "ident_table[$i_ident_tbl] : $ident_table[$i_ident_tbl]<br>";
													}
												?>
												<?php 
													$n_ident_rel = count($ident_relasi);
													for ($i_ident_rel = 1; $i_ident_rel <= $n_ident_rel; $i_ident_rel++){
														echo "ident_relasi[$i_ident_rel] : $ident_relasi[$i_ident_rel]<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Masukan] Identifikasi Atribut</td>
												<td>
												<?php 
													$n_ident_attr = count($ident_attribute);
													for ($i_ident_attr = 1; $i_ident_attr <= $n_ident_attr; $i_ident_attr++){
														echo "ident_attribute[$i_ident_attr] : ".$ident_attribute[$i_ident_attr]["attribute"]."<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Masukan] Identifikasi Kondisi</td>
												<td>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo " ident_where[$i_ident_where] : ".$token_alias[$ident_where[$i_ident_where]["posisi"]]."<br>";
													}
												?>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_logika[$i_ident_where] : ".$ident_where[$i_ident_where]["logika"]."<br>";
													}
												?>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_tabel[$i_ident_where] : ".$ident_where[$i_ident_where]["table"]."<br>";
													}
												?>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_atribut[$i_ident_where] : ".$ident_where[$i_ident_where]["attribute"]."<br>";
													}
												?>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_banding[$i_ident_where] : ".$ident_where[$i_ident_where]["perbandingan"]."<br>";
													}
												?>
												<?php 
													$n_ident_where = count($ident_where);
													for ($i_ident_where = 1; $i_ident_where <= $n_ident_where; $i_ident_where++){
														echo "ident_where_isi[$i_ident_where] : ".$ident_where[$i_ident_where]["isi"]."<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Penyusunan Query</td>
												<td>
												<?php 
													$n_query = count($query_part);
													for ($i_query = 1; $i_query <= $n_query; $i_query++){
														echo "query_part[$i_query] : $query_part[$i_query]<br>";
													}
												?>
												</td>
											</tr>
											<tr>
												<td>[Keluaran] Hasil Query</td>
												<td>
												<?php 
													echo "$code_query";
												?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="testing">
								<h4>Pengujian Query</h4>
								<table class="table table-hover table-bordered table-responsive">
									<thead>
										<tr>
											<th style="width:10%">Nama</th>
											<th>Objek</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>[Masukan] Hasil Proses</td>
											<td>
											<?php 
												echo "$code_query";
											?>
											</td>
										</tr>
										<tr>
											<td>[Keluaran] Hasil Eksekusi</td>
											<td>
												<table class="table table-striped">
													<tr>
													<?php
														$n_attr=count($ident_attribute);
														if ($ident_attribute[1] == "*"){
															$n_atribut = count($attr_pilihan[$ident_table[1]]);
															for ($i = 1; $i <= $n_atribut; $i++) {
																?><th><?php echo $attr_pilihan[$ident_table[1]][$i]["nama"]; ?></th><?php
															}
														} else {
															for ($kt=1;$kt<=$n_attr;$kt++) {
																if ($ident_attribute[$kt]["posisi"] < $ident_where[1]["posisi"]){
																	?><th><?php echo $ident_attribute[$kt]["attribute"]; ?></th><?php
																}
															}
														}
													?></tr><?php
												$connect_db_pilih = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["db_pilih"]);
												//echo $_SESSION["servername"]." | ".$_SESSION["username"]." | ".$_SESSION["password"]." | ".$_SESSION["db_pilih"];
												// Check connection
												if (!$connect_db_pilih) {
													die("Connection failed: ".mysqli_connect_error());
												}
												$result_query = mysqli_query($connect_db_pilih, $code_query);
												if (mysqli_num_rows($result_query) > 0) {
													// output data of each row
													while($row = mysqli_fetch_assoc($result_query)) {
														?><tr><?php
														if ($ident_attribute[1] == "*"){
															
															$n_atribut = count($attr_pilihan[$ident_table[1]]);
															for ($i = 1; $i <= $n_atribut; $i++) {
																echo"<td>".$row[$attr_pilihan[$ident_table[1]][$i]["nama"]]."</td>";
															}
														} else {
															for ($kt=1;$kt<=$n_attr;$kt++){
																if ($ident_attribute[$kt]["posisi"] < $ident_where[1]["posisi"]){
																	echo"<td>".$row[$ident_attribute[$kt]["attribute"]]."</td>";
																}
															}
														}
														?></tr><?php
													}
												} else {
													?><td>0 results</td><?php
												}
												?>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<?php
						}
					?>
				</div>	
			</div>
		</div>
	</body>
</html> 