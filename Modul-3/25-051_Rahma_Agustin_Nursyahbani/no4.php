<?php
// 4.1 
$height = array("Andy"=>"176","Barry"=>"165","Charlie"=>"170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

echo "height = ";
print_r($height);
echo "<br>";

foreach ($height as $nama => $tinggi) {
    echo $nama . " is " . $tinggi . " cm tall.<br>";
}
echo "<br>";

// 4.2 
$weight = array("Andy"=>"70","Barry"=>"65","Charlie"=>"75");

echo "weight = ";
print_r($weight);
echo "<br>";

foreach ($weight as $nama => $berat) {
    echo $nama . " is " . $berat . " kg." . "<br>";
}
?>