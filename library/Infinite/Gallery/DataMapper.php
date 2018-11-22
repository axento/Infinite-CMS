<?php

class Infinite_Gallery_DataMapper
{

	public function getById($id)
	{
        $systemNamespace = new Zend_Session_Namespace('System');
        $lng = $systemNamespace->lng;
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('a' => 'Gallery'), array('a.*'))
            ->join(array('t' => 'GalleryTsl'), 'a.gid = t.g_id', array('t.*'))
            ->where('a.gid = ?', $id)
            ->where('t.lng = ?', $lng);

        $result = $db->fetchRow($select);
        if ($result) {
            $gallery = new Infinite_Gallery();
            $gallery->makeObject($result);
            return $gallery;
        } else {
            return false;
        }

	}

    public function getAll($lng)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('a' => 'Gallery'), array('a.*'))
            ->join(array('t' => 'GalleryTsl'), 'a.gid = t.g_id', array('t.*'))
            ->where('t.lng = ?', $lng)
            ->order("a.dateCreated desc");

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $objects = array();
        foreach($results as $result) {
            $object = new Infinite_Gallery();
            $object->makeObject($result);
            $objects[$object->getGid()] = $object;
        }
        return $objects;
    }
        
}
