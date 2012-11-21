<?php
define("EW_PAGE_ID", "search", TRUE); // Page ID
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
<?php include "tbl_instructorsinfo.php" ?>
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

// Get action
$tbl_students->CurrentAction = @$_POST["a_search"];
switch ($tbl_students->CurrentAction) {
	case "S": // Get Search Criteria

		// Build search string for advanced search, remove blank field
		$sSrchStr = BuildAdvancedSearch();
		if ($sSrchStr <> "") {
			Page_Terminate("tbl_studentslist.php?" . $sSrchStr); // Go to list page
		}
		break;
	default: // Restore search settings
		LoadAdvancedSearch();
}

// Render row for search
$tbl_students->RowType = EW_ROWTYPE_SEARCH;
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
		elm = fobj.elements["x" + infix + "_s_studentid"];
		if (elm && !ew_CheckInteger(elm.value)) {
			if (!ew_OnError(elm, "Incorrect integer - s studentid"))
				return false; 
		}
		elm = fobj.elements["x" + infix + "_i_instructid"];
		if (elm && !ew_CheckInteger(elm.value)) {
			if (!ew_OnError(elm, "Incorrect integer - i instructid"))
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
<p><span class="edge">Student Search <br>
    <br>
    <a href="tbl_studentslist.php">Back to Student List </a></span></p>
<form name="ftbl_studentssearch" id="ftbl_studentssearch" action="tbl_studentssrch.php" method="post" onSubmit="return ew_ValidateForm(this);">
<p>
<input type="hidden" name="a_search" id="a_search" value="S">
<table class="ewTable">
	<tr class="ewTableRow">
		<td class="ewTableHeader">First Name</td>
		<td<?php echo $tbl_students->s_first_name->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_s_first_name" id="z_s_first_name" value="LIKE"></span></td>
		<td<?php echo $tbl_students->s_first_name->CellAttributes() ?>><span class="edge">
<input type="text" name="x_s_first_name" id="x_s_first_name" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_first_name->EditValue ?>"<?php echo $tbl_students->s_first_name->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Last Name</td>
		<td<?php echo $tbl_students->s_last_name->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_s_last_name" id="z_s_last_name" value="LIKE"></span></td>
		<td<?php echo $tbl_students->s_last_name->CellAttributes() ?>><span class="edge">
<input type="text" name="x_s_last_name" id="x_s_last_name" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_last_name->EditValue ?>"<?php echo $tbl_students->s_last_name->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Middle Name</td>
		<td<?php echo $tbl_students->s_middle_name->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_s_middle_name" id="z_s_middle_name" value="LIKE"></span></td>
		<td<?php echo $tbl_students->s_middle_name->CellAttributes() ?>><span class="edge">
<input type="text" name="x_s_middle_name" id="x_s_middle_name" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_middle_name->EditValue ?>"<?php echo $tbl_students->s_middle_name->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">E-mail</td>
		<td<?php echo $tbl_students->s_student_email->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_s_student_email" id="z_s_student_email" value="LIKE"></span></td>
		<td<?php echo $tbl_students->s_student_email->CellAttributes() ?>><span class="edge">
<input type="text" name="x_s_student_email" id="x_s_student_email" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_student_email->EditValue ?>"<?php echo $tbl_students->s_student_email->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Graduation Year</td>
		<td<?php echo $tbl_students->s_graduation_year->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_s_graduation_year" id="z_s_graduation_year" value="LIKE"></span></td>
		<td<?php echo $tbl_students->s_graduation_year->CellAttributes() ?>><span class="edge">
<input type="text" name="x_s_graduation_year" id="x_s_graduation_year" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_graduation_year->EditValue ?>"<?php echo $tbl_students->s_graduation_year->EditAttributes() ?>>
</span></td>
	</tr>
	<tr class="ewTableRow">
		<td class="ewTableHeader">Username</td>
		<td<?php echo $tbl_students->s_usrname->CellAttributes() ?>><span class="ewSearchOpr">contains<input type="hidden" name="z_s_usrname" id="z_s_usrname" value="LIKE"></span></td>
		<td<?php echo $tbl_students->s_usrname->CellAttributes() ?>><span class="edge">
<input type="text" name="x_s_usrname" id="x_s_usrname" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_usrname->EditValue ?>"<?php echo $tbl_students->s_usrname->EditAttributes() ?>>
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
	global $tbl_students;
	$sSrchUrl = "";

	// Field s_studentid
	BuildSearchUrl($sSrchUrl, $tbl_students->s_studentid, @$_POST["x_s_studentid"], @$_POST["z_s_studentid"], @$_POST["v_s_studentid"], @$_POST["y_s_studentid"], @$_POST["w_s_studentid"]);

	// Field s_first_name
	BuildSearchUrl($sSrchUrl, $tbl_students->s_first_name, @$_POST["x_s_first_name"], @$_POST["z_s_first_name"], @$_POST["v_s_first_name"], @$_POST["y_s_first_name"], @$_POST["w_s_first_name"]);

	// Field s_last_name
	BuildSearchUrl($sSrchUrl, $tbl_students->s_last_name, @$_POST["x_s_last_name"], @$_POST["z_s_last_name"], @$_POST["v_s_last_name"], @$_POST["y_s_last_name"], @$_POST["w_s_last_name"]);

	// Field s_middle_name
	BuildSearchUrl($sSrchUrl, $tbl_students->s_middle_name, @$_POST["x_s_middle_name"], @$_POST["z_s_middle_name"], @$_POST["v_s_middle_name"], @$_POST["y_s_middle_name"], @$_POST["w_s_middle_name"]);

	// Field s_address
	BuildSearchUrl($sSrchUrl, $tbl_students->s_address, @$_POST["x_s_address"], @$_POST["z_s_address"], @$_POST["v_s_address"], @$_POST["y_s_address"], @$_POST["w_s_address"]);

	// Field s_city
	BuildSearchUrl($sSrchUrl, $tbl_students->s_city, @$_POST["x_s_city"], @$_POST["z_s_city"], @$_POST["v_s_city"], @$_POST["y_s_city"], @$_POST["w_s_city"]);

	// Field s_postal_code
	BuildSearchUrl($sSrchUrl, $tbl_students->s_postal_code, @$_POST["x_s_postal_code"], @$_POST["z_s_postal_code"], @$_POST["v_s_postal_code"], @$_POST["y_s_postal_code"], @$_POST["w_s_postal_code"]);

	// Field s_state
	BuildSearchUrl($sSrchUrl, $tbl_students->s_state, @$_POST["x_s_state"], @$_POST["z_s_state"], @$_POST["v_s_state"], @$_POST["y_s_state"], @$_POST["w_s_state"]);

	// Field s_country
	BuildSearchUrl($sSrchUrl, $tbl_students->s_country, @$_POST["x_s_country"], @$_POST["z_s_country"], @$_POST["v_s_country"], @$_POST["y_s_country"], @$_POST["w_s_country"]);

	// Field s_home_phone
	BuildSearchUrl($sSrchUrl, $tbl_students->s_home_phone, @$_POST["x_s_home_phone"], @$_POST["z_s_home_phone"], @$_POST["v_s_home_phone"], @$_POST["y_s_home_phone"], @$_POST["w_s_home_phone"]);

	// Field s_student_mobile
	BuildSearchUrl($sSrchUrl, $tbl_students->s_student_mobile, @$_POST["x_s_student_mobile"], @$_POST["z_s_student_mobile"], @$_POST["v_s_student_mobile"], @$_POST["y_s_student_mobile"], @$_POST["w_s_student_mobile"]);

	// Field s_student_email
	BuildSearchUrl($sSrchUrl, $tbl_students->s_student_email, @$_POST["x_s_student_email"], @$_POST["z_s_student_email"], @$_POST["v_s_student_email"], @$_POST["y_s_student_email"], @$_POST["w_s_student_email"]);

	// Field s_parent_name
	BuildSearchUrl($sSrchUrl, $tbl_students->s_parent_name, @$_POST["x_s_parent_name"], @$_POST["z_s_parent_name"], @$_POST["v_s_parent_name"], @$_POST["y_s_parent_name"], @$_POST["w_s_parent_name"]);

	// Field s_parent_mobile
	BuildSearchUrl($sSrchUrl, $tbl_students->s_parent_mobile, @$_POST["x_s_parent_mobile"], @$_POST["z_s_parent_mobile"], @$_POST["v_s_parent_mobile"], @$_POST["y_s_parent_mobile"], @$_POST["w_s_parent_mobile"]);

	// Field s_parent_email
	BuildSearchUrl($sSrchUrl, $tbl_students->s_parent_email, @$_POST["x_s_parent_email"], @$_POST["z_s_parent_email"], @$_POST["v_s_parent_email"], @$_POST["y_s_parent_email"], @$_POST["w_s_parent_email"]);

	// Field s_school
	BuildSearchUrl($sSrchUrl, $tbl_students->s_school, @$_POST["x_s_school"], @$_POST["z_s_school"], @$_POST["v_s_school"], @$_POST["y_s_school"], @$_POST["w_s_school"]);

	// Field s_graduation_year
	BuildSearchUrl($sSrchUrl, $tbl_students->s_graduation_year, @$_POST["x_s_graduation_year"], @$_POST["z_s_graduation_year"], @$_POST["v_s_graduation_year"], @$_POST["y_s_graduation_year"], @$_POST["w_s_graduation_year"]);

	// Field s_usrname
	BuildSearchUrl($sSrchUrl, $tbl_students->s_usrname, @$_POST["x_s_usrname"], @$_POST["z_s_usrname"], @$_POST["v_s_usrname"], @$_POST["y_s_usrname"], @$_POST["w_s_usrname"]);

	// Field s_pwd
	BuildSearchUrl($sSrchUrl, $tbl_students->s_pwd, @$_POST["x_s_pwd"], @$_POST["z_s_pwd"], @$_POST["v_s_pwd"], @$_POST["y_s_pwd"], @$_POST["w_s_pwd"]);

	// Field i_instructid
	BuildSearchUrl($sSrchUrl, $tbl_students->i_instructid, @$_POST["x_i_instructid"], @$_POST["z_i_instructid"], @$_POST["v_i_instructid"], @$_POST["y_i_instructid"], @$_POST["w_i_instructid"]);

	// Field g_grpid
	BuildSearchUrl($sSrchUrl, $tbl_students->g_grpid, @$_POST["x_g_grpid"], @$_POST["z_g_grpid"], @$_POST["v_g_grpid"], @$_POST["y_g_grpid"], @$_POST["w_g_grpid"]);
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
	global $conn, $Security, $tbl_students;

	// Call Row Rendering event
	$tbl_students->Row_Rendering();

	// Common render codes for all row types
	if ($tbl_students->RowType == EW_ROWTYPE_VIEW) { // View row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_SEARCH) { // Search row

		// s_studentid
		$tbl_students->s_studentid->EditCustomAttributes = "";
		$tbl_students->s_studentid->EditValue = ew_HtmlEncode($tbl_students->s_studentid->AdvancedSearch->SearchValue);

		// s_first_name
		$tbl_students->s_first_name->EditCustomAttributes = "";
		$tbl_students->s_first_name->EditValue = ew_HtmlEncode($tbl_students->s_first_name->AdvancedSearch->SearchValue);

		// s_last_name
		$tbl_students->s_last_name->EditCustomAttributes = "";
		$tbl_students->s_last_name->EditValue = ew_HtmlEncode($tbl_students->s_last_name->AdvancedSearch->SearchValue);

		// s_middle_name
		$tbl_students->s_middle_name->EditCustomAttributes = "";
		$tbl_students->s_middle_name->EditValue = ew_HtmlEncode($tbl_students->s_middle_name->AdvancedSearch->SearchValue);

		// s_address
		$tbl_students->s_address->EditCustomAttributes = "";
		$tbl_students->s_address->EditValue = ew_HtmlEncode($tbl_students->s_address->AdvancedSearch->SearchValue);

		// s_city
		$tbl_students->s_city->EditCustomAttributes = "";
		$tbl_students->s_city->EditValue = ew_HtmlEncode($tbl_students->s_city->AdvancedSearch->SearchValue);

		// s_postal_code
		$tbl_students->s_postal_code->EditCustomAttributes = "";
		$tbl_students->s_postal_code->EditValue = ew_HtmlEncode($tbl_students->s_postal_code->AdvancedSearch->SearchValue);

		// s_state
		$tbl_students->s_state->EditCustomAttributes = "";
		$tbl_students->s_state->EditValue = ew_HtmlEncode($tbl_students->s_state->AdvancedSearch->SearchValue);

		// s_country
		$tbl_students->s_country->EditCustomAttributes = "";
		$tbl_students->s_country->EditValue = ew_HtmlEncode($tbl_students->s_country->AdvancedSearch->SearchValue);

		// s_home_phone
		$tbl_students->s_home_phone->EditCustomAttributes = "";
		$tbl_students->s_home_phone->EditValue = ew_HtmlEncode($tbl_students->s_home_phone->AdvancedSearch->SearchValue);

		// s_student_mobile
		$tbl_students->s_student_mobile->EditCustomAttributes = "";
		$tbl_students->s_student_mobile->EditValue = ew_HtmlEncode($tbl_students->s_student_mobile->AdvancedSearch->SearchValue);

		// s_student_email
		$tbl_students->s_student_email->EditCustomAttributes = "";
		$tbl_students->s_student_email->EditValue = ew_HtmlEncode($tbl_students->s_student_email->AdvancedSearch->SearchValue);

		// s_parent_name
		$tbl_students->s_parent_name->EditCustomAttributes = "";
		$tbl_students->s_parent_name->EditValue = ew_HtmlEncode($tbl_students->s_parent_name->AdvancedSearch->SearchValue);

		// s_parent_mobile
		$tbl_students->s_parent_mobile->EditCustomAttributes = "";
		$tbl_students->s_parent_mobile->EditValue = ew_HtmlEncode($tbl_students->s_parent_mobile->AdvancedSearch->SearchValue);

		// s_parent_email
		$tbl_students->s_parent_email->EditCustomAttributes = "";
		$tbl_students->s_parent_email->EditValue = ew_HtmlEncode($tbl_students->s_parent_email->AdvancedSearch->SearchValue);

		// s_school
		$tbl_students->s_school->EditCustomAttributes = "";
		$tbl_students->s_school->EditValue = ew_HtmlEncode($tbl_students->s_school->AdvancedSearch->SearchValue);

		// s_graduation_year
		$tbl_students->s_graduation_year->EditCustomAttributes = "";
		$tbl_students->s_graduation_year->EditValue = ew_HtmlEncode($tbl_students->s_graduation_year->AdvancedSearch->SearchValue);

		// s_usrname
		$tbl_students->s_usrname->EditCustomAttributes = "";
		$tbl_students->s_usrname->EditValue = ew_HtmlEncode($tbl_students->s_usrname->AdvancedSearch->SearchValue);

		// s_pwd
		$tbl_students->s_pwd->EditCustomAttributes = "";
		$tbl_students->s_pwd->EditValue = ew_HtmlEncode($tbl_students->s_pwd->AdvancedSearch->SearchValue);

		// i_instructid
		$tbl_students->i_instructid->EditCustomAttributes = "";
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
		$sSqlWrk = "SELECT `i_instructorid`, `i_instructorid` FROM `tbl_instructors`";
		$sWhereWrk = "`i_instructorid` = " . $Security->CurrentUserID() . "";
		$sWhereWrk = $tbl_students->AddParentUserIDFilter($sWhereWrk, "`i_instructorid`", $Security->CurrentUserID());
		$sSqlWrk .= " WHERE (" . $sWhereWrk . ")";
		$rswrk = $conn->Execute($sSqlWrk);
		$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
		if ($rswrk) $rswrk->Close();
		$tbl_students->i_instructid->EditValue = $arwrk;
		} else {
		$tbl_students->i_instructid->EditValue = ew_HtmlEncode($tbl_students->i_instructid->AdvancedSearch->SearchValue);
		}

		// g_grpid
		$tbl_students->g_grpid->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array("-1", "Administrator");
		$arwrk[] = array("0", "Default");
		$arwrk[] = array("1", "Student");
		$arwrk[] = array("2", "Instructor");
		array_unshift($arwrk, array("", "Please Select"));
		$tbl_students->g_grpid->EditValue = $arwrk;
	}

	// Call Row Rendered event
	$tbl_students->Row_Rendered();
}
?>
<?php

