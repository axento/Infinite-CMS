<?php

class Infinite_Account_PasswordStrength extends Zend_Validate_Abstract
{
    const LENGTH = 'passwordStrengthLength';
    const UPPER  = 'passwordStrengthUpper';
    const LOWER  = 'passwordStrengthLower';
    const DIGIT  = 'passwordStrengthDigit';

    protected $_min = 6;
    protected $_requireUppercase = false;
    protected $_requireLowercase = false;
    protected $_requireDigit = false;

    protected $_messageTemplates = array(
        self::LENGTH => "Password must be at least %min% characters in length",
        self::UPPER  => "Password must contain at least one uppercase letter",
        self::LOWER  => "Password must contain at least one lowercase letter",
        self::DIGIT  => "Password must contain at least one digit character"
    );
    protected $_messageVariables = array(
        'min' => '_min',
    );

    public function setMinimumLength($min) {
		$this->_min = (int) $min;
		return $this;
    }

    public function setRequireUppercase($required = true) {
    	$this->_requireUppercase = (bool) $required;
    	return $this;
    }

    public function setRequireLowercase($required = true) {
    	$this->_requireLowercase = (bool) $required;
    	return $this;
    }

    public function setRequireDigit($required = true) {
    	$this->_requireDigit = (bool) $required;
    	return $this;
    }

    public function isValid($value)
    {
        $this->_setValue($value);
        $isValid = true;

        if (strlen($value) < $this->_min) {
            $this->_error(self::LENGTH);
            $isValid = false;
        }

        if ($this->_requireUppercase && !preg_match('/[A-Z]/', $value)) {
            $this->_error(self::UPPER);
            $isValid = false;
        }

        if ($this->_requireLowercase && !preg_match('/[a-z]/', $value)) {
            $this->_error(self::LOWER);
            $isValid = false;
        }

        if ($this->_requireDigit && !preg_match('/\d/', $value)) {
            $this->_error(self::DIGIT);
            $isValid = false;
        }

        return $isValid;
    }
}
