<div class="card card-outline card-primary">
    <div class="card-header p-0 pt-1 ">
        <ul class="nav nav-tabs mr-2" id="custom-tabs-one-tab" role="tablist">
            <li class="pt-2 px-3">
                <h3 class="card-title">List of IR / DA</h3>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-one-active-tab" data-toggle="pill" href="#custom-tabs-one-active" role="tab" aria-controls="custom-tabs-one-active" aria-selected="true">IR / DA</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-history-tab" data-toggle="pill" href="#custom-tabs-one-history" role="tab" aria-controls="custom-tabs-one-history" aria-selected="false">DA</a>
            </li> -->
            <li class="nav-item ml-auto">
                <!-- <a href="<?php echo base_url ?>admin/?page=prf/new_prf" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Export</a> -->
                <a href="#" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Export</a>
            </li>
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

                        <thead class="text-center">
                            <tr class="bg-gradient-primary">
                                <th>#</th>
                                <th>IR Date</th>
                                <th>IR No</th>
                                <th>Issued to</th>
                                <th>Supervisor</th>
                                <th>PL</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            if ($is_quality > 0) {
                                $qry = $conn->query("SELECT * FROM ir_requests where quality_violation = 2 ORDER BY `date_created` desc");
                            } else {
                                $qry = $conn->query("SELECT * FROM ir_requests ORDER BY `date_created` desc");
                            }

                            while ($row = $qry->fetch_assoc()) :

                                $approver_2 = $conn->query("SELECT APPROVER2 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $approver_1 = $conn->query("SELECT APPROVER1 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $approver_1 = $approver_1 == 'na' ? $approver_2 : $approver_1;
                                $approver_3 = $conn->query("SELECT APPROVER3 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                $c = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;
                                $is_valid = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}'")->num_rows;
                                if ($row['ir_status'] != 2 || $c == 0) :
                                    $valid_to_da_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_to_da_name']}'")->fetch_array();
                                    $valid_appeal_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_appeal_name']}'")->fetch_array();
                                    $od_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['od_name']}'")->fetch_array();
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo date("m-d-Y", strtotime($row['when'])) ?></td>
                                        <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                        <?php
                                        $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                        $svname = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = (SELECT CASE WHEN APPROVER1 = 'na' THEN APPROVER2 ELSE APPROVER1 END AS Approver from employee_masterlist WHERE (EMPLOYID = '{$row['emp_no']}'))")->fetch_array();
                                        while ($row1 = $qry1->fetch_assoc()) :
                                            $reqName = $row1['EMPNAME'];
                                        ?>
                                            <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                        <?php endwhile; ?>
                                        <td class="text-center"><?php echo isset($svname[0]) ? $svname[0] : '' ?></td>
                                        <td class="text-center"><?php echo $row['productline'] ?></td>
                                        <td class="text-center"><?php echo $row['department'] ?></td>
                                        <td class="text-center">
                                            <?php if ($row['is_inactive'] == 1) { ?>
                                                <span class="badge badge-danger rounded-pill">Inactive</span>
                                            <?php } else { ?>
                                                <?php
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
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1) : ?>
                                                    <span class="badge badge-warning rounded-pill">IR: For approval department</span>
                                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                                    <span class="badge badge-primary rounded-pill">For Validation</span>
                                                <?php elseif ($row['ir_status'] == 2) : ?>
                                                    <?php if ($is_valid > 0) { ?>
                                                        <span class="badge badge-success rounded-pill">Valid</span>
                                                    <?php } elseif ($is_valid == 0) { ?>
                                                        <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                                    <?php } ?>
                                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                    <div class="text-left">
                                                        Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                                        Reason: <?php echo $row['disapprove_remarks'] ?>
                                                    </div>
                                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                    <span class="badge badge-danger rounded-pill">Disapproved</span>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary rounded-pill">Cancelled</span><br>
                                                    <div class="text-left">
                                                        Cancelled by: <?php echo isset($dpr[0]) ? $dpr[0] : '' ?> <br>
                                                        Reason: <?php echo $row['disapprove_remarks'] ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php } ?>
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
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/manageIRDA/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                                <?php if ($row['is_inactive'] == 0) { ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item inactive_data" href="javascript:void(0)" data-id="<?php echo $row['emp_no'] ?>"><span class="fa fa-ban text-danger"></span> Inactive</a>
                                                <?php }  ?>
                                                <?php if ($row['is_inactive'] == 0 && $row['ir_status'] < 4) { ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item cancel_data" href="javascript:void(0)" data-id="<?php echo $row['id']  ?>" data-val="2" data-sign="3" data-name="<?php echo $_settings->userdata('EMPLOYID') ?>"> <i class="fas fa-trash text-danger"></i> Cancel</a>
                                                <?php }  ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php elseif ($row['ir_status'] == 2 && $c > 0) : ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo date("m-d-Y", strtotime($row['when'])) ?></td>
                                        <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                        <?php

                                        $svname = $conn->query("SELECT empname FROM employee_masterlist WHERE EMPLOYID = '{$row['sv_name']}'")->fetch_array();
                                        $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                        while ($row1 = $qry1->fetch_assoc()) :
                                            $reqName = $row1['EMPNAME'];
                                        ?>
                                            <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                        <?php endwhile; ?>
                                        <td class="text-center"><?php echo isset($svname[0]) ? $svname[0] : '' ?></td>
                                        <td class="text-center"><?php echo $row['productline'] ?></td>
                                        <td class="text-center"><?php echo $row['department'] ?></td>
                                        <td class="text-center">
                                            <?php if ($row['is_inactive'] == 1) { ?>
                                                <span class="badge badge-danger rounded-pill">Inactive</span>
                                            <?php } else { ?>
                                                <?php if ($row['has_da'] == 0) : ?>
                                                    <span class="badge badge-warning rounded-pill">For DA</span>
                                                <?php elseif ($row['has_da'] == 1) : ?>
                                                    <?php if ($row['da_status'] == 1) : ?>
                                                        <span class="badge badge-danger rounded-pill">For HR Manager</span>
                                                    <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                                        <span class="badge badge-secondary rounded-pill">DA: For Supervisor</span>
                                                    <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                                        <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                                    <?php elseif ($row['da_status'] == 4) : ?>
                                                        <span class="badge badge-danger rounded-pill">For Acknowledgement</span>
                                                    <?php elseif ($row['da_status'] == 5) : ?>
                                                        <span class="badge badge-success rounded-pill">Acknowledged </span>
                                                    <?php else : ?>
                                                        <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php } ?>
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
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/manageIRDA/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View IR</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/manageIRDA/viewDA&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> View DA</a>
                                                <?php if ($row['is_inactive'] == 0) { ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item inactive_data" href="javascript:void(0)" data-id="<?php echo $row['emp_no'] ?>"><span class="fa fa-ban text-danger"></span> Inactive</a>
                                                <?php }  ?>
                                                <?php if ($row['is_inactive'] == 0 && $row['ir_status'] < 4) { ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item cancel_data" href="javascript:void(0)" data-id="<?php echo $row['id']  ?>" data-val="2" data-sign="3" data-name="<?php echo $_settings->userdata('EMPLOYID') ?>"> <i class="fas fa-trash text-danger"></i> Cancel</a>
                                                <?php }  ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="tab-pane fade" id="custom-tabs-one-history" role="tabpanel" aria-labelledby="custom-tabs-one-history-tab">
                <div class="container-fluid">
                    <table class="table table-bordered table-stripped">

                        <thead>
                            <tr class="bg-gradient-primary">
                                <th>#</th>
                                <th>Date Created</th>
                                <th>IR No</th>
                                <th>Issued to</th>
                                <th>Supervisor</th>
                                <th>PL</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $qry = $conn->query("SELECT * FROM ir_requests where ir_status = 2 ORDER BY `date_created` desc");
                            while ($row = $qry->fetch_assoc()) :
                                $c = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;
                                if ($c > 0) {
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo date("m-d-Y", strtotime($row['when'])) ?></td>
                                        <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                        <?php

                                        $svname = $conn->query("SELECT empname FROM employee_masterlist WHERE EMPLOYID = '{$row['sv_name']}'")->fetch_array();
                                        $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                        while ($row1 = $qry1->fetch_assoc()) :
                                            $reqName = $row1['EMPNAME'];
                                        ?>
                                            <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                        <?php endwhile; ?>
                                        <td class="text-center"><?php echo isset($svname[0]) ? $svname[0] : '' ?></td>
                                        <td class="text-center"><?php echo $row['productline'] ?></td>
                                        <td class="text-center">
                                            <?php if ($row['da_status'] == 0) : ?>
                                                <span class="badge badge-warning rounded-pill">For DA</span>
                                            <?php elseif ($row['da_status'] == 1) : ?>
                                                <span class="badge badge-danger rounded-pill">For HR Manager</span>
                                                    <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                                        <span class="badge badge-secondary rounded-pill">For Supervisor</span>
                                                    <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                                <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                            <?php elseif ($row['da_status'] == 4) : ?>
                                                <span class="badge badge-danger rounded-pill">For Acknowledgement</span>
                                            <?php elseif ($row['da_status'] == 5) : ?>
                                                <span class="badge badge-success rounded-pill">Acknowledged </span>
                                            <?php else : ?>
                                                <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/manageIRDA/viewDA&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> View D.A</a>
                                                <?php if ($_settings->userdata('EMPLOYID') != $row['emp_no']) { ?>

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
            </div> -->
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('.delete_data').click(function() {
            _conf("Are you sure to cancel this incident report?", "delete_po", [$(this).attr('data-id')])
        })
        $('.inactive_data').click(function() {
            _conf("Are you sure to inactive this employee?", "inactive_ir", [$(this).attr('data-id')])
        })
        $('.issue_da').click(function() {
            // _conf("Are you sure to issue disciplinary action on this incident report?", "issue_da", [$(this).attr('data-id')])
            uni_modal("Are you sure to issue disciplinary action on this incident report?", "incidentreport/createNewIRDA/new_da.php?id=" + $(this).attr('data-id'), 'mid-large')
        })
        $('.export_list').click(function() {
            uni_modal("", "incidentreport/createNewIRDA/export_data.php", 'mid-large')
        })
        $('.cancel_data').click(function() {
            uni_modal("", "incidentreport/approveIR/cancel_ir.php?id=" + $(this).attr('data-id'), 'mid-large');
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

    function inactive_ir($emp_no) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=inactive_ir",
            method: "POST",
            data: {
                emp_no: $emp_no
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