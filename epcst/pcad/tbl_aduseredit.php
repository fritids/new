<?php
define("EW_PAGE_ID", "edit", TRUE); // Page ID
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

// Load key from QueryString
if (@$_GET["a_uname"] <> "") {
	$tbl_aduser->a_uname->setQueryStringValue($_GET["a_uname"]);
}

// Create form object
$objForm = new cFormObj();
if (@$_POST["a_edit"] <> "") {
	$tbl_aduser->CurrentAction = $_POST["a_edit"]; // Get action code
	LoadFormValues(); // Get form values
} else {
	$tbl_aduser->CurrentAction = "I"; // Default action is display
}

// Check if valid key
if ($tbl_aduser->a_uname->CurrentValue == "") Page_Terminate($tbl_aduser->getReturnUrl()); // Invalid key, exit
switch ($tbl_aduser->CurrentAction) {
	case "I": // Get a record to display
		if (!LoadRow()) { // Load Record based on key
			$_SESSION[EW_SESSION_MESSAGE] = "No records found"; // No record found
			Page_Terminate($tbl_aduser->getReturnUrl()); // Return to caller
		}
		break;
	Case "U": // Update
		$tbl_aduser->SendEmail = TRUE; // Send email on update success
		if (EditRow()) { // Update Record based on key
			$_SESSION[EW_SESSION_MESSAGE] = "Update successful"; // Update success
			Page_Terminate($tbl_aduser->getReturnUrl()); // Return to caller
		} else {
			RestoreFormValues(); // Restore form values if update failed
		}
}

