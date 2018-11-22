<?php

class Infinite_Content //extends Zend_Navigation
{

    protected $_id;
    protected $_parentID;
    protected $_userID;
    protected $_categoryID;
    protected $_layout;
    protected $_contentType;
    protected $_link;
    protected $_navigation = true;
    protected $_ranking;
    protected $_image;
    protected $_video;
    protected $_dateCreated;
    protected $_dateUpdated;

    protected $_language = 'nl';
    protected $_title;
    protected $_subtitle;
    protected $_content;
    protected $_url;
    protected $_seoTitle;
    protected $_seoTags;
    protected $_seoDescription;

    protected $_active = false;
    protected $_permissions = array();
    protected $_children = array();
    protected $_galleries = array();
    protected $_menus = array();

    public function setID($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
    public function getID()
    {
        if (!isset($this->_id)) {
            return false;
        }

        return $this->_id;
    }

    public function setParent(Infinite_Content $page)
    {
        $this->_parent = $page;
        return $this;
    }
    public function getParent()
    {
        if (!isset($this->_parent)) {
            return new Infinite_Content();
        }

        return $this->_parent;
    }
    public function setParentID($parentID)
    {
        $this->_parentID = $parentID;
        return $this;
    }
    public function getParentID()
    {
        return $this->_parentID;
    }

    public function setUserID($userID)
    {
        $this->_userID = $userID;
        return $this;
    }
    public function getUserID()
    {
        return $this->_userID;
    }

    public function setCategoryID($categoryID)
    {
        $this->_categoryID = $categoryID;
        return $this;
    }
    public function getCategoryID()
    {
        return $this->_categoryID;
    }

    public function setLayout($layout)
    {
        $this->_layout = $layout;
        return $this;
    }
    public function getLayout()
    {
        return $this->_layout;
    }

    public function setContentType($contentType)
    {
        $this->_contentType = $contentType;
        return $this;
    }
    public function getContentType()
    {
        return $this->_contentType;
    }

    public function setLink($link)
    {
        $this->_link = $link;
        return $this;
    }
    public function getLink()
    {
        return $this->_link;
    }

    public function setNavigation($show = true)
    {
        $this->_navigation = (bool) $show;
        return $this;
    }
    public function getNavigation()
    {
        return $this->_navigation;
    }
    public function isInNavigation()
    {
        return $this->_navigation;
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

    public function setImage($image)
    {
        $this->_image = $image;
        return $this;
    }
    public function getImage()
    {
        return $this->_image;
    }

    public function setVideo($video)
    {
        $this->_video = $video;
        return $this;
    }
    public function getVideo()
    {
        return $this->_video;
    }

    public function setDateCreated($date)
    {
        $this->_dateCreated = $date;
        return $this;
    }
    public function getDateCreated($format = null)
    {
        if ($format) {
            return strftime($format, strtotime($this->_dateCreated));
        }
        return $this->_dateCreated;
    }

    public function setDateUpdated($date)
    {
        $this->_dateUpdated = $date;
        return $this;
    }
    public function getDateUpdated($format = null)
    {
        if ($format) {
            return strftime($format, strtotime($this->_dateUpdated));
        }
        return $this->_dateUpdated;
    }

    public function setLanguage($language)
    {
        $this->_language = strtolower($language);
        return $this;
    }
    public function getLanguage()
    {
        return $this->_language;
    }

    public function setTitle($title)
    {
        $this->_title = trim($title);
        return $this;
    }
    public function getTitle()
    {
        return $this->_title;
    }

    public function setSubtitle($subtitle)
    {
        $this->_subtitle = trim($subtitle);
        return $this;
    }
    public function getSubtitle()
    {
        return $this->_subtitle;
    }

    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }
    public function getContent()
    {
        return $this->_content;
    }

    public function setURL($url)
    {
        $this->_url = $url;
        return $this;
    }
    public function getURL()
    {
        return $this->_url;
    }

    public function setSeoTitle($seoTitle)
    {
        $this->_seoTitle = $seoTitle;
        return $this;
    }
    public function getSeoTitle()
    {
        return $this->_seoTitle;
    }

    public function setSeoTags($seoTags)
    {
        $this->_seoTags = $seoTags;
        return $this;
    }
    public function getSeoTags()
    {
        return $this->_seoTags;
    }

    public function setSeoDescription($seoDescription)
    {
        $this->_seoDescription = $seoDescription;
        return $this;
    }
    public function getSeoDescription()
    {
        return $this->_seoDescription;
    }

    public function setChildren($children)
    {
        $this->_children = $children;
        return $this;
    }
    public function getChildren()
    {
        return $this->_children;
    }
    public function addChild(Infinite_Content $page)
    {
        $this->_children[$page->getId()] = $page;
        return $this;
    }

    public function setActive($active = true)
    {
        $this->_active = (bool) $active;
        return $this;
    }

    public function isActive()
    {
        return $this->_active;
    }

    public function addPermission(Infinite_Group $group)
    {
        $this->_permissions[$group->getId()] = $group;
        return $this;
    }
    public function setPermissions($permissions)
    {
        $this->_permissions = $permissions;
        return $this;
    }
    public function getPermissions()
    {
        return $this->_permissions;
    }
    public function clearPermissions()
    {
        $this->_permissions = array();
        return $this;
    }
    public function isAllowed($identity)
    {
        if (!$this->getPermissions()) {
            return true;
        }

        if (!$identity) {
            return !$this->getPermissions();
        }

        foreach ($identity->getGroups() as $group) {
            if (isset($this->_permissions[$group->getId()])) {
                return true;
            }
        }

        return false;
    }

    public function getGalleries() {
        return $this->_galleries;
    }
    public function setGalleries($galleries) {
        $this->_galleries = $galleries;
        return $this;
    }

    public function getMenus() {
        return $this->_menus;
    }
    public function setMenus($menus) {
        $this->_menus = $menus;
        return $this;
    }

    public function save()
    {
        $cache = Zend_Registry::get('cache');
        $cache->clean(Zend_Cache::CLEANING_MODE_ALL);

        $db = Zend_Registry::get('db');
        if ($this->getContentType() == 'module') {
            $this->setLayout(null)
                ->setContent(null);
        }

        $identity = Zend_Auth::getInstance()->getIdentity();
        $pageData = array(
            'parentID'		 => $this->getParentID(),
            'userID' 	     => $identity->getId(),
            'layout'	 	 => $this->getLayout(),
            'navigation'	 => (int) $this->isInNavigation(),
            'ranking'        => (int) $this->getRanking(),
            'image'          => $this->getImage(),
            'video'          => $this->getVideo(),
            'contentType'	 => $this->getContentType(),
            'link'	         => $this->getLink(),
            'dateCreated'    => new Zend_Db_Expr('NOW()'),
            'dateUpdated'    => new Zend_Db_Expr('NOW()')
        );

        $db->insert('Content', $pageData);
        $this->setID($db->lastInsertId());


        /* Set TSL content voor ELKE taal  */
        if ($this->getContentType() !== 'module' && $this->_id != 0) {
            if ($this->getParentID() != 0) {
                $mapper = new Infinite_Content_DataMapper();
                $parent = $mapper->getByID($this->getParentID(),$this->getLanguage());
                $parent_url = $parent->getURL();
                $this->setURL($parent_url . '/' . $this->_createUrl($this->getLanguage()));
            } else {
                $this->setURL($this->_createUrl($this->getLanguage()));
            }
        }
        $url = $this->getURL();
        $tslData = array(
            'contentID'	 	=> $this->getID(),
            'title'		 	=> stripslashes($this->getTitle()),
            'subtitle'	    => stripslashes($this->getSubtitle()),
            'content'   	=> stripslashes($this->getContent()),
            'url'		 	=> $url,
            'seotitle'	 	=> $this->getSeoTitle(),
            'seotags'	 	=> $this->getSeoTags(),
            'seodescription' => $this->getSeoDescription()
        );

        $config = Zend_Registry::get('config');
        foreach ($config->system->language as $lng => $slng) {
            $tslData['language'] = $lng;
            $db->insert('ContentTsl', $tslData);
        }

        /* Set permissions */
        foreach ($this->_permissions as $group) {
            $gData = array(
                'contentID' => $this->_id,
                'groupID' => $group->getId(),
            );
            $db->insert('ContentAcl', $gData);
        }

        /* Set galleries */
        $db->delete('PageGalleries', 'pid = ' .   $this->getID());
        if($this->_galleries) {
            foreach ($this->_galleries as $gallery_id) {
                $db->insert('PageGalleries', array('pid' => $this->getID(), 'gid' => $gallery_id));
            }
        }

        /* Set menus */
        $db->delete('PageMenus', 'pid = ' .   $this->getID());
        if($this->_menus) {
            foreach ($this->_menus as $menu_id) {
                $db->insert('PageMenus', array('pid' => $this->getID(), 'mid' => $menu_id));
            }
        }

        return true;
    }

    public function update()
    {
        $cache = Zend_Registry::get('cache');
        $cache->clean(Zend_Cache::CLEANING_MODE_ALL);

        $db = Zend_Registry::get('db');

        $identity = Zend_Auth::getInstance()->getIdentity();
        $pageData = array(
            'id'             => (int) $this->getID(),
            'parentID'		 => (int) $this->getParentID(),
            'userID' 	 => $identity->getId(),
            'layout'	 	 => $this->getLayout(),
            'navigation'	 => (int) $this->isInNavigation(),
            'contentType'    => $this->getContentType(),
            'link'           => $this->getLink(),
            'image'          => $this->getImage(),
            'video'          => $this->getVideo(),
            'dateUpdated'   => new Zend_Db_Expr('NOW()'),
        );

        /* Set TSL */
        if ($this->getContentType() !== 'module' && $this->_id != 0) {
            if ($this->getParentID() != 0) {
                $mapper = new Infinite_Content_DataMapper();
                $parent = $mapper->getByID($this->getParentID(),$this->getLanguage());
                $parent_url = $parent->getURL();
                $this->setURL($parent_url . '/' . $this->_createUrl($this->getLanguage()));
            } else {
                $this->setURL($this->_createUrl($this->getLanguage()));
            }
        }
        $url = $this->getURL();
        $tslData = array(
            'title'          => $this->getTitle(),
            'subtitle'       => $this->getSubtitle(),
            'content'        => $this->getContent(),
            'url'            => $url,
            'seotitle'       => $this->getSeoTitle(),
            'seotags'        => $this->getSeoTags(),
            'seodescription' => $this->getSeoDescription()
        );

        $contentID = $this->getID();
        $lng = $this->getLanguage();
        $where[] = "contentID = '$contentID'";
        $where[] = "language = '$lng'";

        $db->update('Content', $pageData, 'id = ' . $this->getID());
        $db->update('ContentTsl', $tslData, $where );

        /* Set permissions */
        $db->delete('ContentAcl', 'contentID = ' . $this->getId());
        foreach ($this->_permissions as $group) {
            $gData = array(
                'contentID' => $this->getID(),
                'groupID' => $group->getId(),
            );
            $db->insert('ContentAcl', $gData);
        }

        /* Set galleries */
        $db->delete('PageGalleries', 'pid = ' .   $this->getID());
        if($this->_galleries) {
            foreach ($this->_galleries as $gallery_id) {
                $db->insert('PageGalleries', array('pid' => $this->getID(), 'gid' => $gallery_id));
            }
        }

        /* Set menus */
        $db->delete('PageMenus', 'pid = ' .   $this->getID());
        if($this->_menus) {
            foreach ($this->_menus as $menu_id) {
                $db->insert('PageMenus', array('pid' => $this->getID(), 'mid' => $menu_id));
            }
        }

        /* Indien deze pagina subpagina's heeft, ook de URL van alle children aanpassen */
        $mapper = new Infinite_Content_DataMapper();
        $children = $mapper->getChildrenByParentId($this->getID(),$lng);
        foreach($children as $child) {
            $childID = $child->getID();
            $mapper = new Infinite_Content_DataMapper();
            $page = $mapper->getByID($childID, $this->getLanguage());
            $page->setURL($url . '/' . $page->_createUrl($lng));
            $page->updateURL($child->getID(),$lng);
        }

        return true;

    }

    public function updateURL()
    {
        $db = Zend_Registry::get('db');
        $tslData = array(
            'url' => $this->getURL()
        );

        //$contentID = $this->getID();
        //$lng = $this->getLanguage();
        $where[] = "contentID = '$contentID'";
        $where[] = "language = '$lng'";

        $db->update('ContentTsl', $tslData, $where );
    }


    public function rank()
    {
        $db = Zend_Registry::get('db');
        $data = array(
            'id' => (int) $this->getID(),
            'ranking' => $this->getRanking()
        );
        $db->update('Content', $data, 'id = ' . $this->getID());
    }

    public function delete()
    {
        $cache = Zend_Registry::get('cache');
        $cache->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array('Infinite_Content')
        );

        /* eventuele subpagina's verwijderen' */
        $db = Zend_Registry::get('db');
        $mapper = new Infinite_Content_DataMapper();
        $children = $mapper->getChildrenByParentId($this->getID(),null);

        foreach ($children as $child) {
            $db->delete('Content', 'id = ' . $child->getId());
            $db->delete('ContentTsl', 'contentID = ' . $child->getId());
            $db->delete('ContentAcl', 'contentID = ' . $child->getId());
        }

        /* verwijder pagina's uit DB' */
        $db->delete('Content', 'id = ' . $this->getId());
        $db->delete('ContentTsl', 'contentID = ' . $this->getId());
        $db->delete('ContentAcl', 'contentID = ' . $this->getId());
        $db->delete('PageGalleries', 'pid = ' . $this->getId());

    }

