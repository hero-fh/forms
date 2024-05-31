<!-- <style>
    td {
        vertical-align: middle;
    }
</style> -->
<?php

require_once('../../../config.php'); ?>

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
            <tr class="bg-gradient-primary text-center">
                <th>#</th>
                <th>Date Created</th>
                <th>IR No</th>
                <th>Issued to</th>
                <th>Status</th>
                <th>Code no</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            if ($_settings->userdata('DEPARTMENT') == 'Human Resource') {
                $ir_da_qry = $conn->query("SELECT * FROM ir_requests where (valid_to_da_name =  " . $_settings->userdata('EMPLOYID') . ") or (appeal_status > 1 and hr_mngr = " . $_settings->userdata('EMPLOYID') . ") ORDER BY `date_created` asc");
            } else {
                $ir_da_qry = $conn->query("SELECT * FROM ir_requests where (hr_status = 1 and sv_status !=0 and sv_name = " . $_settings->userdata('EMPLOYID') . ") or (hr_status = 1 and sv_status = 1 and dh_name = " . $_settings->userdata('EMPLOYID') . ") or (hr_status != 0 and hr_name = " . $_settings->userdata('EMPLOYID') . ") or (da_status != 0 and valid_to_da_name = " . $_settings->userdata('EMPLOYID') . ") or (appeal_status > 2 and od_name = " . $_settings->userdata('EMPLOYID') . ")  ORDER BY `date_created` asc");
            }


            // $ir_da_qry = $conn->query("SELECT * FROM ir_requests where ir_status != 2 ORDER BY `date_created` asc");

            while ($row = $ir_da_qry->fetch_assoc()) :
                $approver_2 = $conn->query("SELECT APPROVER2 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                $approver_1 = $conn->query("SELECT APPROVER1 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                $approver_1 = $approver_1 == 'na' ? $approver_2 : $approver_1;
                $approver_3 = $conn->query("SELECT APPROVER3 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                $c = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;
                $is_valid = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}'")->num_rows;
            ?>
                <tr>
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td class="text-center"><?php echo date("m-d-Y h:ia", strtotime($row['date_created'])) ?></td>
                    <td class="text-center"><?php echo $row['ir_no'] ?></td>

                    <?php
                    $ir_da_qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                    while ($row1 = $ir_da_qry1->fetch_assoc()) :
                        $reqName = $row1['EMPNAME'];
                    ?>
                        <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                    <?php endwhile; ?>

                    <td class="text-center">
                        <?php if ($row['hr_name'] == $_settings->userdata('EMPLOYID')) { ?>
                            <?php if ($row['has_da'] == 0 && ($row['ir_status'] < 2 || $row['ir_status'] == 3)) {
                                $valid_to_da_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_to_da_name']}'")->fetch_array();
                                $valid_appeal_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['valid_appeal_name']}'")->fetch_array();
                                $od_name = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['od_name']}'")->fetch_array();
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
                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 1) : ?>
                                    <span class="badge badge-warning rounded-pill">Appeal validation</span>
                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 2) : ?>
                                    <span class="badge badge-warning rounded-pill">Director appeal approval</span>
                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1 && $row['appeal_status'] == 0) : ?>
                                    <span class="badge badge-warning rounded-pill">IR: For Acknowledgment</span>
                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                    <span class="badge badge-primary rounded-pill">For Validation</span>
                                <?php elseif ($row['ir_status'] == 2) : ?>
                                    <?php if ($is_valid > 0) { ?>
                                        <span class="badge badge-success rounded-pill">Valid</span>
                                    <?php } elseif ($is_valid == 0) { ?>
                                        <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                    <?php } ?>
                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                    Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                    <span class="badge badge-danger rounded-pill">Disapproved</span>
                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 4) : ?>
                                    <span class="badge badge-success rounded-pill">IR: For Acknowledgment</span>
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
                                <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                    <span class="badge badge-secondary rounded-pill">DA: For Supervisor</span>
                                <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                    <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                <?php elseif ($row['da_status'] == 4) : ?>
                                    <span class="badge badge-primary rounded-pill">IR: For Acknowledgment</span>
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


                        <?php } elseif ($row['sv_name'] == $_settings->userdata('EMPLOYID')) { ?>
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
                                    <span class="badge badge-warning rounded-pill">IR: For Acknowledgment</span>
                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                    <span class="badge badge-primary rounded-pill">For Validation</span>
                                <?php elseif ($row['ir_status'] == 2) : ?>
                                    <span class="badge badge-success rounded-pill">Approved</span>
                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                    Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                    <span class="badge badge-danger rounded-pill">Disapproved</span>
                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 4) : ?>
                                    <span class="badge badge-success rounded-pill">IR: For Acknowledgment</span>
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
                                <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                    <span class="badge badge-secondary rounded-pill">DA: For Supervisor</span>
                                <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                    <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                <?php elseif ($row['da_status'] == 4) : ?>
                                    <span class="badge badge-primary rounded-pill">IR: For Acknowledgment</span>
                                <?php elseif ($row['da_status'] == 5) : ?>
                                    <span class="badge badge-success rounded-pill">Approved</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary rounded-pill">--</span>
                                <?php endif; ?>
                            <?php } elseif ($row['has_da'] == 0 && $row['ir_status'] == 2 && $c > 0) { ?>
                                <?php if ($row['da_status'] == 0) : ?>
                                    <span class="badge badge-danger rounded-pill">For DA</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                <?php endif; ?>
                            <?php } else { ?>
                                <?php if ($is_valid > 0) { ?>
                                    <span class="badge badge-success rounded-pill">Served</span>
                                <?php } elseif ($is_valid == 0) { ?>
                                    <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                <?php } ?>
                            <?php } ?>



                        <?php } elseif ($row['dh_name'] == $_settings->userdata('EMPLOYID')) { ?>
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
                                    <span class="badge badge-warning rounded-pill">IR: For Acknowledgment</span>
                                <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                    <span class="badge badge-primary rounded-pill">For Validation</span>
                                <?php elseif ($row['ir_status'] == 2) : ?>
                                    <span class="badge badge-success rounded-pill">Approved</span>
                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                    Invalid by: <?php echo isset($valid_to_da_name[0]) ? $valid_to_da_name[0] : '' ?> <br>
                                    Reason: <?php echo $row['disapprove_remarks'] ?>
                                <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                    <span class="badge badge-danger rounded-pill">Disapproved</span>
                                <?php elseif ($row['ir_status'] == 1 && $row['appeal_status'] == 4) : ?>
                                    <span class="badge badge-success rounded-pill">IR: For Acknowledgment</span>
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
                                <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                    <span class="badge badge-secondary rounded-pill">DA: For Supervisor</span>
                                <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                    <span class="badge badge-secondary rounded-pill">DA: For Department manager</span>
                                <?php elseif ($row['da_status'] == 4) : ?>
                                    <span class="badge badge-primary rounded-pill">IR: For Acknowledgment</span>
                                <?php elseif ($row['da_status'] == 5) : ?>
                                    <span class="badge badge-success rounded-pill">Approved</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary rounded-pill">--</span>
                                <?php endif; ?>
                            <?php } elseif ($row['has_da'] == 0 && $row['ir_status'] == 2 && $c > 0) { ?>
                                <?php if ($row['da_status'] == 0) : ?>
                                    <span class="badge badge-danger rounded-pill">For DA</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary rounded-pill">Cancelled</span>
                                <?php endif; ?>
                            <?php } else { ?>
                                <?php if ($is_valid > 0) { ?>
                                    <span class="badge badge-success rounded-pill">Served</span>
                                <?php } elseif ($is_valid == 0) { ?>
                                    <span class="badge badge-danger rounded-pill">Invalid Dispositions</span>
                                <?php } ?>
                            <?php } ?>


                            <!-- <?php if ($row['dh_status'] == 1) : ?>
                                                <span class="badge badge-primary rounded-pill">Approved</span>
                                            <?php elseif ($row['dh_status'] == 2) : ?>
                                                <span class="badge badge-danger rounded-pill">Disapproved</span>
                                            <?php endif; ?> -->



                        <?php } ?>
                    </td>
                    <td>
                        <div class="text-center">
                            <?php
                            $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '{$row['ir_no']}' ");
                            while ($rows = $options->fetch_assoc()) :
                            ?>
                                <span class="badge badge-success rounded-pill"><?php echo $rows['code_no'] ?></span>

                                <!-- Suspension Date:
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

                                                ?> -->
                            <?php endwhile; ?>
                        </div>
                    </td>
                    <td align="center">
                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            Action
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=incidentreport/approveDA/sign_ir&id=' . md5($row['id']) ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
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
<script>
    $('.table td,.table th').addClass('py-1 px-2 align-middle')
    $('.table').dataTable();
</script>