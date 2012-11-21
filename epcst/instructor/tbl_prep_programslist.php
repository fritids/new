<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_prep_programs', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_prep_programsinfo.php" ?>
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
$tbl_prep_programs->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_prep_programs->Export; // Get export parameter, used in header
$sExportFile = $tbl_prep_programs->TableVar; // Get export file, used in header
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

// Set up master detail parameters
SetUpMasterDetail();

// Check QueryString parameters
if (@$_GET["a"] <> "") {
	$tbl_prep_programs->CurrentAction = $_GET["a"];

	// Clear inline mode
	if ($tbl_prep_programs->CurrentAction == "cancel") {
		ClearInlineMode();
	}

	// Switch to inline edit mode
	if ($tbl_prep_programs->CurrentAction == "edit") {
		InlineEditMode();
	}
} else {

	// Create form object
	$objForm = new cFormObj;
	if (@$_POST["a_list"] <> "") {
		$tbl_prep_programs->CurrentAction = $_POST["a_list"]; // Get action

		// Inline Update
		if ($tbl_prep_programs->CurrentAction == "update" && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit") {
			InlineUpdate();
		}
	}
}

// Build filter
$sFilter = "";
if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
	if ($tbl_prep_programs->getCurrentMasterTable() == "tbl_students") {
		$sFilter = $tbl_prep_programs->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add detail User ID filter
		$sDbMasterFilter = $tbl_prep_programs->AddMasterUserIDFilter($sDbMasterFilter, "tbl_students", $Security->CurrentUserID()); // Add master User ID filter
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
if ($tbl_prep_programs->getMasterFilter() <> "" && $tbl_prep_programs->getCurrentMasterTable() == "tbl_students") {
	$rsmaster = $tbl_students->LoadRs($sDbMasterFilter);
	$bMasterRecordExists = ($rsmaster && !$rsmaster->EOF);
	if (!$bMasterRecordExists) {
		$tbl_prep_programs->setMasterFilter(""); // Clear master filter
		$tbl_prep_programs->setDetailFilter(""); // Clear detail filter
		$_SESSION[EW_SESSION_MESSAGE] = "No records found"; // Set no record found
		Page_Terminate("tbl_studentslist.php"); // Return to caller
	} else {
		$tbl_students->LoadListRowValues($rsmaster);
		$tbl_students->RenderListRow();
		$rsmaster->Close();
	}
}

// Set up filter in Session
$tbl_prep_programs->setSessionWhere($sFilter);
$tbl_prep_programs->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$tbl_prep_programs->setReturnUrl("tbl_prep_programslist.php");
?>
<?php include "header.php" ?>
<?php if ($tbl_prep_programs->Export == "") { ?>
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
	}
	return true;
}

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
<?php if ($tbl_prep_programs->Export == "") { ?>
<?php
$sMasterReturnUrl = "tbl_studentslist.php";
if ($tbl_prep_programs->getMasterFilter() <> "" && $tbl_prep_programs->getCurrentMasterTable() == "tbl_students") {
	if ($bMasterRecordExists) {
		if ($tbl_prep_programs->getCurrentMasterTable() == $tbl_prep_programs->TableVar) $sMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include "tbl_studentsmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $tbl_prep_programs->Export <> "");
$bSelectLimit = ($tbl_prep_programs->Export == "" && $tbl_prep_programs->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $tbl_prep_programs->SelectRecordCount() : $rs->RecordCount();
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
<p><span class="edge" style="white-space: nowrap;">Student's Prep Programs
</span></p>
<?php if ($tbl_prep_programs->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form name="ftbl_prep_programslist" id="ftbl_prep_programslist" action="tbl_prep_programslist.php" method="post">
<?php if ($tbl_prep_programs->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?>
<table border="0" cellspacing="5" cellpadding="5">
<?php
if (defined("EW_EXPORT_ALL") && $tbl_prep_programs->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$tbl_prep_programs->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
$nEditRowCnt = 0;
if ($tbl_prep_programs->CurrentAction == "edit") $RowIndex = 1;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;
		$ColCnt++;
		if ($ColCnt > $nRecPerRow) $ColCnt = 1;

	// Init row class and style
	$tbl_prep_programs->CssClass = "ewTableRow";
	$tbl_prep_programs->CssStyle = "";

	// Init row event
	$tbl_prep_programs->RowClientEvents = "";
	LoadRowValues($rs); // Load row values
	$tbl_prep_programs->RowType = EW_ROWTYPE_VIEW; // Render view
	if ($tbl_prep_programs->CurrentAction == "edit") {
		if (CheckInlineEditKey() && $nEditRowCnt == 0) { // Inline edit
			$tbl_prep_programs->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
	}
		if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT && $tbl_prep_programs->EventCancelled) { // Update failed
			if ($tbl_prep_programs->CurrentAction == "edit") {
				RestoreFormValues(); // Restore form values
			}
		}
		if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit row
			$nEditRowCnt++;
			$tbl_prep_programs->CssClass = "ewTableEditRow";
			$tbl_prep_programs->RowClientEvents = "";
		}
	RenderRow();
?>
<?php if ($ColCnt == 1) { ?>
<tr>
  <td width="316" valign="top"<?php echo $tbl_prep_programs->DisplayAttributes() ?>><table class="ewTable" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="ewTableRow" heigh="10">
      <td width="100" ></td>
      <td width="150" <?php echo $tbl_prep_programs->p_prepid->CellAttributes() ?>><?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
          <input type="hidden" name="x<?php echo $RowIndex ?>_p_prepid" id="x<?php echo $RowIndex ?>_p_prepid" value="<?php echo ew_HtmlEncode($tbl_prep_programs->p_prepid->CurrentValue) ?>" />
          <?php } else { ?>
</div>
        <?php } ?></td>
    </tr>
    <tr class="ewTableRow">
      <td width="100" class="ewTableHeader"><?php if ($tbl_prep_programs->Export <> "") { ?>
        Arithmetic
        <?php } else { ?>
        Arithmetic
        <?php if ($tbl_prep_programs->p_arithmetic->getSort() == "ASC") { ?>
        <img src="images/sortup.gif" width="10" height="9" border="0" />
        <?php } elseif ($tbl_prep_programs->p_arithmetic->getSort() == "DESC") { ?>
        <img src="images/sortdown.gif" width="10" height="9" border="0" />
        <?php } ?>
        <?php } ?>      </td>
      <td width="150"<?php echo $tbl_prep_programs->p_arithmetic->CellAttributes() ?>><?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
          <input type="text" name="x<?php echo $RowIndex ?>_p_arithmetic" id="x<?php echo $RowIndex ?>_p_arithmetic" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_arithmetic->EditValue ?>"<?php echo $tbl_prep_programs->p_arithmetic->EditAttributes() ?> />
          <?php } else { ?>
          <div<?php echo $tbl_prep_programs->p_arithmetic->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_arithmetic->ViewValue ?></div>
        <?php } ?></td>
    </tr>
    <tr class="ewTableRow">
      <td width="100" class="ewTableHeader"><?php if ($tbl_prep_programs->Export <> "") { ?>
        Algebra
        <?php } else { ?>
        Algebra
        <?php if ($tbl_prep_programs->p_algebra->getSort() == "ASC") { ?>
        <img src="images/sortup.gif" width="10" height="9" border="0" />
        <?php } elseif ($tbl_prep_programs->p_algebra->getSort() == "DESC") { ?>
        <img src="images/sortdown.gif" width="10" height="9" border="0" />
        <?php } ?>
        <?php } ?>      </td>
      <td width="150"<?php echo $tbl_prep_programs->p_algebra->CellAttributes() ?>><?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
          <input type="text" name="x<?php echo $RowIndex ?>_p_algebra" id="x<?php echo $RowIndex ?>_p_algebra" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_algebra->EditValue ?>"<?php echo $tbl_prep_programs->p_algebra->EditAttributes() ?> />
          <?php } else { ?>
          <div<?php echo $tbl_prep_programs->p_algebra->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_algebra->ViewValue ?></div>
        <?php } ?></td>
    </tr>
    <tr class="ewTableRow">
      <td width="100" class="ewTableHeader"><?php if ($tbl_prep_programs->Export <> "") { ?>
        Techniques
        <?php } else { ?>
        Techniques
        <?php if ($tbl_prep_programs->p_techniques->getSort() == "ASC") { ?>
        <img src="images/sortup.gif" width="10" height="9" border="0" />
        <?php } elseif ($tbl_prep_programs->p_techniques->getSort() == "DESC") { ?>
        <img src="images/sortdown.gif" width="10" height="9" border="0" />
        <?php } ?>
        <?php } ?>      </td>
      <td width="150"<?php echo $tbl_prep_programs->p_techniques->CellAttributes() ?>><?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
          <input type="text" name="x<?php echo $RowIndex ?>_p_techniques" id="x<?php echo $RowIndex ?>_p_techniques" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_techniques->EditValue ?>"<?php echo $tbl_prep_programs->p_techniques->EditAttributes() ?> />
          <?php } else { ?>
          <div<?php echo $tbl_prep_programs->p_techniques->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_techniques->ViewValue ?></div>
        <?php } ?></td>
    </tr>
    <tr class="ewTableRow">
      <td width="100" class="ewTableHeader"><?php if ($tbl_prep_programs->Export <> "") { ?>
        Geometry
        <?php } else { ?>
        Geometry
        <?php if ($tbl_prep_programs->p_geometry->getSort() == "ASC") { ?>
        <img src="images/sortup.gif" width="10" height="9" border="0" />
        <?php } elseif ($tbl_prep_programs->p_geometry->getSort() == "DESC") { ?>
        <img src="images/sortdown.gif" width="10" height="9" border="0" />
        <?php } ?>
        <?php } ?>      </td>
      <td width="150"<?php echo $tbl_prep_programs->p_geometry->CellAttributes() ?>><?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
          <input type="text" name="x<?php echo $RowIndex ?>_p_geometry" id="x<?php echo $RowIndex ?>_p_geometry" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_geometry->EditValue ?>"<?php echo $tbl_prep_programs->p_geometry->EditAttributes() ?> />
          <?php } else { ?>
          <div<?php echo $tbl_prep_programs->p_geometry->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_geometry->ViewValue ?></div>
        <?php } ?></td>
    </tr>
    <tr class="ewTableRow">
      <td width="100" class="ewTableHeader"><?php if ($tbl_prep_programs->Export <> "") { ?>
        Advance Topics
        <?php } else { ?>
        Advance Topics
        <?php if ($tbl_prep_programs->p_advanced_topics->getSort() == "ASC") { ?>
        <img src="images/sortup.gif" width="10" height="9" border="0" />
        <?php } elseif ($tbl_prep_programs->p_advanced_topics->getSort() == "DESC") { ?>
        <img src="images/sortdown.gif" width="10" height="9" border="0" />
        <?php } ?>
        <?php } ?>      </td>
      <td width="150"<?php echo $tbl_prep_programs->p_advanced_topics->CellAttributes() ?>><?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
          <input type="text" name="x<?php echo $RowIndex ?>_p_advanced_topics" id="x<?php echo $RowIndex ?>_p_advanced_topics" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_advanced_topics->EditValue ?>"<?php echo $tbl_prep_programs->p_advanced_topics->EditAttributes() ?> />
          <?php } else { ?>
          <div<?php echo $tbl_prep_programs->p_advanced_topics->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_advanced_topics->ViewValue ?></div>
        <?php } ?></td>
    </tr>

  </table></td>
<?php } ?>
	<td width="316" valign="top"<?php echo $tbl_prep_programs->DisplayAttributes() ?>>
	<table class="ewTable">
		<tr class="ewTableRow" heigh="10">
		  <td ></td>
		  <td></td>
		  </tr>
		<tr class="ewTableRow">
			<td width="150" class="ewTableHeader">