    public function createUrl()
    {
        return $this->_createUrl();
    }

    protected function _createUrl()
    {

        $url = str_replace('/', '-', $this->getTitle());
        $url = preg_replace('%>%', '/', $url);
        $url = preg_replace('%[^a-zA-Z0-9\\-/]%', '-', $url);
        $url = preg_replace('%-{2,}%', '-', $url);
        $url = preg_replace('%/{2,}%', '/', $url);
        $url = preg_replace('%(-*)/(-*)%', '/', $url);

        $url = trim($url, '/');
        $url = trim($url, '-');

        return strtolower($url);
    }

    public function createThumbName($name) {
        $names = explode('.',$name);
        $thumbname = str_replace('/', '-', $names[0]);
        $thumbname = preg_replace('%>%', '/', $thumbname);
        $thumbname = preg_replace('%[^a-zA-Z0-9\\-/]%', '-', $thumbname);
        $thumbname = preg_replace('%-{2,}%', '-', $thumbname);
        $thumbname = preg_replace('%/{2,}%', '/', $thumbname);
        $thumbname = preg_replace('%(-*)/(-*)%', '/', $thumbname);

        $thumbname = trim($thumbname, '/');
        $thumbname = trim($thumbname, '-');

        return strtolower($thumbname);
    }

}
