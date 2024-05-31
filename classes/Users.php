<?php
require_once('../config.php');
class Users extends DBConnection
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
	public function save_users()
	{
		extract($_POST);
		$oid = $id;
		$data = '';
		// if (isset($oldpassword)) {
		// 	if (md5($oldpassword) != $this->settings->userdata('password')) {
		// 		return 4;
		// 	}
		// }
		$chk = $this->conn->query("SELECT * FROM `system_user` where log_username ='{$log_username}' " . ($id > 0 ? " and id!= '{$id}' " : ""))->num_rows;
		if ($chk > 0) {
			return 3;
			exit;
		}
		foreach ($_POST as $k => $v) {
			if (in_array($k, array('log_user', 'log_username', 'log_category'))) {
				if (!empty($data)) $data .= " , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if (!empty($log_password)) {
			// $log_password = md5($log_password);
			if (!empty($data)) $data .= " , ";
			$data .= " `log_password` = '{$log_password}' ";
		}

		if (empty($id)) {
			$qry = $this->conn->query("INSERT INTO system_user set {$data}");
			if ($qry) {
				$id = $this->conn->insert_id;
				$this->settings->set_flashdata('success', 'User Details successfully saved.');
				$resp['status'] = 1;
			} else {
				$resp['status'] = 2;
			}
		} else {
			$qry = $this->conn->query("UPDATE system_user set $data where id = {$id}");
			if ($qry) {
				$this->settings->set_flashdata('success', 'User Details successfully updated.');
				if ($id == $this->settings->userdata('id')) {
					foreach ($_POST as $k => $v) {
						if ($k != 'id') {
							if (!empty($data)) $data .= " , ";
							$this->settings->set_userdata($k, $v);
						}
					}
				}
				$resp['status'] = 1;
			} else {
				$resp['status'] = 2;
			}
		}
		// if ($resp['status'] == 1) {
		// 	$data = "";
		// 	foreach ($_POST as $k => $v) {
		// 		if (!in_array($k, array('id', 'firstname', 'middlename', 'lastname', 'department', 'log_username', 'password', 'log_category', 'oldpassword'))) {
		// 			if (!empty($data)) $data .= ", ";
		// 			$v = $this->conn->real_escape_string($v);
		// 			$data .= "('{$id}','{$k}', '{$v}')";
		// 		}
		// 	}
		// 	if (!empty($data)) {
		// 		//TANGALIN ANG * SA QUERY PAG VERSION 8.0 NA ANG XAMPP. DAHIL ERROR NA YAN SA SQL QUERY
		// 		// LALABAS ANG ERROR NA log_username ALREADY EXIST KAHIT WALA PANAMAN. SA CREATE AND UPDATE USER YAN.
		// 		$this->conn->query("DELETE * FROM `user_meta` where user_id = '{$id}' ");
		// 		$save = $this->conn->query("INSERT INTO `user_meta` (user_id,`meta_field`,`meta_value`) VALUES {$data}");
		// 		if (!$save) {
		// 			$resp['status'] = 2;
		// 			if (empty($oid)) {
		// 				$this->conn->query("DELETE * FROM `system_user` where id = '{$id}' ");
		// 			}
		// 		}
		// 	}
		// }


		if (isset($resp['msg']))
			$this->settings->set_flashdata('success', $resp['msg']);
		return  $resp['status'];
	}
	public function delete_users()
	{
		extract($_POST);
		// $avatar = $this->conn->query("SELECT avatar FROM system_user where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM system_user where id = $id");
		if ($qry) {
			// $avatar = explode("?", $avatar)[0];
			$this->settings->set_flashdata('success', 'User Details successfully deleted.');
			// if (is_file(base_app . $avatar))
			// 	unlink(base_app . $avatar);
			$resp['status'] = 'success';
		} else {
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}

	public function save_susers()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'password'))) {
				if (!empty($data)) $data .= ", ";
				$data .= " `{$k}` = '{$v}' ";
			}
		}

		if (!empty($password))
			$data .= ", `password` = '" . md5($password) . "' ";

		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = 'uploads/' . strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], '../' . $fname);
			if ($move) {
				$data .= " , avatar = '{$fname}' ";
				if (isset($_SESSION['userdata']['avatar']) && is_file('../' . $_SESSION['userdata']['avatar']))
					unlink('../' . $_SESSION['userdata']['avatar']);
			}
		}
		$sql = "UPDATE students set {$data} where id = $id";
		$save = $this->conn->query($sql);

		if ($save) {
			$this->settings->set_flashdata('success', 'User Details successfully updated.');
			foreach ($_POST as $k => $v) {
				if (!in_array($k, array('id', 'password'))) {
					if (!empty($data)) $data .= " , ";
					$this->settings->set_userdata($k, $v);
				}
			}
			if (isset($fname) && isset($move))
				$this->settings->set_userdata('avatar', $fname);
			return 1;
		} else {
			$resp['error'] = $sql;
			return json_encode($resp);
		}
	}
	function author_pass()
	{

		extract($_POST);
		$data = '';
		// if (isset($oldpassword)) {
		// 	if (md5($oldpassword) != $this->settings->userdata('password')) {
		// 		return 4;
		// 	}
		// }
		foreach ($_POST as $k => $v) {
			if (!empty($data)) $data .= ",";
			$v = !empty($v) ? addslashes($v) : null;
			$data .= "`{$k}`" . ($v !== null ? "='{$v}'" : " = NULL");
		}
		$insertQuery = $this->conn->query("UPDATE employee_masterlist set USERNAME='$USERNAME',password='{$_POST['PASSWORD']}' where EMPLOYID = {$EMPLOYID}");
		// var_dump($data);
		// var_dump($newusername);
		// var_dump($$_POST['USERNAME']);
		if ($insertQuery) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', 'Account successfully saved.');
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$insertQuery}]";
		}
		return json_encode($resp);
	}
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save_users':
		echo $users->save_users();
		break;
	case 'author_pass':
		echo $users->author_pass();
		break;
	case 'ssave':
		echo $users->save_susers();
		break;
	case 'delete':
		echo $users->delete_users();
		break;
	default:
		// echo $sysset->index();
		break;
}
