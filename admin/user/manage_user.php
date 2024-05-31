<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$user = $conn->query("SELECT * FROM system_user where id ='{$_GET['id']}'");
	foreach ($user->fetch_array() as $k => $v) {
		$meta[$k] = $v;
	}
}
?>
<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline card-primary">
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">
				<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
				<div class="row">
					<div class="form-group col-6">
						<label for="log_user">Name</label>
						<input type="text" name="log_user" id="log_user" class="form-control" value="<?php echo isset($meta['log_user']) ? $meta['log_user'] : '' ?>" required autocomplete="off">
					</div>

					<div class="form-group col-6">
						<label for="log_category">User Type</label>
						<select name="log_category" id="log_category" class="custom-select" value="<?php echo isset($meta['log_category']) ? $meta['log_category'] : '' ?>" required>
							<option value="1" <?php echo isset($meta['log_category']) && $meta['log_category'] == 1 ? 'selected' : '' ?>>Administrator</option>
							<option value="2" <?php echo isset($meta['log_category']) && $meta['log_category'] == 2 ? 'selected' : '' ?>>HR Manager</option>
							<option value="3" <?php echo isset($meta['log_category']) && $meta['log_category'] == 3 ? 'selected' : '' ?>>HR Support 1</option>
							<option value="4" <?php echo isset($meta['log_category']) && $meta['log_category'] == 4 ? 'selected' : '' ?>>HR Support 2</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-6">
						<label for="log_username">Username</label>
						<input type="text" name="log_username" id="log_username" class="form-control" value="<?php echo isset($meta['log_username']) ? $meta['log_username'] : '' ?>" required autocomplete="off">
					</div>
					<div class="form-group col-6">
						<label for="log_password">Password</label>
						<input type="password" name="log_password" id="log_password" class="form-control" value="" autocomplete="off" <?php echo isset($meta['id']) ? "" : 'required' ?>>
						<?php if (isset($_GET['id'])) : ?>
							<small class="text-info"><i>Leave this blank if you dont want to change the password.</i></small>
						<?php endif; ?>
					</div>

				</div>
			</form>
		</div>
	</div>
	<div class="card-footer">
		<div class="col-md-12">
			<div class="row">
				<button class="btn btn-sm btn-primary mr-2" form="manage-user">Save</button>
				<a class="btn btn-sm btn-secondary" href="./?page=user/list">Cancel</a>
			</div>
		</div>
	</div>
</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	$(function() {
		$('.select2').select2({
			width: 'resolve'
		})
	})

	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	$('#manage-user').submit(function(e) {
		e.preventDefault();
		var _this = $(this)
		start_loader()
		$.ajax({
			url: _base_url_ + 'classes/Users.php?f=save_users',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					location.href = './?page=user/list';
				} else {
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					$("html, body").animate({
						scrollTop: 0
					}, "fast");
				}
				end_loader()
			}
		})
	})
</script>