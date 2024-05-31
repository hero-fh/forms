<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `ir_requests` where md5(id) = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
$noted_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = $sv_name")->fetch_array()[0];
// $appr_by = $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = $sv_name")->fetch_array()[0];
$e_id = $_settings->userdata('EMPLOYID');
?>
<style>
    input.form-control:read-only {
        background-color: #fff;
    }

    select.custom-select:disabled {
        background-color: #fff;

    }

    textarea.form-control:read-only {
        background-color: #fff;
    }

    td {
        vertical-align: middle;
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
    <div class="card-header">
        <h4 class="card-title card-primary">CREATE NEW INCIDENT REPORT</h4>
    </div>
    <div class="card-body">
        <form action="" id="ir-form">
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
                    <h4 class="text-center"><strong>I. Incident report form</strong></h4>

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
                            <input required type="number" name="emp_no" id="emp_no" class="form-control  rounded-0" readonly value="<?php echo isset($emp_no) ? $emp_no : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">Shift/Team</label>
                            <input required type="text" name="shift" readonly id="shift" class="form-control  rounded-0" value="<?php echo isset($shift) ? $shift : '' ?>">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-info">Employee name</label>
                            <input required readonly type="text" name="emp_name" id="emp_name" class="form-control  rounded-0" value="<?php echo isset($emp_name)  ? $emp_name : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">Position</label>
                            <input required type="text" readonly name="position" id="position" class="form-control  rounded-0" value="<?php echo isset($position)  ? $position : '' ?>">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label text-info">Department</label>
                            <input required readonly type="text" name="department" id="department" class="form-control  rounded-0" value="<?php echo isset($department)  ? $department : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="control-label text-info">Station</label>
                            <input required readonly type="text" name="station" id="station" class="form-control  rounded-0" value="<?php echo isset($station)  ? $station : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="control-label text-info">Product line</label>
                            <input required type="text" readonly name="productline" id="productline" class="form-control rounded-0" value="<?php echo isset($productline)  ? $productline : '' ?>">
                        </div>
                    </div>

                    </br>

                    <h5>Please check appropriate box and indicate the reference no. for quality violation:</h5><br>
                    <div class="row" style="pointer-events: none;">
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary btn-lg d-inline">
                                    <input type="radio" <?php echo isset($quality_violation) && $quality_violation == 1 ? 'checked' : '' ?> value="1" class="form-control" id="administrative" name="quality_violation" />
                                    <label for="administrative">Administrative</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary btn-lg d-inline">
                                    <input type="radio" <?php echo isset($quality_violation) && $quality_violation == 2 ? 'checked' : '' ?> value="2" class="form-control" id="quality" name="quality_violation" />
                                    <label for="quality">Quality</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="control-label text-info">Reference document(IPNR/QDN/EQS):</label>
                            <input <?php echo isset($reference) ? 'readonly' : '' ?> <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'readonly' : 'required' ?> type="text" name="reference" class="form-control  rounded-0" value="<?php echo isset($reference)  ? $reference : '' ?>">
                        </div>
                    </div>
                    <br>
                    <h5>Statement of facts/incident details:</h5>
                    <div class="row" style="pointer-events: none;">
                        <div class="col-md-12">
                            <label class="control-label text-info">What</label>
                            <textarea required type="text" name="what" class="form-control  rounded-0"><?php echo isset($what)  ? $what : '' ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">When</label>
                            <input required type="date" name="when" class="form-control  rounded-0" value="<?php echo isset($when)  ? $when : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-info">Where</label>
                            <input required type="text" name="where" class="form-control  rounded-0" value="<?php echo isset($where)  ? $where : '' ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="control-label text-info">How/Other information</label>
                            <textarea type="text" name="how" class="form-control  rounded-0"><?php echo isset($how)  ? $how : '' ?></textarea>
                        </div>
                    </div>
                    <br>

                    <br>
                    <fieldset>
                        <!-- Table to display the added inputs -->
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2">Code No</th>
                                    <th rowspan="2">Violations</th>
                                    <th rowspan="2">D.A</th>
                                    <th rowspan="2">Date commited</th>
                                    <th rowspan="2">No. of offense</th>
                                    <th colspan="2">Disposition</th>
                                    <!-- <th colspan="2">Explanation</th> -->

                                </tr>
                                <tr>
                                    <!-- <th>Valid</th>
                                    <th>Invalid</th> -->
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '$ir_no'");
                                while ($row = $options->fetch_assoc()) :
                                ?>
                                    <input type="hidden" name="code_no[]" value="<?php echo $row['code_no'] ?>">
                                    <tr class="text-center">
                                        <td><?php echo $row['code_no'] ?></td>
                                        <td> <?php echo $row['violation'] ?></td>
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
                                        <td class="text-center">
                                            <?php if ($row['offense_no'] == 1) : ?>
                                                <span class="badge badge-success rounded-pill"><?php echo $row['offense_no'] ?></span>
                                            <?php elseif ($row['offense_no'] == 2) : ?>
                                                <span class="badge badge-primary rounded-pill"><?php echo $row['offense_no'] ?></span>
                                            <?php else : ?>
                                                <span class="badge badge-danger rounded-pill"><?php echo $row['offense_no'] ?></span>
                                            <?php endif; ?>
                                        </td>


                                        <td class="text-center">
                                            <div class="icheck-primary btn-lg d-inline">
                                                <input type="checkbox" required class="con_yes" data-id="<?php echo $row['id'] ?>" id="con_yes<?php echo $row['id'] ?>" value="1" <?php echo isset($row['valid']) && $row['valid'] == 1 ? 'checked' : '' ?> name="valid[]">
                                                <label>Valid</label>
                                            </div>
                                            <!-- <button class="btn btn-flat btn-sm btn-outline-success approve_data" type="button" data-id="<?php echo $id ?>" data-val="1" data-sign="1"> <i class="fas fa-thumbs-up"></i> Valid</button> -->
                                        </td>
                                        <td class="text-center">
                                            <div class="icheck-primary btn-lg d-inline">
                                                <input type="checkbox" required class="con_no" data-id="<?php echo $row['id'] ?>" id="con_no<?php echo $row['id'] ?>" value="2" <?php echo isset($row['valid']) && $row['valid'] == 2 ? 'checked' : '' ?> name="valid[]">
                                                <label>Invalid</label>

                                            </div>
                                            <!-- <button class="btn btn-flat btn-sm btn-outline-danger disapprove_data" type="button" data-id="<?php echo $id ?>" data-val="2" data-sign="1"> <i class="fas fa-thumbs-down"></i> Invalid</button> -->
                                        </td>
                                        <!-- <td>
                                            <a class="btn btn-sm btn-block edt" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fas fa-eye text-lg text-danger"></span></a>
                                        </td> -->
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            <a class="btn btn-sm btn-block edt" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fas fa-eye text-dark"> Letter of explanation</i></a>
                                        </td>
                                    </tr>
                                    <tr class="<?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'd-none' ?>" style="pointer-events: none;">
                                        <td class="text-center" colspan="2">
                                            <label class="control-label">No of days:</label>
                                            <input type="number" id="days_no<?php echo $row['id'] ?>" name="days_no[]" class="form-control text-center" <?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'disabled' ?> value="<?php echo $row['days_no'] ?>">
                                        </td>
                                        <td class="text-center" colspan="6">
                                            <label class="control-label">Suspension date:</label>
                                            <input type="text" id="date_of_suspension<?php echo $row['id'] ?>" autocomplete="off" name="date_of_suspension[]" class="form-control text-center" <?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'disabled' ?> value="<?php echo $row['date_of_suspension'] ?>">
                                        </td>
                                    </tr>
                                <?php endwhile; ?>


                            </tbody>
                        </table>
                    </fieldset>
                    <div class="row ">
                        <div class="col-md-12">
                            <label class="control-label text-info">Admin hearing/counseling</label>
                            <textarea readonly type="text" name="assessment" placeholder="Input details of admin hearing/counseling" class="form-control  rounded-0"><?php echo isset($assessment)  ? $assessment : '' ?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label text-info">Recommendation</label>
                            <textarea readonly type="text" name="recommendation" class="form-control  rounded-0"><?php echo isset($recommendation)  ? $recommendation : '' ?></textarea>
                        </div>
                    </div>

                </div>
                <br>
                <div class=" py-1 text-center">
                    <!-- <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource') { ?>
                        <button class="btn btn-flat btn-sn btn-success" type="button" id="print"><i class="fa fa-print"></i> Print IR</button>
                    <?php } ?> -->


                    <button class="btn btn-flat btn-lg d-none btn-outline-warning click_1 appeal_data" type="button" data-id="<?php echo $id ?>" data-val="2" data-sign="2" data-name="<?php echo $_settings->userdata('EMPLOYID') ?>"> <i class="fas fa-gavel"></i> Appeal</button>
                    <div id="trainContentDiv"></div>

                </div>

                <div class=" py-1 text-center">
                    <!-- <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource') { ?>
                        <button class="btn btn-flat btn-sn btn-success" type="button" id="print"><i class="fa fa-print"></i> Print IR</button>
                    <?php } ?> -->


                    <!-- <button <?php echo isset($ir_status) && $ir_status == 4 ? 'disabled' : '' ?> class="btn btn-flat btn-primary" type="submit" form="ir-form">SUBMIT</button> -->
                    <a class="btn btn-flat btn-dark" href="<?php echo base_url . 'admin?page=incidentreport/DA' ?>">Back</a>
                </div>
            </div>

        </form>
    </div>
