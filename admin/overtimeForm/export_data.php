<?php
// Load the database configuration file 
require_once('../../config.php');
?>

<style>
    #uni_modal .modal-header,
    #uni_modal .modal-footer {
        display: none;
    }
</style>
<h1 class="float-right">
    <button type="button" class="close" data-dismiss="modal" arial-label="Close">
        <span arial-hidden="true">&times;</span>
    </button>
</h1>
</br></br>
<div class="container-fluid">
    <form action="<?php echo base_url ?>admin/overtimeForm/export.php" method="post">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info">Payroll Cut-off : From</label>
                    <input type="date" class="form-control rounded-0" name="exDatefrom" class="form-control form-control-sm rounded-0" value="" required>
                </div>
                <div class="col-md-6">
                    <label class="control-label text-info">Payroll Cut-off : To</label>
                    <input type="date" class="form-control rounded-0" name="exDateto" class="form-control form-control-sm rounded-0" value="" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-flat btn-primary" type="submit">Export</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Add event listener to form submission
        $("form").on("submit", function(event) {
            //event.preventDefault(); // Prevent default form submission

            // Perform your form submission using AJAX or regular form submission
            // ...

            // Assuming the form submission is successful, close the UniModal and display a toast
            //UniModal.close(); // Close the UniModal
            $(".close").click();

            // Display an alert toast
            alert_toast("Export overtime request successfully", "success");
        });
    });
</script>