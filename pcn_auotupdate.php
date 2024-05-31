<?php
require_once('config.php');

date_default_timezone_set("Asia/Kuala_Lumpur");
$curDate = date("Y-m-d");

//pcn department
$qry_1 = $conn->query("SELECT * FROM pcn_department WHERE `status` = 0");
if ($qry_1->num_rows > 0) {
    while ($row_1 = $qry_1->fetch_assoc()) :

        // Convert string to date
        $eff_dept_date = DateTime::createFromFormat("Y-m-d", $row_1['eff_dept_date']);
        // Compare dates
        if ($curDate >= $eff_dept_date) {
            $updateQuery = $this->conn->query("UPDATE `employee_masterlist` set `DEPARTMENT` = '{$row_1['dept_to']}' where EMPLOYID = '{$row_1['pcn_emp_no']}'");
            if ($updateQuery) {
                $myQuery = $this->conn->query("UPDATE `pcn_department` set `status` = 1");
                if ($myQuery) {
                    echo $row_1['pcn_code'] . ' - department - successfully updated</br>';
                } else {
                    echo $row_1['pcn_code'] . ' failed_2';
                }
            } else {
                echo $row_1['pcn_code'] . ' failed';
            }
        } else {
        }

    endwhile;
} else {
    echo "Nothing to update for department</br>";
}

//pcn jobtitle
$qry_2 = $conn->query("SELECT * FROM pcn_jobtitle WHERE `status` = 0");
if ($qry_2->num_rows > 0) {
    while ($row_2 = $qry_2->fetch_assoc()) :

        // Convert string to date
        $eff_dept_date = DateTime::createFromFormat("Y-m-d", $row_2['eff_dept_date']);

        // Compare dates
        if ($curDate >= $eff_dept_date) {
            $updateQuery = $this->conn->query("UPDATE `employee_masterlist` set `JOB_TITLE` = '{$row_2['jd_to']}' where EMPLOYID = '{$row_2['pcn_emp_no']}'");
            if ($updateQuery) {
                $myQuery = $this->conn->query("UPDATE `pcn_jobtitle` set `status` = 1");
                if ($myQuery) {
                    echo $row_2['pcn_code'] . ' - jobtitle - successfully updated</br>';
                } else {
                    echo $row_2['pcn_code'] . ' failed_2';
                }
            } else {
                echo $row_2['pcn_code'] . ' failed';
            }
        } else {
        }

    endwhile;
} else {
    echo "Nothing to update for job title</br>";
}
//pcn pl
$qry_3 = $conn->query("SELECT * FROM pcn_pl WHERE `status` = 0");
if ($qry_3->num_rows > 0) {
    while ($row_3 = $qry_3->fetch_assoc()) :

        // Convert string to date
        $eff_dept_date = DateTime::createFromFormat("Y-m-d", $row_3['eff_dept_date']);

        // Compare dates
        if ($curDate >= $eff_dept_date) {
            $updateQuery = $this->conn->query("UPDATE `employee_masterlist` set `PRODLINE` = '{$row_3['pl_to']}' where EMPLOYID = '{$row_3['pcn_emp_no']}'");
            if ($updateQuery) {
                $myQuery = $this->conn->query("UPDATE `pcn_jobtitle` set `status` = 1");
                if ($myQuery) {
                    echo $row_1['pcn_code'] . ' - prodline - successfully updated</br>';
                } else {
                    echo $row_1['pcn_code'] . ' failed_2';
                }
            } else {
                echo $row_1['pcn_code'] . ' failed';
            }
        } else {
        }

    endwhile;
} else {
    echo "Nothing to update for product line</br>";
}
