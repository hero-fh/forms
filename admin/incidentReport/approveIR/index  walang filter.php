<style>
    td {
        vertical-align: middle;
    }
</style>
<div class="card card-outline card-primary">
    <div class="card-header p-0 pt-1 ">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <li class="pt-2 px-3">
                <h3 class="card-title">List of Incident Reports</h3>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-one-active-tab" data-toggle="pill" href="#custom-tabs-one-active" role="tab" aria-controls="custom-tabs-one-active" aria-selected="true">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-history-tab" data-toggle="pill" href="#custom-tabs-one-history" role="tab" aria-controls="custom-tabs-one-history" aria-selected="false">History</a>
            </li>
            <!-- <li class="nav-item ml-auto">
                <a href="<?php echo base_url ?>admin/?page=prf/new_prf" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>
            </li> -->
        </ul>
    </div>
    <!-- <div class="card-header">
        <h3 class="card-title">List of Incident Reports</h3>
        <div class="card-tools">

            <a href="<?php echo base_url ?>admin/?page=incidentreport/createNewIRDA/new_ir" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>
            <?php
            if ($_settings->userdata('DEPARTMENT') == 'Human Resource') {
            ?>
                <a href="javascript:void(0)" class="btn btn-flat btn-primary export_list"><span class="fa fa-download"></span> Export</a>
            <?php
            }
            ?>

        </div>
    </div> -->
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-one-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-one-active" role="tabpanel" aria-labelledby="custom-tabs-one-active-tab">
                <div class="container-fluid">
                    <table class="table table-bordered table-stripped">

                        <thead>
                            <tr class="bg-gradient-primary text-center">
                                <th>#</th>
                                <th>Date Created</th>
                                <th>IR No</th>
                                <th>Issued to</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // echo extractAuditor($word);
                            // roselle 1191
                            $i = 1;
                            if ($is_operator > 0) {
                                $ir_da_qry = $conn->query("SELECT * FROM ir_requests where (ir_status = 3 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') )) and is_inactive = 0) or 
                                (hr_status = 0 and ($is_operator > 0) and is_inactive = 0) or 
                                (hr_status = 1 and why1 != '' and sv_status = 0 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') )) and da_status = 0 and is_inactive = 0) or 
                                (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and ($is_operator > 0) and da_status = 0 and quality_violation = 1 and is_inactive = 0) or
                                (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2 and is_inactive = 0) 
                                -- (ir_status = 2 and has_da = 0 and ($is_operator > 0) and is_inactive = 0)
                                 ORDER BY `date_created` asc");
                            } else {
                                if ($_settings->userdata('EMPPOSITION') == 5) {
                                    $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) or 
                                    (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) or
                                    (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and da_status = 1 and appeal_status = 2 and ir_status = 1)
                                    ORDER BY `date_created` asc");
                                } elseif ($is_quality > 0) {
                                    $ir_da_qry = $conn->query("SELECT * FROM ir_requests where 
                                                                (hr_status = 0 and quality_violation = 2) or
                                                                (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and quality_violation = 2 and da_status = 0) or 
                                                                (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) or
                                                                (ir_status = 3 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) 
                                                               ORDER BY `date_created` asc");
                                } else {
                                    // $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE 
                                    // (`ir_status` = 2 and dm_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2 and dh_name = '{$_settings->userdata('EMPLOYID')}') or
                                    // (`ir_status` = 2 and dm_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 3 and dh_name != '{$_settings->userdata('EMPLOYID')}') or
                                    // (da_status = 3 and  ir_status = 2 and dh_name = '{$_settings->userdata('EMPLOYID')}' and dm_name ='{$_settings->userdata('EMPLOYID')}') or
                                    // (ir_status = 2 and da_status = 1 and has_da = 1 and '{$_settings->userdata('DEPARTMENT')}' = 'Human Resource' and '{$_settings->userdata('EMPPOSITION')}' > 3)
                                    //                       ORDER BY `date_created` desc");
                                    // $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE 
                                    //         (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name != '{$_settings->userdata('EMPLOYID')}') or
                                    //         (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or
                                    //         (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name != '{$_settings->userdata('EMPLOYID')}') or 
                                    //         (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or #  nainvalid yung assessment ni sec head
                                    //         (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 1 and appeal_status IN (0, 4, 5, 3) and ir_status = 1 and dh_name = '{$_settings->userdata('EMPLOYID')}') or
                                    //         (hr_status = 1 and why1 != '' and sv_status = 1 and da_status = 1 and appeal_status = 1 and ir_status = 1 and '{$_settings->userdata('DEPARTMENT')}' = 'Human Resource' and '{$_settings->userdata('EMPPOSITION')}' > 3) 
                                    //             ORDER BY `date_created` desc");

                                    $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE 
                                    (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) or
                                    -- (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or # wala na to since approver 2 na din yung nasa taas
                                    (ir_status = 3 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) or 
                                    -- (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or # wala na to since approver 2 na din yung nasa taas
                                    
                                    (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 1 and appeal_status = 0 and ir_status = 1 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE APPROVER2 ='{$_settings->userdata('EMPLOYID')}'))
                                    
                                        ORDER BY `date_created` desc");
                                    // $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE 
                                    //                         (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name != '{$_settings->userdata('EMPLOYID')}') or
                                    //                         (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name != '{$_settings->userdata('EMPLOYID')}') or 
                                    //                         (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or #  nainvalid yung assessment ni sec head
                                    //                         (ir_status = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2 and dh_name != '{$_settings->userdata('EMPLOYID')}') or
                                    //                         (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or
                                    //                         (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 1 and appeal_status IN (0, 4, 5, 3) and ir_status = 1 and dh_name = '{$_settings->userdata('EMPLOYID')}') or
                                    //                         (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 3 and  ir_status = 2 and dh_name = '{$_settings->userdata('EMPLOYID')}') or
                                    //                         (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 2 and  ir_status = 2 and dh_name = '{$_settings->userdata('EMPLOYID')}' and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                    //                         (hr_status = 1 and why1 != '' and sv_status = 1 and da_status = 1 and appeal_status = 1 and ir_status = 1 and '{$_settings->userdata('DEPARTMENT')}' = 'Human Resource' and '{$_settings->userdata('EMPPOSITION')}' > 3) or 
                                    //                         (ir_status = 2 and da_status = 1 and has_da = 1 and '{$_settings->userdata('DEPARTMENT')}' = 'Human Resource' and '{$_settings->userdata('EMPPOSITION')}' > 3)
                                    //                           ORDER BY `date_created` desc");

                                    // $ir_da_qry = $conn->query("SELECT ir_requests.*, ir_list.*
                                    // FROM ir_requests
                                    // INNER JOIN ir_list ON ir_requests.ir_no = ir_list.ir_no
                                    // WHERE ir_requests.ir_status = 2
                                    //   AND ir_requests.has_da = 0
                                    //   AND ir_list.valid = 1
                                    //   AND ir_list.offense_no REGEXP '[a-zA-Z]' 
                                    //   AND ir_list.offense_no REGEXP '[0-9]' 
                                    //   AND (ir_requests.sv_name = '{$_settings->userdata('EMPLOYID')}' or ir_requests.dh_name = '{$_settings->userdata('EMPLOYID')}')");
                                }
                            }



                            // -------
                            {
                                // } else {
                                //     if ($_settings->userdata('EMPPOSITION') == 5) {
                                //         $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //         (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //         (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and da_status = 1 and appeal_status = 2 and ir_status = 1)
                                //         ORDER BY `date_created` asc");
                                //     } else {
                                //         if ($_settings->userdata('EMPLOYID') == '1694') { // Leand 1694
                                //             $dept1 = "MIS";
                                //             $dept2 = "Facilities";
                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
                                //             (`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //              ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 3) or
                                //              ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                                //               ORDER BY `date_created` desc");
                                //         } elseif ($_settings->userdata('EMPLOYID') == '702') { // Joan
                                //             $dept1 = 'Finance';
                                //             $dept2 = 'Purchasing';
                                //             $prodline1 = 'G & A';

                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}' AND `productline` = '{$prodline1}') and ir_status = 2 and da_status = 3) or
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                                //             ORDER BY `date_created` desc");
                                //         } elseif ($_settings->userdata('EMPLOYID') == '524') { // Charity
                                //             $dept1 = 'Human Resource';
                                //             $dept2 = 'Training';
                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and
                                //             (`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and da_status = 1 and appeal_status = 1 and ir_status = 1) or
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 3) or
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             (ir_status = 2 and da_status = 1 and has_da = 1))
                                //             ORDER BY `date_created` desc");
                                //         } elseif ($_settings->userdata('EMPLOYID') == '8563') { // Bryan
                                //             $dept1 = 'Production';
                                //             $dept2 = 'Production - QFP';
                                //             $dept3 = 'Production - RFC';
                                //             $dept4 = 'Production / Non - TNR';
                                //             $prodline1 = 'PL1 - PL4';
                                //             $prodline2 = 'PL1 (ADGT)';
                                //             $prodline3 = 'PL4 (ADGT)';
                                //             $prodline4 = 'PL6 (ADLT)';

                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND (`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or 
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and da_status = 1 and appeal_status IN (4, 5, 3) and ir_status = 1) or 
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and ir_status = 2 and da_status = 3) or
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')
                                //             ORDER BY `date_created` DESC");
                                //         } elseif ($_settings->userdata('EMPLOYID') == '20') { // Noel
                                //             $dept1 = 'Production';
                                //             $dept2 = 'Store';
                                //             $dept3 = 'IQA Warehouse';
                                //             $dept4 = 'Logistics';
                                //             $prodline1 = 'PL9 (AD/WHSE)';
                                //             $prodline2 = 'G & A';
                                //             $prodline3 = 'PL8 (AMS O/S)';


                                //             $prodline4 = 'PL3 (ADCV)';
                                //             $prodline5 = 'PL3 (ADCV) - Onsite';






                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             (((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and ir_status = 2 and da_status = 3) or
                                //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')) or


                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND (`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and  ir_status = 2 and da_status = 3) or
                                //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')
                                //             ))
                                //                 ORDER BY `date_created` desc");
                                //         }
                                //         // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL6 (ADLT)') {
                                //         //     $dept1 = 'Production';
                                //         //     $dept2 = 'Production / Non - TNR';
                                //         //     $prodline1 = 'PL6 (ADLT)';

                                //         //     $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
                                //         //                             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline2}')) and da_status = 1 and appeal_status = 0 and ir_status = 1 ORDER BY `date_created` asc");
                                //         // }
                                //         elseif ($_settings->userdata('EMPLOYID') == '297') { // Erwin
                                //             $dept1 = 'Quality Assurance';
                                //             $prodline1 = 'G & A';
                                //             $prodline2 = 'PL1 - PL4';
                                //             $prodline3 = 'PL1 (ADGT)';
                                //             $prodline4 = 'PL2 (AD/OS)';
                                //             $prodline5 = 'PL3 (ADCV)';
                                //             $prodline6 = 'PL3 (ADCV) - Onsite';
                                //             $prodline7 = 'PL4 (ADGT)';
                                //             $prodline8 = 'PL6 (ADLT)';
                                //             $prodline9 = 'PL8 (AMS O/S)';

                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
                                //             ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 0 and ir_status = 1) or 
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 4 and ir_status = 1) or 
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 5 and ir_status = 1) or 
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 3 and ir_status = 1) or 
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and ir_status = 2 and da_status = 3) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                                //                 ORDER BY `date_created` desc");
                                //         } elseif (($_settings->userdata('EMPLOYID') == '1023')) { // Adonis
                                //             $dept1 = 'Equipment Engineering';
                                //             $prodline1 = 'G & A';
                                //             $prodline2 = 'PL1 (ADGT)';
                                //             $prodline3 = 'PL2 (AD/OS)';
                                //             $prodline4 = 'PL3 (ADCV)';
                                //             $prodline5 = 'PL3 (ADCV) - Onsite';
                                //             $prodline6 = 'PL4 (ADGT)';
                                //             $prodline7 = 'PL6 (ADLT)';
                                //             $prodline8 = 'PL8 (AMS O/S)';

                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
                                //             ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and ir_status = 2 and da_status = 3) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                                //                 ORDER BY `date_created` asc");
                                //         } elseif ($_settings->userdata('EMPLOYID') == '1170') { // Realyn
                                //             $dept1 = 'Process Engineering';
                                //             $prodline1 = 'G & A';
                                //             $prodline2 = 'PL1 - PL4';
                                //             $prodline3 = 'PL2 (AD/OS)';
                                //             $prodline4 = 'PL3 (ADCV)';
                                //             $prodline5 = 'PL3 (ADCV) - Onsite';
                                //             $prodline6 = 'PL6 (ADLT)';
                                //             $prodline7 = 'PL8 (AMS O/S)';

                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
                                //             ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and  ir_status = 2 and da_status = 3) or
                                //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                                //             ORDER BY `date_created` desc");
                                //         } elseif ($_settings->userdata('EMPLOYID') == '1065') { // Tess
                                //             $dept1 = 'Production';
                                //             $dept2 = 'Production / PE';
                                //             $prodline1 = 'PL2 (AD/OS)';

                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
                                //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and  ir_status = 2 and da_status = 3) or
                                //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                                //                      ORDER BY `date_created` asc");
                                //         } elseif ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
                                //             $dept1 = "PPC";
                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND 
                                //             `department` = '{$dept1}' and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             (`department` = '{$dept1}' and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             (`department` = '{$dept1}' and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             (`department` = '{$dept1}' and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             (`department` = '{$dept1}' and  ir_status = 2 and da_status = 3) or
                                //             (`department` = '{$dept1}' and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                                //             ORDER BY `date_created` desc");
                                //         } elseif ($_settings->userdata('EMPLOYID') == '600') { // tin
                                //             $prodline1 = 'PL3 (ADCV)';
                                //             $prodline2 = 'PL3 (ADCV) - Onsite';
                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND 
                                //             (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
                                //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
                                //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
                                //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
                                //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and  ir_status = 2 and da_status = 3) or
                                //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
                                //             ORDER BY `date_created` desc");
                                //         } elseif ($_settings->userdata('EMPLOYID') == 13019) {
                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests where 
                                //             (hr_status = 0 and quality_violation = 2) or
                                //             (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and quality_violation = 2 and da_status = 0) or 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //             (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //             (`ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
                                //            ORDER BY `date_created` asc");
                                //         } else {
                                //             $ir_da_qry = $conn->query("SELECT * FROM ir_requests where 
                                //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                                //           (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                                //           (`ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
                                //          ORDER BY `date_created` asc");
                                //         }


                                //         // $ir_da_qry = $conn->query("SELECT * FROM ir_requests WHERE hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` asc");
                                //     }
                                // }
                            }
                            while ($row = $ir_da_qry->fetch_assoc()) :
                                $approver_2 = $conn->query("SELECT APPROVER2 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $approver_1 = $conn->query("SELECT APPROVER1 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $approver_1 = $approver_1 == 'na' ? $approver_2 : $approver_1;
                                $approver_3 = $conn->query("SELECT APPROVER3 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $c = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;
                            ?>
                                <?php if ($row['has_da'] == 0 && ($row['ir_status'] < 2 || $row['ir_status'] == 3)) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                        <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                        <?php
                                        $valid_to_da_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_to_da_name']}'")->fetch_array();
                                        $valid_appeal_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_appeal_name']}'")->fetch_array();
                                        $od_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['od_name']}'")->fetch_array();
                                        $ir_da_qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                        while ($row1 = $ir_da_qry1->fetch_assoc()) :
                                            $reqName = $row1['EMPNAME'];
                                        ?>
                                            <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                        <?php endwhile; ?>

                                        <td class="text-center">
                                            <?php if ($row['ir_status'] == 0) : ?>
                                                <span class="badge badge-primary rounded-pill">Pending</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 0) : ?>
                                                <span class="badge badge-secondary rounded-pill">For assessment</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 1) : ?>
                                                <span class="badge badge-warning rounded-pill">Appeal validation</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 2) : ?>
                                                <span class="badge badge-warning rounded-pill">Director appeal approval</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 0) : ?>
                                                <span class="badge badge-warning rounded-pill">IR: For Acknowledgment</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                                <span class="badge badge-primary rounded-pill">For Validation</span>
                                            <?php elseif ($row['ir_status'] == 2) : ?>
                                                <span class="badge badge-success rounded-pill">Approved</span>
                                            <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                                Reason: <?php echo $row['disapprove_remarks'] ?>
                                            <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                <span class="badge badge-danger rounded-pill">Disapproved</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 4) : ?>
                                                <span class="badge badge-success rounded-pill">IR: For Acknowledgment</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 3) : ?>
                                                Invalid by: <?php echo isset($valid_appeal_name[0]) ? $valid_appeal_name[0] : '' ?> <br>
                                                Reason: <?php echo $row['disapprove_remarks'] ?>
                                            <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 5) : ?>
                                                Disapproved by: <?php echo isset($od_name[0]) ? $od_name[0] : '' ?> <br>
                                                Reason: <?php echo $row['disapprove_remarks'] ?>
                                            <?php else : ?>
                                                <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '{$row['ir_no']}' ");
                                            while ($rows = $options->fetch_assoc()) :
                                            ?>
                                                Code no: <?php echo $rows['code_no'] ?><br>
                                                Suspension Date:
                                                <?php
                                                $dateString = $rows['date_of_suspension'];
                                                $dateArray = explode(' + ', $dateString);

                                                foreach ($dateArray as $key => $date) {
                                                    $trimmedDate = trim($date);
                                                    $timestamp = strtotime($trimmedDate);

                                                    if ($timestamp === false) {
                                                        echo "--";
                                                    } else {
                                                        $dateTime = new DateTime();
                                                        $dateTime->setTimestamp($timestamp);

                                                        $dayOfWeek = $dateTime->format('D');
                                                        echo "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)<br>";
                                                    }
                                                }

                                                ?>
                                            <?php endwhile; ?>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <?php if ($row['ir_status'] == 2 && $row['has_da'] == 1) { ?>
                                                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/viewDA&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Sign DA</a>
                                                <?php } elseif ($row['ir_status'] == 3 && $row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 2) { ?>
                                                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Edit</a>
                                                <?php } elseif ($row['ir_status'] == 1 && $row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 0) { ?>
                                                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/validate_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> validate</a>
                                                <?php } else { ?>
                                                    <?php if ($row['hr_status'] == 1 && $row['sv_status'] == 0) { ?>
                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Assess</a>
                                                    <?php } elseif ($row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 0 && ($is_operator > 0)) { ?>

                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/validate_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Sign</a>
                                                    <?php } elseif ($row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 1 && $_settings->userdata('EMPPOSITION') >= 2 && $_settings->userdata('DEPARTMENT') == 'Human Resource') { ?>
                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/appeal_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Sign</a>
                                                    <?php } elseif ($row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 1 && $_settings->userdata('EMPPOSITION') >= 2 && $_settings->userdata('DEPARTMENT') != 'Human Resource') { ?>
                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/appeal_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Sign</a>
                                                    <?php } else { ?>
                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Sign</a>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if ($_settings->userdata('EMPLOYID') == $row['requestor_id'] && $row['emp_no'] == $row['requestor_id'] && $row['ir_status'] == 0) : ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Cancel</a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } elseif ($row['has_da'] == 1 && $c > 0) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                        <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                        <?php
                                        $valid_to_da_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_to_da_name']}'")->fetch_array();
                                        $ir_da_qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                        while ($row1 = $ir_da_qry1->fetch_assoc()) :
                                            $reqName = $row1['EMPNAME'];
                                        ?>
                                            <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                        <?php endwhile; ?>

                                        <td class="text-center">
                                            <?php if ($row['da_status'] == 1) : ?>
                                                <span class="badge badge-primary rounded-pill">For HR Manager</span>
                                            <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                                <span class="badge badge-secondary rounded-pill">DA: For Supervisor</span>
                                            <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                                <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                            <?php elseif ($row['da_status'] == 4) : ?>
                                                <span class="badge badge-primary rounded-pill">IR: For Acknowledgment</span>
                                            <?php elseif ($row['da_status'] == 5) : ?>
                                                <span class="badge badge-success rounded-pill">Approved</span>
                                            <?php else : ?>
                                                <span class="badge badge-secondary rounded-pill">--</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>

                                            <?php
                                            $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '{$row['ir_no']}' ");
                                            while ($rows = $options->fetch_assoc()) :
                                            ?>
                                                Code no: <?php echo $rows['code_no'] ?><br>
                                                Suspension Date:
                                                <?php
                                                $dateString = $rows['date_of_suspension'];
                                                $dateArray = explode(' + ', $dateString);

                                                foreach ($dateArray as $key => $date) {
                                                    $trimmedDate = trim($date);
                                                    $timestamp = strtotime($trimmedDate);

                                                    if ($timestamp === false) {
                                                        echo "--";
                                                    } else {
                                                        $dateTime = new DateTime();
                                                        $dateTime->setTimestamp($timestamp);

                                                        $dayOfWeek = $dateTime->format('D');
                                                        echo "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)<br>";
                                                    }
                                                }

                                                ?>
                                            <?php endwhile; ?>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <?php if ($row['ir_status'] == 2 && $row['has_da'] == 1) { ?>
                                                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/viewDA&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Sign DA</a>
                                                <?php } elseif ($row['ir_status'] == 3 && $row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 2) { ?>
                                                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Edit</a>
                                                <?php } elseif ($row['ir_status'] == 1 && $row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 0) { ?>
                                                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/validate_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Edit</a>
                                                <?php } else { ?>
                                                    <?php if ($row['hr_status'] == 1 && $row['sv_status'] == 0 && $row['sv_name'] == $_settings->userdata('EMPLOYID')) { ?>
                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Assess</a>
                                                    <?php } elseif ($row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 0 && ($is_operator > 0)) { ?>

                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/validate_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Sign</a>
                                                    <?php } elseif ($row['hr_status'] == 1 && $row['sv_status'] == 1 && $row['da_status'] == 1 && $_settings->userdata('EMPPOSITION') >= 2) { ?>

                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Sign</a>
                                                    <?php } else { ?>
                                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Sign</a>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if ($_settings->userdata('EMPLOYID') == $row['requestor_id'] && $row['emp_no'] == $row['requestor_id'] && $row['ir_status'] == 0) : ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Cancel</a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } elseif ($row['has_da'] == 0 && $row['ir_status'] == 2 && $c > 0) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                        <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                        <?php
                                        $ir_da_qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                        while ($row1 = $ir_da_qry1->fetch_assoc()) :
                                            $reqName = $row1['EMPNAME'];
                                        ?>
                                            <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                        <?php endwhile; ?>

                                        <td class="text-center">
                                            <?php if ($row['has_da'] == 0) : ?>
                                                <span class="badge badge-danger rounded-pill">For DA</span>
                                            <?php elseif ($row['da_status'] == 1) : ?>
                                                <span class="badge badge-warning rounded-pill">For HR Manager</span>
                                            <?php elseif ($row['da_status'] == 2) : ?>
                                                <span class="badge badge-primary rounded-pill">For immediate superior</span>
                                            <?php elseif ($row['da_status'] == 3) : ?>
                                                <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                            <?php elseif ($row['da_status'] == 4) : ?>
                                                <span class="badge badge-danger rounded-pill">For Acknowledgement</span>
                                            <?php elseif ($row['da_status'] == 4) : ?>
                                                <span class="badge badge-success rounded-pill">Acknowledged</span>
                                            <?php else : ?>
                                                <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>

                                            <?php
                                            $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '{$row['ir_no']}' ");
                                            while ($rows = $options->fetch_assoc()) :
                                            ?>
                                                Code no: <?php echo $rows['code_no'] ?><br>
                                                Suspension Date:
                                                <?php
                                                $dateString = $rows['date_of_suspension'];
                                                $dateArray = explode(' + ', $dateString);

                                                foreach ($dateArray as $key => $date) {
                                                    $trimmedDate = trim($date);
                                                    $timestamp = strtotime($trimmedDate);

                                                    if ($timestamp === false) {
                                                        echo "--";
                                                    } else {
                                                        $dateTime = new DateTime();
                                                        $dateTime->setTimestamp($timestamp);

                                                        $dayOfWeek = $dateTime->format('D');
                                                        echo "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)<br>";
                                                    }
                                                }

                                                ?>
                                            <?php endwhile; ?>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/validate_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View IR</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/new_da&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Issue D.A</a>
                                                <?php if ($_settings->userdata('EMPLOYID') != $row['emp_no']) { ?>
                                                    <!-- <?php if ($_settings->userdata('EMPLOYID') == $row['sv_name'] && $row['sv_status'] == 0) { ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/DA/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
                                        <?php } else if ($_settings->userdata('EMPLOYID') == $row['dh_name'] && $row['dh_status'] == 0) { ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/DA/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
                                        <?php } else if ($row['hr_status'] == 0) { ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/DA/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
                                        <?php } ?> 

                                        <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource') { ?>

                                            <div class="dropdown-divider"></div>
                                            <?php if ($row['has_da'] == 0 && $row['ir_status'] != 4) { ?>
                                                <a class="dropdown-item issue_da" href="javascript:void(0)" data-id="<?php echo md5($row['id']) ?>"><span class="fa fa-times text-danger"></span> Issue DA</a>
                                            <?php } elseif ($row['has_da'] == 1 && $row['ir_status'] != 4) { ?>
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=nda_form/view_da&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> View DA</a>
                                            <?php } ?>
                                        <?php } ?>-->
                                                <?php } ?>
                                                <?php if ($_settings->userdata('EMPLOYID') == $row['requestor_id'] && $row['emp_no'] == $row['requestor_id'] && $row['ir_status'] == 0) : ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Cancel</a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>

                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-one-history" role="tabpanel" aria-labelledby="custom-tabs-one-history-tab">
                <div class="container-fluid">
                    <table class="table table-bordered table-stripped">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="10%">
                            <col width="25%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-primary text-center">
                                <th>#</th>
                                <th>Date Created</th>
                                <th>IR No</th>
                                <th>Issued to</th>
                                <th>Status</th>
                                <th>Code no</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            if ($_settings->userdata('DEPARTMENT') == 'Human Resource') {
                                $ir_da_qry = $conn->query("SELECT * FROM ir_requests where (hr_status != 0 and hr_name = " . $_settings->userdata('EMPLOYID') . ") or (hr_status = 1 and sv_status !=0 and sv_name = " . $_settings->userdata('EMPLOYID') . ") or (hr_status = 1 and sv_status = 1 and dh_name = " . $_settings->userdata('EMPLOYID') . ") or (appeal_status > 1 and hr_mngr = " . $_settings->userdata('EMPLOYID') . ") ORDER BY `date_created` asc");
                            } else {
                                $ir_da_qry = $conn->query("SELECT * FROM ir_requests where (hr_status = 1 and sv_status !=0 and sv_name = " . $_settings->userdata('EMPLOYID') . ") or (hr_status = 1 and sv_status = 1 and dh_name = " . $_settings->userdata('EMPLOYID') . ") or (hr_status != 0 and hr_name = " . $_settings->userdata('EMPLOYID') . ") or (da_status != 0 and valid_to_da_name = " . $_settings->userdata('EMPLOYID') . ") or (appeal_status > 2 and od_name = " . $_settings->userdata('EMPLOYID') . ")  ORDER BY `date_created` asc");
                            }


                            // $ir_da_qry = $conn->query("SELECT * FROM ir_requests where ir_status != 2 ORDER BY `date_created` asc");

                            while ($row = $ir_da_qry->fetch_assoc()) :
                                $approver_2 = $conn->query("SELECT APPROVER2 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $approver_1 = $conn->query("SELECT APPROVER1 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $approver_1 = $approver_1 == 'na' ? $approver_2 : $approver_1;
                                $approver_3 = $conn->query("SELECT APPROVER3 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $c = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;
                                $is_valid = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}'")->num_rows;
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td class="text-center"><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                    <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                    <?php
                                    $ir_da_qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                    while ($row1 = $ir_da_qry1->fetch_assoc()) :
                                        $reqName = $row1['EMPNAME'];
                                    ?>
                                        <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                    <?php endwhile; ?>

                                    <td class="text-center">
                                        <?php if ($row['hr_name'] == $_settings->userdata('EMPLOYID')) { ?>
                                            <?php if ($row['has_da'] == 0 && ($row['ir_status'] < 2 || $row['ir_status'] == 3)) {
                                                $valid_to_da_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_to_da_name']}'")->fetch_array();
                                                $valid_appeal_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_appeal_name']}'")->fetch_array();
                                                $od_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['od_name']}'")->fetch_array();
                                                $dpr = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['hr_name']}'")->fetch_array();
                                                if ($row['ir_status'] == 0 && $row['hr_status'] == 2) :
                                            ?>
                                                    <div class="text-left">
                                                        Disapproved by: <?php echo isset($dpr[0]) ? $dpr[0] : '' ?> <br>
                                                        Reason: <?php echo $row['disapprove_remarks'] ?>
                                                    </div>
                                                <?php elseif ($row['ir_status'] == 0 && $row['hr_status'] == 0) : ?>
                                                    <span class="badge badge-primary rounded-pill">Pending</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['why1'] == '' && $row['sv_status'] == 0) : ?>
                                                    <span class="badge badge-warning rounded-pill">Letter of explanation</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 0 && $row['why1'] != '') : ?>
                                                    <span class="badge badge-secondary rounded-pill">For assessment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 1) : ?>
                                                    <span class="badge badge-warning rounded-pill">Appeal validation</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 2) : ?>
                                                    <span class="badge badge-warning rounded-pill">Director appeal approval</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 0) : ?>
                                                    <span class="badge badge-warning rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                                    <span class="badge badge-primary rounded-pill">For Validation</span>
                                                <?php elseif ($row['ir_status'] == 2) : ?>
                                                    <?php if ($is_valid > 0) { ?>
                                                        <span class="badge badge-success rounded-pill">Valid</span>
                                                    <?php } elseif ($is_valid == 0) { ?>
                                                        <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                                    <?php } ?>
                                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                    Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                    <span class="badge badge-danger rounded-pill">Disapproved</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 4) : ?>
                                                    <span class="badge badge-success rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 3) : ?>
                                                    Invalid by: <?php echo isset($valid_appeal_name[0]) ? $valid_appeal_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 5) : ?>
                                                    Disapproved by: <?php echo isset($od_name[0]) ? $od_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                                <?php endif; ?>
                                            <?php } elseif ($row['has_da'] == 1 && $c > 0) { ?>
                                                <?php if ($row['da_status'] == 1) : ?>
                                                    <span class="badge badge-primary rounded-pill">For HR Manager</span>
                                                <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                                    <span class="badge badge-secondary rounded-pill">DA: For Supervisor</span>
                                                <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                                    <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                                <?php elseif ($row['da_status'] == 4) : ?>
                                                    <span class="badge badge-primary rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['da_status'] == 5) : ?>
                                                    <span class="badge badge-success rounded-pill">Approved</span>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">--</span>
                                                <?php endif; ?>
                                            <?php } elseif ($row['has_da'] == 0 && $row['ir_status'] == 2 && $c > 0) { ?>
                                                <?php if ($row['has_da'] == 0) : ?>
                                                    <span class="badge badge-danger rounded-pill">For DA</span>
                                                <?php endif; ?>
                                            <?php } else { ?>
                                                <?php if ($is_valid > 0) { ?>
                                                    <span class="badge badge-success rounded-pill">Served</span>
                                                <?php } elseif ($is_valid == 0) { ?>
                                                    <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                                <?php } ?>
                                            <?php } ?>


                                        <?php } elseif ($row['sv_name'] == $_settings->userdata('EMPLOYID')) { ?>
                                            <?php if ($row['has_da'] == 0 && ($row['ir_status'] < 2 || $row['ir_status'] == 3)) {
                                                $valid_to_da_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_to_da_name']}'")->fetch_array();
                                                $valid_appeal_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_appeal_name']}'")->fetch_array();
                                                $od_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['od_name']}'")->fetch_array();

                                            ?>
                                                <?php if ($row['ir_status'] == 0) : ?>
                                                    <span class="badge badge-primary rounded-pill">Pending</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 0) : ?>
                                                    <span class="badge badge-secondary rounded-pill">For assessment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 1) : ?>
                                                    <span class="badge badge-warning rounded-pill">Appeal validation</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 2) : ?>
                                                    <span class="badge badge-warning rounded-pill">Director appeal approval</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 0) : ?>
                                                    <span class="badge badge-warning rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                                    <span class="badge badge-primary rounded-pill">For Validation</span>
                                                <?php elseif ($row['ir_status'] == 2) : ?>
                                                    <span class="badge badge-success rounded-pill">Approved</span>
                                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                    Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                    <span class="badge badge-danger rounded-pill">Disapproved</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 4) : ?>
                                                    <span class="badge badge-success rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 3) : ?>
                                                    Invalid by: <?php echo isset($valid_appeal_name[0]) ? $valid_appeal_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 5) : ?>
                                                    Disapproved by: <?php echo isset($od_name[0]) ? $od_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                                <?php endif; ?>
                                            <?php } elseif ($row['has_da'] == 1 && $c > 0) { ?>
                                                <?php if ($row['da_status'] == 1) : ?>
                                                    <span class="badge badge-primary rounded-pill">For HR Manager</span>
                                                <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                                    <span class="badge badge-secondary rounded-pill">DA: For Supervisor</span>
                                                <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                                    <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                                <?php elseif ($row['da_status'] == 4) : ?>
                                                    <span class="badge badge-primary rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['da_status'] == 5) : ?>
                                                    <span class="badge badge-success rounded-pill">Approved</span>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">--</span>
                                                <?php endif; ?>
                                            <?php } elseif ($row['has_da'] == 0 && $row['ir_status'] == 2 && $c > 0) { ?>
                                                <?php if ($row['da_status'] == 0) : ?>
                                                    <span class="badge badge-danger rounded-pill">For DA</span>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                                <?php endif; ?>
                                            <?php } else { ?>
                                                <?php if ($is_valid > 0) { ?>
                                                    <span class="badge badge-success rounded-pill">Served</span>
                                                <?php } elseif ($is_valid == 0) { ?>
                                                    <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                                <?php } ?>
                                            <?php } ?>



                                        <?php } elseif ($row['dh_name'] == $_settings->userdata('EMPLOYID')) { ?>
                                            <?php if ($row['has_da'] == 0 && ($row['ir_status'] < 2 || $row['ir_status'] == 3)) {
                                                $valid_to_da_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_to_da_name']}'")->fetch_array();
                                                $valid_appeal_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_appeal_name']}'")->fetch_array();
                                                $od_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['od_name']}'")->fetch_array();

                                            ?>
                                                <?php if ($row['ir_status'] == 0) : ?>
                                                    <span class="badge badge-primary rounded-pill">Pending</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 0) : ?>
                                                    <span class="badge badge-secondary rounded-pill">For assessment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 1) : ?>
                                                    <span class="badge badge-warning rounded-pill">Appeal validation</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 2) : ?>
                                                    <span class="badge badge-warning rounded-pill">Director appeal approval</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 0) : ?>
                                                    <span class="badge badge-warning rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                                    <span class="badge badge-primary rounded-pill">For Validation</span>
                                                <?php elseif ($row['ir_status'] == 2) : ?>
                                                    <span class="badge badge-success rounded-pill">Approved</span>
                                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                    Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                    <span class="badge badge-danger rounded-pill">Disapproved</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 4) : ?>
                                                    <span class="badge badge-success rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 3) : ?>
                                                    Invalid by: <?php echo isset($valid_appeal_name[0]) ? $valid_appeal_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 5) : ?>
                                                    Disapproved by: <?php echo isset($od_name[0]) ? $od_name[0] : '' ?> <br>
                                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                                <?php endif; ?>
                                            <?php } elseif ($row['has_da'] == 1 && $c > 0) { ?>
                                                <?php if ($row['da_status'] == 1) : ?>
                                                    <span class="badge badge-primary rounded-pill">For HR Manager</span>
                                                <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                                    <span class="badge badge-secondary rounded-pill">DA: For Supervisor</span>
                                                <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                                    <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                                <?php elseif ($row['da_status'] == 4) : ?>
                                                    <span class="badge badge-primary rounded-pill">IR: For Acknowledgment</span>
                                                <?php elseif ($row['da_status'] == 5) : ?>
                                                    <span class="badge badge-success rounded-pill">Approved</span>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">--</span>
                                                <?php endif; ?>
                                            <?php } elseif ($row['has_da'] == 0 && $row['ir_status'] == 2 && $c > 0) { ?>
                                                <?php if ($row['da_status'] == 0) : ?>
                                                    <span class="badge badge-danger rounded-pill">For DA</span>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                                <?php endif; ?>
                                            <?php } else { ?>
                                                <?php if ($is_valid > 0) { ?>
                                                    <span class="badge badge-success rounded-pill">Served</span>
                                                <?php } elseif ($is_valid == 0) { ?>
                                                    <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                                <?php } ?>
                                            <?php } ?>


                                            <!-- <?php if ($row['dh_status'] == 1) : ?>
                                                <span class="badge badge-primary rounded-pill">Approved</span>
                                            <?php elseif ($row['dh_status'] == 2) : ?>
                                                <span class="badge badge-danger rounded-pill">Disapproved</span>
                                            <?php endif; ?> -->



                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <?php
                                            $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '{$row['ir_no']}' ");
                                            while ($rows = $options->fetch_assoc()) :
                                            ?>
                                                <span class="badge badge-success rounded-pill"><?php echo $rows['code_no'] ?></span>

                                                <!-- Suspension Date:
                                                <?php
                                                $dateString = $rows['date_of_suspension'];
                                                $dateArray = explode(' + ', $dateString);

                                                foreach ($dateArray as $key => $date) {
                                                    $trimmedDate = trim($date);
                                                    $timestamp = strtotime($trimmedDate);

                                                    if ($timestamp === false) {
                                                        echo "--";
                                                    } else {
                                                        $dateTime = new DateTime();
                                                        $dateTime->setTimestamp($timestamp);

                                                        $dayOfWeek = $dateTime->format('D');
                                                        echo "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)<br>";
                                                    }
                                                }

                                                ?> -->
                                            <?php endwhile; ?>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveIR/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                            <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/createNewIRDA/new_da&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Issue D.A</a> -->
                                            <?php if ($_settings->userdata('EMPLOYID') != $row['emp_no']) { ?>
                                                <!-- <?php if ($_settings->userdata('EMPLOYID') == $row['sv_name'] && $row['sv_status'] == 0) { ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/createNewIRDA/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
                                        <?php } else if ($_settings->userdata('EMPLOYID') == $row['dh_name'] && $row['dh_status'] == 0) { ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/createNewIRDA/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
                                        <?php } else if ($row['hr_status'] == 0) { ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/createNewIRDA/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
                                        <?php } ?> 

                                        <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource') { ?>

                                            <div class="dropdown-divider"></div>
                                            <?php if ($row['has_da'] == 0 && $row['ir_status'] != 4) { ?>
                                                <a class="dropdown-item issue_da" href="javascript:void(0)" data-id="<?php echo md5($row['id']) ?>"><span class="fa fa-times text-danger"></span> Issue DA</a>
                                            <?php } elseif ($row['has_da'] == 1 && $row['ir_status'] != 4) { ?>
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=nda_form/view_da&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> View DA</a>
                                            <?php } ?>
                                        <?php } ?>-->
                                            <?php } ?>
                                            <?php if ($_settings->userdata('EMPLOYID') == $row['requestor_id'] && $row['emp_no'] == $row['requestor_id'] && $row['ir_status'] == 0) : ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Cancel</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.delete_data').click(function() {
            _conf("Are you sure to cancel this incident report?", "delete_po", [$(this).attr('data-id')])
        })
        $('.issue_da').click(function() {
            // _conf("Are you sure to issue disciplinary action on this incident report?", "issue_da", [$(this).attr('data-id')])
            uni_modal("Are you sure to issue disciplinary action on this incident report?", "incidentreport/createNewIRDA/new_da.php?id=" + $(this).attr('data-id'), 'mid-large')
        })
        $('.export_list').click(function() {
            uni_modal("", "incidentreport/createNewIRDA/export_data.php", 'mid-large')

        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable();
    })

    function delete_po($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=ir_cancel",
            method: "POST",
            data: {
                id: $id
            },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>