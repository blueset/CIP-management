<div class="container">
	<div class="well well-lg text-center">
		<h1><?php echo $message ?></h1>
		<p>Redirecting to <?php echo anchor($path,$path); ?>.</p>
	</div>
</div>
<script type="text/JavaScript">
<!--
setTimeout("location.href = '<?php echo site_url($path); ?>';",1500);
-->
</script>