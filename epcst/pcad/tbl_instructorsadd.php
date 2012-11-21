<?php
define("EW_PAGE_ID", "add", TRUE); // Page ID
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
<?php include "tbl_aduserinfo.php" ?>
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
$tbl_instructors->Export = @$_GET["export"]; // Get export parameter
$sExport = $tbl_instructors->Export; // Get export parameter, used in header
$sExportFile = $tbl_instructors->TableVar; // Get export file, used in header
?>
<?php

// Load key values from QueryString
$bCopy = TRUE;
if (@$_GET["i_instructorid"] != "") {
  $tbl_instructors->i_instructorid->setQueryStringValue($_GET["i_instructorid"]);
} else {
  $bCopy = FALSE;
}
if (@$_GET["i_uname"] != "") {
  $tbl_instructors->i_uname->setQueryStringValue($_GET["i_uname"]);
} else {
  $bCopy = FALSE;
}

// Create form object
$objForm = new cFormObj();

// Process form if post back
if (@$_POST["a_add"] <> "") {
  $tbl_instructors->CurrentAction = $_POST["a_add"]; // Get form action
  LoadFormValues(); // Load form values
} else { // Not post back
  if ($bCopy) {
    $tbl_instructors->CurrentAction = "C"; // Copy Record
  } else {
    $tbl_instructors->CurrentAction = "I"; // Display Blank Record
    LoadDefaultValues(); // Load default values
  }
}

// Perform action based on action code
switch ($tbl_instructors->CurrentAction) {
  case "I": // Blank record, no action required
		break;
  case "C": // Copy an existing record
   if (!LoadRow()) { // Load record based on key
      $_SESSION[EW_SESSION_MESSAGE] = "No records found"; // No record found
      Page_Terminate($tbl_instructors->getReturnUrl()); // Clean up and return
    }
		break;
  case "A": // ' Add new record
		$tbl_instructors->SendEmail = TRUE; // Send email on add success
    if (AddRow()) { // Add successful
      $_SESSION[EW_SESSION_MESSAGE] = "Add New Record Successful"; // Set up success message
      Page_Terminate($tbl_instructors->KeyUrl($tbl_instructors->getReturnUrl())); // Clean up and return
    } else {
      RestoreFormValues(); // Add failed, restore form values
    }
}

// Render row based on row type
$tbl_instructors->RowType = EW_ROWTYPE_ADD;  // Render add type
RenderRow();
?>
<?php include "header.php" ?>
<script type="text/javascript">
<!--
var EW_PAGE_ID = "add"; // Page id

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
<p><span class="edge">Add New Instructor<br>
    <br><a href="<?php echo $tbl_instructors->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") { // Mesasge in Session, display
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
  $_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
}
?>
<form name="ftbl_instructorsadd" id="ftbl_instructorsadd" action="tbl_instructorsadd.php" method="post" onSubmit="return ew_ValidateForm(this);">
<p>
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewTable">
  <tr class="ewTableRow">
    <td class="ewTableHeader">First Name<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_instructors->i_first_name->CellAttributes() ?>><span id="cb_x_i_first_name">
<input type="text" name="x_i_first_name" id="x_i_first_name" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_first_name->EditValue ?>"<?php echo $tbl_instructors->i_first_name->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Last Name<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_instructors->i_last_name->CellAttributes() ?>><span id="cb_x_i_last_name">
<input type="text" name="x_i_last_name" id="x_i_last_name" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_last_name->EditValue ?>"<?php echo $tbl_instructors->i_last_name->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">E-mail</td>
    <td<?php echo $tbl_instructors->i_email->CellAttributes() ?>><span id="cb_x_i_email">
<input type="text" name="x_i_email" id="x_i_email" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_email->EditValue ?>"<?php echo $tbl_instructors->i_email->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Mobile</td>
    <td<?php echo $tbl_instructors->i_mobile->CellAttributes() ?>><span id="cb_x_i_mobile">
<input type="text" name="x_i_mobile" id="x_i_mobile" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_mobile->EditValue ?>"<?php echo $tbl_instructors->i_mobile->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Username<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_instructors->i_uname->CellAttributes() ?>><span id="cb_x_i_uname">
<input type="text" name="x_i_uname" id="x_i_uname" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_uname->EditValue ?>"<?php echo $tbl_instructors->i_uname->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Password<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_instructors->i_pwd->CellAttributes() ?>><span id="cb_x_i_pwd">
<input type="password" name="x_i_pwd" id="x_i_pwd" title="" value="<?php echo $tbl_instructors->i_pwd->EditValue ?>" size="15" maxlength="45"<?php echo $tbl_instructors->i_pwd->EditAttributes() ?>>
</span></td>
  </tr>
</table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="    Add    ">
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

// Load default values
function LoadDefaultValues() {
	global $tbl_instructors;
}
?>
<?php

