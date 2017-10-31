<?php

//Data Dumy Ideal
$string = "tampilkan nim dan nama dari mahasiswa";
$string = "tampilkan semua data mahasiswa";
$string = "tampilkan nim , nama dan alamat dari mahasiswa";

$table = array("mahasiswa","dosen");
$atribut = array("semua","nim","nama","alamat");

// Tahap 1.Preprocessing
// parameter = $string
// Proses pemotongan String berdasarkan spasi
$token = strtok($string, " ");
//Hasil
// Input Kalimat
echo "Kalimat perintah : <br> $string <br>";
// Token-token
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
echo "<br>";
// Tahap 2 identifikasi perintah dalam kalimat
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
				echo "Token sumber : $tokens[$j] => $kata_sumber<br>";
			}
			else if($tokens[$j] == "data")
			{
				$kata_sumber = "FROM";
				echo "Token sumber : $tokens[$j] => $kata_sumber<br>";
			}
		}
	}
}
echo "<br>";
// Tahap 3 identifikasi pkata kunci
$kunci_utama = array();
$kunci_tambahan = array();
$i = 0; $ku = 0; $kt = 0;
$count_table = count($table);
$count_atribut = count($atribut);
while ($i !== $count)
{
	$i++;
	for ($j = 0; $j < $count_table; $j++)
	{
		//echo "$tokens[$i] == $table[$j] | $atribut[$j]<br>";
		if ($tokens[$i] == $table[$j])
		{
			$kunci_utama[$ku] = $table[$j];
			echo "kata Kunci Utama : $kunci_utama[$ku]<br>";
			$ku++;
		}
	}
	for ($j = 0; $j < $count_atribut; $j++)
	{
		if ($tokens[$i] == $atribut[$j])
		{
			$kunci_tambahan[$kt] = $atribut[$j];
			echo "kata Kunci Tambahan : $kunci_tambahan[$kt]<br>";
			$kt++;
		}
	}
}
echo "<br>Hasil Translasi<br><br>";
$count_kunci_tambahan = count($kunci_tambahan);

echo "$kata_perintah ";
for ($i = 0;$i < $count_kunci_tambahan; $i++)
{
	if ($kunci_tambahan[$i] == "semua")
		echo "* ";
	else
	echo "$kunci_tambahan[$i] ";
	if ($i < ($count_kunci_tambahan-1))
	{
		echo ", ";
	}
}
echo "$kata_sumber ";
echo " $kunci_utama[0] ";

?>