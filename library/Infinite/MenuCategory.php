<?php

class Infinite_MenuCategory
{

    protected $_id;
    protected $_menuID;
    protected $_title;
    protected $_description;
    protected $_ranking;
    protected $_dateCreated;
    protected $_dateUpdated;

    protected $_menuTitle;
    protected $_menuContent;

    public function setID($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
    public function getID()
    {
        if (!isset($this->_id)) {
            return false;
        }
        return $this->_id;
    }

    public function setMenuID($menuID)
    {
        $this->_menuID = (int) $menuID;
        return $this;
    }
    public function getMenuID()
    {
        return $this->_menuID;
    }

    public function setTitle($title)
    {
        $this->_title = trim($title);
        return $this;
    }
    public function getTitle()
    {
        return $this->_title;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }
    public function getDescription()
    {
        return $this->_description;
    }

    public function setRanking($ranking)
    {
        $this->_ranking = (int) $ranking;
        return $this;
    }
    public function getRanking()
    {
        return $this->_ranking;
    }

    public function setDateCreated($date)
    {
        $this->_dateCreated = $date;
        return $this;
    }
    public function getDateCreated($format = null)
    {
        if ($format) {
            return strftime($format, strtotime($this->_dateCreated));
        }
        return $this->_dateCreated;
    }

    public function setDateUpdated($date)
    {
        $this->_dateUpdated = $date;
        return $this;
    }
    public function getDateUpdated($format = null)
    {
        if ($format) {
            return strftime($format, strtotime($this->_dateUpdated));
        }
        return $this->_dateUpdated;
    }

    public function setMenuTitle($menuTitle)
    {
        $this->_menuTitle = trim($menuTitle);
        return $this;
    }
    public function getMenuTitle()
    {
        return $this->_menuTitle;
    }

    public function setMenuContent($menuContent)
    {
        $this->_menuContent = trim($menuContent);
        return $this;
    }
    public function getMenuContent()
    {
        return $this->_menuContent;
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setTitle($result['title'])
            ->setDescription($result['description'])
            ->setRanking($result['ranking'])
            ->setDateCreated($result['dateCreated'])
            ->setDateUpdated($result['dateUpdated'])

            ->setMenuTitle($result['menuTitle'])
            ->setMenuContent($result['menuContent']);

        return $this;
    }

    public function save() {
        $db = Zend_Registry::get('db');

        $data = array(
            'menuID' => $this->getMenuID(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription()
        );

        if ($this->_id) {
            $data['dateUpdated'] = new Zend_Db_Expr('NOW()');
            $db->update('MenuCategory', $data, 'id = ' . $this->_id);
        } else {
            $data['dateCreated'] = new Zend_Db_Expr('NOW()');
            $db->insert('MenuCategory', $data);
        }

        return $this;
    }

    public function delete() {
        $id = $this->getID();

        $db = Zend_Registry::get('db');
        $db->delete('MenuCategory', 'id = ' . $id);
        $db->delete('MenuItem', 'categoryID = ' . $id);
        return true;
    }

    public function rank()
    {
        $db = Zend_Registry::get('db');
        $data = array(
            'id' => (int) $this->getID(),
            'ranking' => $this->getRanking()
        );
        $db->update('MenuCategory', $data, 'id = ' . $this->getID());
    }


}
