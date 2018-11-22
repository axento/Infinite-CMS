<?php

function exportContacts($contacts,$CSVfile) {
    $handle = fopen(APPLICATION_ROOT . "/www/tmp/$CSVfile","w");
    $str = "ID;Language;Datetime;Email\r\n";
    
    foreach ($contacts as $item) {
        $str .= stripslashes(utf8_decode($item->getID())).";";
        $str .= stripslashes(utf8_decode($item->getLng())).";";
        $str .= stripslashes(utf8_decode($item->getDatetime())).";";
        $str .= stripslashes(utf8_decode($item->getEmail())).";";
        $str .= "\r\n";
    }
                 
    fwrite($handle,$str);
    fclose($handle);
}

function exportClients($contacts,$CSVfile) {
    $handle = fopen(APPLICATION_ROOT . "/www/tmp/$CSVfile","w");
    $str = "ID;Active;Language;Fname;Name;Street;Nr;Box;Zip;City;Country;Phone;Mobile;Email;DateCreated;DateUpdated\r\n";

    foreach ($contacts as $item) {
        $str .= stripslashes(utf8_decode($item->getID())).";";
        $str .= stripslashes(utf8_decode($item->getActive())).";";
        $str .= stripslashes(utf8_decode($item->getLng())).";";
        $str .= stripslashes(utf8_decode($item->getFname())).";";
        $str .= stripslashes(utf8_decode($item->getName())).";";
        $str .= stripslashes(utf8_decode($item->getStreet())).";";
        $str .= stripslashes(utf8_decode($item->getNr())).";";
        $str .= stripslashes(utf8_decode($item->getBox())).";";
        $str .= stripslashes(utf8_decode($item->getZip())).";";
        $str .= stripslashes(utf8_decode($item->getCity())).";";
        $str .= stripslashes(utf8_decode($item->getCountry())).";";
        $str .= stripslashes(utf8_decode($item->getPhone())).";";
        $str .= stripslashes(utf8_decode($item->getMobile())).";";
        $str .= stripslashes(utf8_decode($item->getEmail())).";";
        $str .= stripslashes(utf8_decode($item->getDateCreated())).";";
        $str .= stripslashes(utf8_decode($item->getDateUpdated())).";";
        $str .= "\r\n";
    }

    fwrite($handle,$str);
    fclose($handle);
}

function exportProducts($products,$CSVfile) {
    $handle = fopen(APPLICATION_ROOT . "/www/tmp/$CSVfile","w");
    $str = "ID;Designer;Active;Promo;Price;PromoPrice;Title NL;Title EN;Title FR;Description NL;Description EN;Description FR;Amount;Views;DateCreated;DateUpdated\r\n";

    foreach ($products as $item) {
        $str .= stripslashes(utf8_decode($item->getID())).";";
        $str .= stripslashes(utf8_decode($item->getName().' '.$item->getFname())).";";
        $str .= stripslashes(utf8_decode($item->getActive())).";";
        $str .= stripslashes(utf8_decode($item->getPromo())).";";
        $str .= stripslashes(utf8_decode($item->getPrice())).";";
        $str .= stripslashes(utf8_decode($item->getPromoPrice())).";";
        $str .= stripslashes(utf8_decode($item->getTitleNL())).";";
        $str .= stripslashes(utf8_decode($item->getTitleEN())).";";
        $str .= stripslashes(utf8_decode($item->getTitleFR())).";";
        $str .= stripslashes(utf8_decode($item->getDescriptionNL())).";";
        $str .= stripslashes(utf8_decode($item->getDescriptionEN())).";";
        $str .= stripslashes(utf8_decode($item->getDescriptionFR())).";";
        $str .= stripslashes(utf8_decode($item->getAmount())).";";
        $str .= stripslashes(utf8_decode($item->getViews())).";";
        $str .= stripslashes(utf8_decode($item->getDateCreated())).";";
        $str .= stripslashes(utf8_decode($item->getDateUpdated())).";";
        $str .= "\r\n";
    }

    fwrite($handle,$str);
    fclose($handle);
}