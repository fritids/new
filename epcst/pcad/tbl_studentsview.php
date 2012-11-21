<?php
define("EW_PAGE_ID", "view", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_students', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_studentsinfo.php" ?>
<?php include "userfn50.php" ?>
<?php include "tbl_aduserinfo.php" ?>
<?php include "tbl_instructorsinfo.php" ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>
<?php

// Open connection to the database
$conn = ew_Connect();
?>
<?php
$Security = new cAdvancedSecurity();
?>
<?php
if (!$Security->IsLoggedIn()) $Security->AutoLogin();
if (!$Security->IsLoggedIn()) {
	$Security->SaveLastUrl();
	Page_Terminate("login.php");
}
?>
<?php

// Common page loading event (in userfn*.php)
Page_Loading();
?>
<?php

// Page load event, used in current page
Page_Load();
?>
<?php
$tbl_students->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_students->Export; // Get export parameter, used in header
$sExportFile = $tbl_students->TableVar; // Get export file, used in header
?>
<?php
if (@$_GET["s_studentid"] <> "") {
	$tbl_students->s_studentid->setQueryStringValue($_GET["s_studentid"]);
} else {
	Page_Terminate("tbl_studentslist.php"); // Return to list page
}
if (@$_GET["s_usrname"] <> "") {
	$tbl_students->s_usrname->setQueryStringValue($_GET["s_usrname"]);
} else {
	Page_Terminate("tbl_studentslist.php"); // Return to list page
}

// Get action
if (@$_POST["a_view"] <> "") {
	$tbl_students->CurrentAction = $_POST["a_view"];
} else {
	$tbl_students->CurrentAction = "I"; // Display form
}
switch ($tbl_students->CurrentAction) {
	case "I": // Get a record to display
		if (!LoadRow()) { // Load record based on key
			$_SESSION[EW_SESSION_MESSAGE] = "No records found"; // Set no record message
			Page_Terminate("tbl_studentslist.php"); // Return to list
		}
}

// Set return url
$tbl_students->setReturnUrl("tbl_studentsview.php");

// Render row
$tbl_students->RowType = EW_ROWTYPE_VIEW;
RenderRow();
?>
<?php include "header.php" ?>
<script type="text/javascript">
<!--
var EW_PAGE_ID = "view"; // Page id

//-->
</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js"); 
//-->

</script>
<p><span class="edge">Student's Profile <br>
  <br>
<a href="tbl_studentslist.php">Back to List</a>&nbsp;&nbsp;&nbsp; <?php if ($Security->IsLoggedIn()) { ?>
<a href="<?php echo $tbl_students->EditUrl() ?>">Edit</a>&nbsp;
<?php } ?>&nbsp;&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<a href="<?php echo $tbl_students->DeleteUrl() ?>">Delete</a>&nbsp;
<?php } ?>
</span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<p>
<form>
<table width="450" class="ewTable">
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">First Name</td>
		<td width="300"<?php echo $tbl_students->s_first_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_first_name->ViewAttributes() ?>><?php echo $tbl_students->s_first_name->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_first_name->CellAttributes() ?>><span class="edge">
        <?php if ($Security->IsLoggedIn()) { ?>
        <a href="tbl_prep_programslist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Prep Programs</a> &nbsp;
        <?php } ?>
        </span></td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Last Name</td>
		<td width="300"<?php echo $tbl_students->s_last_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_last_name->ViewAttributes() ?>><?php echo $tbl_students->s_last_name->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_last_name->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Middle Name</td>
		<td width="300"<?php echo $tbl_students->s_middle_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_middle_name->ViewAttributes() ?>><?php echo $tbl_students->s_middle_name->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_middle_name->CellAttributes() ?>><span class="edge">
	      <?php if ($Security->IsLoggedIn()) { ?>
          <a href="tbl_sessionlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Sessions</a> &nbsp;
          <?php } ?>
        </span></td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Address</td>
		<td width="300"<?php echo $tbl_students->s_address->CellAttributes() ?>>
<div<?php echo $tbl_students->s_address->ViewAttributes() ?>><?php echo $tbl_students->s_address->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_address->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">City</td>
		<td width="300"<?php echo $tbl_students->s_city->CellAttributes() ?>>
