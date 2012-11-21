<?php
class FormValidator
{
	private $messages = array();
	private $errors = array();
	private $rules = array();
	private $fields = array();
	private $functions = array();
	private $haspostdata = FALSE;

	public function __construct()
	{
		$this -> haspostdata = ($_SERVER['REQUEST_METHOD'] === 'POST') ? TRUE : FALSE;
	}

	/*** ADD NEW RULE FUNCTIONS BELOW THIS LINE ***/
	/**
	 * email
	 * @param string $message
	 * @return FormValidator
	 */
	public function email($message = '')
	{
		$message = (empty($message)) ? '%s is an invalid email address.' : $message;
		$this->set_rule(__FUNCTION__, function($email)
		{
			return (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) ? FALSE : TRUE;
		}, $message);
		return $this;
	}

	/**
	 * required
	 * @param string $message
	 * @return FormValidator
	 */
	public function required($message = '')
	{
		$message = (empty($message)) ? '%s is required.' : $message;
		$this->set_rule(__FUNCTION__, function($string) 		
		{
			return (empty($string)) ? FALSE : TRUE;
		}, $message);
		return $this;
	}

	/**
	 * numbersonly
	 * @param string $message
	 * @return FormValidator
	 */
	public function numeric($message = '')
	{
		$message = (empty($message)) ? '%s must consist of numbers only.' : $message;
		$this->set_rule(__FUNCTION__, function($string)	
		{
			return (preg_match('/^[-]?[0-9.]+$/', $string)) ? TRUE : FALSE;
		}, $message);
		return $this;
	}

	/**
	 *
	 * @param int $len
	 * @param string $message
	 * @return FormValidator
	 */
	public function minlength($minlen, $message = '')
	{
		$message = (empty($message)) ? '%s must be at least ' . $minlen . ' characters or longer.' : $message;
		$this->set_rule(__FUNCTION__, function($string) use ($minlen) 
		{
			return (strlen(trim($string)) < $minlen) ? FALSE : TRUE;
		}, $message);
		return $this;
	}

	/**
	 *
	 * @param int $len
	 * @param string $message
	 * @return FormValidator
	 */
	public function maxlength($maxlen, $message = '')
	{
		$message = (empty($message)) ? '%s must be no longer than ' . $maxlen . ' characters.' : $message;
		$this->set_rule(__FUNCTION__, function($string) use ($maxlen)
		{
			return (strlen(trim($string)) > $maxlen) ? FALSE : TRUE;
		}, $message);
		return $this;
	}

	/**
	 *
	 * @param int $len
	 * @param string $message
	 * @return FormValidator
	 */
	public function length($len, $message = '')
	{
		$message = (empty($message)) ? '%s must be exactly ' . $len . ' characters in length.' : $message;
		$this->set_rule(__FUNCTION__, function($string) use ($len) 
		
		{
			return (strlen(trim($string)) == $len) ? TRUE : FALSE;
		}, $message);
		return $this;
	}

	/**
	 *
	 * @param string $field
	 * @param string $label
	 * @param string $message
	 * @return FormValidator
	 */
	public function matches($field, $label, $message = '')
	{
		$message = (empty($message)) ? '%s must match ' . $label . '.' : $message;
		$matchvalue = $this -> getval($field);
		$this->set_rule(__FUNCTION__, function($string) use ($matchvalue)		
		{
			return ((string) $matchvalue == (string) $string) ? TRUE : FALSE;
		}, $message);
		return $this;
	}
	
	/**
	 * checked
	 * @param string $message
	 * @return FormValidator
	 */
	public function checked($message = '', $value = 'on')
	{
		$message = (empty($message)) ? '%s is required.' : $message;
		$this->set_rule(__FUNCTION__, function($string) use ($value) 		
		{
			return ($string == $value) ? TRUE : FALSE;
		}, $message);
		return $this;
	}
	
