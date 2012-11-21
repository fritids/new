<?php
define("EW_PAGE_ID", "delete", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_testing_act', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_testing_actinfo.php" ?>
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
$tbl_testing_act->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_testing_act->Export; // Get export parameter, used in header
$sExportFile = $tbl_testing_act->TableVar; // Get export file, used in header
?>
<?php

// Load Key Parameters
$sKey = "";
$bSingleDelete = TRUE; // Initialize as single delete
$arRecKeys = array();
$nKeySelected = 0; // Initialize selected key count
$sFilter = "";
if (@$_GET["t_actid"] <> "") {
	$tbl_testing_act->t_actid->setQueryStringValue($_GET["t_actid"]);
	if (!is_numeric($tbl_testing_act->t_actid->QueryStringValue)) {
		Page_Terminate($tbl_testing_act->getReturnUrl()); // Prevent sql injection, exit
	}
	$sKey .= $tbl_testing_act->t_actid->QueryStringValue;
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
if ($nKeySelected <= 0) Page_Terminate($tbl_testing_act->getReturnUrl()); // No key specified, exit

// Build filter
foreach ($arRecKeys as $sKey) {
	$sFilter .= "(";

	// Set up key field
	$sKeyFld = $sKey;
	if (!is_numeric($sKeyFld)) {
		Page_Terminate($tbl_testing_act->getReturnUrl()); // Prevent sql injection, exit
	}
	$sFilter .= "`t_actid`=" . ew_AdjustSql($sKeyFld) . " AND ";
	if (substr($sFilter, -5) == " AND ") $sFilter = substr($sFilter, 0, strlen($sFilter)-5) . ") OR ";
}
if (substr($sFilter, -4) == " OR ") $sFilter = substr($sFilter, 0, strlen($sFilter)-4);

// Set up filter (Sql Where Clause) and get Return Sql
// Sql constructor in tbl_testing_act class, tbl_testing_actinfo.php

$tbl_testing_act->CurrentFilter = $sFilter;

// Get action
if (@$_POST["a_delete"] <> "") {
	$tbl_testing_act->CurrentAction = $_POST["a_delete"];
} else {
	$tbl_testing_act->CurrentAction = "I"; // Display record
}
switch ($tbl_testing_act->CurrentAction) {
	case "D": // Delete
		$tbl_testing_act->SendEmail = TRUE; // Send email on delete success
		if (DeleteRows()) { // delete rows
			$_SESSION[EW_SESSION_MESSAGE] = "Delete Successful"; // Set up success message
			Page_Terminate($tbl_testing_act->getReturnUrl()); // Return to caller
		}
}

// Load records for display
$rs = LoadRecordset();
$nTotalRecs = $rs->RecordCount(); // Get record count
if ($nTotalRecs <= 0) { // No record found, exit
	$rs->Close();
	Page_Terminate($tbl_testing_act->getReturnUrl()); // Return to caller
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
<p><span class="edge">Delete Student's Test ACT<br>
    <br><a href="<?php echo $tbl_testing_act->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form action="tbl_testing_actdelete.php" method="post">
<p>
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($arRecKeys as $sKey) { ?>
<input type="hidden" name="key_m[]" id="key_m[]" value="<?php echo ew_HtmlEncode($sKey) ?>">
<?php } ?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td width="100" valign="top">Test Date</td>
		<td width="75" valign="top">English</td>
		<td width="75" valign="top">Math</td>
		<td width="75" valign="top">Reading</td>
		<td width="75" valign="top">Science</td>
		<td width="75" valign="top">Essay</td>
		<td width="120" valign="top">Test Site</td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while (!$rs->EOF) {
	$nRecCount++;

	// Set row class and style
	$tbl_testing_act->CssClass = "ewTableRow";
	$tbl_testing_act->CssStyle = "";

	// Get the field contents
	LoadRowValues($rs);

	// Render row value
	$tbl_testing_act->RowType = EW_ROWTYPE_VIEW; // view
	RenderRow();
?>
	<tr<?php echo $tbl_testing_act->DisplayAttributes() ?>>
		<td width="100"<?php echo $tbl_testing_act->t_act_test_date->CellAttributes() ?>>
<div<?php echo $tbl_testing_act->t_act_test_date->ViewAttributes() ?>><?php echo $tbl_testing_act->t_act_test_date->ViewValue ?></div></td>
		<td width="75"<?php echo $tbl_testing_act->t_act_english->CellAttributes() ?>>
<div<?php echo $tbl_testing_act->t_act_english->ViewAttributes() ?>><?php echo $tbl_testing_act->t_act_english->ViewValue ?></div></td>
		<td width="75"<?php echo $tbl_testing_act->t_act_math->CellAttributes() ?>>
<div<?php echo $tbl_testing_act->t_act_math->ViewAttributes() ?>><?php echo $tbl_testing_act->t_act_math->ViewValue ?></div></td>
		<td width="75"<?php echo $tbl_testing_act->t_act_reading->CellAttributes() ?>>
<div<?php echo $tbl_testing_act->t_act_reading->ViewAttributes() ?>><?php echo $tbl_testing_act->t_act_reading->ViewValue ?></div></td>
		<td width="75"<?php echo $tbl_testing_act->t_act_science->CellAttributes() ?>>
<div<?php echo $tbl_testing_act->t_act_science->ViewAttributes() ?>><?php echo $tbl_testing_act->t_act_science->ViewValue ?></div></td>
		<td width="75"<?php echo $tbl_testing_act->t_act_essay->CellAttributes() ?>>
<div<?php echo $tbl_testing_act->t_act_essay->ViewAttributes() ?>><?php echo $tbl_testing_act->t_act_essay->ViewValue ?></div></td>
		<td width="120"<?php echo $tbl_testing_act->t_act_test_site->CellAttributes() ?>>
<div<?php echo $tbl_testing_act->t_act_test_site->ViewAttributes() ?>><?php echo $tbl_testing_act->t_act_test_site->ViewValue ?></div></td>
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
	global $conn, $Security, $tbl_testing_act;
	$DeleteRows = TRUE;
	$sWrkFilter = $tbl_testing_act->CurrentFilter;

	// Set up filter (Sql Where Clause) and get Return Sql
	// Sql constructor in tbl_testing_act class, tbl_testing_actinfo.php

	$tbl_testing_act->CurrentFilter = $sWrkFilter;
	$sSql = $tbl_testing_act->SQL();
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
			$DeleteRows = $tbl_testing_act->Row_Deleting($row);
			if (!$DeleteRows) break;
		}
	}
	if ($DeleteRows) {
		$sKey = "";
		foreach ($rsold as $row) {
			$sThisKey = "";
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['t_actid'];
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$DeleteRows = $conn->Execute($tbl_testing_act->DeleteSQL($row)); // Delete
			$conn->raiseErrorFn = '';
			if ($DeleteRows === FALSE)
				break;
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}
	} else {

		// Set up error message
		if ($tbl_testing_act->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_testing_act->CancelMessage;
			$tbl_testing_act->CancelMessage = "";
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
			$tbl_testing_act->Row_Deleted($row);
		}	
	}
	return $DeleteRows;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_testing_act;

	// Call Recordset Selecting event
	$tbl_testing_act->Recordset_Selecting($tbl_testing_act->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_testing_act->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_testing_act->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_testing_act;
	$sFilter = $tbl_testing_act->SqlKeyFilter();
	if (!is_numeric($tbl_testing_act->t_actid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@t_actid@", ew_AdjustSql($tbl_testing_act->t_actid->CurrentValue), $sFilter); // Replace key value

	// Call Row Selecting event
	$tbl_testing_act->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_testing_act->CurrentFilter = $sFilter;
	$sSql = $tbl_testing_act->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_testing_act->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_testing_act;
	$tbl_testing_act->t_actid->setDbValue($rs->fields('t_actid'));
	$tbl_testing_act->t_act_test_date->setDbValue($rs->fields('t_act_test_date'));
	$tbl_testing_act->t_act_english->setDbValue($rs->fields('t_act_english'));
	$tbl_testing_act->t_act_math->setDbValue($rs->fields('t_act_math'));
	$tbl_testing_act->t_act_reading->setDbValue($rs->fields('t_act_reading'));
	$tbl_testing_act->t_act_science->setDbValue($rs->fields('t_act_science'));
	$tbl_testing_act->t_act_essay->setDbValue($rs->fields('t_act_essay'));
	$tbl_testing_act->t_act_test_site->setDbValue($rs->fields('t_act_test_site'));
	$tbl_testing_act->s_stuid->setDbValue($rs->fields('s_stuid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_testing_act;

	// Call Row Rendering event
	$tbl_testing_act->Row_Rendering();

	// Common render codes for all row types
	// t_act_test_date

	$tbl_testing_act->t_act_test_date->CellCssStyle = "";
	$tbl_testing_act->t_act_test_date->CellCssClass = "";

	// t_act_english
	$tbl_testing_act->t_act_english->CellCssStyle = "";
	$tbl_testing_act->t_act_english->CellCssClass = "";

	// t_act_math
	$tbl_testing_act->t_act_math->CellCssStyle = "";
	$tbl_testing_act->t_act_math->CellCssClass = "";

	// t_act_reading
	$tbl_testing_act->t_act_reading->CellCssStyle = "";
	$tbl_testing_act->t_act_reading->CellCssClass = "";

	// t_act_science
	$tbl_testing_act->t_act_science->CellCssStyle = "";
	$tbl_testing_act->t_act_science->CellCssClass = "";

	// t_act_essay
	$tbl_testing_act->t_act_essay->CellCssStyle = "";
	$tbl_testing_act->t_act_essay->CellCssClass = "";

	// t_act_test_site
	$tbl_testing_act->t_act_test_site->CellCssStyle = "";
	$tbl_testing_act->t_act_test_site->CellCssClass = "";

	// s_stuid
	$tbl_testing_act->s_stuid->CellCssStyle = "";
	$tbl_testing_act->s_stuid->CellCssClass = "";
	if ($tbl_testing_act->RowType == EW_ROWTYPE_VIEW) { // View row

		// t_act_test_date
		$tbl_testing_act->t_act_test_date->ViewValue = $tbl_testing_act->t_act_test_date->CurrentValue;
		$tbl_testing_act->t_act_test_date->ViewValue = ew_FormatDateTime($tbl_testing_act->t_act_test_date->ViewValue, 6);
		$tbl_testing_act->t_act_test_date->CssStyle = "";
		$tbl_testing_act->t_act_test_date->CssClass = "";
		$tbl_testing_act->t_act_test_date->ViewCustomAttributes = "";

		// t_act_english
		$tbl_testing_act->t_act_english->ViewValue = $tbl_testing_act->t_act_english->CurrentValue;
		$tbl_testing_act->t_act_english->CssStyle = "";
		$tbl_testing_act->t_act_english->CssClass = "";
		$tbl_testing_act->t_act_english->ViewCustomAttributes = "";

		// t_act_math
		$tbl_testing_act->t_act_math->ViewValue = $tbl_testing_act->t_act_math->CurrentValue;
		$tbl_testing_act->t_act_math->CssStyle = "";
		$tbl_testing_act->t_act_math->CssClass = "";
		$tbl_testing_act->t_act_math->ViewCustomAttributes = "";

		// t_act_reading
		$tbl_testing_act->t_act_reading->ViewValue = $tbl_testing_act->t_act_reading->CurrentValue;
		$tbl_testing_act->t_act_reading->CssStyle = "";
		$tbl_testing_act->t_act_reading->CssClass = "";
		$tbl_testing_act->t_act_reading->ViewCustomAttributes = "";

		// t_act_science
		$tbl_testing_act->t_act_science->ViewValue = $tbl_testing_act->t_act_science->CurrentValue;
		$tbl_testing_act->t_act_science->CssStyle = "";
		$tbl_testing_act->t_act_science->CssClass = "";
		$tbl_testing_act->t_act_science->ViewCustomAttributes = "";

		// t_act_essay
		$tbl_testing_act->t_act_essay->ViewValue = $tbl_testing_act->t_act_essay->CurrentValue;
		$tbl_testing_act->t_act_essay->CssStyle = "";
		$tbl_testing_act->t_act_essay->CssClass = "";
		$tbl_testing_act->t_act_essay->ViewCustomAttributes = "";

		// t_act_test_site
		$tbl_testing_act->t_act_test_site->ViewValue = $tbl_testing_act->t_act_test_site->CurrentValue;
		$tbl_testing_act->t_act_test_site->CssStyle = "";
		$tbl_testing_act->t_act_test_site->CssClass = "";
		$tbl_testing_act->t_act_test_site->ViewCustomAttributes = "";

		// s_stuid
		$tbl_testing_act->s_stuid->ViewValue = $tbl_testing_act->s_stuid->CurrentValue;
		$tbl_testing_act->s_stuid->CssStyle = "";
		$tbl_testing_act->s_stuid->CssClass = "";
		$tbl_testing_act->s_stuid->ViewCustomAttributes = "";

		// t_act_test_date
		$tbl_testing_act->t_act_test_date->HrefValue = "";

		// t_act_english
		$tbl_testing_act->t_act_english->HrefValue = "";

		// t_act_math
		$tbl_testing_act->t_act_math->HrefValue = "";

		// t_act_reading
		$tbl_testing_act->t_act_reading->HrefValue = "";

		// t_act_science
		$tbl_testing_act->t_act_science->HrefValue = "";

		// t_act_essay
		$tbl_testing_act->t_act_essay->HrefValue = "";

		// t_act_test_site
		$tbl_testing_act->t_act_test_site->HrefValue = "";

		// s_stuid
		$tbl_testing_act->s_stuid->HrefValue = "";
	} elseif ($tbl_testing_act->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_testing_act->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_testing_act->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_testing_act->Row_Rendered();
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
