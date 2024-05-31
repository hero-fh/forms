<?php

require_once('../../../config.php');

// $resultType = $_POST['resultType'];
// if ($resultType == 2) {
//     $date_range = $_POST['date_range'];
//     $status = $_POST['status'];

//     $dates = explode(' - ', $date_range);
//     $from_date = date('Y-m-d', strtotime($dates[0]));
//     $to_date = date('Y-m-d', strtotime($dates[1]));
// } else if ($resultType == 3) {
//     $ir_no = $_POST['ir_no'];
// }

$is_operator = $conn->query("SELECT * FROM ir_operator where emp_no = '{$_settings->userdata('EMPLOYID')}' and status = 1  and to_handle = 1")->num_rows;
$is_quality = $conn->query("SELECT * FROM ir_operator where emp_no = '{$_settings->userdata('EMPLOYID')}' and status = 1  and to_handle = 2")->num_rows;


?>
<style>
    #uni_modal .modal-header,
    #uni_modal .modal-footer {
        display: none
    }
</style>

<link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Theme style -->
<div class="card card-outline card-primary d-none">
    <div class="col-12 col-sm-12">

        <div class="card-body">
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="card-body overflow-auto">
                        <table id="export" class="table display export table-bordered table-striped">

                            <thead class="text-center">
                                <tr class="bg-gradient-primary">
                                    <th>No.</th>
                                    <th>IR #</th>
                                    <th>DATE CREATED</th>
                                    <th>EMPLOYEE NO</th>
                                    <th>NAME</th>
                                    <th>TYPE</th>
                                    <th>TYPE & ITEM #</th>
                                    <th>NATURE OF OFFENSE/VIOLATION</th>
                                    <th>NO. OF OFFENSE</th>
                                    <th>TYPE OF DA</th>
                                    <th>DATE SUSPENSION</th>
                                    <th>DATE COMMITED</th>
                                    <th>CLEANSING PERIOD</th>
                                    <th>PRODUCT LINE</th>
                                    <th>STATION</th>
                                    <th>DEPARTMENT</th>
                                    <th>IMMEDIATE SUPERIOR</th>
                                    <th>DEPARTMENT HEAD/MANAGER</th>
                                    <th>POSITION</th>
                                    <th>SHIFT</th>
                                    <th>TEAM</th>
                                    <th>STATUS</th>
                                    <th>REMARKS</th>
                                    <th>CATEGORY</th>
                                    <th>MONTH</th>
                                    <th>YEAR</th>
                                    <th>DATE OF LETTER OF EXPLANTION</th>
                                    <th>DATE OF ASSESSMENT</th>
                                    <th>DATE OF HR VALIDATION</th>
                                    <th>DATE OF IR APPROVAL</th>
                                    <th>DATE OF DA ISSUANCE</th>
                                    <th>HR MANAGER ACKNOWLEDGEMENT DATE</th>
                                    <th>IMMEDIATE SUPERIOR ACKNOWLEDGEMENT DATE</th>
                                    <th>MANAGER ACKNOWLEDGEMENT DATE</th>
                                    <th>PERSON INVOLVED ACKNOWLEDGEMENT DATE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;

                                $sql = $conn->query("SELECT ir.ir_no,il.ir_no,ir.date_created,ir.emp_no,ir.emp_name,ir.quality_violation,il.code_no,il.violation,il.offense_no,il.da_type,il.date_of_suspension,ir.when,il.da_type,
                                ir.productline,ir.station,ir.department,ir.position,ir.shift,ir.is_inactive,ir.ir_status,ir.hr_status,ir.sv_status,ir.why1,ir.da_status,ir.has_da,ir.disapprove_remarks,
                                ir.hr_name,ir.sv_name,il.date_of_LOE,ir.sv_sign_date,ir.dh_sign_date,ir.valid_to_da_date,ir.hr_mngr_sign_date,ir.da_requested_date,ir.dh_da_sign_date,ir.dm_sign_date,ir.acknowledge_date FROM ir_requests ir inner join ir_list il on ir.ir_no  = il.ir_no order by ir.id asc");

                                // $qry = $conn->query("SELECT * FROM ir_requests where quality_violation = 3 ORDER BY `date_created` desc");

                                while ($row = $sql->fetch_assoc()) :

                                    $approver_2 = $conn->query("SELECT APPROVER2 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                    $approver_1 = $conn->query("SELECT APPROVER1 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                    $approver_1 = $approver_1 == 'na' ? $approver_2 : $approver_1;
                                    $approver_3 = $conn->query("SELECT APPROVER3 from employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'")->fetch_array()[0];
                                    $c = $conn->query("SELECT valid FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;
                                    $is_valid = $conn->query("SELECT valid FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}'")->num_rows;

                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo $row['ir_no'] ?></td>
                                        <td class="text-center"><?php echo date("m-d-Y", strtotime($row['date_created'])) ?></td>
                                        <td class="text-center"><?php echo $row['emp_no'] ?></td>
                                        <td class="text-center"><?php echo $row['emp_name'] ?></td>
                                        <td>
                                            <?php switch ($row['quality_violation']) {
                                                case 1:
                                                    echo 'A';
                                                    break;
                                                case 2:
                                                    echo 'Q';
                                                    break;
                                            } ?>
                                        </td>
                                        <td class="text-center"><?php echo $row['code_no'] ?></td>
                                        <td class="text-center"><?php echo $row['violation'] ?></td>
                                        <td class="text-center"><?php echo $row['offense_no'] ?></td>
                                        <td class="text-center"><?php echo $row['da_type'] ?></td>
                                        <td>
                                            <?php
                                            $dateString = $row['date_of_suspension'];
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
                                        </td>
                                        <td class="text-center"><?php echo date("m-d-Y", strtotime($row['when'])) ?></td>
                                        <td>
                                            <?php
                                            $originalDate = $row['when'];
                                            $daType = $row['da_type'];

                                            if ($daType == 1) {
                                                $adjustedDate = strtotime($originalDate . "+6 months");
                                            } elseif ($daType == 2) {
                                                $adjustedDate = strtotime($originalDate . "+9 months");
                                            } elseif ($daType == 3) {
                                                $adjustedDate = strtotime($originalDate . "+12 months");
                                            } elseif ($daType == 4) {
                                                $adjustedDate = strtotime($originalDate . "+18 months");
                                            } else {
                                                // Handle other cases or set a default date
                                                $adjustedDate = strtotime($originalDate);
                                            }
                                            if ($daType < 5) {
                                                $newDate = date("m-d-Y", $adjustedDate);
                                                echo $newDate;
                                            } else {
                                                echo '--';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo $row['productline'] ?></td>
                                        <td class="text-center"><?php echo $row['station'] ?></td>
                                        <td class="text-center"><?php echo $row['department'] ?></td>
                                        <?php
                                        $qry1 = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$row['emp_no']}'");
                                        $svname = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = (SELECT CASE WHEN APPROVER1 = 'na' THEN APPROVER2 ELSE APPROVER1 END AS Approver from employee_masterlist WHERE (EMPLOYID = '{$row['emp_no']}'))")->fetch_array();
                                        while ($row1 = $qry1->fetch_assoc()) :
                                            $reqName = $row1['EMPNAME'];
                                        ?>
                                            <td class="text-center"><?php echo $row1['EMPNAME'] ?></td>
                                        <?php endwhile; ?>
                                        <td class="text-center"><?php echo isset($svname[0]) ? $svname[0] : '' ?></td>

                                        <td class="text-center"><?php echo $row['position'] ?></td>
                                        <td class="text-center"><?php echo $row['shift'] ?></td>
                                        <td class="text-center"><?php echo $row['shift'] ?></td>
                                        <?php if ($row['ir_status'] != 2 || $c == 0) : ?>
                                            <td>
                                                <?php if ($row['is_inactive'] == 1) { ?>
                                                    Inactive
                                                <?php } else { ?>
                                                    <?php
                                                    $dpr = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['hr_name']}'")->fetch_array();
                                                    if ($row['ir_status'] == 0 && $row['hr_status'] == 2) :
                                                    ?>
                                                        Invalid
                                                    <?php elseif ($row['ir_status'] == 0 && $row['hr_status'] == 0) : ?>
                                                        Pending
                                                    <?php elseif ($row['ir_status'] == 1 && $row['why1'] == '' && $row['sv_status'] == 0) : ?>
                                                        For Assessment
                                                    <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 0 && $row['why1'] != '') : ?>
                                                        For Assessment
                                                    <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1) : ?>
                                                        For Assessment
                                                    <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                                        For Assessment
                                                    <?php elseif ($row['ir_status'] == 2) : ?>
                                                        <?php if ($is_valid > 0) { ?>
                                                            For monitoring (for TK purposes)
                                                        <?php } elseif ($is_valid == 0) { ?>
                                                            Invalid
                                                        <?php } ?>
                                                    <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                        Invalid
                                                    <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                        Invalid
                                                    <?php else : ?>
                                                        Invalid
                                                    <?php endif; ?>
                                                <?php } ?>
                                            </td>

                                        <?php elseif ($row['ir_status'] == 2 && $c > 0) : ?>
                                            <td class="text-center">
                                                <?php if ($row['is_inactive'] == 1) { ?>
                                                    Inactive
                                                <?php } else { ?>
                                                    <?php if ($row['has_da'] == 0) : ?>
                                                        For DA
                                                    <?php elseif ($row['has_da'] == 1) : ?>
                                                        <?php if ($row['da_status'] == 1) : ?>
                                                            Pending
                                                        <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                                            Pending
                                                        <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                                            Pending
                                                        <?php elseif ($row['da_status'] == 4) : ?>
                                                            Pending
                                                        <?php elseif ($row['da_status'] == 5) : ?>
                                                            Served
                                                        <?php else : ?>
                                                            Invalid
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php } ?>
                                            </td>
                                        <?php endif; ?>
                                        <td class="text-center">
                                            <?php if (($row['ir_status'] == 0 && $row['hr_status'] == 2) || ($row['ir_status'] == 3 && $row['da_status'] == 2) || ($row['ir_status'] == 4)) :
                                                echo $row['disapprove_remarks'];
                                            endif; ?>
                                        </td>
                                        <?php if ($row['ir_status'] != 2) : ?>
                                            <td>
                                                <?php if ($row['is_inactive'] == 1) { ?>
                                                <?php } else { ?>
                                                    <?php
                                                    $dpr = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$row['hr_name']}'")->fetch_array();
                                                    if ($row['ir_status'] == 0 && $row['hr_status'] == 2) :
                                                    ?>
                                                        IR
                                                    <?php elseif ($row['ir_status'] == 0 && $row['hr_status'] == 0) : ?>
                                                        IR
                                                    <?php elseif ($row['ir_status'] == 1 && $row['why1'] == '' && $row['sv_status'] == 0) : ?>
                                                        IR
                                                    <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 0 && $row['why1'] != '') : ?>
                                                        IR
                                                    <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 1) : ?>
                                                        IR
                                                    <?php elseif ($row['ir_status'] == 1 && $row['sv_status'] == 1  && $row['da_status'] == 0) : ?>
                                                        IR
                                                    <?php elseif ($row['ir_status'] == 2) : ?>
                                                        <?php if ($is_valid > 0) { ?>
                                                            DA
                                                        <?php } elseif ($is_valid == 0) { ?>
                                                            IR
                                                        <?php } ?>
                                                    <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                        IR

                                                    <?php elseif ($row['ir_status'] == 3 && $row['da_status'] == 2) : ?>
                                                        IR
                                                    <?php else : ?>
                                                        IR<br>

                                                    <?php endif; ?>
                                                <?php } ?>
                                            </td>
                                        <?php elseif ($row['ir_status'] == 2) : ?>
                                            <td class="text-center">
                                                <?php if ($row['is_inactive'] == 1) { ?>
                                                <?php } else { ?>
                                                    <?php if ($row['has_da'] == 0) : ?>
                                                        IR
                                                    <?php elseif ($row['has_da'] == 1) : ?>
                                                        <?php if ($row['da_status'] == 1) : ?>
                                                            DA
                                                        <?php elseif ($row['da_status'] == 2 && $approver_1 != $approver_3) : ?>
                                                            DA
                                                        <?php elseif ($row['da_status'] == 3 || ($row['da_status'] == 2 && $approver_1 == $approver_3)) : ?>
                                                            DA
                                                        <?php elseif ($row['da_status'] == 4) : ?>
                                                            DA
                                                        <?php elseif ($row['da_status'] == 5) : ?>
                                                            DA
                                                        <?php else : ?>
                                                            IR
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php } ?>
                                            </td>
                                        <?php endif; ?>

                                        <!-- $ir_appvl_date = isset($row['dh_sign_date']) ? date("Y-m-d", strtotime($row['dh_sign_date'])) : '';
        $date_da_request = isset($row['da_requested_date']) ? date("Y-m-d", strtotime($row['da_requested_date'])) : ''; -->
                                        <td><?php echo date("M", strtotime($row['date_created'])); ?></td>
                                        <td><?php echo date("Y", strtotime($row['date_created'])); ?></td>
                                        <td><?php echo isset($row['date_of_LOE']) ? date("Y-m-d", strtotime($row['date_of_LOE'])) : '' ?></td>
                                        <td><?php echo isset($row['sv_sign_date']) ? date("Y-m-d", strtotime($row['sv_sign_date'])) : '' ?></td>
                                        <td><?php echo isset($row['valid_to_da_date']) ? date("Y-m-d", strtotime($row['valid_to_da_date'])) : '' ?></td>
                                        <td><?php echo isset($row['dh_sign_date']) ? date("Y-m-d", strtotime($row['dh_sign_date'])) : '' ?></td>
                                        <td><?php echo isset($row['da_requested_date']) ? date("Y-m-d", strtotime($row['da_requested_date'])) : '' ?></td>
                                        <td><?php echo isset($row['hr_mngr_sign_date']) ? date("Y-m-d", strtotime($row['hr_mngr_sign_date'])) : '' ?></td>
                                        <td><?php echo isset($row['dh_da_sign_date']) ? date("Y-m-d", strtotime($row['dh_da_sign_date'])) : '' ?></td>
                                        <td><?php echo isset($row['dm_sign_date']) ? date("Y-m-d", strtotime($row['dm_sign_date'])) : '' ?></td>
                                        <td><?php echo isset($row['acknowledge_date']) ? date("Y-m-d", strtotime($row['acknowledge_date'])) : '' ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url ?>plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script>
    var currentDate = new Date();

    var currentMonth = currentDate.getMonth() + 1; // Adding 1 to adjust for zero-based month numbering
    var currentDay = currentDate.getDate();
    var currentYear = currentDate.getFullYear();

    console.log("Current date: " + currentMonth + "/" + currentDay + "/" + currentYear);

    var pageTitle = document.title;

    // $("#example13").DataTable({
    //     "responsive": true,
    //     "lengthChange": false,
    //     "autoWidth": false,
    //     "buttons": ["excel"]
    // });
    $('#export').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        "buttons": [{
            extend: 'excel',
            className: 'custom-button',
            text: 'Export',
            title: '',
            filename: function() {
                return pageTitle + '(' + currentMonth + '-' + currentDay + '-' + currentYear + ')';
            }
            // title: false
        }]
    }).buttons().container().appendTo('#export_wrapper');
    $('#export_wrapper .dt-buttons button:eq(0)').trigger('click');
    setTimeout(function() {
        location.reload()
    }, 2300)
</script>