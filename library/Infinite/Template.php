<?php

class Infinite_Template {
    protected $_id;
    protected $_active;
    protected $_templateID;
    protected $_name;
    protected $_description;
    protected $_image;

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

    public function getActive() {
        return $this->_active;
    }
    public function setActive($active) {
        $this->_active = $active;
        return $this;
    }

    public function getTemplateID() {
        return $this->_templateID;
    }
    public function setTemplateID($templateID) {
        $this->_templateID = $templateID;
        return $this;
    }

    public function getName() {
        return $this->_name;
    }
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    public function getDescription() {
        return $this->_description;
    }
    public function setDescription($description) {
        $this->_description = $description;
        return $this;
    }

    public function getImage() {
        return $this->_image;
    }
    public function setImage($image) {
        $this->_image = $image;
        return $this;
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setActive($result['active'])
            ->setTemplateID($result['templateID'])
            ->setName($result['name'])
            ->setDescription($result['description'])
            ->setImage($result['image']);

        return $this;
    }

}
