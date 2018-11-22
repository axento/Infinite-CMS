<?php

class Infinite_Event_DataMapper
{

	public function getByID($id)
	{

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Event'), array('*'))
            ->where('m.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $event = new Infinite_Event();
            $event->makeObject($result);
            return $event;
        } else {
            return false;
        }
	}

	public function getAll()
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Event'), array('*'))
            ->order('m.dateStart DESC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $events = array();
        foreach($results as $result) {
            $event = new Infinite_Event();
            $event->makeObject($result);
			$events[$event->getID()] = $event;
        }

        return $events;
	}

    public function getFutureEvents()
    {
        $time = date("Y-m-d H:i:s", time());
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Event'), array('*'))
            ->where('m.dateStart > ?', $time)
            ->order('m.dateStart ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $events = array();
        foreach($results as $result) {
            $event = new Infinite_Event();
            $event->makeObject($result);
            $events[$event->getID()] = $event;
        }

        return $events;
    }

    public function getLatest()
    {
        $time = date("Y-m-d H:i:s", time());
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Event'), array('*'))
            ->where('m.dateStart > ?', $time)
            ->order('m.dateStart ASC')
            ->limit(3);

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $events = array();
        foreach($results as $result) {
            $event = new Infinite_Event();
            $event->makeObject($result);
            $events[$event->getID()] = $event;
        }

        return $events;
    }

}
