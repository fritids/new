<?php
function IDGL_BgImagesList($param,$name){
	eval('$args="'.$param.'";');
	$bgArr=IDGL_File::getFileList(IDG_ROOT."/images/");
	$current=getThemeMeta("BgImagesList");
	echo "<ul class='imagesList'>";
	foreach($bgArr as $img){
		if(strpos($img,".png")!=-1 || strpos($img,".jpg")!=-1){
			$sel="";
			if($current==$img){
				$sel=" checked='checked' ";
			}
			echo "<li><label><img width='100px' src='".IDGL_THEME_URL.'/images/'.$img."' /> <input type='radio' ".$sel." name='IDGL_elem[".$name."]' value='".$img."' /> select</label></li>";
		}
	}
	echo "</ul>";
}
function print_datagrid(){
	$dg=new IDGL_DataGrid("wp_posts",array(
											"post_title"=>"Title",
											"post_status"=>"Status",
											"post_modified"=>"Date Modified",
											"guid"=>"ID"
											));
	echo $dg->render();
}
//TEACHERS MENU - TEACHER TAB
function list_Teacher_Student_Relation(){?>
	
	<script type="text/javascript">
	jQuery(document).ready(function($){

		var teacher_id = <?php echo $_GET['user_id']; ?>;
		
		$('.button_remove_student').click(function(e){
			e.preventDefault();
			$(this).attr("disabled",true);
			var buttmp = $(this);
			var row = $(this).parents('li:first');
			var student_id = $(this).parent().find('.student_id_hidden').val();
			
		console.log(teacher_id + ' ' + student_id);
		
		$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/rem_stud_from_teach.php", { teacher_id: teacher_id, student_id: student_id },
				   function(data){
				     if(data=='0'){
				    	 $('.error_student_teacher_remove').text("Cannot Remove Student").css({"display":"inline-block"}).delay(2000).fadeOut();
				     }
				     else{
				    	 $('.error_student_teacher_remove').text("Sucess Remove Student").css({"display":"inline-block"}).delay(2000).fadeOut();

							var id = student_id;
				    	 	var name= row.find('div:first').html();
				    	 $('.students_all_list').append('<li><input class="students_checkbox" type="checkbox" name="students_checkbox" value="'+id+'"><span>'+name+'</span><input class="student_id_hidden" type="hidden" value="'+id+'"></li>')
				    	 $(row).remove();
				    	
				     }
				   });
		
		
		$(buttmp).removeAttr("disabled");
		});
		
	});	
	</script>
	
	<?php 
	global $wpdb;
	$q="SELECT ID, user_email, user_login, first_name.meta_value AS first_name, last_name.meta_value AS last_name, _IDGL_elem_user_type.meta_value AS _IDGL_elem_user_type
		FROM wp_users
		JOIN wp_usermeta AS _IDGL_elem_user_type ON _IDGL_elem_user_type.user_id = ID
		AND _IDGL_elem_user_type.meta_key =  '_IDGL_elem_user_type'
		LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = ID
		AND first_name.meta_key =  'first_name'
		LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = ID
		AND last_name.meta_key =  'last_name'
		WHERE _IDGL_elem_user_type.meta_value =  'student'
		ORDER BY first_name";
	
	$results = $wpdb->get_results($q,OBJECT);
	//print_r($results);
	
	$q = "SELECT * from wp_teacherstudent where teacher_ID=".$_GET['user_id'];
	$teac = $wpdb->get_results($q,OBJECT);

	?>
	<p>List of students that professor have:</p>
	<?php if(count($results)){?>	
	<ul id="sortable1" class="students_delete_list">
	<?php foreach($results as $res){
		foreach($teac as $tea){
			if($tea->student_ID == $res->ID){
				?>
				<li>
				<div style="margin:0px;float:left;width:200px;"><?php if(!empty($res->first_name)){ echo $res->first_name . ' ' . $res->last_name; }else{ echo $res->user_login;} ?></div>
				<input class="student_id_hidden" type="hidden" value="<?php echo $res->ID; ?>" />
				<a class="button-secondary action button_remove_student" href="">Remove</a>
				</li>
				<?php 
			}
		}	
	}
	?>
	</ul>
	<p style="color:red;display:none;margin:0 0 0 0px;" class="error_student_teacher_remove"></p>
	
	<?php 
	}
	else{
	echo '<div>No Students yet</div>';
	}
}

