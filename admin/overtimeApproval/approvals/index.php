<script>
    start_loader();
</script>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Pending Overtime Request</h3>
        <div class="card-tools">
            <button class="btn btn-flat btn btn-outline-success btn-xs" id="selectAllButton">Select All</button>
            <button class="btn btn-flat btn btn-outline-success btn-xs" id="unSelectAllButton">Unselect All</button>
            <button class="btn btn-flat btn btn-outline-success btn-xs approve_data" type="submit" form="ot-form" name="action" value="approve" id="approve"> <i class="fas fa-thumbs-up"></i> Approve</button>
            <button class="btn btn-flat btn btn-outline-danger btn-xs disapprove_data" type="submit" form="ot-form" name="action" value="disapprove" id="disapprove"> <i class="fas fa-thumbs-down"></i> Disapprove</button>
        </div>
    </div>
    <form action="" id="ot-form">
        <div class="card-body">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="5%">
                        <col width="15%">
                        <col width="10%">
                        <col width="25%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>Date Created</th>
                            <th>OT Form #</th>
                            <th>Requestor</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (!empty($_settings->userdata('EMPNAME'))) {
                            if ($_settings->userdata('EMPPOSITION') == 5) {
                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 1 ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('EMPPOSITION') == 4) {
                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$_settings->userdata('DEPARTMENT')}' 
                                                    AND `productline` = '{$_settings->userdata('PRODUCT_LINE')}') ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('EMPPOSITION') == 4) {
                                if ($_settings->userdata('EMPLOYID') == '1694') { // Lean
                                    $dept1 = "MIS";
                                    $dept2 = "Facilities";
                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            (`department` = '{$dept1}' OR `department` = '{$dept2}') ORDER BY `date_created` desc");
                                }
                                if ($_settings->userdata('EMPLOYID') == '702') { // Joan
                                    $dept1 = 'Finance';
                                    $dept2 = 'Purchasing';
                                    $prodline1 = 'G & A';

                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') ORDER BY `date_created` desc");
                                }
                                if ($_settings->userdata('EMPLOYID') == '524') { // Charity
                                    $dept1 = 'Human Resource';
                                    $dept2 = 'Training';

                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            (`department` = '{$dept1}' OR `department` = '{$dept2}') ORDER BY `date_created` desc");
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

                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}')) ORDER BY `date_created` desc");
                                }
                                if ($_settings->userdata('EMPLOYID') == '20') { // Noel
                                    $dept1 = 'Production';
                                    $dept2 = 'Store';
                                    $dept3 = 'IQA Warehouse';
                                    $dept4 = 'Logistics';
                                    $prodline1 = 'PL9 (AD/WHSE)';
                                    $prodline2 = 'G & A';
                                    $prodline3 = 'PL8 (AMS O/S)';
                                    $prodline4 = 'PL3 (ADCV)';
                                    $prodline5 = 'PL3 (ADCV) - Onsite';

                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            (((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') 
                                                                AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}'))) 
                                                            ORDER BY `date_created` desc");
                                }
                                // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL6 (ADLT)') {
                                //     $dept1 = 'Production';
                                //     $dept2 = 'Production / Non - TNR';
                                //     $prodline1 = 'PL6 (ADLT)';

                                //     $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                //                             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline2}')) ORDER BY `date_created` desc");
                                // }
                                if ($_settings->userdata('EMPLOYID') == '297') { // Erwin
                                    $dept1 = 'Quality Assurance';
                                    $prodline1 = 'G & A';
                                    $prodline2 = 'PL1 - PL4';
                                    $prodline3 = 'PL1 (ADGT)';
                                    $prodline4 = 'PL2 (AD/OS)';
                                    $prodline5 = 'PL3 (ADCV)';
                                    $prodline6 = 'PL3 (ADCV) - Onsite';
                                    $prodline7 = 'PL4 (ADGT)';
                                    $prodline8 = 'PL6 (ADLT)';
                                    $prodline9 = 'PL8 (AMS O/S)';

                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                                OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}'
                                                                OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) ORDER BY `date_created` desc");
                                }
                                if (($_settings->userdata('EMPLOYID') == '1023')) { // Adonis
                                    $dept1 = 'Equipment Engineering';
                                    $prodline1 = 'G & A';
                                    $prodline2 = 'PL1 (ADGT)';
                                    $prodline3 = 'PL2 (AD/OS)';
                                    $prodline4 = 'PL3 (ADCV)';
                                    $prodline5 = 'PL3 (ADCV) - Onsite';
                                    $prodline6 = 'PL4 (ADGT)';
                                    $prodline7 = 'PL6 (ADLT)';
                                    $prodline8 = 'PL8 (AMS O/S)';

                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                                OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}'
                                                                OR `productline` = '{$prodline8}')) ORDER BY `date_created` desc");
                                }
                                if ($_settings->userdata('EMPLOYID') == '1170') { // Realyn
                                    $dept1 = 'Process Engineering';
                                    $prodline1 = 'G & A';
                                    $prodline2 = 'PL1 - PL4';
                                    $prodline3 = 'PL2 (AD/OS)';
                                    $prodline4 = 'PL3 (ADCV)';
                                    $prodline5 = 'PL3 (ADCV) - Onsite';
                                    $prodline6 = 'PL6 (ADLT)';
                                    $prodline7 = 'PL8 (AMS O/S)';

                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                                OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) ORDER BY `date_created` desc");
                                }
                                if ($_settings->userdata('EMPLOYID') == '1065') { // Tess
                                    $dept1 = 'Production';
                                    $dept2 = 'Production / PE';
                                    $prodline1 = 'PL2 (AD/OS)';

                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                            ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') ORDER BY `date_created` desc");
                                }
                            }
                            if ($_settings->userdata('EMPPOSITION') == 3) {
                                if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
                                    $dept1 = "PPC";
                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND `department` = '{$dept1}' ORDER BY `date_created` desc");
                                }
                                if ($_settings->userdata('EMPLOYID') == '600') { // Christine
                                    $dept1 = 'Production';
                                    $prodline1 = 'PL3 (ADCV)';
                                    $prodline2 = 'PL3 (ADCV) - Onsite';
                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$dept1}' 
                                                        AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}')) ORDER BY `date_created` desc");
                                }
                            }
                            if ($_settings->userdata('EMPPOSITION') == 2) {
                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                            }
                        } else {
                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 ORDER BY `date_created` desc");
                        }
                        while ($row = $qry->fetch_assoc()) :
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center">
                                    <div class="icheck-primary d-inline">
                                        <input class="checkbox" type="checkbox" id="checkboxPrimary<?php echo $i ?>" value="<?php echo $row['id'] ?>" name="iCheck[]">
                                        <label for="checkboxPrimary<?php echo $i ?>">
                                        </label>
                                    </div>
                                    <!-- <input type="checkbox" class="form-control form-control-sm checkbox" id="checkboxPrimary1" value="<?php //echo $row['ot_form_no'] 
                                                                                                                                            ?>" name="iCheck[]"> -->
                                </td>
                                <td><?php echo date("m-d-Y H:ia", strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['ot_form_no'] ?></td>

                                <?php
                                $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['requestor_id']}'");
                                while ($row1 = $qry1->fetch_assoc()) :
                                    $reqName = $row1['EMPNAME'];
                                ?>
                                    <td><?php echo $row1['EMPNAME'] ?></td>
                                <?php endwhile; ?>

                                <td class="text-center">
                                    <?php if ($row['ot_status'] == 0) : ?>
                                        <span class="badge badge-primary rounded-pill">Pending</span>
                                    <?php elseif ($row['ot_status'] == 1) : ?>
                                        <span class="badge badge-warning rounded-pill">Partially Approved</span>
                                    <?php elseif ($row['ot_status'] == 2) : ?>
                                        <span class="badge badge-success rounded-pill">Approved</span>
                                    <?php elseif ($row['ot_status'] == 3) : ?>
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
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=overtimeForm/view_ot&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                        <div class="dropdown-divider"></div>

                                        <?php if (($_settings->userdata('EMPPOSITION') == 5) && $row['ot_status'] == 1) : ?>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=overtimeApproval/manage_ot&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
                                        <?php endif; ?>

                                        <?php if (($_settings->userdata('EMPPOSITION') == 4 || $_settings->userdata('EMPPOSITION') == 3 || $_settings->userdata('EMPLOYID') == '600') && $row['ot_status'] == 0) : ?>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=overtimeApproval/manage_ot&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
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
<input type="hidden" value="<?php echo $_settings->userdata('EMPLOYID') ?>" name="approverNum">
<input type="hidden" value="<?php echo $_settings->userdata('EMPPOSITION') ?>" name="appType">
</form>
<script>
    $(document).ready(function() {
        end_loader();
        $('.table').dataTable();

        // Attach a click event handler to the "Select All" button
        $('#selectAllButton').click(function() {
            // Toggle the state of all checkboxes
            $('.checkbox').prop('checked', true);
        });
        // Attach a click event handler to the "Select All" button
        $('#unSelectAllButton').click(function() {
            // Toggle the state of all checkboxes
            $('.checkbox').prop('checked', false);
        });
    })

    $('#ot-form').submit(function(e) {
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
            url: _base_url_ + "classes/Master.php?f=sign_ot_icheck",
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
                    location.replace(_base_url_ + "admin/?page=overtimeApproval/approvalhistory");
                } else if (resp.status == 'failed' && !!resp.msg) {
                    var el = $('<div>')
                    el.addClass("alert alert-danger err-msg").text(resp.msg)
                    _this.prepend(el)
                    el.show('slow')
                    end_loader()
                } else {
                    alert_toast("Please check the boxes!", 'warning');
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