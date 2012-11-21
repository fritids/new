<?php

// configuration for Table vwstudentsession
$vwstudentsession = new cvwstudentsession; // Initialize table object

// Define table class
class cvwstudentsession {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
	var $s_studentid;
	var $s_stuid;
	var $s_first_name;
	var $s_middle_name;
	var $s_last_name;
	var $sessionid;
	var $session_number;
	var $session_goal;
	var $session_goal_completed;
	var $session_homework;
	var $session_hmwrk_completed;
	var $session_date;
	var $fields = array();

	function cvwstudentsession() {
		$this->TableVar = "vwstudentsession";
		$this->TableName = "vwstudentsession";
		$this->s_studentid = new cField('vwstudentsession', 'x_s_studentid', 's_studentid', "tbl_students.s_studentid", 3, -1, FALSE);
		$this->fields['s_studentid'] =& $this->s_studentid;
		$this->s_stuid = new cField('vwstudentsession', 'x_s_stuid', 's_stuid', "tbl_session.s_stuid", 3, -1, FALSE);
		$this->fields['s_stuid'] =& $this->s_stuid;
		$this->s_first_name = new cField('vwstudentsession', 'x_s_first_name', 's_first_name', "tbl_students.s_first_name", 200, -1, FALSE);
		$this->fields['s_first_name'] =& $this->s_first_name;
		$this->s_middle_name = new cField('vwstudentsession', 'x_s_middle_name', 's_middle_name', "tbl_students.s_middle_name", 200, -1, FALSE);
		$this->fields['s_middle_name'] =& $this->s_middle_name;
		$this->s_last_name = new cField('vwstudentsession', 'x_s_last_name', 's_last_name', "tbl_students.s_last_name", 200, -1, FALSE);
		$this->fields['s_last_name'] =& $this->s_last_name;
		$this->sessionid = new cField('vwstudentsession', 'x_sessionid', 'sessionid', "tbl_session.sessionid", 3, -1, FALSE);
		$this->fields['sessionid'] =& $this->sessionid;
		$this->session_number = new cField('vwstudentsession', 'x_session_number', 'session_number', "tbl_session.session_number", 3, -1, FALSE);
		$this->fields['session_number'] =& $this->session_number;
		$this->session_goal = new cField('vwstudentsession', 'x_session_goal', 'session_goal', "tbl_session.session_goal", 200, -1, FALSE);
		$this->fields['session_goal'] =& $this->session_goal;
	
		$this->session_goal_completed = new cField('vwstudentsession', 'x_session_goal_completed', 'session_goal_completed', "tbl_session.session_goal_completed", 200, -1, FALSE);
		$this->fields['session_goal_completed'] =& $this->session_goal_completed;
	
		$this->session_homework = new cField('vwstudentsession', 'x_session_homework', 'session_homework', "tbl_session.session_homework", 200, -1, FALSE);
		$this->fields['session_homework'] =& $this->session_homework;
	
		$this->session_hmwrk_completed = new cField('vwstudentsession', 'x_session_hmwrk_completed', 'session_hmwrk_completed', "tbl_session.session_hmwrk_completed", 200, -1, FALSE);
		$this->fields['session_hmwrk_completed'] =& $this->session_hmwrk_completed;
	
		$this->session_date = new cField('vwstudentsession', 'x_session_date', 'session_date', "tbl_session.session_date", 135, 5, FALSE);
		$this->fields['session_date'] =& $this->session_date;
	
	
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
		return "SELECT tbl_students.s_studentid, tbl_session.s_stuid, tbl_students.s_first_name, tbl_students.s_middle_name, tbl_students.s_last_name, tbl_session.sessionid, tbl_session.session_number, tbl_session.session_goal, tbl_session.session_goal_completed, tbl_session.session_homework, tbl_session.session_hmwrk_completed, tbl_session.session_date FROM tbl_students, tbl_session";
	}

	function SqlWhere() { // Where
		return "(tbl_students.s_studentid = tbl_session.s_stuid)";
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
		return "INSERT INTO tbl_students, tbl_session ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE tbl_students, tbl_session SET ";
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
		$SQL = "DELETE FROM tbl_students, tbl_session WHERE ";
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
			return "vwstudentsessionlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("vwstudentsessionview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("vwstudentsessionedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("vwstudentsessionlist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("vwstudentsessionadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("vwstudentsessionlist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("vwstudentsessiondelete.php");
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
		$this->s_stuid->setDbValue($rs->fields('s_stuid'));
		$this->s_first_name->setDbValue($rs->fields('s_first_name'));
		$this->s_middle_name->setDbValue($rs->fields('s_middle_name'));
		$this->s_last_name->setDbValue($rs->fields('s_last_name'));
		$this->sessionid->setDbValue($rs->fields('sessionid'));
		$this->session_number->setDbValue($rs->fields('session_number'));
		$this->session_goal->setDbValue($rs->fields('session_goal'));
		$this->session_goal_completed->setDbValue($rs->fields('session_goal_completed'));
		$this->session_homework->setDbValue($rs->fields('session_homework'));
		$this->session_hmwrk_completed->setDbValue($rs->fields('session_hmwrk_completed'));
		$this->session_date->setDbValue($rs->fields('session_date'));
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

		// session_number
		$this->session_number->ViewValue = $this->session_number->CurrentValue;
		$this->session_number->CssStyle = "";
		$this->session_number->CssClass = "";
		$this->session_number->ViewCustomAttributes = "";

		// session_goal
		$this->session_goal->ViewValue = $this->session_goal->CurrentValue;
		$this->session_goal->CssStyle = "";
		$this->session_goal->CssClass = "";
		$this->session_goal->ViewCustomAttributes = "";

		// session_goal_completed
		$this->session_goal_completed->ViewValue = $this->session_goal_completed->CurrentValue;
		$this->session_goal_completed->CssStyle = "";
		$this->session_goal_completed->CssClass = "";
		$this->session_goal_completed->ViewCustomAttributes = "";

		// session_homework
		$this->session_homework->ViewValue = $this->session_homework->CurrentValue;
		$this->session_homework->CssStyle = "";
		$this->session_homework->CssClass = "";
		$this->session_homework->ViewCustomAttributes = "";

		// session_hmwrk_completed
		$this->session_hmwrk_completed->ViewValue = $this->session_hmwrk_completed->CurrentValue;
		$this->session_hmwrk_completed->CssStyle = "";
		$this->session_hmwrk_completed->CssClass = "";
		$this->session_hmwrk_completed->ViewCustomAttributes = "";

		// session_date
		$this->session_date->ViewValue = $this->session_date->CurrentValue;
		$this->session_date->ViewValue = ew_FormatDateTime($this->session_date->ViewValue, 5);
		$this->session_date->CssStyle = "";
		$this->session_date->CssClass = "";
		$this->session_date->ViewCustomAttributes = "";

		// s_first_name
		$this->s_first_name->HrefValue = "";

		// s_middle_name
		$this->s_middle_name->HrefValue = "";

		// s_last_name
		$this->s_last_name->HrefValue = "";

		// session_number
		$this->session_number->HrefValue = "";

		// session_goal
		$this->session_goal->HrefValue = "";

		// session_goal_completed
		$this->session_goal_completed->HrefValue = "";

		// session_homework
		$this->session_homework->HrefValue = "";

		// session_hmwrk_completed
		$this->session_hmwrk_completed->HrefValue = "";

		// session_date
		$this->session_date->HrefValue = "";
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
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM tbl_students, tbl_session WHERE " . $this->AddUserIDFilter("", $userid);

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
