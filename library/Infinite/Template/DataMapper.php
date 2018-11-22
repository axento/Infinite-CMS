<?php

class Infinite_Template_DataMapper
{

	public function getAll()
	{
		$db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('t' => 'Template'), array('*'))
            ->order('t.id ASC');

        $results = $db->fetchAll($select);
        
        $templates = array();
        foreach($results as $result) {
            $template = new Infinite_Template();
            $template->makeObject($result);
            $templates[$template->getID()] = $template;
        }
        return $templates;
	}

    public function getAllActive()
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('t' => 'Template'), array('*'))
            ->where('t.active = 1')
            ->order('t.id ASC');

        $results = $db->fetchAll($select);

        $templates = array();
        foreach($results as $result) {
            $template = new Infinite_Template();
            $template->makeObject($result);
            $templates[$template->getID()] = $template;
        }
        return $templates;
    }
        
}