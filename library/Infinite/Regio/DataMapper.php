<?php

class Infinite_Regio_DataMapper
{

	public function getById($id)
	{

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('r' => 'Regio'), array('*'))
            ->where('r.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $regio = new Infinite_Regio();
            $regio->makeObject($result);
            $regio = $this->toObject($result);
            return $regio;
        } else {
            return false;
        }
	}

    public function getByURL($url)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('r' => 'Regio'), array('*'))
            ->where('r.url = ?', $url);

        $result = $db->fetchRow($select);
        if ($result) {
            $regio = new Infinite_Regio();
            $regio->makeObject($result);
            return $regio;
        } else {
            return false;
        }
    }

	public function getAll()
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('r' => 'Regio'), array('*'))
            ->order('r.regio ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $regios = array();
        foreach($results as $result) {
            $regio = new Infinite_Regio();
            $regio->makeObject($result);
			$regios[$regio->getID()] = $regio;
        }

        return $regios;
	}
        
}
