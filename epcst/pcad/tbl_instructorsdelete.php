<?php
define("EW_PAGE_ID", "delete", TRUE); // Page ID
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

// Load Key Parameters
$sKey = "";
$bSingleDelete = TRUE; // Initialize as single delete
$arRecKeys = array();
$nKeySelected = 0; // Initialize selected key count
$sFilter = "";
if (@$_GET["i_instructorid"] <> "") {
	$tbl_instructors->i_instructorid->setQueryStringValue($_GET["i_instructorid"]);
	if (!is_numeric($tbl_instructors->i_instructorid->QueryStringValue)) {
		Page_Terminate($tbl_instructors->getReturnUrl()); // Prevent sql injection, exit
	}
	$sKey .= $tbl_instructors->i_instructorid->QueryStringValue;
} else {
	$bSingleDelete = FALSE;
}
if (@$_GET["i_uname"] <> "") {
	$tbl_instructors->i_uname->setQueryStringValue($_GET["i_uname"]);
	if ($sKey <> "") $sKey .= EW_COMPOSITE_KEY_SEPARATOR;
	$sKey .= $tbl_instructors->i_uname->QueryStringValue;
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
if ($nKeySelected <= 0) Page_Terminate($tbl_instructors->getReturnUrl()); // No key specified, exit

// Build filter
foreach ($arRecKeys as $sKey) {
	$sFilter .= "(";
	$arKeyFlds = explode(EW_COMPOSITE_KEY_SEPARATOR, trim($sKey)); // Split key by separator
	if (count($arKeyFlds) <> 2) Page_Terminate($tbl_instructors->getReturnUrl()); // Invalid key, exit

	// Set up key field
	$sKeyFld = $arKeyFlds[0];
	if (!is_numeric($sKeyFld)) {
		Page_Terminate($tbl_instructors->getReturnUrl()); // Prevent sql injection, exit
	}
	$sFilter .= "`i_instructorid`=" . ew_AdjustSql($sKeyFld) . " AND ";

	// Set up key field
	$sKeyFld = $arKeyFlds[1];
	$sFilter .= "`i_uname`='" . ew_AdjustSql($sKeyFld) . "' AND ";
	if (substr($sFilter, -5) == " AND ") $sFilter = substr($sFilter, 0, strlen($sFilter)-5) . ") OR ";
}
if (substr($sFilter, -4) == " OR ") $sFilter = substr($sFilter, 0, strlen($sFilter)-4);

// Set up filter (Sql Where Clause) and get Return Sql
// Sql constructor in tbl_instructors class, tbl_instructorsinfo.php

$tbl_instructors->CurrentFilter = $sFilter;

// Get action
if (@$_POST["a_delete"] <> "") {
	$tbl_instructors->CurrentAction = $_POST["a_delete"];
} else {
	$tbl_instructors->CurrentAction = "I"; // Display record
}
switch ($tbl_instructors->CurrentAction) {
	case "D": // Delete
		$tbl_instructors->SendEmail = TRUE; // Send email on delete success
		if (DeleteRows()) { // delete rows
			$_SESSION[EW_SESSION_MESSAGE] = "Delete Successful"; // Set up success message
			Page_Terminate($tbl_instructors->getReturnUrl()); // Return to caller
		}
}

// Load records for display
$rs = LoadRecordset();
$nTotalRecs = $rs->RecordCount(); // Get record count
if ($nTotalRecs <= 0) { // No record found, exit
	$rs->Close();
	Page_Terminate($tbl_instructors->getReturnUrl()); // Return to caller
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
<p><span class="edge">Delete Instructor<br>
    <br><a href="<?php echo $tbl_instructors->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form action="tbl_instructorsdelete.php" method="post">
<p>
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($arRecKeys as $sKey) { ?>
<input type="hidden" name="key_m[]" id="key_m[]" value="<?php echo ew_HtmlEncode($sKey) ?>">
<?php } ?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top">First Name</td>
		<td valign="top">Last Name</td>
		<td valign="top">E-mail</td>
		<td valign="top">Mobile</td>
		<td valign="top">Username</td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while (!$rs->EOF) {
	$nRecCount++;

	// Set row class and style
	$tbl_instructors->CssClass = "ewTableRow";
	$tbl_instructors->CssStyle = "";

	// Get the field contents
	LoadRowValues($rs);

	// Render row value
	$tbl_instructors->RowType = EW_ROWTYPE_VIEW; // view
	RenderRow();
?>
	<tr<?php echo $tbl_instructors->DisplayAttributes() ?>>
		<td<?php echo $tbl_instructors->i_first_name->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_first_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_first_name->ViewValue ?></div>
</td>
		<td<?php echo $tbl_instructors->i_last_name->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_last_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_last_name->ViewValue ?></div>
</td>
		<td<?php echo $tbl_instructors->i_email->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_email->ViewAttributes() ?>><?php echo $tbl_instructors->i_email->ViewValue ?></div>
</td>
		<td<?php echo $tbl_instructors->i_mobile->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_mobile->ViewAttributes() ?>><?php echo $tbl_instructors->i_mobile->ViewValue ?></div>
</td>
		<td<?php echo $tbl_instructors->i_uname->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_uname->ViewAttributes() ?>><?php echo $tbl_instructors->i_uname->ViewValue ?></div>
</td>
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
	global $conn, $Security, $tbl_instructors;
	$DeleteRows = TRUE;
	$sWrkFilter = $tbl_instructors->CurrentFilter;

	// Set up filter (Sql Where Clause) and get Return Sql
	// Sql constructor in tbl_instructors class, tbl_instructorsinfo.php

	$tbl_instructors->CurrentFilter = $sWrkFilter;
	$sSql = $tbl_instructors->SQL();
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
			$DeleteRows = $tbl_instructors->Row_Deleting($row);
			if (!$DeleteRows) break;
		}
	}
	if ($DeleteRows) {
		$sKey = "";
		foreach ($rsold as $row) {
			$sThisKey = "";
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['i_uname'];
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['i_uname'];
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$DeleteRows = $conn->Execute($tbl_instructors->DeleteSQL($row)); // Delete
			$conn->raiseErrorFn = '';
			if ($DeleteRows === FALSE)
				break;
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}
	} else {

		// Set up error message
		if ($tbl_instructors->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_instructors->CancelMessage;
			$tbl_instructors->CancelMessage = "";
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
			$tbl_instructors->Row_Deleted($row);
		}	
	}
	return $DeleteRows;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_instructors;

	// Call Recordset Selecting event
	$tbl_instructors->Recordset_Selecting($tbl_instructors->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_instructors->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_instructors->Recordset_Selected($rs);
	return $rs;
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

	$tbl_instructors->i_first_name->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_first_name->CellCssClass = "";

	// i_last_name
	$tbl_instructors->i_last_name->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_last_name->CellCssClass = "";

	// i_email
	$tbl_instructors->i_email->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_email->CellCssClass = "";

	// i_mobile
	$tbl_instructors->i_mobile->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_mobile->CellCssClass = "";

	// i_uname
	$tbl_instructors->i_uname->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_uname->CellCssClass = "";
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
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_instructors->Row_Rendered();
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
