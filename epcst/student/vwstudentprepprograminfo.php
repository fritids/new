<?php

// configuration for Table vwstudentprepprogram
$vwstudentprepprogram = new cvwstudentprepprogram; // Initialize table object

// Define table class
class cvwstudentprepprogram {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
	var $s_studentid;
	var $s_first_name;
	var $s_middle_name;
	var $s_last_name;
	var $p_prepid;
	var $p_arithmetic;
	var $p_algebra;
	var $p_techniques;
	var $p_geometry;
	var $p_advanced_topics;
	var $p_sentence_completion;
	var $p_critical_reading;
	var $p_error_id;
	var $p_sentence_improvement;
	var $p_paragraph_improvement;
	var $s_stuid;
	var $fields = array();

	function cvwstudentprepprogram() {
		$this->TableVar = "vwstudentprepprogram";
		$this->TableName = "vwstudentprepprogram";
		$this->s_studentid = new cField('vwstudentprepprogram', 'x_s_studentid', 's_studentid', "tbl_students.s_studentid", 3, -1, FALSE);
		$this->fields['s_studentid'] =& $this->s_studentid;
		$this->s_first_name = new cField('vwstudentprepprogram', 'x_s_first_name', 's_first_name', "tbl_students.s_first_name", 200, -1, FALSE);
		$this->fields['s_first_name'] =& $this->s_first_name;
		$this->s_middle_name = new cField('vwstudentprepprogram', 'x_s_middle_name', 's_middle_name', "tbl_students.s_middle_name", 200, -1, FALSE);
		$this->fields['s_middle_name'] =& $this->s_middle_name;
		$this->s_last_name = new cField('vwstudentprepprogram', 'x_s_last_name', 's_last_name', "tbl_students.s_last_name", 200, -1, FALSE);
		$this->fields['s_last_name'] =& $this->s_last_name;
		$this->p_prepid = new cField('vwstudentprepprogram', 'x_p_prepid', 'p_prepid', "tbl_prep_programs.p_prepid", 3, -1, FALSE);
		$this->fields['p_prepid'] =& $this->p_prepid;
		$this->p_arithmetic = new cField('vwstudentprepprogram', 'x_p_arithmetic', 'p_arithmetic', "tbl_prep_programs.p_arithmetic", 200, -1, FALSE);
		$this->fields['p_arithmetic'] =& $this->p_arithmetic;
		$this->p_algebra = new cField('vwstudentprepprogram', 'x_p_algebra', 'p_algebra', "tbl_prep_programs.p_algebra", 200, -1, FALSE);
		$this->fields['p_algebra'] =& $this->p_algebra;
		$this->p_techniques = new cField('vwstudentprepprogram', 'x_p_techniques', 'p_techniques', "tbl_prep_programs.p_techniques", 200, -1, FALSE);
		$this->fields['p_techniques'] =& $this->p_techniques;
		$this->p_geometry = new cField('vwstudentprepprogram', 'x_p_geometry', 'p_geometry', "tbl_prep_programs.p_geometry", 200, -1, FALSE);
		$this->fields['p_geometry'] =& $this->p_geometry;
		$this->p_advanced_topics = new cField('vwstudentprepprogram', 'x_p_advanced_topics', 'p_advanced_topics', "tbl_prep_programs.p_advanced_topics", 200, -1, FALSE);
		$this->fields['p_advanced_topics'] =& $this->p_advanced_topics;
		$this->p_sentence_completion = new cField('vwstudentprepprogram', 'x_p_sentence_completion', 'p_sentence_completion', "tbl_prep_programs.p_sentence_completion", 200, -1, FALSE);
		$this->fields['p_sentence_completion'] =& $this->p_sentence_completion;
		$this->p_critical_reading = new cField('vwstudentprepprogram', 'x_p_critical_reading', 'p_critical_reading', "tbl_prep_programs.p_critical_reading", 200, -1, FALSE);
		$this->fields['p_critical_reading'] =& $this->p_critical_reading;
		$this->p_error_id = new cField('vwstudentprepprogram', 'x_p_error_id', 'p_error_id', "tbl_prep_programs.p_error_id", 200, -1, FALSE);
		$this->fields['p_error_id'] =& $this->p_error_id;
		$this->p_sentence_improvement = new cField('vwstudentprepprogram', 'x_p_sentence_improvement', 'p_sentence_improvement', "tbl_prep_programs.p_sentence_improvement", 200, -1, FALSE);
		$this->fields['p_sentence_improvement'] =& $this->p_sentence_improvement;
		$this->p_paragraph_improvement = new cField('vwstudentprepprogram', 'x_p_paragraph_improvement', 'p_paragraph_improvement', "tbl_prep_programs.p_paragraph_improvement", 200, -1, FALSE);
		$this->fields['p_paragraph_improvement'] =& $this->p_paragraph_improvement;
		$this->s_stuid = new cField('vwstudentprepprogram', 'x_s_stuid', 's_stuid', "tbl_prep_programs.s_stuid", 3, -1, FALSE);
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
		return "SELECT tbl_students.s_studentid, tbl_students.s_first_name, tbl_students.s_middle_name, tbl_students.s_last_name, tbl_prep_programs.p_prepid, tbl_prep_programs.p_arithmetic, tbl_prep_programs.p_algebra, tbl_prep_programs.p_techniques, tbl_prep_programs.p_geometry, tbl_prep_programs.p_advanced_topics, tbl_prep_programs.p_sentence_completion, tbl_prep_programs.p_critical_reading, tbl_prep_programs.p_error_id, tbl_prep_programs.p_sentence_improvement, tbl_prep_programs.p_paragraph_improvement, tbl_prep_programs.s_stuid FROM tbl_students, tbl_prep_programs";
	}