//TEACHERS MENU - STUDENTS TAB
function list_All_Students(){
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){

		$('.student_assign_button').click(function(e){
			e.preventDefault();
			var buttmp = $(this);
			var teacher_id = <?php echo $_GET['user_id']; ?>;
			var students = new Array();
			$('.students_checkbox:checked').each(function(i){
				students[i] = $(this).val();
			});

			$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/ins_stud_in_teach.php", { teacher_id: teacher_id, student_id: students },
					   function(data){
					     if(data=='0'){
					    	$('.students_button_updating').text("Failed assigning student").css({"display":"inline-block"}).delay(2000).fadeOut();
					     }
					     else{

					    	 $('.students_button_updating').text("Sucess assigning student").css({"display":"inline-block"}).delay(2000).fadeOut();
					    	 $('.students_checkbox:checked').each(function(){
					    		var row = $(this).parents('li:first');
					    		var name = row.find('span:first').html();
					    		var id = row.find('.student_id_hidden').val();
					    		    $('.students_delete_list').show().append('<li><div style="margin: 0px; float: left; width: 200px;">'+name+'</div><input type="hidden" value="'+id+'" class="student_id_hidden"><a href="" class="button-secondary action button_remove_student">Remove</a></li>');
								    $(row).remove();
							   	 });
					     }
					   });

			
		});
	});
	</script>
	<?php 	
	global $wpdb;

	$q="SELECT DISTINCT ID, user_email, user_login, first_name.meta_value AS first_name, last_name.meta_value AS last_name, _IDGL_elem_user_type.meta_value AS _IDGL_elem_user_type
		FROM wp_users
		JOIN wp_usermeta AS _IDGL_elem_user_type ON _IDGL_elem_user_type.user_id = ID
		AND _IDGL_elem_user_type.meta_key =  '_IDGL_elem_user_type'
		LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = ID
		AND first_name.meta_key =  'first_name'
		LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = ID
		AND last_name.meta_key =  'last_name'
		WHERE _IDGL_elem_user_type.meta_value =  'student'
		ORDER BY first_name";
	
	$results = $wpdb->get_results($q,ARRAY_A);
	$q = "SELECT * from wp_teacherstudent where teacher_ID=".$_GET['user_id'];
	$studteach = $wpdb->get_results($q,ARRAY_A);
	
	$temp_index_array = array();
	$temp_unique_array = array();
	
	foreach($studteach as $singe)
	{
			array_push($temp_index_array, $singe["student_ID"]);
	}
	foreach($results as $result)
	{
		if(!in_array($result["ID"], $temp_index_array))
		{
			array_push($temp_unique_array, $result);
		}
	}
	?>
	<ul id="sortable2" class="students_all_list">
	<?php
	if(count($temp_unique_array)){
	foreach($temp_unique_array as $res):
		?>
		<li>
		<input class="students_checkbox" type="checkbox" name="students_checkbox" value="<?php echo $res['ID']; ?>" />
		<span><?php if(!empty($res['first_name'])){ echo $res['first_name'] . ' ' . $res['last_name']; }else{ echo $res['user_login'];} ?></span>
		<input class="student_id_hidden" type="hidden" value="<?php echo $res['ID']; ?>" />
		</li>
		
	<?php 
	endforeach;
	?>
	</ul>
	<input type="button" value="Assign to this teacher" name="student_assign_button" class="student_assign_button" />
	<p style="color:red;display:none;margin:0 0 0 0px;" class="students_button_updating"></p>
	<?php
	}
	else{
	echo '<div>No students left for assigning</div>';
	}
}

