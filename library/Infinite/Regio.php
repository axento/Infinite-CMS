<?php
class Infinite_Regio {
    protected $_id;
    protected $_regio;
    protected $_content;
    protected $_image;
    protected $_url;
    protected $_seoTags;
    protected $_seoTitle;
    protected $_seoDescription;
    protected $_views;

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

    public function getRegio() {
        return $this->_regio;
    }
    public function setRegio($regio) {
        $this->_regio = $regio;
        return $this;
    }

    public function getContent() {
        return $this->_content;
    }
    public function setContent($content) {
        $this->_content = $content;
        return $this;
    }

    public function getImage() {
        return $this->_image;
    }
    public function setImage($image) {
        $this->_image = $image;
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
        return $this->_seoTags;
    }
    public function setSeoTags($seotags) {
        $this->_seoTags = $seotags;
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
	
	public function getViews() {
        return $this->_views;
    }
    public function setViews($views) {
        $this->_views = $views;
        return $this;
    }

    protected function _createUrl()
    {
        $title = $this->getRegio();
        $url = strtolower($title);
		$url = str_replace('/', '-', $url);
        $url = preg_replace('%>%', '/', $url);
        $url = preg_replace('%[^a-zA-Z0-9\\-/]%', '-', $url);
        $url = preg_replace('%-{2,}%', '-', $url);
        $url = preg_replace('%/{2,}%', '/', $url);
        $url = preg_replace('%(-*)/(-*)%', '/', $url);

        $url = trim($url, '/');
        $url = trim($url, '-');

        return strtolower($url);
    }

    public function createImageName($name)
    {
        $names = explode('.',$name);
        $imagename = str_replace('/', '-', $names[0]);
        $imagename = preg_replace('%>%', '/', $imagename);
        $imagename = preg_replace('%[^a-zA-Z0-9\\-/]%', '-', $imagename);
        $imagename = preg_replace('%-{2,}%', '-', $imagename);
        $imagename = preg_replace('%/{2,}%', '/', $imagename);
        $imagename = preg_replace('%(-*)/(-*)%', '/', $imagename);

        $imagename = trim($imagename, '/');
        $imagename = trim($imagename, '-');

        return strtolower($imagename);
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setViews($result['views'])
            ->setUrl($result['url'])
            ->setRegio($result['regio'])
            ->setImage($result['image'])
            ->setSeoTags($result['seotags'])
            ->setSeoTitle($result['seotitle'])
            ->setSeoDescription($result['seodescription']);

        return $this;
    }

    public function save()
    {

        $db = Zend_Registry::get('db');
        $data = array(
                'regio' => $this->getRegio(),
                'content' => $this->getContent(),
                'image' => $this->getImage(),
                'views' => $this->getViews(),
                'url' => $this->_createUrl(),
                'seotags' => $this->getSeoTags(),
                'seotitle' => $this->getSeoTitle(),
                'seodescription' => $this->getSeoDescription()
        );

        if ($this->_id) {
            $db->update('Regio', $data, 'id = ' . $this->_id);
        } else {
            $db->insert('Regio', $data);
        }

        return $this;
    }

    public function delete()
    {
        $db = Zend_Registry::get('db');
        $db->delete('Regio', 'id = ' . $this->getID());
        return true;
    }
	
	
	public function updateRead()
    {
		$db = Zend_Registry::get('db');
		$read = $this->getViews() +1;
		$data = array(
                'views' => $read,
        );
        $db->update('Regio', $data, 'id = ' . $this->getID());
	}

}