<?php

class Infinite_Merchant_DataMapper
{

	public function getByID($id)
	{

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Merchant'), array('*'))
            ->where('m.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $merchant = new Infinite_Merchant();
            $merchant->makeObject($result);
            return $merchant;
        } else {
            return false;
        }
	}

    public function getByURL($url)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Merchant'), array('*'))
            ->where('m.url = ?', $url);

        $result = $db->fetchRow($select);
        if ($result) {
            $merchant = new Infinite_Merchant();
            $merchant->makeObject($result);
            return $merchant;
        } else {
            return false;
        }
    }

    public function getByTypeID($typeID)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Merchant'), array('*'))
            ->joinLeft(array('mt' => 'MerchantType'), 'm.typeID = mt.id', array('type'))
            ->where('m.typeID = ?', $typeID);

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $merchants = array();
        foreach($results as $result) {
            $merchant = new Infinite_Merchant();
            $merchant->makeObject($result);
            $merchants[$merchant->getID()] = $merchant;
        }

        return $merchants;
    }

	public function getAllMerchants()
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Merchant'), array('*'))
            ->joinLeft(array('mt' => 'MerchantType'), 'm.typeID = mt.id', array('type'))
            ->order('m.company ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $merchants = array();
        foreach($results as $result) {
            $merchant = new Infinite_Merchant();
            $merchant->makeObject($result);
			$merchants[$merchant->getID()] = $merchant;
        }

        return $merchants;
	}
        
}
