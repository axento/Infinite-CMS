<?php

class Infinite_News_DataMapper
{

    public function toObject(Array $import)
    {
        $fields = array(
            'nid'    => null,
            'tid' => null,
            'active' => null,
            'archive' => null,
            'layout' => null,
            'views' => null,
            'lng' => null,
            'url' => null,
            'title' => null,
            'summary' => null,
            'content' => null,
            'image' => null,
            'video' => null,
            'seotags' => null,
            'seotitle' => null,
            'seodescription' => null,
            'authorId' => null,
            'datePublication' => null,
            'dateCreated' => null,
            'dateUpdated' => null,

            'galleries' => null,
            'tag' => null
        );

        foreach ($import as $key => $value) {
            if (array_key_exists($key, $fields)) {
                $fields[$key] = $value;
            }
        }

		$news = new Infinite_News();
        $news ->setNid($fields['nid'])
            ->setTid($fields['tid'])
            ->setActive($fields['active'])
            ->setArchive($fields['archive'])
            ->setLayout($fields['layout'])
            ->setViews($fields['views'])
            ->setLng($fields['lng'])
            ->setUrl($fields['url'])
            ->setTitle($fields['title'])
            ->setSummary($fields['summary'])
            ->setContent($fields['content'])
            ->setImage($fields['image'])
            ->setVideo($fields['video'])
            ->setSeoTags($fields['seotags'])
            ->setSeoTitle($fields['seotitle'])
            ->setSeoDescription($fields['seodescription'])
            ->setAuthorId($fields['author_id'])
            ->setDatePublication($fields['datePublication'])
            ->setDateCreated($fields['dateCreated'])
            ->setDateUpdated($fields['dateUpdated'])
            ->setTag($fields['tag']);

        //galleries
        $galleries = array();
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('r' => 'NewsGalleries'), array('*'))
            ->where('r.nid = ?', $fields['nid']);
        $results = $db->fetchAll($select);
        foreach($results as $result) {
            $galleries[] = $result['gid'];
        }

        $news->setGalleries($galleries);

        return $news;

    }

	public function getById($id, $active = null)
	{
        $systemNamespace = new Zend_Session_Namespace('System');
        $lng = $systemNamespace->lng;
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('n' => 'News'), array('*'))
            ->joinLeft(array('t' => 'NewsTsl', array('lng', 'title', 'summary', 'content', 'url', 'seotags', 'seotitle', 'seodescription', 'active')), 'n.nid = t.nid')
            ->joinLeft(array('ta' => 'NewsTags'), 'ta.tid = t.tid')
            ->where('n.nid = ?', $id)
            ->where('t.lng LIKE "' . $lng . '"');

        if ($active != null) {
            $select->where('t.active = ?', $active);
        }

        $result = $db->fetchRow($select);
        if ($result) {
            $news = $this->toObject($result);
            return $news;
        } else {
            return false;
        }
	}

	public function getAllNews($lng = 'nl')
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('n' => 'News'), array('*'))
            ->joinLeft(array('t' => 'NewsTsl', array('lng', 'title', 'summary', 'content', 'url', 'seotags', 'seotitle', 'seodescription', 'active')), 'n.nid = t.nid')
            ->joinLeft(array('ta' => 'NewsTags'), 'ta.tid = t.tid')
            ->where('t.lng LIKE "' . $lng . '"')
            ->order('n.datePublication DESC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $newss = array();
        foreach($results as $result) {
            $news = $this->toObject($result);
			$newss[$news->getNid()] = $news;
        }

        return $newss;
	}

    public function getActive($lng = 'nl',$limit, $offset = 0)
    {
        $now = date("Y-m-d H:i:s", time());
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('n' => 'News'), array('*'))
            ->joinLeft(array('t' => 'NewsTsl', array('lng', 'title', 'summary', 'content', 'url', 'seotags', 'seotitle', 'seodescription', 'active')), 'n.nid = t.nid')
            ->joinLeft(array('ta' => 'NewsTags'), 'ta.tid = t.tid')
            ->where('t.lng LIKE "' . $lng . '"')
            ->where('t.active = 1')
            ->where('n.datePublication < "'.$now.'"')
            ->order('n.datePublication DESC')
            ->limit($limit, $offset);

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $newss = array();
        foreach($results as $result) {
            $news = $this->toObject($result);
            $newss[$news->getNid()] = $news;
        }

        return $newss;
    }

    public function getPrev($news, $lng)
    {
        $date = $news->getDateCreated();

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('n' => 'News'), array('*'))
            ->joinLeft(array('t' => 'NewsTsl', array('lng', 'title', 'summary', 'content', 'url', 'seotags', 'seotitle', 'seodescription', 'active')), 'n.nid = t.nid')
            ->where('n.dateCreated < ?', $date)
            ->where('t.lng LIKE "' . $lng . '"')
            ->where('n.nid != ?', $news->getNid())
            ->where('t.active = 1')
            ->order('n.dateCreated DESC')
            ->limit(1);

        $result = $db->fetchRow($select);
        if ($result) {
            $news = $this->toObject($result);
            return $news;
        } else {
            return false;
        }
    }

    public function getNext($news, $lng)
    {
        $date = $news->getDateCreated();

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('n' => 'News'), array('*'))
            ->joinLeft(array('t' => 'NewsTsl', array('lng', 'title', 'summary', 'content', 'url', 'seotags', 'seotitle', 'seodescription', 'active')), 'n.nid = t.nid')
            ->where('n.dateCreated > ?', $date)
            ->where('t.lng LIKE "' . $lng . '"')
            ->where('n.nid != ?', $news->getNid())
            ->where('t.active = 1')
            ->order('n.dateCreated')
            ->limit(1);

        $result = $db->fetchRow($select);
        if ($result) {
            $news = $this->toObject($result);
            return $news;
        } else {
            return false;
        }

    }

    public function getLatestNews($limit, $lng = 'nl', $offset = 0, $filter = false, $tag =false)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('n' => 'News'), array('*'))
            ->joinLeft(array('t' => 'NewsTsl', array('lng', 'title', 'summary', 'content', 'url', 'seotags', 'seotitle', 'seodescription', 'active')), 'n.nid = t.nid')
            ->joinLeft(array('ta' => 'NewsTags'), 'ta.tid = t.tid')
            ->where('t.lng LIKE "' . $lng . '"')
            ->order('n.dateCreated DESC')
            ->where('t.active = 1')
            ->limit($limit, $offset);

        if($filter)
            $select->where('t.tid = ?',$filter);

        if($tag)
            $select->where('seotags LIKE "%'.$tag. '%"');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $newss = array();
        foreach($results as $result) {
            $news = $this->toObject($result);
            $newss[$news->getNid()] = $news;
        }

        return $newss;
    }
/*
    public function getNextID()
    {
        $db = Zend_Registry::get('db');
        $stmt = $db->query("SELECT Auto_increment FROM information_schema.tables WHERE TABLE_SCHEMA = '2014_dietiste-smeets' AND TABLE_NAME='News'");
        $result = $stmt->fetch();
        $nextID = (int) $result['Auto_increment'];
        return $nextID;
    }
        */
}
