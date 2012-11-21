<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
define("EW_TABLE_NAME", 'vwstudentsession', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "vwstudentsessioninfo.php" ?>
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
$vwstudentsession->Export = @$_GET["export"]; // Get export parameter
$sExport = $vwstudentsession->Export; // Get export parameter, used in header
$sExportFile = $vwstudentsession->TableVar; // Get export file, used in header
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
	$sFilter = $vwstudentsession->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
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
$vwstudentsession->setSessionWhere($sFilter);
$vwstudentsession->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$vwstudentsession->setReturnUrl("vwstudentsessionlist.php");
?>
<?php include "header.php" ?>
<?php if ($vwstudentsession->Export == "") { ?>
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
<?php if ($vwstudentsession->Export == "") { ?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $vwstudentsession->Export <> "");
$bSelectLimit = ($vwstudentsession->Export == "" && $vwstudentsession->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $vwstudentsession->SelectRecordCount() : $rs->RecordCount();
$nStartRec = 1;
if ($nDisplayRecs <= 0) $nDisplayRecs = $nTotalRecs; // Display all records
if (!$bExportAll) SetUpStartRec(); // Set up start record position
if ($bSelectLimit) $rs = LoadRecordset($nStartRec-1, $nDisplayRecs);
?>
<p><span class="edge" style="white-space: nowrap;">Student's Session
</span></p>
<?php if ($vwstudentsession->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form method="post" name="fvwstudentsessionlist" id="fvwstudentsessionlist">
<?php if ($vwstudentsession->Export == "") { ?>
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
		<td width="120" valign="top">
<?php if ($vwstudentsession->Export <> "") { ?>
Session Date
<?php } else { ?>
	 Date
	 <?php if ($vwstudentsession->session_date->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentsession->session_date->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="120" valign="top">
<?php if ($vwstudentsession->Export <> "") { ?>
Session Number
<?php } else { ?>
	 Number
	 <?php if ($vwstudentsession->session_number->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentsession->session_number->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="120" valign="top">
<?php if ($vwstudentsession->Export <> "") { ?>
Goal
<?php } else { ?>
	Goal<?php if ($vwstudentsession->session_goal->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentsession->session_goal->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="120" valign="top">
<?php if ($vwstudentsession->Export <> "") { ?>
Completed
<?php } else { ?>
	Completed<?php if ($vwstudentsession->session_goal_completed->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentsession->session_goal_completed->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="120" valign="top">
<?php if ($vwstudentsession->Export <> "") { ?>
Homework
<?php } else { ?>
	Homework<?php if ($vwstudentsession->session_homework->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentsession->session_homework->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="120" valign="top">
<?php if ($vwstudentsession->Export <> "") { ?>
Completed
<?php } else { ?>
	Completed<?php if ($vwstudentsession->session_hmwrk_completed->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentsession->session_hmwrk_completed->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
<?php if ($vwstudentsession->Export == "") { ?>
<?php } ?>
	</tr>
<?php
if (defined("EW_EXPORT_ALL") && $vwstudentsession->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$vwstudentsession->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;

	// Init row class and style
	$vwstudentsession->CssClass = "ewTableRow";
	$vwstudentsession->CssStyle = "";

	// Init row event
	$vwstudentsession->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
	LoadRowValues($rs); // Load row values
	$vwstudentsession->RowType = EW_ROWTYPE_VIEW; // Render view
	RenderRow();
?>
	<!-- Table body -->
	<tr<?php echo $vwstudentsession->DisplayAttributes() ?>>
		<!-- s_first_name -->
		<!-- s_middle_name -->
		<!-- s_last_name -->
		<!-- session_number -->
				<td width="120"<?php echo $vwstudentsession->session_date->CellAttributes() ?>>
<div<?php echo $vwstudentsession->session_date->ViewAttributes() ?>><?php echo $vwstudentsession->session_date->ViewValue ?></div></td>
		<td width="120"<?php echo $vwstudentsession->session_number->CellAttributes() ?>>
<div<?php echo $vwstudentsession->session_number->ViewAttributes() ?>><?php echo $vwstudentsession->session_number->ViewValue ?></div></td>
		<!-- session_goal -->
		<td width="120"<?php echo $vwstudentsession->session_goal->CellAttributes() ?>>
<div<?php echo $vwstudentsession->session_goal->ViewAttributes() ?>><?php echo $vwstudentsession->session_goal->ViewValue ?></div></td>
		<!-- session_goal_completed -->
		<td width="120"<?php echo $vwstudentsession->session_goal_completed->CellAttributes() ?>>
<div<?php echo $vwstudentsession->session_goal_completed->ViewAttributes() ?>><?php echo $vwstudentsession->session_goal_completed->ViewValue ?></div></td>
		<!-- session_homework -->
		<td width="120"<?php echo $vwstudentsession->session_homework->CellAttributes() ?>>
<div<?php echo $vwstudentsession->session_homework->ViewAttributes() ?>><?php echo $vwstudentsession->session_homework->ViewValue ?></div></td>
		<!-- session_hmwrk_completed -->
		<td width="120"<?php echo $vwstudentsession->session_hmwrk_completed->CellAttributes() ?>>
<div<?php echo $vwstudentsession->session_hmwrk_completed->ViewAttributes() ?>><?php echo $vwstudentsession->session_hmwrk_completed->ViewValue ?></div></td>
<?php if ($vwstudentsession->Export == "") { ?>
<?php } ?>
	</tr>
<?php
	}
	$rs->MoveNext();
}
?>
</table>
<?php if ($vwstudentsession->Export == "") { ?>
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
<?php if ($vwstudentsession->Export == "") { ?>
<form action="vwstudentsessionlist.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap>
<span class="edge">
<?php if (!isset($Pager)) $Pager = new cNumericPager($nStartRec, $nDisplayRecs, $nTotalRecs, $nRecRange) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<a href="vwstudentsessionlist.php?start=<?php echo $Pager->FirstButton->Start ?>"><b>First</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<a href="vwstudentsessionlist.php?start=<?php echo $Pager->PrevButton->Start ?>"><b>Previous</b></a>&nbsp;
	<?php } ?>
	<?php foreach ($Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="vwstudentsessionlist.php?start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($Pager->NextButton->Enabled) { ?>
	<a href="vwstudentsessionlist.php?start=<?php echo $Pager->NextButton->Start ?>"><b>Next</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->LastButton->Enabled) { ?>
	<a href="vwstudentsessionlist.php?start=<?php echo $Pager->LastButton->Start ?>"><b>Last</b></a>&nbsp;
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
<?php if ($vwstudentsession->Export == "") { ?>
<?php } ?>
<?php if ($vwstudentsession->Export == "") { ?>
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
	global $vwstudentsession;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$vwstudentsession->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$vwstudentsession->CurrentOrderType = @$_GET["ordertype"];
		$vwstudentsession->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $vwstudentsession->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($vwstudentsession->SqlOrderBy() <> "") {
			$sOrderBy = $vwstudentsession->SqlOrderBy();
			$vwstudentsession->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $vwstudentsession;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$vwstudentsession->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$vwstudentsession->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $vwstudentsession;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$vwstudentsession->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$vwstudentsession->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $vwstudentsession->getStartRecordNumber();
		}
	} else {
		$nStartRec = $vwstudentsession->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$vwstudentsession->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$vwstudentsession->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$vwstudentsession->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $vwstudentsession;

	// Call Recordset Selecting event
	$vwstudentsession->Recordset_Selecting($vwstudentsession->CurrentFilter);

	// Load list page sql
	$sSql = $vwstudentsession->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$vwstudentsession->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $vwstudentsession;
	$sFilter = $vwstudentsession->SqlKeyFilter();
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $vwstudentsession->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
	}

	// Call Row Selecting event
	$vwstudentsession->Row_Selecting($sFilter);

	// Load sql based on filter
	$vwstudentsession->CurrentFilter = $sFilter;
	$sSql = $vwstudentsession->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$vwstudentsession->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $vwstudentsession;
	$vwstudentsession->s_studentid->setDbValue($rs->fields('s_studentid'));
	$vwstudentsession->s_stuid->setDbValue($rs->fields('s_stuid'));
	$vwstudentsession->s_first_name->setDbValue($rs->fields('s_first_name'));
	$vwstudentsession->s_middle_name->setDbValue($rs->fields('s_middle_name'));
	$vwstudentsession->s_last_name->setDbValue($rs->fields('s_last_name'));
	$vwstudentsession->sessionid->setDbValue($rs->fields('sessionid'));
	$vwstudentsession->session_number->setDbValue($rs->fields('session_number'));
	$vwstudentsession->session_goal->setDbValue($rs->fields('session_goal'));
	$vwstudentsession->session_goal_completed->setDbValue($rs->fields('session_goal_completed'));
	$vwstudentsession->session_homework->setDbValue($rs->fields('session_homework'));
	$vwstudentsession->session_hmwrk_completed->setDbValue($rs->fields('session_hmwrk_completed'));
	$vwstudentsession->session_date->setDbValue($rs->fields('session_date'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $vwstudentsession;

	// Call Row Rendering event
	$vwstudentsession->Row_Rendering();

	// Common render codes for all row types
	// s_first_name

	$vwstudentsession->s_first_name->CellCssStyle = "";
	$vwstudentsession->s_first_name->CellCssClass = "";

	// s_middle_name
	$vwstudentsession->s_middle_name->CellCssStyle = "";
	$vwstudentsession->s_middle_name->CellCssClass = "";

	// s_last_name
	$vwstudentsession->s_last_name->CellCssStyle = "";
	$vwstudentsession->s_last_name->CellCssClass = "";

	// session_number
	$vwstudentsession->session_number->CellCssStyle = "";
	$vwstudentsession->session_number->CellCssClass = "";

	// session_goal
	$vwstudentsession->session_goal->CellCssStyle = "";
	$vwstudentsession->session_goal->CellCssClass = "";

	// session_goal_completed
	$vwstudentsession->session_goal_completed->CellCssStyle = "";
	$vwstudentsession->session_goal_completed->CellCssClass = "";

	// session_homework
	$vwstudentsession->session_homework->CellCssStyle = "";
	$vwstudentsession->session_homework->CellCssClass = "";

	// session_hmwrk_completed
	$vwstudentsession->session_hmwrk_completed->CellCssStyle = "";
	$vwstudentsession->session_hmwrk_completed->CellCssClass = "";
	
	// session_date
	$vwstudentsession->session_date->CellCssStyle = "";
	$vwstudentsession->session_date->CellCssClass = "";
	
	if ($vwstudentsession->RowType == EW_ROWTYPE_VIEW) { // View row

		// s_first_name
		$vwstudentsession->s_first_name->ViewValue = $vwstudentsession->s_first_name->CurrentValue;
		$vwstudentsession->s_first_name->CssStyle = "";
		$vwstudentsession->s_first_name->CssClass = "";
		$vwstudentsession->s_first_name->ViewCustomAttributes = "";

		// s_middle_name
		$vwstudentsession->s_middle_name->ViewValue = $vwstudentsession->s_middle_name->CurrentValue;
		$vwstudentsession->s_middle_name->CssStyle = "";
		$vwstudentsession->s_middle_name->CssClass = "";
		$vwstudentsession->s_middle_name->ViewCustomAttributes = "";

		// s_last_name
		$vwstudentsession->s_last_name->ViewValue = $vwstudentsession->s_last_name->CurrentValue;
		$vwstudentsession->s_last_name->CssStyle = "";
		$vwstudentsession->s_last_name->CssClass = "";
		$vwstudentsession->s_last_name->ViewCustomAttributes = "";

		// session_number
		$vwstudentsession->session_number->ViewValue = $vwstudentsession->session_number->CurrentValue;
		$vwstudentsession->session_number->CssStyle = "";
		$vwstudentsession->session_number->CssClass = "";
		$vwstudentsession->session_number->ViewCustomAttributes = "";

		// session_goal
		$vwstudentsession->session_goal->ViewValue = $vwstudentsession->session_goal->CurrentValue;
		$vwstudentsession->session_goal->CssStyle = "";
		$vwstudentsession->session_goal->CssClass = "";
		$vwstudentsession->session_goal->ViewCustomAttributes = "";

		// session_goal_completed
		$vwstudentsession->session_goal_completed->ViewValue = $vwstudentsession->session_goal_completed->CurrentValue;
		$vwstudentsession->session_goal_completed->CssStyle = "";
		$vwstudentsession->session_goal_completed->CssClass = "";
		$vwstudentsession->session_goal_completed->ViewCustomAttributes = "";

		// session_homework
		$vwstudentsession->session_homework->ViewValue = $vwstudentsession->session_homework->CurrentValue;
		$vwstudentsession->session_homework->CssStyle = "";
		$vwstudentsession->session_homework->CssClass = "";
		$vwstudentsession->session_homework->ViewCustomAttributes = "";

		// session_hmwrk_completed
		$vwstudentsession->session_hmwrk_completed->ViewValue = $vwstudentsession->session_hmwrk_completed->CurrentValue;
		$vwstudentsession->session_hmwrk_completed->CssStyle = "";
		$vwstudentsession->session_hmwrk_completed->CssClass = "";
		$vwstudentsession->session_hmwrk_completed->ViewCustomAttributes = "";
		
		// session_date
		$vwstudentsession->session_date->ViewValue = $vwstudentsession->session_date->CurrentValue;
		$vwstudentsession->session_date->ViewValue = ew_FormatDateTime($vwstudentsession->session_date->ViewValue, 5);
		$vwstudentsession->session_date->CssStyle = "";
		$vwstudentsession->session_date->CssClass = "";
		$vwstudentsession->session_date->ViewCustomAttributes = "";

		// s_first_name
		$vwstudentsession->s_first_name->HrefValue = "";

		// s_middle_name
		$vwstudentsession->s_middle_name->HrefValue = "";

		// s_last_name
		$vwstudentsession->s_last_name->HrefValue = "";

		// session_number
		$vwstudentsession->session_number->HrefValue = "";

		// session_goal
		$vwstudentsession->session_goal->HrefValue = "";

		// session_goal_completed
		$vwstudentsession->session_goal_completed->HrefValue = "";

		// session_homework
		$vwstudentsession->session_homework->HrefValue = "";

		// session_hmwrk_completed
		$vwstudentsession->session_hmwrk_completed->HrefValue = "";
		
		// session_date
		$vwstudentsession->session_date->HrefValue = "";
	} elseif ($vwstudentsession->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($vwstudentsession->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($vwstudentsession->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$vwstudentsession->Row_Rendered();
}
?>
<?php

// Show link optionally based on User ID
function ShowOptionLink() {
	global $Security, $vwstudentsession;
	if ($Security->IsLoggedIn()) {
		if (!$Security->IsAdmin()) {
			return $Security->IsValidUserID($vwstudentsession->s_studentid->CurrentValue);
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
