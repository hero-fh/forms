<?php
$user = $conn->query("SELECT * FROM employee_masterlist where EMPLOYID =" . $_settings->userdata('EMPLOYID'));
foreach ($user->fetch_array() as $k => $v) {
    $meta[$k] = $v;
}

?>
<?php if ($_settings->chk_flashdata('success')) : ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>
<div class="card card-outline card-success container">
    <div class="card-body">
        <div class="container-fluid overflow-auto">
            <div id="msg"></div>
            <form action="" id="mng">
                <input type="hidden" name="EMPLOYID" value="<?php echo $_settings->userdata('EMPLOYID') ?>">
                <div class="form-group">
                    <label for="USERNAME">Username</label>
                    <input type="text" name="USERNAME" id="USERNAME" class="form-control" value="<?php echo isset($meta['USERNAME']) ? $meta['USERNAME'] : '' ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="PASSWORD">Password</label>
                    <input type="PASSWORD" name="PASSWORD" id="PASSWORD" class="form-control" value="" autocomplete="off">
                    <small><i>Leave this blank if you dont want to change the password.</i></small>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-block btn-success" form="mng">Update</button>
            </div>
            <div class="col-md-6">
                <a class="btn btn-block btn-secondary" href="<?php echo base_url . 'admin/' ?>">Back</a>
            </div>
        </div>
    </div>
</div>
<style>
    img#cimg {
        height: 15vh;
        width: 15vh;
        object-fit: cover;
        border-radius: 100% 100%;
    }
</style>
<script>
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#cimg').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $('#mng').submit(function(e) {
        e.preventDefault();
        var _this = $(this)
        start_loader()
        $.ajax({
            url: _base_url_ + 'classes/Users.php?f=author_pass',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            error: err => {
                console.log(err)
                el.text('An error occured')
                el.addClass('alert-danger')
                _this.append(el)
                el.show('slow')
                end_loader()
            },
            success: function(resp) {
                if (resp.status == 'success') {
                    location.reload();
                } else if (resp.status == 'failed') {
                    $('#msg').html('<div class="alert alert-danger">Error Occured</div>')
                    end_loader()
                }
            }
        })
    })
</script>