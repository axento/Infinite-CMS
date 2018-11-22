<?php

class Infinite_ErrorStack
{

	private static $_instance;
	protected $_namespace = 'default';
	protected $_errors = array();
	protected $_tabs = array();
    
	public static function getInstance($namespace = 'default') {
        if (!isset(self::$_instance)) {
            $c = __CLASS__;
            self::$_instance = new $c;
        }

        return self::$_instance->setNamespace($namespace);
	}

	public function setNamespace($namespace = 'default') {
		$this->_namespace = $namespace;
		return $this;
	}

	public function getNamespace() {
		return $this->_namespace;
	}

	public function reset()
	{
		$this->_errors[$this->_namespace] = array();
	}
	
	public function addMessage($key, $errors = array(), $tab = null) {
        if (!isset($this->_errors[$this->_namespace])) {
            $this->_errors[$this->_namespace] = array();
        }

        if (null !== $tab) {
        	if (!isset($this->_tabs[$this->_namespace])) {
        		$this->_tabs[$this->_namespace] = array();
        	}

            if (!isset($this->_tabs[$this->_namespace][$tab])) {
        		$this->_tabs[$this->_namespace][$tab] = array();
        	}

            if (is_array($key)) {
	        	$this->_tabs[$this->_namespace][$tab] += $key;
	        } else {
	        	if (!isset($this->_tabs[$this->_namespace][$tab][$key])) {
	        		$this->_tabs[$this->_namespace][$tab][$key] = array();
	        	}

	        	$this->_tabs[$this->_namespace][$tab][$key] += $errors;
	        }
        }

        if (is_array($key)) {
        	$this->_errors[$this->_namespace] += $key;
        } else {
        	if (!isset($this->_errors[$this->_namespace][$key])) {
        		$this->_errors[$this->_namespace][$key] = array();
        	}

        	$this->_errors[$this->_namespace][$key] += $errors;
        }

        return $this;
	}

	public function getTabErrors($tab)
	{
    	if (!isset($this->_tabs[$this->_namespace][$tab])) {
    		return array();
    	}

       	if (isset($this->_tabs[$this->_namespace][$tab])) {
    		return $this->_tabs[$this->_namespace][$tab];
    	}

    	return array();
	}

    public function getErrors($key)
    {
    	if (!isset($this->_errors[$this->_namespace])) {
    		return array();
    	}

       	if (isset($this->_errors[$this->_namespace][$key])) {
    		return $this->_errors[$this->_namespace][$key];
    	}

    	return array();
    }

    public function getNamespaceErrors() {
       	if (!isset($this->_errors[$this->_namespace])) {
    		return array();
    	}

    	return $this->_errors[$this->_namespace];
    }

    public function getHtmlErrors($key) {
    	$errors = $this->getErrors($key);
    	if (!count($errors)) {
			return null;
    	}

    	$html = '<ul class="errors">';
    	foreach ($errors as $values) {
    		if (null !== $key) {
				$html .= '<li>' . $values . '</li>';
    		} else {
    			foreach ($values as $value) {
    				$html .= '<li>' . $value . '</li>';
    			}
    		}
    	}

    	$html .= '</ul>';
    	return $html;
    }
}
