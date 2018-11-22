<?php

class Admin_EventController extends Zend_Controller_Action
{
    protected $_item;

    public function init()
    {
        Infinite_Acl::checkAcl('event');
        $this->view ->placeholder('modules')
            ->append('active');
        //$system = new Zend_Session_Namespace('System');
        $this->view->eventTab = 'active';
        $config = Zend_Registry::get('config');
        $lngs = $config->system->language;
        $this->view->lngs = $lngs;
    }

    public function indexAction()
    {
        $mapper = new Infinite_Event_DataMapper();
        $events  = $mapper->getAll();
        $this->view->events = $events;
    }

    public function addAction()
    {

        $event = new Infinite_Event();
        $event->setDateStart(date("Y-m-d H:i:s", time()));
        $event->setDateStop(date("Y-m-d H:i:s", time()));


        if ($this->getRequest()->isPost()) {

            $event = new Infinite_Event();

            $dateStart = substr($this->_getParam('dateStart'),6,4).'-'.
                substr($this->_getParam('dateStart'),3,2).'-'.
                substr($this->_getParam('dateStart'),0,2).' '.
                substr($this->_getParam('timeStart'),0,2).':'.
                substr($this->_getParam('timeStart'),3,2).':00';

            $dateStop = substr($this->_getParam('dateStop'),6,4).'-'.
                substr($this->_getParam('dateStop'),3,2).'-'.
                substr($this->_getParam('dateStop'),0,2).' '.
                substr($this->_getParam('timeStop'),0,2).':'.
                substr($this->_getParam('timeStop'),3,2).':00';

            $event->setTitle($this->_getParam('title'))
                ->setContent($this->_getParam('content'))
                ->setDateStart($dateStart)
                ->setDateStop($dateStop);

            /* validate post */
            $validator = new Infinite_Event_InsertValidator();
            if ($validator->validate($event)) {
                $event->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('Het event werd succesvol aangemaakt!');

                $this->_helper->redirector->gotoSimple('index', 'event');
            }
        }

        $this->view->dayStart = substr($event->getDateStart(),8,2);
        $this->view->monthStart = substr($event->getDateStart(),5,2);
        $this->view->yearStart = substr($event->getDateStart(),0,4);
        $this->view->hourStart = substr($event->getDateStart(),11,2);
        $this->view->minutesStart = substr($event->getDateStart(),14,2);

        $this->view->dayStop = substr($event->getDateStop(),8,2);
        $this->view->monthStop = substr($event->getDateStop(),5,2);
        $this->view->yearStop = substr($event->getDateStop(),0,4);
        $this->view->hourStop = substr($event->getDateStop(),11,2);
        $this->view->minutesStop = substr($event->getDateStop(),14,2);

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Event');
        $this->view->event = $event;
    }

    public function editAction()
    {

        $id = $this->_getParam('id');

        if ($this->getRequest()->isGet()) {
            $mapper = new Infinite_Event_DataMapper();
            $event = $mapper->getById($id);
        }

        if ($this->getRequest()->isPost()) {

            $mapper = new Infinite_Event_DataMapper();
            $event = $mapper->getById($id);

            $dateStart = substr($this->_getParam('dateStart'),6,4).'-'.
                substr($this->_getParam('dateStart'),3,2).'-'.
                substr($this->_getParam('dateStart'),0,2).' '.
                substr($this->_getParam('timeStart'),0,2).':'.
                substr($this->_getParam('timeStart'),3,2).':00';

            $dateStop = substr($this->_getParam('dateStop'),6,4).'-'.
                substr($this->_getParam('dateStop'),3,2).'-'.
                substr($this->_getParam('dateStop'),0,2).' '.
                substr($this->_getParam('timeStop'),0,2).':'.
                substr($this->_getParam('timeStop'),3,2).':00';

            $event
                ->setTitle($this->_getParam('title'))
                ->setContent($this->_getParam('content'))
                ->setDateStart($dateStart)
                ->setDateStop($dateStop);

            $validator = new Infinite_Event_UpdateValidator();
            if ($validator->validate($event)) {
                $event->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('Het event werd succesvol aangepast!');

                $this->_helper->redirector->gotoSimple('index', 'event');
            }
        }

        $this->view->dayStart = substr($event->getDateStart(),8,2);
        $this->view->monthStart = substr($event->getDateStart(),5,2);
        $this->view->yearStart = substr($event->getDateStart(),0,4);
        $this->view->hourStart = substr($event->getDateStart(),11,2);
        $this->view->minutesStart = substr($event->getDateStart(),14,2);

        $this->view->dayStop = substr($event->getDateStop(),8,2);
        $this->view->monthStop = substr($event->getDateStop(),5,2);
        $this->view->yearStop = substr($event->getDateStop(),0,4);
        $this->view->hourStop = substr($event->getDateStop(),11,2);
        $this->view->minutesStop = substr($event->getDateStop(),14,2);

        $this->view->event  = $event;

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Event');
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id');
        $mapper = new Infinite_Event_DataMapper();
        $event = $mapper->getById($id);
        $event->delete();

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('Het event werd succesvol verwijderd!');

        $this->_helper->redirector->gotoSimple('index', 'event');
    }

}