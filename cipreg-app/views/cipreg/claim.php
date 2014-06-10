<div class="container">
	<div class="page-header">
		<h1>Claim your CIP</h1>
	</div>
	<p>Fill the form and claim your cip record.</p>
	<?php if($valid_err != ""){echo '<div class="alert alert-danger">'.$valid_err.'</div>';} ?>
	<?php echo form_open('claim','class="row"'); ?>
	
	<div id="user-block" class="col-md-6">
		<p>
			<a href="#" class="btn btn-default" id="btn-add-user">Add participant</a>
		</p>
		<div class="well user-well" id="user-1">
			
			<div class="form-group"><label for="name">Name</label><input type="text" required class="form-control" id="name" placeholder="Name" name="name[]"></div>
			<div class="form-group"><label for="nric">NRIC</label><input type="text" required maxlength="9" class="form-control" id="nric" placeholder="NRIC" name="nric[]"></div>
			<div class="form-group"><label for="school_id">School</label><?php echo form_dropdown('school_id[]', $school_name, '', 'class="form-control"'); ?></div>
			<div class="form-group"><label for="class">Class</label><input type="text" class="form-control" id="class" placeholder="Class" name="class[]"></div>
		</div>
	</div>
	
	<div id="event-block" class="col-md-6">
		<p>
			<a href="#" class="btn btn-default" id="btn-add-event">Add Activity</a>
		</p>
		<div class="well event-well" id="event-1">
			<div class="form-group"><label for="event_id">Activity / Event</label><?php echo form_dropdown('event_id[]', $activity_name, '', 'class="form-control"'); ?></div>
			<div class="form-group"><label for="hours">Number of hours</label><input type="number" required step="any" class="form-control" id="hours" name="hours[]" placeholder="Number" of="" hours=""></div>
			<div class="form-group"><label for="remarks">Remarks</label><input type="text" class="form-control" id="remarks[]" name="remarks" placeholder="Remarks"></div>
			<div class="form-group"><label for="in_charge">Person in charge</label><?php echo form_dropdown('in_charge[]', $user_name, '', 'class="form-control"'); ?></div>
		</div>
	</div>
	

	<div class="col-md-12">
		<input name="submit" class="btn btn-primary" type="submit" value="Submit" />
	</div>
	</form>
</div>

<script>
$(document).ready(function() {
	$("#btn-add-user").click(function () {
		$("#user-block").append('<div class="well user-well" id="user-1"><a href="#" class="btn btn-sm btn-danger pull-right btn-delete">&times;</a>'+$("#user-1").html()+'</div>');
		$(".btn-delete").click(function () {
			$(this).parent().remove();
		});
	});
	$("#btn-add-event").click(function () {
		$("#event-block").append('<div class="well event-well" id="event-1"><a href="#" class="btn btn-sm btn-danger pull-right btn-delete">&times;</a>'+$("#event-1").html()+'</div>');
		$(".btn-delete").click(function () {
			$(this).parent().remove();
		});
	});

});
</script>