<?php

// configuration for Table tbl_instructors
$tbl_instructors = new ctbl_instructors; // Initialize table object

// Define table class
class ctbl_instructors {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
	var $i_instructorid;
	var $i_first_name;
	var $i_last_name;
	var $i_email;
	var $i_mobile;
	var $i_uname;
	var $i_pwd;
	var $fields = array();

	function ctbl_instructors() {
		$this->TableVar = "tbl_instructors";
		$this->TableName = "tbl_instructors";
		$this->SelectLimit = TRUE;
		$this->i_instructorid = new cField('tbl_instructors', 'x_i_instructorid', 'i_instructorid', "`i_instructorid`", 3, -1, FALSE);
		$this->fields['i_instructorid'] =& $this->i_instructorid;
		$this->i_first_name = new cField('tbl_instructors', 'x_i_first_name', 'i_first_name', "`i_first_name`", 200, -1, FALSE);
		$this->fields['i_first_name'] =& $this->i_first_name;
		$this->i_last_name = new cField('tbl_instructors', 'x_i_last_name', 'i_last_name', "`i_last_name`", 200, -1, FALSE);
		$this->fields['i_last_name'] =& $this->i_last_name;
		$this->i_email = new cField('tbl_instructors', 'x_i_email', 'i_email', "`i_email`", 200, -1, FALSE);
		$this->fields['i_email'] =& $this->i_email;
		$this->i_mobile = new cField('tbl_instructors', 'x_i_mobile', 'i_mobile', "`i_mobile`", 200, -1, FALSE);
		$this->fields['i_mobile'] =& $this->i_mobile;
		$this->i_uname = new cField('tbl_instructors', 'x_i_uname', 'i_uname', "`i_uname`", 200, -1, FALSE);
		$this->fields['i_uname'] =& $this->i_uname;
		$this->i_pwd = new cField('tbl_instructors', 'x_i_pwd', 'i_pwd', "`i_pwd`", 200, -1, FALSE);
		$this->fields['i_pwd'] =& $this->i_pwd;
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

	// Table level SQL
	function SqlSelect() { // Select
		return "SELECT * FROM `tbl_instructors`";
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
			if (EW_MD5_PASSWORD && $name == 'i_pwd') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? md5($value) : md5(strtolower($value));
			}
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= (is_null($value) ? "NULL" : ew_QuotedValue($value, $this->fields[$name]->FldDataType)) . ",";
		}
		if (substr($names, -1) == ",") $names = substr($names, 0, strlen($names)-1);
		if (substr($values, -1) == ",") $values = substr($values, 0, strlen($values)-1);
		return "INSERT INTO `tbl_instructors` ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE `tbl_instructors` SET ";
		foreach ($rs as $name => $value) {
			if (EW_MD5_PASSWORD && $name == 'i_pwd') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? md5($value) : md5(strtolower($value));
			}		
			$SQL .= $this->fields[$name]->FldExpression . "=" .
					(is_null($value) ? "NULL" : ew_QuotedValue($value, $this->fields[$name]->FldDataType)) . ",";
		}
		if (substr($SQL, -1) == ",") $SQL = substr($SQL, 0, strlen($SQL)-1);
		if ($this->CurrentFilter <> "")	$SQL .= " WHERE " . $this->CurrentFilter;
		return $SQL;
	}

	// DELETE statement
	function DeleteSQL(&$rs) {
		$SQL = "DELETE FROM `tbl_instructors` WHERE ";
		$SQL .= EW_DB_QUOTE_START . 'i_instructorid' . EW_DB_QUOTE_END . '=' .	ew_QuotedValue($rs['i_instructorid'], $this->i_instructorid->FldDataType) . ' AND ';
		$SQL .= EW_DB_QUOTE_START . 'i_uname' . EW_DB_QUOTE_END . '=' .	ew_QuotedValue($rs['i_uname'], $this->i_uname->FldDataType) . ' AND ';
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter for table
	function SqlKeyFilter() {
		return "`i_instructorid` = @i_instructorid@ AND `i_uname` = '@i_uname@'";
	}

	// Return url
	function getReturnUrl() {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] <> "") {
			return $_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL];
		} else {
			return "tbl_instructorslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("tbl_instructorsview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("tbl_instructorsedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("tbl_instructorslist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("tbl_instructorsadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("tbl_instructorslist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("tbl_instructorsdelete.php");
	}

	// Key url
	function KeyUrl($url, $action = "") {
		$sUrl = $url . "?";
		if ($action <> "") $sUrl .= $action . "&";
		if (!is_null($this->i_instructorid->CurrentValue)) {
			$sUrl .= "i_instructorid=" . urlencode($this->i_instructorid->CurrentValue);
		} else {
			return "javascript:alert('Invalid Record! Key is null');";
		}
		if (!is_null($this->i_uname->CurrentValue)) {
			$sUrl .= "&i_uname=" . urlencode($this->i_uname->CurrentValue);
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
		$this->i_instructorid->setDbValue($rs->fields('i_instructorid'));
		$this->i_first_name->setDbValue($rs->fields('i_first_name'));
		$this->i_last_name->setDbValue($rs->fields('i_last_name'));
		$this->i_email->setDbValue($rs->fields('i_email'));
		$this->i_mobile->setDbValue($rs->fields('i_mobile'));
		$this->i_uname->setDbValue($rs->fields('i_uname'));
		$this->i_pwd->setDbValue($rs->fields('i_pwd'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// i_first_name
		$this->i_first_name->ViewValue = $this->i_first_name->CurrentValue;
		$this->i_first_name->CssStyle = "";
		$this->i_first_name->CssClass = "";
		$this->i_first_name->ViewCustomAttributes = "";

		// i_last_name
		$this->i_last_name->ViewValue = $this->i_last_name->CurrentValue;
		$this->i_last_name->CssStyle = "";
		$this->i_last_name->CssClass = "";
		$this->i_last_name->ViewCustomAttributes = "";

		// i_email
		$this->i_email->ViewValue = $this->i_email->CurrentValue;
		$this->i_email->CssStyle = "";
		$this->i_email->CssClass = "";
		$this->i_email->ViewCustomAttributes = "";

		// i_mobile
		$this->i_mobile->ViewValue = $this->i_mobile->CurrentValue;
		$this->i_mobile->CssStyle = "";
		$this->i_mobile->CssClass = "";
		$this->i_mobile->ViewCustomAttributes = "";

		// i_uname
		$this->i_uname->ViewValue = $this->i_uname->CurrentValue;
		$this->i_uname->CssStyle = "";
		$this->i_uname->CssClass = "";
		$this->i_uname->ViewCustomAttributes = "";

		// i_first_name
		$this->i_first_name->HrefValue = "";

		// i_last_name
		$this->i_last_name->HrefValue = "";

		// i_email
		$this->i_email->HrefValue = "";

		// i_mobile
		$this->i_mobile->HrefValue = "";

		// i_uname
		$this->i_uname->HrefValue = "";
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