<div<?php echo $tbl_students->s_city->ViewAttributes() ?>><?php echo $tbl_students->s_city->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_city->CellAttributes() ?>><span class="edge">
	      <?php if ($Security->IsLoggedIn()) { ?>
          <a href="tbl_actual_satlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Actual SAT</a> &nbsp;
          <?php } ?>
	    </span></td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Postal Code</td>
		<td width="300"<?php echo $tbl_students->s_postal_code->CellAttributes() ?>>
<div<?php echo $tbl_students->s_postal_code->ViewAttributes() ?>><?php echo $tbl_students->s_postal_code->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_postal_code->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">State</td>
		<td width="300"<?php echo $tbl_students->s_state->CellAttributes() ?>>
<div<?php echo $tbl_students->s_state->ViewAttributes() ?>><?php echo $tbl_students->s_state->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_state->CellAttributes() ?>><span class="edge">
	      <?php if ($Security->IsLoggedIn()) { ?>
          <a href="tbl_testing_satlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Test SAT</a> &nbsp;
          <?php } ?>
	    </span></td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Country</td>
		<td width="300"<?php echo $tbl_students->s_country->CellAttributes() ?>>
<div<?php echo $tbl_students->s_country->ViewAttributes() ?>><?php echo $tbl_students->s_country->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_country->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Home Phone</td>
		<td width="300"<?php echo $tbl_students->s_home_phone->CellAttributes() ?>>
<div<?php echo $tbl_students->s_home_phone->ViewAttributes() ?>><?php echo $tbl_students->s_home_phone->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_home_phone->CellAttributes() ?>><span class="edge">
	      <?php if ($Security->IsLoggedIn()) { ?>
          <a href="tbl_actual_actlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Actual ACT</a> &nbsp;
          <?php } ?>
        </span></td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Mobile</td>
		<td width="300"<?php echo $tbl_students->s_student_mobile->CellAttributes() ?>>
<div<?php echo $tbl_students->s_student_mobile->ViewAttributes() ?>><?php echo $tbl_students->s_student_mobile->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_student_mobile->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">E-mail</td>
		<td width="300"<?php echo $tbl_students->s_student_email->CellAttributes() ?>>
<div<?php echo $tbl_students->s_student_email->ViewAttributes() ?>><?php echo $tbl_students->s_student_email->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_student_email->CellAttributes() ?>><span class="edge">
	      <?php if ($Security->IsLoggedIn()) { ?>
          <a href="tbl_testing_actlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Test ACT</a> &nbsp;
          <?php } ?>
	    </span></td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Parent Name</td>
		<td width="300"<?php echo $tbl_students->s_parent_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_parent_name->ViewAttributes() ?>><?php echo $tbl_students->s_parent_name->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_parent_name->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Parent Mobile</td>
		<td width="300"<?php echo $tbl_students->s_parent_mobile->CellAttributes() ?>>
<div<?php echo $tbl_students->s_parent_mobile->ViewAttributes() ?>><?php echo $tbl_students->s_parent_mobile->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_parent_mobile->CellAttributes() ?>><span class="edge">
	      <?php if ($Security->IsLoggedIn()) { ?>
          <a href="tbl_psatlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">PSAT</a> &nbsp;
          <?php } ?>
        </span></td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Parent Email</td>
		<td width="300"<?php echo $tbl_students->s_parent_email->CellAttributes() ?>>
<div<?php echo $tbl_students->s_parent_email->ViewAttributes() ?>><?php echo $tbl_students->s_parent_email->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_parent_email->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">School</td>
		<td width="300"<?php echo $tbl_students->s_school->CellAttributes() ?>>
<div<?php echo $tbl_students->s_school->ViewAttributes() ?>><?php echo $tbl_students->s_school->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_school->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Graduation Year</td>
		<td width="300"<?php echo $tbl_students->s_graduation_year->CellAttributes() ?>>
<div<?php echo $tbl_students->s_graduation_year->ViewAttributes() ?>><?php echo $tbl_students->s_graduation_year->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_graduation_year->CellAttributes() ?>>&nbsp;</td>
	</tr>
	<tr class="ewTableRow">
		<td width="120" class="ewTableHeader">Username</td>
		<td width="300"<?php echo $tbl_students->s_usrname->CellAttributes() ?>>
