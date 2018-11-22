<?php

class Infinite_Contact
{
	protected $_id;
    protected $_lng;
    protected $_datetime;
    protected $_name;
    protected $_email;
    protected $_phone;
    protected $_message;

    public function setID($id)
    {
        $this->_id = $id;
        return $this;
    }
    public function getID()
    {
        return $this->_id;
    }

    public function setLng($lng)
    {
        $this->_lng = $lng;
        return $this;
    }
    public function getLng()
    {
        return $this->_lng;
    }

    public function setDatetime($datetime)
    {
        $this->_datetime = $datetime;
        return $this;
    }
    public function getDatetime()
    {
        return $this->_datetime;
    }

    public function setName($name)
    {
        $this->_name = strip_tags(trim($name));
        return $this;
    }
    public function getName()
    {
        return $this->_name;
    }

    public function setEmail($email)
    {
        $this->_email = strip_tags(trim($email));
        return $this;
    }
    public function getEmail()
    {
        return $this->_email;
    }

    public function setPhone($phone)
    {
        $this->_phone = strip_tags(trim($phone));
        return $this;
    }
    public function getPhone()
    {
        return $this->_phone;
    }

    public function setMessage($message)
    {
        $this->_message = strip_tags(trim($message));
        return $this;
    }
    public function getMessage()
    {
        return $this->_message;
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setDatetime($result['datetime'])
            ->setLng($result['lng'])
            ->setName($result['name'])
            ->setEmail($result['email'])
            ->setPhone($result['phone'])
            ->setMessage($result['message']);

        return $this;
    }

    public function save() {
        $db = Zend_Registry::get('db');

        $data = array(
            'datetime' => $this->getDatetime(),
            'lng' => $this->getLng(),
            'name' => $this->getName(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'message' => $this->getMessage()
        );
        $db->insert('Contact', $data);
        $this->setID($db->lastInsertId());

        return $this;
    }
    
    public function delete() {
        $db = Zend_Registry::get('db');
        $db->delete('Contact', 'id = ' . $this->getID());
        return true;
    }
}