<?php if ($tbl_prep_programs->Export <> "") { ?>
Sentence Completion
<?php } else { ?>
	Sentence Completion<?php if ($tbl_prep_programs->p_sentence_completion->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_prep_programs->p_sentence_completion->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_prep_programs->p_sentence_completion->CellAttributes() ?>>
<?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_p_sentence_completion" id="x<?php echo $RowIndex ?>_p_sentence_completion" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_sentence_completion->EditValue ?>"<?php echo $tbl_prep_programs->p_sentence_completion->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_prep_programs->p_sentence_completion->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_sentence_completion->ViewValue ?></div>
<?php } ?></td>
		</tr>
		<tr class="ewTableRow">
			<td width="150" class="ewTableHeader">
<?php if ($tbl_prep_programs->Export <> "") { ?>
Critical Reading
<?php } else { ?>
	Critical Reading<?php if ($tbl_prep_programs->p_critical_reading->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_prep_programs->p_critical_reading->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_prep_programs->p_critical_reading->CellAttributes() ?>>
<?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_p_critical_reading" id="x<?php echo $RowIndex ?>_p_critical_reading" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_critical_reading->EditValue ?>"<?php echo $tbl_prep_programs->p_critical_reading->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_prep_programs->p_critical_reading->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_critical_reading->ViewValue ?></div>
<?php } ?></td>
		</tr>
		<tr class="ewTableRow">
			<td width="150" class="ewTableHeader">
<?php if ($tbl_prep_programs->Export <> "") { ?>
Error ID
<?php } else { ?>
	Error ID<?php if ($tbl_prep_programs->p_error_id->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_prep_programs->p_error_id->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_prep_programs->p_error_id->CellAttributes() ?>>
<?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_p_error_id" id="x<?php echo $RowIndex ?>_p_error_id" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_error_id->EditValue ?>"<?php echo $tbl_prep_programs->p_error_id->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_prep_programs->p_error_id->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_error_id->ViewValue ?></div>
<?php } ?></td>
		</tr>
		<tr class="ewTableRow">
			<td width="150" class="ewTableHeader">
<?php if ($tbl_prep_programs->Export <> "") { ?>
Sentence Improvement
<?php } else { ?>
	Sentence Improvement<?php if ($tbl_prep_programs->p_sentence_improvement->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_prep_programs->p_sentence_improvement->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_prep_programs->p_sentence_improvement->CellAttributes() ?>>
<?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_p_sentence_improvement" id="x<?php echo $RowIndex ?>_p_sentence_improvement" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_sentence_improvement->EditValue ?>"<?php echo $tbl_prep_programs->p_sentence_improvement->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_prep_programs->p_sentence_improvement->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_sentence_improvement->ViewValue ?></div>
<?php } ?></td>
		</tr>
		<tr class="ewTableRow">
			<td width="150" class="ewTableHeader">
<?php if ($tbl_prep_programs->Export <> "") { ?>
Paragraph Improvement
<?php } else { ?>
	Paragraph Improvement<?php if ($tbl_prep_programs->p_paragraph_improvement->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_prep_programs->p_paragraph_improvement->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_prep_programs->p_paragraph_improvement->CellAttributes() ?>>
<?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_p_paragraph_improvement" id="x<?php echo $RowIndex ?>_p_paragraph_improvement" title="" size="15" maxlength="45" value="<?php echo $tbl_prep_programs->p_paragraph_improvement->EditValue ?>"<?php echo $tbl_prep_programs->p_paragraph_improvement->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_prep_programs->p_paragraph_improvement->ViewAttributes() ?>><?php echo $tbl_prep_programs->p_paragraph_improvement->ViewValue ?></div>
<?php } ?></td>
		</tr>
		<tr class="ewTableRow">
			<td width="150" class=""></td>
			<td width="150"<?php echo $tbl_prep_programs->s_stuid->CellAttributes() ?>>
<?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<?php if ($tbl_prep_programs->s_stuid->getSessionValue() <> "") { ?>
<input type="hidden" id="x<?php echo $RowIndex ?>_s_stuid" name="x<?php echo $RowIndex ?>_s_stuid" value="<?php echo ew_HtmlEncode($tbl_prep_programs->s_stuid->CurrentValue) ?>">
<?php } else { ?>
<input type="hidden" name="x<?php echo $RowIndex ?>_s_stuid" id="x<?php echo $RowIndex ?>_s_stuid" value="<?php echo ew_HtmlEncode($tbl_prep_programs->s_stuid->CurrentValue) ?>">
<?php } ?>
<?php } else { ?>
<?php } ?></td>
		</tr>
	</table>
<span class="edge">
<?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { ?>
<?php if ($tbl_prep_programs->CurrentAction == "edit") { ?>
<a href="" onClick="if (ew_ValidateForm(document.ftbl_prep_programslist)) document.ftbl_prep_programslist.submit();return false;">Update</a>&nbsp;<a href="tbl_prep_programslist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" id="a_list" value="update">
<?php } ?>
<?php } else { ?>
<?php if ($tbl_prep_programs->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<a href="<?php echo $tbl_prep_programs->InlineEditUrl() ?>"> Edit</a>&nbsp;
<?php } ?>
<?php } ?>
<?php } ?>
</span>	</td>
    <?php if ($ColCnt == $nRecPerRow) { ?>
</tr>
<?php } ?>
<?php if ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { ?>
<?php } ?>
<?php
	}
	$rs->MoveNext();
}
?>
<?php if ($ColCnt < $nRecPerRow) { ?>
<?php for ($i = 1; $i <= $nRecPerRow - $ColCnt; $i++) { ?>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
<?php } ?>
</tr>
<?php } ?>
</table>
<?php if ($tbl_prep_programs->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php } ?>
<?php if ($tbl_prep_programs->CurrentAction == "edit") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $RowIndex ?>">
<?php } ?>
</form>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($tbl_prep_programs->Export == "") { ?>
<?php } ?>
<?php if ($tbl_prep_programs->Export == "") { ?>
<?php } ?>
<?php if ($tbl_prep_programs->Export == "") { ?>
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
	global $tbl_prep_programs;
	$tbl_prep_programs->setKey("p_prepid", ""); // Clear inline edit key
	$tbl_prep_programs->CurrentAction = ""; // Clear action
	$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
}

