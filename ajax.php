<?php
header('Content-Type: text/html; charset=cp1251');
require_once('./connection.php');
if ($_REQUEST[action]=='get_departments') {
    $dp_query = $db->prepare("SELECT dp_pcode_str = CONVERT(NVARCHAR(50), dp_pcode), dp_Name  FROM Departments WHERE dp_Name<>'NULL'") or die('123');
    $dp_query -> execute();
    print ("
	      <option value='#'>Выбрать отделение</test>
	  ");
    while ($data = $dp_query->fetch(PDO::FETCH_ASSOC)) {
	print ("
	      <option value='$data[dp_pcode_str]'>$data[dp_Name]</option>
	  ");
    }
  }

if ($_REQUEST[action]=='get_groups') {
    //print_r($_REQUEST);
    $department_code=$_REQUEST[department_code];
    //print("$department_code");
    $year=date("Y");
    $year=$year-5;
    $gr_query = $db->prepare("SELECT gr_pcode = CONVERT(NVARCHAR(50), gr_pcode), gr_Name  FROM Groups WHERE gr_depcode='$department_code' AND gr_CreateYear>'$year'") or die('123');
    $gr_query -> execute();
    print ("
	      <option value='#'>Выбрать группу</test>
	  ");
    while ($data = $gr_query->fetch(PDO::FETCH_ASSOC)) {
	print ("
	      <option value='$data[gr_pcode]'>$data[gr_Name]</option>
	  ");
    }
  }
if ($_REQUEST[action]=='get_semester') {
    //print_r($_REQUEST);
    $group_code=$_REQUEST[group_code];
    //print("$department_code");
    $sm_query = $db->prepare("SELECT sm_pcode_str = CONVERT(NVARCHAR(50), sm_pcode), sm_Number FROM Semesters WHERE sm_grcode='$group_code' ORDER BY sm_Number ASC") or die('123');
    $sm_query -> execute();
    print ("
	      <option value='#'>Выбрать семестр</test>
	  ");
    while ($data = $sm_query->fetch(PDO::FETCH_ASSOC)) {
	print ("
	      <option value='$data[sm_pcode_str]'>$data[sm_Number]</option>
	  ");
    }
  }  
?> 
