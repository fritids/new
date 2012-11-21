<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
define("EW_TABLE_NAME", 'vwstudentinstructor', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "vwstudentinstructorinfo.php" ?>
<?php include "userfn50.php" ?>
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
if ($Security->IsLoggedIn() && $Security->CurrentUserID() == "") {
	$_SESSION[EW_SESSION_MESSAGE] = "You do not have the right permission to view the page";
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
$vwstudentinstructor->Export = @$_GET["export"]; // Get export parameter
$sExport = $vwstudentinstructor->Export; // Get export parameter, used in header
$sExportFile = $vwstudentinstructor->TableVar; // Get export file, used in header
?>
<?php
?>
<?php

// Paging variables
$nStartRec = 0; // Start record index
$nStopRec = 0; // Stop record index
$nTotalRecs = 0; // Total number of records
$nDisplayRecs = 20;
$nRecRange = 10;
$nRecCount = 0; // Record count

// Multi Column
$nRecPerRow = 1;
$ColCnt = 0;

// Search filters
$sSrchAdvanced = ""; // Advanced search filter
$sSrchBasic = ""; // Basic search filter
$sSrchWhere = ""; // Search where clause
$sFilter = "";

// Master/Detail
$sDbMasterFilter = ""; // Master filter
$sDbDetailFilter = ""; // Detail filter
$sSqlMaster = ""; // Sql for master record

// Handle reset command
ResetCmd();

// Build filter
$sFilter = "";
if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
	$sFilter = $vwstudentinstructor->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
}
if ($sDbDetailFilter <> "") {
	if ($sFilter <> "") $sFilter .= " AND ";
	$sFilter .= "(" . $sDbDetailFilter . ")";
}
if ($sSrchWhere <> "") {
	if ($sFilter <> "") $sFilter .= " AND ";
	$sFilter .= "(" . $sSrchWhere . ")";
}

// Set up filter in Session
$vwstudentinstructor->setSessionWhere($sFilter);
$vwstudentinstructor->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$vwstudentinstructor->setReturnUrl("vwstudentinstructorlist.php");
?>
<?php include "header.php" ?>
<?php if ($vwstudentinstructor->Export == "") { ?>
<script type="text/javascript">
<!--
var EW_PAGE_ID = "list"; // Page id

//-->
</script>
<script type="text/javascript">
<!--

// js for DHtml Editor
//-->

</script>
<script type="text/javascript">
<!--

// js for Popup Calendar
//-->

</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js"); 
//-->

</script>
<?php } ?>
<?php if ($vwstudentinstructor->Export == "") { ?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $vwstudentinstructor->Export <> "");
$bSelectLimit = ($vwstudentinstructor->Export == "" && $vwstudentinstructor->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $vwstudentinstructor->SelectRecordCount() : $rs->RecordCount();
$nStartRec = 1;
if ($nDisplayRecs <= 0) $nDisplayRecs = $nTotalRecs; // Display all records
if (!$bExportAll) SetUpStartRec(); // Set up start record position
if ($bSelectLimit) $rs = LoadRecordset($nStartRec-1, $nDisplayRecs);
?>
<p><span class="edge" style="white-space: nowrap;"> Student's Instructor
</span></p>
<?php if ($vwstudentinstructor->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form method="post" name="fvwstudentinstructorlist" id="fvwstudentinstructorlist">
<?php if ($vwstudentinstructor->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?>
<table border="0" cellspacing="5" cellpadding="5">
<?php
if (defined("EW_EXPORT_ALL") && $vwstudentinstructor->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$vwstudentinstructor->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;
		$ColCnt++;
		if ($ColCnt > $nRecPerRow) $ColCnt = 1;

	// Init row class and style
	$vwstudentinstructor->CssClass = "ewTableRow";
	$vwstudentinstructor->CssStyle = "";

	// Init row event
	$vwstudentinstructor->RowClientEvents = "";
	LoadRowValues($rs); // Load row values
	$vwstudentinstructor->RowType = EW_ROWTYPE_VIEW; // Render view
	RenderRow();
?>
<?php if ($ColCnt == 1) { ?>
<tr>
<?php } ?>
	<td valign="top"<?php echo $vwstudentinstructor->DisplayAttributes() ?>>
	<table class="ewTable">
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentinstructor->Export <> "") { ?>
First Name
<?php } else { ?>
	First Name<?php if ($vwstudentinstructor->i_first_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentinstructor->i_first_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td<?php echo $vwstudentinstructor->i_first_name->CellAttributes() ?>>
<div<?php echo $vwstudentinstructor->i_first_name->ViewAttributes() ?>><?php echo $vwstudentinstructor->i_first_name->ViewValue ?></div>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentinstructor->Export <> "") { ?>
Last Name
<?php } else { ?>
	Last Name<?php if ($vwstudentinstructor->i_last_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentinstructor->i_last_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td<?php echo $vwstudentinstructor->i_last_name->CellAttributes() ?>>
<div<?php echo $vwstudentinstructor->i_last_name->ViewAttributes() ?>><?php echo $vwstudentinstructor->i_last_name->ViewValue ?></div>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentinstructor->Export <> "") { ?>
E-mail
<?php } else { ?>
	E-mail<?php if ($vwstudentinstructor->i_email->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentinstructor->i_email->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td<?php echo $vwstudentinstructor->i_email->CellAttributes() ?>>
<div<?php echo $vwstudentinstructor->i_email->ViewAttributes() ?>><?php echo $vwstudentinstructor->i_email->ViewValue ?></div>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentinstructor->Export <> "") { ?>
Mobile
<?php } else { ?>
	Mobile<?php if ($vwstudentinstructor->i_mobile->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentinstructor->i_mobile->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td<?php echo $vwstudentinstructor->i_mobile->CellAttributes() ?>>
<div<?php echo $vwstudentinstructor->i_mobile->ViewAttributes() ?>><?php echo $vwstudentinstructor->i_mobile->ViewValue ?></div>
</td>
		</tr>
	</table>
<span class="edge">
<?php if ($vwstudentinstructor->Export == "") { ?>
<?php } ?>
</span>
	</td>
<?php if ($ColCnt == $nRecPerRow) { ?>
</tr>
<?php } ?>
<?php
	}
	$rs->MoveNext();
}
?>
<?php if ($ColCnt < $nRecPerRow) { ?>
<?php for ($i = 1; $i <= $nRecPerRow - $ColCnt; $i++) { ?>
	<td>&nbsp;</td>
<?php } ?>
</tr>
<?php } ?>
</table>
<?php if ($vwstudentinstructor->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php } ?>
</form>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($vwstudentinstructor->Export == "") { ?>
<?php } ?>
<?php if ($vwstudentinstructor->Export == "") { ?>
<?php } ?>
<?php if ($vwstudentinstructor->Export == "") { ?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php } ?>
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

// Set up Sort parameters based on Sort Links clicked
function SetUpSortOrder() {
	global $vwstudentinstructor;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$vwstudentinstructor->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$vwstudentinstructor->CurrentOrderType = @$_GET["ordertype"];
		$vwstudentinstructor->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $vwstudentinstructor->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($vwstudentinstructor->SqlOrderBy() <> "") {
			$sOrderBy = $vwstudentinstructor->SqlOrderBy();
			$vwstudentinstructor->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $vwstudentinstructor;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$vwstudentinstructor->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$vwstudentinstructor->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $vwstudentinstructor;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$vwstudentinstructor->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$vwstudentinstructor->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $vwstudentinstructor->getStartRecordNumber();
		}
	} else {
		$nStartRec = $vwstudentinstructor->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$vwstudentinstructor->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$vwstudentinstructor->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$vwstudentinstructor->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $vwstudentinstructor;

	// Call Recordset Selecting event
	$vwstudentinstructor->Recordset_Selecting($vwstudentinstructor->CurrentFilter);

	// Load list page sql
	$sSql = $vwstudentinstructor->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$vwstudentinstructor->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $vwstudentinstructor;
	$sFilter = $vwstudentinstructor->SqlKeyFilter();
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $vwstudentinstructor->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
	}

	// Call Row Selecting event
	$vwstudentinstructor->Row_Selecting($sFilter);

	// Load sql based on filter
	$vwstudentinstructor->CurrentFilter = $sFilter;
	$sSql = $vwstudentinstructor->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$vwstudentinstructor->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $vwstudentinstructor;
	$vwstudentinstructor->i_instructid->setDbValue($rs->fields('i_instructid'));
	$vwstudentinstructor->i_instructorid->setDbValue($rs->fields('i_instructorid'));
	$vwstudentinstructor->i_first_name->setDbValue($rs->fields('i_first_name'));
	$vwstudentinstructor->i_last_name->setDbValue($rs->fields('i_last_name'));
	$vwstudentinstructor->i_email->setDbValue($rs->fields('i_email'));
	$vwstudentinstructor->i_mobile->setDbValue($rs->fields('i_mobile'));
	$vwstudentinstructor->s_studentid->setDbValue($rs->fields('s_studentid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $vwstudentinstructor;

	// Call Row Rendering event
	$vwstudentinstructor->Row_Rendering();

	// Common render codes for all row types
	// i_first_name

	$vwstudentinstructor->i_first_name->CellCssStyle = "";
	$vwstudentinstructor->i_first_name->CellCssClass = "";

	// i_last_name
	$vwstudentinstructor->i_last_name->CellCssStyle = "";
	$vwstudentinstructor->i_last_name->CellCssClass = "";

	// i_email
	$vwstudentinstructor->i_email->CellCssStyle = "";
	$vwstudentinstructor->i_email->CellCssClass = "";

	// i_mobile
	$vwstudentinstructor->i_mobile->CellCssStyle = "";
	$vwstudentinstructor->i_mobile->CellCssClass = "";
	if ($vwstudentinstructor->RowType == EW_ROWTYPE_VIEW) { // View row

		// i_first_name
		$vwstudentinstructor->i_first_name->ViewValue = $vwstudentinstructor->i_first_name->CurrentValue;
		$vwstudentinstructor->i_first_name->CssStyle = "";
		$vwstudentinstructor->i_first_name->CssClass = "";
		$vwstudentinstructor->i_first_name->ViewCustomAttributes = "";

		// i_last_name
		$vwstudentinstructor->i_last_name->ViewValue = $vwstudentinstructor->i_last_name->CurrentValue;
		$vwstudentinstructor->i_last_name->CssStyle = "";
		$vwstudentinstructor->i_last_name->CssClass = "";
		$vwstudentinstructor->i_last_name->ViewCustomAttributes = "";

		// i_email
		$vwstudentinstructor->i_email->ViewValue = $vwstudentinstructor->i_email->CurrentValue;
		$vwstudentinstructor->i_email->CssStyle = "";
		$vwstudentinstructor->i_email->CssClass = "";
		$vwstudentinstructor->i_email->ViewCustomAttributes = "";

		// i_mobile
		$vwstudentinstructor->i_mobile->ViewValue = $vwstudentinstructor->i_mobile->CurrentValue;
		$vwstudentinstructor->i_mobile->CssStyle = "";
		$vwstudentinstructor->i_mobile->CssClass = "";
		$vwstudentinstructor->i_mobile->ViewCustomAttributes = "";

		// i_first_name
		$vwstudentinstructor->i_first_name->HrefValue = "";

		// i_last_name
		$vwstudentinstructor->i_last_name->HrefValue = "";

		// i_email
		$vwstudentinstructor->i_email->HrefValue = "";

		// i_mobile
		$vwstudentinstructor->i_mobile->HrefValue = "";
	} elseif ($vwstudentinstructor->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($vwstudentinstructor->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($vwstudentinstructor->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$vwstudentinstructor->Row_Rendered();
}
?>
<?php

// Show link optionally based on User ID
function ShowOptionLink() {
	global $Security, $vwstudentinstructor;
	if ($Security->IsLoggedIn()) {
		if (!$Security->IsAdmin()) {
			return $Security->IsValidUserID($vwstudentinstructor->s_studentid->CurrentValue);
		}
	}
	return TRUE;
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
