<?php get_header(); ?>
<?php
	global $tryout_session;
	global $errors;
	if(!$errors -> isEmpty())
	{
		$user_data = $tryout_session -> getAllUserData();
		$tryout_session -> emptyUserData();
	}
	else
	{
		$user_data = NULL;
	}
?>
<script type="text/javascript">var AJAX_ADMIN_PATH = "<?php bloginfo("url") ?>/wp-admin/admin-ajax.php";</script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/date_functions.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/jquery-validate/jquery.validate.pack.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/validation.js"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo("template_directory") ?>/lib/plugins/url_handlers/pages/tryout/css/style.css" ></link>

<?php $section = $wp_query -> query_vars['section']; ?>
<?php if(is_null($section)): ?>
	<form action="<?php echo get_bloginfo("url")."/tryout/handler"; ?>" method="POST" id="ecp_cart_form">
		<div class="whitebox">
			<h4>Account Setup</h4>
			<p><strong>Register for a FREE 5-day trial subscription to the online SAT prep course</strong></p>
			<div class="ecp_order_wrapper">
			
				<?php if(!is_null($errors) && !$errors -> isEmpty() && count($errors -> getAllUserData()) > 1 && !empty($user_data)): ?>
				<div class="req-notes">
					<h6>Following errors occurred in your form:</h6>
					<ul>
						<?php foreach($errors -> getAllUserData() as $error): ?>
							<li><?php echo $error; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php $errors -> emptyUserData(); ?>
				<?php endif; ?>
				<?php echo Templator::getTemplate("tryout_form", $user_data, dirname(__FILE__)."/tryout/templates/"); ?>
		</div>
	</form>
<?php elseif($section == "form-handler"): ?>
	<?php echo Templator::getTemplate("notification", null, dirname(__FILE__)."/tryout/templates/"); ?>
<?php endif; ?>
<?php get_footer(); ?>