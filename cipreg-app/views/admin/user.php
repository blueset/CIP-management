<div class="col-md-10">
	
	<h1><a href="#" class="btn btn-primary pull-right" id="btn-add">Add User</a>Users</h1>
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
</div>

<script>
$(document).ready(function (){
	$("#btn-add").click(function () {
		$("#err_msg").html("");
		$("#user-modal-label").text("New user");
		$(".modal-body input").val("");
		$("#user_id").val(-1);
		$('#user-modal').modal('toggle');
	});
	$(".btn-edit").click(function () {
		$("#err_msg").html("");
		var user_id = parseInt($(this).data("id"))+1;
		var name = $(this).parent().children(".name").text();
		$.ajax({type: "POST",
			dataType: "jsonp",
			url: "<?php echo site_url('admin/get_user') ?>?callback=?",
  			data: { id: user_id }
		})
  		.done(function( data ) {
  			$("#user-modal-label").text("Edit user: "+data.display_name);
  			$("#user_id").val(data.id);
  			$("#username").val(data.username);
  			$("#display_name").val(data.display_name);
  			$('#user-modal').modal('toggle');
  		})
  		.fail(function () {
  			alert('Failed to fetch user information');
		 });
	});
	$("#submit").click(function () {
		$(this).text("Loading...");
		$("#err_msg").html("");
		if($("#user_id").val() == "-1"){
			//New user
			$.ajax({type: "POST",
				dataType: "jsonp",
				url: "<?php echo site_url('admin/new_user') ?>?callback=?",
  				data: $("#user_man").serialize()
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
			//Edit user
			$.ajax({type: "POST",
				dataType: "jsonp",
				url: "<?php echo site_url('admin/edit_user') ?>?callback=?",
  				data: $("#user_man").serialize()
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
<div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="user-modal-label">User management</h4>
      </div>
      <form action="" class="form" id="user_man">
      	<div class="modal-body">
      		<input type="text" name="id" id="user_id" style="display:none;">
      	  	<div class="form-group">
      	  		<input type="text" name="username" id="username" placeholder="Username" class="form-control">
      	  	</div>
      	  	<div class="form-group">
      	  		<input type="text" name="password" id="password" placeholder="Password (Blank if no change)" class="form-control">
      	  	</div>
			<div class="form-group">
				<input type="text" name="display_name" id="display_name" placeholder="Display Name" class="form-control">
			</div>
      	</div>
      	<div class="modal-footer">
      		<p class="pull-left" id="err_msg" style="color:red;font-weight:bold;"></p>
      	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	  <button type="button" class="btn btn-primary" id="submit">Submit</button>
      	</div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->