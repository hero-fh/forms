<?php
$pcnFormNumber = $_GET['id'];
$qry = $conn->query("SELECT * FROM pcn_requests where md5(id) = '{$pcnFormNumber}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        if ($k === 'date_created') {
            $date_created_pcn_request = $v;
        } else {
            $$k = $v;
        }
        if ($k === 'id') {
            $pcn_id = $v;
        } else {
            $$k = $v;
        }
    }
}

$qry1 = $conn->query("SELECT * FROM employee_masterlist where EMPLOYID = '{$requestor_id}'");
if ($qry1->num_rows > 0) {
    foreach ($qry1->fetch_array() as $k1 => $v1) {
        $$k1 = $v1;
    }
}


$qry = $conn->query("SELECT * FROM pcn_salary where pcn_code = '{$pcn_form_no}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
    $withSalary = 1;
}

$qry = $conn->query("SELECT * FROM pcn_department where pcn_code = '{$pcn_form_no}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
    $withDepartment = 1;
}

$qry = $conn->query("SELECT * FROM pcn_jobtitle where pcn_code = '{$pcn_form_no}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
    $withJobTitle = 1;
}

$qry = $conn->query("SELECT * FROM pcn_pl where pcn_code = '{$pcn_form_no}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
    $withProductLine = 1;
}

function decrypt($data, $key)
{
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $encryptedData = substr($data, 16);
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
    return $decryptedData;
}

$encryptionKey = "7K#p2Y@Wz!qX8e&1";

switch ($employ_type) {
    case "0":
        $empStatus = "Probationary";
        break;
    case "1":
        $empStatus = "Permanent";
        break;
}

switch ($emp_job_level) {
    case "1":
        $jlName = "Direct";
        break;
    case "2":
        $jlName = "Non-Exempt";
        break;
    case "3":
        $jlName = "Exempt";
        break;
    case "4":
        $jlName = "Section Head";
        break;
    case "5":
        $jlName = "Manager";
        break;
    case "6":
        $jlName = "Senior Management";
        break;
}

