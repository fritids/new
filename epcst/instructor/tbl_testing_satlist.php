<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_testing_sat', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_testing_satinfo.php" ?>
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
$tbl_testing_sat->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_testing_sat->Export; // Get export parameter, used in header
$sExportFile = $tbl_testing_sat->TableVar; // Get export file, used in header
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
	$tbl_testing_sat->CurrentAction = $_GET["a"];

	// Clear inline mode
	if ($tbl_testing_sat->CurrentAction == "cancel") {
		ClearInlineMode();
	}

	// Switch to inline edit mode
	if ($tbl_testing_sat->CurrentAction == "edit") {
		InlineEditMode();
	}

	// Switch to inline add mode
	if ($tbl_testing_sat->CurrentAction == "add" || $tbl_testing_sat->CurrentAction == "copy") {
		InlineAddMode();
	}
} else {

	// Create form object
	$objForm = new cFormObj;
	if (@$_POST["a_list"] <> "") {
		$tbl_testing_sat->CurrentAction = $_POST["a_list"]; // Get action

		// Inline Update
		if ($tbl_testing_sat->CurrentAction == "update" && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit") {
			InlineUpdate();
		}

		// Insert Inline
		if ($tbl_testing_sat->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add") {
			InlineInsert();
		}
	}
}

