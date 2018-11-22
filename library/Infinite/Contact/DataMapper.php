<?php

class Infinite_Contact_DataMapper
{

	public function getAll()
	{
		$db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('c' => 'Contact'), array('*'))
            ->order('c.datetime DESC');

        $results = $db->fetchAll($select);
        
        $contacts = array();
        foreach($results as $result) {
            $contact = new Infinite_Contact();
            $contact->makeObject($result);
            $contacts[$contact->getID()] = $contact;
        }
        return $contacts;
	}
        
}