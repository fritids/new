<?php

// configuration for Table tbl_prep_programs
$tbl_prep_programs = new ctbl_prep_programs; // Initialize table object

// Define table class
class ctbl_prep_programs {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
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

	function ctbl_prep_programs() {
		$this->TableVar = "tbl_prep_programs";
		$this->TableName = "tbl_prep_programs";
		$this->SelectLimit = TRUE;
		$this->p_prepid = new cField('tbl_prep_programs', 'x_p_prepid', 'p_prepid', "`p_prepid`", 3, -1, FALSE);
		$this->fields['p_prepid'] =& $this->p_prepid;
		$this->p_arithmetic = new cField('tbl_prep_programs', 'x_p_arithmetic', 'p_arithmetic', "`p_arithmetic`", 200, -1, FALSE);
		$this->fields['p_arithmetic'] =& $this->p_arithmetic;
		$this->p_algebra = new cField('tbl_prep_programs', 'x_p_algebra', 'p_algebra', "`p_algebra`", 200, -1, FALSE);
		$this->fields['p_algebra'] =& $this->p_algebra;
		$this->p_techniques = new cField('tbl_prep_programs', 'x_p_techniques', 'p_techniques', "`p_techniques`", 200, -1, FALSE);
		$this->fields['p_techniques'] =& $this->p_techniques;
		$this->p_geometry = new cField('tbl_prep_programs', 'x_p_geometry', 'p_geometry', "`p_geometry`", 200, -1, FALSE);
		$this->fields['p_geometry'] =& $this->p_geometry;
		$this->p_advanced_topics = new cField('tbl_prep_programs', 'x_p_advanced_topics', 'p_advanced_topics', "`p_advanced_topics`", 200, -1, FALSE);
		$this->fields['p_advanced_topics'] =& $this->p_advanced_topics;
		$this->p_sentence_completion = new cField('tbl_prep_programs', 'x_p_sentence_completion', 'p_sentence_completion', "`p_sentence_completion`", 200, -1, FALSE);
		$this->fields['p_sentence_completion'] =& $this->p_sentence_completion;
		$this->p_critical_reading = new cField('tbl_prep_programs', 'x_p_critical_reading', 'p_critical_reading', "`p_critical_reading`", 200, -1, FALSE);
		$this->fields['p_critical_reading'] =& $this->p_critical_reading;
		$this->p_error_id = new cField('tbl_prep_programs', 'x_p_error_id', 'p_error_id', "`p_error_id`", 200, -1, FALSE);
		$this->fields['p_error_id'] =& $this->p_error_id;
		$this->p_sentence_improvement = new cField('tbl_prep_programs', 'x_p_sentence_improvement', 'p_sentence_improvement', "`p_sentence_improvement`", 200, -1, FALSE);
		$this->fields['p_sentence_improvement'] =& $this->p_sentence_improvement;
		$this->p_paragraph_improvement = new cField('tbl_prep_programs', 'x_p_paragraph_improvement', 'p_paragraph_improvement', "`p_paragraph_improvement`", 200, -1, FALSE);
		$this->fields['p_paragraph_improvement'] =& $this->p_paragraph_improvement;
		$this->s_stuid = new cField('tbl_prep_programs', 'x_s_stuid', 's_stuid', "`s_stuid`", 3, -1, FALSE);
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
		return "SELECT * FROM `tbl_prep_programs`";
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
		return "INSERT INTO `tbl_prep_programs` ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE `tbl_prep_programs` SET ";
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
		$SQL = "DELETE FROM `tbl_prep_programs` WHERE ";
		$SQL .= EW_DB_QUOTE_START . 'p_prepid' . EW_DB_QUOTE_END . '=' .	ew_QuotedValue($rs['p_prepid'], $this->p_prepid->FldDataType) . ' AND ';
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter for table
	function SqlKeyFilter() {
		return "`p_prepid` = @p_prepid@";
	}

	// Return url
	function getReturnUrl() {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] <> "") {
			return $_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL];
		} else {
			return "tbl_prep_programslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("tbl_prep_programsview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("tbl_prep_programsedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("tbl_prep_programslist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("tbl_prep_programsadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("tbl_prep_programslist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("tbl_prep_programsdelete.php");
	}

	// Key url
	function KeyUrl($url, $action = "") {
		$sUrl = $url . "?";
		if ($action <> "") $sUrl .= $action . "&";
		if (!is_null($this->p_prepid->CurrentValue)) {
			$sUrl .= "p_prepid=" . urlencode($this->p_prepid->CurrentValue);
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

		// p_prepid
		$this->p_prepid->ViewValue = $this->p_prepid->CurrentValue;
		$this->p_prepid->CssStyle = "";
		$this->p_prepid->CssClass = "";
		$this->p_prepid->ViewCustomAttributes = "";

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

		// s_stuid
		$this->s_stuid->ViewValue = $this->s_stuid->CurrentValue;
		$this->s_stuid->CssStyle = "";
		$this->s_stuid->CssClass = "";
		$this->s_stuid->ViewCustomAttributes = "";

		// p_prepid
		$this->p_prepid->HrefValue = "";

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
