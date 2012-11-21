<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_session', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_sessioninfo.php" ?>
<?php include "userfn50.php" ?>
<?php include "tbl_instructorsinfo.php" ?>
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
$tbl_session->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_session->Export; // Get export parameter, used in header
$sExportFile = $tbl_session->TableVar; // Get export file, used in header
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

// Set up master detail parameters
SetUpMasterDetail();

// Check QueryString parameters
if (@$_GET["a"] <> "") {
	$tbl_session->CurrentAction = $_GET["a"];

	// Clear inline mode
	if ($tbl_session->CurrentAction == "cancel") {
		ClearInlineMode();
	}

	// Switch to inline edit mode
	if ($tbl_session->CurrentAction == "edit") {
		InlineEditMode();
	}

	// Switch to inline add mode
	if ($tbl_session->CurrentAction == "add" || $tbl_session->CurrentAction == "copy") {
		InlineAddMode();
	}
} else {

	// Create form object
	$objForm = new cFormObj;
	if (@$_POST["a_list"] <> "") {
		$tbl_session->CurrentAction = $_POST["a_list"]; // Get action

		// Inline Update
		if ($tbl_session->CurrentAction == "update" && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit") {
			InlineUpdate();
		}

		// Insert Inline
		if ($tbl_session->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add") {
			InlineInsert();
		}
	}
}

// Build filter
$sFilter = "";
if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
	if ($tbl_session->getCurrentMasterTable() == "tbl_students") {
		$sFilter = $tbl_session->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add detail User ID filter
		$sDbMasterFilter = $tbl_session->AddMasterUserIDFilter($sDbMasterFilter, "tbl_students", $Security->CurrentUserID()); // Add master User ID filter
	}
}
if ($sDbDetailFilter <> "") {
	if ($sFilter <> "") $sFilter .= " AND ";
	$sFilter .= "(" . $sDbDetailFilter . ")";
}
if ($sSrchWhere <> "") {
	if ($sFilter <> "") $sFilter .= " AND ";
	$sFilter .= "(" . $sSrchWhere . ")";
}

// Load master record
if ($tbl_session->getMasterFilter() <> "" && $tbl_session->getCurrentMasterTable() == "tbl_students") {
	$rsmaster = $tbl_students->LoadRs($sDbMasterFilter);
	$bMasterRecordExists = ($rsmaster && !$rsmaster->EOF);
	if (!$bMasterRecordExists) {
		$tbl_session->setMasterFilter(""); // Clear master filter
		$tbl_session->setDetailFilter(""); // Clear detail filter
		$_SESSION[EW_SESSION_MESSAGE] = "No records found"; // Set no record found
		Page_Terminate("tbl_studentslist.php"); // Return to caller
	} else {
		$tbl_students->LoadListRowValues($rsmaster);
		$tbl_students->RenderListRow();
		$rsmaster->Close();
	}
}

// Set up filter in Session
$tbl_session->setSessionWhere($sFilter);
$tbl_session->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$tbl_session->setReturnUrl("tbl_sessionlist.php");
?>
<?php include "header.php" ?>
<?php if ($tbl_session->Export == "") { ?>
<script type="text/javascript">
<!--
var EW_PAGE_ID = "list"; // Page id

//-->
</script>
<script type="text/javascript">
<!--

function ew_ValidateForm(fobj) {
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var i, elm, aelm, infix;
	var rowcnt = (fobj.key_count) ? Number(fobj.key_count.value) : 1;
	for (i=0; i<rowcnt; i++) {
		infix = (fobj.key_count) ? String(i+1) : "";
		elm = fobj.elements["x" + infix + "_session_number"];
		if (elm && !ew_CheckInteger(elm.value)) {
			if (!ew_OnError(elm, "Incorrect integer - Session Number"))
				return false; 
		}
	}
	return true;
}

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
<?php if ($tbl_session->Export == "") { ?>
<?php
$sMasterReturnUrl = "tbl_studentslist.php";
if ($tbl_session->getMasterFilter() <> "" && $tbl_session->getCurrentMasterTable() == "tbl_students") {
	if ($bMasterRecordExists) {
		if ($tbl_session->getCurrentMasterTable() == $tbl_session->TableVar) $sMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include "tbl_studentsmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $tbl_session->Export <> "");
$bSelectLimit = ($tbl_session->Export == "" && $tbl_session->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $tbl_session->SelectRecordCount() : $rs->RecordCount();
$nStartRec = 1;
if ($nDisplayRecs <= 0) $nDisplayRecs = $nTotalRecs; // Display all records
if (!$bExportAll) SetUpStartRec(); // Set up start record position
if ($bSelectLimit) $rs = LoadRecordset($nStartRec-1, $nDisplayRecs);
?>
<table cellspacing="5" class="ewTable">
  <tr class="ewTableRow">
    <td width="150"><?php if ($Security->IsLoggedIn()) { ?>
        <?php if (ShowOptionLink()) { ?>
        <a href="tbl_prep_programslist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&amp;s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Prep Programs</a> &nbsp;
        <?php } ?>
        <?php } ?></td>
    <td width="150"><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <?php if (ShowOptionLink()) { ?>
      <a href="tbl_sessionlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&amp;s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Sessions</a> &nbsp;
      <?php } ?>
      <?php } ?>
    </span></td>
    <td width="150"><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <?php if (ShowOptionLink()) { ?>
      <a href="tbl_actual_satlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&amp;s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Actual SAT</a> &nbsp;
      <?php } ?>
      <?php } ?>
    </span></td>
    <td width="150"><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <?php if (ShowOptionLink()) { ?>
      <a href="tbl_testing_satlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&amp;s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Test SAT</a> &nbsp;
      <?php } ?>
      <?php } ?>
    </span></td>
  </tr>
  <tr class="ewTableRow">
    <td width="150"<?php echo $tbl_students->s_middle_name->CellAttributes() ?>><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <?php if (ShowOptionLink()) { ?>
      <a href="tbl_actual_actlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&amp;s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Actual ACT</a> &nbsp;
      <?php } ?>
      <?php } ?>
    </span></td>
    <td width="150"<?php echo $tbl_students->s_middle_name->CellAttributes() ?>><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <?php if (ShowOptionLink()) { ?>
      <a href="tbl_testing_actlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&amp;s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">Test ACT</a> &nbsp;
      <?php } ?>
      <?php } ?>
    </span></td>
    <td width="150"<?php echo $tbl_students->s_middle_name->CellAttributes() ?>><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <?php if (ShowOptionLink()) { ?>
      <a href="tbl_psatlist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_students&amp;s_studentid=<?php echo urlencode(strval($tbl_students->s_studentid->CurrentValue)) ?>">PSAT</a> &nbsp;
      <?php } ?>
      <?php } ?>
    </span></td>
    <td width="150"<?php echo $tbl_students->s_middle_name->CellAttributes() ?>><?php if (ShowOptionLink()) { ?>
        <a href="<?php echo $tbl_students->ViewUrl() ?>">View Profile </a>
        <?php } ?></td>
  </tr>
  <tr class="ewTableRow">
    <td<?php echo $tbl_students->s_middle_name->CellAttributes() ?>>&nbsp;</td>
    <td<?php echo $tbl_students->s_middle_name->CellAttributes() ?>>&nbsp;</td>
    <td<?php echo $tbl_students->s_middle_name->CellAttributes() ?>>&nbsp;</td>
    <td<?php echo $tbl_students->s_middle_name->CellAttributes() ?>>&nbsp;</td>
  </tr>
