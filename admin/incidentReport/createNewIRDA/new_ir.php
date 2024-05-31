<?php
// if (isset($_GET['id']) && $_GET['id'] > 0) {
//     $qry = $conn->query("SELECT * from `ir_requests` where id = '{$_GET['id']}' ");
//     if ($qry->num_rows > 0) {
//         foreach ($qry->fetch_assoc() as $k => $v) {
//             $$k = $v;
//         }
//     }
// }
unset($_SESSION['date_commited_array']);
unset($_SESSION['date_commited_array_aq']);
unset($_SESSION['date_commited_array_bq']);
$year = date('Y'); // Get the current year
if ($year = '2024') {
    $thisy = $conn->query("SELECT ir_no FROM ir_requests ORDER BY CAST(SUBSTRING_INDEX(ir_no, '-', -1) AS UNSIGNED) DESC LIMIT 1")->fetch_array()[0];



    $parts = explode('-', $thisy);

    if (count($parts) == 2) {
        $number = $parts[1];
        $control_no = $number;
    }
} else {
    $control_no = $conn->query("SELECT * FROM ir_requests WHERE YEAR(date_created) = $year")->num_rows;
}
// $control_no = $conn->query("SELECT * FROM ir_requests WHERE YEAR(date_created) = $year")->num_rows;
$e_id = json_encode($_settings->userdata('EMPLOYID'));
$e_dp = json_encode($_settings->userdata('DEPARTMENT'));
// if ($_settings->userdata('DEPARTMENT') == 'Human Resource') {
//     $hr_name = $_settings->userdata('EMPLOYID');
//     $hr_status = 1;
//     $hr_sign_date = date('Y-m-d H:i:s');
//     $hr_ = $conn->query("SELECT empname from `employee_masterlist` where employid = '{$_settings->userdata('EMPLOYID')}' ")->fetch_array()[0];
// }
?>
<style>
    input.form-control:read-only {
        background-color: #fff;
    }

    select.custom-select:disabled {
        background-color: #fff;
    }

    textarea.form-control:read-only {
        background-color: #fff;
    }
