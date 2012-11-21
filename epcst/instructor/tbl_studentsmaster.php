<p><span class="edge">  
<br>
<a href="<?php echo $sMasterReturnUrl ?>">Back to Student List </a></span>
</p>
<table width="316" class="ewTable">
	<tr class="ewTableSelectRow">
	  <td width="59">Name  </td>
	  <td width="245"><strong><?php echo $tbl_students->s_first_name->ViewValue ?></strong>&nbsp;<strong><?php echo $tbl_students->s_last_name->ViewValue ?></strong> <strong><?php echo $tbl_students->s_middle_name->ViewValue ?></strong></div></td>
	<tr class="ewTableSelectRow">
      <td>E-mail</td>
      <td><strong><?php echo $tbl_students->s_student_email->ViewValue ?></strong></div></td>
</table>
<br>