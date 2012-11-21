<?php


/**
 * 
 * Class for creating sidebar...
 * @author Abuzz
 *
 */
class Sidebar {
	
	/**
	 * 
	 * Registers sidebar by name...
	 * @param string $sideBarName
	 */
	public function Sidebar($sideBarName=""){
		if($sideBarName==""){
			$sideBarName="Left";
		}
    	dynamic_sidebar($sideBarName);
	}
}
?>