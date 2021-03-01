<html lang="pl-PL">

<head>
  <title> Rejestracja </title>
</head>

<body>
<h2> Rejestracja </h2>

<?php
session_start();

$conn = oci_connect($_SESSION['DB_LOGN'], $_SESSION['DB_PASW'],
    "//labora.mimuw.edu.pl/LABS");
if (!$conn) {
  echo "oci_connect failed\n";
  $e = oci_error();
  echo $e['message'];
}

$registration_err = "";
if (isset($_POST['EMAIL']) && isset($_POST['PASSWORD'])) {
  $email = $_POST['EMAIL'];
  $password = $_POST['PASSWORD'];

  $sqlText = "SELECT * FROM KLIENT WHERE EMAIL = " . "'" . "$email" . "'";
  $stmt = oci_parse($conn, $sqlText);
  oci_execute($stmt);

  if ($row = oci_fetch_array($stmt, OCI_BOTH)) {
    $registration_err = "E-mail zajęty<br>";
  } else {
    $sqlText = "SELECT COALESCE(MAX(ID) + 1, 1) AS ID FROM KLIENT";
    $stmt = oci_parse($conn, $sqlText);
    oci_execute($stmt);
    if ($row = oci_fetch_array($stmt, OCI_BOTH)) {
      $id = $row['ID'];

      $sqlText = "INSERT INTO KLIENT VALUES ($id, '$email', '$password')";
      $stmt = oci_parse($conn, $sqlText);
      oci_execute($stmt);
      $_SESSION['EMAIL'] = $email;
      $_SESSION['KLIENT_ID'] = $row['ID'];
    } else {
      echo "błąd<br>";
    }
  }
}

include "header.php";
?>

<form action="register.php" method="post">
  E-mail: <input type="text" name="EMAIL" value=""><br><br>
  Hasło: <input type="password" name="PASSWORD" value=""><br><br>
  <input type="submit" value="zarejestruj się">
</form>

<?php
echo $registration_err;

oci_commit($conn);
oci_close($conn);
?>

</body>
</html>
