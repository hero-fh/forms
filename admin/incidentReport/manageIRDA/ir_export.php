<?php
require_once('../../../config.php');

$sql = $conn->query("SELECT ir.ir_no,il.ir_no,ir.date_created,ir.emp_no,ir.emp_name,ir.quality_violation,il.code_no,il.violation,il.offense_no,il.da_type,il.date_of_suspension,ir.when,il.da_type,
ir.productline,ir.station,ir.department,ir.position,ir.shift,ir.is_inactive,ir.ir_status,ir.hr_status,ir.sv_status,ir.why1,ir.da_status,ir.has_da,ir.disapprove_remarks,
ir.hr_name,ir.sv_name,il.date_of_LOE,ir.sv_sign_date,ir.dh_sign_date,ir.valid_to_da_date,ir.hr_mngr_sign_date,ir.da_requested_date,ir.dh_da_sign_date,ir.dm_sign_date,ir.acknowledge_date FROM ir_requests ir inner join ir_list il on ir.ir_no  = il.ir_no order by ir.id asc");

function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// Excel file name for download 
$fileName = "Telford_IR_DA_" . date('Y-m-d') . ".xls";

// Column names 
$fields = array(
    'NO.', 'IR #', 'DATE CREATED', 'EMPLOYEE', 'NAME', 'TYPE', 'TYPE & ITEM #', 'NATURE OF OFFENSE/VIOLATION',
    'NO. OF OFFENSE', 'TYPE OF DA', 'DATE SUSPENSION', 'DATE COMMITTED', 'CLEANSING PERIOD', 'PRODUCT LINE',
    'STATION', 'DEPARTMENT', 'IMMEDIATE SUPERIOR', 'DEPARTMENT HEAD/MANAGER', 'POSITION', 'SHIFT', 'TEAM',
    'STATUS', 'REMARKS', 'CATEGORY', 'MONTH', 'YEAR', 'DATE OF LETTER OF EXPLANATION', 'DATE OF ASSESSMENT',
    'DATE OF HR VALIDATION', 'DATE OF IR APPROVAL', 'DATE OF DA ISSUANCE', 'HR MANAGER ACKNOWLEDGEMENT DATE',
    'IMMEDIATE SUPERIOR ACKNOWLEDGEMENT DATE', 'MANAGER ACKNOWLEDGEMENT DATE', 'PERSON INVOLVED ACKNOWLEDGEMENT DATE'
);

// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n";

if ($sql->num_rows > 0) {
    // OUTPUT DATA OF EACH ROW
    $i = 1;
    while ($row = $sql->fetch_assoc()) {
        $approver_2 = $conn->query("SELECT APPROVER2 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
        $approver_1 = $conn->query("SELECT APPROVER1 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
        $approver_1 = $approver_1 == 'na' ? $approver_2 : $approver_1;
        $approver_3 = $conn->query("SELECT APPROVER3 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
        $c = $conn->query("SELECT valid FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;
        $is_valid = $conn->query("SELECT valid FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}'")->num_rows;

        $date_crtd = date("m-d-Y", strtotime($row['date_created']));
        switch ($row['quality_violation']) {
            case 1:
                $qv = 'A';
                break;
            case 2:
                $qv = 'B';
                break;
        }

        if ($row['da_type'] == 1) {
            $type_of_da = 'Verbal Warning';
        } elseif ($row['da_type'] == 2) {
            $type_of_da = 'Written Warning';
        } elseif ($row['da_type'] == 3) {
            $type_of_da = '3 Days Suspension';
        } elseif ($row['da_type'] == 4) {
            $type_of_da = '7 Days Suspension';
        } elseif ($row['da_type'] == 5) {
            $type_of_da = 'Dismissal';
        } elseif ($row['da_type'] == 6) {
            $type_of_da = '14 Days Suspension';
        }

        $dateString = $row['date_of_suspension'];
        $dateArray = explode(' + ', $dateString);
        $results = [];

        foreach ($dateArray as $key => $date) {
            $trimmedDate = trim($date);
            $timestamp = strtotime($trimmedDate);

            if ($timestamp === false) {
                $results[] = "--";
            } else {
                $dateTime = new DateTime();
                $dateTime->setTimestamp($timestamp);

                $dayOfWeek = $dateTime->format('D');
                $results[] = "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)";
            }
        }

        $finalResult = implode(", ", $results);

        $when =  date("m-d-Y", strtotime($row['when']));

        $originalDate = $row['when'];
        $daType = $row['da_type'];

        if ($daType == 1) {
            $adjustedDate = strtotime($originalDate . "+6 months");
        } elseif ($daType == 2) {
            $adjustedDate = strtotime($originalDate . "+9 months");
        } elseif ($daType == 3) {
            $adjustedDate = strtotime($originalDate . "+12 months");
        } elseif ($daType == 4) {
            $adjustedDate = strtotime($originalDate . "+18 months");
        } else {
            // Handle other cases or set a default date
            $adjustedDate = strtotime($originalDate);
        }
        if ($daType < 5) {
            $newDate = date("m-d-Y", $adjustedDate);
        } else {
            $newDate = '--';
        }
        $emp_nam = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
        $svname = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = (SELECT CASE WHEN APPROVER1 = 'na' THEN APPROVER2 ELSE APPROVER1 END AS Approver from employee_masterlist WHERE (EMPLOYID = '{$row['emp_no']}'))")->fetch_array()[0];


        if ($row['ir_status'] != 2 || $c == 0) {
            if ($row['is_inactive'] == 1) {
                $status = 'Inactive';
            } else {
                $dpr = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['hr_name']}'")->fetch_array();
                if ($row['ir_status'] == 0 && $row['hr_status'] == 2) {
                    $status = 'Invalid';
                } elseif ($row['ir_status'] == 0 && $row['hr_status'] == 0) {
                    $status = 'Pending';
                } elseif ($row['ir_status'] == 1 && $row['why1'] == '' && $row['sv_status'] == 0) {
                    $status = 'For Assessment';
                } elseif ($row['ir_status'] == 1 && $row['sv_status'] == 0 && $row['why1'] != '') {
                    $status = 'For Assessment';
                } elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 1) {
                    $status = 'For Assessment';
                } elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 0) {
                    $status = 'For Assessment';
                } elseif ($row['ir_status'] == 2) {
                    if ($is_valid > 0) {
                        $status = 'For monitoring (for TK purposes)';
                    } elseif ($is_valid == 0) {
                        $status = 'Invalid';
                    }
                } elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) {
                    $status = 'Invalid';
                } else {
                    $status = 'Invalid';
                }
            }
        } elseif ($row['ir_status'] == 2 && $c > 0) {
            if ($row['is_inactive'] == 1) {
                $status = 'Inactive';
            } else {
                if ($row['has_da'] == 0) {
                    $status = 'For DA';
                } elseif ($row['has_da'] == 1) {
                    if ($row['da_status'] == 1) {
                        $status = 'Pending';
                    } elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) {
                        $status = 'Pending';
                    } elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) {
                        $status = 'Pending';
                    } elseif ($row['da_status'] == 4) {
                        $status = 'Pending';
                    } elseif ($row['da_status'] == 5) {
                        $status = 'Served';
                    } else {
                        $status = 'Invalid';
                    }
                }
            }
        }
        if (($row['ir_status'] == 0 && $row['hr_status'] == 2) || ($row['ir_status'] == 3 && $row['da_status'] == 2) || ($row['ir_status'] == 4)) {
            $disappr_rem = $row['disapprove_remarks'];
        } else {
            $disappr_rem = '';
        }
        if ($row['ir_status'] != 2) {
            if ($row['is_inactive'] == 1) {
                // Do nothing if inactive
            } else {
                $dpr = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['hr_name']}'")->fetch_array();
                if ($row['ir_status'] == 0 && $row['hr_status'] == 2) {
                    $categ = 'IR';
                } elseif ($row['ir_status'] == 0 && $row['hr_status'] == 0) {
                    $categ = 'IR';
                } elseif ($row['ir_status'] == 1 && $row['why1'] == '' && $row['sv_status'] == 0) {
                    $categ = 'IR';
                } elseif ($row['ir_status'] == 1 && $row['sv_status'] == 0 && $row['why1'] != '') {
                    $categ = 'IR';
                } elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 1) {
                    $categ = 'IR';
                } elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 0) {
                    $categ = 'IR';
                } elseif ($row['ir_status'] == 2) {
                    if ($is_valid > 0) {
                        $categ = 'DA';
                    } elseif ($is_valid == 0) {
                        $categ = 'IR';
                    }
                } elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) {
                    $categ = 'IR';
                } else {
                    $categ = 'IR';
                }
            }
        } elseif ($row['ir_status'] == 2) {
            if ($row['is_inactive'] == 1) {
                // Do nothing if inactive
            } else {
                if ($row['has_da'] == 0) {
                    $categ = 'IR';
                } elseif ($row['has_da'] == 1) {
                    if ($row['da_status'] == 1) {
                        $categ = 'DA';
                    } elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) {
                        $categ = 'DA';
                    } elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) {
                        $categ = 'DA';
                    } elseif ($row['da_status'] == 4) {
                        $categ = 'DA';
                    } elseif ($row['da_status'] == 5) {
                        $categ = 'DA';
                    } else {
                        $categ = 'IR';
                    }
                }
            }
        }
        $month = date("M", strtotime($row['date_created']));
        $year = date("Y", strtotime($row['date_created']));
        $date_loe = isset($row['date_of_LOE']) ? date("Y-m-d", strtotime($row['date_of_LOE'])) : '';
        $date_sv_sign = isset($row['sv_sign_date']) ? date("Y-m-d", strtotime($row['sv_sign_date'])) : '';
        $date_hr_valid = isset($row['valid_to_da_date']) ? date("Y-m-d", strtotime($row['valid_to_da_date'])) : '';
        $ir_appvl_date = isset($row['dh_sign_date']) ? date("Y-m-d", strtotime($row['dh_sign_date'])) : '';
        $date_da_request = isset($row['da_requested_date']) ? date("Y-m-d", strtotime($row['da_requested_date'])) : '';
        $date_hr_mngr = isset($row['hr_mngr_sign_date']) ? date("Y-m-d", strtotime($row['hr_mngr_sign_date'])) : '';
        $date_dh_sign = isset($row['dh_da_sign_date']) ? date("Y-m-d", strtotime($row['dh_da_sign_date'])) : '';
        $date_dm_sign = isset($row['dm_sign_date']) ? date("Y-m-d", strtotime($row['dm_sign_date'])) : '';
        $date_ack = isset($row['acknowledge_date']) ? date("Y-m-d", strtotime($row['acknowledge_date'])) : '';

        $lineData = array(
            $i++, $row['ir_no'], $date_crtd, $row['emp_no'], $row['emp_name'], $qv,
            $row['code_no'], $row['violation'], $row['offense_no'], $type_of_da, $finalResult,
            $when, $newDate, $row['productline'], $row['station'], $row['department'],
            $emp_nam, $svname, $row['position'], $row['shift'], $row['shift'],
            $status, $disappr_rem, $categ, $month, $year, $date_loe, $date_sv_sign, $date_hr_valid, $ir_appvl_date, $date_da_request, $date_hr_mngr, $date_dh_sign, $date_dm_sign, $date_ack
        );
        array_walk($lineData, 'filterData');
        $excelData .= implode("\t", array_values($lineData)) . "\n";
    }
} else {
    echo "0 results";
}

// Headers for download 
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Render excel data 
echo $excelData;

exit;
