<?php
define("EW_PAGE_ID", "add", TRUE); // Page ID
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
<?php include "tbl_aduserinfo.php" ?>
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

// Load key values from QueryString
$bCopy = TRUE;
if (@$_GET["s_studentid"] != "") {
  $tbl_students->s_studentid->setQueryStringValue($_GET["s_studentid"]);
} else {
  $bCopy = FALSE;
}
if (@$_GET["s_usrname"] != "") {
  $tbl_students->s_usrname->setQueryStringValue($_GET["s_usrname"]);
} else {
  $bCopy = FALSE;
}

// Create form object
$objForm = new cFormObj();

// Process form if post back
if (@$_POST["a_add"] <> "") {
  $tbl_students->CurrentAction = $_POST["a_add"]; // Get form action
  LoadFormValues(); // Load form values
} else { // Not post back
  if ($bCopy) {
    $tbl_students->CurrentAction = "C"; // Copy Record
  } else {
    $tbl_students->CurrentAction = "I"; // Display Blank Record
    LoadDefaultValues(); // Load default values
  }
}

// Perform action based on action code
switch ($tbl_students->CurrentAction) {
  case "I": // Blank record, no action required
		break;
  case "C": // Copy an existing record
   if (!LoadRow()) { // Load record based on key
      $_SESSION[EW_SESSION_MESSAGE] = "No records found"; // No record found
      Page_Terminate($tbl_students->getReturnUrl()); // Clean up and return
    }
		break;
  case "A": // ' Add new record
		$tbl_students->SendEmail = TRUE; // Send email on add success
    if (AddRow()) { // Add successful
      $_SESSION[EW_SESSION_MESSAGE] = "Add New Record Successful"; // Set up success message
      Page_Terminate($tbl_students->KeyUrl($tbl_students->getReturnUrl())); // Clean up and return
    } else {
      RestoreFormValues(); // Add failed, restore form values
    }
}

// Render row based on row type
$tbl_students->RowType = EW_ROWTYPE_ADD;  // Render add type
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
		elm = fobj.elements["x" + infix + "_i_instructid"];
		if (elm && !ew_HasValue(elm)) {
			if (!ew_OnError(elm, "Please enter required field - Instructor Name"))
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
<p><span class="edge">Add New Student<br>
  <br><a href="<?php echo $tbl_students->getReturnUrl() ?>">Go Back</a></span></p>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") { // Mesasge in Session, display
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
  $_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
}
?>
<form name="ftbl_studentsadd" id="ftbl_studentsadd" action="tbl_studentsadd.php" method="post" onSubmit="return ew_ValidateForm(this);">
<p>
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewTable">
  <tr class="ewTableRow">
    <td class="ewTableHeader">First Name<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->s_first_name->CellAttributes() ?>><span id="cb_x_s_first_name">
<input type="text" name="x_s_first_name" id="x_s_first_name" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_first_name->EditValue ?>"<?php echo $tbl_students->s_first_name->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Last Name<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->s_last_name->CellAttributes() ?>><span id="cb_x_s_last_name">
<input type="text" name="x_s_last_name" id="x_s_last_name" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_last_name->EditValue ?>"<?php echo $tbl_students->s_last_name->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Middle Name</td>
    <td<?php echo $tbl_students->s_middle_name->CellAttributes() ?>><span id="cb_x_s_middle_name">
<input type="text" name="x_s_middle_name" id="x_s_middle_name" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_middle_name->EditValue ?>"<?php echo $tbl_students->s_middle_name->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Address<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->s_address->CellAttributes() ?>><span id="cb_x_s_address">
<input type="text" name="x_s_address" id="x_s_address" title="" size="15" maxlength="125" value="<?php echo $tbl_students->s_address->EditValue ?>"<?php echo $tbl_students->s_address->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">City<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->s_city->CellAttributes() ?>><span id="cb_x_s_city">
<input type="text" name="x_s_city" id="x_s_city" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_city->EditValue ?>"<?php echo $tbl_students->s_city->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Postal Code</td>
    <td<?php echo $tbl_students->s_postal_code->CellAttributes() ?>><span id="cb_x_s_postal_code">
<input type="text" name="x_s_postal_code" id="x_s_postal_code" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_postal_code->EditValue ?>"<?php echo $tbl_students->s_postal_code->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">State</td>
    <td<?php echo $tbl_students->s_state->CellAttributes() ?>><span id="cb_x_s_state">
<input type="text" name="x_s_state" id="x_s_state" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_state->EditValue ?>"<?php echo $tbl_students->s_state->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Country<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->s_country->CellAttributes() ?>><span id="cb_x_s_country">
<input type="text" name="x_s_country" id="x_s_country" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_country->EditValue ?>"<?php echo $tbl_students->s_country->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Home Phone</td>
    <td<?php echo $tbl_students->s_home_phone->CellAttributes() ?>><span id="cb_x_s_home_phone">