</div>

<?php
$click = json_decode($appeal_status);
?>
<script>
    var click = <?php echo $click ?>;

    console.log(click)

    function triggerButton() {
        $(".click_1").click()
    }
    if (click >= 1) {
        console.log('tangina ano ba to')
        setTimeout(triggerButton, 500);
    }

    $(".close_appeal_data").click(function() {
        $(".appeal_data").removeClass('d-none')
        $(".close_appeal_data").addClass('d-none')
        $("#trainContentDiv").html('');
    })
    $(".appeal_data").click(function() {
        $(".appeal_data").addClass('d-none')
        $(".close_appeal_data").removeClass('d-none')
        start_loader();
        var qdn_id = $(this).attr('data-id');
        console.log(qdn_id)
        $.ajax({
            url: _base_url_ + "admin/incidentReport/approveIR/appeal_letter.php",
            method: "POST",
            data: {
                qdn_id: qdn_id
            },
            success: function(response) {
                $("#trainContentDiv").html(response);
                // $("#btnSubmit").removeAttr("disabled");
            },
            error: function(error) {
                console.log("Error:", error);
            }
        });
    });


    $(document).ready(function() {
        $('.table td,.table th').addClass('py-1 px-2 align-middle text-center')
        $('.did').hide();
        $('.edt').click(function() {
            uni_modal("Letter of explantion", "inbox/explainIR.php?id=" + $(this).attr('data-id'), 'large');
        })
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
    });
    // $('.edt').click(function() {
    //     uni_modal("Edit Incident", "incidentreport/createNewIRDA/add_ir.php?id=" + $(this).attr('data-id'), 'mid-large');
    // })

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

    $('#ir-form').submit(function(e) {
        e.preventDefault();
        messageType = 2;
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert err-msg")
        el.hide()
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=save_ir_request",
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