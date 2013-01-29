<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.clickbank.com/rest/1.2/orders/list");
curl_setopt($ch, CURLOPT_HEADER, false); 
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json", "Authorization:DEV-FBF19F6F46415AC735EA6ABE17D13062C559:API-C51ADF3E2B447E6232C9550D074841FBAC68"));
$result = curl_exec($ch);
curl_close($ch);
fb(json_decode($result));
// create user and send mail
if(isset($_POST["import"]))
{
	$password = wp_generate_password( 12, false );
			
	$user = $_POST["fname"]." ".$_POST["lname"];
	$data = array(
		"user_pass" => $password,
		"user_login" => $_POST["email"],
		"user_nicename" => $user,
		"user_email" => $_POST["email"],
		"display_name" => $user,
		"first_name" => $_POST["fname"],
		"last_name" => $_POST["lname"]
	);	
	
	$id = wp_insert_user($data);

	if(is_int($id))
	{		
		$idgl_data["id"] = $id;		
		$idgl_data["user_type"] = "student";
		$idgl_data["userSubtype"] = array("online-student");
		
		IDGL_Users::IDGL_addNewUserMeta($idgl_data);
			
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		$headers = "From: Edge in College Prep Administrator <info@edgeincollegeprep.com>" . "\r\n\\";
		
		$msg_subject = "ECP Notification";
		$msg_body = "These are your username: {$_POST['email']} and password: {$password}";
	
		if(!wp_mail($_POST['email'], $msg_subject, $msg_body, $headers))
		{
			
		}
		
		// insert user order
		$product_types = array(
			array("11", "3 Months"), array("12", "6 Months"), array("13", "1 Year")
		);
		$cb_data = json_decode($result);
		
		$products = array();
		$timestamp = time();
		
		foreach($cb_data -> orderData as $cb)
		{
			if($cb -> email == $_POST["email"])
			{
				$products[$id] = $product_types[($cb -> item) - 1][0];
				$products["type"] = $product_types[($cb -> item) - 1][1];
				$products["price"] = $cb -> amount;
				$products["discount"] = 0;
				break;
			}
		}
		
		add_user_meta( $id, "_IDGL_elem_ECP_user_order", serialize($products));
		$previous_orders_details = get_user_meta($id, "_IDGL_elem_ECP_user_orders_details", true);
		$orders_details = (empty($previous_orders_details)) ? array() : $previous_orders_details;
		$orders_details[$timestamp] = $products;
		
		if(!update_user_meta( $id, "_IDGL_elem_ECP_user_orders_details", $orders_details ))
		{
			//return $this -> _err -> get_error_message("insert_products");
		}
	}
}
?>
<table cellspacing="0" class="widefat page fixed tablesorter" id="IDGL_table">
	<thead>
		<tr>
			<th>receipt ID</th>
			<th>Date</th>
			<th>Payment Type</th>
			<th>Transaction Yype</th>
			<th>Amount</th>
			<th>Country</th>
			<th>F. name</th>
			<th>L. name</th>
			<th>Email</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody>
<?php
$user_arr = (json_decode($result));
foreach($user_arr -> orderData as $user_data):
	$udata = get_user_by('email', $user_data->email);
	?>
	<tr <?php if(is_object($udata)) echo "style='background:#ffcaca'"; ?> >
		<td><?php  echo $user_data->receipt; ?></td>
		<td><?php  echo $user_data->date; ?></td>
		<td><?php  echo $user_data->pmtType; ?></td>
		<td><?php  echo $user_data->txnType; ?></td>
		<td><?php  echo $user_data->amount; ?></td>
		<td><?php  echo $user_data->country; ?></td>
		<td><?php  echo $user_data->firstName; ?></td>
		<td><?php  echo $user_data->lastName; ?></td>
		<td><?php  echo $user_data->email; ?></td>
		<td>
		
		<?php if(!is_object($udata)){ ?>
			<form method="post">
				<input type="hidden" name="email" value="<?php echo $user_data -> email; ?>" />
				<input type="hidden" name="fname" value="<?php echo $user_data -> firstName; ?>" />
				<input type="hidden" name="lname" value="<?php echo $user_data -> lastName; ?>" />
				<input type="submit" value="import" name="import" class="button" />
			</form>
		<?php  
		}
		else
		{ 
		?>
			<a class="button" href="http://azriel.uk.to/ecp/portal/wp-admin/user-edit.php?user_id=<?php echo $udata->data->ID; ?>">edit</a> 
		<?php 
		} 
		?>
		</td>
	</tr>
	<?php
endforeach;

?>
	</tbody>
</table>