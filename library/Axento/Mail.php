<?php

class Axento_Mail
{
    public function sendNewPassword($receiver,$password,$name)
    {
        $host = Zend_Registry::get('config')->system->web->url;
        
        $template  = new Axento_Mail_Template();
        $message = $template->sanTemplate(APPLICATION_ROOT . "/library/Axento/Mail/new-password.tmpl");
        
        $message->SetTag("%HOST%");
        $message->addData($host, 1);
        $message->SetTag("%PASSWORD%");
        $message->addData($password, 1);
        $message->SetTag("%NAME%");
        $message->addData($name, 1);
        $html = $message->OutputData();
     
        $email = new Zend_Mail();     
        $email->setBodyHtml($html);
        $email->setFrom('noreply@axento.be','SmartCMS');
        $email->addTo($receiver,$name);          
        $email->setSubject('Uw paswoord werd opnieuw ingesteld!');
        $email->send();

        return $send = true;
    }
    
    public function sendForm($contact)
    {
        $host = Zend_Registry::get('config')->system->web->url;
        $domain = Zend_Registry::get('config')->system->web->domain;
        $receiver = Zend_Registry::get('config')->contact->to;

        $datetime = substr($contact->getDatetime(),8,2). '-' .
                    substr($contact->getDatetime(),5,2). '-' .
                    substr($contact->getDatetime(),0,4). ' ' .
                    substr($contact->getDatetime(), 11,8);

        $template  = new Axento_Mail_Template();
        $message = $template->sanTemplate(APPLICATION_ROOT . "/library/Axento/Mail/contact.tmpl");

        $message->SetTag("%HOST%");
        $message->addData($host, 1);
        $message->SetTag("%DOMAIN%");
        $message->addData($domain, 1);
        $message->SetTag("%DATETIME%");
        $message->addData($datetime, 1);
        $message->SetTag("%NAME%");
        $message->addData($contact->getName(), 1);
        $message->SetTag("%PHONE%");
        $message->addData($contact->getPhone(), 1);
        $message->SetTag("%EMAIL%");
        $message->addData($contact->getEmail(), 1);
        $message->SetTag("%MESSAGE%");
        $message->addData($contact->getMessage(), 1);
        $html = $message->OutputData();

        $email = new Zend_Mail();
        $email->setBodyHtml($html);
        $email->setFrom($contact->getEmail(),$contact->getName());
        $email->addTo($receiver);
        $email->setSubject('Bericht op website ' . $domain);
        $email->send();
        //Zend_Debug::dump($email);die;

        return true;
    }

    public function sendWish($wish)
    {
        $host = Zend_Registry::get('config')->system->web->url;
        $domain = Zend_Registry::get('config')->system->web->domain;
        $receiver = Zend_Registry::get('config')->contact->to;

        $datetime = substr($wish->getDateCreated(),8,2). '-' .
            substr($wish->getDateCreated(),5,2). '-' .
            substr($wish->getDateCreated(),0,4). ' ' .
            substr($wish->getDateCreated(), 11,8);

        $template  = new Axento_Mail_Template();
        $message = $template->sanTemplate(APPLICATION_ROOT . "/library/Axento/Mail/wish.tmpl");

        $message->SetTag("%HOST%");
        $message->addData($host, 1);
        $message->SetTag("%DOMAIN%");
        $message->addData($domain, 1);
        $message->SetTag("%DATETIME%");
        $message->addData($datetime, 1);
        $message->SetTag("%FROMNAME%");
        $message->addData($wish->getFromName(), 1);
        $message->SetTag("%FROMFNAME%");
        $message->addData($wish->getFromFname(), 1);
        $message->SetTag("%TONAME%");
        $message->addData($wish->getToName(), 1);
        $message->SetTag("%TOFNAME%");
        $message->addData($wish->getToFname(), 1);
        $message->SetTag("%WISH%");
        $message->addData($wish->getWish(), 1);
        $html = $message->OutputData();

        $email = new Zend_Mail();
        $email->setBodyHtml($html);
        $email->setFrom('no-reply@bolderberg.be');
        $email->addTo($receiver);
        $email->setSubject('Nieuwe felicitaties via website ' . $domain);
        $email->send();
        //Zend_Debug::dump($email);die;

        return true;
    }

}