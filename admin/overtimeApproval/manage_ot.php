<?php
$qry = $conn->query("SELECT * FROM overtime_requests where id = '{$_GET['id']}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        if ($k === 'date_created') {
            $date_created_ot_request = $v;
        } else {
            $$k = $v;
        }
    }
}

$qry1 = $conn->query("SELECT * FROM employee_masterlist where EMPLOYID = '{$requestor_id}'");
if ($qry1->num_rows > 0) {
    foreach ($qry1->fetch_array() as $k1 => $v1) {
        $$k1 = $v1;
    }
}

?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Overtime Request Details : <?php echo $ot_form_no ?></h4>
        <div class="card-tools">
            <?php if ($ot_status == 0) : ?>
                <span class="badge badge-primary rounded-pill"> Pending</span>
            <?php elseif ($ot_status == 1) : ?>
                <span class="badge badge-warning rounded-pill">Partially Approved</span>
            <?php elseif ($ot_status == 2) : ?>
                <span class="badge badge-success rounded-pill"> Approved</span>
            <?php elseif ($ot_status == 3) :
            ?>
                <span class="badge badge-danger rounded-pill"> Disapproved</span>
            <?php else : ?>
                <span class="badge badge-secondary rounded-pill"> Cancelled</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label text-info">PREPARED BY</label>
                    <div><?php echo isset($EMPNAME) ? $EMPNAME : '' ?></div>
                    <?php
                    $dateCreated = new DateTime($date_created_ot_request);

                    $newdateCreated = $dateCreated->format('m-d-Y h:i:s a');
                    ?>
                    <div><?php echo isset($date_created) ? $newdateCreated : '' ?></div>
                </div>
            </div>
            <HR>

            <div class="row">
                <div class="col-md-4">
                    <label class="control-label text-info">DEPARTMENT</label>
                    <div><?php echo isset($department) ? $department : '' ?></div>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-info"> PRODUCTLINE</label>
                    <div><?php echo isset($productline) ? $productline : '' ?></div>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-info">PAYROLL CUT-OFF</label>
                    <?php

                    $date = new DateTime($date_from);
                    $date0 = new DateTime($date_to);

                    $newDateformat = $date->format('m-d-Y');
                    $newDateformat0 = $date0->format('m-d-Y');
                    ?>
                    <div><?php echo isset($ot_form_no) ? $newDateformat . " To " . $newDateformat0 : '' ?></div>
                </div>
            </div>
            <hr>
            <fieldset>
                <p class="text-center">The following employees have expressed their request and willingness to render overtime work on the date and time as specified below.</p>
                <!-- Table to display the added inputs -->
                <table class="table table-striped table-bordered" id="dataTable">
                    <thead>
                        <tr class="text-light bg-navy">
                            <th class="text-center py-1 px-2">EMP. NO.</th>
                            <th class="text-center py-1 px-2">EMP. NAME</th>
                            <th class="text-center py-1 px-2">WORK SHIFT</th>
                            <th class="text-center py-1 px-2">DATE REQUESTED</th>
                            <th class="text-center py-1 px-2">OT DATE TIME</th>
                            <th class="text-center py-1 px-2">REASON</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM overtime_items WHERE ot_form_code = '{$ot_form_no}'");
                        while ($row = $qry->fetch_assoc()) :
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $row['emp_num'] ?></td>
                                <td class="text-center"><?php echo $row['emp_name'] ?></td>
                                <td class="text-center"><?php echo $row['work_shift'] ?></td>
                                <?php
                                $date1 = new DateTime($row['ot_date_from']);
                                // $date2 = new DateTime($row['ot_date_to']);

                                $newDateformat1 = $date1->format('m-d-Y');
                                // $newDateformat2 = $date2->format('m-d-Y');
                                ?>

                                <td class="text-center"><?php echo $newDateformat1 ?></td>
                                <td class="text-center"><?php echo $row['ot_time_from'] . " To " . $row['ot_time_to'] ?></td>
                                <td class="text-center"><?php echo $row['ot_reason'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </fieldset>
            <HR>
            <form action="" id="ot-form">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                <input type="hidden" name="appType" value="<?php echo $_settings->userdata('EMPPOSITION'); ?>">
                <input type="hidden" name="approverNum" value="<?php echo $_settings->userdata('EMPLOYID'); ?>">
                <div class="row">
                    <!-- DH section-->
                    <?php
                    if (($_settings->userdata('EMPPOSITION') == 3 || $_settings->userdata('EMPPOSITION') == 4 || $_settings->userdata('EMPLOYID') == '600') && $dh_status == 0) {
                    ?>
                        <div class="col-md-12">
                            <label class="control-label text-info">REMARKS</label>
                            <textarea class="form-control form-control rounded-0" rows="3" name="dh_remarks"></textarea>
                            <br>
                            <div>
                                <button class="btn btn-flat btn btn-outline-success approve_data" type="submit" form="ot-form" name="action" value="approve" id="approve"> <i class="fas fa-thumbs-up"></i> Approved</button>
                                <button class="btn btn-flat btn btn-outline-danger disapprove_data" type="submit" form="ot-form" name="action" value="disapprove" id="disapprove"> <i class="fas fa-thumbs-down"></i> Disapproved</button>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- OP DIR section -->
                    <?php
                    if ($_settings->userdata('EMPPOSITION') == 5 && $od_status == 0) {
                    ?>
                        <div class="col-md-12">
                            <label class="control-label text-info">REMARKS</label>
                            <textarea class="form-control form-control rounded-0" rows="3" name="od_remarks"></textarea>
                            <br>
                            <div>
                                <button class="btn btn-flat btn btn-outline-success approve_data" type="submit" form="ot-form" name="action" value="approve" id="approve"> <i class="fas fa-thumbs-up"></i> Approved</button>
                                <button class="btn btn-flat btn btn-outline-danger disapprove_data" type="submit" form="ot-form" name="action" value="disapprove" id="disapprove"> <i class="fas fa-thumbs-down"></i> Disapproved</button>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                </div>
        </div>
        </form>
        </br>
        <div class="card-footer py-1 text-center">
            <br>
            <p class="text-center"><i class="fa fa-info-circle fa-lg text-primary" aria-hidden="true">&nbsp;&nbsp;</i>Work Shift Legend: <b><i>A</i></b> - Morning Shift (7:00AM - 7:00PM) &nbsp; <b><i>C</i></b> - Night Shift (7:00PM - 7:00AM) &nbsp; <b><i>N</i></b> - Normal Shift (7:00AM - 4:00PM)</p>

            <!-- <button class="btn btn-flat btn-success" type="button" id="print">Print</button> -->
            <?php
            if ($ot_status == 2) {
            ?>
                <a class="btn btn-flat btn-primary" href="<?php echo base_url . 'pdf_pr.php?&id=' . $ot_form_no ?>" target="_blank">PDF</a>
            <?php
            }
            ?>
            <!-- <a class="btn btn-flat btn-primary" href="<?php echo base_url . '/admin?page=purchase_order' ?>"><i class="fas fa-file-download"></i> PDF</a> -->
        </div>
    </div>

    <script>
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
                url: _base_url_ + "classes/Master.php?f=sign_ot",
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
                        location.replace(_base_url_ + "admin/?page=overtimeForm/view_ot&id=" + resp.id);
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        end_loader()
                    } else {
                        alert_toast(" Empty employee list !", 'error');
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

        $(function() {
            $('#print').click(function() {
                start_loader()
                var _el = $('<div>')
                var _head = $('head').clone()
                _head.find('title').text("Purchase Order Details - Print View")
                var p = $('#print_out').clone()
                p.find('tr.text-light').removeClass("text-light bg-navy")
                _el.append(_head)
                _el.append('<div class="d-flex justify-content-center">' +
                    '<div class="col-1 text-right">' +
                    '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />' +
                    '</div>' +
                    '<div class="col-10">' +
                    '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>' +
                    '<h4 class="text-center">Purchase Order</h4>' +
                    '</div>' +
                    '<div class="col-1 text-right">' +
                    '</div>' +
                    '</div><hr/>')
                _el.append(p.html())
                var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes")
                nw.document.write(_el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()
                        end_loader()
                    }, 200);
                }, 500);
            })
        })
    </script>