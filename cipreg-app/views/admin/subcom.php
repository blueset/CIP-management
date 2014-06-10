<div class="col-md-10">
	<h1><a href="#" class="btn btn-primary pull-right" id="btn-add">Add Subcom</a>Subcom Assignment</h1>
	<hr>
	
	<div class="row">
		<?php foreach ($subcom as $value) {
			echo '<div class="col-md-4">'.PHP_EOL;
			echo '	<div class="panel panel-default">'.PHP_EOL;
			echo '		<div class="panel-heading">'.PHP_EOL;
			echo '			<div class="btn-group pull-right"><a href="#" class="btn btn-xs btn-default  btn-edit" data-id="'.$value['id'].'">'.PHP_EOL;
			echo '				<span class="glyphicon glyphicon-edit"></span>'.PHP_EOL;
			echo '			</a>'.PHP_EOL;
			echo '			<a href="#" class="btn btn-xs btn-danger btn-delete" data-id="'.$value['id'].'">'.PHP_EOL;
			echo '				<span class="glyphicon glyphicon-remove"></span>'.PHP_EOL;
			echo '			</a></div>'.PHP_EOL;
			echo '<span class="nric">'.$value['nric'].' </span><span class="glyphicon glyphicon-link"></span> <span class="subcom">'.$value['subcom'].'</span>'.PHP_EOL;
			echo '		</div>'.PHP_EOL;
			echo '	</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		} ?>
	</div>
</div>

<script>
$(document).ready(function (){
	$("#btn-add").click(function () {
		$("#err_msg").html("");
		$("#subcom-modal-label").text("New subcom combination");
		$(".modal-body input").val("");
		$("#e_id").val(-1);
		$('#subcom-modal').modal('toggle');
	});
	$(".btn-edit").click(function () {
		$("#err_msg").html("");
		var subcom_id = parseInt($(this).data("id"));
		var nric = $(this).parent().parent().children(".nric").text();
		var subcom = $(this).parent().parent().children(".subcom").text();
		$("#subcom-modal-label").text("Edit subcom: "+nric);
  		$("#e_id").val(subcom_id);
  		$("#e_nric").val(nric);
  		$("#e_subcom").val(subcom);
  		$('#subcom-modal').modal('toggle');
  		
	});
	$(".btn-delete").click(function () {
		var subcom_id = parseInt($(this).data("id"));
		if(confirm('Are you sure you want to delete?')){
			//New subcom
			$.ajax({type: "POST",
				dataType: "jsonp",
				url: "<?php echo site_url('admin/delete_subcom') ?>?callback=?",
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
		if($("#e_id").val() == "-1"){
			//New subcom
			$.ajax({type: "POST",
				dataType: "jsonp",
				url: "<?php echo site_url('admin/new_subcom') ?>?callback=?",
  				data: $("#subcom_man").serialize()
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
	  			$("#err_msg").text("Failed to send new request.");
  				$("#submit").text("Submit");
		 	});
		}else{
			//Edit subcom
			$.ajax({type: "POST",
				dataType: "jsonp",
				url: "<?php echo site_url('admin/edit_subcom') ?>?callback=?",
  				data: $("#subcom_man").serialize()
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
		}
	});
});
</script>

<!-- Modal -->
<div class="modal fade" id="subcom-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="subcom-modal-label">Edit activity</h4>
      </div>
      <form action="" class="form" id="subcom_man">
      	<div class="modal-body">
      		<input type="text" name="id" id="e_id" style="/*display:none;*/">
      	  	<input type="text" name="nric" id="e_nric" required maxlength="9" placeholder="NRIC" class="form-control">
      	  	<input type="text" name="subcom" id="e_subcom" placeholder="Subcom Name" class="form-control">
      	</div>
      	<div class="modal-footer">
      	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	  <button type="button" class="btn btn-primary" id="e_submit">Submit</button>
      	</div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->