// Build filter
$sFilter = "";
if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
	if ($tbl_testing_sat->getCurrentMasterTable() == "tbl_students") {
		$sFilter = $tbl_testing_sat->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add detail User ID filter
		$sDbMasterFilter = $tbl_testing_sat->AddMasterUserIDFilter($sDbMasterFilter, "tbl_students", $Security->CurrentUserID()); // Add master User ID filter
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
if ($tbl_testing_sat->getMasterFilter() <> "" && $tbl_testing_sat->getCurrentMasterTable() == "tbl_students") {
	$rsmaster = $tbl_students->LoadRs($sDbMasterFilter);
	$bMasterRecordExists = ($rsmaster && !$rsmaster->EOF);
	if (!$bMasterRecordExists) {
		$tbl_testing_sat->setMasterFilter(""); // Clear master filter
		$tbl_testing_sat->setDetailFilter(""); // Clear detail filter
		$_SESSION[EW_SESSION_MESSAGE] = "No records found"; // Set no record found
		Page_Terminate("tbl_studentslist.php"); // Return to caller
	} else {
		$tbl_students->LoadListRowValues($rsmaster);
		$tbl_students->RenderListRow();
		$rsmaster->Close();
	}
}

// Set up filter in Session
$tbl_testing_sat->setSessionWhere($sFilter);
$tbl_testing_sat->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$tbl_testing_sat->setReturnUrl("tbl_testing_satlist.php");
?>
<?php include "header.php" ?>
<?php if ($tbl_testing_sat->Export == "") { ?>
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
		elm = fobj.elements["x" + infix + "_t_sat_test_date"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Test Date"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_t_sat_test_date"];
		if (elm && !ew_CheckUSDate(elm.value)) {
			if (!ew_OnError(elm, "Incorrect date, format = mm/dd/yyyy - Test Date"))
				return false; 
		}
		elm = fobj.elements["x" + infix + "_t_sat_reading"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Reading"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_t_sat_reading"];
		if (elm && !ew_CheckInteger(elm.value)) {
			if (!ew_OnError(elm, "Incorrect integer - Reading"))
				return false; 
		}
		elm = fobj.elements["x" + infix + "_t_sat_math"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Math"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_t_sat_math"];
		if (elm && !ew_CheckInteger(elm.value)) {
			if (!ew_OnError(elm, "Incorrect integer - Math"))
				return false; 
		}
		elm = fobj.elements["x" + infix + "_t_sat_writing"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Writing"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_t_sat_writing"];
		if (elm && !ew_CheckInteger(elm.value)) {
			if (!ew_OnError(elm, "Incorrect integer - Writing"))
				return false; 
		}
		elm = fobj.elements["x" + infix + "_t_sat_essay"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Essay"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_t_sat_essay"];
		if (elm && !ew_CheckInteger(elm.value)) {
			if (!ew_OnError(elm, "Incorrect integer - Essay"))
				return false; 
		}
		elm = fobj.elements["x" + infix + "_t_sat_test_site"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Test Site"))
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
<?php if ($tbl_testing_sat->Export == "") { ?>
<?php
$sMasterReturnUrl = "tbl_studentslist.php";
if ($tbl_testing_sat->getMasterFilter() <> "" && $tbl_testing_sat->getCurrentMasterTable() == "tbl_students") {
	if ($bMasterRecordExists) {
		if ($tbl_testing_sat->getCurrentMasterTable() == $tbl_testing_sat->TableVar) $sMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include "tbl_studentsmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $tbl_testing_sat->Export <> "");
$bSelectLimit = ($tbl_testing_sat->Export == "" && $tbl_testing_sat->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $tbl_testing_sat->SelectRecordCount() : $rs->RecordCount();
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

<p><span class="edge" style="white-space: nowrap;">Student's Test SAT</span></p>
<p>
  <?php if ($tbl_testing_sat->Export == "") { ?>
  <?php } ?>
  <?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
</p>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form name="ftbl_testing_satlist" id="ftbl_testing_satlist" action="tbl_testing_satlist.php" method="post">
<?php if ($tbl_testing_sat->Export == "") { ?>
<?php } ?>
<?php if ($nTotalRecs > 0 || $tbl_testing_sat->CurrentAction == "add" || $tbl_testing_sat->CurrentAction == "copy") { ?>
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
<?php if ($tbl_testing_sat->Export <> "") { ?>
Test Date
<?php } else { ?>
	Test Date<?php if ($tbl_testing_sat->t_sat_test_date->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_testing_sat->t_sat_test_date->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="75" valign="top">
<?php if ($tbl_testing_sat->Export <> "") { ?>
Reading
<?php } else { ?>
	Reading<?php if ($tbl_testing_sat->t_sat_reading->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_testing_sat->t_sat_reading->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="75" valign="top">
<?php if ($tbl_testing_sat->Export <> "") { ?>
Math
<?php } else { ?>
	Math<?php if ($tbl_testing_sat->t_sat_math->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_testing_sat->t_sat_math->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="75" valign="top">
<?php if ($tbl_testing_sat->Export <> "") { ?>
Writing
<?php } else { ?>
	Writing<?php if ($tbl_testing_sat->t_sat_writing->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_testing_sat->t_sat_writing->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="75" valign="top">
<?php if ($tbl_testing_sat->Export <> "") { ?>
Essay
<?php } else { ?>
	Essay<?php if ($tbl_testing_sat->t_sat_essay->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_testing_sat->t_sat_essay->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="100" valign="top">
<?php if ($tbl_testing_sat->Export <> "") { ?>
Test Site
<?php } else { ?>
	Test Site<?php if ($tbl_testing_sat->t_sat_test_site->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_testing_sat->t_sat_test_site->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($OptionCnt == 0 && $tbl_testing_sat->CurrentAction == "add") { ?>
<td nowrap>&nbsp;</td>
<?php } ?>
<?php } ?>
	</tr>
<?php
	if ($tbl_testing_sat->CurrentAction == "add" || $tbl_testing_sat->CurrentAction == "copy") {
		$RowIndex = 1;
		if ($tbl_testing_sat->EventCancelled) { // Insert failed
			RestoreFormValues(); // Restore form values
		}

		// Init row class and style
		$tbl_testing_sat->CssClass = "ewTableEditRow"; // edit
		$tbl_testing_sat->CssStyle = "";

		// Init row event
		$tbl_testing_sat->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";

		// Render add row
		$tbl_testing_sat->RowType = EW_ROWTYPE_ADD;
		RenderRow();
?>
	<tr<?php echo $tbl_testing_sat->DisplayAttributes() ?>>
		<!-- t_sat_test_date -->
		<td width="100">
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_test_date" id="x<?php echo $RowIndex ?>_t_sat_test_date" title="" size="10" value="<?php echo $tbl_testing_sat->t_sat_test_date->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_test_date->EditAttributes() ?>></td>
		<!-- t_sat_reading -->
		<td width="75">
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_reading" id="x<?php echo $RowIndex ?>_t_sat_reading" title="" size="3" value="<?php echo $tbl_testing_sat->t_sat_reading->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_reading->EditAttributes() ?>></td>
		<!-- t_sat_math -->
		<td width="75">
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_math" id="x<?php echo $RowIndex ?>_t_sat_math" title="" size="3" value="<?php echo $tbl_testing_sat->t_sat_math->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_math->EditAttributes() ?>></td>
		<!-- t_sat_writing -->
		<td width="75">
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_writing" id="x<?php echo $RowIndex ?>_t_sat_writing" title="" size="3" value="<?php echo $tbl_testing_sat->t_sat_writing->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_writing->EditAttributes() ?>></td>
		<!-- t_sat_essay -->
		<td width="75">
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_essay" id="x<?php echo $RowIndex ?>_t_sat_essay" title="" size="3" value="<?php echo $tbl_testing_sat->t_sat_essay->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_essay->EditAttributes() ?>></td>
		<!-- t_sat_test_site -->
		<td width="100">
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_test_site" id="x<?php echo $RowIndex ?>_t_sat_test_site" title="" size="20" maxlength="125" value="<?php echo $tbl_testing_sat->t_sat_test_site->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_test_site->EditAttributes() ?>></td>
		<!-- s_stuid -->
		<td>
<?php if ($tbl_testing_sat->s_stuid->getSessionValue() <> "") { ?>
<input type="hidden" id="x<?php echo $RowIndex ?>_s_stuid" name="x<?php echo $RowIndex ?>_s_stuid" value="<?php echo ew_HtmlEncode($tbl_testing_sat->s_stuid->CurrentValue) ?>">
<?php } else { ?><?php } ?></td>
<td colspan="<?php echo $OptionCnt ?>"><span class="edge">
<a href="" onClick="if (ew_ValidateForm(document.ftbl_testing_satlist)) document.ftbl_testing_satlist.submit();return false;">Insert</a>&nbsp;<a href="tbl_testing_satlist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" id="a_list" value="insert">
</span></td>
	</tr>
<?php
}
?>
<?php
if (defined("EW_EXPORT_ALL") && $tbl_testing_sat->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$tbl_testing_sat->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
$nEditRowCnt = 0;
if ($tbl_testing_sat->CurrentAction == "edit") $RowIndex = 1;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;

	// Init row class and style
	$tbl_testing_sat->CssClass = "ewTableRow";
	$tbl_testing_sat->CssStyle = "";

	// Init row event
	$tbl_testing_sat->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
	LoadRowValues($rs); // Load row values
	$tbl_testing_sat->RowType = EW_ROWTYPE_VIEW; // Render view
	if ($tbl_testing_sat->CurrentAction == "edit") {
		if (CheckInlineEditKey() && $nEditRowCnt == 0) { // Inline edit
			$tbl_testing_sat->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
	}
		if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT && $tbl_testing_sat->EventCancelled) { // Update failed
			if ($tbl_testing_sat->CurrentAction == "edit") {
				RestoreFormValues(); // Restore form values
			}
		}
		if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit row
			$nEditRowCnt++;
			$tbl_testing_sat->CssClass = "ewTableEditRow";
			$tbl_testing_sat->RowClientEvents = "onmouseover='this.edit=true;ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
		}
	RenderRow();
?>
	<!-- Table body -->
	<tr<?php echo $tbl_testing_sat->DisplayAttributes() ?>>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { ?>
<input type="hidden" name="x<?php echo $RowIndex ?>_t_satid" id="x<?php echo $RowIndex ?>_t_satid" value="<?php echo ew_HtmlEncode($tbl_testing_sat->t_satid->CurrentValue) ?>">
<?php } ?>
		<!-- t_sat_test_date -->
		<td width="100"<?php echo $tbl_testing_sat->t_sat_test_date->CellAttributes() ?>>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_test_date" id="x<?php echo $RowIndex ?>_t_sat_test_date" title="" size="10" value="<?php echo $tbl_testing_sat->t_sat_test_date->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_test_date->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_testing_sat->t_sat_test_date->ViewAttributes() ?>><?php echo $tbl_testing_sat->t_sat_test_date->ViewValue ?></div>
<?php } ?></td>
		<!-- t_sat_reading -->
		<td width="75"<?php echo $tbl_testing_sat->t_sat_reading->CellAttributes() ?>>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_reading" id="x<?php echo $RowIndex ?>_t_sat_reading" title="" size="3" value="<?php echo $tbl_testing_sat->t_sat_reading->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_reading->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_testing_sat->t_sat_reading->ViewAttributes() ?>><?php echo $tbl_testing_sat->t_sat_reading->ViewValue ?></div>
<?php } ?></td>
		<!-- t_sat_math -->
		<td width="75"<?php echo $tbl_testing_sat->t_sat_math->CellAttributes() ?>>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_math" id="x<?php echo $RowIndex ?>_t_sat_math" title="" size="3" value="<?php echo $tbl_testing_sat->t_sat_math->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_math->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_testing_sat->t_sat_math->ViewAttributes() ?>><?php echo $tbl_testing_sat->t_sat_math->ViewValue ?></div>
<?php } ?></td>
		<!-- t_sat_writing -->
		<td width="75"<?php echo $tbl_testing_sat->t_sat_writing->CellAttributes() ?>>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_writing" id="x<?php echo $RowIndex ?>_t_sat_writing" title="" size="3" value="<?php echo $tbl_testing_sat->t_sat_writing->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_writing->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_testing_sat->t_sat_writing->ViewAttributes() ?>><?php echo $tbl_testing_sat->t_sat_writing->ViewValue ?></div>
<?php } ?></td>
		<!-- t_sat_essay -->
		<td width="75"<?php echo $tbl_testing_sat->t_sat_essay->CellAttributes() ?>>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_essay" id="x<?php echo $RowIndex ?>_t_sat_essay" title="" size="3" value="<?php echo $tbl_testing_sat->t_sat_essay->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_essay->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_testing_sat->t_sat_essay->ViewAttributes() ?>><?php echo $tbl_testing_sat->t_sat_essay->ViewValue ?></div>
<?php } ?></td>
		<!-- t_sat_test_site -->
		<td width="100"<?php echo $tbl_testing_sat->t_sat_test_site->CellAttributes() ?>>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_t_sat_test_site" id="x<?php echo $RowIndex ?>_t_sat_test_site" title="" size="20" maxlength="125" value="<?php echo $tbl_testing_sat->t_sat_test_site->EditValue ?>"<?php echo $tbl_testing_sat->t_sat_test_site->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_testing_sat->t_sat_test_site->ViewAttributes() ?>><?php echo $tbl_testing_sat->t_sat_test_site->ViewValue ?></div>
<?php } ?></td>
		<!-- s_stuid -->
		<td<?php echo $tbl_testing_sat->s_stuid->CellAttributes() ?>>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<?php if ($tbl_testing_sat->s_stuid->getSessionValue() <> "") { ?>
<input type="hidden" id="x<?php echo $RowIndex ?>_s_stuid" name="x<?php echo $RowIndex ?>_s_stuid" value="<?php echo ew_HtmlEncode($tbl_testing_sat->s_stuid->CurrentValue) ?>">
<?php } else { ?>
<input type="hidden" name="x<?php echo $RowIndex ?>_s_stuid" id="x<?php echo $RowIndex ?>_s_stuid" value="<?php echo ew_HtmlEncode($tbl_testing_sat->s_stuid->CurrentValue) ?>">
<?php } ?>
<?php } else { ?>
<?php } ?></td>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { ?>
<?php if ($tbl_testing_sat->CurrentAction == "edit") { ?>
<td colspan="<?php echo $OptionCnt ?>"><span class="edge">
<a href="" onClick="if (ew_ValidateForm(document.ftbl_testing_satlist)) document.ftbl_testing_satlist.submit();return false;">Update</a>&nbsp;<a href="tbl_testing_satlist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" id="a_list" value="update">
</span></td>
<?php } ?>
<?php } else { ?>
<?php if ($tbl_testing_sat->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<td nowrap><span class="edge">
<a href="<?php echo $tbl_testing_sat->InlineEditUrl() ?>"> Edit</a>
</span></td>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($OptionCnt == 0 && $tbl_testing_sat->CurrentAction == "add") { ?>
<td nowrap>&nbsp;</td>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
	</tr>
<?php if ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { ?>
<?php } ?>
<?php
	}
	$rs->MoveNext();
}
?>
</table>
<?php if ($tbl_testing_sat->Export == "") { ?>
<?php } ?>
<?php } ?>
<?php if ($tbl_testing_sat->CurrentAction == "add" || $tbl_testing_sat->CurrentAction == "copy") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $RowIndex ?>">
<?php } ?>
<?php if ($tbl_testing_sat->CurrentAction == "edit") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $RowIndex ?>">
<?php } ?>
</form>
<table>
  <tr>
    <td><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <a href="tbl_testing_satlist.php?a=add"> Add</a>&nbsp;&nbsp;
      <?php } ?>
    </span></td>
  </tr>
</table>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($tbl_testing_sat->Export == "") { ?>
<form action="tbl_testing_satlist.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap>
<span class="edge">
<?php if (!isset($Pager)) $Pager = new cNumericPager($nStartRec, $nDisplayRecs, $nTotalRecs, $nRecRange) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<a href="tbl_testing_satlist.php?start=<?php echo $Pager->FirstButton->Start ?>"><b>First</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<a href="tbl_testing_satlist.php?start=<?php echo $Pager->PrevButton->Start ?>"><b>Previous</b></a>&nbsp;
	<?php } ?>
	<?php foreach ($Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="tbl_testing_satlist.php?start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($Pager->NextButton->Enabled) { ?>
	<a href="tbl_testing_satlist.php?start=<?php echo $Pager->NextButton->Start ?>"><b>Next</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->LastButton->Enabled) { ?>
	<a href="tbl_testing_satlist.php?start=<?php echo $Pager->LastButton->Start ?>"><b>Last</b></a>&nbsp;
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
<?php if ($tbl_testing_sat->Export == "") { ?>
<?php } ?>
<?php if ($tbl_testing_sat->Export == "") { ?>
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
	global $tbl_testing_sat;
	$tbl_testing_sat->setKey("t_satid", ""); // Clear inline edit key
	$tbl_testing_sat->CurrentAction = ""; // Clear action
	$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
}

// Switch to Inline Edit Mode
function InlineEditMode() {
	global $Security, $tbl_testing_sat;
	$bInlineEdit = TRUE;
	if (@$_GET["t_satid"] <> "") {
		$tbl_testing_sat->t_satid->setQueryStringValue($_GET["t_satid"]);
	} else {
		$bInlineEdit = FALSE;
	}
	if ($bInlineEdit) {
		if (LoadRow()) {
			$tbl_testing_sat->setKey("t_satid", $tbl_testing_sat->t_satid->CurrentValue); // Set up inline edit key
			$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
		}
	}
}

// Peform update to inline edit record
function InlineUpdate() {
	global $objForm, $tbl_testing_sat;
	$objForm->Index = 1; 
	LoadFormValues(); // Get form values
	if (CheckInlineEditKey()) { // Check key
		$tbl_testing_sat->SendEmail = TRUE; // Send email on update success
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
		$tbl_testing_sat->EventCancelled = TRUE; // Cancel event
		$tbl_testing_sat->CurrentAction = "edit"; // Stay in edit mode
	}
}

// Check inline edit key
function CheckInlineEditKey() {
	global $tbl_testing_sat;

	//CheckInlineEditKey = True
	if (strval($tbl_testing_sat->getKey("t_satid")) <> strval($tbl_testing_sat->t_satid->CurrentValue)) {
		return FALSE;
	}
	return TRUE;
}

// Switch to Inline Add Mode
function InlineAddMode() {
	global $Security, $tbl_testing_sat;
	$tbl_testing_sat->CurrentAction = "add";
	$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
}

// Peform update to inline add/copy record
function InlineInsert() {
	global $objForm, $tbl_testing_sat;
	$objForm->Index = 1;
	LoadFormValues(); // Get form values
	$tbl_testing_sat->SendEmail = TRUE; // Send email on add success
	if (AddRow()) { // Add record
		$_SESSION[EW_SESSION_MESSAGE] = "Add New Record Successful"; // Set add success message
		ClearInlineMode(); // Clear inline add mode
	} else { // Add failed
		$tbl_testing_sat->EventCancelled = TRUE; // Set event cancelled
		$tbl_testing_sat->CurrentAction = "add"; // Stay in add mode
	}
}

// Set up Sort parameters based on Sort Links clicked
function SetUpSortOrder() {
	global $tbl_testing_sat;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$tbl_testing_sat->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$tbl_testing_sat->CurrentOrderType = @$_GET["ordertype"];
		$tbl_testing_sat->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $tbl_testing_sat->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($tbl_testing_sat->SqlOrderBy() <> "") {
			$sOrderBy = $tbl_testing_sat->SqlOrderBy();
			$tbl_testing_sat->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $tbl_testing_sat;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset master/detail keys
		if (strtolower($sCmd) == "resetall") {
			$tbl_testing_sat->setMasterFilter(""); // Clear master filter
			$sDbMasterFilter = "";
			$tbl_testing_sat->setDetailFilter(""); // Clear detail filter
			$sDbDetailFilter = "";
			$tbl_testing_sat->s_stuid->setSessionValue("");
		}

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$tbl_testing_sat->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$tbl_testing_sat->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $tbl_testing_sat;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$tbl_testing_sat->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$tbl_testing_sat->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $tbl_testing_sat->getStartRecordNumber();
		}
	} else {
		$nStartRec = $tbl_testing_sat->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$tbl_testing_sat->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$tbl_testing_sat->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$tbl_testing_sat->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load default values
function LoadDefaultValues() {
	global $tbl_testing_sat;
}
?>
<?php

// Load form values
function LoadFormValues() {

	// Load from form
	global $objForm, $tbl_testing_sat;
	$tbl_testing_sat->t_satid->setFormValue($objForm->GetValue("x_t_satid"));
	$tbl_testing_sat->t_sat_test_date->setFormValue($objForm->GetValue("x_t_sat_test_date"));
	$tbl_testing_sat->t_sat_test_date->CurrentValue = ew_UnFormatDateTime($tbl_testing_sat->t_sat_test_date->CurrentValue, 6);
	$tbl_testing_sat->t_sat_reading->setFormValue($objForm->GetValue("x_t_sat_reading"));
	$tbl_testing_sat->t_sat_math->setFormValue($objForm->GetValue("x_t_sat_math"));
	$tbl_testing_sat->t_sat_writing->setFormValue($objForm->GetValue("x_t_sat_writing"));
	$tbl_testing_sat->t_sat_essay->setFormValue($objForm->GetValue("x_t_sat_essay"));
	$tbl_testing_sat->t_sat_test_site->setFormValue($objForm->GetValue("x_t_sat_test_site"));
	$tbl_testing_sat->s_stuid->setFormValue($objForm->GetValue("x_s_stuid"));
}

// Restore form values
function RestoreFormValues() {
	global $tbl_testing_sat;
	$tbl_testing_sat->t_satid->CurrentValue = $tbl_testing_sat->t_satid->FormValue;
	$tbl_testing_sat->t_sat_test_date->CurrentValue = $tbl_testing_sat->t_sat_test_date->FormValue;
	$tbl_testing_sat->t_sat_test_date->CurrentValue = ew_UnFormatDateTime($tbl_testing_sat->t_sat_test_date->CurrentValue, 6);
	$tbl_testing_sat->t_sat_reading->CurrentValue = $tbl_testing_sat->t_sat_reading->FormValue;
	$tbl_testing_sat->t_sat_math->CurrentValue = $tbl_testing_sat->t_sat_math->FormValue;
	$tbl_testing_sat->t_sat_writing->CurrentValue = $tbl_testing_sat->t_sat_writing->FormValue;
	$tbl_testing_sat->t_sat_essay->CurrentValue = $tbl_testing_sat->t_sat_essay->FormValue;
	$tbl_testing_sat->t_sat_test_site->CurrentValue = $tbl_testing_sat->t_sat_test_site->FormValue;
	$tbl_testing_sat->s_stuid->CurrentValue = $tbl_testing_sat->s_stuid->FormValue;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_testing_sat;

	// Call Recordset Selecting event
	$tbl_testing_sat->Recordset_Selecting($tbl_testing_sat->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_testing_sat->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_testing_sat->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_testing_sat;
	$sFilter = $tbl_testing_sat->SqlKeyFilter();
	if (!is_numeric($tbl_testing_sat->t_satid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@t_satid@", ew_AdjustSql($tbl_testing_sat->t_satid->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_testing_sat->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add User ID filter for master table
	}

	// Call Row Selecting event
	$tbl_testing_sat->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_testing_sat->CurrentFilter = $sFilter;
	$sSql = $tbl_testing_sat->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_testing_sat->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_testing_sat;
	$tbl_testing_sat->t_satid->setDbValue($rs->fields('t_satid'));
	$tbl_testing_sat->t_sat_test_date->setDbValue($rs->fields('t_sat_test_date'));
	$tbl_testing_sat->t_sat_reading->setDbValue($rs->fields('t_sat_reading'));
	$tbl_testing_sat->t_sat_math->setDbValue($rs->fields('t_sat_math'));
	$tbl_testing_sat->t_sat_writing->setDbValue($rs->fields('t_sat_writing'));
	$tbl_testing_sat->t_sat_essay->setDbValue($rs->fields('t_sat_essay'));
	$tbl_testing_sat->t_sat_test_site->setDbValue($rs->fields('t_sat_test_site'));
	$tbl_testing_sat->s_stuid->setDbValue($rs->fields('s_stuid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_testing_sat;

	// Call Row Rendering event
	$tbl_testing_sat->Row_Rendering();

	// Common render codes for all row types
	// t_sat_test_date

	$tbl_testing_sat->t_sat_test_date->CellCssStyle = "";
	$tbl_testing_sat->t_sat_test_date->CellCssClass = "";

	// t_sat_reading
	$tbl_testing_sat->t_sat_reading->CellCssStyle = "";
	$tbl_testing_sat->t_sat_reading->CellCssClass = "";

	// t_sat_math
	$tbl_testing_sat->t_sat_math->CellCssStyle = "";
	$tbl_testing_sat->t_sat_math->CellCssClass = "";

	// t_sat_writing
	$tbl_testing_sat->t_sat_writing->CellCssStyle = "";
	$tbl_testing_sat->t_sat_writing->CellCssClass = "";

	// t_sat_essay
	$tbl_testing_sat->t_sat_essay->CellCssStyle = "";
	$tbl_testing_sat->t_sat_essay->CellCssClass = "";

	// t_sat_test_site
	$tbl_testing_sat->t_sat_test_site->CellCssStyle = "";
	$tbl_testing_sat->t_sat_test_site->CellCssClass = "";

	// s_stuid
	$tbl_testing_sat->s_stuid->CellCssStyle = "";
	$tbl_testing_sat->s_stuid->CellCssClass = "";
	if ($tbl_testing_sat->RowType == EW_ROWTYPE_VIEW) { // View row

		// t_sat_test_date
		$tbl_testing_sat->t_sat_test_date->ViewValue = $tbl_testing_sat->t_sat_test_date->CurrentValue;
		$tbl_testing_sat->t_sat_test_date->ViewValue = ew_FormatDateTime($tbl_testing_sat->t_sat_test_date->ViewValue, 6);
		$tbl_testing_sat->t_sat_test_date->CssStyle = "";
		$tbl_testing_sat->t_sat_test_date->CssClass = "";
		$tbl_testing_sat->t_sat_test_date->ViewCustomAttributes = "";

		// t_sat_reading
		$tbl_testing_sat->t_sat_reading->ViewValue = $tbl_testing_sat->t_sat_reading->CurrentValue;
		$tbl_testing_sat->t_sat_reading->CssStyle = "";
		$tbl_testing_sat->t_sat_reading->CssClass = "";
		$tbl_testing_sat->t_sat_reading->ViewCustomAttributes = "";

		// t_sat_math
		$tbl_testing_sat->t_sat_math->ViewValue = $tbl_testing_sat->t_sat_math->CurrentValue;
		$tbl_testing_sat->t_sat_math->CssStyle = "";
		$tbl_testing_sat->t_sat_math->CssClass = "";
		$tbl_testing_sat->t_sat_math->ViewCustomAttributes = "";

		// t_sat_writing
		$tbl_testing_sat->t_sat_writing->ViewValue = $tbl_testing_sat->t_sat_writing->CurrentValue;
		$tbl_testing_sat->t_sat_writing->CssStyle = "";
		$tbl_testing_sat->t_sat_writing->CssClass = "";
		$tbl_testing_sat->t_sat_writing->ViewCustomAttributes = "";

		// t_sat_essay
		$tbl_testing_sat->t_sat_essay->ViewValue = $tbl_testing_sat->t_sat_essay->CurrentValue;
		$tbl_testing_sat->t_sat_essay->CssStyle = "";
		$tbl_testing_sat->t_sat_essay->CssClass = "";
		$tbl_testing_sat->t_sat_essay->ViewCustomAttributes = "";

		// t_sat_test_site
		$tbl_testing_sat->t_sat_test_site->ViewValue = $tbl_testing_sat->t_sat_test_site->CurrentValue;
		$tbl_testing_sat->t_sat_test_site->CssStyle = "";
		$tbl_testing_sat->t_sat_test_site->CssClass = "";
		$tbl_testing_sat->t_sat_test_site->ViewCustomAttributes = "";

		// s_stuid
		$tbl_testing_sat->s_stuid->ViewValue = $tbl_testing_sat->s_stuid->CurrentValue;
		$tbl_testing_sat->s_stuid->CssStyle = "";
		$tbl_testing_sat->s_stuid->CssClass = "";
		$tbl_testing_sat->s_stuid->ViewCustomAttributes = "";

		// t_sat_test_date
		$tbl_testing_sat->t_sat_test_date->HrefValue = "";

		// t_sat_reading
		$tbl_testing_sat->t_sat_reading->HrefValue = "";

		// t_sat_math
		$tbl_testing_sat->t_sat_math->HrefValue = "";

		// t_sat_writing
		$tbl_testing_sat->t_sat_writing->HrefValue = "";

		// t_sat_essay
		$tbl_testing_sat->t_sat_essay->HrefValue = "";

		// t_sat_test_site
		$tbl_testing_sat->t_sat_test_site->HrefValue = "";

		// s_stuid
		$tbl_testing_sat->s_stuid->HrefValue = "";
	} elseif ($tbl_testing_sat->RowType == EW_ROWTYPE_ADD) { // Add row

		// t_sat_test_date
		$tbl_testing_sat->t_sat_test_date->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_test_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($tbl_testing_sat->t_sat_test_date->CurrentValue, 6));

		// t_sat_reading
		$tbl_testing_sat->t_sat_reading->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_reading->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_reading->CurrentValue);

		// t_sat_math
		$tbl_testing_sat->t_sat_math->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_math->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_math->CurrentValue);

		// t_sat_writing
		$tbl_testing_sat->t_sat_writing->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_writing->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_writing->CurrentValue);

		// t_sat_essay
		$tbl_testing_sat->t_sat_essay->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_essay->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_essay->CurrentValue);

		// t_sat_test_site
		$tbl_testing_sat->t_sat_test_site->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_test_site->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_test_site->CurrentValue);

		// s_stuid
		$tbl_testing_sat->s_stuid->EditCustomAttributes = "";
		if ($tbl_testing_sat->s_stuid->getSessionValue() <> "") {
			$tbl_testing_sat->s_stuid->CurrentValue = $tbl_testing_sat->s_stuid->getSessionValue();
		$tbl_testing_sat->s_stuid->ViewValue = $tbl_testing_sat->s_stuid->CurrentValue;
		$tbl_testing_sat->s_stuid->CssStyle = "";
		$tbl_testing_sat->s_stuid->CssClass = "";
		$tbl_testing_sat->s_stuid->ViewCustomAttributes = "";
		} else {
		$tbl_testing_sat->s_stuid->EditValue = ew_HtmlEncode($tbl_testing_sat->s_stuid->CurrentValue);
		}
	} elseif ($tbl_testing_sat->RowType == EW_ROWTYPE_EDIT) { // Edit row

		// t_sat_test_date
		$tbl_testing_sat->t_sat_test_date->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_test_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($tbl_testing_sat->t_sat_test_date->CurrentValue, 6));

		// t_sat_reading
		$tbl_testing_sat->t_sat_reading->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_reading->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_reading->CurrentValue);

		// t_sat_math
		$tbl_testing_sat->t_sat_math->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_math->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_math->CurrentValue);

		// t_sat_writing
		$tbl_testing_sat->t_sat_writing->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_writing->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_writing->CurrentValue);

		// t_sat_essay
		$tbl_testing_sat->t_sat_essay->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_essay->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_essay->CurrentValue);

		// t_sat_test_site
		$tbl_testing_sat->t_sat_test_site->EditCustomAttributes = "";
		$tbl_testing_sat->t_sat_test_site->EditValue = ew_HtmlEncode($tbl_testing_sat->t_sat_test_site->CurrentValue);

		// s_stuid
		$tbl_testing_sat->s_stuid->EditCustomAttributes = "";
		if ($tbl_testing_sat->s_stuid->getSessionValue() <> "") {
			$tbl_testing_sat->s_stuid->CurrentValue = $tbl_testing_sat->s_stuid->getSessionValue();
		$tbl_testing_sat->s_stuid->ViewValue = $tbl_testing_sat->s_stuid->CurrentValue;
		$tbl_testing_sat->s_stuid->CssStyle = "";
		$tbl_testing_sat->s_stuid->CssClass = "";
		$tbl_testing_sat->s_stuid->ViewCustomAttributes = "";
		} else {
		}
	} elseif ($tbl_testing_sat->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_testing_sat->Row_Rendered();
}
?>
<?php

// Update record based on key values
function EditRow() {
	global $conn, $Security, $tbl_testing_sat;
	$sFilter = $tbl_testing_sat->SqlKeyFilter();
	if (!is_numeric($tbl_testing_sat->t_satid->CurrentValue)) {
		return FALSE;
	}
	$sFilter = str_replace("@t_satid@", ew_AdjustSql($tbl_testing_sat->t_satid->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_testing_sat->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add User ID filter for master table
		$tbl_testing_sat->CurrentFilter = $sFilter;
	}
	$tbl_testing_sat->CurrentFilter = $sFilter;
	$sSql = $tbl_testing_sat->SQL();
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

		// Field t_sat_test_date
		$tbl_testing_sat->t_sat_test_date->SetDbValueDef(ew_UnFormatDateTime($tbl_testing_sat->t_sat_test_date->CurrentValue, 6), ew_CurrentDate());
		$rsnew['t_sat_test_date'] =& $tbl_testing_sat->t_sat_test_date->DbValue;

		// Field t_sat_reading
		$tbl_testing_sat->t_sat_reading->SetDbValueDef($tbl_testing_sat->t_sat_reading->CurrentValue, 0);
		$rsnew['t_sat_reading'] =& $tbl_testing_sat->t_sat_reading->DbValue;

		// Field t_sat_math
		$tbl_testing_sat->t_sat_math->SetDbValueDef($tbl_testing_sat->t_sat_math->CurrentValue, 0);
		$rsnew['t_sat_math'] =& $tbl_testing_sat->t_sat_math->DbValue;

		// Field t_sat_writing
		$tbl_testing_sat->t_sat_writing->SetDbValueDef($tbl_testing_sat->t_sat_writing->CurrentValue, 0);
		$rsnew['t_sat_writing'] =& $tbl_testing_sat->t_sat_writing->DbValue;

		// Field t_sat_essay
		$tbl_testing_sat->t_sat_essay->SetDbValueDef($tbl_testing_sat->t_sat_essay->CurrentValue, 0);
		$rsnew['t_sat_essay'] =& $tbl_testing_sat->t_sat_essay->DbValue;

		// Field t_sat_test_site
		$tbl_testing_sat->t_sat_test_site->SetDbValueDef($tbl_testing_sat->t_sat_test_site->CurrentValue, "");
		$rsnew['t_sat_test_site'] =& $tbl_testing_sat->t_sat_test_site->DbValue;

		// Field s_stuid
		$tbl_testing_sat->s_stuid->SetDbValueDef($tbl_testing_sat->s_stuid->CurrentValue, 0);
		$rsnew['s_stuid'] =& $tbl_testing_sat->s_stuid->DbValue;

		// Call Row Updating event
		$bUpdateRow = $tbl_testing_sat->Row_Updating($rsold, $rsnew);
		if ($bUpdateRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$EditRow = $conn->Execute($tbl_testing_sat->UpdateSQL($rsnew));
			$conn->raiseErrorFn = '';
		} else {
			if ($tbl_testing_sat->CancelMessage <> "") {
				$_SESSION[EW_SESSION_MESSAGE] = $tbl_testing_sat->CancelMessage;
				$tbl_testing_sat->CancelMessage = "";
			} else {
				$_SESSION[EW_SESSION_MESSAGE] = "Update cancelled";
			}
			$EditRow = FALSE;
		}
	}

	// Call Row Updated event
	if ($EditRow) {
		$tbl_testing_sat->Row_Updated($rsold, $rsnew);
	}
	$rs->Close();
	return $EditRow;
}
?>
<?php

// Add record
function AddRow() {
	global $conn, $Security, $tbl_testing_sat;

	// Check if valid User ID for master
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_testing_sat->AddMasterUserIDFilter("", $tbl_testing_sat->getCurrentMasterTable(), $Security->CurrentUserID());
		if ($tbl_testing_sat->getCurrentMasterTable() == "tbl_students") {
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
	$sFilter = $tbl_testing_sat->SqlKeyFilter();
	if (trim(strval($tbl_testing_sat->t_satid->CurrentValue)) == "") {
		$bCheckKey = FALSE;
	} else {
		$sFilter = str_replace("@t_satid@", ew_AdjustSql($tbl_testing_sat->t_satid->CurrentValue), $sFilter); // Replace key value
	}
	if (!is_numeric($tbl_testing_sat->t_satid->CurrentValue)) {
		$bCheckKey = FALSE;
	}
	if ($bCheckKey) {
		$rsChk = $tbl_testing_sat->LoadRs($sFilter);
		if ($rsChk && !$rsChk->EOF) {
			$_SESSION[EW_SESSION_MESSAGE] = "Duplicate value for primary key";
			$rsChk->Close();
			return FALSE;
		}
	}
	$rsnew = array();

	// Field t_sat_test_date
	$tbl_testing_sat->t_sat_test_date->SetDbValueDef(ew_UnFormatDateTime($tbl_testing_sat->t_sat_test_date->CurrentValue, 6), ew_CurrentDate());
	$rsnew['t_sat_test_date'] =& $tbl_testing_sat->t_sat_test_date->DbValue;

	// Field t_sat_reading
	$tbl_testing_sat->t_sat_reading->SetDbValueDef($tbl_testing_sat->t_sat_reading->CurrentValue, 0);
	$rsnew['t_sat_reading'] =& $tbl_testing_sat->t_sat_reading->DbValue;

	// Field t_sat_math
	$tbl_testing_sat->t_sat_math->SetDbValueDef($tbl_testing_sat->t_sat_math->CurrentValue, 0);
	$rsnew['t_sat_math'] =& $tbl_testing_sat->t_sat_math->DbValue;

	// Field t_sat_writing
	$tbl_testing_sat->t_sat_writing->SetDbValueDef($tbl_testing_sat->t_sat_writing->CurrentValue, 0);
	$rsnew['t_sat_writing'] =& $tbl_testing_sat->t_sat_writing->DbValue;

	// Field t_sat_essay
	$tbl_testing_sat->t_sat_essay->SetDbValueDef($tbl_testing_sat->t_sat_essay->CurrentValue, 0);
	$rsnew['t_sat_essay'] =& $tbl_testing_sat->t_sat_essay->DbValue;

	// Field t_sat_test_site
	$tbl_testing_sat->t_sat_test_site->SetDbValueDef($tbl_testing_sat->t_sat_test_site->CurrentValue, "");
	$rsnew['t_sat_test_site'] =& $tbl_testing_sat->t_sat_test_site->DbValue;

	// Field s_stuid
	$tbl_testing_sat->s_stuid->SetDbValueDef($tbl_testing_sat->s_stuid->CurrentValue, 0);
	$rsnew['s_stuid'] =& $tbl_testing_sat->s_stuid->DbValue;

	// Call Row Inserting event
	$bInsertRow = $tbl_testing_sat->Row_Inserting($rsnew);
	if ($bInsertRow) {
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$AddRow = $conn->Execute($tbl_testing_sat->InsertSQL($rsnew));
		$conn->raiseErrorFn = '';
	} else {
		if ($tbl_testing_sat->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_testing_sat->CancelMessage;
			$tbl_testing_sat->CancelMessage = "";
		} else {
			$_SESSION[EW_SESSION_MESSAGE] = "Insert cancelled";
		}
		$AddRow = FALSE;
	}
	if ($AddRow) {
		$tbl_testing_sat->t_satid->setDbValue($conn->Insert_ID());
		$rsnew['t_satid'] =& $tbl_testing_sat->t_satid->DbValue;

		// Call Row Inserted event
		$tbl_testing_sat->Row_Inserted($rsnew);
	}
	return $AddRow;
}
?>
<?php

// Set up Master Detail based on querystring parameter
function SetUpMasterDetail() {
	global $nStartRec, $sDbMasterFilter, $sDbDetailFilter, $tbl_testing_sat;
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
			$sDbMasterFilter = $tbl_testing_sat->SqlMasterFilter_tbl_students();
			$sDbDetailFilter = $tbl_testing_sat->SqlDetailFilter_tbl_students();
			if (@$_GET["s_studentid"] <> "") {
				$GLOBALS["tbl_students"]->s_studentid->setQueryStringValue($_GET["s_studentid"]);
				$tbl_testing_sat->s_stuid->setQueryStringValue($GLOBALS["tbl_students"]->s_studentid->QueryStringValue);
				$tbl_testing_sat->s_stuid->setSessionValue($tbl_testing_sat->s_stuid->QueryStringValue);
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
		$tbl_testing_sat->setCurrentMasterTable($sMasterTblVar);

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$tbl_testing_sat->setStartRecordNumber($nStartRec);
		$tbl_testing_sat->setMasterFilter($sDbMasterFilter); // Set up master filter
		$tbl_testing_sat->setDetailFilter($sDbDetailFilter); // Set up detail filter

		// Clear previous master session values
		if ($sMasterTblVar <> "tbl_students") {
			if ($tbl_testing_sat->s_stuid->QueryStringValue == "") $tbl_testing_sat->s_stuid->setSessionValue("");
		}
	} else {
		$sDbMasterFilter = $tbl_testing_sat->getMasterFilter(); //  Restore master filter
		$sDbDetailFilter = $tbl_testing_sat->getDetailFilter(); // Restore detail filter
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