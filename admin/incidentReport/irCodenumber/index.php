<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<style>
	td {
		vertical-align: middle;
	}
</style>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Code numbers</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" class="btn btn-flat btn-primary holi"><span class="fas fa-plus"></span> Add Code</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="container-fluid overflow-auto">
				<table class="table table-bordered table-stripped">
					<colgroup>
						<col width="5%">
						<col width="10%">
						<col width="50%">
						<!-- <col width="35%"> -->
						<col width="5%">
						<col width="5%">
						<col width="5%">
						<col width="5%">
						<col width="5%">
						<col width="5%">
						<col width="5%">
					</colgroup>
					<thead>
						<tr class="bg-gradient-primary">
							<th>#</th>
							<th>Code No.</th>
							<th>Violation</th>
							<th>1st Offense</th>
							<th>2nd Offense</th>
							<th>3rd Offense</th>
							<th>4th Offense</th>
							<th>5th Offense</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT * from `ir_code_no` ");
						while ($row = $qry->fetch_assoc()) :
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td class="text-center"><?php echo $row['code_number'] ?></td>
								<td class="text-center">
									<!-- <p class="m-0 truncate-1"><?= $row['violation'] ?></p> -->
									<?= $row['violation'] ?>
								</td>
								<td class="text-center">
									<?php echo isset($row['first_offense']) && $row['first_offense'] == 0 ?  '--' : '' ?>
									<?php echo isset($row['first_offense']) && $row['first_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
									<?php echo isset($row['first_offense']) && $row['first_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
									<?php echo isset($row['first_offense']) && $row['first_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
									<?php echo isset($row['first_offense']) && $row['first_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
									<?php echo isset($row['first_offense']) && $row['first_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
								</td>
								<td class="text-center">
									<?php echo isset($row['second_offense']) && $row['second_offense'] == 0 ?  '--' : '' ?>
									<?php echo isset($row['second_offense']) && $row['second_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
									<?php echo isset($row['second_offense']) && $row['second_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
									<?php echo isset($row['second_offense']) && $row['second_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
									<?php echo isset($row['second_offense']) && $row['second_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
									<?php echo isset($row['second_offense']) && $row['second_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
								</td>
								<td class="text-center">
									<?php echo isset($row['third_offense']) && $row['third_offense'] == 0 ?  '--' : '' ?>
									<?php echo isset($row['third_offense']) && $row['third_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
									<?php echo isset($row['third_offense']) && $row['third_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
									<?php echo isset($row['third_offense']) && $row['third_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
									<?php echo isset($row['third_offense']) && $row['third_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
									<?php echo isset($row['third_offense']) && $row['third_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
								</td>
								<td class="text-center">
									<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 0 ?  '--' : '' ?>
									<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
									<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
									<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
									<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
									<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
								</td>
								<td class="text-center">
									<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 0 ?  '--' : '' ?>
									<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
									<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
									<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
									<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
									<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
								</td>
								<td class="text-center">
									<?php if ($row['status'] == 1) : ?>
										<span class="badge badge-success bg-gradient-success px-3 rounded-pill">Active</span>
									<?php else : ?>
										<span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Inactive</span>
									<?php endif; ?>
								</td>
								<td align="center">
									<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
										Action
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu" role="menu">
										<!-- <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a> -->
										<!-- <div class="dropdown-divider"></div> -->
										<a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-success"></span> Edit</a>
										<!-- <div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a> -->
									</div>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {

		$('.holi').click(function() {
			uni_modal('Add New Holiday', "incidentreport/irCodenumber/add_code_number.php")
		})
		$('.edit_data').click(function() {
			uni_modal('Update Holiday', "incidentreport/irCodenumber/manage_code_number.php?id=" + $(this).attr('data-id'))
		})
		$('.view_data').click(function() {
			uni_modal('View holiday Details', "incidentreport/irCodenumber/view_code_number.php?id=" + $(this).attr('data-id'))
		})
		$('.delete_data').click(function() {
			_conf("Are you sure to delete this code number permanently?", "delete_code_number", [$(this).attr('data-id')])
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})

	function delete_code_number($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/IR_DA_Master.php?f=delete_code_number",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("An error occured.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("An error occured.", 'error');
					end_loader();
				}
			}
		})
	}
</script>