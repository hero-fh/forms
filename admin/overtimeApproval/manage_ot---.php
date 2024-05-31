<?php 
$qry = $conn->query("SELECT * FROM overtime_requests where id = '{$_GET['id']}'");
if($qry->num_rows >0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}

$qry1 = $conn->query("SELECT * FROM employee_masterlist where EMPLOYID = '{$requestor_id}'");
if($qry1->num_rows >0){
    foreach($qry1->fetch_array() as $k1 => $v1){
        $$k1 = $v1;
    }
}

?>
    <div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Overtime Request Details : <?php echo $ot_form_no ?>&nbsp;
            <?php if($ot_status == 0): ?>
                <span class="badge badge-primary rounded-pill"> Pending</span>
            <?php elseif($ot_status == 1): ?>
                <span class="badge badge-warning rounded-pill"> Approved</span>
                <?php elseif($ot_status == 2): ?>
                <span class="badge badge-success rounded-pill"> Recieved</span>
            <?php else: ?>
                <span class="badge badge-danger rounded-pill"> Cancelled</span>
            <?php endif; ?>
        </h4>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label text-info">PREPARED BY</label>
                    <div><?php echo isset($EMPNAME) ? $EMPNAME : '' ?></div>
                    <?php
                        $dateCreated = new DateTime($date_created);

                        $newdateCreated = $dateCreated->format('m-d-Y h:i:s a');
                    ?>
                    <div><?php echo isset($date_created) ? $newdateCreated : '' ?></div>
                </div>           
            </div> 
            <HR> 
              
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label text-info">DEPARTMENT</label>
                    <div><?php echo isset($department) ? $department : '' ?></div>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-info"> PRODUCTLINE</label>
                    <div><?php echo isset($productline) ? $productline : '' ?></div>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-info">PAYROLL CUT-OFF</label>
                    <?php

                    $date = new DateTime($date_from);
                    $date0 = new DateTime($date_to);

                    $newDateformat = $date->format('m-d-Y');
                    $newDateformat0 = $date0->format('m-d-Y');
                    ?>
                    <div><?php echo isset($ot_form_no) ? $newDateformat." To ".$newDateformat0 : '' ?></div>
                </div>
            </div>
            <hr>
            <fieldset>
                <p class="text-center">The following employees have expressed their request and willingness to render overtime work on the date and time as specified below.</p>               
                <!-- Table to display the added inputs -->
                <table class="table table-striped table-bordered" id="dataTable">
                <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2">EMP. NO.</th>
                        <th class="text-center py-1 px-2">EMP. NAME</th>
                        <th class="text-center py-1 px-2">WORK SHIFT</th>
                        <th class="text-center py-1 px-2">DATE REQUESTED</th>
                        <th class="text-center py-1 px-2">OT DATE TIME</th>
                        <th class="text-center py-1 px-2">REASON</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM overtime_items WHERE ot_form_code = '{$ot_form_no}'");
                    while($row = $qry->fetch_assoc()):
                    ?>                                
                        <tr>
                            <td class="text-center"><?php echo $row['emp_num'] ?></td>
                            <td class="text-center"><?php echo $row['emp_name'] ?></td>
                            <td class="text-center"><?php echo $row['work_shift'] ?></td>
                            <?php
                                $date1 = new DateTime($row['ot_date_from']);
                                // $date2 = new DateTime($row['ot_date_to']);

                                $newDateformat1 = $date1->format('m-d-Y');
                                // $newDateformat2 = $date2->format('m-d-Y');
                            ?>

                            <td class="text-center"><?php echo $newDateformat1 ?></td>
                            <td class="text-center"><?php echo $row['ot_time_from']." To ".$row['ot_time_to']?></td>
                            <td class="text-center"><?php echo $row['ot_reason'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                </table>
            </fieldset>
            <HR> 
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info">APPROVED BY</label>
                    <small><i class="text-info">DEPARTMENT HEAD</i></small>
                    <?php
                        if($_settings->userdata('TYPE') == 4 && $dh_status == 0){
                    ?>
                        <div>
                            <button class="btn btn-flat btn-sm btn-outline-success approve_data" type="button" data-id="<?php echo $_GET['id'] ?>" data-appr="<?php echo $_settings->userdata('EMPLOYID') ?>" data-apptype="<?php echo $_settings->userdata('TYPE') ?>"> <i class="fas fa-thumbs-up"></i> Approved</button>
                            <button class="btn btn-flat btn-sm btn-outline-danger disapprove_data" type="button" data-id="<?php echo $_GET['id'] ?>" data-appr="<?php echo $_settings->userdata('EMPLOYID') ?>" data-apptype="<?php echo $_settings->userdata('TYPE') ?>"> <i class="fas fa-thumbs-down"></i> Disapproved</button>
                        </div>
                    <?php
                        }
                    ?>
                    <?php
                        if($_settings->userdata('TYPE') != 4 && $dh_status == 0){
                    ?>
                    <div>N/A</div>
                    <?php
                        }
                    ?>
                    <?php
                        if($dh_status != 0){
                    ?>
                    <?php
                        if($dh_name !== null){
                            $qry2 = $conn->query("SELECT * FROM employee_masterlist where EMPLOYID = '{$dh_name}'");
                            if($qry2->num_rows >0){
                                foreach($qry2->fetch_array() as $k2 => $v2){
                                    $$k2 = $v2;
                                }
                            }
                        }
                    ?>
                    <div><?php echo isset($EMPNAME) ? $EMPNAME : '' ?></div>
                    <?php
                        $dateCreated = new DateTime($dh_sign_date);

                        $newdateCreated = $dateCreated->format('m-d-Y h:i:s a');
                    ?>
                    <div><?php echo isset($dh_sign_date) ? $newdateCreated : '' ?></div>
                    <?php
                        }
                    ?>
                </div>
                <div class="col-md-6">
                    <label class="control-label text-info">APPROVED BY</label>
                    <small><i class="text-info">OPERATIONS DIRECTOR</i></small>
                    <?php
                        if($_settings->userdata('TYPE') == 5 && $od_status == 0){
                    ?>
                        <div>
                            <button class="btn btn-flat btn-sm btn-outline-success approve_data" type="button" data-id="<?php echo $_GET['id'] ?>" data-appr="<?php echo $_settings->userdata('EMPLOYID') ?>" data-apptype="<?php echo $_settings->userdata('TYPE') ?>"> <i class="fas fa-thumbs-up"></i> Approved</button>
                            <button class="btn btn-flat btn-sm btn-outline-danger disapprove_data" type="button" data-id="<?php echo $_GET['id'] ?>" data-appr="<?php echo $_settings->userdata('EMPLOYID') ?>" data-apptype="<?php echo $_settings->userdata('TYPE') ?>"> <i class="fas fa-thumbs-down"></i> Disapproved</button>
                        </div>
                    <?php
                        }
                    ?>
                    <?php
                        if($_settings->userdata('TYPE') != 5 && $od_status == 0){
                    ?>
                    <div>N/A</div>
                    <?php
                        }
                    ?>
                    <?php
                        if($od_status != 0){
                    ?>
                    <?php
                        if($od_name !== null){
                            $qry2 = $conn->query("SELECT * FROM employee_masterlist where EMPLOYID = '{$od_name}'");
                            if($qry2->num_rows >0){
                                foreach($qry2->fetch_array() as $k2 => $v2){
                                    $$k2 = $v2;
                                }
                            }
                        }
                    ?>
                    <div><?php echo isset($EMPNAME) ? $EMPNAME : '' ?></div>
                    <?php
                        $dateCreated = new DateTime($od_sign_date);

                        $newdateCreated = $dateCreated->format('m-d-Y h:i:s a');
                    ?>
                    <div><?php echo isset($od_sign_date) ? $newdateCreated : '' ?></div>
                    <?php
                        }
                    ?>
                </div>
                <div class="col-md-4">
                    <!-- <label class="control-label text-info">NOTED BY</label>
                    <small><i class="text-info">HR DEPARTMENT</i></small> -->
                    <?php
                        //if($_settings->userdata('DEPARTMENT') == "Human Resource" && $hr_status == 0){
                    ?>
                        <!-- <div>
                            <button class="btn btn-flat btn-sm btn-outline-success approve_data" type="button" data-id="<?php echo $_GET['id'] ?>" data-appr="<?php echo $_settings->userdata('EMPLOYID') ?> " data-apptype="<?php echo $_settings->userdata('TYPE') ?> "><i class="fas fa-thumbs-up"></i> Approved</button>
                            <button class="btn btn-flat btn-sm btn-outline-danger disapprove_data" type="button" data-id="<?php echo $_GET['id'] ?>" data-appr="<?php echo $_settings->userdata('EMPLOYID') ?>" data-apptype="<?php echo $_settings->userdata('TYPE') ?> "><i class="fas fa-thumbs-up"></i> Disapproved</button>
                        </div> -->
                    <?php
                       // }
                    ?>
                    <?php
                       // if($hr_status != 0){
                    ?>
                    <?php
                        // if($dh_name !== null){
                        //     $qry2 = $conn->query("SELECT * FROM employee_masterlist where EMPLOYID = '{$dh_name}'");
                        //     if($qry2->num_rows >0){
                        //         foreach($qry2->fetch_array() as $k2 => $v2){
                        //             $$k2 = $v2;
                        //         }
                        //     }
                        // }
                    ?>
                    <div><?php //echo isset($EMPNAME) ? $EMPNAME : '' ?></div>
                    <div><?php //echo isset($hr_name) ? $HR : '' ?></div>
                    <?php
                        //$dateCreated = new DateTime($hr_sign_date);

                       // $newdateCreated = $dateCreated->format('m-d-Y h:i:s a');
                    ?>
                    <div><?php //echo isset($hr_sign_date) ? $newdateCreated : '' ?></div>
                    <?php
                        //}
                    ?>
                    <?php
                        //if($_settings->userdata('DEPARTMENT') != "Human Resource" && $hr_status == 0){
                    ?>
                    <!-- <div>N/A</div> -->
                    <?php
                        //}
                    ?>
                </div>
            </div>         
        </div>
    </div>
            
<script>
    	$(document).ready(function(){
		$('.approve_data').click(function(){
			_conf("Are you sure you want to APPROVE this Overtime Request permanently?","sign_po_approve",[$(this).attr('data-id'),$(this).attr('data-appr'),$(this).attr('data-apptype')])
		})
        $('.disapprove_data').click(function(){
			_conf("Are you sure you want to DISAPPROVE this Overtime Request permanently?","sign_po_disapprove",[$(this).attr('data-id'),$(this).attr('data-appr'),$(this).attr('data-apptype')])
		})
	})

    function sign_po_approve($id,$appr,$apptype){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=sign_po_approve",
			method:"POST",
			data:{id: $id, approverNum: $appr, appType: $apptype},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace(_base_url_+"admin/?page=overtime_form/view_ot&id="+resp.id);
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}

    function sign_po_disapprove($id,$appr,$apptype){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=sign_po_disapprove",
			method:"POST",
			data:{id: $id, approverNum: $appr, appType: $apptype},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace(_base_url_+"admin/?page=overtime_form/view_ot&id="+resp.id);
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}

    $(function(){
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Purchase Order Details - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-navy")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Purchase Order</h4>'+
                      '</div>'+
                      '<div class="col-1 text-right">'+
                      '</div>'+
                      '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes")
                     nw.document.write(_el.html())
                     nw.document.close()
                     setTimeout(() => {
                         nw.print()
                         setTimeout(() => {
                            nw.close()
                            end_loader()
                         }, 200);
                     }, 500);
        })
    })
</script>