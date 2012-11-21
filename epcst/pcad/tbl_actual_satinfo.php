<?php

// configuration for Table tbl_actual_sat
$tbl_actual_sat = new ctbl_actual_sat; // Initialize table object

// Define table class
class ctbl_actual_sat {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
	var $a_satid;
	var $a_sat_test_date;
	var $a_sat_reading;
	var $a_sat_math;
	var $a_sat_writing;
	var $a_sat_essay;
	var $a_sat_test_site;
	var $a_stuid;
	var $fields = array();

	function ctbl_actual_sat() {
		$this->TableVar = "tbl_actual_sat";
		$this->TableName = "tbl_actual_sat";
		$this->SelectLimit = TRUE;
		$this->a_satid = new cField('tbl_actual_sat', 'x_a_satid', 'a_satid', "`a_satid`", 3, -1, FALSE);
		$this->fields['a_satid'] =& $this->a_satid;
		$this->a_sat_test_date = new cField('tbl_actual_sat', 'x_a_sat_test_date', 'a_sat_test_date', "`a_sat_test_date`", 135, 6, FALSE);
		$this->fields['a_sat_test_date'] =& $this->a_sat_test_date;
		$this->a_sat_reading = new cField('tbl_actual_sat', 'x_a_sat_reading', 'a_sat_reading', "`a_sat_reading`", 3, -1, FALSE);
		$this->fields['a_sat_reading'] =& $this->a_sat_reading;
		$this->a_sat_math = new cField('tbl_actual_sat', 'x_a_sat_math', 'a_sat_math', "`a_sat_math`", 3, -1, FALSE);
		$this->fields['a_sat_math'] =& $this->a_sat_math;
		$this->a_sat_writing = new cField('tbl_actual_sat', 'x_a_sat_writing', 'a_sat_writing', "`a_sat_writing`", 3, -1, FALSE);
		$this->fields['a_sat_writing'] =& $this->a_sat_writing;
		$this->a_sat_essay = new cField('tbl_actual_sat', 'x_a_sat_essay', 'a_sat_essay', "`a_sat_essay`", 3, -1, FALSE);
		$this->fields['a_sat_essay'] =& $this->a_sat_essay;
		$this->a_sat_test_site = new cField('tbl_actual_sat', 'x_a_sat_test_site', 'a_sat_test_site', "`a_sat_test_site`", 200, -1, FALSE);
		$this->fields['a_sat_test_site'] =& $this->a_sat_test_site;
		$this->a_stuid = new cField('tbl_actual_sat', 'x_a_stuid', 'a_stuid', "`a_stuid`", 3, -1, FALSE);
		$this->fields['a_stuid'] =& $this->a_stuid;
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
		return "`a_stuid`=@a_stuid@";
	}

	// Table level SQL
	function SqlSelect() { // Select
		return "SELECT * FROM `tbl_actual_sat`";
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
		return "INSERT INTO `tbl_actual_sat` ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE `tbl_actual_sat` SET ";
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
		$SQL = "DELETE FROM `tbl_actual_sat` WHERE ";
		$SQL .= EW_DB_QUOTE_START . 'a_satid' . EW_DB_QUOTE_END . '=' .	ew_QuotedValue($rs['a_satid'], $this->a_satid->FldDataType) . ' AND ';
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter for table
	function SqlKeyFilter() {
		return "`a_satid` = @a_satid@";
	}

	// Return url
	function getReturnUrl() {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] <> "") {
			return $_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL];
		} else {
			return "tbl_actual_satlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("tbl_actual_satview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("tbl_actual_satedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("tbl_actual_satlist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("tbl_actual_satadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("tbl_actual_satlist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("tbl_actual_satdelete.php");
	}

	// Key url
	function KeyUrl($url, $action = "") {
		$sUrl = $url . "?";
		if ($action <> "") $sUrl .= $action . "&";
		if (!is_null($this->a_satid->CurrentValue)) {
			$sUrl .= "a_satid=" . urlencode($this->a_satid->CurrentValue);
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
		$this->a_satid->setDbValue($rs->fields('a_satid'));
		$this->a_sat_test_date->setDbValue($rs->fields('a_sat_test_date'));
		$this->a_sat_reading->setDbValue($rs->fields('a_sat_reading'));
		$this->a_sat_math->setDbValue($rs->fields('a_sat_math'));
		$this->a_sat_writing->setDbValue($rs->fields('a_sat_writing'));
		$this->a_sat_essay->setDbValue($rs->fields('a_sat_essay'));
		$this->a_sat_test_site->setDbValue($rs->fields('a_sat_test_site'));
		$this->a_stuid->setDbValue($rs->fields('a_stuid'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// a_sat_test_date
		$this->a_sat_test_date->ViewValue = $this->a_sat_test_date->CurrentValue;
		$this->a_sat_test_date->ViewValue = ew_FormatDateTime($this->a_sat_test_date->ViewValue, 6);
		$this->a_sat_test_date->CssStyle = "";
		$this->a_sat_test_date->CssClass = "";
		$this->a_sat_test_date->ViewCustomAttributes = "";

		// a_sat_reading
		$this->a_sat_reading->ViewValue = $this->a_sat_reading->CurrentValue;
		$this->a_sat_reading->CssStyle = "";
		$this->a_sat_reading->CssClass = "";
		$this->a_sat_reading->ViewCustomAttributes = "";

		// a_sat_math
		$this->a_sat_math->ViewValue = $this->a_sat_math->CurrentValue;
		$this->a_sat_math->CssStyle = "";
		$this->a_sat_math->CssClass = "";
		$this->a_sat_math->ViewCustomAttributes = "";

		// a_sat_writing
		$this->a_sat_writing->ViewValue = $this->a_sat_writing->CurrentValue;
		$this->a_sat_writing->CssStyle = "";
		$this->a_sat_writing->CssClass = "";
		$this->a_sat_writing->ViewCustomAttributes = "";

		// a_sat_essay
		$this->a_sat_essay->ViewValue = $this->a_sat_essay->CurrentValue;
		$this->a_sat_essay->CssStyle = "";
		$this->a_sat_essay->CssClass = "";
		$this->a_sat_essay->ViewCustomAttributes = "";

		// a_sat_test_site
		$this->a_sat_test_site->ViewValue = $this->a_sat_test_site->CurrentValue;
		$this->a_sat_test_site->CssStyle = "";
		$this->a_sat_test_site->CssClass = "";
		$this->a_sat_test_site->ViewCustomAttributes = "";

		// a_stuid
		$this->a_stuid->ViewValue = $this->a_stuid->CurrentValue;
		$this->a_stuid->CssStyle = "";
		$this->a_stuid->CssClass = "";
		$this->a_stuid->ViewCustomAttributes = "";

		// a_sat_test_date
		$this->a_sat_test_date->HrefValue = "";

		// a_sat_reading
		$this->a_sat_reading->HrefValue = "";

		// a_sat_math
		$this->a_sat_math->HrefValue = "";

		// a_sat_writing
		$this->a_sat_writing->HrefValue = "";

		// a_sat_essay
		$this->a_sat_essay->HrefValue = "";

		// a_sat_test_site
		$this->a_sat_test_site->HrefValue = "";

		// a_stuid
		$this->a_stuid->HrefValue = "";
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
