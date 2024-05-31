<?php

require_once('./config.php');

$badge = $_GET['q'];

// Fetch records from database 
$qry = $conn->query("SELECT * FROM `employee_masterlist` WHERE EMPLOYID = '" . $badge . "'");
$hint = "";
if ($qry->num_rows > 0) {
  // Output each row of the data 
  while ($row = $qry->fetch_assoc()) {
    $hint = $row['TEAM'];
  }
}

// Output "no suggestion" if no hint was found or output correct values
switch ($hint) {
  case '1':
    echo 'N';
    break;
  case '2':
    echo 'A';
    break;
  case '3':
    echo 'B';
    break;
  case '4':
    echo 'C';
    break;
  case '5':
    echo 'No Team';
    break;
  default:
    echo "Unregistered badge number";
    break;
}
// echo $hint === "" ? "Unregistered badge number" : $hint;
