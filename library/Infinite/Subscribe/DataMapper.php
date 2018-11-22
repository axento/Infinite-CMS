<?php

class Infinite_Subscribe_DataMapper
{
	public function getAll()
	{
		$db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('c' => 'Subscribe'), array('*'))
            ->order('c.datetime DESC');

        $results = $db->fetchAll($select);
        
        $contacts = array();
        foreach($results as $result) {
            $contact = new Infinite_Subscribe();
            $contact->makeObject($result);
            $contacts[$contact->getID()] = $contact;
        }
        return $contacts;
	}
        
}