</style>
<div class="card card-outline card-primary  overflow-auto">
    <!-- <div class="card-header">
        <h4 class="card-title card-primary">CREATE NEW INCIDENT REPORT</h4>
    </div> -->
    <div class="card-body">
        <form action="" id="ir-form">
            <!-- <input type="hidden" name="id" value="<?php echo isset($id)  ? $id : '' ?>"> -->
            <input type="hidden" name="requestor_id" value="<?php echo $_settings->userdata('EMPLOYID') ?>">
            <h4 class="text-center"><strong>I. Incident Report</strong></h4>
            <div class="container-fluid">
                <div class=" justify-content-end row">
                    <label class=" col-form-label text-info">IR No.</label>
                    <div class="col-sm-3">
                        <input type="text" readonly class="form-control rounded-0 text-inline" id="ir_no" value="<?php echo isset($ir_no) ? $ir_no :  date('Y') . '-' . sprintf("%03d", $control_no + 1) ?>">
                    </div>
                </div>
                <div class=" justify-content-end row">
                    <label class=" col-form-label text-info">Date</label>
                    <div class="col-sm-3">
                        <input type="date" readonly class="form-control  rounded-0" class="form-control  rounded-0" value="<?php echo isset($date_created)  ? date('Y/m/d', strtotime($date_created)) : date('Y-m-d') ?>">

                    </div>
                </div>
                <h5>Details of person involved:</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Employee no</label>
                        <!-- <input required type="number" name="emp_no" id="emp_no" class="form-control  rounded-0" onwheel="event.preventDefault()" onkeyup="showHint(this.value)" value="<?php echo isset($emp_no)  ? $emp_no : '' ?>"> -->
                        <select name="emp_no" onchange="showHint(this.value)" id="emp_no" class="form-control select2" required>
                            <option value="" disabled selected></option>
                            <?php
                            $application = $conn->query("SELECT * FROM `employee_masterlist` where EMPLOYID !=0");
                            while ($row = $application->fetch_assoc()) :
                            ?>
                                <option value="<?= $row['EMPLOYID'] ?>"><?= $row['EMPLOYID'] . " - " . $row['EMPNAME'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label text-info">Shift/Team</label>
                        <input required type="text" name="shift" readonly id="shift" class="form-control  rounded-0" value="<?php echo isset($shift) ? $shift : '' ?>">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Employee name</label>
                        <input required readonly type="text" name="emp_name" id="emp_name" class="form-control  rounded-0" value="<?php echo isset($emp_name)  ? $emp_name : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label text-info">Position</label>
                        <input required type="text" readonly name="position" id="position" class="form-control  rounded-0" value="<?php echo isset($position)  ? $position : '' ?>">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label text-info">Department</label>
                        <input required readonly type="text" name="department" id="department" class="form-control  rounded-0" value="<?php echo isset($department)  ? $department : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="control-label text-info">Station</label>
                        <input required readonly type="text" name="station" id="station" class="form-control  rounded-0" value="<?php echo isset($station)  ? $station : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="control-label text-info">Product line</label>
                        <input required type="text" readonly name="productline" id="productline" class="form-control rounded-0" value="<?php echo isset($productline)  ? $productline : '' ?>">
                    </div>
                </div>
                <br>
                <h5>Statement of facts/incident details:</h5>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label text-info">What</label>
                        <textarea required type="text" name="what" class="form-control  rounded-0"><?php echo isset($what)  ? $what : '' ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label text-info">When</label>
                        <input required type="date" name="when" class="form-control  rounded-0" value="<?php echo isset($when)  ? $when : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label text-info">Where</label>
                        <input required type="text" name="where" class="form-control  rounded-0" value="<?php echo isset($where)  ? $where : '' ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="control-label text-info">How/Other information</label>
                        <textarea required type="text" name="how" class="form-control  rounded-0"><?php echo isset($how)  ? $how : '' ?></textarea>
                    </div>
                </div>
                <!-- <p class="text-center"><button type="button" id="openModalBtn" class="btn btn-flat btn-sm btn-primary">FILL UP</button></p> -->
                <br>
                <!-- <h4 class="text-center"><strong>II. Letter of explanation</strong></h4>

                <p align="justify">
                    Please explain in writing within 5 calendar days upon receipt hereof,why no disciplinary action should be taken against you for the
                    violations committed stated on the Incident report. No explanation received within the period alloted to you may be construed
                    as an admission of offense levelled on you and waived your right to be heard and the corresponding penalty should be given
                    to you.
                </p>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label text-info">Why</label>
                        <textarea type="text" readonly name="why" class="form-control emp_why rounded-0"><?php echo isset($why)  ? $why : '' ?></textarea>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <input type="text" readonly class="form-control rounded-0 emp_sign" placeholder="Place your signature here." style="border: 0; border-bottom: 1px solid black; text-align:center" name="sign" class="form-control  rounded-0" value="<?php echo isset($sign)  ? $sign : '' ?>">
                        <label class="control-label  text-info">Employee's signature over printed name / date</label>
                    </div>
                </div>
 -->

                <hr>
                <br>
                <h5>Please check appropriate box and indicate the reference no. for quality violation:</h5><br>
                <div class="row not_">
                    <div class="col-3">
                        <div class="form-group clearfix">
                            <div class="icheck-primary btn-lg d-inline">
                                <input type="radio" value="1" class="check not_" id="administrative" name="quality_violation" required>
                                <label for="administrative">Administrative</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group clearfix">
                            <div class="icheck-primary btn-lg d-inline">
                                <input type="radio" value="2" class="check not_ ehe" id="quality" name="quality_violation" required>
                                <label for="quality">Quality</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="control-label">Reference document(IPNR/QDN/EQS):</label>
                            <input type="text" disabled required name="reference" id="reference" class="form-control not_ rounded-0" value="<?php echo isset($reference)  ? $reference : '' ?>">
                        </div>
                    </div>
                </div>
                </br>
                </br>
                <div class="text-right">
                    <a href="javascript:void(0)" class="btn btn-primary btn-block-sm view_codes" type="button"><i class="fa fa-eye"></i> View all violations</a> <br><br>
                </div>
                <fieldset>


                    <table class=" table-striped table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="40%">
                            <col width="15%">
                            <col width="25%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center py-1 px-2">Code no.</th>
                                <th class="text-center py-1 px-2">Violation/Nature of offenses</th>
                                <th class="text-center py-1 px-2">D.A</th>
                                <th class="text-center py-1 px-2">Date commited</th>
                                <th class="text-center py-1 px-2">No. of offense</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>

                                    <select name="code_no[]" onchange="get_code(this.value)" class="form-control code_no select2" disabled required>
                                        <option value="" disabled selected>----</option>
                                        <?php
                                        $application = $conn->query("SELECT * FROM `ir_code_no` where status = 1  ");
                                        while ($row = $application->fetch_assoc()) :
                                        ?>
                                            <option value="<?= $row['code_number'] ?>"><?= $row['code_number'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </td>
                                <td><input readonly autocomplete="off" type="text" class="form-control" id="violation" name="violation[]"></td>
                                <td>
                                    <select name="da_type[]" required id="da_type" class="custom-select not_">
                                        <option value="" disabled selected>--Select D.A--</option>
                                        <option <?php echo isset($da_type) && $da_type == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
                                        <option <?php echo isset($da_type) && $da_type == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
                                        <option <?php echo isset($da_type) && $da_type == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
                                        <option <?php echo isset($da_type) && $da_type == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
                                        <option <?php echo isset($da_type) && $da_type == 5 ? 'selected' : '' ?> value="5">Dismissal</option>

                                    </select>
                                </td>
                                <input type="hidden" class="not_for12" value="1">
                                <input type="hidden" class="c_val" value="0">
                                <input type="hidden" class="not_foraq" value="1">
                                <input type="hidden" class="aq_c_val" value="0">
                                <input type="hidden" class="not_forbq" value="1">
                                <input type="hidden" class="bq_c_val" value="0">
                                <td>
                                    <div class="input-group">
                                        <input autocomplete="off" type="text" id="date_com_id" class="form-control asa datepicker" required name="date_commited[]">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    <input autocomplete="off" type="text" class="form-control not_qa" id="offense_no" required name="offense_no[]" value="">
                                    <select required name="offense_no[]" id="offense_no" disabled class="custom-select is_qa d-none">
                                        <option value="" disabled selected>--No. of offense--</option>
                                        <option <?php echo isset($offense_no) && $offense_no == '1 out of 5' ? 'selected' : '' ?> value="1 out of 5">1 out of 5 </option>
                                        <option <?php echo isset($offense_no) && $offense_no == '2 out of 5' ? 'selected' : '' ?> value="2 out of 5">2 out of 5</option>
                                        <option <?php echo isset($offense_no) && $offense_no == '3 out of 5' ? 'selected' : '' ?> value="3 out of 5">3 out of 5</option>
                                        <option <?php echo isset($offense_no) && $offense_no == '4 out of 5' ? 'selected' : '' ?> value="4 out of 5">4 out of 5</option>
                                        <option <?php echo isset($offense_no) && $offense_no == '5 out of 5' ? 'selected' : '' ?> value="5 out of 5">5 out of 5</option>
                                    </select>
                                </td>

                            </tr>

                        </tbody>
                    </table>
                </fieldset>


                <fieldset>
                    <div id="option-list">
                    </div>
                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-block-sm not_ d-none" id="add_option" type="button"><i class="fa fa-plus"></i> Add another violation</button>
                    </div>

                </fieldset>
                <noscript id="option-clone">
                    <div class="item mt-3">
                        <div class="list-group" id="option-list">

                            <div class="row justify-content-end">
                                <!-- <div class="input-group mb-3"> -->
                                <!-- <div class="col-md-2"><button type="button" id="xbtn" class="remove-option btn btn-danger btn-block btn-sm"><i class="fas fa-backspace"></i></button></div> -->
                                <!-- </div> -->
                                <button tabindex="-1" class=" remove-option col-1 text-reset btn btn-danger mb-2 mr-3" id="xbtn" title="Remove Option1"><i class="fas fa-times"></i></button>

                            </div>
                            <table class=" table-striped table-bordered">
                                <colgroup>
                                    <col width="10%">
                                    <col width="40%">
                                    <col width="15%">
                                    <col width="25%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-center py-1 px-2">Code no.</th>
                                        <th class="text-center py-1 px-2">Violation/Nature of offenses</th>
                                        <th class="text-center py-1 px-2">D.A</th>
                                        <th class="text-center py-1 px-2">Date commited</th>
                                        <th class="text-center py-1 px-2">No. of offense</th>
                                        <!-- <th class="text-center py-1 px-2">Disposition</th>
                                            <th class="text-center py-1 px-2">Schedule of suspension</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td>
                                            <!-- <input autocomplete="off" type="text" class="form-control"  name="code_no[]"> -->
                                            <select name="code_no[]" class="custom-select select2 code_no increase_id" required>
                                                <option value="" disabled selected></option>
                                                <?php
                                                $application = $conn->query("SELECT * FROM `ir_code_no`  where status = 1");
                                                while ($row = $application->fetch_assoc()) :
                                                ?>
                                                    <option value="<?= $row['code_number'] ?>"><?= $row['code_number'] ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </td>
                                        <td><input readonly autocomplete="off" type="text" class="form-control" id="violation" name="violation[]"></td>
                                        <td>
                                            <select required name="da_type[]" class="custom-select">
                                                <option value="" disabled selected>--Select D.A--</option>
                                                <option <?php echo isset($da_type) && $da_type == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
                                                <option <?php echo isset($da_type) && $da_type == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
                                                <option <?php echo isset($da_type) && $da_type == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
                                                <option <?php echo isset($da_type) && $da_type == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
                                                <option <?php echo isset($da_type) && $da_type == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
                                            </select>
                                        </td>
                                        <input type="hidden" class="for12" value="0">
                                        <input type="hidden" class="clone_c_val" value="0">
                                        <input type="hidden" class="foraq" value="0">
                                        <input type="hidden" class="aq_clone_c_val" value="0">
                                        <input type="hidden" class="forbq" value="0">
                                        <input type="hidden" class="bq_clone_c_val" value="0">
                                        <td>
                                            <!-- <input autocomplete="off" type="text" class="form-control datepicker" id="date_commited" required name="date_commited[]"> -->
                                            <div class="input-group">
                                                <input autocomplete="off" type="text" class="form-control asa datepicker" id="date_commited" required name="date_commited[]">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input autocomplete="off" type="text" class="form-control not_qa" id="offense_no" required name="offense_no[]" value="">
                                            <select required name="offense_no[]" id="offense_no" disabled class="custom-select is_qa d-none">
                                                <option value="" disabled selected>--No. of offense--</option>
                                                <option <?php echo isset($offense_no) && $offense_no == '1 out of 5' ? 'selected' : '' ?> value="1 out of 5">1 out of 5 </option>
                                                <option <?php echo isset($offense_no) && $offense_no == '2 out of 5' ? 'selected' : '' ?> value="2 out of 5">2 out of 5</option>
                                                <option <?php echo isset($offense_no) && $offense_no == '3 out of 5' ? 'selected' : '' ?> value="3 out of 5">3 out of 5</option>
                                                <option <?php echo isset($offense_no) && $offense_no == '4 out of 5' ? 'selected' : '' ?> value="4 out of 5">4 out of 5</option>
                                                <option <?php echo isset($offense_no) && $offense_no == '5 out of 5' ? 'selected' : '' ?> value="5 out of 5">5 out of 5</option>
                                            </select>
                                        </td>
                                        <!-- <td>
                                                <select required name="disposition[]" class="custom-select ">
                                                    <option value="" disabled selected>--Disposition--</option>
                                                    <option <?php echo isset($disposition) && $disposition == 1 ? 'selected' : '' ?> value="1">VALID</option>
                                                    <option <?php echo isset($disposition) && $disposition == 2 ? 'selected' : '' ?> value="2">INVALID</option>
                                                </select>
                                            </td> -->
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </noscript>

                <br>

                <br>

                <div class="row justify-content-between">
                    <div class="col-5">
                        <label class="control-label  text-info">Reported : </label>
                        <input readonly type="text" class="form-control  rounded-0" name="requestor_name" class="form-control  rounded-0" value="<?php echo $_settings->userdata('EMPNAME') ?>">
                        <i class="text-info" style="display:block; text-align: center;">Name</i>
                    </div>
                    <div class="col-4">
                        <label class="control-label  text-info">Date </label>
                        <input readonly type="date" readonly class="form-control  rounded-0" class="form-control  rounded-0" value="<?php echo date('Y-m-d')  ?>">

                    </div>
                </div>

                <div class="row justify-content-between">
                    <div class="col-5">
                        <label class="control-label  text-info">For validation: </label>
                        <input readonly type="text" placeholder="Human resource personnel" class="form-control  rounded-0" value="<?php echo isset($hr_)  ? $hr_ : '' ?>">
                        <input readonly type="hidden" placeholder="Human resource personnel" name="hr_name" class="form-control  rounded-0" value="<?php echo isset($hr_name)  ? $hr_name : '' ?>">
                        <!-- <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource') { ?>
                            <input readonly type="hidden" placeholder="Human resource personnel" name="hr_status" class="form-control  rounded-0" value="<?php echo isset($hr_status)  ? $hr_status : '' ?>">
                        <?php } ?> -->
                        <i class="text-info" style="display:block; text-align: center;">HR Personnel</i>
                    </div>
                    <div class="col-4">
                        <label class="control-label  text-info">Date </label>
                        <input readonly type="text" name="hr_sign_date" class="form-control  rounded-0" value="<?php echo isset($hr_sign_date)  ? $hr_sign_date : '' ?>">

                    </div>
                </div>
                <div class="row justify-content-between">
                    <div class="form-group  col-5">
                        <label class="control-label  text-info">For approval : </label>

                        <input readonly type="hidden" class="form-control  rounded-0" name="sv_name" id="sv_name" class="form-control  rounded-0" value="<?php echo isset($sv_name)  ? $sv_name : '' ?>">
                        <input readonly type="text" class="form-control  rounded-0" id="sv_name1" class="form-control  rounded-0" value="<?php echo isset($sv)  ? $sv : '' ?>">

                        <!-- <select name="sv_name" id="sv_name" class="form-control select2" required>
                            <option value="" disabled selected></option>
                            <?php
                            $application = $conn->query("SELECT * FROM `employee_masterlist` where EMPPOSITION > 1");
                            while ($row = $application->fetch_assoc()) :
                            ?>
                                <option value="<?= $row['EMPLOYID'] ?>"><?= $row['EMPLOYID'] . " - " . $row['EMPNAME'] ?></option>
                            <?php endwhile; ?>
                        </select> -->
                        <i class="text-info" style="display:block; text-align: center;">Approver 1</i>
                    </div>
                    <div class="col-4">
                        <label class="control-label  text-info">Date </label>
                        <input readonly type="date" name="sv_sign_date" class="form-control  rounded-0" value="<?php echo isset($sv_sign_date)  ? date('Y/m/d', strtotime($sv_sign_date)) : '' ?>">
                    </div>
                </div>
                <div class="row justify-content-between">
                    <div class="form-group  col-5">
                        <label class="control-label  text-info">For approval : </label>
                        <input readonly type="hidden" class="form-control  rounded-0" name="dh_name" id="dh_name" class="form-control  rounded-0" value="<?php echo isset($dh_name)  ? $dh_name : '' ?>">
                        <input readonly type="text" class="form-control  rounded-0" id="dh_name1" class="form-control  rounded-0" value="<?php echo isset($dh_name)  ? $dh_name : '' ?>">

                        <!-- <select name="dh_name" id="dh_name" class="form-control select2" required>
                            <option value="" disabled <?= !isset($dh_name) ? "selected" : "" ?>></option>
                            <?php
                            $application = $conn->query("SELECT * FROM `employee_masterlist` ORDER BY EMPNAME");
                            while ($row = $application->fetch_assoc()) :
                            ?>
                                <option value="<?= $row['EMPLOYID'] ?>" <?php echo isset($dh_name) && $dh_name == $row['EMPLOYID'] ? 'selected' : '' ?>><?= $row['EMPNAME'] ?></option>
                            <?php endwhile; ?>
                        </select> -->
                        <i class="text-info" style="display:block; text-align: center;">Approver 2</i>
                    </div>
                    <div class="col-4">
                        <label class="control-label  text-info">Date </label>
                        <input readonly type="date" class="form-control  rounded-0" name="dh_sign_date" class="form-control  rounded-0" value="<?php echo isset($dh_sign_date)  ? date('Y/m/d', strtotime($dh_sign_date)) : '' ?>">
                    </div>
                </div>
            </div>
            <input readonly type="hidden" class="form-control  rounded-0" name="dm_name" id="dm_name" class="form-control  rounded-0" value="<?php echo isset($dm_name)  ? $dm_name : '' ?>">


            <div class=" py-1 text-center">
                <button class="btn btn-flat btn-primary" type="submit" form="ir-form">SUBMIT</button>
                <!-- <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=incidentreport/createNewIRDA' ?>">Cancel</a> -->
            </div>
    </div>

    </form>
</div>
</div>
<script>
    $('.datepicker').each(function() {
        $(this).datepick({
            multiSelect: 999,
            multiSeparator: ' + ',
            dateFormat: 'yyyy-mm-dd' // Adjust the format as needed
            // minDate: new Date()
        });
    });

    $('input[name="quality_violation"]').on('change', function() {
        if ($('#quality').is(':checked')) {
            $('#reference').attr('disabled', false)
        } else {
            $('#reference').attr('disabled', true)
            $('#reference').val('')
        }
    })
    var e_id = <?php echo $e_id; ?>;
    var e_dp = <?php echo $e_dp; ?>;
    // $('.not_').attr('disabled', true)
    if ($('#emp_no').val() == e_id && e_dp != 'Human Resource') {
        $('.emp_why').removeAttr('readonly')
        $('.emp_sign').removeAttr('readonly')
        $('.emp_sign').attr('required', true)
        $('.emp_why').attr('required', true)
    }
    $('#emp_no').change(function() {
        console.log($('#emp_no').val())
        if ($('#emp_no').val() == e_id && e_dp != 'Human Resource') {
            // $('.not_').attr('disabled', true)
            $('.emp_why').removeAttr('readonly')
            $('.emp_sign').removeAttr('readonly')
            $('.emp_sign').attr('required', true)
            $('.emp_why').attr('required', true)

        } else {
            // $('.not_').removeAttr('disabled')

            $('.emp_why').removeAttr('required')
            $('.emp_sign').removeAttr('required')
            $('.emp_sign').attr('readonly', true)
            $('.emp_why').attr('readonly', true)
        }
    })


    function showHint(str) {
        if (str.length == 0) {
            document.getElementById("emp_name").value = "";
            document.getElementById("department").value = "";
            document.getElementById("shift").value = "";
            document.getElementById("position").value = "";
            document.getElementById("station").value = "";
            document.getElementById("productline").value = "";
            // document.getElementById("sv_name").value = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("emp_name").value = this.responseText;
                    // document.getElementById("requestor_department").value = this.responseText;
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_emp.php?q=" + str, true);
            xmlhttp.send();


            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("department").value = this.responseText;
                    // document.getElementById("requestor_department").value = this.responseText;
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_dept.php?q=" + str, true);
            xmlhttp.send();


            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("shift").value = this.responseText;
                    // document.getElementById("requestor_department").value = this.responseText;
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_shift.php?q=" + str, true);
            xmlhttp.send();

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("position").value = this.responseText;
                    // document.getElementById("requestor_department").value = this.responseText;
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_pos.php?q=" + str, true);
            xmlhttp.send();

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("station").value = this.responseText;
                    // document.getElementById("requestor_department").value = this.responseText;
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_station.php?q=" + str, true);
            xmlhttp.send();

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("productline").value = this.responseText;
                    // document.getElementById("requestor_department").value = this.responseText;
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_pl.php?q=" + str, true);
            xmlhttp.send();


            // var xmlhttp = new XMLHttpRequest();
            // xmlhttp.onreadystatechange = function() {
            //     if (this.readyState == 4 && this.status == 200) {
            //         // document.getElementById("sv_name").value = this.responseText;
            //         console.log('tangina ano ba tooo!!!' + this.responseText)
            //         var inputValue = this.responseText;


            //         // Get the dropdown element
            //         var dropdown = document.getElementById("sv_name");

            //         // Find the option with the desired value and set it as selected
            //         var optionToSelect = dropdown.querySelector('option[value="' + inputValue + '"]');

            //         if (optionToSelect) {
            //             optionToSelect.selected = true;
            //         }
            //         // document.getElementById("requestor_department").value = this.responseText;
            //     }
            // };
            // xmlhttp.open("GET", _base_url_ + "get_apr1.php?q=" + str, true);
            // xmlhttp.send();

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var text = this.responseText;
                    var myArray = text.split("/");

                    document.getElementById("sv_name").value = myArray[0];
                    document.getElementById("sv_name1").value = myArray[1];
                    document.getElementById("dh_name").value = myArray[2];
                    document.getElementById("dh_name1").value = myArray[3];
                    document.getElementById("dm_name").value = myArray[4];
                    // document.getElementById("requestor_department").value = this.responseText;
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_appr.php?q=" + str, true);
            xmlhttp.send();
        }

    }
    $('#emp_no').on('change', function() {
        $('.code_no').removeAttr('disabled')
        $('#add_option').removeClass('d-none')
    })
    // $('#offense_no').on('change', function() {
    //     console.log('tangina ano ba to!!!!!')
    //     if ($("#offense_no").val() == 4) {
    //         $('#da_type').val(4)
    //     } else {
    //         $('#da_type').val('dijaosdl')

    //     }
    // })
    var count = 0;
    var trig = false;
    // Function to increment count
    function counting() {
        if (trig == true) {
            if ($('.code_no').val() == 'A-#12' && parseInt($('.not_for12').val()) == 1) {
                count = count + parseInt($('.not_for12').val());
                $('.not_for12').val(2)
                $('.c_val').val(count)
                // trig = false;
                console.log('Count:', count);
            }
        }
    }


    var aq_count = 0;
    var aq_trig = false;
    // Function to increment count
    function aq_counting() {
        if (aq_trig == true) {
            if ($('.code_no').val() == 'AQ-#2' && parseInt($('.not_foraq').val()) == 1) {
                aq_count = aq_count + parseInt($('.not_foraq').val());
                $('.not_foraq').val(2)
                $('.aq_c_val').val(aq_count)
                // trig = false;
                console.log('aq_Count:', aq_count);
            }
        }
    }


    var bq_count = 0;
    var bq_trig = false;
    // Function to increment count
    function bq_counting() {
        if (bq_trig == true) {
            if ($('.code_no').val() == 'BQ-#3' && parseInt($('.not_forbq').val()) == 1) {
                bq_count = bq_count + parseInt($('.not_forbq').val());
                $('.not_forbq').val(2)
                $('.bq_c_val').val(bq_count)
                // trig = false;
                console.log('bq_Count:', bq_count);
            }
        }
    }
    // var fora12 = 0;
    $('.asa').on("blur", function() {
        var date_commited = $('.asa').val();
        var code_n = $('.code_no').val();
        console.log('this is code num' + code_n)
        if (code_n == 'A-#12') {
            trig = true;
            counting();
            // fora12 = parseInt($('.for12').val()) + 1;
            $.ajax({
                url: _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(code_n) + "&i=" + $('#emp_no').val() + "&date_commited=" + $('.asa').val() + "&count=" + count + "&arr_index=" + $('.c_val').val(),
                type: "GET",
                success: function(response) {
                    $("#offense_no").val(response);
                    console.log('tangina ano ba to!!!!!')
                    // if (response == 4) {
                    //     $('#da_type').val(4)
                    // } else {
                    //     $('#da_type').val(3)

                    // }

                }
            });
        } else {
            if (parseInt($('.not_for12').val()) == 2) {
                count--;
                $('.not_for12').val(1)
                $('.c_val').val(count)
                console.log('Count:', count);
            }
            // if (parseInt($('.not_foraq').val()) == 2) {
            //     aq_count--;
            //     $('.not_foraq').val(1)
            //     $('.aq_c_val').val(aq_count)
            // }
            // if (parseInt($('.not_forbq').val()) == 2) {
            //     bq_count--;
            //     $('.not_forbq').val(1)
            //     $('.bq_c_val').val(aq_count)
            // }
            // if (fora12 > 0) {
            //     fora12 = parseInt($('.for12').val()) - 1;
            // } else if (fora12 < 0) {
            //     fora12 = 0;
            // }
        }
        if (code_n == 'AQ-#2') {
            aq_trig = true;
            aq_counting();
            // fora12 = parseInt($('.for12').val()) + 1;
            $.ajax({
                url: _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(code_n) + "&i=" + $('#emp_no').val() + "&aq_date_commited=" + $('.asa').val() + "&aq_count=" + aq_count + "&aq_arr_index=" + $('.aq_c_val').val(),
                type: "GET",
                success: function(response) {
                    $("#offense_no").val(response);
                    console.log('tangina ano ba to!!!!!')
                    // if (response == 4) {
                    //     $('#da_type').val(4)
                    // } else {
                    //     $('#da_type').val(3)

                    // }

                }
            });
        } else {
            // if (parseInt($('.not_for12').val()) == 2) {
            //     count--;
            //     $('.not_for12').val(1)
            //     $('.c_val').val(count)
            //     console.log('Count:', count);
            // }
            if (parseInt($('.not_foraq').val()) == 2) {
                aq_count--;
                $('.not_foraq').val(1)
                $('.aq_c_val').val(aq_count)
            }
            // if (parseInt($('.not_forbq').val()) == 2) {
            //     bq_count--;
            //     $('.not_forbq').val(1)
            //     $('.bq_c_val').val(aq_count)
            // }
            // if (fora12 > 0) {
            //     fora12 = parseInt($('.for12').val()) - 1;
            // } else if (fora12 < 0) {
            //     fora12 = 0;
            // }
        }
        if (code_n == 'BQ-#3') {
            bq_trig = true;
            bq_counting();
            // fora12 = parseInt($('.for12').val()) + 1;
            $.ajax({
                url: _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(code_n) + "&i=" + $('#emp_no').val() + "&bq_date_commited=" + $('.asa').val() + "&bq_count=" + bq_count + "&bq_arr_index=" + $('.bq_c_val').val(),
                type: "GET",
                success: function(response) {
                    $("#offense_no").val(response);
                    console.log('tangina ano ba to!!!!!')
                    // if (response == 4) {
                    //     $('#da_type').val(4)
                    // } else {
                    //     $('#da_type').val(3)

                    // }

                }
            });
        } else {
            // if (parseInt($('.not_for12').val()) == 2) {
            //     count--;
            //     $('.not_for12').val(1)
            //     $('.c_val').val(count)
            //     console.log('Count:', count);
            // }
            // if (parseInt($('.not_foraq').val()) == 2) {
            //     aq_count--;
            //     $('.not_foraq').val(1)
            //     $('.aq_c_val').val(aq_count)
            // }
            if (parseInt($('.not_forbq').val()) == 2) {
                bq_count--;
                $('.not_forbq').val(1)
                $('.bq_c_val').val(bq_count)
            }
            // if (fora12 > 0) {
            //     fora12 = parseInt($('.for12').val()) - 1;
            // } else if (fora12 < 0) {
            //     fora12 = 0;
            // }
        }
        console.log('bq_Count:', bq_count);
        // console.log('this is for a 12 val ' + fora12)
    })

    function get_code(str) {
        // $encodedBadge = urlencode($badge);
        // var enc_str = encodeURIComponent(str);
        // console.log(enc_str)

        if (str.length == 0) {

            $("#violation").val("");
            $("#offense_no").val("");
            return;
        } else {
            // if (str == 'AQ-#2') {
            //     $('.is_qa').removeClass('d-none')
            //     $('.not_qa').addClass('d-none')
            //     $('.is_qa').attr('disabled', false)
            //     $('.not_qa').attr('disabled', true)
            // } else {
            //     $('.not_qa').removeClass('d-none')
            //     $('.is_qa').addClass('d-none')
            //     $('.not_qa').attr('disabled', false)
            //     $('.is_qa').attr('disabled', true)

            // }
            $.ajax({
                url: _base_url_ + "get_code_no.php?q=" + encodeURIComponent(str),
                type: "GET",
                success: function(response) {
                    $("#violation").val(response);

                }
            });


            $.ajax({
                url: _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(str) + "&i=" + $('#emp_no').val(),
                type: "GET",
                success: function(response) {
                    $("#offense_no").val(response);
                    $("#date_com_id").val("");
                    console.log('tangina ano ba to!!!!!')
                    // if (response == 4) {
                    //     $('#da_type').val(4)
                    // } else {
                    //     $('#da_type').val(3)

                    // }

                }
            });


        }
    }
    // $('#emp_no').on('change', function() {
    //     if ($('#emp_no').val() != '') {
    //         item.find('.code_no').removeAttr('disabled')
    //     } else {
    //         item.find('.code_no').attr('disabled', true)
    //     }
    // })
    // $('#emp_no').on('change', function() {
    //     $('.code_no').val() == "";
    // });
    $('.remove-option').click(function() {
        $(this).closest('.item').remove()
    })
    // Add Option button click event

    $('#add_option').click(function() {
        var item = $($('#option-clone').html()).clone();

        // Append the modified item to the option list
        $('#option-list').append(item);
        item.find('.select2').select2({
            width: '100%'
        })
        // Attach event handlers to the cloned item
        var trig1 = parseInt(item.find('.for12').val());
        var aq_trig1 = parseInt(item.find('.foraq').val());
        var bq_trig1 = parseInt(item.find('.forbq').val());

        item.find('.remove-option').click(function() {
            $(this).closest('.item').remove();
            if (item.find('.code_no').val() == 'A-#12' && trig1 == 2) {
                count--;
                item.find('.clone_c_val').val(count)
                console.log('this is clone_val when x is clicked ' + item.find('.clone_c_val').val())

            }
            if (item.find('.code_no').val() == 'AQ-#2' && aq_trig1 == 2) {
                aq_count--;
                item.find('.aq_clone_c_val').val(aq_count)
                console.log('this is aq_clone_val when x is clicked ' + item.find('.aq_clone_c_val').val())

            }
            if (item.find('.code_no').val() == 'BQ-#3' && bq_trig1 == 2) {
                bq_count--;
                item.find('.bq_clone_c_val').val(bq_count)
                console.log('this is bq_clone_val when x is clicked ' + item.find('.bq_clone_c_val').val())

            }
        });
        item.find('.datepicker').each(function() {
            $(this).datepick({
                multiSelect: 999,
                multiSeparator: ' + ',
                dateFormat: 'yyyy-mm-dd' // Adjust the format as needed
                // minDate: new Date()
            });
        });
        item.find('.code_no').on('change', function() {
            var selectedValue = $(this).val();
            console.log('sel val: ' + selectedValue)
            // Call the get_code function to update the corresponding cloned "violation" input
            get_codee(selectedValue, item.find('#violation'), item.find('#offense_no'), item.find('.asa'));
        });
        item.find('.asa').on("blur", function() {
            var date_commited = item.find('.asa').val();
            var code_n = item.find('.code_no').val();
            // console.log('this is code num clone' + code_n)
            console.log('this is date commited clone ' + date_commited)

            if (code_n == 'A-#12') {
                if (trig1 == 0) {
                    item.find('.for12').val(1);
                    trig1 = parseInt(item.find('.for12').val());
                }
                if (trig1 == 1) {
                    count++;
                    item.find('.clone_c_val').val(count)
                }
                trig1 = 2;
                console.log(count)
                $.ajax({
                    url: _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(code_n) + "&i=" + $('#emp_no').val() + "&date_commited=" + date_commited + "&count=" + count + "&arr_index=" + item.find('.clone_c_val').val(),
                    type: "GET",
                    success: function(response) {
                        item.find("#offense_no").val(response);

                        // console.log('tangina ano ba clone!!!!!')
                    }
                });
            } else {
                if (trig1 == 2) {
                    count--;
                    item.find('.for12').val(0);
                    trig1 = parseInt(item.find('.for12').val());
                    item.find('.clone_c_val').val(count)
                }
                // if (aq_trig1 == 2) {
                //     aq_count--;
                //     item.find('.foraq').val(0);
                //     aq_trig1 = parseInt(item.find('.foraq').val());
                //     item.find('.aq_clone_c_val').val(aq_count)
                // }
                // if (bq_trig1 == 2) {
                //     bq_count--;
                //     item.find('.forbq').val(0);
                //     bq_trig1 = parseInt(item.find('.forbq').val());
                //     item.find('.bq_clone_c_val').val(bq_count)
                // }
                console.log('count: ' + count + 'aq_count: ' + aq_count + 'bq_count: ' + bq_count)
            }
            if (code_n == 'AQ-#2') {
                if (aq_trig1 == 0) {
                    item.find('.foraq').val(1);
                    aq_trig1 = parseInt(item.find('.foraq').val());
                }
                if (aq_trig1 == 1) {
                    aq_count++;
                    item.find('.aq_clone_c_val').val(aq_count)
                }
                aq_trig1 = 2;
                console.log(aq_count)
                $.ajax({
                    url: _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(code_n) + "&i=" + $('#emp_no').val() + "&aq_date_commited=" + date_commited + "&aq_count=" + aq_count + "&aq_arr_index=" + item.find('.aq_clone_c_val').val(),
                    type: "GET",
                    success: function(response) {
                        item.find("#offense_no").val(response);

                        // console.log('tangina ano ba clone!!!!!')
                    }
                });
            } else {
                // if (trig1 == 2) {
                //     count--;
                //     item.find('.for12').val(0);
                //     trig1 = parseInt(item.find('.for12').val());
                //     item.find('.clone_c_val').val(count)
                // }
                if (aq_trig1 == 2) {
                    aq_count--;
                    item.find('.foraq').val(0);
                    aq_trig1 = parseInt(item.find('.foraq').val());
                    item.find('.aq_clone_c_val').val(aq_count)
                }
                // if (bq_trig1 == 2) {
                //     bq_count--;
                //     item.find('.forbq').val(0);
                //     bq_trig1 = parseInt(item.find('.forbq').val());
                //     item.find('.bq_clone_c_val').val(bq_count)
                // }
                console.log('count: ' + count + 'aq_count: ' + aq_count + 'bq_count: ' + bq_count)
            }
            if (code_n == 'BQ-#3') {
                if (bq_trig1 == 0) {
                    item.find('.forbq').val(1);
                    bq_trig1 = parseInt(item.find('.forbq').val());
                }
                if (bq_trig1 == 1) {
                    bq_count++;
                    item.find('.bq_clone_c_val').val(bq_count)
                }
                bq_trig1 = 2;
                console.log(bq_count)
                $.ajax({
                    url: _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(code_n) + "&i=" + $('#emp_no').val() + "&bq_date_commited=" + date_commited + "&bq_count=" + bq_count + "&bq_arr_index=" + item.find('.bq_clone_c_val').val(),
                    type: "GET",
                    success: function(response) {
                        item.find("#offense_no").val(response);

                        // console.log('tangina ano ba clone!!!!!')
                    }
                });
            } else {
                // if (trig1 == 2) {
                //     count--;
                //     item.find('.for12').val(0);
                //     trig1 = parseInt(item.find('.for12').val());
                //     item.find('.clone_c_val').val(count)
                // }
                // if (aq_trig1 == 2) {
                //     aq_count--;
                //     item.find('.foraq').val(0);
                //     aq_trig1 = parseInt(item.find('.foraq').val());
                //     item.find('.aq_clone_c_val').val(aq_count)
                // }
                if (bq_trig1 == 2) {
                    bq_count--;
                    item.find('.forbq').val(0);
                    bq_trig1 = parseInt(item.find('.forbq').val());
                    item.find('.bq_clone_c_val').val(bq_count)
                }
                console.log('count: ' + count + 'aq_count: ' + aq_count + 'bq_count: ' + bq_count)
            }

        })
    });

    function get_codee(str, violation, offense, asa) {
        if (str.length == 0) {
            violation.val("");
            offense.val("");
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    violation.val(this.responseText);
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_code_no.php?q=" + encodeURIComponent(str), true);
            xmlhttp.send();


            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    offense.val(this.responseText);
                    asa.val('');
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(str) + "&i=" + $('#emp_no').val(), true);
            xmlhttp.send();

        }
    }




    $(function() {
        $('.select2').select2({
            width: '100%'
        })
    })
    var messageType = 1;

    // $(window).on("beforeunload", function(event) {
    //     // Show a warning message
    //     if (messageType == 1) {
    //         return "Are you sure you want to leave? Your changes may not be saved.";
    //     }

    // });
    $('.view_codes').click(function() {
        // if ($('#hr_received').val() !== '' && $('#ads_date').val() !== '') {
        uni_modal("Violations with code number", "incidentreport/irCodenumber/code_violation.php", 'large');
        // } else {
        //     $('#sub').click()
        // }
    })

    $('#ir-form').submit(function(e) {
        e.preventDefault();
        messageType = 2;
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert err-msg")
        el.hide()
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=save_ir_request",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: err => {
                console.error(err)
                el.addClass('alert-danger').text("An error occured");
                _this.prepend(el)
                el.show('.modal')
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    // location.reload();
                    location.replace(_base_url_ + 'admin/?page=incidentreport/pendingIRDA')
                } else if (resp.status == 'failed' && !!resp.msg) {
                    el.addClass('alert-danger').text(resp.msg);
                    _this.prepend(el)
                    el.show('.modal')
                } else {
                    el.text("An error occured");
                    console.error(resp)
                }
                $("html, body").scrollTop(0);
                end_loader()

            }
        })
    })

    // document.getElementById("incidentreport/createNewIRDA").addEventListener("keydown", function(event) {
    //     // Check if the Enter key is pressed (key code 13)
    //     if (event.key === "Enter") {
    //         // Prevent the default form submission behavior
    //         event.preventDefault();
    //     }
    // });
</script>