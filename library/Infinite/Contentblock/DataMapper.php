<?php

class Infinite_Contentblock_DataMapper
{

    public function getById($id,  $lng = 'nl') {

        $db = Zend_Registry::get('db');
        $select = $db->select()
                ->from(array('a' => 'ContentBlock'), array('a.*'))
                ->join(array('t' => 'ContentBlockTsl'), 'a.id = t.blockID', array('t.*'))
                ->where('a.id = ?', $id)
                ->where('t.lng = ?', $lng);

        $stm = $db->query($select);
        $result = $stm->fetch();
        $block = new Infinite_Contentblock();
        $block->makeObject($result);

        return $block;

    }

    public function getByBlockname($blockname,  $lng = 'nl') {

        $db = Zend_Registry::get('db');
        $select = $db->select()
                ->from(array('a' => 'ContentBlock'), array('a.*'))
                ->join(array('t' => 'ContentBlockTsl'), 'a.id = t.blockID', array('t.*'))
                ->where('a.blockname = ?', $blockname)
                ->where('t.lng = ?', $lng);

        $stm = $db->query($select);
        $result = $stm->fetch();

        $block = new Infinite_Contentblock();
        $block->makeObject($result);

        return $block;
    }

    public function getAll($lng) {

        $db = Zend_Registry::get('db');

        $select = $db->select()
                ->from(array('a' => 'ContentBlock'), array('a.*'))
                ->join(array('t' => 'ContentBlockTsl'), 'a.id = t.blockID', array('t.*'))
                ->where('t.lng = ?', $lng);

        $results = $db->fetchAll($select);

        $blocks = array();
        foreach($results as $result) {
            $block = new Infinite_Contentblock();
            $block->makeObject($result);
            $blocks[$block->getId()] = $block;
        }

        return $blocks;
    }

}