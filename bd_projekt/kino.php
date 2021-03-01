<html lang="pl-PL">

<head>
  <title> Kino </title>
</head>

<body>
<h2> Kino </h2>

<?php
include "header.php";
session_start();
if (isset($_POST['DB_LOGN']) && isset($_POST['DB_PASW'])) {
  $_SESSION['DB_LOGN'] = $_POST['DB_LOGN'];
  $_SESSION['DB_PASW'] = $_POST['DB_PASW'];
}

$conn = oci_connect($_SESSION['DB_LOGN'], $_SESSION['DB_PASW'],
    "//labora.mimuw.edu.pl/LABS");
if (!$conn) {
  echo "oci_connect failed\n";
  $e = oci_error();
  echo $e['message'];
}

for ($i = 0; $i < 7; ++$i) {
  $weekDay = date("l", strtotime(date("l") . ' + ' . $i .
      ' days'));

  $weekDayStr = "";
  if ($weekDay == 'Monday') {
    $weekDayStr = "Pn";
  } else if ($weekDay == 'Tuesday') {
    $weekDayStr = "Wt";
  } else if ($weekDay == 'Wednesday') {
    $weekDayStr = "Śr";
  } else if ($weekDay == 'Thursday') {
    $weekDayStr = "Cz";
  } else if ($weekDay == 'Friday') {
    $weekDayStr = "Pt";
  } else if ($weekDay == 'Saturday') {
    $weekDayStr = "Sb";
  } else if ($weekDay == 'Sunday') {
    $weekDayStr = "Nd";
  }
  echo "<form action='kino.php?day=$i' method='post'><button class='button'
        type='submit'>$weekDayStr</button></form>";
}

// wyświetl seanse dnia
if (isset($_GET['day'])) {
  $dateStr = date("d-m-Y", mktime(0, 0, 0,
      date("m"), date("d") + $_GET['day'], date("Y")));
  $stmt = oci_parse($conn, "SELECT SEANS.ID AS SEANS_ID ,FILM_ID, TYTUL, 
       to_char(CZAS_ROZPOCZECIA, 'DD-MM-YYYY') AS DZIEN,
       to_char(CZAS_ROZPOCZECIA, 'HH24:MI:SS') AS GODZINA, 
       NUMER_SALI, LICZBA_WOLNYCH_MIEJSC FROM seans 
       INNER JOIN film ON seans.FILM_ID = FILM.ID 
       WHERE to_char(seans.CZAS_ROZPOCZECIA, 'DD-MM-YYYY') = " . "'" . "$dateStr"
      . "'");
  oci_execute($stmt);

  echo "<BR>SEANSE<BR><br>";
  while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
    echo "<table style='border: 1px solid black;'><td>TYTUŁ: " .
        "<a HREF=\"film.php?id=" . $row['FILM_ID'] . "\">" .
        $row['TYTUL'] . "</a><br><br>" .
        "DZIEŃ: " . $row['DZIEN'] . "<br><br>" .
        "GODZINA: " . $row['GODZINA'] . "<br><br>" .
        "SALA: " . $row['NUMER_SALI'] . "<br><br>" .
        "WOLNE MIEJSCA: " . $row['LICZBA_WOLNYCH_MIEJSC'] . "<br><br>" .
        "<form action='zamowienie.php?seans_id=" . $row['SEANS_ID'] . "' method='post'>
        <button class='button' type='submit'>Kup bilet</button>
        </form></td></table>";
  }
}

oci_commit($conn);
oci_close($conn);
?>

</body>
</html>
