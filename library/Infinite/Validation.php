<?php
/**
 * Created by PhpStorm
 */

class Infinite_Validation
{

	private static $_instance;
	protected $_namespace = 'validation';
	protected $_errors = array();
	protected $_tabs = array();

    public static function getInstance($namespace) {

        if (!isset(self::$_instance)) {
            $class = __CLASS__;
            self::$_instance = new $class;
        }

        return self::$_instance->setNamespace($namespace);
    }

    public function getNamespace() {
        return $this->_namespace;
    }
	public function setNamespace($namespace = 'validation') {
		$this->_namespace = $namespace;
		return $this;
	}

    public function getField($key,$tab = null) {
        if ($tab) {
            if (!isset($this->_tabs[$this->_namespace])) {
                $this->_tabs[$this->_namespace] = array();
            }

            if (!isset($this->_tabs[$this->_namespace][$tab])) {
                $this->_tabs[$this->_namespace][$tab] = array();
            }

        }
        if (!isset($this->_errors[$this->_namespace][$key])) {
            $this->_errors[$this->_namespace][$key] = array();
        }

        return $this;
    }

    public function getErrorsByTab($tab) {

        if (isset($this->_tabs[$this->_namespace][$tab])) {
            return true;
        } else {
            return false;
        }

    }

    public function getErrorsByKey($key) {

        if (!isset($this->_errors[$this->_namespace])) {
            return false;
        }

        if (isset($this->_errors[$this->_namespace][$key])) {
            return true;
        }

        return false;
    }

    public function getNamespaceErrors() {
        if (!isset($this->_errors[$this->_namespace])) {
            return array();
        }
        return $this->_errors[$this->_namespace];
    }

}