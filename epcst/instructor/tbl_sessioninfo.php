<?php

// configuration for Table tbl_session
$tbl_session = new ctbl_session; // Initialize table object

// Define table class
class ctbl_session {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
	var $sessionid;
	var $session_number;
	var $session_goal;
	var $session_goal_completed;
	var $session_homework;
	var $session_hmwrk_completed;
	var $session_date;
	var $s_stuid;
	var $fields = array();

	function ctbl_session() {
		$this->TableVar = "tbl_session";
		$this->TableName = "tbl_session";
		$this->SelectLimit = TRUE;
		$this->sessionid = new cField('tbl_session', 'x_sessionid', 'sessionid', "`sessionid`", 3, -1, FALSE);
		$this->fields['sessionid'] =& $this->sessionid;
		$this->session_number = new cField('tbl_session', 'x_session_number', 'session_number', "`session_number`", 3, -1, FALSE);
		$this->fields['session_number'] =& $this->session_number;
		$this->session_goal = new cField('tbl_session', 'x_session_goal', 'session_goal', "`session_goal`", 200, -1, FALSE);
		$this->fields['session_goal'] =& $this->session_goal;
		$this->session_goal_completed = new cField('tbl_session', 'x_session_goal_completed', 'session_goal_completed', "`session_goal_completed`", 200, -1, FALSE);
		$this->fields['session_goal_completed'] =& $this->session_goal_completed;
		$this->session_homework = new cField('tbl_session', 'x_session_homework', 'session_homework', "`session_homework`", 200, -1, FALSE);
		$this->fields['session_homework'] =& $this->session_homework;
		$this->session_hmwrk_completed = new cField('tbl_session', 'x_session_hmwrk_completed', 'session_hmwrk_completed', "`session_hmwrk_completed`", 200, -1, FALSE);
		$this->fields['session_hmwrk_completed'] =& $this->session_hmwrk_completed;
		$this->session_date = new cField('tbl_session', 'x_session_date', 'session_date', "`session_date`", 135, 6, FALSE);
		$this->fields['session_date'] =& $this->session_date;
		$this->s_stuid = new cField('tbl_session', 'x_s_stuid', 's_stuid', "`s_stuid`", 3, -1, FALSE);
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
		return "SELECT * FROM `tbl_session`";
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
		return "INSERT INTO `tbl_session` ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE `tbl_session` SET ";
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
		$SQL = "DELETE FROM `tbl_session` WHERE ";
		$SQL .= EW_DB_QUOTE_START . 'sessionid' . EW_DB_QUOTE_END . '=' .	ew_QuotedValue($rs['sessionid'], $this->sessionid->FldDataType) . ' AND ';
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter for table
	function SqlKeyFilter() {
		return "`sessionid` = @sessionid@";
	}

	// Return url
	function getReturnUrl() {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] <> "") {
			return $_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL];
		} else {
			return "tbl_sessionlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("tbl_sessionview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("tbl_sessionedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("tbl_sessionlist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("tbl_sessionadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("tbl_sessionlist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("tbl_sessiondelete.php");
	}

	// Key url
	function KeyUrl($url, $action = "") {
		$sUrl = $url . "?";
		if ($action <> "") $sUrl .= $action . "&";
		if (!is_null($this->sessionid->CurrentValue)) {
			$sUrl .= "sessionid=" . urlencode($this->sessionid->CurrentValue);
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
		$this->sessionid->setDbValue($rs->fields('sessionid'));
		$this->session_number->setDbValue($rs->fields('session_number'));
		$this->session_goal->setDbValue($rs->fields('session_goal'));
		$this->session_goal_completed->setDbValue($rs->fields('session_goal_completed'));
		$this->session_homework->setDbValue($rs->fields('session_homework'));
		$this->session_hmwrk_completed->setDbValue($rs->fields('session_hmwrk_completed'));
		$this->session_date->setDbValue($rs->fields('session_date'));		
		$this->s_stuid->setDbValue($rs->fields('s_stuid'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

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
		$this->session_date->ViewValue = ew_FormatDateTime($this->session_date->ViewValue, 6);
		$this->session_date->CssStyle = "";
		$this->session_date->CssClass = "";
		$this->session_date->ViewCustomAttributes = "";

		// s_stuid
		$this->s_stuid->ViewValue = $this->s_stuid->CurrentValue;
		$this->s_stuid->CssStyle = "";
		$this->s_stuid->CssClass = "";
		$this->s_stuid->ViewCustomAttributes = "";

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
		
		// s_stuid
		$this->s_stuid->HrefValue = "";
	}

	// Add master User ID filter
	function AddMasterUserIDFilter($sFilter, $sCurrentMasterTable, $userid) {
		$sFilterWrk = $sFilter;
		if ($sCurrentMasterTable == "tbl_students") {
			$sFilterWrk = $GLOBALS["tbl_students"]->AddUserIDFilter($sFilterWrk, $userid);
		}
		return $sFilterWrk;
	}

	// Add detail User ID filter
	function AddDetailUserIDFilter($sFilter, $sCurrentMasterTable, $userid) {
		$sFilterWrk = $sFilter;
		if ($sCurrentMasterTable == "tbl_students") {
			$sSubqueryWrk = $GLOBALS["tbl_students"]->GetUserIDSubquery($this->s_stuid, $GLOBALS["tbl_students"]->s_studentid, $userid);
			if ($sSubqueryWrk <> "") {
				if ($sFilterWrk <> "") $sFilterWrk .= " AND ";
				$sFilterWrk .= $sSubqueryWrk;
			}
		}
		return $sFilterWrk;
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
