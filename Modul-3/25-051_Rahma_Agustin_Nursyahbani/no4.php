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

foreach ($height as $name => $tall) {
    echo $name . " is " . $tall . " cm tall.<br>";
}
echo "<br>";

// 4.2 
$weight = array("Andy"=>"176","Barry"=>"165","Charlie"=>"170");

echo "weight = ";
print_r($weight);
echo "<br>";

foreach ($weight as $name => $tall) {
    echo $name . " is " . $tall . "<br>";
}
?>