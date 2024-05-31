<?php

require_once('./config.php');

$badge = $_GET['q'];

// Fetch records from database 
$qry = $conn->query("SELECT * FROM `employee_masterlist` WHERE EMPLOYID = '" . $badge . "'");
$hint = "";
if ($qry->num_rows > 0) {
  // Output each row of the data 
  while ($row = $qry->fetch_assoc()) {
    $hint = $row['EMPNAME'];
  }
}

// Output "no suggestion" if no hint was found or output correct values
echo $hint === "" ? "Unregistered badge number" : $hint;
