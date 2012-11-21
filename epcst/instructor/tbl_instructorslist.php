<?php
define("EW_PAGE_ID", "list", TRUE); // Page ID
define("EW_TABLE_NAME", 'tbl_instructors', TRUE);
?>
<?php 
session_start(); // Initialize session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg50.php" ?>
<?php include "ewmysql50.php" ?>
<?php include "phpfn50.php" ?>
<?php include "tbl_instructorsinfo.php" ?>
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
$tbl_instructors->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_instructors->Export; // Get export parameter, used in header
$sExportFile = $tbl_instructors->TableVar; // Get export file, used in header
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
	$tbl_instructors->CurrentAction = $_GET["a"];

	// Clear inline mode
	if ($tbl_instructors->CurrentAction == "cancel") {
		ClearInlineMode();
	}

	// Switch to inline edit mode
	if ($tbl_instructors->CurrentAction == "edit") {
		InlineEditMode();
	}
} else {

	// Create form object
	$objForm = new cFormObj;
	if (@$_POST["a_list"] <> "") {
		$tbl_instructors->CurrentAction = $_POST["a_list"]; // Get action

		// Inline Update
		if ($tbl_instructors->CurrentAction == "update" && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit") {
			InlineUpdate();
		}
	}
}

// Build filter
$sFilter = "";
if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
	$sFilter = $tbl_instructors->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
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
$tbl_instructors->setSessionWhere($sFilter);
$tbl_instructors->CurrentFilter = "";

// Set Up Sorting Order
SetUpSortOrder();

