<?php
class Sidebar {
	public function Sidebar($sideBarName=""){
		if($sideBarName==""){
			$sideBarName="Left";
		}
    	echo '<div id="sidebar'.$sideBarName.'" class="sidebar'.$sideBarName.'"><ul class="sidebarContent">';
    	dynamic_sidebar($sideBarName);
   		echo '</ul></div>';
	}
}
?>