// Load advanced search
function LoadAdvancedSearch() {
	global $tbl_students;
	$tbl_students->s_studentid->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_studentid");
	$tbl_students->s_first_name->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_first_name");
	$tbl_students->s_last_name->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_last_name");
	$tbl_students->s_middle_name->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_middle_name");
	$tbl_students->s_address->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_address");
	$tbl_students->s_city->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_city");
	$tbl_students->s_postal_code->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_postal_code");
	$tbl_students->s_state->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_state");
	$tbl_students->s_country->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_country");
	$tbl_students->s_home_phone->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_home_phone");
	$tbl_students->s_student_mobile->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_student_mobile");
	$tbl_students->s_student_email->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_student_email");
	$tbl_students->s_parent_name->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_parent_name");
	$tbl_students->s_parent_mobile->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_parent_mobile");
	$tbl_students->s_parent_email->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_parent_email");
	$tbl_students->s_school->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_school");
	$tbl_students->s_graduation_year->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_graduation_year");
	$tbl_students->s_usrname->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_usrname");
	$tbl_students->s_pwd->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_s_pwd");
	$tbl_students->i_instructid->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_i_instructid");
	$tbl_students->g_grpid->AdvancedSearch->SearchValue = $tbl_students->getAdvancedSearch("x_g_grpid");
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
