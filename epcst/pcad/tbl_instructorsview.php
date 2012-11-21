<?php
define("EW_PAGE_ID", "view", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_instructors', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_instructorsinfo.php" ?>
<?php include "userfn50.php" ?>
<?php include "tbl_aduserinfo.php" ?>
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
$tbl_instructors->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_instructors->Export; // Get export parameter, used in header
$sExportFile = $tbl_instructors->TableVar; // Get export file, used in header
?>
<?php
if (@$_GET["i_instructorid"] <> "") {
	$tbl_instructors->i_instructorid->setQueryStringValue($_GET["i_instructorid"]);
} else {
	Page_Terminate("tbl_instructorslist.php"); // Return to list page
}
if (@$_GET["i_uname"] <> "") {
	$tbl_instructors->i_uname->setQueryStringValue($_GET["i_uname"]);
} else {
	Page_Terminate("tbl_instructorslist.php"); // Return to list page
}

// Get action
if (@$_POST["a_view"] <> "") {
	$tbl_instructors->CurrentAction = $_POST["a_view"];
} else {
	$tbl_instructors->CurrentAction = "I"; // Display form
}
switch ($tbl_instructors->CurrentAction) {
	case "I": // Get a record to display
		if (!LoadRow()) { // Load record based on key
			$_SESSION[EW_SESSION_MESSAGE] = "No records found"; // Set no record message
			Page_Terminate("tbl_instructorslist.php"); // Return to list
		}
}

// Set return url
$tbl_instructors->setReturnUrl("tbl_instructorsview.php");

// Render row
$tbl_instructors->RowType = EW_ROWTYPE_VIEW;
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
<p><span class="edge"> Instructor
Profile<br>
<br>
<a href="tbl_instructorslist.php">Back to Instructor List</a>&nbsp;&nbsp;&nbsp;&nbsp; <?php if ($Security->IsLoggedIn()) { ?>
<a href="<?php echo $tbl_instructors->EditUrl() ?>">Edit</a>&nbsp;
<?php } ?>&nbsp;&nbsp;&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<a href="<?php echo $tbl_instructors->DeleteUrl() ?>">Delete</a>&nbsp;
<?php } ?>&nbsp;&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<a href="tbl_studentslist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_instructors&i_instructorid=<?php echo urlencode(strval($tbl_instructors->i_instructorid->CurrentValue)) ?>">Students</a>
&nbsp;
<?php } ?>
</span>
</p>
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
<table class="ewTable">
	<tr class="ewTableRow">
		<td class="ewTableHeader">First Name</td>
		<td<?php echo $tbl_instructors->i_first_name->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_first_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_first_name->ViewValue ?></div>
</td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Last Name</td>
		<td<?php echo $tbl_instructors->i_last_name->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_last_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_last_name->ViewValue ?></div>
</td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">E-mail</td>
		<td<?php echo $tbl_instructors->i_email->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_email->ViewAttributes() ?>><?php echo $tbl_instructors->i_email->ViewValue ?></div>
</td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Mobile</td>
		<td<?php echo $tbl_instructors->i_mobile->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_mobile->ViewAttributes() ?>><?php echo $tbl_instructors->i_mobile->ViewValue ?></div>
</td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Username</td>
		<td<?php echo $tbl_instructors->i_uname->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_uname->ViewAttributes() ?>><?php echo $tbl_instructors->i_uname->ViewValue ?></div>
</td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Password</td>
		<td<?php echo $tbl_instructors->i_pwd->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_pwd->ViewAttributes() ?>><?php echo $tbl_instructors->i_pwd->ViewValue ?></div>
