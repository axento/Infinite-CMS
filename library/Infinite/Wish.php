<?php
/**
 * Created by PhpStorm.
 * User: axento
 * Date: 30/03/18
 * Time: 20:42
 */

class Infinite_Wish
{
    protected $_id;
    protected $_active;
    protected $_fromName;
    protected $_toName;
    protected $_fromFname;
    protected $_toFname;
    protected $_wish;
    protected $_ip;
    protected $_dateCreated;

    public function setID($id)
    {
        $this->_id = $id;
        return $this;
    }
    public function getID()
    {
        return $this->_id;
    }

    public function setActive($active)
    {
        $this->_active = $active;
        return $this;
    }
    public function getActive()
    {
        return $this->_active;
    }

    public function setFromName($fromName)
    {
        $this->_fromName = $fromName;
        return $this;
    }
    public function getFromName()
    {
        return $this->_fromName;
    }

    public function setToName($toName)
    {
        $this->_toName = strip_tags(trim($toName));
        return $this;
    }
    public function getToName()
    {
        return $this->_toName;
    }

    public function setFromFname($fromFname)
    {
        $this->_fromFname = strip_tags(trim($fromFname));
        return $this;
    }
    public function getFromFname()
    {
        return $this->_fromFname;
    }

    public function setToFname($toFname)
    {
        $this->_toFname = strip_tags(trim($toFname));
        return $this;
    }
    public function getToFname()
    {
        return $this->_toFname;
    }

    public function setWish($wish)
    {
        $this->_wish = strip_tags(trim($wish));
        return $this;
    }
    public function getWish()
    {
        return $this->_wish;
    }

    public function setIP($ip)
    {
        $this->_ip = $ip;
        return $this;
    }
    public function getIP()
    {
        return $this->_ip;
    }

    public function setDateCreated($dateCreated)
    {
        $this->_dateCreated = $dateCreated;
        return $this;
    }
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setActive($result['active'])
            ->setFromName($result['fromName'])
            ->setToName($result['toName'])
            ->setFromFname($result['fromFname'])
            ->setToFname($result['toFname'])
            ->setWish($result['wish'])
            ->setIP($result['ip'])
            ->setDateCreated($result['dateCreated']);

        return $this;
    }

    public function save() {
        $db = Zend_Registry::get('db');

        $data = array(
            'active' => $this->getActive(),
            'fromName' => $this->getFromName(),
            'toName' => $this->getToName(),
            'toFname' => $this->getToFname(),
            'fromFname' => $this->getFromFname(),
            'wish' => $this->getWish(),
            'ip' => $this->getIP(),
            'dateCreated' => $this->getDateCreated()
        );
        $db->insert('Wish', $data);
        $this->setID($db->lastInsertId());

        return $this;
    }

    public function delete() {
        $db = Zend_Registry::get('db');
        $db->delete('Wish', 'id = ' . $this->getID());
        return true;
    }

    public function activate()
    {
        $db = Zend_Registry::get('db');
        if($this->_active) {
            $active = array('active'    => 0);
        }else {
            $active = array('active'    => 1);
        }

        $db->update('Wish', $active, 'id = ' . $this->_id);
        return true;
    }

}
