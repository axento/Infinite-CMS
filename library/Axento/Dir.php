<?php

class Axento_Dir
{
    /*
    * Verwijderd de opgegeven dir met alle achterliggende mappen en bestanden
    */
    public function deleteDir($dir) {  
        $iterator = new RecursiveDirectoryIterator($dir);  
        foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) {  
          if ($file->isDir()) {  
             rmdir($file->getPathname());  
          } else {  
             unlink($file->getPathname());  
          }  
        }  
        rmdir($dir);  
    }  
}