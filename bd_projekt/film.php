<html lang="pl-PL">

<head>
  <title> Film </title>
</head>

<body>
<h2> Film </h2>

<?php
include "header.php";
$filmId = $_GET['id'];
session_start();

$conn = oci_connect($_SESSION['DB_LOGN'], $_SESSION['DB_PASW'],
    "//labora.mimuw.edu.pl/LABS");
if (!$conn) {
  echo "oci_connect failed\n";
  $e = oci_error();
  echo $e['message'];
}

// wyświetl dane filmu
$sqlText = "SELECT * FROM FILM WHERE ID = ". "$filmId";
$stmt = oci_parse($conn, $sqlText);
oci_execute($stmt);

echo "<br>FILM<br><br>";
while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
  echo "TYTUŁ: " . $row['TYTUL'] . "<br><br>" .
      "TYP: " . $row['TYP'] . "<br><br>" .
      "REŻYSER: " . $row['REZYSER'] . "<br><br>" .
      "DŁUGOŚĆ: " . $row['DLUGOSC_W_MINUTACH'] . " minut<br><br>" .
      "ROK: " . $row['ROK'] . "<br><br>" .
      "OPIS: " . $row['OPIS'] . "<br><br>";
}

// wyświetl seanse filmu
$sqlText = "SELECT ID, to_char(CZAS_ROZPOCZECIA, 'DD-MM-YYYY') AS DZIEN,
       to_char(CZAS_ROZPOCZECIA, 'HH24:MI:SS') AS GODZINA, NUMER_SALI, 
       LICZBA_WOLNYCH_MIEJSC FROM seans WHERE FILM_ID = ". "$filmId";
$stmt = oci_parse($conn, $sqlText);
oci_execute($stmt);

echo "<br>SEANSE<br><br>";
while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
  echo "<table style='border: 1px solid black;'><td>" .
      "DZIEŃ: " . $row['DZIEN'] . "<br><br>" .
      "GODZINA: " . $row['GODZINA']  . "<br><br>" .
      "SALA: " . $row['NUMER_SALI'] . "<br><br>" .
      "WOLNE MIEJSCA: " . $row['LICZBA_WOLNYCH_MIEJSC'] . "<br><br>" .
      "<form action='zamowienie.php?seans_id=" . $row['ID'] . "' method='post'>
      <button class='button' type='submit'>Kup bilet</button>
      </form></td></table>";
}

oci_commit($conn);
oci_close($conn);
?>

</body>
</html>
