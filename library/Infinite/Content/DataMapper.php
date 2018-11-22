<?php

class Infinite_Content_DataMapper
{

    public function toObject(Array $import)
    {
        $fields = array(
            'id' => null,
            'parentID'           => null,
            'userID' => null,
            'categoryID' => null,
            'layout' => null,
            'contentType' => null,
            'link' => null,
            'navigation' => null,
            'ranking' => null,
            'image' => null,
            'video' => null,

            'title' => null,
            'subtitle' => null,
            'content' => null,
            'url' => null,
            'seoTitle' => null,
            'seoTags' => null,
            'seoDescription' => null,

            'children' => null,
            'galleries' => null
        );

        foreach ($import as $key => $value) {
            if (array_key_exists($key, $fields)) {
                $fields[$key] = $value;
            }
        }


        $page = new Infinite_Content();
        $page   ->setID($fields['id'])
                ->setParentID((int) $fields['parentID'])
                ->setUserID((int) $fields['userID'])
                ->setCategoryID((int) $fields['categoryID'])
                ->setLayout($fields['layout'])
                ->setContentType($fields['contentType'])
                ->setLink($fields['link'])
                ->setNavigation($fields['navigation'])
                ->setRanking($fields['ranking'])
                ->setImage($fields['image'])
                ->setVideo($fields['video'])

                ->setTitle($fields['title'])
                ->setSubtitle($fields['subtitle'])
                ->setContent($fields['content'])
                ->setURL($fields['url'])
                ->setSeoTitle($fields['seoTitle'])
                ->setSeoTags($fields['seoTags'])
                ->setSeoDescription($fields['seoDescription'])

                ->setChildren($fields['children']);

        //galleries
        $galleries = array();
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('pg' => 'PageGalleries'), array('*'))
            ->where('pg.pid = ?', $fields['id']);
        $results = $db->fetchAll($select);
        foreach($results as $result) {
            $galleries[] = $result['gid'];
        }
        $page->setGalleries($galleries);

