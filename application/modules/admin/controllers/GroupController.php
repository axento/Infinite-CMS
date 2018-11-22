<?php

class Admin_GroupController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->groupTab = 'active';
        Infinite_Acl::checkAcl('group');
    }

	public function indexAction()
	{
		$mapper = new Infinite_Group_DataMapper();
        $groups = $mapper->getAll();
		$this->view->groups = $groups;
	}

    public function addAction()
    {
        $mapper = new Infinite_Acl_DataMapper();
        $acls = $mapper->getAll();
        $this->view->acls = $acls;
        $this->view->aclIDS = array();

        $group = new Infinite_Group();

        if ($this->getRequest()->isPost()) {

            $group->setName($this->_getParam('name'));

            $validator = new Infinite_Group_InsertValidator();
            if ($validator->validate($group)) {
                $group->save($group);

                foreach($this->_getParam('acl') as $aclID => $item) {

                    $groupAcl = new Infinite_GroupAcl();
                    $groupAcl->setAclID($aclID)
                        ->setGroupID($group->getId());
                    $groupAcl->save();

                }

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('Gebruikersgroep werd succesvol opgslagen!');

                $this->_helper->redirector->gotoSimple('index', 'group');
            }
        }

        $this->view->group = $group;
        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Group');

    }

	public function editAction()
	{

        $mapper = new Infinite_Acl_DataMapper();
        $acls = $mapper->getAll();
        $this->view->acls = $acls;

        $mapper = new Infinite_Group_DataMapper();
		$group = $mapper->getById($this->_getParam('id'));

        $mapper = new Infinite_GroupAcl_DataMapper();
        $groupAcls = $mapper->getByGroupID($group->getId());

        $aclIDS = array();
        foreach ($groupAcls as $groupAcl) {
            $aclID = $groupAcl->getAclID();
            $aclIDS[] = $aclID;
        }
        $this->view->aclIDS = $aclIDS;

        if ($this->getRequest()->isPost()) {

            $group->setName($this->_getParam('name'));

            $validator = new Infinite_Group_InsertValidator();
            if ($validator->validate($group)) {
                $group->save($group);

                /* Verwijder eerst de eventuele oude privileges van deze groep */
                $groupAcl = new Infinite_GroupAcl();
                $groupAcl->setGroupID($group->getId());
                $groupAcl->clean();

                foreach($this->_getParam('acl') as $aclID => $item) {

                    $groupAcl = new Infinite_GroupAcl();
                    $groupAcl->setAclID($aclID)
                        ->setGroupID($group->getId());
                    $groupAcl->save();

                }

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('Gebruikersgroep werd succesvol aangepast!');

                $this->_helper->redirector->gotoSimple('index', 'group');
            }
        }

		$this->view->group = $group;
		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Group');
	}

	public function deleteAction()
	{
		$group = new Infinite_Group();
		$group->setId($this->_getParam('id'));

		$group->delete($group);

		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('Gebruikersgroep werd succesvol verwijderd!');

		$this->_helper->redirector->gotoSimple('index', 'group');
	}
}
