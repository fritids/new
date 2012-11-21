<?php
define("EW_PAGE_ID", "search", TRUE); // Page ID
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

// Get action
$tbl_instructors->CurrentAction = @$_POST["a_search"];
switch ($tbl_instructors->CurrentAction) {
	case "S": // Get Search Criteria

		// Build search string for advanced search, remove blank field
		$sSrchStr = BuildAdvancedSearch();
		if ($sSrchStr <> "") {
			Page_Terminate("tbl_instructorslist.php?" . $sSrchStr); // Go to list page
		}
		break;
	default: // Restore search settings
		LoadAdvancedSearch();
}

// Render row for search
$tbl_instructors->RowType = EW_ROWTYPE_SEARCH;
RenderRow();
?>
<?php include "header.php" ?>
<script type="text/javascript">
<!--
var EW_PAGE_ID = "search"; // Page id

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
<p><span class="edge"> Instructor Search <br>
  <br>
    <a href="tbl_instructorslist.php">Back to Instructor List</a></span></p>
<form name="ftbl_instructorssearch" id="ftbl_instructorssearch" action="tbl_instructorssrch.php" method="post" onSubmit="return ew_ValidateForm(this);">
<p>
<input type="hidden" name="a_search" id="a_search" value="S">
<table class="ewTable">
	<tr class="ewTableRow">
		<td class="ewTableHeader">First Name</td>
		<td<?php echo $tbl_instructors->i_first_name->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_i_first_name" id="z_i_first_name" value="LIKE"></span></td>
		<td<?php echo $tbl_instructors->i_first_name->CellAttributes() ?>><span class="edge">
<input type="text" name="x_i_first_name" id="x_i_first_name" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_first_name->EditValue ?>"<?php echo $tbl_instructors->i_first_name->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Last Name</td>
		<td<?php echo $tbl_instructors->i_last_name->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_i_last_name" id="z_i_last_name" value="LIKE"></span></td>
		<td<?php echo $tbl_instructors->i_last_name->CellAttributes() ?>><span class="edge">
<input type="text" name="x_i_last_name" id="x_i_last_name" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_last_name->EditValue ?>"<?php echo $tbl_instructors->i_last_name->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">E-mail</td>
		<td<?php echo $tbl_instructors->i_email->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_i_email" id="z_i_email" value="LIKE"></span></td>
		<td<?php echo $tbl_instructors->i_email->CellAttributes() ?>><span class="edge">
<input type="text" name="x_i_email" id="x_i_email" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_email->EditValue ?>"<?php echo $tbl_instructors->i_email->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Username</td>
		<td<?php echo $tbl_instructors->i_uname->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_i_uname" id="z_i_uname" value="LIKE"></span></td>
		<td<?php echo $tbl_instructors->i_uname->CellAttributes() ?>><span class="edge">
<input type="text" name="x_i_uname" id="x_i_uname" title="" size="15" maxlength="45" value="<?php echo $tbl_instructors->i_uname->EditValue ?>"<?php echo $tbl_instructors->i_uname->EditAttributes() ?>>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" id="Action" value="  Search  ">
<input type="button" name="Reset" id="Reset" value="   Reset   " onclick="ew_ClearForm(this.form);">
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

// Build advanced search
function BuildAdvancedSearch() {
	global $tbl_instructors;
	$sSrchUrl = "";

	// Field i_first_name
	BuildSearchUrl($sSrchUrl, $tbl_instructors->i_first_name, @$_POST["x_i_first_name"], @$_POST["z_i_first_name"], @$_POST["v_i_first_name"], @$_POST["y_i_first_name"], @$_POST["w_i_first_name"]);

	// Field i_last_name
	BuildSearchUrl($sSrchUrl, $tbl_instructors->i_last_name, @$_POST["x_i_last_name"], @$_POST["z_i_last_name"], @$_POST["v_i_last_name"], @$_POST["y_i_last_name"], @$_POST["w_i_last_name"]);

	// Field i_email
	BuildSearchUrl($sSrchUrl, $tbl_instructors->i_email, @$_POST["x_i_email"], @$_POST["z_i_email"], @$_POST["v_i_email"], @$_POST["y_i_email"], @$_POST["w_i_email"]);

	// Field i_mobile
	BuildSearchUrl($sSrchUrl, $tbl_instructors->i_mobile, @$_POST["x_i_mobile"], @$_POST["z_i_mobile"], @$_POST["v_i_mobile"], @$_POST["y_i_mobile"], @$_POST["w_i_mobile"]);

	// Field i_uname
	BuildSearchUrl($sSrchUrl, $tbl_instructors->i_uname, @$_POST["x_i_uname"], @$_POST["z_i_uname"], @$_POST["v_i_uname"], @$_POST["y_i_uname"], @$_POST["w_i_uname"]);

	// Field i_pwd
	BuildSearchUrl($sSrchUrl, $tbl_instructors->i_pwd, @$_POST["x_i_pwd"], @$_POST["z_i_pwd"], @$_POST["v_i_pwd"], @$_POST["y_i_pwd"], @$_POST["w_i_pwd"]);
	return $sSrchUrl;
}

