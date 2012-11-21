<?php

// configuration for Table tbl_testing_act
$tbl_testing_act = new ctbl_testing_act; // Initialize table object

// Define table class
class ctbl_testing_act {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
	var $t_actid;
	var $t_act_test_date;
	var $t_act_english;
	var $t_act_math;
	var $t_act_reading;
	var $t_act_science;
	var $t_act_essay;
	var $t_act_test_site;
	var $s_stuid;
	var $fields = array();

	function ctbl_testing_act() {
		$this->TableVar = "tbl_testing_act";
		$this->TableName = "tbl_testing_act";
		$this->SelectLimit = TRUE;
		$this->t_actid = new cField('tbl_testing_act', 'x_t_actid', 't_actid', "`t_actid`", 3, -1, FALSE);
		$this->fields['t_actid'] =& $this->t_actid;
		$this->t_act_test_date = new cField('tbl_testing_act', 'x_t_act_test_date', 't_act_test_date', "`t_act_test_date`", 135, 6, FALSE);
		$this->fields['t_act_test_date'] =& $this->t_act_test_date;
		$this->t_act_english = new cField('tbl_testing_act', 'x_t_act_english', 't_act_english', "`t_act_english`", 3, -1, FALSE);
		$this->fields['t_act_english'] =& $this->t_act_english;
		$this->t_act_math = new cField('tbl_testing_act', 'x_t_act_math', 't_act_math', "`t_act_math`", 3, -1, FALSE);
		$this->fields['t_act_math'] =& $this->t_act_math;
		$this->t_act_reading = new cField('tbl_testing_act', 'x_t_act_reading', 't_act_reading', "`t_act_reading`", 3, -1, FALSE);
		$this->fields['t_act_reading'] =& $this->t_act_reading;
		$this->t_act_science = new cField('tbl_testing_act', 'x_t_act_science', 't_act_science', "`t_act_science`", 3, -1, FALSE);
		$this->fields['t_act_science'] =& $this->t_act_science;
		$this->t_act_essay = new cField('tbl_testing_act', 'x_t_act_essay', 't_act_essay', "`t_act_essay`", 3, -1, FALSE);
		$this->fields['t_act_essay'] =& $this->t_act_essay;
		$this->t_act_test_site = new cField('tbl_testing_act', 'x_t_act_test_site', 't_act_test_site', "`t_act_test_site`", 200, -1, FALSE);
		$this->fields['t_act_test_site'] =& $this->t_act_test_site;
		$this->s_stuid = new cField('tbl_testing_act', 'x_s_stuid', 's_stuid', "`s_stuid`", 3, -1, FALSE);
		$this->fields['s_stuid'] =& $this->s_stuid;
	}