<div<?php echo $tbl_students->s_usrname->ViewAttributes() ?>><?php echo $tbl_students->s_usrname->ViewValue ?></div></td>
	    <td width="200"<?php echo $tbl_students->s_usrname->CellAttributes() ?>>&nbsp;</td>
	</tr>
</table>
</form>
<p>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php include "footer.php" ?>
<?php

// If control is passed here, simply terminate the page without redirect
Page_Terminate();

// -----------------------------------------------------------------
//  Subroutine Page_Terminate
//  - called when exit page
//  - clean up connection and objects
//  - if url specified, redirect to url, otherwise end response
function Page_Terminate($url = "") {
	global $conn;

	// Page unload event, used in current page
	Page_Unload();

	// Global page unloaded event (in userfn*.php)
	Page_Unloaded();

	 // Close Connection
	$conn->Close();

	// Go to url if specified
	if ($url <> "") {
		ob_end_clean();
		header("Location: $url");
	}
	exit();
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_students;
	$sFilter = $tbl_students->SqlKeyFilter();
	if (!is_numeric($tbl_students->s_studentid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@s_studentid@", ew_AdjustSql($tbl_students->s_studentid->CurrentValue), $sFilter); // Replace key value
	$sFilter = str_replace("@s_usrname@", ew_AdjustSql($tbl_students->s_usrname->CurrentValue), $sFilter); // Replace key value

	// Call Row Selecting event
	$tbl_students->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_students->CurrentFilter = $sFilter;
	$sSql = $tbl_students->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_students->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_students;
	$tbl_students->s_studentid->setDbValue($rs->fields('s_studentid'));
	$tbl_students->s_first_name->setDbValue($rs->fields('s_first_name'));
	$tbl_students->s_last_name->setDbValue($rs->fields('s_last_name'));
	$tbl_students->s_middle_name->setDbValue($rs->fields('s_middle_name'));
	$tbl_students->s_address->setDbValue($rs->fields('s_address'));
	$tbl_students->s_city->setDbValue($rs->fields('s_city'));
	$tbl_students->s_postal_code->setDbValue($rs->fields('s_postal_code'));
	$tbl_students->s_state->setDbValue($rs->fields('s_state'));
	$tbl_students->s_country->setDbValue($rs->fields('s_country'));
	$tbl_students->s_home_phone->setDbValue($rs->fields('s_home_phone'));
	$tbl_students->s_student_mobile->setDbValue($rs->fields('s_student_mobile'));
	$tbl_students->s_student_email->setDbValue($rs->fields('s_student_email'));
	$tbl_students->s_parent_name->setDbValue($rs->fields('s_parent_name'));
	$tbl_students->s_parent_mobile->setDbValue($rs->fields('s_parent_mobile'));
	$tbl_students->s_parent_email->setDbValue($rs->fields('s_parent_email'));
	$tbl_students->s_school->setDbValue($rs->fields('s_school'));
	$tbl_students->s_graduation_year->setDbValue($rs->fields('s_graduation_year'));
	$tbl_students->s_usrname->setDbValue($rs->fields('s_usrname'));
	$tbl_students->s_pwd->setDbValue($rs->fields('s_pwd'));
	$tbl_students->i_instructid->setDbValue($rs->fields('i_instructid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_students;

	// Call Row Rendering event
	$tbl_students->Row_Rendering();

	// Common render codes for all row types
	// s_first_name

	$tbl_students->s_first_name->CellCssStyle = "";
	$tbl_students->s_first_name->CellCssClass = "";

	// s_last_name
	$tbl_students->s_last_name->CellCssStyle = "";
	$tbl_students->s_last_name->CellCssClass = "";

	// s_middle_name
	$tbl_students->s_middle_name->CellCssStyle = "";
	$tbl_students->s_middle_name->CellCssClass = "";

	// s_address
	$tbl_students->s_address->CellCssStyle = "";
	$tbl_students->s_address->CellCssClass = "";

	// s_city
	$tbl_students->s_city->CellCssStyle = "";
	$tbl_students->s_city->CellCssClass = "";

	// s_postal_code
	$tbl_students->s_postal_code->CellCssStyle = "";
	$tbl_students->s_postal_code->CellCssClass = "";

	// s_state
	$tbl_students->s_state->CellCssStyle = "";
	$tbl_students->s_state->CellCssClass = "";

	// s_country
	$tbl_students->s_country->CellCssStyle = "";
	$tbl_students->s_country->CellCssClass = "";

	// s_home_phone
	$tbl_students->s_home_phone->CellCssStyle = "";
	$tbl_students->s_home_phone->CellCssClass = "";

	// s_student_mobile
	$tbl_students->s_student_mobile->CellCssStyle = "";
	$tbl_students->s_student_mobile->CellCssClass = "";

	// s_student_email
	$tbl_students->s_student_email->CellCssStyle = "";
	$tbl_students->s_student_email->CellCssClass = "";

	// s_parent_name
	$tbl_students->s_parent_name->CellCssStyle = "";
	$tbl_students->s_parent_name->CellCssClass = "";

	// s_parent_mobile
	$tbl_students->s_parent_mobile->CellCssStyle = "";
	$tbl_students->s_parent_mobile->CellCssClass = "";

	// s_parent_email
	$tbl_students->s_parent_email->CellCssStyle = "";
	$tbl_students->s_parent_email->CellCssClass = "";

	// s_school
	$tbl_students->s_school->CellCssStyle = "";
	$tbl_students->s_school->CellCssClass = "";

	// s_graduation_year
	$tbl_students->s_graduation_year->CellCssStyle = "";
	$tbl_students->s_graduation_year->CellCssClass = "";

	// s_usrname
	$tbl_students->s_usrname->CellCssStyle = "";
	$tbl_students->s_usrname->CellCssClass = "";
	if ($tbl_students->RowType == EW_ROWTYPE_VIEW) { // View row

		// s_first_name
		$tbl_students->s_first_name->ViewValue = $tbl_students->s_first_name->CurrentValue;
		$tbl_students->s_first_name->CssStyle = "";
		$tbl_students->s_first_name->CssClass = "";
		$tbl_students->s_first_name->ViewCustomAttributes = "";

		// s_last_name
		$tbl_students->s_last_name->ViewValue = $tbl_students->s_last_name->CurrentValue;
		$tbl_students->s_last_name->CssStyle = "";
		$tbl_students->s_last_name->CssClass = "";
		$tbl_students->s_last_name->ViewCustomAttributes = "";

		// s_middle_name
		$tbl_students->s_middle_name->ViewValue = $tbl_students->s_middle_name->CurrentValue;
		$tbl_students->s_middle_name->CssStyle = "";
		$tbl_students->s_middle_name->CssClass = "";
		$tbl_students->s_middle_name->ViewCustomAttributes = "";

		// s_address
		$tbl_students->s_address->ViewValue = $tbl_students->s_address->CurrentValue;
		$tbl_students->s_address->CssStyle = "";
		$tbl_students->s_address->CssClass = "";
		$tbl_students->s_address->ViewCustomAttributes = "";

		// s_city
		$tbl_students->s_city->ViewValue = $tbl_students->s_city->CurrentValue;
		$tbl_students->s_city->CssStyle = "";
		$tbl_students->s_city->CssClass = "";
		$tbl_students->s_city->ViewCustomAttributes = "";

		// s_postal_code
		$tbl_students->s_postal_code->ViewValue = $tbl_students->s_postal_code->CurrentValue;
		$tbl_students->s_postal_code->CssStyle = "";
		$tbl_students->s_postal_code->CssClass = "";
		$tbl_students->s_postal_code->ViewCustomAttributes = "";

		// s_state
		$tbl_students->s_state->ViewValue = $tbl_students->s_state->CurrentValue;
		$tbl_students->s_state->CssStyle = "";
		$tbl_students->s_state->CssClass = "";
		$tbl_students->s_state->ViewCustomAttributes = "";

		// s_country
		$tbl_students->s_country->ViewValue = $tbl_students->s_country->CurrentValue;
		$tbl_students->s_country->CssStyle = "";
		$tbl_students->s_country->CssClass = "";
		$tbl_students->s_country->ViewCustomAttributes = "";

		// s_home_phone
		$tbl_students->s_home_phone->ViewValue = $tbl_students->s_home_phone->CurrentValue;
		$tbl_students->s_home_phone->CssStyle = "";
		$tbl_students->s_home_phone->CssClass = "";
		$tbl_students->s_home_phone->ViewCustomAttributes = "";

		// s_student_mobile
		$tbl_students->s_student_mobile->ViewValue = $tbl_students->s_student_mobile->CurrentValue;
		$tbl_students->s_student_mobile->CssStyle = "";
		$tbl_students->s_student_mobile->CssClass = "";
		$tbl_students->s_student_mobile->ViewCustomAttributes = "";

		// s_student_email
		$tbl_students->s_student_email->ViewValue = $tbl_students->s_student_email->CurrentValue;
		$tbl_students->s_student_email->CssStyle = "";
		$tbl_students->s_student_email->CssClass = "";
		$tbl_students->s_student_email->ViewCustomAttributes = "";

		// s_parent_name
		$tbl_students->s_parent_name->ViewValue = $tbl_students->s_parent_name->CurrentValue;
		$tbl_students->s_parent_name->CssStyle = "";
		$tbl_students->s_parent_name->CssClass = "";
		$tbl_students->s_parent_name->ViewCustomAttributes = "";

		// s_parent_mobile
		$tbl_students->s_parent_mobile->ViewValue = $tbl_students->s_parent_mobile->CurrentValue;
		$tbl_students->s_parent_mobile->CssStyle = "";
		$tbl_students->s_parent_mobile->CssClass = "";
		$tbl_students->s_parent_mobile->ViewCustomAttributes = "";

		// s_parent_email
		$tbl_students->s_parent_email->ViewValue = $tbl_students->s_parent_email->CurrentValue;
		$tbl_students->s_parent_email->CssStyle = "";
		$tbl_students->s_parent_email->CssClass = "";
		$tbl_students->s_parent_email->ViewCustomAttributes = "";

		// s_school
		$tbl_students->s_school->ViewValue = $tbl_students->s_school->CurrentValue;
		$tbl_students->s_school->CssStyle = "";
		$tbl_students->s_school->CssClass = "";
		$tbl_students->s_school->ViewCustomAttributes = "";

		// s_graduation_year
		$tbl_students->s_graduation_year->ViewValue = $tbl_students->s_graduation_year->CurrentValue;
		$tbl_students->s_graduation_year->CssStyle = "";
		$tbl_students->s_graduation_year->CssClass = "";
		$tbl_students->s_graduation_year->ViewCustomAttributes = "";

		// s_usrname
		$tbl_students->s_usrname->ViewValue = $tbl_students->s_usrname->CurrentValue;
		$tbl_students->s_usrname->CssStyle = "";
		$tbl_students->s_usrname->CssClass = "";
		$tbl_students->s_usrname->ViewCustomAttributes = "";

		// s_first_name
		$tbl_students->s_first_name->HrefValue = "";

		// s_last_name
		$tbl_students->s_last_name->HrefValue = "";

		// s_middle_name
		$tbl_students->s_middle_name->HrefValue = "";

		// s_address
		$tbl_students->s_address->HrefValue = "";

		// s_city
		$tbl_students->s_city->HrefValue = "";

		// s_postal_code
		$tbl_students->s_postal_code->HrefValue = "";

		// s_state
		$tbl_students->s_state->HrefValue = "";

		// s_country
		$tbl_students->s_country->HrefValue = "";

		// s_home_phone
		$tbl_students->s_home_phone->HrefValue = "";

		// s_student_mobile
		$tbl_students->s_student_mobile->HrefValue = "";

		// s_student_email
		$tbl_students->s_student_email->HrefValue = "";

		// s_parent_name
		$tbl_students->s_parent_name->HrefValue = "";

		// s_parent_mobile
		$tbl_students->s_parent_mobile->HrefValue = "";

		// s_parent_email
		$tbl_students->s_parent_email->HrefValue = "";

		// s_school
		$tbl_students->s_school->HrefValue = "";

		// s_graduation_year
		$tbl_students->s_graduation_year->HrefValue = "";

		// s_usrname
		$tbl_students->s_usrname->HrefValue = "";
	} elseif ($tbl_students->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_students->Row_Rendered();
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $tbl_students;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$tbl_students->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$tbl_students->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $tbl_students->getStartRecordNumber();
		}
	} else {
		$nStartRec = $tbl_students->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$tbl_students->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$tbl_students->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$tbl_students->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Page Load event
function Page_Load() {

	//echo "Page Load";
}

// Page Unload event
function Page_Unload() {

	//echo "Page Unload";
}
?>
