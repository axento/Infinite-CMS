<?php
class Infinite_MerchantType {

    protected $_id;
    protected $_type;

    public function getID() {
    if(!isset($this->_id)){
        return false;
    }
    return $this->_id;
    }
    public function setID($id) {
        $this->_id = $id;
        return $this;
    }

    public function getType() {
        return $this->_type;
    }
    public function setType($type) {
        $this->_type = $type;
        return $this;
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setType($result['type']);
        return $this;
    }

    public function save()
    {

        $db = Zend_Registry::get('db');
        $data = array(
                'type' => $this->getType()
        );

        if ($this->_id) {
            $db->update('MerchantType', $data, 'id = ' . $this->_id);
        } else {
            $db->insert('MerchantType', $data);
        }

        return $this;
    }

    public function delete()
    {
        $db = Zend_Registry::get('db');
        $db->delete('MerchantType', 'id = ' . $this->getID());
        return true;
    }

}