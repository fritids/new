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

// Get search criteria for advanced search
$sSrchAdvanced = AdvancedSearchWhere();

// Get basic search criteria
$sSrchBasic = BasicSearchWhere();

// Build search criteria
if ($sSrchAdvanced <> "") {
	if ($sSrchWhere <> "") $sSrchWhere .= " AND ";
	$sSrchWhere .= "(" . $sSrchAdvanced . ")";
}
if ($sSrchBasic <> "") {
	if ($sSrchWhere <> "") $sSrchWhere .= " AND ";
	$sSrchWhere .= "(" . $sSrchBasic . ")";
}

// Save search criteria
if ($sSrchWhere <> "") {
	if ($sSrchBasic == "") ResetBasicSearchParms();
	if ($sSrchAdvanced == "") ResetAdvancedSearchParms();
	$tbl_instructors->setSearchWhere($sSrchWhere); // Save to Session
	$nStartRec = 1; // Reset start record counter
	$tbl_instructors->setStartRecordNumber($nStartRec);
} else {
	RestoreSearchParms();
}

// Build filter
$sFilter = "";
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
<p><span class="edge" style="white-space: nowrap;"> Instructors</span></p>
<?php if ($tbl_instructors->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<form name="ftbl_instructorslistsrch" id="ftbl_instructorslistsrch" action="tbl_instructorslist.php" >
<table class="ewBasicSearch">
	<tr>
		<td><span class="edge">
			<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($tbl_instructors->getBasicSearchKeyword()) ?>">
			<input type="Submit" name="Submit" id="Submit" value="Search">
			&nbsp;
			<a href="tbl_instructorslist.php?cmd=reset">Show all</a>&nbsp;
			<a href="tbl_instructorssrch.php">Advanced Search</a>&nbsp;
		</span></td>
	</tr>
	<tr>
	<td><span class="edge"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="" <?php if ($tbl_instructors->getBasicSearchType() == "") { ?>checked<?php } ?>>Exact phrase&nbsp;&nbsp;<input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND" <?php if ($tbl_instructors->getBasicSearchType() == "AND") { ?>checked<?php } ?>>All words&nbsp;&nbsp;<input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR" <?php if ($tbl_instructors->getBasicSearchType() == "OR") { ?>checked<?php } ?>>Any word</span></td>
	</tr>
</table>
</form>
<?php } ?>
<?php } ?>
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form method="post" name="ftbl_instructorslist" id="ftbl_instructorslist">
<?php if ($tbl_instructors->Export == "") { ?>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?><br />
<table id="ewlistmain" class="ewTable">
<?php
	$OptionCnt = 0;
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // view
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // edit
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // delete
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // detail
}
?>
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td width="150" valign="top">
<?php if ($tbl_instructors->Export <> "") { ?>
First Name
<?php } else { ?>
	First Name<?php if ($tbl_instructors->i_first_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_first_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="150" valign="top">
<?php if ($tbl_instructors->Export <> "") { ?>
Last Name
<?php } else { ?>
	Last Name<?php if ($tbl_instructors->i_last_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_last_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="150" valign="top">
<?php if ($tbl_instructors->Export <> "") { ?>
Username
<?php } else { ?>
	Username<?php if ($tbl_instructors->i_uname->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_instructors->i_uname->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
	</tr>
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
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;

	// Init row class and style
	$tbl_instructors->CssClass = "ewTableRow";
	$tbl_instructors->CssStyle = "";

	// Init row event
	$tbl_instructors->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
	LoadRowValues($rs); // Load row values
	$tbl_instructors->RowType = EW_ROWTYPE_VIEW; // Render view
	RenderRow();
?>
	<!-- Table body -->
	<tr<?php echo $tbl_instructors->DisplayAttributes() ?>>
		<!-- i_first_name -->
		<td width="150"<?php echo $tbl_instructors->i_first_name->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_first_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_first_name->ViewValue ?></div></td>
		<!-- i_last_name -->
		<td width="150"<?php echo $tbl_instructors->i_last_name->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_last_name->ViewAttributes() ?>><?php echo $tbl_instructors->i_last_name->ViewValue ?></div></td>
		<!-- i_email -->
		<!-- i_mobile -->
		<!-- i_uname -->
		<td width="150"<?php echo $tbl_instructors->i_uname->CellAttributes() ?>>
<div<?php echo $tbl_instructors->i_uname->ViewAttributes() ?>><?php echo $tbl_instructors->i_uname->ViewValue ?></div></td>
<?php if ($tbl_instructors->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<td nowrap><span class="edge">
<a href="<?php echo $tbl_instructors->ViewUrl() ?>">View</a>
</span></td>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<td nowrap><span class="edge">
<a href="tbl_studentslist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_instructors&i_instructorid=<?php echo urlencode(strval($tbl_instructors->i_instructorid->CurrentValue)) ?>">Students</a>
</span></td>
<?php } ?>
<?php } ?>
	</tr>
<?php
	}
	$rs->MoveNext();
}
?>
</table>
<?php if ($tbl_instructors->Export == "") { ?>
<?php } ?>
<?php } ?>
</form>
<table>
  <tr>
    <td><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <a href="tbl_instructorsadd.php">Add</a>&nbsp;&nbsp;
      <?php } ?>
    </span></td>
  </tr>
</table>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($tbl_instructors->Export == "") { ?>
<form action="tbl_instructorslist.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap>
<span class="edge">
<?php if (!isset($Pager)) $Pager = new cNumericPager($nStartRec, $nDisplayRecs, $nTotalRecs, $nRecRange) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<a href="tbl_instructorslist.php?start=<?php echo $Pager->FirstButton->Start ?>"><b>First</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<a href="tbl_instructorslist.php?start=<?php echo $Pager->PrevButton->Start ?>"><b>Previous</b></a>&nbsp;
	<?php } ?>
	<?php foreach ($Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="tbl_instructorslist.php?start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($Pager->NextButton->Enabled) { ?>
	<a href="tbl_instructorslist.php?start=<?php echo $Pager->NextButton->Start ?>"><b>Next</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->LastButton->Enabled) { ?>
	<a href="tbl_instructorslist.php?start=<?php echo $Pager->LastButton->Start ?>"><b>Last</b></a>&nbsp;
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

// Return Advanced Search Where based on QueryString parameters
function AdvancedSearchWhere() {
	global $Security, $tbl_instructors;
	$sWhere = "";

	// Field i_first_name
	BuildSearchSql($sWhere, $tbl_instructors->i_first_name, @$_GET["x_i_first_name"], @$_GET["z_i_first_name"], @$_GET["v_i_first_name"], @$_GET["y_i_first_name"], @$_GET["w_i_first_name"]);

	// Field i_last_name
	BuildSearchSql($sWhere, $tbl_instructors->i_last_name, @$_GET["x_i_last_name"], @$_GET["z_i_last_name"], @$_GET["v_i_last_name"], @$_GET["y_i_last_name"], @$_GET["w_i_last_name"]);

	// Field i_email
	BuildSearchSql($sWhere, $tbl_instructors->i_email, @$_GET["x_i_email"], @$_GET["z_i_email"], @$_GET["v_i_email"], @$_GET["y_i_email"], @$_GET["w_i_email"]);

	// Field i_mobile
	BuildSearchSql($sWhere, $tbl_instructors->i_mobile, @$_GET["x_i_mobile"], @$_GET["z_i_mobile"], @$_GET["v_i_mobile"], @$_GET["y_i_mobile"], @$_GET["w_i_mobile"]);

	// Field i_uname
	BuildSearchSql($sWhere, $tbl_instructors->i_uname, @$_GET["x_i_uname"], @$_GET["z_i_uname"], @$_GET["v_i_uname"], @$_GET["y_i_uname"], @$_GET["w_i_uname"]);

	// Field i_pwd
	BuildSearchSql($sWhere, $tbl_instructors->i_pwd, @$_GET["x_i_pwd"], @$_GET["z_i_pwd"], @$_GET["v_i_pwd"], @$_GET["y_i_pwd"], @$_GET["w_i_pwd"]);

	//AdvancedSearchWhere = sWhere
	// Set up search parm

	if ($sWhere <> "") {

		// Field i_first_name
		SetSearchParm($tbl_instructors->i_first_name, @$_GET["x_i_first_name"], @$_GET["z_i_first_name"], @$_GET["v_i_first_name"], @$_GET["y_i_first_name"], @$_GET["w_i_first_name"]);

		// Field i_last_name
		SetSearchParm($tbl_instructors->i_last_name, @$_GET["x_i_last_name"], @$_GET["z_i_last_name"], @$_GET["v_i_last_name"], @$_GET["y_i_last_name"], @$_GET["w_i_last_name"]);

		// Field i_email
		SetSearchParm($tbl_instructors->i_email, @$_GET["x_i_email"], @$_GET["z_i_email"], @$_GET["v_i_email"], @$_GET["y_i_email"], @$_GET["w_i_email"]);

		// Field i_mobile
		SetSearchParm($tbl_instructors->i_mobile, @$_GET["x_i_mobile"], @$_GET["z_i_mobile"], @$_GET["v_i_mobile"], @$_GET["y_i_mobile"], @$_GET["w_i_mobile"]);

		// Field i_uname
		SetSearchParm($tbl_instructors->i_uname, @$_GET["x_i_uname"], @$_GET["z_i_uname"], @$_GET["v_i_uname"], @$_GET["y_i_uname"], @$_GET["w_i_uname"]);

		// Field i_pwd
		SetSearchParm($tbl_instructors->i_pwd, @$_GET["x_i_pwd"], @$_GET["z_i_pwd"], @$_GET["v_i_pwd"], @$_GET["y_i_pwd"], @$_GET["w_i_pwd"]);
	}
	return $sWhere;
}

// Build search sql
function BuildSearchSql(&$Where, &$Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2) {
	$sWrk = "";
	$FldParm = substr($Fld->FldVar, 2);
	$FldVal = ew_StripSlashes($FldVal);
	if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
	$FldVal2 = ew_StripSlashes($FldVal2);
	if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
	$FldOpr = strtoupper(trim($FldOpr));
	if ($FldOpr == "") $FldOpr = "=";
	$FldOpr2 = strtoupper(trim($FldOpr2));
	if ($FldOpr2 == "") $FldOpr2 = "=";
	if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
		if ($FldVal <> "") $FldVal = ($FldVal == "1") ? $Fld->TrueValue : $Fld->FalseValue;
		if ($FldVal2 <> "") $FldVal2 = ($FldVal2 == "1") ? $Fld->TrueValue : $Fld->FalseValue;
	} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
		if ($FldVal <> "") $FldVal = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		if ($FldVal2 <> "") $FldVal2 = ew_UnFormatDateTime($FldVal2, $Fld->FldDateTimeFormat);
	}
	if ($FldOpr == "BETWEEN") {
		$IsValidValue = (($Fld->FldDataType <> EW_DATATYPE_NUMBER) ||
			($Fld->FldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal) && is_numeric($FldVal2)));
		if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
			$sWrk = $Fld->FldExpression . " BETWEEN " . ew_QuotedValue($FldVal, $Fld->FldDataType) .
				" AND " . ew_QuotedValue($FldVal2, $Fld->FldDataType);
		}
	} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL") {
		$sWrk = $Fld->FldExpression . " " . $FldOpr;
	} else {
		$IsValidValue = (($Fld->FldDataType <> EW_DATATYPE_NUMBER) ||
			($Fld->FldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal)));
		if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $Fld->FldDataType)) {
			$sWrk = $Fld->FldExpression . SearchString($FldOpr, $FldVal, $Fld->FldDataType);
		}
		$IsValidValue = (($Fld->FldDataType <> EW_DATATYPE_NUMBER) ||
			($Fld->FldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal2)));
		if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $Fld->FldDataType)) {
			if ($sWrk <> "") {
				$sWrk .= " " . (($FldCond=="OR")?"OR":"AND") . " ";
			}
			$sWrk .= $Fld->FldExpression . SearchString($FldOpr2, $FldVal2, $Fld->FldDataType);
		}
	}
	if ($sWrk <> "") {
		if ($Where <> "") $Where .= " AND ";
		$Where .= "(" . $sWrk . ")";
	}
}

