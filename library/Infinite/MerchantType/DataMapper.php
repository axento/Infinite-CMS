<?php

class Infinite_MerchantType_DataMapper
{

	public function getByID($id)
	{

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('mt' => 'MerchantType'), array('*'))
            ->where('mt.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $type = new Infinite_MerchantType();
            $type->makeObject($result);
            return $type;
        } else {
            return false;
        }
	}

	public function getAll()
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('mt' => 'MerchantType'), array('*'))
            ->order('mt.type ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $types = array();
        foreach($results as $result) {
            $type = new Infinite_MerchantType();
            $type->makeObject($result);
            $types[$type->getID()] = $type;
        }

        return $types;
	}
        
}
