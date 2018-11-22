<?php

class Infinite_Contentblock {
    protected $_id;
    protected $_blockname;
    protected $_title;
    protected $_content;
    protected $_lng;

    public function getId() {
		if(!isset($this->_id)){
			return false;
        }
		return $this->_id;
	}
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    public function getBlockname() {
        return $this->_blockname;
    }
    public function setBlockname($blockname) {
        $this->_blockname = $blockname;
        return $this;
    }

    public function getTitle() {
        return $this->_title;
    }
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    public function getContent() {
        return $this->_content;
    }
    public function setContent($content) {
        $this->_content = $content;
        return $this;
    }

    public function getLng() {
        return $this->_lng;
    }
    public function setLng($lng) {
        $this->_lng = $lng;
        return $this;
    }

    public function makeObject($result) {
        $this->setId($result['blockID'])
            ->setLng($result['lng'])
            ->setBlockname($result['blockname'])
            ->setTitle($result['title'])
            ->setContent($result['content']);

        return $this;
    }

    public function save() {
        $db = Zend_Registry::get('db');
        
        $data = array(
                'blockname' => $this->getBlockname()
        );

        if ($this->_id) {
            $db->update('ContentBlock', $data, 'id = ' . $this->_id);
            $id = $this->_id;
        } else {
            $db->insert('ContentBlock', $data);
            $id = $db->lastInsertId();
        }

        $tslData = array(
                'blockID' => $id,
                'lng' => $this->getLng(),
                'title' => $this->getTitle(),
                'content' => $this->getContent(),
            );

        if ($this->getId() === false) {

            $config = Zend_Registry::get('config');
            foreach ($config->system->language as $lng => $slng) {
                $tslData['lng'] = $lng;
                $db->insert('ContentBlockTsl', $tslData);
            }
        } else {
            $db->update('ContentBlockTsl', $tslData,
                    'blockID = ' . $this->_id .
                    ' AND lng = \'' . $this->getLng() . '\'');
        }

        return $this;
    }

}
