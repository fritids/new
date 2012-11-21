<?php
$active_class = "active_progress";
$passive_class = "passive_progress";

$active_arr = array('account', 'billing', 'thankyou');
$page_data = array(
					'<span class="progress_segment %s"><span>Account Setup</span><span class="arrow"></span></span>',
					'<span class="progress_segment %s"><span>Billing Information</span><span class="arrow"></span></span>',
					'<span class="progress_segment %s"><span>Success</span><span class="arrow"></span></span>'
);
$page = rtrim($_SERVER["REQUEST_URI"], "/");
$index = checkPage(substr($page, strrpos(strtolower($page), "/") + 1), $active_arr);

function checkPage($page, $active_arr)
{	
	$ret = array_search($page, $active_arr);
	if($ret !== false)
	{
		return $ret;
	}
	return -1;
}
?>
<script type="text/javascript">var AJAX_ADMIN_PATH = "<?php bloginfo("url") ?>/wp-admin/admin-ajax.php";</script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/ecp_cart.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/date_functions.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/jquery-validate/jquery.validate.pack.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/validation.js"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo("template_directory") ?>/lib/plugins/url_handlers/pages/shopping-cart/css/style.css" />
<h1 class="left">Customize your Edge in College Prep Course</h1>
	<div class="form_progress">
		<span class="progress_segment active_progress first_segment"><span>Selection</span><span class="arrow"></span></span>
		<?php 
			for($i = 0, $len = sizeof($page_data); $i < $len; $i ++)
			{
				if($i <= $index)
				{
					printf($page_data[$i], $active_class);
				}
				else
				{
					printf($page_data[$i], $passive_class);
				}
			}	
		?>
	</div>