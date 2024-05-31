<?php require_once('./../../../config.php'); ?>
<style>
	#uni_modal .modal-header,
	#uni_modal .modal-footer {
		display: none;
	}

	td {
		vertical-align: middle;
	}
</style>
<div class="text-center">
	<h2>Code of conduct</h2>
</div>
<hr>
<br>
<div class=" container-fluid overflow-auto">
	<table class="table table-bordered table-stripped text-center">
		<colgroup>

			<col width="5%">
			<col width="15%">
			<col width="55%">
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
					<td><?php echo $row['code_number'] ?></td>
					<td>
						<?= $row['violation'] ?>
					</td>
					<td>
						<?php echo isset($row['first_offense']) && $row['first_offense'] == 0 ?  '--' : '' ?>
						<?php echo isset($row['first_offense']) && $row['first_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
						<?php echo isset($row['first_offense']) && $row['first_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
						<?php echo isset($row['first_offense']) && $row['first_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
						<?php echo isset($row['first_offense']) && $row['first_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
						<?php echo isset($row['first_offense']) && $row['first_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
					</td>
					<td>
						<?php echo isset($row['second_offense']) && $row['second_offense'] == 0 ?  '--' : '' ?>
						<?php echo isset($row['second_offense']) && $row['second_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
						<?php echo isset($row['second_offense']) && $row['second_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
						<?php echo isset($row['second_offense']) && $row['second_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
						<?php echo isset($row['second_offense']) && $row['second_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
						<?php echo isset($row['second_offense']) && $row['second_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
					</td>
					<td>
						<?php echo isset($row['third_offense']) && $row['third_offense'] == 0 ?  '--' : '' ?>
						<?php echo isset($row['third_offense']) && $row['third_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
						<?php echo isset($row['third_offense']) && $row['third_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
						<?php echo isset($row['third_offense']) && $row['third_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
						<?php echo isset($row['third_offense']) && $row['third_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
						<?php echo isset($row['third_offense']) && $row['third_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
					</td>
					<td>
						<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 0 ?  '--' : '' ?>
						<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
						<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
						<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
						<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
						<?php echo isset($row['fourth_offense']) && $row['fourth_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
					</td>
					<td>
						<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 0 ?  '--' : '' ?>
						<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 1 ?  '<span class="badge badge-success rounded-pill">Verbal Warning</span>' : '' ?>
						<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 2 ?  '<span class="badge badge-primary rounded-pill">Written Warning</span>' : '' ?>
						<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 3 ?  '<span class="badge badge-secondary rounded-pill">3 Days Suspension</span>' : '' ?>
						<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 4 ?  '<span class="badge badge-warning rounded-pill">7 Days Suspension</span>' : '' ?>
						<?php echo isset($row['fifth_offense']) && $row['fifth_offense'] == 5 ?  '<span class="badge badge-danger rounded-pill">Dismissal</span>' : '' ?>
					</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>
<br>
<div class="footer text-right m-2">
	<button class="btn bg-secondary btn-block-sm col-2" data-dismiss="modal">Close</button>
</div>
<script>
	$(document).ready(function() {

		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
</script>