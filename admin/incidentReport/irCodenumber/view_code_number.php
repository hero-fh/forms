<?php
require_once('./../../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `ir_code_no` where id = '{$_GET['id']}'  ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	} else {
?>
		<center>Unknown COde number</center>
		<style>
			#uni_modal {
				display: none
			}
		</style>
		<div class="text-right">
			<button class="btn btn-gradient-dark btn-flat"><i class="fa fa-times"></i> Close</button>
		</div>
<?php
	}
}
?>


<div class="container-fluid">
	<form action="" id="code_number-form">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="code_number" class="control-label">Code number</label>
			<input name="code_number" id="code_number" pattern="([À-ža-zA-Z0-9\s]+){2,}" type="text" class="form-control form-control-sm form-control-border" value="<?php echo isset($code_number) ? $code_number : ''; ?>">
		</div>
		<!-- <div class="form-group">
			<label for="holi_date" class="control-label">Date</label>
			<input name="holi_date" id="holi_date" type="date" class="form-control form-control-sm form-control-border" value="<?php echo isset($holi_date) ? $holi_date : ''; ?>">
		</div> -->
		<div class="form-group">
			<label for="violation" class="control-label">Violation</label>
			<textarea name="violation" id="violation" rows="3" class="form-control form-control-sm rounded-0 no-resize"><?php echo isset($violation) ? $violation : ''; ?></textarea>
		</div>
		<div class="form-group">
			<label for="violation" class="control-label">First offense</label>
			<select required name="first_offense" class="custom-select">
				<option value="" disabled selected>--Select offense--</option>
				<option <?php echo isset($first_offense) && $first_offense == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
				<option <?php echo isset($first_offense) && $first_offense == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
				<option <?php echo isset($first_offense) && $first_offense == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
				<option <?php echo isset($first_offense) && $first_offense == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
				<option <?php echo isset($first_offense) && $first_offense == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
			</select>
		</div>
		<div class="form-group">
			<label for="violation" class="control-label">Second offense</label>
			<select required name="second_offense" class="custom-select">
				<option value="" disabled selected>--Select offense--</option>
				<option <?php echo isset($second_offense) && $second_offense == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
				<option <?php echo isset($second_offense) && $second_offense == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
				<option <?php echo isset($second_offense) && $second_offense == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
				<option <?php echo isset($second_offense) && $second_offense == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
				<option <?php echo isset($second_offense) && $second_offense == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
			</select>
		</div>
		<div class="form-group">
			<label for="violation" class="control-label">Third offense</label>
			<select required name="third_offense" class="custom-select">
				<option value="" disabled selected>--Select offense--</option>
				<option <?php echo isset($third_offense) && $third_offense == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
				<option <?php echo isset($third_offense) && $third_offense == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
				<option <?php echo isset($third_offense) && $third_offense == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
				<option <?php echo isset($third_offense) && $third_offense == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
				<option <?php echo isset($third_offense) && $third_offense == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
			</select>
		</div>
		<div class="form-group">
			<label for="violation" class="control-label">Fourth offense</label>
			<select required name="fourth_offense" class="custom-select">
				<option value="" disabled selected>--Select offense--</option>
				<option <?php echo isset($fourth_offense) && $fourth_offense == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
				<option <?php echo isset($fourth_offense) && $fourth_offense == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
				<option <?php echo isset($fourth_offense) && $fourth_offense == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
				<option <?php echo isset($fourth_offense) && $fourth_offense == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
				<option <?php echo isset($fourth_offense) && $fourth_offense == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
			</select>
		</div>
		<div class="form-group">
			<label for="violation" class="control-label">Fifth offense</label>
			<select required name="fifth_offense" class="custom-select">
				<option value="" disabled selected>--Select offense--</option>
				<option <?php echo isset($fifth_offense) && $fifth_offense == 1 ? 'selected' : '' ?> value="1">Verbal Warning</option>
				<option <?php echo isset($fifth_offense) && $fifth_offense == 2 ? 'selected' : '' ?> value="2">Written Warning</option>
				<option <?php echo isset($fifth_offense) && $fifth_offense == 3 ? 'selected' : '' ?> value="3">3 Days Suspension</option>
				<option <?php echo isset($fifth_offense) && $fifth_offense == 4 ? 'selected' : '' ?> value="4">7 Days Suspension</option>
				<option <?php echo isset($fifth_offense) && $fifth_offense == 5 ? 'selected' : '' ?> value="5">Dismissal</option>
			</select>
		</div>
		<div class="form-group">
			<label for="status" class="control-label">Status</label>
			<select name="status" id="status" class="custom-select selevt">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
				<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
			</select>
		</div>

	</form>
</div>
<script>
	$(document).ready(function() {
		$('#uni_modal #code_number-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			$('.err-msg').remove();
			var el = $('<div>')
			el.addClass("alert err-msg")
			el.hide()
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/Master.php?f=code_number",
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

		$('.summernote').summernote({
			height: 200,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
				['fontname', ['fontname']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ol', 'ul', 'paragraph', 'height']],
				['table', ['table']],
				['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
			]
		})
	})
</script>