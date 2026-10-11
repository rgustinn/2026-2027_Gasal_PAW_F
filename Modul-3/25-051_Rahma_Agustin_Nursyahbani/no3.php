<?php 
// 3.1
$height = array("Andy"=>"176","Barry"=>"165","Charlie"=>"170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

$key = array_key_last($height);

print_r($height);
echo "<br>";
echo "Nilai dengan indeks terakhir: " . $height[$key] . "<br>";

unset($height["David"]);

print_r($height);
echo "<br>";
echo "Nilai dengan indeks terakhir setelah dihapus : " . $height[$key] . "<br>";
echo "<br>";

// 3.2
$weight = array("Andy"=>"70","Barry"=>"65","Charlie"=>"75");

print_r($weight);
echo "<br>";

$value = array_values($weight);
echo "Nilai indeks kedua: ".$value[1];
 ?>