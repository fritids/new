<div>
<?php if(!is_user_logged_in()): ?>
<p>Thank you for registering on college prep. Check your email for login credentials. If you don't receive any email from us, please contact us on : <a href="mailto:online@edgeincollegeprep.com">online@edgeincollegeprep.com</a>, 
and send us your transaction key: <big><?php echo $params["user_data"]["transaction_id"]; ?></big>
</p>
<?php else: ?>
<p>
	Thank you for your order. You will receive mail with your order details. If you don't receive any email from us, please contact us on : <a href="mailto:online@edgeincollegeprep.com">online@edgeincollegeprep.com</a>, 
and send us your transaction key: <big><?php echo $params["user_data"]["transaction_id"]; ?></big>
</p>
<?php endif; ?>
</div>