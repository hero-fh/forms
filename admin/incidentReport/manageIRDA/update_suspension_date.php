<?php
require_once('../../../config.php');
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
<form action="" id="update">
    <input readonly type="hidden" name="id" value="<?php echo isset($id)  ? $id : '' ?>">
    <div class="row">
        <div class="col-12">
            <label class="control-label">Update suspension date: </label>
            <input type="text" autocomplete="off" name="date_of_suspension" class="form-control datepicker text-center" value="<?php echo $date_of_suspension ?? '' ?>">
        </div>
    </div><br>
    <div class=" py-1 text-center">
        <button class="btn btn-flat btn-primary" type="submit">Submit</button>
        <button type="button" class="btn btn-flat btn-secondary" data-dismiss="modal" arial-label="Close">Cancel</button>
    </div>
</form>


<script>
    end_loader()
    $('.table td,.table th').addClass('py-1 px-2 align-middle text-center')
    // $('.printable :input').attr('readonly', true)

    $('#update').submit(function(e) {
        e.preventDefault();
        messageType = 2;
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert err-msg")
        el.hide()
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/IR_DA_Master.php?f=update_suspension_date",
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