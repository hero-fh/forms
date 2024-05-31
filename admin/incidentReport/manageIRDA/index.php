<div class="card card-outline card-primary">
    <div class="card-header p-0 pt-1 ">
        <ul class="nav nav-tabs mr-2" id="custom-tabs-one-tab" role="tablist">
            <li class="pt-2 px-3">
                <h3 class="card-title">List of IR / DA</h3>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-one-active-tab" data-toggle="pill" href="#custom-tabs-one-active" role="tab" aria-controls="custom-tabs-one-active" aria-selected="true">IR / DA</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-history-tab" data-toggle="pill" href="#custom-tabs-one-history" role="tab" aria-controls="custom-tabs-one-history" aria-selected="false">DA</a>
            </li> -->
            <li class="nav-item ml-auto">
                <a href="<?php echo base_url ?>admin/incidentReport/manageIRDA/ir_export.php" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Export</a>
                <!-- <a href="#" class="btn btn-flat btn-primary export"><span class="fas fa-plus"></span> Export</a> -->
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-one-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-one-active" role="tabpanel" aria-labelledby="custom-tabs-one-active-tab">
                <div class="card card-outline card-info shadow">
                    <div class="card-header  p-1 pl-2 pt-2 ">
                        <label for="">Filter</label>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Result Type:</label>
                                        <select class="selevt form-control" required name="resultType" id="resultType" style="width: 100%;">
                                            <option value="" selected disabled>Please select result type</option>
                                            <option value="1">All</option>
                                            <option value="2">Custom</option>
                                            <option value="3">Search IR No.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>IR No:</label>
                                        <select id="ir_no" disabled class="form-control select2" required>
                                            <option value="" disabled selected>Select IR No.</option>
                                            <?php
                                            $application = $conn->query("SELECT ir_no FROM `ir_requests`");
                                            while ($row = $application->fetch_assoc()) :
                                            ?>
                                                <option value="<?= $row['ir_no'] ?>"><?= $row['ir_no']   ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group ">

                                        <div class="info-container">
                                            <label>Result date range:<small><i></i></small>
                                            </label>
                                        </div>
                                        <!-- <small class="fas fa-info-circle text-info"><i>Default value (This month)</i></small> -->
                                        <div class="input-group text-center justify-content-center">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control text-center" disabled name="date_range" id="date_range" value="<?php echo date('m/01/Y') . ' - ' . date('m/t/Y')  ?>">
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label>IR Status</label>
                                    <div class="row mb-3 justify-content-between">
                                        <div class="col-md-2">
                                            <div class="icheck-warning d-inline">
                                                <input type="radio" checked class="status" disabled name="status" id="All" value="1">
                                                <label for="All">&nbsp;All</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="icheck-warning d-inline">
                                                <input type="radio" class="status" disabled name="status" id="explain" value="2">
                                                <label for="explain">&nbsp;For explanation</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="icheck-warning d-inline">
                                                <input type="radio" class="status" disabled name="status" id="ass" value="3">
                                                <label for="ass">&nbsp;For Assessment</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="icheck-warning d-inline">
                                                <input type="radio" class="status" disabled name="status" id="suspension" value="4">
                                                <label for="suspension">&nbsp;W/Suspension</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="icheck-warning d-inline">
                                                <input type="radio" class="status" disabled name="status" id="Inactive" value="5">
                                                <label for="Inactive">&nbsp;Inactive</label>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-2">
                                    <div class="icheck-warning d-inline">
                                        <input type="radio" class="status" disabled name="status" id="Search" value="5">
                                        <label for="Search">&nbsp;Search</label>
                                    </div>
                                </div> -->
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="button" id="loadContentButton" class="btn btn-dark btn-block"> <i class="fa fa-download" aria-hidden="true"></i> Load Result</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="S_result"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.export').click(function() {
        uni_modal("Export", _base_url_ + "admin/incidentReport/manageIRDA/irdaExport.php")

    })
    $(document).ready(function() {
        $('.select2,.selevt').select2()
        $('#resultType').on('change', function() {
            if ($('#resultType').val() == 1) {
                $('#date_range').prop('disabled', true);
                $('.status').prop('disabled', true);
                $('#ir_no').prop('disabled', true);
            } else if ($('#resultType').val() == 2) {
                $('#date_range').prop('disabled', false);
                $('.status').prop('disabled', false);
                $('#ir_no').prop('disabled', true);
            } else if ($('#resultType').val() == 3) {
                $('#ir_no').prop('disabled', false);
                $('#date_range').prop('disabled', true);
                $('.status').prop('disabled', true);
            }
        });
        var ndas = $('#date_range').val()
        var irstats = $('.status').val()
        var ir_no = $('#ir_no').val()
        console.log(ndas)

        $('#date_range').daterangepicker({
                ranges: {
                    'Today': [moment(), moment().add(1, 'day')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                },
                // startDate: moment().subtract(29, 'days'),
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month')
            },
            function(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )
        $('#date_range').on('change', function() {
            ndas = $('#date_range').val()
            console.log(ndas)
        });
        $('#ir_no').on('change', function() {
            ir_no = $('#ir_no').val()
            console.log(ndas)
        });
        $('.status').on('change', function() {
            var selectedValue = $('input[type="radio"]:checked').val();
            irstats = selectedValue
            console.log('it srarus' + irstats)
        });

        $('#loadContentButton').on('click', function() {
            if ($('#resultType').val() != 1 && $('#resultType').val() != 2 && $('#resultType').val() != 3) {
                alert_toast("Select Result type.", 'warning');
                return false;
            }
            console.log('result type' + $('#resultType').val())
            start_loader();
            $.ajax({
                url: _base_url_ + "admin/incidentReport/manageIRDA/searchResult.php",
                method: "POST",
                data: {
                    resultType: $('#resultType').val(),
                    date_range: ndas,
                    status: irstats,
                    ir_no: ir_no,
                },
                success: function(response) {
                    $("#S_result").html(response);
                    end_loader();
                    // $("#btnSubmit").removeAttr("disabled");
                },
                error: function(error) {
                    console.log("Error:", error);
                }
            });
            // $.ajax({
            //     url: _base_url_ + "admin/manageirda/searchResult.php?resultType=" + $('#resultType').val(),
            //     type: "GET",
            //     success: function(response) {
            //         $("#S_result").val(response);
            //         console.log('tangina ano ba to!!!!!')


            //     }
            // });
        })
    });
</script>