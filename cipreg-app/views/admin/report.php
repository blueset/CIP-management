<script type="text/javascript" src="<?=base_url('js/jquery.tablesorter.min.js')?>"></script> 
<style type="text/css">
	table thead tr .header {
		background-image: url(http://tablesorter.com/themes/blue/bg.gif);
		background-repeat: no-repeat;
		background-position: center right;
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
	<h1>Report <small>Approved item only</small></h1>
	<hr>
	<?php echo $table; ?>
</div>
<script>
$(document).ready(function (){
	// call the tablesorter plugin 
    $("table").tablesorter({ 
        // sort on the first column and third column, order asc 
        sortList: [[4,0],[1,0]] 
    });
});
</script>