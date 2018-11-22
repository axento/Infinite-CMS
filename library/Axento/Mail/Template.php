<?php

class Axento_Mail_Template
{
  var $filename;
  var $template;
  var $data;
  var $tmpl;
  var $tag;
  
  //Constructor : Loads a Template
  function sanTemplate($filename, $title='')
  {
    return $this->LoadTemplate($filename, $title);
  }

  function LoadTemplate($filename, $title='')
  {
  	if(file_exists($filename))
    {
      
      unset($this->template);
      $pieces = explode('/', $filename);
      $pieces = explode('.', $pieces[count($pieces)-1]);

      $name = '';
      for($i=0;$i < (count($pieces)-1);$i++)
      {
        $name .= $pieces[$i];
      }

      $this->filename   = $name;

    	$infile = fopen ("$filename", "r");
    	while (!feof ($infile))
    	{
        $this->template .= fgets($infile, 4096);
    	}
    	
      $this->title = $title;
      $this->tmpl = $this->template;
return $this;
    	fclose ($infile);
      return true;
    }
    
    return false;
  }
  
  function ResetData()
  {
    $this->data = '';
  }

  function ResetTemplate()
  {
    $this->LoadTemplate($this->filename);
    $this->data = '';
  }

  function AddData($content, $prep=0)
  {
    $this->data .= $content;
    if($prep)
      $this->PrepareData();
  }

  function SetTag($tag)
  {
    $this->tag = $tag;
  }

  function prepareData()
  {
	  $this->tmpl = str_replace($this->tag, $this->data, $this->tmpl);
	  $this->ResetData();
  }

  function OutputData()
  {
	  return stripslashes($this->tmpl);
  }

  function writeData($filename)
  {
    $outfile = fopen ($filename, "w");
  	fwrite($outfile, stripslashes($this->tmpl), strlen($this->tmpl));
  	fclose ($outfile);
	  return true;
  }
}
