<p><span class="edge">Master Record: Instructor
<br><a href="<?php echo $sMasterReturnUrl ?>">Back to Master Page</a></span>
</p>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top" style="width: 25px;">First Name</td>
		<td valign="top" style="width: 25px;">Last Name</td>
		<td valign="top" style="width: 25px;">E-mail</td>
		<td valign="top" style="width: 25px;">Mobile</td>
		<td valign="top" style="width: 25px;">Username</td>
		<td valign="top" style="width: 25px;">Password</td>
	</tr>
	<tr class="ewTableSelectRow">
		<td style="width: 25px;">
<div<?php echo $tbl_instructors->i_first_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_first_name->ViewValue ?></div>
</td>
		<td style="width: 25px;">
<div<?php echo $tbl_instructors->i_last_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_last_name->ViewValue ?></div>
</td>
		<td style="width: 25px;">
<div<?php echo $tbl_instructors->i_email->ViewAttributes() ?>><?php echo $tbl_instructors->i_email->ViewValue ?></div>
</td>
		<td style="width: 25px;">
<div<?php echo $tbl_instructors->i_mobile->ViewAttributes() ?>><?php echo $tbl_instructors->i_mobile->ViewValue ?></div>
</td>
		<td style="width: 25px;">
<div<?php echo $tbl_instructors->i_uname->ViewAttributes() ?>><?php echo $tbl_instructors->i_uname->ViewValue ?></div>
</td>
		<td style="width: 25px;">
<div<?php echo $tbl_instructors->i_pwd->ViewAttributes() ?>><?php echo $tbl_instructors->i_pwd->ViewValue ?></div>
</td>
	</tr>
</table>
<br>
