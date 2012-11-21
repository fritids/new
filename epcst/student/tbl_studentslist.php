<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
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
$tbl_students->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_students->Export; // Get export parameter, used in header
$sExportFile = $tbl_students->TableVar; // Get export file, used in header
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

// Check QueryString parameters
if (@$_GET["a"] <> "") {
	$tbl_students->CurrentAction = $_GET["a"];

	// Clear inline mode
	if ($tbl_students->CurrentAction == "cancel") {
		ClearInlineMode();
	}

	// Switch to inline edit mode
	if ($tbl_students->CurrentAction == "edit") {
		InlineEditMode();
	}
} else {

	// Create form object
	$objForm = new cFormObj;
	if (@$_POST["a_list"] <> "") {
		$tbl_students->CurrentAction = $_POST["a_list"]; // Get action

		// Inline Update
		if ($tbl_students->CurrentAction == "update" && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit") {
			InlineUpdate();
		}
	}
}

// Build filter
$sFilter = "";
if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
	$sFilter = $tbl_students->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
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
$tbl_students->setSessionWhere($sFilter);
$tbl_students->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$tbl_students->setReturnUrl("tbl_studentslist.php");
?>
<?php include "header.php" ?>
<?php if ($tbl_students->Export == "") { ?>
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
		elm = fobj.elements["x" + infix + "_s_first_name"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - First Name"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_s_last_name"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Last Name"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_s_address"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Address"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_s_city"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - City"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_s_country"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Country"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_s_parent_name"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Parent Name"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_s_usrname"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Username"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_s_pwd"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Password"))
				return false;
		}
	}
	return true;
}

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
<?php if ($tbl_students->Export == "") { ?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $tbl_students->Export <> "");
$bSelectLimit = ($tbl_students->Export == "" && $tbl_students->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $tbl_students->SelectRecordCount() : $rs->RecordCount();
$nStartRec = 1;
if ($nDisplayRecs <= 0) $nDisplayRecs = $nTotalRecs; // Display all records
if (!$bExportAll) SetUpStartRec(); // Set up start record position
if ($bSelectLimit) $rs = LoadRecordset($nStartRec-1, $nDisplayRecs);
?>
<p><span class="edge" style="white-space: nowrap;">Student's Profile </span></p>
<?php if ($tbl_students->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form name="ftbl_studentslist" id="ftbl_studentslist" action="tbl_studentslist.php" method="post">
<?php if ($tbl_students->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?>
<table border="0" cellspacing="5" cellpadding="5">
<?php
if (defined("EW_EXPORT_ALL") && $tbl_students->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$tbl_students->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
$nEditRowCnt = 0;
if ($tbl_students->CurrentAction == "edit") $RowIndex = 1;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;
		$ColCnt++;
		if ($ColCnt > $nRecPerRow) $ColCnt = 1;

	// Init row class and style
	$tbl_students->CssClass = "ewTableRow";
	$tbl_students->CssStyle = "";

	// Init row event
	$tbl_students->RowClientEvents = "";
	LoadRowValues($rs); // Load row values
	$tbl_students->RowType = EW_ROWTYPE_VIEW; // Render view
	if ($tbl_students->CurrentAction == "edit") {
		if (CheckInlineEditKey() && $nEditRowCnt == 0) { // Inline edit
			$tbl_students->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
	}
		if ($tbl_students->RowType == EW_ROWTYPE_EDIT && $tbl_students->EventCancelled) { // Update failed
			if ($tbl_students->CurrentAction == "edit") {
				RestoreFormValues(); // Restore form values
			}
		}
		if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit row
			$nEditRowCnt++;
			$tbl_students->CssClass = "ewTableEditRow";
			$tbl_students->RowClientEvents = "";
		}
	RenderRow();
?>
<?php if ($ColCnt == 1) { ?>
<tr>
<?php } ?>
	<td valign="top"<?php echo $tbl_students->DisplayAttributes() ?>>
	<table class="ewTable">
		<tr class="ewTableRow">
		  <td class="ewTableHeader" <?php echo $tbl_students->s_studentid->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<input type="hidden" name="x<?php echo $RowIndex ?>_s_studentid" id="x<?php echo $RowIndex ?>_s_studentid" value="<?php echo ew_HtmlEncode($tbl_students->s_studentid->CurrentValue) ?>">
<?php } else { ?>
<input type="hidden" name="x<?php echo $RowIndex ?>_s_studentid" id="x<?php echo $RowIndex ?>_s_studentid" value="<?php echo ew_HtmlEncode($tbl_students->s_studentid->CurrentValue) ?>">
<?php } ?>
<?php } else { ?>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
First Name
<?php } else { ?>
	First Name<?php if ($tbl_students->s_first_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_first_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_first_name->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_first_name" id="x<?php echo $RowIndex ?>_s_first_name" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_first_name->EditValue ?>"<?php echo $tbl_students->s_first_name->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_first_name->ViewAttributes() ?>><?php echo $tbl_students->s_first_name->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Middle Name
<?php } else { ?>
	Middle Name<?php if ($tbl_students->s_middle_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_middle_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_middle_name->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_middle_name" id="x<?php echo $RowIndex ?>_s_middle_name" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_middle_name->EditValue ?>"<?php echo $tbl_students->s_middle_name->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_middle_name->ViewAttributes() ?>><?php echo $tbl_students->s_middle_name->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Last Name
<?php } else { ?>
	Last Name<?php if ($tbl_students->s_last_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_last_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_last_name->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_last_name" id="x<?php echo $RowIndex ?>_s_last_name" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_last_name->EditValue ?>"<?php echo $tbl_students->s_last_name->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_last_name->ViewAttributes() ?>><?php echo $tbl_students->s_last_name->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Address
<?php } else { ?>
	Address<?php if ($tbl_students->s_address->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_address->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_address->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_address" id="x<?php echo $RowIndex ?>_s_address" title="" size="30" maxlength="125" value="<?php echo $tbl_students->s_address->EditValue ?>"<?php echo $tbl_students->s_address->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_address->ViewAttributes() ?>><?php echo $tbl_students->s_address->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
City
<?php } else { ?>
	City<?php if ($tbl_students->s_city->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_city->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_city->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_city" id="x<?php echo $RowIndex ?>_s_city" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_city->EditValue ?>"<?php echo $tbl_students->s_city->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_city->ViewAttributes() ?>><?php echo $tbl_students->s_city->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Postal Code
<?php } else { ?>
	Postal Code<?php if ($tbl_students->s_postal_code->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_postal_code->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_postal_code->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_postal_code" id="x<?php echo $RowIndex ?>_s_postal_code" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_postal_code->EditValue ?>"<?php echo $tbl_students->s_postal_code->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_postal_code->ViewAttributes() ?>><?php echo $tbl_students->s_postal_code->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
State
<?php } else { ?>
	State<?php if ($tbl_students->s_state->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_state->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_state->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_state" id="x<?php echo $RowIndex ?>_s_state" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_state->EditValue ?>"<?php echo $tbl_students->s_state->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_state->ViewAttributes() ?>><?php echo $tbl_students->s_state->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Country
<?php } else { ?>
	Country<?php if ($tbl_students->s_country->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_country->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_country->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_country" id="x<?php echo $RowIndex ?>_s_country" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_country->EditValue ?>"<?php echo $tbl_students->s_country->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_country->ViewAttributes() ?>><?php echo $tbl_students->s_country->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Home Phone
<?php } else { ?>
	Home Phone<?php if ($tbl_students->s_home_phone->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_home_phone->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_home_phone->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_home_phone" id="x<?php echo $RowIndex ?>_s_home_phone" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_home_phone->EditValue ?>"<?php echo $tbl_students->s_home_phone->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_home_phone->ViewAttributes() ?>><?php echo $tbl_students->s_home_phone->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Mobile
<?php } else { ?>
	Mobile<?php if ($tbl_students->s_student_mobile->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_student_mobile->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_student_mobile->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_student_mobile" id="x<?php echo $RowIndex ?>_s_student_mobile" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_student_mobile->EditValue ?>"<?php echo $tbl_students->s_student_mobile->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_student_mobile->ViewAttributes() ?>><?php echo $tbl_students->s_student_mobile->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
E-mail
<?php } else { ?>
	E-mail<?php if ($tbl_students->s_student_email->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_student_email->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_student_email->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_student_email" id="x<?php echo $RowIndex ?>_s_student_email" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_student_email->EditValue ?>"<?php echo $tbl_students->s_student_email->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_student_email->ViewAttributes() ?>><?php echo $tbl_students->s_student_email->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Parent Name
<?php } else { ?>
	Parent Name<?php if ($tbl_students->s_parent_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_parent_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_parent_name->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_parent_name" id="x<?php echo $RowIndex ?>_s_parent_name" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_parent_name->EditValue ?>"<?php echo $tbl_students->s_parent_name->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_parent_name->ViewAttributes() ?>><?php echo $tbl_students->s_parent_name->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Parent Mobile
<?php } else { ?>
	Parent Mobile<?php if ($tbl_students->s_parent_mobile->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_parent_mobile->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_parent_mobile->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_parent_mobile" id="x<?php echo $RowIndex ?>_s_parent_mobile" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_parent_mobile->EditValue ?>"<?php echo $tbl_students->s_parent_mobile->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_parent_mobile->ViewAttributes() ?>><?php echo $tbl_students->s_parent_mobile->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Parent E-mail
<?php } else { ?>
	Parent E-mail<?php if ($tbl_students->s_parent_email->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_parent_email->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_parent_email->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_parent_email" id="x<?php echo $RowIndex ?>_s_parent_email" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_parent_email->EditValue ?>"<?php echo $tbl_students->s_parent_email->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_parent_email->ViewAttributes() ?>><?php echo $tbl_students->s_parent_email->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
School
<?php } else { ?>
	School<?php if ($tbl_students->s_school->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_school->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_school->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_school" id="x<?php echo $RowIndex ?>_s_school" title="" size="30" maxlength="125" value="<?php echo $tbl_students->s_school->EditValue ?>"<?php echo $tbl_students->s_school->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_school->ViewAttributes() ?>><?php echo $tbl_students->s_school->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Graduation Year
<?php } else { ?>
	Graduation Year<?php if ($tbl_students->s_graduation_year->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_graduation_year->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td <?php echo $tbl_students->s_graduation_year->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_s_graduation_year" id="x<?php echo $RowIndex ?>_s_graduation_year" title="" size="30" maxlength="45" value="<?php echo $tbl_students->s_graduation_year->EditValue ?>"<?php echo $tbl_students->s_graduation_year->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_graduation_year->ViewAttributes() ?>><?php echo $tbl_students->s_graduation_year->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Username
<?php } else { ?>
	Username<?php if ($tbl_students->s_usrname->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_usrname->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_usrname->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<div<?php echo $tbl_students->s_usrname->ViewAttributes() ?>><?php echo $tbl_students->s_usrname->EditValue ?></div>
<input type="hidden" name="x<?php echo $RowIndex ?>_s_usrname" id="x<?php echo $RowIndex ?>_s_usrname" value="<?php echo ew_HtmlEncode($tbl_students->s_usrname->CurrentValue) ?>">
<?php } else { ?>
<div<?php echo $tbl_students->s_usrname->ViewAttributes() ?>><?php echo $tbl_students->s_usrname->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td class="ewTableHeader">
<?php if ($tbl_students->Export <> "") { ?>
Password
<?php } else { ?>
	Password<?php if ($tbl_students->s_pwd->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_pwd->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>
			</td>
			<td<?php echo $tbl_students->s_pwd->CellAttributes() ?>>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="password" name="x<?php echo $RowIndex ?>_s_pwd" id="x<?php echo $RowIndex ?>_s_pwd" title="" value="<?php echo $tbl_students->s_pwd->EditValue ?>" size="30" maxlength="45"<?php echo $tbl_students->s_pwd->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_students->s_pwd->ViewAttributes() ?>><?php echo $tbl_students->s_pwd->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
	</table>
	</td>
<?php if ($ColCnt == $nRecPerRow) { ?>
</tr>
<?php } ?>
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { ?>
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
<?php if ($tbl_students->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>

<span class="edge">
<?php if ($tbl_students->RowType == EW_ROWTYPE_EDIT) { ?>
<?php if ($tbl_students->CurrentAction == "edit") { ?>
<a href="" onClick="if (ew_ValidateForm(document.ftbl_studentslist)) document.ftbl_studentslist.submit();return false;">Update</a>&nbsp;<a href="tbl_studentslist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" id="a_list" value="update">
<?php } ?>
<?php } else { ?>
<?php if ($tbl_students->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if (ShowOptionLink()) { ?>
<a href="<?php echo $tbl_students->InlineEditUrl() ?>"> Edit</a>&nbsp;
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
</span>


<?php } ?>
<?php } ?>
<?php if ($tbl_students->CurrentAction == "edit") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $RowIndex ?>">
<?php } ?>
</form>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($tbl_students->Export == "") { ?>
<?php } ?>
<?php if ($tbl_students->Export == "") { ?>
<?php } ?>
<?php if ($tbl_students->Export == "") { ?>
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
	global $tbl_students;
	$tbl_students->setKey("s_studentid", ""); // Clear inline edit key
	$tbl_students->setKey("s_usrname", ""); // Clear inline edit key
	$tbl_students->CurrentAction = ""; // Clear action
	$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
}

// Switch to Inline Edit Mode
function InlineEditMode() {
	global $Security, $tbl_students;
	$bInlineEdit = TRUE;
	if (@$_GET["s_studentid"] <> "") {
		$tbl_students->s_studentid->setQueryStringValue($_GET["s_studentid"]);
	} else {
		$bInlineEdit = FALSE;
	}
	if (@$_GET["s_usrname"] <> "") {
		$tbl_students->s_usrname->setQueryStringValue($_GET["s_usrname"]);
	} else {
		$bInlineEdit = FALSE;
	}
	if ($bInlineEdit) {
		if (LoadRow()) {
			$tbl_students->setKey("s_studentid", $tbl_students->s_studentid->CurrentValue); // Set up inline edit key
			$tbl_students->setKey("s_usrname", $tbl_students->s_usrname->CurrentValue); // Set up inline edit key
			$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
		}
	}
}

// Peform update to inline edit record
function InlineUpdate() {
	global $objForm, $tbl_students;
	$objForm->Index = 1; 
	LoadFormValues(); // Get form values
	if (CheckInlineEditKey()) { // Check key
		$tbl_students->SendEmail = TRUE; // Send email on update success
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
		$tbl_students->EventCancelled = TRUE; // Cancel event
		$tbl_students->CurrentAction = "edit"; // Stay in edit mode
	}
}

// Check inline edit key
function CheckInlineEditKey() {
	global $tbl_students;

	//CheckInlineEditKey = True
	if (strval($tbl_students->getKey("s_studentid")) <> strval($tbl_students->s_studentid->CurrentValue)) {
		return FALSE;
	}
	if (strval($tbl_students->getKey("s_usrname")) <> strval($tbl_students->s_usrname->CurrentValue)) {
		return FALSE;
	}
	return TRUE;
}

// Set up Sort parameters based on Sort Links clicked
function SetUpSortOrder() {
	global $tbl_students;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$tbl_students->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$tbl_students->CurrentOrderType = @$_GET["ordertype"];
		$tbl_students->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $tbl_students->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($tbl_students->SqlOrderBy() <> "") {
			$sOrderBy = $tbl_students->SqlOrderBy();
			$tbl_students->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $tbl_students;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$tbl_students->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$tbl_students->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $tbl_students;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$tbl_students->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$tbl_students->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $tbl_students->getStartRecordNumber();
		}
	} else {
		$nStartRec = $tbl_students->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$tbl_students->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$tbl_students->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$tbl_students->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load form values
function LoadFormValues() {

	// Load from form
	global $objForm, $tbl_students;
	$tbl_students->s_studentid->setFormValue($objForm->GetValue("x_s_studentid"));
	$tbl_students->s_first_name->setFormValue($objForm->GetValue("x_s_first_name"));
	$tbl_students->s_middle_name->setFormValue($objForm->GetValue("x_s_middle_name"));
	$tbl_students->s_last_name->setFormValue($objForm->GetValue("x_s_last_name"));
	$tbl_students->s_address->setFormValue($objForm->GetValue("x_s_address"));
	$tbl_students->s_city->setFormValue($objForm->GetValue("x_s_city"));
	$tbl_students->s_postal_code->setFormValue($objForm->GetValue("x_s_postal_code"));
	$tbl_students->s_state->setFormValue($objForm->GetValue("x_s_state"));
	$tbl_students->s_country->setFormValue($objForm->GetValue("x_s_country"));
	$tbl_students->s_home_phone->setFormValue($objForm->GetValue("x_s_home_phone"));
	$tbl_students->s_student_mobile->setFormValue($objForm->GetValue("x_s_student_mobile"));
	$tbl_students->s_student_email->setFormValue($objForm->GetValue("x_s_student_email"));
	$tbl_students->s_parent_name->setFormValue($objForm->GetValue("x_s_parent_name"));
	$tbl_students->s_parent_mobile->setFormValue($objForm->GetValue("x_s_parent_mobile"));
	$tbl_students->s_parent_email->setFormValue($objForm->GetValue("x_s_parent_email"));
	$tbl_students->s_school->setFormValue($objForm->GetValue("x_s_school"));
	$tbl_students->s_graduation_year->setFormValue($objForm->GetValue("x_s_graduation_year"));
	$tbl_students->s_usrname->setFormValue($objForm->GetValue("x_s_usrname"));
	$tbl_students->s_pwd->setFormValue($objForm->GetValue("x_s_pwd"));
}

// Restore form values
function RestoreFormValues() {
	global $tbl_students;
	$tbl_students->s_studentid->CurrentValue = $tbl_students->s_studentid->FormValue;
	$tbl_students->s_first_name->CurrentValue = $tbl_students->s_first_name->FormValue;
	$tbl_students->s_middle_name->CurrentValue = $tbl_students->s_middle_name->FormValue;
	$tbl_students->s_last_name->CurrentValue = $tbl_students->s_last_name->FormValue;
	$tbl_students->s_address->CurrentValue = $tbl_students->s_address->FormValue;
	$tbl_students->s_city->CurrentValue = $tbl_students->s_city->FormValue;
	$tbl_students->s_postal_code->CurrentValue = $tbl_students->s_postal_code->FormValue;
	$tbl_students->s_state->CurrentValue = $tbl_students->s_state->FormValue;
	$tbl_students->s_country->CurrentValue = $tbl_students->s_country->FormValue;
	$tbl_students->s_home_phone->CurrentValue = $tbl_students->s_home_phone->FormValue;
	$tbl_students->s_student_mobile->CurrentValue = $tbl_students->s_student_mobile->FormValue;
	$tbl_students->s_student_email->CurrentValue = $tbl_students->s_student_email->FormValue;
	$tbl_students->s_parent_name->CurrentValue = $tbl_students->s_parent_name->FormValue;
	$tbl_students->s_parent_mobile->CurrentValue = $tbl_students->s_parent_mobile->FormValue;
	$tbl_students->s_parent_email->CurrentValue = $tbl_students->s_parent_email->FormValue;
	$tbl_students->s_school->CurrentValue = $tbl_students->s_school->FormValue;
	$tbl_students->s_graduation_year->CurrentValue = $tbl_students->s_graduation_year->FormValue;
	$tbl_students->s_usrname->CurrentValue = $tbl_students->s_usrname->FormValue;
	$tbl_students->s_pwd->CurrentValue = $tbl_students->s_pwd->FormValue;
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
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_students->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
	}

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
	$tbl_students->s_middle_name->setDbValue($rs->fields('s_middle_name'));
	$tbl_students->s_last_name->setDbValue($rs->fields('s_last_name'));
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
	$tbl_students->g_grpid->setDbValue($rs->fields('g_grpid'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_students;

	// Call Row Rendering event
	$tbl_students->Row_Rendering();

	// Common render codes for all row types
	// s_studentid

	$tbl_students->s_studentid->CellCssStyle = "";
	$tbl_students->s_studentid->CellCssClass = "";

	// s_first_name
	$tbl_students->s_first_name->CellCssStyle = "";
	$tbl_students->s_first_name->CellCssClass = "";

	// s_middle_name
	$tbl_students->s_middle_name->CellCssStyle = "";
	$tbl_students->s_middle_name->CellCssClass = "";

	// s_last_name
	$tbl_students->s_last_name->CellCssStyle = "";
	$tbl_students->s_last_name->CellCssClass = "";

	// s_address
	$tbl_students->s_address->CellCssStyle = "";
	$tbl_students->s_address->CellCssClass = "";

	// s_city
	$tbl_students->s_city->CellCssStyle = "";
	$tbl_students->s_city->CellCssClass = "";

	// s_postal_code
	$tbl_students->s_postal_code->CellCssStyle = "";
	$tbl_students->s_postal_code->CellCssClass = "";

	// s_state
	$tbl_students->s_state->CellCssStyle = "";
	$tbl_students->s_state->CellCssClass = "";

	// s_country
	$tbl_students->s_country->CellCssStyle = "";
	$tbl_students->s_country->CellCssClass = "";

	// s_home_phone
	$tbl_students->s_home_phone->CellCssStyle = "";
	$tbl_students->s_home_phone->CellCssClass = "";

	// s_student_mobile
	$tbl_students->s_student_mobile->CellCssStyle = "";
	$tbl_students->s_student_mobile->CellCssClass = "";

	// s_student_email
	$tbl_students->s_student_email->CellCssStyle = "";
	$tbl_students->s_student_email->CellCssClass = "";

	// s_parent_name
	$tbl_students->s_parent_name->CellCssStyle = "";
	$tbl_students->s_parent_name->CellCssClass = "";

	// s_parent_mobile
	$tbl_students->s_parent_mobile->CellCssStyle = "";
	$tbl_students->s_parent_mobile->CellCssClass = "";

	// s_parent_email
	$tbl_students->s_parent_email->CellCssStyle = "";
	$tbl_students->s_parent_email->CellCssClass = "";

	// s_school
	$tbl_students->s_school->CellCssStyle = "";
	$tbl_students->s_school->CellCssClass = "";

	// s_graduation_year
	$tbl_students->s_graduation_year->CellCssStyle = "";
	$tbl_students->s_graduation_year->CellCssClass = "";

	// s_usrname
	$tbl_students->s_usrname->CellCssStyle = "";
	$tbl_students->s_usrname->CellCssClass = "";

	// s_pwd
	$tbl_students->s_pwd->CellCssStyle = "";
	$tbl_students->s_pwd->CellCssClass = "";
	if ($tbl_students->RowType == EW_ROWTYPE_VIEW) { // View row

		// s_studentid
		$tbl_students->s_studentid->ViewValue = $tbl_students->s_studentid->CurrentValue;
		$tbl_students->s_studentid->CssStyle = "";
		$tbl_students->s_studentid->CssClass = "";
		$tbl_students->s_studentid->ViewCustomAttributes = "";

		// s_first_name
		$tbl_students->s_first_name->ViewValue = $tbl_students->s_first_name->CurrentValue;
		$tbl_students->s_first_name->CssStyle = "";
		$tbl_students->s_first_name->CssClass = "";
		$tbl_students->s_first_name->ViewCustomAttributes = "";

		// s_middle_name
		$tbl_students->s_middle_name->ViewValue = $tbl_students->s_middle_name->CurrentValue;
		$tbl_students->s_middle_name->CssStyle = "";
		$tbl_students->s_middle_name->CssClass = "";
		$tbl_students->s_middle_name->ViewCustomAttributes = "";

		// s_last_name
		$tbl_students->s_last_name->ViewValue = $tbl_students->s_last_name->CurrentValue;
		$tbl_students->s_last_name->CssStyle = "";
		$tbl_students->s_last_name->CssClass = "";
		$tbl_students->s_last_name->ViewCustomAttributes = "";

		// s_address
		$tbl_students->s_address->ViewValue = $tbl_students->s_address->CurrentValue;
		$tbl_students->s_address->CssStyle = "";
		$tbl_students->s_address->CssClass = "";
		$tbl_students->s_address->ViewCustomAttributes = "";

		// s_city
		$tbl_students->s_city->ViewValue = $tbl_students->s_city->CurrentValue;
		$tbl_students->s_city->CssStyle = "";
		$tbl_students->s_city->CssClass = "";
		$tbl_students->s_city->ViewCustomAttributes = "";

		// s_postal_code
		$tbl_students->s_postal_code->ViewValue = $tbl_students->s_postal_code->CurrentValue;
		$tbl_students->s_postal_code->CssStyle = "";
		$tbl_students->s_postal_code->CssClass = "";
		$tbl_students->s_postal_code->ViewCustomAttributes = "";

		// s_state
		$tbl_students->s_state->ViewValue = $tbl_students->s_state->CurrentValue;
		$tbl_students->s_state->CssStyle = "";
		$tbl_students->s_state->CssClass = "";
		$tbl_students->s_state->ViewCustomAttributes = "";

		// s_country
		$tbl_students->s_country->ViewValue = $tbl_students->s_country->CurrentValue;
		$tbl_students->s_country->CssStyle = "";
		$tbl_students->s_country->CssClass = "";
		$tbl_students->s_country->ViewCustomAttributes = "";

		// s_home_phone
		$tbl_students->s_home_phone->ViewValue = $tbl_students->s_home_phone->CurrentValue;
		$tbl_students->s_home_phone->CssStyle = "";
		$tbl_students->s_home_phone->CssClass = "";
		$tbl_students->s_home_phone->ViewCustomAttributes = "";

		// s_student_mobile
		$tbl_students->s_student_mobile->ViewValue = $tbl_students->s_student_mobile->CurrentValue;
		$tbl_students->s_student_mobile->CssStyle = "";
		$tbl_students->s_student_mobile->CssClass = "";
		$tbl_students->s_student_mobile->ViewCustomAttributes = "";

		// s_student_email
		$tbl_students->s_student_email->ViewValue = $tbl_students->s_student_email->CurrentValue;
		$tbl_students->s_student_email->CssStyle = "";
		$tbl_students->s_student_email->CssClass = "";
		$tbl_students->s_student_email->ViewCustomAttributes = "";

		// s_parent_name
		$tbl_students->s_parent_name->ViewValue = $tbl_students->s_parent_name->CurrentValue;
		$tbl_students->s_parent_name->CssStyle = "";
		$tbl_students->s_parent_name->CssClass = "";
		$tbl_students->s_parent_name->ViewCustomAttributes = "";

		// s_parent_mobile
		$tbl_students->s_parent_mobile->ViewValue = $tbl_students->s_parent_mobile->CurrentValue;
		$tbl_students->s_parent_mobile->CssStyle = "";
		$tbl_students->s_parent_mobile->CssClass = "";
		$tbl_students->s_parent_mobile->ViewCustomAttributes = "";

		// s_parent_email
		$tbl_students->s_parent_email->ViewValue = $tbl_students->s_parent_email->CurrentValue;
		$tbl_students->s_parent_email->CssStyle = "";
		$tbl_students->s_parent_email->CssClass = "";
		$tbl_students->s_parent_email->ViewCustomAttributes = "";

		// s_school
		$tbl_students->s_school->ViewValue = $tbl_students->s_school->CurrentValue;
		$tbl_students->s_school->CssStyle = "";
		$tbl_students->s_school->CssClass = "";
		$tbl_students->s_school->ViewCustomAttributes = "";

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

		// s_pwd
		$tbl_students->s_pwd->ViewValue = "********";
		$tbl_students->s_pwd->CssStyle = "";
		$tbl_students->s_pwd->CssClass = "";
		$tbl_students->s_pwd->ViewCustomAttributes = "";

		// s_studentid
		$tbl_students->s_studentid->HrefValue = "";

		// s_first_name
		$tbl_students->s_first_name->HrefValue = "";

		// s_middle_name
		$tbl_students->s_middle_name->HrefValue = "";

		// s_last_name
		$tbl_students->s_last_name->HrefValue = "";

		// s_address
		$tbl_students->s_address->HrefValue = "";

		// s_city
		$tbl_students->s_city->HrefValue = "";

		// s_postal_code
		$tbl_students->s_postal_code->HrefValue = "";

		// s_state
		$tbl_students->s_state->HrefValue = "";

		// s_country
		$tbl_students->s_country->HrefValue = "";

		// s_home_phone
		$tbl_students->s_home_phone->HrefValue = "";

		// s_student_mobile
		$tbl_students->s_student_mobile->HrefValue = "";

		// s_student_email
		$tbl_students->s_student_email->HrefValue = "";

		// s_parent_name
		$tbl_students->s_parent_name->HrefValue = "";

		// s_parent_mobile
		$tbl_students->s_parent_mobile->HrefValue = "";

		// s_parent_email
		$tbl_students->s_parent_email->HrefValue = "";

		// s_school
		$tbl_students->s_school->HrefValue = "";

		// s_graduation_year
		$tbl_students->s_graduation_year->HrefValue = "";

		// s_usrname
		$tbl_students->s_usrname->HrefValue = "";

		// s_pwd
		$tbl_students->s_pwd->HrefValue = "";
	} elseif ($tbl_students->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit row

		// s_studentid
		$tbl_students->s_studentid->EditCustomAttributes = "";
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			$tbl_students->s_studentid->CurrentValue = $Security->CurrentUserID();
		$tbl_students->s_studentid->EditValue = $tbl_students->s_studentid->CurrentValue;
		$tbl_students->s_studentid->CssStyle = "";
		$tbl_students->s_studentid->CssClass = "";
		$tbl_students->s_studentid->ViewCustomAttributes = "";
		} else {
		}

		// s_first_name
		$tbl_students->s_first_name->EditCustomAttributes = "";
		$tbl_students->s_first_name->EditValue = ew_HtmlEncode($tbl_students->s_first_name->CurrentValue);

		// s_middle_name
		$tbl_students->s_middle_name->EditCustomAttributes = "";
		$tbl_students->s_middle_name->EditValue = ew_HtmlEncode($tbl_students->s_middle_name->CurrentValue);

		// s_last_name
		$tbl_students->s_last_name->EditCustomAttributes = "";
		$tbl_students->s_last_name->EditValue = ew_HtmlEncode($tbl_students->s_last_name->CurrentValue);

		// s_address
		$tbl_students->s_address->EditCustomAttributes = "";
		$tbl_students->s_address->EditValue = ew_HtmlEncode($tbl_students->s_address->CurrentValue);

		// s_city
		$tbl_students->s_city->EditCustomAttributes = "";
		$tbl_students->s_city->EditValue = ew_HtmlEncode($tbl_students->s_city->CurrentValue);

		// s_postal_code
		$tbl_students->s_postal_code->EditCustomAttributes = "";
		$tbl_students->s_postal_code->EditValue = ew_HtmlEncode($tbl_students->s_postal_code->CurrentValue);

		// s_state
		$tbl_students->s_state->EditCustomAttributes = "";
		$tbl_students->s_state->EditValue = ew_HtmlEncode($tbl_students->s_state->CurrentValue);

		// s_country
		$tbl_students->s_country->EditCustomAttributes = "";
		$tbl_students->s_country->EditValue = ew_HtmlEncode($tbl_students->s_country->CurrentValue);

		// s_home_phone
		$tbl_students->s_home_phone->EditCustomAttributes = "";
		$tbl_students->s_home_phone->EditValue = ew_HtmlEncode($tbl_students->s_home_phone->CurrentValue);

		// s_student_mobile
		$tbl_students->s_student_mobile->EditCustomAttributes = "";
		$tbl_students->s_student_mobile->EditValue = ew_HtmlEncode($tbl_students->s_student_mobile->CurrentValue);

		// s_student_email
		$tbl_students->s_student_email->EditCustomAttributes = "";
		$tbl_students->s_student_email->EditValue = ew_HtmlEncode($tbl_students->s_student_email->CurrentValue);

		// s_parent_name
		$tbl_students->s_parent_name->EditCustomAttributes = "";
		$tbl_students->s_parent_name->EditValue = ew_HtmlEncode($tbl_students->s_parent_name->CurrentValue);

		// s_parent_mobile
		$tbl_students->s_parent_mobile->EditCustomAttributes = "";
		$tbl_students->s_parent_mobile->EditValue = ew_HtmlEncode($tbl_students->s_parent_mobile->CurrentValue);

		// s_parent_email
		$tbl_students->s_parent_email->EditCustomAttributes = "";
		$tbl_students->s_parent_email->EditValue = ew_HtmlEncode($tbl_students->s_parent_email->CurrentValue);

		// s_school
		$tbl_students->s_school->EditCustomAttributes = "";
		$tbl_students->s_school->EditValue = ew_HtmlEncode($tbl_students->s_school->CurrentValue);

		// s_graduation_year
		$tbl_students->s_graduation_year->EditCustomAttributes = "";
		$tbl_students->s_graduation_year->EditValue = ew_HtmlEncode($tbl_students->s_graduation_year->CurrentValue);

		// s_usrname
		$tbl_students->s_usrname->EditCustomAttributes = "";
		$tbl_students->s_usrname->EditValue = $tbl_students->s_usrname->CurrentValue;
		$tbl_students->s_usrname->CssStyle = "";
		$tbl_students->s_usrname->CssClass = "";
		$tbl_students->s_usrname->ViewCustomAttributes = "";

		// s_pwd
		$tbl_students->s_pwd->EditCustomAttributes = "";
		$tbl_students->s_pwd->EditValue = ew_HtmlEncode($tbl_students->s_pwd->CurrentValue);
	} elseif ($tbl_students->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_students->Row_Rendered();
}
?>
<?php

// Update record based on key values
function EditRow() {
	global $conn, $Security, $tbl_students;
	$sFilter = $tbl_students->SqlKeyFilter();
	if (!is_numeric($tbl_students->s_studentid->CurrentValue)) {
		return FALSE;
	}
	$sFilter = str_replace("@s_studentid@", ew_AdjustSql($tbl_students->s_studentid->CurrentValue), $sFilter); // Replace key value
	$sFilter = str_replace("@s_usrname@", ew_AdjustSql($tbl_students->s_usrname->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_students->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
		$tbl_students->CurrentFilter = $sFilter;
	}
	$tbl_students->CurrentFilter = $sFilter;
	$sSql = $tbl_students->SQL();
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

		// Field s_studentid
		// Field s_first_name

		$tbl_students->s_first_name->SetDbValueDef($tbl_students->s_first_name->CurrentValue, "");
		$rsnew['s_first_name'] =& $tbl_students->s_first_name->DbValue;

		// Field s_middle_name
		$tbl_students->s_middle_name->SetDbValueDef($tbl_students->s_middle_name->CurrentValue, NULL);
		$rsnew['s_middle_name'] =& $tbl_students->s_middle_name->DbValue;

		// Field s_last_name
		$tbl_students->s_last_name->SetDbValueDef($tbl_students->s_last_name->CurrentValue, "");
		$rsnew['s_last_name'] =& $tbl_students->s_last_name->DbValue;

		// Field s_address
		$tbl_students->s_address->SetDbValueDef($tbl_students->s_address->CurrentValue, "");
		$rsnew['s_address'] =& $tbl_students->s_address->DbValue;

		// Field s_city
		$tbl_students->s_city->SetDbValueDef($tbl_students->s_city->CurrentValue, "");
		$rsnew['s_city'] =& $tbl_students->s_city->DbValue;

		// Field s_postal_code
		$tbl_students->s_postal_code->SetDbValueDef($tbl_students->s_postal_code->CurrentValue, NULL);
		$rsnew['s_postal_code'] =& $tbl_students->s_postal_code->DbValue;

		// Field s_state
		$tbl_students->s_state->SetDbValueDef($tbl_students->s_state->CurrentValue, NULL);
		$rsnew['s_state'] =& $tbl_students->s_state->DbValue;

		// Field s_country
		$tbl_students->s_country->SetDbValueDef($tbl_students->s_country->CurrentValue, "");
		$rsnew['s_country'] =& $tbl_students->s_country->DbValue;

		// Field s_home_phone
		$tbl_students->s_home_phone->SetDbValueDef($tbl_students->s_home_phone->CurrentValue, NULL);
		$rsnew['s_home_phone'] =& $tbl_students->s_home_phone->DbValue;

		// Field s_student_mobile
		$tbl_students->s_student_mobile->SetDbValueDef($tbl_students->s_student_mobile->CurrentValue, NULL);
		$rsnew['s_student_mobile'] =& $tbl_students->s_student_mobile->DbValue;

		// Field s_student_email
		$tbl_students->s_student_email->SetDbValueDef($tbl_students->s_student_email->CurrentValue, NULL);
		$rsnew['s_student_email'] =& $tbl_students->s_student_email->DbValue;

		// Field s_parent_name
		$tbl_students->s_parent_name->SetDbValueDef($tbl_students->s_parent_name->CurrentValue, "");
		$rsnew['s_parent_name'] =& $tbl_students->s_parent_name->DbValue;

		// Field s_parent_mobile
		$tbl_students->s_parent_mobile->SetDbValueDef($tbl_students->s_parent_mobile->CurrentValue, NULL);
		$rsnew['s_parent_mobile'] =& $tbl_students->s_parent_mobile->DbValue;

		// Field s_parent_email
		$tbl_students->s_parent_email->SetDbValueDef($tbl_students->s_parent_email->CurrentValue, NULL);
		$rsnew['s_parent_email'] =& $tbl_students->s_parent_email->DbValue;

		// Field s_school
		$tbl_students->s_school->SetDbValueDef($tbl_students->s_school->CurrentValue, NULL);
		$rsnew['s_school'] =& $tbl_students->s_school->DbValue;

		// Field s_graduation_year
		$tbl_students->s_graduation_year->SetDbValueDef($tbl_students->s_graduation_year->CurrentValue, NULL);
		$rsnew['s_graduation_year'] =& $tbl_students->s_graduation_year->DbValue;

		// Field s_usrname
		// Field s_pwd

		$tbl_students->s_pwd->SetDbValueDef($tbl_students->s_pwd->CurrentValue, "");
		$rsnew['s_pwd'] =& $tbl_students->s_pwd->DbValue;

		// Call Row Updating event
		$bUpdateRow = $tbl_students->Row_Updating($rsold, $rsnew);
		if ($bUpdateRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$EditRow = $conn->Execute($tbl_students->UpdateSQL($rsnew));
			$conn->raiseErrorFn = '';
		} else {
			if ($tbl_students->CancelMessage <> "") {
				$_SESSION[EW_SESSION_MESSAGE] = $tbl_students->CancelMessage;
				$tbl_students->CancelMessage = "";
			} else {
				$_SESSION[EW_SESSION_MESSAGE] = "Update cancelled";
			}
			$EditRow = FALSE;
		}
	}

	// Call Row Updated event
	if ($EditRow) {
		$tbl_students->Row_Updated($rsold, $rsnew);
	}
	$rs->Close();
	return $EditRow;
}
?>
<?php

// Show link optionally based on User ID
function ShowOptionLink() {
	global $Security, $tbl_students;
	if ($Security->IsLoggedIn()) {
		if (!$Security->IsAdmin()) {
			return $Security->IsValidUserID($tbl_students->s_studentid->CurrentValue);
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
