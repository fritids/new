<?php
define("EW_PAGE_ID", "delete", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_psat', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_psatinfo.php" ?>
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
$tbl_psat->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_psat->Export; // Get export parameter, used in header
$sExportFile = $tbl_psat->TableVar; // Get export file, used in header
?>
<?php

// Load Key Parameters
$sKey = "";
$bSingleDelete = TRUE; // Initialize as single delete
$arRecKeys = array();
$nKeySelected = 0; // Initialize selected key count
$sFilter = "";
if (@$_GET["psatid"] <> "") {
	$tbl_psat->psatid->setQueryStringValue($_GET["psatid"]);
	if (!is_numeric($tbl_psat->psatid->QueryStringValue)) {
		Page_Terminate($tbl_psat->getReturnUrl()); // Prevent sql injection, exit
	}
	$sKey .= $tbl_psat->psatid->QueryStringValue;
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
if ($nKeySelected <= 0) Page_Terminate($tbl_psat->getReturnUrl()); // No key specified, exit

// Build filter
foreach ($arRecKeys as $sKey) {
	$sFilter .= "(";

	// Set up key field
	$sKeyFld = $sKey;
	if (!is_numeric($sKeyFld)) {
		Page_Terminate($tbl_psat->getReturnUrl()); // Prevent sql injection, exit
	}
	$sFilter .= "`psatid`=" . ew_AdjustSql($sKeyFld) . " AND ";
	if (substr($sFilter, -5) == " AND ") $sFilter = substr($sFilter, 0, strlen($sFilter)-5) . ") OR ";
}
if (substr($sFilter, -4) == " OR ") $sFilter = substr($sFilter, 0, strlen($sFilter)-4);

// Set up filter (Sql Where Clause) and get Return Sql
// Sql constructor in tbl_psat class, tbl_psatinfo.php

$tbl_psat->CurrentFilter = $sFilter;

// Get action
if (@$_POST["a_delete"] <> "") {
	$tbl_psat->CurrentAction = $_POST["a_delete"];
} else {
	$tbl_psat->CurrentAction = "I"; // Display record
}
switch ($tbl_psat->CurrentAction) {
	case "D": // Delete
		$tbl_psat->SendEmail = TRUE; // Send email on delete success
		if (DeleteRows()) { // delete rows
			$_SESSION[EW_SESSION_MESSAGE] = "Delete Successful"; // Set up success message
			Page_Terminate($tbl_psat->getReturnUrl()); // Return to caller
		}
}

// Load records for display
$rs = LoadRecordset();
$nTotalRecs = $rs->RecordCount(); // Get record count
if ($nTotalRecs <= 0) { // No record found, exit
	$rs->Close();
	Page_Terminate($tbl_psat->getReturnUrl()); // Return to caller
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
<p><span class="edge">Delete Student's PSAT<br>
    <br><a href="<?php echo $tbl_psat->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form action="tbl_psatdelete.php" method="post">
<p>
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($arRecKeys as $sKey) { ?>
<input type="hidden" name="key_m[]" id="key_m[]" value="<?php echo ew_HtmlEncode($sKey) ?>">
<?php } ?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td width="100" valign="top">Test Date</td>
		<td width="75" valign="top">Reading</td>
		<td width="75" valign="top">Math</td>
		<td width="75" valign="top">Writing</td>
		<td width="120" valign="top">Test Site</td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while (!$rs->EOF) {
	$nRecCount++;

	// Set row class and style
	$tbl_psat->CssClass = "ewTableRow";
	$tbl_psat->CssStyle = "";

	// Get the field contents
	LoadRowValues($rs);

	// Render row value
	$tbl_psat->RowType = EW_ROWTYPE_VIEW; // view
	RenderRow();
?>
	<tr<?php echo $tbl_psat->DisplayAttributes() ?>>
		<td width="100"<?php echo $tbl_psat->psat_date->CellAttributes() ?>>
<div<?php echo $tbl_psat->psat_date->ViewAttributes() ?>><?php echo $tbl_psat->psat_date->ViewValue ?></div></td>
		<td width="75"<?php echo $tbl_psat->psat_reading->CellAttributes() ?>>
<div<?php echo $tbl_psat->psat_reading->ViewAttributes() ?>><?php echo $tbl_psat->psat_reading->ViewValue ?></div></td>
		<td width="75"<?php echo $tbl_psat->psat_math->CellAttributes() ?>>
<div<?php echo $tbl_psat->psat_math->ViewAttributes() ?>><?php echo $tbl_psat->psat_math->ViewValue ?></div></td>
		<td width="75"<?php echo $tbl_psat->psat_writing->CellAttributes() ?>>
<div<?php echo $tbl_psat->psat_writing->ViewAttributes() ?>><?php echo $tbl_psat->psat_writing->ViewValue ?></div></td>
		<td width="120"<?php echo $tbl_psat->psat_test_site->CellAttributes() ?>>
<div<?php echo $tbl_psat->psat_test_site->ViewAttributes() ?>><?php echo $tbl_psat->psat_test_site->ViewValue ?></div></td>
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
	global $conn, $Security, $tbl_psat;
	$DeleteRows = TRUE;
	$sWrkFilter = $tbl_psat->CurrentFilter;

	// Set up filter (Sql Where Clause) and get Return Sql
	// Sql constructor in tbl_psat class, tbl_psatinfo.php

	$tbl_psat->CurrentFilter = $sWrkFilter;
	$sSql = $tbl_psat->SQL();
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
			$DeleteRows = $tbl_psat->Row_Deleting($row);
			if (!$DeleteRows) break;
		}
	}
	if ($DeleteRows) {
		$sKey = "";
		foreach ($rsold as $row) {
			$sThisKey = "";
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['psatid'];
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$DeleteRows = $conn->Execute($tbl_psat->DeleteSQL($row)); // Delete
			$conn->raiseErrorFn = '';
			if ($DeleteRows === FALSE)
				break;
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}
	} else {

		// Set up error message
		if ($tbl_psat->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_psat->CancelMessage;
			$tbl_psat->CancelMessage = "";
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
			$tbl_psat->Row_Deleted($row);
		}	
	}
	return $DeleteRows;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_psat;

	// Call Recordset Selecting event
	$tbl_psat->Recordset_Selecting($tbl_psat->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_psat->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_psat->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_psat;
	$sFilter = $tbl_psat->SqlKeyFilter();
	if (!is_numeric($tbl_psat->psatid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@psatid@", ew_AdjustSql($tbl_psat->psatid->CurrentValue), $sFilter); // Replace key value

	// Call Row Selecting event
	$tbl_psat->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_psat->CurrentFilter = $sFilter;
	$sSql = $tbl_psat->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_psat->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_psat;
	$tbl_psat->psatid->setDbValue($rs->fields('psatid'));
	$tbl_psat->psat_date->setDbValue($rs->fields('psat_date'));
	$tbl_psat->psat_reading->setDbValue($rs->fields('psat_reading'));
	$tbl_psat->psat_math->setDbValue($rs->fields('psat_math'));
	$tbl_psat->psat_writing->setDbValue($rs->fields('psat_writing'));
	$tbl_psat->psat_test_site->setDbValue($rs->fields('psat_test_site'));
	$tbl_psat->s_stuid->setDbValue($rs->fields('s_stuid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_psat;

	// Call Row Rendering event
	$tbl_psat->Row_Rendering();

	// Common render codes for all row types
	// psat_date

	$tbl_psat->psat_date->CellCssStyle = "";
	$tbl_psat->psat_date->CellCssClass = "";

	// psat_reading
	$tbl_psat->psat_reading->CellCssStyle = "";
	$tbl_psat->psat_reading->CellCssClass = "";

	// psat_math
	$tbl_psat->psat_math->CellCssStyle = "";
	$tbl_psat->psat_math->CellCssClass = "";

	// psat_writing
	$tbl_psat->psat_writing->CellCssStyle = "";
	$tbl_psat->psat_writing->CellCssClass = "";

	// psat_test_site
	$tbl_psat->psat_test_site->CellCssStyle = "";
	$tbl_psat->psat_test_site->CellCssClass = "";

	// s_stuid
	$tbl_psat->s_stuid->CellCssStyle = "";
	$tbl_psat->s_stuid->CellCssClass = "";
	if ($tbl_psat->RowType == EW_ROWTYPE_VIEW) { // View row

		// psat_date
		$tbl_psat->psat_date->ViewValue = $tbl_psat->psat_date->CurrentValue;
		$tbl_psat->psat_date->ViewValue = ew_FormatDateTime($tbl_psat->psat_date->ViewValue, 6);
		$tbl_psat->psat_date->CssStyle = "";
		$tbl_psat->psat_date->CssClass = "";
		$tbl_psat->psat_date->ViewCustomAttributes = "";

		// psat_reading
		$tbl_psat->psat_reading->ViewValue = $tbl_psat->psat_reading->CurrentValue;
		$tbl_psat->psat_reading->CssStyle = "";
		$tbl_psat->psat_reading->CssClass = "";
		$tbl_psat->psat_reading->ViewCustomAttributes = "";

		// psat_math
		$tbl_psat->psat_math->ViewValue = $tbl_psat->psat_math->CurrentValue;
		$tbl_psat->psat_math->CssStyle = "";
		$tbl_psat->psat_math->CssClass = "";
		$tbl_psat->psat_math->ViewCustomAttributes = "";

		// psat_writing
		$tbl_psat->psat_writing->ViewValue = $tbl_psat->psat_writing->CurrentValue;
		$tbl_psat->psat_writing->CssStyle = "";
		$tbl_psat->psat_writing->CssClass = "";
		$tbl_psat->psat_writing->ViewCustomAttributes = "";

		// psat_test_site
		$tbl_psat->psat_test_site->ViewValue = $tbl_psat->psat_test_site->CurrentValue;
		$tbl_psat->psat_test_site->CssStyle = "";
		$tbl_psat->psat_test_site->CssClass = "";
		$tbl_psat->psat_test_site->ViewCustomAttributes = "";

		// s_stuid
		$tbl_psat->s_stuid->ViewValue = $tbl_psat->s_stuid->CurrentValue;
		$tbl_psat->s_stuid->CssStyle = "";
		$tbl_psat->s_stuid->CssClass = "";
		$tbl_psat->s_stuid->ViewCustomAttributes = "";

		// psat_date
		$tbl_psat->psat_date->HrefValue = "";

		// psat_reading
		$tbl_psat->psat_reading->HrefValue = "";

		// psat_math
		$tbl_psat->psat_math->HrefValue = "";

		// psat_writing
		$tbl_psat->psat_writing->HrefValue = "";

		// psat_test_site
		$tbl_psat->psat_test_site->HrefValue = "";

		// s_stuid
		$tbl_psat->s_stuid->HrefValue = "";
	} elseif ($tbl_psat->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_psat->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_psat->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_psat->Row_Rendered();
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
