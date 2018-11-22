<?php

class Infinite_Controller_Plugin_Bootstrap extends Zend_Controller_Plugin_Abstract
{

    protected $_config;
    protected $_env;
    protected $_front;

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
    	$this->initSession();
    	$this->initLocales();
    }

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->initDb();
        $this->initFrontController();
        $this->initRoutes();
        $this->initView();
        $this->initHelpers();
        $this->initCache();
    }

    public function __construct($env)
    {
        $this->_env = $env;
        $this->initConfig();
        $this->initPhpConfig();

        $this->_front = Zend_Controller_Front::getInstance();
    }

    public function initConfig()
    {
		$this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $this->_env);
		Zend_Registry::set('config', $this->_config);
    }

    public function initPhpConfig()
    {
		date_default_timezone_set($this->_config->locale->timezone);
		setlocale(LC_ALL, $this->_config->locale->code);
    }

    public function initSession()
    {
    	$request = $this->getRequest();
      	Zend_Session::start();

		$system = new Zend_Session_Namespace('System');
		if ($request && $request->getParam('lng')) {
			$system->lng = $request->getParam('lng');
		}
    }

    public function initDb()
    {
		$dbCfg = $this->_config->db;
		$dbApp = Zend_Db::factory($dbCfg->adapter, $dbCfg->params->toArray());

		Zend_Db_Table_Abstract::setDefaultAdapter($dbApp);
		Zend_Registry::set('db', $dbApp);
    }

    public function initFrontController()
    {
    	$this->_front->addModuleDirectory(APPLICATION_PATH . '/modules')
    	    ->registerPlugin(new Infinite_Controller_Plugin_Auth())
			->registerPlugin(new Infinite_Controller_Plugin_Messenger())
			->registerPlugin(new Infinite_Controller_Plugin_ErrorSelector());
    }

    public function initLocales()
    {
        $config   = Zend_Registry::get('config');
        $request  = $this->getRequest();
        $language = $request->getParam('lng');

        $locales = $config->locale->list->toArray();
        if (!isset($locales[$language])) {
            $language = $config->locale->default;
        }

        $translator = new Zend_Translate('array', APPLICATION_ROOT . '/application/languages', $language, array(
                'scan' => Zend_Translate::LOCALE_DIRECTORY
            )
        );

        Zend_Validate_Abstract::setDefaultTranslator($translator);
	
        setlocale(LC_ALL, $locales[$language]);
    }
    
    public function initRoutes()
    {
        $config = Zend_Registry::get('config');
        $this->_front->setBaseUrl($config->system->web->baseurl);
            
		$nl = array(
            'content'  => 'inhoud',
            'contact'     => 'contacteer-ons',
            'subscribe' => 'inschrijven',
            'search' => 'zoeken',
            'merchant' => 'handelaars',
            'club' => 'verenigingen',
            'wish' => 'felicitaties',
            'event' => 'kalender'
		);

		$fr = array(
			'content'  => 'contenu',
		    'contact'   => 'contact',
			'subscribe'     => 'subscribe'
		);

        $en = array(
            'content'  => 'contenu',
            'contact'   => 'contact',
            'subscribe'     => 'subscribe'
        );

		$translator = new Zend_Translate('array', $nl, 'nl');
		$translator->addTranslation($fr, 'fr');
        $translator->addTranslation($en, 'en');

		$translator->setLocale('nl');
		Zend_Controller_Router_Route::setDefaultTranslator($translator);

		$router = $this->_front->getRouter();
		$localeRoute = new Zend_Controller_Router_Route(
		    ':lng',
		    array(
				'controller' => 'index',
				'action'     => 'content',
				'module'     => 'default',
				'lng'        => 'nl',
			),
			array('lng' => '(nl|fr|en)')
		);

		$router->addRoute('locale', $localeRoute);
		
		/** Content **/
		$contentRoute = new Zend_Controller_Router_Route_Regex(
		   '(.+)',
		   array(
		       'controller' => 'index',
		       'action'     => 'content',
		       'url'        => null,
		   ),
		   array(1 => 'url'),
		   '%s'
		);
		$router->addRoute('content', $localeRoute->chain($contentRoute, '/'));

        /** Contact form **/
        $contactRoute = new Zend_Controller_Router_Route(
            'contact',
            array(
                'controller' => 'contact',
                'action'     => 'form',
            ),
            '%s'
        );
        $contactChainedRoute = $localeRoute->chain($contactRoute, '/');
        $router->addRoute('contact', $contactChainedRoute);

        /** Wensen en felicitaties **/
        $wishRoute = new Zend_Controller_Router_Route(
            'wensen',
            array(
                'controller' => 'wish',
                'action'     => 'index',
            ),
            '%s'
        );
        $wishChainedRoute = $localeRoute->chain($wishRoute, '/');
        $router->addRoute('wish', $wishChainedRoute);

        $wishAddRoute = new Zend_Controller_Router_Route(
            'wensen/toevoegen',
            array(
                'controller' => 'wish',
                'action'     => 'form',
            ),
            '%s'
        );
        $wishAddChainedRoute = $localeRoute->chain($wishAddRoute, '/');
        $router->addRoute('wish-add', $wishAddChainedRoute);

        /* Subscribe */
        $subscribeRoute = new Zend_Controller_Router_Route(
            '@subscribe/:action',
            array(
                'controller' => 'subscribe',
                'action'     => 'newsletter',
            )
        );
        $subscribeChainedRoute = $localeRoute->chain($subscribeRoute, '/');
        $router->addRoute('subscribe', $subscribeChainedRoute);

        /* Search */
        $searchRoute = new Zend_Controller_Router_Route(
            '@search',
            array(
                'controller' => 'search',
                'action'     => 'index',
            )
        );
        $searchChainedRoute = $localeRoute->chain($searchRoute, '/');
        $router->addRoute('search', $searchChainedRoute);

        /** Login **/
        $loginRoute = new Zend_Controller_Router_Route(
            '/account/:action/:url',
            array(
                'controller' => 'account',
                'action'     => 'login',
                'url'        => '',
            )
        );

        $loginChainedRoute = $localeRoute->chain($loginRoute, '/');
        $router->addRoute('login', $loginChainedRoute);

        /** Fastlink **/
        $fastlinkRoute = new Zend_Controller_Router_Route(
            'fastlink/:id',
            array(
                'controller' => 'index',
                'action'     => 'fastlink',
                'id'         => ''
            )
        );

        $fastlinkChainedRoute = $localeRoute->chain($fastlinkRoute, '/');
        $router->addRoute('fastlink', $fastlinkChainedRoute);

        /** Handelaars **/
        $merchantRoute = new Zend_Controller_Router_Route(
            'handelaars',
            array(
                'controller' => 'merchant',
                'action'     => 'index',
            ),
            '%s'
        );

        $merchantChainedRoute = $localeRoute->chain($merchantRoute, '/');
        $router->addRoute('merchant-index', $merchantChainedRoute);

        $merchantDetailRoute = new Zend_Controller_Router_Route(
            '@handelaar/:id/:url',
            array(
                'controller' => 'merchant',
                'action' 	 => 'show',
                'id'	     => '',
                'url'		 => ''
            )
        );

        $merchantDetailChainedRoute = $localeRoute->chain($merchantDetailRoute, '/');
        $router->addRoute('merchant-show', $merchantDetailChainedRoute);

        /** Verenigingen **/
        $clubRoute = new Zend_Controller_Router_Route(
            'verenigingen',
            array(
                'controller' => 'club',
                'action'     => 'index',
            ),
            '%s'
        );

        $clubChainedRoute = $localeRoute->chain($clubRoute, '/');
        $router->addRoute('club-index', $clubChainedRoute);

        $clubDetailRoute = new Zend_Controller_Router_Route(
            '@vereniging/:id/:url',
            array(
                'controller' => 'club',
                'action' 	 => 'show',
                'id'	     => '',
                'url'		 => ''
            )
        );

        $clubDetailChainedRoute = $localeRoute->chain($clubDetailRoute, '/');
        $router->addRoute('club-show', $clubDetailChainedRoute);

        /** News **/
        $newsRoute = new Zend_Controller_Router_Route(
            '@news/*',
            array(
                'controller' => 'news',
                'action'     => 'index',
            ),
            '%s'
        );

        $newsChainedRoute = $localeRoute->chain($newsRoute, '/');
        $router->addRoute('news-index', $newsChainedRoute);

        $newsDetailRoute = new Zend_Controller_Router_Route(
            '@post/:id/:url',
            array(
                'controller' => 'news',
                'action' 	 => 'show',
                'id'	     => '',
                'url'		 => ''
            )
        );

        $newsDetailChainedRoute = $localeRoute->chain($newsDetailRoute, '/');
        $router->addRoute('news-detail', $newsDetailChainedRoute);

        $newsFilterRoute = new Zend_Controller_Router_Route(
            '@news/filter/:id',
            array(
                'controller' => 'news',
                'action' 	 => 'index',
                'id' => ''
            )
        );

        $newsFilterChainedRoute = $localeRoute->chain($newsFilterRoute, '/');
        $router->addRoute('news-filter', $newsFilterChainedRoute);

        /** Kalender **/
        $eventRoute = new Zend_Controller_Router_Route(
            'kalender',
            array(
                'controller' => 'event',
                'action'     => 'index',
            ),
            '%s'
        );

        $eventChainedRoute = $localeRoute->chain($eventRoute, '/');
        $router->addRoute('event-index', $eventChainedRoute);

        $eventDetailRoute = new Zend_Controller_Router_Route(
            '@kalender/:id/:url',
            array(
                'controller' => 'event',
                'action' 	 => 'show',
                'id'	     => '',
                'url'		 => ''
            )
        );

        $eventDetailChainedRoute = $localeRoute->chain($eventDetailRoute, '/');
        $router->addRoute('event-show', $eventDetailChainedRoute);

        /** Regio **/
        $regioRoute = new Zend_Controller_Router_Route(
            '@regios',
            array(
                'controller' => 'regio',
                'action'     => 'index',
            ),
            '%s'
        );

        $regioChainedRoute = $localeRoute->chain($regioRoute, '/');
        $router->addRoute('webdesign-index', $regioChainedRoute);

        $regioShowRoute = new Zend_Controller_Router_Route(
            '@webdesign/:url',
            array(
                'controller' => 'regio',
                'action' 	 => 'show',
                'url'		 => ''
            )
        );

        $regioShowChainedRoute = $localeRoute->chain($regioShowRoute, '/');
        $router->addRoute('webdesign', $regioShowChainedRoute);

		/** Admin **/
		$router->addRoute(
			'admin',
			new Zend_Controller_Router_Route('admin/:controller/:action/*', array(
				'controller' => 'index',
				'action'     => 'index',
				'module'     => 'admin'
			)
		));
		
    }

    public function initHelpers()
    {
		Zend_Controller_Action_HelperBroker::addPrefix('Infinite_Controller_Action_Helper');
    }

    public function initview()
    {

        $request =  new Zend_Controller_Request_Http();
        $uri = $request->getRequestUri();
        if (substr($uri,1,5) == 'admin') {
            $module = 'admin';
        } else {
            $module = 'default';
        }

        $options = array(
            'layout' => 'default',
            'layoutPath' => APPLICATION_PATH . '/modules/' . $module . '/layouts'
        );
        Zend_Layout::startMvc($options);
    }

    public function initCache()
    {
		$frontendOptions = array(
         	'lifetime' => 3600, // cache lifetime of 1 hour
         	'automatic_serialization' => true,
      	);

		$backendOptions = array(
		    'cache_dir' => APPLICATION_ROOT . '/tmp/cache'
		);

      	$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		Zend_Registry::set('cache', $cache);
    }

}
