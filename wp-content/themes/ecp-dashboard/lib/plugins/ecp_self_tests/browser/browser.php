<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/custom-theme/jquery-ui-1.8.2.custom.css"/>
<script type="text/javascript" src="js/abz_ftp_file_browser.js"></script>
<style type="text/css">
	#abz_browse:hover 
	{
		cursor:pointer;
	}
	.abz_file, .abz_dir, .abz_parent
	{
		width:400px;
		height:30px;
		padding:5px;
		margin:5px;
		cursor:pointer;
	}
	.abz_file:hover, .abz_parent:hover, .abz_dir:hover 
	{
		background:#e0e0e0;
		color:#fff;
	}
	
	.abz_attr
	{
		float:left;
		height:32px;
		line-height:32px;
		margin-left:10px; 
	}
	
	.abz_icon
	{
		float:left;
	}
	
	.selected_file
	{
		background:#e0e0e0;
	}
	
	#abz_preloader
	{
		height:20px;
		background: url(images/ajax-loader.gif) no-repeat center;
		display:none;
	}
</style>

<div id='abz_browse'>
Browse
</div>
<div id='abz_dialog'>
	<div style="width:807px;">
		<div id='abz_holder' style="width:400px; float:left;">
		</div>
		<div id="preview_player" style="width:400px; float:left;"></div>
	</div> 
	<div id='abz_preloader'>
	</div>
</div>
<div>
	<input type='hidden' name='selected_file' id='abz_selected_file'/>
</div>