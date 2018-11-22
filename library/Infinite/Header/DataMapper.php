<?php

class Infinite_Header_DataMapper
{

    public function getById($id, $active = null)
    {
        $systemNamespace = new Zend_Session_Namespace('System');
        $lng = $systemNamespace->lng;
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('b' => 'Header'), array('*'))
            ->joinLeft(array('t' => 'HeaderTsl', array('lng', 'title', 'subtitle', 'content', 'active')), 'b.id = t.id')
            ->where('b.id = ?', $id)
            ->where('t.lng LIKE "' . $lng . '"');

        if ($active != null) {
            $select->where('t.active = ?', $active);
        }

        $result = $db->fetchRow($select);
        if ($result) {

            $header = new Infinite_Header();
            $header->makeObject($result);

            return $header;

        } else {
            return false;
        }
    }

    public function getAll($lng = 'nl')
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('b' => 'Header'), array('*'))
            ->joinLeft(array('t' => 'HeaderTsl', array('lng', 'title', 'subtitle', 'content', 'active')), 'b.id = t.id')
            ->where('t.lng LIKE "' . $lng . '"')
            ->order('b.ranking ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $headers = array();
        foreach($results as $result) {
            $header = new Infinite_Header();
            $header->makeObject($result);
            $headers[$header->getID()] = $header;
        }

        return $headers;
    }

    public function getActive($lng = 'nl')
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('b' => 'Header'), array('*'))
            ->joinLeft(array('t' => 'HeaderTsl', array('lng', 'title', 'subtitle', 'content', 'active')), 'b.id = t.id')
            ->where('t.lng LIKE "' . $lng . '"')
            ->where('t.active = 1 ')
            ->order('b.ranking ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $headers = array();
        foreach($results as $result) {
            $header = new Infinite_Header();
            $header->makeObject($result);
            $headers[$header->getID()] = $header;
        }

        return $headers;
    }

}
