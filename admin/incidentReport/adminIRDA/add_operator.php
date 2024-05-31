<?php
require_once('../../../config.php');
?>
<style>
	#uni_modal .modal-footer {
		display: none;
	}
</style>

<div class="container-fluid">
	<form action="" id="operator-form">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="name" class="control-label">Employee No:</label>&nbsp;&nbsp;<small>(&nbsp;<i>Search by employee id or name</i>&nbsp;)</small>
			<select name="emp_no" onchange="showHint(this.value)" id="emp_no" class="form-control rounded-0 select2" required>
				<option value="" disabled selected>Please select an employee here</option>
				<?php
				$category = $conn->query("SELECT * FROM `employee_masterlist` where EMPLOYID !=0");
				while ($row = $category->fetch_assoc()) :
				?>
					<option value="<?= $row['EMPLOYID'] ?>"><?= $row['EMPLOYID'] . ' - ' . $row['EMPNAME'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label">Employee name:</label>
			<input required readonly type="text" name="emp_name" id="emp_name" class="form-control  rounded-0" value="<?php echo isset($emp_name)  ? $emp_name : '' ?>">
		</div>
		<div class="form-group">
			<label class="control-label">Handled violation:</label>
			<select name="to_handle" id="to_handle" class="form-control rounded-0 select2" required>
				<option value="" disabled selected>--Please select--</option>
				<option value="1">Administrative</option>
				<option value="2">Quality</option>
			</select>
		</div>
		<div class="text-right">
			<button class="btn btn-primary" type="submit">Submit</button>
			<button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		</div>
	</form>
</div>
<script>
	function showHint(str) {
		if (str.length == 0) {
			document.getElementById("emp_name").value = "";
			return;
		} else {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("emp_name").value = this.responseText;
					// document.getElementById("requestor_department").value = this.responseText;
				}
			};
			xmlhttp.open("GET", _base_url_ + "get_emp.php?q=" + str, true);
			xmlhttp.send();
		}
	}
	$(document).ready(function() {
		$('.select2').select2({
			width: 'resolve'
		})
		$('#uni_modal #operator-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			$('.err-msg').remove();
			var el = $('<div>')
			el.addClass("alert err-msg")
			el.hide()
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/IR_DA_Master.php?f=save_operator",
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error: err => {
					console.error(err)
					el.addClass('alert-danger').text("An error occured");
					_this.prepend(el)
					el.show('.modal')
					end_loader();
				},
				success: function(resp) {
					if (typeof resp == 'object' && resp.status == 'success') {
						location.reload();
					} else if (resp.status == 'failed' && !!resp.msg) {
						el.addClass('alert-danger').text(resp.msg);
						_this.prepend(el)
						el.show('.modal')
					} else {
						el.text("An error occured");
						console.error(resp)
					}
					$("html, body").scrollTop(0);
					end_loader()

				}
			})
		})
	})
</script>