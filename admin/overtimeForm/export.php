<?php

// Load the database configuration file 
require_once('../../config.php');
 
// Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 

$exDatefrom = $_POST['exDatefrom'];
$exDateto = $_POST['exDateto'];
 
// Excel file name for download 
$fileName = "overtime-data_" . date('Y-m-d') . ".xls"; 
 
// Column names 
// $fields = array('PR NUMBER', 'PO NUMBER', 'BADGE NUMBER', 'FULL NAME', 'DEPARTMENT', 'DATE REQUISITIONED', 'REQUEST DATE', 'DATE UPDATED', 'STATUS'
//                 , 'PURCHASING', 'STATUS', 'DATE', 'REMARKS', 'APPROVER 1', 'STATUS', 'DATE', 'REMARKS','APPROVER 2', 'STATUS', 'DATE', 'REMARKS'
//                 , 'STOCK NUMBER', 'DESCRIPTION', 'PART NUMBER', 'SUPPLIER', 'QUANTITY', 'UOM', 'CURRENCY', 'PRICE','DICOUNT AMOUNT', 'TOTAL PRICE', 'PRODUCT LINE'); 

$fields = array('EMP. NO.', 'EMP. NAME ', 'DATE REQUESTED', 'TIME START', 'TIME END', 'No. of Hours', 'REMARKS ', 'DATE APPROVED '); 

// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
// Fetch records from database 
$qry = $conn->query("SELECT * FROM `overtime_requests` where `ot_status` = 2");
 
if($qry->num_rows > 0){ 
  // Output each row of the data 
  while($row = $qry->fetch_assoc()){ 
    $formCode = $row['ot_form_no'];
    $dateApproved = date('m-d-Y h:m a', strtotime($row['od_sign_date']));

    $qry1 = $conn->query("SELECT * FROM overtime_items WHERE ot_form_code = '{$formCode}' 
                            AND ot_date_from BETWEEN '{$exDatefrom}' AND '{$exDateto}'");

    if (mysqli_num_rows($qry1) != 0){
      while($row1 = $qry1->fetch_assoc()):

        // $dateRequested = date('m-d-Y', strtotime($row1['date_requested']));
        $dateFrom = date('m-d-Y', strtotime($row1['ot_date_from']));
        // $dateTo = date('m-d-Y', strtotime($row1['ot_date_to']));

        //Getting total number of hours
        $timeFrom = $row1['ot_time_from'];
        $timeTo = $row1['ot_time_to'];

        // Create DateTime objects for the two time values
        $fromDateTime = new DateTime($timeFrom);
        $toDateTime = new DateTime($timeTo);

        // Calculate the time interval
        $timeInterval = $fromDateTime->diff($toDateTime);

        // Get the time difference in hours and minutes
        $hours = $timeInterval->h;
        $minutes = $timeInterval->i;

        // echo "Time difference: $hours hours and $minutes minutes"


        $lineData = array($row1['emp_num'], $row1['emp_name'], $dateFrom, $timeFrom, $timeTo, $hours,$row1['ot_reason'], $dateApproved); 

        array_walk($lineData, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        
        // $qry2 = $conn->query("SELECT * FROM `supplier_list` where id = '{$row1['suppliers']}'");
        // while($row2 = $qry2->fetch_assoc()):

        // // $lineData = array($row['po_code'], $row1['po_num'], $row['requestor_badge'], $row['requestor_name'], $row['requestor_department'], $newDateformat, $row['request_delivery_date'], $newDateformat1,$row['status']
        // //                   , $row['approver_purchasing'], $row['purchasing_status'], $row['purchasing_approved_date'], $row['purchasing_remarks']
        // //                   , $row['approver_department_head'], $row['approver_department_head_status'], $row['department_head_approved_date'], $row['department_head_remarks']  
        // //                   , $row['approver_final'], $row['approver_final_approver_status'], $row['final_approver_approved_date'], $row['final_approver_remarks']                       
        // //                   , $row1['stock_number'], $row1['name'] , $row1['part_number'], $row2['name'], $row1['quantity'], $row1['unit'], $row2['currency'], $row1['price']
        // //                   , $row1['discount_amount'], $row1['total'], $row1['productline']); 

        // if($row['status'] == 0){
        //   $prStatus = "Pending";
        // }
        // if($row['status'] == 1){
        //   $prStatus = "Partially Approved";
        // }
        // if($row['status'] == 2){
        //   $prStatus = "Approved";
        // }
        // if($row['status'] == 3){
        //   $prStatus = "Disapproved";
        // }
        // if($row['purchasing_status'] == 0){
        //   $purchasingStatus = "RFQ";
        // }
        // if($row['purchasing_status'] == 1){
        //   $purchasingStatus = "Approved";
        // }
        // if($row['purchasing_status'] == 3){
        //   $purchasingStatus = "Cancel";
        // }
        // if($row['approver_department_head_status'] == 0){
        //   $departmentHeadStatus = "Pending";
        // }
        // if($row['approver_department_head_status'] == 1){
        //   $departmentHeadStatus = "Approved";
        // }
        // if($row['approver_department_head_status'] == 3){
        //   $departmentHeadStatus = "Disapproved";
        // }
        // $lineData = array($row['po_code'], $row1['po_num'], $row['requestor_badge'], $row['requestor_name'], $row['requestor_department'], $newDateformat, $row['request_delivery_date'], $newDateformat1,$prStatus
        // , $row['approver_purchasing'], $purchasingStatus, $row['purchasing_approved_date'], $row['purchasing_remarks']
        // , $row['approver_department_head'], $departmentHeadStatus, $row['department_head_approved_date'], $row['department_head_remarks']                     
        // , $row1['stock_number'], $row1['name'] , $row1['part_number'], $row2['name'], $row1['quantity'], $row1['unit'], $row2['currency'], $row1['price']
        // , $row1['discount_amount'], $row1['total'], $row1['productline']); 

        // array_walk($lineData, 'filterData'); 
        // $excelData .= implode("\t", array_values($lineData)) . "\n"; 
          
        // endwhile;

      endwhile;  
    }

  } 
}else{ 
  $excelData .= 'No records found...'. "\n"; 
} 
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 

exit;
?>