// Set Return Url
$tbl_instructors->setReturnUrl("tbl_instructorslist.php");
?>
<?php include "header.php" ?>
<?php if ($tbl_instructors->Export == "") { ?>
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
		elm = fobj.elements["x" + infix + "_i_first_name"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - First Name"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_i_last_name"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Last Name"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_i_uname"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Username"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_i_pwd"];
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
<?php if ($tbl_instructors->Export == "") { ?>
<?php } ?>
<?php

// Load recordset
$bExportAll = (defined("EW_EXPORT_ALL") && $tbl_instructors->Export <> "");
$bSelectLimit = ($tbl_instructors->Export == "" && $tbl_instructors->SelectLimit);
if (!$bSelectLimit) $rs = LoadRecordset();
$nTotalRecs = ($bSelectLimit) ? $tbl_instructors->SelectRecordCount() : $rs->RecordCount();
$nStartRec = 1;
if ($nDisplayRecs <= 0) $nDisplayRecs = $nTotalRecs; // Display all records
if (!$bExportAll) SetUpStartRec(); // Set up start record position
if ($bSelectLimit) $rs = LoadRecordset($nStartRec-1, $nDisplayRecs);
?>
<p><span class="edge" style="white-space: nowrap;">Instructor Profile
</span></p>
<?php if ($tbl_instructors->Export == "") { ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form name="ftbl_instructorslist" id="ftbl_instructorslist" action="tbl_instructorslist.php" method="post">
<?php if ($tbl_instructors->Export == "") { ?>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?>
<table border="0" cellspacing="5" cellpadding="5">
<?php
if (defined("EW_EXPORT_ALL") && $tbl_instructors->Export <> "") {
	$nStopRec = $nTotalRecs;
} else {
	$nStopRec = $nStartRec + $nDisplayRecs - 1; // Set the last record to display
}
$nRecCount = $nStartRec - 1;
if (!$rs->EOF) {
	$rs->MoveFirst();
	if (!$tbl_instructors->SelectLimit) $rs->Move($nStartRec - 1); // Move to first record directly
}
$RowCnt = 0;
$nEditRowCnt = 0;
if ($tbl_instructors->CurrentAction == "edit") $RowIndex = 1;
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;
		$ColCnt++;
		if ($ColCnt > $nRecPerRow) $ColCnt = 1;

	// Init row class and style
	$tbl_instructors->CssClass = "ewTableRow";
	$tbl_instructors->CssStyle = "";

	// Init row event
	$tbl_instructors->RowClientEvents = "";
	LoadRowValues($rs); // Load row values
	$tbl_instructors->RowType = EW_ROWTYPE_VIEW; // Render view
	if ($tbl_instructors->CurrentAction == "edit") {
		if (CheckInlineEditKey() && $nEditRowCnt == 0) { // Inline edit
			$tbl_instructors->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
	}
		if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT && $tbl_instructors->EventCancelled) { // Update failed
			if ($tbl_instructors->CurrentAction == "edit") {
				RestoreFormValues(); // Restore form values
			}
		}
		if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit row
			$nEditRowCnt++;
			$tbl_instructors->CssClass = "ewTableEditRow";
			$tbl_instructors->RowClientEvents = "";
		}
	RenderRow();
?>
<?php if ($ColCnt == 1) { ?>
<tr>
<?php } ?>
	<td valign="top"<?php echo $tbl_instructors->DisplayAttributes() ?>>
	<table class="ewTable">
		<tr class="ewTableRow">
			<td width="88" class="ewTableHeader">			</td>
			<td width="150"<?php echo $tbl_instructors->i_instructorid->CellAttributes() ?>>
<?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="hidden" name="x<?php echo $RowIndex ?>_i_instructorid" id="x<?php echo $RowIndex ?>_i_instructorid" value="<?php echo ew_HtmlEncode($tbl_instructors->i_instructorid->CurrentValue) ?>">
<?php } else { ?>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="88" class="ewTableHeader">
<?php if ($tbl_instructors->Export <> "") { ?>
First Name
<?php } else { ?>
	First Name<?php if ($tbl_instructors->i_first_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_first_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_instructors->i_first_name->CellAttributes() ?>>
<?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_i_first_name" id="x<?php echo $RowIndex ?>_i_first_name" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_first_name->EditValue ?>"<?php echo $tbl_instructors->i_first_name->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_instructors->i_first_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_first_name->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="88" class="ewTableHeader">
<?php if ($tbl_instructors->Export <> "") { ?>
Last Name
<?php } else { ?>
	Last Name<?php if ($tbl_instructors->i_last_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_last_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_instructors->i_last_name->CellAttributes() ?>>
<?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_i_last_name" id="x<?php echo $RowIndex ?>_i_last_name" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_last_name->EditValue ?>"<?php echo $tbl_instructors->i_last_name->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_instructors->i_last_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_last_name->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="88" class="ewTableHeader">
<?php if ($tbl_instructors->Export <> "") { ?>
E-mail
<?php } else { ?>
	E-mail<?php if ($tbl_instructors->i_email->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_email->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_instructors->i_email->CellAttributes() ?>>
<?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_i_email" id="x<?php echo $RowIndex ?>_i_email" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_email->EditValue ?>"<?php echo $tbl_instructors->i_email->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_instructors->i_email->ViewAttributes() ?>><?php echo $tbl_instructors->i_email->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="88" class="ewTableHeader">
<?php if ($tbl_instructors->Export <> "") { ?>
Mobile
<?php } else { ?>
	Mobile<?php if ($tbl_instructors->i_mobile->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_mobile->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_instructors->i_mobile->CellAttributes() ?>>
<?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="text" name="x<?php echo $RowIndex ?>_i_mobile" id="x<?php echo $RowIndex ?>_i_mobile" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_mobile->EditValue ?>"<?php echo $tbl_instructors->i_mobile->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_instructors->i_mobile->ViewAttributes() ?>><?php echo $tbl_instructors->i_mobile->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="88" class="ewTableHeader">
<?php if ($tbl_instructors->Export <> "") { ?>
Username
<?php } else { ?>
	Username<?php if ($tbl_instructors->i_uname->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_uname->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_instructors->i_uname->CellAttributes() ?>>
<?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<div<?php echo $tbl_instructors->i_uname->ViewAttributes() ?>><?php echo $tbl_instructors->i_uname->EditValue ?></div>
<input type="hidden" name="x<?php echo $RowIndex ?>_i_uname" id="x<?php echo $RowIndex ?>_i_uname" value="<?php echo ew_HtmlEncode($tbl_instructors->i_uname->CurrentValue) ?>">
<?php } else { ?>
<div<?php echo $tbl_instructors->i_uname->ViewAttributes() ?>><?php echo $tbl_instructors->i_uname->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
		<tr class="ewTableRow">
			<td width="88" class="ewTableHeader">
<?php if ($tbl_instructors->Export <> "") { ?>
Password
<?php } else { ?>
	Password<?php if ($tbl_instructors->i_pwd->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_pwd->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>			</td>
			<td width="150"<?php echo $tbl_instructors->i_pwd->CellAttributes() ?>>
<?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit Record ?>
<input type="password" name="x<?php echo $RowIndex ?>_i_pwd" id="x<?php echo $RowIndex ?>_i_pwd" title="" value="<?php echo $tbl_instructors->i_pwd->EditValue ?>" size="15" maxlength="45"<?php echo $tbl_instructors->i_pwd->EditAttributes() ?>>
<?php } else { ?>
<div<?php echo $tbl_instructors->i_pwd->ViewAttributes() ?>><?php echo $tbl_instructors->i_pwd->ViewValue ?></div>
<?php } ?>
</td>
		</tr>
	</table>
</td>
<?php if ($ColCnt == $nRecPerRow) { ?>
</tr>
<?php } ?>
<?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { ?>
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
<p><span class="edge">
  <?php if ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { ?>
  <?php if ($tbl_instructors->CurrentAction == "edit") { ?>
  <a href="" onclick="if (ew_ValidateForm(document.ftbl_instructorslist)) document.ftbl_instructorslist.submit();return false;">Update</a>&nbsp;<a href="tbl_instructorslist.php?a=cancel">Cancel</a>
  <input type="hidden" name="a_list" id="a_list" value="update" />
  <?php } ?>
  <?php } else { ?>
  <?php if ($tbl_instructors->Export == "") { ?>
  <?php if ($Security->IsLoggedIn()) { ?>
  <?php if (ShowOptionLink()) { ?>
  <a href="<?php echo $tbl_instructors->InlineEditUrl() ?>"> Edit</a>&nbsp;
  <?php } ?>
  <?php } ?>
  <?php } ?>
  <?php } ?>
</span> </p>
<p>
  <?php if ($tbl_instructors->Export == "") { ?>
</p>
<p>&nbsp; </p>
<table>
	<tr><td><span class="edge">
	</span></td></tr>
</table>
<?php } ?>
<?php } ?>
<?php if ($tbl_instructors->CurrentAction == "edit") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $RowIndex ?>">
<?php } ?>
</form>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($tbl_instructors->Export == "") { ?>

<?php } ?>
<?php if ($tbl_instructors->Export == "") { ?>
<?php } ?>
<?php if ($tbl_instructors->Export == "") { ?>
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
	global $tbl_instructors;
	$tbl_instructors->setKey("i_instructorid", ""); // Clear inline edit key
	$tbl_instructors->setKey("i_uname", ""); // Clear inline edit key
	$tbl_instructors->CurrentAction = ""; // Clear action
	$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
}

// Switch to Inline Edit Mode
function InlineEditMode() {
	global $Security, $tbl_instructors;
	$bInlineEdit = TRUE;
	if (@$_GET["i_instructorid"] <> "") {
		$tbl_instructors->i_instructorid->setQueryStringValue($_GET["i_instructorid"]);
	} else {
		$bInlineEdit = FALSE;
	}
	if (@$_GET["i_uname"] <> "") {
		$tbl_instructors->i_uname->setQueryStringValue($_GET["i_uname"]);
	} else {
		$bInlineEdit = FALSE;
	}
	if ($bInlineEdit) {
		if (LoadRow()) {
			$tbl_instructors->setKey("i_instructorid", $tbl_instructors->i_instructorid->CurrentValue); // Set up inline edit key
			$tbl_instructors->setKey("i_uname", $tbl_instructors->i_uname->CurrentValue); // Set up inline edit key
			$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
		}
	}
}

// Peform update to inline edit record
function InlineUpdate() {
	global $objForm, $tbl_instructors;
	$objForm->Index = 1; 
	LoadFormValues(); // Get form values
	if (CheckInlineEditKey()) { // Check key
		$tbl_instructors->SendEmail = TRUE; // Send email on update success
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
		$tbl_instructors->EventCancelled = TRUE; // Cancel event
		$tbl_instructors->CurrentAction = "edit"; // Stay in edit mode
	}
}

// Check inline edit key
function CheckInlineEditKey() {
	global $tbl_instructors;

	//CheckInlineEditKey = True
	if (strval($tbl_instructors->getKey("i_instructorid")) <> strval($tbl_instructors->i_instructorid->CurrentValue)) {
		return FALSE;
	}
	if (strval($tbl_instructors->getKey("i_uname")) <> strval($tbl_instructors->i_uname->CurrentValue)) {
		return FALSE;
	}
	return TRUE;
}

// Set up Sort parameters based on Sort Links clicked
function SetUpSortOrder() {
	global $tbl_instructors;

	// Check for an Order parameter
	if (@$_GET["order"] <> "") {
		$tbl_instructors->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
		$tbl_instructors->CurrentOrderType = @$_GET["ordertype"];
		$tbl_instructors->setStartRecordNumber(1); // Reset start position
	}
	$sOrderBy = $tbl_instructors->getSessionOrderBy(); // Get order by from Session
	if ($sOrderBy == "") {
		if ($tbl_instructors->SqlOrderBy() <> "") {
			$sOrderBy = $tbl_instructors->SqlOrderBy();
			$tbl_instructors->setSessionOrderBy($sOrderBy);
		}
	}
}

// Reset command based on querystring parameter cmd=
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters
function ResetCmd() {
	global $sDbMasterFilter, $sDbDetailFilter, $nStartRec, $sOrderBy;
	global $tbl_instructors;

	// Get reset cmd
	if (@$_GET["cmd"] <> "") {
		$sCmd = $_GET["cmd"];

		// Reset Sort Criteria
		if (strtolower($sCmd) == "resetsort") {
			$sOrderBy = "";
			$tbl_instructors->setSessionOrderBy($sOrderBy);
		}

		// Reset start position
		$nStartRec = 1;
		$tbl_instructors->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Set up Starting Record parameters based on Pager Navigation
function SetUpStartRec() {
	global $nDisplayRecs, $nStartRec, $nTotalRecs, $nPageNo, $tbl_instructors;
	if ($nDisplayRecs == 0) return;

	// Check for a START parameter
	if (@$_GET[EW_TABLE_START_REC] <> "") {
		$nStartRec = $_GET[EW_TABLE_START_REC];
		$tbl_instructors->setStartRecordNumber($nStartRec);
	} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
		$nPageNo = $_GET[EW_TABLE_PAGE_NO];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$tbl_instructors->setStartRecordNumber($nStartRec);
		} else {
			$nStartRec = $tbl_instructors->getStartRecordNumber();
		}
	} else {
		$nStartRec = $tbl_instructors->getStartRecordNumber();
	}

	// Check if correct start record counter
	if (!is_numeric($nStartRec) || $nStartRec == "") { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$tbl_instructors->setStartRecordNumber($nStartRec);
	} elseif (intval($nStartRec) > intval($nTotalRecs)) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$tbl_instructors->setStartRecordNumber($nStartRec);
	} elseif (($nStartRec-1) % $nDisplayRecs <> 0) {
		$nStartRec = intval(($nStartRec-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to page boundary
		$tbl_instructors->setStartRecordNumber($nStartRec);
	}
}
?>
<?php

// Load form values
function LoadFormValues() {

	// Load from form
	global $objForm, $tbl_instructors;
	$tbl_instructors->i_instructorid->setFormValue($objForm->GetValue("x_i_instructorid"));
	$tbl_instructors->i_first_name->setFormValue($objForm->GetValue("x_i_first_name"));
	$tbl_instructors->i_last_name->setFormValue($objForm->GetValue("x_i_last_name"));
	$tbl_instructors->i_email->setFormValue($objForm->GetValue("x_i_email"));
	$tbl_instructors->i_mobile->setFormValue($objForm->GetValue("x_i_mobile"));
	$tbl_instructors->i_uname->setFormValue($objForm->GetValue("x_i_uname"));
	$tbl_instructors->i_pwd->setFormValue($objForm->GetValue("x_i_pwd"));
}

// Restore form values
function RestoreFormValues() {
	global $tbl_instructors;
	$tbl_instructors->i_instructorid->CurrentValue = $tbl_instructors->i_instructorid->FormValue;
	$tbl_instructors->i_first_name->CurrentValue = $tbl_instructors->i_first_name->FormValue;
	$tbl_instructors->i_last_name->CurrentValue = $tbl_instructors->i_last_name->FormValue;
	$tbl_instructors->i_email->CurrentValue = $tbl_instructors->i_email->FormValue;
	$tbl_instructors->i_mobile->CurrentValue = $tbl_instructors->i_mobile->FormValue;
	$tbl_instructors->i_uname->CurrentValue = $tbl_instructors->i_uname->FormValue;
	$tbl_instructors->i_pwd->CurrentValue = $tbl_instructors->i_pwd->FormValue;
}
?>
<?php

// Load recordset
function LoadRecordset($offset = -1, $rowcnt = -1) {
	global $conn, $tbl_instructors;

	// Call Recordset Selecting event
	$tbl_instructors->Recordset_Selecting($tbl_instructors->CurrentFilter);

	// Load list page sql
	$sSql = $tbl_instructors->SelectSQL();
	if ($offset > -1 && $rowcnt > -1) $sSql .= " LIMIT $offset, $rowcnt";

	// Load recordset
	$conn->raiseErrorFn = 'ew_ErrorFn';	
	$rs = $conn->Execute($sSql);
	$conn->raiseErrorFn = '';

	// Call Recordset Selected event
	$tbl_instructors->Recordset_Selected($rs);
	return $rs;
}
?>
<?php

// Load row based on key values
function LoadRow() {
	global $conn, $Security, $tbl_instructors;
	$sFilter = $tbl_instructors->SqlKeyFilter();
	if (!is_numeric($tbl_instructors->i_instructorid->CurrentValue)) {
		return FALSE; // Invalid key, exit
	}
	$sFilter = str_replace("@i_instructorid@", ew_AdjustSql($tbl_instructors->i_instructorid->CurrentValue), $sFilter); // Replace key value
	$sFilter = str_replace("@i_uname@", ew_AdjustSql($tbl_instructors->i_uname->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_instructors->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
	}

	// Call Row Selecting event
	$tbl_instructors->Row_Selecting($sFilter);

	// Load sql based on filter
	$tbl_instructors->CurrentFilter = $sFilter;
	$sSql = $tbl_instructors->SQL();
	if ($rs = $conn->Execute($sSql)) {
		if ($rs->EOF) {
			$LoadRow = FALSE;
		} else {
			$LoadRow = TRUE;
			$rs->MoveFirst();
			LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$tbl_instructors->Row_Selected($rs);
		}
		$rs->Close();
	} else {
		$LoadRow = FALSE;
	}
	return $LoadRow;
}

// Load row values from recordset
function LoadRowValues(&$rs) {
	global $tbl_instructors;
	$tbl_instructors->i_instructorid->setDbValue($rs->fields('i_instructorid'));
	$tbl_instructors->i_first_name->setDbValue($rs->fields('i_first_name'));
	$tbl_instructors->i_last_name->setDbValue($rs->fields('i_last_name'));
	$tbl_instructors->i_email->setDbValue($rs->fields('i_email'));
	$tbl_instructors->i_mobile->setDbValue($rs->fields('i_mobile'));
	$tbl_instructors->g_grpid->setDbValue($rs->fields('g_grpid'));
	$tbl_instructors->i_uname->setDbValue($rs->fields('i_uname'));
	$tbl_instructors->i_pwd->setDbValue($rs->fields('i_pwd'));
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_instructors;

	// Call Row Rendering event
	$tbl_instructors->Row_Rendering();

	// Common render codes for all row types
	// i_instructorid

	$tbl_instructors->i_instructorid->CellCssStyle = "";
	$tbl_instructors->i_instructorid->CellCssClass = "";

	// i_first_name
	$tbl_instructors->i_first_name->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_first_name->CellCssClass = "";

	// i_last_name
	$tbl_instructors->i_last_name->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_last_name->CellCssClass = "";

	// i_email
	$tbl_instructors->i_email->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_email->CellCssClass = "";

	// i_mobile
	$tbl_instructors->i_mobile->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_mobile->CellCssClass = "";

	// i_uname
	$tbl_instructors->i_uname->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_uname->CellCssClass = "";

	// i_pwd
	$tbl_instructors->i_pwd->CellCssStyle = "width: 25px;";
	$tbl_instructors->i_pwd->CellCssClass = "";
	if ($tbl_instructors->RowType == EW_ROWTYPE_VIEW) { // View row

		// i_instructorid
		$tbl_instructors->i_instructorid->CssStyle = "";
		$tbl_instructors->i_instructorid->CssClass = "";
		$tbl_instructors->i_instructorid->ViewCustomAttributes = "";

		// i_first_name
		$tbl_instructors->i_first_name->ViewValue = $tbl_instructors->i_first_name->CurrentValue;
		$tbl_instructors->i_first_name->CssStyle = "";
		$tbl_instructors->i_first_name->CssClass = "";
		$tbl_instructors->i_first_name->ViewCustomAttributes = "";

		// i_last_name
		$tbl_instructors->i_last_name->ViewValue = $tbl_instructors->i_last_name->CurrentValue;
		$tbl_instructors->i_last_name->CssStyle = "";
		$tbl_instructors->i_last_name->CssClass = "";
		$tbl_instructors->i_last_name->ViewCustomAttributes = "";

		// i_email
		$tbl_instructors->i_email->ViewValue = $tbl_instructors->i_email->CurrentValue;
		$tbl_instructors->i_email->CssStyle = "";
		$tbl_instructors->i_email->CssClass = "";
		$tbl_instructors->i_email->ViewCustomAttributes = "";

		// i_mobile
		$tbl_instructors->i_mobile->ViewValue = $tbl_instructors->i_mobile->CurrentValue;
		$tbl_instructors->i_mobile->CssStyle = "";
		$tbl_instructors->i_mobile->CssClass = "";
		$tbl_instructors->i_mobile->ViewCustomAttributes = "";

		// i_uname
		$tbl_instructors->i_uname->ViewValue = $tbl_instructors->i_uname->CurrentValue;
		$tbl_instructors->i_uname->CssStyle = "";
		$tbl_instructors->i_uname->CssClass = "";
		$tbl_instructors->i_uname->ViewCustomAttributes = "";

		// i_pwd
		$tbl_instructors->i_pwd->ViewValue = "********";
		$tbl_instructors->i_pwd->CssStyle = "";
		$tbl_instructors->i_pwd->CssClass = "";
		$tbl_instructors->i_pwd->ViewCustomAttributes = "";

		// i_instructorid
		$tbl_instructors->i_instructorid->HrefValue = "";

		// i_first_name
		$tbl_instructors->i_first_name->HrefValue = "";

		// i_last_name
		$tbl_instructors->i_last_name->HrefValue = "";

		// i_email
		$tbl_instructors->i_email->HrefValue = "";

		// i_mobile
		$tbl_instructors->i_mobile->HrefValue = "";

		// i_uname
		$tbl_instructors->i_uname->HrefValue = "";

		// i_pwd
		$tbl_instructors->i_pwd->HrefValue = "";
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit row

		// i_instructorid
		$tbl_instructors->i_instructorid->EditCustomAttributes = "";
		$tbl_instructors->i_instructorid->CssStyle = "";
		$tbl_instructors->i_instructorid->CssClass = "";
		$tbl_instructors->i_instructorid->ViewCustomAttributes = "";

		// i_first_name
		$tbl_instructors->i_first_name->EditCustomAttributes = "";
		$tbl_instructors->i_first_name->EditValue = ew_HtmlEncode($tbl_instructors->i_first_name->CurrentValue);

		// i_last_name
		$tbl_instructors->i_last_name->EditCustomAttributes = "";
		$tbl_instructors->i_last_name->EditValue = ew_HtmlEncode($tbl_instructors->i_last_name->CurrentValue);

		// i_email
		$tbl_instructors->i_email->EditCustomAttributes = "";
		$tbl_instructors->i_email->EditValue = ew_HtmlEncode($tbl_instructors->i_email->CurrentValue);

		// i_mobile
		$tbl_instructors->i_mobile->EditCustomAttributes = "";
		$tbl_instructors->i_mobile->EditValue = ew_HtmlEncode($tbl_instructors->i_mobile->CurrentValue);

		// i_uname
		$tbl_instructors->i_uname->EditCustomAttributes = "";
		$tbl_instructors->i_uname->EditValue = $tbl_instructors->i_uname->CurrentValue;
		$tbl_instructors->i_uname->CssStyle = "";
		$tbl_instructors->i_uname->CssClass = "";
		$tbl_instructors->i_uname->ViewCustomAttributes = "";

		// i_pwd
		$tbl_instructors->i_pwd->EditCustomAttributes = "";
		$tbl_instructors->i_pwd->EditValue = ew_HtmlEncode($tbl_instructors->i_pwd->CurrentValue);
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_instructors->Row_Rendered();
}
?>
<?php

// Update record based on key values
function EditRow() {
	global $conn, $Security, $tbl_instructors;
	$sFilter = $tbl_instructors->SqlKeyFilter();
	if (!is_numeric($tbl_instructors->i_instructorid->CurrentValue)) {
		return FALSE;
	}
	$sFilter = str_replace("@i_instructorid@", ew_AdjustSql($tbl_instructors->i_instructorid->CurrentValue), $sFilter); // Replace key value
	$sFilter = str_replace("@i_uname@", ew_AdjustSql($tbl_instructors->i_uname->CurrentValue), $sFilter); // Replace key value
	if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		$sFilter = $tbl_instructors->AddUserIDFilter($sFilter, $Security->CurrentUserID()); // Add User ID filter
		$tbl_instructors->CurrentFilter = $sFilter;
	}
	$tbl_instructors->CurrentFilter = $sFilter;
	$sSql = $tbl_instructors->SQL();
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

		// Field i_instructorid
		// Field i_first_name

		$tbl_instructors->i_first_name->SetDbValueDef($tbl_instructors->i_first_name->CurrentValue, "");
		$rsnew['i_first_name'] =& $tbl_instructors->i_first_name->DbValue;

		// Field i_last_name
		$tbl_instructors->i_last_name->SetDbValueDef($tbl_instructors->i_last_name->CurrentValue, "");
		$rsnew['i_last_name'] =& $tbl_instructors->i_last_name->DbValue;

		// Field i_email
		$tbl_instructors->i_email->SetDbValueDef($tbl_instructors->i_email->CurrentValue, NULL);
		$rsnew['i_email'] =& $tbl_instructors->i_email->DbValue;

		// Field i_mobile
		$tbl_instructors->i_mobile->SetDbValueDef($tbl_instructors->i_mobile->CurrentValue, NULL);
		$rsnew['i_mobile'] =& $tbl_instructors->i_mobile->DbValue;

		// Field i_uname
		// Field i_pwd

		$tbl_instructors->i_pwd->SetDbValueDef($tbl_instructors->i_pwd->CurrentValue, "");
		$rsnew['i_pwd'] =& $tbl_instructors->i_pwd->DbValue;

		// Call Row Updating event
		$bUpdateRow = $tbl_instructors->Row_Updating($rsold, $rsnew);
		if ($bUpdateRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$EditRow = $conn->Execute($tbl_instructors->UpdateSQL($rsnew));
			$conn->raiseErrorFn = '';
		} else {
			if ($tbl_instructors->CancelMessage <> "") {
				$_SESSION[EW_SESSION_MESSAGE] = $tbl_instructors->CancelMessage;
				$tbl_instructors->CancelMessage = "";
			} else {
				$_SESSION[EW_SESSION_MESSAGE] = "Update cancelled";
			}
			$EditRow = FALSE;
		}
	}

	// Call Row Updated event
	if ($EditRow) {
		$tbl_instructors->Row_Updated($rsold, $rsnew);
	}
	$rs->Close();
	return $EditRow;
}
?>
<?php

// Show link optionally based on User ID
function ShowOptionLink() {
	global $Security, $tbl_instructors;
	if ($Security->IsLoggedIn()) {
		if (!$Security->IsAdmin()) {
			return $Security->IsValidUserID($tbl_instructors->i_instructorid->CurrentValue);
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
