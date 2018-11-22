<?php

class Admin_IndexController extends Zend_Controller_Action
{
	public function init()
    {
        //$this->view->homeTab = 'active';
    }
    
    public function indexAction()
	{
        $this->_helper->redirector->gotoSimple('dashboard', 'index');
	}

    public function headAction()
    {

    }

    public function navigationAction()
    {

    }

    public function headerAction()
    {

    }

    public function dashboardAction()
    {
        $this->view->dashboardTab = 'active';
    }

    public function clearcacheAction()
    {
        
        // Verwijder alle cachebestanden
        $files = glob(APPLICATION_ROOT . '/tmp/cache/*'); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file))
                unlink($file); // delete file
        }
        
        // maak cachegeheugen leeg
        $cache = Zend_Registry::get('cache');
		$cache->clean(
			Zend_Cache::CLEANING_MODE_MATCHING_TAG,
			array('Infinite_Content')
		);
        
        $this->_helper->redirector->gotoSimple('dashboard', 'index');
    }

    public function sitemapAction()
    {
        $this->_helper->layout->disableLayout();

        $mapper = new Infinite_Content_DataMapper();
        $nav = $mapper->getAll('nl');

        $xml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= '<urlset
            xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $xml .= "\n";

        $url = Zend_Registry::get('config')->system->web->url;

        foreach($nav as $page) {
            if (!$page->getPermissions()) {
                $xml .= "<url>\n";
                $xml .= "<loc><![CDATA[" . $url ."/nl/". $page->getUrl() . "]]></loc>\n";
                $xml .= "<changefreq>weekly</changefreq>\n";
                $xml .= "</url>\n";
            }
        }

        // TODO: extra pagina's ook toevoegen aan sitemap
        /* Regios ook toevoegen aan sitemap */
        /*
        $mapper = new Infinite_Regio_DataMapper();
        $regios = $mapper->getAll();
        foreach($regios as $regio) {
            $xml .= "<url>\n";
            $xml .= "<loc><![CDATA[" . $url ."/nl/webdesign/". $regio->getUrl() . "]]></loc>\n";
            $xml .= "<changefreq>weekly</changefreq>\n";
            $xml .= "</url>\n";
        }
        */

        /* Referenties ook toevoegen aan sitemap */
        /*
        $mapper = new Infinite_Portfolio_DataMapper();
        $projects = $mapper->getAll();
        foreach($projects as $project) {
            $xml .= "<url>\n";
            $xml .= "<loc><![CDATA[" . $url ."/nl/project/". $project->getID() . "/" . $project->getLink() . "]]></loc>\n";
            $xml .= "<changefreq>weekly</changefreq>\n";
            $xml .= "</url>\n";
        }
        */

        $xml .= '</urlset>';

        file_put_contents(APPLICATION_ROOT . '/'.Zend_Registry::get('config')->public->folder.'/sitemap.xml', $xml);

        $this->_helper->redirector->gotoSimple('dashboard', 'index');
    }

    public function forbiddenAction()
    {

    }

}
