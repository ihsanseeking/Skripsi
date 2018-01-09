<?php
	session_start();
	$_SESSION["db_pendukung"] = "db_translator"; 
?>
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
			if (!empty($_POST["kalimat"])) {
				$string = $_POST["kalimat"]; 
			} else {
				$string = "";
			}
		?>
		<?php
			if (!empty($_POST["tab"])) {
				$tab = $_POST["tab"]; 
			} else {
				$tab = "setup";
			}
		?>
		<div class="container">
			<h2>Translator</h2>
			<p>Perancangan Translator Bahasa Alami ke dalam format SQL yang berfokus pada DML :</p>
			<ul class="nav nav-tabs">
				<li class="<?php if($tab=='setup'){echo"active";}?>"><a data-toggle="tab" href="#setup">Setup</a></li>
				<li class="<?php if($tab=='info'){echo"active";}?>"><a data-toggle="tab" href="#info">Info Database</a></li>
				<li class="<?php if($tab=='menu1'){echo"active";}?>"><a data-toggle="tab" href="#menu1">Translator</a></li>
				<li class="<?php if($tab=='menu2'){echo"active";}?>"><a data-toggle="tab" href="#menu2">Detail Penelusuran</a></li>
			</ul>
			<div class="tab-content">
				<div id="setup" class="tab-pane fade <?php if ($tab == 'setup'){echo "in active";}?>">
					<h3>Setup</h3>
					<p></p>
					<hr>
					<h4>Connection</h4>
					<p>form untuk koneksi ke dbms.</p> 
					<form class="form-horizontal" action="Translator.php" method="post">
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
								<input name='kalimat' value="<?php echo $string; ?>" type='hidden'>
								<input name='tab' value="setup" type='hidden'>
								<button type="submit" class="btn btn-primary btn-md">Connect</button>
							</div>
						</div>
					</form>
					<?php
						if (!empty($_POST["servername"])) {
							$_SESSION["servername"] = $_POST["servername"]; 
							$_SESSION["username"] = $_POST["username"];
							$_SESSION["password"] = $_POST["password"];
						}
					?>
					<?php
						if (!empty($_SESSION["servername"])) {
							//echo $_SESSION["servername"];echo $_SESSION["username"];echo $_SESSION["password"];
							// Create connection
							$connect = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"]);
							// Check connection
							if (!$connect) {
								echo "<p class='text-danger'>";	die("Connection failed: " . mysqli_connect_error());echo "</p>";
							} else {
								echo "<p class='text-success'>Connected to server ".$_SESSION["servername"]." successfully </p>";
							}
						}
					?>
					<hr>
					<h4>Database</h4>
					<p>Pilih Analisis di Opsi untuk Menganalisis Database yang akan ingin digunakan.</p> 
					
					<?php
						if (!empty($_POST["analisis"])) {
							$db_analisis = $_POST["analisis"];
							//echo "ngeanalisis database $db_analisis"; 
							// Create connection to database
							$connect_db_p = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["db_pendukung"]);
							// Check connection
							if (!$connect_db_p) {die("Connection failed: " . mysqli_connect_error());}
							//echo "<p>Connected to database <b>".$_SESSION["db_pendukung"]."</b> successfully </p>";	
							//Simpan db ke db_pendukung
							$sql_database = "INSERT INTO `d_database` (`id`, `nama`) VALUES (NULL, '$db_analisis')";
							$result_database = mysqli_query($connect_db_p, $sql_database);
							if($result_database){
								echo"<p class='text-success'>Analisis Database <b>$db_analisis</b> Berhasil</p>";
							}
							//Temukan ID db_analisis baru
							$sql_database = "SELECT * FROM `d_database` WHERE `d_database`.`nama` = '$db_analisis'";
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
							echo "<p>Connected to database <b>$db_analisis</b> successfully </p>";	
							//lihat semua table di db_analisis
							$sql_database = "SHOW FULL TABLES";
							$result_database = mysqli_query($connect_db_a, $sql_database);
							if (mysqli_num_rows($result_database) > 0) {
								while($row_database = mysqli_fetch_assoc($result_database)) {
									$tbl_analisis = $row_database["Tables_in_$db_analisis"];
									$tbl_analisis_tipe = $row_database["Table_type"];
									//simpan table ke d_table
									$sql_table="INSERT INTO `d_table` (`id`, `id_database`, `nama`, `tipe`) VALUES (NULL, '$db_analisis_id', '$tbl_analisis', '$tbl_analisis_tipe')";
									//echo "$sql_table";
									$result_table = mysqli_query($connect_db_p, $sql_table);
									if($result_table){
										echo"<p class='text-success'>Analisis Tabel <b>$db_analisis.$tbl_analisis</b> Berhasil</p>";
									}
									//Temukan ID tbl_analisis baru
									$sql_table = "SELECT * FROM `d_table` WHERE `d_table`.`id_database` = '$db_analisis_id' AND `d_table`.`nama` = '$tbl_analisis'";
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
										$sql_part_attribute[$sql_i_attribute]="INSERT INTO `d_attribute` (`id`, `id_table`, `nama`, `tipe`) VALUES ";
										while($row_table = mysqli_fetch_assoc($result_table)) {
											$attr_analisis = $row_table["Field"];
											$attr_analisis_tipe = '"'.$row_table['Type'].'"';
											//echo "<br> $attr_analisis_tipe <br>";
											$sql_i_attribute++;
											$sql_part_attribute[$sql_i_attribute]="(NULL, '$tbl_analisis_id', '$attr_analisis', $attr_analisis_tipe)";
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
								
								/*
								// output data of each row
								//echo "Database <b>$db_analisis</b><br>";
								$i=0;
								$sql_part_table = array();
								$sql_i_table=0;
								$sql_part_table[$sql_i_table]="INSERT INTO `d_table` (`id`, `id_database`, `nama`, `tipe`) VALUES ";
								while($row = mysqli_fetch_assoc($result)) {
									$tbl_analisis = $row["Tables_in_$db_analisis"];
									$tbl_analisis_tipe = $row["Table_type"];
									$sql_i_table++;
									$sql_part_table[$sql_i_table]="(NULL, '$db_analisis_id', '$tbl_analisis', '$tbl_analisis_tipe')";
									$sql_i_table++;
									$sql_part_table[$sql_i_table]=",";
								}	
								$sql_part_table[$sql_i_table]=";";
								$sql_table = join("",$sql_part_table);
								echo "$sql_table";
								$result = mysqli_query($connect_db_p, $sql_table);
								if($result){
									echo"<p class='text-success'>Analisis Tabel <b>$db_analisis</b> Berhasil</p>";
								}	
								*/	
									/*$sql2 = "DESC $table";
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
									}*/
								
							} else {
								echo "0 results";
							}
							
						}
						
						if (!empty($_POST["hapus"])) {
							$db_hapus = $_POST["hapus"];
							$db_id_hapus = $_POST["id_hapus"];
							//echo "ngehapus database $db_hapus dengan id $db_id_hapus"; 
							// Create connection to database
							$connect_db_p = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["db_pendukung"]);
							// Check connection
							if (!$connect_db_p) {die("Connection failed: " . mysqli_connect_error());}
							//echo "<p>Connected to database <b>".$_SESSION["db_pendukung"]."</b> successfully </p>";	
							//Simpan db ke db_pendukung
							$sql = "DELETE FROM `d_database` WHERE `d_database`.`id` = $db_id_hapus";
							$result = mysqli_query($connect_db_p, $sql);
							if($result){
								echo"<p class='text-success'>Hapus Analisis Database <b>$db_hapus</b> berhasil</p>";
							}
						}
					?>
					
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Nama Database</th>
								<th>Status Analisis</th>
								<th>Opsi</th>
							</tr>
						</thead>
						<tbody>
						<?php
							// Create connection to database
							$connect_db_p = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"],$_SESSION["db_pendukung"]);
							// Check connection
							if (!$connect_db_p) {
								die("Connection to database pendukung failed: " . mysqli_connect_error());
							}
							//echo "<p>Connected to database <b>$db_pendukung</b> successfully </p>";
							
							$sql = "SELECT * FROM `d_database`";
							$result = mysqli_query($connect_db_p, $sql);
							if (mysqli_num_rows($result) > 0) {
								// output data of each row
								$no=0;
								while($row = mysqli_fetch_assoc($result)) {
									$no++;
									$d_database["id"][$no]=$row["id"];
									$d_database["nama"][$no]=$row["nama"];
									//echo "$no - ".$d_database["id"][$no]." - ".$d_database["nama"][$no]."<br>";
								}
							} else {
								$d_database="";
							}
							$sql = "SHOW DATABASES";
							$result = mysqli_query($connect, $sql);
							if (mysqli_num_rows($result) > 0) {
								// output data of each row
								$no=0;
								if (!empty($d_database["id"])) {
									$count_db = count($d_database["id"]);
								} else {$count_db = 0;}
								$db_nama="";
								$db_id="";
								//echo $d_database[$count_db];
								while($row = mysqli_fetch_assoc($result)) {
									$no++;
									?>
									<tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $row["Database"]; ?></td>
										<td>
										<?php
											for ($i=1; $i<=$count_db; $i++){
												if($row["Database"] == $d_database["nama"][$i]){
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
											if ($db_nama == $row["Database"]){
												?>
												<form class="form-horizontal" action="Translator.php" method="post">
													<input type="hidden" name='hapus' value="<?php echo $row["Database"]; ?>">
													<input type="hidden" name='id_hapus' value="<?php echo $db_id; ?>">
													<button type="submit" class="btn btn-danger">Hapus</button>
												</form>
												<?php
											} else {
												?>
												<form class="form-horizontal" action="Translator.php" method="post">
													<input type="hidden" name='analisis' value="<?php echo $row["Database"]; ?>">
													<button type="submit" class="btn btn-primary">Analisis</button>
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
					<h3>Analisis Database</h3>
					<p></p>
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
							</select>
						</div>
							<?php
							} else {
								echo "0 results";
							}
							?>
						<div class="form-group">
							<input name='kalimat' value="<?php echo $string; ?>" type='hidden'>
							<input name='tab' value="info" type='hidden'>
							<input value="kirim" type="submit"  class="btn btn-primary btn-md">
						</div>
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
							
						$sql = "SHOW FULL TABLES";
						$result = mysqli_query($connd, $sql);

						if (mysqli_num_rows($result) > 0) {
							// output data of each row
							//echo "Database <b>$dbname</b><br>";
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
					
					?>
					<h4> Analisis Tabel dan Atribut </h4>
					<hr>
					<div class="row">		
					<?php
					for($i=0; $i<$i2; $i++){
					?>
						<div class="col-sm-4">
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th><?php echo "Table Base [".$btables[$i]."]"; ?></th>
											<th>AS</th>
											<th>
												<textarea class="form-control" rows="1" id="comment"><?php echo $btables[$i]; ?></textarea>
											</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$c_attr = count($atributs[$btables[$i]]['field']);
									for($j=0; $j<$c_attr; $j++){
									?>
										<tr>
											<td>
												<?php echo $atributs[$btables[$i]]['field'][$j]; ?>
												<?php echo $atributs[$btables[$i]]['type'][$j]; ?>
												<?php echo $atributs[$btables[$i]]['key'][$j]; ?>
											</td>
											<td>AS</td>
											<td>
												<textarea class="form-control" rows="1" id="comment"><?php echo $atributs[$btables[$i]]['field'][$j]; ?></textarea>
											</td>
										</tr>
									<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					}
					for($i=0; $i<$i1; $i++){
					?>
						<div class="col-sm-4">
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th><?php echo "Table View [".$vtables[$i]."]"; ?></th>
											<th>AS</th>
											<th>
												<textarea class="form-control" rows="1" id="comment"><?php echo $btables[$i]; ?></textarea>
											</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$c_attr = count($atributs[$vtables[$i]]['field']);
									for($j=0; $j<$c_attr; $j++){
									?>
										<tr>
											<td>
												<?php echo $atributs[$vtables[$i]]['field'][$j]; ?>
												<?php echo $atributs[$vtables[$i]]['type'][$j]; ?>
												<?php echo $atributs[$vtables[$i]]['key'][$j]; ?>
											</td>
											<td>AS</td>
											<td>
												<textarea class="form-control" rows="1" id="comment"><?php echo $atributs[$vtables[$i]]['field'][$j]; ?></textarea>
											</td>
										</tr>
									<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					}
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
					
					?>
					</div>
					<hr>
					<h4> Analisis Relasi </h4>
					<hr>
					<div class="row">		
					<?php
					for($i=0; $i<$i2; $i++){
					?>
						<div class="col-sm-4">
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th><?php echo "Table [".$btables[$i]."]"; ?></th>
										</tr>
									</thead>
									<tbody>
									<?php
									$c_relasi = count($relasi[$btables[$i]]['tab']);
									for($j=0; $j<$c_relasi; $j++){
									?>
										<tr>
											<td>
												<?php echo $relasi[$btables[$i]]['tab'][$j]; ?>
												(<?php echo $relasi[$btables[$i]]['attr'][$j]; ?>)
												<?php echo $relasi[$btables[$i]]['car'][$j]; ?>
											</td>
										</tr>
									<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					}
					
					?>
					</div>
				</div>
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
				<?php
					if ($tab == 'menu1'){
					?>
				<div id="menu1" class="tab-pane fade in active">
					<?php
					} else {
					?>
				<div id="menu1" class="tab-pane fade">
					<?php
					}
				?>
					<h3>Translator</h3>
					<p></p>
					
					<form action="Translator.php" method="post">
			<p><textarea class="form-control" name="kalimat" rows="4" cols="50" placeholder="Kalimat Perintah"><?php echo $string; ?></textarea></p>
			<input name="database" value="<?php echo $dbname; ?>" type="hidden">
			<input name='tab' value="menu1" type='hidden'>
			<p><input value="kirim" type="submit" class="btn btn-primary btn-md"></p>
		</form>
		<?php
			if (!empty($_POST["kalimat"])) 
			{
				$string = $_POST["kalimat"]; 
				?>
				<!--<p>Kalimat Perintah : </p>
				<p> <?php //echo$string; ?></p>
			   
				<p><b>Tahap 1. Preprocessing</b></p>-->
				<?php
					// parameter = $string
					// Proses pemotongan String berdasarkan spasi
					$token = strtok($string, " ");
					//echo "Token-Token : <br>";
					$i = 0;
					$tokens = array();
					while ($token !== false)
					{
						$i++;
						//echo "Token $i : $token<br>";
						// masukan token kedalam array
						$tokens[$i] = $token;
						$token = strtok(" ");
						//echo "Token $i : $tokens[$i]<br>";
					} 
				?>
				<!--<p><b>Tahap 2. Proses translator Kalimat</b></p>
				<p><u>Tahap 2.a. Identifikasi Perintah DML</u></p>-->
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
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "tampilkanlah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "menampilkan")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "siapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "siapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "siapakah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "cari")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "carikan")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "berapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "berapakah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "tambah")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "tambahkan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "masukan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "isikan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "ubah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "ubahlah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "mengubah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "ganti")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "gantilah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "gantikan")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "hapus")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "hapuslah")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "menghapus")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "delete")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "buang")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "kurangi")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							//echo "DML : $tokens[$i] => $kata_perintah<br>";
						} 
					}
				?> 
				<!--<p><u>Tahap 2.b. Identifikasi Nama Tabel</u></p>
				<p><i>Tahap 2.b.i Nama Tabel</i></p>-->
				<?php
					$nama_table = array();
					$i = 0; $nt = 0; $ct = 0;
					$nama_table[$nt] = "Tidak ada nama tabel";
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
									$sama=false;
									for ($ct = 0; $ct < $nt; $ct++) {
										//cek bisi ada token yang sama dengan nama_tabel yg sudah di daftarkan
										if($tokens[$i] == $nama_table[$ct]){
											//echo " nemu nama tabel yg sama [$nama_table[$ct]] <br>";
											$sama=true;	
										} 
									}
									if (!$sama) {
										$nama_table[$nt] = $tables[$j];
										//echo "nama tabel[$nt] : $nama_table[$nt]<br>";
										$nt++;
									}
								} else {
									$nama_table[$nt] = $tables[$j];
									//echo "nama tabel[$nt] : $nama_table[$nt]<br>";
									$nt++;
								}
							}
						}
					}
					//echo "<p>jumlah $nt </p>";
				?>
				<!--<p><i>Tahap 2.b.ii Relasi Tabel</i></p>-->
				<?php
					//bismillah
					$tr=0;
					$tabel_relasi[0]=$nama_table[0];
					//echo "Tabel FROM => [$tabel_relasi[0]]<br>";
					$c_relasi = count($relasi[$tabel_relasi[0]]['tab']);
					for($j=0; $j<$c_relasi; $j++){// ngulang sebanyak relasi yg dimiliki tabel from	
						for($ii=0; $ii<$nt; $ii++){// ngulang sejumlah nama tabel yg terdetkesi di kuriangi 1
							if ($nama_table[$ii] != $tabel_relasi[0]) {// jika tida sama dengan table from maka cek
								//echo "<br>$j. [".$relasi[$tabel_relasi[0]]['tab'][$j]."] ";
								//echo "dengan [$nama_table[$ii]] ";
								if ($relasi[$tabel_relasi[0]]['tab'][$j] == $nama_table[$ii]) {
									//echo " = Sama ";// jika sama maka selesai 1
									$tr++;
									$tabel_relasi[$tr] = $nama_table[$ii];
									$attr_relasi[$tr] = $relasi[$tabel_relasi[0]]['attr'][$j];
									//echo "JOIN $tabel_relasi[$tr] USING ($attr_relasi[$tr])<br>";
								
								} else {
									//echo " = Beda ";// jika tidak sama maka cek ke langkang berikutya yaitu cek dengan relasi dengan relasi lagi.
									//echo "<br> - karena tidak sama maka bandingkan dengan nama relasinya";
									$c_relasi2 = count($relasi[$nama_table[$ii]]['tab']); //hitung relasi milik nama tabel yg di scan
									//echo "<br> relasi tabel milik $nama_table[$ii]] ada ($c_relasi2) yaitu : ";
									for($jj=0; $jj<$c_relasi2; $jj++){
										//echo "<br>$jj. [".$relasi[$nama_table[$ii]]['tab'][$jj]."]";
										//echo " bandingkan dengan [".$relasi[$tabel_relasi[0]]['tab'][$j]."]";
										if ($relasi[$nama_table[$ii]]['tab'][$jj] == $relasi[$tabel_relasi[0]]['tab'][$j]){
											//echo " == Sama ";
											$tr++;
											$tabel_relasi[$tr] = $relasi[$tabel_relasi[0]]['tab'][$j];
											$attr_relasi[$tr] = $relasi[$tabel_relasi[0]]['attr'][$j];
											//echo "JOIN $tabel_relasi[$tr] USING ($attr_relasi[$tr])<br>";
											$tr++;
											$tabel_relasi[$tr] = $nama_table[$ii];
											$attr_relasi[$tr] = $relasi[$nama_table[$ii]]['attr'][$jj];
											//echo "JOIN $tabel_relasi[$tr] USING ($attr_relasi[$tr])<br>";
										} else {
											//echo " == Beda ";
										}
									}
								}
								
							}
						}
						
					}
				?>				
				<!--<p><u>Tahap 2.c. Identifikasi Syarat Kondisi</u></p>-->
				<?php
					$kata_kondisi[0] = "tidak teridentifikasi";
					$t = 0;
					$nk = 0;
					$s_2c = 0;
					$p_kondisi=$count;
					while ($t !== $count) {
						$t++;
						//echo "Token $t : $tokens[$t]<br>";
						if ($tokens[$t] == "yang") {
							$kata_kondisi[$nk] = $tokens[$t];
							$s_2c = 1;
							//echo "Kata Kondisi[$nk] : $kata_kondisi[$nk]<br>";
							$p_kondisi=$t; //echo "p kondisi ($t)";
							$nk++;
							//break;
						} else if ($tokens[$t] == "dimana") {
							$kata_kondisi[$nk] = $tokens[$t];
							//$s_2c = 1;
							//echo "Kata Kondisi[$nk] : $kata_kondisi[$nk]<br>";
							$p_kondisi=$t; //echo "p kondisi ($t)";
							$nk++;
							//break;
						}
					}
					
					//Cari Operator Logika
					If ($s_2c == 1){
						$t=$p_kondisi;
						while ($t !== $count) {
							$t++;
							//echo "Token $t : $tokens[$t]<br>";
							if($tokens[$t] == "dan") {
								$kata_kondisi[$nk] = "AND";
								$nk++;
								//echo "Operator Logika[$nk] : $kata_kondisi[$nk]<br>";
							} else if($tokens[$t] == "atau") {
								$kata_kondisi[$nk] = "OR";
								$nk++;
								//echo "Operator Logika[$nk] : $kata_kondisi[$nk]<br>";
							}
						}
					}
				
				?> 
				<!--<p><u>Tahap 2.d. Identifikasi Nama atribut</u></p>-->
				
				<?php
				//cek dulu soalnya apakaha atribut selalu sebelum "yang"
					//nyari atribut dari tabel
					$na=0;
					for($i_nt=0; $i_nt<$nt; $i_nt++){
						$i2=0;
						
						$c_atribut = count($atributs[$nama_table[$i_nt]]['field']);
						//echo "$i_nt --- $c_atribut";
						//echo "<p>jumlah $c_atribut</p>";
						//looping sebanyak token
						while ($i2 !== $p_kondisi) {
							$i2++;
							//echo "-$i2-";
							//looping sebanyak atribut table terpilih
							for ($j2 = 0; $j2 < $c_atribut; $j2++)
							{
								//echo "kata kunci atribut : $tokens[$i2]<br>";
								//echo " - ".$tokens[$i2]." == ".$atributs[$nama_table[$i_nt]]['field'][$j2]."<br>";
								if ($tokens[$i2] == $atributs[$nama_table[$i_nt]]['field'][$j2]) {
									
									$nama_atribut[$na] = "$tokens[$i2]";// $nama_table[$i_nt]";
									//echo "nama atribut[$na] : ".$nama_atribut[$na]."<br>";
									$na++;
									
								}
							}
						}
						
					}
					if($na < 1){
							$nama_atribut[$na] = "*";
							$na++;
							
						}
					//echo "sementara atributnya * dulu";
				?>
				<!--<p><u>Tahap 2.e. Penyusunan Query</u></p>-->
				<h3>Hasil</h3>
				<?php
					$sql_part = array();
					$nsql=0;
					if ($s_2a > 0) {
						//echo "<br>Ada Kata Perintah => ".$kata_perintah;
						$sql_part[$nsql]=$kata_perintah." ";
						$nsql++;
					}
					if ($na > 0) {
						for ($i = 0; $i < $na; $i++) {
							//echo "<br>Ada nama atribut[$i] => ".$nama_atribut[$i];
							$sql_part[$nsql]=$nama_atribut[$i]." ";
							$nsql++;
							if ($i < ($na-1))
							{
								$sql_part[$nsql] = ", ";
								$nsql++;
							}
						}
					}
					if ($tr > 0) {
						//echo "<br>Ada nama Tabel[0] => $tabel_relasi[0]";
						$sql_part[$nsql]="FROM ";
						$nsql++;
						$sql_part[$nsql]=$tabel_relasi[0]." ";
						$nsql++;
						if ($tr > 1) {
							for ($i = 1; $i <= $tr; $i++) {
								//echo "<br>Ada Join Tabel[$i] => $tabel_relasi[$i] (Using) $attr_relasi[$i]";
								$sql_part[$nsql]="JOIN ";
								$nsql++;
								$sql_part[$nsql]=$tabel_relasi[$i]." ";
								$nsql++;
								$sql_part[$nsql]="USING ";
								$nsql++;
								$sql_part[$nsql]="(".$attr_relasi[$i].") ";
								$nsql++;
							}
						}
					}
					//if ($s_2c > 0) {
						//echo "<br>Ada Kata kondisi => ".$kata_kondisi;
						//$sql_part[$nsql]="WHERE ";
						//$nsql++;
					//}
					//$sql_part[$nsql]=";";
					//$nsql++;
					$codesql = join("",$sql_part);
					
					echo "<b><p>$codesql</p></b>";
					
				?>
				<!--<p><b>Tahap 3. Pengujian Query</b></p>-->
				<?php
					//Eksekusi
					$result = mysqli_query($connd, $codesql);
					if (mysqli_num_rows($result) > 0) {
						// output data of each row
						// echo "Database <b>$dbname</b><br>";
						
						$c_attrb=count($nama_atribut);
						?>
						<div class="table-responsive">
						<table class="table table-striped">
						<tr>
						<?php
						//echo "<table border=1><tr>";
						if ($nama_atribut[0] == "*"){
							$c_atribut = count($atributs[$nama_table[0]]['field']);
							for ($i = 0; $i < $c_atribut; $i++) {
	
								echo"<th>".$atributs[$nama_table[0]]['field'][$i]."</th>";
							}
						} else {
							for ($kt=0;$kt<$c_attrb;$kt++) {
								echo"<th>$nama_atribut[$kt]</th>";
							}
						}
						echo "</tr>";
						while($row = mysqli_fetch_assoc($result)) {
							echo "<tr>";
							if ($nama_atribut[0] == "*"){
								$c_atribut = count($atributs[$nama_table[0]]['field']);
								for ($i = 0; $i < $c_atribut; $i++) {
									echo"<td>".$row[$atributs[$nama_table[0]]['field'][$i]]."</td>";
								}
							} else {
								for ($kt=0;$kt<$c_attrb;$kt++)
								{
									echo"<td>".$row[$nama_atribut[$kt]]."</td>";
								}
							}
							echo"</tr>";
						}
						echo "</table></div>";
					} else {
						echo "0 results";
					}
					
				?>
				<?php
			}
		?>
					
				</div>
				
				<?php
					if ($tab == 'menu2'){
					?>
				<div id="menu2" class="tab-pane fade in active">
					<?php
					} else {
					?>
				<div id="menu2" class="tab-pane fade">
					<?php
					}
				?>
				<div id="menu2" class="tab-pane fade">
					<h3>Penelusuran</h3>
					<p></p>
					
					<form action="Translator.php" method="post">
			<p><textarea class="form-control" name="kalimat" rows="4" cols="50" placeholder="Kalimat Perintah"><?php echo $string; ?></textarea></p>
			<input name="database" value="<?php echo $dbname; ?>" type="hidden">
			<p><input value="kirim" type="submit" class="btn btn-primary btn-md"></p>
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
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "tampilkanlah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "menampilkan")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "siapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "siapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "siapakah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "cari")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "carikan")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "berapa")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "berapakah")  {
							$kata_perintah = "SELECT";
							$s_2a = 1;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "tambah")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "tambahkan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "masukan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "isikan")  {
							$kata_perintah = "INSERT";
							$s_2a = 2;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "ubah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "ubahlah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "mengubah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "ganti")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "gantilah")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "gantikan")  {
							$kata_perintah = "UPDATE";
							$s_2a = 3;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "hapus")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "hapuslah")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "menghapus")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "delete")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "buang")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} else if ($tokens[$i] == "kurangi")  {
							$kata_perintah = "DELETE";
							$s_2a = 4;
							echo "DML : $tokens[$i] => $kata_perintah<br>";
						} 
					}
				?> 
				<p><u>Tahap 2.b. Identifikasi Nama Tabel</u></p>
				<p><i>Tahap 2.b.i Nama Tabel</i></p>
				<?php
					$nama_table = array();
					$i = 0; $nt = 0; $ct = 0;
					$nama_table[$nt] = "Tidak ada nama tabel";
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
									$sama=false;
									for ($ct = 0; $ct < $nt; $ct++) {
										//cek bisi ada token yang sama dengan nama_tabel yg sudah di daftarkan
										if($tokens[$i] == $nama_table[$ct]){
											//echo " nemu nama tabel yg sama [$nama_table[$ct]] <br>";
											$sama=true;	
										} 
									}
									if (!$sama) {
										$nama_table[$nt] = $tables[$j];
										echo "nama tabel[$nt] : $nama_table[$nt]<br>";
										$nt++;
									}
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
				<p><i>Tahap 2.b.ii Relasi Tabel</i></p>
				<?php
					//bismillah
					$tr=0;
					$tabel_relasi[0]=$nama_table[0];
					echo "Tabel FROM => [$tabel_relasi[0]]<br>";
					$c_relasi = count($relasi[$tabel_relasi[0]]['tab']);
					for($j=0; $j<$c_relasi; $j++){// ngulang sebanyak relasi yg dimiliki tabel from	
						for($ii=0; $ii<$nt; $ii++){// ngulang sejumlah nama tabel yg terdetkesi di kuriangi 1
							if ($nama_table[$ii] != $tabel_relasi[0]) {// jika tida sama dengan table from maka cek
								//echo "<br>$j. [".$relasi[$tabel_relasi[0]]['tab'][$j]."] ";
								//echo "dengan [$nama_table[$ii]] ";
								if ($relasi[$tabel_relasi[0]]['tab'][$j] == $nama_table[$ii]) {
									//echo " = Sama ";// jika sama maka selesai 1
									$tr++;
									$tabel_relasi[$tr] = $nama_table[$ii];
									$attr_relasi[$tr] = $relasi[$tabel_relasi[0]]['attr'][$j];
									echo "JOIN $tabel_relasi[$tr] USING ($attr_relasi[$tr])<br>";
								
								} else {
									//echo " = Beda ";// jika tidak sama maka cek ke langkang berikutya yaitu cek dengan relasi dengan relasi lagi.
									//echo "<br> - karena tidak sama maka bandingkan dengan nama relasinya";
									$c_relasi2 = count($relasi[$nama_table[$ii]]['tab']); //hitung relasi milik nama tabel yg di scan
									//echo "<br> relasi tabel milik $nama_table[$ii]] ada ($c_relasi2) yaitu : ";
									for($jj=0; $jj<$c_relasi2; $jj++){
										//echo "<br>$jj. [".$relasi[$nama_table[$ii]]['tab'][$jj]."]";
										//echo " bandingkan dengan [".$relasi[$tabel_relasi[0]]['tab'][$j]."]";
										if ($relasi[$nama_table[$ii]]['tab'][$jj] == $relasi[$tabel_relasi[0]]['tab'][$j]){
											//echo " == Sama ";
											$tr++;
											$tabel_relasi[$tr] = $relasi[$tabel_relasi[0]]['tab'][$j];
											$attr_relasi[$tr] = $relasi[$tabel_relasi[0]]['attr'][$j];
											echo "JOIN $tabel_relasi[$tr] USING ($attr_relasi[$tr])<br>";
											$tr++;
											$tabel_relasi[$tr] = $nama_table[$ii];
											$attr_relasi[$tr] = $relasi[$nama_table[$ii]]['attr'][$jj];
											echo "JOIN $tabel_relasi[$tr] USING ($attr_relasi[$tr])<br>";
										} else {
											//echo " == Beda ";
										}
									}
								}
								
							}
						}
						
					}
				?>				
				<p><u>Tahap 2.c. Identifikasi Syarat Kondisi</u></p>
				<?php
					$kata_kondisi[0] = "tidak teridentifikasi";
					$t = 0;
					$nk = 0;
					$s_2c = 0;
					$p_kondisi=$count;
					while ($t !== $count) {
						$t++;
						//echo "Token $t : $tokens[$t]<br>";
						if ($tokens[$t] == "yang") {
							$kata_kondisi[$nk] = $tokens[$t];
							$s_2c = 1;
							echo "Kata Kondisi[$nk] : $kata_kondisi[$nk]<br>";
							$p_kondisi=$t; //echo "p kondisi ($t)";
							$nk++;
							//break;
						} else if ($tokens[$t] == "dimana") {
							$kata_kondisi[$nk] = $tokens[$t];
							//$s_2c = 1;
							echo "Kata Kondisi[$nk] : $kata_kondisi[$nk]<br>";
							$p_kondisi=$t; //echo "p kondisi ($t)";
							$nk++;
							//break;
						}
					}
					
					//Cari Operator Logika
					If ($s_2c == 1){
						$t=$p_kondisi;
						while ($t !== $count) {
							$t++;
							//echo "Token $t : $tokens[$t]<br>";
							if($tokens[$t] == "dan") {
								$kata_kondisi[$nk] = "AND";
								$nk++;
								echo "Operator Logika[$nk] : $kata_kondisi[$nk]<br>";
							} else if($tokens[$t] == "atau") {
								$kata_kondisi[$nk] = "OR";
								$nk++;
								echo "Operator Logika[$nk] : $kata_kondisi[$nk]<br>";
							}
						}
					}
				
				?> 
				<p><u>Tahap 2.d. Identifikasi Nama atribut</u></p>
				
				<?php
				//cek dulu soalnya apakaha atribut selalu sebelum "yang"
					//nyari atribut dari tabel
					$na=0;
					for($i_nt=0; $i_nt<$nt; $i_nt++){
						$i2=0;
						
						$c_atribut = count($atributs[$nama_table[$i_nt]]['field']);
						//echo "$i_nt --- $c_atribut";
						//echo "<p>jumlah $c_atribut</p>";
						//looping sebanyak token
						while ($i2 !== $p_kondisi) {
							$i2++;
							//echo "-$i2-";
							//looping sebanyak atribut table terpilih
							for ($j2 = 0; $j2 < $c_atribut; $j2++)
							{
								//echo "kata kunci atribut : $tokens[$i2]<br>";
								//echo " - ".$tokens[$i2]." == ".$atributs[$nama_table[$i_nt]]['field'][$j2]."<br>";
								if ($tokens[$i2] == $atributs[$nama_table[$i_nt]]['field'][$j2]) {
									
									$nama_atribut[$na] = "$tokens[$i2]";// $nama_table[$i_nt]";
									echo "nama atribut[$na] : ".$nama_atribut[$na]."<br>";
									$na++;
									
								}
							}
						}
						
					}
					if($na < 1){
							$nama_atribut[$na] = "*";
							$na++;
							
						}
					//echo "sementara atributnya * dulu";
				?>
				<p><u>Tahap 2.e. Penyusunan Query</u></p>
				<?php
					$sql_part = array();
					$nsql=0;
					if ($s_2a > 0) {
						echo "<br>Ada Kata Perintah => ".$kata_perintah;
						$sql_part[$nsql]=$kata_perintah." ";
						$nsql++;
					}
					if ($na > 0) {
						for ($i = 0; $i < $na; $i++) {
							echo "<br>Ada nama atribut[$i] => ".$nama_atribut[$i];
							$sql_part[$nsql]=$nama_atribut[$i]." ";
							$nsql++;
							if ($i < ($na-1))
							{
								$sql_part[$nsql] = ", ";
								$nsql++;
							}
						}
					}
					if ($tr > 0) {
						echo "<br>Ada nama Tabel[0] => $tabel_relasi[0]";
						$sql_part[$nsql]="FROM ";
						$nsql++;
						$sql_part[$nsql]=$tabel_relasi[0]." ";
						$nsql++;
						if ($tr > 1) {
							for ($i = 1; $i <= $tr; $i++) {
								echo "<br>Ada Join Tabel[$i] => $tabel_relasi[$i] (Using) $attr_relasi[$i]";
								$sql_part[$nsql]="JOIN ";
								$nsql++;
								$sql_part[$nsql]=$tabel_relasi[$i]." ";
								$nsql++;
								$sql_part[$nsql]="USING ";
								$nsql++;
								$sql_part[$nsql]="(".$attr_relasi[$i].") ";
								$nsql++;
							}
						}
					}
					//if ($s_2c > 0) {
						//echo "<br>Ada Kata kondisi => ".$kata_kondisi;
						//$sql_part[$nsql]="WHERE ";
						//$nsql++;
					//}
					//$sql_part[$nsql]=";";
					//$nsql++;
					$codesql = join("",$sql_part);
					
					echo "<br><br><b><p>$codesql</p></b>";
					
				?>
				<p><b>Tahap 3. Pengujian Query</b></p>
				<?php
					//Eksekusi
					$result = mysqli_query($connd, $codesql);
					if (mysqli_num_rows($result) > 0) {
						// output data of each row
						// echo "Database <b>$dbname</b><br>";
						
						$c_attrb=count($nama_atribut);
						?>
						<div class="table-responsive">
						<table class="table table-striped">
						<tr>
						<?php
						//echo "<table border=1><tr>";
						if ($nama_atribut[0] == "*"){
							$c_atribut = count($atributs[$nama_table[0]]['field']);
							for ($i = 0; $i < $c_atribut; $i++) {
	
								echo"<th>".$atributs[$nama_table[0]]['field'][$i]."</th>";
							}
						} else {
							for ($kt=0;$kt<$c_attrb;$kt++) {
								echo"<th>$nama_atribut[$kt]</th>";
							}
						}
						echo "</tr>";
						while($row = mysqli_fetch_assoc($result)) {
							echo "<tr>";
							if ($nama_atribut[0] == "*"){
								$c_atribut = count($atributs[$nama_table[0]]['field']);
								for ($i = 0; $i < $c_atribut; $i++) {
									echo"<td>".$row[$atributs[$nama_table[0]]['field'][$i]]."</td>";
								}
							} else {
								for ($kt=0;$kt<$c_attrb;$kt++)
								{
									echo"<td>".$row[$nama_atribut[$kt]]."</td>";
								}
							}
							echo"</tr>";
						}
						echo "</table></div>";
					} else {
						echo "0 results";
					}
					
				?>
				<?php
			}
		?>
					
				</div>
			</div>
		</div>
	</body>
</html> 