<input type="text" name="x_s_home_phone" id="x_s_home_phone" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_home_phone->EditValue ?>"<?php echo $tbl_students->s_home_phone->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Mobile</td>
    <td<?php echo $tbl_students->s_student_mobile->CellAttributes() ?>><span id="cb_x_s_student_mobile">
<input type="text" name="x_s_student_mobile" id="x_s_student_mobile" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_student_mobile->EditValue ?>"<?php echo $tbl_students->s_student_mobile->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">E-mail</td>
    <td<?php echo $tbl_students->s_student_email->CellAttributes() ?>><span id="cb_x_s_student_email">
<input type="text" name="x_s_student_email" id="x_s_student_email" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_student_email->EditValue ?>"<?php echo $tbl_students->s_student_email->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Parent Name<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->s_parent_name->CellAttributes() ?>><span id="cb_x_s_parent_name">
<input type="text" name="x_s_parent_name" id="x_s_parent_name" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_parent_name->EditValue ?>"<?php echo $tbl_students->s_parent_name->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Parent Mobile</td>
    <td<?php echo $tbl_students->s_parent_mobile->CellAttributes() ?>><span id="cb_x_s_parent_mobile">
<input type="text" name="x_s_parent_mobile" id="x_s_parent_mobile" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_parent_mobile->EditValue ?>"<?php echo $tbl_students->s_parent_mobile->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Parent Email</td>
    <td<?php echo $tbl_students->s_parent_email->CellAttributes() ?>><span id="cb_x_s_parent_email">
<input type="text" name="x_s_parent_email" id="x_s_parent_email" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_parent_email->EditValue ?>"<?php echo $tbl_students->s_parent_email->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">School</td>
    <td<?php echo $tbl_students->s_school->CellAttributes() ?>><span id="cb_x_s_school">
<input type="text" name="x_s_school" id="x_s_school" title="" size="15" maxlength="125" value="<?php echo $tbl_students->s_school->EditValue ?>"<?php echo $tbl_students->s_school->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Graduation Year</td>
    <td<?php echo $tbl_students->s_graduation_year->CellAttributes() ?>><span id="cb_x_s_graduation_year">
<input type="text" name="x_s_graduation_year" id="x_s_graduation_year" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_graduation_year->EditValue ?>"<?php echo $tbl_students->s_graduation_year->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Username<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->s_usrname->CellAttributes() ?>><span id="cb_x_s_usrname">
<input type="text" name="x_s_usrname" id="x_s_usrname" title="" size="15" maxlength="45" value="<?php echo $tbl_students->s_usrname->EditValue ?>"<?php echo $tbl_students->s_usrname->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Password<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->s_pwd->CellAttributes() ?>><span id="cb_x_s_pwd">
<input type="password" name="x_s_pwd" id="x_s_pwd" title="" value="<?php echo $tbl_students->s_pwd->EditValue ?>" size="15" maxlength="45"<?php echo $tbl_students->s_pwd->EditAttributes() ?>>
</span></td>
  </tr>
  <tr class="ewTableRow">
    <td class="ewTableHeader">Instructor Name<span class='ewmsg'>&nbsp;*</span></td>
    <td<?php echo $tbl_students->i_instructid->CellAttributes() ?>><span id="cb_x_i_instructid">
<?php if ($tbl_students->i_instructid->getSessionValue() <> "") { ?>
<div<?php echo $tbl_students->i_instructid->ViewAttributes() ?>><?php echo $tbl_students->i_instructid->ViewValue ?></div>
<input type="hidden" id="x_i_instructid" name="x_i_instructid" value="<?php echo ew_HtmlEncode($tbl_students->i_instructid->CurrentValue) ?>">
<?php } else { ?>
<select id="x_i_instructid" name="x_i_instructid"<?php echo $tbl_students->i_instructid->EditAttributes() ?>>
<!--option value="">Please Select</option-->
<?php
if (is_array($tbl_students->i_instructid->EditValue)) {
	$arwrk = $tbl_students->i_instructid->EditValue;
	$rowswrk = count($arwrk);
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tbl_students->i_instructid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected" : "";	
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator($rowcntwrk) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
			}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `i_instructorid`, `i_first_name`, `i_last_name` FROM `tbl_instructors`";
$sSqlWrk = TEAencrypt($sSqlWrk, EW_RANDOM_KEY);
?>
<input type="hidden" name="s_x_i_instructid" id="s_x_i_instructid" value="<?php echo $sSqlWrk ?>"><input type="hidden" name="lc_x_i_instructid" id="lc_x_i_instructid" value="3"><input type="hidden" name="ld1_x_i_instructid" id="ld1_x_i_instructid" value="1"><input type="hidden" name="ld2_x_i_instructid" id="ld2_x_i_instructid" value="2"><input type="hidden" name="lft_x_i_instructid" id="lft_x_i_instructid" value="1">
<?php } ?>
</span></td>
  </tr>
