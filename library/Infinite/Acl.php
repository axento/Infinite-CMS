<?php

class Infinite_Acl
{
    protected $_id;
    protected $_name;
    protected $_resource;

    public function setID($id)
    {
        $this->_id = $id;
        return $this;
    }
    public function getID()
    {
        return $this->_id;
    }

    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    public function getName()
    {
        return $this->_name;
    }

    public function setResource($resource)
    {
        $this->_resource = $resource;
        return $this;
    }
    public function getResource()
    {
        return $this->_resource;
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setResource($result['resource'])
            ->setName($result['name']);

        return $this;
    }

    public static function checkAcl($resource) {

        $user = Zend_Auth::getInstance()->getIdentity();
        $mapper = new Infinite_Account_DataMapper();
        $account = $mapper->getByEmail($user->getEmail());

        /* Nagaan welke acls er werden ingesteld voor deze gebruiker */
        $mapper = new Infinite_GroupAcl_DataMapper();
        $acls = $mapper->getByGroupID($account->getGroupID());
        foreach ($acls as $acl) {
            $aclID = $acl->getAclID();
            $mapper = new Infinite_Acl_DataMapper();
            $privilege = $mapper->getByID($aclID);
            if ($privilege->getResource() == $resource) {
                return true;
            }
        }

        $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $flash->addMessage('U heeft geen rechten om deze actie uit te voeren');

        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
        $redirector->setGotoSimple('forbidden', 'index', null, array('redirect' => true));

    }
}
