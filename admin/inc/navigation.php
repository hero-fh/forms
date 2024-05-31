</style>
<?php
//----------------------------------------------- ir --------------------------------------- //
$word = $_settings->userdata('JOB_TITLE');
// Function to extract 'Auditor' from a word
function extractAuditor($word)
{
  // Use preg_match to find the word 'Auditor'
  if (preg_match('/\bAuditor\b/', $word, $matches)) {
    return $matches[0];
  } else {
    return null;
  }
}
$is_operator = $conn->query("SELECT * FROM ir_operator where emp_no = '{$_settings->userdata('EMPLOYID')}' and status = 1  and to_handle = 1")->num_rows;
$is_quality = $conn->query("SELECT * FROM ir_operator where emp_no = '{$_settings->userdata('EMPLOYID')}' and status = 1  and to_handle = 2")->num_rows;


// ----
{
  // if (($_settings->userdata('DEPARTMENT') == 'Human Resource' && $_settings->userdata('EMPPOSITION') < 4) || $_settings->userdata('EMPLOYID') == 1191) {
  //   $qryyy = $conn->query("SELECT * FROM ir_requests where (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //     (hr_status = 0 and ($is_operator > 0)) or 
  //     (hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and da_status = 0) or 
  //     (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and ($is_operator > 0) and da_status = 0 and quality_violation = 1) or
  //     (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
  //      ORDER BY `date_created` desc")->num_rows;
  // } else {
  //   if ($_settings->userdata('EMPPOSITION') == 5) {
  //     $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //         (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //         (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and da_status = 1 and appeal_status = 2 and ir_status = 1)
  //         ORDER BY `date_created` desc")->num_rows;
  //   } else {
  //     if ($_settings->userdata('EMPLOYID') == '1694') { // Leand 1694
  //       $dept1 = "MIS";
  //       $dept2 = "Facilities";
  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
  //             (`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //              ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 3) or
  //              ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
  //               ORDER BY `date_created` desc")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == '702') { // Joan
  //       $dept1 = 'Finance';
  //       $dept2 = 'Purchasing';
  //       $prodline1 = 'G & A';

  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}' AND `productline` = '{$prodline1}') and ir_status = 2 and da_status = 3) or
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
  //              ORDER BY `date_created` desc")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == '524') { // Charity
  //       $dept1 = 'Human Resource';
  //       $dept2 = 'Training';

  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and
  //             (`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             ((`department` = '{$dept1}' or `department` = '{$dept2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and da_status = 1 and appeal_status = 1 and ir_status = 1) or
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 3) or
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             (ir_status = 2 and da_status = 1 and has_da = 1))
  //             ORDER BY `date_created` desc")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == '8563') { // Bryan
  //       $dept1 = 'Production';
  //       $dept2 = 'Production - QFP';
  //       $dept3 = 'Production - RFC';
  //       $dept4 = 'Production / Non - TNR';
  //       $prodline1 = 'PL1 - PL4';
  //       $prodline2 = 'PL1 (ADGT)';
  //       $prodline3 = 'PL4 (ADGT)';
  //       $prodline4 = 'PL6 (ADLT)';

  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND (`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or 
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and da_status = 1 and appeal_status IN (4, 5, 3) and ir_status = 1) or 
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and ir_status = 2 and da_status = 3) or
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}') and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')
  //             ORDER BY `date_created` DESC")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == '20') { // Noel
  //       $dept1 = 'Production';
  //       $dept2 = 'Store';
  //       $dept3 = 'IQA Warehouse';
  //       $dept4 = 'Logistics';
  //       $prodline1 = 'PL9 (AD/WHSE)';
  //       $prodline2 = 'G & A';
  //       $prodline3 = 'PL8 (AMS O/S)';


  //       $prodline4 = 'PL3 (ADCV)';
  //       $prodline5 = 'PL3 (ADCV) - Onsite';






  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             (((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and ir_status = 2 and da_status = 3) or
  //             ((((`department` = '{$dept1}' OR `department` = '{$dept2}' OR `department` = '{$dept3}' OR `department` = '{$dept4}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}'))) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')) or


  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND (`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and  ir_status = 2 and da_status = 3) or
  //             ((`productline` = '{$prodline4}' OR `productline` = '{$prodline5}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}')
  //             ))
  //                 ORDER BY `date_created` desc")->num_rows;
  //     }
  //     // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL6 (ADLT)') {
  //     //     $dept1 = 'Production';
  //     //     $dept2 = 'Production / Non - TNR';
  //     //     $prodline1 = 'PL6 (ADLT)';

  //     //     $qryyy = $conn->query("SELECT * FROM ir_requests WHERE hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
  //     //                             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND (`productline` = '{$prodline2}')) and da_status = 1 and appeal_status = 0 and ir_status = 1 ORDER BY `date_created` desc")->num_rows;
  //     // }
  //     elseif ($_settings->userdata('EMPLOYID') == '297') { // Erwin
  //       $dept1 = 'Quality Assurance';
  //       $prodline1 = 'G & A';
  //       $prodline2 = 'PL1 - PL4';
  //       $prodline3 = 'PL1 (ADGT)';
  //       $prodline4 = 'PL2 (AD/OS)';
  //       $prodline5 = 'PL3 (ADCV)';
  //       $prodline6 = 'PL3 (ADCV) - Onsite';
  //       $prodline7 = 'PL4 (ADGT)';
  //       $prodline8 = 'PL6 (ADLT)';
  //       $prodline9 = 'PL8 (AMS O/S)';

  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
  //             ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 0 and ir_status = 1) or 
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 4 and ir_status = 1) or 
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 5 and ir_status = 1) or 
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and da_status = 1 and appeal_status = 3 and ir_status = 1) or 
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and ir_status = 2 and da_status = 3) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}' OR `productline` = '{$prodline9}')) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
  //                 ORDER BY `date_created` desc")->num_rows;
  //     } elseif (($_settings->userdata('EMPLOYID') == '1023')) { // Adonis
  //       $dept1 = 'Equipment Engineering';
  //       $prodline1 = 'G & A';
  //       $prodline2 = 'PL1 (ADGT)';
  //       $prodline3 = 'PL2 (AD/OS)';
  //       $prodline4 = 'PL3 (ADCV)';
  //       $prodline5 = 'PL3 (ADCV) - Onsite';
  //       $prodline6 = 'PL4 (ADGT)';
  //       $prodline7 = 'PL6 (ADLT)';
  //       $prodline8 = 'PL8 (AMS O/S)';

  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
  //             ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and ir_status = 2 and da_status = 3) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' OR `productline` = '{$prodline8}')) and ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
  //             ORDER BY `date_created` asc")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == '1170') { // Realyn
  //       $dept1 = 'Process Engineering';
  //       $prodline1 = 'G & A';
  //       $prodline2 = 'PL1 - PL4';
  //       $prodline3 = 'PL2 (AD/OS)';
  //       $prodline4 = 'PL3 (ADCV)';
  //       $prodline5 = 'PL3 (ADCV) - Onsite';
  //       $prodline6 = 'PL6 (ADLT)';
  //       $prodline7 = 'PL8 (AMS O/S)';

  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
  //             ((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and  ir_status = 2 and da_status = 3) or
  //             (((`department` = '{$dept1}') AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}' OR `productline` = '{$prodline3}' OR `productline` = '{$prodline4}' OR `productline` = '{$prodline5}' OR `productline` = '{$prodline6}' OR `productline` = '{$prodline7}' )) and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
  //              ORDER BY `date_created` desc")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == '1065') { // Tess
  //       $dept1 = 'Production';
  //       $dept2 = 'Production / PE';
  //       $prodline1 = 'PL2 (AD/OS)';

  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND
  //             ((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and  ir_status = 2 and da_status = 3) or
  //             (((`department` = '{$dept1}' OR `department` = '{$dept2}') AND `productline` = '{$prodline1}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
  //             ORDER BY `date_created` asc")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
  //       $dept1 = "PPC";
  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND 
  //             `department` = '{$dept1}' and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             (`department` = '{$dept1}' and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             (`department` = '{$dept1}' and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             (`department` = '{$dept1}' and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             (`department` = '{$dept1}' and  ir_status = 2 and da_status = 3) or
  //             (`department` = '{$dept1}' and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
  //              ORDER BY `date_created` desc")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == '600') { // tin
  //       $prodline1 = 'PL3 (ADCV)';
  //       $prodline2 = 'PL3 (ADCV) - Onsite';
  //       $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             ((hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND 
  //             (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 0 and ir_status = 1) or
  //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 4 and ir_status = 1) or
  //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 5 and ir_status = 1) or
  //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and da_status = 1 and appeal_status = 3 and ir_status = 1) or
  //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and  ir_status = 2 and da_status = 3) or
  //             ((`productline` = '{$prodline1}' OR `productline` = '{$prodline2}') and  ir_status = 2 and da_status = 2 and sv_name = '{$_settings->userdata('EMPLOYID')}'))
  //              ORDER BY `date_created` desc")->num_rows;
  //     } elseif ($_settings->userdata('EMPLOYID') == 13019 || extractAuditor($word) == 'Auditor') { //auditor
  //       $ext = extractAuditor($word);
  //       $qryyy = $conn->query("SELECT * FROM ir_requests where 
  //             (hr_status = 0 and quality_violation = 2) or
  //             (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and quality_violation = 2 and da_status = 0) or 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //             (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //             (`ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
  //            ORDER BY `date_created` desc")->num_rows;
  //     } else { //supervisors
  //       $qryyy = $conn->query("SELECT * FROM ir_requests where 
  //             (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
  //           (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
  //           (`ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
  //          ORDER BY `date_created` desc")->num_rows;
  //     }


  //     // $qry = $conn->query("SELECT * FROM ir_requests WHERE hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 AND `requestor_id` = '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc")->num_rows;
  //   }
  // }
}
if ($is_operator > 0) {
  $qryyy = $conn->query("SELECT * FROM ir_requests where (ir_status = 3 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') )) and is_inactive = 0) or 
      (hr_status = 0 and ($is_operator > 0) and is_inactive = 0) or 
      (hr_status = 1 and why1 != '' and sv_status = 0 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') )) and is_inactive = 0 and da_status = 0) or 
      (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and ($is_operator > 0) and da_status = 0 and quality_violation = 1  and is_inactive = 0) or
      (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 2 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') )) and is_inactive = 0 and da_status = 2)
       ORDER BY `date_created` desc")->num_rows;
  $hr_da_qry = $conn->query("SELECT ir.id FROM ir_requests ir WHERE ir.ir_status = 2 AND ir.has_da = 0 AND $is_operator > 0 AND ir.is_inactive = 0 AND EXISTS (SELECT 1  FROM ir_list il WHERE ir.ir_no = il.ir_no AND il.valid = 1 AND il.offense_no REGEXP '^[0-9]+$') ORDER BY `date_created` DESC")->num_rows;

  // $hr_da_qry = $conn->query("SELECT ir.id FROM ir_requests ir inner join ir_list il on ir.ir_no = il.ir_no where ir.ir_status = 2 and ir.has_da = 0 and $is_operator > 0 and il.valid = 1 and il.offense_no REGEXP '^[0-9]+$' and ir.is_inactive = 0
  //       ORDER BY `date_created` desc LIMIT 1")->num_rows;
  // $c = $conn->query("SELECT * FROM ir_list where valid = 1 and ir_no = '{$row['ir_no']}' AND offense_no REGEXP '^[0-9]+$'")->num_rows;

} else {
  if ($_settings->userdata('EMPPOSITION') == 5) {
    $qryyy = $conn->query("SELECT * FROM ir_requests WHERE (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                      (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                      (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and da_status = 1 and appeal_status = 2 and ir_status = 1)
                      ORDER BY `date_created` asc")->num_rows;
  } elseif ($is_quality > 0) {

    $qryyy = $conn->query("SELECT * FROM ir_requests where 
                              (hr_status = 0 and quality_violation = 2) or
                              (hr_status = 1 and why1 != '' and sv_status = 1 and `ir_status` = 1 and quality_violation = 2 and da_status = 0) or 
                              (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}') or
                              (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}') or 
                              (`ir_status` = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2)
                             ORDER BY `date_created` asc")->num_rows;
  } else {
    // $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
    //                       (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name != '{$_settings->userdata('EMPLOYID')}') or
    //                       (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name != '{$_settings->userdata('EMPLOYID')}') or 
    //                       (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or #  nainvalid yung assessment ni sec head
    //                       (ir_status = 2 and sv_name ='{$_settings->userdata('EMPLOYID')}' and da_status = 2 and dh_name != '{$_settings->userdata('EMPLOYID')}') or
    //                       (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or
    //                       (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 1 and appeal_status IN (0, 4, 5, 3) and ir_status = 1 and dh_name = '{$_settings->userdata('EMPLOYID')}') or
    //                       (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 3 and  ir_status = 2 and dh_name = '{$_settings->userdata('EMPLOYID')}') or
    //                       (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 2 and  ir_status = 2 and dh_name = '{$_settings->userdata('EMPLOYID')}' and sv_name = '{$_settings->userdata('EMPLOYID')}') or
    //                       (hr_status = 1 and why1 != '' and sv_status = 1 and da_status = 1 and appeal_status = 1 and ir_status = 1 and '{$_settings->userdata('DEPARTMENT')}' = 'Human Resource' and '{$_settings->userdata('EMPPOSITION')}' > 3) or 
    //                       (ir_status = 2 and da_status = 1 and has_da = 1 and '{$_settings->userdata('DEPARTMENT')}' = 'Human Resource' and '{$_settings->userdata('EMPPOSITION')}' > 3)
    //                         ORDER BY `date_created` desc")->num_rows;
    $qryyy = $conn->query("SELECT * FROM ir_requests WHERE 
   (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) or
                                    -- (ir_status = 1 and hr_status = 1 and why1 != '' and sv_status = 0 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or # wala na to since approver 2 na din yung nasa taas
                                    (ir_status = 3 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) or 
                                    -- (ir_status = 3 and sv_name = '{$_settings->userdata('EMPLOYID')}' and dh_name = '{$_settings->userdata('EMPLOYID')}') or # wala na to since approver 2 na din yung nasa taas
                                    
                                    (hr_status = 1 and why1 != '' and sv_status = 1 and  da_status = 1 and appeal_status = 0 and ir_status = 1 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE APPROVER2 ='{$_settings->userdata('EMPLOYID')}'))
        ORDER BY `date_created` desc")->num_rows;
  }
}
$qry_da =  $conn->query("SELECT * FROM ir_requests WHERE 
              (`ir_status` = 2 and da_status = 2 and appeal_status = 0 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE ((APPROVER1 != 'na' and APPROVER1 ='{$_settings->userdata('EMPLOYID')}') or (APPROVER1 = 'na' and APPROVER2 ='{$_settings->userdata('EMPLOYID')}') ))) or
              (`ir_status` = 2 and da_status = 3 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE APPROVER3='{$_settings->userdata('EMPLOYID')}') and appeal_status = 0) or
              (appeal_status IN (3, 4, 5) and da_status = 3 and appeal_name is not null and ir_status = 2 and emp_no IN (SELECT EMPLOYID from employee_masterlist WHERE APPROVER3='{$_settings->userdata('EMPLOYID')}')) or
              (ir_status = 2 and da_status = 1 and has_da = 1 and '{$_settings->userdata('DEPARTMENT')}' = 'Human Resource' and '{$_settings->userdata('EMPPOSITION')}' > 3) or 
              (appeal_status = 1 and ir_status = 2 and '{$_settings->userdata('DEPARTMENT')}' = 'Human Resource' and '{$_settings->userdata('EMPPOSITION')}' > 3) 
                ORDER BY `date_created` desc")->num_rows;
$diff = $conn->query("SELECT ir_requests.*, ir_list.*
FROM ir_requests
INNER JOIN ir_list ON ir_requests.ir_no = ir_list.ir_no
WHERE ir_requests.ir_status = 2
  AND ir_requests.has_da = 0
  AND ir_list.valid = 1
  AND ir_list.offense_no REGEXP '[a-zA-Z0-9]'
  AND (ir_requests.sv_name = '{$_settings->userdata('EMPLOYID')}' or ir_requests.dh_name = '{$_settings->userdata('EMPLOYID')}')")->num_rows;

$for_da = $conn->query(" SELECT ir_requests.*, ir_list.*
  FROM ir_requests
  INNER JOIN ir_list ON ir_requests.ir_no = ir_list.ir_no
  WHERE ir_requests.ir_status = 2
    AND ir_requests.has_da = 0
    AND ir_list.valid = 1
    AND ir_list.offense_no REGEXP '^[0-9]+$' 
    AND ($is_operator > 0)")->num_rows;
if (!empty($_settings->userdata('EMPLOYID')) && ($_settings->userdata('EMPPOSITION') > 1 || $_settings->userdata('DEPARTMENT') == 'Human Resource' ||  $is_quality > 0  ||  $_settings->userdata('EMPLOYID') == 1191)) {
  $dis_qry = $conn->query("SELECT * FROM ir_requests where (hr_status=2 and ir_status = 0 and requestor_id = " . $_settings->userdata('EMPLOYID') . ") or  (ir_status = 3 and sv_status != 1  and requestor_id = " . $_settings->userdata('EMPLOYID') . ") ORDER BY `date_created` desc")->num_rows;
  $qryyy = $qryyy < 0 ? 0 : $qryyy;
  $inbox_ir = $conn->query("SELECT * FROM ir_requests where hr_status = 1 and ir_status !=2 and ir_status != 3 and ir_status != 4 and why1 IS NULL and emp_no = " . $_settings->userdata('EMPLOYID') . " ORDER BY `date_created` desc")->num_rows;
  $inbox_da = $conn->query("SELECT * FROM ir_requests where ir_status = 2 and has_da = 1 and acknowledge_da = 0 and da_status = 4 and emp_no =  '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc")->num_rows;


  $issue_da = $conn->query("SELECT ir.*,il.* FROM ir_requests ir INNER JOIN ir_list il ON ir.ir_no = il.ir_no where ir.ir_status = 2 and ir.has_da = 0 and il.valid = 1")->num_rows;
} elseif ($_settings->userdata('EMPPOSITION') == 1) {
  $inbox_ir = $conn->query("SELECT * FROM ir_requests where hr_status = 1 and ir_status !=2 and ir_status != 3 and ir_status != 4 and why1 IS NULL and emp_no = " . $_settings->userdata('EMPLOYID') . " ORDER BY `date_created` desc")->num_rows;
  $inbox_da = $conn->query("SELECT * FROM ir_requests where ir_status = 2 and has_da = 1 and acknowledge_da = 0 and da_status = 4 and emp_no =  '{$_settings->userdata('EMPLOYID')}' ORDER BY `date_created` desc")->num_rows;
  $dis_qry = $conn->query("SELECT * FROM ir_requests where (hr_status=2 and ir_status = 0 and requestor_id = " . $_settings->userdata('EMPLOYID') . ") or  (ir_status = 3 and sv_status != 1 and requestor_id = " . $_settings->userdata('EMPLOYID') . ") ORDER BY `date_created` desc")->num_rows;
} else {
  $dis_qry = 0;
  $qryyy = 0;
  $inbox_ir = 0;
  $inbox_da = 0;
  $issue_da = 0;
}
?>
<?php
if (!empty($_settings->userdata('EMPNAME'))) {
  $appvalsCount = 0;
  if ($_settings->userdata('EMPPOSITION') >= 4) {
    if ($_settings->userdata('EMPPOSITION') == 5) {
      $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 1 ORDER BY `date_created` desc");
    }
    if ($_settings->userdata('EMPPOSITION') == 4) {
      $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$_settings->userdata('DEPARTMENT')}' 
                                                AND `productline` = '{$_settings->userdata('PRODUCT_LINE')}') ORDER BY `date_created` desc");
    }
    if ($_settings->userdata('EMPPOSITION') == 4) {
      if ($_settings->userdata('EMPLOYID') == '1694') { // Lean
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
      // if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL6 (ADLT)') {
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

    // if ($_settings->userdata('EMPPOSITION') == 3) {
    //     if ($_settings->userdata('DEPARTMENT') == 'Production' && $_settings->userdata('PRODUCT_LINE') == 'PL3 (ADCV)') {
    //         $dept1 = "Process Engineering";
    //         $prodline1 = 'PL3';

    //         $qry = $conn->query("SELECT * FROM overtime_requests WHERE 
    //                                 (`department` = '{$dept1}' AND `productline` = '{$prodline1}') ORDER BY `date_created` desc");
    //     }
    // }
    // if ($_settings->userdata('EMPLOYID') == '600') {
    //   $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$_settings->userdata('DEPARTMENT')}' 
    //                                               AND `productline` = '{$_settings->userdata('PRODUCT_LINE')}') ORDER BY `date_created` desc");
    // }

  }
  if ($_settings->userdata('EMPPOSITION') == 3) {
    if ($_settings->userdata('EMPLOYID') == '108') { // Ma. Lourdes
      $test = "123123123";
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

  if ($_settings->userdata('EMPLOYID') == '600') { // Christine
    $dept1 = 'Production';
    $prodline1 = 'PL3 (ADCV)';
    $prodline2 = 'PL3 (ADCV) - Onsite';
    $qry = $conn->query("SELECT * FROM overtime_requests WHERE `ot_status` = 0 AND (`department` = '{$dept1}' 
                      AND (`productline` = '{$prodline1}' OR `productline` = '{$prodline2}')) ORDER BY `date_created` desc");
  }
  if ($_settings->userdata('EMPPOSITION') > 2) {
    $appvalsCount = $qry->num_rows ?? 0;
    if ($appvalsCount == 0) {
      $notif = '';
    } else {
      $notif = '<span class="badge badge-info">New</span>';
    }
  }
}
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
  <!-- Brand Logo -->
  <a href="http://192.168.1.28/hr-portal/" class="brand-link bg-primary text-sm">
    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Store Logo" class="brand-image  elevation-3 bg-black" style="height: 1.8rem;max-height: unset">
    <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
    <div class="os-resize-observer-host observed">
      <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
    </div>
    <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
      <div class="os-resize-observer"></div>
    </div>
    <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
    <div class="os-padding">
      <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
        <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
          <!-- Sidebar user panel (optional) -->
          <div class="clearfix"></div>
          <!-- Sidebar Menu -->

          <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item dropdown">
                <a href="./" class="nav-link nav-home">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              <?php
              if (!empty($_settings->userdata('EMPNAME'))) {
              ?>
                <li class="nav-item">
                  <a href="#" class="nav-link nav-is-tree nav-inbox">
                    <i class="nav-icon fas fa-envelope"></i>
                    <p>
                      Inbox
                      <i class="right fas fa-angle-left"></i>
                    </p> <?php echo $inbox_ir + $inbox_da ? '<span class="badge badge-warning">New</span>' : '' ?>
                  </a>
                  <ul class="nav nav-treeview">

                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=inbox/ircompletion" class="nav-link nav-ircompletion">
                        <i class="far fa-circle nav-icon"></i>
                        <p>IR</p> <span class="badge badge-warning rounded-pill"><?php echo $inbox_ir ?>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=inbox/issuedDA" class="nav-link nav-issuedDA">
                        <i class="far fa-circle nav-icon"></i>
                        <p>DA</p> <span class="badge badge-warning rounded-pill"><?php echo $inbox_da ?>
                      </a>
                    </li>
                  </ul>
                </li>
              <?php
              }
              ?>
              <?php if ($_settings->userdata('EMPPOSITION') > 1 || $_settings->userdata('log_category') >= 1) { ?>

                <?php if ($_settings->userdata('EMPPOSITION') >= 3 || $_settings->userdata('EMPLOYID') == 600) { ?>
                  <li class="nav-item">
                    <a href="#" class="nav-link nav-is-tree nav-overtimeApproval">
                      <i class="nav-icon fas fa-chart-pie"></i>
                      <p>
                        Overtime Approval
                      </p>
                      <?php echo " " . $notif ?>
                      <i class="right fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="<?php echo base_url ?>admin/?page=overtimeApproval/approvals" class="nav-link nav-approvals">
                          <i class="far fa-circle nav-icon"></i>
                          <p>For Approvals</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="<?php echo base_url ?>admin/?page=overtimeApproval/approvalhistory" class="nav-link nav-approvalhistory">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Approval History</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                <?php } ?>
                <?php if ($_settings->userdata('EMPPOSITION') >= 2 || empty($_settings->userdata('EMPNAME'))) { ?>
                  <li class="nav-item">
                    <a href="#" class="nav-link nav-is-tree nav-overtimeForm">
                      <i class="nav-icon fas fa-chart-pie"></i>
                      <p>
                        Overtime Request
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <?php
                      if (!empty($_settings->userdata('EMPNAME'))) {
                      ?>
                        <li class="nav-item">
                          <a href="<?php echo base_url ?>admin/?page=overtimeform/manage_ot" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Create OT</p>
                          </a>
                        </li>
                      <?php
                      }
                      ?>
                      <li class="nav-item">
                        <a href="<?php echo base_url ?>admin/?page=overtimeForm/pending" class="nav-link nav-pending">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Pending</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="<?php echo base_url ?>admin/?page=overtimeForm/approved" class="nav-link nav-approved">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Approved</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="<?php echo base_url ?>admin/?page=overtimeForm/disapproved" class="nav-link nav-disapproved">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Disapproved</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="<?php echo base_url ?>admin/?page=overtimeForm/cancelled" class="nav-link nav-cancelled">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Cancelled</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                <?php } ?>
                <!-- <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=appraisal_form" class="nav-link nav-appraisal">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                      Appraisal Request
                    </p>
                  </a>
                </li> -->
                <!-- <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=pcn_form" class="nav-link nav-pcn">
                    <i class="nav-icon fas fa-address-card"></i>
                    <p>
                      Payroll Change Notice
                    </p>
                  </a>
                </li> -->
              <?php } ?>
              <li class="nav-item">
                <a href="#" class="nav-link nav-is-tree nav-incidentreport">
                  <i class="nav-icon fas fa-exclamation-triangle"></i>
                  <p>
                    Incident Report
                    <i class="right fas fa-angle-left"></i>
                  </p> <?php
                        if ($is_operator > 0 && $_settings->userdata('EMPPOSITION') > 4) {
                          echo ($dis_qry + $qryyy + $issue_da + $for_da + $hr_da_qry) > 0 ? '<span class="badge badge-warning">New</span>' : '';
                        } else {
                          echo ($dis_qry + $qryyy + $qry_da) > 0 ? '<span class="badge badge-warning">New</span>' : '';
                        }


                        ?>
                </a>
                <ul class="nav nav-treeview">
                  <?php if ($_settings->userdata('EMPPOSITION') > 1 ||  $is_quality > 0   || $is_operator > 0) {
                  ?>
                    <li class="nav-header">Approvals</li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=incidentreport/approveIR" class="nav-link nav-approveIR">
                        <i class="far fa-circle nav-icon"></i>
                        <p>For Approvals (IR) </p>
                        <?php if ($is_operator > 0 && $_settings->userdata('EMPPOSITION') > 4) { ?>
                          <span class="badge badge-warning rounded-pill"><?php echo $qryyy + $issue_da  ?></span>
                        <?php  } else { ?>
                          <span class="badge badge-warning rounded-pill"><?php echo $qryyy ?></span>
                        <?php } ?>

                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=incidentreport/approveDA" class="nav-link nav-approveDA">
                        <i class="far fa-circle nav-icon"></i>
                        <p>For Approvals (DA) </p>
                        <?php if ($is_operator > 0) : ?>
                          <span class="badge badge-warning rounded-pill"><?php echo $hr_da_qry ?></span>
                        <?php else : ?>
                          <span class="badge badge-warning rounded-pill"><?php echo $qry_da ?></span>
                        <?php endif; ?>
                      </a>
                    </li>
                    <li class="nav-header">Requests IR</li>
                  <?php } ?>
                  <li class="nav-item">
                    <a href="<?php echo base_url ?>admin/?page=incidentreport/createNewIRDA/new_ir" class="nav-link nav-createNewIRDA">
                      <i class="nav-icon far fa-circle"></i>
                      <p>
                        Create IR
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo base_url ?>admin/?page=incidentreport/pendingIRDA" class="nav-link nav-pendingIRDA">
                      <i class="nav-icon far fa-circle"></i>
                      <p>
                        Pending
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo base_url ?>admin/?page=incidentreport/doneIRDA" class="nav-link nav-doneIRDA">
                      <i class="nav-icon far fa-circle"></i>
                      <p>
                        Approve
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo base_url ?>admin/?page=incidentreport/disapprovedIRDA" class="nav-link nav-disapprovedIRDA">
                      <i class="nav-icon far fa-circle"></i>
                      <p>
                        Disapprove <span class="badge badge-warning rounded-pill"><?php echo $dis_qry ?></span>
                      </p>
                    </a>
                  </li>
                  <?php if ($is_quality > 0) { ?>

                    <li class="nav-header">Maintenance</li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=incidentreport/manageIRDA" class="nav-link nav-manageIRDA">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Manage IR (Quality)
                        </p>
                      </a>
                    </li>
                  <?php } ?>
                  <?php if ($is_operator > 0) { ?>
                    <!-- <li class="nav-header">Issue DA</li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url ?>admin/?page=incidentreport/DA" class="nav-link nav-DA">
                                                <i class="nav-icon far fa-circle"></i>
                                                <p>
                                                    Issue DA
                                                </p> <span class="badge badge-warning rounded-pill"><?php echo $issue_da ?>
                                            </a>
                                        </li> -->
                    <li class="nav-header">Maintenance</li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=incidentreport/manageIRDA" class="nav-link nav-manageIRDA">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Manage IR/DA
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=incidentreport/irCodenumber" class="nav-link nav-irCodenumber">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Code Number
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=incidentreport/adminIRDA" class="nav-link nav-adminIRDA">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          HR Administrator
                        </p>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </li>
              <!-- <li class="nav-item dropdown">
                <a href="http://192.168.1.28/leavesys" class="nav-link nav-home">
                  <i class="nav-icon fas fa-calendar"></i>
                  <p>
                    Leavesys
                  </p>
                </a>
              </li> -->
              <?php if ($_settings->userdata('EMPLOYID') == 1681) { ?>
                <li class="nav-item">
                  <a href="#" class="nav-link nav-is-tree nav-exitClearance">
                    <i class="nav-icon fa-solid fa-person-through-window"></i>
                    <p>
                      Exit Clearance
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">

                    <li class="nav-header">Approvals</li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=exitClearance/approvedClearance" class="nav-link nav-approvedClearance">
                        <i class="far fa-circle nav-icon"></i>
                        <p>For Approvals</p>
                      </a>
                    </li>
                    <li class="nav-header">Request Exit Clearance</li>
                    <?php if ($_settings->userdata('DEPARTMENT') == 'Human Resource') {
                    ?>
                      <li class="nav-item">
                        <a href="<?php echo base_url ?>admin/?page=exitClearance/createNewclearance/new_exit" class="nav-link nav-createNewclearance">
                          <i class="nav-icon far fa-circle"></i>
                          <p>
                            Create Exit Clearance
                          </p>
                        </a>
                      </li>
                    <?php } ?>
                    <li class="nav-header">Manage Exit Clearance</li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=exitClearance/pendingClearance" class="nav-link nav-pendingClearance">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Pending
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=exitClearance/doneIRDA" class="nav-link nav-doneIRDA">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Approve
                        </p>
                      </a>
                    </li>
                  </ul>
                </li>
              <?php } ?>
              <?php if ($_settings->userdata('EMPPOSITION') >= 1 || $_settings->userdata('log_category') >= 1) { ?>
                <!-- <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=appraisal_form" class="nav-link nav-appraisal">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                      Appraisal Request
                    </p>
                  </a>
                </li> -->
                <!-- <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=ir_form" class="nav-link nav-ir">
                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                    <p>
                      Incident Report
                    </p>
                  </a>
                </li> -->
              <?php } ?>

              <!-- <li class="nav-item">
                <a href="<?php echo base_url ?>admin/?page=nda_form" class="nav-link nav-nda">
                  <i class="nav-icon fas fa-exclamation-triangle"></i>
                  <p>
                    Notice of Diciplinary Action
                  </p>
                </a>
              </li> -->
              <!-- <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=receiving" class="nav-link nav-receiving">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                          Receiving
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=back_order" class="nav-link nav-back_order">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>
                          Back Order
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=return" class="nav-link nav-return">
                        <i class="nav-icon fas fa-undo"></i>
                        <p>
                          Return List
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=stocks" class="nav-link nav-stocks">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                          Stocks
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=sales" class="nav-link nav-sales">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                          Sale List
                        </p>
                      </a>
                    </li> -->
              <?php if ($_settings->userdata('EMPPOSITION') == 1 || $_settings->userdata('log_category') == 1) { ?>
                <!-- <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=maintenance/supplier" class="nav-link nav-maintenance_supplier">
                    <i class="nav-icon fas fa-truck-loading"></i>
                    <p>
                      Supplier List
                    </p>
                  </a>
                </li> -->
              <?php } ?>
              <?php if ($_settings->userdata('EMPPOSITION') != 0) { ?>
                <!-- <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=maintenance/item" class="nav-link nav-maintenance_item">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                          Item List
                        </p>
                      </a>
                    </li> -->
              <?php } ?>
              <?php if ($_settings->userdata('log_category') == 1) { ?>
                <li class="nav-header">Maintenance</li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                      User List
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                      Settings
                    </p>
                  </a>
                </li>
              <?php } ?>

            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
      </div>
    </div>
    <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
      <div class="os-scrollbar-track">
        <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
      </div>
    </div>
    <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
      <div class="os-scrollbar-track">
        <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
      </div>
    </div>
    <div class="os-scrollbar-corner"></div>
  </div>
  <!-- /.sidebar -->
</aside>
<script>
  // var page;
  // $(document).ready(function() {
  //   page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  //   page = page.replace(/\//gi, '_');

  //   if ($('.nav-link.nav-' + page).length > 0) {
  //     $('.nav-link.nav-' + page).addClass('active')
  //     if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
  //       $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
  //       $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
  //     }
  //     if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
  //       $('.nav-link.nav-' + page).parent().addClass('menu-open')
  //     }

  //   }

  //   $('#receive-nav').click(function() {
  //     $('#uni_modal').on('shown.bs.modal', function() {
  //       $('#find-transaction [name="tracking_code"]').focus();
  //     })
  //     uni_modal("Enter Tracking Number", "transaction/find_transaction.php");
  //   })
  // })
  var page;
  $(document).ready(function() {
    page = '<?php
            // echo isset($_GET['page']) ? $_GET['page'] : 'home'
            if ($_settings->userdata('EMPPOSITION') == 1) {
              echo isset($_GET['page']) ? $_GET['page'] : 'ir_form';
            } else {
              echo  isset($_GET['page']) ? $_GET['page'] : 'home';
            }
            ?>';
    page = page.replace(/\//gi, '_');
    console.log(page)
    var str = page;
    var parts = str.split("_");
    var result = parts[0];
    var after = parts[1];
    // var pattern = /applicants(\w+)/;
    // var match = str.match(pattern);
    // var str = page;
    var pattern = new RegExp(result + "(\\w+)");
    var match = str.match(pattern);
    if (match) {
      var wordAfterApplicants = match[0];
      console.log("Word after " + result + ": " + after);

      console.log(after)
      if ($('.nav-link.nav-' + result).length > 0) {
        $('.nav-link.nav-' + result).addClass('active')
        if ($('.nav-link.nav-' + result).hasClass('nav-is-tree') == true) {
          $('.nav-link.nav-' + result).parent().addClass('menu-open')
          $('.nav-link.nav-' + after).addClass('active')
        }
      }
    } else {
      console.log("No word found after '" + result + "'.");
      if ($('.nav-link.nav-' + page).length > 0) {
        $('.nav-link.nav-' + page).addClass('active')
        if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
          $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
          $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')

        }
        if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
          $('.nav-link.nav-' + page).parent().addClass('menu-open')
        }
      }
    }
  })
</script>