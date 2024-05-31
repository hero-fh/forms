<script>
    start_loader();
</script>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Approved Overtime Request</h3>
        <div class="card-tools">
            <?php
            if ($_settings->userdata('EMPPOSITION') == 2) {
            ?>
                <a href="<?php echo base_url ?>admin/?page=overtimeform/manage_ot" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>
            <?php
            }
            ?>
            <?php
            if (!empty($_settings->userdata('log_category'))) {
            ?>
                <a href="javascript:void(0)" class="btn btn-flat btn-primary export_list"><span class="fa fa-download"></span> Export</a>
            <?php
            }
            ?>

        </div>
    </div>
    <div class="card-body">
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
                    <tr>
                        <th>#</th>
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
                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND (`department` = '{$_settings->userdata('DEPARTMENT')}' 
                                                AND `productline` = '{$_settings->userdata('PRODUCT_LINE')}') ORDER BY `date_created` desc");
                        }
                        if ($_settings->userdata('EMPPOSITION') == 4) {
                            if ($_settings->userdata('DEPARTMENT') == 'Facilities/ MIS') {
                                $dept1 = "MIS";
                                $dept2 = "Facilities";
                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        (`department` = '{$dept1}' OR `department` = '{$dept2}') ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('DEPARTMENT') == 'Finance/ Purchasing') {
                                $dept1 = 'Finance';
                                $dept2 = 'Purchasing &amp; IQA Warehouse';
                                $dept2 = 'Purchasing';
                                $prodline1 = 'G &amp; A';

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}') AND `productline` = '{$prodline1}') ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('DEPARTMENT') == 'Human Resource') {
                                $dept1 = 'Human Resource';
                                $dept2 = 'Training';

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        (`department` = '{$dept1}' OR `department` = '{$dept2}') ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL1 - PL4') {
                                $dept1 = 'Production';
                                $dept2 = 'Production - QFP';
                                $dept3 = 'Production - RFC';
                                $dept4 = 'Production / Non - TNR';
                                $prodline1 = 'PL1 - PL4';
                                $prodline2 = 'PL1 (ADGT)';
                                $prodline3 = 'PL4 (ADGT)';

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}')) ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('DEPARTMENT') == 'Production / Site and Support/ Store/ Security/ IQA Warehouse' && $_settings->userdata('PRODUCT_LINE') == 'G &amp; A / PL8 / PL9') {
                                $dept1 = 'Production';
                                $dept2 = 'Site and Support';
                                $dept3 = 'Store';
                                $dept4 = 'Security';
                                $dept5 = 'IQA Warehouse - Store';
                                $dept6 = 'IQA Warehouse';
                                $prodline1 = 'PL9 (AD/WHSE)';
                                $prodline2 = 'G &amp; A';
                                $prodline3 = 'PL8 (AMS O/S)';

                                // $qry = $conn->query("SELECT * FROM overtime_requests WHERE 
                                //                         (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}'))
                                //                             OR (`department` = '{$dept3}' OR `department` = '{$dept4}')) ORDER BY `date_created` desc");

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        (((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}' OR `department` = '{$dept5}' OR `department` = '{$dept6}') 
                                                            AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) 
                                                        ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL6 (ADLT)') {
                                $dept1 = 'Production';
                                $dept2 = 'Production / Non - TNR';
                                $prodline1 = 'PL6 (ADLT)';

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline2}')) ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('DEPARTMENT') == 'Quality Assurance' && $_settings->userdata('PRODUCT_LINE') == 'G &amp; A') {
                                $dept1 = 'Quality Assurance';
                                $prodline1 = 'G &amp; A';
                                $prodline2 = 'PL1 - PL4';
                                $prodline3 = 'PL1 (ADGT)';
                                $prodline4 = 'PL2 (AD/OS)';
                                $prodline5 = 'PL3 (ADCV)';
                                $prodline6 = 'PL3 (ADCV) - Onsite';
                                $prodline7 = 'PL4 (ADGT)';
                                $prodline8 = 'PL6 (ADLT)';
                                $prodline9 = 'PL8 (AMS O/S)';

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                            OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}'
                                                            OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) ORDER BY `date_created` desc");
                            }
                            if (($_settings->userdata('DEPARTMENT') == 'Engineering' || $_settings->userdata('DEPARTMENT') == 'Equipment Engineering') && $_settings->userdata('PRODUCT_LINE') == 'G &amp; A') {
                                $dept1 = 'Engineering';
                                $dept2 = 'Equipment Engineering';
                                $prodline1 = 'G &amp; A';
                                $prodline2 = 'PL1 - PL4';
                                $prodline3 = 'PL1 (ADGT)';
                                $prodline4 = 'PL2 (AD/OS)';
                                $prodline5 = 'PL3 (ADCV)';
                                $prodline6 = 'PL3 (ADCV) - Onsite';
                                $prodline7 = 'PL4 (ADGT)';
                                $prodline8 = 'PL6 (ADLT)';
                                $prodline9 = 'PL8 (AMS O/S)';

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                            OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}'
                                                            OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('DEPARTMENT') == 'Process Engineering' && $_settings->userdata('PRODUCT_LINE') == 'G &amp; A') {
                                $dept1 = 'Process Engineering';
                                $dept2 = 'Process Engineering / PE';
                                $prodline1 = 'G &amp; A';
                                $prodline2 = 'PL1 - PL4';
                                $prodline3 = 'PL2 (AD/OS)';
                                $prodline4 = 'PL3 (ADCV)';
                                $prodline5 = 'PL3 (ADCV) - Onsite';
                                $prodline6 = 'PL6 (ADLT)';

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                            OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}')) ORDER BY `date_created` desc");
                            }
                            if (($_settings->userdata('DEPARTMENT') == 'Operations') && $_settings->userdata('PRODUCT_LINE') == 'G &amp; A') {
                                $dept1 = 'Engineering';
                                $dept2 = 'Equipment Engineering';
                                $dept3 = 'PPC';
                                $dept4 = 'Production';
                                $dept5 = 'Production / PE';
                                $dept6 = 'Production - RFC';
                                $dept7 = 'Production - QFP';
                                $dept8 = 'Production / Non - TNR';
                                $dept9 = 'Process Engineering';
                                $prodline1 = 'G &amp; A';
                                $prodline2 = 'PL1 - PL4';
                                $prodline3 = 'PL1 (ADGT)';
                                $prodline4 = 'PL2 (AD/OS)';
                                $prodline5 = 'PL3 (ADCV)';
                                $prodline6 = 'PL3 (ADCV) - Onsite';
                                $prodline7 = 'PL4 (ADGT)';
                                $prodline8 = 'PL6 (ADLT)';
                                $prodline9 = 'PL3 (GEN TR)';
                                $prodline10 = 'PL8 (AMS O/S)';

                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND
                                                        ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}' OR `department` = '{$dept5}' OR `department` = '{$dept6}' 
                                                            OR `department` = '{$dept7}' OR `department` = '{$dept8}' OR `department` = '{$dept9}')                                                         
                                                        AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                            OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}'
                                                            OR `productline` = '{$prodline9}' OR `productline` = '{$prodline10}')) ORDER BY `date_created` desc");
                            }
                        }
                        if ($_settings->userdata('EMPPOSITION') == 3) {
                            if ($_settings->userdata('DEPARTMENT') == 'PPC') {
                                $dept1 = "PPC";
                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND `department` = '{$dept1}' ORDER BY `date_created` desc");
                            }
                        }
                        // if ($_settings->userdata('EMPPOSITION') == 3) {
                        //     if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL3 (ADCV)') {
                        //         $dept1 = "Process Engineering";
                        //         $prodline1 = 'PL3';

                        //         $qry = $conn->query("SELECT * FROM overtime_requests WHERE 
                        //                                 (`department` = '{$dept1}' AND `productline` = '{$prodline1}') ORDER BY `date_created` desc");
                        //     }
                        // }
                        if ($_settings->userdata('EMPLOYID') == '600') {
                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND (`department` = '{$_settings->userdata('DEPARTMENT')}' 
                                                AND `productline` = '{$_settings->userdata('PRODUCT_LINE')}') ORDER BY `date_created` desc");
                        }
                        if ($_settings->userdata('EMPPOSITION') >= 2) {
                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                        }
                    } else {
                        $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 ORDER BY `date_created` desc");
                    }
                    while ($row = $qry->fetch_assoc()) :
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
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
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>

                                    <?php if (($_settings->userdata('EMPPOSITION') == 4 || $_settings->userdata('EMPLOYID') == '600') && $row['ot_status'] == 0) : ?>
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=overtimeApproval/manage_ot&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fas fa-exchange-alt text-dark"></span> Approve</a>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>

                                    <?php if (($_settings->userdata('EMPPOSITION') == 2 && $_settings->userdata('EMPNAME') == $reqName) && $row['ot_status'] == 0) : ?>
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
<script>
    $(document).ready(function() {
        end_loader();
        $('.delete_data').click(function() {
            _conf("Are you sure to cancel this Overtime Request permanently?", "delete_ot", [$(this).attr('data-id')])
        })
        $('.export_list').click(function() {
            uni_modal("", "overtimeForm/export_data.php", 'mid-large')

        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable();
    })

    function delete_ot($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_ot",
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