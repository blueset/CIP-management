<div class="container">
	<div class="page-header">
	<img src="https://raw.githubusercontent.com/einsteinly/vigorise-cip-record/master/app/assets/images/logo.png" alt="logo" class="pull-right" style="width:200px;height:auto;margin-right:20px">
		<h1>Welcome to Team VigoRISE. <br>
			<small>Team VigoRISE Online CIP Reporting System</small></h1>
	</div>
	<div class="clearfix">
		
		<div><strong>Team VigoRISE Online CIP Reporting System</strong> is managed by <strong>Team VigoRISE Human Resources Subordinate Committee</strong>, and programmed by <a href="http://1a23.com">Blueset Studio</a>.</div>
	</div>
	<h1>Latest Update</h1>
	<hr>
	<p>Showing last 50 claims only.</p>
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Time</th>
				<th>Name</th>
				<th>Event</th>
				<th>Hours</th>
				<th>Person in charge</th>
				<th>Remarks</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>

		<?php 
		foreach ($table as $row){
			if($row->status <= -1){
				echo '<tr class="warning">';
			}else{
				echo '<tr class="success">';
			}
			echo '<td>'.$row->id.'</td>';
			echo '<td>'.$row->time.'</td>';
			echo '<td>'.$row->name.'</td>';
			echo '<td>'.$row->event.'</td>';
			echo '<td>'.$row->hours.'</td>';
			echo '<td>'.$row->in_charge_name.'</td>';
			echo '<td>'.$row->remarks.'</td>';
			echo '<td>'.$row->status_str.'</td>';
			echo '</tr>';
		}
		?>
		</tbody>
	</table>

	<?php echo anchor('claim','Claim CIP Record','class="btn btn-primary"');?>
</div>

