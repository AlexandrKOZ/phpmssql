jQuery(document).ready(function($){
	var action='get_departments';
	$('#select_department').load("ajax.php", {action:action});
	$('#select_department').live('change', function(){
		$('#select_group').show();
		var action = 'get_groups';
		var department_code = $("#select_department option:selected").val();
		//alert(department_code);
		$('#select_group').load("ajax.php", {action:action, department_code:department_code});
	})
	$('#select_group').live('change', function(){
		$('#select_semester').show();
		var action = 'get_semester';
		var group_code = $("#select_group option:selected").val();
		//alert(group_code);
		$('#select_semester').load("ajax.php", {action:action, group_code:group_code});
	})
	
	$('#select_semester').live('change', function(){
		$('#generate_button').show();
	})
	
	$('#generate_button').live('click', function(){
		var semester_code = $("#select_semester option:selected").val();
		var group_code = $("#select_group option:selected").val();
		window.open("print.php?action=semester_report&group="+group_code+"&semester_code="+semester_code);
	})
})
 