	// Records per page
	function getRecordsPerPage() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_REC_PER_PAGE];
	}

	function setRecordsPerPage($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_REC_PER_PAGE] = $v;
	}

	// Start record number
	function getStartRecordNumber() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_START_REC];
	}

	function setStartRecordNumber($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_START_REC] = $v;
	}

	// Advanced search
	function getAdvancedSearch($fld) {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ADVANCED_SEARCH . "_" . $fld];
	}

	function setAdvancedSearch($fld, $v) {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ADVANCED_SEARCH . "_" . $fld] <> $v) {
			$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ADVANCED_SEARCH . "_" . $fld] = $v;
		}
	}

	// Basic search Keyword
	function getBasicSearchKeyword() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_BASIC_SEARCH];
	}

	function setBasicSearchKeyword($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_BASIC_SEARCH] = $v;
	}

	// Basic Search Type
	function getBasicSearchType() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_BASIC_SEARCH_TYPE];
	}

	function setBasicSearchType($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_BASIC_SEARCH_TYPE] = $v;
	}

	// Search where clause
	function getSearchWhere() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_SEARCH_WHERE];
	}

	function setSearchWhere($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_SEARCH_WHERE] = $v;
	}

	// Session WHERE Clause
	function getSessionWhere() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_WHERE];
	}

	function setSessionWhere($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_WHERE] = $v;
	}

	// Session ORDER BY
	function getSessionOrderBy() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY];
	}

	function setSessionOrderBy($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY] = $v;
	}

	// Session Key
	function getKey($fld) {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_KEY . "_" . $fld];
	}

	function setKey($fld, $v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_KEY . "_" . $fld] = $v;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master where clause
	function getMasterFilter() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_FILTER];
	}

	function setMasterFilter($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_FILTER] = $v;
	}

	// Session detail where clause
	function getDetailFilter() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_FILTER];
	}

	function setDetailFilter($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_FILTER] = $v;
	}

	// Master filter
	function SqlMasterFilter_tbl_students() {
		return "`s_studentid`=@s_studentid@";
	}

	// Detail filter
	function SqlDetailFilter_tbl_students() {
		return "`s_stuid`=@s_stuid@";
	}

	// Table level SQL
	function SqlSelect() { // Select
		return "SELECT * FROM `tbl_testing_act`";
	}

	function SqlWhere() { // Where
		return "";
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// SQL variables
	var $CurrentFilter; // Current filter
	var $CurrentOrder; // Current order
	var $CurrentOrderType; // Current order type

	// Report table sql
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Return table sql with list page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		if ($this->CurrentFilter <> "") {
			if ($sFilter <> "") $sFilter .= " AND ";
			$sFilter .= $this->CurrentFilter;
		}
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Return record count
	function SelectRecordCount() {
		global $conn;
		$cnt = -1;
		$sFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		if ($this->SelectLimit) {
			$sSelect = $this->SelectSQL();
			if (strtoupper(substr($sSelect, 0, 13)) == "SELECT * FROM") {
				$sSelect = "SELECT COUNT(*) FROM" . substr($sSelect, 13);
				if ($rs = $conn->Execute($sSelect)) {
					if (!$rs->EOF) $cnt = $rs->fields[0];
					$rs->Close();
				}
			}
		}
		if ($cnt == -1) {
			if ($rs = $conn->Execute($this->SelectSQL())) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $sFilter;
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= (is_null($value) ? "NULL" : ew_QuotedValue($value, $this->fields[$name]->FldDataType)) . ",";
		}
		if (substr($names, -1) == ",") $names = substr($names, 0, strlen($names)-1);
		if (substr($values, -1) == ",") $values = substr($values, 0, strlen($values)-1);
		return "INSERT INTO `tbl_testing_act` ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE `tbl_testing_act` SET ";
		foreach ($rs as $name => $value) {
			$SQL .= $this->fields[$name]->FldExpression . "=" .
					(is_null($value) ? "NULL" : ew_QuotedValue($value, $this->fields[$name]->FldDataType)) . ",";
		}
		if (substr($SQL, -1) == ",") $SQL = substr($SQL, 0, strlen($SQL)-1);
		if ($this->CurrentFilter <> "")	$SQL .= " WHERE " . $this->CurrentFilter;
		return $SQL;
	}

	// DELETE statement
	function DeleteSQL(&$rs) {
		$SQL = "DELETE FROM `tbl_testing_act` WHERE ";
		$SQL .= EW_DB_QUOTE_START . 't_actid' . EW_DB_QUOTE_END . '=' .	ew_QuotedValue($rs['t_actid'], $this->t_actid->FldDataType) . ' AND ';
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter for table
	function SqlKeyFilter() {
		return "`t_actid` = @t_actid@";
	}

	// Return url
	function getReturnUrl() {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] <> "") {
			return $_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL];
		} else {
			return "tbl_testing_actlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("tbl_testing_actview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("tbl_testing_actedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("tbl_testing_actlist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("tbl_testing_actadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("tbl_testing_actlist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("tbl_testing_actdelete.php");
	}

	// Key url
	function KeyUrl($url, $action = "") {
		$sUrl = $url . "?";
		if ($action <> "") $sUrl .= $action . "&";
		if (!is_null($this->t_actid->CurrentValue)) {
			$sUrl .= "t_actid=" . urlencode($this->t_actid->CurrentValue);
		} else {
			return "javascript:alert('Invalid Record! Key is null');";
		}
		return $sUrl;
	}

	// Function LoadRs
	// - Load Row based on Key Value
	function LoadRs($sFilter) {
		global $conn;

		// Set up filter (Sql Where Clause) and get Return Sql
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		return $conn->Execute($sSql);
	}

	// Load row values from rs
	function LoadListRowValues(&$rs) {
		$this->t_actid->setDbValue($rs->fields('t_actid'));
		$this->t_act_test_date->setDbValue($rs->fields('t_act_test_date'));
		$this->t_act_english->setDbValue($rs->fields('t_act_english'));
		$this->t_act_math->setDbValue($rs->fields('t_act_math'));
		$this->t_act_reading->setDbValue($rs->fields('t_act_reading'));
		$this->t_act_science->setDbValue($rs->fields('t_act_science'));
		$this->t_act_essay->setDbValue($rs->fields('t_act_essay'));
		$this->t_act_test_site->setDbValue($rs->fields('t_act_test_site'));
		$this->s_stuid->setDbValue($rs->fields('s_stuid'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// t_act_test_date
		$this->t_act_test_date->ViewValue = $this->t_act_test_date->CurrentValue;
		$this->t_act_test_date->ViewValue = ew_FormatDateTime($this->t_act_test_date->ViewValue, 6);
		$this->t_act_test_date->CssStyle = "";
		$this->t_act_test_date->CssClass = "";
		$this->t_act_test_date->ViewCustomAttributes = "";

		// t_act_english
		$this->t_act_english->ViewValue = $this->t_act_english->CurrentValue;
		$this->t_act_english->CssStyle = "";
		$this->t_act_english->CssClass = "";
		$this->t_act_english->ViewCustomAttributes = "";

		// t_act_math
		$this->t_act_math->ViewValue = $this->t_act_math->CurrentValue;
		$this->t_act_math->CssStyle = "";
		$this->t_act_math->CssClass = "";
		$this->t_act_math->ViewCustomAttributes = "";

		// t_act_reading
		$this->t_act_reading->ViewValue = $this->t_act_reading->CurrentValue;
		$this->t_act_reading->CssStyle = "";
		$this->t_act_reading->CssClass = "";
		$this->t_act_reading->ViewCustomAttributes = "";

		// t_act_science
		$this->t_act_science->ViewValue = $this->t_act_science->CurrentValue;
		$this->t_act_science->CssStyle = "";
		$this->t_act_science->CssClass = "";
		$this->t_act_science->ViewCustomAttributes = "";

		// t_act_essay
		$this->t_act_essay->ViewValue = $this->t_act_essay->CurrentValue;
		$this->t_act_essay->CssStyle = "";
		$this->t_act_essay->CssClass = "";
		$this->t_act_essay->ViewCustomAttributes = "";

		// t_act_test_site
		$this->t_act_test_site->ViewValue = $this->t_act_test_site->CurrentValue;
		$this->t_act_test_site->CssStyle = "";
		$this->t_act_test_site->CssClass = "";
		$this->t_act_test_site->ViewCustomAttributes = "";

		// s_stuid
		$this->s_stuid->ViewValue = $this->s_stuid->CurrentValue;
		$this->s_stuid->CssStyle = "";
		$this->s_stuid->CssClass = "";
		$this->s_stuid->ViewCustomAttributes = "";

		// t_act_test_date
		$this->t_act_test_date->HrefValue = "";

		// t_act_english
		$this->t_act_english->HrefValue = "";

		// t_act_math
		$this->t_act_math->HrefValue = "";

		// t_act_reading
		$this->t_act_reading->HrefValue = "";

		// t_act_science
		$this->t_act_science->HrefValue = "";

		// t_act_essay
		$this->t_act_essay->HrefValue = "";

		// t_act_test_site
		$this->t_act_test_site->HrefValue = "";

		// s_stuid
		$this->s_stuid->HrefValue = "";
	}
	var $CurrentAction; // Current action
	var $EventName; // Event name
	var $EventCancelled; // Event cancelled
	var $CancelMessage; // Cancel message
	var $RowType; // Row Type
	var $CssClass; // Css class
	var $CssStyle; // Css style
	var $RowClientEvents; // Row client events

	// Display Attribute
	function DisplayAttributes() {
		$sAtt = "";
		if (trim($this->CssStyle) <> "") {
			$sAtt .= " style=\"" . trim($this->CssStyle) . "\"";
		}
		if (trim($this->CssClass) <> "") {
			$sAtt .= " class=\"" . trim($this->CssClass) . "\"";
		}
		if ($this->Export == "") {
			if (trim($this->RowClientEvents) <> "") {
				$sAtt .= " " . $this->RowClientEvents;
			}
		}
		return $sAtt;
	}

	// Export
	var $Export;

//	 ----------------
//	  Field objects
//	 ----------------
	function fields($fldname) {
		return $this->fields[$fldname];
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// Row Inserting event
	function Row_Inserting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted(&$rs) {

		//echo "Row Inserted";
	}

	// Row Updating event
	function Row_Updating(&$rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Updated event
	function Row_Updated(&$rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Deleting event
	function Row_Deleting($rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}
}
?>
