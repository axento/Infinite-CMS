<?php

class Infinites_MenuItem
{

    protected $_id;
    protected $_categoryID;
    protected $_title;
    protected $_price;
    protected $_ranking;
    protected $_dateCreated;
    protected $_dateUpdated;

    protected $_categoryTitle;
    protected $_categoryDescription;
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

    public function setCategoryID($categoryID)
    {
        $this->_categoryID = (int) $categoryID;
        return $this;
    }
    public function getCategoryID()
    {
        return $this->_categoryID;
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

    public function setPrice($price)
    {
        $this->_price = (int) $price;
        return $this;
    }
    public function getPrice()
    {
        return $this->_price;
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

    public function setCategoryTitle($categoryTitle)
    {
        $this->_categoryTitle = trim($categoryTitle);
        return $this;
    }
    public function getCategoryTitle()
    {
        return $this->_categoryTitle;
    }

    public function setCategoryDescription($categoryDescription)
    {
        $this->_categoryDescription = trim($categoryDescription);
        return $this;
    }
    public function getCategoryDescription()
    {
        return $this->_categoryDescription;
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
            ->setCategoryID($result['categoryID'])
            ->setTitle($result['title'])
            ->setPrice($result['price'])
            ->setRanking($result['ranking'])
            ->setDateCreated($result['dateCreated'])
            ->setDateUpdated($result['dateUpdated'])

            ->setCategoryTitle($result['categoryTitle'])
            ->setCategoryDescription($result['categoryDescription'])
            ->setMenuTitle($result['menuTitle'])
            ->setMenuContent($result['menuContent']);

        return $this;
    }

    public function save() {
        $db = Zend_Registry::get('db');

        $data = array(
            'categoryID' => $this->getCategoryID(),
            'title' => $this->getTitle(),
            'price' => $this->getPrice()
        );

        if ($this->_id) {
            $data['dateUpdated'] = new Zend_Db_Expr('NOW()');
            $db->update('MenuItem', $data, 'id = ' . $this->_id);
        } else {
            $data['dateCreated'] = new Zend_Db_Expr('NOW()');
            $db->insert('MenuItem', $data);
        }

        return $this;
    }

    public function delete() {
        $id = $this->getID();

        $db = Zend_Registry::get('db');
        $db->delete('MenuItem', 'id = ' . $id);
        return true;
    }

    public function rank()
    {
        $db = Zend_Registry::get('db');
        $data = array(
            'id' => (int) $this->getID(),
            'ranking' => $this->getRanking()
        );
        $db->update('MenuItem', $data, 'id = ' . $this->getID());
    }


}