// Render the record
$tbl_aduser->RowType = EW_ROWTYPE_EDIT; // Render as edit
RenderRow();
?>
<?php include "header.php" ?>
<script type="text/javascript">
<!--
var EW_PAGE_ID = "edit"; // Page id

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
		elm = fobj.elements["x" + infix + "_a_uname"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Username"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_a_first_name"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - First Name"))
				return false;
		}
		elm = fobj.elements["x" + infix + "_a_last_name"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Last Name"))
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
<script type="text/javascript">
<!--
var ew_MultiPagePage = "Page"; // multi-page Page Text
var ew_MultiPageOf = "of"; // multi-page Of Text
var ew_MultiPagePrev = "Prev"; // multi-page Prev Text
var ew_MultiPageNext = "Next"; // multi-page Next Text

//-->
</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js"); 
//-->

</script>
<p><span class="edge">Update  Admin Information <br>
  <br><a href="<?php echo $tbl_aduser->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form name="ftbl_aduseredit" id="ftbl_aduseredit" action="tbl_aduseredit.php" method="post" onSubmit="return ew_ValidateForm(this);">
<p>
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewTable">
	<tr class="ewTableRow">
		<td class="ewTableHeader">Username<span class='ewmsg'>&nbsp;*</span></td>
		<td<?php echo $tbl_aduser->a_uname->CellAttributes() ?>><span id="cb_x_a_uname">
<div<?php echo $tbl_aduser->a_uname->ViewAttributes() ?>><?php echo $tbl_aduser->a_uname->EditValue ?></div>
<input type="hidden" name="x_a_uname" id="x_a_uname" value="<?php echo ew_HtmlEncode($tbl_aduser->a_uname->CurrentValue) ?>">
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">First Name<span class='ewmsg'>&nbsp;*</span></td>
		<td<?php echo $tbl_aduser->a_first_name->CellAttributes() ?>><span id="cb_x_a_first_name">
<input type="text" name="x_a_first_name" id="x_a_first_name" title="" size="30" maxlength="45" value="<?php echo $tbl_aduser->a_first_name->EditValue ?>"<?php echo $tbl_aduser->a_first_name->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Last Name<span class='ewmsg'>&nbsp;*</span></td>
		<td<?php echo $tbl_aduser->a_last_name->CellAttributes() ?>><span id="cb_x_a_last_name">
<input type="text" name="x_a_last_name" id="x_a_last_name" title="" size="30" maxlength="45" value="<?php echo $tbl_aduser->a_last_name->EditValue ?>"<?php echo $tbl_aduser->a_last_name->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">E-mail</td>
		<td<?php echo $tbl_aduser->a_email->CellAttributes() ?>><span id="cb_x_a_email">
<input type="text" name="x_a_email" id="x_a_email" title="" size="30" maxlength="45" value="<?php echo $tbl_aduser->a_email->EditValue ?>"<?php echo $tbl_aduser->a_email->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Mobile</td>
		<td<?php echo $tbl_aduser->a_mobile->CellAttributes() ?>><span id="cb_x_a_mobile">
<input type="text" name="x_a_mobile" id="x_a_mobile" title="" size="30" maxlength="45" value="<?php echo $tbl_aduser->a_mobile->EditValue ?>"<?php echo $tbl_aduser->a_mobile->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Password</td>
		<td<?php echo $tbl_aduser->a_pwd->CellAttributes() ?>><span id="cb_x_a_pwd">
<input type="password" name="x_a_pwd" id="x_a_pwd" title="" value="<?php echo $tbl_aduser->a_pwd->EditValue ?>" size="30" maxlength="45"<?php echo $tbl_aduser->a_pwd->EditAttributes() ?>>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="   Update   ">
</form>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
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

// Load form values
function LoadFormValues() {

	// Load from form
	global $objForm, $tbl_aduser;
	$tbl_aduser->a_uname->setFormValue($objForm->GetValue("x_a_uname"));
	$tbl_aduser->a_first_name->setFormValue($objForm->GetValue("x_a_first_name"));
	$tbl_aduser->a_last_name->setFormValue($objForm->GetValue("x_a_last_name"));
	$tbl_aduser->a_email->setFormValue($objForm->GetValue("x_a_email"));
	$tbl_aduser->a_mobile->setFormValue($objForm->GetValue("x_a_mobile"));
	$tbl_aduser->a_pwd->setFormValue($objForm->GetValue("x_a_pwd"));
}

// Restore form values
function RestoreFormValues() {
	global $tbl_aduser;
	$tbl_aduser->a_uname->CurrentValue = $tbl_aduser->a_uname->FormValue;
	$tbl_aduser->a_first_name->CurrentValue = $tbl_aduser->a_first_name->FormValue;
	$tbl_aduser->a_last_name->CurrentValue = $tbl_aduser->a_last_name->FormValue;
	$tbl_aduser->a_email->CurrentValue = $tbl_aduser->a_email->FormValue;
	$tbl_aduser->a_mobile->CurrentValue = $tbl_aduser->a_mobile->FormValue;
	$tbl_aduser->a_pwd->CurrentValue = $tbl_aduser->a_pwd->FormValue;
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
	} elseif ($tbl_aduser->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_aduser->RowType == EW_ROWTYPE_EDIT) { // Edit row

		// a_uname
		$tbl_aduser->a_uname->EditCustomAttributes = "";
		$tbl_aduser->a_uname->EditValue = $tbl_aduser->a_uname->CurrentValue;
		$tbl_aduser->a_uname->CssStyle = "";
		$tbl_aduser->a_uname->CssClass = "";
		$tbl_aduser->a_uname->ViewCustomAttributes = "";

		// a_first_name
		$tbl_aduser->a_first_name->EditCustomAttributes = "";
		$tbl_aduser->a_first_name->EditValue = ew_HtmlEncode($tbl_aduser->a_first_name->CurrentValue);

		// a_last_name
		$tbl_aduser->a_last_name->EditCustomAttributes = "";
		$tbl_aduser->a_last_name->EditValue = ew_HtmlEncode($tbl_aduser->a_last_name->CurrentValue);

		// a_email
		$tbl_aduser->a_email->EditCustomAttributes = "";
		$tbl_aduser->a_email->EditValue = ew_HtmlEncode($tbl_aduser->a_email->CurrentValue);

		// a_mobile
		$tbl_aduser->a_mobile->EditCustomAttributes = "";
		$tbl_aduser->a_mobile->EditValue = ew_HtmlEncode($tbl_aduser->a_mobile->CurrentValue);

		// a_pwd
		$tbl_aduser->a_pwd->EditCustomAttributes = "";
		$tbl_aduser->a_pwd->EditValue = ew_HtmlEncode($tbl_aduser->a_pwd->CurrentValue);
	} elseif ($tbl_aduser->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_aduser->Row_Rendered();
}
?>
<?php

// Update record based on key values
function EditRow() {
	global $conn, $Security, $tbl_aduser;
	$sFilter = $tbl_aduser->SqlKeyFilter();
	$sFilter = str_replace("@a_uname@", ew_AdjustSql($tbl_aduser->a_uname->CurrentValue), $sFilter); // Replace key value
	$tbl_aduser->CurrentFilter = $sFilter;
	$sSql = $tbl_aduser->SQL();
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

		// Field a_uname
		// Field a_first_name

		$tbl_aduser->a_first_name->SetDbValueDef($tbl_aduser->a_first_name->CurrentValue, "");
		$rsnew['a_first_name'] =& $tbl_aduser->a_first_name->DbValue;

		// Field a_last_name
		$tbl_aduser->a_last_name->SetDbValueDef($tbl_aduser->a_last_name->CurrentValue, "");
		$rsnew['a_last_name'] =& $tbl_aduser->a_last_name->DbValue;

		// Field a_email
		$tbl_aduser->a_email->SetDbValueDef($tbl_aduser->a_email->CurrentValue, NULL);
		$rsnew['a_email'] =& $tbl_aduser->a_email->DbValue;

		// Field a_mobile
		$tbl_aduser->a_mobile->SetDbValueDef($tbl_aduser->a_mobile->CurrentValue, NULL);
		$rsnew['a_mobile'] =& $tbl_aduser->a_mobile->DbValue;

		// Field a_pwd
		$tbl_aduser->a_pwd->SetDbValueDef($tbl_aduser->a_pwd->CurrentValue, NULL);
		$rsnew['a_pwd'] =& $tbl_aduser->a_pwd->DbValue;

		// Call Row Updating event
		$bUpdateRow = $tbl_aduser->Row_Updating($rsold, $rsnew);
		if ($bUpdateRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$EditRow = $conn->Execute($tbl_aduser->UpdateSQL($rsnew));
			$conn->raiseErrorFn = '';
		} else {
			if ($tbl_aduser->CancelMessage <> "") {
				$_SESSION[EW_SESSION_MESSAGE] = $tbl_aduser->CancelMessage;
				$tbl_aduser->CancelMessage = "";
			} else {
				$_SESSION[EW_SESSION_MESSAGE] = "Update cancelled";
			}
			$EditRow = FALSE;
		}
	}

	// Call Row Updated event
	if ($EditRow) {
		$tbl_aduser->Row_Updated($rsold, $rsnew);
	}
	$rs->Close();
	return $EditRow;
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