</table>
<p><span class="edge" style="white-space: nowrap;">Student's Sessions
</span></p>
<?php if ($tbl_session->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form name="ftbl_sessionlist" id="ftbl_sessionlist" action="tbl_sessionlist.php" method="post">
<?php if ($tbl_session->Export == "") { ?>
<?php } ?>
<?php if ($nTotalRecs > 0 || $tbl_session->CurrentAction == "add" || $tbl_session->CurrentAction == "copy") { ?>
<table id="ewlistmain" class="ewTable">
<?php
	$OptionCnt = 0;
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // edit
}
?>
	<!-- Table header -->
	<tr class="ewTableHeader">
	
			<td width="100" valign="top">
<?php if ($tbl_session->Export <> "") { ?>
Date
<?php } else { ?>
	Date<?php if ($tbl_session->session_date->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_session->session_date->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
			<td width="100" valign="top">
<?php if ($tbl_session->Export <> "") { ?>
Session Number
<?php } else { ?>
	Number
	<?php if ($tbl_session->session_number->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_session->session_number->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="100" valign="top">
<?php if ($tbl_session->Export <> "") { ?>
Goal
<?php } else { ?>
	Goal<?php if ($tbl_session->session_goal->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_session->session_goal->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="100" valign="top">
<?php if ($tbl_session->Export <> "") { ?>
Completed
<?php } else { ?>
	Completed<?php if ($tbl_session->session_goal_completed->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_session->session_goal_completed->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="100" valign="top">
<?php if ($tbl_session->Export <> "") { ?>
Homework
<?php } else { ?>
	Homework<?php if ($tbl_session->session_homework->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_session->session_homework->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="100" valign="top">
<?php if ($tbl_session->Export <> "") { ?>
Completed
<?php } else { ?>
	Completed<?php if ($tbl_session->session_hmwrk_completed->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_session->session_hmwrk_completed->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		
	</tr>
<?php
	if ($tbl_session->CurrentAction == "add" || $tbl_session->CurrentAction == "copy") {
		$RowIndex = 1;
		if ($tbl_session->EventCancelled) { // Insert failed
			RestoreFormValues(); // Restore form values
		}

		// Init row class and style
		$tbl_session->CssClass = "ewTableEditRow"; // edit
		$tbl_session->CssStyle = "";

		// Init row event
		$tbl_session->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";

		// Render add row
		$tbl_session->RowType = EW_ROWTYPE_ADD;
		RenderRow();
?>
	<tr<?php echo $tbl_session->DisplayAttributes() ?>>
	
		<!-- session_date -->
		<td width="100">
<input type="text" name="x<?php echo $RowIndex ?>_session_date" id="x<?php echo $RowIndex ?>_session_date" title="" size="10" value="<?php echo $tbl_session->session_date->EditValue ?>"<?php echo $tbl_session->session_date->EditAttributes() ?>>
</td>

		<!-- session_number -->
		<td width="100">
<input type="text" name="x<?php echo $RowIndex ?>_session_number" id="x<?php echo $RowIndex ?>_session_number" title="" size="5" value="<?php echo $tbl_session->session_number->EditValue ?>"<?php echo $tbl_session->session_number->EditAttributes() ?>>
</td>
		<!-- session_goal -->
		<td width="100">
<input type="text" name="x<?php echo $RowIndex ?>_session_goal" id="x<?php echo $RowIndex ?>_session_goal" title="" size="5" maxlength="125" value="<?php echo $tbl_session->session_goal->EditValue ?>"<?php echo $tbl_session->session_goal->EditAttributes() ?>>
</td>
		<!-- session_goal_completed -->
		<td width="100">
<input type="text" name="x<?php echo $RowIndex ?>_session_goal_completed" id="x<?php echo $RowIndex ?>_session_goal_completed" title="" size="5" maxlength="125" value="<?php echo $tbl_session->session_goal_completed->EditValue ?>"<?php echo $tbl_session->session_goal_completed->EditAttributes() ?>>
</td>
		<!-- session_homework -->
		<td width="100">
<input type="text" name="x<?php echo $RowIndex ?>_session_homework" id="x<?php echo $RowIndex ?>_session_homework" title="" size="5" maxlength="125" value="<?php echo $tbl_session->session_homework->EditValue ?>"<?php echo $tbl_session->session_homework->EditAttributes() ?>>
</td>
		<!-- session_hmwrk_completed -->
		<td width="100">
<input type="text" name="x<?php echo $RowIndex ?>_session_hmwrk_completed" id="x<?php echo $RowIndex ?>_session_hmwrk_completed" title="" size="5" maxlength="125" value="<?php echo $tbl_session->session_hmwrk_completed->EditValue ?>"<?php echo $tbl_session->session_hmwrk_completed->EditAttributes() ?>>
</td>
		<!-- s_stuid -->
		<td style="white-space: nowrap;">
<?php if ($tbl_session->s_stuid->getSessionValue() <> "") { ?>
<input type="hidden" id="x<?php echo $RowIndex ?>_s_stuid" name="x<?php echo $RowIndex ?>_s_stuid" value="<?php echo ew_HtmlEncode($tbl_session->s_stuid->CurrentValue) ?>">
<?php } else { ?>
<div class='ewAstList' style='visibility:hidden' id='as_x<?php echo $RowIndex ?>_s_stuid'><input type="hidden" name="x<?php echo $RowIndex ?>_s_stuid" id="x<?php echo $RowIndex ?>_s_stuid" title="" size="30" value="<?php echo $tbl_session->s_stuid->EditValue ?>" onblur="ew_AstHideDiv('as_x<?php echo $RowIndex ?>_s_stuid');" onkeydown="ew_AstOnKeyDown('x<?php echo $RowIndex ?>_s_stuid', 'as_x<?php echo $RowIndex ?>_s_stuid', event);" onkeypress="return ew_AstOnKeyPress(event);" onkeyup="ew_AstOnKeyUp('x<?php echo $RowIndex ?>_s_stuid', 'as_x<?php echo $RowIndex ?>_s_stuid', event);" autocomplete="off"></div>
<div class='ewAstList' style='visibility:hidden' id='as_x<?php echo $RowIndex ?>_s_stuid'></div>
<input type="hidden" name="sv_x<?php echo $RowIndex ?>_s_stuid" id="sv_x<?php echo $RowIndex ?>_s_stuid" value="">
<?php
	$sSqlWrk = "SELECT DISTINCT `s_studentid`, '' FROM `tbl_students` WHERE (`s_studentid` LIKE @FILTER_VALUE%)";
	$sSqlWrk .= " AND " . "`s_studentid`";
	$sSqlWrk = TEAencrypt($sSqlWrk, EW_RANDOM_KEY);
?>
<input type="hidden" name="s_x<?php echo $RowIndex ?>_s_stuid" id="s_x<?php echo $RowIndex ?>_s_stuid" value="<?php echo $sSqlWrk ?>">
<input type="hidden" name="lt_x<?php echo $RowIndex ?>_s_stuid" id="lt_x<?php echo $RowIndex ?>_s_stuid" value="1">
<?php } ?>
</td>
<td colspan="<?php echo $OptionCnt ?>"><span class="edge">
<a href="" onClick="if (ew_ValidateForm(document.ftbl_sessionlist)) document.ftbl_sessionlist.submit();return false;">Insert</a>&nbsp;<a href="tbl_sessionlist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" id="a_list" value="insert">
</span></td>
	</tr>
<?php
}
?>
<?php
if (defined("EW_EXPORT_ALL") && $tbl_session->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$tbl_session->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
$nEditRowCnt = 0;
if ($tbl_session->CurrentAction == "edit") $RowIndex = 1;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;

	// Init row class and style
	$tbl_session->CssClass = "ewTableRow";
	$tbl_session->CssStyle = "";

	// Init row event
	$tbl_session->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
	LoadRowValues($rs); // Load row values
	$tbl_session->RowType = EW_ROWTYPE_VIEW; // Render view
	if ($tbl_session->CurrentAction == "edit") {
		if (CheckInlineEditKey() && $nEditRowCnt == 0) { // Inline edit
			$tbl_session->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
	}
		if ($tbl_session->RowType == EW_ROWTYPE_EDIT && $tbl_session->EventCancelled) { // Update failed
			if ($tbl_session->CurrentAction == "edit") {
				RestoreFormValues(); // Restore form values
			}
		}
		if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit row
			$nEditRowCnt++;
			$tbl_session->CssClass = "ewTableEditRow";
			$tbl_session->RowClientEvents = "onmouseover='this.edit=true;ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
		}
	RenderRow();
?>
	<!-- Table body -->
	<tr<?php echo $tbl_session->DisplayAttributes() ?>>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { ?>
<input type="hidden" name="x<?php echo $RowIndex ?>_sessionid" id="x<?php echo $RowIndex ?>_sessionid" value="<?php echo ew_HtmlEncode($tbl_session->sessionid->CurrentValue) ?>">
<?php } ?>

		<!-- session_number -->
		<td width="100"<?php echo $tbl_session->session_date->CellAttributes() ?>>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_session_date" id="x<?php echo $RowIndex ?>_session_date" title="" size="10" value="<?php echo $tbl_session->session_date->EditValue ?>"<?php echo $tbl_session->session_date->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_session->session_date->ViewAttributes() ?>><?php echo $tbl_session->session_date->ViewValue ?></div>
<?php } ?>
</td>

		<!-- session_number -->
		<td width="100"<?php echo $tbl_session->session_number->CellAttributes() ?>>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_session_number" id="x<?php echo $RowIndex ?>_session_number" title="" size="5" value="<?php echo $tbl_session->session_number->EditValue ?>"<?php echo $tbl_session->session_number->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_session->session_number->ViewAttributes() ?>><?php echo $tbl_session->session_number->ViewValue ?></div>
<?php } ?>
</td>
		<!-- session_goal -->
		<td width="100"<?php echo $tbl_session->session_goal->CellAttributes() ?>>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_session_goal" id="x<?php echo $RowIndex ?>_session_goal" title="" size="5" maxlength="125" value="<?php echo $tbl_session->session_goal->EditValue ?>"<?php echo $tbl_session->session_goal->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_session->session_goal->ViewAttributes() ?>><?php echo $tbl_session->session_goal->ViewValue ?></div>
<?php } ?>
</td>
		<!-- session_goal_completed -->
		<td width="100"<?php echo $tbl_session->session_goal_completed->CellAttributes() ?>>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_session_goal_completed" id="x<?php echo $RowIndex ?>_session_goal_completed" title="" size="5" maxlength="125" value="<?php echo $tbl_session->session_goal_completed->EditValue ?>"<?php echo $tbl_session->session_goal_completed->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_session->session_goal_completed->ViewAttributes() ?>><?php echo $tbl_session->session_goal_completed->ViewValue ?></div>
<?php } ?>
</td>
		<!-- session_homework -->
		<td width="100"<?php echo $tbl_session->session_homework->CellAttributes() ?>>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_session_homework" id="x<?php echo $RowIndex ?>_session_homework" title="" size="5" maxlength="125" value="<?php echo $tbl_session->session_homework->EditValue ?>"<?php echo $tbl_session->session_homework->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_session->session_homework->ViewAttributes() ?>><?php echo $tbl_session->session_homework->ViewValue ?></div>
<?php } ?>
</td>
		<!-- session_hmwrk_completed -->
		<td width="100"<?php echo $tbl_session->session_hmwrk_completed->CellAttributes() ?>>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_session_hmwrk_completed" id="x<?php echo $RowIndex ?>_session_hmwrk_completed" title="" size="5" maxlength="125" value="<?php echo $tbl_session->session_hmwrk_completed->EditValue ?>"<?php echo $tbl_session->session_hmwrk_completed->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_session->session_hmwrk_completed->ViewAttributes() ?>><?php echo $tbl_session->session_hmwrk_completed->ViewValue ?></div>
<?php } ?>
</td>
		<!-- s_stuid -->
		<td<?php echo $tbl_session->s_stuid->CellAttributes() ?>>

<input type="hidden" name="x<?php echo $RowIndex ?>_s_stuid" id="x<?php echo $RowIndex ?>_s_stuid" value="<?php echo ew_HtmlEncode($tbl_session->s_stuid->CurrentValue) ?>">
</td>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { ?>
<?php if ($tbl_session->CurrentAction == "edit") { ?>
<td colspan="<?php echo $OptionCnt ?>"><span class="edge">
<a href="" onClick="if (ew_ValidateForm(document.ftbl_sessionlist)) document.ftbl_sessionlist.submit();return false;">Update</a>&nbsp;<a href="tbl_sessionlist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" id="a_list" value="update">
</span></td>
<?php } ?>
<?php } else { ?>
<?php if ($tbl_session->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<td nowrap><span class="edge">
<a href="<?php echo $tbl_session->InlineEditUrl() ?>"> Edit</a>
</span></td>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($OptionCnt == 0 && $tbl_session->CurrentAction == "add") { ?>
<td nowrap>&nbsp;</td>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
	</tr>
<?php if ($tbl_session->RowType == EW_ROWTYPE_EDIT) { ?>
<?php } ?>
<?php
	}
	$rs->MoveNext();
}
?>
</table>
<?php if ($tbl_session->Export == "") { ?>
<?php } ?>
<?php } ?>
<?php if ($tbl_session->CurrentAction == "add" || $tbl_session->CurrentAction == "copy") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $RowIndex ?>">
<?php } ?>
<?php if ($tbl_session->CurrentAction == "edit") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $RowIndex ?>">
<?php } ?>
</form>
<table>
  <tr>
    <td><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <a href="tbl_sessionlist.php?a=add"> Add</a>&nbsp;&nbsp;
      <?php } ?>
    </span></td>
  </tr>
</table>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($tbl_session->Export == "") { ?>
<form action="tbl_sessionlist.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap>
<span class="edge">
<?php if (!isset($Pager)) $Pager = new cNumericPager($nStartRec, $nDisplayRecs, $nTotalRecs, $nRecRange) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<a href="tbl_sessionlist.php?start=<?php echo $Pager->FirstButton->Start ?>"><b>First</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<a href="tbl_sessionlist.php?start=<?php echo $Pager->PrevButton->Start ?>"><b>Previous</b></a>&nbsp;
	<?php } ?>
	<?php foreach ($Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="tbl_sessionlist.php?start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($Pager->NextButton->Enabled) { ?>
	<a href="tbl_sessionlist.php?start=<?php echo $Pager->NextButton->Start ?>"><b>Next</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->LastButton->Enabled) { ?>
	<a href="tbl_sessionlist.php?start=<?php echo $Pager->LastButton->Start ?>"><b>Last</b></a>&nbsp;
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
<?php if ($tbl_session->Export == "") { ?>
<?php } ?>
<?php if ($tbl_session->Export == "") { ?>
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

//  Exit out of inline mode
function ClearInlineMode() {
	global $tbl_session;
	$tbl_session->setKey("sessionid", ""); // Clear inline edit key
	$tbl_session->CurrentAction = ""; // Clear action
	$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
}

// Switch to Inline Edit Mode
function InlineEditMode() {
	global $Security, $tbl_session;
	$bInlineEdit = TRUE;
	if (@$_GET["sessionid"] <> "") {
		$tbl_session->sessionid->setQueryStringValue($_GET["sessionid"]);
	} else {
		$bInlineEdit = FALSE;
	}
	if ($bInlineEdit) {
		if (LoadRow()) {
			$tbl_session->setKey("sessionid", $tbl_session->sessionid->CurrentValue); // Set up inline edit key
			$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
		}
	}
}

// Peform update to inline edit record
function InlineUpdate() {
	global $objForm, $tbl_session;
	$objForm->Index = 1; 
	LoadFormValues(); // Get form values
	if (CheckInlineEditKey()) { // Check key
		$tbl_session->SendEmail = TRUE; // Send email on update success
		$bInlineUpdate = EditRow(); // Update record
	} else {
		$bInlineUpdate = FALSE;
	}
	if ($bInlineUpdate) { // Update success
		$_SESSION[EW_SESSION_MESSAGE] = "Update successful"; // Set success message
		ClearInlineMode(); // Clear inline edit mode
	} else {
		if (@$_SESSION[EW_SESSION_MESSAGE] == "") {
			$_SESSION[EW_SESSION_MESSAGE] = "Update failed"; // Set update failed message
		}
		$tbl_session->EventCancelled = TRUE; // Cancel event
		$tbl_session->CurrentAction = "edit"; // Stay in edit mode
	}
}

// Check inline edit key
function CheckInlineEditKey() {
	global $tbl_session;

	//CheckInlineEditKey = True
	if (strval($tbl_session->getKey("sessionid")) <> strval($tbl_session->sessionid->CurrentValue)) {
		return FALSE;
	}
	return TRUE;
}

// Switch to Inline Add Mode
function InlineAddMode() {
	global $Security, $tbl_session;
	$tbl_session->CurrentAction = "add";
	$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
}

// Peform update to inline add/copy record
function InlineInsert() {
	global $objForm, $tbl_session;
	$objForm->Index = 1;
	LoadFormValues(); // Get form values
	$tbl_session->SendEmail = TRUE; // Send email on add success
	if (AddRow()) { // Add record
		$_SESSION[EW_SESSION_MESSAGE] = "Add New Record Successful"; // Set add success message
		ClearInlineMode(); // Clear inline add mode
	} else { // Add failed
		$tbl_session->EventCancelled = TRUE; // Set event cancelled
		$tbl_session->CurrentAction = "add"; // Stay in add mode
	}
}

// Set up Sort parameters based on Sort Links clicked
function SetUpSortOrder() {
	global $tbl_session;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$tbl_session->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$tbl_session->CurrentOrderType = @$_GET["ordertype"];
		$tbl_session->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $tbl_session->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($tbl_session->SqlOrderBy() <> "") {
			$sOrderBy = $tbl_session->SqlOrderBy();
			$tbl_session->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $tbl_session;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset master/detail keys
		if (strtolower($sCmd) == "resetall") {
			$tbl_session->setMasterFilter(""); // Clear master filter
			$sDbMasterFilter = "";
			$tbl_session->setDetailFilter(""); // Clear detail filter
			$sDbDetailFilter = "";
			$tbl_session->s_stuid->setSessionValue("");
		}

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$tbl_session->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$tbl_session->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $tbl_session;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$tbl_session->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$tbl_session->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $tbl_session->getStartRecordNumber();
		}
	} else {
		$nStartRec = $tbl_session->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$tbl_session->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$tbl_session->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$tbl_session->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load default values
function LoadDefaultValues() {
	global $tbl_session;
}
?>
<?php

// Load form values
function LoadFormValues() {

	// Load from form
	global $objForm, $tbl_session;
	$tbl_session->sessionid->setFormValue($objForm->GetValue("x_sessionid"));
	$tbl_session->session_number->setFormValue($objForm->GetValue("x_session_number"));
	$tbl_session->session_goal->setFormValue($objForm->GetValue("x_session_goal"));
	$tbl_session->session_goal_completed->setFormValue($objForm->GetValue("x_session_goal_completed"));
	$tbl_session->session_homework->setFormValue($objForm->GetValue("x_session_homework"));
	$tbl_session->session_hmwrk_completed->setFormValue($objForm->GetValue("x_session_hmwrk_completed"));
	$tbl_session->s_stuid->setFormValue($objForm->GetValue("x_s_stuid"));

	$tbl_session->session_date->setFormValue($objForm->GetValue("x_session_date"));
	$tbl_session->session_date->CurrentValue = ew_UnFormatDateTime($tbl_session->session_date->CurrentValue, 6);
}

// Restore form values
function RestoreFormValues() {
	global $tbl_session;
	$tbl_session->sessionid->CurrentValue = $tbl_session->sessionid->FormValue;
	$tbl_session->session_number->CurrentValue = $tbl_session->session_number->FormValue;
	$tbl_session->session_goal->CurrentValue = $tbl_session->session_goal->FormValue;
	$tbl_session->session_goal_completed->CurrentValue = $tbl_session->session_goal_completed->FormValue;
	$tbl_session->session_homework->CurrentValue = $tbl_session->session_homework->FormValue;
	$tbl_session->session_hmwrk_completed->CurrentValue = $tbl_session->session_hmwrk_completed->FormValue;
	$tbl_session->s_stuid->CurrentValue = $tbl_session->s_stuid->FormValue;

	$tbl_session->session_date->CurrentValue = $tbl_session->session_date->FormValue;
	$tbl_session->session_date->CurrentValue = ew_UnFormatDateTime($tbl_session->session_date->CurrentValue, 6);
	
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_session;

	// Call Recordset Selecting event
	$tbl_session->Recordset_Selecting($tbl_session->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_session->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_session->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_session;
	$sFilter = $tbl_session->SqlKeyFilter();
	if (!is_numeric($tbl_session->sessionid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@sessionid@", ew_AdjustSql($tbl_session->sessionid->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_session->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add User ID filter for master table
	}

	// Call Row Selecting event
	$tbl_session->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_session->CurrentFilter = $sFilter;
	$sSql = $tbl_session->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_session->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_session;
	$tbl_session->sessionid->setDbValue($rs->fields('sessionid'));
	$tbl_session->session_number->setDbValue($rs->fields('session_number'));
	$tbl_session->session_goal->setDbValue($rs->fields('session_goal'));
	$tbl_session->session_goal_completed->setDbValue($rs->fields('session_goal_completed'));
	$tbl_session->session_homework->setDbValue($rs->fields('session_homework'));
	$tbl_session->session_hmwrk_completed->setDbValue($rs->fields('session_hmwrk_completed'));
	$tbl_session->s_stuid->setDbValue($rs->fields('s_stuid'));
	$tbl_session->session_date->setDbValue($rs->fields('session_date'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_session;

	// Call Row Rendering event
	$tbl_session->Row_Rendering();

	// Common render codes for all row types
		// session_date

	$tbl_session->session_date->CellCssStyle = "";
	$tbl_session->session_date->CellCssClass = "";
	
	// session_number

	$tbl_session->session_number->CellCssStyle = "";
	$tbl_session->session_number->CellCssClass = "";

	// session_goal
	$tbl_session->session_goal->CellCssStyle = "";
	$tbl_session->session_goal->CellCssClass = "";

	// session_goal_completed
	$tbl_session->session_goal_completed->CellCssStyle = "";
	$tbl_session->session_goal_completed->CellCssClass = "";

	// session_homework
	$tbl_session->session_homework->CellCssStyle = "";
	$tbl_session->session_homework->CellCssClass = "";

	// session_hmwrk_completed
	$tbl_session->session_hmwrk_completed->CellCssStyle = "";
	$tbl_session->session_hmwrk_completed->CellCssClass = "";

	// s_stuid
	$tbl_session->s_stuid->CellCssStyle = "white-space: nowrap;";
	$tbl_session->s_stuid->CellCssClass = "";
	if ($tbl_session->RowType == EW_ROWTYPE_VIEW) { // View row

				// session_date
		$tbl_session->session_date->ViewValue = $tbl_session->session_date->CurrentValue;
		$tbl_session->session_date->ViewValue = ew_FormatDateTime($tbl_session->session_date->ViewValue, 6);
		$tbl_session->session_date->CssStyle = "";
		$tbl_session->session_date->CssClass = "";
		$tbl_session->session_date->ViewCustomAttributes = "";
		
		// session_number
		$tbl_session->session_number->ViewValue = $tbl_session->session_number->CurrentValue;
		$tbl_session->session_number->CssStyle = "";
		$tbl_session->session_number->CssClass = "";
		$tbl_session->session_number->ViewCustomAttributes = "";

		// session_goal
		$tbl_session->session_goal->ViewValue = $tbl_session->session_goal->CurrentValue;
		$tbl_session->session_goal->CssStyle = "";
		$tbl_session->session_goal->CssClass = "";
		$tbl_session->session_goal->ViewCustomAttributes = "";

		// session_goal_completed
		$tbl_session->session_goal_completed->ViewValue = $tbl_session->session_goal_completed->CurrentValue;
		$tbl_session->session_goal_completed->CssStyle = "";
		$tbl_session->session_goal_completed->CssClass = "";
		$tbl_session->session_goal_completed->ViewCustomAttributes = "";

		// session_homework
		$tbl_session->session_homework->ViewValue = $tbl_session->session_homework->CurrentValue;
		$tbl_session->session_homework->CssStyle = "";
		$tbl_session->session_homework->CssClass = "";
		$tbl_session->session_homework->ViewCustomAttributes = "";

		// session_hmwrk_completed
		$tbl_session->session_hmwrk_completed->ViewValue = $tbl_session->session_hmwrk_completed->CurrentValue;
		$tbl_session->session_hmwrk_completed->CssStyle = "";
		$tbl_session->session_hmwrk_completed->CssClass = "";
		$tbl_session->session_hmwrk_completed->ViewCustomAttributes = "";

		// s_stuid
		$tbl_session->s_stuid->ViewValue = $tbl_session->s_stuid->CurrentValue;
		$tbl_session->s_stuid->CssStyle = "";
		$tbl_session->s_stuid->CssClass = "";
		$tbl_session->s_stuid->ViewCustomAttributes = "";

		// session_date
		$tbl_session->session_date->HrefValue = "";
		
		// session_number
		$tbl_session->session_number->HrefValue = "";

		// session_goal
		$tbl_session->session_goal->HrefValue = "";

		// session_goal_completed
		$tbl_session->session_goal_completed->HrefValue = "";

		// session_homework
		$tbl_session->session_homework->HrefValue = "";

		// session_hmwrk_completed
		$tbl_session->session_hmwrk_completed->HrefValue = "";

		// s_stuid
		$tbl_session->s_stuid->HrefValue = "";
	} elseif ($tbl_session->RowType == EW_ROWTYPE_ADD) { // Add row

		// session_date
		$tbl_session->session_date->EditCustomAttributes = "";
		$tbl_session->session_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($tbl_session->session_date->CurrentValue, 6));
		
		// session_number
		$tbl_session->session_number->EditCustomAttributes = "";
		$tbl_session->session_number->EditValue = ew_HtmlEncode($tbl_session->session_number->CurrentValue);

		// session_goal
		$tbl_session->session_goal->EditCustomAttributes = "";
		$tbl_session->session_goal->EditValue = ew_HtmlEncode($tbl_session->session_goal->CurrentValue);

		// session_goal_completed
		$tbl_session->session_goal_completed->EditCustomAttributes = "";
		$tbl_session->session_goal_completed->EditValue = ew_HtmlEncode($tbl_session->session_goal_completed->CurrentValue);

		// session_homework
		$tbl_session->session_homework->EditCustomAttributes = "";
		$tbl_session->session_homework->EditValue = ew_HtmlEncode($tbl_session->session_homework->CurrentValue);

		// session_hmwrk_completed
		$tbl_session->session_hmwrk_completed->EditCustomAttributes = "";
		$tbl_session->session_hmwrk_completed->EditValue = ew_HtmlEncode($tbl_session->session_hmwrk_completed->CurrentValue);

		// s_stuid
		$tbl_session->s_stuid->EditCustomAttributes = "";
		if ($tbl_session->s_stuid->getSessionValue() <> "") {
			$tbl_session->s_stuid->CurrentValue = $tbl_session->s_stuid->getSessionValue();
		$tbl_session->s_stuid->ViewValue = $tbl_session->s_stuid->CurrentValue;
		$tbl_session->s_stuid->CssStyle = "";
		$tbl_session->s_stuid->CssClass = "";
		$tbl_session->s_stuid->ViewCustomAttributes = "";
		} else {
		$tbl_session->s_stuid->EditValue = ew_HtmlEncode($tbl_session->s_stuid->CurrentValue);
		}
	} elseif ($tbl_session->RowType == EW_ROWTYPE_EDIT) { // Edit row

		// session_date
		$tbl_session->session_date->EditCustomAttributes = "";
		$tbl_session->session_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($tbl_session->session_date->CurrentValue, 6));
	
		// session_number
		$tbl_session->session_number->EditCustomAttributes = "";
		$tbl_session->session_number->EditValue = ew_HtmlEncode($tbl_session->session_number->CurrentValue);

		// session_goal
		$tbl_session->session_goal->EditCustomAttributes = "";
		$tbl_session->session_goal->EditValue = ew_HtmlEncode($tbl_session->session_goal->CurrentValue);

		// session_goal_completed
		$tbl_session->session_goal_completed->EditCustomAttributes = "";
		$tbl_session->session_goal_completed->EditValue = ew_HtmlEncode($tbl_session->session_goal_completed->CurrentValue);

		// session_homework
		$tbl_session->session_homework->EditCustomAttributes = "";
		$tbl_session->session_homework->EditValue = ew_HtmlEncode($tbl_session->session_homework->CurrentValue);

		// session_hmwrk_completed
		$tbl_session->session_hmwrk_completed->EditCustomAttributes = "";
		$tbl_session->session_hmwrk_completed->EditValue = ew_HtmlEncode($tbl_session->session_hmwrk_completed->CurrentValue);

		// s_stuid
		$tbl_session->s_stuid->EditCustomAttributes = "";
		if ($tbl_session->s_stuid->getSessionValue() <> "") {
			$tbl_session->s_stuid->CurrentValue = $tbl_session->s_stuid->getSessionValue();
		$tbl_session->s_stuid->ViewValue = $tbl_session->s_stuid->CurrentValue;
		$tbl_session->s_stuid->CssStyle = "";
		$tbl_session->s_stuid->CssClass = "";
		$tbl_session->s_stuid->ViewCustomAttributes = "";
		} else {
		}
	} elseif ($tbl_session->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_session->Row_Rendered();
}
?>
<?php

// Update record based on key values
function EditRow() {
	global $conn, $Security, $tbl_session;
	$sFilter = $tbl_session->SqlKeyFilter();
	if (!is_numeric($tbl_session->sessionid->CurrentValue)) {
		return FALSE;
	}
	$sFilter = str_replace("@sessionid@", ew_AdjustSql($tbl_session->sessionid->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_session->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add User ID filter for master table
		$tbl_session->CurrentFilter = $sFilter;
	}
	$tbl_session->CurrentFilter = $sFilter;
	$sSql = $tbl_session->SQL();
	$conn->raiseErrorFn = 'ew_ErrorFn';
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';
	if ($rs === FALSE)
		return FALSE;
	if ($rs->EOF) {
		$EditRow = FALSE; // Update Failed
	} else {

		// Save old values
		$rsold =& $rs->fields;
		$rsnew = array();

				// Field session_date
		$tbl_session->session_date->SetDbValueDef(ew_UnFormatDateTime($tbl_session->session_date->CurrentValue, 6), ew_CurrentDate());
		$rsnew['session_date'] =& $tbl_session->session_date->DbValue;
		
		// Field session_number
		$tbl_session->session_number->SetDbValueDef($tbl_session->session_number->CurrentValue, NULL);
		$rsnew['session_number'] =& $tbl_session->session_number->DbValue;

		// Field session_goal
		$tbl_session->session_goal->SetDbValueDef($tbl_session->session_goal->CurrentValue, NULL);
		$rsnew['session_goal'] =& $tbl_session->session_goal->DbValue;

		// Field session_goal_completed
		$tbl_session->session_goal_completed->SetDbValueDef($tbl_session->session_goal_completed->CurrentValue, NULL);
		$rsnew['session_goal_completed'] =& $tbl_session->session_goal_completed->DbValue;

		// Field session_homework
		$tbl_session->session_homework->SetDbValueDef($tbl_session->session_homework->CurrentValue, NULL);
		$rsnew['session_homework'] =& $tbl_session->session_homework->DbValue;

		// Field session_hmwrk_completed
		$tbl_session->session_hmwrk_completed->SetDbValueDef($tbl_session->session_hmwrk_completed->CurrentValue, NULL);
		$rsnew['session_hmwrk_completed'] =& $tbl_session->session_hmwrk_completed->DbValue;

		// Field s_stuid
		$tbl_session->s_stuid->SetDbValueDef($tbl_session->s_stuid->CurrentValue, NULL);
		$rsnew['s_stuid'] =& $tbl_session->s_stuid->DbValue;

		// Call Row Updating event
		$bUpdateRow = $tbl_session->Row_Updating($rsold, $rsnew);
		if ($bUpdateRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$EditRow = $conn->Execute($tbl_session->UpdateSQL($rsnew));
			$conn->raiseErrorFn = '';
		} else {
			if ($tbl_session->CancelMessage <> "") {
				$_SESSION[EW_SESSION_MESSAGE] = $tbl_session->CancelMessage;
				$tbl_session->CancelMessage = "";
			} else {
				$_SESSION[EW_SESSION_MESSAGE] = "Update cancelled";
			}
			$EditRow = FALSE;
		}
	}

	// Call Row Updated event
	if ($EditRow) {
		$tbl_session->Row_Updated($rsold, $rsnew);
	}
	$rs->Close();
	return $EditRow;
}
?>
<?php

// Add record
function AddRow() {
	global $conn, $Security, $tbl_session;

	// Check if valid User ID for master
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_session->AddMasterUserIDFilter("", $tbl_session->getCurrentMasterTable(), $Security->CurrentUserID());
		if ($tbl_session->getCurrentMasterTable() == "tbl_students") {
			$rsmaster = $GLOBALS["tbl_students"]->LoadRs($sFilter);
			$bMasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$bMasterRecordExists) {
				$_SESSION[EW_SESSION_MESSAGE] = "Unauthorized";
				return FALSE;
			} else {
				$rsmaster->Close();
			}
		}
	}

	// Check for duplicate key
	$bCheckKey = TRUE;
	$sFilter = $tbl_session->SqlKeyFilter();
	if (trim(strval($tbl_session->sessionid->CurrentValue)) == "") {
		$bCheckKey = FALSE;
	} else {
		$sFilter = str_replace("@sessionid@", ew_AdjustSql($tbl_session->sessionid->CurrentValue), $sFilter); // Replace key value
	}
	if (!is_numeric($tbl_session->sessionid->CurrentValue)) {
		$bCheckKey = FALSE;
	}
	if ($bCheckKey) {
		$rsChk = $tbl_session->LoadRs($sFilter);
		if ($rsChk && !$rsChk->EOF) {
			$_SESSION[EW_SESSION_MESSAGE] = "Duplicate value for primary key";
			$rsChk->Close();
			return FALSE;
		}
	}
	$rsnew = array();

// Field session_date
	$tbl_session->session_date->SetDbValueDef(ew_UnFormatDateTime($tbl_session->session_date->CurrentValue, 6), ew_CurrentDate());
	$rsnew['session_date'] =& $tbl_session->session_date->DbValue;
	
	// Field session_number
	$tbl_session->session_number->SetDbValueDef($tbl_session->session_number->CurrentValue, NULL);
	$rsnew['session_number'] =& $tbl_session->session_number->DbValue;

	// Field session_goal
	$tbl_session->session_goal->SetDbValueDef($tbl_session->session_goal->CurrentValue, NULL);
	$rsnew['session_goal'] =& $tbl_session->session_goal->DbValue;

	// Field session_goal_completed
	$tbl_session->session_goal_completed->SetDbValueDef($tbl_session->session_goal_completed->CurrentValue, NULL);
	$rsnew['session_goal_completed'] =& $tbl_session->session_goal_completed->DbValue;

	// Field session_homework
	$tbl_session->session_homework->SetDbValueDef($tbl_session->session_homework->CurrentValue, NULL);
	$rsnew['session_homework'] =& $tbl_session->session_homework->DbValue;

	// Field session_hmwrk_completed
	$tbl_session->session_hmwrk_completed->SetDbValueDef($tbl_session->session_hmwrk_completed->CurrentValue, NULL);
	$rsnew['session_hmwrk_completed'] =& $tbl_session->session_hmwrk_completed->DbValue;

	// Field s_stuid
	$tbl_session->s_stuid->SetDbValueDef($tbl_session->s_stuid->CurrentValue, NULL);
	$rsnew['s_stuid'] =& $tbl_session->s_stuid->DbValue;

	// Call Row Inserting event
	$bInsertRow = $tbl_session->Row_Inserting($rsnew);
	if ($bInsertRow) {
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$AddRow = $conn->Execute($tbl_session->InsertSQL($rsnew));
		$conn->raiseErrorFn = '';
	} else {
		if ($tbl_session->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_session->CancelMessage;
			$tbl_session->CancelMessage = "";
		} else {
			$_SESSION[EW_SESSION_MESSAGE] = "Insert cancelled";
		}
		$AddRow = FALSE;
	}
	if ($AddRow) {
		$tbl_session->sessionid->setDbValue($conn->Insert_ID());
		$rsnew['sessionid'] =& $tbl_session->sessionid->DbValue;

		// Call Row Inserted event
		$tbl_session->Row_Inserted($rsnew);
	}
	return $AddRow;
}
?>
<?php

// Set up Master Detail based on querystring parameter
function SetUpMasterDetail() {
	global $nStartRec, $sDbMasterFilter, $sDbDetailFilter, $tbl_session;
	$bValidMaster = FALSE;

	// Get the keys for master table
	if (@$_GET[EW_TABLE_SHOW_MASTER] <> "") {
		$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
		if ($sMasterTblVar == "") {
			$bValidMaster = TRUE;
			$sDbMasterFilter = "";
			$sDbDetailFilter = "";
		}
		if ($sMasterTblVar == "tbl_students") {
			$bValidMaster = TRUE;
			$sDbMasterFilter = $tbl_session->SqlMasterFilter_tbl_students();
			$sDbDetailFilter = $tbl_session->SqlDetailFilter_tbl_students();
			if (@$_GET["s_studentid"] <> "") {
				$GLOBALS["tbl_students"]->s_studentid->setQueryStringValue($_GET["s_studentid"]);
				$tbl_session->s_stuid->setQueryStringValue($GLOBALS["tbl_students"]->s_studentid->QueryStringValue);
				$tbl_session->s_stuid->setSessionValue($tbl_session->s_stuid->QueryStringValue);
				if (!is_numeric($GLOBALS["tbl_students"]->s_studentid->QueryStringValue)) $bValidMaster = FALSE;
				$sDbMasterFilter = str_replace("@s_studentid@", ew_AdjustSql($GLOBALS["tbl_students"]->s_studentid->QueryStringValue), $sDbMasterFilter);
				$sDbDetailFilter = str_replace("@s_stuid@", ew_AdjustSql($GLOBALS["tbl_students"]->s_studentid->QueryStringValue), $sDbDetailFilter);
			} else {
				$bValidMaster = FALSE;
			}
		}
	}
	if ($bValidMaster) {

		// Save current master table
		$tbl_session->setCurrentMasterTable($sMasterTblVar);

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$tbl_session->setStartRecordNumber($nStartRec);
		$tbl_session->setMasterFilter($sDbMasterFilter); // Set up master filter
		$tbl_session->setDetailFilter($sDbDetailFilter); // Set up detail filter

		// Clear previous master session values
		if ($sMasterTblVar <> "tbl_students") {
			if ($tbl_session->s_stuid->QueryStringValue == "") $tbl_session->s_stuid->setSessionValue("");
		}
	} else {
		$sDbMasterFilter = $tbl_session->getMasterFilter(); //  Restore master filter
		$sDbDetailFilter = $tbl_session->getDetailFilter(); // Restore detail filter
	}
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

<?php

// Show link optionally based on User ID
function ShowOptionLink() {
	global $Security, $tbl_students;
	if ($Security->IsLoggedIn()) {
		if (!$Security->IsAdmin()) {
			return $Security->IsValidUserID($tbl_students->i_instructid->CurrentValue);
		}
	}
	return TRUE;
}
?>