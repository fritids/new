<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
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
if ($sDbDetailFilter <> "") {
	if ($sFilter <> "") $sFilter .= " AND ";
	$sFilter .= "(" . $sDbDetailFilter . ")";
}
if ($sSrchWhere <> "") {
	if ($sFilter <> "") $sFilter .= " AND ";
	$sFilter .= "(" . $sSrchWhere . ")";
}

// Set up filter in Session
$tbl_aduser->setSessionWhere($sFilter);
$tbl_aduser->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$tbl_aduser->setReturnUrl("tbl_aduserlist.php");
?>
<?php include "header.php" ?>
<?php if ($tbl_aduser->Export == "") { ?>
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
var ew_DHTMLEditors = [];

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
<?php if ($tbl_aduser->Export == "") { ?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $tbl_aduser->Export <> "");
$bSelectLimit = ($tbl_aduser->Export == "" && $tbl_aduser->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $tbl_aduser->SelectRecordCount() : $rs->RecordCount();
$nStartRec = 1;
if ($nDisplayRecs <= 0) $nDisplayRecs = $nTotalRecs; // Display all records
if (!$bExportAll) SetUpStartRec(); // Set up start record position
if ($bSelectLimit) $rs = LoadRecordset($nStartRec-1, $nDisplayRecs);
?>
<p><span class="edge" style="white-space: nowrap;">Admin List
</span></p>
<?php if ($tbl_aduser->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form method="post" name="ftbl_aduserlist" id="ftbl_aduserlist">
<?php if ($tbl_aduser->Export == "") { ?>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?>
<table id="ewlistmain" class="ewTable">
<?php
	$OptionCnt = 0;
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // view
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // edit
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // delete
}
?>
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td width="150" valign="top">
<?php if ($tbl_aduser->Export <> "") { ?>
First Name
<?php } else { ?>
	First Name<?php if ($tbl_aduser->a_first_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_aduser->a_first_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="150" valign="top"><?php if ($tbl_aduser->Export <> "") { ?>
Last Name
  <?php } else { ?>
Last Name
<?php if ($tbl_aduser->a_last_name->getSort() == "ASC") { ?>
<img src="images/sortup.gif" width="10" height="9" border="0" />
<?php } elseif ($tbl_aduser->a_last_name->getSort() == "DESC") { ?>
<img src="images/sortdown.gif" width="10" height="9" border="0" />
<?php } ?>
<?php } ?></td>
	  <td width="150" valign="top"><?php if ($tbl_aduser->Export <> "") { ?>
Username
  <?php } else { ?>
Username
<?php if ($tbl_aduser->a_uname->getSort() == "ASC") { ?>
<img src="images/sortup.gif" width="10" height="9" border="0" />
<?php } elseif ($tbl_aduser->a_uname->getSort() == "DESC") { ?>
<img src="images/sortdown.gif" width="10" height="9" border="0" />
<?php } ?>
<?php } ?></td>
	</tr>
<?php
if (defined("EW_EXPORT_ALL") && $tbl_aduser->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$tbl_aduser->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;

	// Init row class and style
	$tbl_aduser->CssClass = "ewTableRow";
	$tbl_aduser->CssStyle = "";

	// Init row event
	$tbl_aduser->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
	LoadRowValues($rs); // Load row values
	$tbl_aduser->RowType = EW_ROWTYPE_VIEW; // Render view
	RenderRow();
?>
	<!-- Table body -->
	<tr<?php echo $tbl_aduser->DisplayAttributes() ?>>
		<!-- a_uname -->
		<!-- a_first_name -->
		<td width="150"<?php echo $tbl_aduser->a_first_name->CellAttributes() ?>>
<div<?php echo $tbl_aduser->a_first_name->ViewAttributes() ?>><?php echo $tbl_aduser->a_first_name->ViewValue ?></div></td>
		<td width="150"<?php echo $tbl_aduser->a_last_name->CellAttributes() ?>><div<?php echo $tbl_aduser->a_last_name->ViewAttributes() ?>><?php echo $tbl_aduser->a_last_name->ViewValue ?></div></td>
		<!-- a_last_name -->
		<td width="150"<?php echo $tbl_aduser->a_last_name->CellAttributes() ?>><div<?php echo $tbl_aduser->a_uname->ViewAttributes() ?>><?php echo $tbl_aduser->a_uname->ViewValue ?></div></td>
		<!-- a_email -->
		<!-- a_mobile -->
		<!-- a_pwd -->
	  <?php if ($tbl_aduser->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<td nowrap><span class="edge">
<a href="<?php echo $tbl_aduser->ViewUrl() ?>">View</a>
</span></td>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<td nowrap><span class="edge">
<a href="<?php echo $tbl_aduser->EditUrl() ?>">Edit</a>
</span></td>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<td nowrap><span class="edge">
<a href="<?php echo $tbl_aduser->DeleteUrl() ?>">Delete</a>
</span></td>
<?php } ?>
<?php } ?>
	</tr>
<?php
	}
	$rs->MoveNext();
}
?>
</table>
<?php if ($tbl_aduser->Export == "") { ?>
<table>
	<tr><td><span class="edge">
<?php if ($Security->IsLoggedIn()) { ?>
<a href="tbl_aduseradd.php">Add</a>&nbsp;&nbsp;
<?php } ?>
	</span></td></tr>
</table>
<?php } ?>
<?php } ?>
</form>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($tbl_aduser->Export == "") { ?>
<form action="tbl_aduserlist.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap>
<span class="edge">
<?php if (!isset($Pager)) $Pager = new cNumericPager($nStartRec, $nDisplayRecs, $nTotalRecs, $nRecRange) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<a href="tbl_aduserlist.php?start=<?php echo $Pager->FirstButton->Start ?>"><b>First</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<a href="tbl_aduserlist.php?start=<?php echo $Pager->PrevButton->Start ?>"><b>Previous</b></a>&nbsp;
	<?php } ?>
	<?php foreach ($Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="tbl_aduserlist.php?start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($Pager->NextButton->Enabled) { ?>
	<a href="tbl_aduserlist.php?start=<?php echo $Pager->NextButton->Start ?>"><b>Next</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->LastButton->Enabled) { ?>
	<a href="tbl_aduserlist.php?start=<?php echo $Pager->LastButton->Start ?>"><b>Last</b></a>&nbsp;
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
<?php if ($tbl_aduser->Export == "") { ?>
<?php } ?>
<?php if ($tbl_aduser->Export == "") { ?>
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
	global $tbl_aduser;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$tbl_aduser->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$tbl_aduser->CurrentOrderType = @$_GET["ordertype"];
		$tbl_aduser->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $tbl_aduser->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($tbl_aduser->SqlOrderBy() <> "") {
			$sOrderBy = $tbl_aduser->SqlOrderBy();
			$tbl_aduser->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $tbl_aduser;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$tbl_aduser->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$tbl_aduser->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $tbl_aduser;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$tbl_aduser->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$tbl_aduser->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $tbl_aduser->getStartRecordNumber();
		}
	} else {
		$nStartRec = $tbl_aduser->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$tbl_aduser->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$tbl_aduser->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$tbl_aduser->setStartRecordNumber($nStartRec);
	}
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
