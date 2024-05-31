<style>
    td {
        vertical-align: middle;
    }
</style>
<div class="card card-outline card-primary">

    <div class="card-header p-0 pt-1 ">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <li class="pt-2 px-3">
                <h3 class="card-title">List of Incident report</h3>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-one-active-tab" data-toggle="pill" href="#custom-tabs-one-active" role="tab" aria-controls="custom-tabs-one-active" aria-selected="true">Invalid</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-history-tab" data-toggle="pill" href="#custom-tabs-one-history" role="tab" aria-controls="custom-tabs-one-history" aria-selected="false">Cancelled</a>
            </li>
            <!-- <li class="nav-item ml-auto">
                <a href="<?php echo base_url ?>admin/?page=incidentreport/DA/new_ir" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>

            </li> -->
        </ul>
    </div>
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
                            $i = 1;

                            $qry = $conn->query("SELECT * FROM ir_requests where (hr_status=2 and ir_status = 0 and requestor_id = " . $_settings->userdata('EMPLOYID') . ") or  (ir_status = 3 and sv_status != 1 and requestor_id = " . $_settings->userdata('EMPLOYID') . ")ORDER BY `date_created` desc");

                            while ($row = $qry->fetch_assoc()) :
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td class="text-center"><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                    <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                    <?php
                                    $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                    while ($row1 = $qry1->fetch_assoc()) :
                                        $reqName = $row1['EMPNAME'];
                                        $dpr = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['hr_name']}'")->fetch_array();
                                    ?>
                                        <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                    <?php endwhile; ?>

                                    <td class="text-center">
                                        <?php if ($row['hr_status'] == 2) : ?>
                                            <span class="badge badge-danger rounded-pill">Invalid</span>
                                        <?php elseif ($row['ir_status'] == 1) : ?>
                                            <span class="badge badge-warning rounded-pill">Partially Approved</span>
                                        <?php elseif ($row['ir_status'] == 2) : ?>
                                            <span class="badge badge-success rounded-pill">Approved</span>
                                        <?php elseif ($row['ir_status'] == 3) : ?>
                                            <span class="badge badge-danger rounded-pill">Disapproved</span>
                                        <?php else : ?>
                                            <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-left">Disapproved by: <?php echo isset($dpr[0]) ? $dpr[0] : '' ?> <br>
                                        Reason: <?php echo $row['disapprove_remarks'] ?></td>
                                    <td align="center">
                                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">

                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/disapprovedIRDA/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> Edit</a>


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
                                            <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item cancel_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Cancel</a> -->
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-one-history" role="tabpanel" aria-labelledby="custom-tabs-one-history-tab">
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
                            $i = 1;

                            $qry = $conn->query("SELECT * FROM ir_requests where  (ir_status = 4 or (ir_status = 3 and sv_status = 1))and requestor_id = " . $_settings->userdata('EMPLOYID') . " ORDER BY `date_created` desc");

                            while ($row = $qry->fetch_assoc()) :
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td class="text-center"><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                    <td class="text-center"><?php echo $row['ir_no'] ?></td>

                                    <?php
                                    $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                    while ($row1 = $qry1->fetch_assoc()) :
                                        $reqName = $row1['EMPNAME'];
                                        $dpr = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['hr_name']}'")->fetch_array();
                                    ?>
                                        <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                    <?php endwhile; ?>

                                    <td class="text-center">
                                        <?php if ($row['ir_status'] == 4) : ?>
                                            <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                        <?php elseif ($row['ir_status'] == 1) : ?>
                                            <span class="badge badge-warning rounded-pill">Partially Approved</span>
                                        <?php elseif ($row['ir_status'] == 2) : ?>
                                            <span class="badge badge-success rounded-pill">Approved</span>
                                        <?php elseif ($row['ir_status'] == 3) : ?>
                                            <span class="badge badge-danger rounded-pill">Disapproved</span>
                                        <?php else : ?>
                                            <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-left">Cancelled by: <?php echo isset($dpr[0]) ? $dpr[0] : '' ?> <br>
                                        Reason: <?php echo $row['disapprove_remarks'] ?></td>
                                    <td align="center">
                                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">

                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/disapprovedIRDA/view_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-pen text-dark"></span> View</a>

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
        $('.cancel_data').click(function() {
            // _conf("Are you sure to DISAPPROVE this Incident Report?", "appr_irda", [$(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name')])
            // appr_irda($(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name'));
            uni_modal("", "incidentreport/approveIR/cancel_ir.php?id=" + $(this).attr('data-id'), 'mid-large');
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