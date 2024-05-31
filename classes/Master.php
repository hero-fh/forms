<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

require_once('../config.php');
class Master extends DBConnection
{
	private $settings;
	public function __construct()
	{
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function capture_err()
	{
		if (!$this->conn->error)
			return false;
		else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_ot()
	{
		$today_year = date("y");
		$prefix = "OT" . $today_year;
		$code = sprintf("%'.04d", 1);
		while (true) {
			$check_code = $this->conn->query("SELECT * FROM `overtime_requests` where ot_form_no ='" . $prefix . '-' . $code . "' ")->num_rows;
			if ($check_code > 0) {
				$code = sprintf("%'.04d", $code + 1);
			} else {
				break;
			}
		}
		$otFormCode = $prefix . "-" . $code;

		extract($_POST);
		$data = "";

		if (empty($empNumber)) {
			$resp['status'] = 'failed';
			return json_encode($resp);
		}

		$insertValues = [];
		foreach ($department as $key => $item) {
			$productlineValue = $productline[$key];
			$requestor_id = $otRequestor;
			// Sanitize and escape the data before adding to the query
			// $item = mysqli_real_escape_string($conn, $item);
			$item = $this->conn->real_escape_string($item);
			$productline = $this->conn->real_escape_string($productlineValue);
			$requestorID = $this->conn->real_escape_string($requestor_id);

			if ($empPosition >= 4) {
				$otStatus = 1;
				$dhStatus = 1;
				$dhAutoRemarks = "NA";
				$insertValues[] = "('$otFormCode','$requestorID','$item', '$productlineValue', '$date_from', '$date_to', '$otStatus', '$dhEmpNumber', '$dhStatus', '$dhAutoRemarks')";
			} else {
				$insertValues[] = "('$otFormCode','$requestorID','$item', '$productlineValue', '$date_from', '$date_to')";
			}
		}

		if (!empty($insertValues)) {
			$data = implode(', ', $insertValues);

			if ($empPosition >= 4) {
				$sql = "INSERT INTO `overtime_requests` (`ot_form_no`, `requestor_id`, `department`, `productline`, `date_from`,`date_to`, `ot_status`, `dh_name`, `dh_status`, `dh_remarks`) VALUES {$data}";
			} else {
				$sql = "INSERT INTO `overtime_requests` (`ot_form_no`, `requestor_id`, `department`, `productline`, `date_from`,`date_to`) VALUES {$data}";
			}

			$save = $this->conn->query($sql);

			$ot_id = $this->conn->insert_id;
			$resp['id'] = $ot_id;

			if ($save) {
				extract($_POST);
				//$data2 = "";
				$empNumberValue = explode(',', $empNumber);
				$empNameValue = explode('/', $empName);
				$workShiftValue = explode(',', $workShift);
				$otDateFrom = explode(',', $otDateFrom);
				$otTimeFromValue = explode(',', $otTimeFrom);
				$otTimeToValue = explode(',', $otTimeTo);
				$otReasonValue = explode('^^', $otReason);

				foreach ($empNumberValue as $key => $empNumberValue1) {
					// $item will contain each individual item like 'apple' or 'banana'
					// $key will give you the index to access the corresponding color from the $colors array
					$empnumVal = $this->conn->real_escape_string($empNumberValue1);
					// Get the color corresponding to the current item
					$empNameValue1 = $empNameValue[$key];
					$empnameVal = $this->conn->real_escape_string($empNameValue1);
					$workShiftValue1 = $workShiftValue[$key];
					$workshiftVal = $this->conn->real_escape_string($workShiftValue1);
					// $dateRequestedValue1 = $dateRequestedValue[$key];
					$otDateFrom1 = $otDateFrom[$key];
					$otdateFrom = $this->conn->real_escape_string($otDateFrom1);
					// $otDateTo1 = $otDateTo[$key];
					$otTimeFromValue1 = $otTimeFromValue[$key];
					$ottimeFrom = $this->conn->real_escape_string($otTimeFromValue1);
					$otTimeToValue1 = $otTimeToValue[$key];
					$ottimeTo = $this->conn->real_escape_string($otTimeToValue1);
					$otReasonValue1 = $otReasonValue[$key];
					$otreasonVal = $this->conn->real_escape_string($otReasonValue1);

					// Insert $item and $color into the database using your SQL INSERT statement
					// Example:
					// $sql = "INSERT INTO `overtime_items` (`ot_form_code`,`emp_num`, `emp_name`, `work_shift`, `date_requested`,`ot_date_from`,`ot_date_to`,`ot_time_from`,`ot_time_to`,`ot_reason`) 
					// 		VALUES ('$otFormCode','$empNumberValue1','$empNameValue1','$workShiftValue1','$dateRequestedValue1','$otDateFrom1','$otDateTo1','$otTimeFromValue1','$otTimeToValue1','$otReasonValue1')";
					$sql = "INSERT INTO `overtime_items` (`ot_form_code`,`emp_num`, `emp_name`, `work_shift`,`ot_date_from`,`ot_time_from`,`ot_time_to`,`ot_reason`) 
					VALUES ('$otFormCode','$empnumVal','$empnameVal','$workshiftVal','$otdateFrom','$ottimeFrom','$ottimeTo','$otreasonVal')";

					// Execute the SQL query here
					$save = $this->conn->query($sql);
				}
				if ($save) {
					$resp['status'] = 'success';
					$this->settings->set_flashdata('success', " Overtime Form Successfully Created.");
				}
			}
		}

		return json_encode($resp);
	}
	function delete_ot()
	{
		extract($_POST);
		// $bo = $this->conn->query("SELECT * FROM back_order_list where po_id = '{$id}'");
		$del = $this->conn->query("UPDATE `overtime_requests` set `ot_status` = 4 where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "OT Form Successfully Canceled.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function sign_ot()
	{

		extract($_POST);

		$qry = $this->conn->query("SELECT * FROM overtime_requests where id = '{$id}'");
		while ($row = $qry->fetch_assoc()) :
			$holdStatus = $row['ot_status'];
		endwhile;

		if (isset($_POST['action'])) {
			$action = $_POST['action'];

			// Now you can use the $action variable to determine which button was clicked
			if ($action === 'approve') {
				$holdStatus = $holdStatus + 1;

				$otStatus = 1;
			} elseif ($action === 'disapprove') {
				// Handle the disapprove action
				$otStatus = 2;
				$holdStatus = 3;
			}
		}


		//date_default_timezone_set("Asia/Manila");
		$signed_date = date("Y-m-d H:i:s");

		if ($appType == 4 || $appType == 3) {
			$del = $this->conn->query("UPDATE `overtime_requests` set `ot_status` = {$holdStatus}, 
								`dh_name` = '{$approverNum}', `dh_status` = {$otStatus}, `dh_remarks` = '{$this->conn->real_escape_string($dh_remarks)}',
								`dh_sign_date` = '{$signed_date}' where `id` = '{$id}'");
			if ($del) {
				$resp['id'] = $id;
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success', "OT Form Successfully Signed.");
			} else {
				$resp['id'] = $id;
				$resp['status'] = 'failed';
				$resp['error'] = $this->conn->error;
			}
			return json_encode($resp);
		}
		if ($appType == 5) {
			$del = $this->conn->query("UPDATE `overtime_requests` set `ot_status` = {$holdStatus}, 
								`od_name` = '{$approverNum}', `od_status` = {$otStatus}, `od_remarks` = '{$this->conn->real_escape_string($od_remarks)}',
								`od_sign_date` = '{$signed_date}' where `id` = '{$id}'");
			if ($del) {
				$resp['id'] = $id;
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success', "OT Form Successfully Signed.");
			} else {
				$resp['id'] = $id;
				$resp['status'] = 'failed';
				$resp['error'] = $this->conn->error;
			}
			return json_encode($resp);
		}
	}
	function sign_ot_icheck()
	{
		//date_default_timezone_set("Asia/Manila");
		$signed_date = date("Y-m-d H:i:s");

		extract($_POST);
		// $data = "";
		if (!empty($iCheck)) {
			$count = count($iCheck);
			foreach ($iCheck as $k => $v) {
				// if (!empty($data)) $data .= ",";
				// $data .= "('{$v}')";

				$qry = $this->conn->query("SELECT * FROM overtime_requests where id = '{$v}'");
				while ($row = $qry->fetch_assoc()) :
					$holdStatus = $row['ot_status'];
				endwhile;

				if (isset($_POST['action'])) {
					$action = $_POST['action'];

					// Now you can use the $action variable to determine which button was clicked
					if ($action === 'approve') {
						$holdStatus = $holdStatus + 1;

						$otStatus = 1;
					} elseif ($action === 'disapprove') {
						// Handle the disapprove action
						$otStatus = 2;
						$holdStatus = 3;
					}

					if ($appType == 4 || $appType == 3) {
						$del = $this->conn->query("UPDATE `overtime_requests` set `ot_status` = {$holdStatus}, 
											`dh_name` = '{$approverNum}', `dh_status` = {$otStatus}, `dh_remarks` = '{$this->conn->real_escape_string('na')}',
											`dh_sign_date` = '{$signed_date}' where `id` = '{$v}'");
						if ($del) {
							$resp['id'] = $v;
							$resp['status'] = 'success';
							$this->settings->set_flashdata('success', $count . " OT Form Successfully Signed.");
						} else {
							$resp['id'] = $v;
							$resp['status'] = 'failed';
							$resp['error'] = $this->conn->error;
						}
					}
					if ($appType == 5) {
						$del = $this->conn->query("UPDATE `overtime_requests` set `ot_status` = {$holdStatus}, 
											`od_name` = '{$approverNum}', `od_status` = {$otStatus}, `od_remarks` = '{$this->conn->real_escape_string('na')}',
											`od_sign_date` = '{$signed_date}' where `id` = '{$v}'");
						if ($del) {
							$resp['id'] = $v;
							$resp['status'] = 'success';
							$this->settings->set_flashdata('success', $count . " OT Form Successfully Signed.");
						} else {
							$resp['id'] = $v;
							$resp['status'] = 'failed';
							$resp['error'] = $this->conn->error;
						}
					}
				}
			}
			return json_encode($resp);
		} else {
			$resp['status'] = 'failed';
			return json_encode($resp);
		}
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_ot':
		echo $Master->save_ot();
		break;
	case 'delete_ot':
		echo $Master->delete_ot();
		break;
	case 'sign_ot':
		echo $Master->sign_ot();
		break;
	case 'sign_ot_icheck':
		echo $Master->sign_ot_icheck();
		break;
	default:
		// echo $sysset->index();
		break;
}
