<div class="card card-outline card-primary">
    <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">List of Payroll Change Notice Issued Forms</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
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
                                <th>PCN Form #</th>
                                <th>Employee</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            if (!empty($_settings->userdata('EMPNAME'))) {
                                $qry = $conn->query("SELECT * FROM pcn_requests WHERE `emp_num` = '{$_settings->userdata('EMPLOYID')}' AND pcn_status = 3;");
                            }
                            while ($row = $qry->fetch_assoc()) :
                                // echo $row['pa_form_code'];
                                $qry1 = $conn->query("SELECT * FROM appraisal_requests WHERE pa_form_no = '{$row['pa_form_code']}'");

                                $passSatus = 0;

                                while ($row1 = $qry1->fetch_assoc()) :
                                    //  echo "|" . $row['pcn_status'] . "|" . "-" . $row1['pa_status'] . "-";
                                    //    if ((!empty($row['pa_form_code']) && ($row1['pa_status'] >= 2 && $row1['pa_status'] != 6) && ($row['pcn_status'] < 3)) || ($row['pa_form_code'] == NULL && $row['pcn_status'] < 3)) {
                                    $paStatus = $row1['pa_status'];
                                    if (($row['pa_form_code'] != "" && $paStatus < 2) && ($row['pcn_status'] == 0)) {
                                        $passSatus = 1;
                                    }
                                endwhile;
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                                    <td><?php echo $row['pcn_form_no'] ?></td>
                                    <td><?php echo $row['emp_name'] ?></td>

                                    <td class="text-center">
                                        <?php if ($row['emp_status'] == 0) : ?>
                                            <span class="badge badge-warning">Needs Acknowledgement</span>
                                        <?php elseif ($row['emp_status'] == 1) : ?>
                                            <span class="badge badge-success">Acknowledged</span>
                                        <?php endif; ?>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=pcnForms/view_pcn&id=' . md5($row['id']) . '&pas=' . $passSatus ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                            <!-- Employee accounts-->
                                            <?php if (!empty($_settings->userdata('EMPNAME'))) { ?>
                                                <?php if ($row['emp_status'] == 0) : ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=inbox/signpcn&id=' . md5($row['id']) . '&pas=' . $passSatus ?>"><span class="fas fa-pen text-warning"></span> ACK</a>
                                                <?php endif; ?>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php

                            //  }
                            //  endwhile;
                            endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        // start_loader();

        // $(window).on('load', function() {
        //     end_loader();
        // });

        $('.cancel_data').click(function() {
            _conf("Are you sure to cancel this Performance Appraisal permanently?", "cancel_pa", [$(this).attr('data-id'), "'" + $(this).attr('data-form') + "'"])
        })
        $('.pa_format').click(function() {
            uni_modal("", "appraisalForms/pa_formats.php", 'mid-large')

        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable();
    })

    function cancel_pa($id, $form) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=cancel_pa",
            method: "POST",
            data: {
                id: $id,
                form: $form
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