?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Payroll Change Notice<?php echo " : " . $pcn_form_no ?></h4>
        <div class="card-tools">
            <?php if ($_GET['pas'] == 0) { ?>
                <?php if ($pcn_status == 0) : ?>
                    <span class="badge badge-primary ">DA: For Department manager's Approval</span>
                <?php elseif ($pcn_status == 1) : ?>
                    <span class="badge badge-warning ">For Human Resource Manager's Approval</span>
                <?php elseif ($pcn_status == 2) : ?>
                    <span class="badge badge-warning ">For Operation Director's Approval</span>
                <?php elseif ($pcn_status == 3) : ?>
                    <span class="badge badge-success ">Approved</span>
                <?php elseif ($pcn_status == 6) : ?>
                    <span class="badge badge-danger ">Disapproved</span>
                <?php endif; ?>
            <?php } ?>
            <?php if ($_GET['pas'] == 1) { ?>
                <span class="badge badge-warning ">Waiting for the completion of attached Performance Appraisal</span>
            <?php } ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                // if ($pcn_status != 5) {
                //     $lblHeader = "Discussed and Assessed ";
                // }
                // if ($pcn_status == 5) {
                //     $lblHeader = "Cancelled ";
                // }
                $lblHeader = "Prepared";
                ?>
                <label class="control-label text-info"><?php echo $lblHeader ?> by <i>Immediate Supervisor</i></label>
                <?php
                $qry = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$requestor_id}'");
                while ($row = $qry->fetch_assoc()) :
                    $reqName = $row['EMPNAME'];
                    $reqPosition = $row['JOB_TITLE'];
                    $reqDepartment = $row['DEPARTMENT'];
                    $reqGender = $row['EMPSEX'];
                endwhile;
                $dateCreated = new DateTime($date_created_pcn_request);
                $newDateformat = $dateCreated->format('m-d-Y h:i:s a');
                ?>
                <div class="mt-1">
                    <div class="user-block">
                        <?php
                        switch ($reqGender) {
                            case 1:
                        ?>
                                <img class="img-circle img-bordered-sm" src="<?php echo validate_image('admin/appraisalForms/profile.png') ?>">
                                <?php
                                break;
                                ?>
                            <?php
                            case 2:
                            ?>
                                <img class="img-circle img-bordered-sm" src="<?php echo validate_image('admin/appraisalForms/profile_f.png') ?>">
                        <?php
                                break;
                        }
                        ?>
                        <span class="username">
                            <?php echo $reqName ?>
                        </span>
                        <span class="description"><?php echo $reqPosition ?></span>
                        <span class="description"><?php echo $newDateformat ?></span>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <!-- Appraisal-->
        <div class="callout callout-info">
            <h5>Employee Information</h5>
            <hr>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label text-info">Emp. Number</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo $emp_num ?>" readonly>
                    </div>
                    <div class="col-md-5">
                        <label class="control-label text-info">Name</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo $emp_name ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label text-info">Department</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo $emp_dept ?>" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label text-info">Product Line</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo $emp_pl ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label text-info">Date Hired</label>
                        <input type="date" class="form-control form-control-sm rounded-0" value="<?php echo $emp_date_hire ?>" readonly>
                    </div>
                    <div class="col-md-5">
                        <label class="control-label text-info">Position</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo $emp_position ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label text-info">Employee Status</label>
                        <input type="hidden" name="emp_status" id="emp_status" value="">
                        <input type="text" id="emp_status0" class="form-control form-control-sm rounded-0" value="<?php echo $empStatus ?>" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label text-info">Job Level</label>
                        <input type="hidden" name="emp_job_level" id="emp_job_level" value="">
                        <input type="text" id="emp_job_level0" class="form-control form-control-sm rounded-0" value="<?php echo $jlName ?>" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="callout callout-success">
            <h5>Content of the Payroll Change Notice</h5>
            <hr>
            <!-- Salary -->
            <?php
            if (isset($withSalary)) {
            ?>
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Salary</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-stripped">

                            <thead id="data-table">
                                <tr>
                                    <th>Effectivity Date</th>
                                    <th>From (PHP)</th>
                                    <th>To (PHP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $decryptedData1 = decrypt($salary_from, $encryptionKey);
                                $decryptedData2 = decrypt($salary_to, $encryptionKey);
                                ?>
                                <tr id="tr3">
                                    <td><input type="date" class="form-control form-control-md rounded-0" value="<?php echo $eff_sal_date ?>" disabled></td>
                                    <td align="center">
                                        <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $decryptedData1 ?>" disabled>
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $decryptedData2 ?>" disabled>
                                    </td>
                                </tr>
                                <?php




                                $qry0 = $conn->query("SELECT * FROM pcn_salary WHERE pcn_code = '{$pcn_form_no}' UNION ALL SELECT * FROM pcn_salary WHERE pcn_emp_no = '{$emp_num}' AND pcn_code != '{$pcn_form_no}'  ORDER BY date_created DESC");
                                while ($row0 = $qry0->fetch_assoc()) :

                                    $approvedPCN = $conn->query("SELECT `pcn_status` FROM pcn_requests where pcn_form_no = '{$row0['pcn_code']}'")->fetch_array()[0];

                                    $qry1 = $conn->query("SELECT * FROM pcn_salary WHERE pcn_code = '{$pcn_form_no}' UNION ALL SELECT * FROM pcn_salary WHERE pcn_emp_no = '{$emp_num}' AND pcn_code != '{$pcn_form_no}' AND id < '{$row0['id']}' ORDER BY date_created DESC");
                                    while ($row = $qry1->fetch_assoc()) :

                                        if (($row0['id'] < $row['id']) && $approvedPCN == '3') {
                                            $decryptedData3 = decrypt($row0['salary_from'], $encryptionKey);
                                            $decryptedData4 = decrypt($row0['salary_to'], $encryptionKey);
                                ?>
                                            <tr id="tr1">
                                                <td class="text-center"><input type="date" name="eff_sal_date[]" class="form-control form-control-md rounded-0" value="<?php echo $row0['eff_sal_date'] ?>" disabled></td>
                                                <td class="text-center">
                                                    <input type="number" class="form-control" value="<?php echo $decryptedData3  ?>" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" class="form-control" value="<?php echo $decryptedData4  ?>" disabled>
                                                </td>
                                            </tr>
                                <?php

                                        }

                                    endwhile;
                                endwhile;

                                ?>

                        </table>
                    </div>
                </div>
            <?php
            }
            ?>
            <!-- jobTitle -->
            <?php
            if (isset($withJobTitle)) {
            ?>
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Position</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-stripped">

                            <thead id="data-table">
                                <tr>

                                    <th>Effectivity date</th>
                                    <th>From</th>
                                    <th>To</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr id="tr3">
                                    <td><input type="date" class="form-control form-control-md rounded-0" value="<?php echo $eff_jd_date ?>" disabled></td>
                                    <td align="center">
                                        <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $jd_from ?>" disabled>
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $jd_to ?>" disabled>
                                    </td>
                                </tr>
                                <?php

                                $qry0 = $conn->query("SELECT * FROM pcn_jobtitle WHERE pcn_code = '{$pcn_form_no}' UNION ALL SELECT * FROM pcn_jobtitle WHERE pcn_emp_no = '{$emp_num}' AND pcn_code != '{$pcn_form_no}'  ORDER BY date_created DESC");
                                while ($row0 = $qry0->fetch_assoc()) :

                                    $approvedPCN = $conn->query("SELECT `pcn_status` FROM pcn_requests where pcn_form_no = '{$row0['pcn_code']}'")->fetch_array()[0];

                                    $qry1 = $conn->query("SELECT * FROM pcn_jobtitle WHERE pcn_code = '{$pcn_form_no}' UNION ALL SELECT * FROM pcn_jobtitle WHERE pcn_emp_no = '{$emp_num}' AND pcn_code != '{$pcn_form_no}' AND id < '{$row0['id']}' ORDER BY date_created DESC");
                                    while ($row = $qry1->fetch_assoc()) :

                                        if (($row0['id'] < $row['id']) && $approvedPCN == '3') {

                                ?>
                                            <tr id="tr1">
                                                <td><input type="date" class="form-control form-control-md rounded-0" value="<?php echo $row0['eff_jd_date'] ?>" disabled></td>
                                                <td align="center">
                                                    <input type="text" id="designation_from" class="form-control form-control-md rounded-0" value="<?php echo $row0['jd_from'] ?>" disabled>
                                                </td>
                                                <td align="center">
                                                    <input type="text" id="designation_to" class="form-control form-control-md rounded-0" value="<?php echo $row0['jd_to'] ?>" disabled>
                                                </td>
                                            </tr>
                                <?php

                                        }

                                    endwhile;
                                endwhile;

                                ?>
                        </table>
                    </div>
                </div>
            <?php
            }
            ?>
            <!-- departartment -->
            <?php
            if (isset($withDepartment)) {
            ?>
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Department</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-stripped">

                            <thead id="data-table">
                                <tr>

                                    <th>Effectivity date</th>
                                    <th>From</th>
                                    <th>To</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr id="tr3">
                                    <td><input type="date" class="form-control form-control-md rounded-0" value="<?php echo $eff_dept_date ?>" disabled></td>
                                    <td align="center">
                                        <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $dept_from ?>" disabled>
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $dept_to ?>" disabled>
                                    </td>
                                </tr>
                                <?php

                                $qry0 = $conn->query("SELECT * FROM pcn_department WHERE pcn_code = '{$pcn_form_no}' UNION ALL SELECT * FROM pcn_department WHERE pcn_emp_no = '{$emp_num}' AND pcn_code != '{$pcn_form_no}'  ORDER BY date_created DESC");
                                while ($row0 = $qry0->fetch_assoc()) :

                                    $approvedPCN = $conn->query("SELECT `pcn_status` FROM pcn_requests where pcn_form_no = '{$row0['pcn_code']}'")->fetch_array()[0];

                                    $qry1 = $conn->query("SELECT * FROM pcn_department WHERE pcn_code = '{$pcn_form_no}' UNION ALL SELECT * FROM pcn_department WHERE pcn_emp_no = '{$emp_num}' AND pcn_code != '{$pcn_form_no}' AND id < '{$row0['id']}' ORDER BY date_created DESC");
                                    while ($row = $qry1->fetch_assoc()) :

                                        if (($row0['id'] < $row['id']) && $approvedPCN == '3') {

                                ?>
                                            <tr id="tr3">
                                                <td><input type="date" class="form-control form-control-md rounded-0" value="<?php echo $row0['eff_dept_date'] ?>" disabled></td>
                                                <td align="center">
                                                    <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $row0['dept_from'] ?>" disabled>
                                                </td>
                                                <td align="center">
                                                    <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $row0['dept_to'] ?>" disabled>
                                                </td>
                                            </tr>
                                <?php

                                        }

                                    endwhile;
                                endwhile;

                                ?>
                        </table>
                    </div>
                </div>
            <?php
            }
            ?>
            <!-- product line -->
            <?php
            if (isset($withProductLine)) {
            ?>
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Product Line</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-stripped">

                            <thead id="data-table">
                                <tr>

                                    <th>Effectivity date</th>
                                    <th>From</th>
                                    <th>To</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr id="tr3">
                                    <td><input type="date" class="form-control form-control-md rounded-0" value="<?php echo $eff_pl_date ?>" disabled></td>
                                    <td align="center">
                                        <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $pl_from ?>" disabled>
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control form-control-md rounded-0" value="<?php echo  $pl_to ?>" disabled>
                                    </td>
                                </tr>
                                <?php

                                $qry0 = $conn->query("SELECT * FROM pcn_pl WHERE pcn_code = '{$pcn_form_no}' UNION ALL SELECT * FROM pcn_pl WHERE pcn_emp_no = '{$emp_num}' AND pcn_code != '{$pcn_form_no}'  ORDER BY date_created DESC");
                                while ($row0 = $qry0->fetch_assoc()) :

                                    $approvedPCN = $conn->query("SELECT `pcn_status` FROM pcn_requests where pcn_form_no = '{$row0['pcn_code']}'")->fetch_array()[0];

                                    $qry1 = $conn->query("SELECT * FROM pcn_pl WHERE pcn_code = '{$pcn_form_no}' UNION ALL SELECT * FROM pcn_pl WHERE pcn_emp_no = '{$emp_num}' AND pcn_code != '{$pcn_form_no}' AND id < '{$row0['id']}' ORDER BY date_created DESC");
                                    while ($row = $qry1->fetch_assoc()) :

                                        if (($row0['id'] < $row['id']) && $approvedPCN == '3') {

                                ?>
                                            <tr id="tr3">
                                                <td><input type="date" class="form-control form-control-md rounded-0" value="<?php echo $row0['eff_pl_date'] ?>" disabled></td>
                                                <td align="center">
                                                    <input type="text" class="form-control form-control-md rounded-0" value="<?php echo $row0['pl_from'] ?>" disabled>
                                                </td>
                                                <td align="center">
                                                    <input type="text" class="form-control form-control-md rounded-0" value="<?php echo $row0['pl_to'] ?>" disabled>
                                                </td>
                                            </tr>
                                <?php

                                        }

                                    endwhile;
                                endwhile;

                                ?>
                        </table>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="callout callout-success">
            <h5>Reason for Changes</h5>
            <hr>
            <div class="container-fluid">
                <div class="row">
                    <?php
                    if ($rfc_confirmation == 1) {
                    ?>
                        <div class="col-4">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" value="1" class="check" checked>
                                    <label for="rfc_confirmation">
                                        Confirmation
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($rfc_increase == 1) {
                    ?>
                        <div class="col-4">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" value="2" class="check" checked>
                                    <label for="rfc_increase">
                                        Increase
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($rfc_promotion == 1) {
                    ?>
                        <div class="col-4">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" value="3" class="check" checked>
                                    <label for="rfc_promotion">
                                        Promotion
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($rfc_transfer == 1) {
                    ?>
                        <div class="col-4">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" value="4" class="check" checked>
                                    <label for="rfc_transfer">
                                        Transfer
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($rfc_annual_review == 1) {
                    ?>
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" value="5" class="check" checked>
                                <label for="rfc_annual_review">
                                    Annual Review
                                </label>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($rfc_adjustment == 1) {
                    ?>
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" value="6" class="check" checked>
                                <label for="rfc_adjustment">
                                    Adjustment to Market Rate
                                </label>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                if ($rfc_other == 1) {
                ?>
                    <div class="row">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" value="7" class="check" checked>
                                        <label for="rfc_other">
                                            Others :
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="text" class="form-control form-control-border border-width-2" placeholder="click here to provide other details" value="<?php echo $pcn_other_rem ?>" readonly>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
        </div>
        <hr>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Approvals</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <?php
                        if ($dh_status == 0) {
                            $lblHeader = "To be Approved ";
                        }
                        if ($dh_status == 1) {
                            $lblHeader = "Approved ";
                        }
                        if ($dh_status == 2) {
                            $lblHeader = "Disapproved ";
                        }
                        ?>
                        <label class="control-label text-info"><?php echo $lblHeader ?> by <i>Department Manager</i></label>
                        <div class="mt-1">
                            <?php
                            if ($dh_status > 0) {
                                $qry = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$dh_name}'");
                                while ($row = $qry->fetch_assoc()) :
                                    $reqName = $row['EMPNAME'];
                                    $reqPosition = $row['JOB_TITLE'];
                                    $reqDepartment = $row['DEPARTMENT'];
                                    $reqGender = $row['EMPSEX'];
                                endwhile;
                                $dateCreated = new DateTime($dh_sign_date);
                                $newDateformat = $dateCreated->format('m-d-Y h:i:s a');
                            ?>
                                <div class="user-block">
                                    <?php
                                    switch ($reqGender) {
                                        case 1:
                                    ?>
                                            <img class="img-circle img-bordered-sm" src="<?php echo validate_image('admin/appraisalForms/profile.png') ?>">
                                            <?php
                                            break;
                                            ?>
                                        <?php
                                        case 2:
                                        ?>
                                            <img class="img-circle img-bordered-sm" src="<?php echo validate_image('admin/appraisalForms/profile_f.png') ?>">
                                    <?php
                                            break;
                                    }
                                    ?>
                                    <span class="username">
                                        <?php echo $reqName ?>
                                    </span>
                                    <span class="description"><?php echo $reqPosition ?></span>
                                    <span class="description"><?php echo $newDateformat ?></span>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if ($dh_status == 0) {
                            ?>
                                <span class="badge badge-primary ">Pending</span>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php
                        if ($dh_status !== 0) {
                        ?>
                            <label class="control-label text-info">Remarks</label>
                            <p>
                                <?php echo $dh_remarks; ?>
                            </p>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        if ($hr_status == 0) {
                            $lblHeader = "To be Approved ";
                        }
                        if ($hr_status == 1) {
                            $lblHeader = "Approved ";
                        }
                        if ($hr_status == 2) {
                            $lblHeader = "Disapproved ";
                        }
                        ?>
                        <label class="control-label text-info"><?php echo $lblHeader ?> <i>by HR Manager</i></label>
                        <div class="mt-1">
                            <?php
                            if ($hr_status > 0) {
                                $qry = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$hr_name}'");
                                while ($row = $qry->fetch_assoc()) :
                                    $reqName = $row['EMPNAME'];
                                    $reqPosition = $row['JOB_TITLE'];
                                    $reqDepartment = $row['DEPARTMENT'];
                                endwhile;
                                $dateCreated = new DateTime($hr_sign_date);
                                $newDateformat = $dateCreated->format('m-d-Y h:i:s a');
                            ?>
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="<?php echo validate_image('admin/appraisalForms/profile_f.png') ?>">
                                    <span class="username">
                                        <?php echo $reqName ?>
                                    </span>
                                    <span class="description"><?php echo $reqPosition ?></span>
                                    <span class="description"><?php echo $newDateformat ?></span>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if ($hr_status == 0) {
                            ?>
                                <span class="badge badge-primary ">Pending</span>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php
                        if ($hr_status !== 0) {
                        ?>
                            <label class="control-label text-info">Remarks</label>
                            <p>
                                <?php echo $hr_remarks; ?>
                            </p>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        if ($od_status == 0) {
                            $lblHeader = "To be Approved ";
                        }
                        if ($od_status == 1) {
                            $lblHeader = "Approved ";
                        }
                        if ($od_status == 2) {
                            $lblHeader = "Disapproved ";
                        }
                        ?>
                        <label class="control-label text-info"><?php echo $lblHeader ?> by <i>Operations Director</i></label>
                        <div class="mt-1">
                            <?php
                            if ($od_status > 0) {
                                $qry = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$od_name}'");
                                while ($row = $qry->fetch_assoc()) :
                                    $reqName = $row['EMPNAME'];
                                    $reqPosition = $row['JOB_TITLE'];
                                    $reqDepartment = $row['DEPARTMENT'];
                                endwhile;
                                $dateCreated = new DateTime($od_sign_date);
                                $newDateformat = $dateCreated->format('m-d-Y h:i:s a');
                            ?>
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="<?php echo validate_image('admin/appraisalApproval/profile.png') ?>">
                                    <span class="username">
                                        <?php echo $reqName ?>
                                    </span>
                                    <span class="description"><?php echo $reqPosition ?></span>
                                    <span class="description"><?php echo $newDateformat ?></span>
                                </div>

                            <?php
                            }
                            ?>
                            <?php
                            if ($od_status == 0) {
                            ?>
                                <span class="badge badge-primary ">Pending</span>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php
                        if ($od_status !== 0) {
                        ?>
                            <label class="control-label text-info">Remarks</label>
                            <p>
                                <?php echo $od_remarks; ?>
                            </p>
                        <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>

        <form action="" id="pcn-form">
            <input type="hidden" name="pcn_form_no" value="<?php echo $pcn_form_no; ?>">
            <input type="hidden" name="pcn_id" value="<?php echo $pcn_id; ?>">

            <div class="row">
                <div class="col-md-12">
                    <div>
                        <label class="control-label text-info">REMARKS</label>
                        <textarea class="form-control form-control rounded-0" rows="3" name="emp_remarks"></textarea>
                        <br>
                        <div>
                            <button class="btn btn-flat btn btn-outline-success approve_data" type="submit" form="pcn-form" name="action" value="approve" id="approve"> <i class="fas fa-thumbs-up"></i> Acknowledge</button>
                        </div>
                    </div>
                </div>
            </div>
            <div>
            </div>
        </form>
    </div>
    <script>
        $('#pcn-form').submit(function(e) {
            e.preventDefault();
            messageType = 2;
            var _this = $(this)

            // Retrieve the value of the clicked button (either 'approve' or 'disapprove')
            var action = $("button[type=submit][clicked=true]").val();

            $('.err-msg').remove();
            start_loader();

            // Include 'action' in the data sent via AJAX
            var formData = new FormData($(this)[0]);
            formData.append('action', action);

            $.ajax({
                url: _base_url_ + "classes/Master.php?f=ack_pcn",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    alert_toast("An error occured", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        location.replace(_base_url_ + "admin/?page=pcnForms/view_pcn&id=" + resp.id + "&pas=" + resp.pas);
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        end_loader()
                    } else {
                        alert_toast(" Empty employee list !", 'error');
                        end_loader();
                        console.log(resp)
                    }
                    $('html,body').animate({
                        scrollTop: 0
                    }, 'fast')
                }
            })
        })

        // Track which button was clicked and set the 'clicked' attribute
        $("button[type=submit]").click(function() {
            $("button[type=submit]").removeAttr("clicked");
            $(this).attr("clicked", "true");
        });
    </script>