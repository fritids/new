<?php

// configuration for Table vwstudenttestsat
$vwstudenttestsat = new cvwstudenttestsat; // Initialize table object

// Define table class
class cvwstudenttestsat {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
	var $s_studentid;
	var $s_first_name;
	var $s_middle_name;
	var $s_last_name;
	var $t_satid;
	var $t_sat_test_date;
	var $t_sat_reading;
	var $t_sat_math;
	var $t_sat_writing;
	var $t_sat_essay;
	var $t_sat_test_site;
	var $s_stuid;
	var $fields = array();

	function cvwstudenttestsat() {
		$this->TableVar = "vwstudenttestsat";
		$this->TableName = "vwstudenttestsat";
		$this->s_studentid = new cField('vwstudenttestsat', 'x_s_studentid', 's_studentid', "tbl_students.s_studentid", 3, -1, FALSE);
		$this->fields['s_studentid'] =& $this->s_studentid;
		$this->s_first_name = new cField('vwstudenttestsat', 'x_s_first_name', 's_first_name', "tbl_students.s_first_name", 200, -1, FALSE);
		$this->fields['s_first_name'] =& $this->s_first_name;
		$this->s_middle_name = new cField('vwstudenttestsat', 'x_s_middle_name', 's_middle_name', "tbl_students.s_middle_name", 200, -1, FALSE);
		$this->fields['s_middle_name'] =& $this->s_middle_name;
		$this->s_last_name = new cField('vwstudenttestsat', 'x_s_last_name', 's_last_name', "tbl_students.s_last_name", 200, -1, FALSE);
		$this->fields['s_last_name'] =& $this->s_last_name;
		$this->t_satid = new cField('vwstudenttestsat', 'x_t_satid', 't_satid', "tbl_testing_sat.t_satid", 3, -1, FALSE);
		$this->fields['t_satid'] =& $this->t_satid;
		$this->t_sat_test_date = new cField('vwstudenttestsat', 'x_t_sat_test_date', 't_sat_test_date', "tbl_testing_sat.t_sat_test_date", 135, 5, FALSE);
		$this->fields['t_sat_test_date'] =& $this->t_sat_test_date;
		$this->t_sat_reading = new cField('vwstudenttestsat', 'x_t_sat_reading', 't_sat_reading', "tbl_testing_sat.t_sat_reading", 3, -1, FALSE);
		$this->fields['t_sat_reading'] =& $this->t_sat_reading;
		$this->t_sat_math = new cField('vwstudenttestsat', 'x_t_sat_math', 't_sat_math', "tbl_testing_sat.t_sat_math", 3, -1, FALSE);
		$this->fields['t_sat_math'] =& $this->t_sat_math;
		$this->t_sat_writing = new cField('vwstudenttestsat', 'x_t_sat_writing', 't_sat_writing', "tbl_testing_sat.t_sat_writing", 3, -1, FALSE);
		$this->fields['t_sat_writing'] =& $this->t_sat_writing;
		$this->t_sat_essay = new cField('vwstudenttestsat', 'x_t_sat_essay', 't_sat_essay', "tbl_testing_sat.t_sat_essay", 3, -1, FALSE);
		$this->fields['t_sat_essay'] =& $this->t_sat_essay;
		$this->t_sat_test_site = new cField('vwstudenttestsat', 'x_t_sat_test_site', 't_sat_test_site', "tbl_testing_sat.t_sat_test_site", 200, -1, FALSE);
		$this->fields['t_sat_test_site'] =& $this->t_sat_test_site;
		$this->s_stuid = new cField('vwstudenttestsat', 'x_s_stuid', 's_stuid', "tbl_testing_sat.s_stuid", 3, -1, FALSE);
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

	// Table level SQL
	function SqlSelect() { // Select
		return "SELECT tbl_students.s_studentid, tbl_students.s_first_name, tbl_students.s_middle_name, tbl_students.s_last_name, tbl_testing_sat.t_satid, tbl_testing_sat.t_sat_test_date, tbl_testing_sat.t_sat_reading, tbl_testing_sat.t_sat_math, tbl_testing_sat.t_sat_writing, tbl_testing_sat.t_sat_essay, tbl_testing_sat.t_sat_test_site, tbl_testing_sat.s_stuid FROM tbl_students, tbl_testing_sat";
	}

	function SqlWhere() { // Where
		return "(tbl_students.s_studentid = tbl_testing_sat.s_stuid)";
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
		return "INSERT INTO tbl_students, tbl_testing_sat ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE tbl_students, tbl_testing_sat SET ";
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
		$SQL = "DELETE FROM tbl_students, tbl_testing_sat WHERE ";
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter for table
	function SqlKeyFilter() {
		return "";
	}

	// Return url
	function getReturnUrl() {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] <> "") {
			return $_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL];
		} else {
			return "vwstudenttestsatlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("vwstudenttestsatview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("vwstudenttestsatedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("vwstudenttestsatlist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("vwstudenttestsatadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("vwstudenttestsatlist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("vwstudenttestsatdelete.php");
	}

	// Key url
	function KeyUrl($url, $action = "") {
		$sUrl = $url . "?";
		if ($action <> "") $sUrl .= $action . "&";
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
		$this->s_studentid->setDbValue($rs->fields('s_studentid'));
		$this->s_first_name->setDbValue($rs->fields('s_first_name'));
		$this->s_middle_name->setDbValue($rs->fields('s_middle_name'));
		$this->s_last_name->setDbValue($rs->fields('s_last_name'));
		$this->t_satid->setDbValue($rs->fields('t_satid'));
		$this->t_sat_test_date->setDbValue($rs->fields('t_sat_test_date'));
		$this->t_sat_reading->setDbValue($rs->fields('t_sat_reading'));
		$this->t_sat_math->setDbValue($rs->fields('t_sat_math'));
		$this->t_sat_writing->setDbValue($rs->fields('t_sat_writing'));
		$this->t_sat_essay->setDbValue($rs->fields('t_sat_essay'));
		$this->t_sat_test_site->setDbValue($rs->fields('t_sat_test_site'));
		$this->s_stuid->setDbValue($rs->fields('s_stuid'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// s_first_name
		$this->s_first_name->ViewValue = $this->s_first_name->CurrentValue;
		$this->s_first_name->CssStyle = "";
		$this->s_first_name->CssClass = "";
		$this->s_first_name->ViewCustomAttributes = "";

		// s_middle_name
		$this->s_middle_name->ViewValue = $this->s_middle_name->CurrentValue;
		$this->s_middle_name->CssStyle = "";
		$this->s_middle_name->CssClass = "";
		$this->s_middle_name->ViewCustomAttributes = "";

		// s_last_name
		$this->s_last_name->ViewValue = $this->s_last_name->CurrentValue;
		$this->s_last_name->CssStyle = "";
		$this->s_last_name->CssClass = "";
		$this->s_last_name->ViewCustomAttributes = "";

		// t_sat_test_date
		$this->t_sat_test_date->ViewValue = $this->t_sat_test_date->CurrentValue;
		$this->t_sat_test_date->ViewValue = ew_FormatDateTime($this->t_sat_test_date->ViewValue, 5);
		$this->t_sat_test_date->CssStyle = "";
		$this->t_sat_test_date->CssClass = "";
		$this->t_sat_test_date->ViewCustomAttributes = "";

		// t_sat_reading
		$this->t_sat_reading->ViewValue = $this->t_sat_reading->CurrentValue;
		$this->t_sat_reading->CssStyle = "";
		$this->t_sat_reading->CssClass = "";
		$this->t_sat_reading->ViewCustomAttributes = "";

		// t_sat_math
		$this->t_sat_math->ViewValue = $this->t_sat_math->CurrentValue;
		$this->t_sat_math->CssStyle = "";
		$this->t_sat_math->CssClass = "";
		$this->t_sat_math->ViewCustomAttributes = "";

		// t_sat_writing
		$this->t_sat_writing->ViewValue = $this->t_sat_writing->CurrentValue;
		$this->t_sat_writing->CssStyle = "";
		$this->t_sat_writing->CssClass = "";
		$this->t_sat_writing->ViewCustomAttributes = "";

		// t_sat_essay
		$this->t_sat_essay->ViewValue = $this->t_sat_essay->CurrentValue;
		$this->t_sat_essay->CssStyle = "";
		$this->t_sat_essay->CssClass = "";
		$this->t_sat_essay->ViewCustomAttributes = "";

		// t_sat_test_site
		$this->t_sat_test_site->ViewValue = $this->t_sat_test_site->CurrentValue;
		$this->t_sat_test_site->CssStyle = "";
		$this->t_sat_test_site->CssClass = "";
		$this->t_sat_test_site->ViewCustomAttributes = "";

		// s_first_name
		$this->s_first_name->HrefValue = "";

		// s_middle_name
		$this->s_middle_name->HrefValue = "";

		// s_last_name
		$this->s_last_name->HrefValue = "";

		// t_sat_test_date
		$this->t_sat_test_date->HrefValue = "";

		// t_sat_reading
		$this->t_sat_reading->HrefValue = "";

		// t_sat_math
		$this->t_sat_math->HrefValue = "";

		// t_sat_writing
		$this->t_sat_writing->HrefValue = "";

		// t_sat_essay
		$this->t_sat_essay->HrefValue = "";

		// t_sat_test_site
		$this->t_sat_test_site->HrefValue = "";
	}

	// User id filter
	function UserIDFilter() {
		return "tbl_students.s_studentid=@UserID@";
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter, $userid) {
		$sFilterWrk = "";
		$sFilterWrk = $this->UserIDFilter();
		if ($sFilter <> "") {
			if ($sFilterWrk <> "") {
				$sFilterWrk = $sFilter . " AND " . $sFilterWrk;
			} else {
				$sFilterWrk = $sFilter;
			}
		}
		$sFilterWrk = str_replace("@UserID@", ew_AdjustSql($userid), $sFilterWrk);
		return $sFilterWrk;
	}

	// Get User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld, $userid) {
		global $conn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM tbl_students, tbl_testing_sat WHERE " . $this->AddUserIDFilter("", $userid);

		// List all values
		if ($rs = $conn->Execute($sSql)) {
			while (!$rs->EOF) {
				if ($sWrk <> "") $sWrk .= ",";
				$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType);
				$rs->MoveNext();
			}
			$rs->Close();
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
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
