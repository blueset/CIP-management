<div class="container">
  <div class="page-header text-center">
    <h1>Log in</h1>
  </div>
</div>
  <div style="max-width:300px" class="center-block">
		<?php if(validation_errors() !== '' || @(!$err_message == '')){ ?>
		<div class="alert alert-error fade in alert-warning alert-dismissable">
  			<a href="#" class="close" data-dismiss="alert">&times;</a>
  			<strong>Error!</strong> <?=validation_errors('<span>','</span>');?> <?=@$err_message?>
		</div>
		<?php } ?>
		<?php if(@$message!=''){ ?>
		<div class="alert fade in alert-success alert-success alert-dismissable">
  			<a href="#" class="close" data-dismiss="alert">&times;</a>
  			<strong>Success!</strong> <?=$message?>
		</div>
		<?php } ?>
			<?php echo form_open('admin/login',array('class'=>'form-horizontal')); ?>
				<div class="form-group">
    				<input type="text" class="form-control" id="username" name="username" placeholder="User name">
    			</div>
  				<div class="form-group">
      				<input type="password" class="form-control" id="password" name="password" placeholder="Password">
  				</div>
				<div style="text-align:center;">
					<input type="submit" name="submit" value="Log in" class="btn btn-primary form-control">
				</div>
		</Form>
	</div>