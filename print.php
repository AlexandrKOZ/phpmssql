<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=cp1251"> 
    <title>111111</title>
    <link href="css/print.css" rel="stylesheet" type="text/css" />
</head>
<?php
//header('Content-Type: text/html; charset=cp1251');
require_once('./connection.php');
if ($_REQUEST[action]=='semester_report') {
      $st_query = $db->prepare("SELECT st_pcode_str = CONVERT(NVARCHAR(50), st_pcode),* FROM Students WHERE st_grcode='$_REQUEST[group]' AND st_Attributes = '0' ORDER BY st_Fname ASC") or die('123');
      $st_query -> execute();
      $st_data=$st_query->fetchAll(PDO::FETCH_ASSOC);
      $st_query->closeCursor();
      $exam_query = $db -> prepare("SELECT ex_pcode_str = CONVERT (NVARCHAR(50), ex_pcode), sb_Name, ex_Teacher  FROM Exams, Subjects WHERE (ex_smcode='$_REQUEST[semester_code]') AND (ex_sbcode = sb_pcode)");
      $exam_query -> execute();
      $exam_data = $exam_query->fetchAll(PDO::FETCH_ASSOC);
      $exam_query->closeCursor();
      $checkpoints_query = $db -> prepare("SELECT cp_pcode_str = CONVERT (NVARCHAR(50), cp_pcode), *  FROM checkpoints, Subjects WHERE (cp_smcode='$_REQUEST[semester_code]') AND (cp_sbcode = sb_pcode)");
      $checkpoints_query -> execute();
      $checkpoints_data = $checkpoints_query->fetchAll(PDO::FETCH_ASSOC);
      $checkpoints_query->closeCursor();
      $cworks_query = $db -> prepare("SELECT cw_pcode_str = CONVERT (NVARCHAR(50), cw_pcode),* FROM CourseWorks, Subjects WHERE (cw_smcode='$_REQUEST[semester_code]') AND (cw_sbcode = sb_pcode)");
      $cworks_query -> execute();
      $cworks_data = $cworks_query->fetchAll(PDO::FETCH_ASSOC);
      $cworks_query->closeCursor();
      $groups_query = $db -> prepare("SELECT gr_speccode_str = CONVERT (NVARCHAR(50), gr_speccode),* FROM Groups WHERE (gr_pcode='$_REQUEST[group]')");
      $groups_query -> execute();
      $groups_data = $groups_query->fetch(PDO::FETCH_ASSOC);
      $groups_query->closeCursor();
      
      $semesters_query = $db -> prepare("SELECT * FROM Semesters WHERE (sm_pcode='$_REQUEST[semester_code]')");
      $semesters_query -> execute();
      $semesters_data = $semesters_query->fetch(PDO::FETCH_ASSOC);
      $semesters_query->closeCursor();
      
      $spc_query = $db -> prepare("SELECT * FROM Specialities WHERE (sp_pcode='$groups_data[gr_speccode_str]')");
      $spc_query -> execute();
      $spc_data = $spc_query->fetch(PDO::FETCH_ASSOC);
      $spc_query->closeCursor();
      
      print ("<center>");
      print ("Государственное казённое профессиональное образовательное учреждение<br>");
      print ("Прокопьевский горнотехнический техникум им. В.П. Романова");
      print ("<h3>СВОДНАЯ ВЕДОМОСТЬ</h3>");
      print ("успеваемости группы $groups_data[gr_Name] $groups_data[gr_Course] курса");
      if ($groups_data[isZaoch]==false) {
	  print (" очной");
	}
      else {
	  print (" заочной");
	}
      if ($semesters_data[sm_BMounth]>=9 AND $semesters_data[sm_BMounth]<=12) {
	  $byear=$semesters_data[sm_BYear];
	  $eyear=$byear+1;
	  $print_year="$byear-$eyear";
	}
      else {
	  $byear=$semesters_data[sm_BYear];
	  $eyear=$byear-1;
	  $print_year="$eyear-$byear";
      
	}
      print (" формы обучения за $semesters_data[sm_Number]-й семестр $print_year учебного года<br>");
      print ("Специальность: \"$spc_data[sp_Name]\"<br><br>");
      print ("<table border=1 width=800>");
      print ("<tr>");
      print ("<td><div class=rotate>№ п/п</div></td>");
      print ("<td>Фамилия, имя, отчество</td>");
      for ($i=0; $i<count($exam_data); $i++) {
	  $sb_arr = $exam_data[$i];
	  print ("<td><div class=rotate>(ЭКЗ) $sb_arr[sb_Name]</div></td>");
	}
      for ($i=0; $i<count($checkpoints_data); $i++) {
	  $cp_arr=$checkpoints_data[$i];
	  print ("<td><div class=rotate>(ЗАЧ) $cp_arr[sb_Name]</div></td>");
	}
      for ($i=0; $i<count($cworks_data); $i++) {
	  $cw_arr=$cworks_data[$i];
	  print ("<td><div class=rotate>(КУРС) $cw_arr[sb_Name]</div></td>");
	}
      print ("<td><div class=rotate>Уваж.</div></td>");
      print ("<td><div class=rotate>Неув.</div></td>");
      print ("<td><div class=rotate>Всего</div></td>");
      print ("</tr>");
      for ($i=0; $i<count($st_data); $i++) {
	  $current_st_arr=$st_data[$i];
	  $st_fullname=$st_data[$i][st_FName]." ".substr($st_data[$i][st_MName], 0, 1).".".substr($st_data[$i][st_LName], 0, 1).".";
	  $count=$i+1;
	  print ("<tr>");
	  print ("<td>$count</td><td>$st_fullname</td>");
	  for ($c=0; $c<count($exam_data); $c++) {
		$sb_arr = $exam_data[$c];
		$mark_query = $db -> prepare("SELECT * FROM ExMarks WHERE (exm_stcode = '$current_st_arr[st_pcode_str]') AND (exm_excode = '$sb_arr[ex_pcode_str]')");
		$mark_query -> execute();
		$mark_data = $mark_query->fetch(PDO::FETCH_ASSOC);
		$mark_query->closeCursor();
		print ("
		    <td>$mark_data[exm_Mark]</td>
		  ");
	    }
	    for ($c=0; $c<count($checkpoints_data); $c++) {
		$chps_arr = $checkpoints_data[$c];
		$mark_query = $db -> prepare("SELECT * FROM CpMarks WHERE (cpm_stcode = '$current_st_arr[st_pcode_str]') AND (cpm_cpcode = '$chps_arr[cp_pcode_str]')");
		$mark_query -> execute();
		$mark_data = $mark_query -> fetch(PDO::FETCH_ASSOC);
		$mark_query -> closeCursor();
		print ("<td>$mark_data[cpm_Mark]</td>");
	      }
	    for ($c=0; $c<count($cworks_data); $c++) {
		$cw_arr=$cworks_data[$c];
		$mark_query = $db -> prepare("SELECT * FROM CwMarks WHERE (cwm_stcode = '$current_st_arr[st_pcode_str]') AND (cwm_cwcode = '$cw_arr[cw_pcode_str]')");
		$mark_query -> execute();
		$mark_data = $mark_query -> fetch(PDO::FETCH_ASSOC);
		$mark_query -> closeCursor();
		print ("<td>$mark_data[cwm_Mark]</td>");
	      }
	    $missings_query = $db -> prepare("SELECT legal = sum(swm_Legal), illegal = sum(swm_Illegal), missings = (sum(swm_Legal) + sum(swm_Illegal))  FROM StWeekMissings WHERE swm_stcode = '$current_st_arr[st_pcode_str]' AND swm_date >= (SELECT sm_BDate FROM Semesters where (sm_pcode = '$_REQUEST[semester_code]')) AND swm_date <= (SELECT sm_EDate FROM Semesters WHERE (sm_pcode = '$_REQUEST[semester_code]'))");
	    $missings_query -> execute();
	    $missings_data = $missings_query -> fetch(PDO::FETCH_ASSOC);
	    $missings_query -> closeCursor();
	    $legal_missings = $legal_missings + $missings_data[legal];
	    $illegal_missings = $illegal_missings + $missings_data[illegal];
	    $missings = $missings + $missings_data[missings];
	    print ("<td>$missings_data[legal]</td>");
	    print ("<td>$missings_data[illegal]</td>");
	    print ("<td>$missings_data[missings]</td>");
	  print ("</tr>");
	}
      print ("<tr id=tfooter>");
      print ("<td><div class=rotate> Преподаватели</div></td><td></td>");
      for ($i=0; $i<count($exam_data); $i++) {
	  $sb_arr = $exam_data[$i];
	  print ("<td><div class=rotate> $sb_arr[ex_Teacher]</div></td>");
	}
      for ($i=0; $i<count($checkpoints_data); $i++) {
	  $cp_arr = $checkpoints_data[$i];
	  print ("<td><div class=rotate> $cp_arr[cp_Teacher]</div></td>");
	}
      for ($i=0; $i<count($cworks_data); $i++) {
	  $cw_arr = $cworks_data[$i];
	  print ("<td><div class=rotate> $cw_arr[cw_Teacher]</div></td>");
	}
      print ("<td><div class=rotate> $legal_missings </div></td>");
      print ("<td><div class=rotate> $illegal_missings </div></td>");
      print ("<td><div class=rotate> $missings </div></td>");
      print ("</tr>");
      print ("</table>");
      
      print ("</center>");
      print ("<br><br><br><br>Зав. отделением _____________________");
   }
?> 
</body>
</html>