</td>
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
	global $conn, $Security, $tbl_instructors;
	$sFilter = $tbl_instructors->SqlKeyFilter();
	if (!is_numeric($tbl_instructors->i_instructorid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@i_instructorid@", ew_AdjustSql($tbl_instructors->i_instructorid->CurrentValue), $sFilter); // Replace key value
	$sFilter = str_replace("@i_uname@", ew_AdjustSql($tbl_instructors->i_uname->CurrentValue), $sFilter); // Replace key value

	// Call Row Selecting event
	$tbl_instructors->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_instructors->CurrentFilter = $sFilter;
	$sSql = $tbl_instructors->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_instructors->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_instructors;
	$tbl_instructors->i_instructorid->setDbValue($rs->fields('i_instructorid'));
	$tbl_instructors->i_first_name->setDbValue($rs->fields('i_first_name'));
	$tbl_instructors->i_last_name->setDbValue($rs->fields('i_last_name'));
	$tbl_instructors->i_email->setDbValue($rs->fields('i_email'));
	$tbl_instructors->i_mobile->setDbValue($rs->fields('i_mobile'));
	$tbl_instructors->i_uname->setDbValue($rs->fields('i_uname'));
	$tbl_instructors->i_pwd->setDbValue($rs->fields('i_pwd'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_instructors;

	// Call Row Rendering event
	$tbl_instructors->Row_Rendering();

	// Common render codes for all row types
	// i_first_name

	$tbl_instructors->i_first_name->CellCssStyle = "";
	$tbl_instructors->i_first_name->CellCssClass = "";

	// i_last_name
	$tbl_instructors->i_last_name->CellCssStyle = "";
	$tbl_instructors->i_last_name->CellCssClass = "";

	// i_email
	$tbl_instructors->i_email->CellCssStyle = "";
	$tbl_instructors->i_email->CellCssClass = "";

	// i_mobile
	$tbl_instructors->i_mobile->CellCssStyle = "";
	$tbl_instructors->i_mobile->CellCssClass = "";

	// i_uname
	$tbl_instructors->i_uname->CellCssStyle = "";
	$tbl_instructors->i_uname->CellCssClass = "";

	// i_pwd
	$tbl_instructors->i_pwd->CellCssStyle = "";
	$tbl_instructors->i_pwd->CellCssClass = "";
	if ($tbl_instructors->RowType == EW_ROWTYPE_VIEW) { // View row

		// i_first_name
		$tbl_instructors->i_first_name->ViewValue = $tbl_instructors->i_first_name->CurrentValue;
		$tbl_instructors->i_first_name->CssStyle = "";
		$tbl_instructors->i_first_name->CssClass = "";
		$tbl_instructors->i_first_name->ViewCustomAttributes = "";

		// i_last_name
		$tbl_instructors->i_last_name->ViewValue = $tbl_instructors->i_last_name->CurrentValue;
		$tbl_instructors->i_last_name->CssStyle = "";
		$tbl_instructors->i_last_name->CssClass = "";
		$tbl_instructors->i_last_name->ViewCustomAttributes = "";

		// i_email
		$tbl_instructors->i_email->ViewValue = $tbl_instructors->i_email->CurrentValue;
		$tbl_instructors->i_email->CssStyle = "";
		$tbl_instructors->i_email->CssClass = "";
		$tbl_instructors->i_email->ViewCustomAttributes = "";

		// i_mobile
		$tbl_instructors->i_mobile->ViewValue = $tbl_instructors->i_mobile->CurrentValue;
		$tbl_instructors->i_mobile->CssStyle = "";
		$tbl_instructors->i_mobile->CssClass = "";
		$tbl_instructors->i_mobile->ViewCustomAttributes = "";

		// i_uname
		$tbl_instructors->i_uname->ViewValue = $tbl_instructors->i_uname->CurrentValue;
		$tbl_instructors->i_uname->CssStyle = "";
		$tbl_instructors->i_uname->CssClass = "";
		$tbl_instructors->i_uname->ViewCustomAttributes = "";

		// i_pwd
		$tbl_instructors->i_pwd->ViewValue = "********";
		$tbl_instructors->i_pwd->CssStyle = "";
		$tbl_instructors->i_pwd->CssClass = "";
		$tbl_instructors->i_pwd->ViewCustomAttributes = "";

		// i_first_name
		$tbl_instructors->i_first_name->HrefValue = "";

		// i_last_name
		$tbl_instructors->i_last_name->HrefValue = "";

		// i_email
		$tbl_instructors->i_email->HrefValue = "";

		// i_mobile
		$tbl_instructors->i_mobile->HrefValue = "";

		// i_uname
		$tbl_instructors->i_uname->HrefValue = "";

		// i_pwd
		$tbl_instructors->i_pwd->HrefValue = "";
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_instructors->Row_Rendered();
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $tbl_instructors;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$tbl_instructors->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$tbl_instructors->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $tbl_instructors->getStartRecordNumber();
		}
	} else {
		$nStartRec = $tbl_instructors->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$tbl_instructors->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$tbl_instructors->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$tbl_instructors->setStartRecordNumber($nStartRec);
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
