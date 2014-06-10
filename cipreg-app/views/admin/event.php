<div class="col-md-10">
	<h1>Current Events</h1>
	<hr>
	
	<div class="row">
		<?php foreach ($events as $key => $value) {
			echo '<div class="col-md-3">'.PHP_EOL;
			echo '	<div class="panel panel-default">'.PHP_EOL;
			echo '		<div class="panel-heading">'.PHP_EOL;
			echo '			<a href="#" class="btn btn-xs btn-default pull-right btn-edit" data-id="'.$key.'">'.PHP_EOL;
			echo '				<span class="glyphicon glyphicon-edit"></span>'.PHP_EOL;
			echo '			</a>'.PHP_EOL;
			echo '<span class="name">'.$value.'</span>'.PHP_EOL;
			echo '		</div>'.PHP_EOL;
			echo '	</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		} ?>
	</div>
	<form action="" class="form-inline" id="add_event">
		<label for="event_name">Add new event</label>
		<input type="text" id="event_name" name="event_name" class="form-control">
		<input type="button" value="Add" id="btn_add_event" class="btn btn-default">
	</form>
</div>

<script>
$(document).ready(function (){
	$("#btn_add_event").click(function () {
		$.post( "<?php echo site_url('admin/new_event') ?>", $("#add_event").serialize() )
		 .done(function () {
		 	location.reload();
		 })
		 .fail(function () {
		 	$(this).text("Failed");
		 });
	});
	$(".btn-edit").click(function () {
		var id = parseInt($(this).data("id"))+1;
		var name = $(this).parent().children(".name").text();
		$("#edit-modal-label").text("Edit activity "+id);
		$("#e_event_id").val(id);
		$("#e_event_name").val(name);
		$('#edit-modal').modal('toggle');
	});
	$("#e_submit").click(function () {
		$(this).text("Loading...");
		$.post( "<?php echo site_url('admin/edit_event') ?>", $("#event_edit").serialize() )
		 .done(function () {
		 	location.reload();
		 })
		 .fail(function () {
		 	$(this).text("Failed");
		 });
	});
});
</script>

<!-- Modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="edit-modal-label">Edit activity</h4>
      </div>
      <form action="" class="form" id="event_edit">
      	<div class="modal-body">
      		<input type="text" name="event_id" id="e_event_id" style="display:none;">
      	  	<input type="text" name="event_name" id="e_event_name" placeholder="New name" class="form-control">
      	</div>
      	<div class="modal-footer">
      	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	  <button type="button" class="btn btn-primary" id="e_submit">Save</button>
      	</div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->