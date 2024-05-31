<?php

require_once('./config.php');

$badge = $_GET['q'];

// Fetch records from database 
$qry = $conn->query("SELECT * FROM `employee_masterlist` WHERE EMPLOYID = '" . $badge . "' and EMPLOYID > 0");
$hint = "";
$hint1 = "";
$hint2 = "";
if ($qry->num_rows > 0) {
  // Output each row of the data 
  while ($row = $qry->fetch_assoc()) {
    $hint = $row['APPROVER1'] !== 'na' ? $row['APPROVER1'] : $row['APPROVER2'];
    $hint1 = $row['APPROVER2'] !== 'na' ? $row['APPROVER2'] : 0;
    $hint2 = $row['APPROVER3'] !== 'na' ? $row['APPROVER3'] : 0;
  }
  $qry1 = $conn->query("SELECT EMPNAME FROM `employee_masterlist` WHERE EMPLOYID = '" . $hint . "'")->fetch_array()[0] ?? $conn->query("SELECT EMPNAME FROM `employee_masterlist` WHERE EMPLOYID = '" . $hint1 . "'")->fetch_array()[0];
  $qry2 = $conn->query("SELECT EMPNAME FROM `employee_masterlist` WHERE EMPLOYID = '" . $hint1 . "'")->fetch_array()[0];
}
// Output "no suggestion" if no hint was found or output correct values
echo $hint === "" ? "Unregistered badge number/Unregistered badge number/Unregistered badge number/Unregistered badge number" : $hint . '/' . $qry1 . '/' . $hint1 . '/' . $qry2 . '/' . $hint2;
