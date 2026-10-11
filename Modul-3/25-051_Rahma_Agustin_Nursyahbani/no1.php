<?php 
//1.1
$fruits = array("Avocado","Blueberry","Cherry");
array_push($fruits, "Durian","Elderberry","Fig","Grape","Honeydew");
echo "fruits = " . implode(", ", $fruits) . "<br>";

$indeksTertinggi = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: ".$fruits[$indeksTertinggi] . "<br>";
echo "<br>"; 

//1.2
echo "Data " . $fruits[1] . " dihapus" . "<br>";
unset($fruits[1]);
echo "fruits = " . implode(", ", $fruits) . "<br>";
echo "Nilai dengan indeks tertinggi: ".$fruits[$indeksTertinggi];
 ?>