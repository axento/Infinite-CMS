<?php
class Infinite_News {
    protected $_nid;
    protected $_authorId;
    protected $_layout;
    protected $_image;
    protected $_video;
    protected $_archive;
    protected $_views;
    protected $_datePublication;
    protected $_dateHidden;
    protected $_dateCreated;
    protected $_dateUpdated;

    protected $_tid;
    protected $_lng;
    protected $_title;
    protected $_summary;
    protected $_content;
    protected $_url;
    protected $_seoTags;
    protected $_seoTitle;
    protected $_seoDescription;
    protected $_active;

	protected $_galleries = array();
    protected $_tag;

    public function getNid() {
    if(!isset($this->_nid)){
        return false;
    }
    return $this->_nid;
    }
    public function setNid($nid) {
        $this->_nid = $nid;
        return $this;
    }

    public function getTid() {
        if(!isset($this->_tid)){
            return false;
        }
        return $this->_tid;
    }
    public function setTid($tid) {
        $this->_tid = $tid;
        return $this;
    }

    public function getLng() {
        return $this->_lng;
    }
    public function setLng($lng) {
        $this->_lng = $lng;
        return $this;
    }

    public function getActive() {
        return $this->_active;
    }
    public function setActive($active) {
        $this->_active = $active;
        return $this;
    }
	
	public function getViews() {
        return $this->_views;
    }
    public function setViews($views) {
        $this->_views = (int) $views;
        return $this;
    }

    public function getAuthorId() {
        return $this->_authorId;
    }
    public function setAuthorId($authorId) {
        $this->_authorId = $authorId;
        return $this;
    }

    public function getDatePublication($format = null) {
        if ($format) {
            return strftime($format, strtotime($this->_dateCreated));
        }
        return $this->_datePublication;
    }
    public function setDatePublication($datePublication) {
        $this->_datePublication = $datePublication;
        return $this;
    }

    public function getDateHidden() {
        return $this->_dateHidden;
    }
    public function setDateHidden($dateHidden) {
        $this->_dateHidden = $dateHidden;
        return $this;
    }

   public function getDateCreated($format = null)
	{
		if ($format) {
			return strftime($format, strtotime($this->_dateCreated));
		}
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

    public function getImage() {
        return $this->_image;
    }
    public function setImage($image) {
        $this->_image = $image;
        return $this;
    }

    public function getLayout() {
        return $this->_layout;
    }
    public function setLayout($layout) {
        $this->_layout = $layout;
        return $this;
    }

    public function getVideo() {
        return $this->_video;
    }
    public function setVideo($video) {
        $this->_video = $video;
        return $this;
    }

    public function getArchive() {
        return $this->_archive;
    }
    public function setArchive($archive) {
        $this->_archive = (int) $archive;
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
	
	public function getGalleries() {
        return $this->_galleries;
    }
    public function setGalleries($galleries) {
        $this->_galleries = $galleries;
        return $this;
    }

	public function getNewsGalleries() {
        $systemNamespace = new Zend_Session_Namespace('System');
        $db = Zend_Registry::get('db');
		$select = $db->select()
			->from(array('a' => 'NewsGalleries'), array('*'))
			->join(array('g' => 'Gallery'), 'g.gid = a.gid' ,array())
			->join(array('gtsl' => 'GalleryTsl'), 'g.gid = gtsl.g_id', array('title'))
			->where('a.nid = ?', $this->getNid())
			->where('gtsl.lng = ?', $systemNamespace->lng);

		$stmt = $db->query($select);
		$results = $stmt->fetchAll();

		return (array) $results;
	}

    public function getTag() {
        return $this->_tag;
    }
    public function setTag($tag) {
        $this->_tag = $tag;
        return $this;
    }

    protected function _createUrl() {
        $title = $this->getTitle();
        $url = strtolower($title);
		$url = str_replace('/', '_', $url);
        $url = preg_replace('%>%', '/', $url);
        $url = preg_replace('%[^a-zA-Z0-9\\-/]%', '_', $url);
        $url = preg_replace('%-{2,}%', '_', $url);
        $url = preg_replace('%/{2,}%', '/', $url);
        $url = preg_replace('%(-*)/(-*)%', '/', $url);

        $url = trim($url, '/');
        $url = trim($url, '_');

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

    public function save() {
        $systemNamespace = new Zend_Session_Namespace('System');
        $db = Zend_Registry::get('db');
        $identity = Zend_Auth::getInstance()->getIdentity();

        $data = array(
               'author_id' => $identity->getId(),
                'image' => $this->getImage(),
                'layout' => $this->getLayout(),
                'video' => $this->getVideo(),
                'archive' => $this->getArchive(),
                'views' => $this->getViews(),
                'datePublication' => $this->getDatePublication(),
                'dateHidden' => $this->getDateHidden()
        );

        if ($this->_nid) {
            $data['dateUpdated'] = new Zend_Db_Expr('NOW()');
            $db->update('News', $data, 'nid = ' . $this->_nid);
            $nieuwsid = $this->_nid;
        } else {
            $data['dateCreated'] = new Zend_Db_Expr('NOW()');
            $db->insert('News', $data);
            $nieuwsid = $db->lastInsertId();
        }

        $tslData = array(
                'nid' => $nieuwsid,
                'tid' => $this->getTid(),
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

		
        if ($this->getNid() === false) {
            
            $config = Zend_Registry::get('config');
            foreach ($config->system->language as $lng => $slng) {
                if ($lng != $systemNamespace->lng) {
					$tslData['active'] = 0;
				}
                $tslData['lng'] = $lng;
                $db->insert('NewsTsl', $tslData);
            }
        } else {
            $db->update('NewsTsl', $tslData,
                    'nid = ' . $this->_nid .
                    ' AND lng = \'' . $this->getLng() . '\'');
        }
  
      
		$db->delete('NewsGalleries', 'nid = ' .   $nieuwsid);
        if($this->_galleries) {
            foreach ($this->_galleries as $gallery_id) {
               $db->insert('NewsGalleries', array('nid' => $nieuwsid, 'gid' => $gallery_id));
            }
        }

        return $this;
    }

    public function delete() {
        $db = Zend_Registry::get('db');
        $db->delete('News', 'nid = ' . $this->getNid());
        $db->delete('NewsTsl', 'nid = ' . $this->getNid());
		$db->delete('NewsGalleries', 'nid = ' . $this->getNid());
        return true;
    }
	
	
	public function updateRead() {
		$db = Zend_Registry::get('db');
		$read = $this->getViews() +1;
		$data = array(
                'views' => $read,
        );
        $db->update('News', $data, 'nid = ' . $this->getNid());
	}

    public function activate()
	{
		$db = Zend_Registry::get('db');
           if($this->_active) {
                $active = array('active'    => 0);
           }else {
                $active = array('active'    => 1);
           }

		$db->update('NewsTsl', $active, 'nid = ' . $this->_nid);
		return true;
	}

}