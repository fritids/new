<?php

// configuration for Table tbl_students
$tbl_students = new ctbl_students; // Initialize table object

// Define table class
class ctbl_students {

	// Define table level constants
	var $TableVar;
	var $TableName;
	var $SelectLimit = FALSE;
	var $s_studentid;
	var $s_first_name;
	var $s_middle_name;
	var $s_last_name;
	var $s_address;
	var $s_city;
	var $s_postal_code;
	var $s_state;
	var $s_country;
	var $s_home_phone;
	var $s_student_mobile;
	var $s_student_email;
	var $s_parent_name;
	var $s_parent_mobile;
	var $s_parent_email;
	var $s_school;
	var $s_graduation_year;
	var $s_usrname;
	var $s_pwd;
	var $i_instructid;
	var $g_grpid;
	var $fields = array();

	function ctbl_students() {
		$this->TableVar = "tbl_students";
		$this->TableName = "tbl_students";
		$this->SelectLimit = TRUE;
		$this->s_studentid = new cField('tbl_students', 'x_s_studentid', 's_studentid', "`s_studentid`", 3, -1, FALSE);
		$this->fields['s_studentid'] =& $this->s_studentid;
		$this->s_first_name = new cField('tbl_students', 'x_s_first_name', 's_first_name', "`s_first_name`", 200, -1, FALSE);
		$this->fields['s_first_name'] =& $this->s_first_name;
		$this->s_middle_name = new cField('tbl_students', 'x_s_middle_name', 's_middle_name', "`s_middle_name`", 200, -1, FALSE);
		$this->fields['s_middle_name'] =& $this->s_middle_name;
		$this->s_last_name = new cField('tbl_students', 'x_s_last_name', 's_last_name', "`s_last_name`", 200, -1, FALSE);
		$this->fields['s_last_name'] =& $this->s_last_name;
		$this->s_address = new cField('tbl_students', 'x_s_address', 's_address', "`s_address`", 200, -1, FALSE);
		$this->fields['s_address'] =& $this->s_address;
		$this->s_city = new cField('tbl_students', 'x_s_city', 's_city', "`s_city`", 200, -1, FALSE);
		$this->fields['s_city'] =& $this->s_city;
		$this->s_postal_code = new cField('tbl_students', 'x_s_postal_code', 's_postal_code', "`s_postal_code`", 200, -1, FALSE);
		$this->fields['s_postal_code'] =& $this->s_postal_code;
		$this->s_state = new cField('tbl_students', 'x_s_state', 's_state', "`s_state`", 200, -1, FALSE);
		$this->fields['s_state'] =& $this->s_state;
		$this->s_country = new cField('tbl_students', 'x_s_country', 's_country', "`s_country`", 200, -1, FALSE);
		$this->fields['s_country'] =& $this->s_country;
		$this->s_home_phone = new cField('tbl_students', 'x_s_home_phone', 's_home_phone', "`s_home_phone`", 200, -1, FALSE);
		$this->fields['s_home_phone'] =& $this->s_home_phone;
		$this->s_student_mobile = new cField('tbl_students', 'x_s_student_mobile', 's_student_mobile', "`s_student_mobile`", 200, -1, FALSE);
		$this->fields['s_student_mobile'] =& $this->s_student_mobile;
		$this->s_student_email = new cField('tbl_students', 'x_s_student_email', 's_student_email', "`s_student_email`", 200, -1, FALSE);
		$this->fields['s_student_email'] =& $this->s_student_email;
		$this->s_parent_name = new cField('tbl_students', 'x_s_parent_name', 's_parent_name', "`s_parent_name`", 200, -1, FALSE);
		$this->fields['s_parent_name'] =& $this->s_parent_name;
		$this->s_parent_mobile = new cField('tbl_students', 'x_s_parent_mobile', 's_parent_mobile', "`s_parent_mobile`", 200, -1, FALSE);
		$this->fields['s_parent_mobile'] =& $this->s_parent_mobile;
		$this->s_parent_email = new cField('tbl_students', 'x_s_parent_email', 's_parent_email', "`s_parent_email`", 200, -1, FALSE);
		$this->fields['s_parent_email'] =& $this->s_parent_email;
		$this->s_school = new cField('tbl_students', 'x_s_school', 's_school', "`s_school`", 200, -1, FALSE);
		$this->fields['s_school'] =& $this->s_school;
		$this->s_graduation_year = new cField('tbl_students', 'x_s_graduation_year', 's_graduation_year', "`s_graduation_year`", 200, -1, FALSE);
		$this->fields['s_graduation_year'] =& $this->s_graduation_year;
		$this->s_usrname = new cField('tbl_students', 'x_s_usrname', 's_usrname', "`s_usrname`", 200, -1, FALSE);
		$this->fields['s_usrname'] =& $this->s_usrname;
		$this->s_pwd = new cField('tbl_students', 'x_s_pwd', 's_pwd', "`s_pwd`", 200, -1, FALSE);
		$this->fields['s_pwd'] =& $this->s_pwd;
		$this->i_instructid = new cField('tbl_students', 'x_i_instructid', 'i_instructid', "`i_instructid`", 3, -1, FALSE);
		$this->fields['i_instructid'] =& $this->i_instructid;
		$this->g_grpid = new cField('tbl_students', 'x_g_grpid', 'g_grpid', "`g_grpid`", 3, -1, FALSE);
		$this->fields['g_grpid'] =& $this->g_grpid;
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
		return "SELECT * FROM `tbl_students`";
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
			if (EW_MD5_PASSWORD && $name == 's_pwd') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? md5($value) : md5(strtolower($value));
			}
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= (is_null($value) ? "NULL" : ew_QuotedValue($value, $this->fields[$name]->FldDataType)) . ",";
		}
		if (substr($names, -1) == ",") $names = substr($names, 0, strlen($names)-1);
		if (substr($values, -1) == ",") $values = substr($values, 0, strlen($values)-1);
		return "INSERT INTO `tbl_students` ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		$SQL = "UPDATE `tbl_students` SET ";
		foreach ($rs as $name => $value) {
			if (EW_MD5_PASSWORD && $name == 's_pwd') {
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
		$SQL = "DELETE FROM `tbl_students` WHERE ";
		$SQL .= EW_DB_QUOTE_START . 's_studentid' . EW_DB_QUOTE_END . '=' .	ew_QuotedValue($rs['s_studentid'], $this->s_studentid->FldDataType) . ' AND ';
		$SQL .= EW_DB_QUOTE_START . 's_usrname' . EW_DB_QUOTE_END . '=' .	ew_QuotedValue($rs['s_usrname'], $this->s_usrname->FldDataType) . ' AND ';
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter for table
	function SqlKeyFilter() {
		return "`s_studentid` = @s_studentid@ AND `s_usrname` = '@s_usrname@'";
	}

	// Return url
	function getReturnUrl() {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] <> "") {
			return $_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL];
		} else {
			return "tbl_studentslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// View url
	function ViewUrl() {
		return $this->KeyUrl("tbl_studentsview.php");
	}

	// Edit url
	function EditUrl() {
		return $this->KeyUrl("tbl_studentsedit.php");
	}

	// Inline edit url
	function InlineEditUrl() {
		return $this->KeyUrl("tbl_studentslist.php", "a=edit");
	}

	// Copy url
	function CopyUrl() {
		return $this->KeyUrl("tbl_studentsadd.php");
	}

	// Inline copy url
	function InlineCopyUrl() {
		return $this->KeyUrl("tbl_studentslist.php", "a=copy");
	}

	// Delete url
	function DeleteUrl() {
		return $this->KeyUrl("tbl_studentsdelete.php");
	}

	// Key url
	function KeyUrl($url, $action = "") {
		$sUrl = $url . "?";
		if ($action <> "") $sUrl .= $action . "&";
		if (!is_null($this->s_studentid->CurrentValue)) {
			$sUrl .= "s_studentid=" . urlencode($this->s_studentid->CurrentValue);
		} else {
			return "javascript:alert('Invalid Record! Key is null');";
		}
		if (!is_null($this->s_usrname->CurrentValue)) {
			$sUrl .= "&s_usrname=" . urlencode($this->s_usrname->CurrentValue);
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
		$this->s_studentid->setDbValue($rs->fields('s_studentid'));
		$this->s_first_name->setDbValue($rs->fields('s_first_name'));
		$this->s_middle_name->setDbValue($rs->fields('s_middle_name'));
		$this->s_last_name->setDbValue($rs->fields('s_last_name'));
		$this->s_address->setDbValue($rs->fields('s_address'));
		$this->s_city->setDbValue($rs->fields('s_city'));
		$this->s_postal_code->setDbValue($rs->fields('s_postal_code'));
		$this->s_state->setDbValue($rs->fields('s_state'));
		$this->s_country->setDbValue($rs->fields('s_country'));
		$this->s_home_phone->setDbValue($rs->fields('s_home_phone'));
		$this->s_student_mobile->setDbValue($rs->fields('s_student_mobile'));
		$this->s_student_email->setDbValue($rs->fields('s_student_email'));
		$this->s_parent_name->setDbValue($rs->fields('s_parent_name'));
		$this->s_parent_mobile->setDbValue($rs->fields('s_parent_mobile'));
		$this->s_parent_email->setDbValue($rs->fields('s_parent_email'));
		$this->s_school->setDbValue($rs->fields('s_school'));
		$this->s_graduation_year->setDbValue($rs->fields('s_graduation_year'));
		$this->s_usrname->setDbValue($rs->fields('s_usrname'));
		$this->s_pwd->setDbValue($rs->fields('s_pwd'));
		$this->i_instructid->setDbValue($rs->fields('i_instructid'));
		$this->g_grpid->setDbValue($rs->fields('g_grpid'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// s_studentid
		$this->s_studentid->ViewValue = $this->s_studentid->CurrentValue;
		$this->s_studentid->CssStyle = "";
		$this->s_studentid->CssClass = "";
		$this->s_studentid->ViewCustomAttributes = "";

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

		// s_address
		$this->s_address->ViewValue = $this->s_address->CurrentValue;
		$this->s_address->CssStyle = "";
		$this->s_address->CssClass = "";
		$this->s_address->ViewCustomAttributes = "";

		// s_city
		$this->s_city->ViewValue = $this->s_city->CurrentValue;
		$this->s_city->CssStyle = "";
		$this->s_city->CssClass = "";
		$this->s_city->ViewCustomAttributes = "";

		// s_postal_code
		$this->s_postal_code->ViewValue = $this->s_postal_code->CurrentValue;
		$this->s_postal_code->CssStyle = "";
		$this->s_postal_code->CssClass = "";
		$this->s_postal_code->ViewCustomAttributes = "";

		// s_state
		$this->s_state->ViewValue = $this->s_state->CurrentValue;
		$this->s_state->CssStyle = "";
		$this->s_state->CssClass = "";
		$this->s_state->ViewCustomAttributes = "";

		// s_country
		$this->s_country->ViewValue = $this->s_country->CurrentValue;
		$this->s_country->CssStyle = "";
		$this->s_country->CssClass = "";
		$this->s_country->ViewCustomAttributes = "";

		// s_home_phone
		$this->s_home_phone->ViewValue = $this->s_home_phone->CurrentValue;
		$this->s_home_phone->CssStyle = "";
		$this->s_home_phone->CssClass = "";
		$this->s_home_phone->ViewCustomAttributes = "";

		// s_student_mobile
		$this->s_student_mobile->ViewValue = $this->s_student_mobile->CurrentValue;
		$this->s_student_mobile->CssStyle = "";
		$this->s_student_mobile->CssClass = "";
		$this->s_student_mobile->ViewCustomAttributes = "";

		// s_student_email
		$this->s_student_email->ViewValue = $this->s_student_email->CurrentValue;
		$this->s_student_email->CssStyle = "";
		$this->s_student_email->CssClass = "";
		$this->s_student_email->ViewCustomAttributes = "";

		// s_parent_name
		$this->s_parent_name->ViewValue = $this->s_parent_name->CurrentValue;
		$this->s_parent_name->CssStyle = "";
		$this->s_parent_name->CssClass = "";
		$this->s_parent_name->ViewCustomAttributes = "";

		// s_parent_mobile
		$this->s_parent_mobile->ViewValue = $this->s_parent_mobile->CurrentValue;
		$this->s_parent_mobile->CssStyle = "";
		$this->s_parent_mobile->CssClass = "";
		$this->s_parent_mobile->ViewCustomAttributes = "";

		// s_parent_email
		$this->s_parent_email->ViewValue = $this->s_parent_email->CurrentValue;
		$this->s_parent_email->CssStyle = "";
		$this->s_parent_email->CssClass = "";
		$this->s_parent_email->ViewCustomAttributes = "";

		// s_school
		$this->s_school->ViewValue = $this->s_school->CurrentValue;
		$this->s_school->CssStyle = "";
		$this->s_school->CssClass = "";
		$this->s_school->ViewCustomAttributes = "";

		// s_graduation_year
		$this->s_graduation_year->ViewValue = $this->s_graduation_year->CurrentValue;
		$this->s_graduation_year->CssStyle = "";
		$this->s_graduation_year->CssClass = "";
		$this->s_graduation_year->ViewCustomAttributes = "";

		// s_usrname
		$this->s_usrname->ViewValue = $this->s_usrname->CurrentValue;
		$this->s_usrname->CssStyle = "";
		$this->s_usrname->CssClass = "";
		$this->s_usrname->ViewCustomAttributes = "";

		// s_pwd
		$this->s_pwd->ViewValue = "********";
		$this->s_pwd->CssStyle = "";
		$this->s_pwd->CssClass = "";
		$this->s_pwd->ViewCustomAttributes = "";

		// s_studentid
		$this->s_studentid->HrefValue = "";

		// s_first_name
		$this->s_first_name->HrefValue = "";

		// s_middle_name
		$this->s_middle_name->HrefValue = "";

		// s_last_name
		$this->s_last_name->HrefValue = "";

		// s_address
		$this->s_address->HrefValue = "";

		// s_city
		$this->s_city->HrefValue = "";

		// s_postal_code
		$this->s_postal_code->HrefValue = "";

		// s_state
		$this->s_state->HrefValue = "";

		// s_country
		$this->s_country->HrefValue = "";

		// s_home_phone
		$this->s_home_phone->HrefValue = "";

		// s_student_mobile
		$this->s_student_mobile->HrefValue = "";

		// s_student_email
		$this->s_student_email->HrefValue = "";

		// s_parent_name
		$this->s_parent_name->HrefValue = "";

		// s_parent_mobile
		$this->s_parent_mobile->HrefValue = "";

		// s_parent_email
		$this->s_parent_email->HrefValue = "";

		// s_school
		$this->s_school->HrefValue = "";

		// s_graduation_year
		$this->s_graduation_year->HrefValue = "";

		// s_usrname
		$this->s_usrname->HrefValue = "";

		// s_pwd
		$this->s_pwd->HrefValue = "";
	}

	// User id filter
	function UserIDFilter() {
		return "`s_studentid`=@UserID@";
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
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `tbl_students` WHERE " . $this->AddUserIDFilter("", $userid);

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
