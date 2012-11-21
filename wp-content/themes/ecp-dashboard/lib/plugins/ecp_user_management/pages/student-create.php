<?php
if(session_id() == "")
{
	session_start();
}

require_once IDG_CLASS_PATH . "class.IDGL_Node.php";
$form_options = IDGL_Config::getConfig(IDG_CREATE_USERS_CONF_FILE);

foreach($form_options as $f)
{
	if($f["user_type"] == "student")
	{
		$form_data = $f;
		break;
	}
}

$messages = array(
	"success" => "<p style='color: #009900'>".ucfirst($form_data["user_type"])." successfuly created.</p>",
	"error" => "<p style='color: #990000'>Failed to create ". $form_data["user_type"]."</p>",
	"v_error" => unserialize($_SESSION["v_error"]),
	"mail-error" => "<p style='color: #990000'>Failed to send mail to ". $form_data["user_type"].".</p>"
);
$return_url = parse_url($_SERVER['REQUEST_URI']);
$return_url = $return_url["path"];
?>

<div class="wrap">
	<h2>Insert Student</h2>
	<div class="notification_box">
		<?php 
			$msg = $_GET["res"];
			if(isset($msg))
			{
				if(is_array($messages[$msg]))
				{
					fb($messages[$msg]);
					foreach($messages[$msg] as $m)
					{
						echo "<span style='color: #990000'>{$m}</span><br/>";
					}
				}
				else
				{
					echo $messages[$msg];
				}
			}
		?>
	</div>
	<form id="ecp_create_user" action="<?php echo $return_url; ?>?page=create-user" method="POST">
		<?php
			foreach($form_data as $node)
			{
				$nd = new IDGL_Node($node);
				echo $nd -> renderAdmin(get_user_meta($user -> ID, "_IDGL_elem_" . $node["name"], true));
			}
		?>
		<div class="half">
			<input type="submit" value="Create" id="createStudent" />
		</div>
		<input type="hidden" value="<?php echo $form_data["user_type"] ?>" name="IDGL_elem[user_type]" />
		<input type="hidden" value="<?php echo $return_url; ?>?page=student-create" name="return_url" />
	</form>
</div>