</table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="    Add    ">
</form>
<script language="JavaScript">
<!--
var f = document.ftbl_studentsadd;
ew_AjaxUpdateOpt(f.x_i_instructid, f.x_i_instructid, false);

//-->
</script>
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
	global $tbl_students;
}
?>
<?php

// Load form values
function LoadFormValues() {

	// Load from form
	global $objForm, $tbl_students;
	$tbl_students->s_first_name->setFormValue($objForm->GetValue("x_s_first_name"));
	$tbl_students->s_last_name->setFormValue($objForm->GetValue("x_s_last_name"));
	$tbl_students->s_middle_name->setFormValue($objForm->GetValue("x_s_middle_name"));
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
	$tbl_students->i_instructid->setFormValue($objForm->GetValue("x_i_instructid"));
}

// Restore form values
function RestoreFormValues() {
	global $tbl_students;
	$tbl_students->s_first_name->CurrentValue = $tbl_students->s_first_name->FormValue;
	$tbl_students->s_last_name->CurrentValue = $tbl_students->s_last_name->FormValue;
	$tbl_students->s_middle_name->CurrentValue = $tbl_students->s_middle_name->FormValue;
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
	$tbl_students->i_instructid->CurrentValue = $tbl_students->i_instructid->FormValue;
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
	$tbl_students->s_last_name->setDbValue($rs->fields('s_last_name'));
	$tbl_students->s_middle_name->setDbValue($rs->fields('s_middle_name'));
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
}
?>
<?php

// Render row values based on field settings
function RenderRow() {
	global $conn, $Security, $tbl_students;

	// Call Row Rendering event
	$tbl_students->Row_Rendering();

	// Common render codes for all row types
	// s_first_name

	$tbl_students->s_first_name->CellCssStyle = "";
	$tbl_students->s_first_name->CellCssClass = "";

	// s_last_name
	$tbl_students->s_last_name->CellCssStyle = "";
	$tbl_students->s_last_name->CellCssClass = "";

	// s_middle_name
	$tbl_students->s_middle_name->CellCssStyle = "";
	$tbl_students->s_middle_name->CellCssClass = "";

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

	// i_instructid
	$tbl_students->i_instructid->CellCssStyle = "";
	$tbl_students->i_instructid->CellCssClass = "";
	if ($tbl_students->RowType == EW_ROWTYPE_VIEW) { // View row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_ADD) { // Add row

		// s_first_name
		$tbl_students->s_first_name->EditCustomAttributes = "";
		$tbl_students->s_first_name->EditValue = ew_HtmlEncode($tbl_students->s_first_name->CurrentValue);

		// s_last_name
		$tbl_students->s_last_name->EditCustomAttributes = "";
		$tbl_students->s_last_name->EditValue = ew_HtmlEncode($tbl_students->s_last_name->CurrentValue);

		// s_middle_name
		$tbl_students->s_middle_name->EditCustomAttributes = "";
		$tbl_students->s_middle_name->EditValue = ew_HtmlEncode($tbl_students->s_middle_name->CurrentValue);

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
		$tbl_students->s_usrname->EditValue = ew_HtmlEncode($tbl_students->s_usrname->CurrentValue);

		// s_pwd
		$tbl_students->s_pwd->EditCustomAttributes = "";
		$tbl_students->s_pwd->EditValue = ew_HtmlEncode($tbl_students->s_pwd->CurrentValue);

		// i_instructid
		$tbl_students->i_instructid->EditCustomAttributes = "";
		if ($tbl_students->i_instructid->getSessionValue() <> "") {
			$tbl_students->i_instructid->CurrentValue = $tbl_students->i_instructid->getSessionValue();
		if (!is_null($tbl_students->i_instructid->CurrentValue)) {
			$sSqlWrk = "SELECT `i_first_name`, `i_last_name` FROM `tbl_instructors` WHERE `i_instructorid` = " . ew_AdjustSql($tbl_students->i_instructid->CurrentValue) . "";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk) {
				if (!$rswrk->EOF) {
					$tbl_students->i_instructid->ViewValue = $rswrk->fields('i_first_name');
					$tbl_students->i_instructid->ViewValue .= ew_ValueSeparator(0) . $rswrk->fields('i_last_name');
				}
				$rswrk->Close();
			} else {
				$tbl_students->i_instructid->ViewValue = $tbl_students->i_instructid->CurrentValue;
			}
		} else {
			$tbl_students->i_instructid->ViewValue = NULL;
		}
		$tbl_students->i_instructid->CssStyle = "";
		$tbl_students->i_instructid->CssClass = "";
		$tbl_students->i_instructid->ViewCustomAttributes = "";
		} else {
		$sSqlWrk = "SELECT `i_instructorid`, `i_first_name`, `i_last_name` FROM `tbl_instructors`";
		if (trim(strval($tbl_students->i_instructid->CurrentValue)) == "") {
			$sSqlWrk .= " WHERE 0=1";
		} else {
			$sSqlWrk .= " WHERE `i_instructorid` = " . ew_AdjustSql($tbl_students->i_instructid->CurrentValue) . "";
		}
		$rswrk = $conn->Execute($sSqlWrk);
		$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
		if ($rswrk) $rswrk->Close();
		array_unshift($arwrk, array("", "Please Select", ""));
		$tbl_students->i_instructid->EditValue = $arwrk;
		}
	} elseif ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_students->Row_Rendered();
}
?>
<?php

