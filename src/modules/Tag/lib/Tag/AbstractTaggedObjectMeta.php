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
    /**
     * Object's url string
     * @deprecated since Tag version 1.0.2
     * @var string 
     */
    private $urlString;
    /**
     * @var Zikula_ModUrl
     */
    private $urlObject;
    protected $title = '';
    protected $date = '';
    protected $author = '';

    function __construct($objectId, $areaId, $module, $urlString = null, Zikula_ModUrl $urlObject = null)
    {
        $this->setObjectId($objectId);
        $this->setAreaId($areaId);
        $this->setModule($module);
        $this->setObjectUrl($urlString); // deprecated
        $this->setUrlObject($urlObject);
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
        LogUtil::log('Tag_AbstractTaggedObjectMeta::setObjectUrl() is deprecated, please use Tag_AbstractTaggedObjectMeta::setObjectUrlObject()', E_USER_DEPRECATED);
        $this->urlString = $url;
    }

    public function getObjectUrl()
    {
        LogUtil::log('Tag_AbstractTaggedObjectMeta::getObjectUrl() is deprecated, please use Tag_AbstractTaggedObjectMeta::getObjectUrlObject()', E_USER_DEPRECATED);
        return $this->urlString;
    }

    public function setUrlObject($objectUrlObject)
    {
        $this->urlObject = $objectUrlObject;
    }
    
    public function getUrlObject()
    {
        return $this->urlObject;
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
            $url = $this->getUrlObject();
            // the fourth arg is forceLang and if left to default (true) then the url is malformed - core bug as of 1.3.0
            $url = isset($url) ? $url->getUrl(null, null, false, false) : $this->getObjectUrl();
            $link = "<a href='$url'>$title</a>";
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