// Return search string
function SearchString($FldOpr, $FldVal, $FldType) {
	if ($FldOpr == "LIKE" || $FldOpr == "NOT LIKE") {
		return " " . $FldOpr . " " . ew_QuotedValue("%" . $FldVal . "%", $FldType);
	} elseif ($FldOpr == "STARTS WITH") {
		return " LIKE " . ew_QuotedValue($FldVal . "%", $FldType);
	} else {
		return " " . $FldOpr . " " . ew_QuotedValue($FldVal, $FldType);
	}
}

// Set search parm
function SetSearchParm($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2) {
	global $tbl_instructors;
	$FldParm = substr($Fld->FldVar, 2);
	$FldVal = ew_StripSlashes($FldVal);
	if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
	$FldVal2 = ew_StripSlashes($FldVal2);
	if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
	$tbl_instructors->setAdvancedSearch("x_" . $FldParm, $FldVal);
	$tbl_instructors->setAdvancedSearch("z_" . $FldParm, $FldOpr);
	$tbl_instructors->setAdvancedSearch("v_" . $FldParm, $FldCond);
	$tbl_instructors->setAdvancedSearch("y_" . $FldParm, $FldVal2);
	$tbl_instructors->setAdvancedSearch("w_" . $FldParm, $FldOpr2);
}