// Switch to Inline Edit Mode
function InlineEditMode() {
	global $Security, $tbl_prep_programs;
	$bInlineEdit = TRUE;
	if (@$_GET["p_prepid"] <> "") {
		$tbl_prep_programs->p_prepid->setQueryStringValue($_GET["p_prepid"]);
	} else {
		$bInlineEdit = FALSE;
	}
	if ($bInlineEdit) {
		if (LoadRow()) {
			$tbl_prep_programs->setKey("p_prepid", $tbl_prep_programs->p_prepid->CurrentValue); // Set up inline edit key
			$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
		}
	}
}

// Peform update to inline edit record
function InlineUpdate() {
	global $objForm, $tbl_prep_programs;
	$objForm->Index = 1; 
	LoadFormValues(); // Get form values
	if (CheckInlineEditKey()) { // Check key
		$tbl_prep_programs->SendEmail = TRUE; // Send email on update success
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
		$tbl_prep_programs->EventCancelled = TRUE; // Cancel event
		$tbl_prep_programs->CurrentAction = "edit"; // Stay in edit mode
	}
}

// Check inline edit key
function CheckInlineEditKey() {
	global $tbl_prep_programs;

	//CheckInlineEditKey = True
	if (strval($tbl_prep_programs->getKey("p_prepid")) <> strval($tbl_prep_programs->p_prepid->CurrentValue)) {
		return FALSE;
	}
	return TRUE;
}

