<?php
include '../js/function_db.php';
session_name('tutorial');
session_start();
$_SESSION['intra_czid'];

?>


    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">ข้อมูลบริษัท</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">ข้อมูลบริษัท</h4>
	</div>
	<br>

<?php
  $C_comp = "SELECT COUNT(company_id)  FROM ref_company";
  $countc = selectSql($C_comp);
  foreach ($countc as $row) {}
?>


  <div class="col-12" style="text-align:left;color:black"><h5 style="font-family: Kanit;">จำนวนบริษัท : <?php echo $row['COUNT(company_id)'];?></h5></div>

<div class="col-12">
<table class="tablex table-bordered " id="scompany" >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-1" scope="col">ลำดับ</th>
      <th style="text-align:center" class="col-md-6" scope="col">ชื่อบริษัท </th>
	  <th style="text-align:center" class="col-md-4" scope="col">เบอร์ติดต่อ</th>
	  <th style="text-align:center" class="col-md-1" scope="col">จำนวนโครงการ</th>

      
    </tr>
</thead >
<tbody style="background-color:white">
        <?php
		
				
		
        $n = 0;
        $sql = "SELECT *, COUNT(tb_project.pro_company) FROM ref_company LEFT JOIN tb_project on ref_company.company_id = tb_project.pro_company GROUP BY ref_company.company_id ORDER BY ref_company.company_id";
        $result = selectSql($sql);
        foreach ($result as $row) { $n++;	

		//$csql = "SELECT COUNT(pro_company) FROM tb_project WHERE pro_company = company_id"
		?>
    <tr class="trcolor" >
      <th style="text-align:center" scope="row"><?php echo $n; ?></th>
      <td style="text-align:center" onclick="company_project(<?php echo $row['company_id'];?>);"><?php echo $row['campany_name']; ?></td>
	  <td style="text-align:center"><?php echo $row['campany_phone']; ?></td>
	  <td style="text-align:center"><?php echo $row['COUNT(tb_project.pro_company)']; ?></td>

    </tr>
      <?php } ?>
      </tbody>
    </table>
 </div>

<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>

<script type="text/javascript">
  $(function () {
    $('#scompany').DataTable({
      'paging'      : true,
      'lengthChange': false,
	  'lengthMenu'  : [[10, 50, 100, -1], [20, 50, 100, "ทั้งหมด"]],
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
	  'processing'  : true,
      'autoWidth'   : false

    })
  })	
</script>