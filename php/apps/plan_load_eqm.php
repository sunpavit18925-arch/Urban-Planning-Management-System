<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];
	$_SESSION['eid'];

    ?>


<table id="tableeqm" class="tablex table-bordered " >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-5" scope="col">อุปกรณ์</th>
      <th style="text-align:center" class="col-md-1" scope="col">จัดการ</th>

    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        $plan = "plan".$_SESSION['eid'];
        $sql = "SELECT eqm_id, eqm_name, $plan FROM tb_equipment ";
        $result = selectSql($sql);

        foreach ($result as $row){ 
		
		?>

    <tr class="trcolor" >
	  <td> <?php echo $row['eqm_name']; ?></td>
      <td style="text-align:center" >
	  <?php 
	    if($row[$plan]=='1' ){ ?>
			<svg  onclick="setequipment(0,<?php echo $row['eqm_id']; ?>,<?php echo $_SESSION['eid']; ?>);" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
			  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
			  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
			</svg> <?php
		}
		else{ ?>
			<svg onclick="setequipment(1,<?php echo $row['eqm_id']; ?>,<?php echo $_SESSION['eid']; ?>);" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
			  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
			  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
			</svg> <?php 
		} ?>
	  </td>
    </tr>	  
  <?php } ?>
  </tbody>
  </table>
  
   <script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>
<script>
  $(function () {
    $('#tableqm1').DataTable()
    $('#tableeqm').DataTable({
      'paging'      : true,
      'lengthChange': false,
	  'lengthMenu'  : [[10, 50, 100, -1], [20, 50, 100, "ทั้งหมด"]],
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
	  'processing'  : true,
	  'stateSave'	: false,
	  'stateDuration': 6000,
      'autoWidth'   : false
	  
    })
  }) 
</script>
<script>
function setequipment(x,id,pname){
	  name = 'plan'+pname;
      if ( (x==1)) {
        $.post('apps/plan_equipment(pro).php',{
        event: 'seqm',
        id : id,        
        eqm_status : x,
		column_name : name 
        },function(data) {          
        // alert(data);
		 $('#loadpeqm').load('apps/plan_load_eqm.php');

         
       });
      }
      else {
      $.post('apps/plan_equipment(pro).php',{
          event : 'seqm',
          id : id,          
          eqm_status : x,
		  column_name : name
      },function(data) {        
       // alert(data);		
		$('#loadpeqm').load('apps/plan_load_eqm.php');

        });
      }
    }
	

</script>