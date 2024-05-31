<?php
$qry = $conn->query("SELECT * FROM pa_forms where form_id = '{$_GET['id']}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
}

$qry = $conn->query("SELECT * FROM appraisal_requests where pa_form_no = '{$_GET['pa']}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        if ($k === 'date_created') {
            $date_created_appraisal_request = $v;
        } else {
            $$k = $v;
        }

        if ($k === 'dh_sign_date') {
            $appraisal_request_dh_sign_date = $v;
        } else {
            $$k = $v;
        }

        if ($k === 'hr_sign_date') {
            $appraisal_request_hr_sign_date = $v;
        } else {
            $$k = $v;
        }

        if ($k === 'od_sign_date') {
            $appraisal_request_od_sign_date = $v;
        } else {
            $$k = $v;
        }

        if ($k === 'emp_job_level') {
            $appraisal_request_emp_job_level = $v;
        } else {
            $$k = $v;
        }

        if ($k === 'employ_type') {
            $appraisal_request_emp_status = $v;
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

function decrypt($data, $key)
{
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $encryptedData = substr($data, 16);
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
    return $decryptedData;
}

$encryptionKey = "7K#p2Y@Wz!qX8e&1";

switch ($appraisal_request_emp_status) {
    case "0":
        $empStatus = "Probationary";
        break;
    case "1":
        $empStatus = "Regular";
        break;
}

switch ($appraisal_request_emp_job_level) {
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

switch ($form_type) {
    case '0':
        $formTypeName = "Probationary";
        break;
    case '1':
        $formTypeName = "Non - Supervisory";
        break;
    case '2':
        $formTypeName = "Supervisory";
        break;
    case '3':
        $formTypeName = "Managerial";
        break;
    default:
        // echo $sysset->index();
        break;
}

$qry_form = $conn->query("SELECT position FROM position where id = '{$form_name}'");
if ($qry_form->num_rows > 0) {
    foreach ($qry_form->fetch_array() as $k => $v) {
        if ($k === 'position') {
            $formPosition = $v;
        } else {
            $$k = $v;
        }
    }
}

?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Performance Appraisal<?php echo " : " . $_GET['pa'] ?></h4>
        <div class="card-tools">
            <?php if ($pa_status == 1) : ?>
                <span class="badge badge-primary rounded-pill">Incomplete Part 2 of 2</span>
            <?php elseif ($pa_status == 2) : ?>
                <span class="badge badge-primary rounded-pill">For DH's Approval</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                // if ($pa_status != 5) {
                //     $lblHeader = "Discussed and Assessed ";
                // }
                // if ($pa_status == 5) {
                //     $lblHeader = "Cancelled ";
                // }
                $lblHeader = "Discussed and Assessed";
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
                $dateCreated = new DateTime($date_created_appraisal_request);
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
        <!-- <div class="card card-primary collapsed-card"> -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">PA Format <?php echo " : " . $formPosition . " - " . $formTypeName . " - Revision " . $rev_num ?></h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
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
                    <h5>Type of Performance Appraisal</h5>
                    <hr>
                    <div class="container-fluid">
                        <?php
                        if ($pa_type == 1 || $pa_type == 2 || $pa_type == 4) {
                        ?>
                            <div class="row">
                                <?php
                                if ($pa_type == 1) {
                                ?>
                                    <div class="col-md-2">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary1" checked value="1">
                                                <label class="control-label" for="radioPrimary1">
                                                    3 Months
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if ($pa_type == 2) {
                                ?>
                                    <div class="col-md-10">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary2" checked value="2">
                                                <label class="control-label" for="radioPrimary2">
                                                    5 Months
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if ($pa_type == 4) {
                                ?>
                                    <div class="col-md-10">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary2" checked value="3">
                                                <label class="control-label" for="radioPrimary2">
                                                    Annual
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if ($pa_type_1 == 1 || $pa_type_others != NULL) {
                        ?>
                            <div class="row">
                                <?php
                                if ($pa_type_1 == 1) {
                                ?>
                                    <div class="col-md-2">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary3" checked value="1">
                                                <label class="control-label" for="radioPrimary3">
                                                    Promotion
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if ($pa_type_others != NULL) {
                                ?>
                                    <div class="col-md">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary5" checked value="2">
                                                <label for="radioPrimary5">
                                                    Others:
                                                </label>
                                                <i><?php echo " " . $pa_type_others ?></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="callout callout-danger">
                    <h5>Employee Performance Targets</h5>
                    <hr>
                    <div class="timeline">
                        <!-- timeline item -->
                        <div>
                            <i class="fas fa-bullhorn bg-yellow"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header no-border">Appropriate percentage allocation of KRA/KPI can be changed by Department Head depending on goals.</h3>
                            </div>
                        </div>
                        <!-- END timeline item -->
                        <!-- timeline item -->
                        <div>
                            <i class="fas fa-info bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">KRA and KPI</h3>
                                <div class="timeline-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            KRA are general areas of outcomes or outputs for which a department's role is responsible.
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            KPI are variables that can be measured to help achieve the organization's goals.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start: format-->
                <?php
                $overall = 0;
                $totalPerfomancePercentage = 0;
                $totalProductivityPercentage = 0;
                $totalQualityPercentage = 0;
                $qry = $conn->query("SELECT * FROM performance_score WHERE form_id = '{$_GET['pa']}'");
                while ($row = $qry->fetch_assoc()) :
                    if ($row['kra_name'] == "Productivity") :
                        $totalProductivityPercentage = $totalProductivityPercentage + $row['kpi_perc'];
                    elseif ($row['kra_name'] == "Quality") :
                        $totalQualityPercentage = $totalQualityPercentage + $row['kpi_perc'];
                    endif;
                endwhile;
                $totalPerfomancePercentage = $totalProductivityPercentage + $totalQualityPercentage;
                ?>
                <div class="callout callout-warning">
                    <h6>Performance Output<?php echo " - " . $totalPerfomancePercentage ?>%</h6>
                    <hr>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">Key Performance Area (KRA)</th>
                                            <th style="width: 55%">Key Performance Indicators (KPI)</th>
                                            <th style="width: 10%">Wtd Ave</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <center>PRODUCTIVITY (<?php echo $totalProductivityPercentage ?>%)</center>
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <!-- while loop -->
                                                    <?php
                                                    $qry = $conn->query("SELECT * FROM performance_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Productivity'");
                                                    while ($row = $qry->fetch_assoc()) :
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <?php echo $row['kpi_name']; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            <b><i>Sub Total</i></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <!-- while loop -->
                                                    <?php
                                                    $wtd_ave = 0;
                                                    $prod_sub_total = 0;
                                                    $prod_pass_rate = 0;
                                                    $qry = $conn->query("SELECT * FROM performance_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Productivity'");
                                                    while ($row = $qry->fetch_assoc()) :

                                                        $wtd_ave = (floatval($row['kpi_perc']) / 100) * $row['kpi_score'];
                                                        $prod_sub_total = $prod_sub_total + $wtd_ave;

                                                        $prod_wtd = (floatval($row['kpi_perc']) / 100) * 3;
                                                        $prod_pass_rate = $prod_pass_rate + $prod_wtd;
                                                    ?>
                                                        <tr>
                                                            <td style="text-align: right;">
                                                                <div>
                                                                    <?php echo $wtd_ave; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                    $overall = $overall + $prod_sub_total;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            <b><i><?php echo  $prod_sub_total ?></i></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <center>QUALITY (<?php echo $totalQualityPercentage ?>%)</center>
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <!-- while loop -->
                                                    <?php
                                                    $qry = $conn->query("SELECT * FROM performance_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Quality'");
                                                    while ($row = $qry->fetch_assoc()) :
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <?php echo $row['kpi_name']; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            <b><i>Sub Total</i></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <!-- while loop -->
                                                    <?php
                                                    $wtd_ave = 0;
                                                    $qual_sub_total = 0;
                                                    $qual_pass_rate = 0;
                                                    $qry = $conn->query("SELECT * FROM performance_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Quality'");
                                                    while ($row = $qry->fetch_assoc()) :

                                                        $wtd_ave = (floatval($row['kpi_perc']) / 100) * $row['kpi_score'];
                                                        $qual_sub_total = $qual_sub_total + $wtd_ave;

                                                        $qual_wtd = (floatval($row['kpi_perc']) / 100) * 3;
                                                        $qual_pass_rate = $qual_pass_rate + $qual_wtd;
                                                    ?>
                                                        <tr>
                                                            <td style="text-align: right;">
                                                                <div>
                                                                    <?php echo $wtd_ave; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                    $overall = $overall +  $qual_sub_total;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            <b><i><?php echo  $qual_sub_total ?></i></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: format-->
                <!-- start: format-->
                <?php
                $totalBehavioralPercentage = 0;
                $totalDisciplinePercentage = 0;
                $totalAttendancePercentage = 0;
                $totalLeadershipPercentage = 0;
                $qry = $conn->query("SELECT * FROM behavioral_score WHERE form_id = '{$_GET['pa']}'");
                while ($row = $qry->fetch_assoc()) :
                    if ($row['kra_name'] == "Discipline") :
                        $totalDisciplinePercentage = $totalDisciplinePercentage + $row['kpi_perc'];
                    elseif ($row['kra_name'] == "Attendance") :
                        $totalAttendancePercentage = $totalAttendancePercentage + $row['kpi_perc'];
                    elseif ($row['kra_name'] == "Leadership") :
                        $totalLeadershipPercentage = $totalLeadershipPercentage + $row['kpi_perc'];
                    endif;
                endwhile;
                $totalBehavioralPercentage = $totalDisciplinePercentage + $totalAttendancePercentage + $totalLeadershipPercentage;
                ?>
                <div class="callout callout-warning">
                    <h6>Behavioral Output<?php echo " - " . $totalBehavioralPercentage ?>%</h6>
                    <hr>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">Key Performance Area (KRA)</th>
                                            <th style="width: 55%">Key Performance Indicators (KPI)</th>
                                            <th style="width: 10%">Wtd Ave</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <center>DISCIPLINE (<?php echo $totalDisciplinePercentage ?>%)</center>
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <!-- while loop -->
                                                    <?php
                                                    $qry = $conn->query("SELECT * FROM behavioral_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Discipline'");
                                                    while ($row = $qry->fetch_assoc()) :
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <?php echo $row['kpi_name']; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            <b><i>Sub Total</i></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <!-- while loop -->
                                                    <?php
                                                    $wtd_ave = 0;
                                                    $disc_sub_total = 0;
                                                    $disc_pass_rate = 0;
                                                    $qry = $conn->query("SELECT * FROM behavioral_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Discipline'");
                                                    while ($row = $qry->fetch_assoc()) :

                                                        $wtd_ave = (floatval($row['kpi_perc']) / 100) * $row['kpi_score'];
                                                        $disc_sub_total = $disc_sub_total + $wtd_ave;

                                                        $disc_wtd = (floatval($row['kpi_perc']) / 100) * 3;
                                                        $disc_pass_rate = $disc_pass_rate + $disc_wtd;
                                                    ?>
                                                        <tr>
                                                            <td style="text-align: right;">
                                                                <div>
                                                                    <?php echo $wtd_ave; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                    $overall = $overall +  $disc_sub_total;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            <b><i><?php echo  $disc_sub_total ?></i></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <center>ATTENDANCE (<?php echo $totalAttendancePercentage ?>%)</center>
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <!-- while loop -->
                                                    <?php
                                                    $qry = $conn->query("SELECT * FROM behavioral_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Attendance'");
                                                    while ($row = $qry->fetch_assoc()) :
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <?php echo $row['kpi_name']; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            <b><i>Sub Total</i></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <!-- while loop -->
                                                    <?php
                                                    $wtd_ave = 0;
                                                    $atte_sub_total = 0;
                                                    $atte_pass_rate = 0;
                                                    $qry = $conn->query("SELECT * FROM behavioral_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Attendance'");
                                                    while ($row = $qry->fetch_assoc()) :

                                                        $wtd_ave = (floatval($row['kpi_perc']) / 100) * $row['kpi_score'];
                                                        $atte_sub_total = $atte_sub_total + $wtd_ave;

                                                        $atte_wtd = (floatval($row['kpi_perc']) / 100) * 3;
                                                        $atte_pass_rate = $atte_pass_rate + $atte_wtd;


                                                    ?>
                                                        <tr>
                                                            <td style="text-align: right;">
                                                                <div>
                                                                    <?php echo $wtd_ave; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                    $overall = $overall +  $atte_sub_total;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            <b><i><?php echo  $atte_sub_total ?></i></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php if ($totalLeadershipPercentage != 0) { ?>
                                            <tr>
                                                <td>
                                                    <center>LEADERSHIP (<?php echo $totalLeadershipPercentage ?>%)</center>
                                                </td>
                                                <td>
                                                    <table width="100%">
                                                        <!-- while loop -->
                                                        <?php
                                                        $qry = $conn->query("SELECT * FROM behavioral_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Leadership'");
                                                        while ($row = $qry->fetch_assoc()) :
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <div>
                                                                        <?php echo $row['kpi_name']; ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endwhile;
                                                        ?>
                                                        <tr>
                                                            <td style="text-align: right;">
                                                                <b><i>Sub Total</i></b>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table width="100%">
                                                        <!-- while loop -->
                                                        <?php
                                                        $wtd_ave = 0;
                                                        $lead_sub_total = 0;
                                                        $lead_pass_rate = 0;
                                                        $qry = $conn->query("SELECT * FROM behavioral_score WHERE form_id = '{$_GET['pa']}' AND kra_name = 'Leadership'");
                                                        while ($row = $qry->fetch_assoc()) :

                                                            $wtd_ave = (floatval($row['kpi_perc']) / 100) * $row['kpi_score'];
                                                            $lead_sub_total = $lead_sub_total + $wtd_ave;

                                                            $lead_wtd = (floatval($row['kpi_perc']) / 100) * 3;
                                                            $lead_pass_rate = $lead_pass_rate + $lead_wtd;


                                                        ?>
                                                            <tr>
                                                                <td style="text-align: right;">
                                                                    <div>
                                                                        <?php echo $wtd_ave; ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endwhile;
                                                        $overall = $overall +  $lead_sub_total;
                                                        ?>
                                                        <tr>
                                                            <td style="text-align: right;">
                                                                <b><i><?php echo  $lead_sub_total ?></i></b>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <div class="timeline">
                                    <!-- timeline time label -->
                                    <div class="time-label">
                                        <span class="bg-info">Overall Rating with Equivalent Adjectives : </span>
                                    </div>
                                    <!-- /.timeline-label -->
                                    <div>
                                        <i class="fas fa-info bg-blue"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header">Rule</h3>
                                            <div class="timeline-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        Based on the overall rating below, you can check how well the employee did and the word that describes it, like "Satisfactory." Even if they got a "Satisfactory" word, if they fail in any of the four KRA's, their overall result is still fail.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- timeline item -->
                                    <div>
                                        <i class="fas fa-star bg-purple"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-body">
                                                <div class="row">
                                                    <?php
                                                    $overallPassed = 0;
                                                    $overallColor = "";
                                                    if ($prod_sub_total >= $prod_pass_rate) {
                                                        $overallPassed = $overallPassed + 1;
                                                    }
                                                    if ($qual_sub_total >= $qual_pass_rate) {
                                                        $overallPassed = $overallPassed + 1;
                                                    }
                                                    if ($disc_sub_total >= $disc_pass_rate) {
                                                        $overallPassed = $overallPassed + 1;
                                                    }
                                                    if ($atte_sub_total >= $atte_pass_rate) {
                                                        $overallPassed = $overallPassed + 1;
                                                    }
                                                    if ($totalLeadershipPercentage != 0 && ($lead_sub_total >= $lead_pass_rate)) {
                                                        $overallPassed = $overallPassed + 1;
                                                    }

                                                    if ($totalLeadershipPercentage != 0) {
                                                        if ($overallPassed == 5) {
                                                            $overallColor = "success";
                                                        } else {
                                                            $overallColor = "danger";
                                                        }
                                                    } else {
                                                        if ($overallPassed == 4) {
                                                            $overallColor = "success";
                                                        } else {
                                                            $overallColor = "danger";
                                                        }
                                                    }

                                                    switch ($overall) {
                                                        case $overall <= 1:
                                                    ?>
                                                            <div class="col-md">
                                                                <div class="info-box bg-<?php echo $overallColor ?>">
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Overall Rating : <?php echo $overall ?></span>
                                                                        <span class="info-box-text">djective : Poor</span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                                <!-- /.info-box -->
                                                            </div>
                                                        <?php
                                                            break;
                                                        case $overall > 1 && $overall <= 2:
                                                        ?>
                                                            <div class="col-md">
                                                                <div class="info-box bg-<?php echo $overallColor ?>">
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Overall Rating : <?php echo $overall ?></span>
                                                                        <span class="info-box-text">djective : Unsatisfactory</span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                                <!-- /.info-box -->
                                                            </div>
                                                        <?php
                                                            break;
                                                        case $overall > 2 && $overall <= 3:
                                                        ?>
                                                            <div class="col-md">
                                                                <div class="info-box bg-<?php echo $overallColor ?>">
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Overall Rating : <?php echo $overall ?></span>
                                                                        <span class="info-box-text">djective : Satisfactory</span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                                <!-- /.info-box -->
                                                            </div>
                                                        <?php
                                                            break;
                                                        case $overall > 3 && $overall <= 4:
                                                        ?>
                                                            <div class="col-md">
                                                                <div class="info-box bg-<?php echo $overallColor ?>">
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Overall Rating : <?php echo $overall ?></span>
                                                                        <span class="info-box-text">Adjective : Very Satisfactory</span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                                <!-- /.info-box -->
                                                            </div>
                                                        <?php
                                                            break;
                                                        case $overall > 4 && $overall <= 5:
                                                        ?>
                                                            <div class="col-md">
                                                                <div class="info-box bg-<?php echo $overallColor ?>">
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Overall Rating : <?php echo $overall ?></span>
                                                                        <span class="info-box-text">Adjective : Outstanding</span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                                <!-- /.info-box -->
                                                            </div>
                                                            <?php
                                                            break;
                                                            ?>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10px">#</th>
                                                            <th>KRA</th>
                                                            <th>Status</th>
                                                            <th style="width: 40px">Score</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>Productivity</td>
                                                            <?php
                                                            if ($prod_sub_total >= $prod_pass_rate) {
                                                                // echo "pass" . $qual_pass_rate . "</br>";
                                                                $color = "success";
                                                                $kraStatusLabel = "Passed";
                                                            } else {
                                                                // echo "fail" . $qual_pass_rate . "</br>";
                                                                $color = "danger";
                                                                $kraStatusLabel = "Failed";
                                                            }
                                                            ?>
                                                            <td>

                                                                <span class="badge badge-<?php echo $color ?>"><?php echo $kraStatusLabel ?></span>

                                                            </td>
                                                            <td class="text-center"><span class="badge bg-<?php echo $color; ?>"><?php echo $prod_sub_total ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>Quality</td>
                                                            <?php
                                                            if ($qual_sub_total >= $qual_pass_rate) {
                                                                // echo "pass" . $qual_pass_rate . "</br>";
                                                                $color = "success";
                                                                $kraStatusLabel = "Passed";
                                                            } else {
                                                                // echo "fail" . $qual_pass_rate . "</br>";
                                                                $color = "danger";
                                                                $kraStatusLabel = "Failed";
                                                            }
                                                            ?>
                                                            <td>

                                                                <span class="badge badge-<?php echo $color ?>"><?php echo $kraStatusLabel ?></span>

                                                            </td>
                                                            <td class="text-center"><span class="badge bg-<?php echo $color; ?>"><?php echo $qual_sub_total ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>3.</td>
                                                            <td>Discipline</td>
                                                            <?php
                                                            if ($disc_sub_total >= $disc_pass_rate) {
                                                                // echo "pass" . $qual_pass_rate . "</br>";
                                                                $color = "success";
                                                                $kraStatusLabel = "Passed";
                                                            } else {
                                                                // echo "fail" . $qual_pass_rate . "</br>";
                                                                $color = "danger";
                                                                $kraStatusLabel = "Failed";
                                                            }
                                                            ?>
                                                            <td>

                                                                <span class="badge badge-<?php echo $color ?>"><?php echo $kraStatusLabel ?></span>

                                                            </td>
                                                            <td class="text-center"><span class="badge bg-<?php echo $color; ?>"><?php echo $disc_sub_total ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>4.</td>
                                                            <td>Attendance</td>
                                                            <?php
                                                            if ($atte_sub_total >= $atte_pass_rate) {
                                                                // echo "pass" . $qual_pass_rate . "</br>";
                                                                $color = "success";
                                                                $kraStatusLabel = "Passed";
                                                            } else {
                                                                // echo "fail" . $qual_pass_rate . "</br>";
                                                                $color = "danger";
                                                                $kraStatusLabel = "Failed";
                                                            }
                                                            ?>
                                                            <td>

                                                                <span class="badge badge-<?php echo $color ?>"><?php echo $kraStatusLabel ?></span>

                                                            </td>
                                                            <td class="text-center"><span class="badge bg-<?php echo $color; ?>"><?php echo $atte_sub_total ?></span></td>
                                                        </tr>
                                                        <?php if ($totalLeadershipPercentage != 0) { ?>
                                                            <tr>
                                                                <td>5.</td>
                                                                <td>Leadership</td>
                                                                <?php
                                                                if ($lead_sub_total >= $lead_pass_rate) {
                                                                    // echo "pass" . $qual_pass_rate . "</br>";
                                                                    $color = "success";
                                                                    $kraStatusLabel = "Passed";
                                                                } else {
                                                                    // echo "fail" . $qual_pass_rate . "</br>";
                                                                    $color = "danger";
                                                                    $kraStatusLabel = "Failed";
                                                                }
                                                                ?>
                                                                <td>

                                                                    <span class="badge badge-<?php echo $color ?>"><?php echo $kraStatusLabel ?></span>

                                                                </td>
                                                                <td class="text-center"><span class="badge bg-<?php echo $color; ?>"><?php echo $lead_sub_total ?></span></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- END timeline item -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: format-->

                <div class="callout callout-info">
                    <h5>Immediate Supervisor Comments</h5>
                    <hr>
                    <div class="container-fluid">
                        <div class="timeline">
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-bullhorn bg-yellow"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Productivity</h3>
                                    <div class="timeline-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <em><?php echo $sup_prod_remarks ?></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-bullhorn bg-yellow"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Quality</h3>
                                    <div class="timeline-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <em><?php echo $sup_qual_remarks ?></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-bullhorn bg-yellow"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Discipline</h3>
                                    <div class="timeline-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <em><?php echo $sup_disc_remarks ?></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-bullhorn bg-yellow"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Attendance</h3>
                                    <div class="timeline-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <em><?php echo $sup_atte_remarks ?></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-bullhorn bg-yellow"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Over-all Comments and Recommendation</h3>
                                    <div class="timeline-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <em><?php echo $sup_overall_remarks ?></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                        </div>
                    </div>
                </div>

                <div class="callout callout-success">
                    <h5>Recommendation</h5>
                    <hr>
                    <div class="container-fluid">
                        <?php
                        if ($pa_reco == 1 || $pa_reco == 2) {
                        ?>
                            <div class="row">
                                <?php
                                if ($pa_reco == 1) {
                                ?>
                                    <div class="col-md-4">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary6" name="pa_reco" id="pa_reco" checked>
                                                <label class="control-label" for="radioPrimary6">
                                                    For Regularization
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if ($pa_reco == 2) {
                                ?>
                                    <div class="col-md-8">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary7" name="pa_reco" id="pa_reco" checked>
                                                <label class="control-label" for="radioPrimary7">
                                                    For Confirmation of Promotion
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if ($pa_reco == 3 || $pa_reco == 4) {
                        ?>
                            <div class="row">
                                <?php
                                if ($pa_reco == 3) {
                                ?>
                                    <div class="col-md-4">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary8" name="pa_reco" id="pa_reco" checked>
                                                <label class="control-label" for="radioPrimary8">
                                                    For End of Contract
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if ($pa_reco == 4) {
                                ?>
                                    <div class="col-md-8">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary9" name="pa_reco" id="pa_reco" checked>
                                                <label class="control-label" for="radioPrimary9">
                                                    For Extension of Training
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if ($pa_reco == 5) {
                        ?>
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="radioPrimary10" name="pa_reco" id="pa_reco" checked>
                                            <label for="radioPrimary10">
                                                Others:
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="form-group clearfix">
                                        <input type="text" name="pa_reco_others" id="pa_reco_others" class="form-control form-control-border border-width-2" placeholder="click here to provide details" value="<?php echo $pa_reco_others ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="callout callout-danger">
                    <h5>Trainings Needed By Employee</h5>
                    <hr>
                    <div class="container-fluid">
                        <div class="callout callout-warning">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Technical Training</h5>
                                </div>
                            </div>
                            <hr>
                            <div id="tech-training" class="timeline">
                                <?php
                                $qry = $conn->query("SELECT * FROM appraisal_tech_trainings where pa_code = '{$pa_form_no}'");
                                if ($qry->num_rows > 0) {
                                    while ($row = $qry->fetch_assoc()) :

                                        $newDateformat = date(" F Y", strtotime($row['tech_train_date']));
                                ?>
                                        <!-- timeline item -->
                                        <div class="tech-items">
                                            <i class="fas fa-user-graduate bg-success"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <h6>Training Title and Completion Date</h6>
                                                        </div>
                                                        <div class="col-md-2">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php echo $row['tech_train_title'];
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php echo $newDateformat;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    endwhile;
                                } else {
                                    ?>
                                    <h6>NA</h6>
                                <?php
                                }
                                ?>
                            </div>

                            <!-- END timeline item -->
                        </div>
                        <div class="callout callout-info">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Behavioral Training</h5>
                                </div>
                            </div>
                            <hr>
                            <div id="beha-training" class="timeline">
                                <?php
                                $qry = $conn->query("SELECT * FROM appraisal_beha_trainings where pa_code = '{$pa_form_no}'");
                                if ($qry->num_rows > 0) {
                                    while ($row = $qry->fetch_assoc()) :

                                        $newDateformat = date(" F Y", strtotime($row['beha_train_date']));
                                ?>
                                        <!-- timeline item -->
                                        <div class="tech-items">
                                            <i class="fas fa-user-graduate bg-success"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <h6>Training Title and Completion Date</h6>
                                                        </div>
                                                        <div class="col-md-2">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php echo $row['beha_train_title'];
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php echo $newDateformat;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    endwhile;
                                } else {
                                    ?>
                                    <h6>NA</h6>
                                <?php
                                }
                                ?>
                            </div>

                            <!-- END timeline item -->
                        </div>
                    </div>
                </div>


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
                        if ($dh_status == 1 || $dh_status == 3) {
                            $lblHeader = "Approved ";
                        }
                        if ($dh_status == 2) {
                            $lblHeader = "Disapproved ";
                        }
                        ?>
                        <label class="control-label text-info"><?php echo $lblHeader ?> by <i>Department Manager</i></label>
                        <div class="mt-1">
                            <?php
                            if ($dh_status == 1 || $dh_status == 2 || $dh_status == 3) {
                                $qry = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$dh_name}'");
                                while ($row = $qry->fetch_assoc()) :
                                    $reqName = $row['EMPNAME'];
                                    $reqPosition = $row['JOB_TITLE'];
                                    $reqDepartment = $row['DEPARTMENT'];
                                    $reqGender = $row['EMPSEX'];
                                endwhile;
                                $dateCreated = new DateTime($appraisal_request_dh_sign_date);
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
                        if ($dh_status != 0 && $dh_status != 3) {
                        ?>
                            <label class="control-label text-info">Remarks</label>
                            <p>
                                <?php echo $dh_remarks; ?>
                            </p>
                        <?php
                        }
                        ?>
                        <?php
                        if ($dh_status == 3) {
                        ?>
                            <label class="control-label text-info">Remarks</label>
                            <p>
                                <span class="badge badge-info ">Not Applicable</span>
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
                        if ($hr_status == 1 || $hr_status == 3) {
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
                                $dateCreated = new DateTime($appraisal_request_hr_sign_date);
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
                        if ($hr_status !== 0 && $hr_status != 3) {
                        ?>
                            <label class="control-label text-info">Remarks</label>
                            <p>
                                <?php echo $hr_remarks; ?>
                            </p>
                        <?php
                        }
                        ?>
                        <?php
                        if ($hr_status == 3) {
                        ?>
                            <label class="control-label text-info">Remarks</label>
                            <p>
                                <span class="badge badge-info ">Not Applicable</span>
                            </p>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                if ($pa_type != 1) {
                ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            if ($od_status == 0) {
                                $lblHeader = "To be Approved ";
                            }
                            if ($od_status == 1 || $od_status == 3) {
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
                                    $dateCreated = new DateTime($appraisal_request_od_sign_date);
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
                <?php
                }
                ?>
                <!-- employee -->
                <?php
                if ($emp_status == 1) {
                ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            if ($emp_status == 0) {
                                $lblHeader = "To be Acknowledge ";
                            }
                            if ($emp_status == 1) {
                                $lblHeader = "Acknowledged ";
                            }

                            ?>
                            <label class="control-label text-info"><?php echo $lblHeader ?> by <i>Appraisee</i></label>
                            <div class="mt-1">
                                <?php
                                if ($emp_status > 0) {
                                    $qry = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$emp_num}'");
                                    while ($row = $qry->fetch_assoc()) :
                                        $reqName = $row['EMPNAME'];
                                        $reqPosition = $row['JOB_TITLE'];
                                        $reqDepartment = $row['DEPARTMENT'];
                                    endwhile;
                                    $dateCreated = new DateTime($emp_sign_date);
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
                                if ($emp_status == 0) {
                                ?>
                                    <span class="badge badge-primary ">Pending</span>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($emp_status !== 0) {
                            ?>
                                <label class="control-label text-info">Remarks</label>
                                <p>
                                    <?php echo $emp_remarks; ?>
                                </p>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <hr>
        <form action="" id="pa-form">
            <input type="hidden" name="pa_form_no" value="<?php echo $_GET['pa']; ?>">
            <input type="hidden" name="form_id" value="<?php echo $_GET['id']; ?>">
            <input type="hidden" name="pcn_form_no" value="<?php echo $pcnFormNo; ?>">
            <input type="hidden" name="appType" value="<?php echo $_settings->userdata('EMPPOSITION'); ?>">
            <input type="hidden" name="approverNum" value="<?php echo $_settings->userdata('EMPLOYID'); ?>">
            <input type="hidden" name="apprDept" value="<?php echo $_settings->userdata('DEPARTMENT'); ?>">

            <div class="row mt-4">
                <div class="col-md-12">
                    <label class="control-label text-dark">
                        <i>"I hereby acknowledge receipt of the document pertaining to the results of my recent appraisal and confirm that I have reviewed and accepted the outcomes outlined therein."</i>
                    </label>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div>
                        <label class="control-label text-info">REMARKS</label>
                        <textarea class="form-control form-control rounded-0" rows="3" name="emp_remarks"></textarea>
                        <br>
                        <div>
                            <button class="btn btn-flat btn btn-outline-success approve_data" type="submit" form="pa-form" name="action" value="approve" id="approve"> <i class="fas fa-thumbs-up"></i> Acknowledge</button>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    <script>
        $('#pa-form').submit(function(e) {
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
                url: _base_url_ + "classes/Master.php?f=ack_pa",
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
                        location.replace(_base_url_ + "admin?page=appraisalForms/view_pa_approval&id=" + resp.id + "&pa=" + resp.pfn);
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