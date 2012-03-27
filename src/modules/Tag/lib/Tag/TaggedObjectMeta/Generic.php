<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
class Tag_TaggedObjectMeta_Generic extends Tag_AbstractTaggedObjectMeta
{

    function __construct($objectId, $areaId, $module, $urlString = null, Zikula_ModUrl $urlObject = null)
    {
        parent::__construct($objectId, $areaId, $module, $urlString, $urlObject);

        $this->setObjectAuthor("");
        $this->setObjectDate("");
        $this->setObjectTitle("");
    }

    public function setObjectTitle($title)
    {
        $dom = ZLanguage::getModuleDomain('Tag');
        $item = __('item', $dom);
        $this->title = "{$this->getModule()} $item (id# {$this->getObjectId()})";
    }

    public function setObjectDate($date)
    {
        $this->date = "";
    }

    public function setObjectAuthor($author)
    {
        $this->author = "";
    }

}