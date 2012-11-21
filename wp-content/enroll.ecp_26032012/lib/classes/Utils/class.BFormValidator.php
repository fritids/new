<?php

/**
 * 
 * Form Validation class
 * 
 * example: $validation = new BFormValidator($fields); // where fields is $_POST or $_GET
 * 			$validation->validate('username')->vRequired('Empty Username')->vAlpha('Contains non-alpha String');
 * 			if($validation->checkErrors()){
 * 			echo $validation->displayErrors();
 * 			}
 * 			else{
 * 			// No errors
 * 			}
 * 
 * @author Bojan Naumoski
 *
 */

class BFormValidator{
	
    private $errors=array();
    private $fields=array();
    private $field;
    private $errfield;
    
    /**
     * 
     * Constructor
     * @param array $fields all fields from $_POST or $_GET
     */
    
    public function __construct($fields){
   		if(isset($fields)){
   			$this->fields = $fields;
   		}
   	}
   	
   	/**
   	 * 
   	 * First function to call in chain
   	 * @param string $field Key of the field name
   	 */
   	
   	public function validate($field){
   		if(array_key_exists($field, $this->fields)){
   			$this->field = trim($this->fields[$field]);
   			$this->errfield = $field;
   			return $this;
   		}
   		else{
   			return false;
   		}
   	}
   	   	
   	/**
     * 
     * Validate if its required field
     * @param string $errorMessage
     */
   	
    public function vRequired($errorMessage)
    {
    	
        if(trim($this->field)=='')
        {
           $this->setError($errorMessage);
        }
     return $this;
    }
    
    /**
     * 
     * Validate Minimum length of string
     * @param string $errorMessage
     * @param int $min
     */
    public function vMinLength($errorMessage,$min){
    	//$this->vRequired('Empty Field $');
    	if((strlen($this->field)<$min) && (!empty($this->field))){
    		$this->setError($errorMessage);
    	}
    	return $this;
    }
    /**
     * 
     * Validate Maximum length of string
     * @param string $errorMessage
     * @param int $max
     */
    public function vMaxLength($errorMessage,$max){
    	if(strlen($this->field)>$max){
    		$this->setError($errorMessage);
    	}
    	return $this;
    }
    
    /**
     * 
     * Validate Minimum and Maximum length of string
     * @param string $errorMessage
     * @param int $min
     * @param int $max
     */
    public function vMinMaxLength($errorMessage,$min=1,$max=10){
    	if((strlen($this->field)<$min || strlen($this->field)>$max) && (!empty($this->field))){
    		$this->setError($errorMessage);
    	}
    	return $this;
    }
    
    /**
     * 
     * Validate if its integer
     * @param string $errorMessage
     */
    
    public function vInt($errorMessage)
    {
        if((!is_numeric($this->field) || intval($this->field)!=$this->field) && (!empty($this->field)))
        {
            $this->setError($errorMessage);
            
        }     
     return $this;   
    }
    /**
     * 
     * Validate if field is numeric
     * @param string $errorMessage
     */
    
    public function vNumber($errorMessage){
        if((!is_numeric($this->field)) && (!empty($this->field)))
        {
            $this->setError($errorMessage);
        }
     return $this;
    }
    
    /**
     * 
     * Validate Range
     * @param string $errorMessage
     * @param int $min
     * @param int $max
     */
    
    public function vRange($errorMessage,$min=1,$max=99){
        if(($this->field<$min || $this->field>$max) && (!empty($this->field))){
            $this->setError($errorMessage);
        }
    }
    /**
     * 
     * Validate from a-Z
     * @param string $errorMessage
     */
    
    public function vAlpha($errorMessage){
        if((!preg_match("/^[a-zA-Z]+$/",$this->field)) && (!empty($this->field))){
            $this->setError($errorMessage);
        }
        return $this;
    }
    /**
     * 
     * Validate from a-Z alpha and numbers 0-9
     * @param string $errorMessage
     */
    
    public function vAlphanum($errorMessage)
    {
        if((!preg_match("/^[-a-zA-Z0-9_]+$/",$this->field)) && (!empty($this->field)))
        {
           $this->setError($errorMessage);
        }
        return $this;
    }
    