// Set up Sort parameters based on Sort Links clicked
function SetUpSortOrder() {
	global $tbl_prep_programs;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$tbl_prep_programs->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$tbl_prep_programs->CurrentOrderType = @$_GET["ordertype"];
		$tbl_prep_programs->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $tbl_prep_programs->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($tbl_prep_programs->SqlOrderBy() <> "") {
			$sOrderBy = $tbl_prep_programs->SqlOrderBy();
			$tbl_prep_programs->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $tbl_prep_programs;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset master/detail keys
		if (strtolower($sCmd) == "resetall") {
			$tbl_prep_programs->setMasterFilter(""); // Clear master filter
			$sDbMasterFilter = "";
			$tbl_prep_programs->setDetailFilter(""); // Clear detail filter
			$sDbDetailFilter = "";
			$tbl_prep_programs->s_stuid->setSessionValue("");
		}

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$tbl_prep_programs->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$tbl_prep_programs->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $tbl_prep_programs;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$tbl_prep_programs->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$tbl_prep_programs->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $tbl_prep_programs->getStartRecordNumber();
		}
	} else {
		$nStartRec = $tbl_prep_programs->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$tbl_prep_programs->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$tbl_prep_programs->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$tbl_prep_programs->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load form values
function LoadFormValues() {

	// Load from form
	global $objForm, $tbl_prep_programs;
	$tbl_prep_programs->p_prepid->setFormValue($objForm->GetValue("x_p_prepid"));
	$tbl_prep_programs->p_arithmetic->setFormValue($objForm->GetValue("x_p_arithmetic"));
	$tbl_prep_programs->p_algebra->setFormValue($objForm->GetValue("x_p_algebra"));
	$tbl_prep_programs->p_techniques->setFormValue($objForm->GetValue("x_p_techniques"));
	$tbl_prep_programs->p_geometry->setFormValue($objForm->GetValue("x_p_geometry"));
	$tbl_prep_programs->p_advanced_topics->setFormValue($objForm->GetValue("x_p_advanced_topics"));
	$tbl_prep_programs->p_sentence_completion->setFormValue($objForm->GetValue("x_p_sentence_completion"));
	$tbl_prep_programs->p_critical_reading->setFormValue($objForm->GetValue("x_p_critical_reading"));
	$tbl_prep_programs->p_error_id->setFormValue($objForm->GetValue("x_p_error_id"));
	$tbl_prep_programs->p_sentence_improvement->setFormValue($objForm->GetValue("x_p_sentence_improvement"));
	$tbl_prep_programs->p_paragraph_improvement->setFormValue($objForm->GetValue("x_p_paragraph_improvement"));
	$tbl_prep_programs->s_stuid->setFormValue($objForm->GetValue("x_s_stuid"));
}

// Restore form values
function RestoreFormValues() {
	global $tbl_prep_programs;
	$tbl_prep_programs->p_prepid->CurrentValue = $tbl_prep_programs->p_prepid->FormValue;
	$tbl_prep_programs->p_arithmetic->CurrentValue = $tbl_prep_programs->p_arithmetic->FormValue;
	$tbl_prep_programs->p_algebra->CurrentValue = $tbl_prep_programs->p_algebra->FormValue;
	$tbl_prep_programs->p_techniques->CurrentValue = $tbl_prep_programs->p_techniques->FormValue;
	$tbl_prep_programs->p_geometry->CurrentValue = $tbl_prep_programs->p_geometry->FormValue;
	$tbl_prep_programs->p_advanced_topics->CurrentValue = $tbl_prep_programs->p_advanced_topics->FormValue;
	$tbl_prep_programs->p_sentence_completion->CurrentValue = $tbl_prep_programs->p_sentence_completion->FormValue;
	$tbl_prep_programs->p_critical_reading->CurrentValue = $tbl_prep_programs->p_critical_reading->FormValue;
	$tbl_prep_programs->p_error_id->CurrentValue = $tbl_prep_programs->p_error_id->FormValue;
	$tbl_prep_programs->p_sentence_improvement->CurrentValue = $tbl_prep_programs->p_sentence_improvement->FormValue;
	$tbl_prep_programs->p_paragraph_improvement->CurrentValue = $tbl_prep_programs->p_paragraph_improvement->FormValue;
	$tbl_prep_programs->s_stuid->CurrentValue = $tbl_prep_programs->s_stuid->FormValue;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_prep_programs;

	// Call Recordset Selecting event
	$tbl_prep_programs->Recordset_Selecting($tbl_prep_programs->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_prep_programs->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_prep_programs->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_prep_programs;
	$sFilter = $tbl_prep_programs->SqlKeyFilter();
	if (!is_numeric($tbl_prep_programs->p_prepid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@p_prepid@", ew_AdjustSql($tbl_prep_programs->p_prepid->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_prep_programs->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add User ID filter for master table
	}

	// Call Row Selecting event
	$tbl_prep_programs->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_prep_programs->CurrentFilter = $sFilter;
	$sSql = $tbl_prep_programs->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_prep_programs->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_prep_programs;
	$tbl_prep_programs->p_prepid->setDbValue($rs->fields('p_prepid'));
	$tbl_prep_programs->p_arithmetic->setDbValue($rs->fields('p_arithmetic'));
	$tbl_prep_programs->p_algebra->setDbValue($rs->fields('p_algebra'));
	$tbl_prep_programs->p_techniques->setDbValue($rs->fields('p_techniques'));
	$tbl_prep_programs->p_geometry->setDbValue($rs->fields('p_geometry'));
	$tbl_prep_programs->p_advanced_topics->setDbValue($rs->fields('p_advanced_topics'));
	$tbl_prep_programs->p_sentence_completion->setDbValue($rs->fields('p_sentence_completion'));
	$tbl_prep_programs->p_critical_reading->setDbValue($rs->fields('p_critical_reading'));
	$tbl_prep_programs->p_error_id->setDbValue($rs->fields('p_error_id'));
	$tbl_prep_programs->p_sentence_improvement->setDbValue($rs->fields('p_sentence_improvement'));
	$tbl_prep_programs->p_paragraph_improvement->setDbValue($rs->fields('p_paragraph_improvement'));
	$tbl_prep_programs->s_stuid->setDbValue($rs->fields('s_stuid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_prep_programs;

	// Call Row Rendering event
	$tbl_prep_programs->Row_Rendering();

	// Common render codes for all row types
	// p_prepid

	$tbl_prep_programs->p_prepid->CellCssStyle = "";
	$tbl_prep_programs->p_prepid->CellCssClass = "";

	// p_arithmetic
	$tbl_prep_programs->p_arithmetic->CellCssStyle = "";
	$tbl_prep_programs->p_arithmetic->CellCssClass = "";

	// p_algebra
	$tbl_prep_programs->p_algebra->CellCssStyle = "";
	$tbl_prep_programs->p_algebra->CellCssClass = "";

	// p_techniques
	$tbl_prep_programs->p_techniques->CellCssStyle = "";
	$tbl_prep_programs->p_techniques->CellCssClass = "";

	// p_geometry
	$tbl_prep_programs->p_geometry->CellCssStyle = "";
	$tbl_prep_programs->p_geometry->CellCssClass = "";

	// p_advanced_topics
	$tbl_prep_programs->p_advanced_topics->CellCssStyle = "";
	$tbl_prep_programs->p_advanced_topics->CellCssClass = "";

	// p_sentence_completion
	$tbl_prep_programs->p_sentence_completion->CellCssStyle = "";
	$tbl_prep_programs->p_sentence_completion->CellCssClass = "";

	// p_critical_reading
	$tbl_prep_programs->p_critical_reading->CellCssStyle = "";
	$tbl_prep_programs->p_critical_reading->CellCssClass = "";

	// p_error_id
	$tbl_prep_programs->p_error_id->CellCssStyle = "";
	$tbl_prep_programs->p_error_id->CellCssClass = "";

	// p_sentence_improvement
	$tbl_prep_programs->p_sentence_improvement->CellCssStyle = "";
	$tbl_prep_programs->p_sentence_improvement->CellCssClass = "";

	// p_paragraph_improvement
	$tbl_prep_programs->p_paragraph_improvement->CellCssStyle = "";
	$tbl_prep_programs->p_paragraph_improvement->CellCssClass = "";

	// s_stuid
	$tbl_prep_programs->s_stuid->CellCssStyle = "";
	$tbl_prep_programs->s_stuid->CellCssClass = "";
	if ($tbl_prep_programs->RowType == EW_ROWTYPE_VIEW) { // View row

		// p_prepid
		$tbl_prep_programs->p_prepid->ViewValue = $tbl_prep_programs->p_prepid->CurrentValue;
		$tbl_prep_programs->p_prepid->CssStyle = "";
		$tbl_prep_programs->p_prepid->CssClass = "";
		$tbl_prep_programs->p_prepid->ViewCustomAttributes = "";

		// p_arithmetic
		$tbl_prep_programs->p_arithmetic->ViewValue = $tbl_prep_programs->p_arithmetic->CurrentValue;
		$tbl_prep_programs->p_arithmetic->CssStyle = "";
		$tbl_prep_programs->p_arithmetic->CssClass = "";
		$tbl_prep_programs->p_arithmetic->ViewCustomAttributes = "";

		// p_algebra
		$tbl_prep_programs->p_algebra->ViewValue = $tbl_prep_programs->p_algebra->CurrentValue;
		$tbl_prep_programs->p_algebra->CssStyle = "";
		$tbl_prep_programs->p_algebra->CssClass = "";
		$tbl_prep_programs->p_algebra->ViewCustomAttributes = "";

		// p_techniques
		$tbl_prep_programs->p_techniques->ViewValue = $tbl_prep_programs->p_techniques->CurrentValue;
		$tbl_prep_programs->p_techniques->CssStyle = "";
		$tbl_prep_programs->p_techniques->CssClass = "";
		$tbl_prep_programs->p_techniques->ViewCustomAttributes = "";

		// p_geometry
		$tbl_prep_programs->p_geometry->ViewValue = $tbl_prep_programs->p_geometry->CurrentValue;
		$tbl_prep_programs->p_geometry->CssStyle = "";
		$tbl_prep_programs->p_geometry->CssClass = "";
		$tbl_prep_programs->p_geometry->ViewCustomAttributes = "";

		// p_advanced_topics
		$tbl_prep_programs->p_advanced_topics->ViewValue = $tbl_prep_programs->p_advanced_topics->CurrentValue;
		$tbl_prep_programs->p_advanced_topics->CssStyle = "";
		$tbl_prep_programs->p_advanced_topics->CssClass = "";
		$tbl_prep_programs->p_advanced_topics->ViewCustomAttributes = "";

		// p_sentence_completion
		$tbl_prep_programs->p_sentence_completion->ViewValue = $tbl_prep_programs->p_sentence_completion->CurrentValue;
		$tbl_prep_programs->p_sentence_completion->CssStyle = "";
		$tbl_prep_programs->p_sentence_completion->CssClass = "";
		$tbl_prep_programs->p_sentence_completion->ViewCustomAttributes = "";

		// p_critical_reading
		$tbl_prep_programs->p_critical_reading->ViewValue = $tbl_prep_programs->p_critical_reading->CurrentValue;
		$tbl_prep_programs->p_critical_reading->CssStyle = "";
		$tbl_prep_programs->p_critical_reading->CssClass = "";
		$tbl_prep_programs->p_critical_reading->ViewCustomAttributes = "";

		// p_error_id
		$tbl_prep_programs->p_error_id->ViewValue = $tbl_prep_programs->p_error_id->CurrentValue;
		$tbl_prep_programs->p_error_id->CssStyle = "";
		$tbl_prep_programs->p_error_id->CssClass = "";
		$tbl_prep_programs->p_error_id->ViewCustomAttributes = "";

		// p_sentence_improvement
		$tbl_prep_programs->p_sentence_improvement->ViewValue = $tbl_prep_programs->p_sentence_improvement->CurrentValue;
		$tbl_prep_programs->p_sentence_improvement->CssStyle = "";
		$tbl_prep_programs->p_sentence_improvement->CssClass = "";
		$tbl_prep_programs->p_sentence_improvement->ViewCustomAttributes = "";

		// p_paragraph_improvement
		$tbl_prep_programs->p_paragraph_improvement->ViewValue = $tbl_prep_programs->p_paragraph_improvement->CurrentValue;
		$tbl_prep_programs->p_paragraph_improvement->CssStyle = "";
		$tbl_prep_programs->p_paragraph_improvement->CssClass = "";
		$tbl_prep_programs->p_paragraph_improvement->ViewCustomAttributes = "";

		// s_stuid
		$tbl_prep_programs->s_stuid->ViewValue = $tbl_prep_programs->s_stuid->CurrentValue;
		$tbl_prep_programs->s_stuid->CssStyle = "";
		$tbl_prep_programs->s_stuid->CssClass = "";
		$tbl_prep_programs->s_stuid->ViewCustomAttributes = "";

		// p_prepid
		$tbl_prep_programs->p_prepid->HrefValue = "";

		// p_arithmetic
		$tbl_prep_programs->p_arithmetic->HrefValue = "";

		// p_algebra
		$tbl_prep_programs->p_algebra->HrefValue = "";

		// p_techniques
		$tbl_prep_programs->p_techniques->HrefValue = "";

		// p_geometry
		$tbl_prep_programs->p_geometry->HrefValue = "";

		// p_advanced_topics
		$tbl_prep_programs->p_advanced_topics->HrefValue = "";

		// p_sentence_completion
		$tbl_prep_programs->p_sentence_completion->HrefValue = "";

		// p_critical_reading
		$tbl_prep_programs->p_critical_reading->HrefValue = "";

		// p_error_id
		$tbl_prep_programs->p_error_id->HrefValue = "";

		// p_sentence_improvement
		$tbl_prep_programs->p_sentence_improvement->HrefValue = "";

		// p_paragraph_improvement
		$tbl_prep_programs->p_paragraph_improvement->HrefValue = "";

		// s_stuid
		$tbl_prep_programs->s_stuid->HrefValue = "";
	} elseif ($tbl_prep_programs->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_prep_programs->RowType == EW_ROWTYPE_EDIT) { // Edit row

		// p_prepid
		$tbl_prep_programs->p_prepid->EditCustomAttributes = "";

		// p_arithmetic
		$tbl_prep_programs->p_arithmetic->EditCustomAttributes = "";
		$tbl_prep_programs->p_arithmetic->EditValue = ew_HtmlEncode($tbl_prep_programs->p_arithmetic->CurrentValue);

		// p_algebra
		$tbl_prep_programs->p_algebra->EditCustomAttributes = "";
		$tbl_prep_programs->p_algebra->EditValue = ew_HtmlEncode($tbl_prep_programs->p_algebra->CurrentValue);

		// p_techniques
		$tbl_prep_programs->p_techniques->EditCustomAttributes = "";
		$tbl_prep_programs->p_techniques->EditValue = ew_HtmlEncode($tbl_prep_programs->p_techniques->CurrentValue);

		// p_geometry
		$tbl_prep_programs->p_geometry->EditCustomAttributes = "";
		$tbl_prep_programs->p_geometry->EditValue = ew_HtmlEncode($tbl_prep_programs->p_geometry->CurrentValue);

		// p_advanced_topics
		$tbl_prep_programs->p_advanced_topics->EditCustomAttributes = "";
		$tbl_prep_programs->p_advanced_topics->EditValue = ew_HtmlEncode($tbl_prep_programs->p_advanced_topics->CurrentValue);

		// p_sentence_completion
		$tbl_prep_programs->p_sentence_completion->EditCustomAttributes = "";
		$tbl_prep_programs->p_sentence_completion->EditValue = ew_HtmlEncode($tbl_prep_programs->p_sentence_completion->CurrentValue);

		// p_critical_reading
		$tbl_prep_programs->p_critical_reading->EditCustomAttributes = "";
		$tbl_prep_programs->p_critical_reading->EditValue = ew_HtmlEncode($tbl_prep_programs->p_critical_reading->CurrentValue);

		// p_error_id
		$tbl_prep_programs->p_error_id->EditCustomAttributes = "";
		$tbl_prep_programs->p_error_id->EditValue = ew_HtmlEncode($tbl_prep_programs->p_error_id->CurrentValue);

		// p_sentence_improvement
		$tbl_prep_programs->p_sentence_improvement->EditCustomAttributes = "";
		$tbl_prep_programs->p_sentence_improvement->EditValue = ew_HtmlEncode($tbl_prep_programs->p_sentence_improvement->CurrentValue);

		// p_paragraph_improvement
		$tbl_prep_programs->p_paragraph_improvement->EditCustomAttributes = "";
		$tbl_prep_programs->p_paragraph_improvement->EditValue = ew_HtmlEncode($tbl_prep_programs->p_paragraph_improvement->CurrentValue);

		// s_stuid
		$tbl_prep_programs->s_stuid->EditCustomAttributes = "";
		if ($tbl_prep_programs->s_stuid->getSessionValue() <> "") {
			$tbl_prep_programs->s_stuid->CurrentValue = $tbl_prep_programs->s_stuid->getSessionValue();
		$tbl_prep_programs->s_stuid->ViewValue = $tbl_prep_programs->s_stuid->CurrentValue;
		$tbl_prep_programs->s_stuid->CssStyle = "";
		$tbl_prep_programs->s_stuid->CssClass = "";
		$tbl_prep_programs->s_stuid->ViewCustomAttributes = "";
		} else {
		}
	} elseif ($tbl_prep_programs->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_prep_programs->Row_Rendered();
}
?>
<?php

// Update record based on key values
function EditRow() {
	global $conn, $Security, $tbl_prep_programs;
	$sFilter = $tbl_prep_programs->SqlKeyFilter();
	if (!is_numeric($tbl_prep_programs->p_prepid->CurrentValue)) {
		return FALSE;
	}
	$sFilter = str_replace("@p_prepid@", ew_AdjustSql($tbl_prep_programs->p_prepid->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_prep_programs->AddDetailUserIDFilter($sFilter, "tbl_students", $Security->CurrentUserID()); // Add User ID filter for master table
		$tbl_prep_programs->CurrentFilter = $sFilter;
	}
	$tbl_prep_programs->CurrentFilter = $sFilter;
	$sSql = $tbl_prep_programs->SQL();
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

		// Field p_prepid
		// Field p_arithmetic

		$tbl_prep_programs->p_arithmetic->SetDbValueDef($tbl_prep_programs->p_arithmetic->CurrentValue, NULL);
		$rsnew['p_arithmetic'] =& $tbl_prep_programs->p_arithmetic->DbValue;

		// Field p_algebra
		$tbl_prep_programs->p_algebra->SetDbValueDef($tbl_prep_programs->p_algebra->CurrentValue, NULL);
		$rsnew['p_algebra'] =& $tbl_prep_programs->p_algebra->DbValue;

		// Field p_techniques
		$tbl_prep_programs->p_techniques->SetDbValueDef($tbl_prep_programs->p_techniques->CurrentValue, NULL);
		$rsnew['p_techniques'] =& $tbl_prep_programs->p_techniques->DbValue;

		// Field p_geometry
		$tbl_prep_programs->p_geometry->SetDbValueDef($tbl_prep_programs->p_geometry->CurrentValue, NULL);
		$rsnew['p_geometry'] =& $tbl_prep_programs->p_geometry->DbValue;

		// Field p_advanced_topics
		$tbl_prep_programs->p_advanced_topics->SetDbValueDef($tbl_prep_programs->p_advanced_topics->CurrentValue, NULL);
		$rsnew['p_advanced_topics'] =& $tbl_prep_programs->p_advanced_topics->DbValue;

		// Field p_sentence_completion
		$tbl_prep_programs->p_sentence_completion->SetDbValueDef($tbl_prep_programs->p_sentence_completion->CurrentValue, NULL);
		$rsnew['p_sentence_completion'] =& $tbl_prep_programs->p_sentence_completion->DbValue;

		// Field p_critical_reading
		$tbl_prep_programs->p_critical_reading->SetDbValueDef($tbl_prep_programs->p_critical_reading->CurrentValue, NULL);
		$rsnew['p_critical_reading'] =& $tbl_prep_programs->p_critical_reading->DbValue;

		// Field p_error_id
		$tbl_prep_programs->p_error_id->SetDbValueDef($tbl_prep_programs->p_error_id->CurrentValue, NULL);
		$rsnew['p_error_id'] =& $tbl_prep_programs->p_error_id->DbValue;

		// Field p_sentence_improvement
		$tbl_prep_programs->p_sentence_improvement->SetDbValueDef($tbl_prep_programs->p_sentence_improvement->CurrentValue, NULL);
		$rsnew['p_sentence_improvement'] =& $tbl_prep_programs->p_sentence_improvement->DbValue;

		// Field p_paragraph_improvement
		$tbl_prep_programs->p_paragraph_improvement->SetDbValueDef($tbl_prep_programs->p_paragraph_improvement->CurrentValue, NULL);
		$rsnew['p_paragraph_improvement'] =& $tbl_prep_programs->p_paragraph_improvement->DbValue;

		// Field s_stuid
		$tbl_prep_programs->s_stuid->SetDbValueDef($tbl_prep_programs->s_stuid->CurrentValue, 0);
		$rsnew['s_stuid'] =& $tbl_prep_programs->s_stuid->DbValue;

		// Call Row Updating event
		$bUpdateRow = $tbl_prep_programs->Row_Updating($rsold, $rsnew);
		if ($bUpdateRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$EditRow = $conn->Execute($tbl_prep_programs->UpdateSQL($rsnew));
			$conn->raiseErrorFn = '';
		} else {
			if ($tbl_prep_programs->CancelMessage <> "") {
				$_SESSION[EW_SESSION_MESSAGE] = $tbl_prep_programs->CancelMessage;
				$tbl_prep_programs->CancelMessage = "";
			} else {
				$_SESSION[EW_SESSION_MESSAGE] = "Update cancelled";
			}
			$EditRow = FALSE;
		}
	}

	// Call Row Updated event
	if ($EditRow) {
		$tbl_prep_programs->Row_Updated($rsold, $rsnew);
	}
	$rs->Close();
	return $EditRow;
}
?>
<?php

// Set up Master Detail based on querystring parameter
function SetUpMasterDetail() {
	global $nStartRec, $sDbMasterFilter, $sDbDetailFilter, $tbl_prep_programs;
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
			$sDbMasterFilter = $tbl_prep_programs->SqlMasterFilter_tbl_students();
			$sDbDetailFilter = $tbl_prep_programs->SqlDetailFilter_tbl_students();
			if (@$_GET["s_studentid"] <> "") {
				$GLOBALS["tbl_students"]->s_studentid->setQueryStringValue($_GET["s_studentid"]);
				$tbl_prep_programs->s_stuid->setQueryStringValue($GLOBALS["tbl_students"]->s_studentid->QueryStringValue);
				$tbl_prep_programs->s_stuid->setSessionValue($tbl_prep_programs->s_stuid->QueryStringValue);
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
		$tbl_prep_programs->setCurrentMasterTable($sMasterTblVar);

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$tbl_prep_programs->setStartRecordNumber($nStartRec);
		$tbl_prep_programs->setMasterFilter($sDbMasterFilter); // Set up master filter
		$tbl_prep_programs->setDetailFilter($sDbDetailFilter); // Set up detail filter

		// Clear previous master session values
		if ($sMasterTblVar <> "tbl_students") {
			if ($tbl_prep_programs->s_stuid->QueryStringValue == "") $tbl_prep_programs->s_stuid->setSessionValue("");
		}
	} else {
		$sDbMasterFilter = $tbl_prep_programs->getMasterFilter(); //  Restore master filter
		$sDbDetailFilter = $tbl_prep_programs->getDetailFilter(); // Restore detail filter
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