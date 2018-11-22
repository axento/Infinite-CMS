<?php

class Infinite_NewsTag
{
	protected $_tid;
    protected $_tag;

	public function getTid() {
		if(!isset($this->_tid)){
			return false;
        }
		return $this->_tid;
	}
    public function setTid($tid) {
        $this->_tid = $tid;
        return $this;
    }
    
    public function getTag() {
        return $this->_tag;
    }
    public function setTag($tag) {
        $this->_tag = $tag;
        return $this;
    }
    
	public function save()
	{
		$db = Zend_Registry::get('db');
        $identity = Zend_Auth::getInstance()->getIdentity();
		        
        $data = array(
                'tag' => $this->getTag()
        );

        if ($this->_tid) {
            $db->update('NewsTags', $data, 'tid = ' . $this->_tid);
        } else {
            $db->insert('NewsTags', $data);
        }
        
		return $this;
	}
    
    public function delete() {
        $db = Zend_Registry::get('db');
        $db->delete('NewsTags', 'tid = ' . $this->getTid());
        return true;
    }

}
