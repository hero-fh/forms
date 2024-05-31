<!-- notifications -->
<?php
//FORM COMPLETION -----------------
$qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` < 3 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");

if (empty($_settings->userdata('EMPNAME'))) {
    $qry = $conn->query("SELECT * FROM appraisal_requests WHERE ((pa_status < 4 AND pa_type = 1) OR (pa_status < 5 AND (pa_type != 1 OR pa_type IS NULL))) ORDER BY date_created DESC;");
}

if (!empty($_settings->userdata('EMPNAME'))) {
    $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 1 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc;");
}

$notif1 = "";
if ($qry->num_rows > 0) {
    $notif1 = '<span class="badge badge-warning">New</span>';
}
//NEEDS APPRAISAL ------------------
$qry = $conn->query("SELECT * FROM employee_masterlist WHERE `APPROVER1`='{$_settings->userdata('EMPLOYID')}' AND ACCSTATUS = 1");

if ($_settings->userdata('EMPPOSITION') == 4) {
    $qry = $conn->query("SELECT * FROM employee_masterlist WHERE 
                                        (`APPROVER2`='{$_settings->userdata('EMPLOYID')}' AND `EMPPOSITION` > 1) AND ACCSTATUS = 1");
}

if ($_settings->userdata('EMPPOSITION') == 5) {
    $qry = $conn->query("SELECT * FROM employee_masterlist WHERE 
                                        `APPROVER2`='{$_settings->userdata('EMPLOYID')}' AND ACCSTATUS = 1");
}

$appvalsCount0 = 0;

while ($row = $qry->fetch_assoc()) :
    $todayDate = date("Y-m-d");

    $empDate = $row['DATEHIRED'];

    // Convert the date strings to DateTime objects
    $empDateTime = new DateTime($empDate);
    $todayDateTime = new DateTime($todayDate);

    // Calculate the difference
    $interval = $todayDateTime->diff($empDateTime);

    // Access the difference in days, months, and years
    $daysDifference = $interval->days;
    $monthsDifference = $interval->m;
    $yearsDifference = $interval->y;

    if ($row['pa_level'] == 0.1 && $monthsDifference >= 2) {
        $appvalsCount0++;
    }
    if ($row['pa_level'] == 0.2 && $monthsDifference >= 5) {
        $appvalsCount0++;
    }
    if (($row['pa_level'] != 0.1 && $row['pa_level'] != 0.2) && $row['pa_level'] < $yearsDifference) {
        $appvalsCount0++;
    }

endwhile;

if ($appvalsCount0 > 0) {
    $notif1 = '<span class="badge badge-warning">New</span>';
}
//FOR APRROVALS ------------------
if (empty($_settings->userdata('EMPNAME'))) {
    if ($_settings->userdata('EMPPOSITION') == 5) {
        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 4 ORDER BY `date_created` desc");
    }
    if ($_settings->userdata('EMPPOSITION') == 4) {
        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND (`requestor_department` = '{$_settings->userdata('DEPARTMENT')}' 
                        AND `requestor_productline` = '{$_settings->userdata('PRODLINE')}') ORDER BY `date_created` desc");
    }
    if ($_settings->userdata('EMPPOSITION') == 4) { // Leandro
        if ($_settings->userdata('EMPLOYID') == '1694') {
            $dept1 = "MIS";
            $dept2 = "Facilities";
            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND
                                (`requestor_department` = '{$dept1}' OR `requestor_department` = '{$dept2}') ORDER BY `date_created` desc");
        }
        if ($_settings->userdata('EMPLOYID') == '702') { // Joan
            $dept1 = 'Finance';
            $dept2 = 'Purchasing';
            $prodline1 = 'G &amp; A';

            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND
                                ((`requestor_department` = '{$dept1}' OR `requestor_department` = '{$dept2}') AND `requestor_productline` = '{$prodline1}') ORDER BY `date_created` desc");
        }
        if ($_settings->userdata('EMPLOYID') == '524') { // Charity
            $dept1 = 'Human Resource';
            $dept2 = 'Training';

            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE (`pa_status` = 2 AND
            (`requestor_department` = '{$dept1}' OR `requestor_department` = '{$dept2}')) OR (`pa_status` = 3) ORDER BY `date_created` desc");
        }
        if ($_settings->userdata('EMPLOYID') == '8563') { // Bryan
            $dept1 = 'Production';
            $dept2 = 'Production - QFP';
            $dept3 = 'Production - RFC';
            $dept4 = 'Production / Non - TNR';
            $prodline1 = 'PL1 - PL4';
            $prodline2 = 'PL1 (ADGT)';
            $prodline3 = 'PL4 (ADGT)';
            $prodline4 = 'PL6 (ADLT)';

            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND
                                ((`requestor_department` = '{$dept1}' OR `requestor_department` = '{$dept2}' OR `requestor_department` = '{$dept3}' OR `requestor_department` = '{$dept4}') AND (`requestor_productline` = '{$prodline1}' OR `requestor_productline` = '{$prodline2}' OR `requestor_productline` = '{$prodline3}' OR `requestor_productline` = '{$prodline4}')) ORDER BY `date_created` desc");
        }
        if ($_settings->userdata('EMPLOYID') == '20') { // Noel
            $dept1 = 'Production';
            $dept2 = 'Store';
            $dept3 = 'IQA Warehouse';
            $dept4 = 'Logistics';
            $prodline1 = 'PL9 (AD/WHSE)';
            $prodline2 = 'G &amp; A';
            $prodline3 = 'PL8 (AMS O/S)';

            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND
                                (((`requestor_department` = '{$dept1}' OR `requestor_department` = '{$dept2}' OR `requestor_department` = '{$dept3}' OR `requestor_department` = '{$dept4}') 
                                    AND (`requestor_productline` = '{$prodline1}' OR `requestor_productline` = '{$prodline2}' OR `requestor_productline` = '{$prodline3}'))) 
                                ORDER BY `date_created` desc");
        }
        // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODLINE') == 'PL6 (ADLT)') {
        //     $dept1 = 'Production';
        //     $dept2 = 'Production / Non - TNR';
        //     $prodline1 = 'PL6 (ADLT)';

        //     $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 0 AND
        //                             ((`requestor_department` = '{$dept1}' OR `requestor_department` = '{$dept2}') AND (`requestor_productline` = '{$prodline2}')) ORDER BY `date_created` desc");
        // }
        if ($_settings->userdata('EMPLOYID') == '297') { // Erwin
            $dept1 = 'Quality Assurance';
            $prodline1 = 'G &amp; A';
            $prodline2 = 'PL1 - PL4';
            $prodline3 = 'PL1 (ADGT)';
            $prodline4 = 'PL2 (AD/OS)';
            $prodline5 = 'PL3 (ADCV)';
            $prodline6 = 'PL3 (ADCV) - Onsite';
            $prodline7 = 'PL4 (ADGT)';
            $prodline8 = 'PL6 (ADLT)';
            $prodline9 = 'PL8 (AMS O/S)';

            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND
                                ((`requestor_department` = '{$dept1}') AND (`requestor_productline` = '{$prodline1}' OR `requestor_productline` = '{$prodline2}' OR `requestor_productline` = '{$prodline3}' 
                                    OR `requestor_productline` = '{$prodline4}' OR `requestor_productline` = '{$prodline5}' OR `requestor_productline` = '{$prodline6}' OR `requestor_productline` = '{$prodline7}'
                                    OR `requestor_productline` = '{$prodline8}' OR `requestor_productline` = '{$prodline9}')) ORDER BY `date_created` desc");
        }
        if (($_settings->userdata('EMPLOYID') == '1023')) { // Adonis
            $dept1 = 'Equipment Engineering';
            $prodline1 = 'G &amp; A';
            $prodline2 = 'PL1 (ADGT)';
            $prodline3 = 'PL2 (AD/OS)';
            $prodline4 = 'PL3 (ADCV)';
            $prodline5 = 'PL3 (ADCV) - Onsite';
            $prodline6 = 'PL4 (ADGT)';
            $prodline7 = 'PL6 (ADLT)';
            $prodline8 = 'PL8 (AMS O/S)';

            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND
                                ((`requestor_department` = '{$dept1}') AND (`requestor_productline` = '{$prodline1}' OR `requestor_productline` = '{$prodline2}' OR `requestor_productline` = '{$prodline3}' 
                                    OR `requestor_productline` = '{$prodline4}' OR `requestor_productline` = '{$prodline5}' OR `requestor_productline` = '{$prodline6}' OR `requestor_productline` = '{$prodline7}'
                                    OR `requestor_productline` = '{$prodline8}')) ORDER BY `date_created` desc");
        }
        if ($_settings->userdata('EMPLOYID') == '1170') { // Realyn
            $dept1 = 'Process Engineering';
            $prodline1 = 'G &amp; A';
            $prodline2 = 'PL1 - PL4';
            $prodline3 = 'PL2 (AD/OS)';
            $prodline4 = 'PL3 (ADCV)';
            $prodline5 = 'PL3 (ADCV) - Onsite';
            $prodline6 = 'PL6 (ADLT)';
            $prodline7 = 'PL8 (AMS O/S)';

            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND
                                ((`requestor_department` = '{$dept1}') AND (`requestor_productline` = '{$prodline1}' OR `requestor_productline` = '{$prodline2}' OR `requestor_productline` = '{$prodline3}' 
                                    OR `requestor_productline` = '{$prodline4}' OR `requestor_productline` = '{$prodline5}' OR `requestor_productline` = '{$prodline6}' OR `requestor_productline` = '{$prodline7}' )) ORDER BY `date_created` desc");
        }
        if ($_settings->userdata('EMPLOYID') == '1065') { // Tess
            $dept1 = 'Production';
            $dept2 = 'Production / PE';
            $prodline1 = 'PL2 (AD/OS)';

            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND
                                ((`requestor_department` = '{$dept1}' OR `requestor_department` = '{$dept2}') AND `requestor_productline` = '{$prodline1}') ORDER BY `date_created` desc");
        }
    }
    if ($_settings->userdata('EMPPOSITION') == 3) {

        if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
            $dept1 = "PPC";
            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND `requestor_department` = '{$dept1}' ORDER BY `date_created` desc");
        }
    }
    // if ($_settings->userdata('EMPLOYID') == '600') {
    //     $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 0 AND (`requestor_department` = '{$_settings->userdata('DEPARTMENT')}' 
    //                         AND `requestor_productline` = '{$_settings->userdata('PRODLINE')}') ORDER BY `date_created` desc");
    // }
    if ($_settings->userdata('EMPPOSITION') == 2) {
        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
    }
    if (empty($_settings->userdata('EMPNAME'))) {
        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 ORDER BY `date_created` desc");
    }

    $appvalsCount0 = $qry->num_rows;
}
if ($appvalsCount0 > 0) {
    $notif1 = '<span class="badge badge-warning">New</span>';
}

//PCN ----------------------------

// if (!empty($_settings->userdata('EMPNAME'))) {
//     if ($_settings->userdata('EMPPOSITION') == 5) {
//         $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//         FROM pcn_requests
//         INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//         WHERE pcn_requests.pcn_status = 2 AND pa_form_code != ''

//         UNION

//         SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//         FROM pcn_requests
//         WHERE pcn_status = 2 AND pa_form_code IS NULL
//         ORDER BY `date_created` ASC;");
//     }
//     if ($_settings->userdata('EMPPOSITION') == 4) {
//         $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE `pcn_status` = 0 AND (`requestor_department` = '{$_settings->userdata('DEPARTMENT')}' 
//                         AND `requestor_productline` = '{$_settings->userdata('PRODLINE')}') ORDER BY `date_created` ASC");

//         $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//         FROM pcn_requests
//         INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//         WHERE (pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}' AND pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') 
//             AND (pcn_requests.pcn_status = 2 AND pcn_requests.pa_form_code != '')

//         UNION

//         SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//         FROM pcn_requests
//         WHERE (pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}' AND pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}')
//             AND (pcn_status = 0 AND pa_form_code IS NULL)
//         ORDER BY `date_created` ASC;");
//     }
//     if ($_settings->userdata('EMPPOSITION') == 4) {
//         if ($_settings->userdata('EMPLOYID') == '1694') { // Leandro
//             $dept1 = "MIS";
//             $dept2 = "Facilities";
//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE (pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}')
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE (pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}')
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//         if ($_settings->userdata('EMPLOYID') == '702') { // Joan
//             $dept1 = 'Finance';
//             $dept2 = 'Purchasing';
//             $prodline1 = 'G &amp; A';

//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}') )
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}'))
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//         if ($_settings->userdata('EMPLOYID') == '524') { // Charity
//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE (((pcn_requests.pcn_status = 0 AND pcn_requests.requestor_department = 'Human Resource') OR pcn_requests.pcn_status = 1) AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE (((pcn_requests.pcn_status = 0 AND pcn_requests.requestor_department = 'Human Resource') OR pcn_requests.pcn_status = 1) AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//         if ($_settings->userdata('EMPLOYID') == '8563') { // Bryan
//             $dept1 = 'Production';
//             $dept2 = 'Production - QFP';
//             $dept3 = 'Production - RFC';
//             $dept4 = 'Production / Non - TNR';
//             $prodline1 = 'PL1 - PL4';
//             $prodline2 = 'PL1 (ADGT)';
//             $prodline3 = 'PL4 (ADGT)';
//             $prodline4 = 'PL6 (ADLT)';

//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}'  OR pcn_requests.requestor_department = '{$dept4}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}'  OR pcn_requests.requestor_productline = '{$prodline4}') )
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}' OR pcn_requests.requestor_department = '{$dept4}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}'))
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//         if ($_settings->userdata('EMPLOYID') == '20') { // Noel
//             $dept1 = 'Production';
//             $dept2 = 'Store';
//             $dept3 = 'IQA Warehouse';
//             $dept4 = 'Logistics';
//             $prodline1 = 'PL9 (AD/WHSE)';
//             $prodline2 = 'G &amp; A';
//             $prodline3 = 'PL8 (AMS O/S)';

//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}'  OR pcn_requests.requestor_department = '{$dept4}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}') )
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}' OR pcn_requests.requestor_department = '{$dept4}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}'))
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//         // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODLINE') == 'PL6 (ADLT)') {
//         //     $dept1 = 'Production';
//         //     $dept2 = 'Production / Non - TNR';
//         //     $prodline1 = 'PL6 (ADLT)';

//         //     $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//         //     FROM pcn_requests
//         //     INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//         //     WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') 
//         //             AND (pcn_requests.requestor_productline = '{$prodline1}') )
//         //         AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//         //     UNION

//         //     SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//         //     FROM pcn_requests
//         //     WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') 
//         //             AND (pcn_requests.requestor_productline = '{$prodline1}'))
//         //         AND (pcn_status = 0 AND pa_form_code IS NULL)
//         //     ORDER BY `date_created` ASC;");
//         // }
//         if ($_settings->userdata('EMPLOYID') == '297') { // Erwin
//             $dept1 = 'Quality Assurance';
//             $prodline1 = 'G &amp; A';
//             $prodline2 = 'PL1 - PL4';
//             $prodline3 = 'PL1 (ADGT)';
//             $prodline4 = 'PL2 (AD/OS)';
//             $prodline5 = 'PL3 (ADCV)';
//             $prodline6 = 'PL3 (ADCV) - Onsite';
//             $prodline7 = 'PL4 (ADGT)';
//             $prodline8 = 'PL6 (ADLT)';
//             $prodline9 = 'PL8 (AMS O/S)';

//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE ((pcn_requests.requestor_department = '{$dept1}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}') OR pcn_requests.requestor_productline = '{$prodline9}')
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE ((pcn_requests.requestor_department = '{$dept1}') 
//             AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}') OR pcn_requests.requestor_productline = '{$prodline9}')
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//         if (($_settings->userdata('EMPLOYID') == '1023')) { // Adonis
//             $dept1 = 'Equipment Engineering';
//             $prodline1 = 'G &amp; A';
//             $prodline2 = 'PL1 (ADGT)';
//             $prodline3 = 'PL2 (AD/OS)';
//             $prodline4 = 'PL3 (ADCV)';
//             $prodline5 = 'PL3 (ADCV) - Onsite';
//             $prodline6 = 'PL4 (ADGT)';
//             $prodline7 = 'PL6 (ADLT)';
//             $prodline8 = 'PL8 (AMS O/S)';

//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE ((pcn_requests.requestor_department = '{$dept1}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}'))
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE ((pcn_requests.requestor_department = '{$dept1}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}'))
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//         if ($_settings->userdata('EMPLOYID') == '1170') { // Realyn
//             $dept1 = 'Process Engineering';
//             $prodline1 = 'G &amp; A';
//             $prodline2 = 'PL1 - PL4';
//             $prodline3 = 'PL2 (AD/OS)';
//             $prodline4 = 'PL3 (ADCV)';
//             $prodline5 = 'PL3 (ADCV) - Onsite';
//             $prodline6 = 'PL6 (ADLT)';
//             $prodline7 = 'PL8 (AMS O/S)';

//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE ((pcn_requests.requestor_department = '{$dept1}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}')
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE ((pcn_requests.requestor_department = '{$dept1}') 
//                     AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}')
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//         if ($_settings->userdata('EMPLOYID') == '1065') { // Tess
//             $dept1 = 'Production';
//             $dept2 = 'Production / PE';
//             $prodline1 = 'PL2 (AD/OS)';

//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}') )
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}'))
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//     }
//     if ($_settings->userdata('EMPPOSITION') == 3) {
//         if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
//             $dept1 = "PPC";
//             $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//             FROM pcn_requests
//             INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//             WHERE (pcn_requests.requestor_department = '{$dept1}')
//                 AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//             UNION

//             SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//             FROM pcn_requests
//             WHERE (pcn_requests.requestor_department = '{$dept1}')
//                 AND (pcn_status = 0 AND pa_form_code IS NULL)
//             ORDER BY `date_created` ASC;");
//         }
//     }

//     // if ($_settings->userdata('EMPPOSITION') == 3) {
//     //     if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODLINE') == 'PL3 (ADCV)') {
//     //         $dept1 = "Process Engineering";
//     //         $prodline1 = 'PL3';

//     //         $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE 
//     //                                 (`requestor_department` = '{$dept1}' AND `requestor_productline` = '{$prodline1}') ORDER BY `date_created` ASC");
//     //     }
//     // }
//     // if ($_settings->userdata('EMPLOYID') == '600') {
//     //     $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
//     //     FROM pcn_requests
//     //     INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
//     //     WHERE ((pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}') 
//     //             AND (pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') )
//     //         AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

//     //     UNION

//     //     SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
//     //     FROM pcn_requests
//     //     WHERE ((pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}') 
//     //             AND (pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') )
//     //         AND (pcn_status = 0 AND pa_form_code IS NULL)
//     //     ORDER BY `date_created` ASC;");
//     // }
// } else {
//     $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE `pcn_status` = 2 ORDER BY `date_created` ASC");
// }

$pcnappvalsCount = 0;

while ($row = $qry->fetch_assoc()) :
    if (($row['pa_form_code'] != "") && ($row['pa_status'] >= 2 && $row['pa_status'] != 6) && $row['pcn_status'] == 0) {
        $pcnappvalsCount++;
    } else {
        $pcnappvalsCount++;
    }
endwhile;

if ($pcnappvalsCount > 0) {
    $notif1 = '<span class="badge badge-warning">New</span>';
}
//----------------------------------------------- ir --------------------------------------- //
$word = $_settings->userdata('JOB_TITLE');
// Function to extract 'Auditor' from a word
function extractAuditor($word)
{
    // Use preg_match to find the word 'Auditor'
    if (preg_match('/\bAuditor\b/', $word, $matches)) {
        return $matches[0];
    } else {
        return null;
    }
}
if (($_settings->userdata('DEPARTMENT') == 'Human Resource' && $_settings->userdata('EMPPOSITION') < 4) || $_settings->userdata('EMPLOYID') == 1191) {
    $qryyy = $conn->query("SELECT * FROM ir_requests where 
    (hr_status = 0 and ('{$_settings->userdata('EMPLOYID')}' = 1270 or '{$_settings->userdata('EMPLOYID')}' = 1289 or '{$_settings->userdata('EMPLOYID')}' = 1191)) or 
    (hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and da_status = 0) or 
    (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and ('{$_settings->userdata('EMPLOYID')}' = 1270 or '{$_settings->userdata('EMPLOYID')}' = 1289 or '{$_settings->userdata('EMPLOYID')}' = 1191) and da_status = 0 and quality_violation = 1) or
    (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
     ORDER BY `date_created` desc")->num_rows;
} else {
    if ($_settings->userdata('EMPPOSITION') == 5) {
        $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
        (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
        (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and da_status = 1 and appeal_status = 2 and ir_status = 1)
        ORDER BY `date_created` desc")->num_rows;
    } else {
        if ($_settings->userdata('EMPLOYID') == '1694') { // Leand 1694
            $dept1 = "MIS";
            $dept2 = "Facilities";
            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
            (`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 3) or
             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
              ORDER BY `date_created` desc")->num_rows;
        } elseif ($_settings->userdata('EMPLOYID') == '702') { // Joan
            $dept1 = 'Finance';
            $dept2 = 'Purchasing';
            $prodline1 = 'G & A';

            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
            ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            ((`department` = '{$dept1}' OR `department` = '{$dept2}' AND `productline` = '{$prodline1}') and ir_status = 2 and da_status = 3) or
            ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
             ORDER BY `date_created` desc")->num_rows;
        } elseif ($_settings->userdata('EMPLOYID') == '524') { // Charity
            $dept1 = 'Human Resource';
            $dept2 = 'Training';

            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and
            (`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and da_status = 1 and appeal_status = 1 and ir_status = 1) or
            ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 3) or
            ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            (ir_status = 2 and da_status = 1 and has_da = 1))
            ORDER BY `date_created` desc")->num_rows;
        } elseif ($_settings->userdata('EMPLOYID') == '8563') { // Bryan
            $dept1 = 'Production';
            $dept2 = 'Production - QFP';
            $dept3 = 'Production - RFC';
            $dept4 = 'Production / Non - TNR';
            $prodline1 = 'PL1 - PL4';
            $prodline2 = 'PL1 (ADGT)';
            $prodline3 = 'PL4 (ADGT)';
            $prodline4 = 'PL6 (ADLT)';

            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND (`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or 
            ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and da_status = 1 and appeal_status IN (4, 5, 3) and ir_status = 1) or 
            ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and ir_status = 2 and da_status = 3) or
            ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')
            ORDER BY `date_created` DESC")->num_rows;
        } elseif ($_settings->userdata('EMPLOYID') == '20') { // Noel
            $dept1 = 'Production';
            $dept2 = 'Store';
            $dept3 = 'IQA Warehouse';
            $dept4 = 'Logistics';
            $prodline1 = 'PL9 (AD/WHSE)';
            $prodline2 = 'G & A';
            $prodline3 = 'PL8 (AMS O/S)';


            $prodline4 = 'PL3 (ADCV)';
            $prodline5 = 'PL3 (ADCV) - Onsite';






            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            (((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
            (((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and ir_status = 2 and da_status = 3) or
            ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')) or
            
            
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND (`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and  ir_status = 2 and da_status = 3) or
            ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')
            ))
                ORDER BY `date_created` desc")->num_rows;
        }
        // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL6 (ADLT)') {
        //     $dept1 = 'Production';
        //     $dept2 = 'Production / Non - TNR';
        //     $prodline1 = 'PL6 (ADLT)';

        //     $qryyy = $conn->query("SELECT * FROM ir_requests WHERE hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
        //                             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline2}')) and da_status = 1 and appeal_status = 0 and ir_status = 1 ORDER BY `date_created` desc")->num_rows;
        // }
        elseif ($_settings->userdata('EMPLOYID') == '297') { // Erwin
            $dept1 = 'Quality Assurance';
            $prodline1 = 'G & A';
            $prodline2 = 'PL1 - PL4';
            $prodline3 = 'PL1 (ADGT)';
            $prodline4 = 'PL2 (AD/OS)';
            $prodline5 = 'PL3 (ADCV)';
            $prodline6 = 'PL3 (ADCV) - Onsite';
            $prodline7 = 'PL4 (ADGT)';
            $prodline8 = 'PL6 (ADLT)';
            $prodline9 = 'PL8 (AMS O/S)';

            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
            ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 0 and ir_status = 1) or 
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 4 and ir_status = 1) or 
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 5 and ir_status = 1) or 
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 3 and ir_status = 1) or 
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and ir_status = 2 and da_status = 3) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                ORDER BY `date_created` desc")->num_rows;
        } elseif (($_settings->userdata('EMPLOYID') == '1023')) { // Adonis
            $dept1 = 'Equipment Engineering';
            $prodline1 = 'G & A';
            $prodline2 = 'PL1 (ADGT)';
            $prodline3 = 'PL2 (AD/OS)';
            $prodline4 = 'PL3 (ADCV)';
            $prodline5 = 'PL3 (ADCV) - Onsite';
            $prodline6 = 'PL4 (ADGT)';
            $prodline7 = 'PL6 (ADLT)';
            $prodline8 = 'PL8 (AMS O/S)';

            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
            ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and ir_status = 2 and da_status = 3) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
            ORDER BY `date_created` asc")->num_rows;
        } elseif ($_settings->userdata('EMPLOYID') == '1170') { // Realyn
            $dept1 = 'Process Engineering';
            $prodline1 = 'G & A';
            $prodline2 = 'PL1 - PL4';
            $prodline3 = 'PL2 (AD/OS)';
            $prodline4 = 'PL3 (ADCV)';
            $prodline5 = 'PL3 (ADCV) - Onsite';
            $prodline6 = 'PL6 (ADLT)';
            $prodline7 = 'PL8 (AMS O/S)';

            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
            ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and  ir_status = 2 and da_status = 3) or
            (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
             ORDER BY `date_created` desc")->num_rows;
        } elseif ($_settings->userdata('EMPLOYID') == '1065') { // Tess
            $dept1 = 'Production';
            $dept2 = 'Production / PE';
            $prodline1 = 'PL2 (AD/OS)';

            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
            ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and  ir_status = 2 and da_status = 3) or
            (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
            ORDER BY `date_created` asc")->num_rows;
        } elseif ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
            $dept1 = "PPC";
            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND 
            `department` = '{$dept1}' and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            (`department` = '{$dept1}' and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            (`department` = '{$dept1}' and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            (`department` = '{$dept1}' and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            (`department` = '{$dept1}' and  ir_status = 2 and da_status = 3) or
            (`department` = '{$dept1}' and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
             ORDER BY `date_created` desc")->num_rows;
        } elseif ($_settings->userdata('EMPLOYID') == '600') { // tin
            $prodline1 = 'PL3 (ADCV)';
            $prodline2 = 'PL3 (ADCV) - Onsite';
            $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND 
            (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
            ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
            ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
            ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
            ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and  ir_status = 2 and da_status = 3) or
            ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
             ORDER BY `date_created` desc")->num_rows;
        } elseif ($is_quality > 0 || extractAuditor($word) == 'Auditor') { //auditor
            $ext = extractAuditor($word);
            $qryyy = $conn->query("SELECT * FROM ir_requests where 
            (hr_status = 0 and quality_violation = 2) or
            (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and quality_violation = 2 and da_status = 0) or 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
            (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
            (`ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
           ORDER BY `date_created` desc")->num_rows;
        } else { //supervisors
            $qryyy = $conn->query("SELECT * FROM ir_requests where 
            (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
          (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
          (`ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
         ORDER BY `date_created` desc")->num_rows;
        }


        // $qry = $conn->query("SELECT * FROM ir_requests WHERE hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc")->num_rows;
    }
}
if (!empty($_settings->userdata('EMPLOYID')) && ($_settings->userdata('EMPPOSITION') > 1 || $_settings->userdata('DEPARTMENT') == 'Human Resource' || $_settings->userdata('EMPLOYID') == 16411 || $_settings->userdata('EMPLOYID') == 16615   || extractAuditor($word) == 'Auditor' || $_settings->userdata('EMPLOYID') == 1191)) {
    $dis_qry = $conn->query("SELECT * FROM ir_requests where (hr_status=2 and ir_status = 0 and requestor_id = " . $_settings->userdata('EMPLOYID') . ") or  (ir_status = 3 and requestor_id = " . $_settings->userdata('EMPLOYID') . ") ORDER BY `date_created` desc")->num_rows;
    $qryyy = $qryyy < 0 ? 0 : $qryyy;
    $inbox_ir = $conn->query("SELECT * FROM ir_requests where hr_status = 1 and ir_status !=2 and ir_status != 3 and ir_status != 4 and why1 IS NULL and emp_no = " . $_settings->userdata('EMPLOYID') . " ORDER BY `date_created` desc")->num_rows;
    $inbox_da = $conn->query("SELECT * FROM ir_requests where ir_status = 2 and has_da = 1 and acknowledge_da = 0 and da_status = 4 and emp_no =  '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc")->num_rows;


    $issue_da = $conn->query("SELECT ir.*,il.* FROM ir_requests ir INNER JOIN ir_list il ON ir.ir_no = il.ir_no where ir.ir_status = 2 and ir.has_da = 0 and il.valid = 1")->num_rows;
} elseif ($_settings->userdata('EMPPOSITION') == 1) {
    $inbox_ir = $conn->query("SELECT * FROM ir_requests where hr_status = 1 and ir_status !=2 and ir_status != 3 and ir_status != 4 and why1 IS NULL and emp_no = " . $_settings->userdata('EMPLOYID') . " ORDER BY `date_created` desc")->num_rows;
    $inbox_da = $conn->query("SELECT * FROM ir_requests where ir_status = 2 and has_da = 1 and acknowledge_da = 0 and da_status = 4 and emp_no =  '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc")->num_rows;
    $dis_qry = $conn->query("SELECT * FROM ir_requests where (hr_status=2 and ir_status = 0 and requestor_id = " . $_settings->userdata('EMPLOYID') . ") or  (ir_status = 3 and requestor_id = " . $_settings->userdata('EMPLOYID') . ") ORDER BY `date_created` desc")->num_rows;
} else {
    $dis_qry = 0;
    $qryyy = 0;
    $inbox_ir = 0;
    $inbox_da = 0;
    $issue_da = 0;
}
?>
<!-- -->

</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="<?php echo base_url ?>admin" class="brand-link bg-primary text-sm">
        <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Store Logo" class="brand-image  elevation-3 bg-black" style="height: 1.8rem;max-height: unset">
        <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
        <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
        </div>
        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
        </div>
        <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
        <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                    <!-- Sidebar user panel (optional) -->
                    <div class="clearfix"></div>
                    <!-- Sidebar Menu -->

                    <nav class="mt-4">
                        <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
                            <?php
                            if ($_settings->userdata('EMPPOSITION') != 1 || empty($_settings->userdata('EMPNAME')) || $_settings->userdata('DEPARTMENT') == 'Human Resource') {
                            ?>
                                <li class="nav-item dropdown">
                                    <a href="./" class="nav-link nav-home">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>
                                            Dashboard <?php echo (empty($_settings->userdata('EMPPOSITION')) ? $notif1 : ''); ?><?php echo (!empty($_settings->userdata('EMPPOSITION')) ? $notif1 : ''); ?>
                                        </p>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                            <?php
                            if (!empty($_settings->userdata('EMPNAME'))) {
                            ?>
                                <li class="nav-item">
                                    <a href="#" class="nav-link nav-is-tree nav-inbox">
                                        <i class="nav-icon fas fa-envelope"></i>
                                        <p>
                                            Inbox
                                            <i class="right fas fa-angle-left"></i>
                                        </p> <?php echo $inbox_ir + $inbox_da ? '<span class="badge badge-warning">New</span>' : '' ?>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=inbox/pa_list" class="nav-link nav-pa_list">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>PA</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=inbox/pcnlist" class="nav-link nav-pcnlist">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>PCN</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=inbox/ircompletion" class="nav-link nav-ircompletion">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>IR</p> <span class="badge badge-warning rounded-pill"><?php echo $inbox_ir ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=inbox/issuedDA" class="nav-link nav-issuedDA">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>DA</p> <span class="badge badge-warning rounded-pill"><?php echo $inbox_da ?>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php
                            }
                            ?>
                            <!-- <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=overtime_form" class="nav-link nav-overtime">
                    <i class="nav-icon fas fa-user-clock"></i>
                    <p>
                      Overtime Request
                    </p>
                  </a>
                </li> -->
                            <?php if ($_settings->userdata('EMPPOSITION') > 1 || $_settings->userdata('log_category') >= 1) { ?>
                                <?php if ($_settings->userdata('EMPPOSITION') > 1) { ?>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link nav-is-tree nav-appraisalForms nav-appraisalNeeds nav-appraisalApproval">
                                            <i class="nav-icon fas fa-chart-line"></i>
                                            <p>
                                                Performance Appraisal
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <?php if ($_settings->userdata('EMPPOSITION') > 2) {
                                            ?>
                                                <li class="nav-header">Approvals</li>
                                                <li class="nav-item">
                                                    <a href="<?php echo base_url ?>admin/?page=appraisalApproval/approvals" class="nav-link nav-approvals">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>For Approvals</p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="<?php echo base_url ?>admin/?page=appraisalApproval/approvalhistory" class="nav-link nav-approvalhistory">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>Approval History</p>
                                                    </a>
                                                </li>
                                                <li class="nav-header">Requests</li>
                                            <?php
                                            } ?>

                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/new_pa" class="nav-link nav-new_pa">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Create PA</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/forcompletion" class="nav-link nav-forcompletion">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>For Completion</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/pending" class="nav-link nav-pending">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Pending</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/approved" class="nav-link nav-approved">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Approved</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/disapproved" class="nav-link nav-disapproved">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Disapproved</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (empty($_settings->userdata('EMPNAME'))) { ?>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link nav-is-tree nav-appraisalFormats nav-appraisalForms nav-appraisalNeeds nav-appraisalTrainings nav-appraisalCutoffPeriod">
                                            <i class="nav-icon fas fa-chart-line"></i>
                                            <p>
                                                Performance Appraisal
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalFormats/template" class="nav-link nav-template">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Formats</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalTrainings/trainlist" class="nav-link nav-trainlist">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Trainings</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalCutoffPeriod/cutofflist" class="nav-link nav-cutofflist">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Cutoff Periods</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalNeeds" class="nav-link nav-appraisalNeeds">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Needs Appraisal</p>
                                                </a>
                                            </li>
                                            <li class="nav-header">Requests</li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/forcompletion" class="nav-link nav-forcompletion">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>For Completion</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/pending" class="nav-link nav-pending">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Pending</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/approved" class="nav-link nav-approved">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Approved</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=appraisalForms/disapproved" class="nav-link nav-disapproved">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Disapproved</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if ($_settings->userdata('EMPPOSITION') > 1) { ?>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link nav-is-tree nav-pcnForms nav-pcnApproval">
                                            <i class="nav-icon fas fa-people-arrows"></i>
                                            <p>
                                                Payroll Change Notice
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <?php if ($_settings->userdata('EMPPOSITION') > 2) {
                                            ?>
                                                <li class="nav-header">Approvals</li>
                                                <li class="nav-item">
                                                    <a href="<?php echo base_url ?>admin/?page=pcnApproval/approvals" class="nav-link nav-approvals">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>For Approvals</p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="<?php echo base_url ?>admin/?page=pcnApproval/approvalhistory" class="nav-link nav-approvalhistory">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>Approval History</p>
                                                    </a>
                                                </li>
                                                <li class="nav-header">Requests</li>
                                            <?php
                                            } ?>

                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=pcnForms/manage_pcn" class="nav-link nav-manage_pcn">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Create PCN</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=pcnForms/pending" class="nav-link nav-pending">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Pending</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=pcnForms/approved" class="nav-link nav-approved">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Approved</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=pcnForms/disapproved" class="nav-link nav-disapproved">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Disapproved</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (empty($_settings->userdata('EMPNAME')) && $_settings->userdata('log_category') >= 0) { ?>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link nav-is-tree nav-pcnForms">
                                            <i class="nav-icon fas fa-people-arrows"></i>
                                            <p>
                                                Payroll Change Notice
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=pcnForms/pending" class="nav-link nav-pending">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Pending</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=pcnForms/approved" class="nav-link nav-approved">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Approved</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=pcnForms/disapproved" class="nav-link nav-disapproved">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Disapproved</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <!-- <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=ir_form" class="nav-link nav-ir">
                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                    <p>
                      Incident Report
                    </p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=nda_form" class="nav-link nav-nda">
                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                    <p>
                      Notice of Diciplinary Action
                    </p>
                  </a>
                </li> -->
                            <?php } ?>

                            <li class="nav-item">
                                <a href="#" class="nav-link nav-is-tree nav-incidentreport">
                                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                                    <p>
                                        Incident Report
                                        <i class="right fas fa-angle-left"></i>
                                    </p> <?php
                                            if (($_settings->userdata('DEPARTMENT') == 'Human Resource'  || $_settings->userdata('EMPLOYID') == 1191) && $_settings->userdata('EMPPOSITION') > 4) {
                                                echo ($dis_qry + $qryyy + $issue_da) > 0 ? '<span class="badge badge-warning">New</span>' : '';
                                            } else {
                                                echo ($dis_qry + $qryyy) > 0 ? '<span class="badge badge-warning">New</span>' : '';
                                            }


                                            ?>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($_settings->userdata('EMPPOSITION') > 1 || $_settings->userdata('DEPARTMENT') == 'Human Resource' || $_settings->userdata('EMPLOYID') == 13019 || extractAuditor($word) == 'Auditor' || $_settings->userdata('EMPLOYID') == 1191) {
                                    ?>
                                        <li class="nav-header">Approvals</li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=incidentreport/approveIR" class="nav-link nav-approveIRDA">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>For Approvals </p>
                                                <?php if (($_settings->userdata('DEPARTMENT') == 'Human Resource'  || $_settings->userdata('EMPLOYID') == 1191) && $_settings->userdata('EMPPOSITION') > 4) { ?>
                                                    <span class="badge badge-warning rounded-pill"><?php echo $qryyy + $issue_da ?></span>
                                                <?php  } else { ?>
                                                    <span class="badge badge-warning rounded-pill"><?php echo $qryyy  ?></span>
                                                <?php } ?>

                                            </a>
                                        </li>
                                        <li class="nav-header">Requests IR</li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url ?>admin/?page=incidentreport/createNewIRDA/new_ir" class="nav-link nav-createNewIRDA">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Create IR
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url ?>admin/?page=incidentreport/pendingIRDA" class="nav-link nav-pendingIRDA">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Pending
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url ?>admin/?page=incidentreport/doneIRDA" class="nav-link nav-doneIRDA">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Approve
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url ?>admin/?page=incidentreport/disapprovedIRDA" class="nav-link nav-disapprovedIRDA">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Disapprove <span class="badge badge-warning rounded-pill"><?php echo $dis_qry ?></span>
                                            </p>
                                        </a>
                                    </li>
                                    <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource' || $_settings->userdata('EMPLOYID') == 1191) { ?>
                                        <!-- <li class="nav-header">Issue DA</li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=incidentreport/DA" class="nav-link nav-DA">
                                                <i class="nav-icon far fa-circle"></i>
                                                <p>
                                                    Issue DA
                                                </p> <span class="badge badge-warning rounded-pill"><?php echo $issue_da ?>
                                            </a>
                                        </li> -->
                                        <li class="nav-header">Maintenance</li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=incidentreport/manageIRDA" class="nav-link nav-manageIRDA">
                                                <i class="nav-icon far fa-circle"></i>
                                                <p>
                                                    Manage IR/DA
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=incidentreport/irCodenumber" class="nav-link nav-irCodenumber">
                                                <i class="nav-icon far fa-circle"></i>
                                                <p>
                                                    Code Number
                                                </p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php if ($_settings->userdata('EMPLOYID') == 1681) { ?>
                                <li class="nav-item">
                                    <a href="#" class="nav-link nav-is-tree nav-exitClearance">
                                        <i class="nav-icon fa-solid fa-person-through-window"></i>
                                        <p>
                                            Exit Clearance
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">

                                        <li class="nav-header">Approvals</li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=exitClearance/approvedClearance" class="nav-link nav-approvedClearance">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>For Approvals</p>
                                            </a>
                                        </li>
                                        <li class="nav-header">Request Exit Clearance</li>
                                        <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource') {
                                        ?>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url ?>admin/?page=exitClearance/createNewclearance/new_exit" class="nav-link nav-createNewclearance">
                                                    <i class="nav-icon far fa-circle"></i>
                                                    <p>
                                                        Create Exit Clearance
                                                    </p>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <li class="nav-header">Manage Exit Clearance</li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=exitClearance/pendingClearance" class="nav-link nav-pendingClearance">
                                                <i class="nav-icon far fa-circle"></i>
                                                <p>
                                                    Pending
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=exitClearance/doneIRDA" class="nav-link nav-doneIRDA">
                                                <i class="nav-icon far fa-circle"></i>
                                                <p>
                                                    Approve
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <!-- <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=receiving" class="nav-link nav-receiving">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                          Receiving
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=back_order" class="nav-link nav-back_order">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>
                          Back Order
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=return" class="nav-link nav-return">
                        <i class="nav-icon fas fa-undo"></i>
                        <p>
                          Return List
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=stocks" class="nav-link nav-stocks">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                          Stocks
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=sales" class="nav-link nav-sales">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                          Sale List
                        </p>
                      </a>
                    </li> -->
                            <?php if ($_settings->userdata('EMPPOSITION') == 1 || $_settings->userdata('log_category') == 1) { ?>
                                <!-- <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=maintenance/supplier" class="nav-link nav-maintenance_supplier">
                    <i class="nav-icon fas fa-truck-loading"></i>
                    <p>
                      Supplier List
                    </p>
                  </a>
                </li> -->
                            <?php } ?>
                            <?php if ($_settings->userdata('EMPPOSITION') != 0) { ?>
                                <!-- <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=maintenance/item" class="nav-link nav-maintenance_item">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                          Item List
                        </p>
                      </a>
                    </li> -->
                            <?php } ?>
                            <?php if ($_settings->userdata('log_category') == 1) { ?>
                                <li class="nav-header">Maintenance</li>
                                <li class="nav-item dropdown">
                                    <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            User List
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system">
                                        <i class="nav-icon fas fa-cogs"></i>
                                        <p>
                                            Settings
                                        </p>
                                    </a>
                                </li>
                            <?php } ?>

                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar-corner"></div>
    </div>
    <!-- /.sidebar -->
</aside>
<script>
    // var page;
    // $(document).ready(function() {
    //   page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    //   page = page.replace(/\//gi, '_');

    //   if ($('.nav-link.nav-' + page).length > 0) {
    //     $('.nav-link.nav-' + page).addClass('active')
    //     if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
    //       $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
    //       $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
    //     }
    //     if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
    //       $('.nav-link.nav-' + page).parent().addClass('menu-open')
    //     }

    //   }

    //   $('#receive-nav').click(function() {
    //     $('#uni_modal').on('shown.bs.modal', function() {
    //       $('#find-transaction [name="tracking_code"]').focus();
    //     })
    //     uni_modal("Enter Tracking Number", "transaction/find_transaction.php");
    //   })
    // })
    var page;
    $(document).ready(function() {
        page = '<?php
                // echo isset($_GET['page']) ? $_GET['page'] : 'home'
                if ($_settings->userdata('EMPPOSITION') == 1 && $_settings->userdata('DEPARTMENT') != 'Human Resource') {
                    echo isset($_GET['page']) ? $_GET['page'] : 'appraisal_form';
                } else {
                    echo  isset($_GET['page']) ? $_GET['page'] : 'home';
                }
                ?>';
        page = page.replace(/\//gi, '_');
        console.log(page)
        var str = page;
        var parts = str.split("_");
        var result = parts[0];
        var after = parts[1];
        // var pattern = /applicants(\w+)/;
        // var match = str.match(pattern);
        // var str = page;
        var pattern = new RegExp(result + "(\\w+)");
        var match = str.match(pattern);
        if (match) {
            var wordAfterApplicants = match[0];
            console.log("Word after " + result + ": " + after);

            console.log(after)
            if ($('.nav-link.nav-' + result).length > 0) {
                $('.nav-link.nav-' + result).addClass('active')
                if ($('.nav-link.nav-' + result).hasClass('nav-is-tree') == true) {
                    $('.nav-link.nav-' + result).parent().addClass('menu-open')
                    $('.nav-link.nav-' + after).addClass('active')
                }
            }
        } else {
            console.log("No word found after '" + result + "'.");
            if ($('.nav-link.nav-' + page).length > 0) {
                $('.nav-link.nav-' + page).addClass('active')
                if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
                    $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
                    $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')

                }
                if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
                    $('.nav-link.nav-' + page).parent().addClass('menu-open')
                }
            }
        }
    })
</script>