function list_teachers_from_student_prof(){
	global $wpdb;	
	$q="SELECT 
	ID, 
	user_email, 
	user_login, 
	first_name.meta_value AS first_name, 
	last_name.meta_value AS last_name, 
	_IDGL_elem_user_type.meta_value AS _IDGL_elem_user_type
	FROM wp_users
	JOIN wp_usermeta AS _IDGL_elem_user_type ON _IDGL_elem_user_type.user_id = ID
	AND _IDGL_elem_user_type.meta_key = '_IDGL_elem_user_type'
	LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = ID
	AND first_name.meta_key = 'first_name'
	LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = ID
	AND last_name.meta_key = 'last_name'
	WHERE _IDGL_elem_user_type.meta_value = 'teacher'
	ORDER BY first_name";
	
	$results = $wpdb->get_results($q,OBJECT);
	//print_r($results);
	
	$q="SELECT * FROM wp_teacherstudent where student_ID = ".$_GET['user_id'];
	$teachers= $wpdb->get_results($q,OBJECT);
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){

		var user_id = <?php echo $_GET['user_id']; ?>;
		var teachers = new Array();
		$('.teachers_button').click(function(){
			$(this).attr("disabled",true);
			var buttmp = $(this);
		$('.teachers_checkbox:checked').each(function(i){

			teachers[i] = $(this).val();
			
		});
		console.log(user_id + ' ' + teachers);
		
		$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/ins_teach_stud.php", { teacher_id: teachers, student_id: user_id },
				   function(data){
				     if(data=='0'){
						$('.teachers_button_updating').fadeIn().text("Failed to Update").delay(2000).fadeOut();
				     }
				     else{
				        $('.teachers_button_updating').fadeIn().text("Update Success").delay(2000).fadeOut();
				     }
				   });
		
		
		$(buttmp).attr("disabled",false);
		});
		
	});
	</script>
	<br>
	<p>Select teachers for this student : </p>
	<?php
	$checked = array();
	if(count($results)){
	foreach($results as $res):
		foreach($teachers as $teac):
			if($teac->teacher_ID == $res->ID){
			$checked[$res->ID] = 'checked';
			}
		endforeach;	
	?>
	<br>
	<input class="teachers_checkbox" type="checkbox" name="teachers_checkbox" <?php echo $checked[$res->ID]; ?> value="<?php echo $res->ID; ?>" />
	<span><?php 
	if(!empty($res->first_name)){echo $res->first_name . ' ' . $res->last_name;}else{echo $res->user_login ;} ?></span>
		
	<?php endforeach;?>
	<br>
	<input class="teachers_button" type="button" name="teachers_button" value="Update" /><span class="teachers_button_updating"></span>
		
	
<?php 
		}else{
		echo '<div>No teachers left to assign</div>';
		}
	}

function listProducts($type){
	
global $wpdb;

$sql ="
SELECT * FROM wp_3_posts as product
INNER JOIN wp_3_term_relationships ON wp_3_term_relationships.object_id=product.ID
INNER JOIN wp_3_term_taxonomy as taxonomy ON wp_3_term_relationships.term_taxonomy_id=taxonomy.term_taxonomy_id
INNER JOIN wp_3_terms as terms ON taxonomy.term_id=terms.term_id
WHERE product.post_type='ecpproduct' AND
taxonomy.taxonomy='ecp-products' AND
terms.slug='$type'
";
$results = $wpdb->get_results($sql);
foreach($results as $postRes){
?>
<div class="users_books" style="clear:both;">
		<h2><?php echo $postRes->post_title; ?></h2>
		<div class="bookContent">
			<div style="width:600px;" id="prid_<?php echo $postRes->ID; ?>"><?php echo $postRes->post_content; ?></div>
		</div>
		<p>
            <a class="button blue learn_more" href="#prid_<?php echo $postRes->ID; ?>">Find Out More</a>
                <a class="button pink" href="#">Buy</a>
                <a class="button green" href="#">Download</a>
		</p>
	</div>
<?php 	
} 
}