	function SqlWhere() { // Where
		return "(tbl_students.s_studentid = tbl_prep_programs.s_stuid)";
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
		return "INSERT INTO tbl_students, tbl_prep_programs ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE tbl_students, tbl_prep_programs SET ";
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
		$SQL = "DELETE FROM tbl_students, tbl_prep_programs WHERE ";
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
			return "vwstudentprepprogramlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("vwstudentprepprogramview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("vwstudentprepprogramedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("vwstudentprepprogramlist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("vwstudentprepprogramadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("vwstudentprepprogramlist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("vwstudentprepprogramdelete.php");
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
		$this->p_prepid->setDbValue($rs->fields('p_prepid'));
		$this->p_arithmetic->setDbValue($rs->fields('p_arithmetic'));
		$this->p_algebra->setDbValue($rs->fields('p_algebra'));
		$this->p_techniques->setDbValue($rs->fields('p_techniques'));
		$this->p_geometry->setDbValue($rs->fields('p_geometry'));
		$this->p_advanced_topics->setDbValue($rs->fields('p_advanced_topics'));
		$this->p_sentence_completion->setDbValue($rs->fields('p_sentence_completion'));
		$this->p_critical_reading->setDbValue($rs->fields('p_critical_reading'));
		$this->p_error_id->setDbValue($rs->fields('p_error_id'));
		$this->p_sentence_improvement->setDbValue($rs->fields('p_sentence_improvement'));
		$this->p_paragraph_improvement->setDbValue($rs->fields('p_paragraph_improvement'));
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

		// p_arithmetic
		$this->p_arithmetic->ViewValue = $this->p_arithmetic->CurrentValue;
		$this->p_arithmetic->CssStyle = "";
		$this->p_arithmetic->CssClass = "";
		$this->p_arithmetic->ViewCustomAttributes = "";

		// p_algebra
		$this->p_algebra->ViewValue = $this->p_algebra->CurrentValue;
		$this->p_algebra->CssStyle = "";
		$this->p_algebra->CssClass = "";
		$this->p_algebra->ViewCustomAttributes = "";

		// p_techniques
		$this->p_techniques->ViewValue = $this->p_techniques->CurrentValue;
		$this->p_techniques->CssStyle = "";
		$this->p_techniques->CssClass = "";
		$this->p_techniques->ViewCustomAttributes = "";

		// p_geometry
		$this->p_geometry->ViewValue = $this->p_geometry->CurrentValue;
		$this->p_geometry->CssStyle = "";
		$this->p_geometry->CssClass = "";
		$this->p_geometry->ViewCustomAttributes = "";

		// p_advanced_topics
		$this->p_advanced_topics->ViewValue = $this->p_advanced_topics->CurrentValue;
		$this->p_advanced_topics->CssStyle = "";
		$this->p_advanced_topics->CssClass = "";
		$this->p_advanced_topics->ViewCustomAttributes = "";

		// p_sentence_completion
		$this->p_sentence_completion->ViewValue = $this->p_sentence_completion->CurrentValue;
		$this->p_sentence_completion->CssStyle = "";
		$this->p_sentence_completion->CssClass = "";
		$this->p_sentence_completion->ViewCustomAttributes = "";

		// p_critical_reading
		$this->p_critical_reading->ViewValue = $this->p_critical_reading->CurrentValue;
		$this->p_critical_reading->CssStyle = "";
		$this->p_critical_reading->CssClass = "";
		$this->p_critical_reading->ViewCustomAttributes = "";

		// p_error_id
		$this->p_error_id->ViewValue = $this->p_error_id->CurrentValue;
		$this->p_error_id->CssStyle = "";
		$this->p_error_id->CssClass = "";
		$this->p_error_id->ViewCustomAttributes = "";

		// p_sentence_improvement
		$this->p_sentence_improvement->ViewValue = $this->p_sentence_improvement->CurrentValue;
		$this->p_sentence_improvement->CssStyle = "";
		$this->p_sentence_improvement->CssClass = "";
		$this->p_sentence_improvement->ViewCustomAttributes = "";

		// p_paragraph_improvement
		$this->p_paragraph_improvement->ViewValue = $this->p_paragraph_improvement->CurrentValue;
		$this->p_paragraph_improvement->CssStyle = "";
		$this->p_paragraph_improvement->CssClass = "";
		$this->p_paragraph_improvement->ViewCustomAttributes = "";

		// s_first_name
		$this->s_first_name->HrefValue = "";

		// s_middle_name
		$this->s_middle_name->HrefValue = "";

		// s_last_name
		$this->s_last_name->HrefValue = "";

		// p_arithmetic
		$this->p_arithmetic->HrefValue = "";

		// p_algebra
		$this->p_algebra->HrefValue = "";

		// p_techniques
		$this->p_techniques->HrefValue = "";

		// p_geometry
		$this->p_geometry->HrefValue = "";

		// p_advanced_topics
		$this->p_advanced_topics->HrefValue = "";

		// p_sentence_completion
		$this->p_sentence_completion->HrefValue = "";

		// p_critical_reading
		$this->p_critical_reading->HrefValue = "";

		// p_error_id
		$this->p_error_id->HrefValue = "";

		// p_sentence_improvement
		$this->p_sentence_improvement->HrefValue = "";

		// p_paragraph_improvement
		$this->p_paragraph_improvement->HrefValue = "";
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
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM tbl_students, tbl_prep_programs WHERE " . $this->AddUserIDFilter("", $userid);

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
