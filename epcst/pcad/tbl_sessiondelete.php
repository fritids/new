<?php
define("EW_PAGE_ID", "delete", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_session', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_sessioninfo.php" ?>
<?php include "userfn50.php" ?>
<?php include "tbl_aduserinfo.php" ?>
<?php include "tbl_studentsinfo.php" ?>
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
$tbl_session->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_session->Export; // Get export parameter, used in header
$sExportFile = $tbl_session->TableVar; // Get export file, used in header
?>
<?php

// Load Key Parameters
$sKey = "";
$bSingleDelete = TRUE; // Initialize as single delete
$arRecKeys = array();
$nKeySelected = 0; // Initialize selected key count
$sFilter = "";
if (@$_GET["sessionid"] <> "") {
	$tbl_session->sessionid->setQueryStringValue($_GET["sessionid"]);
	if (!is_numeric($tbl_session->sessionid->QueryStringValue)) {
		Page_Terminate($tbl_session->getReturnUrl()); // Prevent sql injection, exit
	}
	$sKey .= $tbl_session->sessionid->QueryStringValue;
} else {
	$bSingleDelete = FALSE;
}
if ($bSingleDelete) {
	$nKeySelected = 1; // Set up key selected count
	$arRecKeys[0] = $sKey;
} else {
	if (isset($_POST["key_m"])) { // Key in form
		$nKeySelected = count($_POST["key_m"]); // Set up key selected count
		$arRecKeys = ew_StripSlashes($_POST["key_m"]);
	}
}
if ($nKeySelected <= 0) Page_Terminate($tbl_session->getReturnUrl()); // No key specified, exit

// Build filter
foreach ($arRecKeys as $sKey) {
	$sFilter .= "(";

	// Set up key field
	$sKeyFld = $sKey;
	if (!is_numeric($sKeyFld)) {
		Page_Terminate($tbl_session->getReturnUrl()); // Prevent sql injection, exit
	}
	$sFilter .= "`sessionid`=" . ew_AdjustSql($sKeyFld) . " AND ";
	if (substr($sFilter, -5) == " AND ") $sFilter = substr($sFilter, 0, strlen($sFilter)-5) . ") OR ";
}
if (substr($sFilter, -4) == " OR ") $sFilter = substr($sFilter, 0, strlen($sFilter)-4);

// Set up filter (Sql Where Clause) and get Return Sql
// Sql constructor in tbl_session class, tbl_sessioninfo.php

$tbl_session->CurrentFilter = $sFilter;

// Get action
if (@$_POST["a_delete"] <> "") {
	$tbl_session->CurrentAction = $_POST["a_delete"];
} else {
	$tbl_session->CurrentAction = "I"; // Display record
}
switch ($tbl_session->CurrentAction) {
	case "D": // Delete
		$tbl_session->SendEmail = TRUE; // Send email on delete success
		if (DeleteRows()) { // delete rows
			$_SESSION[EW_SESSION_MESSAGE] = "Delete Successful"; // Set up success message
			Page_Terminate($tbl_session->getReturnUrl()); // Return to caller
		}
}

// Load records for display
$rs = LoadRecordset();
$nTotalRecs = $rs->RecordCount(); // Get record count
if ($nTotalRecs <= 0) { // No record found, exit
	$rs->Close();
	Page_Terminate($tbl_session->getReturnUrl()); // Return to caller
}
?>
<?php include "header.php" ?>
<script type="text/javascript">
<!--
var EW_PAGE_ID = "delete"; // Page id

//-->
</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js"); 
//-->

</script>
<p><span class="edge">Delete Student's Sessions<br>
    <br><a href="<?php echo $tbl_session->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form action="tbl_sessiondelete.php" method="post">
<p>
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($arRecKeys as $sKey) { ?>
<input type="hidden" name="key_m[]" id="key_m[]" value="<?php echo ew_HtmlEncode($sKey) ?>">
<?php } ?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td width="100" valign="top">Date</td>
		<td width="100" valign="top">Number</td>
		<td width="100" valign="top">Goal</td>
		<td width="100" valign="top">Completed</td>
		<td width="100" valign="top">Homework</td>
		<td width="100" valign="top">Completed</td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while (!$rs->EOF) {
	$nRecCount++;

	// Set row class and style
	$tbl_session->CssClass = "ewTableRow";
	$tbl_session->CssStyle = "";

	// Get the field contents
	LoadRowValues($rs);

	// Render row value
	$tbl_session->RowType = EW_ROWTYPE_VIEW; // view
	RenderRow();
?>
	<tr<?php echo $tbl_session->DisplayAttributes() ?>>
	<td width="100"<?php echo $tbl_session->session_date->CellAttributes() ?>>
<div<?php echo $tbl_session->session_date->ViewAttributes() ?>><?php echo $tbl_session->session_date->ViewValue ?></div></td>
		<td width="100"<?php echo $tbl_session->session_number->CellAttributes() ?>>
<div<?php echo $tbl_session->session_number->ViewAttributes() ?>><?php echo $tbl_session->session_number->ViewValue ?></div></td>
		<td width="100"<?php echo $tbl_session->session_goal->CellAttributes() ?>>
<div<?php echo $tbl_session->session_goal->ViewAttributes() ?>><?php echo $tbl_session->session_goal->ViewValue ?></div></td>
		<td width="100"<?php echo $tbl_session->session_goal_completed->CellAttributes() ?>>
<div<?php echo $tbl_session->session_goal_completed->ViewAttributes() ?>><?php echo $tbl_session->session_goal_completed->ViewValue ?></div></td>
		<td width="100"<?php echo $tbl_session->session_homework->CellAttributes() ?>>
<div<?php echo $tbl_session->session_homework->ViewAttributes() ?>><?php echo $tbl_session->session_homework->ViewValue ?></div></td>
		<td width="100"<?php echo $tbl_session->session_hmwrk_completed->CellAttributes() ?>>
<div<?php echo $tbl_session->session_hmwrk_completed->ViewAttributes() ?>><?php echo $tbl_session->session_hmwrk_completed->ViewValue ?></div></td>
	</tr>
<?php
	$rs->MoveNext();
}
$rs->Close();
?>
</table>
<p>
<input type="submit" name="Action" id="Action" value="Confirm Delete">
</form>
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

// ------------------------------------------------
//  Function DeleteRows
//  - Delete Records based on current filter
function DeleteRows() {
	global $conn, $Security, $tbl_session;
	$DeleteRows = TRUE;
	$sWrkFilter = $tbl_session->CurrentFilter;

	// Set up filter (Sql Where Clause) and get Return Sql
	// Sql constructor in tbl_session class, tbl_sessioninfo.php

	$tbl_session->CurrentFilter = $sWrkFilter;
	$sSql = $tbl_session->SQL();
	$conn->raiseErrorFn = 'ew_ErrorFn';
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';
	if ($rs === FALSE) {
		return FALSE;
	} elseif ($rs->EOF) {
		$_SESSION[EW_SESSION_MESSAGE] = "No records found"; // No record found
		$rs->Close();
		return FALSE;
	}
	$conn->BeginTrans();

	// Clone old rows
	$rsold = ($rs) ? $rs->GetRows() : array();
	if ($rs) $rs->Close();

	// Call row deleting event
	if ($DeleteRows) {
		foreach ($rsold as $row) {
			$DeleteRows = $tbl_session->Row_Deleting($row);
			if (!$DeleteRows) break;
		}
	}
	if ($DeleteRows) {
		$sKey = "";
		foreach ($rsold as $row) {
			$sThisKey = "";
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['sessionid'];
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$DeleteRows = $conn->Execute($tbl_session->DeleteSQL($row)); // Delete
			$conn->raiseErrorFn = '';
			if ($DeleteRows === FALSE)
				break;
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}
	} else {

		// Set up error message
		if ($tbl_session->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_session->CancelMessage;
			$tbl_session->CancelMessage = "";
		} else {
			$_SESSION[EW_SESSION_MESSAGE] = "Delete cancelled";
		}
	}
	if ($DeleteRows) {
		$conn->CommitTrans(); // Commit the changes
	} else {
		$conn->RollbackTrans(); // Rollback changes
	}

	// Call recordset deleted event
	if ($DeleteRows) {
		foreach ($rsold as $row) {
			$tbl_session->Row_Deleted($row);
		}	
	}
	return $DeleteRows;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_session;

	// Call Recordset Selecting event
	$tbl_session->Recordset_Selecting($tbl_session->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_session->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_session->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_session;
	$sFilter = $tbl_session->SqlKeyFilter();
	if (!is_numeric($tbl_session->sessionid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@sessionid@", ew_AdjustSql($tbl_session->sessionid->CurrentValue), $sFilter); // Replace key value

	// Call Row Selecting event
	$tbl_session->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_session->CurrentFilter = $sFilter;
	$sSql = $tbl_session->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_session->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_session;
	$tbl_session->sessionid->setDbValue($rs->fields('sessionid'));
	$tbl_session->session_date->setDbValue($rs->fields('session_date'));
	$tbl_session->session_number->setDbValue($rs->fields('session_number'));
	$tbl_session->session_goal->setDbValue($rs->fields('session_goal'));
	$tbl_session->session_goal_completed->setDbValue($rs->fields('session_goal_completed'));
	$tbl_session->session_homework->setDbValue($rs->fields('session_homework'));
	$tbl_session->session_hmwrk_completed->setDbValue($rs->fields('session_hmwrk_completed'));
	$tbl_session->s_stuid->setDbValue($rs->fields('s_stuid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_session;

	// Call Row Rendering event
	$tbl_session->Row_Rendering();

	// Common render codes for all row types
	// psat_date

	$tbl_session->session_date->CellCssStyle = "";
	$tbl_session->session_date->CellCssClass = "";

// session_number

	$tbl_session->session_number->CellCssStyle = "";
	$tbl_session->session_number->CellCssClass = "";

	// session_goal
	$tbl_session->session_goal->CellCssStyle = "";
	$tbl_session->session_goal->CellCssClass = "";

	// session_goal_completed
	$tbl_session->session_goal_completed->CellCssStyle = "";
	$tbl_session->session_goal_completed->CellCssClass = "";

	// session_homework
	$tbl_session->session_homework->CellCssStyle = "";
	$tbl_session->session_homework->CellCssClass = "";

	// session_hmwrk_completed
	$tbl_session->session_hmwrk_completed->CellCssStyle = "";
	$tbl_session->session_hmwrk_completed->CellCssClass = "";

	// s_stuid
	$tbl_session->s_stuid->CellCssStyle = "white-space: nowrap;";
	$tbl_session->s_stuid->CellCssClass = "";
	if ($tbl_session->RowType == EW_ROWTYPE_VIEW) { // View row

		// psat_date
		$tbl_session->session_date->ViewValue = $tbl_session->session_date->CurrentValue;
		$tbl_session->session_date->ViewValue = ew_FormatDateTime($tbl_session->session_date->ViewValue, 6);
		$tbl_session->session_date->CssStyle = "";
		$tbl_session->session_date->CssClass = "";
		$tbl_session->session_date->ViewCustomAttributes = "";

		// session_number
		$tbl_session->session_number->ViewValue = $tbl_session->session_number->CurrentValue;
		$tbl_session->session_number->CssStyle = "";
		$tbl_session->session_number->CssClass = "";
		$tbl_session->session_number->ViewCustomAttributes = "";

		// session_goal
		$tbl_session->session_goal->ViewValue = $tbl_session->session_goal->CurrentValue;
		$tbl_session->session_goal->CssStyle = "";
		$tbl_session->session_goal->CssClass = "";
		$tbl_session->session_goal->ViewCustomAttributes = "";

		// session_goal_completed
		$tbl_session->session_goal_completed->ViewValue = $tbl_session->session_goal_completed->CurrentValue;
		$tbl_session->session_goal_completed->CssStyle = "";
		$tbl_session->session_goal_completed->CssClass = "";
		$tbl_session->session_goal_completed->ViewCustomAttributes = "";

		// session_homework
		$tbl_session->session_homework->ViewValue = $tbl_session->session_homework->CurrentValue;
		$tbl_session->session_homework->CssStyle = "";
		$tbl_session->session_homework->CssClass = "";
		$tbl_session->session_homework->ViewCustomAttributes = "";

		// session_hmwrk_completed
		$tbl_session->session_hmwrk_completed->ViewValue = $tbl_session->session_hmwrk_completed->CurrentValue;
		$tbl_session->session_hmwrk_completed->CssStyle = "";
		$tbl_session->session_hmwrk_completed->CssClass = "";
		$tbl_session->session_hmwrk_completed->ViewCustomAttributes = "";

		// s_stuid
		$tbl_session->s_stuid->ViewValue = $tbl_session->s_stuid->CurrentValue;
		$tbl_session->s_stuid->CssStyle = "";
		$tbl_session->s_stuid->CssClass = "";
		$tbl_session->s_stuid->ViewCustomAttributes = "";

		// session_date
		$tbl_session->session_date->HrefValue = "";
		
		// session_number
		$tbl_session->session_number->HrefValue = "";

		// session_goal
		$tbl_session->session_goal->HrefValue = "";

		// session_goal_completed
		$tbl_session->session_goal_completed->HrefValue = "";

		// session_homework
		$tbl_session->session_homework->HrefValue = "";

		// session_hmwrk_completed
		$tbl_session->session_hmwrk_completed->HrefValue = "";

		// s_stuid
		$tbl_session->s_stuid->HrefValue = "";
	} elseif ($tbl_session->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_session->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_session->Row_Rendered();
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
