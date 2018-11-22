<?php

class Infinite_ContentAcl
{

    protected $contentID;
	protected $groupID;

    protected $name;

    public function setContentID($contentID)
    {
        $this->contentID = (int) $contentID;
        return $this;
    }
    public function getContentID()
    {
        return $this->contentID;
    }

	public function setGroupID($groupID)
	{
		$this->groupID = (int) $groupID;
		return $this;
	}
	public function getGroupID()
	{
		return $this->groupID;
	}

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function getName()
    {
        return $this->name;
    }

    public function makeObject($result) {
        $this->setContentID($result['contentID'])
            ->setgroupID($result['groupID'])
            ->setName($result['name']);

        return $this;
    }

}
