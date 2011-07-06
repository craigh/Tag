<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
abstract class Tag_AbstractTaggedObjectMeta implements Tag_TaggedObjectMetaInterface
{

    private $objectId;
    private $areaId;
    private $module;
    private $objectUrl;
    protected $title = '';
    protected $date = '';
    protected $author = '';

    function __construct($objectId, $areaId, $module, $objectUrl)
    {
        $this->setObjectId($objectId);
        $this->setAreaId($areaId);
        $this->setModule($module);
        $this->setObjectUrl($objectUrl);
    }

    public function setObjectId($id)
    {
        $this->objectId = $id;
    }

    public function getObjectId()
    {
        return $this->objectId;
    }

    public function setAreaId($id)
    {
        $this->areaId = $id;
    }

    public function getAreaId()
    {
        return $this->areaId;
    }

    public function setModule($name)
    {
        $this->module = $name;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setObjectUrl($url)
    {
        $this->objectUrl = $url;
    }

    public function getObjectUrl()
    {
        return $this->objectUrl;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getPresentationLink()
    {
        $author = $this->getAuthor();
        $date = $this->getDate();
        $title = $this->getTitle();
        $link = null;
        if (!empty($title)) {
            $dom = ZLanguage::getModuleDomain('Tag');
            $by = __('by', $dom);
            $on = __('on', $dom);
            $link = "<a href='{$this->getObjectUrl()}'>$title</a>";
            $sub = '';
            if (!empty($author)) {
                $sub .= " $by $author";
            }
            if (!empty($date)) {
                $sub .= " $on $date";
            }
            $link .= ( !empty($sub)) ? " (" . trim($sub) . ")" : '';
        }
        return $link;
    }

}