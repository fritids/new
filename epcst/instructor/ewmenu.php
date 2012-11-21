<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr><td><span class="edge"><a href="http://www.edgeincollegeprep.com/home.html">Home</a></span></td></tr>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="tbl_instructorslist.php?cmd=resetall">Instructor</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="tbl_studentslist.php?cmd=resetall">Students</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="logout.php">Logout</a></span></td></tr>
<?php } elseif (substr(ew_ScriptName(), -1*strlen("login.php")) <> "login.php") { ?>
	<tr><td><span class="edge"><a href="login.php">Login</a></span></td></tr>
<?php } ?>
</table>
