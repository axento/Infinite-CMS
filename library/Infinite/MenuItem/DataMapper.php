<?php

class Smart_MenuItem_DataMapper
{

	public function getById($id)
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('b' => 'MenuItem'), array('*'))
            //->joinLeft(array('t' => 'MenuCategory', array('lng', 'title', 'summary', 'content', 'url', 'seotags', 'seotitle', 'seodescription', 'active')), 'b.id = t.id')
            ->where('b.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $menuItem = new Infinites_MenuItem();
            $menuItem->makeObject($result);
            return $menuItem;
        } else {
            return false;
        }
	}

    public function getByMenuID($menuID)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('mi' => 'MenuItem'), array('*'))
            ->joinLeft(array('mc' => 'MenuCategory'), 'mi.categoryID = mc.id', array('categoryTitle' => 'title', 'categoryDescription' => 'description'))
            ->joinLeft(array('m' => 'Menu'), 'm.id = mc.menuID', array('menuTitle' => 'title', 'menuContent' => 'content'))
            ->where('mc.menuID = ?', $menuID);

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $categories = array();
        foreach($results as $result) {
            $category = new Infinites_MenuItem();
            $category->makeObject($result);
            $categories[$category->getID()] = $category;
        }

        return $categories;
    }

    public function getByCategoryID($categoryID)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('mi' => 'MenuItem'), array('*'))
            ->joinLeft(array('mc' => 'MenuCategory'), 'mi.categoryID = mc.id', array('categoryTitle' => 'title', 'categoryDescription' => 'description'))
            ->joinLeft(array('m' => 'Menu'), 'm.id = mc.menuID', array('menuTitle' => 'title', 'menuContent' => 'content'))
            ->order('mi.ranking ASC')
            ->where('mi.categoryID = ?', $categoryID);

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $items = array();
        foreach($results as $result) {
            $item = new Infinites_MenuItem();
            $item->makeObject($result);
            $items[$item->getID()] = $item;
        }

        return $items;
    }

	public function getAll()
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('mi' => 'MenuItem'), array('*'))
            ->joinLeft(array('mc' => 'MenuCategory'), 'mi.categoryID = mc.id', array('categoryTitle' => 'title', 'categoryDescription' => 'description'))
            ->joinLeft(array('m' => 'Menu'), 'm.id = mc.menuID', array('menuTitle' => 'title', 'menuContent' => 'content'))
            ->order('mc.menuID ASC')
            ->order('mi.categoryID ASC')
            ->order('mi.ranking ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $items = array();
        foreach($results as $result) {
            $item = new Infinites_MenuItem();
            $item->makeObject($result);
            $items[$item->getID()] = $item;
        }

        return $items;
	}
        
}
