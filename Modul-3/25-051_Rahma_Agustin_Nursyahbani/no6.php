<?php 
$array1 = ["A"];
echo "Array awal: ($array1[0]) <br>";
$push = array_push($array1, "B");
echo "Hasil array_push: $array1[0], $array1[1] <br><br>";

$array2 = ["C"];
$merge = array_merge($array1, $array2);
echo "Array awal: ($array1[0], $array1[1]) digabung dengan ($array2[0]) <br>";
echo "Hasil array_merge: $merge[0], $merge[1], $merge[2] <br><br>";

$array3 = ["x"=>1, "y"=>2];
echo 'Array awal: ("x" => ' . $array3["x"] . ', "y" => ' . $array3["y"] . ') <br>';
$arrvalue = array_values($array3);
echo "Hasil array_values: $arrvalue[0] $arrvalue[1] <br><br>";

echo "Mencari $merge[1] pada array: ($merge[0], $merge[1], $merge[2]) <br>";
$search = array_search("B", $merge);
echo "Hasil array_search: $search <br><br>";

$array5 = [0, 1, false, 2, "", 3, "array"];
$filter =  array_filter($array5);
echo 'Array awal: (' . $array5[0] . ', ' . $array5[1] . ', false, ' . $array5[3] . ', "", ' . $array5[5] . ', "' . $array5[6] . '")<br>';
echo "Hasil array_filter: " . implode(" ", $filter) . "<br><br>";

$array6= [3, 1, 2];
$sort = $array6;
sort($sort);
$rsort = $array6;
rsort($rsort);
echo "Array awal: ($array6[0], $array6[1], $array6[2]) <br>";
echo "Hasil sort: " . implode(" ", $sort) . " <br>";
echo "Hasil rsort: " . implode(" ", $rsort) . " <br><br>";

$array7 = array("Peter" => 35, "Ben" => 37, "Joe" => 43);

echo "Array awal: ";
print_r($array7);
echo "<br>";

$asort = $array7;
asort($asort);
echo "Hasil asort: ";
print_r($asort);
echo "<br>";

$ksort = $array7;
ksort($ksort);
echo "Hasil ksort: ";
print_r($ksort);
echo "<br>";

$arsort = $array7;
arsort($arsort);
echo "Hasil arsort: ";
print_r($arsort);
echo "<br>";

$krsort = $array7;
krsort($krsort);
echo "Hasil krsort: ";
print_r($krsort);
?>
