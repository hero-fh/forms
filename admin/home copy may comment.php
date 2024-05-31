<!-- notifications -->
<?php
// FORM COMPLETION -----------------------
$qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` < 3 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");

if (!empty($_settings->userdata('EMPNAME'))) {
    $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 1 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc;");
}

if (empty($_settings->userdata('EMPNAME'))) {
    $qry = $conn->query("SELECT * FROM appraisal_requests WHERE ((pa_status < 4 AND pa_type = 1) OR (pa_status < 5 AND (pa_type != 1 OR pa_type IS NULL))) ORDER BY date_created DESC;");
}

$notif1 = "";
if ($qry->num_rows > 0) {
    $notif1 = '<span class="badge badge-warning">New</span>';
}
// NEEDS APPRAISAL --------------------
if (!empty($_settings->userdata('EMPNAME'))) {
    $appvalsCount = 0;

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

        if ($row['pa_level'] == 0.1 && $monthsDifference >= 3) {
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
}
if (empty($_settings->userdata('EMPNAME'))) {
    $appvalsCount = 0;

    $qry = $conn->query("SELECT * FROM employee_masterlist WHERE ACCSTATUS = 1;");

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

        if ($row['pa_level'] == 0.1 && $monthsDifference >= 3) {
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
}

//PCN ----------------------------

if (!empty($_settings->userdata('EMPNAME'))) {
    if ($_settings->userdata('EMPPOSITION') == 5) {
        $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
        FROM pcn_requests
        INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
        WHERE pcn_requests.pcn_status = 2 AND pa_form_code != ''
        
        UNION
        
        SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
        FROM pcn_requests
        WHERE pcn_status = 2 AND pa_form_code IS NULL
        ORDER BY `date_created` ASC;");
    }
    if ($_settings->userdata('EMPPOSITION') == 4) {
        $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE `pcn_status` = 0 AND (`requestor_department` = '{$_settings->userdata('DEPARTMENT')}' 
                        AND `requestor_productline` = '{$_settings->userdata('PRODLINE')}') ORDER BY `date_created` ASC");

        $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
        FROM pcn_requests
        INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
        WHERE (pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}' AND pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') 
            AND (pcn_requests.pcn_status = 2 AND pcn_requests.pa_form_code != '')
        
        UNION
        
        SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
        FROM pcn_requests
        WHERE (pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}' AND pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}')
            AND (pcn_status = 0 AND pa_form_code IS NULL)
        ORDER BY `date_created` ASC;");
    }
    if ($_settings->userdata('EMPPOSITION') == 4) {
        if ($_settings->userdata('EMPLOYID') == '1694') { // Leandro
            $dept1 = "MIS";
            $dept2 = "Facilities";
            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE (pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}')
                AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE (pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}')
                AND (pcn_status = 0 AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
        }
        if ($_settings->userdata('EMPLOYID') == '702') { // Joan
            $dept1 = 'Finance';
            $dept2 = 'Purchasing';
            $prodline1 = 'G &amp; A';

            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}') )
                AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}'))
                AND (pcn_status = 0 AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
        }
        if ($_settings->userdata('EMPLOYID') == '524') { // Charity
            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE (((pcn_requests.pcn_status = 0 AND pcn_requests.requestor_department = 'Human Resource') OR pcn_requests.pcn_status = 1) AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE (((pcn_requests.pcn_status = 0 AND pcn_requests.requestor_department = 'Human Resource') OR pcn_requests.pcn_status = 1) AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
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

            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}'  OR pcn_requests.requestor_department = '{$dept4}') 
                    AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}'  OR pcn_requests.requestor_productline = '{$prodline4}') )
                AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}' OR pcn_requests.requestor_department = '{$dept4}') 
                    AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}'))
                AND (pcn_status = 0 AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
        }
        if ($_settings->userdata('EMPLOYID') == '20') { // Noel
            $dept1 = 'Production';
            $dept2 = 'Store';
            $dept3 = 'IQA Warehouse';
            $dept4 = 'Logistics';
            $prodline1 = 'PL9 (AD/WHSE)';
            $prodline2 = 'G &amp; A';
            $prodline3 = 'PL8 (AMS O/S)';

            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}'  OR pcn_requests.requestor_department = '{$dept4}') 
                    AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}') )
                AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}' OR pcn_requests.requestor_department = '{$dept4}') 
                    AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}'))
                AND (pcn_status = 0 AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
        }
        // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODLINE') == 'PL6 (ADLT)') {
        //     $dept1 = 'Production';
        //     $dept2 = 'Production / Non - TNR';
        //     $prodline1 = 'PL6 (ADLT)';

        //     $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
        //     FROM pcn_requests
        //     INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
        //     WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') 
        //             AND (pcn_requests.requestor_productline = '{$prodline1}') )
        //         AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

        //     UNION

        //     SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
        //     FROM pcn_requests
        //     WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') 
        //             AND (pcn_requests.requestor_productline = '{$prodline1}'))
        //         AND (pcn_status = 0 AND pa_form_code IS NULL)
        //     ORDER BY `date_created` ASC;");
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

            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                    AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}') OR pcn_requests.requestor_productline = '{$prodline9}')
                AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE ((pcn_requests.requestor_department = '{$dept1}') 
            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}') OR pcn_requests.requestor_productline = '{$prodline9}')
                AND (pcn_status = 0 AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
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

            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                    AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}'))
                AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                    AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}'))
                AND (pcn_status = 0 AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
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

            $qry = $conn->query("SELECT 
            pcn_requests.*, 
            appraisal_requests.form_format, 
            appraisal_requests.pa_form_no, 
            appraisal_requests.pa_status
        FROM 
            pcn_requests
        INNER JOIN 
            appraisal_requests 
        ON 
            pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
        WHERE 
            pcn_requests.requestor_department = '{$dept1}' AND 
            pcn_requests.requestor_productline IN ('{$prodline1}', '{$prodline2}', '{$prodline3}', '{$prodline4}', '{$prodline5}', '{$prodline6}', '{$prodline7}') AND 
            pcn_requests.pcn_status = 0 AND 
            pcn_requests.pa_form_code IS NOT NULL
    
        UNION
    
        SELECT 
            pcn_requests.*, 
            NULL AS form_format, 
            NULL AS pa_form_no, 
            NULL AS pa_status
        FROM 
            pcn_requests
        WHERE 
            pcn_requests.requestor_department = '{$dept1}' AND 
            pcn_requests.requestor_productline IN ('{$prodline1}', '{$prodline2}', '{$prodline3}', '{$prodline4}', '{$prodline5}', '{$prodline6}', '{$prodline7}') AND 
            pcn_requests.pcn_status = 0 AND 
            pcn_requests.pa_form_code IS NULL
        ORDER BY 
            `date_created` ASC;
    ");
        }
        if ($_settings->userdata('EMPLOYID') == '1065') { // Tess
            $dept1 = 'Production';
            $dept2 = 'Production / PE';
            $prodline1 = 'PL2 (AD/OS)';

            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}') )
                AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}'))
                AND (pcn_status = 0 AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
        }
    }
    if ($_settings->userdata('EMPPOSITION') == 3) {
        if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
            $dept1 = "PPC";
            $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
            FROM pcn_requests
            INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
            WHERE (pcn_requests.requestor_department = '{$dept1}')
                AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
            
            UNION
            
            SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
            FROM pcn_requests
            WHERE (pcn_requests.requestor_department = '{$dept1}')
                AND (pcn_status = 0 AND pa_form_code IS NULL)
            ORDER BY `date_created` ASC;");
        }
    }

    // if ($_settings->userdata('EMPPOSITION') == 3) {
    //     if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODLINE') == 'PL3 (ADCV)') {
    //         $dept1 = "Process Engineering";
    //         $prodline1 = 'PL3';

    //         $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE 
    //                                 (`requestor_department` = '{$dept1}' AND `requestor_productline` = '{$prodline1}') ORDER BY `date_created` ASC");
    //     }
    // }
    // if ($_settings->userdata('EMPLOYID') == '600') {
    //     $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
    //     FROM pcn_requests
    //     INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
    //     WHERE ((pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}') 
    //             AND (pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') )
    //         AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

    //     UNION

    //     SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
    //     FROM pcn_requests
    //     WHERE ((pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}') 
    //             AND (pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') )
    //         AND (pcn_status = 0 AND pa_form_code IS NULL)
    //     ORDER BY `date_created` ASC;");
    // }
} else {
    $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE `pcn_status` = 2 ORDER BY `date_created` ASC");
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

$notif222 = "";

if ($pcnappvalsCount > 0) {
    $notif222 = '<span class="badge badge-warning">New</span>';
}

?>
<!-- -->

<!-- <img src="<?php echo validate_image('uploads\telford_prms_logo.png') ?>" alt=""> -->
<h3 class="">Dashboard</h3><br>
<div class="row  justify-content-left">
    <div class="col-md-12">

        <!-- Performance Appraisal (PA) -->
        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header">
                <h3 class="card-title"><b>Performance Appraisal (PA)</b></h3>&nbsp;<?php echo (empty($_settings->userdata('EMPPOSITION')) ? $notif1 : ''); ?><?php echo (!empty($_settings->userdata('EMPPOSITION')) ? $notif1 : ''); ?>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php
                if ($_settings->userdata('EMPPOSITION') > 2) {
                ?>
                    <div class="row  justify-content-left">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-primary elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalApproval/approvals" ?>"><i class="fas fa-file-invoice"></i></a></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">For Approvals</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        if ($_settings->userdata('EMPPOSITION') == 5) {
                                            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 4 ORDER BY `date_created` desc");
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 4) {
                                            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 2 AND (`requestor_department` = '{$_settings->userdata('DEPARTMENT')}' 
                                                            AND `requestor_productline` = '{$_settings->userdata('PRODLINE')}') ORDER BY `date_created` desc");
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 4) { // leandro
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

                                        $appvalsCount = $qry->num_rows;
                                        echo $appvalsCount;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-success elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalApproval/approvalhistory" ?>"><i class="fas fa-file-invoice"></i></a></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Signed PA</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        $appvalsCount = 0;

                                        if ($_settings->userdata('EMPPOSITION') == 5) {
                                            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `od_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 4) {
                                            $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");

                                            if ($_settings->userdata('EMPPOSITION') == 4 && $_settings->userdata('DEPARTMENT') == 'Human Resource') {
                                                $qry = $conn->query("SELECT * FROM `appraisal_requests` WHERE (`hr_name` = '{$_settings->userdata('EMPLOYID')}' OR `dh_name` = '{$_settings->userdata('EMPLOYID')}') ORDER BY `date_created` desc");
                                            }
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 3) {
                                            if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
                                                $dept1 = "PPC";
                                                $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                            }
                                        }

                                        $appvalsCount = $qry->num_rows;
                                        echo $appvalsCount;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                    <hr>
                <?php
                }
                ?>

                <?php
                if (empty($_settings->userdata('EMPNAME'))) {

                ?>
                    <div class="row  justify-content-left">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-info elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalNeeds/" ?>"><i class="fas fa-file-invoice"></i></a></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Needs Appraisal</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        echo $appvalsCount0;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-warning elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalForms/forcompletion" ?>"><i class="fas fa-file-invoice"></i></a></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">PA for Completion</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        $appvalsCount = 0;

                                        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 0 ORDER BY `date_created` desc");

                                        $appvalsCount = $qry->num_rows;
                                        echo $appvalsCount;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                <?php
                }
                ?>

                <?php
                if ($_settings->userdata('EMPPOSITION') > 1) {

                ?>
                    <div class="row  justify-content-left">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-info elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalForms/new_pa" ?>"><i class="fas fa-file-invoice"></i></a></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Needs Appraisal</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        echo $appvalsCount0;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-warning elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalForms/forcompletion" ?>"><i class="fas fa-file-invoice"></i></a></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">PA for Completion</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        $appvalsCount = 0;

                                        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 1 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");

                                        $appvalsCount = $qry->num_rows;
                                        echo $appvalsCount;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>

                <?php
                }
                ?>

                <div class="row  justify-content-left">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-primary elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalForms/pending" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pending PA</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    $qry = $conn->query("SELECT * FROM appraisal_requests WHERE ((pa_status < 4 AND pa_type = 1) OR (pa_status < 5 AND (pa_type != 1 OR pa_type IS NULL))) AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");

                                    if (empty($_settings->userdata('EMPNAME'))) {
                                        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE ((pa_status < 4 AND pa_type = 1) OR (pa_status < 5 AND (pa_type != 1 OR pa_type IS NULL))) ORDER BY date_created DESC;");
                                    }

                                    $appvalsCount = $qry->num_rows;
                                    echo $appvalsCount;
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-success elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalForms/approved" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Approved PA</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    $appvalsCount = 0;
                                    if ($_settings->userdata('EMPPOSITION') > 1) {
                                        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE ((pa_status = 4 AND pa_type = 1) OR (pa_status = 5 AND (pa_type != 1 OR pa_type IS NULL))) AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                    }
                                    if (empty($_settings->userdata('EMPNAME'))) {
                                        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE ((pa_status = 4 AND pa_type = 1) OR (pa_status = 5 AND (pa_type != 1 OR pa_type IS NULL))) ORDER BY `date_created` desc");
                                    }
                                    $appvalsCount = $qry->num_rows;
                                    echo $appvalsCount;
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-danger elevation-1"><a href="<?php echo base_url . "admin/?page=appraisalForms/disapproved" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Disapproved PA</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    if ($_settings->userdata('EMPPOSITION') == 2) {
                                        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 6 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                    }
                                    if (empty($_settings->userdata('EMPNAME'))) {
                                        $qry = $conn->query("SELECT * FROM appraisal_requests WHERE `pa_status` = 6 ORDER BY `date_created` desc");
                                    }
                                    $appvalsCount = $qry->num_rows;
                                    echo $appvalsCount;
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- Payroll Change Notice (PCN) -->
        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header">
                <h3 class="card-title"><b>Payroll Change Notice (PCN)</b></h3>&nbsp;<?php echo (empty($_settings->userdata('EMPPOSITION')) ? $notif222 : ''); ?><?php echo (!empty($_settings->userdata('EMPPOSITION')) ? $notif222 : ''); ?>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php
                if ($_settings->userdata('EMPPOSITION') > 2) {
                ?>
                    <div class="row  justify-content-left">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-primary elevation-1"><a href="<?php echo base_url . "admin/?page=pcnApproval/approvals" ?>"><i class="fas fa-file-invoice"></i></a></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">For Approvals</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        if (!empty($_settings->userdata('EMPNAME'))) {
                                            if ($_settings->userdata('EMPPOSITION') == 5 || $_settings->userdata('EMPPOSITION') == 6) {
                                                $meron = "test";

                                                $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                FROM pcn_requests
                                                INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                WHERE pcn_requests.pcn_status = 2 AND pa_form_code != ''
                                                
                                                UNION
                                                
                                                SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                FROM pcn_requests
                                                WHERE pcn_status = 2 AND pa_form_code IS NULL
                                                ORDER BY `date_created` ASC;");
                                            }
                                            if ($_settings->userdata('EMPPOSITION') == 4) {
                                                $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE `pcn_status` = 0 AND (`requestor_department` = '{$_settings->userdata('DEPARTMENT')}' 
                                                                AND `requestor_productline` = '{$_settings->userdata('PRODLINE')}') ORDER BY `date_created` ASC");

                                                $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                FROM pcn_requests
                                                INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                WHERE (pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}' AND pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') 
                                                    AND (pcn_requests.pcn_status = 2 AND pcn_requests.pa_form_code != '')
                                                
                                                UNION
                                                
                                                SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                FROM pcn_requests
                                                WHERE (pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}' AND pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}')
                                                    AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                ORDER BY `date_created` ASC;");
                                            }
                                            if ($_settings->userdata('EMPPOSITION') == 4) {
                                                if ($_settings->userdata('EMPLOYID') == '1694') { // Leandro
                                                    $dept1 = "MIS";
                                                    $dept2 = "Facilities";
                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE (pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}')
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE (pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}')
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
                                                }
                                                if ($_settings->userdata('EMPLOYID') == '702') { // Joan
                                                    $dept1 = 'Finance';
                                                    $dept2 = 'Purchasing';
                                                    $prodline1 = 'G &amp; A';

                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}') )
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}'))
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
                                                }
                                                if ($_settings->userdata('EMPLOYID') == '524') { // Charity
                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE (((pcn_requests.pcn_status = 0 AND pcn_requests.requestor_department = 'Human Resource') OR pcn_requests.pcn_status = 1) AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE (((pcn_requests.pcn_status = 0 AND pcn_requests.requestor_department = 'Human Resource') OR pcn_requests.pcn_status = 1) AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
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

                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}'  OR pcn_requests.requestor_department = '{$dept4}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}'  OR pcn_requests.requestor_productline = '{$prodline4}') )
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}' OR pcn_requests.requestor_department = '{$dept4}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}'))
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
                                                }
                                                if ($_settings->userdata('EMPLOYID') == '20') { // Noel
                                                    $dept1 = 'Production';
                                                    $dept2 = 'Store';
                                                    $dept3 = 'IQA Warehouse';
                                                    $dept4 = 'Logistics';
                                                    $prodline1 = 'PL9 (AD/WHSE)';
                                                    $prodline2 = 'G &amp; A';
                                                    $prodline3 = 'PL8 (AMS O/S)';

                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}'  OR pcn_requests.requestor_department = '{$dept4}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}') )
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}' OR pcn_requests.requestor_department = '{$dept3}' OR pcn_requests.requestor_department = '{$dept4}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}'))
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
                                                }
                                                // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODLINE') == 'PL6 (ADLT)') {
                                                //     $dept1 = 'Production';
                                                //     $dept2 = 'Production / Non - TNR';
                                                //     $prodline1 = 'PL6 (ADLT)';

                                                //     $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                //     FROM pcn_requests
                                                //     INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                //     WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') 
                                                //             AND (pcn_requests.requestor_productline = '{$prodline1}') )
                                                //         AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

                                                //     UNION

                                                //     SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                //     FROM pcn_requests
                                                //     WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') 
                                                //             AND (pcn_requests.requestor_productline = '{$prodline1}'))
                                                //         AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                //     ORDER BY `date_created` ASC;");
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

                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}') OR pcn_requests.requestor_productline = '{$prodline9}')
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                                                    AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}') OR pcn_requests.requestor_productline = '{$prodline9}')
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
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

                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}'))
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}' OR pcn_requests.requestor_productline = '{$prodline8}'))
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
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

                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}')
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}') 
                                                            AND (pcn_requests.requestor_productline = '{$prodline1}' OR pcn_requests.requestor_productline = '{$prodline2}' OR pcn_requests.requestor_productline = '{$prodline3}' OR pcn_requests.requestor_productline = '{$prodline4}' OR pcn_requests.requestor_productline = '{$prodline5}' OR pcn_requests.requestor_productline = '{$prodline6}' OR pcn_requests.requestor_productline = '{$prodline7}')
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
                                                }
                                                if ($_settings->userdata('EMPLOYID') == '1065') { // Tess
                                                    $dept1 = 'Production';
                                                    $dept2 = 'Production / PE';
                                                    $prodline1 = 'PL2 (AD/OS)';

                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}') )
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE ((pcn_requests.requestor_department = '{$dept1}' OR pcn_requests.requestor_department = '{$dept2}') AND (pcn_requests.requestor_productline = '{$prodline1}'))
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
                                                }
                                            }
                                            if ($_settings->userdata('EMPPOSITION') == 3) {
                                                if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
                                                    $dept1 = "PPC";
                                                    $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                                    FROM pcn_requests
                                                    INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                                    WHERE (pcn_requests.requestor_department = '{$dept1}')
                                                        AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)
                                                    
                                                    UNION
                                                    
                                                    SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                                    FROM pcn_requests
                                                    WHERE (pcn_requests.requestor_department = '{$dept1}')
                                                        AND (pcn_status = 0 AND pa_form_code IS NULL)
                                                    ORDER BY `date_created` ASC;");
                                                }
                                            }

                                            // if ($_settings->userdata('EMPPOSITION') == 3) {
                                            //     if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODLINE') == 'PL3 (ADCV)') {
                                            //         $dept1 = "Process Engineering";
                                            //         $prodline1 = 'PL3';

                                            //         $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE 
                                            //                                 (`requestor_department` = '{$dept1}' AND `requestor_productline` = '{$prodline1}') ORDER BY `date_created` ASC");
                                            //     }
                                            // }
                                            // if ($_settings->userdata('EMPLOYID') == '600') {
                                            //     $qry = $conn->query("SELECT pcn_requests.*, appraisal_requests.form_format, appraisal_requests.pa_form_no, appraisal_requests.pa_status
                                            //     FROM pcn_requests
                                            //     INNER JOIN appraisal_requests ON pcn_requests.pa_form_code = appraisal_requests.pa_form_no 
                                            //     WHERE ((pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}') 
                                            //             AND (pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') )
                                            //         AND (pcn_requests.pcn_status = 0 AND pcn_requests.pa_form_code IS NOT NULL)

                                            //     UNION

                                            //     SELECT pcn_requests.*, NULL AS form_format, NULL AS pa_form_no, NULL AS pa_status
                                            //     FROM pcn_requests
                                            //     WHERE ((pcn_requests.requestor_department = '{$_settings->userdata('DEPARTMENT')}') 
                                            //             AND (pcn_requests.requestor_productline = '{$_settings->userdata('PRODLINE')}') )
                                            //         AND (pcn_status = 0 AND pa_form_code IS NULL)
                                            //     ORDER BY `date_created` ASC;");
                                            // }
                                        } else {
                                            //$qry = $conn->query("SELECT * FROM `pcn_requests` WHERE `pcn_status` = 2 ORDER BY `date_created` ASC");
                                        }

                                        $appvalsCount = 0;

                                        while ($row = $qry->fetch_assoc()) :
                                            if (($row['pa_form_code'] != "") && ($row['pa_status'] >= 2 && $row['pa_status'] != 6) && $row['pcn_status'] == 0) {
                                                $appvalsCount++;
                                            } else {
                                                $appvalsCount++;
                                            }
                                        endwhile;

                                        echo $appvalsCount;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-success elevation-1"><a href="<?php echo base_url . "admin/?page=pcnApproval/approvalhistory" ?>"><i class="fas fa-file-invoice"></i></a></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Signed PCN</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        $appvalsCount = 0;

                                        if ($_settings->userdata('EMPPOSITION') == 5) {
                                            $qry = $conn->query("SELECT * FROM pcn_requests WHERE `od_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 4) {
                                            $qry = $conn->query("SELECT * FROM pcn_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");

                                            if ($_settings->userdata('EMPPOSITION') == 4 && $_settings->userdata('DEPARTMENT') == 'Human Resource') {
                                                $qry = $conn->query("SELECT * FROM `pcn_requests` WHERE (`hr_name` = '{$_settings->userdata('EMPLOYID')}' OR `dh_name` = '{$_settings->userdata('EMPLOYID')}') ORDER BY `date_created` desc");
                                            }
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 3) {
                                            if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
                                                $dept1 = "PPC";
                                                $qry = $conn->query("SELECT * FROM pcn_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                            }
                                        }

                                        $appvalsCount = $qry->num_rows;
                                        echo $appvalsCount;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                    <hr>
                <?php
                }
                ?>

                <div class="row  justify-content-left">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-primary elevation-1"><a href="<?php echo base_url . "admin/?page=pcnForms/pending" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pending PCN</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    $appvalsCount = 0;
                                    if (!empty($_settings->userdata('EMPNAME'))) {
                                        $qry = $conn->query("SELECT * FROM pcn_requests WHERE requestor_id = '{$_settings->userdata('EMPLOYID')}' AND (pcn_status = 0 OR pcn_status = 1 OR pcn_status = 2);");
                                    }
                                    if (empty($_settings->userdata('EMPNAME'))) {
                                        if ($_settings->userdata('log_category') == 2) {
                                            $qry = $conn->query("SELECT * FROM pcn_requests WHERE (pcn_status = 0 OR pcn_status = 1 OR pcn_status = 2)");
                                        }
                                        if ($_settings->userdata('log_category') == 3) {
                                            $qry = $conn->query("SELECT * FROM pcn_requests WHERE emp_job_level = 1 AND (pcn_status = 0 OR pcn_status = 1 OR pcn_status = 2)");
                                        }
                                    }
                                    $appvalsCount = $qry->num_rows;
                                    echo $appvalsCount;
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-success elevation-1"><a href="<?php echo base_url . "admin/?page=pcnForms/approved" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Approved PCN</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    $appvalsCount = 0;
                                    if (!empty($_settings->userdata('EMPNAME'))) {
                                        $qry = $conn->query("SELECT * FROM pcn_requests WHERE requestor_id = '{$_settings->userdata('EMPLOYID')}' AND pcn_status = 3");
                                    }
                                    if (empty($_settings->userdata('EMPNAME'))) {
                                        if ($_settings->userdata('log_category') == 2) {
                                            $qry = $conn->query("SELECT * FROM pcn_requests WHERE pcn_status = 3");
                                        }
                                        if ($_settings->userdata('log_category') == 3) {
                                            $qry = $conn->query("SELECT * FROM pcn_requests WHERE emp_job_level = 1 AND pcn_status = 3");
                                        }
                                    }
                                    $appvalsCount = $qry->num_rows;
                                    echo $appvalsCount;
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-danger elevation-1"><a href="<?php echo base_url . "admin/?page=pcnForms/disapproved" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Disapproved PCN</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    $appvalsCount = 0;
                                    if (!empty($_settings->userdata('EMPNAME'))) {
                                        $qry = $conn->query("SELECT * FROM pcn_requests WHERE requestor_id = '{$_settings->userdata('EMPLOYID')}' AND pcn_status = 6");
                                    }
                                    if (empty($_settings->userdata('EMPNAME'))) {
                                        if ($_settings->userdata('log_category') == 2) {
                                            $qry = $conn->query("SELECT * FROM pcn_requests WHERE pcn_status = 6");
                                        }
                                        if ($_settings->userdata('log_category') == 3) {
                                            $qry = $conn->query("SELECT * FROM pcn_requests WHERE emp_job_level = 1 AND pcn_status = 6");
                                        }
                                    }
                                    $appvalsCount = $qry->num_rows;
                                    echo $appvalsCount;
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- // ---------------------------------------------------------------------------------- ir da --------------------------------------------------------------------------------------------// -->


        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header">
                <h3 class="card-title"><b>IR/DA</b></h3>&nbsp;
                <?php
                if ($_settings->userdata('DEPARTMENT') == 'Human Resource'  || $_settings->userdata('EMPLOYID') == 1191) {
                    echo ($dis_qry + $qryyy + $issue_da) > 0 ? ' <span class="badge badge-warning">New</span>' : '';
                } else {
                    echo ($dis_qry + $qryyy) > 0 ? ' <span class="badge badge-warning">New</span>' : '';
                }
                ?>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row justify-content-left">
                    <?php
                    if ($_settings->userdata('EMPPOSITION') > 2 || $_settings->userdata('DEPARTMENT') == 'Human Resource' || $_settings->userdata('EMPLOYID') == 1191 || $_settings->userdata('EMPLOYID') == 16615  || $_settings->userdata('EMPLOYID') == 16411  || extractAuditor($word) == 'Auditor') {
                    ?>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-primary elevation-1"><a href="<?php echo base_url . "admin/?page=incidentreport/approveIR" ?>"><i class="fas fa-file-invoice"></i></a></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">For Approvals</span>
                                    <span class="info-box-number text-right">
                                        <?php if (($_settings->userdata('DEPARTMENT') == 'Human Resource'  || $_settings->userdata('EMPLOYID') == 1191) && $_settings->userdata('EMPPOSITION') > 4) { ?>
                                            <?php echo $qryyy + $issue_da ?>
                                        <?php  } else { ?>
                                            <?php echo $qryyy  ?>
                                        <?php } ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <hr>
                <div class="row  justify-content-left">

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-warning elevation-1"><a href="<?php echo base_url . "admin/?page=inbox/ircompletion" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pending IR</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    echo $inbox_ir
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-secondary elevation-1"><a href="<?php echo base_url . "admin/?page=inbox/issuedDA" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pending DA</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    echo $inbox_da
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-danger elevation-1"><a href="<?php echo base_url . "admin/?page=incidentreport/disapprovedIRDA" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Invalid requests</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    echo $dis_qry
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                </div>



            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->


    </div>
</div>