<?php
require_once('./../../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `ir_requests` where id = '{$_GET['id']}' ");
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

<form action="" id="disappr-frm">
    <input type="hidden" name='id' value="<?php echo isset($id) ? $id : '' ?>">
    <input type="hidden" name='hr_name' value="<?php echo $_settings->userdata('EMPLOYID') ?>">
    <!-- <h4 class="text-center">Reason</h4> -->
    <div class="row mb-3 mt-5">
        <div class="col-md-12">
            <div class="form-group ">
                <label class="control-label">Remarks:</label>

                <input class="form-control" required type="text" name="disapprove_remarks" value="">
            </div>
        </div>
    </div>
    <div class="text-right">
        <button class="btn btn-primary" type="submit">Submit</button>
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    </div>
</form>
<script>
    $('#disappr-frm').submit(function(e) {
        e.preventDefault();
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert err-msg")
        el.hide()
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=cancel_ir_fromhr",
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
                    location.reload();
                    // location.replace(_base_url_ + 'admin/?page=prf/request')
                } else {
                    el.text("An error occured");
                    console.error(resp)
                }
                $("html, body").scrollTop(0);
                end_loader()

            }
        })
    })
</script>