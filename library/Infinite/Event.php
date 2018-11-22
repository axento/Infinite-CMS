<?php

class Infinite_Event {

    protected $_id;
    protected $_title;
    protected $_content;
    protected $_url;
    protected $_dateStart;
    protected $_dateStop;
    protected $_dateCreated;
    protected $_dateUpdated;

    public function getID() {
		if(!isset($this->_id)){
			return false;
        }
		return $this->_id;
	}
    public function setID($id) {
        $this->_id = $id;
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

    public function getUrl() {
        return $this->_url;
    }
    public function setUrl($url) {
        $this->_url = $url;
        return $this;
    }

    public function getDateStart() {
        return $this->_dateStart;
    }
    public function setDateStart($dateStart) {
        $this->_dateStart = $dateStart;
        return $this;
    }

    public function getDateStop() {
        return $this->_dateStop;
    }
    public function setDateStop($dateStop) {
        $this->_dateStop = $dateStop;
        return $this;
    }

    public function getDateCreated() {
        return $this->_dateCreated;
    }
    public function setDateCreated($dateCreated) {
        $this->_dateCreated = $dateCreated;
        return $this;
    }

    public function getDateUpdated() {
        return $this->_dateUpdated;
    }
    public function setDateUpdated($dateUpdated) {
        $this->_dateUpdated = $dateUpdated;
        return $this;
    }

    public function makeObject($result)
    {
        $this ->setID($result['id'])
            ->setTitle($result['title'])
            ->setContent($result['content'])
            ->setUrl($result['url'])
            ->setDateStart($result['dateStart'])
            ->setDateStop($result['dateStop'])
            ->setDateCreated($result['dateCreated'])
            ->setDateUpdated($result['dateUpdated']);

        return $this;
    }

    protected function _createUrl() {
        $title = $this->getTitle();
        $url = strtolower($title);
        $url = str_replace('/', '_', $url);
        $url = preg_replace('%>%', '/', $url);
        $url = preg_replace('%[^a-zA-Z0-9\\-/]%', '_', $url);
        $url = preg_replace('%-{2,}%', '_', $url);
        $url = preg_replace('%/{2,}%', '/', $url);
        $url = preg_replace('%(-*)/(-*)%', '/', $url);

        $url = trim($url, '/');
        $url = trim($url, '_');

        return strtolower($url);
    }

    public function save() {
        $db = Zend_Registry::get('db');

        $data = array(
                'title' => $this->getTitle(),
                'content' => $this->getContent(),
                'url' => $this->_createUrl(),
                'dateStart' => $this->getDateStart(),
                'dateStop' => $this->getDateStop()
        );

        if ($this->getID()) {
            $data['dateUpdated'] = new Zend_Db_Expr('NOW()');
            $db->update('Event', $data, 'id = ' . $this->getID());
        } else {
            $data['dateCreated'] = new Zend_Db_Expr('NOW()');
            $db->insert('Event', $data);
        }

        return $this;
    }



    public function delete() {
        $id = $this->getID();
        $db = Zend_Registry::get('db');
        $db->delete('Event', 'id = ' . $id);
        return true;
    }

}
