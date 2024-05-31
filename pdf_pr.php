<?php
require_once('config.php');

$otFormNum = $_GET['id'];

$qry = $conn->query("SELECT * FROM overtime_requests where ot_form_no = '{$otFormNum}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
}

$qry1 = $conn->query("SELECT * FROM employee_masterlist where EMPLOYID = '{$requestor_id}'");
if ($qry1->num_rows > 0) {
    foreach ($qry1->fetch_array() as $k1 => $v1) {
        $$k1 = $v1;
    }
}

require('mc_table.php');

//$pdf = new FPDF('L','mm','Legal');
$pdf = new PDF_MC_Table('P', 'mm', 'Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
// Insert a logo in the top-left corner at 300 dpi
$pdf->Image('telford_logo1.jpg', 11, 10, -500);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 5, '', 0, 1, 'C');
$pdf->Cell(50, 5, 'Telford Svc. Phils., Inc.', 0, 1);
// Remove Bold
$pdf->SetFont('');
$pdf->SetFontSize(8);
$pdf->Cell(50, 5, 'Gateway Business Park, Bgry. Jaavalera, Gen. Trias, Cavite, Philippines', 0, 1);
$pdf->Cell(50, 5, 'Tel.: (046) 433-0536   Fax: 433-0529', 0, 0);

//OT number:
// $code = $otFormNum;
// $pdf->Code128(150,18,$code,50,10);
$pdf->SetFont('', 'B', 11);
$pdf->SetTextColor(194, 8, 8);
$pdf->Cell(110, 15, 'No.', 0, 0, 'R');
$pdf->Cell(56, 15, $otFormNum, 0, 1, 'L');

$pdf->Ln(1);

$pdf->SetTextColor(0, 0, 0);
//Form title
$pdf->Cell(205, 0, 'EMPLOYEE REQUEST FOR OVERTIME', 0, 1, 'C');
// $pdf->Line(10,42,205,42);
// $pdf->Line(10,43,205,43);
$pdf->Ln(10);

//Requestor Details
$pdf->SetFont('');
$pdf->SetFontSize(8);
$pdf->SetFont('', 'B');
$pdf->Cell(25, 5, 'Department/PL:', 0, 0);
$pdf->SetFont('');
$pdf->Cell(105, 5, $department . ' , ' . $productline, 0, 0);

$pdf->SetFont('', 'B');
$pdf->Cell(25, 5, 'Payroll Cut-off:', 0, 0);
$pdf->SetFont('');

$dateCreated = new DateTime($date_from);
$newdateFrom = $dateCreated->format('m-d-Y');

$dateCreated = new DateTime($date_to);
$newdateTo = $dateCreated->format('m-d-Y');

$pdf->Cell(25, 5, $newdateFrom . ' To ' . $newdateTo, 0, 0);

$pdf->Ln(5);

$pdf->SetFont('');
$pdf->SetFontSize(8);
$pdf->SetFont('', 'B');
$pdf->Cell(25, 5, 'Prepared By:', 0, 0);
$pdf->SetFont('');
$pdf->Cell(105, 5, $EMPNAME, 0, 0);

$pdf->SetFont('', 'B');
$pdf->Cell(25, 5, 'Date Created:', 0, 0);
$pdf->SetFont('');

$dateCreated = new DateTime($date_created);
$newdateCreated = $dateCreated->format('m-d-Y h:i:s a');

$pdf->Cell(25, 5, $newdateCreated, 0, 0);

$pdf->Ln(10);

$pdf->SetFont('');
$pdf->SetFontSize(8);
$pdf->SetFont('', 'B');
$pdf->Cell(25, 5, 'Approved By:', 0, 0);
$pdf->SetFont('');

$qry = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$dh_name}' LIMIT 1");
while ($row = $qry->fetch_assoc()) :
    $pdf->Cell(105, 5, $row["EMPNAME"], 0, 0);
    $pdf->SetFont('', 'B');

    $dateCreated = new DateTime($dh_sign_date);
    $newdhsignDate = $dateCreated->format('m-d-Y h:i:s a');

    $pdf->Cell(25, 5, 'Date Approved:', 0, 0);
    $pdf->SetFont('');
    $pdf->Cell(25, 5, $newdhsignDate, 0,);

    $pdf->Ln(5);
    $pdf->Cell(25, 5, '', 0, 0);
    $pdf->Cell(90, 5, $row["JOB_TITLE"], 0, 0);
endwhile;
$pdf->Ln(10);

$pdf->SetFont('');
$pdf->SetFontSize(8);
$pdf->SetFont('', 'B');
$pdf->Cell(25, 5, 'Approved By:', 0, 0);
$pdf->SetFont('');

$qry = $conn->query("SELECT * FROM employee_masterlist WHERE EMPLOYID = '{$od_name}' LIMIT 1");
while ($row = $qry->fetch_assoc()) :
    $pdf->Cell(105, 5, $row["EMPNAME"], 0, 0);

    $pdf->SetFont('', 'B');
    $pdf->Cell(25, 5, 'Date Approved:', 0, 0);
    $pdf->SetFont('');

    $dateCreated = new DateTime($od_sign_date);
    $newodsignDate = $dateCreated->format('m-d-Y h:i:s a');

    $pdf->Cell(25, 5, $newodsignDate, 0,);

    $pdf->Ln(5);
    $pdf->Cell(25, 5, '', 0, 0);
    $pdf->Cell(90, 5, $row["JOB_TITLE"], 0, 0);
endwhile;
$pdf->Ln(10);

$pdf->Cell(28, 5, "The following employees have expressed their request and willingness to render overtime work on the date and time as specified below. ", 0, 0);

//Item Table
$pdf->Ln(7);
$pdf->SetFont('', 'B');
//Table Headers
$pdf->Cell(17, 10, 'EMP. NO.', 1, 0, 'C');
$pdf->Cell(45, 10, 'EMP. NAME', 1, 0, 'C');
$pdf->MultiCell(12, 5, 'WORK SHIFT', 1, 'C', false);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetXY($x + 74, $y - 10);
$pdf->MultiCell(25, 5, 'DATE REQUESTED', 1, 'C', false);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetXY($x + 99, $y - 10);
$pdf->MultiCell(28, 10, 'OT TIME', 1, 'C', false);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetXY($x + 127, $y - 10);
$pdf->MultiCell(46, 10, 'REASON', 1, 'C', false);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetXY($x + 173, $y - 10);
$pdf->MultiCell(25, 5, 'EMP. SIGNATURE', 1, 'C', false);
$x = $pdf->GetX();
$y = $pdf->GetY();

$pdf->SetFont('', '');
// Table with 20 rows and 4 columns
$pdf->SetWidths(array(17, 45, 12, 25, 28, 46, 25));
//for($i=0;$i<5;$i++)
//$pdf->Row(array(GenerateSentence(), GenerateSentence(), GenerateSentence(), GenerateSentence()));

$qry = $conn->query("SELECT * FROM overtime_items WHERE ot_form_code = '{$otFormNum}'");
while ($row = $qry->fetch_assoc()) :

    // Item List
    $date0 = new DateTime($row["date_requested"]);
    $newDate0 = $date0->format('m-d-Y');

    $date1 = new DateTime($row["ot_date_from"]);
    $newDate1 = $date1->format('m-d-Y');
    $otDateTime = $row["ot_time_from"] . " - " . $row["ot_time_to"];
    $pdf->Row(array(
        $row["emp_num"], $row["emp_name"], $row["work_shift"],
        $newDate1, $otDateTime, $row["ot_reason"], ""
    ));

endwhile;

$pdf->SetFont('');

$conn->close();

$pdf->Output();
