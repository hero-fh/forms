<script>
    start_loader();
</script>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Aproval History</h3>
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
                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `od_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                        }
                        if ($_settings->userdata('EMPPOSITION') == 4) {
                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                        }
                        if ($_settings->userdata('EMPPOSITION') == 3) {
                            if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
                                $dept1 = "PPC";
                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                            }
                            if ($_settings->userdata('EMPLOYID') == '600') { // Christine
                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                            }
                        }
                    } else {
                        $qry = $conn->query("SELECT * FROM overtime_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
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
                                <?php if ($_settings->userdata('EMPPOSITION') == 4 || $_settings->userdata('EMPLOYID') == '600') {
                                ?>
                                    <?php if ($row['dh_status'] == 1) : ?>
                                        <span class="badge badge-success rounded-pill">Approved</span>
                                    <?php elseif ($row['dh_status'] == 2) : ?>
                                        <span class="badge badge-danger rounded-pill">Disapproved</span>
                                    <?php endif; ?>
                                <?php
                                } ?>
                                <?php if ($_settings->userdata('EMPPOSITION') == 5) {
                                ?>
                                    <?php if ($row['od_status'] == 1) : ?>
                                        <span class="badge badge-success rounded-pill">Approved</span>
                                    <?php elseif ($row['od_status'] == 2) : ?>
                                        <span class="badge badge-danger rounded-pill">Disapproved</span>
                                    <?php endif; ?>
                                <?php
                                } ?>

                            </td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=overtimeForm/view_ot&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
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
            uni_modal("", "overtime_form/export_data.php", 'mid-large')

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