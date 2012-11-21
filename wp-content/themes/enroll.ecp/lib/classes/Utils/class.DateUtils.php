<?php
class DateUtils
{
	private $array_map;
	private $days_arr;
	private $months_arr;
	private $years_arr;
	private $cd;
	private $cm;
	private $cy;
	
	public function __construct()
	{
		$this -> array_map = array();
		$this -> cd = date("d");
		$this -> cm = date("F");
		$this -> cy = date("Y");
		$this -> days_arr = range(1, 31);
		$this -> months_arr = array("January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December");
		$this -> years_arr = range(1980, date('Y'));
	}
	
	public function printDateOptions($type, $sel_value = NULL)
	{
		$opt = "";
		switch ($type)
		{
			case "days":
				$this -> checkMonth();
				if(!is_null($sel_value)) { $this -> cd = $sel_value; }
				foreach($this -> days_arr as $day)
				{
					$selected = "";
					if($day == $this -> cd) { $selected = "selected=selected"; }
					$opt .= "<option value='{$day}' {$selected}>{$day}</option>";
				}
			break;
			case "months":
				if(!is_null($sel_value)) { $this -> cm = $sel_value; }
				foreach($this -> months_arr as $month)
				{
					$selected = "";
					if($month == $this -> cm) { $selected = "selected=selected"; }
					$opt .= "<option value='{$month}' {$selected}>{$month}</option>";
				}
			break;
			case "num_months":
				if(!is_null($sel_value)) { $this -> cm = $sel_value; }
				$prefix = "0";
				for($i = 1, $len = sizeof($this -> months_arr); $i <= $len; $i++)
				{
					$selected = "";
					if($i > 9) {$prefix = "";}
					$opt .= "<option value='{$prefix}{$i}' {$selected}>{$prefix}{$i}</option>";
				}
			break;
			case "years":
				if(!is_null($sel_value)) { $this -> cy = $sel_value; }
				foreach($this -> years_arr as $year)
				{
					$selected = "";
					if($year == $this -> cy) { $selected = "selected=selected"; }
					$opt .= "<option value='{$year}' {$selected}>{$year}</option>";
				}
			break;
		}
		
		return $opt;
	}
	
	/*
	 * Creates months in numeric format (0X)
	 */
	public function getNumMonths()
	{
		$temp_arr = array();
		$prefix = "0";
		for($i = 1, $len = sizeof($this -> months_arr); $i <= $len; $i++)
		{
			$selected = "";
			if($i > 9) {$prefix = "";}
			$temp_arr[$prefix.$i] = $prefix.$i;
		}
		return $temp_arr;
	}
	
	/*
	 * Creates months in numeric format (0X)
	 */
	public function getYears()
	{
		$temp_arr = array();
		$prefix = "0";
		foreach($this -> years_arr as $year)
		{
			$temp_arr[$year] = $year;
		}
		return $temp_arr;
	}
	
	/*
	 * Sets range to $range year point
	 */
	public function setYearRangeFromToday($range,$start=0)
	{
		if($start==0){
			$start=$this -> years_arr[0];
		}
		$this -> years_arr = range($start, $range);
	}
	
	/*
	 * 
	 */
	public function setMonthYearDropdown($yRange, $sel_value = NULL)
	{
		$out = "";
		$this -> cy = date("Y");
		$this -> cm = date("F");
		$curr_month_index = array_search($this -> cm, $this -> months_arr);
		for($i = $curr_month_index; $i < sizeof($this -> months_arr); $i++)
		{
			$selected = "";
			if($sel_value == $this -> months_arr[$i]." ". $this -> cy) { $selected = "selected='selected'"; }
			$out .= "<option value='".$this -> months_arr[$i]."_". $this -> cy."' {$selected}>".$this -> months_arr[$i]." ". $this -> cy."</option>";
		}
		
		if($yRange > $this -> cy)
		{ 
			$this -> setYearRangeFromToday($yRange);
			array_shift($this -> years_arr);
			foreach($this -> years_arr as $year)
			{
				foreach ($this -> months_arr as $month)
				{
					$selected = "";
					if($sel_value == $month." ".$year) { $selected = "selected='selected'"; }
					$out .= "<option value='".$month."_".$year."' {$selected}>".$month." ".$year."</option>";
				}
			}
		}
		$this -> setDefaults();
		return $out;
	}
	
	private function setDefaults()
	{
		$this -> years_arr = range(1900, date('Y'));
	}
	
	/*
	 * Checks for month
	 */
	private function checkMonth()
	{
		if($this -> cm == "February")
		{
			$this -> days_arr = range(1, 28); 
			if($this -> checkLeapYear){ $this -> days_arr = range(1, 29); }
		}
		
		if($this -> cm == "April" || $this -> cm == "June" || $this -> cm == "September" || $this -> cm == "November")
		{
			$this -> days_arr = range(1, 30); 
		}
	}
	
	/*
	 * Checks if year is a leap one
	 */
	private function checkLeapYear()
	{
		$year = (int) $this -> cy;
		if($year % 4 == 0)
		{
			if($year % 100 != 0)
			{
				return true;
			}
			elseif($year % 400 == 0)
			{
				return true;
			}
		}
		return false;
	}
}
?>