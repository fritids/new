<p><span class="edge"><br>
  <a href="<?php echo $sMasterReturnUrl ?>">Back to Instructor List</a></span>
</p>
<table width="455" class="ewTable">
  <tr class="ewTableSelectRow">
    <td width="140">Instructor Name </td>
    <td width="303"> &nbsp;<?php echo $tbl_instructors->i_first_name->ViewValue ?> <?php echo $tbl_instructors->i_last_name->ViewValue ?></td>
  </tr>
  <tr class="ewTableSelectRow">
    <td>E-mail</td>
    <td></div>
    <?php echo $tbl_instructors->i_email->ViewValue ?></td>
  </tr>
</table>
<p>&nbsp; </p>
