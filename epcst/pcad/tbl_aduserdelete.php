<?php
define("EW_PAGE_ID", "delete", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_aduser', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_aduserinfo.php" ?>
<?php include "userfn50.php" ?>
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
$tbl_aduser->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_aduser->Export; // Get export parameter, used in header
$sExportFile = $tbl_aduser->TableVar; // Get export file, used in header
?>
<?php

// Load Key Parameters
$sKey = "";
$bSingleDelete = TRUE; // Initialize as single delete
$arRecKeys = array();
$nKeySelected = 0; // Initialize selected key count
$sFilter = "";
if (@$_GET["a_uname"] <> "") {
	$tbl_aduser->a_uname->setQueryStringValue($_GET["a_uname"]);
	$sKey .= $tbl_aduser->a_uname->QueryStringValue;
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
if ($nKeySelected <= 0) Page_Terminate($tbl_aduser->getReturnUrl()); // No key specified, exit

// Build filter
foreach ($arRecKeys as $sKey) {
	$sFilter .= "(";

	// Set up key field
	$sKeyFld = $sKey;
	$sFilter .= "`a_uname`='" . ew_AdjustSql($sKeyFld) . "' AND ";
	if (substr($sFilter, -5) == " AND ") $sFilter = substr($sFilter, 0, strlen($sFilter)-5) . ") OR ";
}
if (substr($sFilter, -4) == " OR ") $sFilter = substr($sFilter, 0, strlen($sFilter)-4);

// Set up filter (Sql Where Clause) and get Return Sql
// Sql constructor in tbl_aduser class, tbl_aduserinfo.php

$tbl_aduser->CurrentFilter = $sFilter;

// Get action
if (@$_POST["a_delete"] <> "") {
	$tbl_aduser->CurrentAction = $_POST["a_delete"];
} else {
	$tbl_aduser->CurrentAction = "I"; // Display record
}
switch ($tbl_aduser->CurrentAction) {
	case "D": // Delete
		$tbl_aduser->SendEmail = TRUE; // Send email on delete success
		if (DeleteRows()) { // delete rows
			$_SESSION[EW_SESSION_MESSAGE] = "Delete Successful"; // Set up success message
			Page_Terminate($tbl_aduser->getReturnUrl()); // Return to caller
		}
}

// Load records for display
$rs = LoadRecordset();
$nTotalRecs = $rs->RecordCount(); // Get record count
if ($nTotalRecs <= 0) { // No record found, exit
	$rs->Close();
	Page_Terminate($tbl_aduser->getReturnUrl()); // Return to caller
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
<p><span class="edge">Delete Admin<br>
  <br><a href="<?php echo $tbl_aduser->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form action="tbl_aduserdelete.php" method="post">
<p>
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($arRecKeys as $sKey) { ?>
<input type="hidden" name="key_m[]" id="key_m[]" value="<?php echo ew_HtmlEncode($sKey) ?>">
<?php } ?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top">Username</td>
		<td valign="top">First Name</td>
		<td valign="top">Last Name</td>
		<td valign="top">E-mail</td>
		<td valign="top">Mobile</td>
		<td valign="top">Password</td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while (!$rs->EOF) {
	$nRecCount++;

	// Set row class and style
	$tbl_aduser->CssClass = "ewTableRow";
	$tbl_aduser->CssStyle = "";

	// Get the field contents
	LoadRowValues($rs);

	// Render row value
	$tbl_aduser->RowType = EW_ROWTYPE_VIEW; // view
	RenderRow();
?>
	<tr<?php echo $tbl_aduser->DisplayAttributes() ?>>
		<td<?php echo $tbl_aduser->a_uname->CellAttributes() ?>>
<div<?php echo $tbl_aduser->a_uname->ViewAttributes() ?>><?php echo $tbl_aduser->a_uname->ViewValue ?></div>
</td>
		<td<?php echo $tbl_aduser->a_first_name->CellAttributes() ?>>
<div<?php echo $tbl_aduser->a_first_name->ViewAttributes() ?>><?php echo $tbl_aduser->a_first_name->ViewValue ?></div>
</td>
		<td<?php echo $tbl_aduser->a_last_name->CellAttributes() ?>>
<div<?php echo $tbl_aduser->a_last_name->ViewAttributes() ?>><?php echo $tbl_aduser->a_last_name->ViewValue ?></div>
</td>
		<td<?php echo $tbl_aduser->a_email->CellAttributes() ?>>
<div<?php echo $tbl_aduser->a_email->ViewAttributes() ?>><?php echo $tbl_aduser->a_email->ViewValue ?></div>
</td>
		<td<?php echo $tbl_aduser->a_mobile->CellAttributes() ?>>
<div<?php echo $tbl_aduser->a_mobile->ViewAttributes() ?>><?php echo $tbl_aduser->a_mobile->ViewValue ?></div>
</td>
		<td<?php echo $tbl_aduser->a_pwd->CellAttributes() ?>>
<div<?php echo $tbl_aduser->a_pwd->ViewAttributes() ?>><?php echo $tbl_aduser->a_pwd->ViewValue ?></div>
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
	global $conn, $Security, $tbl_aduser;
	$DeleteRows = TRUE;
	$sWrkFilter = $tbl_aduser->CurrentFilter;

	// Set up filter (Sql Where Clause) and get Return Sql
	// Sql constructor in tbl_aduser class, tbl_aduserinfo.php

	$tbl_aduser->CurrentFilter = $sWrkFilter;
	$sSql = $tbl_aduser->SQL();
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
			$DeleteRows = $tbl_aduser->Row_Deleting($row);
			if (!$DeleteRows) break;
		}
	}
	if ($DeleteRows) {
		$sKey = "";
		foreach ($rsold as $row) {
			$sThisKey = "";
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['a_uname'];
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$DeleteRows = $conn->Execute($tbl_aduser->DeleteSQL($row)); // Delete
			$conn->raiseErrorFn = '';
			if ($DeleteRows === FALSE)
				break;
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}
	} else {

		// Set up error message
		if ($tbl_aduser->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_aduser->CancelMessage;
			$tbl_aduser->CancelMessage = "";
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
			$tbl_aduser->Row_Deleted($row);
		}	
	}
	return $DeleteRows;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_aduser;

	// Call Recordset Selecting event
	$tbl_aduser->Recordset_Selecting($tbl_aduser->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_aduser->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_aduser->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_aduser;
	$sFilter = $tbl_aduser->SqlKeyFilter();
	$sFilter = str_replace("@a_uname@", ew_AdjustSql($tbl_aduser->a_uname->CurrentValue), $sFilter); // Replace key value

	// Call Row Selecting event
	$tbl_aduser->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_aduser->CurrentFilter = $sFilter;
	$sSql = $tbl_aduser->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_aduser->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_aduser;
	$tbl_aduser->a_uname->setDbValue($rs->fields('a_uname'));
	$tbl_aduser->a_first_name->setDbValue($rs->fields('a_first_name'));
	$tbl_aduser->a_last_name->setDbValue($rs->fields('a_last_name'));
	$tbl_aduser->a_email->setDbValue($rs->fields('a_email'));
	$tbl_aduser->a_mobile->setDbValue($rs->fields('a_mobile'));
	$tbl_aduser->a_pwd->setDbValue($rs->fields('a_pwd'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_aduser;

	// Call Row Rendering event
	$tbl_aduser->Row_Rendering();

	// Common render codes for all row types
	// a_uname

	$tbl_aduser->a_uname->CellCssStyle = "";
	$tbl_aduser->a_uname->CellCssClass = "";

	// a_first_name
	$tbl_aduser->a_first_name->CellCssStyle = "";
	$tbl_aduser->a_first_name->CellCssClass = "";

	// a_last_name
	$tbl_aduser->a_last_name->CellCssStyle = "";
	$tbl_aduser->a_last_name->CellCssClass = "";

	// a_email
	$tbl_aduser->a_email->CellCssStyle = "";
	$tbl_aduser->a_email->CellCssClass = "";

	// a_mobile
	$tbl_aduser->a_mobile->CellCssStyle = "";
	$tbl_aduser->a_mobile->CellCssClass = "";

	// a_pwd
	$tbl_aduser->a_pwd->CellCssStyle = "";
	$tbl_aduser->a_pwd->CellCssClass = "";
	if ($tbl_aduser->RowType == EW_ROWTYPE_VIEW) { // View row

		// a_uname
		$tbl_aduser->a_uname->ViewValue = $tbl_aduser->a_uname->CurrentValue;
		$tbl_aduser->a_uname->CssStyle = "";
		$tbl_aduser->a_uname->CssClass = "";
		$tbl_aduser->a_uname->ViewCustomAttributes = "";

		// a_first_name
		$tbl_aduser->a_first_name->ViewValue = $tbl_aduser->a_first_name->CurrentValue;
		$tbl_aduser->a_first_name->CssStyle = "";
		$tbl_aduser->a_first_name->CssClass = "";
		$tbl_aduser->a_first_name->ViewCustomAttributes = "";

		// a_last_name
		$tbl_aduser->a_last_name->ViewValue = $tbl_aduser->a_last_name->CurrentValue;
		$tbl_aduser->a_last_name->CssStyle = "";
		$tbl_aduser->a_last_name->CssClass = "";
		$tbl_aduser->a_last_name->ViewCustomAttributes = "";

		// a_email
		$tbl_aduser->a_email->ViewValue = $tbl_aduser->a_email->CurrentValue;
		$tbl_aduser->a_email->CssStyle = "";
		$tbl_aduser->a_email->CssClass = "";
		$tbl_aduser->a_email->ViewCustomAttributes = "";

		// a_mobile
		$tbl_aduser->a_mobile->ViewValue = $tbl_aduser->a_mobile->CurrentValue;
		$tbl_aduser->a_mobile->CssStyle = "";
		$tbl_aduser->a_mobile->CssClass = "";
		$tbl_aduser->a_mobile->ViewCustomAttributes = "";

		// a_pwd
		$tbl_aduser->a_pwd->ViewValue = "********";
		$tbl_aduser->a_pwd->CssStyle = "";
		$tbl_aduser->a_pwd->CssClass = "";
		$tbl_aduser->a_pwd->ViewCustomAttributes = "";

		// a_uname
		$tbl_aduser->a_uname->HrefValue = "";

		// a_first_name
		$tbl_aduser->a_first_name->HrefValue = "";

		// a_last_name
		$tbl_aduser->a_last_name->HrefValue = "";

		// a_email
		$tbl_aduser->a_email->HrefValue = "";

		// a_mobile
		$tbl_aduser->a_mobile->HrefValue = "";

		// a_pwd
		$tbl_aduser->a_pwd->HrefValue = "";
	} elseif ($tbl_aduser->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_aduser->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_aduser->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_aduser->Row_Rendered();
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
