<!-- <img src="<?php echo validate_image('uploads\telford_prms_logo.png') ?>" alt=""> -->
<?php
// if($_settings->userdata('EMPPOSITION') == 5 || $_settings->userdata('EMPPOSITION') == 4 || $_settings->userdata('EMPPOSITION') == 3){
if ($_settings->userdata('EMPPOSITION') == 5 || $_settings->userdata('EMPPOSITION') == 4 || $_settings->userdata('EMPLOYID') == '600') {
    $pendingLink = base_url . "admin/?page=overtimeApproval/approvals";
    $approvedLink = base_url . "admin/?page=overtimeApproval/approvalhistory";
}
if ($_settings->userdata('EMPPOSITION') != 5 && $_settings->userdata('EMPPOSITION') != 4 && $_settings->userdata('EMPPOSITION') != 3 && $_settings->userdata('EMPLOYID') != '600') {
    $pendingLink = base_url . "admin/?page=overtimeForm/pending";
    $approvedLink = base_url . "admin/?page=overtimeForm/approved";
    $disapprovedLink = base_url . "admin/?page=overtimeForm/disapproved";
}
?>
<h3 class="">Dashboard</h3><br>
<div class="row">
    <div class="col-md-12">
        <?php if ($_settings->userdata('EMPPOSITION') >= 2) { ?>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Overtime</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-primary elevation-1"><a href="<?php echo $pendingLink ?>"><i class="fas fa-file-invoice"></i></a></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Pending OTR</span>
                                    <span class="info-box-number text-right">
                                        <?php
                                        if ($_settings->userdata('EMPPOSITION') == 5) {
                                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 1 ORDER BY `date_created` desc");
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 4) {
                                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$_settings->userdata('DEPARTMENT')}' 
                                                            AND `productline` = '{$_settings->userdata('PRODLINE')}') ORDER BY `date_created` desc");
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 4) { // Lean
                                            if ($_settings->userdata('EMPLOYID') == '1694') {
                                                $dept1 = "MIS";
                                                $dept2 = "Facilities";
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    (`department` = '{$dept1}' OR `department` = '{$dept2}') ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPLOYID') == '702') { // Joan
                                                $dept1 = 'Finance';
                                                $dept2 = 'Purchasing';
                                                $prodline1 = 'G & A';

                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPLOYID') == '524') { // Charity
                                                $dept1 = 'Human Resource';
                                                $dept2 = 'Training';

                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    (`department` = '{$dept1}' OR `department` = '{$dept2}') ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPLOYID') == '8563') { // Bryan
                                                $dept1 = 'Production';
                                                $dept2 = 'Production - QFP';
                                                $dept3 = 'Production - RFC';
                                                $dept4 = 'Production / Non - TNR';
                                                $prodline1 = 'PL1 - PL4';
                                                $prodline2 = 'PL1 (ADGT)';
                                                $prodline3 = 'PL4 (ADGT)';
                                                $prodline4 = 'PL6 (ADLT)';

                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}')) ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPLOYID') == '20') { // Noel
                                                $dept1 = 'Production';
                                                $dept2 = 'Store';
                                                $dept3 = 'IQA Warehouse';
                                                $dept4 = 'Logistics';
                                                $prodline1 = 'PL9 (AD/WHSE)';
                                                $prodline2 = 'G & A';
                                                $prodline3 = 'PL8 (AMS O/S)';

                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    (((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') 
                                                                        AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) 
                                                                    ORDER BY `date_created` desc");
                                            }
                                            // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODLINE') == 'PL6 (ADLT)') {
                                            //     $dept1 = 'Production';
                                            //     $dept2 = 'Production / Non - TNR';
                                            //     $prodline1 = 'PL6 (ADLT)';

                                            //     $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                            //                             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline2}')) ORDER BY `date_created` desc");
                                            // }
                                            if ($_settings->userdata('EMPLOYID') == '297') { // Erwin
                                                $dept1 = 'Quality Assurance';
                                                $prodline1 = 'G & A';
                                                $prodline2 = 'PL1 - PL4';
                                                $prodline3 = 'PL1 (ADGT)';
                                                $prodline4 = 'PL2 (AD/OS)';
                                                $prodline5 = 'PL3 (ADCV)';
                                                $prodline6 = 'PL3 (ADCV) - Onsite';
                                                $prodline7 = 'PL4 (ADGT)';
                                                $prodline8 = 'PL6 (ADLT)';
                                                $prodline9 = 'PL8 (AMS O/S)';

                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                                        OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}'
                                                                        OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) ORDER BY `date_created` desc");
                                            }
                                            if (($_settings->userdata('EMPLOYID') == '1023')) { // Adonis
                                                $dept1 = 'Equipment Engineering';
                                                $prodline1 = 'G & A';
                                                $prodline2 = 'PL1 (ADGT)';
                                                $prodline3 = 'PL2 (AD/OS)';
                                                $prodline4 = 'PL3 (ADCV)';
                                                $prodline5 = 'PL3 (ADCV) - Onsite';
                                                $prodline6 = 'PL4 (ADGT)';
                                                $prodline7 = 'PL6 (ADLT)';
                                                $prodline8 = 'PL8 (AMS O/S)';

                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                                        OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}'
                                                                        OR `productline` = '{$prodline8}')) ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPLOYID') == '1170') { // Realyn
                                                $dept1 = 'Process Engineering';
                                                $prodline1 = 'G & A';
                                                $prodline2 = 'PL1 - PL4';
                                                $prodline3 = 'PL2 (AD/OS)';
                                                $prodline4 = 'PL3 (ADCV)';
                                                $prodline5 = 'PL3 (ADCV) - Onsite';
                                                $prodline6 = 'PL6 (ADLT)';
                                                $prodline7 = 'PL8 (AMS O/S)';

                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' 
                                                                        OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPLOYID') == '1065') { // Tess
                                                $dept1 = 'Production';
                                                $dept2 = 'Production / PE';
                                                $prodline1 = 'PL2 (AD/OS)';

                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND
                                                                    ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') ORDER BY `date_created` desc");
                                            }
                                        }
                                        if ($_settings->userdata('EMPPOSITION') == 3) {
                                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");

                                            if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
                                                $dept1 = "PPC";
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND `department` = '{$dept1}' ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPLOYID') == '600') { // Christine
                                                $dept1 = 'Production';
                                                $prodline1 = 'PL3 (ADCV)';
                                                $prodline2 = 'PL3 (ADCV) - Onsite';
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$dept1}' 
                                                                AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}')) ORDER BY `date_created` desc");
                                            }
                                        }
                                        // if ($_settings->userdata('EMPLOYID') == '600') {
                                        //     $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$_settings->userdata('DEPARTMENT')}' 
                                        //                         AND `productline` = '{$_settings->userdata('PRODLINE')}') ORDER BY `date_created` desc");
                                        // }
                                        if ($_settings->userdata('EMPPOSITION') == 2) {
                                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                        }
                                        if (empty($_settings->userdata('EMPNAME'))) {
                                            $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 ORDER BY `date_created` desc");
                                        }

                                        $appvalsCount = $qry->num_rows;
                                        echo $appvalsCount;
                                        ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <?php
                        if ($_settings->userdata('EMPPOSITION') == 2 || (empty($_settings->userdata('EMPNAME')))) {
                        ?>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box bg-light shadow">
                                    <span class="info-box-icon bg-warning elevation-1"><a href="<?php echo $pendingLink ?>"><i class="fas fa-file-invoice"></i></a></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Partially Approved OTR</span>
                                        <span class="info-box-number text-right">
                                            <?php
                                            $appvalsCount = 0;
                                            if ($_settings->userdata('EMPPOSITION') == 2) {
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 1 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                            }
                                            if (empty($_settings->userdata('EMPNAME'))) {
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 1 ORDER BY `date_created` desc");
                                            }
                                            $appvalsCount = $qry->num_rows;
                                            echo $appvalsCount;
                                            ?>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box bg-light shadow">
                                    <span class="info-box-icon bg-success elevation-1"><a href="<?php echo $approvedLink ?>"><i class="fas fa-file-invoice"></i></a></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Approved OTR</span>
                                        <span class="info-box-number text-right">
                                            <?php
                                            $appvalsCount = 0;
                                            if ($_settings->userdata('EMPPOSITION') == 2) {
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                            }
                                            if (empty($_settings->userdata('EMPNAME'))) {
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 2 ORDER BY `date_created` desc");
                                            }
                                            $appvalsCount = $qry->num_rows;
                                            echo $appvalsCount;
                                            ?>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box bg-light shadow">
                                    <span class="info-box-icon bg-danger elevation-1"><a href="<?php echo $disapprovedLink ?>"><i class="fas fa-file-invoice"></i></a></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Disapproved OTR</span>
                                        <span class="info-box-number text-right">
                                            <?php
                                            if ($_settings->userdata('EMPPOSITION') == 2) {
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 3 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                            }
                                            if (empty($_settings->userdata('EMPNAME'))) {
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 3 ORDER BY `date_created` desc");
                                            }
                                            $appvalsCount = $qry->num_rows;
                                            echo $appvalsCount;
                                            ?>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if ($_settings->userdata('EMPPOSITION') == 4 || $_settings->userdata('EMPLOYID') == '600') {
                        ?>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box bg-light shadow">
                                    <span class="info-box-icon bg-success elevation-1"><a href="<?php echo $approvedLink ?>"><i class="fas fa-file-invoice"></i></a></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Signed OTR</span>
                                        <span class="info-box-number text-right">
                                            <?php
                                            $appvalsCount = 0;

                                            if ($_settings->userdata('EMPPOSITION') == 5) {
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `od_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPPOSITION') == 4) {
                                                $qry = $conn->query("SELECT * FROM overtime_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                            }
                                            if ($_settings->userdata('EMPPOSITION') == 3) {
                                                if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
                                                    $dept1 = "PPC";
                                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                                }
                                                if ($_settings->userdata('EMPLOYID') == '600') { // Ma. Christine
                                                    $dept1 = 'Production';
                                                    $prodline1 = 'PL3 (ADCV)';
                                                    $prodline2 = 'PL3 (ADCV) - Onsite';
                                                    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$dept1}' AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}')) AND `dh_name` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc");
                                                }
                                            }

                                            $appvalsCount = $qry->num_rows;
                                            echo $appvalsCount;
                                            ?>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        <?php
                        }
                        ?>



                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        <?php } ?>
        <!-- /.card -->

        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header">
                <h3 class="card-title"><b>IR/DA</b></h3>&nbsp;
                <?php
                if ($_settings->userdata('DEPARTMENT') == 'Human Resource'  || $_settings->userdata('EMPLOYID') == 1191) {
                    echo ($dis_qry + $qryyy + $issue_da) > 0 ? ' <span class="badge badge-warning">New</span>' : '';
                } else {
                    echo ($dis_qry + $qryyy) > 0 ? ' <span class="badge badge-warning">New</span>' : '';
                }
                ?>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row justify-content-left">
                    <?php
                    if ($_settings->userdata('EMPPOSITION') > 2 || $_settings->userdata('DEPARTMENT') == 'Human Resource' || $_settings->userdata('EMPLOYID') == 1191 || $is_quality > 0) {
                    ?>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box bg-light shadow">
                                <span class="info-box-icon bg-primary elevation-1"><a href="<?php echo base_url . "admin/?page=incidentreport/approveIR" ?>"><i class="fas fa-file-invoice"></i></a></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">For Approvals</span>
                                    <span class="info-box-number text-right">
                                        <?php if (($_settings->userdata('DEPARTMENT') == 'Human Resource'  || $_settings->userdata('EMPLOYID') == 1191) && $_settings->userdata('EMPPOSITION') > 4) { ?>
                                            <?php echo $qryyy + $issue_da ?>
                                        <?php  } else { ?>
                                            <?php echo $qryyy  ?>
                                        <?php } ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <hr>
                <div class="row  justify-content-left">

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-warning elevation-1"><a href="<?php echo base_url . "admin/?page=inbox/ircompletion" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pending IR</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    echo $inbox_ir
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-secondary elevation-1"><a href="<?php echo base_url . "admin/?page=inbox/issuedDA" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pending DA</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    echo $inbox_da
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box bg-light shadow">
                            <span class="info-box-icon bg-danger elevation-1"><a href="<?php echo base_url . "admin/?page=incidentreport/disapprovedIRDA" ?>"><i class="fas fa-file-invoice"></i></a></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Invalid requests</span>
                                <span class="info-box-number text-right">
                                    <?php
                                    echo $dis_qry
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                </div>



            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>