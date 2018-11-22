<?php

class Infinite_Gallery_Picture
{
	protected $_pictureId;
	protected $_gid;
    protected $_ranking;
	protected $_image;
    protected $_title;
    protected $_subtitle;


	public function setPictureId($id)
	{
		$this->_pictureId = (int) $id;
		return $this;
	}
	public function getPictureId()
	{
		return $this->_pictureId;
	}

	public function setGid($id)
	{
		$this->_gid = (int) $id;
		return $this;
	}
	public function getGid()
	{
		return $this->_gid;
	}

    public function setRanking($ranking)
    {
        $this->_ranking = (int) $ranking;
        return $this;
    }
    public function getRanking()
    {
        return $this->_ranking;
    }
	
	public function setImage($image)
	{
		$this->_image = $image;
		return $this;
	}
	public function getImage()
	{
		return $this->_image;
	}

    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }
    public function getTitle()
    {
        return $this->_title;
    }

    public function setSubtitle($subtitle)
    {
        $this->_subtitle = $subtitle;
        return $this;
    }
    public function getSubtitle()
    {
        return $this->_subtitle;
    }

    public function makeObject($result)
    {
        $this->setPictureId($result['picture_id'])
            ->setGid($result['album_id'])
            ->setRanking($result['ranking'])
            ->setImage($result['image'])
            ->setTitle($result['title'])
            ->setSubtitle($result['subtitle']);

        return $this;
    }

	public function save()
	{
		$db = Zend_Registry::get('db');
		

		$data = array(
            'picture_id' => $this->getPictureId(),
			'album_id' => $this->getGid(),
            'ranking'        => (int) $this->getRanking(),
		    'image' => $this->getImage(),
            'title' => $this->getTitle(),
            'subtitle' => $this->getSubtitle()
		);


        if ($this->getPictureId()) {
			$db->update('GalleryPictures', $data, 'picture_id = ' . $this->getPictureId());
		} else {
			$db->insert('GalleryPictures', $data);
			$this->setPictureId($db->lastInsertId());
		}

		return true;
	}

    public function rank()
    {
        $db = Zend_Registry::get('db');
        $data = array(
            'picture_id' => (int) $this->getPictureId(),
            'ranking' => $this->getRanking()
        );

        $db->update('GalleryPictures', $data, 'picture_id = ' . $this->getPictureId());
    }

	public function delete()
	{
		$db = Zend_Registry::get('db');
        $db->delete('GalleryPictures', 'picture_id = ' . $this->getPictureId());
        return true;
	}

}
