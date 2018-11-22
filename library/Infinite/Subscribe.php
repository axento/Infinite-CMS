<?php

class Infinite_Subscribe
{
	protected $_id;
    protected $_lng;
    protected $_datetime;
    protected $_email;

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

    public function setEmail($email)
    {
        $this->_email = strip_tags(trim($email));
        return $this;
    }
    public function getEmail()
    {
        return $this->_email;
    }

    public function makeObject($result)
    {
        $this->setID($result['id'])
            ->setLng($result['lng'])
            ->setDatetime($result['datetime'])
            ->setEmail($result['email']);

        return $this;
    }

    public function save() {
        $db = Zend_Registry::get('db');

        $data = array(
            'datetime' => $this->getDatetime(),
            'lng' => $this->getLng(),
            'email' => $this->getEmail()
        );
        $db->insert('Subscribe', $data);
        $this->setID($db->lastInsertId());

        return $this;
    }

    public function delete() {
        $db = Zend_Registry::get('db');
        $db->delete('Subscribe', 'id = ' . $this->getID());
        return true;
    }
}
