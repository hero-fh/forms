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
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="10%">
                            <col width="25%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-primary">
                                <th>#</th>
                                <th>Date Created</th>
                                <th>IR No</th>
                                <th>Issued to</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $qry = $conn->query("SELECT * FROM ir_requests where hr_status = 1 and ir_status !=2 and ir_status != 3 and ir_status != 4 and emp_no = " . $_settings->userdata('EMPLOYID') . " ORDER BY `date_created` desc");
                            while ($row = $qry->fetch_assoc()) :
                                if ($row['ir_status'] == 1 && $row['why1'] == '') {
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                        <td><?php echo $row['ir_no'] ?></td>

                                        <?php
                                        $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                        while ($row1 = $qry1->fetch_assoc()) :
                                            $reqName = $row1['EMPNAME'];
                                        ?>
                                            <td><?php echo $row1['EMPNAME'] ?></td>
                                        <?php endwhile; ?>

                                        <td class="text-center">
                                            <?php if ($row['ir_status'] == 0) : ?>
                                                <span class="badge badge-primary rounded-pill">Pending</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['why1'] == '') : ?>
                                                <span class="badge badge-warning rounded-pill">Letter of explanation</span>
                                            <?php elseif ($row['ir_status'] == 2) : ?>
                                                <span class="badge badge-success rounded-pill">Approved</span>
                                            <?php elseif ($row['ir_status'] == 3) : ?>
                                                <span class="badge badge-danger rounded-pill">Disapproved</span>
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
                                                <a class="dropdown-item" href="<?php echo base_url . 'admin?page=inbox/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                                <!-- <div class="dropdown-divider"></div> -->
                                                <!-- <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/createNewIRDA/new_da&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Issue D.A</a> -->
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
                            <?php }
                            endwhile; ?>
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
                            <tr class="bg-gradient-primary">
                                <th>#</th>
                                <th>Date Created</th>
                                <th>IR No</th>
                                <th>Issued to</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $qry = $conn->query("SELECT * FROM ir_requests where  (ir_status = 2 and emp_no = " . $_settings->userdata('EMPLOYID') . ") or (why1 != '' and emp_no = " . $_settings->userdata('EMPLOYID') . ") ORDER BY `date_created` desc");
                            while ($row = $qry->fetch_assoc()) :
                                $c = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;
                                $is_valid = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}'")->num_rows;
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                    <td><?php echo $row['ir_no'] ?></td>

                                    <?php
                                    $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                    while ($row1 = $qry1->fetch_assoc()) :
                                        $reqName = $row1['EMPNAME'];
                                    ?>
                                        <td><?php echo $row1['EMPNAME'] ?></td>
                                    <?php endwhile; ?>

                                    <td class="text-center">
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
                                                <span class="badge badge-warning rounded-pill">DA: For Acknowledgment</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                                <span class="badge badge-primary rounded-pill">For Validation</span>
                                            <?php elseif ($row['ir_status'] == 2) : ?>
                                                <?php if ($is_valid > 0) { ?>
                                                    <span class="badge badge-success rounded-pill">Served</span>
                                                <?php } elseif ($is_valid == 0) { ?>
                                                    <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                                <?php } ?>
                                            <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                                Reason: <?php echo $row['disapprove_remarks'] ?>
                                            <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                <span class="badge badge-danger rounded-pill">Disapproved</span>
                                            <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 4) : ?>
                                                <span class="badge badge-success rounded-pill">DA: For Acknowledgment</span>
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
                                            <?php elseif ($row['da_status'] == 2 && $row['sv_name'] != $row['dh_name']) : ?>
                                                <span class="badge badge-secondary rounded-pill">For Supervisor</span>
                                            <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $row['dh_name'] == $row['sv_name'])) : ?>
                                                <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                            <?php elseif ($row['da_status'] == 4) : ?>
                                                <span class="badge badge-primary rounded-pill">DA: For Acknowledgment</span>
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
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=inbox/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
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