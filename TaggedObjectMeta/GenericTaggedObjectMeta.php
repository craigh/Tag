<?php
/**
 * Tag - a content-tagging module for the Zikula Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\TagModule\TaggedObjectMeta;

use ZLanguage;
use Zikula\Core\ModUrl;
use \Zikula\TagModule\AbstractTaggedObjectMeta;

class GenericTaggedObjectMeta extends AbstractTaggedObjectMeta
{
    function __construct($objectId, $areaId, $module, $urlString = null, ModUrl $urlObject = null)
    {
        parent::__construct($objectId, $areaId, $module, $urlString, $urlObject);
        $this->setObjectAuthor('');
        $this->setObjectDate('');
        $this->setObjectTitle('');
    }
    public function setObjectTitle($title)
    {
        $dom = ZLanguage::getModuleDomain('ZikulaTagModule');
        $item = __('item', $dom);
        $this->title = "{$this->getModule()} {$item} (id# {$this->getObjectId()})";
    }
    public function setObjectDate($date)
    {
        $this->date = '';
    }
    public function setObjectAuthor($author)
    {
        $this->author = '';
    }
}