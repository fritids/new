<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
define("EW_TABLE_NAME", 'vwstudentprepprogram', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "vwstudentprepprograminfo.php" ?>
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
$vwstudentprepprogram->Export = @$_GET["export"]; // Get export parameter
$sExport = $vwstudentprepprogram->Export; // Get export parameter, used in header
$sExportFile = $vwstudentprepprogram->TableVar; // Get export file, used in header
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
	$sFilter = $vwstudentprepprogram->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
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
$vwstudentprepprogram->setSessionWhere($sFilter);
$vwstudentprepprogram->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$vwstudentprepprogram->setReturnUrl("vwstudentprepprogramlist.php");
?>
<?php include "header.php" ?>
<?php if ($vwstudentprepprogram->Export == "") { ?>
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
<?php if ($vwstudentprepprogram->Export == "") { ?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $vwstudentprepprogram->Export <> "");
$bSelectLimit = ($vwstudentprepprogram->Export == "" && $vwstudentprepprogram->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $vwstudentprepprogram->SelectRecordCount() : $rs->RecordCount();
$nStartRec = 1;
if ($nDisplayRecs <= 0) $nDisplayRecs = $nTotalRecs; // Display all records
if (!$bExportAll) SetUpStartRec(); // Set up start record position
if ($bSelectLimit) $rs = LoadRecordset($nStartRec-1, $nDisplayRecs);
?>
<p><span class="edge" style="white-space: nowrap;">Student's Prep Program
</span></p>
<?php if ($vwstudentprepprogram->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form method="post" name="fvwstudentprepprogramlist" id="fvwstudentprepprogramlist">
<?php if ($vwstudentprepprogram->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?>
<table border="0" cellspacing="5" cellpadding="5">
<?php
if (defined("EW_EXPORT_ALL") && $vwstudentprepprogram->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$vwstudentprepprogram->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;
		$ColCnt++;
		if ($ColCnt > $nRecPerRow) $ColCnt = 1;

	// Init row class and style
	$vwstudentprepprogram->CssClass = "ewTableRow";
	$vwstudentprepprogram->CssStyle = "";

	// Init row event
	$vwstudentprepprogram->RowClientEvents = "";
	LoadRowValues($rs); // Load row values
	$vwstudentprepprogram->RowType = EW_ROWTYPE_VIEW; // Render view
	RenderRow();
?>
<?php if ($ColCnt == 1) { ?>
<tr>
<?php } ?>
	<td width="268" valign="top"<?php echo $vwstudentprepprogram->DisplayAttributes() ?>>
	<table class="ewTable">
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentprepprogram->Export <> "") { ?>
Arithmetic
<?php } else { ?>
	Arithmetic<?php if ($vwstudentprepprogram->p_arithmetic->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentprepprogram->p_arithmetic->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $vwstudentprepprogram->p_arithmetic->CellAttributes() ?>>
<div<?php echo $vwstudentprepprogram->p_arithmetic->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_arithmetic->ViewValue ?></div></td>
		</tr>
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentprepprogram->Export <> "") { ?>
Algebra
<?php } else { ?>
	Algebra<?php if ($vwstudentprepprogram->p_algebra->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentprepprogram->p_algebra->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $vwstudentprepprogram->p_algebra->CellAttributes() ?>>
<div<?php echo $vwstudentprepprogram->p_algebra->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_algebra->ViewValue ?></div></td>
		</tr>
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentprepprogram->Export <> "") { ?>
Techniques
<?php } else { ?>
	Techniques<?php if ($vwstudentprepprogram->p_techniques->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentprepprogram->p_techniques->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $vwstudentprepprogram->p_techniques->CellAttributes() ?>>
<div<?php echo $vwstudentprepprogram->p_techniques->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_techniques->ViewValue ?></div></td>
		</tr>
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentprepprogram->Export <> "") { ?>
Geometry
<?php } else { ?>
	Geometry<?php if ($vwstudentprepprogram->p_geometry->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentprepprogram->p_geometry->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $vwstudentprepprogram->p_geometry->CellAttributes() ?>>
<div<?php echo $vwstudentprepprogram->p_geometry->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_geometry->ViewValue ?></div></td>
		</tr>
		<tr class="ewTableRow">
			<td width="100" class="ewTableHeader">
<?php if ($vwstudentprepprogram->Export <> "") { ?>
Advance Topics
<?php } else { ?>
	Advance Topics<?php if ($vwstudentprepprogram->p_advanced_topics->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($vwstudentprepprogram->p_advanced_topics->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $vwstudentprepprogram->p_advanced_topics->CellAttributes() ?>>
<div<?php echo $vwstudentprepprogram->p_advanced_topics->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_advanced_topics->ViewValue ?></div></td>
		</tr>
	</table>
<span class="edge">
<?php if ($vwstudentprepprogram->Export == "") { ?>
<?php } ?>
</span>	</td>
    <td width="311" valign="top"<?php echo $vwstudentprepprogram->DisplayAttributes() ?>><table class="ewTable">


      <tr class="ewTableRow">
        <td width="150" class="ewTableHeader"><?php if ($vwstudentprepprogram->Export <> "") { ?>
          Sentence Completion
          <?php } else { ?>
          Sentence Completion
          <?php if ($vwstudentprepprogram->p_sentence_completion->getSort() == "ASC") { ?>
          <img src="images/sortup.gif" width="10" height="9" border="0" />
          <?php } elseif ($vwstudentprepprogram->p_sentence_completion->getSort() == "DESC") { ?>
          <img src="images/sortdown.gif" width="10" height="9" border="0" />
          <?php } ?>
          <?php } ?>        </td>
        <td width="150"<?php echo $vwstudentprepprogram->p_sentence_completion->CellAttributes() ?>><div<?php echo $vwstudentprepprogram->p_sentence_completion->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_sentence_completion->ViewValue ?></div></td>
      </tr>
      <tr class="ewTableRow">
        <td width="150" class="ewTableHeader"><?php if ($vwstudentprepprogram->Export <> "") { ?>
          Critical Reading
          <?php } else { ?>
          Critical Reading
          <?php if ($vwstudentprepprogram->p_critical_reading->getSort() == "ASC") { ?>
          <img src="images/sortup.gif" width="10" height="9" border="0" />
          <?php } elseif ($vwstudentprepprogram->p_critical_reading->getSort() == "DESC") { ?>
          <img src="images/sortdown.gif" width="10" height="9" border="0" />
          <?php } ?>
          <?php } ?>        </td>
        <td width="150"<?php echo $vwstudentprepprogram->p_critical_reading->CellAttributes() ?>><div<?php echo $vwstudentprepprogram->p_critical_reading->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_critical_reading->ViewValue ?></div></td>
      </tr>
      <tr class="ewTableRow">
        <td width="150" class="ewTableHeader"><?php if ($vwstudentprepprogram->Export <> "") { ?>
          Error ID
          <?php } else { ?>
          Error ID
          <?php if ($vwstudentprepprogram->p_error_id->getSort() == "ASC") { ?>
          <img src="images/sortup.gif" width="10" height="9" border="0" />
          <?php } elseif ($vwstudentprepprogram->p_error_id->getSort() == "DESC") { ?>
          <img src="images/sortdown.gif" width="10" height="9" border="0" />
          <?php } ?>
          <?php } ?>        </td>
        <td width="150"<?php echo $vwstudentprepprogram->p_error_id->CellAttributes() ?>><div<?php echo $vwstudentprepprogram->p_error_id->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_error_id->ViewValue ?></div></td>
      </tr>
      <tr class="ewTableRow">
        <td width="150" class="ewTableHeader"><?php if ($vwstudentprepprogram->Export <> "") { ?>
          Sentence Improvement
          <?php } else { ?>
          Sentence Improvement
          <?php if ($vwstudentprepprogram->p_sentence_improvement->getSort() == "ASC") { ?>
          <img src="images/sortup.gif" width="10" height="9" border="0" />
          <?php } elseif ($vwstudentprepprogram->p_sentence_improvement->getSort() == "DESC") { ?>
          <img src="images/sortdown.gif" width="10" height="9" border="0" />
          <?php } ?>
          <?php } ?>        </td>
        <td width="150"<?php echo $vwstudentprepprogram->p_sentence_improvement->CellAttributes() ?>><div<?php echo $vwstudentprepprogram->p_sentence_improvement->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_sentence_improvement->ViewValue ?></div></td>
      </tr>
      <tr class="ewTableRow">
        <td width="150" class="ewTableHeader"><?php if ($vwstudentprepprogram->Export <> "") { ?>
          Paragraph Improvement
          <?php } else { ?>
          Paragraph Improvement
          <?php if ($vwstudentprepprogram->p_paragraph_improvement->getSort() == "ASC") { ?>
          <img src="images/sortup.gif" width="10" height="9" border="0" />
          <?php } elseif ($vwstudentprepprogram->p_paragraph_improvement->getSort() == "DESC") { ?>
          <img src="images/sortdown.gif" width="10" height="9" border="0" />
          <?php } ?>
          <?php } ?>        </td>
        <td width="150"<?php echo $vwstudentprepprogram->p_paragraph_improvement->CellAttributes() ?>><div<?php echo $vwstudentprepprogram->p_paragraph_improvement->ViewAttributes() ?>><?php echo $vwstudentprepprogram->p_paragraph_improvement->ViewValue ?></div></td>
      </tr>
    </table></td>
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
	<tr><td width="268">&nbsp;</td>
      <td width="311">&nbsp;</td>
      <?php } ?>
</tr>
<?php } ?>
</table>
<?php if ($vwstudentprepprogram->Export == "") { ?>
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
<?php if ($vwstudentprepprogram->Export == "") { ?>

<?php } ?>
<?php if ($vwstudentprepprogram->Export == "") { ?>
<?php } ?>
<?php if ($vwstudentprepprogram->Export == "") { ?>
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
	global $vwstudentprepprogram;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$vwstudentprepprogram->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$vwstudentprepprogram->CurrentOrderType = @$_GET["ordertype"];
		$vwstudentprepprogram->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $vwstudentprepprogram->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($vwstudentprepprogram->SqlOrderBy() <> "") {
			$sOrderBy = $vwstudentprepprogram->SqlOrderBy();
			$vwstudentprepprogram->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $vwstudentprepprogram;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$vwstudentprepprogram->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$vwstudentprepprogram->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $vwstudentprepprogram;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$vwstudentprepprogram->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$vwstudentprepprogram->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $vwstudentprepprogram->getStartRecordNumber();
		}
	} else {
		$nStartRec = $vwstudentprepprogram->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$vwstudentprepprogram->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$vwstudentprepprogram->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$vwstudentprepprogram->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $vwstudentprepprogram;

	// Call Recordset Selecting event
	$vwstudentprepprogram->Recordset_Selecting($vwstudentprepprogram->CurrentFilter);

	// Load list page sql
	$sSql = $vwstudentprepprogram->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$vwstudentprepprogram->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $vwstudentprepprogram;
	$sFilter = $vwstudentprepprogram->SqlKeyFilter();
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $vwstudentprepprogram->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
	}

	// Call Row Selecting event
	$vwstudentprepprogram->Row_Selecting($sFilter);

	// Load sql based on filter
	$vwstudentprepprogram->CurrentFilter = $sFilter;
	$sSql = $vwstudentprepprogram->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$vwstudentprepprogram->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $vwstudentprepprogram;
	$vwstudentprepprogram->s_studentid->setDbValue($rs->fields('s_studentid'));
	$vwstudentprepprogram->s_first_name->setDbValue($rs->fields('s_first_name'));
	$vwstudentprepprogram->s_middle_name->setDbValue($rs->fields('s_middle_name'));
	$vwstudentprepprogram->s_last_name->setDbValue($rs->fields('s_last_name'));
	$vwstudentprepprogram->p_prepid->setDbValue($rs->fields('p_prepid'));
	$vwstudentprepprogram->p_arithmetic->setDbValue($rs->fields('p_arithmetic'));
	$vwstudentprepprogram->p_algebra->setDbValue($rs->fields('p_algebra'));
	$vwstudentprepprogram->p_techniques->setDbValue($rs->fields('p_techniques'));
	$vwstudentprepprogram->p_geometry->setDbValue($rs->fields('p_geometry'));
	$vwstudentprepprogram->p_advanced_topics->setDbValue($rs->fields('p_advanced_topics'));
	$vwstudentprepprogram->p_sentence_completion->setDbValue($rs->fields('p_sentence_completion'));
	$vwstudentprepprogram->p_critical_reading->setDbValue($rs->fields('p_critical_reading'));
	$vwstudentprepprogram->p_error_id->setDbValue($rs->fields('p_error_id'));
	$vwstudentprepprogram->p_sentence_improvement->setDbValue($rs->fields('p_sentence_improvement'));
	$vwstudentprepprogram->p_paragraph_improvement->setDbValue($rs->fields('p_paragraph_improvement'));
	$vwstudentprepprogram->s_stuid->setDbValue($rs->fields('s_stuid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $vwstudentprepprogram;

	// Call Row Rendering event
	$vwstudentprepprogram->Row_Rendering();

	// Common render codes for all row types
	// s_first_name

	$vwstudentprepprogram->s_first_name->CellCssStyle = "";
	$vwstudentprepprogram->s_first_name->CellCssClass = "";

	// s_middle_name
	$vwstudentprepprogram->s_middle_name->CellCssStyle = "";
	$vwstudentprepprogram->s_middle_name->CellCssClass = "";

	// s_last_name
	$vwstudentprepprogram->s_last_name->CellCssStyle = "";
	$vwstudentprepprogram->s_last_name->CellCssClass = "";

	// p_arithmetic
	$vwstudentprepprogram->p_arithmetic->CellCssStyle = "";
	$vwstudentprepprogram->p_arithmetic->CellCssClass = "";

	// p_algebra
	$vwstudentprepprogram->p_algebra->CellCssStyle = "";
	$vwstudentprepprogram->p_algebra->CellCssClass = "";

	// p_techniques
	$vwstudentprepprogram->p_techniques->CellCssStyle = "";
	$vwstudentprepprogram->p_techniques->CellCssClass = "";

	// p_geometry
	$vwstudentprepprogram->p_geometry->CellCssStyle = "";
	$vwstudentprepprogram->p_geometry->CellCssClass = "";

	// p_advanced_topics
	$vwstudentprepprogram->p_advanced_topics->CellCssStyle = "";
	$vwstudentprepprogram->p_advanced_topics->CellCssClass = "";

	// p_sentence_completion
	$vwstudentprepprogram->p_sentence_completion->CellCssStyle = "";
	$vwstudentprepprogram->p_sentence_completion->CellCssClass = "";

	// p_critical_reading
	$vwstudentprepprogram->p_critical_reading->CellCssStyle = "";
	$vwstudentprepprogram->p_critical_reading->CellCssClass = "";

	// p_error_id
	$vwstudentprepprogram->p_error_id->CellCssStyle = "";
	$vwstudentprepprogram->p_error_id->CellCssClass = "";

	// p_sentence_improvement
	$vwstudentprepprogram->p_sentence_improvement->CellCssStyle = "";
	$vwstudentprepprogram->p_sentence_improvement->CellCssClass = "";

	// p_paragraph_improvement
	$vwstudentprepprogram->p_paragraph_improvement->CellCssStyle = "";
	$vwstudentprepprogram->p_paragraph_improvement->CellCssClass = "";
	if ($vwstudentprepprogram->RowType == EW_ROWTYPE_VIEW) { // View row

		// s_first_name
		$vwstudentprepprogram->s_first_name->ViewValue = $vwstudentprepprogram->s_first_name->CurrentValue;
		$vwstudentprepprogram->s_first_name->CssStyle = "";
		$vwstudentprepprogram->s_first_name->CssClass = "";
		$vwstudentprepprogram->s_first_name->ViewCustomAttributes = "";

		// s_middle_name
		$vwstudentprepprogram->s_middle_name->ViewValue = $vwstudentprepprogram->s_middle_name->CurrentValue;
		$vwstudentprepprogram->s_middle_name->CssStyle = "";
		$vwstudentprepprogram->s_middle_name->CssClass = "";
		$vwstudentprepprogram->s_middle_name->ViewCustomAttributes = "";

		// s_last_name
		$vwstudentprepprogram->s_last_name->ViewValue = $vwstudentprepprogram->s_last_name->CurrentValue;
		$vwstudentprepprogram->s_last_name->CssStyle = "";
		$vwstudentprepprogram->s_last_name->CssClass = "";
		$vwstudentprepprogram->s_last_name->ViewCustomAttributes = "";

		// p_arithmetic
		$vwstudentprepprogram->p_arithmetic->ViewValue = $vwstudentprepprogram->p_arithmetic->CurrentValue;
		$vwstudentprepprogram->p_arithmetic->CssStyle = "";
		$vwstudentprepprogram->p_arithmetic->CssClass = "";
		$vwstudentprepprogram->p_arithmetic->ViewCustomAttributes = "";

		// p_algebra
		$vwstudentprepprogram->p_algebra->ViewValue = $vwstudentprepprogram->p_algebra->CurrentValue;
		$vwstudentprepprogram->p_algebra->CssStyle = "";
		$vwstudentprepprogram->p_algebra->CssClass = "";
		$vwstudentprepprogram->p_algebra->ViewCustomAttributes = "";

		// p_techniques
		$vwstudentprepprogram->p_techniques->ViewValue = $vwstudentprepprogram->p_techniques->CurrentValue;
		$vwstudentprepprogram->p_techniques->CssStyle = "";
		$vwstudentprepprogram->p_techniques->CssClass = "";
		$vwstudentprepprogram->p_techniques->ViewCustomAttributes = "";

		// p_geometry
		$vwstudentprepprogram->p_geometry->ViewValue = $vwstudentprepprogram->p_geometry->CurrentValue;
		$vwstudentprepprogram->p_geometry->CssStyle = "";
		$vwstudentprepprogram->p_geometry->CssClass = "";
		$vwstudentprepprogram->p_geometry->ViewCustomAttributes = "";

		// p_advanced_topics
		$vwstudentprepprogram->p_advanced_topics->ViewValue = $vwstudentprepprogram->p_advanced_topics->CurrentValue;
		$vwstudentprepprogram->p_advanced_topics->CssStyle = "";
		$vwstudentprepprogram->p_advanced_topics->CssClass = "";
		$vwstudentprepprogram->p_advanced_topics->ViewCustomAttributes = "";

		// p_sentence_completion
		$vwstudentprepprogram->p_sentence_completion->ViewValue = $vwstudentprepprogram->p_sentence_completion->CurrentValue;
		$vwstudentprepprogram->p_sentence_completion->CssStyle = "";
		$vwstudentprepprogram->p_sentence_completion->CssClass = "";
		$vwstudentprepprogram->p_sentence_completion->ViewCustomAttributes = "";

		// p_critical_reading
		$vwstudentprepprogram->p_critical_reading->ViewValue = $vwstudentprepprogram->p_critical_reading->CurrentValue;
		$vwstudentprepprogram->p_critical_reading->CssStyle = "";
		$vwstudentprepprogram->p_critical_reading->CssClass = "";
		$vwstudentprepprogram->p_critical_reading->ViewCustomAttributes = "";

		// p_error_id
		$vwstudentprepprogram->p_error_id->ViewValue = $vwstudentprepprogram->p_error_id->CurrentValue;
		$vwstudentprepprogram->p_error_id->CssStyle = "";
		$vwstudentprepprogram->p_error_id->CssClass = "";
		$vwstudentprepprogram->p_error_id->ViewCustomAttributes = "";

		// p_sentence_improvement
		$vwstudentprepprogram->p_sentence_improvement->ViewValue = $vwstudentprepprogram->p_sentence_improvement->CurrentValue;
		$vwstudentprepprogram->p_sentence_improvement->CssStyle = "";
		$vwstudentprepprogram->p_sentence_improvement->CssClass = "";
		$vwstudentprepprogram->p_sentence_improvement->ViewCustomAttributes = "";

		// p_paragraph_improvement
		$vwstudentprepprogram->p_paragraph_improvement->ViewValue = $vwstudentprepprogram->p_paragraph_improvement->CurrentValue;
		$vwstudentprepprogram->p_paragraph_improvement->CssStyle = "";
		$vwstudentprepprogram->p_paragraph_improvement->CssClass = "";
		$vwstudentprepprogram->p_paragraph_improvement->ViewCustomAttributes = "";

		// s_first_name
		$vwstudentprepprogram->s_first_name->HrefValue = "";

		// s_middle_name
		$vwstudentprepprogram->s_middle_name->HrefValue = "";

		// s_last_name
		$vwstudentprepprogram->s_last_name->HrefValue = "";

		// p_arithmetic
		$vwstudentprepprogram->p_arithmetic->HrefValue = "";

		// p_algebra
		$vwstudentprepprogram->p_algebra->HrefValue = "";

		// p_techniques
		$vwstudentprepprogram->p_techniques->HrefValue = "";

		// p_geometry
		$vwstudentprepprogram->p_geometry->HrefValue = "";

		// p_advanced_topics
		$vwstudentprepprogram->p_advanced_topics->HrefValue = "";

		// p_sentence_completion
		$vwstudentprepprogram->p_sentence_completion->HrefValue = "";

		// p_critical_reading
		$vwstudentprepprogram->p_critical_reading->HrefValue = "";

		// p_error_id
		$vwstudentprepprogram->p_error_id->HrefValue = "";

		// p_sentence_improvement
		$vwstudentprepprogram->p_sentence_improvement->HrefValue = "";

		// p_paragraph_improvement
		$vwstudentprepprogram->p_paragraph_improvement->HrefValue = "";
	} elseif ($vwstudentprepprogram->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($vwstudentprepprogram->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($vwstudentprepprogram->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$vwstudentprepprogram->Row_Rendered();
}
?>
<?php

// Show link optionally based on User ID
function ShowOptionLink() {
	global $Security, $vwstudentprepprogram;
	if ($Security->IsLoggedIn()) {
		if (!$Security->IsAdmin()) {
			return $Security->IsValidUserID($vwstudentprepprogram->s_studentid->CurrentValue);
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