// Return Basic Search sql
function BasicSearchSQL($Keyword) {
	$sKeyword = ew_AdjustSql($Keyword);
	$sql = "";
	$sql .= "`i_first_name` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`i_last_name` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`i_email` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`i_mobile` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`i_uname` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`i_pwd` LIKE '%" . $sKeyword . "%' OR ";
	if (substr($sql, -4) == " OR ") $sql = substr($sql, 0, strlen($sql)-4);
	return $sql;
}

// Return Basic Search Where based on search keyword and type
function BasicSearchWhere() {
	global $Security, $tbl_instructors;
	$sSearchStr = "";
	$sSearchKeyword = ew_StripSlashes(@$_GET[EW_TABLE_BASIC_SEARCH]);
	$sSearchType = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	if ($sSearchKeyword <> "") {
		$sSearch = trim($sSearchKeyword);
		if ($sSearchType <> "") {
			while (strpos($sSearch, "  ") !== FALSE)
				$sSearch = str_replace("  ", " ", $sSearch);
			$arKeyword = explode(" ", trim($sSearch));
			foreach ($arKeyword as $sKeyword) {
				if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
				$sSearchStr .= "(" . BasicSearchSQL($sKeyword) . ")";
			}
		} else {
			$sSearchStr = BasicSearchSQL($sSearch);
		}
	}
	if ($sSearchKeyword <> "") {
		$tbl_instructors->setBasicSearchKeyword($sSearchKeyword);
		$tbl_instructors->setBasicSearchType($sSearchType);
	}
	return $sSearchStr;
}

