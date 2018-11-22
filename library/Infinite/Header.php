<?php

class Infinite_Header {

    protected $_id;
    protected $_name;
    protected $_image;
    protected $_textColor;
    protected $_xTitle;
    protected $_yTitle;
    protected $_xContent;
    protected $_yContent;
    protected $_ranking;
    protected $_dateCreated;
    protected $_dateUpdated;

    protected $_lng;
    protected $_title;
    protected $_subtitle;
    protected $_content;
    protected $_url;
    protected $_active;

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

    public function getName() {
        return $this->_name;
    }
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    public function getImage() {
        return $this->_image;
    }
    public function setImage($image) {
        $this->_image = $image;
        return $this;
    }

    public function getTextColor() {
        return $this->_textColor;
    }
    public function setTextColor($textColor) {
        $this->_textColor = $textColor;
        return $this;
    }

    public function getXTitle() {
        return $this->_xTitle;
    }
    public function setXTitle($xTitle) {
        $this->_xTitle = $xTitle;
        return $this;
    }

    public function getYTitle() {
        return $this->_yTitle;
    }
    public function setYTitle($yTitle) {
        $this->_yTitle = $yTitle;
        return $this;
    }

    public function getXContent() {
        return $this->_xContent;
    }
    public function setXContent($xContent) {
        $this->_xContent = $xContent;
        return $this;
    }

    public function getYContent() {
        return $this->_yContent;
    }
    public function setYContent($yContent) {
        $this->_yContent = $yContent;
        return $this;
    }

    public function setRanking($ranking)
    {
        $this->_ranking = $ranking;
        return $this;
    }
    public function getRanking()
    {
        return $this->_ranking;
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

    public function getLng() {
        return $this->_lng;
    }
    public function setLng($lng) {
        $this->_lng = $lng;
        return $this;
    }

    public function getTitle() {
        return $this->_title;
    }
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    public function getSubtitle() {
        return $this->_subtitle;
    }
    public function setSubtitle($subtitle) {
        $this->_subtitle = $subtitle;
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

    public function getActive() {
        return $this->_active;
    }
    public function setActive($active) {
        $this->_active = $active;
        return $this;
    }

    public function makeObject($result)
    {
        $this ->setID($result['id'])
            ->setActive($result['active'])
            ->setLng($result['lng'])
            ->setName($result['name'])
            ->setImage($result['image'])
            ->setTitle($result['title'])
            ->setSubtitle($result['subtitle'])
            ->setContent($result['content'])
            ->setUrl($result['url'])
            ->setTextColor($result['textColor'])
            ->setXTitle($result['xTitle'])
            ->setYTitle($result['yTitle'])
            ->setXContent($result['xContent'])
            ->setYContent($result['yContent'])
            ->setDateCreated($result['dateCreated'])
            ->setDateUpdated($result['dateUpdated']);

        return $this;
    }

     public function save() {

        $systemNamespace = new Zend_Session_Namespace('System');
        $db = Zend_Registry::get('db');

        $data = array(
                'image' => $this->getImage(),
                'name' => $this->getName(),
                'xTitle' => $this->getXTitle(),
                'yTitle' => $this->getYTitle(),
                'xContent' => $this->getXContent(),
                'yContent' => $this->getYContent(),
                'textColor' => $this->getTextColor()
        );

        if ($this->getID()) {
            $data['dateUpdated'] = new Zend_Db_Expr('NOW()');
            $db->update('Header', $data, 'id = ' . $this->getID());
            $headerid = $this->_id;
        } else {
            $data['dateCreated'] = new Zend_Db_Expr('NOW()');
            $db->insert('Header', $data);
            $headerid = $db->lastInsertId();
        }

         $tslData = array(
             'id' => $headerid,
             'lng' => $this->getLng(),
             'title' => $this->getTitle(),
             'subtitle' => $this->getSubtitle(),
             'content' => $this->getContent(),
             'url' => $this->getURL(),
             'active' => (int) $this->getActive()
         );

         if ($this->getID() === false) {

             $config = Zend_Registry::get('config');
             foreach ($config->system->language as $lng => $slng) {
                 if ($lng != $systemNamespace->lng) {
                     $tslData['active'] = 0;
                 }
                 $tslData['lng'] = $lng;
                 $db->insert('HeaderTsl', $tslData);
             }
         } else {
             $db->update('HeaderTsl', $tslData,
                 'id = ' . $this->_id .
                 ' AND lng = \'' . $this->getLng() . '\'');
         }

        return $this;
    }



    public function delete() {
        $id = $this->getID();
        $db = Zend_Registry::get('db');
        $db->delete('Header', 'id = ' . $id);
        $db->delete('HeaderTsl', 'id = ' . $id);
        return true;
    }

    public function activate()
	{
		$db = Zend_Registry::get('db');
           if($this->_active) {
                $active = array('active'    => 0);
           }else {
                $active = array('active'    => 1);
           }

		$db->update('HeaderTsl', $active, 'id = ' . $this->_id);

		return true;
	}

    public function rank()
    {
        $db = Zend_Registry::get('db');
        $data = array(
            'id' => (int) $this->getID(),
            'ranking' => $this->getRanking()
        );
        $db->update('Header', $data, 'id = ' . $this->getID());
    }

}