	/**
	 * checked
	 * @param string $message
	 * @return FormValidator
	 */
	public function creditcard($message = '', $cc_num = '', $cc_type = '')
	{
		$message = (empty($message)) ? '%s is required.' : $message;
		$cc_obj = array($cc_num, $cc_type);
		$this->set_rule(__FUNCTION__, function($string) use ($cc_obj) 		
		{
			require_once IDG_CLASS_PATH."Utils/creditcard.inc.php";
			$cc = new CCVAL();
			$obj = $cc -> isVAlidCreditCard($cc_obj[0], $cc_obj[1]);
			return $obj;
		}, $message);
		return $this;
	}

	/*** ADD NEW RULE FUNCTIONS ABOVE THIS LINE ***/
	/**
	 * callback
	 * @param string $name
	 * @param mixed $function
	 * @param string $message
	 * @return FormValidator
	 */
	public function callback($name, $function, $message = '')
	{
		if(is_callable($function))
		{
			// set rule and function
			$this -> set_rule($name, $function, $message);
		}
		elseif(is_string($function) && preg_match($function, 'callback') !== FALSE)
		{
			// we can parse this as a regexp. set rule function accordingly.
			$this->set_rule($name, function($value) use ($function)
			{
				return (preg_match($function, $value)) ? TRUE : FALSE;
			}, $message);
		}
		else
		{
			// just set a rule function to check equality.
			$this->set_rule($name, function($value) use ( $function) 
			{
				return ((string) $value === (string) $function) ? TRUE : FALSE;
			}, $message);
		}
		return $this;
	}

	/**
	 * validate
	 * @param string $key
	 * @param string $label
	 * @return bool
	 */
	public function validate($key, $label = '')
	{
		// do not attempt to validate when no post data is present
		if($this -> haspostdata)
		{
			// set up field name for error message
			if(!empty($label))
			{
				$this -> fields[$key] = $label;
			}
			// try each rule function
			foreach($this -> rules as $rule => $is_true)
			{
				if($is_true)
				{
					$function = $this -> functions[$rule];
					if(is_callable($function))
					{
						if($function($this -> getval($key)) === FALSE)
						{
							$this -> register_error($rule, $key);
							// reset rules
							$this -> rules = array();
							return FALSE;
						}
					}
				}
			}
			// reset rules
			$this -> rules = array();
		}
	}

	/**
	 * has_errors
	 * @return bool
	 */
	public function has_errors()
	{
		return (count($this -> errors) > 0) ? TRUE : FALSE;
	}

	/**
	 * set_error_message
	 * @param string $rule
	 * @param string $message
	 */
	public function set_error_message($rule, $message)
	{
		$this -> messages[$rule] = $message;
	}

	/**
	 * get_error
	 * @param string $field
	 * @return string
	 */
	public function get_error($field)
	{
		return $this -> errors[$field];
	}

	/**
	 * get_all_errors
	 * @return array
	 */
	public function get_all_errors()
	{
		return $this -> errors;
	}

	/**
	 * getval
	 * @param string $key
	 * @return mixed
	 */
	private function getval($key)
	{
		return (isset($_POST[$key])) ? $_POST[$key] : FALSE;
	}

	/**
	 * register_error
	 * @param string $rule
	 * @param string $key
	 */
	private function register_error($rule, $key)
	{
		$message = $this -> messages[$rule];
		$field = $this -> fields[$key];
		if(empty($message))
			$message = '%s has an error.';
		if(empty($field))
			$field = "Field with the name of '$key'";
		$this -> errors[$key] = sprintf($message, $field);
	}

	/**
	 * set_rule
	 * @param string $rule
	 * @param closure $function
	 * @param string $message
	 */
	private function set_rule($rule, $function, $message = '')
	{
		// do not attempt to validate when no post data is present
		if($this -> haspostdata)
		{
			if(!array_key_exists($rule, $this -> rules))
			{
				$this -> rules[$rule] = TRUE;
				if(!array_key_exists($rule, $this -> functions) && is_callable($function))
				{
					$this -> functions[$rule] = $function;
				}
				if(!empty($message))
				{
					$this -> messages[$rule] = $message;
				}
			}
		}
	}
}
?>


