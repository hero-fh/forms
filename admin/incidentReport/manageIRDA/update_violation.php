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
            <label class="control-label">Code No: </label>
            <select name="code_no" onchange="get_code(this.value)" class="form-control code_no select2" required>
                <?php
                $application = $conn->query("SELECT * FROM `ir_code_no` where status = 1  ");
                while ($row = $application->fetch_assoc()) :
                ?>
                    <option value="<?= $row['code_number'] ?>" <?= $row['code_number'] == $code_no ? 'selected' : '' ?>><?= $row['code_number'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-12">
            <label class="control-label">Violation </label>
            <textarea readonly autocomplete="off" type="text" class="form-control" id="violation" name="violation"><?php echo $violation ?></textarea>
        </div>
        <div class="col-12">
            <label class="control-label">D.A. </label>
            <select name="da_type" required id="da_type" class="custom-select not_">
                <option value="" disabled selected>--Select D.A--</option>
                <option <?php echo isset($da_type) && $da_type == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
                <option <?php echo isset($da_type) && $da_type == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
                <option <?php echo isset($da_type) && $da_type == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
                <option <?php echo isset($da_type) && $da_type == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
                <option <?php echo isset($da_type) && $da_type == 5 ? 'selected' : '' ?> value="5">Dismissal</option>

            </select>
        </div>
        <div class="col-12">
            <label class="control-label">Date Commited</label>
            <input autocomplete="off" type="text" class="form-control not_qa" id="date_commited" required name="date_commited" value="<?php echo isset($date_commited) ? $date_commited : '' ?>">
        </div>
        <div class="col-12">
            <label class="control-label">No. of offense </label>
            <input autocomplete="off" type="text" class="form-control not_qa" id="offense_no" required name="offense_no" value="<?php echo $offense_no ?>">
        </div>
        <div class="col-12">
            <label class="control-label">Disposition </label>
            <select required name="valid" class="custom-select">
                <option value="" disabled selected>--Disposition--</option>
                <option <?php echo isset($valid) && $valid == 1 ? 'selected' : '' ?> value="1">VALID</option>
                <option <?php echo isset($valid) && $valid == 2 ? 'selected' : '' ?> value="2">INVALID</option>
            </select>
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
    $('.select2').select2({
        width: '100%'
    })
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
            url: _base_url_ + "classes/IR_DA_Master.php?f=update_violation",
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

    function get_code(str) {
        if (str.length == 0) {
            $("#violation").val("");
            return;
        } else {
            $.ajax({
                url: _base_url_ + "get_code_no.php?q=" + encodeURIComponent(str),
                type: "GET",
                success: function(response) {
                    $("#violation").val(response);

                }
            });

        }
    }
</script>