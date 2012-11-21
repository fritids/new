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
	$tbl_students->setSearchWhere($sSrchWhere); // Save to Session
	$nStartRec = 1; // Reset start record counter
	$tbl_students->setStartRecordNumber($nStartRec);
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

// Load master record
if ($tbl_students->getMasterFilter() <> "" && $tbl_students->getCurrentMasterTable() == "tbl_instructors") {
	$rsmaster = $tbl_instructors->LoadRs($sDbMasterFilter);
	$bMasterRecordExists = ($rsmaster && !$rsmaster->EOF);
	if (!$bMasterRecordExists) {
		$tbl_students->setMasterFilter(""); // Clear master filter
		$tbl_students->setDetailFilter(""); // Clear detail filter
		$_SESSION[EW_SESSION_MESSAGE] = "No records found"; // Set no record found
		Page_Terminate("tbl_instructorslist.php"); // Return to caller
	} else {
		$tbl_instructors->LoadListRowValues($rsmaster);
		$tbl_instructors->RenderListRow();
		$rsmaster->Close();
	}
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
<?php if ($tbl_students->Export == "") { ?>
<?php
$sMasterReturnUrl = "tbl_instructorslist.php";
if ($tbl_students->getMasterFilter() <> "" && $tbl_students->getCurrentMasterTable() == "tbl_instructors") {
	if ($bMasterRecordExists) {
		if ($tbl_students->getCurrentMasterTable() == $tbl_students->TableVar) $sMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include "tbl_instructorsmaster.php" ?>
<?php
	}
}
?>
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
<p><span class="edge" style="white-space: nowrap;">Students</span></p>
<?php if ($tbl_students->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<form name="ftbl_studentslistsrch" id="ftbl_studentslistsrch" action="tbl_studentslist.php" >
<table class="ewBasicSearch">
	<tr>
		<td><span class="edge">
			<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($tbl_students->getBasicSearchKeyword()) ?>">
			<input type="Submit" name="Submit" id="Submit" value="Search">
			&nbsp;
			<a href="tbl_studentslist.php?cmd=reset">Show all</a>&nbsp;
			<a href="tbl_studentssrch.php">Advanced Search</a>&nbsp;
		</span></td>
	</tr>
	<tr>
	<td><span class="edge"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="" <?php if ($tbl_students->getBasicSearchType() == "") { ?>checked<?php } ?>>Exact phrase&nbsp;&nbsp;<input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND" <?php if ($tbl_students->getBasicSearchType() == "AND") { ?>checked<?php } ?>>All words&nbsp;&nbsp;<input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR" <?php if ($tbl_students->getBasicSearchType() == "OR") { ?>checked<?php } ?>>Any word</span></td>
	</tr>
</table>
</form>
<?php } ?>
<?php } ?><br />
<?php
if (@$_SESSION[EW_SESSION_MESSAGE] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION[EW_SESSION_MESSAGE] ?></span></p>
<?php
	$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message
}
?>
<form method="post" name="ftbl_studentslist" id="ftbl_studentslist">
<?php if ($tbl_students->Export == "") { ?>
<?php } ?>
<?php if ($nTotalRecs > 0) { ?>
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
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // detail
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // detail
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // detail
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // detail
}
if ($Security->IsLoggedIn()) {
	$OptionCnt++; // detail
}
?>
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td width="150" valign="top">
<?php if ($tbl_students->Export <> "") { ?>
First Name
<?php } else { ?>
	First Name<?php if ($tbl_students->s_first_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_first_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="150" valign="top">
<?php if ($tbl_students->Export <> "") { ?>
Last Name
<?php } else { ?>
	Last Name<?php if ($tbl_students->s_last_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_last_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="150" valign="top">
<?php if ($tbl_students->Export <> "") { ?>
Middle Name
<?php } else { ?>
	Middle Name<?php if ($tbl_students->s_middle_name->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_middle_name->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
		<td width="150" valign="top">
<?php if ($tbl_students->Export <> "") { ?>
Username
<?php } else { ?>
	Username<?php if ($tbl_students->s_usrname->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($tbl_students->s_usrname->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
<?php } ?>		</td>
<?php if ($tbl_students->Export == "") { ?>

<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php } ?>
	</tr>
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
while (!$rs->EOF && $nRecCount < $nStopRec) {
	$nRecCount++;
	if (intval($nRecCount) >= intval($nStartRec)) {
		$RowCnt++;

	// Init row class and style
	$tbl_students->CssClass = "ewTableRow";
	$tbl_students->CssStyle = "";

	// Init row event
	$tbl_students->RowClientEvents = "onmouseover='ew_MouseOver(this);' onmouseout='ew_MouseOut(this);' onclick='ew_Click(this);'";
	LoadRowValues($rs); // Load row values
	$tbl_students->RowType = EW_ROWTYPE_VIEW; // Render view
	RenderRow();
?>
	<!-- Table body -->
	<tr<?php echo $tbl_students->DisplayAttributes() ?>>
		<!-- s_first_name -->
		<td width="150"<?php echo $tbl_students->s_first_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_first_name->ViewAttributes() ?>><?php echo $tbl_students->s_first_name->ViewValue ?></div></td>
		<!-- s_last_name -->
		<td width="150"<?php echo $tbl_students->s_last_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_last_name->ViewAttributes() ?>><?php echo $tbl_students->s_last_name->ViewValue ?></div></td>
		<!-- s_middle_name -->
		<td width="150"<?php echo $tbl_students->s_middle_name->CellAttributes() ?>>
<div<?php echo $tbl_students->s_middle_name->ViewAttributes() ?>><?php echo $tbl_students->s_middle_name->ViewValue ?></div></td>
		<!-- s_student_email -->
		<!-- s_graduation_year -->
		<!-- s_usrname -->
		<td width="150"<?php echo $tbl_students->s_usrname->CellAttributes() ?>>
<div<?php echo $tbl_students->s_usrname->ViewAttributes() ?>><?php echo $tbl_students->s_usrname->ViewValue ?></div></td>
<?php if ($tbl_students->Export == "") { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<td nowrap><span class="edge">
<a href="<?php echo $tbl_students->ViewUrl() ?>">View</a>
</span></td>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php } ?>
<?php } ?>
	</tr>
<?php
	}
	$rs->MoveNext();
}
?>
</table>
<?php if ($tbl_students->Export == "") { ?>
<?php } ?>
<?php } ?>
</form>
<table>
  <tr>
    <td><span class="edge">
      <?php if ($Security->IsLoggedIn()) { ?>
      <a href="tbl_studentsadd.php">Add</a>&nbsp;&nbsp;
      <?php } ?>
    </span></td>
  </tr>
</table>
<?php

// Close recordset and connection
if ($rs) $rs->Close();
?>
<?php if ($tbl_students->Export == "") { ?>
<form action="tbl_studentslist.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap>
<span class="edge">
<?php if (!isset($Pager)) $Pager = new cNumericPager($nStartRec, $nDisplayRecs, $nTotalRecs, $nRecRange) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<a href="tbl_studentslist.php?start=<?php echo $Pager->FirstButton->Start ?>"><b>First</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<a href="tbl_studentslist.php?start=<?php echo $Pager->PrevButton->Start ?>"><b>Previous</b></a>&nbsp;
	<?php } ?>
	<?php foreach ($Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="tbl_studentslist.php?start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($Pager->NextButton->Enabled) { ?>
	<a href="tbl_studentslist.php?start=<?php echo $Pager->NextButton->Start ?>"><b>Next</b></a>&nbsp;
	<?php } ?>
	<?php if ($Pager->LastButton->Enabled) { ?>
	<a href="tbl_studentslist.php?start=<?php echo $Pager->LastButton->Start ?>"><b>Last</b></a>&nbsp;
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

// Return Advanced Search Where based on QueryString parameters
function AdvancedSearchWhere() {
	global $Security, $tbl_students;
	$sWhere = "";

	// Field s_studentid
	BuildSearchSql($sWhere, $tbl_students->s_studentid, @$_GET["x_s_studentid"], @$_GET["z_s_studentid"], @$_GET["v_s_studentid"], @$_GET["y_s_studentid"], @$_GET["w_s_studentid"]);

	// Field s_first_name
	BuildSearchSql($sWhere, $tbl_students->s_first_name, @$_GET["x_s_first_name"], @$_GET["z_s_first_name"], @$_GET["v_s_first_name"], @$_GET["y_s_first_name"], @$_GET["w_s_first_name"]);

	// Field s_last_name
	BuildSearchSql($sWhere, $tbl_students->s_last_name, @$_GET["x_s_last_name"], @$_GET["z_s_last_name"], @$_GET["v_s_last_name"], @$_GET["y_s_last_name"], @$_GET["w_s_last_name"]);

	// Field s_middle_name
	BuildSearchSql($sWhere, $tbl_students->s_middle_name, @$_GET["x_s_middle_name"], @$_GET["z_s_middle_name"], @$_GET["v_s_middle_name"], @$_GET["y_s_middle_name"], @$_GET["w_s_middle_name"]);

	// Field s_address
	BuildSearchSql($sWhere, $tbl_students->s_address, @$_GET["x_s_address"], @$_GET["z_s_address"], @$_GET["v_s_address"], @$_GET["y_s_address"], @$_GET["w_s_address"]);

	// Field s_city
	BuildSearchSql($sWhere, $tbl_students->s_city, @$_GET["x_s_city"], @$_GET["z_s_city"], @$_GET["v_s_city"], @$_GET["y_s_city"], @$_GET["w_s_city"]);

	// Field s_postal_code
	BuildSearchSql($sWhere, $tbl_students->s_postal_code, @$_GET["x_s_postal_code"], @$_GET["z_s_postal_code"], @$_GET["v_s_postal_code"], @$_GET["y_s_postal_code"], @$_GET["w_s_postal_code"]);

	// Field s_state
	BuildSearchSql($sWhere, $tbl_students->s_state, @$_GET["x_s_state"], @$_GET["z_s_state"], @$_GET["v_s_state"], @$_GET["y_s_state"], @$_GET["w_s_state"]);

	// Field s_country
	BuildSearchSql($sWhere, $tbl_students->s_country, @$_GET["x_s_country"], @$_GET["z_s_country"], @$_GET["v_s_country"], @$_GET["y_s_country"], @$_GET["w_s_country"]);

	// Field s_home_phone
	BuildSearchSql($sWhere, $tbl_students->s_home_phone, @$_GET["x_s_home_phone"], @$_GET["z_s_home_phone"], @$_GET["v_s_home_phone"], @$_GET["y_s_home_phone"], @$_GET["w_s_home_phone"]);

	// Field s_student_mobile
	BuildSearchSql($sWhere, $tbl_students->s_student_mobile, @$_GET["x_s_student_mobile"], @$_GET["z_s_student_mobile"], @$_GET["v_s_student_mobile"], @$_GET["y_s_student_mobile"], @$_GET["w_s_student_mobile"]);

	// Field s_student_email
	BuildSearchSql($sWhere, $tbl_students->s_student_email, @$_GET["x_s_student_email"], @$_GET["z_s_student_email"], @$_GET["v_s_student_email"], @$_GET["y_s_student_email"], @$_GET["w_s_student_email"]);

	// Field s_parent_name
	BuildSearchSql($sWhere, $tbl_students->s_parent_name, @$_GET["x_s_parent_name"], @$_GET["z_s_parent_name"], @$_GET["v_s_parent_name"], @$_GET["y_s_parent_name"], @$_GET["w_s_parent_name"]);

	// Field s_parent_mobile
	BuildSearchSql($sWhere, $tbl_students->s_parent_mobile, @$_GET["x_s_parent_mobile"], @$_GET["z_s_parent_mobile"], @$_GET["v_s_parent_mobile"], @$_GET["y_s_parent_mobile"], @$_GET["w_s_parent_mobile"]);

	// Field s_parent_email
	BuildSearchSql($sWhere, $tbl_students->s_parent_email, @$_GET["x_s_parent_email"], @$_GET["z_s_parent_email"], @$_GET["v_s_parent_email"], @$_GET["y_s_parent_email"], @$_GET["w_s_parent_email"]);

	// Field s_school
	BuildSearchSql($sWhere, $tbl_students->s_school, @$_GET["x_s_school"], @$_GET["z_s_school"], @$_GET["v_s_school"], @$_GET["y_s_school"], @$_GET["w_s_school"]);

	// Field s_graduation_year
	BuildSearchSql($sWhere, $tbl_students->s_graduation_year, @$_GET["x_s_graduation_year"], @$_GET["z_s_graduation_year"], @$_GET["v_s_graduation_year"], @$_GET["y_s_graduation_year"], @$_GET["w_s_graduation_year"]);

	// Field s_usrname
	BuildSearchSql($sWhere, $tbl_students->s_usrname, @$_GET["x_s_usrname"], @$_GET["z_s_usrname"], @$_GET["v_s_usrname"], @$_GET["y_s_usrname"], @$_GET["w_s_usrname"]);

	// Field s_pwd
	BuildSearchSql($sWhere, $tbl_students->s_pwd, @$_GET["x_s_pwd"], @$_GET["z_s_pwd"], @$_GET["v_s_pwd"], @$_GET["y_s_pwd"], @$_GET["w_s_pwd"]);

	// Field i_instructid
	BuildSearchSql($sWhere, $tbl_students->i_instructid, @$_GET["x_i_instructid"], @$_GET["z_i_instructid"], @$_GET["v_i_instructid"], @$_GET["y_i_instructid"], @$_GET["w_i_instructid"]);

	//AdvancedSearchWhere = sWhere
	// Set up search parm

	if ($sWhere <> "") {

		// Field s_studentid
		SetSearchParm($tbl_students->s_studentid, @$_GET["x_s_studentid"], @$_GET["z_s_studentid"], @$_GET["v_s_studentid"], @$_GET["y_s_studentid"], @$_GET["w_s_studentid"]);

		// Field s_first_name
		SetSearchParm($tbl_students->s_first_name, @$_GET["x_s_first_name"], @$_GET["z_s_first_name"], @$_GET["v_s_first_name"], @$_GET["y_s_first_name"], @$_GET["w_s_first_name"]);

		// Field s_last_name
		SetSearchParm($tbl_students->s_last_name, @$_GET["x_s_last_name"], @$_GET["z_s_last_name"], @$_GET["v_s_last_name"], @$_GET["y_s_last_name"], @$_GET["w_s_last_name"]);

		// Field s_middle_name
		SetSearchParm($tbl_students->s_middle_name, @$_GET["x_s_middle_name"], @$_GET["z_s_middle_name"], @$_GET["v_s_middle_name"], @$_GET["y_s_middle_name"], @$_GET["w_s_middle_name"]);

		// Field s_address
		SetSearchParm($tbl_students->s_address, @$_GET["x_s_address"], @$_GET["z_s_address"], @$_GET["v_s_address"], @$_GET["y_s_address"], @$_GET["w_s_address"]);

		// Field s_city
		SetSearchParm($tbl_students->s_city, @$_GET["x_s_city"], @$_GET["z_s_city"], @$_GET["v_s_city"], @$_GET["y_s_city"], @$_GET["w_s_city"]);

		// Field s_postal_code
		SetSearchParm($tbl_students->s_postal_code, @$_GET["x_s_postal_code"], @$_GET["z_s_postal_code"], @$_GET["v_s_postal_code"], @$_GET["y_s_postal_code"], @$_GET["w_s_postal_code"]);

		// Field s_state
		SetSearchParm($tbl_students->s_state, @$_GET["x_s_state"], @$_GET["z_s_state"], @$_GET["v_s_state"], @$_GET["y_s_state"], @$_GET["w_s_state"]);

		// Field s_country
		SetSearchParm($tbl_students->s_country, @$_GET["x_s_country"], @$_GET["z_s_country"], @$_GET["v_s_country"], @$_GET["y_s_country"], @$_GET["w_s_country"]);

		// Field s_home_phone
		SetSearchParm($tbl_students->s_home_phone, @$_GET["x_s_home_phone"], @$_GET["z_s_home_phone"], @$_GET["v_s_home_phone"], @$_GET["y_s_home_phone"], @$_GET["w_s_home_phone"]);

		// Field s_student_mobile
		SetSearchParm($tbl_students->s_student_mobile, @$_GET["x_s_student_mobile"], @$_GET["z_s_student_mobile"], @$_GET["v_s_student_mobile"], @$_GET["y_s_student_mobile"], @$_GET["w_s_student_mobile"]);

		// Field s_student_email
		SetSearchParm($tbl_students->s_student_email, @$_GET["x_s_student_email"], @$_GET["z_s_student_email"], @$_GET["v_s_student_email"], @$_GET["y_s_student_email"], @$_GET["w_s_student_email"]);

		// Field s_parent_name
		SetSearchParm($tbl_students->s_parent_name, @$_GET["x_s_parent_name"], @$_GET["z_s_parent_name"], @$_GET["v_s_parent_name"], @$_GET["y_s_parent_name"], @$_GET["w_s_parent_name"]);

		// Field s_parent_mobile
		SetSearchParm($tbl_students->s_parent_mobile, @$_GET["x_s_parent_mobile"], @$_GET["z_s_parent_mobile"], @$_GET["v_s_parent_mobile"], @$_GET["y_s_parent_mobile"], @$_GET["w_s_parent_mobile"]);

		// Field s_parent_email
		SetSearchParm($tbl_students->s_parent_email, @$_GET["x_s_parent_email"], @$_GET["z_s_parent_email"], @$_GET["v_s_parent_email"], @$_GET["y_s_parent_email"], @$_GET["w_s_parent_email"]);

		// Field s_school
		SetSearchParm($tbl_students->s_school, @$_GET["x_s_school"], @$_GET["z_s_school"], @$_GET["v_s_school"], @$_GET["y_s_school"], @$_GET["w_s_school"]);

		// Field s_graduation_year
		SetSearchParm($tbl_students->s_graduation_year, @$_GET["x_s_graduation_year"], @$_GET["z_s_graduation_year"], @$_GET["v_s_graduation_year"], @$_GET["y_s_graduation_year"], @$_GET["w_s_graduation_year"]);

		// Field s_usrname
		SetSearchParm($tbl_students->s_usrname, @$_GET["x_s_usrname"], @$_GET["z_s_usrname"], @$_GET["v_s_usrname"], @$_GET["y_s_usrname"], @$_GET["w_s_usrname"]);

		// Field s_pwd
		SetSearchParm($tbl_students->s_pwd, @$_GET["x_s_pwd"], @$_GET["z_s_pwd"], @$_GET["v_s_pwd"], @$_GET["y_s_pwd"], @$_GET["w_s_pwd"]);

		// Field i_instructid
		SetSearchParm($tbl_students->i_instructid, @$_GET["x_i_instructid"], @$_GET["z_i_instructid"], @$_GET["v_i_instructid"], @$_GET["y_i_instructid"], @$_GET["w_i_instructid"]);
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
	global $tbl_students;
	$FldParm = substr($Fld->FldVar, 2);
	$FldVal = ew_StripSlashes($FldVal);
	if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
	$FldVal2 = ew_StripSlashes($FldVal2);
	if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
	$tbl_students->setAdvancedSearch("x_" . $FldParm, $FldVal);
	$tbl_students->setAdvancedSearch("z_" . $FldParm, $FldOpr);
	$tbl_students->setAdvancedSearch("v_" . $FldParm, $FldCond);
	$tbl_students->setAdvancedSearch("y_" . $FldParm, $FldVal2);
	$tbl_students->setAdvancedSearch("w_" . $FldParm, $FldOpr2);
}

// Return Basic Search sql
function BasicSearchSQL($Keyword) {
	$sKeyword = ew_AdjustSql($Keyword);
	$sql = "";
	$sql .= "`s_first_name` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_last_name` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_middle_name` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_address` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_city` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_postal_code` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_state` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_country` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_home_phone` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_student_mobile` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_student_email` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_parent_name` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_parent_mobile` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_parent_email` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_school` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_graduation_year` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_usrname` LIKE '%" . $sKeyword . "%' OR ";
	$sql .= "`s_pwd` LIKE '%" . $sKeyword . "%' OR ";
	if (substr($sql, -4) == " OR ") $sql = substr($sql, 0, strlen($sql)-4);
	return $sql;
}

// Return Basic Search Where based on search keyword and type
function BasicSearchWhere() {
	global $Security, $tbl_students;
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
		$tbl_students->setBasicSearchKeyword($sSearchKeyword);
		$tbl_students->setBasicSearchType($sSearchType);
	}
	return $sSearchStr;
}

// Clear all search parameters
function ResetSearchParms() {

	// Clear search where
	global $tbl_students;
	$sSrchWhere = "";
	$tbl_students->setSearchWhere($sSrchWhere);

	// Clear basic search parameters
	ResetBasicSearchParms();

	// Clear advanced search parameters
	ResetAdvancedSearchParms();
}

// Clear all basic search parameters
function ResetBasicSearchParms() {

	// Clear basic search parameters
	global $tbl_students;
	$tbl_students->setBasicSearchKeyword("");
	$tbl_students->setBasicSearchType("");
}

// Clear all advanced search parameters
function ResetAdvancedSearchParms() {

	// Clear advanced search parameters
	global $tbl_students;
	$tbl_students->setAdvancedSearch("x_s_studentid", "");
	$tbl_students->setAdvancedSearch("x_s_first_name", "");
	$tbl_students->setAdvancedSearch("x_s_last_name", "");
	$tbl_students->setAdvancedSearch("x_s_middle_name", "");
	$tbl_students->setAdvancedSearch("x_s_address", "");
	$tbl_students->setAdvancedSearch("x_s_city", "");
	$tbl_students->setAdvancedSearch("x_s_postal_code", "");
	$tbl_students->setAdvancedSearch("x_s_state", "");
	$tbl_students->setAdvancedSearch("x_s_country", "");
	$tbl_students->setAdvancedSearch("x_s_home_phone", "");
	$tbl_students->setAdvancedSearch("x_s_student_mobile", "");
	$tbl_students->setAdvancedSearch("x_s_student_email", "");
	$tbl_students->setAdvancedSearch("x_s_parent_name", "");
	$tbl_students->setAdvancedSearch("x_s_parent_mobile", "");
	$tbl_students->setAdvancedSearch("x_s_parent_email", "");
	$tbl_students->setAdvancedSearch("x_s_school", "");
	$tbl_students->setAdvancedSearch("x_s_graduation_year", "");
	$tbl_students->setAdvancedSearch("x_s_usrname", "");
	$tbl_students->setAdvancedSearch("x_s_pwd", "");
	$tbl_students->setAdvancedSearch("x_i_instructid", "");
}

// Restore all search parameters
function RestoreSearchParms() {
	global $sSrchWhere, $tbl_students;
	$sSrchWhere = $tbl_students->getSearchWhere();

	// Restore advanced search settings
	RestoreAdvancedSearchParms();
}

// Restore all advanced search parameters
function RestoreAdvancedSearchParms() {

	// Restore advanced search parms
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

		// Reset search criteria
		if (strtolower($sCmd) == "reset" || strtolower($sCmd) == "resetall") {
			ResetSearchParms();
		}

		// Reset master/detail keys
		if (strtolower($sCmd) == "resetall") {
			$tbl_students->setMasterFilter(""); // Clear master filter
			$sDbMasterFilter = "";
			$tbl_students->setDetailFilter(""); // Clear detail filter
			$sDbDetailFilter = "";
			$tbl_students->i_instructid->setSessionValue("");
		}

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

	// s_student_email
	$tbl_students->s_student_email->CellCssStyle = "";
	$tbl_students->s_student_email->CellCssClass = "";

	// s_graduation_year
	$tbl_students->s_graduation_year->CellCssStyle = "";
	$tbl_students->s_graduation_year->CellCssClass = "";

	// s_usrname
	$tbl_students->s_usrname->CellCssStyle = "";
	$tbl_students->s_usrname->CellCssClass = "";
	if ($tbl_students->RowType == EW_ROWTYPE_VIEW) { // View row

		// s_first_name
		$tbl_students->s_first_name->ViewValue = $tbl_students->s_first_name->CurrentValue;
		$tbl_students->s_first_name->CssStyle = "";
		$tbl_students->s_first_name->CssClass = "";
		$tbl_students->s_first_name->ViewCustomAttributes = "";

		// s_last_name
		$tbl_students->s_last_name->ViewValue = $tbl_students->s_last_name->CurrentValue;
		$tbl_students->s_last_name->CssStyle = "";
		$tbl_students->s_last_name->CssClass = "";
		$tbl_students->s_last_name->ViewCustomAttributes = "";

		// s_middle_name
		$tbl_students->s_middle_name->ViewValue = $tbl_students->s_middle_name->CurrentValue;
		$tbl_students->s_middle_name->CssStyle = "";
		$tbl_students->s_middle_name->CssClass = "";
		$tbl_students->s_middle_name->ViewCustomAttributes = "";

		// s_student_email
		$tbl_students->s_student_email->ViewValue = $tbl_students->s_student_email->CurrentValue;
		$tbl_students->s_student_email->CssStyle = "";
		$tbl_students->s_student_email->CssClass = "";
		$tbl_students->s_student_email->ViewCustomAttributes = "";

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

		// s_first_name
		$tbl_students->s_first_name->HrefValue = "";

		// s_last_name
		$tbl_students->s_last_name->HrefValue = "";

		// s_middle_name
		$tbl_students->s_middle_name->HrefValue = "";

		// s_student_email
		$tbl_students->s_student_email->HrefValue = "";

		// s_graduation_year
		$tbl_students->s_graduation_year->HrefValue = "";

		// s_usrname
		$tbl_students->s_usrname->HrefValue = "";
	} elseif ($tbl_students->RowType == EW_ROWTYPE_ADD) { // Add row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_EDIT) { // Edit row
	} elseif ($tbl_students->RowType == EW_ROWTYPE_SEARCH) { // Search row
	}

	// Call Row Rendered event
	$tbl_students->Row_Rendered();
}
?>
<?php

// Load advanced search
function LoadAdvancedSearch() {
	global $tbl_students;
}
?>
<?php

// Set up Master Detail based on querystring parameter
function SetUpMasterDetail() {
	global $nStartRec, $sDbMasterFilter, $sDbDetailFilter, $tbl_students;
	$bValidMaster = FALSE;

	// Get the keys for master table
	if (@$_GET[EW_TABLE_SHOW_MASTER] <> "") {
		$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
		if ($sMasterTblVar == "") {
			$bValidMaster = TRUE;
			$sDbMasterFilter = "";
			$sDbDetailFilter = "";
		}
		if ($sMasterTblVar == "tbl_instructors") {
			$bValidMaster = TRUE;
			$sDbMasterFilter = $tbl_students->SqlMasterFilter_tbl_instructors();
			$sDbDetailFilter = $tbl_students->SqlDetailFilter_tbl_instructors();
			if (@$_GET["i_instructorid"] <> "") {
				$GLOBALS["tbl_instructors"]->i_instructorid->setQueryStringValue($_GET["i_instructorid"]);
				$tbl_students->i_instructid->setQueryStringValue($GLOBALS["tbl_instructors"]->i_instructorid->QueryStringValue);
				$tbl_students->i_instructid->setSessionValue($tbl_students->i_instructid->QueryStringValue);
				if (!is_numeric($GLOBALS["tbl_instructors"]->i_instructorid->QueryStringValue)) $bValidMaster = FALSE;
				$sDbMasterFilter = str_replace("@i_instructorid@", ew_AdjustSql($GLOBALS["tbl_instructors"]->i_instructorid->QueryStringValue), $sDbMasterFilter);
				$sDbDetailFilter = str_replace("@i_instructid@", ew_AdjustSql($GLOBALS["tbl_instructors"]->i_instructorid->QueryStringValue), $sDbDetailFilter);
			} else {
				$bValidMaster = FALSE;
			}
		}
	}
	if ($bValidMaster) {

		// Save current master table
		$tbl_students->setCurrentMasterTable($sMasterTblVar);

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$tbl_students->setStartRecordNumber($nStartRec);
		$tbl_students->setMasterFilter($sDbMasterFilter); // Set up master filter
		$tbl_students->setDetailFilter($sDbDetailFilter); // Set up detail filter

		// Clear previous master session values
		if ($sMasterTblVar <> "tbl_instructors") {
			if ($tbl_students->i_instructid->QueryStringValue == "") $tbl_students->i_instructid->setSessionValue("");
		}
	} else {
		$sDbMasterFilter = $tbl_students->getMasterFilter(); //  Restore master filter
		$sDbDetailFilter = $tbl_students->getDetailFilter(); // Restore detail filter
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