    /**
     * 
     * Validates URL adresses
     * user must provide http:// or https://
     * @param unknown_type $errorMessage
     */
    public function vURL($errorMessage)
    {
    	if((!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $this->field)) && (!empty($this->field)))
		{	
		$this->setError($errorMessage);
		}
    	return $this;
    }
        
    /**
     * 
     * Validates Email
     * @param string $errorMessage
     */
    
    public function vEmail($errorMessage)
    {
    	
    	if((!filter_var($this->field, FILTER_VALIDATE_EMAIL)) && (!empty($this->field)))
        //if((!preg_match("/.+@.+\..+./",$this->field) || !checkdnsrr(array_pop(explode("@",$this->field)),"MX")) && (!empty($this->field)))
        {
            $this->setError($errorMessage);
        }
         return $this;
    }
    
    /**
     * 
     * Checks if date is valid format
     * @param string $errorMessage
     */
	public function vDate($errorMessage){
		
		if((strtotime($this->field) == FALSE) && (!empty($this->field))){
			$this->setError($errorMessage);
		}
			
		return $this;
	}

	/**
	 * 
	 * Validates Hex color with or without #...
	 * @param string $errorMessage
	 */
	public static function vColor($errorMessage)
	{
		if((!preg_match('/^#?+[0-9a-f]{3}(?:[0-9a-f]{3})?$/iD', $this->field)) && (!empty($this->field)))
		{
			$this->setError($errorMessage);
		}
		return $this;
		
	}
	
	/**
	 * checked
	 * @param string $message
	 * @return FormValidator
	 */
	public function VChecked($errorMessage = '', $value = 'on')
	{
		if($this -> field != $value || empty($this -> field)){
            $this -> setError($errorMessage);
        }
	}
	
	/**
	 * 
	 * Function related with vCreditCard, checks if numbers are ok, should not be used alone
	 * @param int $ccnum
	 */
	
	private function _checkSum($ccnum)
    {
            $checksum = 0;
            for ($i=(2-(strlen($ccnum) % 2)); $i<=strlen($ccnum); $i+=2)
            {
                $checksum += (int)($ccnum{$i-1});
            }
            // Analyze odd digits in even length strings or even digits in odd length strings.
            for ($i=(strlen($ccnum)% 2) + 1; $i<strlen($ccnum); $i+=2) 
            {
              $digit = (int)($ccnum{$i-1}) * 2;
              if ($digit < 10) 
                  { $checksum += $digit; } 
              else 
                  { $checksum += ($digit-9); }
            }
            if (($checksum % 10) == 0) 
                return true; 
            else 
                return false;
    }
	
    /**
     * 
     * 
     * Checks for credit card validation
     * @param string $errorMessage
     */
    public function vCreditCard($errorMessage)
    {
        	$ccnum = $this->field;
        	
            $creditcard=array(  "visa"=>"/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/",
                                "mastercard"=>"/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/",
                                "discover"=>"/^6011-?\d{4}-?\d{4}-?\d{4}$/",
                                "amex"=>"/^3[4,7]\d{13}$/",
                                "diners"=>"/^3[0,6,8]\d{12}$/",
                                "bankcard"=>"/^5610-?\d{4}-?\d{4}-?\d{4}$/",
                                "jcb"=>"/^[3088|3096|3112|3158|3337|3528]\d{12}$/",
                                "enroute"=>"/^[2014|2149]\d{11}$/",
                                "switch"=>"/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/");
            $match=false;
            foreach($creditcard as $type=>$pattern)
            {
              if(preg_match($pattern,$ccnum)==1)
              {
                $match=true;
                break;
              }
            }
                
            if(!$match)
            {
               $this->setError($errorMessage);
            }
            else
            {
               if(!$this->_checkSum($ccnum)){
               	$this->setError($errorMessage);
               }
            }
        return $this; 
     } 
    
    /**
     * 
     * Check for existing errors 
     * @return bool error
     */
    
    public function checkErrors()
    {
        if(count($this->errors)>0)
        {
            return true;
        }
        return false;
    }
    
    
    
    /**
     * Set error in array
     * 
     * @access private
     * @param mixed $errorMessage
     * @return void
     */
    private function setError($errorMessage)
    {
    	   
    	$this->errors[$this->errfield]=$errorMessage;
    
    }
    
    /**
     * 
     * Returns by default string with <ul><li>error</li></ul> format, if you set $asArray = true then it will return array of errors
     * @param bool $asArray
     * @return array or string depending on variable , default string
     */
    
    public function displayErrors($asArray='false')
    {
    	if($asArray){
    		return $this->errors;
    	}
    	else{
    		$errorOutput='<ul>';
	        foreach($this->errors as $err){
	            $errorOutput.='<li>'.$err.'</li>';
	        }
	        $errorOutput.='</ul>';
	        return $errorOutput;
    	}
        
    }
}
?>