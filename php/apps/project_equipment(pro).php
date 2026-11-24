<?php
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  $eqm_id = $_POST['eqm_id'];
  $pro_id = $_POST['pro_id'];
  $pro_test = $_POST['pro_test'];
  //echo $x,'+',$eqm_id,'+',$pro_id;  
  //$sql = "SELECT pro_test FROM tb_project WHERE pro_id  = $pro_id";
  //$result = selectSql($sql);
  $sql = "UPDATE tb_project SET  pro_test = '$pro_test' WHERE pro_id = '$pro_id'";
  $result = in_up_delSql($sql);
  //echo $sql;
  echo "แก้ไขสำเร็จ";
  

  
  ?>
  
    