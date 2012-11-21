<html>
<head>
	<title>The EDGE In College Preparation  |  College Admission and SAT /  ACT Preparation  |  New York, London</title>
<link href="astyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript">
<!--
var EW_DATE_SEPARATOR; // Default date separator
EW_DATE_SEPARATOR = "/";
if (EW_DATE_SEPARATOR == '') EW_DATE_SEPARATOR = '/';
EW_UPLOAD_ALLOWED_FILE_EXT = "gif,jpg,jpeg,bmp,png,doc,xls,pdf,zip"; // Allowed upload file extension
var EW_FIELD_SEP = ', '; // Default field separator
var EW_TABLE_CLASSNAME = "ewTable"; // Note: changed the class name as needed

// Ajax settings
EW_LOOKUP_FILE_NAME = "ewlookup50.php"; // lookup file name
EW_ADD_OPTION_FILE_NAME = "ewaddopt50.php"; // add option file name

// Auto suggest settings
var EW_AST_SELECT_LIST_ITEM = 0;
var EW_AST_TEXT_BOX_ID;
var EW_AST_CANCEL_SUBMIT;
var EW_AST_OLD_TEXT_BOX_VALUE = "";
var EW_AST_MAX_NEW_VALUE_LENGTH = 5; // Only get data if value length <= this setting

// Multipage settings
var ew_PageIndex = 0;
var ew_MaxPageIndex = 0;
var ew_MinPageIndex = 0;
var ew_MultiPageElements = new Array();

//-->
</script>
<script type="text/javascript" src="ewp50.js"></script>
<script type="text/javascript" src="userfn50.js"></script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js");
//-->

</script>
<div class="parentdiv">
<table class="parenttable" width="901" border="0" cellspacing="0" cellpadding="0" style="margin:auto">
	<!-- header (begin) -->
	<tr class="ewHeaderRow">
	<td><img src="images/home_01.jpg" alt="" border="0"></td>
	</tr>
	<!-- header (end) -->
	<!-- content (begin) -->
	<tr>
		<td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<!-- left column (begin) -->
		<td valign="top" class="ewMenuColumn">
<?php include "ewmenu.php" ?>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr><td width="100%" class="menutext"><!-- Area below Left Nav --><br/><br/>
				  <br/><br/>
				    "Not only did two of us get perfect scores (on the hardest SAT II!!),  but we actually had fun with our tutor and looked forward to our  sessions."<br/><br/><br/><br/><br/><p class="contactinfo">&nbsp;</p>
			      <br/>


<?php 
$temp =  Currentusername();


mysql_connect("205.178.146.71", "baracuda", "henlengA1") or die(mysql_error());
mysql_select_db("edgetest") or die(mysql_error());

$result = mysql_query("SELECT * FROM tbl_aduser
 WHERE a_uname='$temp'") or die(mysql_error());  

$row = mysql_fetch_array( $result );
?>




</td>
				</tr> 
			</table> 
		</td>
		<!-- left column (end) -->
		<!-- right column (begin) -->
		<td valign="top" class="ewContentColumn">

<div class="loggedinuser"><?php if (IsLoggedIn()) { ?>Logged in as admin : <?php echo $row['a_first_name']." ".$row['a_last_name']; ?><?php } ?></div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td><span class="edge"><b></b></span></td></tr>
</table>
