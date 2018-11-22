<?php

class Infinite_Gallery_Picture_DataMapper
{

    public function getAllByAlbum($album = null)
	{
    	$db = Zend_Registry::get('db');
        $select	= $db->select()
                     ->from(array('r' => 'GalleryPictures'), array('*'))
                     ->where('r.album_id = ?', $album)
                     ->order(array('ranking ASC','album_id DESC'));

        $stm		= $db->query($select);
        $pictures	= $stm->fetchAll();

        return $pictures;
	}

    public function getSingleByAlbum($album = null)
	{
    	$db = Zend_Registry::get('db');
        $select	= $db->select()
                     ->from(array('r' => 'GalleryPictures'), array('*'))
                     ->where('r.album_id = ?', $album)
                     ->order(array('r.picture_id ASC','r.album_id DESC'))
                     ->limit(1);

        $stm		= $db->query($select);
        $results	= $stm->fetchAll();

        $pictures = array();
        foreach($results as $result) {
            $picture = new Infinite_Gallery_Picture();
            $picture->makeObject($result);
            $pictures[$picture->getGid()] = $picture;
        }

        return $pictures;
	}
	
	public function getByID($id)
	{
		$db		= Zend_Registry::get('db');
		$select	= $db->select()
			->from(array('r' => 'GalleryPictures'), array('*'))
			->where('r.picture_id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $picture = new Infinite_Gallery_Picture();
            $picture->makeObject($result);
            return $picture;
        } else {
            return false;
        }

	}

    public function countByAlbum($album = null)
    {
        $db = Zend_Registry::get('db');
        $select	= $db->select()
                     ->from('GalleryPictures', (array('count(*) as total')))
                     ->where('album_id = ?', $album)
                     ->order(array('picture_id ASC','album_id DESC'));

        $stm		= $db->query($select);
        $rows =  $stm->fetchAll();
        return($rows[0]['total']);
    }

    public function getHighestRank($albumID)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('gp' => 'GalleryPictures'), array('*'))
            ->where('gp.album_id = ?', $albumID)
            ->order('gp.ranking DESC')
            ->limit(1);

        $stmt = $db->query($select);
        $result = $stmt->fetch();

        if ($result) {
            $picture = new Infinite_Gallery_Picture();
            $picture->makeObject($result);
            return $picture;
        }
        return false;
    }

}