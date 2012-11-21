<?php
define("EW_PAGE_ID", "delete", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_prep_programs', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_prep_programsinfo.php" ?>
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
$tbl_prep_programs->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_prep_programs->Export; // Get export parameter, used in header
$sExportFile = $tbl_prep_programs->TableVar; // Get export file, used in header
?>
<?php

// Load Key Parameters
$sKey = "";
$bSingleDelete = TRUE; // Initialize as single delete
$arRecKeys = array();
$nKeySelected = 0; // Initialize selected key count
$sFilter = "";
if (@$_GET["p_prepid"] <> "") {
	$tbl_prep_programs->p_prepid->setQueryStringValue($_GET["p_prepid"]);
	if (!is_numeric($tbl_prep_programs->p_prepid->QueryStringValue)) {
		Page_Terminate($tbl_prep_programs->getReturnUrl()); // Prevent sql injection, exit
	}
	$sKey .= $tbl_prep_programs->p_prepid->QueryStringValue;
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
if ($nKeySelected <= 0) Page_Terminate($tbl_prep_programs->getReturnUrl()); // No key specified, exit

// Build filter
foreach ($arRecKeys as $sKey) {
	$sFilter .= "(";

	// Set up key field
	$sKeyFld = $sKey;
	if (!is_numeric($sKeyFld)) {
		Page_Terminate($tbl_prep_programs->getReturnUrl()); // Prevent sql injection, exit
	}
	$sFilter .= "`p_prepid`=" . ew_AdjustSql($sKeyFld) . " AND ";
	if (substr($sFilter, -5) == " AND ") $sFilter = substr($sFilter, 0, strlen($sFilter)-5) . ") OR ";
}
if (substr($sFilter, -4) == " OR ") $sFilter = substr($sFilter, 0, strlen($sFilter)-4);

// Set up filter (Sql Where Clause) and get Return Sql
// Sql constructor in tbl_prep_programs class, tbl_prep_programsinfo.php

$tbl_prep_programs->CurrentFilter = $sFilter;

// Get action
if (@$_POST["a_delete"] <> "") {
	$tbl_prep_programs->CurrentAction = $_POST["a_delete"];
} else {
	$tbl_prep_programs->CurrentAction = "I"; // Display record
}
switch ($tbl_prep_programs->CurrentAction) {
	case "D": // Delete
		$tbl_prep_programs->SendEmail = TRUE; // Send email on delete success
		if (DeleteRows()) { // delete rows
			$_SESSION[EW_SESSION_MESSAGE] = "Delete Successful"; // Set up success message
			Page_Terminate($tbl_prep_programs->getReturnUrl()); // Return to caller
		}
}

// Load records for display
$rs = LoadRecordset();
$nTotalRecs = $rs->RecordCount(); // Get record count
if ($nTotalRecs <= 0) { // No record found, exit
	$rs->Close();
	Page_Terminate($tbl_prep_programs->getReturnUrl()); // Return to caller
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
<p><span class="edge">Delete Student's Prep Programs<br>
    <br><a href="<?php echo $tbl_prep_programs->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form action="tbl_prep_programsdelete.php" method="post">
<p>
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($arRecKeys as $sKey) { ?>
<input type="hidden" name="key_m[]" id="key_m[]" value="<?php echo ew_HtmlEncode($sKey) ?>">
<?php } ?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top">Arithmetic</td>
		<td valign="top">Algebra</td>
		<td valign="top">Techniques</td>
		<td valign="top">Geometry</td>
		<td valign="top">Advance Topics</td>
		<td valign="top">Sentence Completion</td>
		<td valign="top">Critical Reading</td>
		<td valign="top">Error ID</td>
		<td valign="top">Sentence Improvement</td>
		<td valign="top">Paragraph Improvement</td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while (!$rs->EOF) {
	$nRecCount++;

	// Set row class and style
	$tbl_prep_programs->CssClass = "ewTableRow";
	$tbl_prep_programs->CssStyle = "";

	// Get the field contents
	LoadRowValues($rs);

	// Render row value
	$tbl_prep_programs->RowType = EW_ROWTYPE_VIEW; // view
	RenderRow();
?>
	<tr<?php echo $tbl_prep_programs->DisplayAttributes() ?>>
		<td<?php echo $tbl_prep_programs->p_arithmetic->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_arithmetic->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_arithmetic->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_algebra->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_algebra->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_algebra->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_techniques->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_techniques->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_techniques->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_geometry->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_geometry->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_geometry->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_advanced_topics->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_advanced_topics->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_advanced_topics->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_sentence_completion->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_sentence_completion->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_sentence_completion->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_critical_reading->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_critical_reading->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_critical_reading->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_error_id->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_error_id->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_error_id->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_sentence_improvement->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_sentence_improvement->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_sentence_improvement->ViewValue ?></div></td>
		<td<?php echo $tbl_prep_programs->p_paragraph_improvement->CellAttributes() ?>>
<div<?php echo $tbl_prep_programs->p_paragraph_improvement->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_paragraph_improvement->ViewValue ?></div></td>
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
	global $conn, $Security, $tbl_prep_programs;
	$DeleteRows = TRUE;
	$sWrkFilter = $tbl_prep_programs->CurrentFilter;

	// Set up filter (Sql Where Clause) and get Return Sql
	// Sql constructor in tbl_prep_programs class, tbl_prep_programsinfo.php

	$tbl_prep_programs->CurrentFilter = $sWrkFilter;
	$sSql = $tbl_prep_programs->SQL();
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
			$DeleteRows = $tbl_prep_programs->Row_Deleting($row);
			if (!$DeleteRows) break;
		}
	}
	if ($DeleteRows) {
		$sKey = "";
		foreach ($rsold as $row) {
			$sThisKey = "";
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['p_prepid'];
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$DeleteRows = $conn->Execute($tbl_prep_programs->DeleteSQL($row)); // Delete
			$conn->raiseErrorFn = '';
			if ($DeleteRows === FALSE)
				break;
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}
	} else {

		// Set up error message
		if ($tbl_prep_programs->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_prep_programs->CancelMessage;
			$tbl_prep_programs->CancelMessage = "";
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
			$tbl_prep_programs->Row_Deleted($row);
		}	
	}
	return $DeleteRows;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_prep_programs;

	// Call Recordset Selecting event
	$tbl_prep_programs->Recordset_Selecting($tbl_prep_programs->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_prep_programs->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_prep_programs->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_prep_programs;
	$sFilter = $tbl_prep_programs->SqlKeyFilter();
	if (!is_numeric($tbl_prep_programs->p_prepid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@p_prepid@", ew_AdjustSql($tbl_prep_programs->p_prepid->CurrentValue), $sFilter); // Replace key value

	// Call Row Selecting event
	$tbl_prep_programs->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_prep_programs->CurrentFilter = $sFilter;
	$sSql = $tbl_prep_programs->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_prep_programs->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_prep_programs;
	$tbl_prep_programs->p_prepid->setDbValue($rs->fields('p_prepid'));
	$tbl_prep_programs->p_arithmetic->setDbValue($rs->fields('p_arithmetic'));
	$tbl_prep_programs->p_algebra->setDbValue($rs->fields('p_algebra'));
	$tbl_prep_programs->p_techniques->setDbValue($rs->fields('p_techniques'));
	$tbl_prep_programs->p_geometry->setDbValue($rs->fields('p_geometry'));
	$tbl_prep_programs->p_advanced_topics->setDbValue($rs->fields('p_advanced_topics'));
	$tbl_prep_programs->p_sentence_completion->setDbValue($rs->fields('p_sentence_completion'));
	$tbl_prep_programs->p_critical_reading->setDbValue($rs->fields('p_critical_reading'));
	$tbl_prep_programs->p_error_id->setDbValue($rs->fields('p_error_id'));
	$tbl_prep_programs->p_sentence_improvement->setDbValue($rs->fields('p_sentence_improvement'));
	$tbl_prep_programs->p_paragraph_improvement->setDbValue($rs->fields('p_paragraph_improvement'));
	$tbl_prep_programs->s_stuid->setDbValue($rs->fields('s_stuid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_prep_programs;

	// Call Row Rendering event
	$tbl_prep_programs->Row_Rendering();

	// Common render codes for all row types
	// p_prepid

	$tbl_prep_programs->p_prepid->CellCssStyle = "";
	$tbl_prep_programs->p_prepid->CellCssClass = "";

	// p_arithmetic
	$tbl_prep_programs->p_arithmetic->CellCssStyle = "";
	$tbl_prep_programs->p_arithmetic->CellCssClass = "";

	// p_algebra
	$tbl_prep_programs->p_algebra->CellCssStyle = "";
	$tbl_prep_programs->p_algebra->CellCssClass = "";

	// p_techniques
	$tbl_prep_programs->p_techniques->CellCssStyle = "";
	$tbl_prep_programs->p_techniques->CellCssClass = "";

	// p_geometry
	$tbl_prep_programs->p_geometry->CellCssStyle = "";
	$tbl_prep_programs->p_geometry->CellCssClass = "";

	// p_advanced_topics
	$tbl_prep_programs->p_advanced_topics->CellCssStyle = "";
	$tbl_prep_programs->p_advanced_topics->CellCssClass = "";

	// p_sentence_completion
	$tbl_prep_programs->p_sentence_completion->CellCssStyle = "";
	$tbl_prep_programs->p_sentence_completion->CellCssClass = "";

	// p_critical_reading
	$tbl_prep_programs->p_critical_reading->CellCssStyle = "";
	$tbl_prep_programs->p_critical_reading->CellCssClass = "";

	// p_error_id
	$tbl_prep_programs->p_error_id->CellCssStyle = "";
	$tbl_prep_programs->p_error_id->CellCssClass = "";

	// p_sentence_improvement
	$tbl_prep_programs->p_sentence_improvement->CellCssStyle = "";
	$tbl_prep_programs->p_sentence_improvement->CellCssClass = "";

	// p_paragraph_improvement
	$tbl_prep_programs->p_paragraph_improvement->CellCssStyle = "";
	$tbl_prep_programs->p_paragraph_improvement->CellCssClass = "";

	// s_stuid
	$tbl_prep_programs->s_stuid->CellCssStyle = "";
	$tbl_prep_programs->s_stuid->CellCssClass = "";
	if ($tbl_prep_programs->RowType == EW_ROWTYPE_VIEW) { // View row

		// p_prepid
		$tbl_prep_programs->p_prepid->ViewValue = $tbl_prep_programs->p_prepid->CurrentValue;
		$tbl_prep_programs->p_prepid->CssStyle = "";
		$tbl_prep_programs->p_prepid->CssClass = "";
		$tbl_prep_programs->p_prepid->ViewCustomAttributes = "";

		// p_arithmetic
		$tbl_prep_programs->p_arithmetic->ViewValue = $tbl_prep_programs->p_arithmetic->CurrentValue;
		$tbl_prep_programs->p_arithmetic->CssStyle = "";
		$tbl_prep_programs->p_arithmetic->CssClass = "";
		$tbl_prep_programs->p_arithmetic->ViewCustomAttributes = "";

		// p_algebra
		$tbl_prep_programs->p_algebra->ViewValue = $tbl_prep_programs->p_algebra->CurrentValue;
		$tbl_prep_programs->p_algebra->CssStyle = "";
		$tbl_prep_programs->p_algebra->CssClass = "";
		$tbl_prep_programs->p_algebra->ViewCustomAttributes = "";

		// p_techniques
		$tbl_prep_programs->p_techniques->ViewValue = $tbl_prep_programs->p_techniques->CurrentValue;
		$tbl_prep_programs->p_techniques->CssStyle = "";
		$tbl_prep_programs->p_techniques->CssClass = "";
		$tbl_prep_programs->p_techniques->ViewCustomAttributes = "";

		// p_geometry
		$tbl_prep_programs->p_geometry->ViewValue = $tbl_prep_programs->p_geometry->CurrentValue;
		$tbl_prep_programs->p_geometry->CssStyle = "";
		$tbl_prep_programs->p_geometry->CssClass = "";
		$tbl_prep_programs->p_geometry->ViewCustomAttributes = "";

		// p_advanced_topics
		$tbl_prep_programs->p_advanced_topics->ViewValue = $tbl_prep_programs->p_advanced_topics->CurrentValue;
		$tbl_prep_programs->p_advanced_topics->CssStyle = "";
		$tbl_prep_programs->p_advanced_topics->CssClass = "";
		$tbl_prep_programs->p_advanced_topics->ViewCustomAttributes = "";

		// p_sentence_completion
		$tbl_prep_programs->p_sentence_completion->ViewValue = $tbl_prep_programs->p_sentence_completion->CurrentValue;
		$tbl_prep_programs->p_sentence_completion->CssStyle = "";
		$tbl_prep_programs->p_sentence_completion->CssClass = "";
		$tbl_prep_programs->p_sentence_completion->ViewCustomAttributes = "";

		// p_critical_reading
		$tbl_prep_programs->p_critical_reading->ViewValue = $tbl_prep_programs->p_critical_reading->CurrentValue;
		$tbl_prep_programs->p_critical_reading->CssStyle = "";
		$tbl_prep_programs->p_critical_reading->CssClass = "";
		$tbl_prep_programs->p_critical_reading->ViewCustomAttributes = "";

		// p_error_id
		$tbl_prep_programs->p_error_id->ViewValue = $tbl_prep_programs->p_error_id->CurrentValue;
		$tbl_prep_programs->p_error_id->CssStyle = "";
		$tbl_prep_programs->p_error_id->CssClass = "";
		$tbl_prep_programs->p_error_id->ViewCustomAttributes = "";

		// p_sentence_improvement
		$tbl_prep_programs->p_sentence_improvement->ViewValue = $tbl_prep_programs->p_sentence_improvement->CurrentValue;
		$tbl_prep_programs->p_sentence_improvement->CssStyle = "";
		$tbl_prep_programs->p_sentence_improvement->CssClass = "";
		$tbl_prep_programs->p_sentence_improvement->ViewCustomAttributes = "";

		// p_paragraph_improvement
		$tbl_prep_programs->p_paragraph_improvement->ViewValue = $tbl_prep_programs->p_paragraph_improvement->CurrentValue;
		$tbl_prep_programs->p_paragraph_improvement->CssStyle = "";
		$tbl_prep_programs->p_paragraph_improvement->CssClass = "";
		$tbl_prep_programs->p_paragraph_improvement->ViewCustomAttributes = "";

		// s_stuid
		$tbl_prep_programs->s_stuid->ViewValue = $tbl_prep_programs->s_stuid->CurrentValue;
		$tbl_prep_programs->s_stuid->CssStyle = "";
		$tbl_prep_programs->s_stuid->CssClass = "";
		$tbl_prep_programs->s_stuid->ViewCustomAttributes = "";

		// p_prepid
		$tbl_prep_programs->p_prepid->HrefValue = "";

		// p_arithmetic
		$tbl_prep_programs->p_arithmetic->HrefValue = "";

		// p_algebra
		$tbl_prep_programs->p_algebra->HrefValue = "";

		// p_techniques
		$tbl_prep_programs->p_techniques->HrefValue = "";

		// p_geometry
		$tbl_prep_programs->p_geometry->HrefValue = "";

		// p_advanced_topics
		$tbl_prep_programs->p_advanced_topics->HrefValue = "";

		// p_sentence_completion
		$tbl_prep_programs->p_sentence_completion->HrefValue = "";

		// p_critical_reading
		$tbl_prep_programs->p_critical_reading->HrefValue = "";

		// p_error_id
		$tbl_prep_programs->p_error_id->HrefValue = "";

		// p_sentence_improvement
		$tbl_prep_programs->p_sentence_improvement->HrefValue = "";

		// p_paragraph_improvement
		$tbl_prep_programs->p_paragraph_improvement->HrefValue = "";

		// s_stuid
		$tbl_prep_programs->s_stuid->HrefValue = "";
	} elseif ($tbl_prep_programs->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_prep_programs->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_prep_programs->Row_Rendered();
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
