<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `ir_requests` where md5(id) = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
$noted_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = $sv_name")->fetch_array()[0];
// $appr_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = $sv_name")->fetch_array()[0];
$e_id = $_settings->userdata('EMPLOYID');
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

    td {
        vertical-align: middle;
    }
</style>
<noscript>
    <style>
        .page-break {
            page-break-before: always;
        }

        select.custom-select:disabled {
            background-color: #fff;
        }

        input.form-control:read-only {
            background-color: #fff;
        }

        textarea.form-control:read-only {
            background-color: #fff;
        }
    </style>
</noscript>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title card-primary">CREATE NEW INCIDENT REPORT</h4>
    </div>
    <div class="card-body">
        <form action="" id="ir-form">
            <input readonly type="hidden" name="id" value="<?php echo isset($id)  ? $id : '' ?>">
            <input readonly type="hidden" name="requestor_id" value="<?php echo $_settings->userdata('EMPLOYID') ?>">
            <div class="container-fluid">
                <div class="printable">
                    <div class="row" style="margin-left: 1%;">
                        <p class="MsoNormal" align="left" style="margin: 0cm; text-indent: 0cm; line-height: 107%; background: rgb(153, 51, 0);"><b><span style="font-size:24.0pt;line-height:107%;color:white">T
                                    E L F O R D</span></b>
                            <o:p></o:p>
                        </p>
                    </div>
                    <p class="MsoNormal" align="left"><b><span style="margin-left: 1%;font-size:11.5pt;line-height:107%">TELFORD
                                SVC. PHILS., INC.</span>
                        </b>
                        <o:p></o:p>
                    </p>
                    <br>
                    <h4 class="text-center"><strong>I. Incident report form</strong></h4>

                    <div class="form-group justify-content-end row">
                        <label class=" col-form-label text-info">IR No.</label>
                        <div class="col-sm-3">
                            <input readonly type="text" class="form-control rounded-0 text-inline" id="ir_no" name="ir_no" value="<?php echo isset($ir_no) ? $ir_no :  '' ?>">
                        </div>
                    </div>
                    <div class="form-group justify-content-end row">
                        <label class=" col-form-label text-info">Date</label>
                        <div class="col-sm-3">
                            <input readonly type="text" class="form-control  rounded-0" class="form-control  rounded-0" value="<?php echo isset($date_created)  ? date('m/d/Y', strtotime($date_created)) : date('Y-m-d') ?>">

                        </div>
                    </div>
                    <h5>Details of person involved:</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-info">Employee no</label>
                            <input required type="number" name="emp_no" id="emp_no" class="form-control  rounded-0" readonly value="<?php echo isset($emp_no) ? $emp_no : '' ?>">
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

                    </br>
                    <!--  <h5>Statement of facts/incident details:</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-info">What</label>
                            <textarea readonly type="text" name="what" class="form-control  rounded-0"><?php echo isset($what)  ? $what : '' ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">When</label>
                            <input readonly required type="date" name="when" class="form-control  rounded-0" value="<?php echo isset($when)  ? $when : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">Where</label>
                            <input readonly required type="text" name="where" class="form-control  rounded-0" value="<?php echo isset($where)  ? $where : '' ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="control-label text-info">How/Other information</label>
                            <textarea readonly type="text" name="how" class="form-control  rounded-0"><?php echo isset($how)  ? $how : '' ?></textarea>
                        </div>
                    </div>
                    <br>
                    <h4 class="text-center"><strong>II. Letter of explanation</strong></h4>

                    <p align="justify">
                        Please explain in writing within 5 calendar days upon receipt hereof,why no disciplinary action should be taken against you for the
                        Violations committed stated on the Incident report. No explanation received within the period alloted to you may be construed
                        as an admission of offense levelled on you and waived your right to be heard and the corresponding penalty should be given
                        to you.
                    </p>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-info">Why? :</label>
                            <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'required' : 'readonly' ?> <?php echo isset($why)  ? 'readonly' : '' ?> type="text" name="why" class="form-control  rounded-0"><?php echo isset($why)  ? $why : '' ?></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center">
                            <input <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'required' : 'readonly' ?> <?php echo isset($sign)  ? 'readonly' : '' ?>type="text" class="form-control  rounded-0" placeholder="Place your signature here." style="border: 0; border-bottom: 1px solid black; text-align:center" name="sign" class="form-control  rounded-0" value="<?php echo isset($sign)  ? $sign : '' ?>">
                            <label class="control-label  text-info">Employee's signature over printed name / date</label>
                        </div>
                    </div> -->
                    <!-- <hr class="dont_">
                    <div class="page-break"></div>
                    <br>
                    <div class="did">

                        <div class="row" style="margin-left: 1%;">
                            <p class="MsoNormal" align="left" style="margin: 0cm; text-indent: 0cm; line-height: 107%; background: rgb(153, 51, 0);"><b><span style="font-size:24.0pt;line-height:107%;color:white">T
                                        E L F O R D</span></b>
                                <o:p></o:p>
                            </p>
                        </div>
                        <p class="MsoNormal" align="left"><b><span style="margin-left: 1%;font-size:11.5pt;line-height:107%">TELFORD
                                    SVC. PHILS., INC.</span>
                            </b>
                            <o:p></o:p>
                        </p>
                        <br>
                    </div> -->
                    <h5>Please check appropriate box and indicate the reference no. for quality violation:</h5><br>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary btn-lg d-inline">
                                    <input <?php echo isset($quality_violation) && $quality_violation != 0 ? 'onclick="return false"' : '' ?> <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'onclick="return false"' : 'required' ?> type="radio" <?php echo isset($quality_violation) && $quality_violation == 1 ? 'checked' : '' ?> value="1" class="form-control" id="administrative" name="quality_violation" />
                                    <label for="administrative">Administrative</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary btn-lg d-inline">
                                    <input <?php echo isset($quality_violation) && $quality_violation != 0 ? 'onclick="return false"' : '' ?> <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'onclick="return false"' : 'required' ?> type="radio" <?php echo isset($quality_violation) && $quality_violation == 2 ? 'checked' : '' ?> value="2" class="form-control" id="quality" name="quality_violation" />
                                    <label for="quality">Quality</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="control-label text-info">Reference document(IPNR/QDN/EQS):</label>
                            <input readonly type="text" name="reference" class="form-control  rounded-0" value="<?php echo isset($reference)  ? $reference : '' ?>">
                        </div>
                    </div>
                    <br>
                    <h5>Statement of facts/incident details:</h5>
                    <div class="row" style="pointer-events: none;">
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
                            <textarea type="text" name="how" class="form-control  rounded-0"><?php echo isset($how)  ? $how : '' ?></textarea>
                        </div>
                    </div>
                    <br>
                    <fieldset>
                        <!-- Table to display the added inputs -->
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center py-1 px-2">Code no.</th>
                                    <th class="text-center py-1 px-2">Violation/Nature of offenses</th>
                                    <th class="text-center py-1 px-2">D.A</th>
                                    <th class="text-center py-1 px-2">Date commited</th>
                                    <th class="text-center py-1 px-2">No. of offense</th>
                                    <!-- <th class="text-center py-1 px-2">Disposition</th>
                                    <th class="text-center py-1 px-2">Schedule of suspension</th>
                                    <?php if ($_settings->userdata('EMPLOYID') != $emp_no) { ?>
                                        <?php } ?> -->
                                    <th class="text-center py-1 px-2 dont_">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '$ir_no'");
                                while ($row = $options->fetch_assoc()) :
                                ?>
                                    <tr class="text-center">
                                        <td><?php echo $row['code_no'] ?></td>
                                        <td><?php echo $row['violation'] ?></td>
                                        <td class="text-center">
                                            <?php if ($row['da_type'] == 1) : ?>
                                                <span class="badge badge-success rounded-pill">Verbal Warning</span>
                                            <?php elseif ($row['da_type'] == 2) : ?>
                                                <span class="badge badge-primary rounded-pill">Written Warning</span>
                                            <?php elseif ($row['da_type'] == 3) : ?>
                                                <span class="badge badge-secondary rounded-pill">3 Days Suspension</span>
                                            <?php elseif ($row['da_type'] == 4) : ?>
                                                <span class="badge badge-warning rounded-pill">7 Days Suspension</span>
                                            <?php elseif ($row['da_type'] == 5) : ?>
                                                <span class="badge badge-danger rounded-pill">Dismissal</span>
                                            <?php elseif ($row['da_type'] == 6) : ?>
                                                <span class="badge badge-danger rounded-pill">14 Days Suspension</span>
                                            <?php endif; ?>
                                            <!-- <?php if ($row['da_type'] == 1) : ?>
                                                <span class="badge badge-success rounded-pill">Type A</span>
                                            <?php elseif ($row['da_type'] == 2) : ?>
                                                <span class="badge badge-primary rounded-pill">Type B</span>
                                            <?php elseif ($row['da_type'] == 3) : ?>
                                                <span class="badge badge-secondary rounded-pill">Type C</span>
                                            <?php elseif ($row['da_type'] == 4) : ?>
                                                <span class="badge badge-warning rounded-pill">Type D</span>
                                            <?php elseif ($row['da_type'] == 5) : ?>
                                                <span class="badge badge-danger rounded-pill">Type E</span>
                                            <?php endif; ?> -->
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $dateString = $row['date_commited'];
                                            $dateArray = explode(' + ', $dateString);

                                            foreach ($dateArray as $key => $date) {
                                                $trimmedDate = trim($date);
                                                $timestamp = strtotime($trimmedDate);

                                                if ($timestamp === false) {
                                                    echo "Error parsing date: $trimmedDate <br>";
                                                } else {
                                                    $dateTime = new DateTime();
                                                    $dateTime->setTimestamp($timestamp);

                                                    $dayOfWeek = $dateTime->format('D');
                                                    echo "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)<br>";
                                                }
                                            }

                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['offense_no'] == 1) : ?>
                                                <span class="badge badge-success rounded-pill"><?php echo $row['offense_no'] ?></span>
                                            <?php elseif ($row['offense_no'] == 2) : ?>
                                                <span class="badge badge-primary rounded-pill"><?php echo $row['offense_no'] ?></span>
                                            <?php else : ?>
                                                <span class="badge badge-danger rounded-pill"><?php echo $row['offense_no'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <!--  <td class="text-center">
                                           <?php if ($row['disposition'] == 1) : ?>
                                                <span class="badge badge-success rounded-pill">VALID</span>
                                            <?php elseif ($row['disposition'] == 2) : ?>
                                                <span class="badge badge-warning rounded-pill">INVALID</span>
                                            <?php endif; ?>
                                        </td> -->
                                        <!-- <?php if ($row['offense_no'] == 3 || $row['offense_no'] == 4) : ?>
                                            <td class="text-center">
                                                <?php echo isset($row['date_of_suspension']) && isset($row['date_of_suspension_end']) ? date("m-d-Y", strtotime($row['date_of_suspension'])) . ' - ' . date("m-d-Y", strtotime($row['date_of_suspension_end'])) : 'mm/dd/yyyy - mm/dd/yyyy' ?>
                                            </td>
                                        <?php else : ?>
                                            <td>
                                                <span class="badge badge-primary rounded-pill">No schedule</span>
                                            </td>
                                        <?php endif; ?> -->
                                        <?php if ($_settings->userdata('EMPLOYID') != $emp_no) { ?>
                                            <td class="dont_">
                                                <a class="btn btn-sm btn-block update_" <?php echo $row['da_type'] >= 3 ? '' : 'style="pointer-events:none"' ?> href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fas <?php echo $row['da_type'] >= 3 ? 'fa-pencil-alt' : 'fa-ban' ?>"></span></a>
                                            </td>
                                        <?php } ?>


                                    </tr>
                                <?php endwhile; ?>

                                <!-- Table rows will be added dynamically using JavaScript -->
                            </tbody>
                        </table>
                        <fieldset class="dont_">
                            <div id="option-list">
                            </div>
                            <!-- <?php if ($_settings->userdata('EMPLOYID') != $emp_no) { ?>
                                <div class="my-2 text-center">
                                    <button class="btn btn-success btn-block-sm" id="add_option" type="button"><i class="fa fa-plus"></i> Add another Incident</button>
                                </div>
                            <?php } ?> -->

                        </fieldset>
                        <noscript id="option-clone" class="dont_">
                            <div class="item">
                                <div class="list-group" id="option-list">

                                    <div class="row justify-content-end">
                                        <!-- <div class="input-group mb-3"> -->
                                        <!-- <div class="col-md-2"><button type="button" id="xbtn" class="remove-option btn btn-danger btn-block btn-sm"><i class="fas fa-backspace"></i></button></div> -->
                                        <!-- </div> -->
                                        <button tabindex="-1" class=" remove-option col-1 text-reset btn btn-danger mb-2 mr-3" id="xbtn" title="Remove Option1"><i class="fas fa-times"></i></button>

                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center py-1 px-2">Code no.</th>
                                                    <th class="text-center py-1 px-2">Violation/Nature of offenses</th>
                                                    <th class="text-center py-1 px-2">D.A</th>
                                                    <th class="text-center py-1 px-2">Date commited</th>
                                                    <th class="text-center py-1 px-2">No. of offense</th>
                                                    <!-- <th class="text-center py-1 px-2">Disposition</th>
                                                    <th class="text-center py-1 px-2">Schedule of suspension (if applicable)</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>

                                                    <td><input autocomplete="off" type="text" class="form-control" id="code_no" required name="code_no[]"></td>
                                                    <td><input autocomplete="off" type="text" class="form-control" id="violation" required name="violation[]"></td>
                                                    <td>
                                                        <select required name="da_type[]" class="custom-select">
                                                            <option value="" disabled selected>--Type of D.A--</option>
                                                            <option <?php echo isset($da_type) && $da_type == 1 ? 'selected' : '' ?> value="1">Type A</option>
                                                            <option <?php echo isset($da_type) && $da_type == 2 ? 'selected' : '' ?> value="2">Type B</option>
                                                            <option <?php echo isset($da_type) && $da_type == 3 ? 'selected' : '' ?> value="3">Type C</option>
                                                            <option <?php echo isset($da_type) && $da_type == 4 ? 'selected' : '' ?> value="4">Type D</option>
                                                            <option <?php echo isset($da_type) && $da_type == 5 ? 'selected' : '' ?> value="5">Type E</option>
                                                        </select>
                                                    </td>
                                                    <td><input autocomplete="off" type="date" class="form-control" id="date_commited" required name="date_commited[]"></td>
                                                    <td>
                                                        <input autocomplete="off" type="text" class="form-control" id="offense_no" required name="offense_no[]" value="">
                                                        <!-- <select required name="offense_no[]" class="custom-select">
                                                            <option value="" disabled selected>--No. of offense--</option>
                                                            <option <?php echo isset($offense_no) && $offense_no == 1 ? 'selected' : '' ?> value="1">Verbal Warning </option>
                                                            <option <?php echo isset($offense_no) && $offense_no == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
                                                            <option <?php echo isset($offense_no) && $offense_no == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
                                                            <option <?php echo isset($offense_no) && $offense_no == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
                                                            <option <?php echo isset($offense_no) && $offense_no == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
                                                        </select> -->
                                                    </td>
                                                    <!-- <td>
                                                        <select required name="disposition[]" class="custom-select">
                                                            <option value="" disabled selected>--Disposition--</option>
                                                            <option <?php echo isset($disposition) && $disposition == 1 ? 'selected' : '' ?> value="1">VALID</option>
                                                            <option <?php echo isset($disposition) && $disposition == 2 ? 'selected' : '' ?> value="2">INVALID</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        From<input autocomplete="off" type="date" class="form-control" id="date_of_suspension" name="date_of_suspension[]">
                                                        To<input autocomplete="off" type="date" class="form-control" id="date_of_suspension_end" name="date_of_suspension_end[]">
                                                    </td> -->


                                                </tr>

                                            </tbody>
                                        </table>
                                        <!-- <button tabindex="-1" class=" remove-option col-1 text-reset btn btn-danger mb-2" id="xbtn" title="Remove Option1"><i class="fas fa-backspace"></i></button> -->

                                    </div>
                                </div>
                            </div>
                        </noscript>
                        <!-- <div class="form-group row ml-1">
                            <label class="col-form-label text-info">Date of Suspension (if applicable):</label>
                            <div class="col-sm-6">
                                <input <?php echo isset($suspension) && $suspension != '' ? 'readonly' : ''  ?> <?php echo isset($e_id) && $e_id == $emp_no ? 'readonly' : ''  ?> type="date" class="form-control rounded-0 text-inline" id="suspension" name="suspension" value="<?php echo isset($suspension) ? date('Y-m-d', strtotime($suspension)) : '' ?>">
                            </div>
                        </div> -->
                        <!-- <div class="form-group col-md-12">
                                <label class="control-label ">Date of Suspension (if applicable):</label>

                            </div>
                        </div> -->

                    </fieldset>
                    <!-- <br>
                    <h4 class="text-center"><strong>II. Letter of explanation</strong></h4>

                    <p align="justify">
                        Please explain in writing within 5 calendar days upon receipt hereof, why no disciplinary action should be taken against you for the violations committed stated on the Incident report.
                        No explanation received within the period alloted to you may be construed as an admission of offense levelled on you and waived your right to be heard and the corresponding penalty should be given to you.

                    </p>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-info">Why? :</label>
                            <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'required' : 'disabled' ?> <?php echo isset($why1)  ? ' ' : '' ?> type="text" name="why1" class="form-control  rounded-0"><?php echo isset($why1)  ? $why1 : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-info">Why? :</label>
                            <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'required' : 'disabled' ?> <?php echo isset($why2)  ? ' ' : '' ?> type="text" name="why2" class="form-control  rounded-0"><?php echo isset($why2)  ? $why2 : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-info">Why? :</label>
                            <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'required' : 'disabled' ?> <?php echo isset($why3)  ? ' ' : '' ?> type="text" name="why3" class="form-control  rounded-0"><?php echo isset($why3)  ? $why3 : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-info">Why? :</label>
                            <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? '' : 'disabled' ?> <?php echo isset($why4)  ? ' ' : '' ?> type="text" name="why4" class="form-control  rounded-0"><?php echo isset($why4)  ? $why4 : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-info">Why? :</label>
                            <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? '' : 'disabled' ?> <?php echo isset($why5)  ? ' ' : '' ?> type="text" name="why5" class="form-control  rounded-0"><?php echo isset($why5)  ? $why5 : '' ?></textarea>
                        </div>
                    </div> -->
                    <br>




                    <?php
                    if ($is_quality > 0 ||  $is_operator > 0) { ?>
                        <hr>
                        <br>
                        <fieldset class="overflow-auto" style="pointer-events: none;">
                            <!-- Table to display the added inputs -->
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Code No</th>
                                        <th rowspan="2">Violations</th>
                                        <th rowspan="2">D.A</th>
                                        <th rowspan="2">Date commited</th>
                                        <th rowspan="2">No. of offense</th>
                                        <th colspan="2">Disposition</th>
                                        <?php if ($sv_status == 1 && $ir_status == 1) { ?>
                                            <th colspan="2">Action</th>
                                        <?php } ?>
                                        <!-- <th rowspan="2">Explanation</th> -->

                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                    $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '$ir_no'");
                                    while ($row = $options->fetch_assoc()) :
                                    ?>
                                        <!-- <input type="hidden" name="ir_no[]" value="<?php echo $row['ir_no'] ?>"> -->
                                        <input type="hidden" name="code_no[]" value="<?php echo $row['code_no'] ?>">
                                        <tr class="text-center">
                                            <td><?php echo $row['code_no'] ?></td>
                                            <td> <?php echo $row['violation'] ?></td>
                                            <td class="text-center">
                                                <?php if ($row['da_type'] == 1) : ?>
                                                    <span class="badge badge-success rounded-pill">Verbal Warning</span>
                                                <?php elseif ($row['da_type'] == 2) : ?>
                                                    <span class="badge badge-primary rounded-pill">Written Warning</span>
                                                <?php elseif ($row['da_type'] == 3) : ?>
                                                    <span class="badge badge-secondary rounded-pill">3 Days Suspension</span>
                                                <?php elseif ($row['da_type'] == 4) : ?>
                                                    <span class="badge badge-warning rounded-pill">7 Days Suspension</span>
                                                <?php elseif ($row['da_type'] == 5) : ?>
                                                    <span class="badge badge-danger rounded-pill">Dismissal</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $dateString = $row['date_commited'];
                                                $dateArray = explode(' + ', $dateString);

                                                foreach ($dateArray as $key => $date) {
                                                    $trimmedDate = trim($date);
                                                    $timestamp = strtotime($trimmedDate);

                                                    if ($timestamp === false) {
                                                        echo "Error parsing date: $trimmedDate <br>";
                                                    } else {
                                                        $dateTime = new DateTime();
                                                        $dateTime->setTimestamp($timestamp);

                                                        $dayOfWeek = $dateTime->format('D');
                                                        echo "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)<br>";
                                                    }
                                                }

                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($row['offense_no'] == 1) : ?>
                                                    <span class="badge badge-success rounded-pill"><?php echo $row['offense_no'] ?></span>
                                                <?php elseif ($row['offense_no'] == 2) : ?>
                                                    <span class="badge badge-primary rounded-pill"><?php echo $row['offense_no'] ?></span>
                                                <?php else : ?>
                                                    <span class="badge badge-danger rounded-pill"><?php echo $row['offense_no'] ?></span>
                                                <?php endif; ?>
                                            </td>


                                            <td class="text-center">
                                                <div class="icheck-primary btn-lg d-inline">
                                                    <input type="checkbox" required class="con_yes" data-id="<?php echo $row['id'] ?>" id="con_yes<?php echo $row['id'] ?>" value="1" <?php echo isset($row['valid']) && $row['valid'] == 1 ? 'checked' : '' ?> name="valid[]">
                                                    <label for="con_yes<?php echo $row['id'] ?>">Valid</label>
                                                </div>
                                                <!-- <button class="btn btn-flat btn-sm btn-outline-success approve_data" type="button" data-id="<?php echo $id ?>" data-val="1" data-sign="1"> <i class="fas fa-thumbs-up"></i> Valid</button> -->
                                            </td>
                                            <td class="text-center">
                                                <div class="icheck-primary btn-lg d-inline">
                                                    <input type="checkbox" required class="con_no" data-id="<?php echo $row['id'] ?>" id="con_no<?php echo $row['id'] ?>" value="2" <?php echo isset($row['valid']) && $row['valid'] == 2 ? 'checked' : '' ?> name="valid[]">
                                                    <label for="con_no<?php echo $row['id'] ?>">Invalid</label>

                                                </div>
                                                <!-- <button class="btn btn-flat btn-sm btn-outline-danger disapprove_data" type="button" data-id="<?php echo $id ?>" data-val="2" data-sign="1"> <i class="fas fa-thumbs-down"></i> Invalid</button> -->
                                            </td>
                                            <?php if ($sv_status == 1 && $ir_status == 1) { ?>
                                                <td class="dont_">
                                                    <a class="btn btn-sm btn-block update_code" style="pointer-events: all;" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fas fa-pencil-alt"></span></a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <?php if ($sv_status == 1 && $ir_status == 1) { ?>
                                            <tr>
                                                <td colspan="8">
                                                    <a class="btn btn-sm btn-block edt" style="pointer-events: all;" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fas fa-eye text-dark"> View letter of explanation</i></a>
                                                </td>
                                            </tr>
                                            <tr class="<?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'd-none' ?>">
                                                <td class="text-center" colspan="2">
                                                    <label class="control-label">No of days:</label>
                                                    <input type="number" id="days_no" name="days_no[]" required class="form-control text-center" value="<?php echo $row['days_no'] ?>">
                                                </td>
                                                <td class="text-center" colspan="6">
                                                    <label class="control-label">Suspension date: </label>
                                                    <input type="text" id="date_of_suspension" autocomplete="off" name="date_of_suspension[]" class="form-control datepicker text-center" <?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'readonly' ?> value="<?php echo $row['date_of_suspension'] ?>">
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="7">
                                                    <a class="btn btn-sm btn-block edt" style="pointer-events: all;" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fas fa-eye text-dark"> View letter of explanation</i></a>
                                                </td>
                                            </tr>
                                            <tr class="<?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'd-none' ?>">
                                                <td class="text-center" colspan="2">
                                                    <label class="control-label">No of days:</label>
                                                    <input type="number" id="days_no" name="days_no[]" required class="form-control text-center" value="<?php echo $row['days_no'] ?>">
                                                </td>
                                                <td class="text-center" colspan="5">
                                                    <label class="control-label">Suspension date: </label>
                                                    <input type="text" id="date_of_suspension" autocomplete="off" name="date_of_suspension[]" class="form-control datepicker text-center" <?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'readonly' ?> value="<?php echo $row['date_of_suspension'] ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php endwhile; ?>

                                    <!-- Table rows will be added dynamically using JavaScript -->
                                </tbody>
                            </table>
                        </fieldset>
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="control-label text-info">Admin hearing/counseling</label>
                                <textarea required <?php echo isset($sv_name) && $_settings->userdata('EMPLOYID')  == $sv_name ? 'required' : 'readonly' ?> <?php echo isset($assessment) ? 'readonly' : ''  ?> type="text" name="assessment" placeholder="Input details of admin hearing/counseling" class="form-control  rounded-0"><?php echo isset($assessment)  ? $assessment : '' ?></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="control-label text-info">Recommendation</label>
                                <textarea required <?php echo isset($sv_name) && $_settings->userdata('EMPLOYID')  == $sv_name ? 'required' : 'readonly' ?> <?php echo isset($recommendation) ? 'readonly' : ''  ?> type="text" name="recommendation" class="form-control  rounded-0"><?php echo isset($recommendation)  ? $recommendation : '' ?></textarea>
                            </div>
                            <!-- <?php if ($_settings->userdata('EMPPOSITION')  > 2) { ?>
                                <div class="col-md-12">
                                    <label class="control-label text-info">Remarks</label>
                                    <textarea required type="text" name="dh_remarks" class="form-control  rounded-0"><?php echo isset($dh_remarks)  ? $dh_remarks : '' ?></textarea>
                                </div>
                            <?php } ?> -->
                        </div>

                    <?php } ?>
                    <br>
                    <div class="row justify-content-between">
                        <div class="col-5">
                            <label class="control-label  text-info">Reported : </label>
                            <input readonly required type="text" class="form-control  rounded-0" name="requestor_name" class="form-control  rounded-0" value="<?php echo isset($requestor_name)  ? $requestor_name : '' ?>">
                            <i class="text-info" style="display:block; text-align: center;">Name</i>
                        </div>
                        <div class="col-4">
                            <label class="control-label  text-info">Date </label>
                            <input readonly required type="text" class="form-control  rounded-0" class="form-control  rounded-0" value="<?php echo isset($date_created)  ? date('m/d/Y h:i a', strtotime($date_created)) : '' ?>">

                        </div>
                    </div>


                </div>

                <?php
                $appr_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$sv_name}'")->fetch_array()[0];
                $appr_by_dh = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$dm_name}'")->fetch_array();
                ?>

                <div class="row justify-content-between">
                    <?php if ($hr_status != 0) {
                        $valid_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = $hr_name")->fetch_array()[0];
                    } ?>
                    <?php if ($hr_status != 0 && $ir_status != 4) { ?>
                        <div class="col-5">
                            <label class="control-label text-info"><?php echo isset($hr_status) && $hr_status == 2 ? 'Disapproved' : 'Validated' ?> by :</label>
                            <input readonly type="text" class="form-control" value="<?php echo isset($valid_by)  ? $valid_by : '' ?>">
                            <i class="text-info" style="display:block; text-align: center;">HR Personnel/Auditor</i>
                        </div>
                        <div class="col-4">
                            <label class="control-label text-info">Date:</label>
                            <input readonly type="text" class="form-control rounded-0" class="form-control form-control-sm rounded-0" value="<?php echo isset($hr_sign_date)  ? date('m/d/Y h:i a', strtotime($hr_sign_date)) : '' ?>">
                        </div>
                    <?php } elseif ($ir_status == 4) { ?>
                        <div class="col-5">
                            <label class="control-label text-info">Cancelled by :</label>
                            <input readonly type="text" class="form-control" value="<?php echo isset($valid_by)  ? $valid_by : '' ?>">
                            <i class="text-info" style="display:block; text-align: center;">Name</i>
                        </div>
                        <div class="col-4">
                            <label class="control-label text-info">Date:</label>
                            <input readonly type="text" class="form-control rounded-0" class="form-control form-control-sm rounded-0" value="<?php echo isset($hr_sign_date)  ? date('m/d/Y h:i a', strtotime($hr_sign_date)) : '' ?>">
                        </div>
                    <?php } ?>
                </div>
                <div class="row justify-content-between">
                    <?php if ($hr_status == 1 && $sv_status != 0) { ?>
                        <div class="col-5">
                            <label class="control-label text-info"><?php echo isset($sv_status) && $sv_status == 2 ? 'Disapproved' : 'Approved' ?> by :</label>
                            <input readonly type="text" class="form-control" id="sv_name" value="<?php echo isset($appr_by)  ? $appr_by : '' ?>">
                            <i class="text-info" style="display:block; text-align: center;">Approver 1</i>
                        </div>
                        <div class="col-4">
                            <label class="control-label text-info">Date:</label>
                            <input readonly type="text" class="form-control rounded-0" class="form-control form-control-sm rounded-0" value="<?php echo isset($sv_sign_date)  ? date('m/d/Y h:i a', strtotime($sv_sign_date)) : '' ?>">
                        </div>
                    <?php }  ?>
                </div>
                <div class="row justify-content-between">
                    <?php if ($dh_status != 0 && $hr_status == 1 && $sv_status == 1) { ?>
                        <div class="col-5">
                            <label class="control-label text-info"><?php echo isset($dh_status) && $dh_status == 2 ? 'Disapproved' : 'Approved' ?> by :</label>
                            <input disabled type="text" class="form-control" id="dh_name" value="<?php echo isset($appr_by_dh[0])  ? $appr_by_dh[0] : '' ?>">
                            <i class="text-info" style="display:block; text-align: center;">Approver 2</i>
                        </div>
                        <div class="col-4">
                            <label class="control-label text-info">Date:</label>
                            <input readonly type="text" class="form-control rounded-0" class="form-control form-control-sm rounded-0" value="<?php echo isset($dh_sign_date)  ? date('m/d/Y h:i a', strtotime($dh_sign_date)) : '' ?>" required>
                        </div>
                    <?php } ?>
                </div>
                <div class=" py-1 text-center">
                    <!-- <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource') { ?>
                        <button class="btn btn-flat btn-sn btn-success" type="button" id="print"><i class="fa fa-print"></i> Print IR</button>
                    <?php } ?> -->


                    <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=incidentreport/manageIRDA' ?>">Back</a>
                </div>
            </div>

        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.approve_data').click(function() {
            // _conf("Are you sure to APPROVE this Incident Report?", "appr_irda", [$(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name')])
            appr_irda($(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name'));
        })
        $('.disapprove_data').click(function() {
            // _conf("Are you sure to DISAPPROVE this Incident Report?", "appr_irda", [$(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name')])
            // appr_irda($(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name'));
            uni_modal("", "incidentreport/approveIR/valid_disappr.php?id=" + $(this).attr('data-id'), 'mid-large');
        })
        $('.update_').click(function() {
            uni_modal("Update supension date", "incidentreport/manageIRDA/update_suspension_date.php?id=" + $(this).attr('data-id'), 'small');
        })
        $('.update_code').click(function() {
            uni_modal("Update supension date", "incidentreport/manageIRDA/update_violation.php?id=" + $(this).attr('data-id'), 'small');
        })

    })

    function appr_irda($id, $val, $sign, $name) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=valid_to_da",
            method: "POST",
            data: {
                id: $id,
                val: $val,
                sign: $sign,
                name: $name
            },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    // $('.sub').click();
                    // location.reload()
                    location.replace(_base_url_ + "admin/?page=incidentreport/approveIR");
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
    $('.edt').click(function() {
        uni_modal("Letter of explantion", _base_url_ + "admin/inbox/explainIR.php?id=" + $(this).attr('data-id'), 'large');
    })

    $('.datepicker').each(function() {
        $(this).datepick({
            multiSelect: 999,
            multiSeparator: ' + '
        });
    });
    // function passid(str) {
    //     // console.log($(this).attr('data-id'))
    //     console.log($('#date_of_suspension' + str))
    //     $('#date_of_suspension' + str).datepick({
    //         multiSelect: 999,
    //         multiSeparator: ' + '
    //     });
    // }


    // $(function() {

    // });
    $(document).ready(function() {

        // $('#date_of_suspension39').on('click', function() {
        // })


        $('input[type=number]').on('mousewheel', function(e) {
            $(e.target).blur();
        });

        $('.con_yes').change(function() {
            if ($('#con_yes' + $(this).attr('data-id')).is(':checked')) {
                console.log('data-id' + $(this).attr('data-id'))
                $('#con_no' + $(this).data('id')).prop('checked', false);
                $('#days_no' + $(this).data('id')).prop('required', true);
                $('#date_of_suspension' + $(this).data('id')).prop('required', true);
                $('#con_yes' + $(this).data('id')).prop('required', true);
                $('#con_yes' + $(this).data('id')).prop('required', true);
                $('#con_no' + $(this).data('id')).prop('required', false);

            }
        })
        $('.con_no').change(function() {
            if ($('#con_no' + $(this).attr('data-id')).is(':checked')) {
                $('#con_yes' + $(this).data('id')).prop('checked', false);
                $('#days_no' + $(this).data('id')).prop('required', false);
                $('#date_of_suspension' + $(this).data('id')).prop('required', false);
                $('#con_no' + $(this).data('id')).prop('required', true);
                $('#con_yes' + $(this).data('id')).prop('required', false);

            }
        })


        $('.table td,.table th').addClass('py-1 px-2 align-middle text-center')
        $('.did').hide();

        $('#offense_no').change(function() {
            console.log($('#offense_no').val());
            if ($('#offense_no').val() == 3 || $('#offense_no').val() == 4) {
                $('#suspension').attr('required', true)
                $('#suspension').removeAttr('readonly')
            } else {
                $('#suspension').attr('readonly', true)
                $('#suspension').removeAttr('required')
            }
        });

        $('#print').click(function() {
            $('.dont_').hide();
            $('.did').show();
            var head = $('head').clone();
            var rep = $('.printable').clone();
            var ns = $('noscript').clone().html();
            start_loader()
            rep.find('.content').after('<div class="page-break"></div>');

            rep.prepend(ns)
            rep.prepend(head)
            rep.find('#print_header').show()
            var nw = window.document.open('', '_blank', 'width=900,height=600')
            nw.document.write(rep.html())
            nw.document.close()
            setTimeout(function() {
                nw.print()
                setTimeout(function() {
                    $('.dont_').show();
                    $('.did').hide();
                    nw.close()
                    end_loader()
                }, 200)
            }, 300)
        })
    });
    // $('.edt').click(function() {
    //     uni_modal("Edit Incident", "incidentreport/createNewIRDA/add_ir.php?id=" + $(this).attr('data-id'), 'mid-large');
    // })

    $('.remove-option').click(function() {
        $(this).closest('.item').remove()
    })
    // Add Option button click event
    $('#add_option').click(function() {

        // Clone the template option and convert it into a jQuery object
        var item = $($('#option-clone').html()).clone();

        // Append the modified item to the option list
        $('#option-list').append(item);

        // Attach event handlers to the cloned item

        item.find('.remove-option').click(function() {
            $(this).closest('.item').remove();

        });
    });
    var messageType = 1;

    // $(window).on("beforeunload", function(event) {
    //     // Show a warning message
    //     if (messageType == 1) {
    //         return "Are you sure you want to leave? Your changes may not be saved.";
    //     }

    // });

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
            url: _base_url_ + "classes/IR_DA_Master.php?f=update_ir_request",
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
                    location.reload();
                    // location.replace(_base_url_ + 'admin/?page=pcn_form/')
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