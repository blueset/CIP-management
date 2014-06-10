<script type="text/javascript" src="<?=base_url('js/jquery.tablesorter.min.js')?>"></script> 
<style type="text/css">
	table thead tr .header {
		background-image: url(http://tablesorter.com/themes/blue/bg.gif);
		background-repeat: no-repeat;
		background-position: top right;
		cursor: pointer;
	}
	table thead tr .headerSortUp {
		background-image: url(http://tablesorter.com/themes/blue/asc.gif);
	}
	table thead tr .headerSortDown {
		background-image: url(http://tablesorter.com/themes/blue/desc.gif);
	}
</style>
<div class="col-md-10">
	<h1>Approval</h1>
	<hr>
	<table class="table table-condensed" id="table-sort" style="font-size:12px">
	<thead>
		<tr>
			<th>ID</th>
			<th>Time</th>
			<th>Name</th>
			<th>NRIC</th>
			<th>School</th>
			<th>Class</th>
			<th>Event</th>
			<th>Hours</th>
			<th>Person in Charge</th>
			<th>Remarks</th>
			<th>Status</th>
			<th style="width: 85px;">Action</th>
		</tr>
	</thead>
	<tbody>
	
	<?php 
		foreach ($claim as $row){
			if($row->status <= -1){
				echo '<tr class="warning">';
			}else{
				echo '<tr class="success">';
			}
			echo '<td class="data-id">'.$row->id.'</td>';
			echo '<td class="data-time">'.$row->time.'</td>';
			echo '<td class="data-name">'.$row->name.'</td>';
			echo '<td class="data-nric">'.$row->nric.'</td>';
			echo '<td class="data-school">'.$row->school.'</td>';
			echo '<td class="data-class">'.$row->class.'</td>';
			echo '<td class="data-event">'.$row->event.'</td>';
			echo '<td class="data-hours">'.$row->hours.'</td>';
			echo '<td class="data-in_charge_name">'.$row->in_charge_name.'</td>';
			echo '<td class="data-remarks">'.$row->remarks.'</td>';
			echo '<td class="data-status_str">'.$row->status_str.'</td>';
			if($row->status <= -1){
				echo '<td><a href="#" class="btn btn-success btn-xs btn-approve" data-id="'.$row->id.'"><span class="glyphicon glyphicon-ok"></span></a>';
			}else{
				echo '<td><span href="#" class="btn btn-default btn-xs btn-approve" disabled data-id="'.$row->id.'"><span class="glyphicon glyphicon-ok"></span></span>';
			}
			echo '<a href="#" class="btn btn-primary btn-xs btn-edit" data-id="'.$row->id.'"><span class="glyphicon glyphicon-edit"></span></a>';
			echo '<a href="#" class="btn btn-danger btn-xs btn-delete" data-id="'.$row->id.'"><span class="glyphicon glyphicon-trash"></span></a></td>';
			echo '</tr>';
		}
		?>
	<tbody></table>
	<?=$this->pagination->create_links();?>
</div>

<script>
	$('a.btn-approve').click(function () {
		$(this).text("Loading...");
		$.ajax({
			url: "<?php echo site_url('admin/approve_claim/') ?>"+"/"+$(this).data("id"),
		}).done(function() {
  			location.reload();
  		}).fail(function() {
    		$(this).text("Failed");
  		});
	});

</script>

<script>
$(document).ready(function (){
	// call the tablesorter plugin 
    $("#table-sort").tablesorter({  });
	$(".btn-edit").click(function () {
		$("#err_msg").html("");
		var claim_id = parseInt($(this).data("id"));
		var claim_nric = $(this).parent().parent().children(".data-nric").text();
		var claim_name = $(this).parent().parent().children(".data-name").text();
		var claim_nric = $(this).parent().parent().children(".data-nric").text();
		var claim_school = $(this).parent().parent().children(".data-school").text();
		var claim_class = $(this).parent().parent().children(".data-class").text();
		var claim_event = $(this).parent().parent().children(".data-event").text();
		var claim_hours = $(this).parent().parent().children(".data-hours").text();
		var claim_in_charge_name = $(this).parent().parent().children(".data-in_charge_name").text();
		var remarks = $(this).parent().parent().children(".data-remarks").text();
		$("#subcom-modal-label").text("Edit claim: "+claim_id);
  		$("#e_id").val(claim_id);
		$("#e_nric").val(claim_nric);
		$("#e_name").val(claim_name);
		$("#e_nric").val(claim_nric);
		$("#e_school_id option:contains("+claim_school+")").attr('selected', true);
		$("#e_class").val(claim_class);
		$("#e_event_id option:contains("+claim_event+")").attr('selected', true);
		$("#e_hours").val(claim_hours);
		$("#e_in_charge option:contains("+claim_in_charge_name+")").attr('selected', true);
		var remarks = $(this).parent().parent().children(".data-remarks").text();
  		$('#claim-modal').modal('toggle');
  		
	});
	$(".btn-delete").click(function () {
		var subcom_id = parseInt($(this).data("id"));
		if(confirm('Are you sure you want to delete?')){
			//New subcom
			$.ajax({type: "POST",
				dataType: "jsonp",
				url: "<?php echo site_url('admin/delete_claim') ?>?callback=?",
  				data: {id:subcom_id}
			})
  			.done(function( data ) {
  				if(data == "OK"){
  					location.reload();
  				}else{
  					alert(data);
  				}
  			})
  			.fail(function () {
	  			alert("Failed to send request.");
		 	});
		}
	});
	$("#e_submit").click(function () {
		$(this).text("Loading...");
		$("#err_msg").html("");
			//Edit subcom
			$.ajax({type: "POST",
				dataType: "jsonp",
				url: "<?php echo site_url('admin/edit_claim') ?>?callback=?",
  				data: $("#claim_man").serialize()
			})
  			.done(function( data ) {
  				if(data == "OK"){
  					location.reload();
  				}else{
  					$("#err_msg").html(data);
  					$("#submit").text("Submit");
  				}
  			})
  			.fail(function () {
	  			$("#err_msg").text("Failed to send edit request.");
  				$("#submit").text("Submit");
		 	});
	});
});
</script>

<!-- Modal -->
<div class="modal fade" id="claim-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="subcom-modal-label">Edit claim</h4>
      </div>
      <form action="" class="form" id="claim_man">
      	<div class="modal-body clearfix">
      		<input type="text" name="id" id="e_id" style="display:none;">
      	  	<div id="user-block" class="col-md-6">
				<div class="form-group"><label for="name">Name</label><input type="text" required class="form-control" id="e_name" placeholder="Name" name="name"></div>
				<div class="form-group"><label for="nric">NRIC</label><input type="text" required maxlength="9" class="form-control" id="e_nric" placeholder="NRIC" name="nric"></div>
				<div class="form-group"><label for="school_id">School</label><?php echo form_dropdown('school_id', $school_name, '', 'id="e_school_id" class="form-control"'); ?></div>
				<div class="form-group"><label for="class">Class</label><input type="text" class="form-control" id="e_class" placeholder="Class" name="class"></div>
			</div>
			<div id="event-block" class="col-md-6">
				<div class="form-group"><label for="event_id">Activity / Event</label><?php echo form_dropdown('event_id', $activity_name, '', 'id="e_event_id" class="form-control"'); ?></div>
				<div class="form-group"><label for="hours">Number of hours</label><input type="number" required step="any" class="form-control" id="e_hours" name="hours" placeholder="Number" of="" hours=""></div>
				<div class="form-group"><label for="remarks">Remarks</label><input type="text" class="form-control" id="e_remarks" name="remarks" placeholder="Remarks"></div>
				<div class="form-group"><label for="in_charge">Person in charge</label><?php echo form_dropdown('in_charge', $user_name, '', 'id="e_in_charge" class="form-control"'); ?></div>
			</div>
      	</div>
      	<div class="modal-footer">
      	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	  <button type="button" class="btn btn-primary" id="e_submit">Submit</button>
      	</div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->