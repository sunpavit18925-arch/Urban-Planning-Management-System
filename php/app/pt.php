pagination test 
<?php 
	include '../js/function_db.php';
	session_start();
?>
	<div id="tableshow">
	</div>
	
	<button onclick="next(1);">NEXT</button>
	<script>
	first();
	function first(){
		$.post('app/ptt.php',{
			event : 'first'
		},function(data){
			$('#tableshow').html(data);
		});
	}
	
	function next(x){
		$.post('app/ptt.php',{
			event :  'next'
		},function(data){
			$('#tableshow').load(data);
			$('#tableshow').html(data);
			alert(<?php echo $_SESSION['no'];?>);
		});
	}
	
	</script>
	