        return $page;
    }

    public function getAll($lng='nl')
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('c' => 'Content'), array('*'))
            ->join(array('t' => 'ContentTsl'), 'c.id = t.contentID')
            ->where('t.language = ?', $lng);

        $results = $db->fetchAll($select);

        $pages = array();
        foreach($results as $result) {
            $page = $this->toObject($result);
            $page = $this->_collectPermissions($page);
            $pages[$page->getID()] = $page;
        }
        return $pages;
    }

    public function getTree($lng)
    {
        $cacheID = 'getTree_' . $lng;
        $cache = Zend_Registry::get('cache');
        if (true == ($result = $cache->load($cacheID))) {
            return $result;
        }

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('c' => 'Content'), array('*'))
            ->join(array('t' => 'ContentTsl'), 'c.id = t.contentID')
            ->where('c.parentID = 0')
            ->where('t.language = ?', $lng)
            ->order('c.ranking ASC');

        $results = $db->fetchAll($select);

        $pages = array();
        foreach($results as $result) {
            /* get children */
            $parentID = $result['id'];
            if ($parentID != 0) {
                $select = $db->select()
                    ->from(array('c' => 'Content'), array('*'))
                    ->join(array('t' => 'ContentTsl'), 'c.id = t.contentID')
                    ->where('c.parentID = ?', $parentID)
                    ->where('t.language = ?', $lng)
                    ->order('c.ranking ASC');
                $subpages = $db->fetchAll($select);
                if ($subpages) {
                    foreach ($subpages as $subpage) {
                        $subpageObject = $this->toObject($subpage);
                        $pageID = $subpage['id'];
                        $result['children'][$pageID] = $subpageObject;
                    }
                }
            }
            $page = $this->toObject($result);
            $pages[$page->getID()] = $page;
        }
        $cache->save($pages, $cacheID, array('Infinite_Content'));
        return $pages;
    }

    public function getMain() // Haalt alle pagina's op die juist onder de homepage vallen
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('c' => 'Content'), array('*'))
            ->join(array('t' => 'ContentTsl'), 'c.id = t.contentID')
            ->where('c.parentID = 0')
            ->where('c.id != 0')
            ->order('c.ranking ASC');

        $results = $db->fetchAll($select);

        $pages = array();
        foreach($results as $result) {
            $page = $this->toObject($result);
            $pages[$page->getID()] = $page;
        }
        return $pages;
    }

    public function getByID($pageID, $lng)
    {
        $cacheID = 'getContentByID_' . (int) $pageID . $lng;
        $cache = Zend_Registry::get('cache');
        if (true == ($result = $cache->load($cacheID))) {
            return $result;
        }

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('p' => 'Content'), array('*'))
            ->join(array('t' => 'ContentTsl'), 'p.id = t.contentID')
            ->where('p.id = ?', $pageID)
            ->where('t.language = ?', $lng);

        $stmt = $db->query($select);
        $result = $stmt->fetch();

        if ($result) {
            $page = $this->toObject($result);
            $page = $this->_collectPermissions($page);
            $cache->save($page, $cacheID, array('Infinite_Content'));
            return $page;
        }
        return false;

    }

    public function getByURL($url, $lng)
    {
        $cacheID = 'getContentByUrl_' . md5($url) . $lng;
        $cache = Zend_Registry::get('cache');
        if (true == ($result = $cache->load($cacheID))) {
            return $result;
        }

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('p' => 'Content'), array('*'))
            ->join(array('t' => 'ContentTsl'), 'p.id = t.contentID')
            ->where('t.language = ?', $lng);

        if (is_null($url)) {
            $select->where('t.url IS NULL');
        } else {
            $select->where('t.url = ?', $url);
        }

        $stmt = $db->query($select);
        $result = $stmt->fetch();

        if ($result) {
            $page = $this->toObject($result);
            $page = $this->_collectPermissions($page);
            $cache->save($page, $cacheID, array('Infinite_Content'));
            return $page;
        }
        return false;
    }

    public function getChildrenByParentId($parentID, $lng)
    {
        $db = Zend_Registry::get('db');
        if ($lng == null) {
            $select = $db->select()
                ->from(array('p' => 'Content'), array('*'))
                ->join(array('t' => 'ContentTsl'), 'p.id = t.contentID')
                ->where('p.parentID = ?', $parentID)
                ->where('p.id != 0')
                ->order('p.parentID ASC')
                ->order('p.ranking ASC');
        } else {
            $select = $db->select()
                ->from(array('p' => 'Content'), array('*'))
                ->join(array('t' => 'ContentTsl'), 'p.id = t.contentID')
                ->where('p.parentID = ?', $parentID)
                ->where('p.id != 0')
                ->where('t.language = ?', $lng)
                ->order('p.parentID ASC')
                ->order('p.ranking ASC');
        }

        $results = $db->fetchAll($select);

        $pages = array();
        foreach($results as $result) {
            $page = $this->toObject($result);
            $pages[$page->getID()] = $page;
        }
        return $pages;
    }

    public function getHighestRank($parentID)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('p' => 'Content'), array('*'))
            ->where('p.parentID = ?', $parentID)
            ->order('p.ranking DESC')
            ->limit(1);

        $stmt = $db->query($select);
        $result = $stmt->fetch();

        if ($result) {
            $page = $this->toObject($result);
            $page = $this->_collectPermissions($page);
            return $page;
        }
        return false;
    }

    protected function _collectPermissions($pageObject)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('p' => 'ContentAcl'), array('*'))
            ->join(array('g' => 'Group'), 'p.groupID = g.id');

        if ($pageObject instanceof Infinite_Content) {
            $select->where('contentID = ?', $pageObject->getId());
        } else if (is_array($pageObject) && count($pageObject)) {
            $select->where('contentID IN (?)', array_keys($pageObject));
        } else {
            return $pageObject;
        }

        $stmt = $db->query($select);
        $result = $stmt->fetchAll();

        foreach ($result as $row) {
            $group = new Infinite_Group();
            $group->setId($row['id'])
                ->setName($row['name']);

            if ($pageObject instanceof Infinite_Content) {
                $pageObject->addPermission($group);
            } else {
                $pageObject[$row['contentID']]->addPermission($group);
            }
        }

        return $pageObject;
    }

    public function getByString($string, $lng) // Zoekt pagina's volgens de zoekstring
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('c' => 'Content'), array('*'))
            ->join(array('t' => 'ContentTsl'), 'c.id = t.contentID')
            ->where('c.id != 0')
            ->where('t.language = ?', $lng)
            ->where("t.content LIKE '%". $string . "%' OR t.title LIKE '%" . $string . "%'")
            ->order('c.ranking ASC');

        $results = $db->fetchAll($select);

        $pages = array();
        foreach($results as $result) {
            $page = $this->toObject($result);
            $pages[$page->getID()] = $page;
        }
        return $pages;
    }

}