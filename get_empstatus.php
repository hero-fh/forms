<?php

require_once('./config.php');

$badge = $_GET['q'];

// Fetch records from database 
$qry = $conn->query("SELECT EMPLOYID, EMPSTATUS FROM `employee_masterlist` WHERE EMPLOYID = '" . $badge . "'");
$hint = "";
if ($qry->num_rows > 0) {
  // Output each row of the data 
  while ($row = $qry->fetch_assoc()) {
    $hint = $row['EMPSTATUS'];
  }
}

// Output "no suggestion" if no hint was found or output correct values
echo $hint === "" ? "Unregistered badge number" : $hint;
// switch ($hint) {
//   case '1':
//     echo 'DIRECT';
//     break;
//   case '2':
//     echo 'NON-EXEMPT';
//     break;
//   case '3':
//     echo 'EXEMPT';
//     break;
//   case '4':
//     echo 'SECTION HEAD';
//     break;
//   case '5':
//     echo 'MANAGER';
//     break;
//   case '6':
//     echo 'SENIOR MANAGEMENT';
//     break;
//   default:
//     echo "Unregistered badge number";
//     break;
// }
