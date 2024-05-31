<?php

require_once('./config.php');


$badge = $_GET['q'];
$decodedBadge = urldecode($badge);
// Fetch records from database 
$qry = $conn->query("SELECT * FROM `ir_code_no` WHERE code_number = '{$decodedBadge}' ");
$hint = "";
if ($qry->num_rows > 0) {
  // Output each row of the data 
  while ($row = $qry->fetch_assoc()) {
    $hint = $row['violation'];
  }
}

// Output "no suggestion" if no hint was found or output correct values
echo $hint === "" ?  $_GET['q'] : $hint;
