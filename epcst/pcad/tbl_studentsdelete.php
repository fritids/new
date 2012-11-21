<?php
define("EW_PAGE_ID", "delete", TRUE); // Page ID
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

// Load Key Parameters
$sKey = "";
$bSingleDelete = TRUE; // Initialize as single delete
$arRecKeys = array();
$nKeySelected = 0; // Initialize selected key count
$sFilter = "";
if (@$_GET["s_studentid"] <> "") {
	$tbl_students->s_studentid->setQueryStringValue($_GET["s_studentid"]);
	if (!is_numeric($tbl_students->s_studentid->QueryStringValue)) {
		Page_Terminate($tbl_students->getReturnUrl()); // Prevent sql injection, exit
	}
	$sKey .= $tbl_students->s_studentid->QueryStringValue;
} else {
	$bSingleDelete = FALSE;
}
if (@$_GET["s_usrname"] <> "") {
	$tbl_students->s_usrname->setQueryStringValue($_GET["s_usrname"]);
	if ($sKey <> "") $sKey .= EW_COMPOSITE_KEY_SEPARATOR;
	$sKey .= $tbl_students->s_usrname->QueryStringValue;
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
if ($nKeySelected <= 0) Page_Terminate($tbl_students->getReturnUrl()); // No key specified, exit

// Build filter
foreach ($arRecKeys as $sKey) {
	$sFilter .= "(";
	$arKeyFlds = explode(EW_COMPOSITE_KEY_SEPARATOR, trim($sKey)); // Split key by separator
	if (count($arKeyFlds) <> 2) Page_Terminate($tbl_students->getReturnUrl()); // Invalid key, exit

	// Set up key field
	$sKeyFld = $arKeyFlds[0];
	if (!is_numeric($sKeyFld)) {
		Page_Terminate($tbl_students->getReturnUrl()); // Prevent sql injection, exit
	}
	$sFilter .= "`s_studentid`=" . ew_AdjustSql($sKeyFld) . " AND ";

	// Set up key field
	$sKeyFld = $arKeyFlds[1];
	$sFilter .= "`s_usrname`='" . ew_AdjustSql($sKeyFld) . "' AND ";
	if (substr($sFilter, -5) == " AND ") $sFilter = substr($sFilter, 0, strlen($sFilter)-5) . ") OR ";
}
if (substr($sFilter, -4) == " OR ") $sFilter = substr($sFilter, 0, strlen($sFilter)-4);

// Set up filter (Sql Where Clause) and get Return Sql
// Sql constructor in tbl_students class, tbl_studentsinfo.php

$tbl_students->CurrentFilter = $sFilter;

// Get action
if (@$_POST["a_delete"] <> "") {
	$tbl_students->CurrentAction = $_POST["a_delete"];
} else {
	$tbl_students->CurrentAction = "I"; // Display record
}
switch ($tbl_students->CurrentAction) {
	case "D": // Delete
		$tbl_students->SendEmail = TRUE; // Send email on delete success
		if (DeleteRows()) { // delete rows
			$_SESSION[EW_SESSION_MESSAGE] = "Delete Successful"; // Set up success message
			Page_Terminate($tbl_students->getReturnUrl()); // Return to caller
		}
}

// Load records for display
$rs = LoadRecordset();
$nTotalRecs = $rs->RecordCount(); // Get record count
if ($nTotalRecs <= 0) { // No record found, exit
	$rs->Close();
	Page_Terminate($tbl_students->getReturnUrl()); // Return to caller
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
<p><span class="edge">Delete  Student <br>
  <br><a href="<?php echo $tbl_students->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form action="tbl_studentsdelete.php" method="post">
<p>
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($arRecKeys as $sKey) { ?>
<input type="hidden" name="key_m[]" id="key_m[]" value="<?php echo ew_HtmlEncode($sKey) ?>">
<?php } ?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td width="120" valign="top">First Name</td>
		<td width="120" valign="top">Last Name</td>
		<td width="120" valign="top">Middle Name</td>
		<td width="150" valign="top">E-mail</td>
		<td width="100" valign="top">Username</td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while (!$rs->EOF) {
	$nRecCount++;

	// Set row class and style
	$tbl_students->CssClass = "ewTableRow";
	$tbl_students->CssStyle = "";

	// Get the field contents
	LoadRowValues($rs);

	// Render row value
	$tbl_students->RowType = EW_ROWTYPE_VIEW; // view
	RenderRow();
?>
	<tr<?php echo $tbl_students->DisplayAttributes() ?>>
		<td width="120"<?php echo $tbl_students->s_first_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_first_name->ViewAttributes() ?>><?php echo $tbl_students->s_first_name->ViewValue ?></div></td>
		<td width="120"<?php echo $tbl_students->s_last_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_last_name->ViewAttributes() ?>><?php echo $tbl_students->s_last_name->ViewValue ?></div></td>
		<td width="120"<?php echo $tbl_students->s_middle_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_middle_name->ViewAttributes() ?>><?php echo $tbl_students->s_middle_name->ViewValue ?></div></td>
		<td width="150"<?php echo $tbl_students->s_student_email->CellAttributes() ?>>
<div<?php echo $tbl_students->s_student_email->ViewAttributes() ?>><?php echo $tbl_students->s_student_email->ViewValue ?></div></td>
		<td width="100"<?php echo $tbl_students->s_usrname->CellAttributes() ?>>
<div<?php echo $tbl_students->s_usrname->ViewAttributes() ?>><?php echo $tbl_students->s_usrname->ViewValue ?></div></td>
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
	global $conn, $Security, $tbl_students;
	$DeleteRows = TRUE;
	$sWrkFilter = $tbl_students->CurrentFilter;

	// Set up filter (Sql Where Clause) and get Return Sql
	// Sql constructor in tbl_students class, tbl_studentsinfo.php

	$tbl_students->CurrentFilter = $sWrkFilter;
	$sSql = $tbl_students->SQL();
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
			$DeleteRows = $tbl_students->Row_Deleting($row);
			if (!$DeleteRows) break;
		}
	}
	if ($DeleteRows) {
		$sKey = "";
		foreach ($rsold as $row) {
			$sThisKey = "";
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['s_usrname'];
			if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
			$sThisKey .= $row['s_usrname'];
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$DeleteRows = $conn->Execute($tbl_students->DeleteSQL($row)); // Delete
			$conn->raiseErrorFn = '';
			if ($DeleteRows === FALSE)
				break;
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}
	} else {

		// Set up error message
		if ($tbl_students->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_students->CancelMessage;
			$tbl_students->CancelMessage = "";
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
			$tbl_students->Row_Deleted($row);
		}	
	}
	return $DeleteRows;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_students;

	// Call Recordset Selecting event
	$tbl_students->Recordset_Selecting($tbl_students->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_students->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_students->Recordset_Selected($rs);
	return $rs;
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

	// s_student_email
	$tbl_students->s_student_email->CellCssStyle = "";
	$tbl_students->s_student_email->CellCssClass = "";

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

		// s_student_email
		$tbl_students->s_student_email->ViewValue = $tbl_students->s_student_email->CurrentValue;
		$tbl_students->s_student_email->CssStyle = "";
		$tbl_students->s_student_email->CssClass = "";
		$tbl_students->s_student_email->ViewCustomAttributes = "";

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

		// s_student_email
		$tbl_students->s_student_email->HrefValue = "";

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

// Page Load event
function Page_Load() {

	//echo "Page Load";
}

// Page Unload event
function Page_Unload() {

	//echo "Page Unload";
}
?>