// Function to build search URL
function BuildSearchUrl(&$Url, &$Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2) {
	$sWrk = "";
	$FldParm = substr($Fld->FldVar, 2);
	$FldVal = ew_StripSlashes($FldVal);
	if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
	$FldVal2 = ew_StripSlashes($FldVal2);
	if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
	$FldOpr = strtoupper(trim($FldOpr));
	if ($FldOpr == "BETWEEN") {
		$IsValidValue = ($Fld->FldDataType <> EW_DATATYPE_NUMBER) ||
			($Fld->FldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal) && is_numeric($FldVal2));
		if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
			$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
				"&y_" . $FldParm . "=" . urlencode($FldVal2) .
				"&z_" . $FldParm . "=" . urlencode($FldOpr);
		}
	} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL") {
		$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
			"&z_" . $FldParm . "=" . urlencode($FldOpr);
	} else {
		$IsValidValue = ($Fld->FldDataType <> EW_DATATYPE_NUMBER) ||
			($Fld->FldDataType = EW_DATATYPE_NUMBER && is_numeric($FldVal));
		if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $Fld->FldDataType)) {
			$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
				"&z_" . $FldParm . "=" . urlencode($FldOpr);
		}
		$IsValidValue = ($Fld->FldDataType <> EW_DATATYPE_NUMBER) ||
			($Fld->FldDataType = EW_DATATYPE_NUMBER && is_numeric($FldVal2));
		if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $Fld->FldDataType)) {
			if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
			$sWrk .= "&y_" . $FldParm . "=" . urlencode($FldVal2) .
				"&w_" . $FldParm . "=" . urlencode($FldOpr2);
		}
	}
	if ($sWrk <> "") {
		if ($Url <> "") $Url .= "&";
		$Url .= $sWrk;
	}
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_instructors;

	// Call Row Rendering event
	$tbl_instructors->Row_Rendering();

	// Common render codes for all row types
	if ($tbl_instructors->RowType == EW_ROWTYPE_VIEW) { // View row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_SEARCH) { // Search row

		// i_first_name
		$tbl_instructors->i_first_name->EditCustomAttributes = "";
		$tbl_instructors->i_first_name->EditValue = ew_HtmlEncode($tbl_instructors->i_first_name->AdvancedSearch->SearchValue);

		// i_last_name
		$tbl_instructors->i_last_name->EditCustomAttributes = "";
		$tbl_instructors->i_last_name->EditValue = ew_HtmlEncode($tbl_instructors->i_last_name->AdvancedSearch->SearchValue);

		// i_email
		$tbl_instructors->i_email->EditCustomAttributes = "";
		$tbl_instructors->i_email->EditValue = ew_HtmlEncode($tbl_instructors->i_email->AdvancedSearch->SearchValue);

		// i_mobile
		$tbl_instructors->i_mobile->EditCustomAttributes = "";
		$tbl_instructors->i_mobile->EditValue = ew_HtmlEncode($tbl_instructors->i_mobile->AdvancedSearch->SearchValue);

		// i_uname
		$tbl_instructors->i_uname->EditCustomAttributes = "";
		$tbl_instructors->i_uname->EditValue = ew_HtmlEncode($tbl_instructors->i_uname->AdvancedSearch->SearchValue);

		// i_pwd
		$tbl_instructors->i_pwd->EditCustomAttributes = "";
		$tbl_instructors->i_pwd->EditValue = ew_HtmlEncode($tbl_instructors->i_pwd->AdvancedSearch->SearchValue);
	}

	// Call Row Rendered event
	$tbl_instructors->Row_Rendered();
}
?>
<?php

// Load advanced search
function LoadAdvancedSearch() {
	global $tbl_instructors;
	$tbl_instructors->i_first_name->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_first_name");
	$tbl_instructors->i_last_name->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_last_name");
	$tbl_instructors->i_email->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_email");
	$tbl_instructors->i_mobile->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_mobile");
	$tbl_instructors->i_uname->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_uname");
	$tbl_instructors->i_pwd->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_pwd");
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