// Load form values
function LoadFormValues() {

	// Load from form
	global $objForm, $tbl_instructors;
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
	$tbl_instructors->i_first_name->CurrentValue = $tbl_instructors->i_first_name->FormValue;
	$tbl_instructors->i_last_name->CurrentValue = $tbl_instructors->i_last_name->FormValue;
	$tbl_instructors->i_email->CurrentValue = $tbl_instructors->i_email->FormValue;
	$tbl_instructors->i_mobile->CurrentValue = $tbl_instructors->i_mobile->FormValue;
	$tbl_instructors->i_uname->CurrentValue = $tbl_instructors->i_uname->FormValue;
	$tbl_instructors->i_pwd->CurrentValue = $tbl_instructors->i_pwd->FormValue;
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
	// i_first_name

	$tbl_instructors->i_first_name->CellCssStyle = "";
	$tbl_instructors->i_first_name->CellCssClass = "";

	// i_last_name
	$tbl_instructors->i_last_name->CellCssStyle = "";
	$tbl_instructors->i_last_name->CellCssClass = "";

	// i_email
	$tbl_instructors->i_email->CellCssStyle = "";
	$tbl_instructors->i_email->CellCssClass = "";

	// i_mobile
	$tbl_instructors->i_mobile->CellCssStyle = "";
	$tbl_instructors->i_mobile->CellCssClass = "";

	// i_uname
	$tbl_instructors->i_uname->CellCssStyle = "";
	$tbl_instructors->i_uname->CellCssClass = "";

	// i_pwd
	$tbl_instructors->i_pwd->CellCssStyle = "";
	$tbl_instructors->i_pwd->CellCssClass = "";
	if ($tbl_instructors->RowType == EW_ROWTYPE_VIEW) { // View row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_ADD) { // Add row

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
		$tbl_instructors->i_uname->EditValue = ew_HtmlEncode($tbl_instructors->i_uname->CurrentValue);

		// i_pwd
		$tbl_instructors->i_pwd->EditCustomAttributes = "";
		$tbl_instructors->i_pwd->EditValue = ew_HtmlEncode($tbl_instructors->i_pwd->CurrentValue);
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_instructors->Row_Rendered();
}
?>
<?php

// Add record
function AddRow() {
	global $conn, $Security, $tbl_instructors;

	// Check for duplicate key
	$bCheckKey = TRUE;
	$sFilter = $tbl_instructors->SqlKeyFilter();
	if (trim(strval($tbl_instructors->i_instructorid->CurrentValue)) == "") {
		$bCheckKey = FALSE;
	} else {
		$sFilter = str_replace("@i_instructorid@", ew_AdjustSql($tbl_instructors->i_instructorid->CurrentValue), $sFilter); // Replace key value
	}
	if (!is_numeric($tbl_instructors->i_instructorid->CurrentValue)) {
		$bCheckKey = FALSE;
	}
	if (trim(strval($tbl_instructors->i_uname->CurrentValue)) == "") {
		$bCheckKey = FALSE;
	} else {
		$sFilter = str_replace("@i_uname@", ew_AdjustSql($tbl_instructors->i_uname->CurrentValue), $sFilter); // Replace key value
	}
	if ($bCheckKey) {
		$rsChk = $tbl_instructors->LoadRs($sFilter);
		if ($rsChk && !$rsChk->EOF) {
			$_SESSION[EW_SESSION_MESSAGE] = "Duplicate value for primary key";
			$rsChk->Close();
			return FALSE;
		}
	}
	$rsnew = array();

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
	$tbl_instructors->i_uname->SetDbValueDef($tbl_instructors->i_uname->CurrentValue, "");
	$rsnew['i_uname'] =& $tbl_instructors->i_uname->DbValue;

	// Field i_pwd
	$tbl_instructors->i_pwd->SetDbValueDef($tbl_instructors->i_pwd->CurrentValue, "");
	$rsnew['i_pwd'] =& $tbl_instructors->i_pwd->DbValue;

	// Call Row Inserting event
	$bInsertRow = $tbl_instructors->Row_Inserting($rsnew);
	if ($bInsertRow) {
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$AddRow = $conn->Execute($tbl_instructors->InsertSQL($rsnew));
		$conn->raiseErrorFn = '';
	} else {
		if ($tbl_instructors->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_instructors->CancelMessage;
			$tbl_instructors->CancelMessage = "";
		} else {
			$_SESSION[EW_SESSION_MESSAGE] = "Insert cancelled";
		}
		$AddRow = FALSE;
	}
	if ($AddRow) {
		$tbl_instructors->i_instructorid->setDbValue($conn->Insert_ID());
		$rsnew['i_instructorid'] =& $tbl_instructors->i_instructorid->DbValue;

		// Call Row Inserted event
		$tbl_instructors->Row_Inserted($rsnew);
	}
	return $AddRow;
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
