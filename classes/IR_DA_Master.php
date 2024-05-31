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
	function code_number()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}

		$check = $this->conn->query("SELECT * FROM `ir_code_no` where `code_number` = '{$code_number}'  " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Code number already exists.";
		} else {
			if (empty($id)) {
				$sql = "INSERT INTO `ir_code_no` set {$data} ";
			} else {
				$sql = "UPDATE `ir_code_no` set {$data} where id = '{$id}' ";
			}
			$save = $this->conn->query($sql);
			if ($save) {
				$resp['status'] = 'success';
				if (empty($id))
					$resp['msg'] = " New code number successfully saved.";
				else
					$resp['msg'] = " Code number successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = $this->conn->error . "[{$sql}]";
			}
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function delete_code_number()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE  FROM `ir_code_no` where id='{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', " Code number successfully deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_suspension_date()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `ir_list` set `date_of_suspension` = '{$date_of_suspension}' where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Date of suspension successfully updated.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_ir_request() // saving ir to database: start
	{
		extract($_POST);
		$check = $this->conn->query("SELECT EMPLOYID FROM `employee_masterlist` where EMPLOYID = '{$emp_no}'")->num_rows;
		if ($check == 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Invalid employee number.";
			$this->settings->set_flashdata('error', $resp['msg']);
			return json_encode($resp);
		}
		$data = "";
		$p_fields = [
			'id', 'requestor_id', 'ir_no', 'emp_no', 'emp_name', 'productline', 'station', 'shift',
			'department', 'position', 'quality_violation', 'reference', 'what',
			'when', 'where', 'how', 'suspension', 'why1', 'why2', 'why3', 'why4', 'why5',
			'assessment', 'recommendation', 'sign', 'sign_date', 'sv_name', 'sv_status',
			'sv_sign_date', 'ir_status', 'requestor_name', 'dm_name', 'dh_name', 'dh_status',
			'dh_sign_date', 'hr_name', 'hr_status', 'hr_sign_date',
			'da_sign', 'da_others', 'da_requestor_name', 'da_requested_date'
		];
		foreach ($_POST as $k => $v) {
			if (in_array($k, $p_fields)) {
				// if (!empty($data)) $data .= ",";
				// $data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
				if (!empty($data)) $data .= ",";
				$v = !empty($v) ? addslashes($v) : null;
				$data .= "`{$k}`" . ($v !== null ? "='{$v}'" : " = NULL");
			}
		}
		if (!empty($data)) $data .= ",";
		$year = date('Y'); // Get the current year
		if ($year = '2024') {
			$thisy = $this->conn->query("SELECT ir_no FROM ir_requests ORDER BY CAST(SUBSTRING_INDEX(ir_no, '-', -1) AS UNSIGNED) DESC LIMIT 1")->fetch_array()[0];



			$parts = explode('-', $thisy);

			if (count($parts) == 2) {
				$number = $parts[1];
				$control_no = $number;
			}
		} else {
			$control_no = $this->conn->query("SELECT * FROM ir_requests WHERE YEAR(date_created) = $year")->num_rows;
		}
		$ir_no = isset($ir_no) ? $ir_no : date('Y') . '-' . sprintf("%03d", $control_no + 1);
		$data .= "`ir_no`='{$ir_no}'";
		if (isset($hr_status) && $hr_status == 1)
			$data .= ",`ir_status`='1'";

		// foreach ($_POST as $k => $v) {
		// 	if (!in_array($k, array('id'))) {
		// 		if (!empty($data)) $data .= ",";
		// 		$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
		// 	}
		// }
		// var_dump($data);
		if (empty($id)) {
			$sql = "INSERT INTO `ir_requests` set {$data} ";
		} else {
			$sql = "UPDATE `ir_requests` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if (!empty($code_no)) {
			$qid =  $ir_no;
			$data = "";
			$emp_no =  $emp_no;
			foreach ($code_no as $k => $v) {
				$v = empty($v) ? NULL : $v;
				$code_no[$k] = isset($code_no[$k]) ? $code_no[$k] : '';
				$violation[$k] = isset($violation[$k]) ? $this->conn->real_escape_string($violation[$k]) : '';
				$da_type[$k] = isset($da_type[$k]) ? $da_type[$k] : '';
				$date_commited[$k] = isset($date_commited[$k]) ? $date_commited[$k] : '';
				$offense_no[$k] = isset($offense_no[$k]) ? $offense_no[$k] : '';
				$disposition[$k] = isset($disposition[$k]) ? $disposition[$k] : 0;
				$code_no[$k] = $this->conn->real_escape_string($code_no[$k]);
				$da_type[$k] = $this->conn->real_escape_string($da_type[$k]);
				$date_commited[$k] = $this->conn->real_escape_string($date_commited[$k]);
				$offense_no[$k] = $this->conn->real_escape_string($offense_no[$k]);
				if (!empty($data)) $data .= ",";
				$data .= "('{$qid}','{$emp_no}','{$code_no[$k]}','{$violation[$k]}','{$da_type[$k]}','{$date_commited[$k]}','{$offense_no[$k]}','{$disposition[$k]}')";
			}
			if (!empty($data)) {
				$sql2 =  $this->conn->query("INSERT INTO `ir_list` (`ir_no`,`emp_no`,`code_no`,`violation`,`da_type`,`date_commited`,`offense_no`,`disposition`) VALUES {$data}");
				if ($sql2) {
					$resp['status'] = 'success';
				} else {
					$resp['status'] = 'failed';
					$resp['error'] = $this->conn->error;
				}
			}
		}
		if (!empty($da_requestor_name)) {
			$sql1 = $this->conn->query("UPDATE `ir_requests` set {$data} where id = '{$id}' ");
			// $sql2 = $this->conn->query("UPDATE `ir_requests` set has_da = 1 where id = '{$id}' ");
		}
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$resp['msg'] = " Incident report successfully saved.";
			else
				$resp['msg'] = " Incident report successfully updated.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}

		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	} // saving ir to database: end

	function update_ir_request() // saving ir to database: start
	{
		extract($_POST);
		$check = $this->conn->query("SELECT EMPLOYID FROM `employee_masterlist` where EMPLOYID = '{$emp_no}'")->num_rows;
		if ($check == 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Invalid employee number.";
			$this->settings->set_flashdata('error', $resp['msg']);
			return json_encode($resp);
		}
		$data = "";
		$p_fields = [
			'id', 'ir_no', 'assessment', 'recommendation'
		];
		foreach ($_POST as $k => $v) {
			if (in_array($k, $p_fields)) {
				// if (!empty($data)) $data .= ",";
				// $data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
				if (!empty($data)) $data .= ",";
				$v = !empty($v) ? addslashes($v) : null;
				$data .= "`{$k}`" . ($v !== null ? "='{$v}'" : " = NULL");
			}
		}
		$now = date('Y-m-d H:i:s');
		if (!empty($data)) $data .= ",";
		// $year = date('Y'); // Get the current year
		// $control_no = $this->conn->query("SELECT * FROM ir_requests WHERE YEAR(date_created) = $year")->num_rows;
		// $ir_no = isset($ir_no) ? $ir_no : date('Y') . '-' . sprintf("%03d", $control_no + 1);
		// $data .= "`ir_no`='{$ir_no}'";
		$data .= "`sv_status`='1'";
		if (!empty($this->settings->userdata('EMPLOYID'))) {
			$data .= ",`sv_name`='{$this->settings->userdata('EMPLOYID')}'";
		}
		$data .= ",`sv_sign_date`='{$now}'";
		$data .= ",`da_status`='0'";
		$data .= ",`ir_status`='1'";
		// var_dump($data);
		// foreach ($_POST as $k => $v) {
		// 	if (!in_array($k, array('id'))) {
		// 		if (!empty($data)) $data .= ",";
		// 		$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
		// 	}
		// }
		if (empty($id)) {
			$sql = "INSERT INTO `ir_requests` set {$data} ";
		} else {
			$sql = "UPDATE `ir_requests` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);

		// $qid =  $ir_no;
		// $data = "";
		// $emp_no =  $emp_no;
		foreach ($code_no as $k1 => $v1) {
			$data1 = "";
			// $v = empty($v) ? NULL : $v;
			if (!in_array($k1, array('code_no'))) {
				if (!empty($data1)) $data1 .= ",";
				$data1 .= " `{$k1}`='{$v1}' ";
			}
			$ir_no = isset($ir_no) ? $ir_no : '';
			$code_no[$k1] = isset($code_no[$k1]) ? $code_no[$k1] : '';
			// $valid[$k1] = isset($valid[$k1]) ? $valid[$k1] : '';
			$days_no[$k1] = isset($days_no[$k1]) ? $days_no[$k1] : '';
			// $date_commited[$k1] = isset($date_commited[$k1]) ? $date_commited[$k1] : '';
			$date_of_suspension[$k1] = isset($date_of_suspension[$k1]) ? $date_of_suspension[$k1] : '';
			// // $disposition[$k1] = isset($disposition[$k1]) ? $disposition[$k1] : '';
			// $valid[$k1] = isset($valid[$k1]) ? $valid[$k1] : '';
			// $code_no[$k1] = $this->conn->real_escape_string($code_no[$k1]);
			// $da_type[$k1] = $this->conn->real_escape_string($da_type[$k1]);
			// $date_commited[$k1] = $this->conn->real_escape_string($date_commited[$k1]);
			// $offense_no[$k1] = $this->conn->real_escape_string($offense_no[$k1]);
			if (!empty($data1)) $data1 .= ",";
			// var_dump($date_of_suspension[$k1]);
			$this->conn->query("UPDATE `ir_list` SET valid = '{$valid[$k1]}',days_no = '{$days_no[$k1]}',date_of_suspension = '{$date_of_suspension[$k1]}' WHERE code_no = '{$code_no[$k1]}' and ir_no = '{$ir_no}'");
			// if (!empty($data)) {
			// 	$sql2 =  $this->conn->query("INSERT INTO `ir_list` (`ir_no`,`emp_no`,`code_no`,`violation`,`da_type`,`date_commited`,`offense_no`,`disposition`) VALUES {$data}");
			// 	if ($sql2) {
			// 		$resp['status'] = 'success';
			// 	} else {
			// 		$resp['status'] = 'failed';
			// 		$resp['error'] = $this->conn->error;
			// 	}
			// }
		}
		// if (!empty($da_requestor_name)) {
		// 	$sql1 = $this->conn->query("UPDATE `ir_requests` set {$data} where id = '{$id}' ");
		// 	$sql2 = $this->conn->query("UPDATE `ir_requests` set has_da = 1 where id = '{$id}' ");
		// }
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$resp['msg'] = " Incident report successfully saved.";
			else
				$resp['msg'] = " Incident report successfully updated.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}

		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	} // saving ir to database: end
	function appr_irda() // approving and disapproving of pcn :start
	{
		extract($_POST);
		if ($sign == 1) {
			$del = $this->conn->query("UPDATE `ir_requests` set `sv_status` = '{$val}',`sv_sign_date` = NOW() where id = '{$id}'");
			if ($val == 1) {
				$this->conn->query("UPDATE `ir_requests` set `ir_status` = 1 where id = '{$id}'");
			} elseif ($val == 2) {
				$this->conn->query("UPDATE `ir_requests` set `ir_status` = 3 where id = '{$id}'");
			}
		} elseif ($sign == 2) {
			$del = $this->conn->query("UPDATE `ir_requests` set `dh_name` = '{$name}',`dh_status` = '{$val}',`dh_sign_date` = NOW() where id = '{$id}'");
			if ($val == 1) {
				$this->conn->query("UPDATE `ir_requests` set `ir_status` = 2 where id = '{$id}'");
			} elseif ($val == 2) {
				$this->conn->query("UPDATE `ir_requests` set `ir_status` = 3 where id = '{$id}'");
			}
		} elseif ($sign == 3) {
			$del = $this->conn->query("UPDATE `ir_requests` set `hr_name` = '{$name}',`hr_status` = '{$val}',`hr_sign_date` = NOW() where id = '{$id}'");
			if ($val == 1) {
				$this->conn->query("UPDATE `ir_requests` set `ir_status` = 1 where id = '{$id}'");
			} elseif ($val == 2) {
				$this->conn->query("UPDATE `ir_requests` set `ir_status` = 3 where id = '{$id}'");
			}
		} elseif ($sign == 4) {
			$del = $this->conn->query("UPDATE `ir_requests` set `valid_appeal_name` = '{$name}',`appeal_status` = '{$val}',`valid_appeal_sign_date` = NOW() where id = '{$id}'");
		} elseif ($sign == 5) {
			$del = $this->conn->query("UPDATE `ir_requests` set `od_name` = '{$name}',`appeal_status` = '{$val}',`od_sign_date` = NOW() where id = '{$id}'");
		}

		if ($del) {
			// $resp['id'] = $id;
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "IR Successfully Signed.");
		} else {
			// $resp['id'] = $id;
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function valid_to_da() // approving and disapproving of pcn :start
	{
		extract($_POST);

		$del = $this->conn->query("UPDATE `ir_requests` set `valid_to_da_name` = '{$name}',`da_status` = 1,`valid_to_da_date` = NOW() where id = '{$id}'");

		if ($del) {
			// $resp['id'] = $id;
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Form Validated.");
		} else {
			// $resp['id'] = $id;
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function cancel_ir()
	{
		extract($_POST);
		// var_dump($disappr_reason);
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_status` = 2,`od_status` = 2,`ir_status` = 3,`disappr_reason`='{$disappr_reason}' where id = '{$id}'");
		$hr_name = $this->conn->real_escape_string($hr_name);
		$disapprove_remarks = $this->conn->real_escape_string($disapprove_remarks);

		$del = $this->conn->query("UPDATE `ir_requests` SET hr_name ='{$hr_name}', `hr_status` = 2, `ir_status` = 0, `disapprove_remarks`='{$disapprove_remarks}' WHERE id = '{$id}'");

		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "IR disapproved.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function cancel_ir_fromhr()
	{
		extract($_POST);
		// var_dump($disappr_reason);
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_status` = 2,`od_status` = 2,`ir_status` = 3,`disappr_reason`='{$disappr_reason}' where id = '{$id}'");
		$disapprove_remarks = $this->conn->real_escape_string($disapprove_remarks);

		$del = $this->conn->query("UPDATE `ir_requests` set hr_name ='{$hr_name}',`hr_status` = 2,`ir_status` = 4 ,`disapprove_remarks`='{$disapprove_remarks}' where id = '{$id}'");

		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "IR cancelled.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function invalid_ir_to_da()
	{
		extract($_POST);
		// var_dump($disappr_reason);
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_status` = 2,`od_status` = 2,`ir_status` = 3,`disappr_reason`='{$disappr_reason}' where id = '{$id}'");
		$disapprove_remarks = $this->conn->real_escape_string($disapprove_remarks);

		$del = $this->conn->query("UPDATE `ir_requests` set valid_to_da_name ='{$hr_name}',`da_status` = 2,`ir_status` = 3,`disapprove_remarks`='{$disapprove_remarks}' where id = '{$id}'");

		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Form disapproved.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function mngr_disappr_ir()
	{
		extract($_POST);
		// var_dump($disappr_reason);
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_status` = 2,`od_status` = 2,`ir_status` = 3,`disappr_reason`='{$disappr_reason}' where id = '{$id}'");
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_name` = '{$name}',`hr_status` = 2,`dh_sign_date` = NOW() where id = '{$id}'");
		$disapprove_remarks = $this->conn->real_escape_string($disapprove_remarks);

		$del = $this->conn->query("UPDATE `ir_requests` set dh_name ='{$name}',`dh_status` = 2,`disapprove_remarks`='{$disapprove_remarks}' where id = '{$id}'");

		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "IR disapproved.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function re_pass_ir() // updating ir to database: start
	{
		extract($_POST);
		$check = $this->conn->query("SELECT EMPLOYID FROM `employee_masterlist` where EMPLOYID = '{$emp_no}'")->num_rows;
		if ($check == 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Invalid employee number.";
			$this->settings->set_flashdata('error', $resp['msg']);
			return json_encode($resp);
		}
		$data = "";
		$p_fields = [
			'id', 'requestor_id', 'ir_no', 'emp_no', 'emp_name', 'productline', 'station', 'shift',
			'department', 'position', 'quality_violation', 'reference', 'what',
			'when', 'where', 'how', 'suspension', 'why1', 'why2', 'why3', 'why4', 'why5',
			'assessment', 'recommendation', 'sign', 'sign_date', 'sv_name', 'sv_status',
			'sv_sign_date', 'ir_status', 'requestor_name', 'dh_name', 'dh_status',
			'dh_sign_date', 'hr_name', 'hr_status', 'hr_sign_date',
			'da_sign', 'da_others', 'da_requestor_name', 'da_requested_date'
		];
		foreach ($_POST as $k => $v) {
			if (in_array($k, $p_fields)) {
				// if (!empty($data)) $data .= ",";
				// $data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
				if (!empty($data)) $data .= ",";
				$v = !empty($v) ? addslashes($v) : null;
				$data .= "`{$k}`" . ($v !== null ? "='{$v}'" : " = NULL");
			}
		}
		// if (!empty($data)) $data .= ",";
		// $year = date('Y'); // Get the current year
		// $control_no = $this->conn->query("SELECT * FROM ir_requests WHERE YEAR(date_created) = $year")->num_rows;
		// $ir_no = isset($ir_no) ? $ir_no : date('Y') . '-' . sprintf("%03d", $control_no + 1);
		// $data .= "`ir_no`='{$ir_no}'";
		// if (isset($hr_status) && $hr_status == 1)
		$data .= ",`hr_status`='0'";

		// foreach ($_POST as $k => $v) {
		// 	if (!in_array($k, array('id'))) {
		// 		if (!empty($data)) $data .= ",";
		// 		$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
		// 	}
		// }
		// var_dump($data);
		if (empty($id)) {
			$sql = "INSERT INTO `ir_requests` set {$data} ";
		} else {
			$sql = "UPDATE `ir_requests` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if (!empty($code_no)) {
			$qid =  $ir_no;
			$data = "";
			$emp_no =  $emp_no;
			foreach ($code_no as $k => $v) {
				$v = empty($v) ? NULL : $v;
				$code_no[$k] = isset($code_no[$k]) ? $code_no[$k] : '';
				$violation[$k] = isset($violation[$k]) ? $this->conn->real_escape_string($violation[$k]) : '';
				$da_type[$k] = isset($da_type[$k]) ? $da_type[$k] : '';
				$date_commited[$k] = isset($date_commited[$k]) ? $date_commited[$k] : '';
				$offense_no[$k] = isset($offense_no[$k]) ? $offense_no[$k] : '';
				$disposition[$k] = isset($disposition[$k]) ? $disposition[$k] : 0;
				$code_no[$k] = $this->conn->real_escape_string($code_no[$k]);
				$da_type[$k] = $this->conn->real_escape_string($da_type[$k]);
				$date_commited[$k] = $this->conn->real_escape_string($date_commited[$k]);
				$offense_no[$k] = $this->conn->real_escape_string($offense_no[$k]);
				if (!empty($data)) $data .= ",";
				$data .= "('{$qid}','{$emp_no}','{$code_no[$k]}','{$violation[$k]}','{$da_type[$k]}','{$date_commited[$k]}','{$offense_no[$k]}','{$disposition[$k]}')";
			}
			if (!empty($data)) {
				$this->conn->query("DELETE  FROM `ir_list` where ir_no='{$ir_no}'");
				$sql2 =  $this->conn->query("INSERT INTO `ir_list` (`ir_no`,`emp_no`,`code_no`,`violation`,`da_type`,`date_commited`,`offense_no`,`disposition`) VALUES {$data}");
				if ($sql2) {
					$resp['status'] = 'success';
				} else {
					$resp['status'] = 'failed';
					$resp['error'] = $this->conn->error;
				}
			}
		}
		// if (!empty($da_requestor_name)) {
		// 	$sql1 = $this->conn->query("UPDATE `ir_requests` set {$data} where id = '{$id}' ");
		// 	$sql2 = $this->conn->query("UPDATE `ir_requests` set has_da = 1 where id = '{$id}' ");
		// }
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$resp['msg'] = " Incident report successfully saved.";
			else
				$resp['msg'] = " Incident report successfully updated.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}

		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	} // saving ir to database: end
	function repass_mngr_ir() // updating ir to database: start
	{
		extract($_POST);
		$check = $this->conn->query("SELECT EMPLOYID FROM `employee_masterlist` where EMPLOYID = '{$emp_no}'")->num_rows;
		if ($check == 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Invalid employee number.";
			$this->settings->set_flashdata('error', $resp['msg']);
			return json_encode($resp);
		}
		$data = "";
		$p_fields = [
			'id', 'requestor_id', 'ir_no', 'emp_no', 'emp_name', 'productline', 'station', 'shift',
			'department', 'position', 'quality_violation', 'reference', 'what',
			'when', 'where', 'how', 'suspension', 'why1', 'why2', 'why3', 'why4', 'why5',
			'assessment', 'recommendation', 'sign', 'sign_date', 'sv_name', 'sv_status',
			'sv_sign_date', 'ir_status', 'requestor_name', 'dh_name', 'dh_status',
			'dh_sign_date', 'hr_name', 'hr_status', 'hr_sign_date',
			'da_sign', 'da_others', 'da_requestor_name', 'da_requested_date'
		];
		foreach ($_POST as $k => $v) {
			if (in_array($k, $p_fields)) {
				// if (!empty($data)) $data .= ",";
				// $data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
				if (!empty($data)) $data .= ",";
				$v = !empty($v) ? addslashes($v) : null;
				$data .= "`{$k}`" . ($v !== null ? "='{$v}'" : " = NULL");
			}
		}
		// if (!empty($data)) $data .= ",";
		// $year = date('Y'); // Get the current year
		// $control_no = $this->conn->query("SELECT * FROM ir_requests WHERE YEAR(date_created) = $year")->num_rows;
		// $ir_no = isset($ir_no) ? $ir_no : date('Y') . '-' . sprintf("%03d", $control_no + 1);
		// $data .= "`ir_no`='{$ir_no}'";
		// if (isset($hr_status) && $hr_status == 1)
		$data .= ",`ir_status`='1'";

		// foreach ($_POST as $k => $v) {
		// 	if (!in_array($k, array('id'))) {
		// 		if (!empty($data)) $data .= ",";
		// 		$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
		// 	}
		// }
		// var_dump($data);
		if (empty($id)) {
			$sql = "INSERT INTO `ir_requests` set {$data} ";
		} else {
			$sql = "UPDATE `ir_requests` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if (!empty($code_no)) {
			$qid =  $ir_no;
			$data = "";
			$emp_no =  $emp_no;
			foreach ($code_no as $k => $v) {
				$v = empty($v) ? NULL : $v;
				$code_no[$k] = isset($code_no[$k]) ? $code_no[$k] : '';
				$violation[$k] = isset($violation[$k]) ? $this->conn->real_escape_string($violation[$k]) : '';
				$da_type[$k] = isset($da_type[$k]) ? $da_type[$k] : '';
				$date_commited[$k] = isset($date_commited[$k]) ? $date_commited[$k] : '';
				$offense_no[$k] = isset($offense_no[$k]) ? $offense_no[$k] : '';
				$disposition[$k] = isset($disposition[$k]) ? $disposition[$k] : 0;
				$code_no[$k] = $this->conn->real_escape_string($code_no[$k]);
				$da_type[$k] = $this->conn->real_escape_string($da_type[$k]);
				$date_commited[$k] = $this->conn->real_escape_string($date_commited[$k]);
				$offense_no[$k] = $this->conn->real_escape_string($offense_no[$k]);
				if (!empty($data)) $data .= ",";
				$data .= "('{$qid}','{$emp_no}','{$code_no[$k]}','{$violation[$k]}','{$da_type[$k]}','{$date_commited[$k]}','{$offense_no[$k]}','{$disposition[$k]}')";
			}
			if (!empty($data)) {
				$this->conn->query("DELETE  FROM `ir_list` where ir_no='{$ir_no}'");
				$sql2 =  $this->conn->query("INSERT INTO `ir_list` (`ir_no`,`emp_no`,`code_no`,`violation`,`da_type`,`date_commited`,`offense_no`,`disposition`) VALUES {$data}");
				if ($sql2) {
					$resp['status'] = 'success';
				} else {
					$resp['status'] = 'failed';
					$resp['error'] = $this->conn->error;
				}
			}
		}
		// if (!empty($da_requestor_name)) {
		// 	$sql1 = $this->conn->query("UPDATE `ir_requests` set {$data} where id = '{$id}' ");
		// 	$sql2 = $this->conn->query("UPDATE `ir_requests` set has_da = 1 where id = '{$id}' ");
		// }
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$resp['msg'] = " Incident report successfully saved.";
			else
				$resp['msg'] = " Incident report successfully updated.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}

		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	} // saving ir to database: end
	function letter_of_explain() // saving ir to database: start
	{
		extract($_POST);
		$data = "";

		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		$sql = "UPDATE `ir_list` set {$data} where id = '{$id}' ";
		$save = $this->conn->query($sql);

		if ($save) {
			$no_why =  $this->conn->query("SELECT id from `ir_requests` where `why1` IS NULL and `emp_no` = '{$emp_no}' and `ir_no` = '{$ir_no}' ")->num_rows;
			// var_dump($hiodas);
			if ($no_why == 1) {
				$date_now = date('Y-m-d H:i:s');
				$this->conn->query("UPDATE `ir_list` set `date_of_LOE` = '{$date_now}' where id = '{$id}'");
				$this->conn->query("UPDATE `ir_requests` set why1 = 'value' where  emp_no = '{$emp_no}' and ir_no = '{$ir_no}' ");
				// var_dump($no_why);
				// } else if ($no_why = 1) {
			}
		}
		if ($save) {

			$resp['status'] = 'success';
			$resp['msg'] = " Letter of explanation successfully saved.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}

		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	} // saving ir to database: end
	function ir_delete()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE  FROM `ir_list` where id='{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', " IR successfully deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function issue_da()
	{
		extract($_POST);
		$DA_date = date('Y-m-d H:i:s');
		if (!empty($this->settings->userdata('EMPLOYID')))
			$del = $this->conn->query("UPDATE `ir_requests` set has_da = 1,da_requested_date = '{$DA_date}',da_requestor_name = '{$this->settings->userdata('EMPLOYID')}' where id = '{$id}' ");
		else
			$del = $this->conn->query("UPDATE `ir_requests` set has_da = 1,da_requested_date = '{$DA_date}',da_requestor_name = '{$_POST['requestor_id']}' where id = '{$id}' ");
		// $del = $this->conn->query("DELETE  FROM `ir_list` where id='{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "DA successfully issued.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function ack_data()
	{
		// extract($_POST);
		// $del = $this->conn->query("UPDATE `ir_requests` set acknowledge_da = 1, acknowledge_date = NOW()  where id = '{$id}' and emp_no  =  '{$emp_no}'");
		// $del = $this->conn->query("DELETE  FROM `ir_list` where id='{$id}'");

		extract($_POST);
		if ($sign == 2) {
			$del = $this->conn->query("UPDATE `ir_requests` set da_status=2, hr_mngr='{$mngr}', hr_mngr_sign_date=NOW() where id = '{$id}' and emp_no  =  '{$emp_no}'");
		} elseif ($sign == 3) {
			$del = $this->conn->query("UPDATE `ir_requests` set da_status=3, dh_da_sign_date=NOW() where id = '{$id}' and emp_no  =  '{$emp_no}'");
		} elseif ($sign == 4) {
			$del = $this->conn->query("UPDATE `ir_requests` set da_status=4, dm_name = '{$this->settings->userdata('EMPLOYID')}', dm_sign_date=NOW() where id = '{$id}' and emp_no  =  '{$emp_no}'");
		} elseif ($sign == 5) {
			$del = $this->conn->query("UPDATE `ir_requests` set acknowledge_da = 1, acknowledge_date = NOW(),da_status=5  where id = '{$id}' and emp_no  =  '{$emp_no}'");
		}


		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "DA successfully passed.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function appeal_disappr_ir()
	{
		extract($_POST);
		// var_dump($disappr_reason);
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_status` = 2,`od_status` = 2,`ir_status` = 3,`disappr_reason`='{$disappr_reason}' where id = '{$id}'");
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_name` = '{$name}',`hr_status` = 2,`dh_sign_date` = NOW() where id = '{$id}'");
		$disapprove_remarks = $this->conn->real_escape_string($disapprove_remarks);

		$del = $this->conn->query("UPDATE `ir_requests` set od_name ='{$name}',`appeal_status` = 5,`od_sign_date` = NOW(),`disapprove_remarks`='{$disapprove_remarks}' where id = '{$id}'");

		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Appeal disapproved.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function appeal_invalid_ir()
	{
		extract($_POST);
		// var_dump($disappr_reason);
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_status` = 2,`od_status` = 2,`ir_status` = 3,`disappr_reason`='{$disappr_reason}' where id = '{$id}'");
		// $del = $this->conn->query("UPDATE `ir_requests` set `dh_name` = '{$name}',`hr_status` = 2,`dh_sign_date` = NOW() where id = '{$id}'");
		$disapprove_remarks = $this->conn->real_escape_string($disapprove_remarks);

		$del = $this->conn->query("UPDATE `ir_requests` set valid_appeal_name ='{$name}',`appeal_status` = 3,`valid_appeal_sign_date` = NOW(),`disapprove_remarks`='{$disapprove_remarks}' where id = '{$id}'");

		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Appeal disapproved.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function appeal_ir() // saving ir to database: start
	{
		extract($_POST);
		foreach ($code_no as $k1 => $v1) {
			$data1 = "";
			// $v = empty($v) ? NULL : $v;
			if (!in_array($k1, array('code_no'))) {
				if (!empty($data1)) $data1 .= ",";
				$data1 .= " `{$k1}`='{$v1}' ";
			}
			$id = isset($id) ? $id : '';
			$ir_no = isset($ir_no) ? $ir_no : '';
			$appeal_name = isset($appeal_name) ? $appeal_name : '';
			$code_no[$k1] = isset($code_no[$k1]) ? $code_no[$k1] : '';
			// $valid[$k1] = isset($valid[$k1]) ? $valid[$k1] : '';
			$appeal_date[$k1] = isset($appeal_date[$k1]) ? $appeal_date[$k1] : '';
			// $date_commited[$k1] = isset($date_commited[$k1]) ? $date_commited[$k1] : '';
			$appeal_days[$k1] = isset($appeal_days[$k1]) ? $appeal_days[$k1] : '';
			$appeal_remarks = isset($appeal_remarks) ?  $this->conn->real_escape_string($appeal_remarks) : '';
			$appeal_status = isset($appeal_status) ? $appeal_status : '';
			// // $disposition[$k1] = isset($disposition[$k1]) ? $disposition[$k1] : '';
			// $valid[$k1] = isset($valid[$k1]) ? $valid[$k1] : '';
			// $code_no[$k1] = $this->conn->real_escape_string($code_no[$k1]);
			// $da_type[$k1] = $this->conn->real_escape_string($da_type[$k1]);
			// $date_commited[$k1] = $this->conn->real_escape_string($date_commited[$k1]);
			// $offense_no[$k1] = $this->conn->real_escape_string($offense_no[$k1]);
			if (!empty($data1)) $data1 .= ",";
			// var_dump($date_of_suspension[$k1]);
			$save = $this->conn->query("UPDATE `ir_list` SET appeal_date = '{$appeal_date[$k1]}', appeal_days = '{$appeal_days[$k1]}' WHERE code_no = '{$code_no[$k1]}' and ir_no = '{$ir_no}'");
			$this->conn->query("UPDATE `ir_requests` SET appeal_name = '{$appeal_name}',appeal_status = '{$appeal_status}', date_of_appeal = NOW(), appeal_remarks = '{$appeal_remarks}' WHERE id = '{$id}'");
		}
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$resp['msg'] = " Incident report successfully saved.";
			else
				$resp['msg'] = " Incident report successfully updated.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}

		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	} // saving ir to database: end
	function save_operator()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}

		$check = $this->conn->query("SELECT * FROM `ir_operator` where `emp_no` = '{$emp_no}'  " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "HR Administrator number already exists.";
		} else {
			if (empty($id)) {
				$sql = "INSERT INTO `ir_operator` set {$data} ";
			} else {
				$sql = "UPDATE `ir_operator` set {$data} where id = '{$id}' ";
			}
			$save = $this->conn->query($sql);
			if ($save) {
				$resp['status'] = 'success';
				if (empty($id))
					$resp['msg'] = "New HR Administrator successfully saved.";
				else
					$resp['msg'] = "HR Administrator  successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = $this->conn->error . "[{$sql}]";
			}
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function delete_operator()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE  FROM `ir_operator` where id='{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "HR Administrator successfully deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function inactive_ir()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `ir_requests` SET `is_inactive` = 1 WHERE emp_no = '{$emp_no}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "IR successfully updated.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_violation()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		$sql = "UPDATE `ir_list` set {$data} where id = '{$id}' ";
		$save = $this->conn->query($sql);
		if ($save) {
			$resp['status'] = 'success';
			$resp['msg'] = "IR successfully updated.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'code_number':
		echo $Master->code_number();
		break;
	case 'delete_code_number':
		echo $Master->delete_code_number();
		break;
	case 'update_suspension_date':
		echo $Master->update_suspension_date();
		break;
	case 'save_ir_request':
		echo $Master->save_ir_request();
		break;
	case 'appr_irda':
		echo $Master->appr_irda();
		break;
	case 'valid_to_da':
		echo $Master->valid_to_da();
		break;
	case 'update_ir_request':
		echo $Master->update_ir_request();
		break;
	case 'cancel_ir':
		echo $Master->cancel_ir();
		break;
	case 'cancel_ir_fromhr':
		echo $Master->cancel_ir_fromhr();
		break;
	case 'invalid_ir_to_da':
		echo $Master->invalid_ir_to_da();
		break;
	case 'mngr_disappr_ir':
		echo $Master->mngr_disappr_ir();
		break;
	case 're_pass_ir':
		echo $Master->re_pass_ir();
		break;
	case 'repass_mngr_ir':
		echo $Master->repass_mngr_ir();
		break;
	case 'letter_of_explain':
		echo $Master->letter_of_explain();
		break;
	case 'ir_delete':
		echo $Master->ir_delete();
		break;
	case 'issue_da':
		echo $Master->issue_da();
		break;
	case 'ack_data':
		echo $Master->ack_data();
		break;
	case 'appeal_disappr_ir':
		echo $Master->appeal_disappr_ir();
		break;
	case 'appeal_invalid_ir':
		echo $Master->appeal_invalid_ir();
		break;
	case 'appeal_ir':
		echo $Master->appeal_ir();
		break;
	case 'save_operator':
		echo $Master->save_operator();
		break;
	case 'delete_operator':
		echo $Master->delete_operator();
		break;
	case 'inactive_ir':
		echo $Master->inactive_ir();
		break;
	case 'update_violation':
		echo $Master->update_violation();
		break;
	default:
		// echo $sysset->index();
		break;
}
