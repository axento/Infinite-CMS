<?php

class Infinite_Menu
{

    protected $_id;
    protected $_title;
    protected $_content;
    protected $_dateCreated;
    protected $_dateUpdated;

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

    public function setTitle($title)
    {
        $this->_title = trim($title);
        return $this;
    }
    public function getTitle()
    {
        return $this->_title;
    }

    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }
    public function getContent()
    {
        return $this->_content;
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

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setTitle($result['title'])
            ->setContent($result['content'])
            ->setDateCreated($result['dateCreated'])
            ->setDateUpdated($result['dateUpdated']);

        return $this;
    }

    public function save() {
        $db = Zend_Registry::get('db');

        $data = array(
            'title' => $this->getTitle(),
            'content' => $this->getContent()
        );

        if ($this->_id) {
            $data['dateUpdated'] = new Zend_Db_Expr('NOW()');
            $db->update('Menu', $data, 'id = ' . $this->_id);
        } else {
            $data['dateCreated'] = new Zend_Db_Expr('NOW()');
            $db->insert('Menu', $data);
        }

        return $this;
    }

    public function delete() {
        $id = $this->getID();

        $mapper = new Infinite_menuCategory_DataMapper();
        $categories = $mapper->getByMenuID($id);

        $db = Zend_Registry::get('db');
        $db->delete('Menu', 'id = ' . $id);
        $db->delete('MenuCategory', 'menuID = ' . $id);
        foreach($categories as $category) {
            $categoryID = $category->getID();
            $db->delete('MenuItem', 'categoryID = ' . $categoryID);
        }
        return true;
    }


}
