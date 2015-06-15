<?php

/**
 * Tag - a content-tagging module for the Zikula Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Zikula\Core\ModUrl;

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

    /**
     * Constructor
     * 
     * @param integer $objectId
     * @param integer $areaId
     * @param string $module
     * @param string $urlString **deprecated**
     * @param ModUrl $urlObject
     */
    function __construct($objectId, $areaId, $module, $urlString = null, ModUrl $urlObject = null)
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

    /**
     * Set the object's url string
     * @deprecated since Tag version 1.0.2
     * @param type $url 
     */
    public function setObjectUrl($url)
    {
        LogUtil::log('Tag_AbstractTaggedObjectMeta::setObjectUrl() is deprecated, please use Tag_AbstractTaggedObjectMeta::setObjectUrlObject()', E_USER_DEPRECATED);
        $this->urlString = $url;
    }

    /**
     * Get the object's url string
     * @deprecated since Tag version 1.0.2
     * @return type 
     */
    public function getObjectUrl()
    {
        LogUtil::log('Tag_AbstractTaggedObjectMeta::getObjectUrl() is deprecated, please use Tag_AbstractTaggedObjectMeta::getObjectUrlObject()', E_USER_DEPRECATED);
        return $this->urlString;
    }

    /**
     * Set the object's Url Object
     * @param ModUrl $objectUrlObject
     */
    public function setUrlObject(ModUrl $objectUrlObject)
    {
        $this->urlObject = $objectUrlObject;
    }

    /**
     * Get the object's Url Object
     * @return Zikula_ModUrl
     */
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
            $url = isset($url) ? $url->getUrl() : $this->getObjectUrl();
            $link = "<a href='$url'>$title</a>";
            $sub = '';
            if (!empty($author)) {
                $sub .= " $by $author";
            }
            if (!empty($date)) {
                $sub .= " $on $date";
            }
            $link .= (!empty($sub)) ? " (" . trim($sub) . ")" : '';
        }
        return $link;
    }

}