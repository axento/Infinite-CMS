<?php

class Infinite_NewsTag_DataMapper
{

    public function toObject(Array $import)
    {
        $fields = array(
            'tid'    => null,
            'tag' => null
        );

        foreach ($import as $key => $value) {
            if (array_key_exists($key, $fields)) {
                $fields[$key] = $value;
            }
        }

		$tag = new Infinite_NewsTag();
		$tag ->setTid($fields['tid'])
			        ->setTag($fields['tag']);

        return $tag;
    }

	public function getById($id)
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from(array('t' => 'NewsTags'), array('*'))
			->where('t.tid = ' . (int) $id);

		$result = $db->fetchRow($select);
		$tag = $this->toObject($result);

		return $tag;
	}

	public function getAll()
	{
		$db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('t' => 'NewsTags'), array('*'))
            ->order('t.tag ASC');
        
        $results = $db->fetchAll($select);
        //var_dump($results);
        $tags = array();
        foreach($results as $result) {
            $tag = $this->toObject($result);
			$tags[$tag->getTid()] = $tag;
        }
        
        return $tags;
	}
    
}
