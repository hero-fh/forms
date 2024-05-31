<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `ir_list` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
?>
<style>
    #uni_modal .modal-header,
    #uni_modal .modal-footer {
        display: none;
    }
</style>
<br>
<form action="" id="explain">
    <input readonly type="hidden" name="id" value="<?php echo isset($id)  ? $id : '' ?>">
    <input readonly type="hidden" name="ir_no" value="<?php echo isset($ir_no)  ? $ir_no : '' ?>">
    <input readonly type="hidden" name="emp_no" value="<?php echo isset($emp_no)  ? $emp_no : '' ?>">
    <div class="container-fluid">
        <div class="<?php echo $why1 != '' ? 'printable' : '' ?>">

            <fieldset>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center py-1 px-2">Code no.</th>
                            <th class="text-center py-1 px-2">Violation/Nature of offenses</th>
                            <th class="text-center py-1 px-2">D.A</th>
                            <th class="text-center py-1 px-2">Date commited</th>
                            <th class="text-center py-1 px-2">No. of offense</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td><?php echo $code_no ?></td>
                            <td><?php echo $violation ?></td>
                            <td class="text-center">
                                <?php if ($da_type == 1) : ?>
                                    <span class="badge badge-success rounded-pill">Verbal Warning</span>
                                <?php elseif ($da_type == 2) : ?>
                                    <span class="badge badge-primary rounded-pill">Written Warning</span>
                                <?php elseif ($da_type == 3) : ?>
                                    <span class="badge badge-secondary rounded-pill">3 Days Suspension</span>
                                <?php elseif ($da_type == 4) : ?>
                                    <span class="badge badge-warning rounded-pill">7 Days Suspension</span>
                                <?php elseif ($da_type == 5) : ?>
                                    <span class="badge badge-danger rounded-pill">Dismissal</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date("m-d-Y", strtotime($date_commited)) ?></td>
                            <td class="text-center">
                                <?php if ($offense_no == 1) : ?>
                                    <span class="badge badge-success rounded-pill"><?php echo $offense_no ?></span>
                                <?php elseif ($offense_no == 2) : ?>
                                    <span class="badge badge-primary rounded-pill"><?php echo $offense_no ?></span>
                                <?php else : ?>
                                    <span class="badge badge-danger rounded-pill"><?php echo $offense_no ?></span>
                                <?php endif; ?>
                            </td>




                        </tr>

                    </tbody>
                </table>

            </fieldset>
            <br>
            <h4 class="text-center"><strong>II. Letter of explanation</strong></h4>

            <p align="justify">
                Please explain in writing within 5 calendar days upon receipt hereof, why no disciplinary action should be taken against you for the violations committed stated on the Incident report.
                No explanation received within the period alloted to you may be construed as an admission of offense levelled on you and waived your right to be heard and the corresponding penalty should be given to you.
            </p>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label text-info">Why? :</label>
                    <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'required' : 'disabled' ?> <?php echo isset($why1) && $why1 != ''  ? 'readonly"' : '' ?> type="text" name="why1" class="form-control  rounded-0"><?php echo isset($why1)  ? $why1 : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label text-info">Why? :</label>
                    <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'required' : 'disabled' ?> <?php echo isset($why2) && $why2 != ''  ? 'readonly"' : '' ?> type="text" name="why2" class="form-control  rounded-0"><?php echo isset($why2)  ? $why2 : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label text-info">Why? :</label>
                    <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? 'required' : 'disabled' ?> <?php echo isset($why3) && $why3 != ''  ? 'readonly"' : '' ?> type="text" name="why3" class="form-control  rounded-0"><?php echo isset($why3)  ? $why3 : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label text-info">Why? :</label>
                    <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? '' : 'disabled' ?> <?php echo isset($why4) && $why4 != ''  ? 'readonly"' : '' ?> type="text" name="why4" class="form-control  rounded-0"><?php echo isset($why4)  ? $why4 : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label text-info">Why? :</label>
                    <textarea <?php echo isset($emp_no) && $_settings->userdata('EMPLOYID')  == $emp_no ? '' : 'disabled' ?> <?php echo isset($why5) && $why5 != ''  ? 'readonly"' : '' ?> type="text" name="why5" class="form-control  rounded-0"><?php echo isset($why5)  ? $why5 : '' ?></textarea>
                </div>
            </div>
            <br>
            <div class="col-12 text-center">
                <h3 class="control-label text-gray"><u><?php echo $conn->query("SELECT EMPNAME FROM employee_masterlist WHERE EMPLOYID = '{$emp_no}'")->fetch_array()[0] ?></u></h3>
            </div>
            <div class="form-group clearfix text-center">
                <div class="icheck-primary btn-lg d-inline">
                    <input type="checkbox" id="cert" required <?php echo isset($why1) ? 'checked' : '' ?> <?php echo $_settings->userdata('EMPLOYID')  == $emp_no ? '' : 'disabled' ?>>
                    <label for="cert">I hereby certify that the above information is true and correct.</label>
                </div>
            </div>
        </div>
    </div>
    <div class=" py-1 text-center">
        <?php if ($emp_no == $_settings->userdata('EMPLOYID') && ($why1 == '' || $why2 == '' || $why3 == '' || $why4 == '' || $why5 == '')) { ?>
            <button class="btn btn-flat btn-primary" type="submit">Submit</button>
        <?php } ?>

        <button type="button" class="btn btn-flat btn-secondary" data-dismiss="modal" arial-label="Close">Back</button>
    </div>
</form>


<script>
    end_loader()
    $('.table td,.table th').addClass('py-1 px-2 align-middle text-center')
    // $('.printable :input').attr('readonly', true)

    $('#explain').submit(function(e) {
        e.preventDefault();
        messageType = 2;
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert err-msg")
        el.hide()
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=letter_of_explain",
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