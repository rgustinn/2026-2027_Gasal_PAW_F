<?php
$siswa = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665")
);

echo "Data awal:<br>";
echo "students = (<br>";
foreach ($siswa as $row) {
    echo '("' . $row[0] . '", "' . $row[1] . '", "' . $row[2] . '"),<br>';
}
echo ")<br><br>";

$siswa[] = array("Daniel", "220404", "0812345611");
$siswa[] = array("Elena", "220405", "0812345622");
$siswa[] = array("Fiona", "220406", "0812345633");
$siswa[] = array("Gabe", "220407", "0812345644");
$siswa[] = array("Hannah", "220408", "0812345655");

echo "Data setelah ditambah 5 data lain:<br>";
echo "students = (<br>";
foreach ($siswa as $row) {
    echo '("' . $row[0] . '", "' . $row[1] . '", "' . $row[2] . '"),<br>';
}
echo ")<br><br>";

echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<tr>
        <th>Name</th>
        <th>NIM</th>
        <th>Mobile</th>
      </tr>';

foreach ($siswa as $row) {
    echo '<tr>';
    echo '<td>' . $row[0] . '</td>';
    echo '<td>' . $row[1] . '</td>';
    echo '<td>' . $row[2] . '</td>';
    echo '</tr>';
}
echo '</table>';
?>