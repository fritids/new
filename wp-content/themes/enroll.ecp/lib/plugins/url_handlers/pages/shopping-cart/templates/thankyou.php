<?php
	global $wpdb;
	global $current_user;
	
	?>

<div class="thankyou-page">
	<?php if(isset($_SESSION['transaction_id'])): unset($_SESSION['transaction_id']); ?>
	<h3>Thank you so much for making a purchase with The Edge</h3>
	<?php else: ?>
	<h3>Thank you so much for registering on The Edge</h3>
	<?php endif; ?>
	<p>
		You should be receiving an e-mail shortly with instructions on how to proceed.
	</p>

	<p>
		If you purchased a subscription to The SAT/ACT Edge, you'll be receiving a welcome e-mail with instructions on how to use the course to
		maximize your score improvement. If you have any issues, please do not hesitate to contact us at
		<a href="mailto:tech@edgeincollegeprep.com">tech@edgeincollegeprep.com</a> (for any technical issues) or <a href="mailto:online@edgeincollegeprep.com">online@edgeincollegeprep.com</a>
		(for any questions about the course). To get your essays graded, please email a copy of your essay along with the question and whether it is
		for an SAT or ACT to <a href="mailto:essays@edgeincollegeprep.com">essays@edgeincollegeprep.com</a>.
	</p>
	
	<p>
		If you purchased Test Prep Tutoring or Admissions Counseling, you'll be contacted by the appropriate office manager depending on your location.
		If you need immediate assistance before you hear from her, please reach out to:
	</p>
	
	<p>
		<ul>
			<li>Anna Maria Calo: <a href="mailto:anna@edgeincollegeprep.com">anna@edgeincollegeprep.com</a> (New York, London, Skype)</li>
			<li>Gala Tonconogy: <a href="mailto:gala@edgeincollegeprep.com">gala@edgeincollegeprep.com</a> (Buenos Aires, Rio de Janeiro)</li>
			<li>Matt Cull: <a href="mailto:matt@edgeincollegeprep.com">matt@edgeincollegeprep.com</a> (South Florida)</li>
		</ul>
	</p>
</div>