<?php
echo '
<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
    border: 1px solid black;
}
img {
    width: 100px;
    height: 100px;
}
</style>
</head>
<body>';
$dbconn=pg_connect("host='db' port=5432 dbname='institut' user=test password=test");


$result = pg_query($dbconn, 'SELECT al.DNI, al.Lletra, al.Nom, al.Cognoms, al.Mail, cur.NomCurs, al.Foto FROM alumnes al, cursos cur where al.CodiCicle=cur.codicicle;');
if (!$result) {
    echo "An error occurred.\n";
    exit;
}
$table=[];
echo "<table style='width:100%'>
<tr>
    <th>DNI</th>
    <th>Lletra</th>
    <th>Nom</th>
    <th>Cognoms</th>
    <th>Mail</th>
    <th>NomCurs</th>
    <th>Foto</th>
</tr>";

# Generates table content
while ($row = pg_fetch_row($result)) {
    echo "<tr>";
    echo "<td><p>${row[0]}</p></td>";
    echo "<td><p>${row[1]}</p></td>";
    echo "<td><p>${row[2]}</p></td>";
    echo "<td><p>${row[3]}</p></td>";
    echo "<td><p>${row[4]}</p></td>";
    echo "<td><p>${row[5]}</p></td>";
    echo "<td><p><img src='${row[6]}'></p></td>";
    echo "</tr>";
}
echo "</table>";



echo '</body></html>';
?>