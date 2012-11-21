<?php
class Templator
{
	/*
	 * Function - loads given template by name
	 * 
	 * @name - string				name of the template
	 * @params - array				template data. Defaults to empty array
	 */
	public static function getTemplate($name, $params = NULL, $location = "")
	{
		ob_start();		
		require "$location{$name}.php";
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}
// dummy comment here
?>