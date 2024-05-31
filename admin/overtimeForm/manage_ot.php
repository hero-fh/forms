<?php
// $qry = $conn->query("SELECT * FROM tblcutoff_period WHERE CURDATE() BETWEEN cutoff_date_start AND cutoff_date_end LIMIT 1");

// while ($row = $qry->fetch_assoc()) :

//     $cutoff_date_start = $row['cutoff_date_start'];
//     $cutoff_date_end = $row['cutoff_date_end'];

// endwhile;
?>
<style>
    select[readonly].select2-hidden-accessible+.select2-container {
        pointer-events: none;
        touch-action: none;
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10vh auto 0;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .center-text {
        text-align: center;
    }
</style>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Create New Overtime Request</h4>
    </div>
    <div class="card-body">
        <form action="" id="ot-form">
            <input type="hidden" name="id" value="">
            <input type="hidden" name="empPosition" value="<?php echo $_settings->userdata('EMPPOSITION') ?>">
            <input type="hidden" name="dhEmpNumber" value="<?php echo $_settings->userdata('EMPLOYID') ?>">

            <div class="container-fluid">
                <!-- <select class="custom-select select2" required>
                <option selected value="0">--Select Employee/s--</option>
                <?php
                //$qry = $conn->query("SELECT * FROM `employee_masterlist` where EMPID!=0");
                // while ($row = $qry->fetch_assoc()) :
                ?>
                <option value="<?php //echo $row['EMPLOYID'] 
                                ?>"><?php //echo ucwords($row['EMPNAME']) 
                                    ?></option>
                <?php //endwhile; 
                ?>
            </select> -->
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Department</label>
                        <input type="text" name="department[]" class="form-control form-control-sm rounded-0" value="<?php echo $_settings->userdata('DEPARTMENT') ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label text-info">Product Line</label>
                        <input type="text" name="productline[]" class="form-control form-control-sm rounded-0" value="<?php echo $_settings->userdata('PRODLINE') ?>" readonly>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label class="control-label text-info">Payroll Cut-off : Start</label>
                        <input type="date" class="form-control rounded-0" name="date_from" id="coDateFrom" class="form-control form-control-sm rounded-0" value="" required>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label text-info">Payroll Cut-off : End</label>
                        <input type="date" class="form-control rounded-0" name="date_to" id="coDateTo" class="form-control form-control-sm rounded-0" value="" required>
                    </div>
                </div>
                <hr>
                <div class="callout callout-info">
                    <div class="container-fluid">
                        <p class="text-center"><i class="fa fa-info-circle fa-lg text-primary" aria-hidden="true">&nbsp;&nbsp;</i>Work Shift Legend: <b><i>A</i></b> - Morning Shift (7:00AM - 7:00PM) &nbsp; <b><i>C</i></b> - Night Shift (7:00PM - 7:00AM) &nbsp; <b><i>N</i></b> - Normal Shift (7:00AM - 4:00PM)</p>
                    </div>
                </div>

                <!-- <div class="timeline">
                    <div>
                        <i class="fa fa-info bg-blue"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Legend</h3>
                            <div class="timeline-body">
                                <div class="row">
                                    <div class="col-md-12">
                                    <p class="text-center">Work Shift Legend: <b><i>A</i></b> - Morning Shift (7:00AM - 7:00PM) &nbsp; <b><i>C</i></b> - Night Shift (7:00PM - 7:00AM) &nbsp; <b><i>N</i></b> - Normal Shift (7:00AM - 4:00PM)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <fieldset>
                    <!-- <p class="text-center">The following employees have expressed their request and willingness to render overtime work on the date and time as specified below.</p> -->
                    <p class="text-center"><button type="button" id="openModalBtn" class="btn btn-flat btn-sm btn-primary">Add Employee</button></p>
                    <!-- Table to display the added inputs -->
                    <table class="table table-striped table-bordered" id="dataTable">
                        <colgroup>
                            <col width="8%">
                            <col width="23%">
                            <col width="5%">
                            <col width="13%">
                            <col width="14%">
                            <col width="32%">
                            <col width="5%">
                        </colgroup>
                        <thead>
                            <tr class="text-light bg-navy">
                                <th class="text-center py-1 px-2">EMP. NO.</th>
                                <th class="text-center py-1 px-2">EMP. NAME</th>
                                <th class="text-center py-1 px-2">WORK SHIFT</th>
                                <th class="text-center py-1 px-2">DATE REQUESTED</th>
                                <th class="text-center py-1 px-2">OT TIME</th>
                                <th class="text-center py-1 px-2">REASON</th>
                                <th class="text-center py-1 px-2">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows will be added dynamically using JavaScript -->
                        </tbody>
                    </table>


                    <!-- <div class="row justify-content-center align-items-end">    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-info">Emp. Number</label>
                                <input type="text" name="requestor_badge" id="requestor_badge" onkeyup="showHint(this.value)" class="form-control form-control-sm rounded-0" value="">             
                            </div>
                        </div>                    

                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label text-info">Name</label>
                                <input type="text" name="requestor_name" id="requestor_name" class="form-control form-control-sm rounded-0" value="" readonly required>
                             </div>
                        </div>
                    </div>  
                    <div class="row justify-content-center align-items-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="qty" class="control-label text-info">Work Shift</label>
                                <select name="productline[]"  class="custom-select">
                                <option value="A">A</option>
                                <option value="C">C</option>
                                <option value="N">N</option>
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="qty" class="control-label text-info">Date Requested</label>
                                <input type="date" class="form-control rounded-0" id="shift">
                            </div>
                        </div>                    
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="qty" class="control-label text-info">OT DATE & TIME</label>
                                <input type="datetime-local" class="form-control rounded-0" id="shift">
                            </div>
                        </div>                                       
                    </div>
                    <div class="row justify-content-center align-items-end">    
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="qty" class="control-label text-info">REASON FOR OVERTIME</label>
                                <input type="text" class="form-control rounded-0" id="shift">
                            </div>
                        </div>                    

                        <div class="col-md-2 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Add to List</button>
                            </div>
                        </div>
                    </div>     -->
                </fieldset>

                <!-- Hidden input to store cell values -->
                <input type="hidden" id="empNumber" name="empNumber">
                <input type="hidden" id="empName" name="empName">
                <input type="hidden" id="workShift" name="workShift">
                <!-- <input type="hidden" id="dateRequested1" name="dateRequested"> -->
                <input type="hidden" id="otDateFrom1" name="otDateFrom">
                <!-- <input type="hidden" id="otDateTo1" name="otDateTo"> -->
                <input type="hidden" id="otTimeFrom1" name="otTimeFrom">
                <input type="hidden" id="otTimeTo1" name="otTimeTo">
                <input type="hidden" id="otReason" name="otReason">
                <input type="hidden" id="otRequestor" name="otRequestor" value="<?php echo $_settings->userdata('EMPLOYID'); ?>">

                <div class="timeline">
                    <!-- timeline item -->
                    <div>
                        <i class="fa fa-exclamation-triangle bg-warning"></i>
                        <div class="timeline-item">
                            <!-- <h3 class="timeline-header">Reminder</h3> -->
                            <div class="timeline-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <center><b>Reminder!</b> Please ensure that all entries are correct before submitting the form.</center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="callout callout-danger">
                    <div class="container-fluid">
                        <p class="text-center"><i class="fa fa-exclamation-triangle text-warning" aria-hidden="true"></i>&nbsp;&nbsp;<i>Please ensure that all entries are correct before submitting the form.</i></p>
                    </div>
                </div> -->

                <div class="card-footer py-1 text-center">
                    <button class="btn btn-flat btn-primary" type="submit" form="ot-form">Save</button>
                    <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=purchase_order' ?>">Cancel</a>
                </div>
        </form>

        <!-- The modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label text-info">Emp. Number</label>
                            <input type="text" name="requestor_badge" id="requestor_badge" onkeyup="showHint(this.value)" class="form-control form-control-sm rounded-0" value="">
                        </div>
                        <div class="col-md-8">
                            <label class="control-label text-info">Name</label>
                            <input type="text" name="requestor_name" id="requestor_name" class="form-control form-control-sm rounded-0" value="" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="qty" class="control-label text-info">Work Shift</label>
                            <select name="workshift[]" id=workshift class="custom-select">
                                <option value=""></option>
                                <option value="A">A</option>
                                <option value="C">C</option>
                                <option value="N">N</option>
                            </select>
                        </div>
                        <!-- <div class="col-md-8">
                                    <label for="qty" class="control-label text-info">Date Requested</label>
                                    <input type="date" class="form-control rounded-0" id="dateRequested">
                                </div> -->
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="qty" class="control-label text-info">OVERTIME DATE</label>
                            <input type="date" min="<?php echo date("Y-m-d") ?>" class="form-control rounded-0" id="otDateFrom">
                            <!-- <input type="date" class="form-control rounded-0" id="otDateFrom"> -->
                        </div>
                        <!-- <div class="col-md-6">
                                    <label for="qty" class="control-label text-info">DATE TO</label>
                                    <input type="date" class="form-control rounded-0" id="otDateTo">
                                </div> -->
                        <div class="col-md-4">
                            <label for="qty" class="control-label text-info">TIME FROM</label>
                            <input type="time" class="form-control rounded-0" id="otTimeFrom">
                        </div>
                        <div class="col-md-4">
                            <label for="qty" class="control-label text-info">TIME TO</label>
                            <input type="time" class="form-control rounded-0" id="otTimeTo">
                        </div>
                    </div>
                    <!-- <div class="row">
                                <div class="col-md-6">
                                    <label for="qty" class="control-label text-info">TIME FROM</label>
                                    <input type="time" class="form-control rounded-0" id="otTimeFrom">
                                </div>
                                <div class="col-md-6">
                                    <label for="qty" class="control-label text-info">TIME FROM</label>
                                    <input type="time" class="form-control rounded-0" id="otTimeTo">
                                </div>
                            </div> -->
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="qty" class="control-label text-info">REASON FOR OVERTIME</label>
                            <input type="text" class="form-control rounded-0" id="reasons">
                        </div>
                    </div>
                    <HR>
                    <div class="row">
                        <div class="col-md-12">

                            <center><button id="addBtn" class="btn btn-flat btn-sm btn-primary" type="button">Add to List</button></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            var messageType = 1;

            $(window).on("beforeunload", function(event) {
                // Show a warning message
                if (messageType == 1) {
                    return "Are you sure you want to leave? Your changes may not be saved.";
                }

            });

            // $(document).ready(function() {
            // 	$('.select2').select2();
            // })

            // Get the modal and the button to open it
            var modal = document.getElementById("myModal");
            var btn = document.getElementById("openModalBtn");

            // Get the close button, input field, and table body
            var closeBtn = document.getElementsByClassName("close")[0];
            var inputField1 = document.getElementById("requestor_badge");
            var inputField2 = document.getElementById("requestor_name");
            var inputField3 = document.getElementById("workshift");
            //   var inputField4 = document.getElementById("dateRequested");
            var inputField5 = document.getElementById("otDateFrom");
            var inputField6 = document.getElementById("reasons");
            var inputField7 = document.getElementById("otTimeFrom");
            var inputField8 = document.getElementById("otTimeTo");
            //   var inputField9 = document.getElementById("otDateTo");

            // Array to store added cell values
            var cellValues1 = [];
            var cellValues2 = [];
            var cellValues3 = [];
            // var cellValues4 = [];
            var cellValues5 = [];
            var cellValues6 = [];
            var cellValues7 = [];
            var cellValues8 = [];
            // var cellValues9 = [];


            var tableBody = document.querySelector("#dataTable tbody");

            // Display the modal when the button is clicked
            btn.onclick = function() {
                modal.style.display = "block";
            };

            // Close the modal when the close button is clicked
            closeBtn.onclick = function() {
                modal.style.display = "none";
            };

            // Add the input value to the table when the "Add to Table" button is clicked
            document.getElementById("addBtn").onclick = function() {

                var inputValue1 = inputField1.value;
                var inputValue2 = inputField2.value;
                var inputValue3 = inputField3.value;

                // var inputValue4 = inputField4.value;
                // var d = new Date(inputValue4);
                // var month = String(d.getMonth() + 1).padStart(2, '0');
                // var day = String(d.getDate()).padStart(2, '0');
                // var year = d.getFullYear();
                // var formatedDate1 = month + '/' + day + '/' + year;

                var inputValue5 = inputField5.value;
                var d0 = new Date(inputValue5);
                var month0 = String(d0.getMonth() + 1).padStart(2, '0');
                var day0 = String(d0.getDate()).padStart(2, '0');
                var year0 = d0.getFullYear();

                var formatedDate0 = month0 + '/' + day0 + '/' + year0;

                // var inputValue9 = inputField9.value;
                // var d1 = new Date(inputValue9);
                // var month1 = String(d1.getMonth() + 1).padStart(2, '0');
                // var day1 = String(d1.getDate()).padStart(2, '0');
                // var year1 = d1.getFullYear();

                // var formatedDate2 = month1 + '/' + day1 + '/' + year1;

                // var otDateTime = formatedDate0 + " - " + formatedDate2 + " - " + inputField7.value + "-" +inputField8.value;

                var otDateTime = inputField7.value + " To " + inputField8.value;

                var inputValue6 = inputField6.value;
                var inputValue7 = inputField7.value;
                var inputValue8 = inputField8.value;

                if (inputValue1 == "" || inputValue2 == "" || inputValue3 == "" || inputValue5 == "" || inputValue6 == "" || inputValue7 == "" || inputValue8 == "") {
                    alert_toast(" Please complete all the required details!.", 'warning');
                } else {
                    if (inputValue2 == "Unregistered badge number") {
                        alert_toast(" Invalid Employee Name!", 'error');
                        return false;
                    }
                    if (inputValue1.trim() !== "") {
                        var newRow = tableBody.insertRow(tableBody.rows.length);
                        var cell1 = newRow.insertCell(0);
                        cell1.innerHTML = inputValue1;
                        cell1.classList.add("center-text");

                        var cell2 = newRow.insertCell(1);
                        cell2.innerHTML = inputValue2;

                        var cell3 = newRow.insertCell(2);
                        cell3.innerHTML = inputValue3;
                        cell3.classList.add("center-text");

                        var cell4 = newRow.insertCell(3);
                        cell4.innerHTML = formatedDate0;
                        cell4.classList.add("center-text");

                        var cell5 = newRow.insertCell(4);
                        cell5.innerHTML = otDateTime;
                        cell5.classList.add("center-text");

                        var cell6 = newRow.insertCell(5);
                        cell6.innerHTML = inputValue6;

                        var actionCell = newRow.insertCell(6);
                        actionCell.appendChild(createDeleteButton());
                        actionCell.classList.add("center-text");
                    }

                    // Save the cell value to the array
                    cellValues1.push(inputValue1);
                    cellValues2.push(inputValue2);
                    cellValues3.push(inputValue3);
                    // cellValues4.push(inputValue4);
                    cellValues5.push(inputValue5);
                    cellValues6.push(inputValue6);
                    cellValues7.push(inputValue7);
                    cellValues8.push(inputValue8);
                    // cellValues9.push(inputValue9);

                    // Update the hidden input value
                    document.getElementById("empNumber").value = cellValues1.join(',');
                    document.getElementById("empName").value = cellValues2.join('/');
                    document.getElementById("workShift").value = cellValues3.join(',');
                    // document.getElementById("dateRequested1").value = cellValues4.join(',');
                    document.getElementById("otDateFrom1").value = cellValues5.join(',');
                    document.getElementById("otTimeFrom1").value = cellValues7.join(',');
                    document.getElementById("otTimeTo1").value = cellValues8.join(',');
                    document.getElementById("otReason").value = cellValues6.join(',');
                    document.getElementById("otReason").value = cellValues6.join('^^');
                    // document.getElementById("otDateTo1").value = cellValues9.join(',');

                    modal.style.display = "none";
                    inputField1.value = "";
                    inputField2.value = "";
                    inputField3.value = "";
                    // inputField4.value = "";
                    inputField5.value = "";
                    inputField6.value = "";
                    inputField7.value = "";
                    inputField8.value = "";
                    // inputField9.value = "";
                }

            };

            // Close the modal if the user clicks outside of it
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            };

            // Function to create a delete button
            function createDeleteButton() {
                var button = document.createElement("button");
                button.innerHTML = '<i class="fas fa-trash"></i>';
                button.className = "btn btn-flat btn-sm btn-danger"; // Add the class
                button.addEventListener("click", function() {
                    var row = this.parentNode.parentNode;
                    var value = row.cells[0].textContent;
                    console.log(value);
                    // Remove the row
                    tableBody.removeChild(row);
                    // Update the cellValues array
                    var index = cellValues1.indexOf(value);

                    if (index !== -1) {
                        cellValues1.splice(index, 1);
                        cellValues2.splice(index, 1);
                        cellValues3.splice(index, 1);
                        // cellValues4.splice(index, 1);
                        cellValues5.splice(index, 1);
                        cellValues6.splice(index, 1);
                        cellValues7.splice(index, 1);
                        cellValues8.splice(index, 1);
                        // cellValues9.splice(index, 1);


                    }
                    // Update the hidden input value
                    document.getElementById("empNumber").value = cellValues1.join(', ');
                    document.getElementById("empName").value = cellValues2.join('/');
                    document.getElementById("workShift").value = cellValues3.join(', ');
                    //   document.getElementById("dateRequested1").value = cellValues4.join(', ');
                    document.getElementById("otDateFrom1").value = cellValues5.join(', ');
                    document.getElementById("otTimeFrom1").value = cellValues6.join(', ');
                    document.getElementById("otTimeTo1").value = cellValues7.join(', ');
                    document.getElementById("otReason").value = cellValues8.join('^^ ');
                    //   document.getElementById("otDateTo1").value = cellValues9.join(', ');

                });
                return button;
            }

            function showHint(str) {

                if (str.length == 0) {
                    document.getElementById("requestor_name").value = "";
                    // document.getElementById("requestor_department").value = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("requestor_name").value = this.responseText;
                            // document.getElementById("requestor_department").value = this.responseText;
                        }
                    };
                    xmlhttp.open("GET", "../get_emp.php?q=" + str, true);
                    xmlhttp.send();

                    // var xmlhttp = new XMLHttpRequest();
                    // xmlhttp.onreadystatechange = function() {
                    //     if (this.readyState == 4 && this.status == 200) {
                    //         document.getElementById("requestor_department").value = this.responseText;
                    //     }
                    // };
                    // xmlhttp.open("GET", "../get_dept.php?q=" + str, true);
                    // xmlhttp.send();

                }
            }

            $('#ot-form').submit(function(e) {
                e.preventDefault();
                messageType = 2;

                var _this = $(this)
                $('.err-msg').remove();
                start_loader();
                $.ajax({
                    url: _base_url_ + "classes/Master.php?f=save_ot",
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    dataType: 'json',
                    error: err => {
                        console.log(err)
                        alert_toast("An error occured", 'error');
                        end_loader();
                    },
                    success: function(resp) {
                        if (resp.status == 'success') {
                            location.replace(_base_url_ + "admin/?page=overtimeForm/view_ot&id=" + resp.id);
                        } else if (resp.status == 'failed' && !!resp.msg) {
                            var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                        } else {
                            alert_toast(" Empty employee list !", 'error');
                            end_loader();
                            console.log(resp)
                        }
                        $('html,body').animate({
                            scrollTop: 0
                        }, 'fast')
                    }
                })
            })

            document.getElementById("ot-form").addEventListener("keydown", function(event) {
                // Check if the Enter key is pressed (key code 13)
                if (event.key === "Enter") {
                    // Prevent the default form submission behavior
                    event.preventDefault();
                }
            });

            // Select the date inputs by their IDs
            var coDateFromInput = $("#coDateFrom");
            var coDateToInput = $("#coDateTo");

            // Attach an event listener to both date inputs
            coDateFromInput.on("change", function() {
                // Parse the date values and compare them
                var fromDate = new Date(coDateFromInput.val());
                var toDate = new Date(coDateToInput.val());

                // Check if coDateFrom is greater than coDateTo
                if (fromDate > toDate) {
                    alert_toast(" Payroll Cut-off Start date cannot be greater than End date.", 'error');
                    coDateFromInput.val(""); // Clear the input
                }
            });

            coDateToInput.on("change", function() {
                // Parse the date values and compare them
                var fromDate = new Date(coDateFromInput.val());
                var toDate = new Date(coDateToInput.val());

                // Check if coDateFrom is greater than coDateTo
                if (fromDate > toDate) {
                    alert_toast(" Payroll Cut-off End date cannot be less than Start date.", 'error');
                    coDateToInput.val(""); // Clear the input
                }
            });
        </script>