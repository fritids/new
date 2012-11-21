<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
define("EW_TABLE_NAME", 'vwstudentpsat', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "vwstudentpsatinfo.php" ?>
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
$vwstudentpsat->Export = @$_GET["export"]; // Get export parameter
$sExport = $vwstudentpsat->Export; // Get export parameter, used in header
$sExportFile = $vwstudentpsat->TableVar; // Get export file, used in header
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
	$sFilter = $vwstudentpsat->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
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
$vwstudentpsat->setSessionWhere($sFilter);
$vwstudentpsat->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$vwstudentpsat->setReturnUrl("vwstudentpsatlist.php");
?>
<?php include "header.php" ?>
<?php if ($vwstudentpsat->Export == "") { ?>
<script type="text/javascript">
<!--
var EW_PAGE_ID = "list"; // Page id

//-->
</script>
<script type="text/javascript">
<!--
var firstrowoffset = 1; // First data row start at
var lastrowoffset = 0; // Last data row end at
var EW_LIST_TABLE_NAME = 'ewlistmain'; // Table name for list page
var rowclass = 'ewTableRow'; // Row class
var rowaltclass = 'ewTableRow'; // Row alternate class
var rowmoverclass = 'ewTableHighlightRow'; // Row mouse over class
var rowselectedclass = 'ewTableSelectRow'; // Row selected class
var roweditclass = 'ewTableEditRow'; // Row edit class

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
<?php if ($vwstudentpsat->Export == "") { ?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $vwstudentpsat->Export <> "");
$bSelectLimit = ($vwstudentpsat->Export == "" && $vwstudentpsat->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $vwstudentpsat->SelectRecordCount() : $rs->RecordCount();
$nStartRec = 1;
if ($nDisplayRecs <= 0) $nDisplayRecs = $nTotalRecs; // Display all records
if (!$bExportAll) SetUpStartRec(); // Set up start record position
if ($bSelectLimit) $rs = LoadRecordset($nStartRec-1, $nDisplayRecs);
?>
<p><span class="edge" style="white-space: nowrap;"> Student's PSAT
</span></p>
<?php if ($vwstudentpsat->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form method="post" name="fvwstudentpsatlist" id="fvwstudentpsatlist">
<?php if ($vwstudentpsat->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?>
<table id="ewlistmain" class="ewTable">
<?php
	$OptionCnt = 0;
?>
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td width="100" valign="top">
<?php if ($vwstudentpsat->Export <> "") { ?>
Test Date
<?php } else { ?>
	Test Date<?php if ($vwstudentpsat->psat_date->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentpsat->psat_date->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="75" valign="top">
<?php if ($vwstudentpsat->Export <> "") { ?>
Reading
<?php } else { ?>
	Reading<?php if ($vwstudentpsat->psat_reading->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentpsat->psat_reading->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="75" valign="top">
<?php if ($vwstudentpsat->Export <> "") { ?>
Math
<?php } else { ?>
	Math<?php if ($vwstudentpsat->psat_math->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentpsat->psat_math->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="75" valign="top">
<?php if ($vwstudentpsat->Export <> "") { ?>
Writing
<?php } else { ?>
	Writing<?php if ($vwstudentpsat->psat_writing->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentpsat->psat_writing->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="200" valign="top">
<?php if ($vwstudentpsat->Export <> "") { ?>
Test Site
<?php } else { ?>
	Test Site<?php if ($vwstudentpsat->psat_test_site->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentpsat->psat_test_site->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
<?php if ($vwstudentpsat->Export == "") { ?>
<?php } ?>
	</tr>
<?php
if (defined("EW_EXPORT_ALL") && $vwstudentpsat->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$vwstudentpsat->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;

	// Init row class and style
	$vwstudentpsat->CssClass = "ewTableRow";
	$vwstudentpsat->CssStyle = "";

	// Init row event
	$vwstudentpsat->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
	LoadRowValues($rs); // Load row values
	$vwstudentpsat->RowType = EW_ROWTYPE_VIEW; // Render view
	RenderRow();
?>
	<!-- Table body -->
	<tr<?php echo $vwstudentpsat->DisplayAttributes() ?>>
		<!-- s_first_name -->
		<!-- s_middle_name -->
		<!-- s_last_name -->
		<!-- psat_date -->
		<td width="100"<?php echo $vwstudentpsat->psat_date->CellAttributes() ?>>
<div<?php echo $vwstudentpsat->psat_date->ViewAttributes() ?>><?php echo $vwstudentpsat->psat_date->ViewValue ?></div></td>
		<!-- psat_reading -->
		<td width="75"<?php echo $vwstudentpsat->psat_reading->CellAttributes() ?>>
<div<?php echo $vwstudentpsat->psat_reading->ViewAttributes() ?>><?php echo $vwstudentpsat->psat_reading->ViewValue ?></div></td>
		<!-- psat_math -->
		<td width="75"<?php echo $vwstudentpsat->psat_math->CellAttributes() ?>>
<div<?php echo $vwstudentpsat->psat_math->ViewAttributes() ?>><?php echo $vwstudentpsat->psat_math->ViewValue ?></div></td>
		<!-- psat_writing -->
		<td width="75"<?php echo $vwstudentpsat->psat_writing->CellAttributes() ?>>
<div<?php echo $vwstudentpsat->psat_writing->ViewAttributes() ?>><?php echo $vwstudentpsat->psat_writing->ViewValue ?></div></td>
		<!-- psat_test_site -->
		<td width="200"<?php echo $vwstudentpsat->psat_test_site->CellAttributes() ?>>
<div<?php echo $vwstudentpsat->psat_test_site->ViewAttributes() ?>><?php echo $vwstudentpsat->psat_test_site->ViewValue ?></div></td>
<?php if ($vwstudentpsat->Export == "") { ?>
<?php } ?>
	</tr>
<?php
	}
	$rs->MoveNext();
}
?>
</table>
<?php if ($vwstudentpsat->Export == "") { ?>
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
<?php if ($vwstudentpsat->Export == "") { ?>
<form action="vwstudentpsatlist.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap>
<span class="edge">
<?php if (!isset($Pager)) $Pager = new cNumericPager($nStartRec, $nDisplayRecs, $nTotalRecs, $nRecRange) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<a href="vwstudentpsatlist.php?start=<?php echo $Pager->FirstButton->Start ?>"><b>First</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<a href="vwstudentpsatlist.php?start=<?php echo $Pager->PrevButton->Start ?>"><b>Previous</b></a>&nbsp;
	<?php } ?>
	<?php foreach ($Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="vwstudentpsatlist.php?start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($Pager->NextButton->Enabled) { ?>
	<a href="vwstudentpsatlist.php?start=<?php echo $Pager->NextButton->Start ?>"><b>Next</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->LastButton->Enabled) { ?>
	<a href="vwstudentpsatlist.php?start=<?php echo $Pager->LastButton->Start ?>"><b>Last</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->ButtonCount > 0) { ?><br><?php } ?>
	Records <?php echo $Pager->FromIndex ?> to <?php echo $Pager->ToIndex ?> of <?php echo $Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($sSrchWhere == "0=101") { ?>
	Please enter search criteria
	<?php } else { ?>
	No records found
	<?php } ?>
<?php } ?>
</span>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<?php if ($vwstudentpsat->Export == "") { ?>
<?php } ?>
<?php if ($vwstudentpsat->Export == "") { ?>
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
	global $vwstudentpsat;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$vwstudentpsat->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$vwstudentpsat->CurrentOrderType = @$_GET["ordertype"];
		$vwstudentpsat->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $vwstudentpsat->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($vwstudentpsat->SqlOrderBy() <> "") {
			$sOrderBy = $vwstudentpsat->SqlOrderBy();
			$vwstudentpsat->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $vwstudentpsat;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$vwstudentpsat->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$vwstudentpsat->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $vwstudentpsat;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$vwstudentpsat->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$vwstudentpsat->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $vwstudentpsat->getStartRecordNumber();
		}
	} else {
		$nStartRec = $vwstudentpsat->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$vwstudentpsat->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$vwstudentpsat->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$vwstudentpsat->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $vwstudentpsat;

	// Call Recordset Selecting event
	$vwstudentpsat->Recordset_Selecting($vwstudentpsat->CurrentFilter);

	// Load list page sql
	$sSql = $vwstudentpsat->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$vwstudentpsat->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $vwstudentpsat;
	$sFilter = $vwstudentpsat->SqlKeyFilter();
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $vwstudentpsat->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
	}

	// Call Row Selecting event
	$vwstudentpsat->Row_Selecting($sFilter);

	// Load sql based on filter
	$vwstudentpsat->CurrentFilter = $sFilter;
	$sSql = $vwstudentpsat->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$vwstudentpsat->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $vwstudentpsat;
	$vwstudentpsat->s_studentid->setDbValue($rs->fields('s_studentid'));
	$vwstudentpsat->s_first_name->setDbValue($rs->fields('s_first_name'));
	$vwstudentpsat->s_middle_name->setDbValue($rs->fields('s_middle_name'));
	$vwstudentpsat->s_last_name->setDbValue($rs->fields('s_last_name'));
	$vwstudentpsat->psatid->setDbValue($rs->fields('psatid'));
	$vwstudentpsat->psat_date->setDbValue($rs->fields('psat_date'));
	$vwstudentpsat->psat_reading->setDbValue($rs->fields('psat_reading'));
	$vwstudentpsat->psat_math->setDbValue($rs->fields('psat_math'));
	$vwstudentpsat->psat_writing->setDbValue($rs->fields('psat_writing'));
	$vwstudentpsat->psat_test_site->setDbValue($rs->fields('psat_test_site'));
	$vwstudentpsat->s_stuid->setDbValue($rs->fields('s_stuid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $vwstudentpsat;

	// Call Row Rendering event
	$vwstudentpsat->Row_Rendering();

	// Common render codes for all row types
	// s_first_name

	$vwstudentpsat->s_first_name->CellCssStyle = "";
	$vwstudentpsat->s_first_name->CellCssClass = "";

	// s_middle_name
	$vwstudentpsat->s_middle_name->CellCssStyle = "";
	$vwstudentpsat->s_middle_name->CellCssClass = "";

	// s_last_name
	$vwstudentpsat->s_last_name->CellCssStyle = "";
	$vwstudentpsat->s_last_name->CellCssClass = "";

	// psat_date
	$vwstudentpsat->psat_date->CellCssStyle = "";
	$vwstudentpsat->psat_date->CellCssClass = "";

	// psat_reading
	$vwstudentpsat->psat_reading->CellCssStyle = "";
	$vwstudentpsat->psat_reading->CellCssClass = "";

	// psat_math
	$vwstudentpsat->psat_math->CellCssStyle = "";
	$vwstudentpsat->psat_math->CellCssClass = "";

	// psat_writing
	$vwstudentpsat->psat_writing->CellCssStyle = "";
	$vwstudentpsat->psat_writing->CellCssClass = "";

	// psat_test_site
	$vwstudentpsat->psat_test_site->CellCssStyle = "";
	$vwstudentpsat->psat_test_site->CellCssClass = "";
	if ($vwstudentpsat->RowType == EW_ROWTYPE_VIEW) { // View row

		// s_first_name
		$vwstudentpsat->s_first_name->ViewValue = $vwstudentpsat->s_first_name->CurrentValue;
		$vwstudentpsat->s_first_name->CssStyle = "";
		$vwstudentpsat->s_first_name->CssClass = "";
		$vwstudentpsat->s_first_name->ViewCustomAttributes = "";

		// s_middle_name
		$vwstudentpsat->s_middle_name->ViewValue = $vwstudentpsat->s_middle_name->CurrentValue;
		$vwstudentpsat->s_middle_name->CssStyle = "";
		$vwstudentpsat->s_middle_name->CssClass = "";
		$vwstudentpsat->s_middle_name->ViewCustomAttributes = "";

		// s_last_name
		$vwstudentpsat->s_last_name->ViewValue = $vwstudentpsat->s_last_name->CurrentValue;
		$vwstudentpsat->s_last_name->CssStyle = "";
		$vwstudentpsat->s_last_name->CssClass = "";
		$vwstudentpsat->s_last_name->ViewCustomAttributes = "";

		// psat_date
		$vwstudentpsat->psat_date->ViewValue = $vwstudentpsat->psat_date->CurrentValue;
		$vwstudentpsat->psat_date->ViewValue = ew_FormatDateTime($vwstudentpsat->psat_date->ViewValue, 5);
		$vwstudentpsat->psat_date->CssStyle = "";
		$vwstudentpsat->psat_date->CssClass = "";
		$vwstudentpsat->psat_date->ViewCustomAttributes = "";

		// psat_reading
		$vwstudentpsat->psat_reading->ViewValue = $vwstudentpsat->psat_reading->CurrentValue;
		$vwstudentpsat->psat_reading->CssStyle = "";
		$vwstudentpsat->psat_reading->CssClass = "";
		$vwstudentpsat->psat_reading->ViewCustomAttributes = "";

		// psat_math
		$vwstudentpsat->psat_math->ViewValue = $vwstudentpsat->psat_math->CurrentValue;
		$vwstudentpsat->psat_math->CssStyle = "";
		$vwstudentpsat->psat_math->CssClass = "";
		$vwstudentpsat->psat_math->ViewCustomAttributes = "";

		// psat_writing
		$vwstudentpsat->psat_writing->ViewValue = $vwstudentpsat->psat_writing->CurrentValue;
		$vwstudentpsat->psat_writing->CssStyle = "";
		$vwstudentpsat->psat_writing->CssClass = "";
		$vwstudentpsat->psat_writing->ViewCustomAttributes = "";

		// psat_test_site
		$vwstudentpsat->psat_test_site->ViewValue = $vwstudentpsat->psat_test_site->CurrentValue;
		$vwstudentpsat->psat_test_site->CssStyle = "";
		$vwstudentpsat->psat_test_site->CssClass = "";
		$vwstudentpsat->psat_test_site->ViewCustomAttributes = "";

		// s_first_name
		$vwstudentpsat->s_first_name->HrefValue = "";

		// s_middle_name
		$vwstudentpsat->s_middle_name->HrefValue = "";

		// s_last_name
		$vwstudentpsat->s_last_name->HrefValue = "";

		// psat_date
		$vwstudentpsat->psat_date->HrefValue = "";

		// psat_reading
		$vwstudentpsat->psat_reading->HrefValue = "";

		// psat_math
		$vwstudentpsat->psat_math->HrefValue = "";

		// psat_writing
		$vwstudentpsat->psat_writing->HrefValue = "";

		// psat_test_site
		$vwstudentpsat->psat_test_site->HrefValue = "";
	} elseif ($vwstudentpsat->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($vwstudentpsat->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($vwstudentpsat->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$vwstudentpsat->Row_Rendered();
}
?>
<?php

// Show link optionally based on User ID
function ShowOptionLink() {
	global $Security, $vwstudentpsat;
	if ($Security->IsLoggedIn()) {
		if (!$Security->IsAdmin()) {
			return $Security->IsValidUserID($vwstudentpsat->s_studentid->CurrentValue);
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
