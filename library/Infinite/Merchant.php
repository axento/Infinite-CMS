<?php
class Infinite_Merchant {

    protected $_id;
    protected $_typeID;
    protected $_shortcut;
    protected $_company;
    protected $_contact;
    protected $_street;
    protected $_number;
    protected $_box;
    protected $_zip;
    protected $_city;
    protected $_phone;
    protected $_email;
    protected $_website;
    protected $_content;
    protected $_image;
    protected $_url;
    protected $_seoTags;
    protected $_seoTitle;
    protected $_seoDescription;
    protected $_views;

    protected $_type;

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

    public function getTypeID() {
        return $this->_typeID;
    }
    public function setTypeID($typeID) {
        $this->_typeID = $typeID;
        return $this;
    }

    public function getShortcut() {
        return $this->_shortcut;
    }
    public function setShortcut($shortcut) {
        $this->_shortcut = $shortcut;
        return $this;
    }

    public function getCompany() {
        return $this->_company;
    }
    public function setCompany($company) {
        $this->_company = $company;
        return $this;
    }

    public function getContact() {
        return $this->_contact;
    }
    public function setContact($contact) {
        $this->_contact = $contact;
        return $this;
    }

    public function getStreet() {
        return $this->_street;
    }
    public function setStreet($street) {
        $this->_street = $street;
        return $this;
    }

    public function getNumber() {
        return $this->_number;
    }
    public function setNumber($number) {
        $this->_number = $number;
        return $this;
    }

    public function getBox() {
        return $this->_box;
    }
    public function setBox($box) {
        $this->_box = $box;
        return $this;
    }

    public function getZip() {
        return $this->_zip;
    }
    public function setZip($zip) {
        $this->_zip = $zip;
        return $this;
    }

    public function getCity() {
        return $this->_city;
    }
    public function setCity($city) {
        $this->_city = $city;
        return $this;
    }

    public function getPhone() {
        return $this->_phone;
    }
    public function setPhone($phone) {
        $this->_phone = $phone;
        return $this;
    }

    public function getEmail() {
        return $this->_email;
    }
    public function setEmail($email) {
        $this->_email = $email;
        return $this;
    }

    public function getWebsite() {
        return $this->_website;
    }
    public function setWebsite($website) {
        $this->_website = $website;
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

    public function getType() {
        return $this->_type;
    }
    public function setType($type) {
        $this->_type = $type;
        return $this;
    }

    protected function _createUrl()
    {
        $title = $this->getCompany();
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
            ->setTypeID($result['typeID'])
            ->setShortcut($result['shortcut'])
            ->setViews($result['views'])
            ->setUrl($result['url'])
            ->setCompany($result['company'])
            ->setContact($result['contact'])
            ->setStreet($result['street'])
            ->setNumber($result['number'])
            ->setBox($result['box'])
            ->setZip($result['zip'])
            ->setCity($result['city'])
            ->setPhone($result['phone'])
            ->setEmail($result['email'])
            ->setWebsite($result['website'])
            ->setContent($result['content'])
            ->setImage($result['image'])
            ->setSeoTags($result['seotags'])
            ->setSeoTitle($result['seotitle'])
            ->setSeoDescription($result['seodescription'])

            ->setType($result['type']);

        return $this;
    }

    public function save()
    {

        $db = Zend_Registry::get('db');
        $data = array(
                'typeID' => $this->getTypeID(),
                'shortcut' => $this->getShortcut(),
                'company' => $this->getCompany(),
                'contact' => $this->getContact(),
                'street' => $this->getStreet(),
                'number' => $this->getNumber(),
                'box' => $this->getBox(),
                'zip' => $this->getZip(),
                'city' => $this->getCity(),
                'phone' => $this->getPhone(),
                'email' => $this->getEmail(),
                'website' => $this->getWebsite(),
                'content' => $this->getContent(),
                'image' => $this->getImage(),
                'views' => $this->getViews(),
                'url' => $this->_createUrl(),
                'seotags' => $this->getSeoTags(),
                'seotitle' => $this->getSeoTitle(),
                'seodescription' => $this->getSeoDescription()
        );

        if ($this->_id) {
            $db->update('Merchant', $data, 'id = ' . $this->_id);
        } else {
            $db->insert('Merchant', $data);
        }

        return $this;
    }

    public function delete()
    {
        $db = Zend_Registry::get('db');
        $db->delete('Merchant', 'id = ' . $this->getID());
        return true;
    }
	
	
	public function updateRead()
    {
		$db = Zend_Registry::get('db');
		$read = $this->getViews() +1;
		$data = array(
                'views' => $read,
        );
        $db->update('Merchant', $data, 'id = ' . $this->getID());
	}

}