<?php

class Infinite_Logo {

    protected $_id;
    protected $_active;
    protected $_logo;
    protected $_url;
    protected $_dateCreated;
    protected $_dateUpdated;

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

    public function getActive() {
        return $this->_active;
    }
    public function setActive($active) {
        $this->_active = $active;
        return $this;
    }

    public function getLogo() {
        return $this->_logo;
    }
    public function setLogo($logo) {
        $this->_logo = $logo;
        return $this;
    }

    public function getUrl() {
        return $this->_url;
    }
    public function setUrl($url) {
        $this->_url = $url;
        return $this;
    }

    public function getDateCreated() {
        return $this->_dateCreated;
    }
    public function setDateCreated($dateCreated) {
        $this->_dateCreated = $dateCreated;
        return $this;
    }

    public function getDateUpdated() {
        return $this->_dateUpdated;
    }
    public function setDateUpdated($dateUpdated) {
        $this->_dateUpdated = $dateUpdated;
        return $this;
    }

    public function makeObject($result)
    {
        $this ->setID($result['id'])
            ->setActive($result['active'])
            ->setLogo($result['logo'])
            ->setUrl($result['url'])
            ->setDateCreated($result['dateCreated'])
            ->setDateUpdated($result['dateUpdated']);

        return $this;
    }

     public function save() {
        $db = Zend_Registry::get('db');

        $data = array(
                'logo' => $this->getLogo(),
                'url' => $this->getURL()
        );

        if ($this->getID()) {
            $data['dateUpdated'] = new Zend_Db_Expr('NOW()');
            $db->update('Logo', $data, 'id = ' . $this->getID());
        } else {
            $data['dateCreated'] = new Zend_Db_Expr('NOW()');
            $db->insert('Logo', $data);
        }

        return $this;
    }



    public function delete() {
        $id = $this->getID();
        $db = Zend_Registry::get('db');
        $db->delete('Logo', 'id = ' . $id);
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

		$db->update('Logo', $active, 'id = ' . $this->_id);

		return true;
	}

}
