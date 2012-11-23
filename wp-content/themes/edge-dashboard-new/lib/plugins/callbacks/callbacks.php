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
				var loader = $(this).next();
				
				$.ajax({
					type: "POST",
					url: "<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/rem_stud_from_teach.php",
					data: { teacher_id: teacher_id, student_id: student_id },
					beforeSend: function(){
						loader.fadeIn().text("Removing...");
					},
					success: function(data){
						 if(data == '0')
						 {
							 loader.fadeIn().text("Cannot Remove Student").css({"display":"inline-block"}).delay(2000).fadeOut();
						 }
						 else
						 {
						 	 loader.fadeIn().text("Success Remove Student").css({"display":"inline-block"}).delay(2000).fadeOut();
							 var id = student_id;
				    	 	 var name= row.find('div:first').html();
							 var id = row.find('.student_id_hidden').val();
					    	 var email = row.find('.user_info_ecp a').text();
					    	 var username = row.find('.student_username_hidden').val();
					    	 $('.students_all_list').append('<li><div style="float: left; margin-right: 15px;"><input type="checkbox" value="'+id+'" name="students_checkbox" class="students_checkbox position_student_btn"><input type="hidden" value="'+id+'" class="student_id_hidden"></div><div style="float: left;"><div class="name_holder_ecp" style="margin: 0px; float: left; width: 331px;"><span>'+name+'</span></div><div class="clear"></div><div class="user_info_ecp"><span>Email:</span><span>'+email+'</span></div><div class="clear"></div><div class="user_actions_ecp"><span>Actions:</span> <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res->user_login ?>/profile/">Dashboard</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res->user_login ?>/online-sat-progress/">Online SAT Course Progress</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res->user_login ?>/live-test-progress/">Live Test Progress</a></div><div class="clear"></div></div></li>');
					    	 row.fadeOut(function(e){
						    	 $(this).remove();
						     });
						 }
					},
					error: function(msg){
						//console.log(msg);
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
	<p class="box_title_ecp">List of students that professor have:</p>
	<?php if(count($results)){?>	
	<ul id="sortable1" class="students_delete_list">
	<?php foreach($results as $res){
		foreach($teac as $tea){
			if($tea->student_ID == $res->ID){
				?>
				<li>
					<div style="margin:0px;float:left;width:331px;" class="name_holder_ecp"><?php if(!empty($res->first_name)){ echo $res->first_name . ' ' . $res->last_name; }else{ echo $res->user_login;} ?></div>
					<input class="student_id_hidden" type="hidden" value="<?php echo $res->ID; ?>" />
					<div class="clear"></div>
					<div class="user_info_ecp">
						<span>Email:</span>
						<?php if(!empty($res->user_email)){ ?>
							<a href="<?php echo $res->user_email; ?>"><?php echo $res->user_email; ?></a>
						<?php }else{ ?>
							<span>N/A</span>	
						<?php } ?>
					</div>
					<div class="clear"></div>
					<div class="user_actions_ecp">
						<span>Actions:</span> <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res->user_login ?>/profile/" >Dashboard</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res->user_login ?>/online-sat-progress/" >Online SAT Course Progress</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res->user_login ?>/live-test-progress/" >Live Test Progress</a>
					</div>
					<a class="button-secondary action button_remove_student position_student_btn" href="#">Remove</a>
					<p style="color:red;display:none;margin:0 0 0 0px;" class="error_student_teacher_remove"></p>
					<div class="clear"></div>
				</li>
				<?php 
			}
		}	
	}
	?>
	</ul>
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
		
		var loader = $("#sbUpdater");
		var teacher_id = <?php echo $_GET['user_id']; ?>;
		var students = new Array();
		
		$('.student_assign_button').click(function(e){
			e.preventDefault();
			var buttmp = $(this);
			
			$('.students_checkbox:checked').each(function(i){
				students[i] = $(this).val();
			});
			
			$.ajax({
				type: "POST",
				url: "<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/ins_stud_in_teach.php",
				data: { teacher_id: teacher_id, student_id: students },
				beforeSend: function(){
					loader.fadeIn().text("Updating...");
				},
				success: function(data){
					 if(data=='0')
					 {
						loader.fadeIn().text("Failed assigning student").css({"display":"inline-block"}).delay(2000).fadeOut();
					 }
					 else
					 {
					    loader.fadeIn().text("Update Success").text("Success assigning student").css({"display":"inline-block"}).delay(2000).fadeOut();
					    $('.students_checkbox:checked').each(function(){
				    		var row = $(this).parents('li:first');
					    	var name = row.find('span:first').html();
					    	var id = row.find('.student_id_hidden').val();
					    	var email = row.find('.user_info_ecp a').text();
					    	var username = row.find('.student_username_hidden').val();
					    	//$('.students_delete_list').show().append('<li><div style="margin: 0px; float: left; width: 200px;">'+name+'</div><input type="hidden" value="'+id+'" class="student_id_hidden"><a href="" class="button-secondary action button_remove_student">Remove</a></li>');
					    	$('.students_delete_list').show().append('<li><div class="name_holder_ecp" style="margin: 0px; float: left; width: 331px;">'+name+'</div><input type="hidden" value="'+id+'" class="student_id_hidden"><div class="clear"></div><div class="user_info_ecp"><span>Email:</span><a href="'+email+'">'+email+'</a></div><div class="clear"></div><div class="user_actions_ecp"><span>Actions:</span> <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res['user_login'] ?>/profile/">Dashboard</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res['user_login'] ?>/online-sat-progress/">Online SAT Course Progress</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res['user_login'] ?>/live-test-progress/">Live Test Progress</a></div><a href="" class="button-secondary action button_remove_student position_student_btn">Remove</a><div class="clear"></div></li>');
							$(row).remove();
						});
					 }
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


		<li>		<div style="float:left;margin-right:15px;">
						<input class="students_checkbox position_student_btn" type="checkbox" name="students_checkbox" value="<?php echo $res['ID']; ?>" />
						<input class="student_id_hidden" type="hidden" value="<?php echo $res['ID']; ?>" />
						<input class="student_username_hidden" type="hidden" value="<?php echo $res['user_login']; ?>" />
					</div>
					<div style="float:left;">
						<div style="margin:0px;float:left;width:331px;" class="name_holder_ecp"><span><?php if(!empty($res['first_name'])){ echo $res['first_name'] . ' ' . $res['last_name']; }else{ echo $res['user_login'];} ?></span></div>
						<div class="clear"></div>
						<div class="user_info_ecp">
							<span>Email:</span>
							<?php if(!empty($res['user_email'])){ ?>
								<a href="<?php echo $res['user_email']; ?>"><?php echo $res['user_email']; ?></a>
							<?php }else{ ?>
								<span>N/A</span>	
							<?php } ?>
						</div>
						<div class="clear"></div>
						<div class="user_actions_ecp">
							<span>Actions:</span> <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res['user_login'] ?>/profile/" >Dashboard</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res['user_login'] ?>/online-sat-progress/" >Online SAT Course Progress</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $res['user_login'] ?>/live-test-progress/" >Live Test Progress</a>
						</div>
						<div class="clear"></div>
					</div>
				</li>
		
	<?php 
	endforeach;
	?>
	</ul>
	<input type="button" value="Assign to this teacher" name="student_assign_button" class="student_assign_button button-secondary" />
	<p style="color:red;display:none;margin:0 0 0 0px;" id="sbUpdater" class="students_button_updating"></p>
	<?php
	}
	else{
	echo '<div>No students left for assigning</div>';
	}
}

function list_teachers_from_student_prof(){
	global $wpdb;	
	$q="SELECT ID, 
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
	<?php 
		$user_id = (isset($_GET['user_id'])) ? $_GET['user_id'] : "";
	?>
	<style type="text/css">
		.IDGL_Options .label, .IDGL_Options .elem{
			display: inline;
		}
		.IDGL_Options{
			margin-top:10px;
		}
	</style>
	<script type="text/javascript">
	jQuery(document).ready(function($){

		var user_id = <?php echo $user_id; ?>;
		var teachers = new Array();
		var loader = $("#tbUpdater");
		
		jQuery('input.teachers_button').click(function(){
			
			jQuery(this).attr("disabled",true);
			var buttmp = $(this);
			jQuery('.teachers_checkbox:checked').each(function(i){
				teachers[i] = $(this).val();
			});
			//console.log(user_id + ' ' + teachers);
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/ins_teach_stud.php",
				data: { teacher_id: teachers, student_id: user_id },
				beforeSend: function(){
					loader.fadeIn().text("Updating...");
				},
				success: function(data){
					 if(data=='0')
					 {
						loader.fadeIn().text("Failed to Update").delay(2000).fadeOut();
					 }
					 else
					 {
					    loader.fadeIn().text("Update Success").delay(2000).fadeOut();
					 }
				}
			});
						
			jQuery(buttmp).attr("disabled",false);
	
			jQuery("span.label").each(function(){
				if(jQuery(this).html()=="")
				{
					jQuery(this).remove();
				}
			});
		
		});
		
	});
	</script>
	<div style="border: solid 1px #ddd; background-color: #fefefe; padding:10px;margin:10px;">
	<p><strong>Select teachers for this student : </strong></p>
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
	<input class="teachers_checkbox" type="checkbox" name="teachers_checkbox" <?php echo $checked[$res->ID]; ?> value="<?php echo $res->ID; ?>" />
	<span><?php 
	if(!empty($res->first_name)){echo $res->first_name . ' ' . $res->last_name;}else{echo $res->user_login ;} ?></span><br/>
		
	<?php endforeach;?>
	<br/><input class="teachers_button" type="button" name="teachers_button" value="Update" /><span id="tbUpdater" class="teachers_button_updating"></span></div>
<?php 
		}else{
		echo '<div>No teachers left to assign</div>';
		}
	}
	
function add_teachers_to_student()
{
global $wpdb;	
	$q="SELECT ID, 
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
	
	$results = $wpdb -> get_results($q,OBJECT);
	?>

	<style type="text/css">
		.IDGL_Options .label, .IDGL_Options .elem{
			display: inline;
		}
		.IDGL_Options{
			margin-top:10px;
		}
	</style>
	
	<div style="border: solid 1px #ddd; background-color: #fefefe; padding:10px;margin:10px;">
		<p><strong>Select teachers for this student : </strong></p>
		<?php
		$checked = array();
		if(count($results)) :
		
			foreach($results as $res):
			?>
			<input class="teachers_checkbox" type="checkbox" name="teachers[]" <?php echo $checked[$res -> ID]; ?> value="<?php echo $res -> ID; ?>" />
			<span>
			<?php 
				if(!empty($res -> first_name))
				{
					echo $res -> first_name . ' ' . $res -> last_name;
				}
				else
				{
					echo $res -> user_login;
				}
			?>
			</span>
			<br/>
		<?php endforeach;?>
		<br/>
	</div>
<?php 
	else:
		echo '<div>No teachers left to assign</div>';
	endif;
}

function listProducts($type){

global $wp_query;
$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
$products=get_user_meta($user_id,"_IDGL_elem_ECP_user_order",true);
$products=unserialize($products);


$prod_arr=array();
foreach($products as $product){
	$prod_arr[]=$product["id"];
}


	
global $wpdb;

$sql ="SELECT * FROM wp_3_posts as product
		INNER JOIN wp_3_term_relationships ON wp_3_term_relationships.object_id=product.ID
		INNER JOIN wp_3_term_taxonomy as taxonomy ON wp_3_term_relationships.term_taxonomy_id=taxonomy.term_taxonomy_id
		INNER JOIN wp_3_terms as terms ON taxonomy.term_id=terms.term_id
		WHERE product.post_type='ecpproduct' AND
		taxonomy.taxonomy='ecp-products' AND
		terms.slug='$type' ORDER BY menu_order ASC" ;
$results = $wpdb->get_results($sql);
foreach($results as $postRes){
?>
<div class="users_books" style="clear:both; <?php if(in_array($postRes->ID,$prod_arr)){ echo "background-color:#f6f6f6;"; } ?> ">
		<h2><?php echo $postRes->post_title; ?></h2>
		<div class="bookContent">
			<div style="width:600px;" id="prid_<?php echo $postRes->ID; ?>"><?php echo $postRes->post_content; ?></div>
		</div>
		<p>
            <?php if(in_array($postRes->ID,$prod_arr)){ ?>
            	You have this product in your account.
            <?php }else{ ?>
            <a class="button blue learn_more" href="#prid_<?php echo $postRes->ID; ?>">Find Out More</a>
                <a class="button pink" href="/enroll/cart/?pid=<?php echo $postRes->ID; ?>">Buy</a>
                <!-- <a class="button green" href="#">Download</a> -->
           <?php } ?>
		</p>
	</div>
<?php 	
} 
}


add_action('wp_ajax_user_products_update', 'user_products_update');

function user_products_update(){
	$products=$_POST["products"];
	$products=explode(",",$products);
	update_user_meta( $_POST["user_id"], "_IDGL_elem_ECP_user_order", serialize($products));
}
function student_product_details($params,$name){
	if(isset($_GET["user_id"])){
		$user_id=$_GET["user_id"];
	}
	$products=get_user_meta($user_id,"_IDGL_elem_ECP_user_order",true);
	
	$products=unserialize($products);
	//print_r($products);
	
 ?>
	
	<script type="text/javascript">
		jQuery(document).ready(function($){
	
			var user_id = <?php echo $_GET['user_id']; ?>;
	
			$('.products_update').click(function(e){
				e.preventDefault();
				var buttmp=$(this);
				buttmp.attr("disabled",true);
				
				var products=new Array();
				jQuery("input[name='_ECP_user_order']").each(function(){
					if(jQuery(this).attr("checked")) products.push(jQuery(this).val());
				})
				products=products.join(",");
				
				var loader = $("#user_tbUpdater");
				
				$.ajax({
					type: "POST",
					url: adminAjax+"?action=user_products_update",
					data: { products: products, user_id: user_id },
					beforeSend: function(){
						loader.fadeIn().text("Updating...");
					},
					success: function(data){
						loader.fadeIn().text("Update done").css({"display":"inline-block"}).delay(2000).fadeOut();
						buttmp.attr("disabled",false);
					},
					error: function(msg){

					}
				});
				
			});
			
		});	
	</script>
	
	<?php
	
	
	echo "<div class='the_product_list' style='border: solid 1px #ddd; background-color: #fefefe; padding:10px;margin:10px;'>";
	echo "<h2>User products</h2>";
	
	
	
	switch_to_blog(3);

	$args = array(
		'posts_per_page'=>-1,
		'post_type'=>'ecpproduct'
	);
	$query = new WP_Query( $args );
	echo "<ul>";
	while ( $query->have_posts() ) : $query->the_post(); global $post;
		if( in_array($post->ID,$products)){
			echo "<li><input type='checkbox'  name='_ECP_user_order' value='".$post->ID."' checked='checked'> ".get_the_title()."</li>";
		}else{
			echo "<li><input type='checkbox'  name='_ECP_user_order' value='".$post->ID."' > ".get_the_title()."</li>";
		}
		
	endwhile;
	echo "</ul>";
	wp_reset_postdata();

    restore_current_blog();
	
	echo '<input class="products_update" type="button" name="products_update" value="Update"><span id="user_tbUpdater" class="users_button_updating"></span>';
	
	echo "</div>";
}