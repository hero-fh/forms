<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `ir_requests` where md5(id) = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
$control_no = $conn->query("SELECT * FROM ir_requests")->num_rows;
$appr_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = $sv_name")->fetch_array()[0];
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
            <input type="hidden" name="id" value="<?php echo isset($id)  ? $id : '' ?>">
            <input type="hidden" name="requestor_id" value="<?php echo $_settings->userdata('EMPLOYID') ?>">
            <h4 class="text-center"><strong>I. Incident Report</strong></h4>
            <div class="container-fluid">
                <div class=" justify-content-end row">
                    <label class=" col-form-label text-info">IR No.</label>
                    <div class="col-sm-3">
                        <input type="text" readonly class="form-control rounded-0 text-inline" id="ir_no" name="ir_no" value="<?php echo isset($ir_no) ? $ir_no :  '' ?>">
                    </div>
                </div>
                <div class=" justify-content-end row">
                    <label class=" col-form-label text-info">Date</label>
                    <div class="col-sm-3">
                        <input type="date" readonly class="form-control  rounded-0" class="form-control  rounded-0" value="<?php echo isset($date_created)  ? date('Y-m-d', strtotime($date_created)) : '' ?>">

                    </div>
                </div>
                <h5>Details of person involved:</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Employee no</label>
                        <input readonly type="number" name="emp_no" id="emp_no" class="form-control  rounded-0" onkeyup="showHint(this.value)" value="<?php echo isset($emp_no)  ? $emp_no : '' ?>">
                        <!-- <select name="emp_no" onchange="showHint(this.value)" id="emp_no" class="form-control select2" required>
                            <option value="" disabled selected></option>
                            <?php
                            $application = $conn->query("SELECT * FROM `employee_masterlist` where EMPLOYID !=0");
                            while ($row = $application->fetch_assoc()) :
                            ?>
                                <option value="<?= $row['EMPLOYID'] ?>"><?= $row['EMPLOYID'] . " - " . $row['EMPNAME'] ?></option>
                            <?php endwhile; ?>
                        </select> -->
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
                                <input type="radio" value="1" class="check not_" id="administrative" <?php echo isset($quality_violation) && $quality_violation == 1 ? 'checked' : '' ?> name="quality_violation" required>
                                <label for="administrative">Administrative</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group clearfix">
                            <div class="icheck-primary btn-lg d-inline">
                                <input type="radio" value="2" class="check not_ ehe" id="quality" <?php echo isset($quality_violation) && $quality_violation == 2 ? 'checked' : '' ?> name="quality_violation" required>
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

                    <!-- Table to display the added inputs -->
                    <table class=" table-striped table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="50%">
                            <col width="15%">
                            <col width="15%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center py-1 px-2">Code no.</th>
                                <th class="text-center py-1 px-2">Violation/Nature of offenses</th>
                                <th class="text-center py-1 px-2">D.A</th>
                                <th class="text-center py-1 px-2">Date commited</th>
                                <th class="text-center py-1 px-2">No. of offense</th>
                                <?php if ($ir_status == 3 && $sv_status == 1 && $hr_status == 1) { ?>
                                    <th class="text-center py-1 px-2">Action</th>
                                <?php } ?>
                                <!-- <th class="text-center py-1 px-2">Disposition</th>
                                <th class="text-center py-1 px-2">Schedule of suspension</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $irs = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '{$ir_no}'  ");
                            while ($rows = $irs->fetch_assoc()) :
                            ?>
                                <tr>
                                    <td>
                                        <!-- <input autocomplete="off" onkeyup="get_code(this.value)" type="text" class="form-control" id="code_no" name="code_no[]"> -->
                                        <select name="code_no[]" onchange="get_code(this.value,<?php echo $rows['id'] ?>)" data-id="<?php echo $rows['id'] ?>" class="custom-select code_no select2" required>
                                            <!-- <option value="" disabled selected>----</option> -->
                                            <?php
                                            $application = $conn->query("SELECT * FROM `ir_code_no` where status = 1  ");
                                            while ($row = $application->fetch_assoc()) :
                                            ?>
                                                <option value="<?= $row['code_number'] ?>" <?php echo isset($rows['code_no']) && $rows['code_no'] == $row['code_number'] ? 'selected' : '' ?>><?= $row['code_number'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </td>
                                    <td><input readonly autocomplete="off" type="text" class="form-control" id="violation<?php echo $rows['id'] ?>" name="violation[]" value="<?php echo isset($rows['violation'])  ? $rows['violation'] : '' ?>"></td>
                                    <td>
                                        <select name="da_type[]" required id="da_type" class="custom-select not_">
                                            <option value="" disabled selected>--Select D.A--</option>
                                            <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
                                            <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
                                            <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
                                            <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
                                            <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
                                            <!-- <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 1 ? 'selected' : '' ?> value="1">Type A</option>
                                        <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 2 ? 'selected' : '' ?> value="2">Type B</option>
                                        <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 3 ? 'selected' : '' ?> value="3">Type C</option>
                                        <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 4 ? 'selected' : '' ?> value="4">Type D</option>
                                        <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 5 ? 'selected' : '' ?> value="5">Type E</option> -->
                                        </select>
                                    </td>
                                    <td><input autocomplete="off" type="date" class="form-control" id="date_commited" required name="date_commited[]" value="<?php echo isset($rows['date_commited'])  ? date('Y-m-d', strtotime($rows['date_commited'])) : '' ?>"></td>
                                    <td>
                                        <input autocomplete="off" type="text" class="form-control" id="offense_no<?php echo $rows['id'] ?>" required name="offense_no[]" readonly value="<?php echo isset($rows['offense_no'])  ? $rows['offense_no'] : '' ?>">
                                        <!-- <select name="offense_no[]" id="offense_no" class="custom-select not_">
                                        <option value="" disabled selected>--No. of offense--</option>
                                        <option <?php echo isset($offense_no) && $offense_no == 1 ? 'selected' : '' ?> value="1">Verbal Warning </option>
                                        <option <?php echo isset($offense_no) && $offense_no == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
                                        <option <?php echo isset($offense_no) && $offense_no == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
                                        <option <?php echo isset($offense_no) && $offense_no == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
                                        <option <?php echo isset($offense_no) && $offense_no == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
                                    </select> -->
                                    </td>
                                    <?php if ($ir_status == 3 && $sv_status == 1 && $hr_status == 1) { ?>
                                        <td>
                                            <a class="btn btn-sm btn-block dngr" href="javascript:void(0)" data-id="<?php echo $rows['id'] ?>"><span class="fas fa-trash text-danger"></span></a>
                                        </td>
                                    <?php } ?>
                                    <!-- <td>
                                    <select name="disposition[]" class="custom-select not_">
                                        <option value="" disabled selected>--Disposition--</option>
                                        <option <?php echo isset($disposition) && $disposition == 1 ? 'selected' : '' ?> value="1">VALID</option>
                                        <option <?php echo isset($disposition) && $disposition == 2 ? 'selected' : '' ?> value="2">INVALID</option>
                                    </select>
                                </td> -->
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </fieldset>


                <br>
                <fieldset>
                    <?php if ($ir_status == 3 && $sv_status == 1 && $hr_status == 1) { ?>
                        <div class="my-2 text-center">
                            <button class="btn btn-primary btn-block-sm not_ " id="add_option" type="button"><i class="fa fa-plus"></i> Add another violation</button>
                        </div>
                    <?php } ?>
                    <div id="option-list">
                    </div>

                </fieldset>
                <noscript id="option-clone">
                    <div class="item">
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
                                    <col width="50%">
                                    <col width="15%">
                                    <col width="15%">
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
                                            <!-- <input autocomplete="off" type="text" class="form-control" id="code_no" name="code_no[]"> -->
                                            <select name="code_no[]" id="code_no" class="custom-select code_no select2 increase_id" required>
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
                                                <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
                                                <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
                                                <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
                                                <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
                                                <option <?php echo isset($rows['da_type']) && $rows['da_type'] == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
                                            </select>
                                        </td>
                                        <td><input autocomplete="off" type="date" class="form-control" id="date_commited" required name="date_commited[]"></td>
                                        <td>
                                            <input autocomplete="off" type="text" class="form-control" id="offense_no" required name="offense_no[]" readonly value="">
                                            <!-- <select required name="offense_no[]" id="offense_no" class="custom-select ">
                                                    <option value="" disabled selected>--No. of offense--</option>
                                                    <option <?php echo isset($offense_no) && $offense_no == 1 ? 'selected' : '' ?> value="1">Verbal Warning </option>
                                                    <option <?php echo isset($offense_no) && $offense_no == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
                                                    <option <?php echo isset($offense_no) && $offense_no == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
                                                    <option <?php echo isset($offense_no) && $offense_no == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
                                                    <option <?php echo isset($offense_no) && $offense_no == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
                                                </select> -->
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


                <!--    <div class="row justify-content-between">
                    <div class="form-group  col-5">
                        <label class="control-label  text-info">For approval : </label>
                        <input readonly type="hidden" class="form-control  rounded-0" name="dh_name" id="dh_name" class="form-control  rounded-0" value="<?php echo isset($dh_name)  ? $dh_name : '' ?>">
                        <input readonly type="text" class="form-control  rounded-0" id="dh_name1" class="form-control  rounded-0" value="<?php echo isset($dh_name)  ? $dh_name : '' ?>">

                       <select name="dh_name" id="dh_name" class="form-control select2" required>
                            <option value="" disabled <?= !isset($dh_name) ? "selected" : "" ?>></option>
                            <?php
                            $application = $conn->query("SELECT * FROM `employee_masterlist` ORDER BY EMPNAME");
                            while ($row = $application->fetch_assoc()) :
                            ?>
                                <option value="<?= $row['EMPLOYID'] ?>" <?php echo isset($dh_name) && $dh_name == $row['EMPLOYID'] ? 'selected' : '' ?>><?= $row['EMPNAME'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <i class="text-info" style="display:block; text-align: center;">Approver 2</i>
                    </div>
                <div class="col-4">
                    <label class="control-label  text-info">Date </label>
                    <input readonly type="date" class="form-control  rounded-0" name="dh_sign_date" class="form-control  rounded-0" value="<?php echo isset($dh_sign_date)  ? date('Y/m/d', strtotime($dh_sign_date)) : '' ?>">
                </div>-->
            </div>


            <div class=" py-1 text-center">
                <?php if ($ir_status == 3 && $sv_status == 1 && $hr_status == 1) { ?>
                    <button class="btn btn-flat btn-primary" type="submit" form="ir-form">SUBMIT</button>
                <?php } ?>
                <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=incidentreport/disapprovedIRDA' ?>">Cancel</a>
            </div>
    </div>

    </form>
</div>
</div>
<script>
    $('input[name="quality_violation"]').on('change', function() {
        if ($('#quality').is(':checked')) {
            $('#reference').attr('disabled', false)
        } else {
            $('#reference').attr('disabled', true)
            $('#reference').val('')
        }
    })
    $('.dngr').click(function() {
        _conf("Are you sure to remove this incident report?", "delete_po", [$(this).attr('data-id')])
    })

    function delete_po($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=ir_delete",
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


    function showHint(str) {
        if (str.length == 0) {
            document.getElementById("emp_name").value = "";
            document.getElementById("department").value = "";
            document.getElementById("shift").value = "";
            document.getElementById("position").value = "";
            document.getElementById("station").value = "";
            document.getElementById("productline").value = "";
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
            //         var text = this.responseText;
            //         var myArray = text.split("/");

            //         document.getElementById("sv_name").value = myArray[0];
            //         document.getElementById("sv_name1").value = myArray[1];
            //         document.getElementById("dh_name").value = myArray[2];
            //         document.getElementById("dh_name1").value = myArray[3];
            //         // document.getElementById("requestor_department").value = this.responseText;
            //     }
            // };
            // xmlhttp.open("GET", _base_url_ + "get_appr.php?q=" + str, true);
            // xmlhttp.send();
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

    function get_code(str, id) {
        // $encodedBadge = urlencode($badge);
        console.log('idgashdsad' + id)
        if (str.length == 0) {
            $("#violation").val("");
            $("#offense_no").val("");
            return;
        } else {
            $.ajax({
                url: _base_url_ + "get_code_no.php?q=" + encodeURIComponent(str),
                type: "GET",
                success: function(response) {
                    $("#violation" + id).val(response);

                }
            });

            $.ajax({
                url: _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(str) + "&i=" + $('#emp_no').val(),
                type: "GET",
                success: function(response) {
                    $("#offense_no" + id).val(response);
                    // if (response == 4) {
                    //     $('#da_type').val(4)
                    // } else {
                    //     $('#da_type').val(3)

                    // }

                }
            });
        }
    }

    $('.remove-option').click(function() {
        $(this).closest('.item').remove()
    })
    // Add Option button click event

    $('#add_option').click(function() {
        var item = $($('#option-clone').html()).clone();

        // Append the modified item to the option list
        $('#option-list').prepend(item);

        // Attach event handlers to the cloned item
        item.find('.select2').select2({})
        item.find('.remove-option').click(function() {
            $(this).closest('.item').remove();
        });
        item.find('.code_no').on('change', function() {
            var selectedValue = $(this).val();
            console.log('sel val: ' + selectedValue)
            // Call the get_code function to update the corresponding cloned "violation" input
            get_codee(selectedValue, item.find('#violation'), item.find('#offense_no'));
        });

    });

    function get_codee(str, violation, offense) {
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
                }
            };
            xmlhttp.open("GET", _base_url_ + "get_offense_no.php?q=" + encodeURIComponent(str) + "&i=" + $('#emp_no').val(), true);
            xmlhttp.send();

        }
    }




    $(function() {
        $('.select2').select2({

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
            url: _base_url_ + "classes/IR_DA_Master.php?f=repass_mngr_ir",
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