// Add record
function AddRow() {
	global $conn, $Security, $tbl_students;

	// Check for duplicate key
	$bCheckKey = TRUE;
	$sFilter = $tbl_students->SqlKeyFilter();
	if (trim(strval($tbl_students->s_studentid->CurrentValue)) == "") {
		$bCheckKey = FALSE;
	} else {
		$sFilter = str_replace("@s_studentid@", ew_AdjustSql($tbl_students->s_studentid->CurrentValue), $sFilter); // Replace key value
	}
	if (!is_numeric($tbl_students->s_studentid->CurrentValue)) {
		$bCheckKey = FALSE;
	}
	if (trim(strval($tbl_students->s_usrname->CurrentValue)) == "") {
		$bCheckKey = FALSE;
	} else {
		$sFilter = str_replace("@s_usrname@", ew_AdjustSql($tbl_students->s_usrname->CurrentValue), $sFilter); // Replace key value
	}
	if ($bCheckKey) {
		$rsChk = $tbl_students->LoadRs($sFilter);
		if ($rsChk && !$rsChk->EOF) {
			$_SESSION[EW_SESSION_MESSAGE] = "Duplicate value for primary key";
			$rsChk->Close();
			return FALSE;
		}
	}
	$rsnew = array();

	// Field s_first_name
	$tbl_students->s_first_name->SetDbValueDef($tbl_students->s_first_name->CurrentValue, "");
	$rsnew['s_first_name'] =& $tbl_students->s_first_name->DbValue;

	// Field s_last_name
	$tbl_students->s_last_name->SetDbValueDef($tbl_students->s_last_name->CurrentValue, "");
	$rsnew['s_last_name'] =& $tbl_students->s_last_name->DbValue;

	// Field s_middle_name
	$tbl_students->s_middle_name->SetDbValueDef($tbl_students->s_middle_name->CurrentValue, NULL);
	$rsnew['s_middle_name'] =& $tbl_students->s_middle_name->DbValue;

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
	$tbl_students->s_usrname->SetDbValueDef($tbl_students->s_usrname->CurrentValue, "");
	$rsnew['s_usrname'] =& $tbl_students->s_usrname->DbValue;

	// Field s_pwd
	$tbl_students->s_pwd->SetDbValueDef($tbl_students->s_pwd->CurrentValue, "");
	$rsnew['s_pwd'] =& $tbl_students->s_pwd->DbValue;

	// Field i_instructid
	$tbl_students->i_instructid->SetDbValueDef($tbl_students->i_instructid->CurrentValue, 0);
	$rsnew['i_instructid'] =& $tbl_students->i_instructid->DbValue;

	// Call Row Inserting event
	$bInsertRow = $tbl_students->Row_Inserting($rsnew);
	if ($bInsertRow) {
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$AddRow = $conn->Execute($tbl_students->InsertSQL($rsnew));
		$conn->raiseErrorFn = '';
	} else {
		if ($tbl_students->CancelMessage <> "") {
			$_SESSION[EW_SESSION_MESSAGE] = $tbl_students->CancelMessage;
			$tbl_students->CancelMessage = "";
		} else {
			$_SESSION[EW_SESSION_MESSAGE] = "Insert cancelled";
		}
		$AddRow = FALSE;
	}
	if ($AddRow) {
		$tbl_students->s_studentid->setDbValue($conn->Insert_ID());
		$rsnew['s_studentid'] =& $tbl_students->s_studentid->DbValue;

		// Call Row Inserted event
		$tbl_students->Row_Inserted($rsnew);
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
