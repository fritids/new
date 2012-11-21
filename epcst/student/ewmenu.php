<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr><td><span class="edge"><a href="http://www.edgeincollegeprep.com/home.html">Home</a></span></td></tr>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="tbl_studentslist.php?cmd=resetall">Profile</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="vwstudentinstructorlist.php?cmd=resetall">Instructor</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="vwstudentprepprogramlist.php?cmd=resetall">Prep Program</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="vwstudentsessionlist.php?cmd=resetall">Session</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="vwstudentactualsatlist.php?cmd=resetall">Actual SAT</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="vwstudenttestsatlist.php?cmd=resetall">Test SAT</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="vwstudentactualactlist.php?cmd=resetall">Actual ACT</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="vwstudenttestactlist.php?cmd=resetall">Test ACT</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="vwstudentpsatlist.php?cmd=resetall">PSAT</a></span></td></tr>
<?php } ?>
<?php if (IsLoggedIn()) { ?>
	<tr><td><span class="edge"><a href="logout.php">Logout</a></span></td></tr>
<?php } elseif (substr(ew_ScriptName(), -1*strlen("login.php")) <> "login.php") { ?>
	<tr><td><span class="edge"><a href="login.php">Login</a></span></td></tr>
<?php } ?>
</table>
