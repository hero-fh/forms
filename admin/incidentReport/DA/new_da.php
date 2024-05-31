<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `ir_requests` where md5(id) = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
// $control_no = $conn->query("SELECT * FROM ir_requests")->num_rows;
$noted_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = $sv_name")->fetch_array()[0];
// $appr_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = $dh_name")->fetch_array()[0];
$e_id = $_settings->userdata('EMPLOYID');
?>
<style>
    td {
        vertical-align: middle;
    }

    input.form-control:read-only {
        background-color: #fff;
    }

    select.custom-select:disabled {
        background-color: #fff;

    }

    textarea.form-control:read-only {
        background-color: #fff;
    }

    #uni_modal .modal-footer,
    .modal-footer {
        display: none;
    }
</style>
<noscript>
    <style>
        .page-break {
            page-break-before: always;
        }

        select.custom-select:disabled {
            background-color: #fff;
        }

        input.form-control:read-only {
            background-color: #fff;
        }

        textarea.form-control:read-only {
            background-color: #fff;
        }
    </style>
</noscript>
<div class="card card-outline card-primary">
    <!-- <div class="card-header">
        <h4 class="card-title card-primary">CREATE NEW INCIDENT REPORT</h4>
    </div> -->
    <div class="card-body">
        <form action="" id="issue_da">
            <input readonly type="hidden" name="id" value="<?php echo isset($id)  ? $id : '' ?>">
            <input readonly type="hidden" name="requestor_id" value="<?php echo $_settings->userdata('EMPLOYID') ?>">
            <div class="container-fluid">
                <div class="printable">
                    <div class="row" style="margin-left: 1%;">
                        <p class="MsoNormal" align="left" style="margin: 0cm; text-indent: 0cm; line-height: 107%; background: rgb(153, 51, 0);"><b><span style="font-size:24.0pt;line-height:107%;color:white">T
                                    E L F O R D</span></b>
                            <o:p></o:p>
                        </p>
                    </div>
                    <p class="MsoNormal" align="left"><b><span style="margin-left: 1%;font-size:11.5pt;line-height:107%">TELFORD
                                SVC. PHILS., INC.</span>
                        </b>
                        <o:p></o:p>
                    </p>
                    <br>
                    <h4 class="text-center"><strong>NOTICE OF DISCIPLINARY ACTION</strong></h4>

                    <div class="form-group justify-content-end row">
                        <label class=" col-form-label text-info">IR No.</label>
                        <div class="col-sm-3">
                            <input readonly type="text" class="form-control rounded-0 text-inline" id="ir_no" name="ir_no" value="<?php echo isset($ir_no) ? $ir_no :  '' ?>">
                        </div>
                    </div>
                    <div class="form-group justify-content-end row">
                        <label class=" col-form-label text-info">Date</label>
                        <div class="col-sm-3">
                            <input readonly type="text" class="form-control  rounded-0" class="form-control  rounded-0" value="<?php echo isset($date_created)  ? date('m/d/Y', strtotime($date_created)) : date('Y-m-d') ?>">

                        </div>
                    </div>
                    <h5>Details of person involved:</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-info">Employee no</label>
                            <input readonly required type="number" name="emp_no" class="form-control  rounded-0" value="<?php echo isset($emp_no)  ? $emp_no : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">Shift/Team</label>
                            <input required type="text" name="shift" readonly id="shift" class="form-control  rounded-0" value="<?php echo isset($shift) ? $shift : '' ?>">
                        </div>

                        <!-- <div class="col-md-2">
                        <label class="control-label text-info">PCN No</label>
                        <input readonly type="text"  name="pcn_no"  class="form-control  rounded-0" value="<?php echo isset($pcn_no)  ? $pcn_no : '' ?>" required>
                    </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-info">Employee name</label>
                            <input readonly required type="text" name="emp_name" class="form-control  rounded-0" value="<?php echo isset($emp_name)  ? $emp_name : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">Position</label>
                            <input readonly required type="text" name="position" class="form-control  rounded-0" value="<?php echo isset($position)  ? $position : '' ?>">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-info">Department</label>
                            <input required readonly type="text" name="department" id="department" class="form-control  rounded-0" value="<?php echo isset($department)  ? $department : '' ?>">

                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">Station/PL</label>
                            <input required readonly type="text" name="station" id="station" class="form-control  rounded-0" value="<?php echo isset($station)  ? $station : '' ?>">
                        </div>
                    </div>
                    <br>
                    <h5><i>Cleansing period</i></h5>
                    <br>
                    <div class="row text-center">
                        <div class="col-2">
                            <div class="callout callout-default">
                                <b> Verbal Warning<br><i>6 Months </i></b>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="callout callout-success">
                                <b> Written Warning<br><i>9 Months </i></b>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="callout callout-info">
                                <b> 3 Days Suspension<br><i>12 Months </i></b>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="callout callout-warning">
                                <b> 7 Days Suspension<br><i>18 Months </i></b>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="callout callout-danger">
                                <b> Dismissal<br><i> -- </i> </b>
                            </div>
                        </div>
                    </div>
                    </br>
                    <h5><i>Violations based on company code of conduct</i></h5>
                    <br>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center py-1 px-2">Code no.</th>
                                <th class="text-center py-1 px-2">Violation/Nature of offenses</th>
                                <th class="text-center py-1 px-2">D.A</th>
                                <th class="text-center py-1 px-2">Date commited</th>
                                <th class="text-center py-1 px-2">No. of offense</th>
                                <th class="text-center py-1 px-2">Schedule of suspension</th>
                                <th class="text-center py-1 px-2">Date cleansed</th>

                            </tr>
                        </thead>
                        <?php if ($appeal_status != 4) { ?>
                            <tbody>
                                <?php
                                $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '$ir_no' and valid = 1");
                                while ($row = $options->fetch_assoc()) :
                                ?>
                                    <tr class="text-center">
                                        <td><?php echo $row['code_no'] ?></td>
                                        <td><?php echo $row['violation'] ?></td>
                                        <td class="text-center">
                                            <?php if ($row['da_type'] == 1) : ?>
                                                <span class="badge badge-success rounded-pill">Verbal Warning</span>
                                            <?php elseif ($row['da_type'] == 2) : ?>
                                                <span class="badge badge-primary rounded-pill">Written Warning</span>
                                            <?php elseif ($row['da_type'] == 3) : ?>
                                                <span class="badge badge-secondary rounded-pill">3 Days Suspension</span>
                                            <?php elseif ($row['da_type'] == 4) : ?>
                                                <span class="badge badge-warning rounded-pill">7 Days Suspension</span>
                                            <?php elseif ($row['da_type'] == 5) : ?>
                                                <span class="badge badge-danger rounded-pill">Dismissal</span>
                                            <?php elseif ($row['da_type'] == 6) : ?>
                                                <span class="badge badge-danger rounded-pill">14 Days Suspension</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $dateString = $row['date_commited'];
                                            $dateArray = explode(' + ', $dateString);

                                            foreach ($dateArray as $key => $date) {
                                                $trimmedDate = trim($date);
                                                $timestamp = strtotime($trimmedDate);

                                                if ($timestamp === false) {
                                                    echo "Error parsing date: $trimmedDate <br>";
                                                } else {
                                                    $dateTime = new DateTime();
                                                    $dateTime->setTimestamp($timestamp);

                                                    $dayOfWeek = $dateTime->format('D');
                                                    echo "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)<br>";
                                                }
                                            }

                                            ?>
                                        </td>
                                        <td><?php echo $row['offense_no'] ?></td>

                                        <?php if ($row['da_type'] == 3 || $row['da_type'] == 4 || $row['da_type'] == 6) : ?>
                                            <td class="text-center">
                                                <?php
                                                $dateArray = explode(' + ', trim($row['date_of_suspension']));

                                                foreach ($dateArray as $key => $date) {
                                                    $dateTime = DateTime::createFromFormat('m/d/Y', $date);
                                                    $dayOfWeek = $dateTime->format('D');

                                                    echo "Day" . ($key + 1) . " = $date ($dayOfWeek)<br>";
                                                }

                                                ?>
                                            </td>
                                        <?php else : ?>
                                            <td>
                                                <span>--</span>
                                            </td>
                                        <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $originalDate = $row['date_commited'];
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
                                        <!-- <td class="dont_">
                                        <a class="btn btn-sm btn-block edt" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fas fa-pencil-alt"></span></a>
                                    </td> -->
                                    </tr>

                                <?php endwhile; ?>
                                <?php
                                $totalSum = $conn->query("SELECT sum(days_no) FROM `ir_list` WHERE `ir_no` = '$ir_no'and (da_type = 3 or da_type = 4)")->fetch_array()[0];
                                ?>
                                <tr>
                                    <td colspan="6" class="text-right">TOTAL NO. OF DAYS(if suspension):</td>
                                    <td>
                                        <input readonly type="text" class="form-control text-center form-control-border" value="<?php echo isset($totalSum) ? $totalSum : 0 . ' Day/s' ?>">
                                    </td>
                                </tr>

                            </tbody>
                        <?php } elseif ($appeal_status == 4) { ?>
                            <tbody>
                                <?php
                                $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '$ir_no' and valid = 1");
                                while ($row = $options->fetch_assoc()) :
                                ?>
                                    <tr class="text-center">
                                        <td><?php echo $row['code_no'] ?></td>
                                        <td><?php echo $row['violation'] ?></td>
                                        <td class="text-center">
                                            <?php if ($row['da_type'] == 1) : ?>
                                                <span class="badge badge-success rounded-pill">Verbal Warning</span>
                                            <?php elseif ($row['da_type'] == 2) : ?>
                                                <span class="badge badge-primary rounded-pill">Written Warning</span>
                                            <?php elseif ($row['da_type'] == 3) : ?>
                                                <span class="badge badge-secondary rounded-pill">3 Days Suspension</span>
                                            <?php elseif ($row['da_type'] == 4) : ?>
                                                <span class="badge badge-warning rounded-pill">7 Days Suspension</span>
                                            <?php elseif ($row['da_type'] == 5) : ?>
                                                <span class="badge badge-danger rounded-pill">Dismissal</span>
                                            <?php elseif ($row['da_type'] == 6) : ?>
                                                <span class="badge badge-danger rounded-pill">14 Days Suspension</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $dateString = $row['date_commited'];
                                            $dateArray = explode(' + ', $dateString);

                                            foreach ($dateArray as $key => $date) {
                                                $trimmedDate = trim($date);
                                                $timestamp = strtotime($trimmedDate);

                                                if ($timestamp === false) {
                                                    echo "Error parsing date: $trimmedDate <br>";
                                                } else {
                                                    $dateTime = new DateTime();
                                                    $dateTime->setTimestamp($timestamp);

                                                    $dayOfWeek = $dateTime->format('D');
                                                    echo "Day" . ($key + 1) . " = $trimmedDate ($dayOfWeek)<br>";
                                                }
                                            }

                                            ?>
                                        </td>
                                        <td><?php echo $row['offense_no'] ?></td>

                                        <?php if ($row['da_type'] == 3 || $row['da_type'] == 4 || $row['da_type'] == 6) : ?>
                                            <td class="text-center">
                                                <?php
                                                $dateArray = explode(' + ', trim($row['appeal_date']));

                                                foreach ($dateArray as $key => $date) {
                                                    $dateTime = DateTime::createFromFormat('m/d/Y', $date);
                                                    $dayOfWeek = $dateTime->format('D');

                                                    echo "Day" . ($key + 1) . " = $date ($dayOfWeek)<br>";
                                                }

                                                ?>
                                            </td>
                                        <?php else : ?>
                                            <td>
                                                <span>--</span>
                                            </td>
                                        <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $originalDate = $row['date_commited'];
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
                                        <!-- <td class="dont_">
                                        <a class="btn btn-sm btn-block edt" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fas fa-pencil-alt"></span></a>
                                    </td> -->
                                    </tr>

                                <?php endwhile; ?>
                                <?php
                                $totalSum = $conn->query("SELECT sum(appeal_days) FROM `ir_list` WHERE `ir_no` = '$ir_no'and (da_type = 3 or da_type = 4)")->fetch_array()[0];
                                ?>
                                <tr>
                                    <td colspan="6" class="text-right">TOTAL NO. OF DAYS(if suspension):</td>
                                    <td>
                                        <input readonly type="text" class="form-control text-center form-control-border" value="<?php echo isset($totalSum) ? $totalSum : 0 . ' Day/s' ?>">
                                    </td>
                                </tr>

                            </tbody>
                        <?php } ?>

                    </table>
                    <br>

                    <br>
                    <h4 class="text-center"><strong>COMMITMENT LETTER</strong></h4>

                    <p align="justify">
                        I, <u><i><?php echo $emp_name ?></i></u>, understand the seriousness of my actions and
                        the potential consequences that may arise from such violations. I want to assure you that I take
                        full responsibility for my behavior and am committed to rectifying the situation.
                        Moving forward, I pledge to adhere to all rules, policies, and guidelines set forth by the company
                        (Telford Svc. Phils. Inc). <br>
                        <small>
                            <i>
                                (Ako <?php echo $emp_name ?>, ay nauunawaan ang kahalagahan ng aking mga kilos at
                                ang posibleng mga kahihinatnan na maaaring maging bunga ng aking mga paglabag. Nais kong tiyakin
                                sa inyo na ako'y lubos na nagmamalasakit sa aking pag-uugali at determinadong ituwid ang sitwasyon.
                                Sa paglipas ng panahon, ako'y sumusumpang sumunod sa lahat ng mga alituntunin, patakaran,
                                at mga patnubay na itinakda ng kumpanya (Telford Svc. Phils. Inc).
                                )
                            </i>
                        </small>
                    </p>
                    <br>
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-info">Others:</label>
                            <textarea type="text" readonly name="da_others" class="form-control rounded-0"><?php echo isset($da_others)  ? $da_others : '' ?></textarea>
                        </div>
                    </div> -->
                    <br>

                </div>

        </form>
    </div>
    <div class="row justify-content-end">
        <?php if ($has_da == 0) { ?>
            <div class="col-6">
                <button class="btn btn-block  btn-primary" type="submit" form="issue_da">Issue</button>
                <!-- <button class="btn btn-block  btn-primary">Issue</button> -->
            </div>
        <?php } ?>
        <div class="col-6">
            <!-- <button class="btn btn-block  btn-dark" data-dismiss="modal">Cancel</button> -->
            <a class="btn btn-block  btn-dark" href="<?php echo base_url . 'admin?page=incidentreport/DA' ?>">Back</a>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.did').hide();

        $('#offense_no').change(function() {
            console.log($('#offense_no').val());
            if ($('#offense_no').val() == 3 || $('#offense_no').val() == 4) {
                $('#suspension').attr('required', true)
                $('#suspension').removeAttr('readonly')
            } else {
                $('#suspension').attr('readonly', true)
                $('#suspension').removeAttr('required')
            }
        });

        $('#print').click(function() {
            $('.dont_').hide();
            $('.did').show();
            var head = $('head').clone();
            var rep = $('.printable').clone();
            var ns = $('noscript').clone().html();
            start_loader()
            rep.find('.content').after('<div class="page-break"></div>');

            rep.prepend(ns)
            rep.prepend(head)
            rep.find('#print_header').show()
            var nw = window.document.open('', '_blank', 'width=900,height=600')
            nw.document.write(rep.html())
            nw.document.close()
            setTimeout(function() {
                nw.print()
                setTimeout(function() {
                    $('.dont_').show();
                    $('.did').hide();
                    nw.close()
                    end_loader()
                }, 200)
            }, 300)
        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle text-center')
    });
    $('.edt').click(function() {
        uni_modal("Edit Incident", "incidentreport/createNewIRDA/add_da.php?id=" + $(this).attr('data-id'), 'mid-large');
    })

    $('.remove-option').click(function() {
        $(this).closest('.item').remove()
    })
    // Add Option button click event
    $('#add_option').click(function() {

        // Clone the template option and convert it into a jQuery object
        var item = $($('#option-clone').html()).clone();

        // Append the modified item to the option list
        $('#option-list').append(item);

        // Attach event handlers to the cloned item

        item.find('.remove-option').click(function() {
            $(this).closest('.item').remove();

        });
    });
    var messageType = 1;

    // $(window).on("beforeunload", function(event) {
    //     // Show a warning message
    //     if (messageType == 1) {
    //         return "Are you sure you want to leave? Your changes may not be saved.";
    //     }

    // });

    $('#issue_da').submit(function(e) {
        e.preventDefault();
        messageType = 2;
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert err-msg")
        el.hide()
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=issue_da",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: err => {
                console.error(err)
                el.addClass('alert-danger').text("An error occured");
                _this.prepend(el)
                el.show('.modal')
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                    // location.replace(_base_url_ + 'admin/?page=pcn_form/')
                } else if (resp.status == 'failed' && !!resp.msg) {
                    el.addClass('alert-danger').text(resp.msg);
                    _this.prepend(el)
                    el.show('.modal')
                } else {
                    el.text("An error occured");
                    console.error(resp)
                }
                $("html, body").scrollTop(0);
                end_loader()

            }
        })
    })

    // document.getElementById("incidentreport/createNewIRDA").addEventListener("keydown", function(event) {
    //     // Check if the Enter key is pressed (key code 13)
    //     if (event.key === "Enter") {
    //         // Prevent the default form submission behavior
    //         event.preventDefault();
    //     }
    // });
</script>