<?php

class Infinite_Gallery {
    protected $_gid;
    protected $_active;
    protected $_authorId;
    protected $_dateCreated;
    protected $_dateUpdated;

    protected $_title;
    protected $_summary;
    protected $_content;
    protected $_url;
    protected $_seoTags;
    protected $_seoTitle;
    protected $_seoDescription;
    protected $_lng;
    protected $_pic;

    public function getGid() {
		if(!isset($this->_gid)){
			return false;
        }
		return $this->_gid;
	}

    public function setGid($gid) {
        $this->_gid = $gid;
        return $this;
    }

    public function getActive() {
        return $this->_active;
    }

    public function setActive($active) {
        $this->_active = $active;
        return $this;
    }

    public function getAuthorId() {
        return $this->_authorId;
    }

    public function setAuthorId($authorId) {
        $this->_authorId = $authorId;
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

    public function getTitle() {
        return $this->_title;
    }

    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    public function getSummary() {
        return $this->_summary;
    }

    public function setSummary($summary) {
        $this->_summary = $summary;
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

    public function getSeoTags() {
        return $this->_seotags;
    }

    public function setSeoTags($seotags) {
        $this->_seotags = $seotags;
        return $this;
    }

    public function getSeoTitle() {
        return $this->_seoTitle;
    }

    public function setSeoTitle($seoTitle) {
        $this->_seoTitle = $seoTitle;
        return $this;
    }

    public function getSeoDescription() {
        return $this->_seoDescription;
    }

    public function setSeoDescription($seoDescription) {
        $this->_seoDescription = $seoDescription;
        return $this;
    }

    public function getLng() {
        return $this->_lng;
    }

    public function setLng($lng) {
        $this->_lng = $lng;
        return $this;
    }

    public function getPic() {
        return $this->_pic;
    }

    public function setPic($pic) {
        $this->_pic = $pic;
        return $this;
    }

    protected function _createUrl() {
        $url = strtolower($this->getTitle());
        $url = preg_replace('%>%', '/', $url);
        $url = preg_replace('%[^a-zA-Z0-9\\-/]%', '_', $url);
        $url = preg_replace('%-{2,}%', '_', $url);
        $url = preg_replace('%/{2,}%', '/', $url);
        $url = preg_replace('%(-*)/(-*)%', '/', $url);

        $url = trim($url, '/');
        $url = trim($url, '_');

        return strtolower($url);
    }

    public function makeObject($result)
    {
        $this ->setGid($result['gid'])
            ->setActive($result['active'])
            ->setLng($result['lng'])
            ->setUrl($result['url'])
            ->setTitle($result['title'])
            ->setSummary($result['summary'])
            ->setContent($result['content'])
            ->setTitle($result['title'])
            ->setSeoTags($result['seotags'])
            ->setSeoTitle($result['seotitle'])
            ->setSeoDescription($result['seodescription'])
            ->setAuthorId($result['author_id'])
            ->setDateCreated($result['dateCreated'])
            ->setDateUpdated($result['dateUpdated']);

        return $this;
    }

     public function save() {
        $db = Zend_Registry::get('db');
        $identity = Zend_Auth::getInstance()->getIdentity();
        $systemNamespace = new Zend_Session_Namespace('System');

        $data = array(
                'author_id' => $identity->getId(),
                'dateCreated' => $this->getDateCreated()
        );

        if ($this->_gid) {
            $data['dateUpdated'] = new Zend_Db_Expr('NOW()');
            $db->update('Gallery', $data, 'gid = ' . $this->_gid);
            $galleryid = $this->_gid;
        } else {
            $data['dateCreated'] = new Zend_Db_Expr('NOW()');
            $db->insert('Gallery', $data);
            $galleryid = $db->lastInsertId();
        }

        $tslData = array(
                'g_id' => $galleryid,
                'lng' => $this->getLng(),
                'title' => $this->getTitle(),
                'summary' => $this->getSummary(),
                'content' => $this->getContent(),
                'url' => $this->_createUrl(),
                'seotags' => $this->getSeoTags(),
                'seotitle' => $this->getSeoTitle(),
                'seodescription' => $this->getSeoDescription(),
                'active' => (int) $this->getActive(),
            );

        if ($this->getGid() === false) {

            $config = Zend_Registry::get('config');
            foreach ($config->system->language as $lng => $slng) {
                if ($lng != $systemNamespace->lng) {
					$tslData['active'] = 0;
				}
                $tslData['lng'] = $lng;
                $db->insert('GalleryTsl', $tslData);
            }
        } else {
            $db->update('GalleryTsl', $tslData,
                    'g_id = ' . $this->_gid .
                    ' AND lng = \'' . $this->getLng() . '\'');
        }

        return $this;
    }



    public function delete() {
        $gid = $this->getGid();
        $db = Zend_Registry::get('db');
        $db->delete('Gallery', 'gid = ' . $gid);
        $db->delete('GalleryTsl', 'g_id = ' . $gid);
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

		$db->update('GalleryTsl', $active, 'g_id = ' . $this->_gid);

		return true;
	}

}