// Clear all search parameters
function ResetSearchParms() {

	// Clear search where
	global $tbl_instructors;
	$sSrchWhere = "";
	$tbl_instructors->setSearchWhere($sSrchWhere);

	// Clear basic search parameters
	ResetBasicSearchParms();

	// Clear advanced search parameters
	ResetAdvancedSearchParms();
}

// Clear all basic search parameters
function ResetBasicSearchParms() {

	// Clear basic search parameters
	global $tbl_instructors;
	$tbl_instructors->setBasicSearchKeyword("");
	$tbl_instructors->setBasicSearchType("");
}

// Clear all advanced search parameters
function ResetAdvancedSearchParms() {

	// Clear advanced search parameters
	global $tbl_instructors;
	$tbl_instructors->setAdvancedSearch("x_i_first_name", "");
	$tbl_instructors->setAdvancedSearch("x_i_last_name", "");
	$tbl_instructors->setAdvancedSearch("x_i_email", "");
	$tbl_instructors->setAdvancedSearch("x_i_mobile", "");
	$tbl_instructors->setAdvancedSearch("x_i_uname", "");
	$tbl_instructors->setAdvancedSearch("x_i_pwd", "");
}

// Restore all search parameters
function RestoreSearchParms() {
	global $sSrchWhere, $tbl_instructors;
	$sSrchWhere = $tbl_instructors->getSearchWhere();

	// Restore advanced search settings
	RestoreAdvancedSearchParms();
}

// Restore all advanced search parameters
function RestoreAdvancedSearchParms() {

	// Restore advanced search parms
	global $tbl_instructors;
	 $tbl_instructors->i_first_name->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_first_name");
	 $tbl_instructors->i_last_name->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_last_name");
	 $tbl_instructors->i_email->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_email");
	 $tbl_instructors->i_mobile->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_mobile");
	 $tbl_instructors->i_uname->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_uname");
	 $tbl_instructors->i_pwd->AdvancedSearch->SearchValue = $tbl_instructors->getAdvancedSearch("x_i_pwd");
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

		// Reset search criteria
		if (strtolower($sCmd) == "reset" || strtolower($sCmd) == "resetall") {
			ResetSearchParms();
		}

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
	if ($tbl_instructors->RowType == EW_ROWTYPE_VIEW) { // View row

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
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_instructors->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_instructors->Row_Rendered();
}
?>
<?php

// Load advanced search
function LoadAdvancedSearch() {
	global $tbl_instructors;
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
