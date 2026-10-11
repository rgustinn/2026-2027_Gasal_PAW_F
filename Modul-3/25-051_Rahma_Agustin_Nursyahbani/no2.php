<?php
// 2.1
$fruits = array("Avocado","Blueberry","Cherry");

for($i = 1; $i <= 5; $i++){
	array_push($fruits, "Buah tambahan " . $i);
}
$arrlength = count($fruits);
echo "panjang array saat ini: " . $arrlength . "<br>";

for($x = 0; $x < $arrlength;$x++){
	echo $fruits[$x];
	echo "<br>";
}

echo "<br>";
echo "<br>";

//2.2
$vegies = array("Carrot", "Broccoli", "Spinach");
$vegies2 = count($vegies);
for($x = 0; $x < $vegies2; $x++){
	echo $vegies[$x];
	echo "<br>";
}
?>