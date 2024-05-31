<?php
require_once('./../../../config.php');
if (isset($_POST['qdn_id']) && $_POST['qdn_id'] > 0) {
    $qryyyyyy = $conn->query("SELECT * from `ir_requests` where id = '{$_POST['qdn_id']}' ");
    if ($qryyyyyy->num_rows > 0) {
        foreach ($qryyyyyy->fetch_assoc() as $k => $v) {
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

<div class="position-relative">
    <h4 class="text-center"><strong>Appeal Details</strong></h4>
    <div class="ribbon-wrapper ribbon-xl" style=" pointer-events: none;">
        <?php if ($appeal_status == 2) { ?>
            <div class="ribbon bg-success text-xl">
                Valid
            </div>
        <?php } ?>
        <?php if ($appeal_status == 3) { ?>
            <div class="ribbon bg-danger text-xl">
                Invalid
            </div>
        <?php } ?>
        <?php if ($appeal_status == 4) { ?>
            <div class="ribbon bg-success text-xl">
                Approved
            </div>
        <?php } ?>
        <?php if ($appeal_status == 5) { ?>
            <div class="ribbon bg-danger text-xl">
                Disapproved
            </div>
        <?php } ?>
    </div>
    <form action="" id="appeal-frm" <?php echo $appeal_status >= 1 ? 'style="pointer-events:none;"' : '' ?>>
        <input type="hidden" name='id' value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name='ir_no' value="<?php echo isset($ir_no) ? $ir_no : '' ?>">
        <input type="hidden" name='appeal_name' value="<?php echo $_settings->userdata('EMPLOYID') ?>">
        <input type="hidden" name='appeal_status' value="<?php echo $_settings->userdata('DEPARTMENT') == 'Human Resource' ? 2 : 1 ?>">
        <!-- <h4 class="text-center">Reason</h4> -->
        <div class="row mb-3 mt-5">
            <?php
            $options = $conn->query("SELECT * FROM `ir_list` where `ir_no` = '{$ir_no}' and (da_type=3 or da_type=4) and valid=1");
            while ($row = $options->fetch_assoc()) :
            ?>
                <input type="hidden" name='code_no[]' value="<?php echo $row['code_no'] ?>">

                <div class="col-md-3">
                    <label class="control-label">Current no of days:</label>
                    <input readonly type="number" id="days_no<?php echo $row['id'] ?>" class="form-control text-center" value="<?php echo $row['da_type'] == 3 ? 3 : 7 ?>">
                </div>
                <div class="col-md-3">
                    <label class="control-label">Requested no of days:</label>
                    <input type="number" id="appeal_days<?php echo $row['id'] ?>" name="appeal_days[]" required class="form-control text-center" value="<?php echo isset($row['appeal_days']) ? $row['appeal_days'] : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="control-label">Requested suspension date: </label>
                    <input type="text" id="appeal_date<?php echo $row['id'] ?>" autocomplete="off" name="appeal_date[]" class="form-control appeal_date text-center" <?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'readonly' ?> value="<?php echo isset($row['appeal_date']) && $row['appeal_date'] !== '0000-00-00 00:00:00' ? $row['appeal_date'] : $row['date_of_suspension'] ?>">

                    <!-- <input type="text" id="appeal_date<?php echo $row['id'] ?>" autocomplete="off" name="appeal_date[]" class="form-control appeal_date text-center" <?php echo $row['da_type'] == 3 || $row['da_type'] == 4 ? '' : 'readonly' ?> value="<?php echo isset($row['appeal_date']) ? $row['appeal_date'] : $row['date_of_suspension'] ?>"> -->
                </div>
            <?php endwhile; ?>
            <div class="col-md-12">
                <div class="form-group ">
                    <label class="control-label">Appeal remarks:</label>
                    <textarea class="form-control" rows="5" required type="text" name="appeal_remarks"><?php echo isset($appeal_remarks) ? $appeal_remarks : '' ?></textarea>
                </div>
            </div>
            <?php if ($appeal_status != 0) { ?>
                <div class="col-md-12">
                    <div class="form-group ">
                        <label class="control-label">Disapprove remarks:</label>
                        <textarea class="form-control" rows="3" required type="text"><?php echo isset($disapprove_remarks) ? $disapprove_remarks : '' ?></textarea>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if ($appeal_status == 0) { ?>
            <div class="text-right">
                <button class="btn btn-primary" type="submit">Submit</button>
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        <?php } ?>
    </form>
</div>
<?php if ($appeal_status == 1) { ?>
    <div class="row">
        <div class="col-12">
            <label class="control-label text-info ">For validation :</label>
            <div class="button-wrapp text-center">
                <button class="btn btn-flat btn-lg btn-outline-success approve_data" type="button" data-id="<?php echo $id ?>" data-val="2" data-sign="4" data-name="<?php echo $_settings->userdata('EMPLOYID') ?>"> <i class="fas fa-thumbs-up"></i> Valid</button>
                <button class="btn btn-flat btn-lg btn-outline-danger disapprove_data_hr" type="button" data-id="<?php echo $id ?>" data-val="3" data-sign="4"> <i class="fas fa-thumbs-down"></i> Invalid</button>
            </div>
            <i class="text-info" style="display:block; text-align: center;">HR Manager</i>
        </div>
    </div>
<?php } elseif ($appeal_status == 2) { ?>
    <div class="row">
        <div class="col-12">
            <label class="control-label text-info ">For approval :</label>
            <div class="button-wrapp text-center">
                <button class="btn btn-flat btn-lg btn-outline-success approve_data" type="button" data-id="<?php echo $id ?>" data-val="4" data-sign="5" data-name="<?php echo $_settings->userdata('EMPLOYID') ?>"> <i class="fas fa-thumbs-up"></i> Approved</button>
                <button class="btn btn-flat btn-lg btn-outline-danger disapprove_data" type="button" data-id="<?php echo $id ?>" data-val="5" data-sign="5"> <i class="fas fa-thumbs-down"></i> Disapproved</button>
            </div>
            <i class="text-info" style="display:block; text-align: center;">Director</i>
        </div>
    </div>
<?php } ?>
<script>
    // Set a lower z-index for the modal
    $('.approve_data').click(function() {
        // _conf("Are you sure to APPROVE this Incident Report?", "appr_irda", [$(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name')])
        appr_irda($(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name'));
    })
    $('.disapprove_data_hr').click(function() {
        // _conf("Are you sure to DISAPPROVE this Incident Report?", "appr_irda", [$(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name')])
        // appr_irda($(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name'));
        uni_modal("", "incidentreport/approveIR/appeal_invalid.php?id=" + $(this).attr('data-id'), 'mid-large');
    })
    $('.disapprove_data').click(function() {
        // _conf("Are you sure to DISAPPROVE this Incident Report?", "appr_irda", [$(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name')])
        // appr_irda($(this).attr('data-id'), $(this).attr('data-val'), $(this).attr('data-sign'), $(this).attr('data-name'));
        uni_modal("", "incidentreport/approveIR/appeal_disappr.php?id=" + $(this).attr('data-id'), 'mid-large');
    })

    function appr_irda($id, $val, $sign, $name) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=appr_irda",
            method: "POST",
            data: {
                id: $id,
                val: $val,
                sign: $sign,
                name: $name
            },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    // $('.sub').click();
                    // location.reload()
                    location.replace(_base_url_ + "/admin/?page=incidentreport/approveIR");
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
    $('.appeal_date').each(function() {
        $(this).datepick({
            multiSelect: 999,
            multiSeparator: ' + ',
            minDate: new Date()
        });
    });
    $('#appeal-frm').submit(function(e) {
        e.preventDefault();
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert err-msg")
        el.hide()
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=appeal_ir",
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
                    alert_toast("Personnel request cancelled", 'success')
                    // location.reload();
                    location.replace(_base_url_ + 'admin/?page=incidentreport/approveIR')
                } else {
                    el.text("An error occured");
                    console.error(resp)
                }
                $("html, body").scrollTop(0);
                end_loader()

            }
        })
    })
    end_loader()
</script>